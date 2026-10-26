// Renders the Campaign A Snapchat ad (book/snap.html) to a 9.5 s, 1080×1920, 30 fps MP4 with its sound.
// Every frame is an exact seek of the GSAP timeline, as in tools/render-reels.mjs.
//   1) serve the repo root:  python -m http.server 8766 --bind 127.0.0.1
//   2a) with Playwright:     node tools/render-snap.mjs            (needs ffmpeg on PATH)
//   2b) or hand this file to any runner that calls `export default async (page) => …` with a Playwright page.
// Sound: the supplied Arabic track (_internal/audio/arabic-music.mp3, git-ignored), cut so its first chorus lands on
// the 20-module reveal at 4.0 s, under a light sound design written here (paper and card pops, the upgrade click,
// the counter ticks, a success chime at 20, a rise into the offer and the logo sting). Mixed to -16 LUFS, -2 dBTP.
// Output: book/media/A-existing-customers/reels/A-snap-ar.mp4, A-snap-ar.jpg (poster) and A-snap-score.m4a
import { mkdirSync, rmSync, writeFileSync, existsSync } from 'node:fs';
import { spawnSync } from 'node:child_process';
import { fileURLToPath } from 'node:url';
import os from 'node:os';
import path from 'node:path';

const ORIGIN = process.env.REELS_ORIGIN || 'http://127.0.0.1:8766';
const repo = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const FPS = 30, DUR = 9.5, FRAMES = Math.round(DUR * FPS), POSTER_AT = 6.2;
const OUT = path.join(repo, 'book', 'media', 'A-existing-customers', 'reels');
const MUSIC = path.join(repo, '_internal', 'audio', 'arabic-music.mp3');
const DROP = 51.33, PEAK = 4.0;   // chorus 1 downbeat in the track (as in tools/reel-music.py), and where the peak opens
const tmp = path.join(os.tmpdir(), 'fq360-snap');

// ---------- sound design: short synthesised cues, 48 kHz mono 16-bit ----------
function sfx(file) {
  const SR = 48000, n = Math.ceil(DUR * SR), buf = new Float32Array(n);
  let seed = 7; const rnd = () => ((seed = (seed * 16807) % 2147483647) / 2147483647) * 2 - 1;
  const add = (t0, len, fn, gain) => { const a = Math.floor(t0 * SR); for (let i = 0; i < len * SR && a + i < n; i++) buf[a + i] += gain * fn(i / SR, i / (len * SR)); };
  const tone = (f, decay) => (t, p) => Math.sin(2 * Math.PI * f * t) * Math.exp(-t * decay);
  const pop = (t0, g = .18) => add(t0, .05, (t, p) => (rnd() * .5 + Math.sin(2 * Math.PI * 1800 * t)) * Math.exp(-t * 90), g);
  const click = (t0, g = .45) => { add(t0, .03, (t) => rnd() * Math.exp(-t * 260), g); add(t0, .12, tone(110, 30), g * .9); };
  const whoosh = (t0, len, g = .16) => { let lp = 0; add(t0, len, (t, p) => { lp += (rnd() - lp) * (.05 + .25 * p); return lp * Math.sin(Math.PI * p); }, g * 3); };
  const chime = (t0, fs, g = .16) => fs.forEach((f, i) => add(t0 + i * .06, 1.1, tone(f, 4.5), g));
  // 1 · the busy desk: papers land, the phone rings twice
  [0.06, 0.16, 0.26, 0.36, 0.46].forEach(t => pop(t, .12));
  chime(0.62, [1320, 1760], .07); chime(0.98, [1320, 1760], .07);
  whoosh(1.22, .22);                                       // the snap into scene 2
  [1.62, 1.75, 1.88, 2.01, 2.14].forEach(t => pop(t, .14)); // the separate modules
  click(3.19);                                             // the upgrade tap
  whoosh(3.78, .2);
  [4.15, 4.23, 4.31, 4.39, 4.47].forEach(t => pop(t, .16));
  for (let i = 0; i < 15; i++) pop(4.75 + i * .045, .07);   // the fifteen join, lightly
  [5.07, 5.43, 5.8].forEach(t => add(t, .06, tone(2400, 70), .14));   // the counter: 10, 15, 20
  chime(5.86, [784, 988, 1175], .12);                      // twenty: done
  [6.55, 6.65, 6.75, 6.86, 6.96, 7.06].forEach(t => pop(t, .08)); // the workflows move
  add(8.2, .5, (t, p) => Math.sin(2 * Math.PI * (500 + 700 * p) * t) * p * p, .05);   // the rise into the call to action
  chime(9.18, [880, 1108.7, 1318.5], .12);                 // logo sting
  const pcm = Buffer.alloc(44 + n * 2);
  pcm.write('RIFF', 0); pcm.writeUInt32LE(36 + n * 2, 4); pcm.write('WAVEfmt ', 8); pcm.writeUInt32LE(16, 16); pcm.writeUInt16LE(1, 20);
  pcm.writeUInt16LE(1, 22); pcm.writeUInt32LE(SR, 24); pcm.writeUInt32LE(SR * 2, 28); pcm.writeUInt16LE(2, 32); pcm.writeUInt16LE(16, 34);
  pcm.write('data', 36); pcm.writeUInt32LE(n * 2, 40);
  for (let i = 0; i < n; i++) pcm.writeInt16LE(Math.max(-1, Math.min(1, buf[i])) * 32767 | 0, 44 + i * 2);
  writeFileSync(file, pcm);
}

function ff(args, what) {
  const r = spawnSync('ffmpeg', ['-y', '-loglevel', 'error', ...args], { encoding: 'utf8' });
  if (r.status !== 0) throw new Error(`ffmpeg failed (${what}): ${r.stderr}`);
}

async function seek(page, t) {
  await page.evaluate(t => { document.body.dataset.seek = String(t); document.dispatchEvent(new Event('reel:seek')); }, t);
  await page.waitForFunction(t => document.body.dataset.frame === String(t), t);
}

export default async function run(page) {
  rmSync(tmp, { recursive: true, force: true }); mkdirSync(path.join(tmp, 'f'), { recursive: true }); mkdirSync(OUT, { recursive: true });
  await page.setViewportSize({ width: 1080, height: 1920 });
  await page.goto(`${ORIGIN}/book/snap.html?render=1`);
  await page.waitForSelector('body[data-ready="1"]', { timeout: 20000 });
  for (let f = 0; f < FRAMES; f++) {
    const t = +(f / FPS).toFixed(4);
    await seek(page, t);
    await page.screenshot({ path: path.join(tmp, 'f', `${String(f).padStart(5, '0')}.jpg`), type: 'jpeg', quality: 94 });
  }
  await seek(page, POSTER_AT);
  await page.screenshot({ path: path.join(OUT, 'A-snap-ar.jpg'), type: 'jpeg', quality: 84 });

  // sound: the music cut (if the source is present) under the cues, mixed and normalised
  const cues = path.join(tmp, 'cues.wav'), score = path.join(OUT, 'A-snap-score.m4a');
  sfx(cues);
  const start = (DROP - PEAK).toFixed(3);
  if (existsSync(MUSIC)) {
    ff(['-ss', start, '-t', String(DUR), '-i', MUSIC, '-i', cues, '-filter_complex',
      `[0:a]aformat=sample_rates=48000:channel_layouts=stereo,afade=t=in:d=0.2,afade=t=out:st=${DUR - 0.75}:d=0.75,volume=0.8[m];` +
      `[1:a]aformat=sample_rates=48000:channel_layouts=stereo[c];[m][c]amix=inputs=2:normalize=0,loudnorm=I=-16:TP=-2:LRA=11,atrim=0:${DUR}[a]`,
      '-map', '[a]', '-c:a', 'aac', '-b:a', '192k', '-ar', '48000', score], 'score');
  } else {
    ff(['-i', cues, '-af', `aformat=sample_rates=48000:channel_layouts=stereo,loudnorm=I=-16:TP=-2`, '-c:a', 'aac', '-b:a', '192k', score], 'cues only');
  }
  const mp4 = path.join(OUT, 'A-snap-ar.mp4');
  // no -shortest: with these inputs it trims the last frame; picture and sound are both exactly 9.5 s
  ff(['-framerate', String(FPS), '-i', path.join(tmp, 'f', '%05d.jpg'), '-i', score, '-map', '0:v', '-map', '1:a',
    '-c:v', 'libx264', '-preset', 'slow', '-crf', '18', '-pix_fmt', 'yuv420p', '-profile:v', 'high', '-r', String(FPS),
    '-c:a', 'copy', '-movflags', '+faststart', '-metadata', 'title=Fandaqah 360 · Snapchat · existing customers (ar)', mp4], 'mp4');
  rmSync(tmp, { recursive: true, force: true });
  return { rendered: [mp4, score].map(f => path.relative(repo, f).replace(/\\/g, '/')), music: existsSync(MUSIC) };
}

if (process.argv[1] && path.resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
  const { chromium } = await import('playwright');
  const browser = await chromium.launch();
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  console.log(JSON.stringify(await run(page), null, 1));
  await browser.close();
}
