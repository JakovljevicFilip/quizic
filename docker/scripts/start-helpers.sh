#!/usr/bin/env bash
set -euo pipefail

if [ "${BASH_SOURCE[0]}" = "$0" ]; then
  echo "This script is a helper. Use docker/scripts/start-local.sh or docker/scripts/start-prod.sh."
  exit 1
fi

say() {
  printf "%s\n" "$*"
}

ensure_repo_root() {
  local script_dir
  script_dir="$(cd "$(dirname "${BASH_SOURCE[1]}")" && pwd)"
  local root_dir
  root_dir="$(cd "${script_dir}/../.." && pwd)"
  cd "${root_dir}"
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
    artisan_with_retry "${compose_file}" "key:generate --force"
  fi
}

ensure_jwt_secret() {
  local compose_file="$1"
  if ! grep -q '^JWT_SECRET=' .env || grep -q '^JWT_SECRET=$' .env; then
    say "Generating JWT_SECRET..."
    artisan_with_retry "${compose_file}" "jwt:secret --force"
  fi
}

ensure_app_dependencies() {
  local compose_file="$1"
  local log_file="$2"

  say "Ensuring app dependencies..."
  if ! docker compose -f "${compose_file}" exec -T app test -f /var/www/vendor/autoload.php >/dev/null 2>&1; then
    say "Running composer install (this can take a few minutes)..."
    local attempts=0
    local max_attempts=5
    while true; do
      attempts=$((attempts + 1))
      if docker compose -f "${compose_file}" exec -T app composer install --no-scripts >>"${log_file}" 2>&1; then
        break
      fi
      if [ "${attempts}" -ge "${max_attempts}" ]; then
        say "Composer install failed after ${max_attempts} attempts. See ${log_file} for details."
        exit 1
      fi
      say "Composer install failed. Retrying..."
      sleep 5
    done
  fi
}

wait_for_app_dependencies() {
  local compose_file="$1"
  local log_file="$2"

  say "Waiting for app dependencies..."
  for i in $(seq 1 60); do
    if docker compose -f "${compose_file}" exec -T app test -f /var/www/vendor/autoload.php >/dev/null 2>&1; then
      say "App dependencies ready ✓"
      break
    fi
    if [ "$i" -eq 60 ]; then
      say "App dependencies did not become ready. See ${log_file} for details."
      exit 1
    fi
    sleep 2
  done
}

run_post_install_tasks() {
  local compose_file="$1"

  say "Running post-install tasks..."
  artisan_with_retry "${compose_file}" "package:discover --ansi"
  artisan_with_retry "${compose_file}" "clear-compiled"
  artisan_with_retry "${compose_file}" "optimize"
  docker_exec_with_retry "${compose_file}" "chmod -R 777 public/"
  artisan_with_retry "${compose_file}" "clear-compiled"
  artisan_with_retry "${compose_file}" "config:clear"
  artisan_with_retry "${compose_file}" "cache:clear"
}

artisan_with_retry() {
  local compose_file="$1"
  local artisan_args="$2"
  local max_attempts=30
  local attempt=1

  while true; do
    if [ -n "${LOG_FILE:-}" ]; then
      if docker compose -f "${compose_file}" exec -T app php artisan ${artisan_args} >>"${LOG_FILE}" 2>&1; then
        return 0
      fi
    else
      if docker compose -f "${compose_file}" exec -T app php artisan ${artisan_args} >/dev/null 2>&1; then
        return 0
      fi
    fi
    if [ "${attempt}" -ge "${max_attempts}" ]; then
      say "ERROR: php artisan ${artisan_args} failed after ${max_attempts} attempts."
      return 1
    fi
    attempt=$((attempt + 1))
    sleep 2
  done
}


docker_exec_with_retry() {
  local compose_file="$1"
  local cmd="$2"
  local max_attempts=30
  local attempt=1

  while true; do
    if [ -n "${LOG_FILE:-}" ]; then
      if docker compose -f "${compose_file}" exec -T app ${cmd} >>"${LOG_FILE}" 2>&1; then
        return 0
      fi
    else
      if docker compose -f "${compose_file}" exec -T app ${cmd} >/dev/null 2>&1; then
        return 0
      fi
    fi
    if [ "${attempt}" -ge "${max_attempts}" ]; then
      say "ERROR: ${cmd} failed after ${max_attempts} attempts."
      return 1
    fi
    attempt=$((attempt + 1))
    sleep 2
  done
}
