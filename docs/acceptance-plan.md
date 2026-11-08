# Acceptance plan — planned, not executed

| Case | Expected result |
|---|---|
| Register valid borrower; attempt staff role injection | 201 borrower; role remains borrower |
| Duplicate email or missing fields | 409 duplicate; 400 invalid input |
| Valid login / wrong password | 200 token / 401 JSON error |
| List equipment; retrieve item; retrieve missing item | 200 list; 200 object; 404 error |
| Staff creates and updates equipment | 201 then 200; read-back matches saved values |
| Missing or invalid bearer token on protected routes | 401; no mutation |
| Borrower attempts catalog mutation | 403; no mutation |
| Delete equipment with no loan history | 204 empty body; subsequent GET returns 404 |
| Borrow available equipment | 201 loan belonging to caller; item unavailable |
| Concurrent or repeated checkout of one item | Exactly one succeeds; other request gets 409 |
| Read /loans as borrower | 200; contains no other borrower's records |
| Return own active loan; repeat return | 200; item available; repeat is 409 |
| Return another borrower's loan | 403; loan unchanged |
| Delete item with any loan history | 409; history retained |
| Injection-shaped string input | Treated as data; no SQL execution or sensitive error |

Use isolated synthetic fixtures. Store timestamps, stage/base URL, expected/actual status and payload checks, command, and pass/fail output. Re-run after the design change. Passing these tests supports only the tested behavior; it does not prove production readiness. Unit and integration tests will supplement these shared acceptance assertions.
