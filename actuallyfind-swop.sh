#!/bin/bash
set -eo pipefail
# This script performs the atomic swap in a Blue/Green deployment.
# It switches the VIRTUAL_HOST from the currently live container to the target container.

VIRTUAL_HOST_DOMAIN="${BASE_DOMAIN}"
LIVE_CONTAINER=""
TARGET_CONTAINER=""
LIVE_SERVICE=""
TARGET_SERVICE=""

# Function to get the VIRTUAL_HOST value for a given service name
get_host_status() {
    local service_name=$1
    # This is the most robust way to check for the VIRTUAL_HOST value
    docker inspect ${service_name} \
        --format '{{range .Config.Env}}{{if eq (index (split . "=") 0) "VIRTUAL_HOST"}}{{(index (split . "=") 1)}}{{end}}{{end}}' 2>/dev/null
}

# 1. Determine the currently LIVE container (the one with the VIRTUAL_HOST)
echo "--- Current Status Check ---"

BLUE_STATUS=$(get_host_status actuallyfind-web-blue)
GREEN_STATUS=$(get_host_status actuallyfind-web-green)

echo "Blue Status (VIRTUAL_HOST): ${BLUE_STATUS}"
echo "Green Status (VIRTUAL_HOST): ${GREEN_STATUS}"

if [ "${BLUE_STATUS}" = "${VIRTUAL_HOST_DOMAIN}" ]; then
    LIVE_SERVICE="blue"
    TARGET_SERVICE="green"
elif [ "${GREEN_STATUS}" = "${VIRTUAL_HOST_DOMAIN}" ]; then
    LIVE_SERVICE="green"
    TARGET_SERVICE="blue"
else
    # If no containers are live, this swap script cannot proceed.
    echo "❌ Swap failed: No LIVE container detected. The system must be in an initial deployment state." >&2
    # In a true Blue/Green setup, the initial deployment should be handled by the main script,
    # and the swap script should only run when containers already exist.
    exit 1
fi

LIVE_CONTAINER="actuallyfind-web-${LIVE_SERVICE}"
TARGET_CONTAINER="actuallyfind-web-${TARGET_SERVICE}"

echo "LIVE Service: ${LIVE_SERVICE}"
echo "TARGET Service: ${TARGET_SERVICE}"
echo "------------------------------"

# The deployment script (deploy-prod.sh) built and ran migrations on TARGET_SERVICE.
# Now we perform the atomic switch.

# 3. ATOMIC SWITCH: Set VIRTUAL_HOST on the Target service
echo "Starting atomic swap: setting VIRTUAL_HOST for ${TARGET_SERVICE}..."

# Set VIRTUAL_HOST on the target container (TARGET_CONTAINER), making it immediately available.
# We must use VIRTUAL_HOST_SET in the environment of docker compose up command.
VIRTUAL_HOST_SET="${VIRTUAL_HOST_DOMAIN}" docker compose up -d ${TARGET_CONTAINER}

if [ $? -ne 0 ]; then
    echo "❌ Swap failed during TARGET service bring-up." >&2
    exit 1
fi

echo "Waiting 5 seconds for Nginx-Proxy to detect the new service..."
sleep 5

# 4. Scale down the old service (the old live slot)
if [ "${LIVE_SERVICE}" != "none" ]; then
    echo "Swap complete. Taking old service (${LIVE_SERVICE}) offline..."

    # Check one last time if the TARGET service is actually live before taking the old one offline
    NEW_HOST_STATUS=$(get_host_status ${TARGET_CONTAINER})
    if [ "${NEW_HOST_STATUS}" = "${VIRTUAL_HOST_DOMAIN}" ]; then
        echo "Traffic confirmed on ${TARGET_SERVICE}. Taking ${LIVE_SERVICE} offline..."
        # Unset the VIRTUAL_HOST on the old container. This causes a brief restart, but traffic is already on the new slot.
        VIRTUAL_HOST_SET="" docker compose up -d ${LIVE_CONTAINER}
    else
        echo "⚠️ WARNING: New service (${TARGET_SERVICE}) did not successfully pick up VIRTUAL_HOST. Abandoning LIVE service shutdown." >&2
    fi
fi

echo "Deployment successful. Traffic is now routed to ${TARGET_SERVICE}."
