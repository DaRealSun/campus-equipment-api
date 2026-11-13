'use strict';

const endpoints = [
  ['POST', '/auth/register', {name: 'Demo Borrower', email: 'demo@example.test', password: 'DemoPassword-42'}],
  ['POST', '/auth/login', {email: 'borrower1@example.test', password: 'CourseFixturePassword-42'}],
  ['GET', '/equipment'],
  ['GET', '/equipment/{id}'],
  ['POST', '/equipment', {name: 'Demo microphone', description: 'Synthetic inventory'}],
  ['PUT', '/equipment/{id}', {name: 'Updated microphone', description: 'Synthetic inventory'}],
  ['DELETE', '/equipment/{id}'],
  ['POST', '/loans', {equipment_id: 1001}],
  ['GET', '/loans'],
  ['PUT', '/loans/{id}/return'],
];

const element = id => document.getElementById(id);
const endpoint = element('endpoint');
const recordId = element('record-id');
const token = element('token');
const body = element('body');
const status = element('status');
const response = element('response');
const useToken = element('use-token');
let loginToken = '';

endpoints.forEach(([method, path], index) => endpoint.add(new Option(method + ' ' + path, index)));

function updateForm() {
  const [, path, example] = endpoints[endpoint.value];
  recordId.required = path.includes('{id}');
  element('id-field').hidden = !recordId.required;
  element('body-field').hidden = example === undefined;
  body.value = example === undefined ? '' : JSON.stringify(example, null, 2);
}

endpoint.addEventListener('change', updateForm);
useToken.addEventListener('click', () => { token.value = loginToken; });
updateForm();

element('request').addEventListener('submit', async event => {
  event.preventDefault();
  const [method, path, example] = endpoints[endpoint.value];
  const url = '/api/v1' + path.replace('{id}', encodeURIComponent(recordId.value));
  const headers = new Headers({Accept: 'application/json'});
  if (token.value.trim()) headers.set('Authorization', 'Bearer ' + token.value.trim());
  const options = {method, headers};
  if (example !== undefined) {
    headers.set('Content-Type', 'application/json');
    options.body = body.value;
  }
  const submit = event.submitter;
  submit.disabled = true;
  response.textContent = '';
  status.textContent = method + ' ' + url + ' …';
  try {
    const result = await fetch(url, options);
    const raw = await result.text();
    status.textContent = method + ' ' + url + ' → HTTP ' + result.status;
    response.textContent = raw || '(empty response)';
    if (raw && result.headers.get('Content-Type')?.includes('application/json')) {
      const data = JSON.parse(raw);
      if (path === '/auth/login' && result.ok) {
        loginToken = data.data.token;
        useToken.disabled = false;
        data.data.token = '(available via Use token from last login)';
      }
      response.textContent = JSON.stringify(data, null, 2);
    }
  } catch (error) {
    status.textContent = 'Request failed: ' + error.message;
  } finally {
    submit.disabled = false;
  }
});
