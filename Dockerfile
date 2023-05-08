FROM php:7.4-apache as base

COPY . /var/www

RUN chown www-data /var/www/data/*

# install composer dependencies
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip

# install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# resolve PHP dependencies with Composer
WORKDIR /var/www
RUN composer install --no-dev
