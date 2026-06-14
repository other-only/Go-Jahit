#!/usr/bin/env sh

if [ ! -f .env ]; then
    cp .env.example .env
fi

chmod -R 777 storage bootstrap/cache 2>/dev/null
mkdir -p storage/logs storage/framework/views storage/framework/cache storage/framework/sessions storage/framework/testing storage/app/private/produk storage/app/private/toko storage/app/private/detail storage/app/public/toko storage/app/public/produk storage/app/public/detail storage/app/public/bukti_pembayaran 2>/dev/null
touch storage/logs/laravel.log 2>/dev/null
chmod 666 storage/logs/laravel.log 2>/dev/null

rm -f bootstrap/cache/packages.php bootstrap/cache/services.php 2>/dev/null

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force 2>/dev/null
fi

php artisan config:clear 2>/dev/null
php artisan storage:link --force 2>/dev/null
php artisan migrate --force 2>/dev/null

# Migrate old uploads from private to public disk
for dir in toko produk detail bukti_pembayaran; do
    if [ -d "storage/app/private/$dir" ] && [ "$(ls -A storage/app/private/$dir 2>/dev/null)" ]; then
        cp -r storage/app/private/$dir/* storage/app/public/$dir/ 2>/dev/null
    fi
done

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
