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

# 4. Bring up the core services (DB/Typesense) and ensure all web slots are defined,
# loading variables explicitly from .env for the application secrets.
echo "🚀 Ensuring all core services and web slots (inactive/active) are running the new image..."
# The DOCKER_*_PORT variables are exported from the CI step.
# VIRTUAL_HOST_SET is passed as an empty variable to keep the new web containers offline.
VIRTUAL_HOST_SET="" docker compose --env-file .env -f docker-compose.yml up -d \
  ${WEB_SERVICE_BASE}-blue \
  ${WEB_SERVICE_BASE}-green \
  ${DB_SERVICE} \
  ${TYPESENSE_SERVICE}; \

# 5. Determine the TARGET_SLOT for setup before the swap
LIVE_SLOT=$(docker ps --filter "name=${WEB_SERVICE_BASE}" --filter "label=com.docker.compose.label=live" --format "{{.Names}}" | head -n 1)

if [ "${LIVE_SLOT}" == "${WEB_SERVICE_BASE}-blue" ]; then
    export TARGET_SLOT="${WEB_SERVICE_BASE}-green"
elif [ "${LIVE_SLOT}" == "${WEB_SERVICE_BASE}-green" ]; then
    export TARGET_SLOT="${WEB_SERVICE_BASE}-blue"
else
    # Initial deploy case: If neither is live, default to green.
    export TARGET_SLOT="${WEB_SERVICE_BASE}-green"
fi

echo "🎯 Identified TARGET_SLOT for deployment and setup: ${TARGET_SLOT}"

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

# --- NEW STEP 8: RUN MIGRATIONS/SEEDERS ON THE INACTIVE TARGET CONTAINER (Using docker-compose run) ---
echo "🛠️ Running migrations and setup on the inactive container (${TARGET_SLOT})..."

# The TARGET_SLOT variable (e.g., actuallyfind-web-green) is the name of the
# web service defined in your docker-compose.yml file.
# We use 'docker-compose run' which safely inherits the environment from the host
# and the service definition, bypassing complex shell parsing for ENV_FLAGS.
# --rm: removes the container instance after the command completes.
# -T: disables pseudo-tty allocation (good for non-interactive scripts).
# -e: Inject the necessary image tag, which is often crucial for compose files.

echo "Running migrations using docker-compose run..."

# 1. Run Migrations
docker-compose run --rm -T \
    -e IMAGE_TAG=${IMAGE_TAG} \
    ${TARGET_SLOT} php artisan migrate --force --no-interaction

# Check if migrations succeeded before seeding
if [ $? -eq 0 ]; then
    echo "✅ Database Migrations successful. Starting Seeding..."

    # 2. Run Seeding
    docker-compose run --rm -T \
        -e IMAGE_TAG=${IMAGE_TAG} \
        ${TARGET_SLOT} php artisan db:seed --force --no-interaction

    if [ $? -ne 0 ]; then
        echo "❌ Database Seeding Failed! Check logs."
        exit 1
    fi
else
    echo "❌ Database Migration Failed! Check logs."
    exit 1
fi
echo "✅ Migrations and seeding complete."

# NOTE: You will need to remove the code blocks that defined and constructed ENV_FLAGS_ARRAY
# from the previous step, as they are no longer needed.

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
