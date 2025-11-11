#!/bin/sh
set -e

echo "Running application setup..."

# 💥 FIX: Ensure Laravel uses static assets, not the Vite HMR server
if [ -f "/var/www/html/public/hot" ]; then
    rm /var/www/html/public/hot
    echo "Removed public/hot to force production asset usage."
fi

# Run Laravel setup
# We will clear all caches (config, route, view) here to ensure
# fresh variables and routing tables are loaded before Apache starts.
php artisan config:clear
php artisan route:clear  # ADDED: Clear cached routes
php artisan view:clear   # ADDED: Clear cached views

php artisan migrate --force

# --- ADDED LOGIC FOR SEEDING ---
if [ "$SEED_DATABASE" = "true" ]; then
    echo "    INFO  Seeding database..."
    php artisan db:seed --force
    echo "    INFO  Seeding complete."
fi
# ---------------------------------

# REPLACED: exec "$@" with the direct call to the Apache foreground process.
echo "Application setup complete. Starting Apache web server..."
exec apache2-foreground
