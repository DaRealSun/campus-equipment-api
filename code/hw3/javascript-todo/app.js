function startTodoApp() {
  const form = document.getElementById('todo-form');
  const input = document.getElementById('todo-text');
  const list = document.getElementById('todo-list');
  const summary = document.getElementById('summary');
  const clear = document.getElementById('clear-completed');

  if (!(form instanceof HTMLFormElement) || !(input instanceof HTMLInputElement) ||
      !(list instanceof HTMLUListElement) || !summary ||
      !(clear instanceof HTMLButtonElement)) {
    throw new Error('Todo controls are missing from the page.');
  }

  let todos = [];
  let nextId = 1;

  const toggleTodo = (id) => {
    todos = todos.map(todo => todo.id === id ? { ...todo, completed: !todo.completed } : todo);
    render();
    list.querySelector(`#task-${id}`)?.focus();
  };

  const deleteTodo = (id) => {
    todos = todos.filter(todo => todo.id !== id);
    render();
    input.focus();
  };

  const render = () => {
    list.replaceChildren(...todos.map(todo => {
      const row = document.createElement('li');
      row.classList.toggle('completed', todo.completed);

      const label = document.createElement('label');
      const checkbox = document.createElement('input');
      checkbox.id = `task-${todo.id}`;
      checkbox.type = 'checkbox';
      checkbox.checked = todo.completed;
      checkbox.addEventListener('change', () => toggleTodo(todo.id));

      const name = document.createElement('span');
      name.textContent = todo.text;
      label.append(checkbox, name);

      const remove = document.createElement('button');
      remove.type = 'button';
      remove.className = 'delete';
      remove.textContent = 'Delete';
      remove.setAttribute('aria-label', `Delete ${todo.text}`);
      remove.addEventListener('click', () => deleteTodo(todo.id));
      row.append(label, remove);
      return row;
    }));

    const completed = todos.filter(todo => todo.completed).length;
    summary.textContent = todos.length === 0 ? 'No tasks yet.' : `${completed} of ${todos.length} completed`;
    clear.disabled = completed === 0;
  };

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const text = input.value.trim();
    if (!text) return;
    todos = [...todos, { id: nextId++, text, completed: false }];
    form.reset();
    render();
    input.focus();
  });

  clear.addEventListener('click', () => {
    todos = todos.filter(todo => !todo.completed);
    render();
    input.focus();
  });

  render();
}
startTodoApp();
