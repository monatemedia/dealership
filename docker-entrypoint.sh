#!/bin/sh
set -e

echo "Running application setup..."

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

# Seed database if enabled
if [ "$SEED_DATABASE" = "true" ]; then
    echo "    INFO  Seeding database..."
    php artisan db:seed --force
    echo "    INFO  Seeding complete."
fi

# Import data to Typesense
echo "    INFO  Importing data to Typesense..."
php artisan typesense:import
echo "    INFO  Typesense import complete."

echo "Application setup complete. Starting Apache web server..."
exec apache2-foreground
