import { useRef, useState } from 'react';
import { TodoForm, TodoList } from './components';

export type Todo = { id: number; text: string; completed: boolean };

export function App() {
  const [todos, setTodos] = useState<Todo[]>([]);
  const nextId = useRef(1);
  const completed = todos.filter(todo => todo.completed).length;

  function addTodo(text: string): void {
    const todo = { id: nextId.current++, text, completed: false };
    setTodos(current => [...current, todo]);
  }

  function toggleTodo(id: number): void {
    setTodos(current => current.map(todo =>
      todo.id === id ? { ...todo, completed: !todo.completed } : todo
    ));
  }

  function deleteTodo(id: number): void {
    setTodos(current => current.filter(todo => todo.id !== id));
  }

  return (
    <main>
      <h1>Todo List</h1>
      <p className="subtitle">React + TypeScript</p>
      <TodoForm onAdd={addTodo} />
      <TodoList todos={todos} onToggle={toggleTodo} onDelete={deleteTodo} />
      <div className="footer">
        <p id="summary" aria-live="polite">
          {todos.length === 0 ? 'No tasks yet.' : `${completed} of ${todos.length} completed`}
        </p>
        <button id="clear-completed" className="secondary" type="button"
          disabled={completed === 0}
          onClick={() => setTodos(current => current.filter(todo => !todo.completed))}>
          Clear Completed
        </button>
      </div>
    </main>
  );
}
