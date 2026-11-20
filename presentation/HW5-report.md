---
title: 'Building quality software with evidence'
---

Minh Son Le · CSC 640 · November 20, 2026

## 1. Define quality through behavior

A campus borrower needs equipment without double booking it. Staff need to maintain the catalog and preserve loan history. Starting the program is not enough. Quality means meeting explicit behavior and access rules, preserving data, and making changes another team member can verify.

My explanation during the course was specific: if borrower A can return borrower B's loan, the product is not high quality. Expect HTTP 403 and no change to the loan. A status check alone is incomplete because a handler could change data before returning the error. The integrity test compares loan records before and after the denied request.

This report teaches the reasoning. The [Stage 2 tutorial](/stage2/) teaches how to run the product. The [repository](https://github.com/DaRealSun/campus-equipment-api) preserves both implementations.

| Attribute | Why it matters | Criterion and evidence |
|---|---|---|
| Functional suitability | Users must find, borrow and return equipment | Ten API contracts exercised by shared acceptance tests |
| Access control | One user's account must not gain another user's powers | Missing/invalid token, role and ownership tests |
| Data integrity | One item cannot have two active loans | Competing checkouts produce one 201, one 409 and one active loan |
| Maintainability | A team member must locate and change a policy | Routes, model relationships and bounded transactions |
| Reproducibility | Reviewers need a repeatable starting point | Scripts, locked dependencies, synthetic fixtures and reports |
| Usability | Callers need understandable failures | Consistent error.code/error.message JSON and a browser example |

These criteria do not establish production readiness. Performance, accessibility, recovery and resistance to every attack have not been verified. Production needs deployment-specific security, monitoring and backup/restore exercises.

## 2. Process exposes uncertainty early

A vertical slice includes a database change, an endpoint and an observable test. It gives earlier feedback than writing every table and route before trying a request. Acceptance criteria state what should happen. Test output records what did happen. A plan must not be presented as an executed result.

The [Stage 1 plan](https://github.com/DaRealSun/campus-equipment-api/blob/main/plan/HW4_plan_stage1.md) records a delay without rewriting the original target. My response was to set a realistic date, keep the double-checkout test and report the delay and next steps early. Deleting the test would hide risk. Reporting it helps others judge the tradeoff.

The [Stage 2 plan](https://github.com/DaRealSun/campus-equipment-api/blob/main/plan/HW4_plan_stage2.md) preserves targets and records actual dates. Documentation developed from November 2 and 8 planning pages, November 13 Stage 1 evidence, and this November 20 revision. This does not claim substantive HW5 writing in every week. [Revision history](https://github.com/DaRealSun/DaRealSun.github.io/commits/main/) lets reviewers assess actual development rather than commit count.

## 3. Design protects a business rule

Abstraction gives callers equipment resources and documented JSON instead of table knowledge. Encapsulation keeps token lookup and state changes behind controlled operations. Information hiding means callers need not know whether PDO or Eloquent reads the database. The API contract makes that replacement testable.

Eloquent models describe users, tokens, equipment and loans. Relationships express ownership. Authentication helpers resolve users and enforce roles. Checkout and return operations own transaction rules. The controller remains fairly large: an EquipmentService and authorization policies would help if policies or channels grow. Creating layers before they serve real changes also adds navigation and indirection.

Checkout locks the equipment row before checking active loans and inserting a loan. Competing requests lock the same row. Without this transaction boundary, two requests could both observe availability before either inserts. A framework cannot infer the invariant. The developer must identify it and test the competing requests.

The course's abstraction, polymorphism, inheritance, encapsulation and composition topics describe ways to manage change. Eloquent inheritance reuses persistence behavior, while relationships compose the domain. SOLID principles help question responsibilities and dependencies. Naming a principle does not prove a design is good; ask which change a boundary makes safer.

## 4. HW3 keeps behavior while changing structure

[HW3's JavaScript, TypeScript and React versions](https://github.com/DaRealSun/campus-equipment-api/tree/main/code/hw3) each add, list, toggle, delete and clear completed todos. Clear Completed retains unfinished tasks. Blank input is rejected. The README supplies commands and screenshots for each app.

JavaScript provides concise array operations and direct DOM updates. TypeScript adds strict compile-time checks. Assigning the string 'false' to completed is dangerous in JavaScript because it is truthy. A Boolean field catches that assignment during TypeScript compilation. It cannot prove that a filter removes the right records, so behavior tests remain necessary.

React makes App own the Todo array. The form and items receive data and report actions through callbacks. Immutable map/filter updates keep the list and count consistent. The cost is a build toolchain and more concepts for a small app. Recorded browser checks exercised all five behaviors, blank input, plain-text display of HTML-like input and console output. They do not establish compatibility with every browser.

## 5. HW4 compares a fixed contract

The [same runner](https://github.com/DaRealSun/campus-equipment-api/blob/main/code/tests/acceptance.py) exercises registration, login, equipment CRUD, borrowing, listing and returning. It also checks duplicate registration, injected roles, loan-history conflicts, ownership, missing/invalid tokens and forbidden roles.

| Runtime | Result | Executed evidence |
|---|---:|---|
| Stage 1 PHP/PDO, NGINX/MySQL | 44/44 | [Stage 1 JSON](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/results/stage1.json) |
| Stage 2 Laravel CLI path | 44/44 | [CLI JSON](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/results/stage2-host.json) |
| Stage 2 Docker, NGINX/PHP-FPM | 44/44 | [Container JSON](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/results/stage2-docker.json) |

Read the timestamp, base URL, passed/failed fields and individual cases. Reports come from actual responses. Expected outputs alone are not evidence of execution. Additional [integrity results](https://github.com/DaRealSun/campus-equipment-api/blob/main/docs/results/stage2-docker-integrity.txt) cover competing checkouts, unchanged data after denied returns, availability filters and input limits.

Stage 2 uses equivalent opaque bearer authentication instead of Sanctum to preserve the existing token contract. Passwords are bcrypt hashes. Only token digests are stored. Registration explicitly assigns borrower and ignores an injected staff role. Eloquent replaces application SQL with model operations. It reduces handwritten persistence details but introduces ORM behavior and dependency costs. The [comparison](https://github.com/DaRealSun/campus-equipment-api/blob/main/presentation/comparison.md) explains these tradeoffs.

## 6. Automation provides repeatability

`run.sh` prepares configuration, starts MySQL, installs locked packages, migrates and starts Laravel. `setup.sh` also builds PHP-FPM and NGINX. Neither deletes application data. Fixture resets are separate and require explicit opt-in plus the exact synthetic database name.

This machine's PHP command was a container wrapper. Its localhost was a different network namespace, so the first migration could not reach the database. Accounting for this made the CLI path work. [Verification notes](https://github.com/DaRealSun/campus-equipment-api/blob/main/code/stage2/SCRIPT-NOTES.md) disclose the detail rather than claiming native Windows PHP. A successful install is not enough: verify a running request and a database operation.

Hugo builds this report and GitHub Actions publishes it on push. Pages hosts documentation, not Laravel. A green publication job proves documentation deployment, not API correctness. Saved API results provide separate evidence. An API acceptance CI job remains a useful improvement and is not claimed by the documentation workflow.

## 7. A change workflow for the next team member

1. Name the behavior and quality attribute, such as allowing borrowers to extend only their own loans.
2. Define success, denied-access and unchanged-state expectations before editing.
3. Identify relationships and the transaction boundary. Decide what must remain true under competing requests.
4. Implement a small coherent change. Run shared acceptance and relevant integrity tests.
5. Inspect data and errors, not only status codes. Confirm the README still works from its prerequisites.
6. Record actual results and limitations. Update the plan, progress and tutorial with evidence links.

Passing tests does not prove the tests express the right requirements. AI can draft quickly and still omit authorization. Challenge assumptions and require evidence. The current catalog also makes per-item availability queries; batching and pagination should be evaluated before large-scale use. No throughput or monetary improvement has been measured.

## Sources, assistance and limits

Course sources: [CSC640 Todo lesson](https://github.com/nkuase/csc640/tree/main/pdf/2%20High%20level%20languages%20-%20Todo%20app), [ASE230 PHP/Laravel/automation](https://github.com/nkuase/ase230), [ASE285 process](https://github.com/nkuase/ase285/tree/main/module1), [ASE420 design](https://github.com/nkuase/ase420/tree/main/module1). Technical references: [Laravel routing](https://laravel.com/framework/docs/13.x/routing), [Eloquent](https://laravel.com/framework/docs/eloquent), [transactions](https://laravel.com/framework/docs/database), [TypeScript strict](https://www.typescriptlang.org/tsconfig/strict.html), [React array updates](https://react.dev/learn/updating-arrays-in-state), [Compose](https://docs.docker.com/compose/), [Hugo](https://gohugo.io/documentation/) and [Marp](https://marp.app/).

OpenAI Codex drafted code, tests, report and slides, ran the recorded verification and prepared publication. The ownership example and schedule-delay response reflect explanations Minh Son Le gave during the course discussion. This report does not claim independent student authorship of AI prose or personal execution of automated tests. The student remains responsible for understanding the work. Future peer reviews and instructor scores are not completed or predicted.
