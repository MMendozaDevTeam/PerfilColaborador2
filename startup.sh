#!/bin/bash

cd /home/site/wwwroot

# Instala dependencias si no existen
if [ ! -d "vendor" ]; then
  composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
fi

# Genera clave si no existe
if [ ! -f ".env" ]; then
  cp .env.example .env
  php artisan key:generate
fi

chmod -R 775 storage bootstrap/cache

php artisan migrate --force || true

php artisan serve --host 0.0.0.0 --port 8080 --disable-ansi
