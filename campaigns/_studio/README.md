# Creative studio

Renders every campaign creative (and the site's link-preview images) from one HTML template, using the site's own fonts,
colours and device mock-ups, so ads and landing pages always match.

| File | What it is |
|---|---|
| `copy.js` | All creative text, Arabic and English, per campaign: headline test pair, carousel/reel slides, CTA, chip |
| `studio.html` | The template. One creative per URL: `?c=A&lang=ar&kind=ad&i=1&fmt=feed` |
| `render.mjs` | Renders all combinations into `campaigns/<X>/creatives/` and `site/assets/img/og/` |

## Outputs per campaign (× Arabic and English)

| Folder | Size | Contents |
|---|---|---|
| `creatives/feed/` | 1080×1350 | `ad-v1`, `ad-v2`: the headline test pair |
| `creatives/story/` | 1080×1920 | story / status version of v1 |
| `creatives/carousel/` | 1080×1350 | slides 1–4: problem, solution, proof, CTA |
| `creatives/reel-frames/` | 1080×1920 | the same four beats as reel keyframes for the video editor |

## Re-render after editing `copy.js`

```bash
# 1. in the repo root
python -m http.server 8767 --bind 127.0.0.1
# 2. in another terminal (Playwright once: npm i -D playwright && npx playwright install chromium)
node campaigns/_studio/render.mjs
# optional: STUDIO_ONLY=B STUDIO_LANGS=ar node campaigns/_studio/render.mjs
```

To preview one creative, open e.g. `http://127.0.0.1:8767/campaigns/_studio/studio.html?c=C&lang=ar&kind=slide&i=3&fmt=story`.

## Before publishing
- Replace the illustrative device screens with **real Fandaqah screens and sample data** (playbook p.7); keep the "Illustrative" label until then.
- A fluent Saudi Arabic reviewer approves every Arabic line.
- Preview cropping and buttons in each ad platform.
- Keep one promise and one CTA; no prices, savings figures, reviews or countdowns unless approved in the release record.
