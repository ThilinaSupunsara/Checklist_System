#!/bin/sh
set -e

RUN npm install
RUN npm run build
# Clear and cache configurations
php artisan config:cache
php artisan route:cache

# Start Apache
exec apache2-foreground
