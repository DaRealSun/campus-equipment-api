#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"
command -v docker >/dev/null
if [ ! -f .env ]; then
 umask 077
 cp .env.example .env
 printf '\nDB_PASSWORD=%s\nDB_ROOT_PASSWORD=%s\nAPP_KEY=base64:%s\n' "$(openssl rand -hex 24)" "$(openssl rand -hex 24)" "$(openssl rand -base64 32)" >> .env
fi
docker compose up -d --wait mysql
