#!/bin/sh

set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

chown -R www-data:www-data storage bootstrap/cache database public
chmod -R 775 storage bootstrap/cache database public

mkdir -p \
    storage/logs \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/testing \
    storage/app/private/produk \
    storage/app/private/toko \
    storage/app/private/detail

touch storage/logs/laravel.log database/database.sqlite
chmod 664 storage/logs/laravel.log database/database.sqlite

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan storage:link --force
php artisan migrate --force

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
