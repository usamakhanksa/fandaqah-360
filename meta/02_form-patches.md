# Form patches — exact edits (developer)

Both pages already generate or can generate a UUID at submit time. That UUID becomes the **one** `event_id` shared by the browser Lead and the server (CAPI) Lead. Nothing else in the forms changes.

---

## A. `/landing/fandaqah-360/assets/js/site.js` (Campaign 1 — existing-customers)

The submit handler already does `const id = crypto.randomUUID()…` and puts it in `payload.submission_id`. Reuse it.

**A1 — attach the CAPI context to the payload** (inside `const payload = { … }`, right after `submission_id: id,`):

```js
        submission_id: id,
        meta: window.fqMeta ? window.fqMeta.capiContext(id) : undefined,
```

**A2 — fire the browser Lead only on confirmed success** (replace the existing `if (result === 'ok') {` block opening):

```js
      if (result === 'ok') {
        if (window.fqMeta) window.fqMeta.lead({ lead_id: id, form_name: 'lead-form', interest: payload.interest }, id);
        if (window.dataLayer) window.dataLayer.push({ event: 'generate_lead', campaign_code: payload.campaign_code, segment: PAGE.segment, interest: payload.interest });
        showDone();
```

Why this is safe:
- Honeypot / too-fast submits call `showDone()` and return **before** this point → no Lead from bots.
- Invalid fields `return` before the UUID is created → no Lead on a bad Submit click.
- `'rejected'` / `'failed'` results never reach `fqMeta.lead` → no Lead without a CRM 2xx.
- Offline outbox retries: the browser Lead is not fired, but the server sends the CAPI Lead with the same `event_id` when the retry succeeds.

---

## B. `fandaqah-360-landing-v2/` inline form script (Campaign 2 — new customers)

This page has **no** UUID today. Add one.

**B1 — immediately before `const payload = {`:**

```js
    const metaId = (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : Date.now().toString(36) + Math.random().toString(36).slice(2);
```

**B2 — first two lines inside `payload = {`:**

```js
      submission_id: metaId,
      meta: window.fqMeta ? window.fqMeta.capiContext(metaId) : undefined,
```

**B3 — on the success path, next to the existing `dataLayer.push({ event: 'generate_lead', lead_plan: plan })`:**

```js
    if (window.fqMeta) window.fqMeta.lead({ lead_id: metaId, form_name: 'f360_v2_form', interest: plan }, metaId);
```

---

## C. Load the event layer on both pages

After the existing base-pixel `<script>` block (do **not** touch the `fbq('init')` / `PageView` lines):

```html
<script src="/landing/fandaqah-360/assets/js/fq-meta-events.js" defer></script>
```

Do **not** add GTM for Meta. The site has no GTM container; adding one now creates a second injection path for the same pixel = duplicate-event risk.

---

## D. SECURITY — fix before anything else (both pages)

The CRM API key (52 characters, sent as `X-API-Key` to `crm.fandaqah.com`) is shipped to every visitor's browser in `site.js` and in the v2 inline script, as the "direct" fallback when `api/lead.php` fails. Anyone can read it and post into the CRM.

1. Remove `API_KEY`, `API_URL` and the `direct()` fallback from both front-end scripts. The browser posts **only** to `../api/lead.php`.
2. Keep the key server-side only (environment variable read by `lead.php`).
3. **Rotate the key in the CRM** — the current one must be treated as public.
4. If you want resilience, retry the proxy (already done) and keep the browser outbox — never fall back to the CRM directly.

---

## E. Google Ads — conversion fires on page load (cross-platform, found during audit)

The v2 page runs `gtag('event', 'conversion', { send_to: 'AW-18383069867/8w92CO-ejqodEKvF3b1E' })` at top level, so **every page view is counted as a Google Ads conversion**. The existing-customers page also sent an `en=conversion` hit on load during testing.

Move it into the success branch (next to `fqMeta.lead`) and dedupe it with the same ID:

```js
    if (window.gtag) gtag('event', 'conversion', { send_to: 'AW-18383069867/8w92CO-ejqodEKvF3b1E', transaction_id: metaId /* or id on site.js */ });
```
