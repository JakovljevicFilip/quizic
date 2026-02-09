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
    say ".env not found. Copying .env.example -> .env"
    cp .env.example .env
  fi

  # Use .env values; auto-generate password if missing
  local db_name db_user db_pass generated_pass
  db_name="$(grep -E '^DB_DATABASE=' .env | head -n1 | cut -d= -f2- || true)"
  db_user="$(grep -E '^DB_USERNAME=' .env | head -n1 | cut -d= -f2- || true)"
  db_pass="$(grep -E '^DB_PASSWORD=' .env | head -n1 | cut -d= -f2- || true)"

  generated_pass="$(LC_ALL=C tr -dc 'A-Za-z0-9' </dev/urandom | head -c 10)"
  db_pass="${generated_pass}"
  if grep -q '^DB_PASSWORD=' .env; then
    sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${db_pass}/" .env
  else
    printf "\nDB_PASSWORD=%s\n" "${db_pass}" >> .env
  fi
  say ".env created/updated with randomized DB_PASSWORD."
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
docker compose -f docker-compose.prod.yml up --build -d >"$LOG_FILE" 2>&1 &
spinner $! "Bringing up services"

say "Prod stack is up."
