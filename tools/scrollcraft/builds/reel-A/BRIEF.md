# Campaign A reel (existing customers): content brief

**Self-authored under explicit creative delegation.** The owner asked to update the Campaign A reel content in English and
Arabic, "complete properly ... eye-catching", thinking as a senior content writer. This is a copy revision of the
existing 25-second GSAP reel (`book/reel.html`, copy in `book/assets/data.js`), not a new page, so no fingerprint row.

## Constraints (observed)

- **Playbook p.2 storyboard:** 0–3 s manager switching between manual records, "Already with Fandaqah? Get more value."
  3–10 s two modules the customer does not use, "Connect more of your daily work." 10–20 s one useful task in the
  live product, "See what your upgrade includes." 20–25 s offer and upgrade button.
- **The owner's latest decision (commit 21ff6e7, 25 Oct 11:46):** Campaign A's offer is the "limited-time offer",
  replacing "two-year prepaid offer" across A. Kept. The playbook asks for the offer's real end date in the commercial
  release record before "limited" wording runs; flagged, not overridden.
- **The reel's mechanics:** headline words rise one by one; captions carry the voice-over for muted viewing; scene 3
  ticks the five modules shown on the existing-customers page (accounting, purchasing, housekeeping, maintenance,
  inventory). Headlines stay the approved on-screen lines.

## What was wrong with the copy

1. The hook caption repeated the headline, wasting the three seconds that must make the problem visible.
2. The three "manual records" in the hook were generic (spreadsheet, paper log, manual report) and paid off nothing.
3. Scene 2's supporting line listed all five modules, stealing scene 3, while its caption named two.
4. Scene 3's caption ("See each step flow into the next") named no task; the playbook asks for one useful task.
5. The close carried about 35 words in 4.5 seconds and said "limited-time" and "upgrade" twice each.

## Decisions

- **Setup and payoff.** The hook's manual records become the modules: purchase spreadsheet (purchasing), paper room
  log (housekeeping), month-end by hand (accounting). Scene 3 ticks them off.
- **Captions are the voice-over, short enough to read with the headline:** 5, 9, 11 and 5 words.
- **Scene 2 is about the two new modules and continuity:** "Same system, same team, more of your hotel." The headline
  already says "connect", so the supporting line doesn't repeat it.
- **Scene 3 names the task:** "Enter a purchase order once, and it reaches your accounts." The five ticked cards
  already say "five modules", so the caption spends its words on the task (product approval still required, as the
  storyboard notes). It breaks at the comma; «أمر الشراء» and "purchase order" are held together with a no-break space.
- **The close is one line:** request your upgrade review today. The supporting line answers the main objection
  (paying for tools they may not need): the review confirms the modules they need.

## Feeling curve

| Beat | Feeling | Cause |
|---|---|---|
| Hook | Recognition | Their own manual records, named |
| Solution | Relief | The same system, now with accounting and purchasing |
| Proof | Confidence | Five modules ticked; one task entered once |
| Close | Intent | One clear action |

**Peak:** "the paper log and the spreadsheet turned into ticks on the screen" (the proof beat).

## Found while verifying

All four captions share one grid cell, so a one-line caption was stretched to the two-line box of the longest one
(text at the top, empty band below). It already affected the shipped Arabic B and C reels. Fixed in `reel.css`
(`align-self:end`, bottom edge stays on the Reels interface line); re-rendered A (AR/EN), B-ar and C-ar. B-en, C-en
and D have uniform caption heights, so their frames are unchanged.

## Owner revision (25 Oct 2026)

Scene 3 caption changed by the owner to «والمزيد من الخدمات الأخرى.» / "And many more services.": the five ticked
modules are shown as a sample of the wider platform rather than one task.
