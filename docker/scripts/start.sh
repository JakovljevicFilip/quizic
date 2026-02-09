#!/usr/bin/env bash
set -euo pipefail

LOG_FILE=".docker-up.log"

say() {
  printf "%s\n" "$*"
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

say "Starting containers (build if needed)..."
docker compose up --build -d >"$LOG_FILE" 2>&1 &
spinner $! "Bringing up services"

say "Waiting for database..."
for i in $(seq 1 60); do
  if docker compose exec -T db mysqladmin ping -uroot -psecret --silent >/dev/null 2>&1; then
    say "Database ready ✓"
    break
  fi
  if [ "$i" -eq 60 ]; then
    say "Database did not become ready. See $LOG_FILE for details."
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
    say "Assets did not become ready. See $LOG_FILE for details."
    exit 1
  fi
  sleep 1
done

say "Setup complete. Open http://localhost:8000"
