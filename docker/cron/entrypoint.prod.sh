#!/bin/bash
set -e

cd /var/www || exit 1

LOG_FILE="/var/www/storage/logs/cron.log"

mkdir -p /var/www/storage/logs
touch "$LOG_FILE"
chown www-data:www-data "$LOG_FILE"

echo "🕒 Running reseed now, then every 2 hours..."

while true; do
  php artisan migrate:fresh --seed --force >> "$LOG_FILE" 2>&1
  sleep 7200
done
