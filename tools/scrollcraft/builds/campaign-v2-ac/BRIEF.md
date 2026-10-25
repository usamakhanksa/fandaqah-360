# Campaign pages v2: A (existing customers) and C (Opera / mandated PMS)

**Structure supplied, craft self-authored under explicit creative delegation.** The owner supplied two full-page
comps (`v2-existing customer.png`, `v2-Campaign-opera.png`) and asked to "clone this website" into the working pages
`existing-customers/index.html` and `opera-pms/index.html`, with "senior expertise ... amazing, unique, professional,
corporate". The comps fix the section order, navigation, copy and close; scroll-craft decides the hero depth, motion,
the one peak per page, the signature interaction and the verification.

## Evidence

- Working pages already carry: CRM lead form (attribution, retry, outbox), language via `window.FQ_PAGE.ar` and
  `assets/js/site.js`, the campaign design floor `assets/css/site.css` (navy, coral, peach, Source Serif 4, Inter,
  Almarai and El Messiri for Arabic).
- Playbook claim rules still apply: no savings numbers, compatibility "confirmed after review", Opera named in text
  only with the trademark notice, eligibility and prepayment terms beside the two-year offer. The comp's "A fixed price
  for two years" is the owner's copy and stays; it remains on the approval checklist in `campaigns/README.md`.
- Assets: no generation key. Comp photography is not available as files, so scenes are composed from owned
  photography (`assets/img/hero-existing.webp`, `landing/assets/site/photos/*`, `assets/img/panel-*`) plus real
  markup for every device, card and document. Nothing is text-in-image.

## The eight topics (authored)

1. **Vibe:** warm, exact, reassuring. A good hotel's back office at golden hour.
2. **Journey:** as drawn in each comp.
3. **Energy:** a confident hero, a calm middle, one held moment, a decisive form.
4. **Feeling and peak:** see the curves below.
5. **What no site does:** A, the upgrade assembles itself from what you pick; C, every cost line you pass is placed on
   the review card.
6. **Range:** corporate-editorial on the campaign palette.
7. **One world or scenes:** distinct scenes (the comps are sectioned).
8. **Assets:** owned photography; devices and documents in markup.

## A: existing customers

| Act | Feeling | Cause |
|---|---|---|
| Hero | Recognition | Their own product on a marble table under arches, the two-year card and module chips around it |
| Value strip | Reassurance | Plan reviewed, modules selected, terms confirmed |
| Modules | Agency | They pick what goes into their review; the choice stays with them down the page |
| **Offer (peak)** | Arrival | The navy band holds; the Fandaqah 360 card rises and lands on its plinth as the two lines resolve |
| Steps | Clarity | Three steps on one line |
| Form | Commitment | The form already holds the modules they picked |
| FAQ | Assurance | Three short answers |

Peak sentence: "the card came up out of the dark and settled on the stone, and the offer was just two lines."
Signature: **the upgrade tray**. Ticking a module lights its chip in the hero, adds it to a small tray that travels with
the reader, and pre-ticks the same module in the form; unticking anywhere unticks everywhere.
Tell-someone: it's the site where the modules you tick follow you all the way into the form.

## C: Opera / mandated PMS

| Act | Feeling | Cause |
|---|---|---|
| Hero | Recognition | Their PMS at the centre of a glass map, the property behind it |
| Navy band | Alignment | IT, finance and the GM each get their line |
| Workflows | Possibility | Four areas around the PMS, each with its own scene |
| **Cost lens (peak)** | Clarity | The section holds; each cost line they pass lights and is placed onto the review card, current against proposed |
| Steps | Confidence | Four steps, one note on what confirms compatibility |
| Form | Resolve | Navy promise beside the request |
| FAQ | Assurance | Three answers side by side |

Peak sentence: "every cost line I scrolled past got placed on the review card next to the proposed one."
Signature: **the cost lens** (scroll-indexed pairing of the checklist and the comparison card; bars carry no values).
Tell-someone: it's the site where the costs you read get laid out side by side as you go.

## Score

| Beat | A device | C device |
|---|---|---|
| Hero | parallax planes (photo/arches, devices, glass, foreground leaves) | parallax planes (photo, glass hub, foreground leaves) + line draw on entry |
| Second | flow + in | flow + in |
| Third | flow (pickable cards) | flow + in (cards) |
| Peak | pin (offer band, card landing) | pin (cost lens) |
| Steps | flow + in | flow + in |
| Form | flow | flow |
| FAQ | native details | flow |

Four families per page (parallax, flow, pin, reveal), one pinned act per page.

## Layer contracts

A hero: far = palms and pool through two arched openings in a warm plaster wall; mid = round marble table; focal =
laptop and phone standing on the table (shared contact line); glass = two-year card and module chips; near = blurred
leaves at the lead edge; type on the beige ground, never on the photograph.

C hero: far = reception photograph, masked away from the type; atmosphere = warm light; focal = glass PMS map; near =
leaf silhouette at the trail edge; script note on a soft light patch.

## Fingerprint gate

| Against | Grammar | Nav | Hero | Shape | Close | Signature | Differs |
|---|---|---|---|---|---|---|---|
| A vs landing-features | ≠ | ≠ | ≈ (arched opening) | ≠ | ≠ | ≠ | 5 / 6 |
| C vs landing-features | ≠ | ≠ | ≠ | ≠ | ≠ | ≠ | 6 / 6 |
| A vs C | = | = | ≠ | ≠ | = | ≠ | 3 / 6 |

A and C share grammar, navigation and close because the owner's comps define one campaign family. That is explicit
direction, recorded here rather than designed around.

## Feel check (cold, then diffed)

| Page | Act | Intended | Felt | Change |
|---|---|---|---|---|
| C | Cost lens | Clarity | **Nothing happened**: the section scrolled away (stage lost its sticky position) | Stage no longer overrides the engine's sticky; lines now place one by one |
| C | Hero | Recognition | The lead ran onto the photograph | Lead narrowed; on phones the photo sits behind the map only |
| C | Band | Alignment | Title wrapped to three lines | Wider title column |
| A | Offer | Arrival | Arrival; plinths read flat | Lit top faces on the plinths |
| A | Modules to form | Agency | Agency: picks lit the chips, filled the tray, pre-ticked the form | None |

## Verified

- Desktop 1440 and phone 390, English and Arabic, every section; pinned states at 10, 45 and 90 percent.
- Forms against a mocked CRM (no test lead reached the CRM): A sends modules, account ID, renewal date, contact consent
  and a normalised +966 phone, and refuses to send until the contact box is ticked; C sends the hidden interest with a
  readable label, current PMS and version.
- The other routes (hub, B, D), the campaign book and the v2 landing: no errors, no failed requests, no overflow at
  either width or language.
- Scrollcraft harness: C 5.8 and A 5.6 viewport-heights, one pinned act each, no console errors, no failed requests.

Not verified: a real iPhone; a live CRM submission.
