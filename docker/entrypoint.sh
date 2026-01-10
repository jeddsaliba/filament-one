#!/bin/sh
set -e

cd /var/www/html

# Install composer dependencies if missing (dev only)
if [ ! -d "vendor" ]; then
  echo "Installing composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Generate APP_KEY if missing
if ! grep -q "APP_KEY=" .env; then
  echo "Generating APP_KEY..."
  php artisan key:generate
fi

# Start PHP-FPM in foreground
exec php-fpm
