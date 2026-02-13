# Stage 1: Composer dependencies
FROM php:8.2-cli-alpine AS composer-stage

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN --mount=type=cache,target=/var/cache/apk \
    apk add git unzip icu-dev libzip-dev libpng-dev freetype-dev libjpeg-turbo-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) intl bcmath zip gd xml

WORKDIR /app

# Copy the entire application first so post-autoload-dump scripts
# (e.g. package:discover) have access to all files including artisan
COPY . .
RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

# Stage 2: Build frontend assets
FROM node:18-alpine AS node-stage

WORKDIR /app
COPY package.json package-lock.json ./
RUN --mount=type=cache,target=/root/.npm \
    npm ci

COPY . .
RUN npm run production

# Stage 3: Production image
FROM php:8.2-fpm-alpine

RUN --mount=type=cache,target=/var/cache/apk \
    apk add \
    nginx \
    supervisor \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    icu-data-full \
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

# PHP upload configuration
RUN echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/uploads.ini

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
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Copy application from composer stage (includes vendor + generated files)
COPY --from=composer-stage /app /var/www/html

# Copy built frontend assets
COPY --from=node-stage /app/public/js ./public/js
COPY --from=node-stage /app/public/css ./public/css
COPY --from=node-stage /app/public/mix-manifest.json ./public/mix-manifest.json

# Clean up dev/unnecessary files
RUN rm -rf node_modules tests .github htdocs \
    && rm -f .env .env.example

# Ensure the full storage directory structure exists and save a backup
# so it can be restored at runtime if a volume mount replaces storage/
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    public/documents \
    && cp -R storage /var/www/storage-init

# Set ownership for the entire application
RUN chown -R www-data:www-data /var/www/html /var/www/storage-init

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["supervisord"]
