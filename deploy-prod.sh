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
# - SETUP_INIT_SERVICE: Service name for migrations/setup.
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
  ${TYPESENSE_SERVICE}

# 5. Run the essential setup container (Migrations/Core Seeders)
echo "✨ Running essential setup (Migrations/Core Seeders)..."
# --- CRITICAL FIX: Add --env-file .env to load DB secrets for the INIT container ---
docker compose --env-file .env -f docker-compose.yml up ${SETUP_INIT_SERVICE} --remove-orphans --abort-on-container-exit
echo "✅ Essential setup complete."

# 6. Wait for the new container to stabilize (passes health check)
echo "⏳ Waiting 10 seconds for the newly built container to stabilize..."
sleep 10

# 7. Granting execute permission to the swap script
echo "🛠️ Granting execute permission to the swap script..."
# NOTE: This assumes 'actuallyfind-swop.sh' is also present in ${WORK_DIR}
chmod +x actuallyfind-swop.sh

# 8. ATOMIC SWITCH: Execute the dedicated swap script
echo "⚡ Executing the atomic Blue/Green swap script..."
# BASE_DOMAIN is passed as the actual domain
BASE_DOMAIN="${APP_URL}" ./actuallyfind-swop.sh

# 9. Restart the Queue service to connect to the new code base
echo "🔁 Restarting Queue service with new code..."
# Pass --env-file .env to ensure the queue service has access to app credentials.
docker compose --env-file .env -f docker-compose.yml restart ${QUEUE_SERVICE}
echo "✅ Deployment complete. Traffic is now routed to the new container."

echo "--- Blue/Green Deployment Finished ---"
