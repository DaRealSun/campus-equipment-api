# HW4 — Stage 1 Plan

Student: Minh Son Le. Project: Campus Equipment Loan API. Option 2.

**Part 1 preparation deadline:** November 7, 2026, 11:59 p.m. (missed).
**Plan prepared:** November 8, 2026. Late acceptance/reopening remains the instructor's decision.
**Stage 1 product deadline (HW4 Part 2):** November 21, 2026, 11:59 p.m., according to the Canvas schedule checked November 8.

This plan uses the same ten endpoints and PHP/MySQL -> Laravel stages proposed in HW1. It does not assert that the instructor has approved an unsubmitted proposal. Any requested scope change will be documented and agreed before implementation.

## HW4 Part 2 Submission

1. **When (Plan):** November 20, 2026, by 8:00 p.m., leaving November 21 as a buffer.
2. **When (Actual):** TBD — record the actual product submission date.
3. **Comment:** Deliver Stage 1 evaluation and Stage 2 plan with verified repository/setup/test/result links. Part 1 readiness is separate from the completed Stage 1 product.

## Milestone 1 — Preparation and Criteria

1. **What:** Same HW1 proposal; NGINX/PHP/MySQL readiness; schema/fixture starter; ten-endpoint acceptance matrix and executable assertions; dated plan and README.
2. **When (Plan):** November 8, 2026.
3. **When (Actual):** November 8, 2026 for local environment, criteria, runner and documents. GitHub/Canvas publication is tracked separately in the checklist.
4. **Comment:** Readiness passes; product acceptance currently fails against the 501 stub. Do not count those failures as completed features. HW1 readings and commitments were confirmed November 8; seek late acceptance for the prepared submissions.

## Milestone 2 — Catalog and Authentication Vertical Slices

1. **What:** Implement registration/login, password/token verification, catalog list/detail, and staff CRUD using PDO prepared statements and JSON contracts. Connect a small HTML/JavaScript test page to working endpoints.
2. **When (Plan):** November 12, 2026, by 8:00 p.m.
3. **When (Actual):** TBD.
4. **Comment:** Implement one end-to-end slice at a time and run its success/failure/authentication assertions. Self-registration cannot grant staff privileges.

## Milestone 3 — Loan Workflows and Full Acceptance

1. **What:** Implement checkout, scoped loan list and return. Verify ownership, missing/invalid tokens, conflicts and transaction-based prevention of double checkout. Run all shared assertions from reset synthetic fixtures; record actual results.
2. **When (Plan):** November 16, 2026, by 8:00 p.m.
3. **When (Actual):** TBD.
4. **Comment:** Keep HTTP/JSON contracts identical for the later Laravel stage. Record failed cases as remaining work rather than changing expectations.

## Milestone 4 — Design Change, Reproducibility and Review

1. **What:** Demonstrate one requirement-driven design change with affected/unaffected modules and regression results. Verify clean setup, persistence, README/tutorial/presentation links and a browser test page. Prepare the Stage 1 evaluation and Stage 2 plan.
2. **When (Plan):** November 19, 2026, by 8:00 p.m.
3. **When (Actual):** TBD.
4. **Comment:** Capture commands, actual results and limitations. Ask a reviewer to follow the instructions and resolve any reproducibility issue before the submission buffer.

## Submission Buffer and Peer Review

- Submit the Stage 1 product by the November 20 target; official deadline November 21.
- Review the three peers assigned in HW1 after the Stage 1 deadline using the assigned Canvas peer-review submission; target November 22, official deadline previously shown as November 23 at 11:59 p.m. Their names are recorded in the private HW1 rubric.
- After every milestone, append dated progress and Plan vs Actual updates in GitHub and on the official Canvas progress page. Preserve earlier reports.

## Risks and Responses

| Risk | Response |
|---|---|
| Missed Part 1 deadline or locked submission | Ask the instructor for late acceptance; do not claim a submission was made |
| PHP/NGINX/MySQL mismatch | Use the checked Compose environment and rerun the readiness probe |
| Authentication or concurrency takes longer | Prioritize minimal secure workflows; raise scope/schedule issues early |
| Framework response differences in Stage 2 | Preserve contract and assertions; adapt Laravel exception/validation responses |
| Lost setup or secret leakage | Keep generated `.env` out of Git; document setup and use synthetic fixtures |

AI assistance: OpenAI Codex drafted the plan under the student's direction. Actual dates are recorded only for verified work; future dates are commitments to review, not completed results.
