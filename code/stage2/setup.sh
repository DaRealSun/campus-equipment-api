#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"
bash prepare.sh
docker compose up -d --build
for attempt in $(seq 1 90); do
 if curl --fail --silent http://127.0.0.1:8642/up >/dev/null; then
  printf 'Laravel container ready at http://127.0.0.1:8642\n'; exit 0
 fi
 sleep 2
done
docker compose logs --tail=30 php nginx
exit 1
