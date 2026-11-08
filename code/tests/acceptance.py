#!/usr/bin/env python3
"""Shared API contract assertions. Part 1 defines them; Stage 1/2 must pass them."""
import argparse
from datetime import datetime, timezone
import json
from pathlib import Path
import sys
import urllib.error
import urllib.request

parser = argparse.ArgumentParser(description=__doc__)
parser.add_argument('--base-url', required=True, help='Example: http://127.0.0.1:8640')
parser.add_argument('--stage', required=True, choices=['stage1','stage2'])
parser.add_argument('--output', required=True)
args = parser.parse_args()
base = args.base_url.rstrip('/') + '/api/v1'
STAFF = 'fixture-staff-token'
BORROWER = 'fixture-borrower1-token'
OTHER = 'fixture-borrower2-token'
records = []
state = {'equipment': 9001, 'loan': 9002}

def nonempty_string(value):
    return isinstance(value, str) and bool(value.strip())

def positive_id(value):
    return isinstance(value, int) and not isinstance(value, bool) and value > 0

def request(method, path, token=None, body=None):
    headers = {'Accept': 'application/json'}
    if token is not None:
        headers['Authorization'] = 'Bearer ' + token
    payload = None if body is None else json.dumps(body).encode()
    if payload is not None:
        headers['Content-Type'] = 'application/json'
    req = urllib.request.Request(base+path, data=payload, headers=headers, method=method)
    try:
        response = urllib.request.urlopen(req, timeout=15)
    except urllib.error.HTTPError as error:
        response = error
    except (OSError, urllib.error.URLError) as error:
        return None, '', None, str(error)
    with response:
        raw = response.read().decode('utf-8', errors='replace')
        content_type = response.headers.get('Content-Type', '')
        try:
            data = json.loads(raw) if raw else None
        except ValueError:
            data = None
        return response.code, content_type, data, raw

def check(case, endpoint, method, path, expected, predicate=None, token=None, body=None, capture=None):
    actual, content_type, data, raw = request(method, path, token, body)
    problems = []
    if actual != expected:
        problems.append(f'expected HTTP {expected}, got {actual}')
    if expected == 204:
        if raw:
            problems.append('204 response must have an empty body')
    else:
        if not content_type.startswith('application/json'):
            problems.append('response is not application/json')
        if not isinstance(data, dict):
            problems.append('response must be a JSON object')
        elif expected >= 400:
            error = data.get('error')
            if not isinstance(error, dict) or not nonempty_string(error.get('code')) or not nonempty_string(error.get('message')):
                problems.append('error.code and error.message must be nonempty strings')
    if predicate and isinstance(data, dict):
        try:
            if not predicate(data):
                problems.append('response content assertion failed')
        except (KeyError, TypeError, ValueError, IndexError):
            problems.append('required content fields are missing or invalid')
    passed = not problems
    if passed and capture:
        capture(data)
    records.append({'case':case,'endpoint':endpoint,'method':method,'path':path,'expected_status':expected,'actual_status':actual,'passed':passed,'problems':problems})
    print(('PASS' if passed else 'FAIL')+' '+case+('' if passed else ': '+'; '.join(problems)))

# One success and at least one failure for every declared endpoint.
check('E01-success',1,'POST','/auth/register',201,
      lambda d: positive_id(d['data']['id']) and d['data']['role']=='borrower' and d['data']['email']=='new@example.test',
      body={'name':'New Borrower','email':'new@example.test','password':'CourseFixturePassword-42'})
check('E01-invalid',1,'POST','/auth/register',400,body={'name':'Missing Email'})
check('E01-duplicate',1,'POST','/auth/register',409,body={'name':'Existing','email':'borrower1@example.test','password':'CourseFixturePassword-42'})
check('E01-role-injection',1,'POST','/auth/register',201,
      lambda d:d['data']['role']=='borrower',body={'name':'Role Test','email':'role-test@example.test','password':'CourseFixturePassword-42','role':'staff'})
check('E02-success',2,'POST','/auth/login',200,
      lambda d:nonempty_string(d['data']['token']) and d['data']['token_type']=='Bearer',
      body={'email':'borrower1@example.test','password':'CourseFixturePassword-42'})
check('E02-wrong-password',2,'POST','/auth/login',401,body={'email':'borrower1@example.test','password':'incorrect'})
check('E03-success',3,'GET','/equipment',200,
      lambda d:isinstance(d['data'],list) and {1001,1002}.issubset({x['id'] for x in d['data']}))
check('E03-invalid-filter',3,'GET','/equipment?available=invalid',400)
check('E04-success',4,'GET','/equipment/1001',200,
      lambda d:d['data']['id']==1001 and d['data']['name']=='Tripod' and d['data']['available'] is True)
check('E04-missing',4,'GET','/equipment/99999999',404)
check('E05-success',5,'POST','/equipment',201,
      lambda d:positive_id(d['data']['id']) and d['data']['name']=='Acceptance Microphone',token=STAFF,
      body={'name':'Acceptance Microphone','description':'Synthetic item'},capture=lambda d:state.update(equipment=d['data']['id']))
check('E05-invalid',5,'POST','/equipment',400,token=STAFF,body={'name':''})
check('E06-success',6,'PUT',f'/equipment/{state["equipment"]}',200,
      lambda d:d['data']['id']==state['equipment'] and d['data']['name']=='Updated Microphone',token=STAFF,
      body={'name':'Updated Microphone','description':'Updated synthetic item'})
check('E06-readback',6,'GET',f'/equipment/{state["equipment"]}',200,
      lambda d:d['data']['name']=='Updated Microphone')
check('E06-missing',6,'PUT','/equipment/99999999',404,token=STAFF,body={'name':'Missing','description':''})
check('E07-success',7,'DELETE',f'/equipment/{state["equipment"]}',204,token=STAFF)
check('E07-readback',7,'GET',f'/equipment/{state["equipment"]}',404)
check('E07-history-conflict',7,'DELETE','/equipment/1002',409,token=STAFF)
check('E08-success',8,'POST','/loans',201,
      lambda d:positive_id(d['data']['id']) and d['data']['user_id']==102 and d['data']['equipment_id']==1001 and d['data']['returned_at'] is None,
      token=BORROWER,body={'equipment_id':1001},capture=lambda d:state.update(loan=d['data']['id']))
check('E08-unavailable',8,'POST','/loans',409,token=BORROWER,body={'equipment_id':1002})
check('E08-invalid',8,'POST','/loans',400,token=BORROWER,body={'equipment_id':'invalid'})
check('E08-missing-item',8,'POST','/loans',404,token=BORROWER,body={'equipment_id':99999999})
check('E09-success',9,'GET','/loans',200,
      lambda d:isinstance(d['data'],list) and len(d['data'])>=1 and all(x['user_id']==102 for x in d['data']) and any(x['id']==state['loan'] for x in d['data']),token=BORROWER)
check('E09-staff-scope',9,'GET','/loans',200,
      lambda d:isinstance(d['data'],list) and any(x['id']==2001 and x['user_id']==103 for x in d['data']),token=STAFF)
check('E09-invalid-token',9,'GET','/loans',401,token='invalid-token')
check('E10-forbidden-owner',10,'PUT','/loans/2001/return',403,token=BORROWER)
check('E10-success',10,'PUT',f'/loans/{state["loan"]}/return',200,
      lambda d:d['data']['id']==state['loan'] and nonempty_string(d['data']['returned_at']),token=BORROWER)
check('E10-repeated',10,'PUT',f'/loans/{state["loan"]}/return',409,token=BORROWER)
check('E10-missing',10,'PUT','/loans/99999999/return',404,token=BORROWER)

# Authentication contract for every protected endpoint. Test schema matches valid requests.
protected = [(5,'POST','/equipment',{'name':'Auth Probe','description':''}),
             (6,'PUT','/equipment/1001',{'name':'Tripod','description':'Fixture'}),
             (7,'DELETE','/equipment/1001',None),
             (8,'POST','/loans',{'equipment_id':1001}),
             (9,'GET','/loans',None),
             (10,'PUT','/loans/2001/return',None)]
for endpoint, method, path, body in protected:
    check(f'E{endpoint:02d}-missing-token',endpoint,method,path,401,body=body)
    check(f'E{endpoint:02d}-invalid-token',endpoint,method,path,401,token='invalid-token',body=body)
for endpoint, method, path, body in protected[:3]:
    check(f'E{endpoint:02d}-borrower-forbidden',endpoint,method,path,403,token=BORROWER,body=body)

report = {'stage':args.stage,'base_url':args.base_url,'recorded_at_utc':datetime.now(timezone.utc).isoformat(),
          'passed':sum(r['passed'] for r in records),'failed':sum(not r['passed'] for r in records),'cases':records}
output = Path(args.output)
output.parent.mkdir(parents=True,exist_ok=True)
output.write_text(json.dumps(report,indent=2)+'\n',encoding='utf-8')
print(f'{report["passed"]}/{len(records)} acceptance assertions passed; {report["failed"]} failed.')
sys.exit(1 if report['failed'] else 0)
