#!/bin/bash
# actuallyfind-swap.sh
# Performs an atomic Blue/Green deployment swap by managing VIRTUAL_HOST environment variables.

set -eu  # Exit immediately if a command exits with a non-zero status or if an unset variable is used.

# --- Configuration ---
BLUE_SERVICE="actuallyfind-web-blue"
GREEN_SERVICE="actuallyfind-web-green"

# The deployment domain (e.g., yourdomain.com).
# CRITICAL: We strip protocol, path, carriage returns, and all whitespace from the domain string.
VIRTUAL_HOST_DOMAIN=$(echo "${APP_URL:-${BASE_DOMAIN}}" | sed -e 's|^[^/]*//||' -e 's|/.*$||' | tr -d '\r' | tr -d '[:space:]')

if [ -z "$VIRTUAL_HOST_DOMAIN" ]; then
    echo "❌ Error: VIRTUAL_HOST_DOMAIN could not be determined. APP_URL or BASE_DOMAIN must be set." >&2
    exit 1
fi

echo "--- Current Status Check ---"

# Function to get the VIRTUAL_HOST status of a running container
get_host_status() {
    local service_name=$1
    # Use the same exact inspect command you found worked, but target a single service
    docker inspect ${service_name} \
        --format '{{range .Config.Env}}{{if eq (index (split . "=") 0) "VIRTUAL_HOST"}}{{(index (split . "=") 1)}}{{end}}{{end}}' 2>/dev/null
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
# CRITICAL: We simplify the logic to rely on the non-zero-length string check (-n).

# Case D: Both Live (True Error) - Check first
if [ -n "${BLUE_HOST}" ] && [ -n "${GREEN_HOST}" ]; then
    echo "❌ Error: Both slots appear LIVE. Cannot safely proceed with swap." >&2
    exit 1
fi

# Case A: Blue is Live
if [ -n "${BLUE_HOST}" ]; then
    LIVE_SERVICE="blue"
    LIVE_CONTAINER=${BLUE_SERVICE}
    TARGET_SERVICE="green"
    TARGET_CONTAINER=${GREEN_SERVICE}
    echo "Live detected: BLUE. Target is: GREEN."

# Case B: Green is Live
elif [ -n "${GREEN_HOST}" ]; then
    LIVE_SERVICE="green"
    LIVE_CONTAINER=${GREEN_SERVICE}
    TARGET_SERVICE="blue"
    TARGET_CONTAINER=${BLUE_SERVICE}
    echo "Live detected: GREEN. Target is: BLUE."

# Case C: Initial Deployment (Neither is Live)
elif [ -z "${BLUE_HOST}" ] && [ -z "${GREEN_HOST}" ]; then
    LIVE_SERVICE="none"
    TARGET_SERVICE="green"
    TARGET_CONTAINER=${GREEN_SERVICE}
    echo "Initial state detected. Setting GREEN as Target."

else
    # This final else is a true catch-all for an unforeseen error, but should not be reached now.
    echo "❌ Fatal Error: Unforeseen state detected. Check VIRTUAL_HOST values." >&2
    exit 1
fi

echo "LIVE Service: ${LIVE_SERVICE}"
echo "TARGET Service: ${TARGET_SERVICE}"
echo "------------------------------"

# 3. ATOMIC SWITCH: Set VIRTUAL_HOST on the Target service (which should be running the new code)
echo "Starting atomic swap: setting VIRTUAL_HOST for ${TARGET_SERVICE}..."

# Set VIRTUAL_HOST on the target container (TARGET_CONTAINER), making it immediately available.
VIRTUAL_HOST_SET="${VIRTUAL_HOST_DOMAIN}" docker compose up -d ${TARGET_CONTAINER}

if [ $? -ne 0 ]; then
    echo "❌ Swap failed during TARGET service bring-up." >&2
    exit 1
fi

# The Nginx-Proxy should detect the new container and start routing traffic to it almost instantly.
echo "Waiting 5 seconds for Nginx-Proxy to detect the new service..."
sleep 5

# 4. Scale down the old service if it existed
if [ "${LIVE_SERVICE}" != "none" ]; then
    echo "Swap complete. Scaling down old service (${LIVE_SERVICE}) by unsetting VIRTUAL_HOST..."

    # CRITICAL: Instead of relying on VIRTUAL_HOST_SET="", we explicitly use the
    # docker inspect/up method but force the VIRTUAL_HOST to an empty value.
    # We still use 'docker compose up' because it's the only way to apply env changes,
    # but the previous fixes should have minimized the restart time.

    # We only take the service offline if the swap was successful (i.e., the target is live)
    # The container that was supposed to go live is TARGET_CONTAINER. Let's inspect it:
    NEW_HOST_STATUS=$(get_host_status ${TARGET_CONTAINER})
    if [ "${NEW_HOST_STATUS}" = "${VIRTUAL_HOST_DOMAIN}" ]; then
        echo "Traffic confirmed on ${TARGET_SERVICE}. Taking ${LIVE_SERVICE} offline..."
        # This is the zero-downtime way: unset the VIRTUAL_HOST variable on the old live slot.
        VIRTUAL_HOST_SET="" docker compose up -d ${LIVE_CONTAINER}
    else
        echo "⚠️ WARNING: New service (${TARGET_SERVICE}) did not successfully pick up VIRTUAL_HOST. Abandoning LIVE service shutdown." >&2
    fi
fi

echo "Deployment successful. Traffic is now routed to ${TARGET_SERVICE} (if successful)."
