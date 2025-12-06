#!/bin/bash

## This script is executed on the production server via SSH by the GitHub Actions workflow.
## It performs the zero-downtime blue/green deployment.

## Environment variables (passed from the GitHub Action):
# - WORK_DIR: The application's working directory on the server.
# - IMAGE_NAME: The full name of the Docker image registry (e.g., ghcr.io/user/repo)
# - APP_URL: The public URL for health checking.
# - WEB_SERVICE_BASE: Base name for web services (e.g., actuallyfind-web)
# - DB_SERVICE: Database service name.
# - TYPESENSE_SERVICE: Typesense service name.
# - QUEUE_SERVICE: Queue service name.
# - DEPLOY_TAG: The tag of the image to deploy (always 'production').
# - DOCKER_*_PORT: Host port variables (now passed directly from the Action)

set -euo pipefail

DEPLOY_TAG="production"
FULL_IMAGE_NAME="${IMAGE_NAME}:${DEPLOY_TAG}"

echo "--- Starting Blue/Green Deployment on Remote Server ---"

# 1. Change to the application directory
# NOTE: The CI workflow step now handles the 'cd ${WORK_DIR}' command *before* executing this script.
echo "✅ Current working directory is: $(pwd)"

# 2. Pull the latest Docker image
echo "📥 Pulling latest image: ${FULL_IMAGE_NAME}"
docker pull ${FULL_IMAGE_NAME}

# 3. Export the Image Tag for docker-compose to use
# (Assumes docker-compose.yml uses IMAGE_TAG environment variable)
export IMAGE_TAG=${DEPLOY_TAG}
echo "🏷️ Exported IMAGE_TAG=${IMAGE_TAG}"

# 4. Determine the TARGET_SLOT for setup before the swap
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
    # Initial deploy case or detection failed
    echo "⚠️ WARNING: Could not detect LIVE_SLOT. Assuming initial deployment or detection failure."
    export LIVE_SLOT="${WEB_SERVICE_BASE}-blue" # Assume blue is currently serving (or should be)
    export TARGET_SLOT="${WEB_SERVICE_BASE}-green"
    echo "🎯 Defaulting to LIVE_SLOT=${LIVE_SLOT} and TARGET_SLOT=${TARGET_SLOT}."
fi

echo "🎯 Identified TARGET_SLOT for deployment and setup: ${TARGET_SLOT}"

# 5. Bring up the core services (DB/Typesense) and ensure all web slots are defined,
# loading variables explicitly from .env for the application secrets.
echo "🚀 Recreating **ONLY** the inactive slot (${TARGET_SLOT}) and ensuring core services are up with the new image..."

# We remove VIRTUAL_HOST_SET="" from the shell call.
# This prevents the live container from having its VIRTUAL_HOST overwritten with a blank value.
# The target container will still get a blank VIRTUAL_HOST from .env, keeping it offline.
docker compose --env-file .env -f docker-compose.yml up -d \
  ${TARGET_SLOT} \
  ${DB_SERVICE} \
  ${TYPESENSE_SERVICE};

# ----------------------------------------------
# NEW STEP: Force-restart the DB to re-read the environment password cleanly
# ----------------------------------------------
echo "🔄 Force-restarting DB container to ensure clean environment variables are applied..."
docker compose restart actuallyfind-db
if [ $? -ne 0 ]; then
    echo "❌ DB restart failed!"
    exit 1
fi
echo "✅ DB restarted successfully."

# 6. Wait for the new container to stabilize (passes health check)
echo "⏳ Waiting 30 seconds for the newly built container to stabilize..."
sleep 30

# 7: APP_KEY MANAGEMENT (CREATE IF MISSING) ---
echo "🔑 Checking and generating APP_KEY..."
# FIX: Use relative path '.env' instead of '${WORK_DIR}/.env'
if grep -q '^APP_KEY=$' .env; then
    echo "⚠️ APP_KEY is missing a value. Generating a new one..."

    # 1. Generate the key inside the container and capture the output
    NEW_KEY=$(docker exec ${TARGET_SLOT} php artisan key:generate --show)

    # 2. Extract the base64 value
    KEY_VALUE=$(echo "$NEW_KEY" | tail -n 1)

    # 3. Replace the old placeholder line with the new key in the host .env file
    # FIX: Use relative path '.env'
    sed -i "/^APP_KEY=/c\APP_KEY=$KEY_VALUE" .env

    if [ $? -ne 0 ]; then
        echo "❌ Failed to generate and update APP_KEY."
        exit 1
    fi
    echo "✅ New APP_KEY generated and saved to .env"
else
    echo "✅ APP_KEY already exists and is in use."
fi

# --- NEW STEP 8: RUN MIGRATIONS/SEEDERS ON THE INACTIVE TARGET CONTAINER (Direct Export) ---
echo "🛠️ Running migrations and setup on the inactive container (${TARGET_SLOT})..."

# 1. Safely read and export critical environment variables from the remote .env file.
# This ensures the exact, remote .env password is used for the docker compose run command.
# This is a highly robust way to read the variables without complex array parsing.
export DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f 2- | tr -d '\r' | xargs)
export DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f 2- | tr -d '\r' | xargs)
export DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f 2- | tr -d '\r' | xargs)

# Note: We are now explicitly setting the environment variables in the script's shell
# so that the 'docker compose run' command, which uses shell interpolation (${DB_PASSWORD}),
# gets the clean, correct value from the remote .env.

echo "Running migrations using docker compose run..."

# 1. Run Migrations
# We rely on the exported variables (DB_PASSWORD, DB_USERNAME, etc.) to be clean.
# We keep the entrypoint override to ensure only the migration runs.
docker compose run --rm -T \
    --entrypoint="/bin/bash" \
    --no-deps \
    -e IMAGE_TAG=${IMAGE_TAG} \
    ${TARGET_SLOT} -c "php artisan migrate --force --no-interaction"

# Check if migrations succeeded before seeding
if [ $? -eq 0 ]; then
    echo "✅ Database Migrations successful. Starting Seeding..."

    # 2. Run Seeding
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

# 9. Granting execute permission to the swap script
echo "🛠️ Granting execute permission to the swap script..."
# NOTE: This assumes 'actuallyfind-swop.sh' is also present in ${WORK_DIR}
chmod +x actuallyfind-swop.sh

# 10. ATOMIC SWITCH: Execute the dedicated swap script
echo "⚡ Executing the atomic Blue/Green swap script..."
# BASE_DOMAIN is passed as the actual domain
BASE_DOMAIN="${APP_URL}" ./actuallyfind-swop.sh

# 11. Restart the Queue service to connect to the new code base
echo "🔁 Restarting Queue service with new code..."
# Pass --env-file .env to ensure the queue service has access to app credentials.
docker compose --env-file .env -f docker-compose.yml restart ${QUEUE_SERVICE}
echo "✅ Deployment complete. Traffic is now routed to the new container."

echo "--- Blue/Green Deployment Finished ---"
