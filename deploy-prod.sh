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

set -euo pipefail

DEPLOY_TAG="production"
FULL_IMAGE_NAME="${IMAGE_NAME}:${DEPLOY_TAG}"

echo "--- Starting Blue/Green Deployment on Remote Server ---"

# 1. Change to the application directory
cd ${WORK_DIR} || { echo "ERROR: Could not change to directory ${WORK_DIR}"; exit 1; }
echo "✅ Changed directory to ${WORK_DIR}"

# 2. Pull the latest Docker image
echo "📥 Pulling latest image: ${FULL_IMAGE_NAME}"
docker pull ${FULL_IMAGE_NAME}

# 3. Export the Image Tag for docker-compose to use
# (Assumes docker-compose.yml uses IMAGE_TAG environment variable)
export IMAGE_TAG=${DEPLOY_TAG}
echo "🏷️ Exported IMAGE_TAG=${IMAGE_TAG}"

# 4. Bring up the core services (DB/Typesense) and ensure all web slots are defined,
# but without the VIRTUAL_HOST_SET environment variable (which is handled by the swap script).
# This ensures the inactive slot is running the new image, but isn't receiving traffic yet.
echo "🚀 Ensuring all core services and web slots (inactive/active) are running the new image..."
# We explicitly pass VIRTUAL_HOST_SET="" here to ensure the newly started container is NOT live yet.
VIRTUAL_HOST_SET="" docker compose -f docker-compose.yml up -d \
  ${WEB_SERVICE_BASE}-blue \
  ${WEB_SERVICE_BASE}-green \
  ${DB_SERVICE} \
  ${TYPESENSE_SERVICE}

# 5. Run the essential setup container (Migrations/Core Seeders)
echo "✨ Running essential setup (Migrations/Core Seeders)..."
docker compose -f docker-compose.yml up ${SETUP_INIT_SERVICE} --remove-orphans --abort-on-container-exit
echo "✅ Essential setup complete."

# 6. Wait for the new container to stabilize (passes health check)
echo "⏳ Waiting 10 seconds for the newly built container to stabilize..."
sleep 10

# 7. Granting execute permission to the swap script
echo "🛠️ Granting execute permission to the swap script..."
# NOTE: This assumes 'actuallyfind-swap.sh' is also present in ${WORK_DIR}
chmod +x actuallyfind-swap.sh

# 8. ATOMIC SWITCH: Execute the dedicated swap script
echo "⚡ Executing the atomic Blue/Green swap script..."
# BASE_DOMAIN is passed as the actual domain
BASE_DOMAIN="${APP_URL}" ./actuallyfind-swap.sh

# 9. Restart the Queue service to connect to the new code base
echo "🔁 Restarting Queue service with new code..."
docker compose -f docker-compose.yml restart ${QUEUE_SERVICE}

echo "✅ Deployment complete. Traffic is now routed to the new container."
echo "--- Blue/Green Deployment Finished ---"
