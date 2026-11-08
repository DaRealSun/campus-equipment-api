#!/usr/bin/env bash
set -euo pipefail
cd -- "$(dirname -- "$0")/.."
if [ ! -f .env ]; then
  python3 - <<'PY'
from pathlib import Path
import secrets
Path('.env').write_text('HTTP_PORT=8640\nDB_PASSWORD='+secrets.token_hex(24)+'\nDB_ROOT_PASSWORD='+secrets.token_hex(24)+'\n',encoding='utf-8')
PY
  chmod 600 .env
fi
docker compose up -d --build --wait
python3 ../tests/environment.py --base-url http://127.0.0.1:8640
