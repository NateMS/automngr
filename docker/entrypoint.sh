#!/bin/sh
set -e

# Initialize storage directory if empty (e.g. from a fresh volume mount)
if [ -z "$(ls -A /var/www/html/storage 2>/dev/null)" ] && [ -d /var/www/storage-init ]; then
    echo "Initializing storage directory from backup..."
    cp -R /var/www/storage-init/. /var/www/html/storage
fi

# Create database if it doesn't exist
php -r "
\$pdo = new PDO('mysql:host='.getenv('DB_HOST').';port='.(getenv('DB_PORT')?:'3306'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
\$pdo->exec('CREATE DATABASE IF NOT EXISTS \`'.getenv('DB_DATABASE').'\`');
"

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions after artisan commands created files as root
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Clean up storage-init backup
rm -rf /var/www/storage-init

# Start supervisord (or whatever CMD was passed)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
