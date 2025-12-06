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
# Check Blue slot: look for VIRTUAL_HOST with any content
if docker inspect ${WEB_SERVICE_BASE}-blue | grep -q 'VIRTUAL_HOST=.\+'; then
    LIVE_SLOT="${WEB_SERVICE_BASE}-blue"
fi

# Check Green slot: look for VIRTUAL_HOST with any content
if [ -z "${LIVE_SLOT}" ]; then
    if docker inspect ${WEB_SERVICE_BASE}-green | grep -q 'VIRTUAL_HOST=.\+'; then
        LIVE_SLOT="${WEB_SERVICE_BASE}-green"
    fi
fi

if [ "${LIVE_SLOT}" == "${WEB_SERVICE_BASE}-blue" ]; then
    export TARGET_SLOT="${WEB_SERVICE_BASE}-green"
    echo "✅ LIVE_SLOT is ${LIVE_SLOT}. Targeting ${TARGET_SLOT} for new deployment."
elif [ "${LIVE_SLOT}" == "${WEB_SERVICE_BASE}-green" ]; then
    export TARGET_SLOT="${WEB_SERVICE_BASE}-blue"
    echo "✅ LIVE_SLOT is ${LIVE_SLOT}. Targeting ${TARGET_SLOT} for new deployment."
else
    # Initial deploy case or detection failed: Default to green target.
    echo "⚠️ WARNING: Could not detect LIVE_SLOT. Assuming blue is LIVE for initial setup."
    export TARGET_SLOT="${WEB_SERVICE_BASE}-green"
fi

echo "🎯 Identified TARGET_SLOT for deployment and setup: ${TARGET_SLOT}"

# --- NEW STEP 3: EXPLICITLY ENSURE LIVE_SLOT VIRTUAL_HOST IS SET ---
echo "🔒 Ensuring LIVE_SLOT (${LIVE_SLOT}) maintains public VIRTUAL_HOST to guarantee zero downtime..."

# This command ensures the LIVE_SLOT is explicitly set with the public VIRTUAL_HOST
# and only re-creates the container if necessary, avoiding configuration loss.
VIRTUAL_HOST_SET="${APP_URL}" docker compose --env-file .env -f docker-compose.yml up -d \
    ${LIVE_SLOT}

if [ $? -ne 0 ]; then
    echo "❌ Failed to secure LIVE_SLOT VIRTUAL_HOST setting."
    exit 1
fi
echo "✅ LIVE_SLOT secured with VIRTUAL_HOST=${APP_URL}."

# -------------------------------------------------------------
# 4. RECREATE ONLY THE TARGET_SLOT (Zero-Downtime Start)
# -------------------------------------------------------------
echo "🚀 Recreating **ONLY** the inactive slot (${TARGET_SLOT}) and ensuring core services are up with the new image..."

# We explicitly set VIRTUAL_HOST_SET="" for the target slot only.
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
echo "⏳ Waiting 30 seconds for the newly built container to stabilize..."
sleep 30

# 6: APP_KEY MANAGEMENT (CREATE IF MISSING)
echo "🔑 Checking and generating APP_KEY..."
if grep -q '^APP_KEY=$' .env; then
    echo "⚠️ APP_KEY is missing a value. Generating a new one..."
    NEW_KEY=$(docker exec ${TARGET_SLOT} php artisan key:generate --show)
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
# 7: RUN MIGRATIONS/SEEDERS ON THE INACTIVE TARGET CONTAINER
# -------------------------------------------------------------
echo "🛠️ Running migrations and setup on the inactive container (${TARGET_SLOT})..."

# Safely read and export DB credentials from the remote .env file
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

# Check if migrations succeeded before seeding
if [ $? -eq 0 ]; then
    echo "✅ Database Migrations successful. Starting Seeding..."

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
else
    echo "❌ Database Migration Failed! Check logs."
    exit 1
fi
echo "✅ Migrations and seeding complete."

# -------------------------------------------------------------
# 8. ATOMIC SWAP
# -------------------------------------------------------------
echo "🛠️ Granting execute permission to the swap script..."
chmod +x actuallyfind-swop.sh

echo "⚡ Executing the atomic Blue/Green swap script..."
BASE_DOMAIN="${APP_URL}" ./actuallyfind-swop.sh

# 9. Restart the Queue service
echo "🔁 Restarting Queue service with new code..."
docker compose --env-file .env -f docker-compose.yml restart ${QUEUE_SERVICE}

echo "✅ Deployment complete. Traffic is now routed to the new container."
echo "--- Blue/Green Deployment Finished ---"
