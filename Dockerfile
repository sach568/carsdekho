# WORKING Laravel Dockerfile for Render
FROM php:8.2-apache

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions (only essential ones)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    bcmath \
    exif

# 3. Install GD if needed for images
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

# 4. Enable Apache rewrite
RUN a2enmod rewrite

# 5. Set working directory
WORKDIR /var/www/html

# 6. Copy application
COPY . .

# 7. Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 8. Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && chmod -R 775 storage bootstrap/cache

# 10. Configure Apache to serve from public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 11. Expose port 80
EXPOSE 80

# 12. Start command
CMD sh -c "php artisan key:generate --force && php artisan config:cache && apache2-foreground"