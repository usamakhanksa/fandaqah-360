# F360 landing pages — exact code patches

Prerequisite: `01-insight-tag-head.html` is in `<head>` of both pages.

---

## Page 1 — `/landing/fandaqah-360/existing-customers/` (uses `assets/js/site.js`)

What it already does well (no change needed):
- Captures `li_fat_id`, `gclid`, `fbclid` and all UTMs into `fq360_attr` (first + latest touch, 90-day first touch).
- Generates a unique `submission_id` per submit (`crypto.randomUUID()`) → perfect CAPI dedup key.
- Requires email (`email*`) → CAPI can match.
- Bot / honeypot submissions get a fake success screen and never reach the success branch → safe place for the conversion.

### Patch A — fire F360_Lead_Submit only on confirmed success

In `site.js`, inside `form.addEventListener('submit', …)`, find:

```js
if (result === 'ok') {
  if (window.dataLayer) window.dataLayer.push({ event: 'generate_lead', campaign_code: payload.campaign_code, segment: PAGE.segment, interest: payload.interest });
  showDone();
}
```

Change to:

```js
if (result === 'ok') {
  if (window.dataLayer) window.dataLayer.push({ event: 'generate_lead', campaign_code: payload.campaign_code, segment: PAGE.segment, interest: payload.interest, submission_id: id });
  if (window.F360LI) window.F360LI.leadSubmitted(id);          // LinkedIn F360_Lead_Submit (browser), event_id = submission_id
  showDone();
}
```

Do **not** call it in `showDone()` itself — `showDone()` also runs for bots (fake success).

### Patch B — send li_fat_id + campaign_type as top-level fields

In the `payload` object add:

```js
li_fat_id: (latest.li_fat_id || first.li_fat_id || (window.F360LI && window.F360LI.fatId()) || undefined),
campaign_type: PAGE.segment === 'A' ? 'existing_customer' : 'new_customer',
```

(It is already inside `first_touch` / `latest_touch`, but the CRM must store it in its own column so the CAPI job can read it.)

---

## Page 2 — `/landing/fandaqah-360/fandaqah-360-landing-v2/` (inline script, does NOT load site.js)

Gaps found:
- No `li_fat_id` capture.
- UTMs read only from the current URL — lost if the visitor navigates or comes back later.
- No `submission_id` → nothing to deduplicate browser vs server events.

### Patch C — in the inline submit handler

Before `const payload = { … }` add:

```js
const submissionId = (crypto.randomUUID ? crypto.randomUUID() : Date.now().toString(36) + Math.random().toString(36).slice(2));
const fatId = (window.F360LI && window.F360LI.fatId()) || qs.get('li_fat_id') || undefined;
```

Inside `payload` add:

```js
submission_id: submissionId,
li_fat_id: fatId,
campaign_type: 'new_customer',
```

Replace:

```js
if (window.dataLayer) window.dataLayer.push({ event: 'generate_lead', lead_plan: plan });
done();
```

with:

```js
if (window.dataLayer) window.dataLayer.push({ event: 'generate_lead', lead_plan: plan, submission_id: submissionId });
if (window.F360LI) window.F360LI.leadSubmitted(submissionId);
done();
```

### Patch D — persist UTMs (same behaviour as page 1)

Top of the inline script:

```js
try {
  const q = new URLSearchParams(location.search), keep = {};
  ['utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(k => { if (q.get(k)) keep[k] = q.get(k).slice(0,150); });
  if (Object.keys(keep).length) sessionStorage.setItem('f360_utm', JSON.stringify(keep));
} catch (e) {}
```

and when building `utm`, fall back to `JSON.parse(sessionStorage.getItem('f360_utm') || '{}')` when the URL has none.

---

## Security finding (fix this week)

`site.js` ships a CRM ingest API key in public JavaScript (`const API_KEY = 'fdq_…'`) and page 2 sends it in an `X-API-Key` header from the browser. Anyone can read it and post fake leads into the CRM (which would then flow into CAPI and poison bidding). The PHP proxy (`api/lead.php`) already exists — make it the only path, remove the key from the client, and rotate the key.
