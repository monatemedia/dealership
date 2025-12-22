#!/bin/bash
set -e

echo "Running application setup..."

# --- 1. Database Wait, Migration, and Seeding ---

ENV_FILE="/var/www/html/.env"

if [ -f "$ENV_FILE" ]; then
    echo "Loading environment variables from $ENV_FILE using 'sed'..."

    # Safely source .env variables using 'sed' to filter out comments/empty lines
    # This sources clean KEY=VALUE lines into the shell environment.
    # The 'eval export' is safer than simply 'export', as it handles values with spaces correctly.
    eval "$(sed -r 's/^#.*$//g; /^\s*$/d' "$ENV_FILE")"

    echo "Environment variables exported to shell."
fi

# Set the literals here to ensure consistency (these use environment variables now set by 'eval')
DB_DATABASE=${DB_DATABASE:-actuallyfind_db}
DB_USERNAME=${DB_USERNAME:-ACTUAL_PROD_DB_USER}
DB_HOST=${DB_HOST:-actuallyfind-db}
DB_PORT=${DB_PORT:-5432}

echo "Waiting for database ($DB_DATABASE) at $DB_HOST:$DB_PORT to be ready..."

# DB Wait Check (using PGPASSWORD which is now sourced from .env)
# Using POSIX-compliant single brackets [ ] and safe 'break' logic
while true; do
    # Note: PGPASSWORD must be exported before pg_isready is called
    export PGPASSWORD="${DB_PASSWORD}"
    pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -d "$DB_DATABASE" -t 1
    if [ $? -eq 0 ]; then
        break
    fi
    echo "Database not ready, sleeping for 2 seconds..."
    sleep 2
done
echo "Database ready..."

# --- 2. Application Setup ---

# Remove hot file to force production assets
if [ -f "/var/www/html/public/hot" ]; then
    rm /var/www/html/public/hot
    echo "Removed public/hot to force production asset usage."
fi

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Create storage symlink
php artisan storage:link
echo "Application setup complete. Starting Apache web server..."

# Instead of: exec apache2-foreground
# Use "$@" which means "execute whatever command was passed by docker-compose"
exec "$@"
