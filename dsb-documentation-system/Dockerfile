FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl zip \
    libzip-dev libpng-dev \
    libonig-dev libxml2-dev \
    nginx \
    && docker-php-ext-install \
    zip pdo_mysql mbstring \
    exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm install && npm run build

RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 10000

CMD bash -c "php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan storage:link && \
    php-fpm -D && \
    nginx -g 'daemon off;'"