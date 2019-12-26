FROM php:7.2-fpm-alpine

RUN apk add libpng-dev --update \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install gd