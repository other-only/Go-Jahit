#!/bin/sh

set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

chmod -R 777 storage bootstrap/cache database 2>/dev/null || true
mkdir -p storage/logs storage/framework/views storage/framework/cache storage/framework/sessions storage/framework/testing storage/app/private/produk storage/app/private/toko storage/app/private/detail 2>/dev/null || true
touch storage/logs/laravel.log database/database.sqlite 2>/dev/null || true
chmod 666 storage/logs/laravel.log database/database.sqlite 2>/dev/null || true

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan config:clear
php artisan storage:link --force
php artisan migrate --force

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
