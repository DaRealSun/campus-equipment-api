# Start and verify Stage 2

Use Linux, WSL2 or macOS with Bash, Docker Engine/Compose, OpenSSL and curl. For `run.sh`, provide PHP 8.4 with PDO MySQL, Composer 2 and Python 3 for tests. Docker Engine must already be running. No Node build is required for the JSON API.

From `code/stage2`, run `bash run.sh`. It generates ignored local configuration once, starts dedicated MySQL, installs locked dependencies, migrates and starts Laravel on localhost:8641. In another terminal, reset only synthetic fixtures with `ALLOW_FIXTURE_RESET=1 php artisan fixtures:reset`. From the repository root:

```sh
python3 code/tests/acceptance.py --base-url http://127.0.0.1:8641 --stage stage2 --output docs/results/stage2-host.json
API_BASE_URL=http://127.0.0.1:8641 python3 code/tests/stage2-integrity.py
```

Alternatively run `bash setup.sh` from `code/stage2`. It builds PHP-FPM and starts NGINX on localhost:8642. Then:

```sh
docker compose exec -T -e ALLOW_FIXTURE_RESET=1 php php artisan fixtures:reset
# From the repository root:
python3 code/tests/acceptance.py --base-url http://127.0.0.1:8642 --stage stage2 --output docs/results/stage2-docker.json
API_BASE_URL=http://127.0.0.1:8642 python3 code/tests/stage2-integrity.py
```

The fixture command requires opt-in and the exact Stage 2 database name. It deletes only synthetic records in that database. Neither startup script resets data. Never use this configuration for personal or production records. Stop the host with Ctrl+C. Stop containers with `docker compose stop`. Startup preserves the named volume.

The ASE230 Module 2 bearer-token `run1-5.sh` sequence is the reference for install/configure/migrate/start steps. This adaptation adds locked installs, readiness checks, generated secrets, localhost-only ports, separate databases and an explicit fixture-reset gate. Automation does not prove correctness, so assertions run afterward.

Equivalent bearer authentication preserves the Stage 1 contract: random 256-bit opaque tokens, SHA-256 digests stored with Eloquent, 24-hour expiry, role restrictions and ownership checks. This is custom authentication rather than Sanctum. A maintained package could reduce custom security code but requires compatibility work for the token contract. No security audit is claimed.

On this machine WSL `php` and `composer` are pre-existing Docker wrappers. The default PHP wrapper cannot reach a host database through its own localhost. For `run.sh` verification, an isolated PHP 8.4 CLI wrapper used host networking and mounted the runtime directory. Laravel's CLI server served port 8641. The separate `setup.sh` test uses PHP-FPM/NGINX containers at port 8642. This does not claim native Windows PHP or testing every supported OS.

References: [course examples](https://github.com/nkuase/ase230/tree/main/module2/code/1_Laravel/5.%20Bearer%20Token%20APIs), [routing](https://laravel.com/framework/docs/13.x/routing), [Eloquent](https://laravel.com/framework/docs/eloquent), [transactions](https://laravel.com/framework/docs/database), [Compose](https://docs.docker.com/compose/). OpenAI Codex adapted, implemented and tested this project.
