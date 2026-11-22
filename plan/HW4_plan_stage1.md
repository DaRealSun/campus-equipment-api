# HW4 — Stage 1 Plan

Student: Minh Son Le. Project: Campus Equipment Loan API. Option 2.

**Part 1 preparation deadline:** November 7, 2026, 11:59 p.m. (original). Canvas reopened Part 1 through November 12, 2026. The complete three-file attempt was recorded November 12 at 10:02 p.m.
**Plan prepared:** November 8, 2026.
**Stage 1 product deadline (HW4 Part 2):** November 21, 2026, 11:59 p.m., according to the Canvas schedule checked November 8.

This plan uses the same ten endpoints and PHP/MySQL to Laravel stages proposed in the submitted HW1 rubric.

## HW4 Part 2 Submission

1. **When (Plan):** November 20, 2026, by 8:00 p.m., leaving November 21 as a buffer.
2. **When (Actual):** November 13, 2026, at 6:48 p.m. Eastern. [Canvas receipt](https://nku.instructure.com/courses/92884/assignments/1628199/submissions/166731) shows both `stage1_evaluation.md` and `HW4_plan_stage2.md`.
3. **Comment:** Submitted before the November 20 internal target and November 21 product deadline. This actual submission field was reconciled on November 22 from the Canvas receipt; it was previously left as TBD. The submitted `hw4-stage1` tag remains unchanged. Part 1 readiness is separate from the completed Stage 1 product.

## Milestone 1 — Preparation and Criteria

1. **What:** Same HW1 proposal; NGINX/PHP/MySQL readiness; schema/fixture starter; ten-endpoint acceptance matrix and executable assertions; dated plan and README.
2. **When (Plan):** November 8, 2026.
3. **When (Actual):** November 8, 2026 for local environment, criteria, runner and documents. GitHub/Canvas publication is tracked separately in the checklist.
4. **Comment:** On November 8, readiness passed and product acceptance failed against the 501 stub (0/44). This was the preparation baseline, not completed features. HW1 readings and commitments were confirmed November 8. Later passing results are recorded under milestones 2–4; the late Part 1 history is retained above.

## Milestone 2 — Catalog and Authentication Vertical Slices

1. **What:** Implement registration/login, password/token verification, catalog list/detail, and staff CRUD using PDO prepared statements and JSON contracts. Connect a small HTML/JavaScript test page to working endpoints.
2. **When (Plan):** November 12, 2026, by 8:00 p.m.
3. **When (Actual):** November 13, 2026.
4. **Comment:** The planned November 12 target slipped. The first verified catalog and authentication run occurred November 13, after the completed Part 1 attempt. The browser test page also exercised each catalog operation.

## Milestone 3 — Loan Workflows and Full Acceptance

1. **What:** Implement checkout, scoped loan list and return. Verify ownership, missing/invalid tokens, conflicts and transaction-based prevention of double checkout. Run all shared assertions from reset synthetic fixtures; record actual results.
2. **When (Plan):** November 16, 2026, by 8:00 p.m.
3. **When (Actual):** November 13, 2026.
4. **Comment:** The shared run passed 44/44 assertions. Concurrent checkout, staff return, and preservation of loan records after a forbidden return passed the supplementary checks.

## Milestone 4 — Design Change, Reproducibility and Review

1. **What:** Demonstrate one requirement-driven design change with affected/unaffected modules and regression results. Verify clean setup, persistence, README/tutorial/presentation links and a browser test page. Prepare the Stage 1 evaluation and Stage 2 plan.
2. **When (Plan):** November 19, 2026, by 8:00 p.m.
3. **When (Actual):** November 13, 2026, for implementation verification and documentation. Independent peer review follows the Canvas deadline.
4. **Comment:** The browser exercised all ten endpoints. The MySQL restart preserved data. The checkout locking decision and supporting checks are in the November 13 progress report. The tutorial is available as Marp Markdown and PDF.

## Submission Buffer and Peer Review

- Stage 1 product: submitted November 13 at 6:48 p.m. Eastern; internal target November 20, official deadline November 21.
- Stage 1 peer reviews: completed for all three assigned peers and submitted November 22. Latest receipt verified November 22 at 9:26 a.m. Eastern, before the November 23, 11:59 p.m. peer-review deadline. [Canvas peer-review receipt](https://nku.instructure.com/courses/92884/assignments/1640996/submissions/166731). The private review stays in Canvas.
- After every milestone, append dated progress and Plan vs Actual updates in GitHub and on the official Canvas progress page. Preserve earlier reports.

## Evidence and maintenance record

The [master plan index](README.md) links both stages. The [November 22 reconciliation](../docs/progress/2026-11-22.md) links the original November 8 and November 13 reports, November 20 regression evidence, and verified submission receipts. This reconciliation was written November 22; it does not represent a report posted earlier.

Three of four original milestones finished on time: 10 × 3/4 = **7.5/10** for the rubric's milestone-date row. The November 12 milestone remained late and was not rescheduled retrospectively. The maintenance row is for the instructor to assess from the dated history, including the late corrections documented here.

## Risks and Responses

| Risk | Response |
|---|---|
| Missed Part 1 deadline or locked submission | Ask the instructor for late acceptance; do not claim a submission was made |
| PHP/NGINX/MySQL mismatch | Use the checked Compose environment and rerun the readiness probe |
| Authentication or concurrency takes longer | Prioritize minimal secure workflows; raise scope/schedule issues early |
| Framework response differences in Stage 2 | Preserve contract and assertions; adapt Laravel exception/validation responses |
| Lost setup or secret leakage | Keep generated `.env` out of Git; document setup and use synthetic fixtures |

AI assistance: OpenAI Codex drafted the plan under the student's direction. Actual dates are recorded only for verified work; future dates are commitments to review, not completed results.
