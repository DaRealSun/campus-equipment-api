---
marp: true
size: 16:9
paginate: true
theme: default
style: |
  section { font-family: Arial, sans-serif; padding: 54px 70px; font-size: 27px; color: #152c40; }
  h1 { color: #0a5361; font-size: 44px; }
  h2 { color: #0a5361; font-size: 36px; }
  section.lead { background: #123347; color: #f7fafb; }
  section.lead h1 { color: #82e3d1; font-size: 52px; }
  a { color: #087779; }
  table { font-size: 23px; }
  footer { font-size: 15px; }
footer: 'Minh Son Le · CSC640 · November 20, 2026'
---

<!-- _class: lead -->
# Quality needs evidence

Teach the next team member through HW3 and HW4.

Campus Equipment Loan API

---

## A running application can still be wrong

Borrower A tries to return borrower B's loan.

- Required response: **403 Forbidden**
- Required state: **no loan record changes**
- Check both. A correct status can hide an incorrect mutation.

The ownership example came from the student's course explanation.

---

## Turn attributes into criteria

| Attribute | Observable check |
|---|---|
| Functionality | Ten API contracts behave as specified |
| Access control | Invalid tokens and forbidden roles are rejected |
| Integrity | Two checkouts produce one active loan |
| Reproducibility | Documented scripts start a testable application |
| Maintainability | A policy change has a clear place and regression checks |

---

## Process makes uncertainty visible

- Build a vertical slice: data, endpoint, observable test.
- Preserve the original milestone target and record actual dates.
- Report a delay with its impact and next step.
- Keep the double-checkout test when the schedule slips.

Deleting a test hides risk. Reporting the risk helps the team decide.

---

## HW3: preserve five behaviors

Add, list, toggle, delete and clear completed tasks.

| Version | What it adds | Cost |
|---|---|---|
| JavaScript | Direct DOM and array operations | Manual UI synchronization |
| TypeScript | Strict Todo and event types | Compilation and annotations |
| React + TS | State owner and reusable components | Build tools and more concepts |

Browser checks still verify behavior. Types do not replace tests.

---

## One type error, one behavior error

`completed = 'false'` is a truthy string in JavaScript.

TypeScript's Boolean field catches that assignment.

But TypeScript cannot prove that **Clear Completed** retains unfinished tasks. The browser test must check that result.

---

## Design protects the invariant

Checkout must lock the equipment row, check active loans and create a loan inside one transaction.

- Both competing requests lock the same row.
- Eloquent relationships express user and equipment ownership.
- The contract hides PDO versus Eloquent from callers.

A framework cannot choose the business invariant for us.

---

## Fixed tests make the comparison useful

| Runtime | Shared acceptance assertions |
|---|---:|
| PHP/PDO, NGINX and MySQL | **44/44** |
| Laravel CLI startup path | **44/44** |
| Laravel Docker/NGINX startup path | **44/44** |

Additional checks: competing checkout, no mutation on denied return, availability filters and input bounds.

---

## Automation reduces repeated setup

- `run.sh`: configuration, MySQL, locked packages, migrations, Laravel.
- `setup.sh`: Docker build, PHP-FPM, NGINX and readiness checks.
- Hugo + Actions: publish documentation on push.
- Fixture deletion is separate and explicitly guarded.

Pages publishes documentation. It does not run the API.

---

## Automation also has costs

More dependencies, image downloads, configuration and network concepts.

This machine's PHP wrapper initially could not reach the database through its own localhost. Runtime checks exposed it.

No measured speed or monetary saving is claimed. Manual requirements, code review and result interpretation remain necessary.

---

## Limits guide the next change

- Passing a finite suite is not a security audit or load test.
- Custom bearer authentication remains maintenance work.
- The controller can be split when policies grow.
- Catalog queries need batching/pagination at scale.
- Dated revisions show actual progress, not invented weekly history.

---

## A repeatable team workflow

1. Name the behavior and quality attribute.
2. Define success, denial and unchanged-state expectations.
3. Choose relationships and transaction boundaries.
4. Implement a coherent change and run relevant checks.
5. Inspect data, errors and reproducible setup.
6. Publish actual results and limitations.

---

## Read the evidence

- [Quality report](https://darealsun.github.io/quality/)
- [Product tutorial](https://darealsun.github.io/stage2/)
- [API test reports](https://github.com/DaRealSun/campus-equipment-api/tree/main/docs/results)
- [HW3 code and screenshots](https://github.com/DaRealSun/campus-equipment-api/tree/main/code/hw3)
- [Two-stage comparison](https://github.com/DaRealSun/campus-equipment-api/blob/main/presentation/comparison.md)

---

## Sources and assistance

Course: CSC640 Todo lesson, ASE230, ASE285 and ASE420.
Technical: Laravel, TypeScript, React, Docker, Hugo and Marp official documentation. Links are in the report.

OpenAI Codex drafted code, report and slides and ran recorded tests. Student explanations informed ownership and schedule examples. No independent student authorship of AI prose is claimed.

The student must understand the work. Peer and instructor scores remain theirs to determine.
