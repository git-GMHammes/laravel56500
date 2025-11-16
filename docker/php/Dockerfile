FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip vim \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && mkdir -p /var/www/.config /var/www/.composer \
    && chown -R www-data:www-data /var/www/.config /var/www/.composer \
    && chmod -R 755 /var/www/.config /var/www/.composer

USER www-data

CMD ["php-fpm"]