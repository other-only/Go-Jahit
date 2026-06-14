#!/bin/sh

set -e

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Run migrations
php artisan migrate --force

# Start supervisor
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
