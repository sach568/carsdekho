#!/bin/bash
# Fix composer and package discovery issues

echo "Fixing composer issues..."

# Increase memory limit
export COMPOSER_MEMORY_LIMIT=-1

# Install without scripts first
composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Run package discovery separately
php artisan package:discover --ansi

echo "Composer fix completed!"