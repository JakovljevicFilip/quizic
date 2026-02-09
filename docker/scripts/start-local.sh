#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck source=/dev/null
source "${SCRIPT_DIR}/start-helpers.sh"
ensure_repo_root

LOG_FILE=".docker-up.log"
compose_file="docker-compose.local.yml"

say "Starting containers (build if needed)..."
ensure_env_from_example ".env.example"
ensure_env_writable
compose_up "${LOG_FILE}" -f "${compose_file}"

ensure_app_dependencies "${compose_file}" "${LOG_FILE}"
wait_for_app_dependencies "${compose_file}" "${LOG_FILE}"

ensure_app_key "${compose_file}"
ensure_jwt_secret "${compose_file}"

run_post_install_tasks "${compose_file}"

# reuse compose_file below
say "Waiting for database..."
db_port="$(grep -E '^DB_PORT=' .env | head -n1 | cut -d= -f2- || true)"
db_user="$(grep -E '^DB_USERNAME=' .env | head -n1 | cut -d= -f2- || true)"
db_pass="$(grep -E '^DB_PASSWORD=' .env | head -n1 | cut -d= -f2- || true)"
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
  if docker compose -f "${compose_file}" exec -T db mysqladmin ping -h"127.0.0.1" -P"${db_port}" -u"${db_user}" ${db_pass_arg} --silent >>"${LOG_FILE}" 2>&1; then
    say "Database ready ✓"
    break
  fi
  if [ "$i" -eq 60 ]; then
    say "Database did not become ready. See ${LOG_FILE} for details."
    exit 1
  fi
  sleep 2
done

say "Running migrations..."
artisan_with_retry ""${compose_file}"" "migrate --force --seed"

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
