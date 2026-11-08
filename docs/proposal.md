# One-Page Project Proposal

**Project: Campus Equipment Loan API — Option 2 (REST API).**

**Problem and users.** A small campus equipment desk needs a consistent way to track loanable equipment and who has checked it out. Separate spreadsheets can show conflicting availability or lose return records. Borrowers need to browse equipment and see their own loans; staff need to maintain the catalog. This is a course demonstration using synthetic data, not an official NKU service.

**Solution and scope.** Build a JSON REST API backed by MySQL and a small HTML/JavaScript test page. Use users, equipment, and loans tables. A loan belongs to one borrower and one physical equipment item. Each item can have at most one active loan. Self-registration creates borrower accounts only; staff accounts are seeded locally. The MVP excludes payments, university SSO, real student records, and email notifications.

**Two stages.** Stage 1 uses PHP 8.4+, PDO prepared statements, MySQL, explicit routing, and opaque bearer tokens stored as hashes. Stage 2 preserves the API contract using Laravel 13+, Composer 2.8+ (major 2), Eloquent, migrations, validation, policies, and Sanctum. Use Docker Compose for a reproducible PHP/MySQL environment, Bash scripts for setup/test tasks, Hugo for the report, Marp for slides, and GitHub Actions for documentation deployment.

**Ten planned APIs.** Paths are relative to `/api/v1`; both stages preserve paths and behavior.

| # | Method and path | Access | Expected successful behavior |
|---|---|---|---|
| 1 | POST /auth/register | Public | Create borrower; 201; never accept a client-supplied staff role |
| 2 | POST /auth/login | Public | Check password; return a bearer token; 200 |
| 3 | GET /equipment | Public | Return catalog and availability as JSON; 200 |
| 4 | GET /equipment/{id} | Public | Return one item; 200, or 404 if absent |
| 5 | POST /equipment | Bearer + staff | Add an item; 201 |
| 6 | PUT /equipment/{id} | Bearer + staff | Replace editable item fields; 200 |
| 7 | DELETE /equipment/{id} | Bearer + staff | Remove item with no loan history; 204; otherwise 409 |
| 8 | POST /loans | Bearer + borrower | Borrow an available item; 201; already on loan returns 409 |
| 9 | GET /loans | Bearer | Borrower sees only own loans; staff can inspect all; 200 |
| 10 | PUT /loans/{id}/return | Bearer + owner or staff | Mark an active loan returned; 200; repeated return is 409 |

**Quality and verification.** Specify acceptance assertions before implementation: malformed/missing input returns 400, unknown records return 404, missing/invalid tokens return 401, forbidden roles or another borrower's loan return 403, and conflicts return 409. Framework validation errors will be normalized to this contract. Authenticated loan creation uses a transaction and locks the equipment row to prevent simultaneous checkout. Passwords and bearer tokens are never stored in plaintext. Run the same black-box acceptance assertions against both stages, covering happy paths, all CRUD operations, unauthorized requests, ownership, invalid data, and duplicate checkout. Capture actual results and document limitations. Show one design change with affected/unaffected modules and passing regression tests; compare framework benefits with dependency/setup costs.

**Delivery plan.** Finalize planning by November 7, deliver Stage 1 by November 21, and Stage 2 plus HW5 by December 10, 2026, subject to the official Canvas schedule. This HW1 proposal is a plan; it does not claim that the API is implemented or tested.
