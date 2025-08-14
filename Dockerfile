FROM php:8.2-fpm

# System deps (add libsqlite3-dev)
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libsqlite3-dev \
 && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd sockets \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
