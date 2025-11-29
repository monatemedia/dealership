#!/bin/sh
set -e

# Wait for Database using the reliable /dev/tcp check
echo "Waiting for database connection (dealership-db:5432)..."
until (exec 6<>/dev/tcp/dealership-db/5432) 2>/dev/null; do
    echo "Database not ready, sleeping for 1 second..."
    sleep 1
done
echo "Database ready. Running demo setup..."

# 1. Run demo seed to create 10,000 fake listings
echo "    INFO  Running db:demo (100 records)..."
# 💡 Each round creates 100 fake listings. Adjust --count as needed.
php artisan db:demo --count=100
echo "    INFO  db:demo complete."

# 2. Run Typesense import after the data exists
echo "    INFO  Importing data to Typesense..."
php artisan typesense:create-collections --force --import
echo "    INFO  Typesense import complete."

echo "Demo setup script finished."
