#!/bin/sh
# docker-init-setup.sh
set -e

# --- Environment Variables (Assumed to be set in docker-compose.yml) ---
# DB_HOST: The database service name (e.g., actuallyfind-db)
# DB_PORT: The database port (e.g., 5432)
# DB_DATABASE: The target database name
# DB_USERNAME: The user for the database
# DB_PASSWORD: The password for the user

DB_HOST=${DB_HOST:-actuallyfind-db}
DB_PORT=${DB_PORT:-5432}
DB_NAME=${DB_DATABASE}
DB_USER=${DB_USERNAME}

echo "Waiting for database ($DB_NAME) at $DB_HOST:$DB_PORT to be ready..."

# The fix: Separate PGPASSWORD assignment and use a standard loop format.
# We run the command inside the loop to ensure PGPASSWORD is set for each check.
while true; do
    # Run pg_isready command, supplying the password via PGPASSWORD variable
    PGPASSWORD=${DB_PASSWORD} pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" -t 1

    # Check the exit code of pg_isready (0 means ready)
    if [ $? -eq 0 ]; then
        break
    fi

    echo "Database not ready or still in recovery mode, sleeping for 2 seconds..."
    sleep 2
done

echo "Database ready. Running essential setup..."

# 1. Run Migrations
echo "    INFO  Running Migrations..."
php artisan migrate --force

# 2. Run Essential Seeders (The slow ones)
echo "    INFO  Running Essential db:seed..."
php artisan db:seed --force # This runs the large seeders

echo "Essential setup script finished."
