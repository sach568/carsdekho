FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev unzip curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip bcmath exif

# Install SQLite
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy .env.example as .env
COPY .env.example .env

# Copy app
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create SQLite database file
RUN mkdir -p database && touch database/database.sqlite \
    && chmod 666 database/database.sqlite

# Create sessions table manually (IMPORTANT)
RUN sqlite3 database/database.sqlite "CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);"

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# Run ALL migrations, not just migrate
CMD php artisan key:generate --force && \
    php artisan migrate:fresh --force && \
    php artisan config:cache && \
    apache2-foreground