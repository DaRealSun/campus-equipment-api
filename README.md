# Campus Equipment Loan API

CSC 640, Option 2. Status: HW1 proposal and HW4 Part 1 preparation. The NGINX/PHP/MySQL readiness route works; the ten business API endpoints are not implemented yet.

The same JSON API will be built with raw PHP/MySQL (Stage 1) and Laravel (Stage 2). All data must be synthetic.

- [Proposal and API contract](docs/proposal.md)
- [Acceptance contract and run commands](acceptance.md)
- [Stage 1 plan: milestones and Plan vs Actual](plan/HW4_plan_stage1.md)
- [Architecture](docs/architecture.md)
- [Current progress](docs/progress/2026-11-08.md)
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

To run the acceptance baseline, reset only the isolated synthetic course fixtures first:

```bash
docker compose exec -T -e ALLOW_FIXTURE_RESET=1 php php /app/scripts/reset-fixtures.php
cd ../..
python3 code/tests/acceptance.py --base-url http://127.0.0.1:8640 --stage stage1 --output docs/results/stage1.json
```

Part 1 baseline: environment check passes; all 44 product assertions fail against the explicit 501 implementation placeholder. This is an honest preparation checkpoint. Stage 1 must implement the APIs and pass these assertions for Part 2. Read [acceptance.md](acceptance.md) before running them.

Stop from `code/stage1` with `docker compose stop`; resume with `bash scripts/start.sh`. This preserves the named database volume. On this machine, use the Docker Engine inside WSL; if needed, start it with `service docker start` as the administrative account. Other users use their normal Docker setup.

## Repository layout

`code/stage1/` contains the preparation environment; `code/stage2/` will contain Laravel; `code/tests/` contains assertions shared by both stages; `presentation/` is reserved for tutorials/presentations; `plan/` contains dated milestones; `docs/` contains architecture, progress and measured results.

## Sources

The HW1 and HW4 Part 1 course handouts define scope and submission requirements. [ASE 230 Module 1](https://github.com/nkuase/ase230/tree/main/module1) is the reference for PHP/NGINX/MySQL and API testing; [Modules 2–3](https://github.com/nkuase/ase230) support Laravel, Docker and documentation deployment. The preparation environment and domain-specific contract were drafted for this project rather than resubmitting the instructor's student-management example.

## AI assistance

OpenAI Codex helped review course materials and draft the proposal, contract, preparation code, executable assertions, plan and documentation. The student must review, adapt and understand these artifacts. Actual results are linked above; no passing product acceptance tests or completed business API implementation are claimed.
