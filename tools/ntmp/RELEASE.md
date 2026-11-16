# NTMP landing page release

## Current status

Implemented, tested and pushed to GitHub `main` on 16 November 2026 using the installed Git Credential Manager. The Pages packaging rules now exclude internal document formats as required by the publication guard. Check GitHub Actions for the static deployment result of the latest commit.

**PHP backend deployment and production CRM delivery remain unverified.** The existing PHP production host returned HTTP 403 to earlier read-only checks from this environment. No SSH/SFTP deployment connection is configured here. A GitHub Pages deployment publishes static files only; it cannot deploy or execute the PHP adapter.

The existing local `api/config.local.php` has a configured CRM key and lead-store configuration; secret values were neither printed nor copied into source or the release ZIP. Production CRM acceptance is unverified. All automated submissions used an isolated local mock CRM or browser interception, never real sales data.

## Design and interaction

The reference composition is retained: angled reception photograph; bilingual problem cards; five-step workflow; four qualified capability cards; navy preview strip; bedroom/form and bilingual FAQ; navy branded footer. Decorative photography is AI-generated. Preview panels are labeled illustrations, not product screenshots. A real captioned NTMP video and anonymized product captures remain unavailable, so no video playback or real product proof is claimed.

`motion.css` and `enhance.js` add restrained entrance motion, one-time section reveals, subtle card/button transitions, sticky navigation, reading progress, keyboard-accessible preview dialogs, FAQ collapse/expand controls (all answers initially visible), a contextual mobile review action and back-to-top. Reduced-motion disables movement; JavaScript-disabled pages retain readable content and a contact fallback. No animation library, remote font request or third-party tracking script is required.

`form.js` provides two-step qualification, translated inline validation, Saudi/Arabic-digit phone normalization, stable submission IDs, disabled pending submission controls, bounded UTM attribution, retry-safe failures and success only after backend acknowledgment. No personal values are stored in localStorage or analytics. Only language and analytics preference are stored there. Entries remain only in the current page until submitted; reload discards unsent entries.

## Backend

`api/ntmp-review.php` reuses the existing private CRM configuration. It implements:

- POST JSON only, HTTPS except loopback, exact origin/CORS allowlist, 16KB body bound, bounded text fields, allowlisted enum values, phone/email checks and explicit request-contact consent.
- Honeypot rejection, eight valid submission attempts per HMAC visitor identity per ten minutes, and locking that rejects concurrent attempts.
- HMAC property/email/phone duplicate protection for accepted requests over 24 hours; stable CRM idempotency key through retries. Failed delivery never produces success. CRM responses require 2xx JSON with affirmative acceptance or a record ID. Verify that acknowledgment contract against the actual CRM before launch.
- Consent purpose, policy version, language, timestamp and separate optional marketing choice in the protected receipt. Accepted records include a deduplicated server event receipt; this is an audit record, not proof of delivery to an analytics provider.
- An owner-managed do-not-contact HMAC list at `<NTMP_DATA_DIR>/do-not-contact.json`. If no list is provisioned, no local entries are suppressed; organization-wide suppression must be supplied or enforced by the CRM. A malformed supplied list fails closed.
- New CRM stage `new`, qualified property details, consent and allowlisted UTMs. Confirm stage mapping with the CRM owner.
- Protected storage under the existing `leads.dir/ntmp` by default, or `NTMP_DATA_DIR` outside the web root. Files use restrictive permissions; Apache denies their directory. Default retention inherits the existing 180-day company lead-store policy. Cleanup is opportunistic and bounded to 100 directory entries per request, not a guaranteed deletion schedule; provision a scheduled retention sweep when required.

The backend depends on existing `api/config.php`, its environment/local secret configuration, `api/fq-visitor-lib.php`, `api/page.php`, PHP with curl/mbstring, and a writable protected directory. Those dependencies already exist in this repository. Do not overwrite the production secret configuration with local files. Credentials never go to the browser.

## Build

Run `python tools/ntmp/match_reference.py` after NTMP copy/asset changes. It produces all four HTML/PHP routes, correct endpoint metadata, HTML/static canonicals and PHP-host canonicals, then fingerprints the page's scripts/styles.

The full `tools/build-landing.py` now runs this generator last. `tools/build-php.py` leaves these two special consent-aware PHP pages to their owner generator, avoiding the global ad/partial-lead injections. This prevents a regular build from restoring the previous design or changing its tracking behavior. CI excludes local QA artifacts from publication.

For a different PHP mount, set `NTMP_PUBLIC_BASE` to the confirmed absolute landing base URL with a trailing slash before generating. PHP defaults to the repository's existing production mount `https://fandaqah.com/landing/fandaqah-360/landing/`; the static HTML defaults to its existing GitHub Pages URL. Verify redirects, final self-canonicals and hreflang at the actual host.

## Verification

- `python tools/ntmp/test_backend.py`: starts a disposable PHP server with an isolated test key, mock CRM and temporary store; checks origin/method, all validation boundaries, consent/honeypot, failed delivery/retry, acknowledgment, Arabic phone normalization, deduplication, suppression and rate limits. No real CRM calls.
- Start `php -S 127.0.0.1:8126 -t .`, then `python tools/ntmp/verify.py`: browser checks at 390/768/1440px in English/Arabic, plus reduced-motion and no-JavaScript. All form delivery is intercepted in the browser; never forwarded to a live backend. Tests cover validation, back/next, failure/retry, success, consent payloads, modal keyboard close, FAQs, no default analytics queue, overflow and asset loading. Full-page screenshots and results go to `artifacts/ntmp/`.
- PHP/JS syntax, Python compilation, diff whitespace and local assets checked. The root source link checker flags existing campaign folder links because deployment renames `-index.html` to `index.html`; use the deploy-shaped static snapshot for that check. This is not a completed penetration test, screen-reader audit or measured production Core Web Vitals report.

## Release and access needed

Run `python tools/ntmp/package_release.py` to produce a ZIP under `_dist/` with the four routes, backend adapter and required local assets. It excludes secrets, private records, QA artifacts and unrelated site files. Upload the ZIP's contents at the existing project root on the PHP host, retaining its configured `api/` dependencies and private configuration. Do not upload a PHP ZIP into GitHub Pages: Pages cannot run PHP.

To publish the static site, authenticate the existing GitHub remote and push the reviewed changes to `main`; the existing Pages workflow handles publication. That deploy does not deploy the PHP adapter, so publish the adapter to the company PHP host as part of the same release. The static page points at that new company-hosted endpoint; until it is present, it correctly shows a delivery error rather than false success.

Before declaring production complete: confirm authenticated deployment to both hosts, final canonical base, secret availability and storage protections; validate the CRM acknowledgment/stage mapping and suppression policy; submit an owner-approved test lead; verify CRM receipt and consent; verify HTTPS/CORS from both language pages; inspect the live responsive pages and record deployed URLs/commit. Remove any test records through the normal CRM process. Do not manufacture missing credentials, acceptance, screenshots or video proof.

## Analytics

Optional analytics default off. DNT/GPC blocks event queuing. A custom settings dialog enables/revokes future page events. The queue contains only `ntmp_landing_view`, `ntmp_review_form_start` and confirmed `ntmp_review_form_submit_success` with language and event ID, never form fields. External collector configuration remains the analytics owner's responsibility. Demo playback, WhatsApp, booking-completed and sales-stage events remain absent until those actual activities exist. The backend stores a deduplicated accepted-submission audit event; it does not claim external analytics delivery.

Official guidance: https://my.gov.sa/en/services/22184. No government logo, official approval, fabricated rating, pricing or compliance guarantee is introduced.
