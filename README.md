# Campus Equipment Loan API

## Stage 2 and quality documentation

Stage 2 is now implemented in Laravel/Eloquent. See [startup and verification](code/stage2/SCRIPT-NOTES.md): `bash code/stage2/run.sh` serves the CLI path on localhost:8641, and `bash code/stage2/setup.sh` serves Docker/NGINX on localhost:8642. Both use a separate Stage 2 MySQL database and preserve normal application data on startup.

The unchanged shared acceptance runner passed 44/44 assertions against [Laravel CLI](docs/results/stage2-host.json), [Docker Laravel](docs/results/stage2-docker.json), and the [November 20 Stage 1 recheck](docs/results/stage1-recheck-2026-11-20.json). Additional Stage 2 integrity results are in `docs/results/`. These are recorded local tests, not a security audit or production deployment.

- [Published Stage 2 tutorial](https://darealsun.github.io/stage2/)
- [HW5 quality report](https://darealsun.github.io/quality/)
- [Marp presentation](presentation/HW5-quality.md) and [PDF](presentation/HW5-quality.pdf)
- [Two-stage comparison](presentation/comparison.md)
- [HW3 implementations and screenshots](code/hw3)

OpenAI Codex implemented Stage 2, ran the recorded checks, and drafted these documents. Student explanations informed the ownership and schedule examples. The report describes assistance and remaining limitations.

CSC 640, Option 2. The `hw4-stage1` tag identifies the PHP/MySQL version for HW4 Part 2. Stage 2 will retain the same JSON contract in Laravel. Use synthetic data only.

- [Proposal and API contract](docs/proposal.md)
- [Acceptance contract and run commands](acceptance.md)
- [Stage 1 plan: milestones and Plan vs Actual](plan/HW4_plan_stage1.md)
- [Stage 2 plan](plan/HW4_plan_stage2.md)
- [Current progress and verification](docs/progress/2026-11-13.md)
- [Stage 1 acceptance results](docs/results/stage1.json)
- [Stage 1 tutorial source](presentation/stage1-tutorial.md) · [PDF](presentation/stage1-tutorial.pdf)
- [Environment verification](docs/results/environment-readiness.md)
- [Recorded failing product baseline](docs/results/part1-contract-baseline.json)
- [Shared executable assertions](code/tests/acceptance.py)
- [Stage 1 code/environment](code/stage1)
- [Stage 2 placeholder](code/stage2)
- [Presentation artifacts](presentation)

## Planned tooling

PHP 8.4+, Composer 2.8+ (major 2), MySQL, Laravel 13+ for Stage 2, Docker Compose, and Bash. Dependencies are not installed by cloning this starter. See the HW1 setup checklist for the actual environment status.

## Start, verify, stop

Inside WSL2 Ubuntu or a Linux/macOS shell with Docker Engine, Docker Compose and Python 3:

```bash
cd code/stage1
bash scripts/start.sh
```

Open `http://127.0.0.1:8640/health`. The startup script creates a local ignored `.env` with random database passwords, builds PHP-FPM with PDO MySQL, starts NGINX and MySQL, and checks the actual database readiness row. Only NGINX is published, on localhost port 8640. Database/PHP ports remain internal.

Open `http://127.0.0.1:8640/test.html` to call each endpoint and inspect its status and response. To run acceptance tests, reset the isolated synthetic course fixtures first. The reset deletes records from this course database and replaces them with the fixtures documented in [acceptance.md](acceptance.md).

```bash
docker compose exec -T -e ALLOW_FIXTURE_RESET=1 php php /app/scripts/reset-fixtures.php
cd ../..
python3 code/tests/acceptance.py --base-url http://127.0.0.1:8640 --stage stage1 --output docs/results/stage1.json
python3 code/tests/stage1-integrity.py
```

The Stage 1 acceptance run passed 44/44 assertions. The supplementary test checks concurrent checkout, unauthorized return, availability filtering, and input limits. Use the fixture credentials in `acceptance.md` only on this local instance.

Stop from `code/stage1` with `docker compose stop`; resume with `bash scripts/start.sh`. This preserves the named database volume. On this machine, use the Docker Engine inside WSL; if needed, start it with `service docker start` as the administrative account. Other users use their normal Docker setup.

## Repository layout

`code/stage1/` contains the PHP routes, PDO database operations, schema and NGINX configuration. `code/stage2/` is reserved for Laravel. `code/tests/` contains the acceptance runner. `presentation/` contains the tutorial, `plan/` contains dated milestones, and `docs/` contains shared results and progress.

## Sources

The HW1 and HW4 Part 1 course handouts define scope and submission requirements. [ASE 230 Module 1](https://github.com/nkuase/ase230/tree/main/module1) is the reference for PHP/NGINX/MySQL and API testing; [Modules 2–3](https://github.com/nkuase/ase230) support Laravel, Docker and documentation deployment. The preparation environment and domain-specific contract were drafted for this project rather than resubmitting the instructor's student-management example.

## AI assistance

OpenAI Codex helped draft the proposal and contract, implement Stage 1 and its browser page, run the tests, and draft the plans and tutorial. Source material is cited in the tutorial. The saved reports record the measured results.
