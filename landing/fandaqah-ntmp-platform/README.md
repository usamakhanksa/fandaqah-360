# Fandaqah NTMP landing page

A static website (HTML, CSS, JavaScript). No build step, no server code, no database.

## Folder contents

```
index.html                  the page
assets/css/styles.css       all styling (brand colours are at the top)
assets/js/config.js         <-- the only file you normally edit
assets/js/i18n.js           Arabic text, steps, FAQ, readiness questions
assets/js/demo.js           built-in product walkthrough
assets/js/main.js           page behaviour
assets/img/                 photos and logo
.htaccess, robots.txt, site.webmanifest
```

## 1. Configure (5 minutes)

Open `assets/js/config.js`. It is pre-filled with details from fandaqah.com:
WhatsApp +966 55 594 7522, LinkedIn, X, Instagram, Facebook, About / Contact / Policy pages.

| Setting | What it does |
|---|---|
| `form.endpoint` | **Set this to receive leads automatically.** Any URL that accepts a JSON POST: your CRM webhook, Zapier, Make, Formspree, HubSpot or your own API. If empty, visitors are asked to send their request on WhatsApp instead. |
| `contact.email` | Optional. Adds a "Send by email" button after the form. |
| `demoVideoUrl` | Paste a YouTube or .mp4 link to replace the built-in walkthrough. |
| `defaultLanguage` | `"auto"`, `"en"` or `"ar"`. Links ending in `?lang=ar` always open in Arabic. |
| `social.youtube` | Add a link and the YouTube icon appears in the footer. |

Each lead is sent as JSON with: fullName, hotelType, jobRole, roomCount, property,
whatsapp (+966 format), city, email, currentPms, ntmpStatus, focus[], callTime,
callLang, notes, readinessScore, consent, reference, language, submittedAt, page, source.

## 2. Replace images (optional, recommended)

The photos were extracted from the design screenshot, so they are softer than originals.
Save high-resolution versions with the same names and sizes ratio:

- `assets/img/hero-team.jpg` (landscape, about 1800 x 1060 or larger)
- `assets/img/room-riyadh.jpg` (about 1200 x 1000)
- `assets/img/logo-mark.svg` and `logo-mark-light.svg` (the light one is for the dark footer)
- `assets/img/og-image.jpg` (1200 x 630, used when the link is shared)

## 3. Publish

Upload the **contents** of this folder (not the folder itself) to any static host:

- **cPanel / Apache:** File Manager, open `public_html` (or a subfolder such as `ntmp`), upload the zip, choose Extract. `.htaccess` enables compression and caching.
- **Nginx:** copy files to the web root, e.g. `/var/www/fandaqah-ntmp`, with `root /var/www/fandaqah-ntmp; index index.html;`
- **Netlify / Cloudflare Pages / Vercel:** drag the folder into the dashboard. Done in under a minute.

Then update the `canonical` and `hreflang` URLs at the top of `index.html` to the real address
(currently `https://fandaqah.com/ntmp/`).

## 4. Editing text

- English text: directly in `index.html`.
- Arabic text: `assets/js/i18n.js`, under the same key as the `data-i18n` attribute in the HTML.
- Steps, FAQ (both languages) and readiness questions: also in `i18n.js`.

## Notes

- Works in all current browsers, on phones and desktops, in English (left to right) and Arabic (right to left).
- Respects visitors' "reduce motion" setting; keyboard and screen-reader accessible.
- Shortcuts: Ctrl/Cmd + K or / opens search.
- Product screens and the walkthrough use sample data and say so on the page.


## Brand identity update - 17 November 2026

- Original dark/light logo lockups extracted as vector paths from page 2 of the supplied Fandaqah Identity.pdf. The bilingual wordmark is never re-typeset, mirrored or animated. The favicon uses its original tag mark.
- Original pattern extracted from page 4, used as a decorative accent away from readable content.
- Digital colours sampled from the rendered guide: coral #F15F5B, navy #262E44, off-white #F7F7F4, grey #889695, peach #FCC6AA. Darker coral text and navy button labels improve legibility.
- Guide typography: El Messiri headings, Montserrat English body, Almarai Arabic body. WOFF2 fonts are self-hosted; OFL licenses are in assets/fonts. No font CDN is required at runtime.
- Deployment URL: https://usamakhanksa.github.io/fandaqah-360/landing/fandaqah-ntmp-platform/ . Canonical, language alternates and sharing-image URL match this deployment.
- Browser verification: English/Arabic at 320, 390, 768, 1024, 1440 and 1920px; no horizontal overflow or JS errors, original logos/images load, mobile navigation, keyboard search and full form validation/WhatsApp handoff checked. No real outbound lead or CRM submission was sent.
- The earlier brand-only release used WhatsApp handoff. CRM delivery is now configured as described below.

## CRM and single-language update

Navigation contains same-page section links without dropdowns. English and Arabic content are mutually exclusive, including FAQ answers and the sample product walkthrough. Technical booking references and the original logo are preserved.

The form now uses the supplied development CRM ingestion endpoint, X-API-Key header and platform fandaqah_tourism_platform_contact. This static integration makes the supplied ingestion key visible to the browser; use only a key restricted to lead ingestion.

All contact fields, property details, preferences, consent, readiness score and notes are included in the CRM payload and readable message. Success requires a positive JSON acknowledgement. Failed, negative, non-JSON and timed-out responses retain the form for retry. Duplicate clicks during submission are blocked and retries reuse the same submission ID.

A real browser submission returned HTTP 200 with ok:true. CRM acknowledgement may report deduplication; this does not establish that a new record was created. Real delivery is tested separately from the browser regression tool, which mocks every CRM response and sends no real lead.

Browser regression command: node tools/verify-ntmp-brand.cjs. Set FQ_PLAYWRIGHT to an installed Playwright package, FQ_QA_URL to the served page and FQ_QA_DIR to the evidence directory.
