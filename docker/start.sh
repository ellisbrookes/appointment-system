#!/bin/bash
set -e

echo "🚀 Starting Appointment System Alpha..."

# Set environment variables with defaults
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}

# Clear any existing cache files
echo "🧹 Clearing cache files..."
rm -f bootstrap/cache/packages.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes-*.php
rm -f bootstrap/cache/events.php

# Wait for database to be ready
echo "📡 Waiting for database connection..."
echo "🔍 Connection details: host=$DB_HOST, port=$DB_PORT, database=$DB_DATABASE, username=$DB_USERNAME"
max_attempts=30
attempt=1
while [ $attempt -le $max_attempts ]; do
    if php -r "try { new PDO('mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD'); exit(0); } catch (Exception \$e) { echo 'PDO Error: ' . \$e->getMessage() . PHP_EOL; exit(1); }"; then
        echo "✅ Database connection established"
        break
    fi
    echo "⏳ Attempt $attempt/$max_attempts - Database not ready, waiting..."
    sleep 5
    attempt=$((attempt + 1))
done

if [ $attempt -gt $max_attempts ]; then
    echo "❌ Failed to connect to database after $max_attempts attempts"
    exit 1
fi

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Clear and cache configurations for production
echo "⚡ Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
# Skip view:cache for now due to missing components
# php artisan view:cache

# Clear view cache to prevent 500 errors from stale cached Blade templates
echo "🧹 Clearing view cache to prevent template errors..."
php artisan view:clear
php artisan cache:clear

# Create storage symlink if it doesn't exist
if [ ! -L /var/www/html/public/storage ]; then
    echo "🔗 Creating storage symlink..."
    php artisan storage:link
fi

# Set proper permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Create health check endpoint
echo "💚 Setting up health check..."
cat > /var/www/html/public/health <<'EOF'
<?php
echo json_encode([
    'status' => 'healthy',
    'timestamp' => date('c'),
    'app' => 'Appointment System Alpha'
]);
?>
EOF

# Start supervisord which will manage Apache and other processes
echo "🎯 Starting application services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
