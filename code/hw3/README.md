# HW3 · One Todo App, Three Ways

Minh Son Le · CSC 640

Each version adds tasks, displays the list, toggles completion, deletes one task, and clears completed tasks. Whitespace-only input is ignored. The array starts empty each time you reload the page.

## JavaScript

Folder: `javascript-todo/`. Prerequisite: a current desktop browser. No install or build step. Open `javascript-todo/index.html` in the browser.

[Screenshot of the JavaScript app](javascript-todo/screenshot.png)

## Strict TypeScript

Folder: `typescript-todo/`. Prerequisites: Node.js 24 and npm. From the extracted submission folder:

```sh
cd typescript-todo
npm ci
npm run build
```

Open `typescript-todo/index.html` in the browser after building. The build compiles `app.ts` into `dist/app.js`. No web server is needed.

[Screenshot of the TypeScript app](typescript-todo/screenshot.png)

## React + TypeScript

Folder: `react-todo/`. Prerequisites: Node.js 24 and npm. From the extracted submission folder:

```sh
cd react-todo
npm ci
npm run build
npm run preview -- --host 127.0.0.1 --port 4173 --strictPort
```

Open `http://127.0.0.1:4173/`. Press Ctrl+C in the terminal to stop the preview.

[Screenshot of the React + TypeScript app](react-todo/screenshot.png)

## Verification

Tested with Node.js 24.14.0, npm 11.9.0, and Chrome. For each app, the same browser check rejected blank text, added tasks with Add and Enter, showed the list in order, toggled a task in both directions, deleted one task, and cleared two completed tasks while retaining the incomplete task. It also checked that HTML-looking input was displayed as plain text, and observed no console warnings or errors during the workflow. The screenshots show one completed and two incomplete tasks.

## Sources and AI assistance

- [CSC 640 Todo lesson](https://github.com/nkuase/csc640/blob/main/pdf/2%20High%20level%20languages%20-%20Todo%20app/todo-app.pdf) and [instructor Todo examples](https://github.com/nkuase/csc640/tree/main/code/2%20High%20level%20languages%20-%20Todo%20app) informed the array operations, Todo type, and component split. The submitted versions use independent implementations with Clear Completed, form validation and plain-text rendering.
- [TypeScript strict mode](https://www.typescriptlang.org/tsconfig/strict.html), [React's immutable array update guide](https://react.dev/learn/updating-arrays-in-state), and [Vite's run and build guide](https://vite.dev/guide/) support the corresponding implementation choices.
- OpenAI Codex drafted the apps and rubric explanations, ran the builds and browser checks, and prepared the screenshots and ZIP.
