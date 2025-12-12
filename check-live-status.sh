#!/bin/bash

# --- Configuration (Must match your environment variables) ---
set -euo pipefail

# Base name for the web service
WEB_SERVICE_BASE="actuallyfind-web"

# Domain name used by Nginx-Proxy
# Note: You may want to hardcode this or fetch it from your .env file
VIRTUAL_HOST_DOMAIN="actuallyfind.com"

# --- Helper Function ---

# Function to get the VIRTUAL_HOST value for a given service name
get_host_status() {
    local service_name=$1
    # Check if the VIRTUAL_HOST environment variable matches the domain
    # Use || true to prevent 'docker inspect' from failing the script if container is down/missing.
    docker inspect ${service_name} \
        --format '{{range .Config.Env}}{{if eq (index (split . "=") 0) "VIRTUAL_HOST"}}{{(index (split . "=") 1)}}{{end}}{{end}}' 2>/dev/null || true
}

# Function to get the exposed host port
get_host_port() {
    local service_name=$1
    # Check the port mapping. This looks for the *first* port mapping (e.g., 80/tcp)
    docker inspect ${service_name} \
        --format '{{range $p, $conf := .NetworkSettings.Ports}} {{if eq $p "80/tcp"}}{{range .}}{{if .HostPort}}{{.HostPort}}{{end}}{{end}}{{end}}{{end}}' 2>/dev/null || echo "N/A"
}


# --- Main Logic ---

echo "--- Live Container Status Check ---"
echo "Target Domain: ${VIRTUAL_HOST_DOMAIN}"
echo "-----------------------------------"

BLUE_HOST=$(get_host_status ${WEB_SERVICE_BASE}-blue)
GREEN_HOST=$(get_host_status ${WEB_SERVICE_BASE}-green)

# Check which container has the active VIRTUAL_HOST
if [ "${BLUE_HOST}" = "${VIRTUAL_HOST_DOMAIN}" ]; then
    LIVE_CONTAINER="${WEB_SERVICE_BASE}-blue"
    INACTIVE_CONTAINER="${WEB_SERVICE_BASE}-green"
elif [ "${GREEN_HOST}" = "${VIRTUAL_HOST_DOMAIN}" ]; then
    LIVE_CONTAINER="${WEB_SERVICE_BASE}-green"
    INACTIVE_CONTAINER="${WEB_SERVICE_BASE}-blue"
else
    echo "⚠️ WARNING: No container (blue or green) currently has the VIRTUAL_HOST set to '${VIRTUAL_HOST_DOMAIN}'."
    echo "   This is expected during an initial deployment or after a full cleanup."
    LIVE_CONTAINER="none"
fi

# --- Output Results ---

if [ "${LIVE_CONTAINER}" != "none" ]; then
    LIVE_PORT=$(get_host_port ${LIVE_CONTAINER} | xargs) # xargs removes leading/trailing spaces

    echo "✅ LIVE Container (serving traffic): **${LIVE_CONTAINER}**"
    echo "🌐 Exposed Host Port: **${LIVE_PORT:-'N/A'}**" # Use N/A if port is empty

    # Get the inactive container's port for comparison/info
    INACTIVE_PORT=$(get_host_port ${INACTIVE_CONTAINER} | xargs)
    echo "   Inactive Container (${INACTIVE_CONTAINER}): Port ${INACTIVE_PORT:-'N/A'}"

    echo ""
    echo "👉 **Command to log into the LIVE container:**"
    echo "docker exec -it ${LIVE_CONTAINER} /bin/bash"
else
    # Output status for the undefined state
    BLUE_PORT=$(get_host_port ${WEB_SERVICE_BASE}-blue | xargs)
    GREEN_PORT=$(get_host_port ${WEB_SERVICE_BASE}-green | xargs)

    echo "Status Summary:"
    echo " - ${WEB_SERVICE_BASE}-blue Port: ${BLUE_PORT:-'N/A'}"
    echo " - ${WEB_SERVICE_BASE}-green Port: ${GREEN_PORT:-'N/A'}"
    echo ""
    echo "👉 To run a command against a known container, e.g., blue:"
    echo "docker exec -it ${WEB_SERVICE_BASE}-blue /bin/bash"
fi

echo "-----------------------------------"
