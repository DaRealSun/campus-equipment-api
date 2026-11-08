#!/usr/bin/env python3
"""Check the real NGINX -> PHP-FPM -> PDO -> MySQL request path."""
import argparse
import json
import sys
import urllib.request

parser = argparse.ArgumentParser()
parser.add_argument('--base-url', default='http://127.0.0.1:8640')
args = parser.parse_args()
try:
    with urllib.request.urlopen(args.base_url.rstrip('/')+'/health', timeout=15) as response:
        data = json.load(response)
        assert response.status == 200, response.status
        assert response.headers.get('Content-Type','').startswith('application/json')
        assert data == {'status':'ok','database':'connected','probe':'nginx-php-mysql-ready'}, data
    print('PASS: HTTP 200 JSON verified through NGINX -> PHP-FPM -> PDO -> MySQL readiness row.')
except Exception as error:
    print(f'FAIL: environment readiness: {error}')
    sys.exit(1)
