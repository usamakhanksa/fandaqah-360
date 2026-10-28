# F360 tracking — code patches (developer hand-off)

Pixel / dataset: `992779690541049` · Pages: `/landing/fandaqah-360/existing-customers/` and `/landing/fandaqah-360/fandaqah-360-landing-v2/`

## 0. Files to upload
| File | Path on server |
|---|---|
| `f360-formopen.js` | `/landing/fandaqah-360/assets/js/f360-formopen.js` |
| `api/meta-capi.php` | `/landing/fandaqah-360/api/meta-capi.php` |
| `api/meta-event.php` | `/landing/fandaqah-360/api/meta-event.php` |

Server env vars: `META_CAPI_TOKEN` (required), `META_TEST_EVENT_CODE` (testing only).

## 1. Both landing pages — load the add-on after fq-meta-events.js
```html
<script src="/landing/fandaqah-360/assets/js/f360-formopen.js?v=1" defer></script>
```

## 2. Browser → lead.php: send the Meta context with the form
**existing-customers** (`assets/js/site.js`) and **landing-v2** (inline form script) — where the request body is built, add one field:
```js
// before: const body = { submission_id: id, ... };
const body = {
  submission_id: id,
  /* ...existing fields... */
  meta: (window.fqMeta && fqMeta.capiContext) ? Object.assign(fqMeta.capiContext(), { event_id: id }) : null
};
```
`event_id` is forced to the submission id, so the browser Lead (`eventID: e.detail.id` in fq-meta-events.js) and the server Lead share one id.

## 3. lead.php — send the server Lead ONLY after the CRM accepts
```php
require_once __DIR__ . '/meta-capi.php';

// ... existing validation + CRM insert ...
if ($crmAccepted) {                                   // same condition that returns 200 to the browser
    $m = is_array($in['meta'] ?? null) ? $in['meta'] : [];
    meta_capi_send(
        'Lead',
        (string)$in['submission_id'],                 // == browser eventID → dedup
        (string)($m['event_source_url'] ?? 'https://fandaqah.com/landing/fandaqah-360/'),
        ['em' => $in['email'] ?? '', 'ph' => $in['phone'] ?? '', 'fn' => $in['name'] ?? ''],
        ['fbp' => $m['fbp'] ?? null, 'fbc' => $m['fbc'] ?? null],
        ['content_name' => 'Fandaqah 360', 'content_category' => $m['campaign_type'] ?? null,
         'lead_id' => (string)$in['submission_id']]
    );
}
```
Rules: skip honeypot/bot submissions (no CAPI Lead for the fake-success bot path); never send a server Lead on the offline-outbox retry if the browser Lead already fired with a different id.

## 4. Keep as-is
- `fq-meta-events.js` → `InitiateCheckout` (form 50% visible) and `Lead` (on `fq:lead`). Do not remove InitiateCheckout yet — the `F360_Open_Form_No_Fill` audience runs on it until the new custom event has 30 days of data.
- `f360-measurement.js` → keep `SEND_META_LEAD = false` (otherwise a second browser Lead fires).
