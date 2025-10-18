# Stage 1: Install dependencies with Composer
FROM composer:2 AS vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# Stage 2: Setup the final application image
FROM php:8.2-apache

# Copy the vendor folder from the first stage
COPY --from=vendor /app/vendor/ /var/www/html/vendor/
# Copy the rest of the application code
COPY . /var/www/html/

# Set up permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# THIS IS THE NEW, CRITICAL LINE
# Install the PHP extension for MySQL
RUN docker-php-ext-install pdo_mysql

# Configure Apache
RUN a2enmod rewrite
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Expose port 80 to the web
EXPOSE 80
