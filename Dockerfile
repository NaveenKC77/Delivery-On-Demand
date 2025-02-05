# Use official PHP image with Apache
FROM php:8.2-apache

# Set working directory inside the container
WORKDIR /var/www/html

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install zip pdo pdo_mysql gd

    # ✅ Install Redis PHP extension
RUN pecl install redis && docker-php-ext-enable redis

# ✅ Fix "dubious ownership" by marking repo as safe
RUN git config --global --add safe.directory /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html

# Set proper permissions
RUN mkdir -p /var/www/html/var \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/var

# Install Symfony dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
