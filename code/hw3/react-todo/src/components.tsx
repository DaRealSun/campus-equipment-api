import { useRef, useState } from 'react';
import type { FormEvent } from 'react';
import type { Todo } from './App';

export function TodoForm({ onAdd }: { onAdd: (text: string) => void }) {
  const [text, setText] = useState('');
  const input = useRef<HTMLInputElement>(null);

  function submit(event: FormEvent<HTMLFormElement>): void {
    event.preventDefault();
    const task = text.trim();
    if (!task) return;
    onAdd(task);
    setText('');
    input.current?.focus();
  }

  return (
    <form id="todo-form" onSubmit={submit}>
      <label htmlFor="todo-text">New task</label>
      <div className="input-row">
        <input id="todo-text" type="text" placeholder="Enter a task" autoComplete="off"
          ref={input} value={text} onChange={event => setText(event.currentTarget.value)} />
        <button className="primary" type="submit">Add</button>
      </div>
    </form>
  );
}

type TodoActions = { onToggle: (id: number) => void; onDelete: (id: number) => void };

export function TodoList({ todos, onToggle, onDelete }: TodoActions & { todos: Todo[] }) {
  return (
    <ul id="todo-list" aria-label="Tasks">
      {todos.map(todo => <TodoItem key={todo.id} todo={todo} onToggle={onToggle} onDelete={onDelete} />)}
    </ul>
  );
}

function TodoItem({ todo, onToggle, onDelete }: TodoActions & { todo: Todo }) {
  return (
    <li className={todo.completed ? 'completed' : undefined}>
      <label>
        <input type="checkbox" checked={todo.completed} onChange={() => onToggle(todo.id)} />
        <span>{todo.text}</span>
      </label>
      <button type="button" className="delete" aria-label={`Delete ${todo.text}`}
        onClick={() => onDelete(todo.id)}>
        Delete
      </button>
    </li>
  );
}
