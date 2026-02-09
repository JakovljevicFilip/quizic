#!/bin/bash
set -e

cd /var/www || exit 1

# Allow git to work even if owner is www-data
git config --global --add safe.directory /var/www || true

echo "📁 Ensuring required Laravel directories exist..."
mkdir -p storage/logs bootstrap/cache storage/framework/{sessions,views,cache}

echo "🔧 Fixing permissions before Laravel bootstraps..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

# Run Laravel's fix-permissions.sh if present
if [ -f "./docker/scripts/fix-permissions.sh" ]; then
  echo "🔧 Running fix-permissions.sh..."
  bash ./docker/scripts/fix-permissions.sh
fi

echo "🚀 Launching PHP-FPM..."
exec php-fpm
