#!/bin/sh
set -e

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

# Start supervisord (nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
