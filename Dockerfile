# استفاده از image پایه PHP با PHP-FPM
FROM php:8.2-fpm

# نصب dependencies لازم برای لاراول
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# پاکسازی cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# نصب PHP extensions مورد نیاز
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# نصب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تنظیم دایرکتوری کار
WORKDIR /var/www/html

# کپی فایل‌های پروژه لاراول
COPY src/ .

# نصب dependencies پروژه
RUN composer install --no-dev --optimize-autoloader

# تنظیم مجوزهای لازم برای لاراول
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache