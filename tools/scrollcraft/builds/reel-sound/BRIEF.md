# Reel sound · BRIEF

Self-authored under explicit creative delegation ("show me your senior expertise ... implement fully").

## What was asked (verbatim)
> all reels using some saudi sounds complete properly no skip any mind relax show me your senior experties

And for the Campaign A close:
> سعر الإطلاق الآن، وسنة إضافية بالسعر نفسه. >change to in reel complete properly · سعر الإطلاق الآن، اشترك سنتين للإستفادة من العرض.

Applied as «سعر الإطلاق الآن، اشترك سنتين للاستفادة من العرض.» (hamzat wasl in «للاستفادة»), with the English and
captions to match, in the reel, its keyframe and carousel slide 4, and the A storyboard and ad copy.

## Decisions
- **Original, not licensed:** every sound is synthesised by `tools/reel-audio.py` (oud, qanun, ney, darbuka, tabl, riq,
  frame drum, group hand-claps, drone, whoosh, impact). No samples and no third-party music, so the reels can run as paid
  ads without a licence question.
- **Saudi, not generic "Arabic":** maqam-based melodies with quarter tones, the Khaleeji triplet swing with group claps,
  and Ardah-style ceremonial drums for the groups reel.
- **Composed on the picture:** 120 bpm with two beats a bar, so the cuts at 3, 10 and 20 s land on downbeats. Every card,
  tick and button plays the next note of the maqam; that is the signature move.
- **One score per campaign:** the Arabic and English reels share timing, so they share the score.
- **Delivery:** AAC 192 kb/s, -16 LUFS integrated, -2 dBTP. The scores live beside the reels
  (`book/media/<X>/reels/<X>-score.m4a`) and `tools/render-reels.mjs` muxes them on every render.

| Reel | Maqam | Groove | Lead | Feeling |
|---|---|---|---|---|
| A existing customers | Rast | Khaleeji with claps | oud | warm, celebratory |
| B new hotels | Bayati | light Khaleeji | oud | bright, easy |
| C Opera / PMS | Hijaz | half-time | ney, then oud | cinematic, corporate |
| D hotel groups | Nahawand | Ardah-style drums | ney, then oud | grand, executive |

## Feeling curve (every reel)
| Act | Feeling | What causes it |
|---|---|---|
| Hook 0–3 s | unease | problem cards land on falling notes, riq shakes as they wobble, a riser pulls into the cut |
| Solution 3–10 s | relief | impact on the downbeat, the groove enters lighter, the lead answers the hook |
| Proof 10–20 s | conviction (the peak) | full groove, each card plays the next note, the qanun doubles the climax an octave up |
| Call to action 20–25 s | resolution | the groove thins, the button pops with a qanun run, the cadence lands on the tonic |

**Peak:** "every card that lands plays the next note, until the whole maqam is there." Scene 3, the loudest section by
design (scene 2 sits 2.2 dB under it, the CTA 1 dB under).
**Tell-someone sentence:** "It's the reel where the music finishes each idea as it appears on screen."

## Verified
Durations 25.033 s, matching the 751-frame video. Each score is -16.1 LUFS with true peaks of -2 dBTP or lower, and ends
in silence, so the book's loop is seamless. The strongest onsets land within 20 ms of the cuts. Stems were measured per
band: the darbuka ring and a thin oud were corrected. All eight MP4s carry the AAC track. In the book, one tap turns a
reel's sound on, and only one reel plays sound at a time (audio decode confirmed). The live GSAP reel has a synced sound
button; its centring on screens shorter than 1920 px was also fixed.
**Not verified:** listening. The checks are measurements, so the mix still needs a human ear, ideally on phone speakers.
