#!/bin/sh

set -e

if [ ! -f .env ]; then
    cp .env.example .env 2>/dev/null || true
fi

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force 2>/dev/null || true
fi

php artisan storage:link --force 2>/dev/null || true

php artisan migrate --force

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
