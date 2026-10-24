# Fandaqah 360 campaigns (A–D)

Internal working folder for the four Fandaqah 360 campaigns defined in the *Marketing Campaign Playbook* (20 Oct 2026).
Nothing in `campaigns/` is published; only `site/` is deployed.

| Code | Campaign | Primary buyer | Desired action | Landing route | Folder |
|---|---|---|---|---|---|
| **A** | Existing customers | Owner, GM or finance manager already on Fandaqah | Request an upgrade review | `/existing-customers/` | [A-existing-customers](A-existing-customers/) |
| **B** | New independent properties | Independent owner or GM | Book a Fandaqah 360 demo | `/new-hotels/` | [B-new-hotels](B-new-hotels/) |
| **C** | Opera and brand-mandated PMS | IT and finance leaders, with GM/owner | Request a technology cost review | `/opera-pms/` | [C-opera-pms](C-opera-pms/) |
| **D** | Local chains and operators (about 10+ properties) | Group owner, CEO, COO or IT director | Book a group discovery meeting | `/hotel-groups/` | [D-hotel-groups](D-hotel-groups/) |

Each folder holds: `README.md` (brief, audience, channels, handoff, scorecard), `ad-copy.md` (Arabic first, English second),
`reel-storyboard.md`, an outreach pack (`outreach.md` for A/B, `sales-brief.md` for C/D), `links.csv` (tagged links) and
`creatives/` (rendered ads, carousel and reel keyframes in Arabic and English).

## Route every account to one campaign

1. Existing customer → **A** (always, even if they are a group or use Opera).
2. New account that is a local multi-property group → **D**. A branded property inside a group may need a C demonstration *inside* the D discussion.
3. New property with a **confirmed** mandated PMS → **C**. A brand name or star rating is not proof of the installed PMS.
4. Every other independent property → **B**.
5. Unknown PMS, role or ownership → research queue, not a campaign.

Never send conflicting offers to the same account.

## One shared URL, four routes

All ads, emails and messages point at **one canonical URL**: `https://usamakhanksa.github.io/fandaqah-360/`
The site reads `utm_campaign` and opens the matching route instantly (`A…` → existing customers, `B…` → new hotels,
`C…` → Opera/PMS, `D…` → groups). Visitors can switch routes from the tabs without losing their original source.

### Link tagging convention

| Parameter | Value | Example |
|---|---|---|
| `utm_campaign` | `<code>_<name>_<yymm>`; **must start with A, B, C or D** | `B_new_hotels_2610` |
| `utm_source` | platform or sender | `google`, `meta`, `linkedin`, `whatsapp`, `email`, `account_manager` |
| `utm_medium` | `paid_search`, `paid_social`, `email`, `whatsapp`, `direct_outreach`, `remarketing` | `paid_search` |
| `utm_content` | creative version | `ad_v1_ar`, `reel_ar`, `carousel_en`, `email_1` |
| `lang` | `ar` for Arabic creatives (the page otherwise follows the visitor's browser) | `ar` |

Never put names, phone numbers or emails in a URL. Every folder's `links.csv` has ready-made links.

## What reaches the CRM

The form posts to `https://crm-dev.fandaqah.com/api/ingest/lead` (switch to production before launch). Each lead carries:

| Field | Source |
|---|---|
| `name`, `email` or `phone` (+966 normalised, raw kept in `phone_raw`) | form |
| `company`, `city`, `role`, `preferred_channel`, `interest` | form |
| `account_id` (A), `modules` (A), `current_pms`, `pms_version` (C), `property_count`, `ownership` (D) | form |
| `campaign`, `campaign_code`, `utm_source`, `utm_medium`, `utm_content`, `utm_term` (missing → `Direct/Unknown`) | latest tagged visit, else first |
| `first_touch`, `latest_touch` (landing URL, referrer, tags, time) | stored in the visitor's browser for 90 days |
| `segment` (route the form was sent from), `page_url`, `landing_url`, `submitted_at`, `language` | page |
| `privacy_notice` (shown, URL, version), `marketing_consent` (+ exact wording and time when given) | form |
| `submission_id` (for de-duplication), `message` (human-readable summary of all of the above) | page |

Success is shown only after the CRM acknowledges. Network or server failures retry three times, then the lead is kept on the
visitor's device and resent on their next visit with the same `submission_id`. **Test all four routes end to end before launch.**

## Claims: what the copy may and may not say (playbook p.11)

| Do not say | Say instead |
|---|---|
| A price in A/B creatives until the commercial release record is signed off | "Ask about our two-year prepaid offer" |
| "50% lower subscriptions" or any savings % | "See where your hotel can reduce technology costs" (C) |
| "100% Saudi compliant" | "Built for Saudi hospitality and supported regulatory workflows" |
| "Saudi made" | "Built for Saudi hospitality" (until the product lead confirms origin) |
| "AI powered" | Only beside a demonstrated, approved feature |
| "24/7 local support" | "Support for your hotel operations" with approved hours |
| "100% customised", "white label you own" | "Dedicated deployment and branding to an agreed scope" (D) |

No invented reviews, savings, countdowns or limited availability. Third-party names (Opera) are text references only,
with no logos and no implied certification.

## Re-render the creatives

Copy lives in [`_studio/copy.js`](_studio/copy.js); layouts in [`_studio/studio.html`](_studio/studio.html). See [`_studio/README.md`](_studio/README.md).

## Before Thursday's approval

- [ ] Commercial release record for A/B (price, VAT, scope, terms, end date) signed off; price stays out of creatives until then
- [ ] Product approves every capability shown; replace the illustrative mock-ups with real product screens and sample data
- [ ] Fluent Saudi Arabic reviewer signs off every Arabic line
- [ ] CRM: production URL, ingest-only key, rate limit, owner assignment and notification for A–D; four test leads accepted
- [ ] Lists: de-duplicated, verified, permission recorded, routed to one campaign; pilot with 20–30 records per segment
- [ ] Sales response capacity confirmed for staffed hours
- [ ] Spend caps per campaign/channel approved; launch status reported end of day
