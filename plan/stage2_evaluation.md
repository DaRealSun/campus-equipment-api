---
marp: true
size: 4:3
paginate: true
---

# Stage 2 Evaluation

Minh Son Le · November 20, 2026 · Proposed score **95/100**. Final grading belongs to the instructor. OpenAI Codex implemented and tested the code and drafted this evaluation. Personal understanding is not attested by the automated checks.

**Total: 100 points.** Submit in HW4 Part 3.

See `HW4.md` for common requirements and peer review. The criteria below describe Option 2; Option 1 uses the equivalent for its approved project.

---

## Self-Grading Rules

The same as `stage1_evaluation.md`: submit this evaluation first; then complete the assigned peer reviews after the Part deadline. The instructor confirms the final 0–100 score after considering the self-evaluation, peer reviews, and submitted evidence; peer scores are not averaged mechanically.

---

## Schedule (20 points)

| Criterion | Points | Earned |
|---|---:|---|
| Milestones in `HW4_plan_stage2.md` were completed on or before their planned dates; score = 10 × milestones on time ÷ total milestones | 10 | 10/10 |
| Plan vs Actual dates were recorded and kept up to date throughout, and changes are explained (master copy in `plan/`, linked from your Canvas progress page) | 10 | 5/10 |
| **Total** | **20** | **15/20** |

---

## Implementation and Quality (60 points)

Evaluate everything below on the application **running** (Laravel through `run.sh`, Docker through `setup.sh`); a working program means the automation works.

For Option 1, you earn each row's points by implementing the equivalent feature.

---

| Criterion | Points | Earned |
|---|---:|---|
| Stage 1 API contract implemented with Laravel routing/controllers | 5 | 5/5 |
| Eloquent models and appropriate relationships; no raw SQL for application database operations | 5 | 5/5 |
| Sanctum or equivalent Bearer authentication: authorized access and invalid/missing-token rejection. For an Option 1 project without protected endpoints, use the instructor-approved equivalent recorded in `acceptance.md`. | 5 | 5/5 |
| Same Stage 1 acceptance assertions run against Laravel, with side-by-side results for both versions | 5 | 5/5 |


---

## Implementation and Quality (cont.)

| Criterion | Points | Earned |
|---|---:|---|
| Adapted shell script with explanation and references | 5 | 5/5 |
| `run.sh` starts the Laravel application from documented prerequisites, and the acceptance tests pass against it | 15 | 15/15 |
| Docker/Compose configuration runs the application and its required services | 10 | 10/10 |
| `setup.sh` builds and starts the environment, and the acceptance tests pass against the container | 10 | 10/10 |
| **Total** | **60** | **60/60** |

---

## Documentation and Publication (20 points)

| Criterion | Points | Earned |
|---|---:|---|
| Hugo tutorial (or an instructor-approved equivalent) adapted from Stage 1; accurate API, setup/run/test instructions and examples | 10 | 10/10 |
| Half-page `comparison.md`: automation benefits, costs, remaining manual work, and links to test results | 5 | 5/5 |
| GitHub Actions builds and publishes the documentation site to GitHub Pages on push, with working project links | 5 | 5/5 |
| **Total** | **20** | **20/20** |

GitHub Pages hosts the documentation, not the Laravel API; its deployment is not evidence that the API tests passed.

---

## Final Checks

- [x] Both runnable stages are preserved and clearly identified in GitHub.
- [x] My HW1 GitHub repository has `code/` (app, scripts, Docker, tests/results), `presentation/` (documentation source and comparison), and `plan/` (updated Plan vs Actual).
- [x] README, criteria, and test results are in my HW1 GitHub repository; its link is on my HW1 Canvas progress page.
- [x] No secrets are committed; required build/configuration files are included.
- [ ] Cited reused materials and AI assistance; I can explain the code, tests, and design.
- [ ] I will submit `peer-review.md` (from `peer-review-example.md`) on Canvas (HW4 Part 3 Peer Review).

**Total: 95/100** (Schedule 20 + Implementation 60 + Documentation 20)


## Evidence and scoring explanation

- **Version:** [Stage 2 source](https://github.com/DaRealSun/campus-equipment-api/tree/main/code/stage2). The implementation/test commit is `2ac58fe`; both stages are preserved separately.
- **Schedule:** three of three milestones completed November 20, before the unchanged November 25, December 2 and December 8 targets: 10 × 3/3 = 10. [Master Plan vs Actual](https://github.com/DaRealSun/campus-equipment-api/blob/main/plan/HW4_plan_stage2.md) was prepared November 13 and updated November 20. Awarded a conservative 5/10 for maintenance because it does not show intermediate weekly Stage 2 reports. [Official Canvas progress](https://nku.instructure.com/courses/92884/pages/individual-project-progress-page-of-minh-son-le-in-csc640) links the master and preserves earlier reports.
- **Implementation:** ten routes/controllers preserve the contract. User, Equipment, Loan and Token models define ownership relationships. Application operations use Eloquent, not raw SQL. Transactions and equipment-row locks protect checkout. Equivalent bearer auth uses random opaque tokens, SHA-256 digests, expiry, role and ownership checks. Registration cannot assign staff.
- **Same tests:** unchanged [acceptance.py](https://github.com/DaRealSun/campus-equipment-api/blob/main/code/tests/acceptance.py) passed **44/44** against [Stage 1 recheck](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/results/stage1-recheck-2026-11-20.json), [Laravel CLI via run.sh](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/results/stage2-host.json), and [Docker/NGINX via setup.sh](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/results/stage2-docker.json). Each report includes stage, base URL, timestamp and individual expected/actual assertions.
- **Additional integrity:** [container results](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/results/stage2-docker-integrity.txt) confirm one 201/one 409 under competing checkout, exactly one active loan, unchanged loan records after a 403 return, availability filters and name limits. The CLI integrity checks also passed.
- **Automation:** [run.sh, setup.sh and explanation](https://github.com/DaRealSun/campus-equipment-api/blob/main/code/stage2/SCRIPT-NOTES.md) document prerequisites, guarded fixture reset, failure handling and ASE230 references. This machine's pre-existing PHP wrapper required host networking for CLI verification. This does not claim native Windows PHP. Docker testing used MySQL 8.4, PHP-FPM 8.4, NGINX and Laravel 13.39.0.
- **Documentation:** [Hugo tutorial](https://darealsun.github.io/stage2/), [half-page comparison](https://github.com/DaRealSun/campus-equipment-api/blob/main/presentation/comparison.md), [successful Actions build and Pages deployment](https://github.com/DaRealSun/DaRealSun.github.io/actions/runs/44746415198). The live tutorial and project links were checked. Pages hosts static documentation, not the API.
- **Limits:** finite local testing, no security audit or load test, custom auth maintenance, per-item catalog queries and a controller that could be split as policies grow. No production-readiness or measured performance improvement claim.
- **Personal/future checks:** sources and AI assistance are cited. The combined “I can explain” checkbox remains unclaimed pending student review. The separate Part 3 peer reviews occur after the December 10 deadline and are not represented as complete.
