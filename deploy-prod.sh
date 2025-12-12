#!/bin/bash
## This script is executed on the production server via SSH by the GitHub Actions workflow.
## It performs the zero-downtime blue/green deployment.

# Environment variables are passed from the GitHub Action.
set -euo pipefail

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
# 4. Force-restart DB (Required for clean password application)
# -------------------------------------------------------------
echo "🔄 Force-restarting DB container to ensure clean environment variables are applied..."
docker compose restart actuallyfind-db
if [ $? -ne 0 ]; then
    echo "❌ DB restart failed!"
    exit 1
fi
echo "✅ DB restarted successfully."

# 5. Wait for the new container to stabilize
echo "⏳ Waiting 10 seconds for the newly built container to stabilize..."
sleep 10

# 6: APP_KEY MANAGEMENT (CREATE IF MISSING)
echo "🔑 Checking and generating APP_KEY..."
if grep -q '^APP_KEY=$' .env; then
    echo "⚠️ APP_KEY is missing a value. Generating a new one..."
    # Execute the command inside the new TARGET_SLOT
    NEW_KEY=$(docker compose run --rm -T --no-deps ${TARGET_SLOT} php artisan key:generate --show)
    KEY_VALUE=$(echo "$NEW_KEY" | tail -n 1)
    sed -i "/^APP_KEY=/c\APP_KEY=$KEY_VALUE" .env
    if [ $? -ne 0 ]; then
        echo "❌ Failed to generate and update APP_KEY."
        exit 1
    fi
    echo "✅ New APP_KEY generated and saved to .env"
else
    echo "✅ APP_KEY already exists and is in use."
fi

# -------------------------------------------------------------
# 7: RUN MIGRATIONS ON THE INACTIVE TARGET CONTAINER
#    (Seeding removed to mitigate downtime/DB locks)
# -------------------------------------------------------------
echo "🛠️ Running migrations on the inactive container (${TARGET_SLOT})..."

# Safely read and export DB credentials from the remote .env file
# (This may be redundant if defined in the image, but kept for robustness)
export DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f 2- | tr -d '\r' | xargs)
export DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f 2- | tr -d '\r' | xargs)
export DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f 2- | tr -d '\r' | xargs)
echo "Running migrations using docker compose run..."

# Run Migrations (using explicit entrypoint and --no-deps)
docker compose run --rm -T \
    --entrypoint="/bin/bash" \
    --no-deps \
    -e IMAGE_TAG=${IMAGE_TAG} \
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

# 9. Restart the Queue service (Moved after the swap/initial bring-up)
echo "🔁 Restarting Queue service with new code..."
docker compose --env-file .env -f docker-compose.yml restart ${QUEUE_SERVICE}

echo "✅ Deployment complete. Traffic is now routed to the new container."
echo "--- Blue/Green Deployment Finished ---"
