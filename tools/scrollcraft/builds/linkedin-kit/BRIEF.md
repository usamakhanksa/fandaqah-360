# LinkedIn outreach kit (`linkdein-message.html`): brief

**Self-authored under explicit creative delegation.** The owner pasted a draft of 12 LinkedIn messages (Fandaqah 360,
English and Arabic, new and existing customers, subject lines, strategic notes) and asked to "enhance content and
complete properly ... amazing and unique, professional ... corporate ... implement fully". No interview was run.
Everything below is an authored decision grounded in `campaigns/README.md`, `campaigns/A-existing-customers/*`,
`campaigns/B-new-hotels/*` and the live campaign site.

## Evidence (observed, not assumed)

- **Who uses it:** Fandaqah account managers and sales reps sending LinkedIn outreach to Saudi hotel owners and GMs.
  It is a working tool, read on a laptop during prospecting, sometimes on a phone.
- **Campaign rules it must obey:** one canonical URL with `utm_campaign` A… / B…; `utm_source=linkedin`,
  `utm_medium=direct_outreach`; no prices or "fixed price" wording in A/B outreach; no savings claims, invented reviews,
  countdowns or limited availability; approved CTAs "Check my upgrade offer" (A) and "Book my hotel demo" (B);
  approved headlines for A; Arabic uses فندقة and التدبير الفندقي; every Arabic line needs a fluent Saudi reviewer.
- **Draft defects found:** an invented 40% saving and an invented testimonial; "فندق 360" (means "Hotel 360") instead
  of فندقة 360; "lock your price" (fixed-price wording); "What if your hotel ran itself?" and "automated night audit"
  (unapproved capabilities); bold text and button labels (LinkedIn messages are plain text); Tuesday–Thursday timing
  (the Saudi week is Sunday–Thursday); a Day 15 final message that was named but never written; no connection notes.
- **LinkedIn limits:** InMail subject 200 and body 1,900 characters; connection note 300 on Premium, 200 on free
  accounts (the kit holds notes to 200 so they work everywhere).

## The eight topics (authored)

1. **Vibe:** exact, calm, ready. References: an airline dispatcher's board; a well-kept sales binder with tabbed
   dividers; a Saudi business card exchanged with both hands.
2. **Journey:** the prospect is set up, the five touches play out in order, the hard replies are answered, the rules
   are on hand, and the rep starts the next prospect.
3. **Energy:** steady and quiet throughout; one rise when the sequence completes.
4. **Feeling, and the one moment:** see the curve. The moment: the whole two-week conversation laid out, dated on Saudi
   working days, ready to paste.
5. **What no kit does:** the page is the conversation. Type the hotel's name once and every message, link and date in
   both languages becomes that hotel's, while the inbox preview builds the thread as the owner will receive it.
6. **Range:** dense, information-forward corporate tool on the campaign site's palette. Not premium-minimal dark.
7. **One world or scenes:** neither: a working surface.
8. **Assets:** the campaign site's own fonts, logo and icon family. No imagery is needed or wanted.

## Grammar: live surface

The page is the tool, running on real logic: live merge of prospect fields, an Arabic number-agreement helper for
"time with Fandaqah", a business-day scheduler that skips Friday and Saturday, character meters against LinkedIn's
limits, tracked links built from the campaign convention, readiness checks, copy-as-plain-text, edit and reset.

- **Nav:** app chrome. A top bar (track and language switches, inbox toggle on narrow screens), a sidebar that lists
  the five touches with their dates and readiness, and a status bar.
- **Hero:** the surface already in a state: a labelled demo prospect loaded, the first touches already in the inbox.
- **Close:** an actual input. "Next prospect": a first-name field with the cursor in it, plus "Copy the full sequence"
  for the CRM note.
- **Forbids:** marketing chrome, photography, display headings, scroll cues, fake panels.

Why not the other seven: filmic one-shot, continuous world and rhythmic cutlist are experiences to watch, and this
page is used, not watched; chaptered editorial suits long-form reading, not copying 30 messages; typographic poster
has one sentence to say, and this has thirty; gallery would present messages as objects without the merge logic that
makes them usable; split stage needs a two-sided argument, and the kit has none.

## Signature move: the conversation writes itself

As the rep scrolls down the sequence, each touch lands in a fixed inbox preview, dated on real Saudi working days from
the first-message date, in the language and with the names the rep entered. By the last step the preview holds the
complete conversation exactly as the owner will read it, and it can be copied whole into the CRM.

**Tell-someone sentence:** it's the kit where you type the hotel's name once and watch the whole two-week LinkedIn
conversation write itself, in Arabic or English, dated on Saudi working days.

## Feeling curve (written before the sections)

| Section | Feeling | What causes it |
|---|---|---|
| Prospect (surface open) | Recognition | Their own job, already set up: a demo prospect, the first touch already in the inbox |
| Connection note | Precision | A short note with a live meter against the 200-character limit |
| First message | Confidence | The personal line and the hotel's name in place; the note becomes a conversation |
| Follow-up 1 | Momentum | A real date three working days on; the first tracked link appears |
| Follow-up 2 | Assurance | The three objections from the campaign brief, answered |
| Last message | Completion (peak) | The inbox preview completes, the schedule resolves, the status line turns to ready |
| Replies | Preparedness | Short answers for the six replies that actually come back |
| Subject lines | Choice | Approved and new lines with their lengths |
| Playbook | Trust | Routing, timing, limits, claims and the editorial record |
| Next prospect | Readiness | A field with the cursor in it |

**Peak:** "I scrolled to the last message and the whole conversation was sitting there, dated, ready to paste." It
lives in the last step. The sequence takes most of the page; the quieter Follow-up 2 sits before it.

**Authored silence:** none. Every section is operable.

## Score

| Section | Device | Why |
|---|---|---|
| Prospect | live inputs (merge) | The surface responds before anything else happens |
| Five touches | flow + sticky inbox that accumulates | The thread fills as the sequence is read |
| Last message | completion state in the inbox and status bar | The resolve |
| Replies, subjects | flow, compressed | Administrative, short |
| Playbook | flow, two columns | Reference |
| Next prospect | input | The close is a field |

Built as a self-contained page (the scrollcraft engine's act devices are not used by this grammar). One file at the
repo root, as the owner specified: `linkdein-message.html` (spelling kept). It deploys with the site, so it carries
`noindex`.

## Verification (25 Oct 2026)

Automated, 40 of 40 passing (`scratchpad/scripts/verify-li.mjs`): no horizontal overflow at 1440, 1280, 1024, 768,
390 and 360 in English and Arabic; no console errors; copy output equals the expected plain text exactly (Windows
reads the clipboard back as CRLF, normalised in the test); first messages carry no link; personal line and its Track B
fallback; 200-character note limit trips "Over the limit"; missing fields named on the badge, the status bar and as
empty slots; edit, inbox following the edit, copy of the edit, reset; a Friday start moves to Sunday and working days
skip Friday and Saturday; "for the past year"; Arabic agreement for 1, 2, 3, 11 years and 1, 18 months; the
alternate sentence without tenure; the inbox completes at the last message; the full sequence copies with 5 touches
and subjects; "next prospect" keeps the rep, clears the rest and focuses the hotel field; the phone drawer takes focus
and closes on Escape. Repo checks: `check-i18n` 52/52 keys, `check-links` OK.

Fixed during verification: an empty trailing cell in the prospect grid on Track B; a 19px overflow of the top bar at
360px (language switch moved up, track switch given its own row); a phantom third grid column on phones; the
strikethrough of retired subject lines reaching their reasons; spacing of "A و B" in Arabic.

**Feel check (cold, one word per section):** set up · precise · personal · progress · reassured · complete · prepared ·
options · rules · ready. This matches the intended curve. The peak reads as the largest state change on the page: the
inbox progress turns green and the conversation-complete line appears.

Not verified: a real phone, and paste behaviour inside LinkedIn itself (the copy is plain text, which LinkedIn
accepts as-is).

---

# Revision 2 (25 Oct 2026): aligned to the Marketing Campaign Playbook

**Trigger.** The owner supplied `Fandaqah_360_Marketing_Campaign_Playbook.pdf` (20 Oct 2026) and said the kit's content
differs from it. Still self-authored under the same explicit creative delegation.

## What the playbook changes (evidence)

- **LinkedIn belongs mainly to C and D.** C: "a small named-account campaign, relevant Search terms and professional
  targeting on LinkedIn. Share a technical brief with IT and a cost comparison with finance." D: "personalised account
  outreach, LinkedIn content and a focused group-demo invitation." A's channels are account manager, permissioned email
  and WhatsApp; B's are Search, Arabic social video and direct follow-up to verified, permitted records. Revision 1
  covered only A and B and sent C/D elsewhere: that was backwards.
- **One primary campaign per account**, routed: existing customer A; new group of about 10+ properties D; confirmed
  mandated PMS C; other independents B; unknown PMS, role or ownership to a research queue.
- **Contact eligibility before sending** (p6): verification status (verified identity and business route / shared
  hotel route only / unverified / invalid), permission recorded per channel, suppression. A public directory or
  government registration is not consent.
- **A personalisation**: current plan, renewal date and the modules they do not use. Exclude customers already on the
  offer; route open complaints to account care; never promise automatic refunds.
- **Price lock is a held playbook headline, not a banned one.** It and the figures (working calculation SAR 3,349 a
  year, SAR 6,698 for 24 months) wait for the commercial release record (VAT, scope, terms, end date).
- **Follow-up**: at most three agreed follow-ups across seven business days (p8).
- **Every campaign has a 25-second reel storyboard** (hook, solution, proof, CTA). The LinkedIn sequence now tells the
  same story beat by beat, so a prospect who saw the reel recognises the conversation.
- **C and D need a scoped proposal**; never quote the A/B package price for Opera integrations or a dedicated
  environment. C: no "50% savings", Opera as text only, no certification implied. D: "customised to an agreed scope",
  white labelling is not source-code ownership.

## Decisions

- Four campaigns, tabbed A to D, each carrying its playbook buyer, concern, desired action, CTA, objections and
  LinkedIn role ("primary" for C and D, "supporting" for A and B).
- The account panel becomes the playbook's front door: contact fields per campaign, the routing questions (which set
  the campaign and flag a conflicting one), the eligibility gate, and for A/B the commercial-release switch with the
  release-record figures and VAT treatment.
- C has IT and finance versions of every touch. D adds the discovery-workshop invitation, three LinkedIn posts, the
  sponsored content test pair and the carousel. C adds its sponsored content test pair. Ad previews use the real
  published creatives in `book/media/`, with the exact tagged links from each campaign's `links.csv`.
- Cadence: first message on acceptance, then working days 3, 5 and 7.
- Replies per campaign, including each campaign's real objections and price rules.
- Playbook section rebuilt from the PDF: campaigns table, routing, eligibility, offers, claims (p11), enquiry handling
  (p8), scorecards and qualified-enquiry definitions (p10).
- Signature unchanged in kind: the conversation writes itself in the inbox. The router and eligibility gate are
  governance, not a second signature.

## Feeling curve (revised)

| Section | Feeling | Cause |
|---|---|---|
| Campaign brief | Orientation | The playbook's buyer, concern and action for this campaign, one glance |
| Account | Control | Facts in, campaign routed, eligibility cleared |
| Connection note | Precision | Short note, live meter |
| Beat 1 | Recognition | The reel's hook, as a question to this person |
| Beat 2 | Momentum | The solution, the first tracked link, a real date |
| Beat 3 | Assurance | Proof and objections answered |
| Beat 4 (peak) | Completion | The inbox completes, the offer lands, status turns ready and cleared |
| Ads and posts (C/D) | Reach | The same story at scale, on the real creatives |
| Replies, subjects, playbook | Preparedness, choice, trust | Reference, compressed |
| Next account | Readiness | An input |

## Revision 2 verification (25 Oct 2026)

Automated, 60 of 60 passing (`scratchpad/scripts/verify-k2.mjs`): no horizontal overflow for all four campaigns at
1440, 1280, 1024, 768, 390 and 360 in English and Arabic, with no console or network errors; routing A, B, C, D and the
research queue, the conflicting-offer flag and "switch to"; the eligibility gate (shared route and missing permission
block; copy still works with a warning; the inbox shows the gate); cadence on working days 3, 5 and 7; C IT and finance
versions, tags, playbook subject; C sponsored link identical to `links.csv` (`ad_v1_en`), v1/v2 creative swap; A plan,
renewal date and module list (three join naturally, a fourth is blocked); release off keeps the price lock out and
blocks the held headlines, release on adds the price-lock line, the playbook subject and the record's figures with
VAT, editable; D band phrase, no "100%", scoped-proposal price reply, post and carousel tags, carousel images load;
Arabic band phrase, module join with و, no "فندق 360"; next account clears, keeps the rep and forces re-routing and
re-clearing; inbox completion and the CRM record (campaign code, verification, permission, owner, next action, five
touches); phone drawer focus and Escape; reduced motion. Repo checks: `check-i18n` 101/101 keys, `check-links` clean
for this page. Every account-panel row fills on desktop and phone.

Fixed during verification: three template-built `src`/`href` attributes that the repo link checker read as local
files (now single-quoted); repeated action and CTA in the brief for C; a blank ad avatar (now the Fandaqah app icon);
empty trailing cells in the account grid for A, B and C.

**Feel check (cold):** oriented · in control · precise · recognised · moving · reassured · complete · reach · prepared ·
ready. Matches the revised curve; the peak is still the inbox completing, now beside the cleared status.

Not verified: a real phone, and paste behaviour inside LinkedIn and Campaign Manager.

## Revision 3 · Campaign C outcome-first (25 Oct 2026)

**Reviewer direction (verbatim):** shift from "Technical Review → Integration Assessment → Scope Definition" to
"Operational Challenge → Business Outcome → Review → Meeting". "Sell the outcome first and the integration second."

**What changed in the kit:** a third role, GM or owner, beside IT and finance, and the default. Every C message now opens
on the operational problem (HR, procurement, inventory, finance, compliance and reporting spread across disconnected
systems), names what Fandaqah adds while Opera stays the core PMS, states the first objective (simplify, consolidate or
improve without disrupting the PMS) and closes on "Request an operational and technology review". Beats, sponsored intro,
headline bank (new v1/v2), replies ("what exactly does Fandaqah add", "we already have systems"), qualification and
scorecard follow the same order. Arabic avoids «بـ {pms}», because the PMS value already begins with «نظام».

**Carried through the campaign:** `/opera-pms/` (H1 matches ad v1; challenge, outcome, review and meeting in that order),
the studio creatives (feed v1/v2, story, carousel, reel frames, link preview), both reels, the book's chapter C, search ads
and the C campaign docs. The four designed banners still carry the earlier headline and need the designer.

**Verified:** all three roles in both languages have every step Ready, within LinkedIn limits, with no unmerged tokens or
errors. The landing page has no overflow at 1440 and 390 in either language. Reels and creatives were checked frame by
frame, and the reel MP4s are 25 s. `check-i18n` and `check-links` pass.

## Revision 4 · Campaigns B and D outcome-first, and the corrected A/B offer (25 Oct 2026)

**Reviewer direction, B (verbatim):** "Make the sequence answer one question consistently: 'Why should I change from my
current setup?'" and leave the impression "This is an easier way to run my property", not "This software has many modules."
**Reviewer direction, D (verbatim):** "This is a platform that helps me manage multiple properties more effectively", not
"This is hotel software."

**B:** every message opens on the owner's day, not the product. Separate systems, data typed twice and several vendors lead
to less manual work, a clearer view and fewer vendors, with Fandaqah 360 named only as the way there. msg3 answers "why
change?" with the hidden cost of the current setup, then the three switching worries. New CTA: "Explore a simpler way to
run my hotel" / «اكتشف طريقة أبسط لإدارة فندقي». The session is now a walkthrough. New replies: "why would we change?"
and a reworked "we already have a system".

**D:** portfolio and executive language only: centralised visibility, standardised operations, governance (permissions and
approvals), executive reporting from one set of figures, and growth without new systems. The workshop is framed as a working
session on the operating model, not a demo. New CTA: "Explore centralised management for my group" /
«استكشف الإدارة المركزية لمجموعتي». New reply: "we already get reports from each property". The posts and carousel follow
suit.

**Offer correction (from the user, relayed by the A session):** there is no two-year subscription or commitment. The launch
price is SAR 3,495 a year excl. VAT on a one-year subscription. A can secure one additional year at the same rate during the
promotion; for B that option is not confirmed, so B never mentions it. The release record's 24-month field is gone, and every
"two-year prepaid offer", "lock your price for two years" and "fixed for two years" line in the A and B tracks is replaced.

**Carried through:** `/new-hotels/` and `/hotel-groups/` copy (H1s match ad v1; keys, classes, ids and form fields
unchanged), studio creatives and link previews, both B and D reels, the book's chapters, B search ads and the B/D docs.

**Verified:** in the kit, A to D in both languages (A and B with the release switch off and on), with every step Ready,
within limits, with no unmerged tokens or errors. Both pages have no overflow and no untranslated text at 1440 and 390 in either
language. Creatives and reels were checked frame by frame, and the four MP4s are 25 s. `check-i18n` and `check-links` pass.
Not verified: a real phone, and paste behaviour inside LinkedIn.
