# Use official PHP image with Apache
FROM php:8.2-apache

# Set working directory inside the container
WORKDIR /var/www/html

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install zip pdo pdo_mysql gd

# Install Redis PHP extension
RUN pecl install redis && docker-php-ext-enable redis

# Fix "dubious ownership" by marking repo as safe
RUN git config --global --add safe.directory /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the customized 000-default.conf file into the container
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set DirectoryIndex to index.php in the public directory
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf

# Copy project files
COPY . /var/www/html/

# Set proper permissions (ensure Apache can access everything)
RUN mkdir -p /var/www/html/var \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/var

# Make sure cache directories exist
RUN mkdir -p /var/www/html/var/cache/prod && \
mkdir -p /var/www/html/var/logs && \
chown -R www-data:www-data /var/www/html/var && \
chmod -R 775 /var/www/html/var/cache && \
chmod -R 775 /var/www/html/var/logs

# Set a fake database URL during build
ARG DATABASE_URL="mysql://fake_user:fake_pass@fake_host:3306/fake_db"

# Use it as an environment variable inside the container
ENV DATABASE_URL=$DATABASE_URL

# Install Symfony dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Add servername in Apache config to suppress warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


# Expose port 80
EXPOSE 8080

#allow listening to 8080
RUN echo "Listen 8080" >> /etc/apache2/ports.conf

# Start Apache
CMD ["apache2-foreground"]
