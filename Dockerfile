# Stage 1: Composer dependencies
FROM php:8.2-cli-alpine AS composer-stage

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache git unzip icu-dev libzip-dev libpng-dev freetype-dev libjpeg-turbo-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl bcmath zip gd xml

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev

# Stage 2: Build frontend assets
FROM node:18-alpine AS node-stage

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run production

# Stage 3: Production image
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    libxml2-dev \
    oniguruma-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        gd \
        zip \
        bcmath \
        intl \
        mbstring \
        xml \
        opcache

# OPcache configuration
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

# Copy configs
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy application
COPY --from=composer-stage /app/vendor ./vendor
COPY . .
COPY --from=node-stage /app/public/js ./public/js
COPY --from=node-stage /app/public/css ./public/css
COPY --from=node-stage /app/public/mix-manifest.json ./public/mix-manifest.json

# Clean up dev/unnecessary files
RUN rm -rf node_modules tests .github htdocs \
    && rm -f .env .env.example

# Create directories and set permissions
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    public/documents \
    && chown -R www-data:www-data storage bootstrap/cache public/documents \
    && chmod -R 775 storage bootstrap/cache public/documents

COPY docker/entrypoint.sh /entrypoint.sh

EXPOSE 80

CMD ["/entrypoint.sh"]
