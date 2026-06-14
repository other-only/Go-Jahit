#!/bin/sh

set -e

if [ ! -f .env ]; then
    cp .env.example .env 2>/dev/null || true
fi

chown -R www-data:www-data storage bootstrap/cache database public 2>/dev/null || true
chmod -R 775 storage bootstrap/cache database public 2>/dev/null || true

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

touch storage/logs/laravel.log database/database.sqlite 2>/dev/null || true
chmod 664 storage/logs/laravel.log database/database.sqlite 2>/dev/null || true

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force 2>/dev/null || true
fi

php artisan storage:link --force 2>/dev/null || true

php artisan migrate --force 2>/dev/null || true

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
