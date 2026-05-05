FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    autoconf \
    g++ \
    make \
    nodejs \
    npm

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    sockets

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/api

# ---- dev target -------------------------------------------------------------
FROM base AS dev

RUN pecl install xdebug && docker-php-ext-enable xdebug

EXPOSE 8000
CMD ["sh", "-c", "composer install && php artisan serve --host=0.0.0.0 --port=8000"]

# ---- prod target ------------------------------------------------------------
FROM base AS prod

COPY api/composer.json api/composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

COPY api/ ./
RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
