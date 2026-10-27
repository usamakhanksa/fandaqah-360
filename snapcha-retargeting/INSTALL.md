# Fandaqah 360 · Snap Pixel + Conversions API — install (≈20 minutes)

Tested end to end on a local copy of the site: 27/27 browser checks + 12/12 server checks passed (see `qa/`).

## 0. Organization Admin (cannot be done with the current Account Admin login)
1. business.snapchat.com → Business Settings → **Pixels → Create Pixel** → name `Fandaqah Website` → share to ad account **Fandaqah Self Service**.
2. Copy the **Pixel ID**.
3. Events Manager → the pixel → **Conversions API → Generate token**. Copy the token (server only, never in a page).

## 1. Fill in two values
| Where | What |
|---|---|
| `landing/fandaqah-360/assets/js/fq-snap.js`, line `const PIXEL_ID = '';` | the Pixel ID |
| Host environment variables (preferred) **or** the `snap` block in `api/config.php` | `FQ_SNAP_PIXEL_ID` = Pixel ID, `FQ_SNAP_CAPI_TOKEN` = token |

`api/config.snap-block.txt` is the block to paste into the live `api/config.php`, just before its closing `];`.
**Do not overwrite the live `config.php`** — it holds the CRM key.

## 2. Upload (cPanel / FTP) into `/landing/fandaqah-360/`
- `assets/js/fq-snap.js` (new)
- `api/snap-capi.php` (new)
- `api/lead.php` (replaces the current one — same behaviour, plus the Snap call after the CRM accepts a lead)
- `api/.htaccess` (adds `snap-capi.php` to the files the web can never open)

## 3. One line in the `<head>` of both live pages
`existing-customers/index.php` and `fandaqah-360-landing-v2/index.php`, right after `<meta charset="utf-8">…`:

```html
<script src="../assets/js/fq-snap.js?v=snap1" async></script>
```
Edit the live files by hand: **don't re-upload regenerated `index.php` copies** — the live pages carry the Meta Pixel and
Google Ads tag that are not in the repo, and a regenerated copy would remove them. Bump `?v=snap1` → `snap2` whenever
`fq-snap.js` changes. (The repo build already adds this line to every page for future builds.)

## 4. Verify (10 minutes)
1. Set `FQ_SNAP_TEST_CODE` (any text, e.g. `fq-qa`) → Events Manager → **Test Events** shows server events live.
2. Open each page with `?fqsnap=debug` → browser console prints every Snap event. Snap Pixel Helper (Chrome) shows the pixel.
3. Send one real test lead on each page → **one** SIGN_UP in Events Manager, marked as Pixel + Conversions API (deduplicated).
4. Remove `FQ_SNAP_TEST_CODE`.

## 5. Offline CRM stages (daily)
`crm-offline/snap-offline-capi.py` sends qualified → `CUSTOM_EVENT_4`, opportunity → `CUSTOM_EVENT_5`, customer → `SUBSCRIBE` (SAR value).
Run it **daily** on a CRM export (Snap refuses events older than 7 days):
```
FQ_SNAP_PIXEL_ID=… FQ_SNAP_CAPI_TOKEN=… python3 -I snap-offline-capi.py crm_export.csv --dry-run
FQ_SNAP_PIXEL_ID=… FQ_SNAP_CAPI_TOKEN=… python3 -I snap-offline-capi.py crm_export.csv
```
CSV columns: `submission_id, stage, stage_time, email, phone, sc_click_id, campaign_type, value`.

## Repo
`snap-tracking.patch` = the full change for the `fandaqah-360` repo (`git am snap-tracking.patch`), including the build
step that adds the pixel to every PHP page.
