# ============================================
# STAGE 1: COMPOSER BUILDER
# ============================================
FROM composer:2 AS composer-builder
RUN apk add --no-cache git
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader
COPY . .
RUN composer dump-autoload --optimize --no-dev
# --------------------------------------------

# ============================================
# STAGE 2: NODE BUILDER
# ============================================
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production=false
COPY resources ./resources
COPY vite.config.js ./
COPY postcss.config.js* ./
COPY tailwind.config.js* ./
COPY public ./public
RUN npm run build
# --------------------------------------------

# ============================================
# STAGE 3: PRODUCTION (Official PHP Base Image) 🚀
# RUN block is split for stability and debugging.
# ============================================
FROM php:8.4-apache-bookworm AS final

# 1. Install ALL Dependencies (Runtime and Build)
RUN set -ex; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
    # Runtime/Base Dependencies
    libpq5 \
    libzip4 \
    dos2unix \
    # GD Dependencies (Development Libraries)
    libjpeg-dev \
    libpng-dev \
    libfreetype-dev \
    # Other Extension Dependencies
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    git \
    unzip;

# 2. Configure and Install PHP Extensions
# This step is isolated to ensure the dependencies from step 1 are available.
RUN set -ex; \
    # Install all extensions (GD will auto-detect installed dependencies)
    docker-php-ext-install -j$(nproc) \
    pdo pdo_pgsql mbstring exif pcntl bcmath gd zip intl;

# 3. Clean Up Build Dependencies and Apt Cache
# This step is isolated so failures here do not block the essential installation.
RUN set -ex; \
    # Remove only the -dev packages and build tools
    apt-get purge -y --auto-remove \
    libjpeg-dev \
    libpng-dev \
    libfreetype-dev \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    git \
    unzip; \
    \
    # Final Cleanup
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Copy dependencies and assets from builder stages
COPY --from=composer-builder /app/vendor ./vendor
COPY --from=node-builder /app/public/build ./public/build

# Custom Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN dos2unix /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh

# Set proper ownership and permissions for cache/storage
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Use custom entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]

# Start Apache
CMD ["apache2-foreground"]
