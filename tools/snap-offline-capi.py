"""Send CRM stage changes (qualified lead, opportunity, customer) to Snap's Conversions API as offline events.

Why: SIGN_UP tells Snap who filled the form. These later stages tell Snap which of those leads were worth it, so reporting and
(later) optimisation can move from "cheap leads" to "real hotels". PURCHASE is never sent on this B2B account.

  Stage in the CRM          Snap event        Notes
  qualified                 CUSTOM_EVENT_4    sales accepted the lead
  opportunity / proposal    CUSTOM_EVENT_5    demo held or proposal sent
  customer / won            SUBSCRIBE         signed; value + currency SAR when the CSV has a value

Input: a CSV exported from the CRM, one row per stage change. Column names are matched loosely:
  submission_id (or lead_id / id)   required: the id the website gave the lead (FQ-… reference or UUID)
  stage                             required: qualified | opportunity | proposal | customer | won
  stage_time (or updated_at / date) required: ISO date-time (e.g. 2026-10-28T10:15:00+03:00) or epoch seconds/ms
  email, phone                      at least one, or sc_click_id: plain values, hashed here (SHA-256) before sending
  sc_click_id                       optional: the Snap click id the website stored with the lead
  campaign_type                     optional: existing_customer | new_customer
  value                             optional: SAR amount, used on SUBSCRIBE only
  page_url                          optional

Rules that keep the data honest:
  - Snap refuses events older than 7 days: run this daily (cron) and older rows are skipped with a warning, never back-dated.
  - event_id = <submission_id>:<stage>, so the same row sent twice is counted once by Snap, and a local ledger
    (tools/.snap-offline-sent.json, git-ignored) stops repeats across runs.

Settings come from the environment (the same values as api/config.php):
  FQ_SNAP_PIXEL_ID, FQ_SNAP_CAPI_TOKEN, optional FQ_SNAP_TEST_CODE, optional FQ_SNAP_ENDPOINT

    python -I tools/snap-offline-capi.py crm_export.csv --dry-run     show what would be sent, send nothing
    python -I tools/snap-offline-capi.py crm_export.csv --validate    ask Snap to check the events (…/events/validate)
    python -I tools/snap-offline-capi.py crm_export.csv               send
"""
import csv, datetime as dt, hashlib, json, os, pathlib, re, sys, time, urllib.parse, urllib.request

ROOT = pathlib.Path(__file__).resolve().parent
LEDGER = ROOT / '.snap-offline-sent.json'
STAGES = {'qualified': 'CUSTOM_EVENT_4', 'sql': 'CUSTOM_EVENT_4', 'mql': 'CUSTOM_EVENT_4',
          'opportunity': 'CUSTOM_EVENT_5', 'proposal': 'CUSTOM_EVENT_5', 'demo': 'CUSTOM_EVENT_5',
          'customer': 'SUBSCRIBE', 'won': 'SUBSCRIBE', 'closed_won': 'SUBSCRIBE'}
MAX_AGE_MS = 7 * 24 * 3600 * 1000 - 10 * 60 * 1000     # Snap's 7-day limit, with 10 minutes of margin
BATCH = 50


def col(row, *names):
    low = {k.strip().lower(): v for k, v in row.items() if k}
    for n in names:
        v = low.get(n)
        if v is not None and str(v).strip() != '':
            return str(v).strip()
    return ''


def sha(v):
    return hashlib.sha256(v.encode('utf-8')).hexdigest() if v else None


def norm_phone(raw):
    d = re.sub(r'\D', '', raw.translate(str.maketrans('٠١٢٣٤٥٦٧٨٩۰۱۲۳۴۵۶۷۸۹', '01234567890123456789')))
    d = re.sub(r'^00', '', d)
    if re.fullmatch(r'0?5\d{8}', d):                     # Saudi mobile written locally: 05XXXXXXXX / 5XXXXXXXX
        d = '966' + d.lstrip('0')
    return d if 8 <= len(d) <= 15 else ''


def parse_time(v):
    if re.fullmatch(r'\d{10}', v): return int(v) * 1000
    if re.fullmatch(r'\d{13}', v): return int(v)
    t = dt.datetime.fromisoformat(v.replace('Z', '+00:00'))
    if t.tzinfo is None: t = t.replace(tzinfo=dt.timezone(dt.timedelta(hours=3)))   # CRM times without a zone are Riyadh time
    return int(t.timestamp() * 1000)


def build(row, now_ms):
    lead = col(row, 'submission_id', 'lead_id', 'id', 'reference')
    stage = col(row, 'stage', 'status', 'lead_stage').lower().replace(' ', '_')
    event = STAGES.get(stage)
    if not lead or not event:
        return None, f'skipped: needs submission_id and a known stage (got {lead!r}, {stage!r})'
    when = parse_time(col(row, 'stage_time', 'updated_at', 'date', 'time'))
    if now_ms - when > MAX_AGE_MS:
        return None, f'{lead}:{stage} skipped: older than 7 days (Snap refuses it); run this daily'
    if when > now_ms + 60_000:
        return None, f'{lead}:{stage} skipped: time is in the future'
    email = col(row, 'email').lower()
    phone = norm_phone(col(row, 'phone', 'mobile'))
    click = col(row, 'sc_click_id', 'sccid')
    user = {k: v for k, v in {'em': [sha(email)] if email else None, 'ph': [sha(phone)] if phone else None,
                              'country': [sha('sa')] if phone.startswith('966') else None, 'sc_click_id': click or None}.items() if v}
    if not ({'em', 'ph', 'sc_click_id'} & user.keys()):
        return None, f'{lead}:{stage} skipped: no email, phone or click id to match on'
    ctype = col(row, 'campaign_type') or 'new_customer'
    custom = {'event_tag': ctype, 'status': 'returning' if ctype == 'existing_customer' else 'new'}
    value = col(row, 'value', 'amount')
    if event == 'SUBSCRIBE' and value:
        custom.update(value=float(value.replace(',', '')), currency='SAR')
    e = {'event_name': event, 'action_source': 'OFFLINE', 'event_time': when, 'event_id': f'{lead}:{stage}'[:100],
         'user_data': user, 'custom_data': custom}
    url = col(row, 'page_url')
    if url.startswith('http'): e['event_source_url'] = url
    if os.environ.get('FQ_SNAP_TEST_CODE'): e['test_event_code'] = os.environ['FQ_SNAP_TEST_CODE']
    return e, None


def post(events, validate):
    pixel, token = os.environ.get('FQ_SNAP_PIXEL_ID', ''), os.environ.get('FQ_SNAP_CAPI_TOKEN', '')
    if not pixel or not token:
        sys.exit('Set FQ_SNAP_PIXEL_ID and FQ_SNAP_CAPI_TOKEN first (the same values as api/config.php).')
    base = os.environ.get('FQ_SNAP_ENDPOINT', 'https://tr.snapchat.com/v3').rstrip('/')
    url = f'{base}/{urllib.parse.quote(pixel)}/events' + ('/validate' if validate else '') + '?access_token=' + urllib.parse.quote(token)
    req = urllib.request.Request(url, data=json.dumps({'data': events}, ensure_ascii=False).encode(), method='POST',
                                 headers={'Content-Type': 'application/json'})
    try:
        with urllib.request.urlopen(req, timeout=20) as r:
            return r.status, r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as err:
        return err.code, err.read().decode('utf-8', 'replace')


def main():
    args = [a for a in sys.argv[1:] if not a.startswith('--')]
    dry, validate = '--dry-run' in sys.argv, '--validate' in sys.argv
    if not args:
        sys.exit(__doc__)
    now = int(time.time() * 1000)
    sent = set(json.loads(LEDGER.read_text())) if LEDGER.exists() else set()
    events, notes = [], []
    with open(args[0], newline='', encoding='utf-8-sig') as f:
        for row in csv.DictReader(f):
            try:
                e, note = build(row, now)
            except Exception as ex:                       # one bad row never stops the rest
                e, note = None, f'skipped: {ex}'
            if note: notes.append(note)
            if e and e['event_id'] in sent:
                notes.append(f"{e['event_id']} already sent earlier"); e = None
            if e: events.append(e)
    for n in notes: print('·', n)
    print(f'{len(events)} event(s) ready:', ', '.join(f"{e['event_id']}→{e['event_name']}" for e in events) or 'none')
    if dry or not events:
        if dry and events: print(json.dumps(events[:3], indent=2, ensure_ascii=False))
        return
    ok = 0
    for i in range(0, len(events), BATCH):
        chunk = events[i:i + BATCH]
        status, body = post(chunk, validate)
        print(f'Snap answered {status}: {body[:300]}')
        if 200 <= status < 300 and not validate:
            ok += len(chunk); sent.update(e['event_id'] for e in chunk)
    if not validate:
        LEDGER.write_text(json.dumps(sorted(sent)))
        print(f'{ok}/{len(events)} sent; ledger: {LEDGER.name}')


if __name__ == '__main__':
    main()
