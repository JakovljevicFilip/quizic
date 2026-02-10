#!/bin/bash
set -e

cd /var/www || exit 1

CRON_FILE="/etc/cron.d/laravel-scheduler"
LOG_FILE="/var/www/storage/logs/cron.log"

mkdir -p /var/www/storage/logs
touch "$LOG_FILE"
chown www-data:www-data "$LOG_FILE"

cat > "$CRON_FILE" <<'EOF'
* * * * * www-data cd /var/www && php artisan schedule:run >> /var/www/storage/logs/cron.log 2>&1
EOF

chmod 0644 "$CRON_FILE"

echo "🕒 Starting cron (Laravel scheduler)..."
exec cron -f
