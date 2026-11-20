---
marp: true
html: true
size: 4:3
paginate: true
style: |
  section.small {
    font-size: 22px;
  }
---

<!-- _class: lead -->
<!-- _class: frontpage -->
<!-- _paginate: skip -->

# HW3 Rubric

Proposed self-grade: 200/200. The student confirmed completing the lesson reading on November 19, 2026. OpenAI Codex reviewed the lesson and examples, drafted the explanations, and verified the implementations. Final grading is determined by the instructor.

---

## How to Grade Yourself

- **Full points**: the row is completely met.
- **0**: not done, or (for an app) it does not work when run from the README.
- Notice that there are no partial points.

The grader can regrade every row. F1–F5 are the Todo behaviors defined in `HW3.md`.

---

## Assignment 1: Understand the Material (50)

<!-- _class: small -->

| Row | Done when | Points | Earned |
|---|---|---:|---|
| 1-1 | I read the **5 High-level Programming Language** lesson and reviewed the provided JavaScript, TypeScript, and React Todo-app examples | 10 | 10 |
| 1-2 | I explained, in my own words, why languages evolved from low-level to high-level | 10 | 10 |
| 1-3 | I compared JavaScript, TypeScript, and React: what each adds and when to use it | 10 | 10 |
| 1-4 | I explained how type safety improves code quality, with an example | 10 | 10 |
| 1-5 | I explained how components improve development, with an example | 10 | 10 |
| | **Total** | **50** | **50** |

---

### Assignment 1 Answers

**1-2. Low-level to high-level languages:**

> A low-level program spells out details close to the machine, such as where data is stored and which operations run. A high-level language lets me describe a list of tasks and filter it with one expression while the runtime handles many of those details. This makes the program easier to read and move between systems.

**1-3. JavaScript vs TypeScript vs React:**

> JavaScript is the language the browser runs. I can use it directly for a small page that updates the DOM. TypeScript adds static checks and compiles to JavaScript, which helps when data and functions are shared across many files. React is a UI library. It helps when a page is built from components that display shared state and report user actions back to their parent.

**1-4. Type safety:**

> The Todo type declares that completed is a Boolean. If I try to assign the string 'false', strict TypeScript catches the mistake at build time. In JavaScript that string is truthy, so a clear-completed filter could accidentally remove an unfinished task. Tests still have to check that the filter uses the Boolean correctly.

**1-5. Components:**

> TodoForm handles the input and reports a new task through onAdd. TodoList maps the array to TodoItem components. Each item shows its task and sends its ID back through the toggle and delete callbacks. App owns the whole array, so the list and completion count stay in sync. I can change the row layout without changing how tasks are stored.

---

## Assignment 2: JavaScript Todo (50)

<!-- _class: small -->

| Row | Done when | Points | Earned |
|---|---|---:|---|
| 2-1 | The page opens from the README and uses semantic HTML: an input, an add button, and a list container | 10 | 10 |
| 2-2 | F1 and F2 work | 10 | 10 |
| 2-3 | F3, F4, and F5 work; **Clear Completed** removes only completed todos | 10 | 10 |
| 2-4 | Todos are stored in an array; each has `id`, `text`, and `completed` | 10 | 10 |
| 2-5 | Code quality: meaningful names, consistent formatting, comments only where intent is unclear, no console errors | 10 | 10 |
| | **Total** | **50** | **50** |

---

## Assignment 3: TypeScript Todo (50)

<!-- _class: small -->

| Row | Done when | Points | Earned |
|---|---|---:|---|
| 3-1 | `tsconfig.json` enables `strict`, and the app compiles with no errors using the README steps | 10 | 10 |
| 3-2 | A `Todo` type; typed parameters and return values; no `any` | 20 | 20 |
| 3-3 | F1–F5 work, including **Clear Completed**; DOM elements are typed and null-checked, and event handlers are typed | 15 | 15 |
| 3-4 | Readable typed code; TypeScript features are used where they help, not forced | 5 | 5 |
| | **Total** | **50** | **50** |

---

## Assignment 4: React + TypeScript Todo (50)

<!-- _class: small -->

| Row | Done when | Points | Earned |
|---|---|---:|---|
| 4-1 | The React + TypeScript project installs and runs using the README steps | 10 | 10 |
| 4-2 | Components with clear responsibilities: `App` owns the state; separate components for the form, the list, and an item (a similar split is fine) | 15 | 15 |
| 4-3 | `useState` holds the todos, and updates are immutable (no direct mutation) | 10 | 10 |
| 4-4 | F1–F5 work, including **Clear Completed**; events flow up through props/callbacks | 15 | 15 |
| | **Total** | **50** | **50** |

---

## Total

| Assignment | Points | Earned |
|---|---:|---|
| 1. Understand the Material | 50 | 50 |
| 2. JavaScript Todo | 50 | 50 |
| 3. TypeScript Todo | 50 | 50 |
| 4. React + TypeScript Todo | 50 | 50 |
| **Total** | **200** | **200** |

---

## Before You Submit

<!-- _class: small -->

- [x] Each app runs by following my README, with no console errors.
- [x] My README has, for each app, its folder, prerequisites, install/build/run commands (or states that none are needed), and one relative-link screenshot.
- [x] My README cites the course examples, documentation, and AI assistance I used.
- [x] My zip has this rubric, `README.md`, and the three app folders, without `node_modules/` or `dist/`.
- [ ] I understand that I will get points deducted (50%) when I add `node_modules/` or `dist/`.
- [ ] I will use HW3 answers for my HW5 submission.
- [ ] I can explain every line I submitted.
