# --- Stage 1: dependencies (composer) ----------------------------------------
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --optimize-autoloader --no-progress

# --- Stage 2: frontend assets (vite) -----------------------------------------
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci || npm install

COPY . .
RUN npm run build

# --- Stage 3: runtime --------------------------------------------------------
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
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd zip intl opcache exif \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# OPcache tuned for production
COPY docker/opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# No composer binary in the runtime image: the autoloader is already optimized
# (built in the vendor stage) and package discovery runs through artisan.
RUN php artisan package:discover --ansi --no-interaction \
    && mkdir -p /var/www/html/storage/framework/{cache,sessions,views} \
    && mkdir -p /var/www/html/storage/app/public \
    && ln -sfn /var/www/html/storage/app/public /var/www/html/public/storage \
    && chown -R www-data:www-data storage bootstrap/cache public/build public/storage \
    && chown -R www-data:www-data /var/www/html/storage/framework

USER www-data

EXPOSE 9000

CMD ["php-fpm"]