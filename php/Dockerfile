ARG PHP_IMAGE_TAG=fpm

FROM php:${PHP_IMAGE_TAG}

ARG COMPOSER_DOWNLOAD_URL="https://getcomposer.org/download/latest-stable/composer.phar"

ADD ${COMPOSER_DOWNLOAD_URL} /usr/local/bin/composer

RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && chmod +x /usr/local/bin/composer

VOLUME [ "/var/www/html" ]
WORKDIR /var/www/html