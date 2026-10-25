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
