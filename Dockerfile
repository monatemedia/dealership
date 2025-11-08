# ============================================
# STAGE 1: COMPOSER BUILDER (PHP Dependency Installation)
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
# STAGE 2: NODE BUILDER (Frontend Asset Compilation)
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
# We are using the official image for maximum compatibility.
# ============================================
FROM php:8.4-apache-bookworm AS final

# 1. Install Dependencies and Cleanup
RUN set -ex; \
    apt-get update; \
    \
    # 1A. Install **Runtime Libraries** (These must REMAIN in the final image)
    apt-get install -y --no-install-recommends \
    libpq5 \
    libpng-tools \
    libzip4 \
    # Add other runtimes if needed, e.g., libonig5 for mbstring, libxml2, etc. \
    # For simplicity, let's keep the others in the build block for now: dos2unix
    dos2unix \
    ; \
    \
    # 1B. Install **Build Dependencies** (These are for compilation and can be removed later)
    apt-get install -y --no-install-recommends \
    libpq-dev \
    libpng-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    git \
    unzip \
    ; \
    \
    # 2. Compile and Install PHP Extensions
    docker-php-ext-install \
    pdo pdo_pgsql mbstring exif pcntl bcmath gd zip intl \
    ; \
    \
    # 3. Remove **Build Dependencies** (Only the -dev packages and build tools)
    apt-get purge -y --auto-remove \
    libpq-dev \
    libpng-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    git \
    unzip \
    ; \
    \
    # 4. Final Cleanup
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache DocumentRoot to point to Laravel's public directory
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

# FIX: dos2unix is still needed here, but it's now a system package
COPY docker-entrypoint.sh /usr/local/bin/
# dos2unix is installed in the previous RUN command
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
