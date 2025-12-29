# PHP + Apache base image
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libpq-dev curl \
    libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring gd exif

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first (for better caching)
COPY composer.json composer.lock ./

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy project files
COPY . .

# Create .env from example if not exists
RUN if [ ! -f .env ]; then \
    cp .env.example .env; \
    fi

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set Apache DocumentRoot to public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring gd exif
# Create entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose Apache port
EXPOSE 80

# Use entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]