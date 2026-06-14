#!/bin/sh

set -e

if [ ! -f .env ]; then
    cp .env.example .env 2>/dev/null || true
fi

chmod -R 775 storage bootstrap/cache 2>/dev/null || true

if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite 2>/dev/null || true
fi

chmod 664 database/database.sqlite 2>/dev/null || true

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force 2>/dev/null || true
fi

php artisan storage:link --force 2>/dev/null || true

php artisan migrate --force 2>/dev/null || true

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
