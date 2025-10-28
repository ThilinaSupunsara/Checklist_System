# Use a standard PHP Apache image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# 1. Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    nodejs \ # <-- Add Node.js
    npm \    # <-- Add npm
    && docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Configure Apache
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# 4. Copy application code and set permissions
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 5. Install Composer (PHP) dependencies
RUN composer install --no-dev --optimize-autoloader

# 6. Install NPM (frontend) dependencies and build assets
RUN npm install
RUN npm run build # <-- This command creates the manifest.json file

# 7. Add the entrypoint script
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
