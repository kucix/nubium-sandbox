FROM php:8.1-fpm-alpine as base

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini $PHP_INI_DIR/conf.d/php.ini
COPY docker/php/fpm.conf $PHP_INI_DIR/../php-fpm.d/zz_www.conf
COPY . /app
RUN chmod -R 0777 /app/log /app/temp
WORKDIR /app
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN composer install --no-interaction --no-cache --prefer-dist --no-ansi --no-scripts --no-dev

ENV MYSQL_HOST="" \
    MYSQL_USER="" \
    MYSQL_PASSWORD="" \
    MYSQL_DATABASE=""

FROM base as dev

USER root
