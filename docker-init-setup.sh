#!/bin/sh
# docker-init-setup.sh
set -e

# --- CRITICAL FIX: Load ALL secrets from the mounted .env file ---
# This forces the shell to load DB_PASSWORD, APP_URL, etc.,
# which prevents Laravel from using an empty/default value.

# Check if the .env file exists and load it
ENV_FILE="/var/www/html/.env"
if [ -f "$ENV_FILE" ]; then
    echo "Loading environment variables from $ENV_FILE..."
    # Read each line and export non-commented, non-empty key=value pairs
    # Note: This is a safe way to source environment variables without eval
    while IFS= read -r line || [[ -n "$line" ]]; do
        # Ignore comments and empty lines
        [[ "$line" =~ ^#.* ]] || [[ -z "$line" ]] && continue

        # Check for key=value and export it
        key=$(echo "$line" | cut -d'=' -f1)
        value=$(echo "$line" | cut -d'=' -f2-)
        if [[ -n "$key" && -n "$value" ]]; then
            export "$key=$value"
        fi
    done < "$ENV_FILE"
    echo "Environment variables exported to shell."
else
    echo "ERROR: .env file not found at $ENV_FILE. Aborting setup."
    exit 1
fi

# --- Environment Variables (Now guaranteed to be set) ---
DB_HOST=${DB_HOST:-actuallyfind-db}
DB_PORT=${DB_PORT:-5432}
DB_NAME=${DB_DATABASE}
DB_USER=${DB_USERNAME}

echo "Waiting for database ($DB_NAME) at $DB_HOST:$DB_PORT to be ready..."

# The DB wait loop uses PGPASSWORD, which is now guaranteed to be set by the sourced .env file
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
# php artisan now runs with the full environment exported, including DB_PASSWORD
php artisan migrate --force

# 2. Run Essential Seeders (The slow ones)
echo "    INFO  Running Essential db:seed..."
php artisan db:seed --force

echo "Essential setup script finished."
