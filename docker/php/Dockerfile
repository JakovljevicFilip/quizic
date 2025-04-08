FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    libjpeg-dev libfreetype6-dev libmcrypt-dev libssl-dev \
    acl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN curl -fsSL https://deb.nodesource.com/setup_14.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@6

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

CMD ["php-fpm"]
