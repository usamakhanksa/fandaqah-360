# Fandaqah 360

Campaign website and campaign kits for **Fandaqah 360**, built from the *Marketing Campaign Playbook* (20 Oct 2026).

The **website lives at the repo root** and is the only thing published. Both GitHub Pages modes give the same result:
the Actions workflow (`.github/workflows/static.yml`) copies the root minus internal folders, and `_config.yml` makes the
"Deploy from a branch" build exclude the same folders.

| Path | What it is | Published? |
|---|---|---|
| `index.html`, `existing-customers/`, `new-hotels/`, `opera-pms/`, `hotel-groups/`, `assets/`, `404.html` | The website: one shared URL, four routes (A–D), Arabic and English, lead form into Fandaqah CRM | ✅ |
| `fandaqah-360-landing-v2/` | The previous Fandaqah 360 landing page, kept live at its original link | ✅ |
| `landing/` | Redirects from the retired multi-page site to the new home | ✅ |
| [`campaigns/`](campaigns/) | One folder per campaign: brief, ad copy, Search ads, reel storyboard, outreach / sales brief, tagged links, rendered creatives; plus `_studio/` (creative template and renderer) | ❌ |
| [`tools/`](tools/) | `check-i18n.py` (Arabic coverage) · `check-links.py` (links and icons) · `build-campaign-data.py` (links and Search ads); the first two run on every deploy | ❌ |
| `archive/` | Retired sites, kept for history | ❌ |
| `banners/` | Working banner files | ❌ |
| `_internal/` | Playbook PDF and design references. **Git-ignored: never committed or published** | ❌ |

## Live URLs
- Shared campaign URL: `https://usamakhanksa.github.io/fandaqah-360/`
  (`?utm_campaign=A…|B…|C…|D…` opens the matching route; tags are kept for the CRM; `?lang=ar` for Arabic)
- Routes: `/existing-customers/` (A) · `/new-hotels/` (B) · `/opera-pms/` (C) · `/hotel-groups/` (D)

## Work locally
```bash
python -m http.server 8766 --bind 127.0.0.1        # in the repo root, open http://127.0.0.1:8766/
python -I tools/check-i18n.py .                      # every string has Arabic
python -I tools/check-links.py .                     # every link, asset and icon resolves
python -I tools/build-campaign-data.py               # regenerate links.csv / search-ads.csv
```
Content edits: English text lives in each page's HTML, Arabic in the `window.FQ_PAGE.ar` block at the bottom of the same
page (shared strings in `assets/js/site.js`). Styles: `assets/css/site.css`.
Adding a new top-level folder that must stay private? Add it to **both** `_config.yml` and the rsync excludes in the workflow.

## Before launch
1. Switch `API_URL` in `assets/js/site.js` from `crm-dev` to production; use an ingest-only, rate-limited key.
2. If the site moves to its own domain, update the `canonical`/`og:url`/`og:image` addresses and `sitemap.xml`.
3. Work through the checklist in [`campaigns/README.md`](campaigns/README.md#before-thursdays-approval).
