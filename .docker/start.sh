#!/usr/bin/env sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

chmod -R 777 storage bootstrap/cache 2>/dev/null || true
mkdir -p storage/logs storage/framework/views storage/framework/cache storage/framework/sessions storage/framework/testing storage/app/private/produk storage/app/private/toko storage/app/private/detail 2>/dev/null || true
touch storage/logs/laravel.log 2>/dev/null || true
chmod 666 storage/logs/laravel.log 2>/dev/null || true

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan config:clear 2>/dev/null || true
php artisan storage:link --force 2>/dev/null || true
php artisan migrate --force 2>/dev/null || true

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
