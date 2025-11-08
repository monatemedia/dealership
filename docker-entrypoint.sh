#!/bin/sh
set -e

echo "Running application setup..."

# 💥 FIX: Ensure Laravel uses static assets, not the Vite HMR server
if [ -f "/var/www/html/public/hot" ]; then
    rm /var/www/html/public/hot
    echo "Removed public/hot to force production asset usage."
fi

# Run Laravel setup
php artisan migrate --force

# --- ADDED LOGIC FOR SEEDING ---
if [ "$SEED_DATABASE" = "true" ]; then
    echo "    INFO  Seeding database..."
    php artisan db:seed --force
    echo "    INFO  Seeding complete."
fi
# ---------------------------------

php artisan config:cache
php artisan route:cache
php artisan view:cache # This command is critical for staging/prod

echo "Starting web server..."
# Execute the main container command (apache2-foreground)
exec "$@"
