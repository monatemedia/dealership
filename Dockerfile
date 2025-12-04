# ============================================
# STAGE 1: COMPOSER BUILDER
# ============================================
FROM composer:2 AS composer-builder
RUN apk add --no-cache git
WORKDIR /app

# 💡 NEW: Define a build argument with a default value of 'false'
ARG INSTALL_DEV_DEPENDENCIES=false

COPY composer.json composer.lock ./

# Conditionally set the COMPOSER_INSTALL_FLAGS
# This uses a simple shell ternary operator: [ condition ] && true_value || false_value
# If INSTALL_DEV_DEPENDENCIES is NOT 'true', we use '--no-dev'
RUN COMPOSER_INSTALL_FLAGS="--no-scripts --no-interaction --prefer-dist --optimize-autoloader"; \
    if [ "$INSTALL_DEV_DEPENDENCIES" != "true" ]; then \
    COMPOSER_INSTALL_FLAGS="$COMPOSER_INSTALL_FLAGS --no-dev"; \
    fi; \
    echo "Composer install flags: $COMPOSER_INSTALL_FLAGS"; \
    # FIX 1: Added --ignore-platform-reqs to bypass the PHP version check in the builder image.
    composer install --ignore-platform-reqs $COMPOSER_INSTALL_FLAGS

COPY . .

# Pass the flag to dump-autoload as well for strict consistency
RUN DUMP_AUTOLOAD_FLAGS="--optimize"; \
    if [ "$INSTALL_DEV_DEPENDENCIES" != "true" ]; then \
    DUMP_AUTOLOAD_FLAGS="$DUMP_AUTOLOAD_FLAGS --no-dev"; \
    fi; \
    composer dump-autoload $DUMP_AUTOLOAD_FLAGS
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
# Includes GD fix and image optimization tools.
# ============================================
FROM php:8.4-apache-bookworm AS final

# 1. Install System Dependencies
RUN set -ex; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
    # Standard Runtime Dependencies
    libpq5 \
    libzip4 \
    dos2unix \
    \
    # Add postgresql-client for pg_isready
    postgresql-client \
    \
    # Image Optimization CLI Tools
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    webp \
    \
    # GD Runtime Libraries (these must stay)
    libjpeg62-turbo \
    libpng16-16 \
    libfreetype6 \
    libwebp7 \
    \
    # Build Dependencies (will be removed)
    libjpeg-dev \
    libpng-dev \
    libfreetype-dev \
    libwebp-dev \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    git \
    unzip;

# 2. Configure and Install PHP Extensions
RUN set -ex; \
    docker-php-ext-configure gd \
    --with-freetype=/usr \
    --with-jpeg=/usr \
    --with-webp=/usr; \
    docker-php-ext-install -j$(nproc) \
    pdo pdo_pgsql mbstring exif pcntl bcmath gd zip intl; \
    \
    # Verify GD was compiled correctly
    php -m | grep -q gd || (echo "ERROR: GD extension failed to compile" && exit 1);

# 3. Clean Up Build Dependencies Only
RUN set -ex; \
    # Mark runtime libraries to keep
    apt-mark manual \
    libjpeg62-turbo \
    libpng16-16 \
    libfreetype6 \
    libwebp7; \
    postgresql-client; \
    \
    # Remove build dependencies
    apt-get purge -y --auto-remove \
    libjpeg-dev \
    libpng-dev \
    libfreetype-dev \
    libwebp-dev \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    git \
    unzip; \
    \
    # Verify GD still works after cleanup
    php -m | grep -q gd || (echo "ERROR: GD extension broken after cleanup" && exit 1); \
    \
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

# Propagate the ARG to an ENV variable for the entrypoint script to read
ARG INSTALL_DEV_DEPENDENCIES=false
ENV INSTALL_DEV_DEPENDENCIES=$INSTALL_DEV_DEPENDENCIES

# Custom Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
# 💡 NEW: Copy the separate dev setup script
COPY docker-dev-setup.sh /usr/local/bin/

RUN dos2unix /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh && \
    # 💡 NEW: Permission the dev setup script
    dos2unix /usr/local/bin/docker-dev-setup.sh && \
    chmod +x /usr/local/bin/docker-dev-setup.sh

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
