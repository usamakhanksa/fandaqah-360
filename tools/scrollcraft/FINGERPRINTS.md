# Fingerprints

Every site you build with **scroll-craft** gets one row here, appended after it
ships. The registry exists so your next build can prove it is a different page
rather than a re-skin of one you already made.

This file is **yours**. It starts empty on purpose: the gate is about not
repeating *yourself*, so it has nothing to say until you have built something.

The rules and the gate live in the skill's
`references/uniqueness.md`. Short version:

**A new build must differ from EVERY row below on at least 4 of the 6
dimensions.** Four against each row individually, not four on average across the
table. If a planned build fails, change the plan. Never edit a row to make room
for it.

The six dimensions are: **grammar**, **nav treatment**, **hero device**,
**act-sequence shape**, **close pattern**, **signature move**.

Dimension 6 is free, because a signature move is unique by definition. So the
gate really asks for three more out of the remaining five, and a build that
changes only grammar and world will fail it.

---

## The registry

| Build | Grammar | Nav treatment | Hero device | Act-sequence shape | Close pattern | Signature move | World | Port |
|---|---|---|---|---|---|---|---|---|
| landing-features (Fandaqah, 20 modules × EN/AR) | Hotel day (new): sections are hours of one operating day | Site header kept; page chrome is a fixed hotel-clock readout that opens into the hours and jumps to them | Pinned dawn shot: wall with an arched opening (vector, scales past the viewer), property photo moving slower behind it, module board on the ledge overlapping the arch, light shaft | pin(hero) > flow > held sticky board with 4 hour entries (peak entry longest) > flow > pin(lateral rail) > flow > night; 2 pinned acts, about 9.2vh desktop | Night section: the day's report printed from the hours the reader passed, price line and one action as its last lines; clock docks | Scroll is the hotel's clock: 06:00 to 23:59, the ground regrades dawn to night, each hour stamps the report | Brand beige/navy/coral, photographic arch | 8766 |
| campaign-v2-A (existing customers) | Comp-led conversion page (structure supplied by the owner) | Anchor bar with section highlight, pill language switch; a floating upgrade tray carries the reader's picks | Two arched openings onto palms in a plaster wall, laptop and phone standing on a marble table, glass two-year card and module chips, blurred leaves at the lead edge | parallax hero > value strip > pickable module cards > pin(offer band, card lands on plinth) > steps > triptych form > FAQ; 1 pinned act, about 5.6vh | Peach triptych (copy, arches, form) then accordion FAQ and navy footer | The upgrade tray: picks light the hero chips, travel in a tray and pre-tick the form | Campaign palette, owned photography + markup devices | 8766 |
| campaign-v2-C (Opera / mandated PMS) | Comp-led conversion page (structure supplied by the owner) | Anchor bar with section highlight | Reception photo masked away from the type, glass PMS map whose connectors draw in, leaf silhouette at the trail edge, script note | parallax hero > navy roles band > workflow cards > pin(cost lens) > four steps > navy/peach split form > three-column FAQ; 1 pinned act, about 5.8vh | Navy promise beside the form card, then FAQ columns and a light footer with the trademark notice | The cost lens: each cost line passed is placed on the review card, current against proposed, no values | Campaign palette, owned photography + markup desk scene | 8766 |
| linkedin-kit (`linkdein-message.html`, sales tool, EN/AR) | Live surface: the page is the outreach tool, running on real merge, scheduling and copy logic | App chrome: top bar with track and language switches, sidebar of the five dated touches with readiness dots (a step strip on phones), navy status bar | The surface already in a state: a labelled demo prospect merged into every message, the invitation already in the inbox | prospect panel > five flow steps beside a sticky inbox that accumulates > replies > subject lines > playbook (all flow, no pinned act); 10 sections, length set by content | An input: a "next prospect" first-name field on a navy plate, with copy-full-sequence for the CRM | The conversation writes itself: passing each touch drops it into the inbox, dated on Saudi working days, until the thread completes | Campaign palette, no imagery (dense corporate tool) | 8766 |


---

## What is taken

Add a bullet here whenever a build claims something a later build should avoid
reusing: a grammar, a nav treatment, a close pattern, a signature move, an
act-count-and-length band. The shared columns are what the next build inherits
as a constraint, so writing them down is the whole point.

- **Grammar:** hotel day (hours of one operating day as the section unit).
- **Nav:** a fixed time readout that doubles as a jump map.
- **Hero:** wall-with-arch near plane over a slower photo plane, product surface on the ledge.
- **Close:** a printed report assembled from what the reader passed.
- **Signature:** scroll-as-clock with a page-wide regrade and stamped log.
- **Shape band:** 7 sections, 2 pinned acts, about 9vh.
- **Grammar:** comp-led conversion page (owner-supplied structure), used by campaign-v2-A and -C.
- **Signatures:** selection that travels into the form (A); scroll-indexed checklist-to-comparison pairing (C).
- **Shape band:** 7 sections, 1 pinned act, about 5.5 to 6vh.
- **Grammar:** live surface as a working sales tool (linkedin-kit).
- **Nav:** app chrome (switch bar, dated-step sidebar, status bar).
- **Close:** a "next prospect" input that resets the tool.
- **Signature:** scroll-accumulated inbox thread dated on working days.
- **Shape band:** 10 flow sections, 0 pinned acts, sticky side panel.
- linkedin-kit revised 25 Oct 2026 to the Marketing Campaign Playbook (campaigns A to D, routing and eligibility gate, ads and posts for C and D). Same build, same row: a revision is not a new build.

---

## Appending a row

After shipping, add one line to the table and one bullet to **What is taken** if
the build claimed something new. Fill every column. Say what the build shares
with existing rows.

Rows are append-only. A build that has been superseded stays in the table,
because the space it occupies is still occupied.

---

## Worked example

The skill's author kept a registry of twelve builds across eight page grammars.
If you want to see what a filled-in table looks like, and which shapes tend to
collide, read `EXAMPLES.md` in the scroll-craft repository. Treat it as
illustration only: those rows are somebody else's builds and they do **not**
constrain yours.
