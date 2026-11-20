#!/usr/bin/env bash
# Laravel host runtime adapted from ASE230 run1-5.sh; see SCRIPT-NOTES.md.
set -euo pipefail
cd "$(dirname "$0")"
command -v php >/dev/null
command -v composer >/dev/null
bash prepare.sh
composer install --no-interaction --prefer-dist
php artisan migrate --force
exec php artisan serve --host=127.0.0.1 --port="${HTTP_PORT:-8641}"
