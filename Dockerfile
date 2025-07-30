# Gunakan PHP resmi + Apache
FROM php:8.2-apache

# Install dependency Laravel
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Salin semua file Laravel ke /var/www/html
COPY . /var/www/html

# Pindah ke direktori Laravel
WORKDIR /var/www/html

# Install dependensi
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Set permission Laravel
RUN chmod -R 755 storage bootstrap/cache

# Expose port (Render pakai 10000 secara default)
EXPOSE 10000

# Jalankan Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
