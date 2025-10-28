#!/bin/sh
set -e


# Clear and cache configurations
php artisan config:cache
php artisan route:cache

# Start Apache
exec apache2-foreground
