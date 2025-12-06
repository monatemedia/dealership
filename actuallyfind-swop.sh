#!/bin/bash
# actuallyfind-swap.sh
# Performs an atomic Blue/Green deployment swap by managing VIRTUAL_HOST environment variables.
set -eu  # Exit immediately if a command exits with a non-zero status (except in conditional/subshell) or if an unset variable is used.

# --- Configuration ---
# Services must match those defined in docker-compose.yml
BLUE_SERVICE="actuallyfind-web-blue"
GREEN_SERVICE="actuallyfind-web-green"
BASE_SERVICE_NAME="actuallyfind-web"

# The deployment domain (e.g., yourdomain.com).
# We strip the protocol (e.g., https://) from the APP_URL provided by the CI environment.
# If APP_URL is not set, we default to the value passed via the BASE_DOMAIN argument.
VIRTUAL_HOST_DOMAIN=$(echo "${APP_URL:-${BASE_DOMAIN}}" | sed -e 's|^[^/]*//||' -e 's|/.*$||')

if [ -z "$VIRTUAL_HOST_DOMAIN" ]; then
    echo "❌ Error: VIRTUAL_HOST_DOMAIN could not be determined. APP_URL or BASE_DOMAIN must be set." >&2
    exit 1
fi

echo "--- Current Status Check ---"

# Function to get the VIRTUAL_HOST status of a running container
# This looks at the VIRTUAL_HOST environment variable set on the container
get_host_status() {
    # Check if the VIRTUAL_HOST environment variable has a value set.
    # The output is simply the full VIRTUAL_HOST value, or an empty string if not found/empty.
    # We use the same inspection method as deploy-prod.sh to be consistent.
    docker inspect --format '{{range .Config.Env}}{{if eq (index (split . "=") 0) "VIRTUAL_HOST"}}{{(index (split . "=") 1)}}{{end}}{{end}}' "$1" 2>/dev/null | head -n 1
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

# Case A: Blue is Live, Green is Target
if [ "${BLUE_HOST}" = "${VIRTUAL_HOST_DOMAIN}" ] && [ -z "${GREEN_HOST}" ]; then
    LIVE_SERVICE="blue"
    LIVE_CONTAINER=${BLUE_SERVICE}
    TARGET_SERVICE="green"
    TARGET_CONTAINER=${GREEN_SERVICE}
    echo "Live detected: BLUE. Target is: GREEN."

# Case B: Green is Live, Blue is Target
elif [ "${GREEN_HOST}" = "${VIRTUAL_HOST_DOMAIN}" ] && [ -z "${BLUE_HOST}" ]; then
    LIVE_SERVICE="green"
    LIVE_CONTAINER=${GREEN_SERVICE}
    TARGET_SERVICE="blue"
    TARGET_CONTAINER=${BLUE_SERVICE}
    echo "Live detected: GREEN. Target is: BLUE."

# Case C: Initial Deployment (or cleanup failed) - assume Blue is the default target
elif [ -z "${BLUE_HOST}" ] && [ -z "${GREEN_HOST}" ]; then
    LIVE_SERVICE="none" # No service is currently live
    TARGET_SERVICE="green" # Defaulting new traffic to green
    TARGET_CONTAINER=${GREEN_SERVICE}
    echo "Initial state detected. Setting GREEN as Target."

# Case D: Ambiguous/Error State (Both Live or unexpected state)
else
    # The only ambiguous state we check for is BOTH having the domain, or NEITHER having it
    # We allow the swap to proceed if the target has been updated (not ideal, but necessary for resilient deployment)

    # Check if we successfully determined a target service (meaning Blue/Green were NOT in a bad state)
    if [ -z "${TARGET_SERVICE}" ]; then
        echo "❌ Error: Ambiguous live state detected. Cannot safely swap." >&2
        exit 1
    fi
fi

echo "LIVE Service: ${LIVE_SERVICE}"
echo "TARGET Service: ${TARGET_SERVICE}"
echo "------------------------------"

# 3. ATOMIC SWITCH: Set VIRTUAL_HOST on the Target service (which should be running the new code)
echo "Starting atomic swap: setting VIRTUAL_HOST for ${TARGET_SERVICE}..."

# Stop the target container first, ensuring we use the image specified by IMAGE_TAG export
# and setting the VIRTUAL_HOST correctly via the 'VIRTUAL_HOST_SET' variable.
# We use docker compose up for the swap as it handles dependency linking.

# The swap works by using the "VIRTUAL_HOST_SET" env variable defined in the docker-compose.yml
# which should be used to override the default "VIRTUAL_HOST" environment variable on the target container.
# This makes the new container immediately join the reverse proxy (nginx-proxy).

VIRTUAL_HOST_SET="${VIRTUAL_HOST_DOMAIN}" docker compose up -d ${TARGET_CONTAINER}

if [ $? -ne 0 ]; then
    echo "❌ Swap failed during TARGET service bring-up." >&2
    exit 1
fi

echo "Waiting 5 seconds for Nginx-Proxy to detect the new service..."
sleep 5

# 4. Scale down the old service if it existed
if [ "${LIVE_SERVICE}" != "none" ]; then
    echo "Swap complete. Scaling down old service (${LIVE_SERVICE}) by unsetting VIRTUAL_HOST..."

    # This is the zero-downtime way: unset the VIRTUAL_HOST variable on the old live slot.
    VIRTUAL_HOST_SET="" docker compose up -d ${LIVE_CONTAINER}
fi

echo "Deployment successful. Traffic is now routed to ${TARGET_SERVICE}."
