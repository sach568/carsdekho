#!/bin/bash
set -e

echo "Starting Laravel application..."

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "Generating new APP_KEY..."
    php artisan key:generate --force
else
    echo "Using provided APP_KEY"
    # Update .env with provided APP_KEY
    sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
fi

# Update .env with Render environment variables
if [ ! -z "$APP_ENV" ]; then
    sed -i "s|^APP_ENV=.*|APP_ENV=${APP_ENV}|" .env
fi

if [ ! -z "$APP_DEBUG" ]; then
    sed -i "s|^APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG}|" .env
fi

if [ ! -z "$APP_URL" ]; then
    sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" .env
fi

# Database configuration
if [ ! -z "$DB_CONNECTION" ]; then
    sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=${DB_CONNECTION}|" .env
fi

if [ ! -z "$DB_HOST" ]; then
    sed -i "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|" .env
fi

if [ ! -z "$DB_PORT" ]; then
    sed -i "s|^DB_PORT=.*|DB_PORT=${DB_PORT}|" .env
fi

if [ ! -z "$DB_DATABASE" ]; then
    sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" .env
fi

if [ ! -z "$DB_USERNAME" ]; then
    sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" .env
fi

if [ ! -z "$DB_PASSWORD" ]; then
    sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
fi

# 1. Project folder में जाएं
cd /path/to/carsdekho

# 2. नई files create करें
touch .dockerignore docker-entrypoint.sh render.yaml

# 3. Files को ऊपर दिए गए content से भरें

# 4. APP_KEY generate करें
cp .env.example .env.local
php artisan key:generate --show
# Output copy करें
# Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground "$@"