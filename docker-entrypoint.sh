#!/bin/sh
set -e

echo "Running production application setup..."

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

# Run migrations
php artisan migrate --force

# Seed database if enabled (Only basic db:seed is left)
if [ "$SEED_DATABASE" = "true" ]; then
    echo "    INFO  Seeding database..."
    php artisan db:seed --force
    echo "    INFO  Seeding complete."
fi

# ⚠️ REMOVED: Conditional Demo/Dev Setup logic (db:demo and typesense:import)

echo "Application setup complete. Starting Apache web server..."
exec apache2-foreground
