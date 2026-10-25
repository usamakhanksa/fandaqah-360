# Fandaqah 360 landing (v2): integrations, guarantee, instalments

**Self-authored under explicit creative delegation.** The owner asked for real partner logos with interlinking, the
tabby / tamara instalment offer switched off for now, a 30-day money-back guarantee in place of "30-Day Upgrade
Protection", and "senior expertise ... unique, professional, corporate ... 100% finished". This is an enhancement of
the existing page (`fandaqah-360-landing-v2/index.html`), not a new page grammar, so no row is added to
`FINGERPRINTS.md`.

## Decisions

- **Real logos only.** Every partner mark is the brand's own artwork, never a redraw. Sizes are set per logo
  (area-normalised, then tuned by eye) so the wall reads evenly. Files: `fandaqah-360-landing-v2/assets/img/partners/`.
- **Interlinking goes inward.** Links point at Fandaqah's own feature pages (`landing/features/`, Arabic twins in
  `landing/ar/features/`), not partner sites: a campaign page should not hand visitors to Booking.com. Each
  integrations card names the module the partners connect through ("Synced by Channel Manager") and links to it;
  government rows link to their own pages; all 20 modules in "What's Included" link to theirs; the links follow the
  EN / AR toggle.
- **Instalments: one switch.** `data-bnpl="off"` on `<html>` hides every tabby / tamara badge and logo (pricing
  cards, contact box, Payment Options tiles). The Payment Gateway page lists mada, Visa, Mastercard and Apple Pay,
  and mentions tabby / tamara only as the subscription instalment offer, so the tiles follow the same switch.
- **Money-back guarantee, where the decision is made.** Leads the Guarantees row with a seal; repeated as a token on
  the Fandaqah 360 price card (links to the guarantee), in the contact box, and in the lead form for 360 / general
  requests only. Copy carries "Terms apply". Not claimed for Basic or Compliance.

## Signature moves

1. **Integrations come online.** As each card arrives, its partner marks colour in one after another (grey to full
   brand colour), then the module link settles. Pure CSS on the page's existing reveal; reduced motion shows the
   final state.
2. **The seal turns with the scroll.** The scalloped edge and its dashed inner ring rotate against each other as the
   page moves and stop when it stops. Static under reduced motion.

## Logo provenance (checked 25 Oct 2026)

| File | Source |
|---|---|
| mada, apple-pay, visa, mastercard, booking, agoda, expedia, trip, airbnb, hotels | Wikimedia Commons originals (Mada Logo, Apple Pay logo, Visa Inc. logo 2021–present, Mastercard 2019 logo, Booking.com logo, Agoda Logo 2022, Expedia Logo 2023, Trip.com logo, Airbnb Logo Bélo, Hotels.com Logo 2025), optimised with svgo |
| mot.webp | Wikimedia Commons "Ministry Of Tourism.jpg" (CC BY-SA 4.0); white ground converted to alpha. mt.gov.sa blocks automated access |
| zatca.svg | zatca.gov.sa header logo |
| shomoos.svg | shomoos.gov.sa portal logo. Note: shomoos.sa is an unrelated home-security shop |
| tabby.svg | tabby.ai header logo |
| tamara.svg | tamara.co text logo (black, EN) |
| hrs.svg | hrs.com header logo |
| zkteco.webp | zkteco.me (ZKTeco Middle East) header logo; its white "ZK" (made for a dark header) recoloured to dark ink for light grounds |

## Verified

Desktop 1440, mobile 390, both languages, reduced motion; no horizontal overflow at 1440 / 1280 / 1024 / 768 / 390 /
360 in EN and AR; switch on and off; lead-form guarantee per plan; seal rotation; `check-i18n` and `check-links` pass.
Not verified: a real phone.
