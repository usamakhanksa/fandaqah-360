# Campaign A · Snapchat ad (existing customers)

One 9.5-second, 1080×1920 (9:16), 30 fps ad, in Saudi Arabic only, for owners, GMs, finance and operations managers
**who already use Fandaqah**. The idea it plants: *you already use Fandaqah; one upgrade opens a bigger system inside the
same account*. Not "buy a new system".

- **File:** `book/media/A-existing-customers/reels/A-snap-ar.mp4` (H.264 + AAC 48 kHz, −16 LUFS), poster
  `A-snap-ar.jpg`. The live composition is `book/snap.html`; re-render with `node tools/render-snap.mjs`.
- **Safe zone:** every line sits between y 220 and y 1600 (of 1920), clear of Snapchat's top bar and the bottom
  attachment and CTA area. Nothing important touches the outer 40 px.
- **Landing page:** `fandaqah.com/landing/fandaqah-360/existing-customers/` (as briefed). Add the campaign's UTM tags
  in Ads Manager (`utm_source=snapchat&utm_medium=paid_social&utm_campaign=A_existing_customers&utm_content=snap_9s`).
- **Snapchat CTA button:** «تحقق من عرض ترقيتك» to match the end frame (or Snapchat's closest preset, "Learn more").

## Shot list

| Time | Beat | Picture | On screen |
|---|---|---|---|
| 0.0–1.4 | Recognition / pain | Two Saudi front-desk colleagues, she in focus; a purchase request, an incoming call from housekeeping, a maintenance note, an inventory sheet and stock documents pile up around them; the desk screen behind is out of focus. Slow push-in. | تستخدم فندقة بالفعل؟ · احصل على أكثر. |
| 1.4–2.8 | What is still separate | Snap zoom and blur; the five modules appear as dashed cards, each marked «منفصل». | لماذا تدير الباقي بشكل منفصل؟ |
| 2.8–4.0 | The upgrade | Their Fandaqah screen (same top bar, same hotel): the cursor taps «الترقية إلى فندقة 360», the 360 badge lands, and the side menu grows by the five modules and «+15 وحدة». | نفس حسابك. · إمكانيات أكثر. |
| 4.0–6.4 | **Peak:** 20 modules | Navy. Fandaqah 360 at the centre; the five named modules on the inner ring, fifteen icon tiles with ticks on the outer ring, «+15 وحدة إضافية»; connections draw in; the counter steps 05 → 10 → 15 → 20, «وحدة متكاملة» and a tick. | كل ما يحتاجه فندقك. · في فندقة 360. |
| 6.4–7.8 | Connected work | One Fandaqah 360 panel, three flows moving at once: طلب شراء → اعتماد → المشتريات → المخزون · غرفة تحتاج تنظيف → التدبير الفندقي → جاهزة · بلاغ صيانة → تم الإسناد → تم الإنجاز. | منصة واحدة. · عمل أكثر ترابطاً. |
| 7.8–8.7 | The offer | Beige card on navy. | عرض حصري للعملاء الحاليين · فندقة 360 · جميع الوحدات الـ20 · 3,495 ر.س سنوياً · غير شامل الضريبة · يثبت السعر لمدة عامين عند الدفع المسبق |
| 8.7–9.5 | Call to action (end frame) | A calm, smiling Saudi host at an organised reception, the Fandaqah screen beside him; the frame holds. | فندقة التي تعرفها… الآن بإمكانيات أكثر. · رقِّ إلى فندقة 360 · وافتح جميع الوحدات الـ20. · [تحقق من عرض ترقيتك] · fandaqah.com · للعملاء الحاليين |

Interface screens are illustrative and say so («بيانات توضيحية»). No guest data, competitor marks or unapproved claims.

## Sound

The supplied Arabic track, cut so its first chorus lands exactly on the 20-module reveal (4.0 s), with a light sound
design: paper pops and a two-ring phone cue under the busy desk, a snap whoosh, card pops, a clean click on the
upgrade tap, counter ticks at 10, 15 and 20, a success chime at 20, a short rise into the call to action and a logo
sting. The music fades under the last 0.75 s.

## Voice-over (to record)

The ad works muted, and Snapchat plays sound by default, so a voice-over lifts it further. Saudi voice, male or female,
30–40, warm and confident, white Saudi dialect. Record the short version: it fits 9.5 s.

> تستخدم فندقة بالفعل؟ احصل على أكثر.
> رقِّ إلى فندقة 360 وافتح جميع الوحدات العشرين في منصة واحدة.
> نفس حسابك… إمكانيات أكثر.
> تحقق من عرض ترقيتك.

Lay it over the music at about −8 dB music / 0 dB voice, and keep the peak at 4.0 s on «جميع الوحدات العشرين».

## Before it runs

- **Price and term.** The card shows SAR 3,495 a year excl. VAT, "fixed for two years when paid upfront", as briefed and
  as on the existing-customers landing page. The main landing page (`fandaqah-360-landing-v2`) describes a one-year
  subscription with an *optional* second year at the same rate. Confirm one offer in the signed release record before
  this runs; if the price is not approved for the campaign, remove the price lines from scene 6 in `book/snap.html`
  and re-render.
- **Photography.** The brief describes live action of one receptionist; this cut uses Fandaqah's own Saudi hotel
  photography (two colleagues in the opening, a reception host in the close) with interface motion. A filmed version
  can follow the same shot list.
- **Arabic review** by a fluent Saudi reviewer.
