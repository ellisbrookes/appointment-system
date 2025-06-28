#!/bin/bash

# Wait for database to be ready
echo "Waiting for database connection..."
until php artisan migrate --dry-run; do
    echo "Database not ready, waiting..."
    sleep 5
done

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache configurations for production
echo "Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink if it doesn't exist
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
fi

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Start supervisord which will manage Apache and other processes
echo "Starting application services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
