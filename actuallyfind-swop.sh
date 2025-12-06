#!/bin/bash
# actuallyfind-swap.sh
# Performs an atomic Blue/Green deployment swap by managing VIRTUAL_HOST environment variables.

set -eu  # Exit immediately if a command exits with a non-zero status or if an unset variable is used.

# --- Configuration ---
BLUE_SERVICE="actuallyfind-web-blue"
GREEN_SERVICE="actuallyfind-web-green"
BASE_SERVICE_NAME="actuallyfind-web"

# The deployment domain (e.g., yourdomain.com).
# We strip the protocol (e.g., https://) from the APP_URL provided by the CI environment.
# If APP_URL is not set, we default to the value passed via the BASE_DOMAIN argument.
VIRTUAL_HOST_DOMAIN=$(echo "${APP_URL:-${BASE_DOMAIN}}" | sed -e 's|^[^/]*//||' -e 's|/.*$||' | tr -d '\r' | tr -d '[:space:]')

if [ -z "$VIRTUAL_HOST_DOMAIN" ]; then
    echo "❌ Error: VIRTUAL_HOST_DOMAIN could not be determined. APP_URL or BASE_DOMAIN must be set." >&2
    exit 1
fi

echo "--- Current Status Check ---"

# Function to get the VIRTUAL_HOST status of a running container
get_host_status() {
    # CRITICAL: We strip all whitespace and control characters (like \r) to ensure accurate comparison.
    # The output is the VIRTUAL_HOST value, or an empty string if not found/empty.
    docker inspect --format '{{range .Config.Env}}{{if eq (index (split . "=") 0) "VIRTUAL_HOST"}}{{(index (split . "=") 1)}}{{end}}{{end}}' "$1" 2>/dev/null | \
    head -n 1 | \
    tr -d '[:space:]' | \
    tr -d '\n\r'
}

# 1. Check current status of Blue and Green containers
BLUE_HOST=$(get_host_status ${BLUE_SERVICE})
GREEN_HOST=$(get_host_status ${GREEN_SERVICE})

echo "Blue Status (VIRTUAL_HOST): ${BLUE_HOST}"
echo "Green Status (VIRTUAL_HOST): ${GREEN_HOST}"

LIVE_SERVICE=""
TARGET_SERVICE=""
LIVE_CONTAINER=""
TARGET_CONTAINER=""

# 2. Determine Live and Target services
# We check only if the container has the public domain to determine if it is LIVE.

# Case A: Blue is Live
if [ "${BLUE_HOST}" = "${VIRTUAL_HOST_DOMAIN}" ]; then
    LIVE_SERVICE="blue"
    LIVE_CONTAINER=${BLUE_SERVICE}
    TARGET_SERVICE="green"
    TARGET_CONTAINER=${GREEN_SERVICE}
    echo "Live detected: BLUE. Target is: GREEN."

# Case B: Green is Live
elif [ "${GREEN_HOST}" = "${VIRTUAL_HOST_DOMAIN}" ]; then
    LIVE_SERVICE="green"
    LIVE_CONTAINER=${GREEN_SERVICE}
    TARGET_SERVICE="blue"
    TARGET_CONTAINER=${BLUE_SERVICE}
    echo "Live detected: GREEN. Target is: BLUE."

# Case C: Initial Deployment (Neither is Live)
elif [ -z "${BLUE_HOST}" ] && [ -z "${GREEN_HOST}" ]; then
    LIVE_SERVICE="none" # No service is currently live
    TARGET_SERVICE="green" # Defaulting new traffic to green
    TARGET_CONTAINER=${GREEN_SERVICE}
    echo "Initial state detected. Setting GREEN as Target."

# Case D: Ambiguous/Error State (Both Live or unexpected configuration)
else
    echo "❌ Error: Ambiguous live state detected. Both services may have the VIRTUAL_HOST set, or an unexpected state was found." >&2
    exit 1
fi

echo "LIVE Service: ${LIVE_SERVICE}"
echo "TARGET Service: ${TARGET_SERVICE}"
echo "------------------------------"

# 3. ATOMIC SWITCH: Set VIRTUAL_HOST on the Target service
echo "Starting atomic swap: setting VIRTUAL_HOST for ${TARGET_SERVICE}..."

# Set the VIRTUAL_HOST on the target slot to bring it online.
VIRTUAL_HOST_SET="${VIRTUAL_HOST_DOMAIN}" docker compose up -d ${TARGET_CONTAINER}

if [ $? -ne 0 ]; then
    echo "❌ Swap failed during TARGET service bring-up." >&2
    exit 1
fi

echo "Waiting 5 seconds for Nginx-Proxy to detect the new service..."
sleep 5

# 4. Scale down the old service (Zero-Downtime Unset)
if [ "${LIVE_SERVICE}" != "none" ]; then
    echo "Swap complete. Scaling down old service (${LIVE_SERVICE}) by unsetting VIRTUAL_HOST..."

    # Use VIRTUAL_HOST_SET="" to unset the VIRTUAL_HOST environment variable,
    # taking the old container offline from the proxy without stopping the container.
    VIRTUAL_HOST_SET="" docker compose up -d ${LIVE_CONTAINER}
fi

echo "Deployment successful. Traffic is now routed to ${TARGET_SERVICE}."
