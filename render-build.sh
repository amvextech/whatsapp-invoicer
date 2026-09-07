#!/usr/bin/env bash
# exit on error
set -o errexit

# Install composer dependencies
composer install --no-dev --optimize-autoloader

# Create SQLite database file if it doesn't exist
touch database/database.sqlite

# Run database migrations
php artisan migrate --force

# Cache configuration and routes for speed
php artisan config:cache
php artisan route:cache
php artisan view:cache