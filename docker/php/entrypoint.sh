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

# Copy .env and setup Laravel if .env doesn't exist
if [ ! -f ".env" ]; then
  echo "⚙️ Setting up Laravel .env..."
  cp .env.example .env
  php artisan key:generate
  php artisan jwt:secret
  echo "⏳ Waiting for Database to accept connections..."
  for i in $(seq 1 30); do
    if (echo > /dev/tcp/db/3306) >/dev/null 2>&1; then
      echo "✅ Database is up."
      break
    fi
    if [ "$i" -eq 30 ]; then
      echo "❌ Database did not become ready in time."
      exit 1
    fi
    sleep 2
  done
  php artisan migrate --force --seed
fi

echo "🚀 Launching PHP-FPM..."
exec php-fpm
