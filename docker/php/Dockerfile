FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libpq-dev libzip-dev libicu-dev \
    libxml2-dev zlib1g-dev \
    libonig-dev \
    && docker-php-ext-install \
    pdo pdo_pgsql zip intl mbstring xml ctype opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www