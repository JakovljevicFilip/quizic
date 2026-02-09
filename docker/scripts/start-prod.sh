#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck source=/dev/null
source "${SCRIPT_DIR}/start-helpers.sh"

LOG_FILE=".docker-up.prod.log"

# Differences vs docker/scripts/start-helpers.sh:
# - Uses docker-compose.prod.yml
# - Does not wait for DB or frontend assets
# - No local dev URL output

ensure_env() {
  if [ ! -f ".env" ]; then
    if [ -f ".env.prod.example" ]; then
      say ".env not found. Creating from .env.prod.example."
      cp .env.prod.example .env
    else
      say ".env not found. Creating from .env.example with production defaults."
      cp .env.example .env
      # Sensible prod defaults; review .env after this runs.
      if grep -q '^APP_ENV=' .env; then
        sed -i "s/^APP_ENV=.*/APP_ENV=production/" .env
      else
        printf "\nAPP_ENV=production\n" >> .env
      fi
      if grep -q '^APP_DEBUG=' .env; then
        sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" .env
      else
        printf "APP_DEBUG=false\n" >> .env
      fi
    fi
    say "Created .env with defaults. Review and update values as needed."
    say "Note: Mailer settings are blank and must be configured separately."
  fi
  if [ ! -w ".env" ]; then
    say "ERROR: .env is not writable. Fix permissions before running start-prod.sh."
    exit 1
  fi

  local app_url
  app_url="$(grep -E '^APP_URL=' .env | head -n1 | cut -d= -f2- || true)"
  local db_pass

  db_pass="$(grep -E '^DB_PASSWORD=' .env | head -n1 | cut -d= -f2- || true)"
  if [ -z "${db_pass}" ]; then
    set +o pipefail
    db_pass="$(LC_ALL=C tr -dc 'A-Za-z0-9' </dev/urandom | head -c 10)"
    set -o pipefail
    if grep -q '^DB_PASSWORD=' .env; then
      sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${db_pass}/" .env
    else
      printf "\nDB_PASSWORD=%s\n" "${db_pass}" >> .env
    fi
    say ".env updated with randomized DB_PASSWORD."
  fi
}
say "Starting prod containers (build if needed)..."
ensure_env
if ! docker network ls --format '{{.Name}}' | grep -Fxq "quizic"; then
  say "Creating docker network: quizic"
  docker network create quizic
fi
compose_up "$LOG_FILE" -f docker-compose.prod.yml

say "Ensuring app dependencies..."
if ! docker compose -f docker-compose.prod.yml exec -T app test -f /var/www/vendor/autoload.php >/dev/null 2>&1; then
  say "Running composer install (this can take a few minutes)..."
  attempts=0
  max_attempts=5
  while true; do
    attempts=$((attempts + 1))
    if docker compose -f docker-compose.prod.yml exec -T app composer install --no-scripts >>"${LOG_FILE}" 2>&1; then
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
  if docker compose -f docker-compose.prod.yml exec -T app test -f /var/www/vendor/autoload.php >/dev/null 2>&1; then
    say "App dependencies ready ✓"
    break
  fi
  if [ "$i" -eq 60 ]; then
    say "App dependencies did not become ready. See ${LOG_FILE} for details."
    exit 1
  fi
  sleep 2
done

ensure_app_key "docker-compose.prod.yml"
ensure_jwt_secret "docker-compose.prod.yml"

say "Running post-install tasks..."
artisan_with_retry "docker-compose.prod.yml" "package:discover --ansi"
artisan_with_retry "docker-compose.prod.yml" "clear-compiled"
artisan_with_retry "docker-compose.prod.yml" "optimize"
docker_exec_with_retry "docker-compose.prod.yml" "chmod -R 777 public/"
artisan_with_retry "docker-compose.prod.yml" "clear-compiled"
artisan_with_retry "docker-compose.prod.yml" "config:clear"
artisan_with_retry "docker-compose.prod.yml" "cache:clear"

if ! grep -q '^TRUSTED_PROXIES=' .env; then
  proxy_cidr="$(docker network inspect traefik_proxy --format '{{(index .IPAM.Config 0).Subnet}}' 2>/dev/null || true)"
  if [ -n "${proxy_cidr}" ]; then
    printf "\nTRUSTED_PROXIES=%s\n" "${proxy_cidr}" >> .env
    say "Set TRUSTED_PROXIES=${proxy_cidr}"
  else
    say "WARNING: Could not detect traefik_proxy subnet. Set TRUSTED_PROXIES manually."
  fi
fi

say "Refreshing config cache..."
docker compose -f docker-compose.prod.yml exec -T app php artisan config:cache

say "Prod stack is up."
