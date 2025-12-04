#!/bin/sh
set -e

echo "Running production application setup..."

# Remove hot file to force production assets
if [ -f "/var/www/html/public/hot" ]; then
    rm /var/www/html/public/hot
    echo "Removed public/hot to force production asset usage."
fi

# Clear caches and run essential setup
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Create storage symlink
php artisan storage:link

echo "Application setup complete. Starting Apache web server..."
exec apache2-foreground
