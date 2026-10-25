# Fandaqah feature pages: brief

**Self-authored under explicit creative delegation.** The owner asked for "senior expertise ... do something
amazing and unique and professional and corporate ... thinking, observe and implement" across the feature pages of
the Fandaqah landing site (`landing/`), with full SEO per feature. No interview was run; every answer below is an
authored decision, marked as such, and grounded in the existing landing site and the live fandaqah.com.

## Evidence (observed, not assumed)

- **Brand:** the landing site's own system: beige `#f2f0eb`, navy `#2a273c`, coral `#e95a54`, peach `#fbcdab`;
  Georgia display, Inter text, an embedded Arabic face for RTL. Logo: the Fandaqah door-hanger mark.
- **Offer:** a cloud hotel management platform for Saudi hotels. 20 modules in four departments. Packages: Basic
  SAR 1,200, Compliance SAR 3,200, Fandaqah 360 SAR 3,495 per year, all excluding 15% VAT.
- **fandaqah.com:** 800+ hotels claim, Khobar address, 920066456, demo hours Sunday to Thursday 9:00 to 17:00. Its
  features page describes the dashboard, booking management, reports, guest reviews, document reader, Sign Stay,
  passport and ID scanner, channel manager, automation, payments and integrations. **No feature has its own page**,
  which is the SEO opening this build takes.
- **Uncertain, so never claimed:** which five modules Basic includes; capabilities of Inventory, HR, Purchasing,
  Guest Room QR and Recipe Management beyond "included module, review in the demo"; any certification; any metric.

## The eight topics (authored)

1. **Vibe:** calm, exact, warm. A good hotel at work. References: a hotel's night audit printout; the light moving
   across a Najdi courtyard wall; an airport departures board settling.
2. **Journey:** the property at dawn, the problem named plainly, the module working through the day hour by hour,
   what it covers, what it connects to, the questions people ask, and the day closing with one action.
3. **Energy:** quiet open, a steady middle, one rush, a still close.
4. **Feeling:** see the curve. The one moment: the busiest hour, when the whole board resolves at once.
5. **What no site does:** the page *is* the hotel's day. Scrolling is the clock.
6. **Range:** editorial-corporate on the brand's own palette. Not premium-minimal dark, not the cream-and-brass trap.
7. **One world or scenes:** distinct scenes on one clock. Not a continuous world.
8. **Assets:** the landing site's photography (lobby, reception with skyline, resort pool, chalet terrace, team,
   Al-Balad). No generation key and no spend: built from owned photos plus vector architecture and real markup.

## Grammar: "hotel day" (new)

Sections are hours of one operating day at the property, in order. The time of day is information the reader
needs, so hour labels are content, not decoration.

- **Nav:** the site's own header stays (search engines and visitors need the site chrome). The page's own chrome is
  the **hotel clock**: a persistent hotel-time readout that opens into the day's hours and jumps to them.
- **Hero:** a dawn establishing shot. A wall with an arched opening; the property is seen through it; the module's
  board sits on the threshold. Real `<h1>` on the wall plane.
- **Sequence:** dawn, pressure, the hours (a held surface while hour entries scroll in natural flow, one hour is the
  peak), coverage, reach (lateral), questions (compressed), night.
- **Close:** night. The page prints the day's report from the log lines collected during the scroll, with the price
  and the single action set as its last lines.
- **Forbids:** scrub video; kinetic character splitting; centred hero copy; text over photography (photos are seen
  only through architecture); invented metrics (the board is labelled sample data); counters on prices; scroll cues;
  copy hidden behind pinning (all copy stays readable in flow, for search and for reduced motion).
- **Leans on:** parallax planes, a pinned hero, a held surface, flow reveals, a pan rail.

Why not the defined eight: filmic one-shot cannot carry 20 navigable SEO pages; chaptered editorial forbids media
above the fold, which breaks the owner's layered-hero standard and the site's photographic identity; live surface
forbids the marketing header the site must keep; continuous world has no geography here; typographic poster and
rhythmic cutlist do not fit a corporate SaaS buyer; gallery is the features hub, not a feature page; split stage
needs a two-sided argument that 20 modules do not share.

## Signature move: the hotel clock

Scroll is the hotel's clock. A fixed readout advances from 06:00 at the top of the page to 23:59 at the close; the
page ground regrades with it, dawn gold to midday to sunset to night, every section at once; each hour the visitor
passes stamps a line into the module's log, and the close prints those lines as the day's report, ticked where the
visitor was present. The readout is also the page's map: it opens into the hours and jumps to them.

**Tell-someone sentence:** it's the site where scrolling runs a whole day at the hotel, sunrise to night, and you
finish holding that day's report.

## Feeling curve (written before the acts)

| Act | Feeling | What on screen causes it |
|---|---|---|
| Dawn | Calm, recognition | Morning light through an arch onto their own kind of property; the board waking at 06:00 |
| Pressure | Unease | One plain sentence naming the problem the module exists for, small type on open ground |
| The hours | Control | The board answers each hour as the reader moves through it |
| Peak hour | Relief | The rush hour: every line on the board resolves at once, the light at its brightest |
| Coverage | Trust | What the module actually covers, stated plainly, with the packages that include it |
| Reach | Curiosity | Sideways travel through the modules this one feeds |
| Questions | Assurance | Short answers, quickly closed |
| Night | Resolve | The ground goes to night and the day's report prints, ending on one action |

**Peak:** "the clock hit the rush hour and every line on the board turned ready at once." It lives in the hours
act, gets the longest entry, the quieter entry before it, and the only staggered resolve on the page.

**Authored silence:** the pressure act is deliberately sparse (one heading, one paragraph, open ground). It is the
quiet before the hours, not dead scroll.

## Layer contract (hero)

| Plane | Asset | Movement | Rule |
|---|---|---|---|
| Far | Property photograph, seen only through the arch | Smallest: scale 1.14 to 1.0 | Never under type |
| Atmosphere | Warm light shaft, CSS gradient | Slow drift, tied to the clock | Separation, never wash |
| Focal subject | The module board: real markup, sample data | Rises and comes forward | Sits on the arch sill; overlaps the arch edge |
| Near | The wall with the arched opening (vector mask, genuine alpha) | Strongest: scales past the viewer | Frames the photo, carries the type |
| Type | Semantic HTML on the wall | Stable, recedes slightly late | Full headline readable at every position |

Opening: wall, arch, morning view, board waking. Midpoint: the arch widens, the board lifts toward the reader.
Exit: the arch has passed the viewer and the page continues on the wall's ground. Outside becomes inside.
Mobile: type above the arch, smaller arch, shorter travel. Reduced motion: the static opening composition, no pin.

## Score

| Beat | Device | Why this one |
|---|---|---|
| Dawn | `pin` + `parallax` planes (page-local, driven by `--sc-p`) | Depth from separate rates: outside becomes inside |
| Pressure | `flow` + `in` | Quiet type on space, the silence before the hours |
| The hours (peak inside) | held surface (sticky, page-local state) | The board holds while the day advances under the reader's hand |
| Coverage | `flow` + `in` (stagger) | Facts, not experience: compressed |
| Reach | `pan` | Lateral travel reads as options |
| Questions | `flow` (native details) | Administrative, short |
| Night | signature close | The page arrives somewhere and stops |

Five device families, none twice in a row. One pinned hero, one pan act; everything else in natural flow.
Roughly 9 to 10 viewport-heights on desktop, set by content, not by a target.

## Constraints carried from the brand and the market

- No invented numbers, testimonials, certifications or savings. Third-party names (Booking.com, Shomoos, ZATCA)
  as text only.
- Every board is labelled "Illustrative sample data" and marked `data-nosnippet`.
- Arabic is a first-class version of every page (separate URL, hreflang), not a toggle.
- One label per intent: "Book a demo".

## Fingerprint gate

The registry was empty: this is the first build, so it clears the gate by definition. Its row is now in
`tools/scrollcraft/FINGERPRINTS.md`; the next build must differ from it on at least 4 of 6 dimensions.

## Feel check (cold scroll, then diffed against the curve)

| Act | Intended | Felt on the first pass | Change |
|---|---|---|---|
| Dawn | Calm, recognition | Calm; the arch reads as a real place | Board pulled clear of the headline; photo oversized so the slower far plane never exposes the opening |
| Pressure | Unease | Quiet, a held breath | None |
| The hours | Control | Control | Hour numerals were rendering at body size (selector specificity); fixed |
| Peak hour | Relief | **Flat.** The hero's arch was the biggest change on the sheet; the rush hour only flipped badges | Peak strengthened: warm light rises behind the board, the board lifts, the hour numeral swells, rows resolve one by one |
| Coverage | Trust | Trust | None |
| Reach | Curiosity | Curiosity | Rail driven page-locally so it also travels the right way in Arabic |
| Questions | Assurance | Brief, administrative | None |
| Night | Resolve | Resolve, but on phones the clock covered the CTA | The clock docks when the report arrives |

## Verified

- Desktop 1440 and phone 390, English and Arabic: hero opening, mid and end; every hour; coverage; rail; questions; night.
- Scrollcraft harness: 9.2 viewport-heights, 2 pinned acts, no dead scroll.
- Reduced motion: still hero, no pinned scroll space, rail becomes a scroll region, board still changes state.
- No JavaScript: all copy visible, clock hidden.
- All 51 landing pages at 1440 and 390: one h1, title and description lengths, canonical, Open Graph, valid JSON-LD,
  hreflang on feature pages, no overflow, no console errors, no failed requests.
- Lead dialog with the CRM mocked: validation, Arabic-Indic digits, retry, email fallback on failure, success state.

Not verified: a real iPhone (headless Chrome cannot reproduce iOS Safari), and a live CRM submission (tests were mocked
so no test leads reached the CRM).
