FROM php:8.2-fpm

COPY ./php.ini /usr/local/etc/php/conf.d/php-custom.ini
RUN docker-php-ext-install pdo_mysql

WORKDIR /data

VOLUME /data

CMD ["php-fpm"]
