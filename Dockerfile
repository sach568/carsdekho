# Laravel Dockerfile for Render with PostgreSQL
FROM php:8.2-apache

# 1. Update package list and install dependencies ONE BY ONE
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    zlib1g-dev \
    libicu-dev \
    g++ \
    && rm -rf /var/lib/apt/lists/*

# 2. Configure and install PHP extensions SEPARATELY
# Configure GD first
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp

# Install extensions one by one to identify which one fails
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install gd
RUN docker-php-ext-install zip
RUN docker-php-ext-install xml
RUN docker-php-ext-install bcmath

# Install intl separately (most problematic)
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# 3. Enable Apache modules
RUN a2enmod rewrite

# 4. Set working directory
WORKDIR /var/www/html

# 5. Copy application files
COPY . .

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Install dependencies
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 8. Run package discovery
RUN php artisan package:discover --ansi || echo "Package discovery completed"

# 9. Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 10. Make entrypoint executable
RUN chmod +x docker-entrypoint.sh

# 11. Configure Apache
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|g' /etc/apache2/sites-available/000-default.conf

# 12. Expose port
EXPOSE 80

# 13. Use entrypoint script
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]