FROM php:8.4-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libicu-dev \
    unzip \
    git \
    curl \
    gnupg \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd zip intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --no-interaction --prefer-dist --no-progress \
    || composer install --no-interaction --prefer-dist --no-progress

COPY docker/zz-fanora.conf /usr/local/etc/php-fpm.d/zz-fanora.conf

RUN chown -R www-data:www-data storage bootstrap/cache \
    && php artisan package:discover --ansi || true