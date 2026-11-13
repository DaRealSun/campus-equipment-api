"""Run after the acceptance suite on the isolated synthetic Stage 1 database."""
import json
from concurrent.futures import ThreadPoolExecutor
from threading import Barrier
from urllib.error import HTTPError
from urllib.request import Request, urlopen

BASE = "http://127.0.0.1:8640/api/v1"


def call(method, path, token=None, body=None):
    headers = {"Accept": "application/json", "Content-Type": "application/json"}
    if token:
        headers["Authorization"] = f"Bearer {token}"
    data = json.dumps(body).encode() if body is not None else None
    request = Request(BASE + path, headers=headers, data=data, method=method)
    try:
        response = urlopen(request, timeout=10)
    except HTTPError as error:
        response = error
    with response:
        return response.status, json.load(response)


def concurrent_checkout():
    ready = Barrier(2, timeout=10)

    def check_out(token):
        ready.wait()
        return call("POST", "/loans", token, {"equipment_id": 1001})

    with ThreadPoolExecutor(2) as pool:
        replies = list(pool.map(check_out, ("fixture-borrower1-token", "fixture-borrower2-token")))
    assert sorted(status for status, _ in replies) == [201, 409], replies
    _, loans = call("GET", "/loans", "fixture-staff-token")
    active = [loan for loan in loans["data"] if loan["equipment_id"] == 1001 and loan["returned_at"] is None]
    assert len(active) == 1, active
    assert call("PUT", f"/loans/{active[0]['id']}/return", "fixture-staff-token")[0] == 200
    print("PASS simultaneous checkout: one 201, one 409, one active loan, then staff return 200")


if __name__ == "__main__":
    concurrent_checkout()
    status, before = call("GET", "/loans", "fixture-staff-token")
    assert status == 200
    assert call("PUT", "/loans/2001/return", "fixture-borrower1-token")[0] == 403
    assert call("GET", "/loans", "fixture-staff-token") == (200, before)
    print("PASS unauthorized return leaves loan records unchanged")
    for flag, expected in [("true", True), ("false", False)]:
        status, items = call("GET", "/equipment?available=" + flag)
        assert status == 200 and items["data"]
        assert all(item["available"] is expected for item in items["data"])
    print("PASS both availability filters return matching fixtures")
    status, error = call("POST", "/equipment", "fixture-staff-token", {"name": "x" * 101})
    assert status == 400 and error["error"]["code"] == "invalid_input"
    print("PASS rejects a name beyond the documented length limit")
