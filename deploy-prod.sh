#!/bin/bash
## This script is executed on the production server via SSH by the GitHub Actions workflow.
## It performs the zero-downtime blue/green deployment.

# Environment variables are passed from the GitHub Action.
set -euo pipefail

# --- FIX: Silence "VIRTUAL_HOST_SET" port warnings ---
export VIRTUAL_HOST_SET=${VIRTUAL_HOST_SET:-""}

DEPLOY_TAG="production"
FULL_IMAGE_NAME="${IMAGE_NAME}:${DEPLOY_TAG}"
WEB_SERVICE_BASE="actuallyfind-web"
DB_SERVICE="actuallyfind-db"
TYPESENSE_SERVICE="actuallyfind-typesense"
QUEUE_SERVICE="actuallyfind-queue"

# Use the APP_URL variable to determine the VIRTUAL_HOST value
# Strip 'https://' or 'http://' from APP_URL for VIRTUAL_HOST comparison.
VIRTUAL_HOST_DOMAIN=$(echo "${APP_URL}" | sed -E 's/^(https?:\/\/)?//')

echo "--- Starting Blue/Green Deployment on Remote Server ---"
echo "✅ Current working directory is: $(pwd)"

# -------------------------------------------------------------
# 1. PULL THE LATEST IMAGE
# -------------------------------------------------------------
# 1. Pull the latest Docker image
echo "📥 Pulling latest image: ${FULL_IMAGE_NAME}"
docker pull ${FULL_IMAGE_NAME}
export IMAGE_TAG=${DEPLOY_TAG}
echo "🏷️ Exported IMAGE_TAG=${IMAGE_TAG}"

# -------------------------------------------------------------
# 2. DETERMINE TARGET_SLOT (Robust check for VIRTUAL_HOST)
# -------------------------------------------------------------
echo "🎯 Determining LIVE_SLOT and TARGET_SLOT for deployment..."
LIVE_SLOT=""

# Helper function to check VIRTUAL_HOST value
get_host_status() {
    local service_name=$1
    #
    # Use || true to prevent 'docker inspect' from failing the script
    # due to 'No such container'. The error is redirected to /dev/null anyway.
    #
    HOST_STATUS=$(docker inspect ${service_name} \
        --format '{{range .Config.Env}}{{if eq (index (split . "=") 0) "VIRTUAL_HOST"}}{{(index (split . "=") 1)}}{{end}}{{end}}' 2>/dev/null || true)

    # Check if the inspect command failed because the container doesn't exist.
    # If the HOST_STATUS is empty *and* the container existed, it means VIRTUAL_HOST was empty.
    # If the HOST_STATUS is empty and the container didn't exist, it means the script is bootstrapping.

    if [ $? -eq 0 ]; then
        echo "$HOST_STATUS"
    else
        # If the container inspect failed (e.g., container not found), return an empty string.
        echo ""
    fi
}

BLUE_HOST=$(get_host_status ${WEB_SERVICE_BASE}-blue)
GREEN_HOST=$(get_host_status ${WEB_SERVICE_BASE}-green)

# --- Check which container is LIVE ---
if [ "${BLUE_HOST}" == "${VIRTUAL_HOST_DOMAIN}" ]; then
    LIVE_SLOT="${WEB_SERVICE_BASE}-blue"
    export TARGET_SLOT="${WEB_SERVICE_BASE}-green"
elif [ "${GREEN_HOST}" == "${VIRTUAL_HOST_DOMAIN}" ]; then
    LIVE_SLOT="${WEB_SERVICE_BASE}-green"
    export TARGET_SLOT="${WEB_SERVICE_BASE}-blue"
else
    # Initial deploy or failure to detect: This is the critical change.
    echo "⚠️ WARNING: Could not detect LIVE_SLOT. Assuming this is an initial deployment."
    LIVE_SLOT="none"
    export TARGET_SLOT="${WEB_SERVICE_BASE}-blue" # Default to blue for initial run
fi

echo "✅ LIVE_SLOT detected as: ${LIVE_SLOT}."
echo "🎯 Identified TARGET_SLOT for deployment and setup: ${TARGET_SLOT}"

# -------------------------------------------------------------
# 3. RECREATE ONLY THE TARGET_SLOT (Zero-Downtime Start)
# -------------------------------------------------------------
echo "🚀 Recreating **ONLY** the inactive slot (${TARGET_SLOT}) and ensuring core services are up with the new image..."

# We explicitly set VIRTUAL_HOST_SET="" for the target slot only.
# This prevents Nginx-Proxy from trying to route traffic here prematurely.
VIRTUAL_HOST_SET="" docker compose --env-file .env -f docker-compose.yml up -d \
  ${TARGET_SLOT} \
  ${DB_SERVICE} \
  ${TYPESENSE_SERVICE};

# -------------------------------------------------------------
# 4. Force-restart DB and Load Credentials
# -------------------------------------------------------------
echo "🔄 Force-restarting DB container..."
docker compose restart ${DB_SERVICE}

# SAFE EXTRACTION: Added DB_PASSWORD here
RAW_DB_USER=$(grep "^DB_USERNAME=" .env | cut -d '=' -f 2- | tr -d '\r' | xargs || true)
RAW_DB_NAME=$(grep "^DB_DATABASE=" .env | cut -d '=' -f 2- | tr -d '\r' | xargs || true)
RAW_DB_PASS=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f 2- | tr -d '\r' | xargs || true)

export DB_USERNAME="${RAW_DB_USER:-postgres}"
export DB_DATABASE="${RAW_DB_NAME:-laravel}"
export DB_PASSWORD="${RAW_DB_PASS:-}" # Bound to empty string if missing

echo "✅ Credentials loaded (User: ${DB_USERNAME}, DB: ${DB_DATABASE})"

# -------------------------------------------------------------
# 5. Wait for the DB to be ready
# -------------------------------------------------------------
echo "⏳ Waiting for database (${DB_SERVICE}) to be ready..."
MAX_RETRIES=30
COUNT=0

# Use the variables we just safely defined
until docker exec ${DB_SERVICE} pg_isready -U "${DB_USERNAME}" -d "${DB_DATABASE}" > /dev/null 2>&1; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $MAX_RETRIES ]; then
        echo "❌ Error: Database was not ready after 60 seconds."
        exit 1
    fi
    echo "Still waiting for DB... ($COUNT/$MAX_RETRIES)"
    sleep 2
done

echo "✅ Database is ready."

# 6: APP_KEY MANAGEMENT (Keep your existing Section 6 logic here)
# ... [Omitted for brevity, keep your code] ...

# -------------------------------------------------------------
# 7: RUN MIGRATIONS
# -------------------------------------------------------------
echo "🛠️ Running migrations on the inactive container (${TARGET_SLOT})..."

# Note: We no longer need to grep DB credentials here because we exported them in Section 4.

echo "Running migrations using docker compose run..."
docker compose run --rm -T \
    --entrypoint="/bin/bash" \
    --no-deps \
    -e IMAGE_TAG=${IMAGE_TAG} \
    -e DB_USERNAME=${DB_USERNAME} \
    -e DB_PASSWORD=${DB_PASSWORD} \
    -e DB_DATABASE=${DB_DATABASE} \
    ${TARGET_SLOT} -c "php artisan migrate --force --no-interaction"

# Check if migrations succeeded before seeding and typesense import
if [ $? -eq 0 ]; then
    echo "✅ Database Migrations successful. Starting Seeding and Typesense Setup..."

    # Run Seeding
    docker compose run --rm -T \
        --entrypoint="/bin/bash" \
        --no-deps \
        -e IMAGE_TAG=${IMAGE_TAG} \
        ${TARGET_SLOT} -c "php artisan db:seed --force --no-interaction"
    if [ $? -ne 0 ]; then
        echo "❌ Database Seeding Failed! Check logs."
        exit 1
    fi

    # 🚀 START TYPESENSE
    echo "🔍 Creating Typesense collections and importing initial data..."
    # Corrected command: Run Typesense setup on the inactive slot
    docker compose run --rm -T \
        --entrypoint="/bin/bash" \
        --no-deps \
        -e IMAGE_TAG=${IMAGE_TAG} \
        ${TARGET_SLOT} -c "php artisan typesense:create-collections --force --import"

    if [ $? -ne 0 ]; then
        echo "❌ Typesense Collection creation and import Failed! Check logs."
        exit 1
    fi
    echo "✅ Typesense collections created and populated successfully."
    # 🔚 END: ADDED TYPESENSE COMMAND HERE

else
    echo "❌ Database Migration Failed! Check logs."
    exit 1
fi
echo "✅ Migrations, seeding, and typesense setup complete."

# -------------------------------------------------------------
# 9. ATOMIC SWAP OR INITIAL DEPLOYMENT
# -------------------------------------------------------------

if [ "${LIVE_SLOT}" = "none" ]; then
    # --- Case 1: Initial Deployment (No LIVE container found) ---
    echo "🚀 Initial deployment detected. Activating ${TARGET_SLOT}."
    # Bring up the TARGET_SLOT container and set its VIRTUAL_HOST to go LIVE.
    VIRTUAL_HOST_SET="${VIRTUAL_HOST_DOMAIN}" docker compose --env-file .env -f docker-compose.yml up -d ${TARGET_SLOT}

    if [ $? -ne 0 ]; then
        echo "❌ Initial TARGET service bring-up failed." >&2
        exit 1
    fi
    echo "✅ Initial service activated successfully."

else
    # --- Case 2: Standard Blue/Green Swap ---
    echo "🛠️ Granting execute permission to the swap script..."
    chmod +x actuallyfind-swop.sh
    echo "⚡ Executing the atomic Blue/Green swap script..."

    # We pass the domain to the swap script so it knows what to set.
    BASE_DOMAIN="${VIRTUAL_HOST_DOMAIN}" ./actuallyfind-swop.sh

    if [ $? -ne 0 ]; then
        echo "❌ Blue/Green swap script failed." >&2
        exit 1
    fi
    echo "✅ Atomic swap successful."
fi

# 9. Tell the worker to finish its current job and then exit, allowing Docker to restart it cleanly.
echo "🛎️ Signaling Queue worker to gracefully restart..."
docker exec ${QUEUE_SERVICE} php artisan queue:restart || true

# 10. Restart the Queue service (Moved after the swap/initial bring-up)
echo "🔁 Restarting Queue service with new code..."
docker compose --env-file .env -f docker-compose.yml restart ${QUEUE_SERVICE}

echo "✅ Deployment complete. Traffic is now routed to the new container."
echo "--- Blue/Green Deployment Finished ---"
