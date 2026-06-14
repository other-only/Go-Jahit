FROM php:8.2-fpm-alpine AS build

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    unzip \
    git \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    sqlite-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo pdo_mysql pdo_sqlite \
        mbstring exif pcntl bcmath gd zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY . .

RUN echo "memory_limit=256M" > /usr/local/etc/php/conf.d/app.ini \
    && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "max_input_vars=3000" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/app.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/app.ini

RUN composer install --prefer-dist --optimize-autoloader --no-interaction --no-dev

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache public

COPY .docker/nginx.conf /etc/nginx/http.d/default.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .docker/start.sh /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
