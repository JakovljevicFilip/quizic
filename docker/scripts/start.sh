#!/usr/bin/env bash
set -euo pipefail

if [ "${BASH_SOURCE[0]}" = "$0" ]; then
  echo "This script is a helper. Use docker/scripts/start-local.sh or docker/scripts/start-prod.sh."
  exit 1
fi

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

ensure_env_from_example() {
  local example_file="$1"
  if [ ! -f ".env" ]; then
    if [ -f "${example_file}" ]; then
      say ".env not found. Creating from ${example_file}."
      cp "${example_file}" .env
    else
      say "ERROR: .env not found and ${example_file} is missing."
      exit 1
    fi
  fi
}

ensure_env_writable() {
  if [ ! -w ".env" ]; then
    say "ERROR: .env is not writable. Fix permissions before continuing."
    exit 1
  fi
}

compose_up() {
  local log_file="$1"
  shift
  docker compose "$@" up --build -d >"${log_file}" 2>&1 &
  spinner $! "Bringing up services"
}

ensure_app_key() {
  local compose_file="$1"
  if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env; then
    say "Generating APP_KEY..."
    docker compose -f "${compose_file}" exec -T app php artisan key:generate --force
  fi
}

ensure_jwt_secret() {
  local compose_file="$1"
  if ! grep -q '^JWT_SECRET=' .env || grep -q '^JWT_SECRET=$' .env; then
    say "Generating JWT_SECRET..."
    docker compose -f "${compose_file}" exec -T app php artisan jwt:secret --force
  fi
}
