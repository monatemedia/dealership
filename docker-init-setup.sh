#!/bin/sh
# docker-init-setup.sh
set -e

ENV_FILE="/var/www/html/.env"

if [ ! -f "$ENV_FILE" ]; then
    echo "ERROR: .env file not found at $ENV_FILE. Aborting setup."
    exit 1
fi

echo "Loading environment variables from $ENV_FILE..."

# Function to safely extract and export a variable from the .env file
safe_export() {
    local key="$1"
    # Use grep to find the key, strip leading/trailing whitespace, remove comments,
    # and then extract the value. Uses sed to handle quoted/unquoted values.
    local value=$(grep "^${key}=" "$ENV_FILE" | head -n 1 | sed -E 's/^[[:space:]]*[^=]+=//' | sed -E 's/^[[:space:]]*//' | sed -E 's/[[:space:]]*$//' | sed -E 's/^"|"$//g')

    # Export the variable
    export "$key=$value"
    # We echo a generic message for security
    if [ "$key" != "DB_PASSWORD" ]; then
        echo "Exported $key"
    else
        echo "Exported $key (Secret)"
    fi
}

# --- Export Required Variables ---
safe_export "DB_CONNECTION"
safe_export "DB_HOST"
safe_export "DB_PORT"
safe_export "DB_PASSWORD"
# Also export APP_KEY as migrations often require it for encryption
safe_export "APP_KEY"

# Set the literals here to GUARANTEE they match the DB container setup
DB_DATABASE="actuallyfind_db"
DB_USERNAME="ACTUAL_PROD_DB_USER"

echo "Environment variables exported to shell."

# --- Proceed with DB Wait Logic (Must be correct for pg_isready to pass) ---
DB_HOST=${DB_HOST:-actuallyfind-db}
DB_PORT=${DB_PORT:-5432}
DB_NAME=${DB_DATABASE}
DB_USER=${DB_USERNAME}

echo "Waiting for database ($DB_NAME) at $DB_HOST:$DB_PORT to be ready..."

while true; do
    PGPASSWORD=${DB_PASSWORD} pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" -t 1
    if [ $? -eq 0 ]; then
        break
    fi
    echo "Database not ready or still in recovery mode, sleeping for 2 seconds..."
    sleep 2
done

echo "Database ready. Running essential setup..."

# 1. Run Migrations
echo "    INFO  Running Migrations..."
# CRITICAL FIX: Removed DB_PASSWORD from env call
env \
    DB_CONNECTION="$DB_CONNECTION" \
    DB_HOST="$DB_HOST" \
    DB_PORT="$DB_PORT" \
    DB_DATABASE="$DB_DATABASE" \
    DB_USERNAME="$DB_USERNAME" \
    APP_KEY="$APP_KEY" \
    php artisan migrate --force

# 2. Run Essential Seeders (The slow ones)
echo "    INFO  Running Essential db:seed..."
# CRITICAL FIX: Removed DB_PASSWORD from env call
env \
    DB_CONNECTION="$DB_CONNECTION" \
    DB_HOST="$DB_HOST" \
    DB_PORT="$DB_PORT" \
    DB_DATABASE="$DB_DATABASE" \
    DB_USERNAME="$DB_USERNAME" \
    APP_KEY="$APP_KEY" \
    php artisan db:seed --force

echo "Essential setup script finished."
