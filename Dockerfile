FROM serversideup/php:8.2-fpm-nginx

WORKDIR /var/www/html

COPY --chown=www-data:www-data composer.json composer.lock ./

RUN composer install --prefer-dist --no-interaction --no-dev --no-scripts --no-autoloader

COPY --chown=www-data:www-data . .

RUN composer install --prefer-dist --optimize-autoloader --no-interaction --no-dev --no-scripts

RUN chmod -R 777 storage bootstrap/cache

COPY --chown=www-data:www-data .docker/start.sh /usr/local/bin/start.sh

EXPOSE 80 443

CMD ["/usr/local/bin/start.sh"]
