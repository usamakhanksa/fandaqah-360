"""Original Saudi soundtrack for the four 25-second campaign reels, composed on each reel's own timeline.

The reels cut at 3, 10 and 20 s (book/assets/reel.js). At 120 bpm with two beats a bar, every cut lands on a downbeat, and
every card, tick and button that appears on screen plays a note of the campaign's maqam, so the sound tells the same story
as the picture. Instruments: oud, qanun, ney, darbuka (doum and tek), tabl, riq, frame drum and group hand-claps over a
soft drone, with a whoosh and a low impact on each cut. Everything is synthesised here: no samples, no third-party music,
nothing to license.

  A  existing customers  maqam Rast, Khaleeji groove with hand-claps     warm, celebratory
  B  new hotels          maqam Bayati, light Khaleeji groove            bright, easy
  C  Opera / PMS         maqam Hijaz, half-time, ney over the drone      cinematic, corporate
  D  hotel groups        maqam Nahawand, Ardah-style ceremonial drums   grand, executive

The reels now use the supplied Arabic track, edited per reel by tools/reel-music.py, which writes the same files. This
synthesised score stays as a fallback: running it replaces those edits.

Usage:  python -I tools/reel-audio.py              write all four scores and mux each into its two reels
        python -I tools/reel-audio.py A C          only these campaigns
        python -I tools/reel-audio.py --no-mux     write the scores only (tools/render-reels.mjs muxes them when it renders)
Output: book/media/<X>/reels/<X>-score.m4a  (AAC 192 kb/s, 48 kHz stereo, -16 LUFS integrated, -2 dBTP true peak)
        --stems <dir> also writes each levelled instrument bus as a WAV, for checking the balance
Needs numpy, scipy and ffmpeg on PATH.
"""
import json, pathlib, re, subprocess, sys, tempfile
import numpy as np
from scipy import ndimage, signal
from scipy.io import wavfile

sys.stdout.reconfigure(encoding='utf-8')
ROOT = pathlib.Path(__file__).resolve().parent.parent
FOLDERS = {'A': 'A-existing-customers', 'B': 'B-new-hotels', 'C': 'C-opera-pms', 'D': 'D-hotel-groups'}
SR = 48000
DUR = 751 / 30                 # every reel is 751 frames at 30 fps
N = int(round(DUR * SR))
SLOT = 1 / 6                   # 120 bpm, two beats a bar, three triplets a beat: one bar per second, Khaleeji swing
CURVE = [(0, 0), (3, 0), (3.4, -2.2), (9.8, -2.2), (10.25, 0), (19.8, 0), (20.3, -1.0), (DUR, -1.0)]   # section gain, dB
STEMS = pathlib.Path(sys.argv[sys.argv.index('--stems') + 1]) if '--stems' in sys.argv else None   # write levelled buses

# maqam scales in cents above the tonic, quarter tones included
MAQAM = {'rast': [0, 200, 350, 500, 700, 900, 1050], 'bayati': [0, 150, 300, 500, 700, 800, 1000],
         'hijaz': [0, 100, 400, 500, 700, 800, 1000], 'nahawand': [0, 200, 300, 500, 700, 800, 1100]}

CFG = {
    'A': dict(maqam='rast', tonic=130.81, bass=-1, lead2='oud', groove='khaleeji', claps_from=5, seed=11),
    'B': dict(maqam='bayati', tonic=146.83, bass=-1, lead2='oud', groove='khaleeji', claps_from=4, seed=23),
    'C': dict(maqam='hijaz', tonic=146.83, bass=-1, lead2='ney', groove='halftime', claps_from=12, seed=37),
    'D': dict(maqam='nahawand', tonic=196.00, bass=-2, lead2='ney', groove='ardah', claps_from=15, seed=41),
}

# what appears on screen and when (seconds), from the reel timeline; each event plays the next note
EVENTS = {
    'A': dict(cards=[11 + .55 * n for n in range(5)], card_deg=[7, 8, 9, 10, 11], ticks=[11.55 + .55 * n for n in range(5)],
              draw=True, cta='gliss'),
    'B': dict(cards=[11 + .55 * n for n in range(4)], card_deg=[7, 11, 14, 16], links=[11.4 + .55 * n for n in range(3)],
              broken=[.9, 1.0], draw=True, cta='gliss'),
    'C': dict(core=4.5, nodes=[5.3, 5.45, 5.6], node_deg=[7, 9, 11], cards=[11, 11.55, 12.1], card_deg=[7, 9, 11],
              ticks=[11.55, 12.1, 12.65], checks=[21 + .14 * n for n in range(4)], check_deg=[11, 12, 13, 14], cta='chord'),
    'D': dict(tiles=[5.1 + .1 * n for n in range(4)], cards=[10.9, 11.15, 11.4], card_deg=[7, 9, 12],
              align=[13.0, 13.3, 13.6], align_deg=11, chip=14.6, draw=True, cta='gliss'),
}

# melodic phrases: (bar, triplet slot, maqam degree, length in slots, flags); 't' = oud tremolo
P2 = [(4, 0, 0, 2, ''), (4, 2, 1, 1, ''), (4, 3, 2, 2, ''), (4, 5, 1, 1, ''),
      (5, 0, 2, 3, 't'), (5, 3, 3, 1, ''), (5, 4, 2, 1, ''), (5, 5, 1, 1, ''),
      (6, 0, 0, 4, 't'), (6, 4, -1, 1, ''), (6, 5, 0, 1, ''),
      (7, 0, 2, 2, ''), (7, 2, 3, 1, ''), (7, 3, 4, 3, 't'),
      (8, 0, 3, 1, ''), (8, 1, 2, 1, ''), (8, 2, 3, 1, ''), (8, 3, 2, 1, ''), (8, 4, 1, 1, ''), (8, 5, 2, 1, ''),
      (9, 0, 0, 4, 't')]
P3 = [(14, 0, 4, 2, ''), (14, 2, 5, 1, ''), (14, 3, 4, 1, ''), (14, 4, 3, 1, ''), (14, 5, 4, 1, ''),
      (15, 0, 5, 3, 't'), (15, 3, 6, 1, ''), (15, 4, 5, 1, ''), (15, 5, 4, 1, ''),
      (16, 0, 3, 2, ''), (16, 2, 4, 1, ''), (16, 3, 2, 2, ''), (16, 5, 1, 1, ''),
      (17, 0, 2, 3, 't'), (17, 3, 1, 1, ''), (17, 4, 2, 1, ''), (17, 5, 3, 1, ''),
      (18, 0, 4, 5, 't')]
OSTINATO = [(b, s, d, 1, '') for b in (10, 11, 12, 13) for s, d in ((0, -7), (2, -3), (3, -7), (5, -4))]
P4 = [(22, 0, 4, 1, ''), (22, 1, 3, 1, ''), (22, 2, 2, 1, ''), (22, 3, 1, 2, ''), (22, 5, 2, 1, ''),
      (23, 0, 1, 2, ''), (23, 2, 0, 4, 't')]
HOOK_NEY = [(1, 1, 0, 3, ''), (1, 4, 1, 2, ''), (2, 0, 2, 2, ''), (2, 2, 1, 3, '')]


# ---------------------------------------------------------------- dsp
def butter(x, kind, f, order=2):
    return signal.sosfilt(signal.butter(order, f, btype=kind, fs=SR, output='sos'), x, axis=-1)


def peaking(x, f, db, q=1.0):
    a_ = 10 ** (db / 40); w = 2 * np.pi * f / SR; al = np.sin(w) / (2 * q)
    return signal.lfilter([1 + al * a_, -2 * np.cos(w), 1 - al * a_], [1 + al / a_, -2 * np.cos(w), 1 - al / a_], x, axis=-1)


def tt(n):
    return np.arange(n) / SR


def pluck(freq, dur, rng, t60=1.4, cut=6000, pos=0.14, warm=False, os_=2):
    """Karplus-Strong string at 2x the sample rate (so quarter tones stay within a few cents), block-recursive.
    warm=True uses a three-tap loop filter, so the upper partials die away quickly as on a gut or nylon oud string."""
    sr = SR * os_; n = int(dur * sr); p = max(3, int(round(sr / freq - (1 if warm else 0.5))))
    g = 10 ** (-3 / (t60 * freq))
    exc = signal.sosfiltfilt(signal.butter(2, cut, fs=sr, output='sos'), rng.uniform(-1, 1, p * 3))[p:2 * p]
    exc = exc - 0.85 * np.roll(exc, max(1, int(pos * p)))      # pluck position
    exc -= exc.mean()
    y = np.zeros(n + p + 2); y[:p] = exc
    i = p
    while i < n:
        j = min(i + p, n); idx = np.arange(i, j)
        y[i:j] = g * (0.25 * y[idx - p] + 0.5 * y[idx - p - 1] + 0.25 * y[idx - p - 2]) if warm else g * 0.5 * (y[idx - p] + y[idx - p - 1])
        i = j
    y = signal.resample_poly(y[:n], 1, os_)
    return y / (np.sqrt(np.mean(y[:int(0.05 * SR)] ** 2)) + 1e-9) * 0.25


def release(x, at, length):
    t = tt(len(x))
    return x * np.clip(1 - (t - at) / length, 0, 1) ** 2


# ---------------------------------------------------------------- instruments
def oud(freq, dur, rng, vel=1.0, trem=False):
    ring = dur + 0.4
    if not trem:
        x = pluck(freq, ring, rng, t60=1.5, cut=2600, pos=0.13, warm=True)
    else:                                   # risha tremolo: alternating down and up strokes, 12 a second
        x = np.zeros(int(ring * SR)); starts = np.arange(0, max(dur - 0.03, SLOT / 2), SLOT / 2)
        for k, st in enumerate(starts):
            seg = pluck(freq, ring - st, rng, t60=1.2, cut=2200, pos=0.13, warm=True) * (0.9 if k % 2 == 0 else 0.6) * (0.85 + 0.15 * rng.random())
            a = int(st * SR)
            if k + 1 < len(starts):        # the next stroke damps the string
                seg = seg[:int((starts[k + 1] - st) * SR) + 144]; seg[-144:] *= np.linspace(1, 0, 144)
            x[a:a + len(seg)] += seg[:len(x) - a]
        x *= 0.72
    pick = butter(rng.uniform(-1, 1, int(0.004 * SR)), 'bandpass', [1500, 5000]) * 0.1
    x[:len(pick)] += pick
    return release(x, dur, 0.28) * vel


def qanun(freq, dur, rng, vel=1.0):
    ring = dur + 1.3
    x = sum(pluck(freq * 2 ** (c / 1200), ring, rng, t60=2.6, cut=9000, pos=0.07) for c in (-3, 0, 3)) / 3
    return release(x, dur + 0.8, 0.5) * vel


def ney(freq, dur, rng, vel=1.0):
    n = int((dur + 0.3) * SR); t = tt(n)
    env = np.clip(t / 0.14, 0, 1) * np.clip((dur + 0.3 - t) / 0.3, 0, 1) * (0.9 + 0.1 * np.sin(2 * np.pi * 0.8 * t))
    vib = 1 + 0.006 * np.sin(2 * np.pi * 5.1 * t) * np.clip((t - 0.3) / 0.4, 0, 1)
    ph = 2 * np.pi * np.cumsum(freq * vib) / SR
    tone = np.sin(ph) + 0.18 * np.sin(2 * ph + 0.3) + 0.07 * np.sin(3 * ph)
    breath = butter(rng.normal(0, 1, n), 'bandpass', [freq * 0.8, min(freq * 5, 9000)]) * 0.35
    chiff = butter(rng.normal(0, 1, n), 'bandpass', [1200, 5000]) * np.exp(-t / 0.05) * 0.4
    return (tone * 0.7 + breath + chiff) * env * vel * 0.45


def doum(rng, vel=1.0, low=62, high=155):
    n = int(0.55 * SR); t = tt(n)
    body = np.sin(2 * np.pi * np.cumsum(low + (high - low) * np.exp(-t / 0.022)) / SR) * np.exp(-t / 0.26)
    knock = butter(rng.uniform(-1, 1, n) * np.exp(-t / 0.008), 'lowpass', 1200) * 0.5
    return np.tanh(1.6 * (body + knock)) / np.tanh(1.6) * vel


def tek(rng, vel=1.0, dark=False):
    n = int(0.18 * SR); t = tt(n)
    noise = butter(rng.uniform(-1, 1, n), 'bandpass', [900, 4000] if dark else [1900, 7500]) * np.exp(-t / (0.03 if dark else 0.022))
    ring = np.sin(2 * np.pi * (520 if dark else 820) * t) * np.exp(-t / 0.035) * 0.18
    return (noise * 1.4 + ring) * vel


def tabl(rng, vel=1.0):
    n = int(1.1 * SR); t = tt(n)
    body = np.sin(2 * np.pi * np.cumsum(46 + 60 * np.exp(-t / 0.04)) / SR) * np.exp(-t / 0.65)
    beat = butter(rng.uniform(-1, 1, n) * np.exp(-t / 0.012), 'bandpass', [150, 1800]) * 0.6
    return np.tanh(1.4 * (body + beat)) / np.tanh(1.4) * vel


def riq(rng, vel=1.0, decay=0.07):
    n = int(0.35 * SR); t = tt(n)
    hiss = butter(rng.uniform(-1, 1, n), 'highpass', 6000) * np.exp(-t / decay)
    metal = sum(np.sin(2 * np.pi * f * t + rng.uniform(0, 6.3)) for f in rng.uniform(5200, 11500, 7)) * np.exp(-t / (decay * 1.4)) * 0.12
    return (hiss * 0.8 + metal) * vel


def daf(rng, vel=1.0):
    n = int(0.6 * SR); t = tt(n)
    body = np.sin(2 * np.pi * np.cumsum(78 + 50 * np.exp(-t / 0.03)) / SR) * np.exp(-t / 0.3)
    slap = butter(rng.uniform(-1, 1, n) * np.exp(-t / 0.01), 'bandpass', [300, 3000]) * 0.5
    x = np.tanh(1.3 * (body + slap)) / np.tanh(1.3)
    j = riq(rng, 0.25, 0.12); x[:len(j)] += j
    return x * vel


def clap(rng):
    n = int(0.22 * SR); t = tt(n); x = np.zeros(n)
    for off, g in ((0, .7), (.007, .8), (.014, 1.0)):
        x += rng.uniform(-1, 1, n) * np.exp(-np.maximum(t - off, 0) / 0.0045) * (t >= off) * g
    x += rng.uniform(-1, 1, n) * np.exp(-np.maximum(t - .016, 0) / 0.06) * (t >= .016) * 0.45
    return butter(x, 'bandpass', [850, 3600])


def bass(freq, dur, rng, vel=1.0):
    n = int((dur + 0.12) * SR); t = tt(n)
    env = np.clip(t / 0.006, 0, 1) * np.exp(-t / 0.9) * np.clip((dur + 0.12 - t) / 0.12, 0, 1)
    ph = 2 * np.pi * freq * t
    return np.tanh(1.2 * (np.sin(ph) + 0.25 * np.sin(2 * ph) + 0.08 * np.sin(3 * ph)) * env) * vel


def pad(freqs, dur, rng, cutoff=1500, attack=1.2, rel=1.4):
    n = int((dur + rel) * SR); t = tt(n); L = np.zeros(n); R = np.zeros(n)
    for f in freqs:
        for cents, side in ((-6, 0), (5, 1), (0, 2)):
            saw = 2 * ((f * 2 ** (cents / 1200) * t + rng.random()) % 1) - 1
            if side == 0: L += saw
            elif side == 1: R += saw
            else: L += saw * 0.7; R += saw * 0.7
    env = np.clip(t / attack, 0, 1) * np.clip((dur + rel - t) / rel, 0, 1)
    return np.vstack([butter(L, 'lowpass', cutoff), butter(R, 'lowpass', cutoff)]) * env / (len(freqs) * 2.2)


def sweep(dur, rng, f0, f1, shape):
    """Filtered-noise sweep (riser, whoosh) shaped in the STFT domain."""
    n = int(dur * SR); x = rng.normal(0, 1, n)
    f, _, Z = signal.stft(x, SR, nperseg=2048)
    k = np.linspace(0, 1, Z.shape[1]); centre = f0 * (f1 / f0) ** k
    Z *= np.exp(-0.5 * (np.log2(np.maximum(f[:, None], 20) / centre[None, :]) / 0.55) ** 2) * shape(k)[None, :]
    return signal.istft(Z, SR, nperseg=2048)[1][:n]


def impact(rng):
    n = int(1.6 * SR); t = tt(n)
    boom = np.sin(2 * np.pi * np.cumsum(38 + 55 * np.exp(-t / 0.06)) / SR) * np.exp(-t / 0.9)
    thump = butter(rng.uniform(-1, 1, n) * np.exp(-t / 0.03), 'lowpass', 500) * 0.6
    return np.tanh(1.3 * (boom + thump)) / np.tanh(1.3)


def reverb_ir(t60, rng, predelay=0.018):
    n = int(t60 * SR); t = tt(n); pd = int(predelay * SR); irs = []
    for ch in range(2):
        tail = butter(butter(rng.normal(0, 1, n) * np.exp(-6.91 * t / t60), 'lowpass', 5200), 'highpass', 160)
        ir = np.zeros(n + pd); ir[pd:] = tail
        for d, g in ((0.009, 0.5), (0.017, 0.38), (0.026, 0.3), (0.041, 0.22)):
            ir[pd + int((d + ch * 0.0023) * SR)] += g * 8
        irs.append(ir / np.sqrt(np.sum(ir ** 2)))
    return irs


# ---------------------------------------------------------------- score
class Score:
    # bus: (target active RMS in dBFS before the master, reverb send)
    BUS = {'oud': (-17, .22), 'ney': (-19, .4), 'qanun': (-21, .35), 'drum': (-19, .12), 'hand': (-25, .15),
           'clap': (-24, .3), 'bass': (-22, 0), 'pad': (-28, .25), 'fx': (-25, .35)}

    def __init__(self, rng, code):
        self.rng = rng; self.code = code; self.buses = {}

    def add(self, bus, x, t, gain=1.0, pan=0.0, jitter=0.0):
        t += self.rng.normal(0, jitter) if jitter else 0
        if x.ndim == 1:
            a = (pan + 1) * np.pi / 4; x = np.vstack([x * np.cos(a), x * np.sin(a)])
        b = self.buses.setdefault(bus, np.zeros((2, N)))
        s = int(round(t * SR))
        if s < 0: x = x[:, -s:]; s = 0
        e = min(N, s + x.shape[1])
        if e > s: b[:, s:e] += x[:, :e - s] * gain

    def mix(self, t60):
        eq = {'oud': lambda x: butter(peaking(peaking(peaking(peaking(x, 140, 3.5, 1.1), 300, 2.5, 1.2), 700, -2), 1800, 1, 1.2), 'lowpass', 5500),
              'qanun': lambda x: peaking(butter(x, 'highpass', 180), 3500, 3),
              'ney': lambda x: butter(butter(x, 'highpass', 200), 'lowpass', 7000),
              'clap': lambda x: butter(x, 'highpass', 400), 'bass': lambda x: butter(x, 'lowpass', 900),
              'pad': lambda x: butter(x, 'highpass', 70)}
        dry = np.zeros((2, N)); wet = np.zeros((2, N)); levels = {}
        for name, x in self.buses.items():
            x = eq.get(name, lambda v: v)(x)
            frames = np.sqrt(np.mean(np.max(np.abs(x), axis=0)[:N // 2400 * 2400].reshape(-1, 2400) ** 2, axis=1))
            active = frames[frames > 10 ** (-50 / 20)]
            rms = 20 * np.log10(np.sqrt(np.mean(active ** 2))) if len(active) else -120
            target, send = self.BUS[name]
            g = 10 ** ((target - rms) / 20) if len(active) else 0
            levels[name] = round(rms, 1)
            dry += x * g; wet += x * g * send
            if STEMS: wavfile.write(STEMS / f'{self.code}-{name}.wav', SR, (x * g).T.astype(np.float32))
        irL, irR = reverb_ir(t60, self.rng)
        out = dry + 0.55 * np.vstack([signal.fftconvolve(wet[0], irL)[:N], signal.fftconvolve(wet[1], irR)[:N]])
        out = butter(out, 'highpass', 30)
        out = glue(out)
        t = tt(N)
        out *= 10 ** (np.interp(t, *zip(*CURVE)) / 20)      # the feeling curve: scene 3, the proof, is the peak
        out *= np.clip(t / 0.01, 0, 1) * np.clip((DUR - t) / 0.55, 0, 1)
        return limit(out), levels


def glue(x, thr_db=-18, ratio=2.0, att=0.01, rel=0.18):
    a_r = np.exp(-1 / (rel * SR)); env = signal.lfilter([1 - a_r], [1, -a_r], np.max(np.abs(x), axis=0) ** 2)
    gdb = np.minimum(0, (thr_db - 10 * np.log10(env + 1e-12)) * (1 - 1 / ratio))
    a_a = np.exp(-1 / (att * SR))
    return x * 10 ** (signal.lfilter([1 - a_a], [1, -a_a], gdb) / 20)


def limit(x, ceiling=0.89, look=0.004):
    w = int(look * SR); held = ndimage.maximum_filter1d(np.max(np.abs(x), axis=0), 2 * w + 1)
    g = ndimage.uniform_filter1d(ndimage.minimum_filter1d(np.minimum(1, ceiling / (held + 1e-9)), 2 * w + 1), w)
    return x * g


def group_clap(S, rng, t, people=6, vel=1.0):
    for _ in range(people):
        S.add('clap', clap(rng), t + rng.uniform(-0.014, 0.014), gain=vel * rng.uniform(0.7, 1.0), pan=rng.uniform(-0.75, 0.75))


def play(S, rng, phrase, f, inst, vel=1.0, octave=0, pan=-0.15):
    for bar, slot, deg, ln, fl in phrase:
        t = bar + slot * SLOT; dur = ln * SLOT; d = deg + 7 * octave
        if inst == 'oud':
            if 't' in fl:                    # a grace note from the degree above leads into each tremolo
                S.add('oud', oud(f(d + 1), 0.05, rng, vel * 0.5), t - 0.055, pan=pan, jitter=0.003)
            S.add('oud', oud(f(d), dur, rng, vel * rng.uniform(0.88, 1.05), trem='t' in fl), t, pan=pan, jitter=0.004)
        else:
            S.add('ney', ney(f(d), dur, rng, vel), t, pan=-0.05, jitter=0.004)


def groove(S, rng, kind, bars, f, cfg, full=True):
    """full=False is the lighter scene-2 groove, so the proof scene (full) is the energy peak."""
    bf = lambda d: f(d + 7 * cfg['bass'])
    for b in bars:
        alt = b % 2
        if kind == 'khaleeji':
            S.add('drum', doum(rng, 1.0), b, jitter=0.003)
            S.add('drum', doum(rng, 0.75), b + SLOT * (4 if alt else 3), jitter=0.003)
            for s, v in (((1, .28), (2, .62), (5, .72)) if full else ((2, .5), (5, .6))):
                S.add('hand', tek(rng, v), b + s * SLOT, pan=.25, jitter=0.004)
            for s in (range(6) if full else (0, 3)):
                S.add('hand', riq(rng, .5 if s in (0, 3) else .22), b + s * SLOT, pan=.45, jitter=0.004)
            if full: S.add('drum', daf(rng, .35), b, jitter=0.003)
            if b >= cfg['claps_from']:
                for s in ((2, 5) if full else (5,)): group_clap(S, rng, b + s * SLOT)
            S.add('bass', bass(bf(0), .28, rng), b)
            S.add('bass', bass(bf(4 if alt else 0), .2, rng, .8), b + SLOT * (4 if alt else 3))
        elif kind == 'halftime':
            S.add('drum', doum(rng, .95, 55, 140), b, jitter=0.003)
            S.add('hand', tek(rng, .55), b + 3 * SLOT, pan=.2, jitter=0.004)
            S.add('hand', tek(rng, .25, dark=True), b + 5 * SLOT, pan=-.2, jitter=0.004)
            for s in (0, 3): S.add('hand', riq(rng, .3, .1), b + s * SLOT, pan=.4)
            if b >= cfg['claps_from'] and b < 19:
                for s in ((0, 3) if b >= 15 else (3,)): group_clap(S, rng, b + s * SLOT, people=7)
            if b in (14, 16, 18): S.add('drum', tabl(rng, .8), b)
            S.add('bass', bass(bf(0 if b < 10 or (b // 2) % 2 == 0 else 4), .9, rng, .9), b)
        elif kind == 'ardah':                # ceremonial: the big drum calls, the small drums answer
            S.add('drum', tabl(rng, .9 if b % 2 == 0 else .7), b, jitter=0.002)
            S.add('drum', daf(rng, .6), b + 3 * SLOT, jitter=0.003)
            for s, v in ((2, .45), (5, .5)): S.add('hand', tek(rng, v, dark=True), b + s * SLOT, pan=.3 if s == 2 else -.3, jitter=0.004)
            for s in (0, 2, 3, 5): S.add('hand', riq(rng, .3 if s in (0, 3) else .18), b + s * SLOT, pan=.45)
            if b >= cfg['claps_from'] and b < 19:
                for s in (2, 5): group_clap(S, rng, b + s * SLOT, people=8)
            S.add('bass', bass(bf(0 if (b // 2) % 2 == 0 else 4), .95, rng, .9), b)


def fill(S, rng, bar):
    for s, v in ((3, .5), (4, .7), (5, .9)): S.add('hand', tek(rng, v), bar + s * SLOT, pan=.2)
    S.add('drum', doum(rng, .8), bar + 5 * SLOT)


def compose(code):
    cfg, ev = CFG[code], EVENTS[code]
    rng = np.random.default_rng(cfg['seed']); S = Score(rng, code)
    f = lambda d: cfg['tonic'] * 2 ** ((MAQAM[cfg['maqam']][d % 7] + 1200 * (d // 7)) / 1200)
    lo = cfg['bass'] * 7

    # drone: tonic and fifth, opening up section by section
    S.add('pad', pad([f(lo), f(lo + 4)], 3.2, rng, cutoff=800, attack=1.6), 0)
    S.add('pad', pad([f(lo), f(lo + 4), f(0)], 7.3, rng, cutoff=1200), 3)
    S.add('pad', pad([f(lo), f(lo + 4), f(0), f(4)], 10.3, rng, cutoff=1500), 10)
    S.add('pad', pad([f(lo), f(lo + 4), f(0), f(4)], 4.4, rng, cutoff=1900, rel=0.9), 20)

    # 1 · hook (0–3 s): the problem cards land, shake, and a riser carries into the first cut
    S.add('drum', daf(rng, .7), 0.02)
    for t, d in zip((.3, .46, .62), (4, 2, 0)):
        S.add('oud', oud(f(d), .25, rng, .8), t, pan=-.2)
    for t in (1.2, 1.52, 1.84, 2.16):
        S.add('hand', riq(rng, .45, .05), t, pan=.35)
    for t in ev.get('broken', []):             # B: the broken links between separate systems
        S.add('qanun', qanun(f(7), .2, rng, .7) + qanun(f(7) * 2 ** (100 / 1200), .2, rng, .7), t, pan=.3)
        S.add('hand', tek(rng, .7, dark=True), t, pan=.1)
    if cfg['lead2'] == 'ney':
        play(S, rng, HOOK_NEY, f, 'ney', .8, octave=1)
    S.add('fx', sweep(1.25, rng, 300, 7000, lambda k: k ** 2.2), 1.78)

    # the three cuts: whoosh with the wipe, a low impact on the downbeat
    for cut in (3, 10, 20):
        S.add('fx', sweep(0.75, rng, 900, 6000, lambda k: np.sin(np.pi * k) ** 2) * 0.8, cut - 0.05, pan=.1)
        S.add('fx', impact(rng), cut)
    S.add('fx', sweep(1.0, rng, 300, 7000, lambda k: k ** 2.2), 9.0)
    S.add('fx', sweep(1.0, rng, 300, 7000, lambda k: k ** 2.2), 19.0)

    # 2 · solution (3–10 s): the groove enters and the lead answers the hook
    groove(S, rng, cfg['groove'], range(3, 10), f, cfg, full=False); fill(S, rng, 9)
    play(S, rng, P2, f, cfg['lead2'], 0.85, octave=1 if cfg['lead2'] == 'ney' else 0)
    if ev.get('draw'):                         # the dashboard chart draws itself
        for k, t in enumerate((4.6, 4.95, 5.3)):
            S.add('qanun', qanun(f(7 + 2 * k), .25, rng, .45), t, pan=.35)
    if 'core' in ev:                           # C: "Your existing PMS" pops, its modules join
        S.add('qanun', qanun(f(7), .4, rng, .7), ev['core'], pan=.3)
        for t, d in zip(ev['nodes'], ev['node_deg']): S.add('qanun', qanun(f(d), .25, rng, .65), t, pan=.35)
    for t in ev.get('tiles', []):              # D: the property tiles fill in
        S.add('hand', riq(rng, .35, .04), t, pan=.4)

    # 3 · proof (10–20 s): every card that lands plays the next note
    groove(S, rng, cfg['groove'], range(10, 19), f, cfg); fill(S, rng, 19)
    play(S, rng, OSTINATO, f, 'oud', .55, pan=-.3)
    play(S, rng, P3, f, 'oud', 1.0)
    for bar, slot, deg, ln, fl in P3:          # the qanun doubles the climax an octave up: the peak of the reel
        S.add('qanun', qanun(f(deg + 7), ln * SLOT, rng, .32), bar + slot * SLOT + 0.012, pan=.35)
    for t, d in zip(ev['cards'], ev['card_deg']): S.add('qanun', qanun(f(d), .3, rng, .8), t, pan=.3)
    for t in ev.get('ticks', []): S.add('hand', tek(rng, .35), t, pan=.5)
    for t in ev.get('links', []): S.add('hand', riq(rng, .35, .04), t, pan=.45)
    for t in ev.get('align', []):              # D: three properties turn the group colour on one shared note
        S.add('qanun', qanun(f(ev['align_deg']), .3, rng, .75), t, pan=.3)
    if 'chip' in ev:
        for k, d in enumerate((7, 11, 14)): S.add('qanun', qanun(f(d), .6, rng, .6), ev['chip'] + k * .03, pan=.3)

    # 4 · call to action (20–25 s): lighter groove, the button gets its flourish, and the cadence lands on the tonic
    if cfg['groove'] == 'khaleeji':
        groove(S, rng, 'khaleeji', range(20, 22), f, cfg)
        for b in (22, 23):
            S.add('drum', doum(rng, .8), b); group_clap(S, rng, b + 5 * SLOT, people=5, vel=.8)
            S.add('bass', bass(f(lo + (4 if b == 22 else 1)), .3, rng), b)
    else:
        for b in (20, 21, 22, 23):
            S.add('drum', doum(rng, .7, 55, 140) if cfg['groove'] == 'halftime' else daf(rng, .7), b)
            S.add('bass', bass(f(lo + (4 if b == 22 else 0)), .9, rng, .8), b)
    for t in ev.get('checks', []):             # C: what the review covers, ticked in order
        S.add('qanun', qanun(f(ev['check_deg'][ev['checks'].index(t)]), .2, rng, .7), t, pan=.3)
    if ev['cta'] == 'gliss':                   # the CTA button pops with a qanun run up the maqam
        for k in range(8): S.add('qanun', qanun(f(7 + k), .12 if k < 7 else .8, rng, .55 + .04 * k), 21.4 + k * .03, pan=.3)
    else:
        for k, d in enumerate((7, 11, 14)): S.add('qanun', qanun(f(d), .8, rng, .7), 21.4 + k * .02, pan=.3)
    for t in (22.4, 23.3): S.add('hand', tek(rng, .3, dark=True), t, pan=-.2)
    play(S, rng, P4, f, 'oud', 1.0)
    for k, d in enumerate((lo, lo + 4, 0, 4)):  # final chord: oud strum and qanun with the last drum hit
        S.add('oud', oud(f(d) if d >= 0 else f(d), .7, rng, .8), 24.0 + k * .014, pan=-.15)
    for k, d in enumerate((7, 11, 14)): S.add('qanun', qanun(f(d), .7, rng, .55), 24.02 + k * .02, pan=.3)
    S.add('drum', tabl(rng, .9) if cfg['groove'] != 'khaleeji' else doum(rng, 1.0), 24.0)
    S.add('drum', daf(rng, .6), 24.0)
    if cfg['groove'] == 'khaleeji': group_clap(S, rng, 24.0, people=7)
    S.add('bass', bass(f(lo), .7, rng), 24.0)
    return S.mix(t60=2.2 if code in 'CD' else 1.5)


# ---------------------------------------------------------------- output
def ffmpeg(*args):
    r = subprocess.run(['ffmpeg', '-hide_banner', '-y', *args], capture_output=True, text=True, encoding='utf-8', errors='replace')
    if r.returncode: raise RuntimeError(r.stderr[-1500:])
    return r.stderr


def encode(x, out):
    with tempfile.TemporaryDirectory() as d:
        wav = pathlib.Path(d) / 'score.wav'
        wavfile.write(wav, SR, x.T.astype(np.float32))
        spec = 'I=-16:TP=-2:LRA=9'                          # -2 dBTP leaves headroom for the AAC encoder
        m = json.loads(re.search(r'\{[^{}]*"input_i"[^{}]*\}', ffmpeg('-i', str(wav), '-af', f'loudnorm={spec}:print_format=json', '-f', 'null', '-')).group(0))
        ln = f"loudnorm={spec}:measured_I={m['input_i']}:measured_TP={m['input_tp']}:measured_LRA={m['input_lra']}:measured_thresh={m['input_thresh']}:offset={m['target_offset']}:linear=true"
        ffmpeg('-i', str(wav), '-af', ln, '-ar', str(SR), '-c:a', 'aac', '-b:a', '192k', '-movflags', '+faststart', str(out))


def mux(code, score):
    reels = ROOT / 'book' / 'media' / FOLDERS[code] / 'reels'
    for lang in ('ar', 'en'):
        mp4 = reels / f'{code}-reel-{lang}.mp4'
        if not mp4.exists(): continue
        tmp = mp4.with_suffix('.tmp.mp4')
        ffmpeg('-i', str(mp4), '-i', str(score), '-map', '0:v:0', '-map', '1:a:0', '-c', 'copy', '-movflags', '+faststart', str(tmp))   # no -shortest: with stream copy it drops the last video frame
        tmp.replace(mp4)
        print(f'  muxed {mp4.relative_to(ROOT).as_posix()}')


if __name__ == '__main__':
    codes = [a for a in sys.argv[1:] if a in FOLDERS] or list(FOLDERS)
    for code in codes:
        x, levels = compose(code)
        out = ROOT / 'book' / 'media' / FOLDERS[code] / 'reels' / f'{code}-score.m4a'
        encode(x, out)
        print(f'{code}: {out.relative_to(ROOT).as_posix()}  bus RMS before levelling (dBFS): {levels}')
        if '--no-mux' not in sys.argv: mux(code, out)
