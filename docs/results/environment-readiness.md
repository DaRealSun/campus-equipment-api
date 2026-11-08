# Stage 1 Environment Readiness — November 8, 2026

Environment: Ubuntu WSL2, Docker Engine 29.9.2, project Compose stack `csc640-equipment-prep`, PHP 8.4-FPM with PDO MySQL, NGINX, MySQL 8.4.

Command from repository root:

```bash
cd code/stage1
bash scripts/start.sh
```

Result: process exit 0. NGINX, PHP and MySQL containers started; MySQL's authenticated `SELECT 1` health check passed. The Python readiness assertion sent `GET /health` through NGINX and verified HTTP 200, JSON content type and this exact result:

```json
{"status":"ok","database":"connected","probe":"nginx-php-mysql-ready"}
```

The probe is read from a real MySQL row by PHP's PDO driver; it is not a hardcoded successful database claim.

```text
PASS: HTTP 200 JSON verified through NGINX -> PHP-FPM -> PDO -> MySQL readiness row.
```

Separate product baseline command:

```bash
python3 code/tests/acceptance.py --base-url http://127.0.0.1:8640 --stage stage1 --output docs/results/part1-contract-baseline.json
```

Result: exit 1, 0/44 assertions pass, 44 fail. The project routes currently return explicit HTTP 501 placeholders. This result defines the starting point for implementation and is not a completed Stage 1 evaluation. The JSON report records the actual timestamp, cases, status comparisons and content failures.
