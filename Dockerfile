FROM alpine:3.20

RUN apk add --no-cache \
    php82 \
    php82-fpm \
    php82-pdo \
    php82-pdo_mysql \
    php82-mbstring \
    php82-gd \
    php82-opcache \
    php82-bcmath \
    php82-ctype \
    php82-curl \
    php82-dom \
    php82-fileinfo \
    php82-iconv \
    php82-json \
    php82-mysqli \
    php82-openssl \
    php82-phar \
    php82-session \
    php82-simplexml \
    php82-tokenizer \
    php82-xml \
    php82-xmlreader \
    php82-xmlwriter \
    php82-zip \
    php82-exif \
    php82-pcntl \
    php82-intl \
    nginx \
    supervisor \
    curl \
    unzip

RUN ln -s /usr/bin/php82 /usr/local/bin/php

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --prefer-dist --no-interaction --no-dev --no-scripts --no-autoloader

COPY . .

RUN composer install --prefer-dist --optimize-autoloader --no-interaction --no-dev --no-scripts

RUN echo "memory_limit=256M" > /etc/php82/conf.d/app.ini \
    && echo "upload_max_filesize=64M" >> /etc/php82/conf.d/app.ini \
    && echo "post_max_size=64M" >> /etc/php82/conf.d/app.ini \
    && echo "max_execution_time=300" >> /etc/php82/conf.d/app.ini \
    && echo "opcache.enable=1" >> /etc/php82/conf.d/app.ini \
    && echo "opcache.memory_consumption=128" >> /etc/php82/conf.d/app.ini \
    && echo "opcache.max_accelerated_files=10000" >> /etc/php82/conf.d/app.ini

RUN adduser -D -H -h /var/www/html -s /sbin/nologin www-data 2>/dev/null || true

RUN chmod -R 777 storage bootstrap/cache

COPY .docker/nginx.conf /etc/nginx/http.d/default.conf
COPY .docker/php-fpm-pool.conf /etc/php82/php-fpm.d/zz-app.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .docker/start.sh /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
