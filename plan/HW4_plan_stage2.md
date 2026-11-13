---
marp: true
---

# HW4 Stage 2 Plan

Minh Son Le · Campus Equipment Loan API · Option 2

**Part 3 deadline:** December 10, 2026, 11:59 p.m. Eastern, per the Canvas schedule previously checked.

**Planned submission:** December 9, 2026, 8:00 p.m. Eastern.
**Actual submission:** Pending.

The GitHub plan is the master copy. Record actual dates and reasons for schedule changes as milestones finish.

---

## Prepare Laravel and preserve the API contract

**Planned:** November 25, 2026, 8:00 p.m. Eastern. **Actual:** Pending.

Add Laravel routes, controllers, Eloquent models, migrations, relationships, and seeded synthetic fixtures. Use Sanctum or equivalent bearer authentication. Match the Stage 1 status codes, response shapes, staff and borrower roles, and ownership checks.

Preparation on November 13: Laravel 13.39.0 ran in WSL. After initializing its local application key, both starter tests passed, including the HTTP feature test. This was a framework smoke test. The shared API suite will be the Stage 2 gate.

---

## Run both versions with the same assertions

**Planned:** December 2, 2026, 8:00 p.m. Eastern. **Actual:** Pending.

Adapt the ASE 230 example into `run.sh` for the local Laravel application and `setup.sh` for Docker Compose. Verify both entry points from documented prerequisites. Run the shared acceptance suite against Stage 1 and both Stage 2 setups. Store separate reports and compare the same cases side by side. Verify simultaneous checkout and unauthorized return behavior.

---

## Publish the tutorial and evaluation evidence

**Planned:** December 8, 2026, 8:00 p.m. Eastern. **Actual:** Pending.

Adapt the Marp tutorial into Hugo, verify GitHub Actions publishes the documentation and that its project links open, and write `comparison.md` covering automation benefits, costs, manual work, and linked test results. Verify the published repository identifies both runnable versions. Score the Stage 2 rubric against measured evidence.

---

## Progress and peer review

Keep the Canvas progress page linked to this master plan. Report each week's goal and result, and update Plan vs Actual here as milestones finish.

Complete the separate Part 3 peer review after the assignment deadline. Follow each assigned peer's setup and tests, verify one documentation claim, and submit the review by the Canvas deadline.

Sources: revised `stage2_evaluation.md` and ASE 230 Module 2 *2. Laravel Installation and Project Structure.pdf* and *5-2. run1-5.sh.pdf*. OpenAI Codex helped draft the plan and verify the Laravel starter.
