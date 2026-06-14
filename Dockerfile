FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx supervisor curl unzip libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql mbstring exif pcntl bcmath gd zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --prefer-dist --no-interaction --no-dev --no-scripts --no-autoloader

COPY --chown=www-data:www-data . .

RUN composer install --prefer-dist --optimize-autoloader --no-interaction --no-dev --no-scripts

RUN echo "memory_limit=256M" > /usr/local/etc/php/conf.d/app.ini \
    && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/app.ini

RUN chmod -R 777 storage bootstrap/cache

COPY .docker/nginx.conf /etc/nginx/http.d/default.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .docker/start.sh /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
