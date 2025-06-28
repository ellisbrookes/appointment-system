#!/bin/bash
set -e

echo "🚀 Starting Appointment System Alpha..."

# Set environment variables with defaults
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}

# Wait for database to be ready
echo "📡 Waiting for database connection..."
max_attempts=30
attempt=1
while [ $attempt -le $max_attempts ]; do
    if php artisan migrate:status > /dev/null 2>&1; then
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
php artisan view:cache

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
