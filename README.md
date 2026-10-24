# Fandaqah 360

Campaign website and campaign kits for **Fandaqah 360**, built from the *Marketing Campaign Playbook* (20 Oct 2026).

| Folder | What it is | Deployed? |
|---|---|---|
| [`site/`](site/) | The public website: one shared URL with four routes (A–D), Arabic and English, lead form into Fandaqah CRM | ✅ only this |
| [`campaigns/`](campaigns/) | One folder per campaign: brief, ad copy, Search ads, reel storyboard, outreach / sales brief, tagged links, rendered creatives | ❌ internal |
| [`campaigns/_studio/`](campaigns/_studio/) | Creative template and renderer (re-render every ad after a copy change) | ❌ |
| [`tools/`](tools/) | `check-i18n.py` (Arabic coverage, also run on every deploy) · `build-campaign-data.py` (links and Search ads) | ❌ |
| `archive/` | Retired sites (`landing/`, `fandaqah-360-landing-v2/`), kept for history. Their old URLs redirect to the new site | ❌ |
| `_internal/` | Playbook PDF and design references. **Git-ignored: never committed or deployed** | ❌ |

## Live URLs
- Shared campaign URL: `https://usamakhanksa.github.io/fandaqah-360/`
  (`?utm_campaign=A…|B…|C…|D…` opens the matching route; tags are kept for the CRM)
- Routes: `/existing-customers/` (A) · `/new-hotels/` (B) · `/opera-pms/` (C) · `/hotel-groups/` (D)

## Work locally
```bash
python -m http.server 8766 --bind 127.0.0.1 --directory site     # open http://127.0.0.1:8766/
python -I tools/check-i18n.py site                                  # every string has Arabic
python -I tools/build-campaign-data.py                              # regenerate links.csv / search-ads.csv
```
Content edits: English text lives in each page's HTML, Arabic in the `window.FQ_PAGE.ar` block at the bottom of the same
page (shared strings in `site/assets/js/site.js`). Styles: `site/assets/css/site.css`.

## Before launch
1. Switch `API_URL` in `site/assets/js/site.js` from `crm-dev` to production; use an ingest-only, rate-limited key.
2. If the site moves to its own domain, update the `canonical`/`og:url`/`og:image` addresses and `sitemap.xml`.
3. Work through the checklist in [`campaigns/README.md`](campaigns/README.md#before-thursdays-approval).
