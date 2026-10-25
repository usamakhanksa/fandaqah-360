# Landing v2 · offer clarity revision (EN/AR) + Campaign B reel close

**Self-authored under explicit creative delegation.** The owner forwarded a visitor review of
`fandaqah-360-landing-v2/` and asked to "enhance content above comments thinking", update the new-hotels reel, "design
okay". A copy revision of an existing page: no new build, no fingerprint row. Design, layout and assets stay.

## Evidence (the review)

- Product explanation 9/10, offer clarity 5/10, clarity of the 2-year commitment 3/10.
- Not understood from the page: one-time launch offer; price locked for 2 years; extra value from committing now; the
  price may not be available after launch.
- The visitor's question: "Why should I upgrade today instead of waiting until next month?"
- They saw "a countdown timer". The page has none (live or local): the clock icon on the offer note read as one.

## Diagnosis

The page said *what* ("Limited-time offer. Subscribe for 2 years to enjoy this special price.") but never *why*: two
years reads as a cost (a longer commitment), never as the benefit (a price that cannot move). "Limited-time" and a clock
signalled urgency with no reason behind it.

## Decisions

1. **One idea, named the same everywhere:** the launch price, locked for 2 years. EN "lock in", AR «ثبّت».
2. **One action label:** "Lock in my launch price" / «ثبّت سعر الإطلاق» on every coral button (nav keeps its short label).
3. **Answer the question where the page closes:** the CTA heading becomes the visitor's own question, the paragraph the
   answer. This is the peak.
4. **Turn the commitment into value:** price fixed for both years; services added during the subscription included
   (the existing 360 claim, narrowed to the term); priority support (existing fine print).
5. **Remove the risk of committing:** pair the 2 years with the 30-day money-back guarantee already on the page.
6. **Clock becomes a lock** (offer note and the guarantee card): the icon now says "price lock", not "countdown".
7. **Honest scarcity only:** "one-time launch offer", "may not be available after launch". No countdown, no invented
   standard price, no savings figure.

## Feeling curve (the offer thread)

| Where | Feeling | Cause |
|---|---|---|
| Hero offer note | Curiosity | "Lock your price for 2 years": a benefit, not a deadline |
| Pricing | Understanding | Section title and 360 card spell out the lock and what the 2 years include |
| Guarantees | Safety | 2-year price lock beside the 30-day money-back guarantee |
| Close | Decision | "Why upgrade today, not next month?" answered in three sentences |

**Peak:** "they literally asked my question, why today and not next month, and answered it."

## Needs sign-off before launch

- The launch period's end date (or the condition that ends it) in the commercial release record.
- That the 2-year price is fixed for the full term and that later services are included during it (T&Cs).

## Round 2 (second review, 25 Oct 2026)

The second reviewer read the **live** page, which still showed the first version: every deploy since `dbe9bdd` failed
because the playbook PDF committed at the repo root tripped the "no internal files" guard. Fixed in the workflow and
`_config.yml` (root `*.pdf` excluded); the Arabic check still fails on `hotel-groups/` and `existing-customers/`,
which are being edited elsewhere.

Applied, in the page's own elements:

1. **Audience and offer named in the hero note:** "Exclusive launch offer for existing customers" (the reviewer's
   Arabic, verbatim), plus "lock in the promotional price before the offer ends".
2. **What waiting costs:** "Standard pricing applies after the launch" in the hero note, pricing intro, price-lock
   card, close and fine print. (Business-supplied claim; replaces "may not return".) No countdown was added: the page
   never had one, and inventing an end time is not allowed.
3. **Value summary:** the hero value card's four ticks are now 20+ integrated modules (matches "More than 20" on
   the page), one unified platform, launch pricing for 2 years, 30-day golden guarantee.
4. **Golden guarantee:** named and explained (full refund + previous plan restored) on the guarantee card, the 360
   card, the close and the lead form; the seal, tokens and card tile turned gold (`#gold` gradient in the sprite).
5. **CTA carries the objective:** "Lock in my price for 2 years" / «ثبّت السعر لمدة سنتين»; nav "Lock in launch
   pricing" / «احجز سعر الإطلاق».

Campaign B reel close: subline now says what waiting costs. Campaign A's reel was being rewritten in another session
at the same time, so it was left alone.
