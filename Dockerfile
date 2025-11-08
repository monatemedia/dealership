# ============================================
# Composer Stage - For PHP dependencies
# ============================================
FROM composer:2 AS composer-builder
WORKDIR /app

# Copy composer files (Less frequent changes layer)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Copy application code for autoload optimization
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize --no-dev

# --------------------------------------------
# ============================================
# Build Stage - For compiling assets
# ============================================
FROM node:20-alpine AS node-builder
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install Node dependencies
RUN npm ci --only=production=false

# Copy source files needed for build (More frequent changes layer)
COPY resources ./resources
COPY vite.config.js ./
COPY postcss.config.js* ./
COPY tailwind.config.js* ./
COPY public ./public

# Build assets
RUN npm run build

# --------------------------------------------
# ============================================
# Production Stage - Final lightweight image
# ============================================
FROM php:8.4-apache

# 🚀 FINAL BULLETPROOF FIX: Explicitly install ALL runtime libraries before building/purging.
RUN set -ex; \
    # 1. Update APT lists
    apt-get update; \
    \
    # 2. Install **ALL REQUIRED LIBRARIES** (Runtime AND Build-time headers)
    apt-get install -y --no-install-recommends \
    # Explicitly list Runtime Libraries to protect them from auto-remove
    libpq5 \
    libpng16-16t64 \
    libicu76 \
    libzip5 \
    \
    # List Build Headers required for compilation (these will be purged)
    libpq-dev \
    libpng-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    \
    # Other essential tools
    git \
    unzip \
    ; \
    \
    # 3. Compile and Install PHP Extensions
    docker-php-ext-install \
    pdo pdo_pgsql mbstring exif pcntl bcmath gd zip intl \
    ; \
    \
    # 4. Clean up **BUILD HEADERS** only.
    # The runtimes are now protected because they were explicitly installed in step 2.
    apt-get purge -y --auto-remove \
    libpq-dev \
    libpng-dev \
    libicu-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    # Only remove build-time tools
    ; \
    \
    # 5. Final Cleanup of APT Cache
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

# FIX: Copy entrypoint, install dos2unix, fix line endings, set permission, and uninstall (Atomic Layer)
COPY docker-entrypoint.sh /usr/local/bin/
RUN apt-get update && apt-get install -y dos2unix && \
    dos2unix /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh && \
    apt-get purge -y --auto-remove dos2unix && \
    rm -rf /var/lib/apt/lists/*

# Set proper ownership and permissions for cache/storage (775 for R/W by www-data user/group)
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Use custom entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]

# Start Apache
CMD ["apache2-foreground"]
