#!/bin/bash
set -e

cd /var/www || exit 1

# Allow git to work even if owner is www-data
git config --global --add safe.directory /var/www

echo "📁 Ensuring required Laravel directories exist..."
mkdir -p storage/logs bootstrap/cache storage/framework/{sessions,views,cache}

echo "🔧 Fixing permissions before Laravel bootstraps..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

# Run Laravel's fix-permissions.sh if present
if [ -f "./fix-permissions.sh" ]; then
  echo "🔧 Running fix-permissions.sh..."
  bash ./fix-permissions.sh
fi

# Composer install only if vendor/ doesn't exist
if [ ! -d "vendor" ]; then
  echo "📦 Running composer install..."
  composer clear-cache
  composer install
fi

# NPM install + dev build if node_modules/ doesn't exist
if [ ! -d "node_modules" ]; then
  echo "📦 Running npm install and npm run dev..."
  npm install
  npm run dev
fi

# Copy .env and setup Laravel if .env doesn't exist
if [ ! -f ".env" ]; then
  echo "⚙️ Setting up Laravel .env..."
  cp .env.example .env
  php artisan key:generate
  php artisan jwt:secret
  php artisan migrate --force --seed
fi

echo "🚀 Launching PHP-FPM..."
exec php-fpm
