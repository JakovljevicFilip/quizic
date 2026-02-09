#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck source=/dev/null
source "${SCRIPT_DIR}/start.sh"

LOG_FILE=".docker-up.log"

say "Starting containers (build if needed)..."
ensure_env_from_example ".env.example"
compose_up "${LOG_FILE}"

say "Waiting for database..."
for i in $(seq 1 60); do
  if docker compose exec -T db mysqladmin ping -uroot -psecret --silent >/dev/null 2>&1; then
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
