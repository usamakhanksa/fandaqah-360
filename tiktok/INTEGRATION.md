# Fandaqah 360 — TikTok tracking: developer integration (≈30 min)

## 0. Prerequisite (BC admin, 2 min)
A **Business Center admin** creates the pixel (the current login is blocked: "admin-level access required"):
Events Manager → Connect data source → **Web** → name `Fandaqah 360 — fandaqah.com` → URL `https://fandaqah.com` → **Manual setup** → choose **Pixel + Events API**.
Then: Pixel → Settings → **Generate access token**. Hand the developer:
- `TIKTOK_PIXEL_ID` (not secret)
- `TIKTOK_EVENTS_API_TOKEN` (secret → server env only)

Also in Pixel Settings: **Automatic Advanced Matching = ON**, **First-party cookie = ON**.

## 1. Browser (both landing pages)
1. Copy `fandaqah-tiktok.js` → `/landing/fandaqah-360/assets/js/fandaqah-tiktok.js`
2. Set `pixelId` on line ~31.
3. In `<head>` of **both** `existing-customers/index` and `fandaqah-360-landing-v2/index`, after the gtag snippet:
   ```html
   <script src="/landing/fandaqah-360/assets/js/fandaqah-tiktok.js" defer></script>
   ```
4. Do **not** paste TikTok's base code separately — the file contains it (double base code = double PageView).

No changes to `site.js` or the v2 inline script are needed: Lead fires on their existing `dataLayer.push({event:'generate_lead'})`, which only happens after the API returns OK.

## 2. Server (`/landing/fandaqah-360/api/lead.php`)
Copy `tiktok-events-api.php` (and `tiktok-crm-events.php`) next to `lead.php`, set env vars, then:

```php
require_once __DIR__ . '/tiktok-events-api.php';

// $input = json_decode(file_get_contents('php://input'), true);   // already exists in lead.php
// ... existing validation + forwarding of the lead ...

if ($leadWasAccepted) {                          // the same condition that returns HTTP 200 today
    http_response_code(200);
    echo json_encode(['ok' => true /* , existing fields */]);
    if (function_exists('fastcgi_finish_request')) fastcgi_finish_request();   // user never waits on TikTok
    f360_tt_track_lead($input, $leadId ?? null);
    exit;
}
```
Rules: call it **only** on accepted leads (never on honeypot/rejected/validation errors). The browser sends `tt.event_id` in the JSON body — the function reads it automatically, so Pixel and Events API deduplicate.

If `lead.php` whitelists incoming keys, allow `tt` (object). Strip `tt` before forwarding the lead to the CRM if the CRM rejects unknown keys.

Log file: `logs/tiktok-events.log` (one line per send, `OK`/`ERR` + TikTok code/message).

## 3. CRM stages (when CRM is connected)
Admin: Events Manager → Connect data source → **CRM** → create the CRM Event Set, define stages, copy its ID → `TIKTOK_CRM_EVENT_SET_ID`.
On stage change: `f360_tt_crm_stage(['id'=>..., 'stage'=>'qualified', 'email'=>..., 'phone'=>..., 'tiktok_lead_id'=>..., 'campaign_type'=>...]);`
Make `F360_TT_CRM_STAGE_MAP` event names match the stages defined in the CRM Event Set.

## 4. QA (Events Manager → Pixel → Test Events)
- Open `…/existing-customers/?tt_debug=1&tt_test=<TEST CODE>` — console shows every event; server sends `test_event_code`.
- Expected sequence: PageView → ViewContent (once) → Contact(demo|whatsapp|call, once each) → LeadFormOpen → LeadFormStart → Lead (once).
- In Test Events, the Lead must show **Browser + Server** with the same event_id → "Deduplicated".
- Remove `tt_test` before go-live (no code change needed — it's URL-driven).

## 5. Security fix (do in the same deploy)
`site.js` and the v2 inline script contain a direct-API fallback that sends `X-API-Key` from the browser. Any visitor can read that key. Remove the fallback, keep only the `lead.php` proxy, and **rotate the key**.
