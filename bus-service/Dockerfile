# Gunakan PHP versi 8.2 (sesuai standar Laravel terbaru)
FROM php:8.2-cli

# Install library yang dibutuhkan Laravel & Database
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer (Manajer paket PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set folder kerja di dalam container
WORKDIR /var/www

# Copy semua file project kamu ke dalam container
COPY . .

# Install dependencies project (vendor)
RUN composer install --no-interaction --optimize-autoloader

# Buka port 8000 (Port standar artisan serve)
EXPOSE 8000

# Perintah agar aplikasi menyala saat container dijalankan
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]