---
marp: true
size: 4:3
paginate: true
---

# HW4 Part 2 · Stage 1 Evaluation

Minh Son Le · Campus Equipment Loan API · Option 2

[Submitted version: hw4-stage1](https://github.com/DaRealSun/campus-equipment-api/tree/hw4-stage1)

**Evaluation revised:** November 22, 2026. The Stage 1 release and original milestone targets are unchanged. Progress-documentation corrections below were made November 22, not backdated.

---

## Schedule · 15 / 20

| Revised rubric criterion | Available | Earned |
|---|---:|---:|
| Milestones met planned dates | 10 | 7.5 |
| Plan vs Actual maintained and linked from Canvas throughout | 10 | 7.5 |

Three of four planned milestones met their original target dates: 10 × 3/4 = 7.5. The November 12 catalog/authentication target was verified November 13, so the late milestone remains a deduction.

I propose 7.5/10 for progress maintenance, with partial credit supported by the dated November 8 preparation report, November 13 implementation report and November 20 regression report, now linked through the master plan and Canvas. The November 22 reconciliation adds the verified submission dates and removes Canvas's stale duplicate table. Because these corrections were late, this does not claim perfect maintenance throughout; the instructor may adjust this row based on the original history.

[Current master Plan vs Actual](https://github.com/DaRealSun/campus-equipment-api/blob/main/plan/HW4_plan_stage1.md) · [Original release plan](https://github.com/DaRealSun/campus-equipment-api/blob/hw4-stage1/plan/HW4_plan_stage1.md)

---

## Progress evidence and submission record

- November 8: readiness passed; 0/10 business endpoints and 0/44 product assertions passing. [Original report](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/progress/2026-11-08.md).
- November 13: all ten endpoints exercised; 44/44 assertions passing; ownership, concurrent checkout and persistence checks recorded. [Original report](https://github.com/DaRealSun/campus-equipment-api/blob/hw4-stage1/docs/progress/2026-11-13.md).
- November 13 at 6:48 p.m. Eastern: both Part 2 files submitted before the November 21 product deadline. [Canvas product receipt](https://nku.instructure.com/courses/92884/assignments/1628199/submissions/166731).
- November 20: Stage 1 recheck remained 44/44 while both Laravel paths passed the same suite. [Regression report](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/progress/2026-11-20.md).
- November 22: corrected missing upload actual date, replaced stale Canvas milestone table with the master link, and recorded all three peer reviews. [Reconciliation and evidence timeline](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/progress/2026-11-22.md).

[Master plan index](https://github.com/DaRealSun/campus-equipment-api/blob/main/plan/README.md) · [Progress index](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/progress/README.md) · [Official Canvas progress page](https://nku.instructure.com/courses/92884/pages/individual-project-progress-page-of-minh-son-le-in-csc640)

---

## Implementation and Quality · 60 / 60

| Revised rubric criterion | Available | Earned |
|---|---:|---:|
| Ten working PHP/MySQL endpoints through NGINX | 20 | 20 |
| Six protected endpoints verified | 10 | 10 |
| Executable success and failure tests with saved results | 15 | 15 |
| Browser test page exercises every endpoint | 5 | 5 |
| MySQL CRUD, schema and setup | 10 | 10 |

All 44 acceptance assertions passed. Chrome exercised all ten endpoints. Two simultaneous checkouts produced one active loan, and a forbidden return preserved the loan records.

[Acceptance results](https://github.com/DaRealSun/campus-equipment-api/blob/hw4-stage1/docs/results/stage1.json) · [Verification details](https://github.com/DaRealSun/campus-equipment-api/blob/hw4-stage1/docs/progress/2026-11-13.md)

---

## Tutorial and Reproducibility · 20 / 20

| Revised rubric criterion | Available | Earned |
|---|---:|---:|
| Marp tutorial documents inputs, outputs and authentication | 10 | 10 |
| Observed successful and failed examples | 5 | 5 |
| Versioned README with setup, run and test steps | 5 | 5 |

[Marp source](https://github.com/DaRealSun/campus-equipment-api/blob/hw4-stage1/presentation/stage1-tutorial.md) · [PDF](https://github.com/DaRealSun/campus-equipment-api/blob/hw4-stage1/presentation/stage1-tutorial.pdf) · [README and run commands](https://github.com/DaRealSun/campus-equipment-api/blob/hw4-stage1/README.md)

---

## Self-evaluation · 95 / 100

The proposed score is 15 for schedule, 60 for implementation, and 20 for reproducibility. The increase from 92.5 to 95 requests partial reconsideration of the progress-maintenance row using the clarified evidence. It does not change the late milestone or claim that corrections were made earlier. The instructor determines the final score.

OpenAI Codex assisted with implementation, test execution, and drafting. Course slide references are in the tutorial. The Stage 2 plan links milestones to the revised Stage 2 rubric and records the Laravel starter smoke test.

The separate HW4 Part 2 peer review includes all three assigned projects and their setup, tests and checked documentation claims. The latest [Canvas peer-review receipt](https://nku.instructure.com/courses/92884/assignments/1640996/submissions/166731) records November 22 at 9:26 a.m. Eastern, before the November 23, 11:59 p.m. peer-review deadline.
