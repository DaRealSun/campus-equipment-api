# Stage 1 and Stage 2 Acceptance Contract

Student: Minh Son Le. Project: Campus Equipment Loan API, Option 2.
Prepared: November 8, 2026. Part 1 defines criteria before implementation.

## Contract

API prefix: `/api/v1`. Responses are UTF-8 JSON with `Content-Type: application/json`, except successful DELETE uses HTTP 204 with an empty body. Success objects use `{"data": ...}`; lists use `{"data": [...]}`. Errors use `{"error":{"code":"machine_readable_code","message":"human-readable explanation"}}`. IDs are positive JSON integers. Password hashes, stored token hashes, and other users' private data must never appear in API responses.

The public catalog may expose equipment availability. `GET /equipment?available=true` or `false` is supported; any other `available` value returns 400. Authentication is checked before role/ownership and mutation. Registration always creates a borrower, even if the client sends `role=staff`. Staff accounts are seeded locally.

## Success and failure criteria for every endpoint

| API | Successful case and expected content | Failure case and expected content | Executable cases |
|---|---|---|---|
| POST /auth/register | Valid name/email/password -> 201; `data.id` positive, supplied email, `role=borrower` | Missing email -> 400 JSON error; existing email -> 409 error. Client staff-role injection still creates borrower | E01-success, E01-invalid, E01-duplicate, E01-role-injection |
| POST /auth/login | Correct fixture credentials -> 200; nonempty `data.token`, `data.token_type=Bearer` | Wrong password -> 401 JSON error | E02-success, E02-wrong-password |
| GET /equipment | 200; array contains fixture item IDs 1001 and 1002 | `available=invalid` -> 400 JSON error | E03-success, E03-invalid-filter |
| GET /equipment/{id} | Item 1001 -> 200; name Tripod, correct ID, availability true before checkout | Absent ID 99999999 -> 404 JSON error | E04-success, E04-missing |
| POST /equipment | Staff token and valid item -> 201; positive item ID and supplied name | Empty name with staff token -> 400; borrower token -> 403; missing/invalid token -> 401 | E05-success, E05-invalid, E05-borrower-forbidden, E05-missing-token, E05-invalid-token |
| PUT /equipment/{id} | Staff updates created item -> 200; matching ID/name; subsequent GET reflects update | Absent ID -> 404; borrower -> 403; missing/invalid token -> 401 | E06-success, E06-readback, E06-missing, E06-borrower-forbidden, E06-missing-token, E06-invalid-token |
| DELETE /equipment/{id} | Staff deletes new item with no loan history -> 204 empty; subsequent GET -> 404 | Item 1002 with loan history -> 409; borrower -> 403; missing/invalid token -> 401 | E07-success, E07-readback, E07-history-conflict, E07-borrower-forbidden, E07-missing-token, E07-invalid-token |
| POST /loans | Borrower 102 checks out available item 1001 -> 201; positive loan ID, correct borrower/item, `returned_at=null` | Item already on loan -> 409; invalid ID type -> 400; absent item -> 404; missing/invalid token -> 401 | E08-success, E08-unavailable, E08-invalid, E08-missing-item, E08-missing-token, E08-invalid-token |
| GET /loans | Borrower -> 200 list contains new own loan and no other borrower's records; staff -> 200 includes fixture loan 2001 belonging to borrower 103 | Missing/invalid token -> 401 JSON error | E09-success, E09-staff-scope, E09-missing-token, E09-invalid-token |
| PUT /loans/{id}/return | Owner returns new loan -> 200; matching ID, nonempty `returned_at` | Returning another user's loan -> 403; repeat return -> 409; absent ID -> 404; missing/invalid token -> 401 | E10-success, E10-forbidden-owner, E10-repeated, E10-missing, E10-missing-token, E10-invalid-token |

Every protected endpoint has a valid-token success assertion and separate missing/invalid-token assertions. Catalog writes additionally check the borrower is forbidden. Loan return checks ownership. The same assertions apply to both implementations; Stage 2 must normalize Laravel validation failures to the agreed 400 contract.

## Test data setup

Use only the isolated `csc640_equipment` course database. From `code/stage1`:

```bash
bash scripts/start.sh
docker compose exec -T -e ALLOW_FIXTURE_RESET=1 php php /app/scripts/reset-fixtures.php
```

The explicit reset recreates only synthetic course fixtures in this isolated database:

- Users: staff 101 (`staff@example.test`), borrower 102 (`borrower1@example.test`), borrower 103 (`borrower2@example.test`). Their synthetic password is `CourseFixturePassword-42`.
- Equipment: 1001 Tripod (available), 1002 Camera (borrowed).
- Loan: 2001 belongs to user 103 for equipment 1002 and is active.
- Test-only bearer tokens: `fixture-staff-token`, `fixture-borrower1-token`, `fixture-borrower2-token`. The database stores SHA-256 hashes, with a one-day expiry. Reset before each run.
- The runner registers two additional synthetic users and creates, updates, deletes and borrows synthetic records. These values have no use outside this localhost test environment.

## Execute and record

From the repository root:

```bash
python3 code/tests/environment.py --base-url http://127.0.0.1:8640
python3 code/tests/acceptance.py --base-url http://127.0.0.1:8640 --stage stage1 --output docs/results/stage1.json
```

For Stage 2, create equivalent fixtures with Laravel's seeder/Sanctum tokens, change the base URL and `--stage stage2`/output path, and preserve the assertions. The seeder must make the same fixture token strings authenticate as the same users. Stage 2 internal token storage can differ. Never weaken assertions to conceal a failure; document and obtain approval for any scope/contract change, then apply it to both stages.

The Python runner checks status and content, prints PASS/FAIL, writes stage, base URL, UTC timestamp, expected/actual status and assertion errors, and exits **1** if any assertion fails. It uses only Python's standard library. Run fixtures and cases in a fresh sequence; dependent create/update/delete/return cases are intentionally ordered.

## Part 1 baseline and Stage 1 results

The environment readiness test passed on November 8: NGINX forwards an HTTP request to PHP-FPM; PDO queries an actual MySQL readiness row and the response matches HTTP 200 JSON.

The business API is not implemented in Part 1. Its explicit stub returns 501. The baseline acceptance run therefore fails; see `docs/results/part1-contract-baseline.json`. Those failures record remaining Stage 1 work, not completed product functionality. A completed Stage 1 submission must replace this with passing results against the real APIs.

On November 13, the implemented Stage 1 API passed all 44 assertions in [the Stage 1 run](docs/results/stage1.json). The [progress report](docs/progress/2026-11-13.md) records the supplementary concurrency, ownership, input limit, filtering, browser, and restart checks. Token hashes and password hashes are omitted from all API response projections.

## Sources and assistance

Criteria derive from the student's HW1 proposal and `HW4.md`/`HW4_part1.md` in the supplied archive. ASE 230 Module 1 is the reference for PHP, NGINX, MySQL and API testing; Modules 2–3 support the later Laravel/automation stage. OpenAI Codex drafted the criteria, runner and preparation environment; the student must review and understand the work.
