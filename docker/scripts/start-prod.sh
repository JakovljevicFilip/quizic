#!/usr/bin/env bash
set -euo pipefail

LOG_FILE=".docker-up.prod.log"

say() {
  printf "%s\n" "$*"
}

# Differences vs docker/scripts/start.sh:
# - Uses docker-compose.prod.yml
# - Does not wait for DB or frontend assets
# - No local dev URL output

ensure_env() {
  if [ ! -f ".env" ]; then
    say "ERROR: .env is missing. Create it manually before running start-prod.sh."
    exit 1
  fi

  local app_url asset_url
  app_url="$(grep -E '^APP_URL=' .env | head -n1 | cut -d= -f2- || true)"
  asset_url="$(grep -E '^ASSET_URL=' .env | head -n1 | cut -d= -f2- || true)"

  if [ -n "${app_url}" ] && [ -z "${asset_url}" ]; then
    if grep -q '^ASSET_URL=' .env; then
      sed -i "s|^ASSET_URL=.*|ASSET_URL=${app_url}|" .env
    else
      printf "ASSET_URL=%s\n" "${app_url}" >> .env
    fi
  fi
}
spinner() {
  local pid="$1"
  local msg="$2"
  local spin='-\|/'
  local i=0
  while kill -0 "$pid" 2>/dev/null; do
    printf "\r%s %c" "$msg" "${spin:i++%4:1}"
    sleep 0.1
  done
  printf "\r%s ✓\n" "$msg"
}

say "Starting prod containers (build if needed)..."
ensure_env
if ! docker network ls --format '{{.Name}}' | grep -Fxq "quizic"; then
  say "Creating docker network: quizic"
  docker network create quizic
fi
docker compose -f docker-compose.prod.yml up --build -d >"$LOG_FILE" 2>&1 &
spinner $! "Bringing up services"

if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env; then
  say "Generating APP_KEY..."
  docker compose -f docker-compose.prod.yml exec -T app php artisan key:generate --force
fi

if ! grep -q '^JWT_SECRET=' .env || grep -q '^JWT_SECRET=$' .env; then
  say "Generating JWT_SECRET..."
  docker compose -f docker-compose.prod.yml exec -T app php artisan jwt:secret --force
fi

say "Refreshing config cache..."
docker compose -f docker-compose.prod.yml exec -T app php artisan config:cache

say "Prod stack is up."
