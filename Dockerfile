### Step 1: Node.js for frontend (Vite)
FROM node:18 AS node-builder

WORKDIR /app
COPY . .

RUN npm install && npm run build


### Step 2: PHP for Laravel backend
FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    sqlite3 libsqlite3-dev

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www
COPY --chown=www-data:www-data . /var/www

# Copy only built frontend assets (from Vite)
COPY --from=node-builder /app/public/build /var/www/public/build

RUN composer install
COPY .env.example .env
RUN php artisan key:generate

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
