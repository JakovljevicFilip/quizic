#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck source=/dev/null
source "${SCRIPT_DIR}/start-helpers.sh"

LOG_FILE=".docker-up.log"

say "Starting containers (build if needed)..."
ensure_env_from_example ".env.example"
ensure_env_writable
compose_up "${LOG_FILE}"

say "Ensuring app dependencies..."
if ! docker compose exec -T app test -f /var/www/vendor/autoload.php >/dev/null 2>&1; then
  say "Running composer install (this can take a few minutes)..."
  attempts=0
  max_attempts=5
  while true; do
    attempts=$((attempts + 1))
    if docker compose exec -T app composer install --no-scripts >>"${LOG_FILE}" 2>&1; then
      break
    fi
    if [ "${attempts}" -ge "${max_attempts}" ]; then
      say "Composer install failed after ${max_attempts} attempts. See ${LOG_FILE} for details."
      exit 1
    fi
    say "Composer install failed. Retrying..."
    sleep 5
  done
fi

say "Waiting for app dependencies..."
for i in $(seq 1 60); do
  if docker compose exec -T app test -f /var/www/vendor/autoload.php >/dev/null 2>&1; then
    say "App dependencies ready ✓"
    break
  fi
  if [ "$i" -eq 60 ]; then
    say "App dependencies did not become ready. See ${LOG_FILE} for details."
    exit 1
  fi
  sleep 2
done

ensure_app_key "docker-compose.yml"
ensure_jwt_secret "docker-compose.yml"

say "Running post-install tasks..."
artisan_with_retry "docker-compose.yml" "package:discover --ansi"
artisan_with_retry "docker-compose.yml" "clear-compiled"
artisan_with_retry "docker-compose.yml" "optimize"
docker_exec_with_retry "docker-compose.yml" "chmod -R 777 public/"
artisan_with_retry "docker-compose.yml" "clear-compiled"
artisan_with_retry "docker-compose.yml" "config:clear"
artisan_with_retry "docker-compose.yml" "cache:clear"

say "Waiting for database..."
db_host="$(grep -E '^DB_HOST=' .env | head -n1 | cut -d= -f2- || true)"
db_port="$(grep -E '^DB_PORT=' .env | head -n1 | cut -d= -f2- || true)"
db_user="$(grep -E '^DB_USERNAME=' .env | head -n1 | cut -d= -f2- || true)"
db_pass="$(grep -E '^DB_PASSWORD=' .env | head -n1 | cut -d= -f2- || true)"
if [ -z "${db_host}" ]; then
  db_host="db"
fi
if [ -z "${db_port}" ]; then
  db_port="3306"
fi
if [ -z "${db_user}" ]; then
  db_user="root"
fi
if [ -n "${db_pass}" ]; then
  db_pass_arg="-p${db_pass}"
else
  db_pass_arg=""
fi
for i in $(seq 1 60); do
  if docker compose exec -T db mysqladmin ping -h"${db_host}" -P"${db_port}" -u"${db_user}" ${db_pass_arg} --silent >/dev/null 2>&1; then
    say "Database ready ✓"
    break
  fi
  if [ "$i" -eq 60 ]; then
    say "Database did not become ready. See ${LOG_FILE} for details."
    exit 1
  fi
  sleep 2
done

say "Waiting for frontend assets..."
for i in $(seq 1 120); do
  if [ -s "public/js/app.js" ] && [ -s "public/css/app.css" ]; then
    say "Assets ready ✓"
    break
  fi
  if [ "$i" -eq 5 ]; then
    say "Almost there..."
  fi
  if [ "$i" -eq 120 ]; then
    say "Assets did not become ready. See ${LOG_FILE} for details."
    exit 1
  fi
  sleep 1
done

say "Setup complete. Open http://localhost:8000"
