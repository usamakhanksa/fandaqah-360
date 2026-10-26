# Campaign A · Snapchat ads (existing customers)

Two separate ads, both Saudi Arabic, 1080×1920, 30 fps, both downloadable from the campaign book (Downloads → A):

| | Film ad (recommended) | Motion ad |
|---|---|---|
| File | `book/media/A-existing-customers/reels/A-snap2-ar.mp4` | `…/A-snap-ar.mp4` |
| Length | 27 s | 25.5 s |
| Picture | the supplied receptionist film + Fandaqah 360 interface | Fandaqah photography + interface motion |
| Composition | `book/snap2.html` | `book/snap.html` |
| Render | `SNAP_PAGE=snap2 node tools/render-snap.mjs` | `node tools/render-snap.mjs` |

**Pace:** both are deliberately slow and calm (owner's request, twice): every time in the shot lists below is the
written beat, and the finished ads play it **1.5× slower** (`SLOW` in `snap2.html`, `SEG` in `snap.html`), music and
sound cues included.

**The offer, in both:** SAR 3,495 a year + VAT at the launch price, with the option to secure the same rate for one
additional year during the offer; 30-day Golden Guarantee; terms apply. Never "two years required" or "SAR 6,990 upfront".

---

## Film ad (A-snap2-ar, 27 s; times below × 1.5)

Built on the owner's second brief: *I already use Fandaqah → I can run far more of the hotel from the same system →
20+ connected solutions → a launch offer that makes the decision easy.* The film (720×1280, supplied) is upscaled once
to `book/media/A-existing-customers/snap/receptionist.mp4`; its English screen is never shown in focus (frosted, or
replaced by the Arabic interface), and its weary close-up only appears blurred under the cards.

| Time | Beat | Picture | On screen |
|---|---|---|---|
| 0.0–2.4 | Hook | Her at the desk, hand to cheek (film 0–2 s), slow push-in; five quiet to-dos stack on the frosted screen: مستند مشتريات · تنبيه صيانة · جدول المخزون · مهمة تدبير فندقي · تقرير مالي | تستخدم فندقة بالفعل؟ · احصل على أكثر. |
| 2.4–4.4 | The gap | Film 2–4 s, blurred under navy; five dashed cards marked «منفصل»: المشتريات · المخزون · الصيانة · التدبير · المحاسبة | لماذا تدير الباقي بشكل منفصل؟ |
| 4.4–6.6 | The upgrade | Over her shoulder (film 4–5 s), then her Fandaqah screen: the tap on «الترقية إلى فندقة 360», the 360 badge, the menu grows | فندقة التي تعرفها. · بإمكانيات أكبر. |
| 6.6–10.4 | **Hero:** 20+ | Four families land one by one, each with its solutions as short labels, then all four wire to a «20+ حل متكامل» hub. التشغيل · المبيعات والمالية · الضيف والمطاعم · التكاملات المحلية | أكثر من 20 حلاً متكاملاً · منصة واحدة |
| 10.4–12.6 | One workflow | حجز جديد → تحديث الغرفة → التدبير → المخزون → التقارير, each ticked | من الحجز إلى التشغيل. · كل شيء مترابط. |
| 12.6–15.0 | Offer | Beige card on navy | عرض إطلاق للعملاء الحاليين · 3,495 ر.س · سنوياً + الضريبة · ويمكنك تثبيت نفس السعر لسنة إضافية خلال العرض · ضمان ذهبي 30 يوماً · تطبق الشروط والأحكام. |
| 15.0–18.0 | Payoff + end frame | Her, standing, smiling (film 8–10 s, slowed), then the end card holds | فندقة التي تعرفها. بإمكانيات أكبر. · رقِّ إلى فندقة 360 · أكثر من 20 حلاً. منصة واحدة. · 3,495 ر.س سنوياً + الضريبة · [احجز سعر الإطلاق] · ضمان ذهبي 30 يوماً · fandaqah.com · للعملاء الحاليين. تطبق الشروط والأحكام. |

**Sound:** the Arabic track with its first chorus on the hero (9.9 s); under it, soft pops as the to-dos and cards land,
a light notification, a whoosh on each cut, the upgrade click, one confirmation tone per family, a chord on «20+», ticks
on the workflow, a low impact on the offer, a rise into the end card and the logo sting.

**Voice-over (to record, Saudi, warm, unhurried):**

> تستخدم فندقة؟ رقِّ إلى فندقة 360.
> أكثر من عشرين حلاً للتشغيل، المالية وتجربة الضيف في منصة واحدة.
> ابدأ بسعر الإطلاق، وثبّت نفس السعر لسنة إضافية.
> فندقة 360. احجز سعر الإطلاق اليوم.

**Snapchat CTA button:** «احجز سعر الإطلاق». UTM `utm_content=snap_film_27s`.

---

## Motion ad (A-snap-ar, 25.5 s)

One 25.5-second, 1080×1920 (9:16), 30 fps ad, in Saudi Arabic only, for owners, GMs, finance and operations managers
**who already use Fandaqah**. The idea it plants: *you already use Fandaqah; one upgrade opens a bigger system inside the
same account*. Not "buy a new system".

- **File:** `book/media/A-existing-customers/reels/A-snap-ar.mp4` (H.264 + AAC 48 kHz, −16 LUFS), poster
  `A-snap-ar.jpg`. The live composition is `book/snap.html`; re-render with `node tools/render-snap.mjs`.
- **Pace:** played at normal reading speed (owner's request): the 9.5 s beat sheet below is stretched scene by scene
  in `book/snap.html` (`SEG`), so quick moves stay quick and the offer and the end frame hold long enough to read.
- **Safe zone:** every line sits between y 220 and y 1600 (of 1920), clear of Snapchat's top bar and the bottom
  attachment and CTA area. Nothing important touches the outer 40 px.
- **Landing page:** `fandaqah.com/landing/fandaqah-360/existing-customers/` (as briefed). Add the campaign's UTM tags
  in Ads Manager (`utm_source=snapchat&utm_medium=paid_social&utm_campaign=A_existing_customers&utm_content=snap_motion_25s`).
- **Snapchat CTA button:** «تحقق من عرض ترقيتك» to match the end frame (or Snapchat's closest preset, "Learn more").

## Shot list

| Time | Beat | Picture | On screen |
|---|---|---|---|
| 0.0–3.3 | Recognition / pain | Two Saudi front-desk colleagues, she in focus; a purchase request, an incoming call from housekeeping, a maintenance note, an inventory sheet and stock documents pile up around them; the desk screen behind is out of focus. Slow push-in. | تستخدم فندقة بالفعل؟ · احصل على أكثر. |
| 3.3–6.3 | What is still separate | Snap zoom and blur; the five modules appear as dashed cards, each marked «منفصل». | لماذا تدير الباقي بشكل منفصل؟ |
| 6.3–9.6 | The upgrade | Their Fandaqah screen (same top bar, same hotel): the cursor taps «الترقية إلى فندقة 360», the 360 badge lands, and the side menu grows by the five modules and «+15 وحدة». | نفس حسابك. · إمكانيات أكثر. |
| 9.6–14.7 | **Peak:** 20 modules | Navy. Fandaqah 360 at the centre; the five named modules on the inner ring, fifteen icon tiles with ticks on the outer ring, «+15 وحدة إضافية»; connections draw in; the counter steps 05 → 10 → 15 → 20, «وحدة متكاملة» and a tick. | كل ما يحتاجه فندقك. · في فندقة 360. |
| 14.7–18.0 | Connected work | One Fandaqah 360 panel, three flows moving at once: طلب شراء → اعتماد → المشتريات → المخزون · غرفة تحتاج تنظيف → التدبير الفندقي → جاهزة · بلاغ صيانة → تم الإسناد → تم الإنجاز. | منصة واحدة. · عمل أكثر ترابطاً. |
| 18.0–21.6 | The offer | Beige card on navy. | عرض حصري للعملاء الحاليين · فندقة 360 · جميع الوحدات الـ20 · 3,495 ر.س · سنوياً + الضريبة · خلال فترة العرض · ويمكنك تثبيت نفس السعر لسنة إضافية |
| 21.6–25.5 | Call to action (end frame) | A calm, smiling Saudi host at an organised reception, the Fandaqah screen beside him; the frame holds. | فندقة التي تعرفها… الآن بإمكانيات أكثر. · رقِّ إلى فندقة 360 · وافتح جميع الوحدات الـ20. · [تحقق من عرض ترقيتك] · fandaqah.com · للعملاء الحاليين |

Interface screens are illustrative and say so («بيانات توضيحية»). No guest data, competitor marks or unapproved claims.

## Sound

The supplied Arabic track, cut so its first chorus lands exactly on the 20-module reveal (9.6 s), with a light sound
design: paper pops and a two-ring phone cue under the busy desk, a snap whoosh, card pops, a clean click on the
upgrade tap, counter ticks at 10, 15 and 20, a success chime at 20, a short rise into the call to action and a logo
sting. The music fades under the last 0.75 s.

## Voice-over (to record)

The ad works muted, and Snapchat plays sound by default, so a voice-over lifts it further. Saudi voice, male or female,
30–40, warm and confident, white Saudi dialect. At 17 s the full version fits:

> تستخدم فندقة بالفعل؟ حان الوقت تحصل على أكثر.
> رقِّ إلى فندقة 360، وفعّل جميع الوحدات العشرين داخل نفس المنصة.
> المحاسبة، المشتريات، المخزون، الصيانة، والتدبير الفندقي… وأكثر.
> فندقة 360. نفس حسابك… إمكانيات أكثر.
> تحقق من عرض ترقيتك.

Lay it over the music at about −8 dB music / 0 dB voice, and keep «جميع الوحدات العشرين» on the peak at 9.6 s.

## Before it runs

- **Price and term.** Both ads show SAR 3,495 a year + VAT with the option of a second year at the same rate, as the
  owner's second brief and the main landing page say. The existing-customers landing page still describes a two-year
  upfront lock: align it, or this ad, before launch, and put the offer's end date in the signed release record.
- **Photography.** The brief describes live action of one receptionist; this cut uses Fandaqah's own Saudi hotel
  photography (two colleagues in the opening, a reception host in the close) with interface motion. A filmed version
  can follow the same shot list.
- **Arabic review** by a fluent Saudi reviewer.
