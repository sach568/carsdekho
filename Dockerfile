FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev unzip curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions ONE BY ONE
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install exif

# Install SQLite separately
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

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