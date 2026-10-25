"""Cut the supplied Arabic music track to each 25-second campaign reel, edited to the picture.

The reels cut at 3, 10 and 20 s (book/assets/reel.js); the cut at 10 s opens scene 3, the proof, which is the peak of
every reel. Each edit starts so that one of the track's two chorus drops lands exactly on that cut:

  A, B  chorus 1 (track 51.33 s): the energy steps up just after the 3 s cut, the stutter break plays under the last
        1.5 s of the solution, and the chorus hits as the proof wipes in
  C, D  chorus 2 (track 140.91 s): the stutter, half a second of near-silence, then the chorus on the cut: the more
        dramatic version, for the corporate and executive reels

Each edit gets a 0.25 s fade-in, a 1.8 s fade-out under the call to action, and the same delivery as before: AAC
192 kb/s, 48 kHz stereo, -16 LUFS integrated, -2 dBTP. Both languages of a campaign share its edit.

Usage:  python -I tools/reel-music.py            write all four edits and mux each into its two reels
        python -I tools/reel-music.py A C        only these campaigns
        python -I tools/reel-music.py --no-mux   write the edits only (tools/render-reels.mjs muxes them when it renders)
Source: _internal/audio/arabic-music.mp3 (supplied; git-ignored, so the full track is never committed or published,
        only the 25-second edits inside the reels)
Output: book/media/<X>/reels/<X>-score.m4a
tools/reel-audio.py, the synthesised score, writes the same files and stays available as a fallback.
"""
import json, pathlib, re, subprocess, sys

sys.stdout.reconfigure(encoding='utf-8')
ROOT = pathlib.Path(__file__).resolve().parent.parent
SRC = ROOT / '_internal' / 'audio' / 'arabic-music.mp3'   # git-ignored: the full track is never committed or published
FOLDERS = {'A': 'A-existing-customers', 'B': 'B-new-hotels', 'C': 'C-opera-pms', 'D': 'D-hotel-groups'}
DUR = 751 / 30                 # every reel is 751 frames at 30 fps
PROOF_CUT = 10.0               # where scene 3 opens
DROP = {'A': 51.33, 'B': 51.33, 'C': 140.91, 'D': 140.91}   # chorus downbeats in the track, measured from its onsets
FADE_IN, FADE_OUT = 0.25, 1.8
SPEC = 'I=-16:TP=-2:LRA=9'


def ffmpeg(*args):
    r = subprocess.run(['ffmpeg', '-hide_banner', '-y', *args], capture_output=True, text=True, encoding='utf-8', errors='replace')
    if r.returncode: raise RuntimeError(r.stderr[-1500:])
    return r.stderr


def edit(code, out):
    start = DROP[code] - PROOF_CUT
    shape = f'afade=t=in:st=0:d={FADE_IN},afade=t=out:st={DUR - FADE_OUT:.3f}:d={FADE_OUT}:curve=qsin,highpass=f=25,aresample=48000'
    cut = ['-ss', f'{start:.3f}', '-t', f'{DUR:.4f}', '-i', str(SRC)]
    m = json.loads(re.search(r'\{[^{}]*"input_i"[^{}]*\}', ffmpeg(*cut, '-af', f'{shape},loudnorm={SPEC}:print_format=json', '-f', 'null', '-')).group(0))
    ln = (f"loudnorm={SPEC}:measured_I={m['input_i']}:measured_TP={m['input_tp']}:measured_LRA={m['input_lra']}"
          f":measured_thresh={m['input_thresh']}:offset={m['target_offset']}:linear=true")
    ffmpeg(*cut, '-af', f'{shape},{ln}', '-ar', '48000', '-ac', '2', '-c:a', 'aac', '-b:a', '192k', '-movflags', '+faststart', str(out))
    return start


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
    if not SRC.exists(): sys.exit(f'missing source track: {SRC.relative_to(ROOT).as_posix()}')
    for code in [a for a in sys.argv[1:] if a in FOLDERS] or list(FOLDERS):
        out = ROOT / 'book' / 'media' / FOLDERS[code] / 'reels' / f'{code}-score.m4a'
        start = edit(code, out)
        print(f'{code}: track {start:.2f}-{start + DUR:.2f} s, chorus on the {PROOF_CUT:.0f} s cut -> {out.relative_to(ROOT).as_posix()}')
        if '--no-mux' not in sys.argv: mux(code, out)
