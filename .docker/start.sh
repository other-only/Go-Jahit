#!/bin/sh

set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

# Determine SQLite database path
DB_PATH="${DB_DATABASE:-database/database.sqlite}"
DB_DIR=$(dirname "$DB_PATH")

chmod -R 777 storage bootstrap/cache 2>/dev/null || true

# Ensure database directory exists and is writable
mkdir -p "$DB_DIR" 2>/dev/null || true
chmod 777 "$DB_DIR" 2>/dev/null || true
touch "$DB_PATH" 2>/dev/null || true
chmod 666 "$DB_PATH" 2>/dev/null || true

mkdir -p \
    storage/logs \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/testing \
    storage/app/private/produk \
    storage/app/private/toko \
    storage/app/private/detail \
    2>/dev/null || true

touch storage/logs/laravel.log 2>/dev/null || true
chmod 666 storage/logs/laravel.log 2>/dev/null || true

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan config:clear
php artisan storage:link --force
php artisan migrate --force

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
