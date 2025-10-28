# Use a standard PHP Apache image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# 1. Install system dependencies & PHP extensions for Laravel with MySQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Configure Apache to use Laravel's public folder
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# 4. Copy application code and set permissions
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 5. Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# 6. Add the script that runs when the container starts
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
