# Étape 1: Build des assets frontend
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Étape 2: Application PHP
FROM php:8.2-fpm AS app
WORKDIR /var/www/html

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie des fichiers du projet
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Installation des dépendances PHP
RUN composer install --no-interaction --no-dev --optimize-autoloader
RUN composer require laravel/pail --dev

# Configuration des permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Optimisation pour la production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Configuration du PHP-FPM
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Healthcheck
HEALTHCHECK --interval=30s --timeout=3s --retries=3 \
    CMD php artisan health:check || exit 1

EXPOSE 9000
CMD ["php-fpm"]
