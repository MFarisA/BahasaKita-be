# ---- Base PHP Stage ----
FROM php:8.3-fpm-alpine AS base

# Install system dependencies needed for Laravel
RUN apk add --no-cache \
    curl \
    nginx \
    supervisor \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev

# Install PHP extensions for Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo_mysql \
    zip \
    bcmath \
    exif \
    intl

# Install Composer (PHP package manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# ---- Build Stage ----
FROM base AS build

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist

# Copy the rest of the application code
COPY . .

# Set correct permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Optimize Laravel for production
RUN composer dump-autoload --optimize && \
    php artisan optimize:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ---- Final Production Stage ----
FROM base AS production

# Copy the application with dependencies from the build stage
COPY --from=build /var/www/html /var/www/html

# Copy the Nginx configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy the Supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]