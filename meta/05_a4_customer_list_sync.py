#!/usr/bin/env python3
"""
A4 — Existing Customers (Customer List) sync for Meta    v1.0  (2026-10-27)

Hashes email + phone locally (SHA-256, Meta normalisation) and adds them to a Customer List
Custom Audience in ad account act_1381700406640980. Safe to run hourly from cron: adding
the same people again does not create duplicates.

Usage
  export META_SYSTEM_USER_TOKEN=...          # system-user token with ads_management on the ad account
  python3 05_a4_customer_list_sync.py customers.csv              # creates "EX-A4 | Existing Customers | CRM" on first run
  python3 05_a4_customer_list_sync.py customers.csv --audience 1202...   # later runs: reuse the printed ID

CSV columns (header names, any order): email, phone   (either may be empty per row)
Raw data never leaves this machine — only hashes are sent.
"""
import argparse, csv, hashlib, json, os, re, sys, time, urllib.parse, urllib.request

AD_ACCOUNT = "act_1381700406640980"
GRAPH = "https://graph.facebook.com/v24.0"
AUDIENCE_NAME = "EX-A4 | Existing Customers | CRM"
BATCH = 10000
AR_DIGITS = str.maketrans("٠١٢٣٤٥٦٧٨٩۰۱۲۳۴۵۶۷۸۹", "01234567890123456789")


def norm_email(v):
    v = (v or "").strip().lower()
    return v if re.fullmatch(r"[^\s@]+@[^\s@]+\.[^\s@]{2,}", v) else None


def norm_phone(v):
    d = re.sub(r"\D", "", (v or "").translate(AR_DIGITS))
    if d.startswith("00"):
        d = d[2:]
    if len(d) == 10 and d.startswith("05"):
        d = "966" + d[1:]
    elif len(d) == 9 and d.startswith("5"):
        d = "966" + d
    return d if len(d) >= 11 else None


def sha(v):
    return hashlib.sha256(v.encode()).hexdigest() if v else ""


def call(path, data, token):
    data = dict(data, access_token=token)
    body = urllib.parse.urlencode({k: (json.dumps(v) if isinstance(v, (dict, list)) else v) for k, v in data.items()}).encode()
    req = urllib.request.Request(f"{GRAPH}/{path}", data=body, method="POST")
    with urllib.request.urlopen(req, timeout=60) as r:
        return json.loads(r.read())


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("csv")
    ap.add_argument("--audience", help="existing Custom Audience ID")
    a = ap.parse_args()
    token = os.environ.get("META_SYSTEM_USER_TOKEN")
    if not token:
        sys.exit("Set META_SYSTEM_USER_TOKEN")

    rows, seen = [], set()
    with open(a.csv, newline="", encoding="utf-8-sig") as f:
        for r in csv.DictReader(f):
            r = {k.strip().lower(): v for k, v in r.items() if k}
            e, p = sha(norm_email(r.get("email"))), sha(norm_phone(r.get("phone") or r.get("mobile")))
            if (e or p) and (e, p) not in seen:
                seen.add((e, p))
                rows.append([e, p])
    if not rows:
        sys.exit("No valid email/phone rows")

    aud = a.audience
    if not aud:
        aud = call(f"{AD_ACCOUNT}/customaudiences", {
            "name": AUDIENCE_NAME, "subtype": "CUSTOM",
            "customer_file_source": "USER_PROVIDED_ONLY",
            "description": "Fandaqah existing customers from CRM. Include in Campaign 1; EXCLUDE from Campaign 2 and all retargeting.",
        }, token)["id"]
        print("Created audience", aud)

    session_id = int(time.time())
    batches = [rows[i:i + BATCH] for i in range(0, len(rows), BATCH)]
    for n, chunk in enumerate(batches, 1):
        res = call(f"{aud}/users", {
            "payload": {"schema": ["EMAIL", "PHONE"], "data": chunk},
            "session": {"session_id": session_id, "batch_seq": n, "last_batch_flag": n == len(batches),
                        "estimated_num_total": len(rows)},
        }, token)
        print(f"batch {n}/{len(batches)}: received={res.get('num_received')} invalid={res.get('num_invalid_entries')}")
    print(f"Done. {len(rows)} hashed rows → audience {aud}")


if __name__ == "__main__":
    main()
