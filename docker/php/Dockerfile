FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    libjpeg-dev libfreetype6-dev libmcrypt-dev libssl-dev \
    acl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

CMD ["php-fpm"]
