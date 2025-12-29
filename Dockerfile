FROM php:8.2-apache

# Install dependencies including SQLite
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev unzip curl sqlite3 \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (SQLite के लिए)
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring zip bcmath exif

# Enable Apache
RUN a2enmod rewrite

# Set workdir
WORKDIR /var/www/html

# Copy .env.example as .env before everything
COPY .env.example .env

# Copy app
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install packages
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create SQLite database file
RUN mkdir -p database && touch database/database.sqlite \
    && chmod 666 database/database.sqlite

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Fix Apache config
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# Start command
CMD php artisan key:generate --force && \
    php artisan migrate --force && \
    php artisan config:cache && \
    apache2-foreground