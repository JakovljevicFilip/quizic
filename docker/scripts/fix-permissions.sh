#!/bin/bash
set -e

# Use UID from docker-compose (host user)
ACTUAL_UID=${HOST_UID:-1000}
APP_PATH="/var/www"

echo "✅ Running fix-permissions.sh as $(whoami)"
echo "👤 Granting access to host UID: $ACTUAL_UID"
echo "📁 App path: $APP_PATH"

# Ownership inside container
chown -R www-data:www-data "$APP_PATH"

# Grant ACL to host user so they can edit files
setfacl -Rm u:${ACTUAL_UID}:rwx,d:u:${ACTUAL_UID}:rwx "$APP_PATH"
