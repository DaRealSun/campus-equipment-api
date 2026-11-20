FROM php:8.4-fpm
RUN apt-get update && apt-get install -y --no-install-recommends git unzip libzip-dev && docker-php-ext-install pdo_mysql
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader && chown -R www-data:www-data storage bootstrap/cache
CMD ["sh", "-c", "php artisan migrate --force && php-fpm"]
