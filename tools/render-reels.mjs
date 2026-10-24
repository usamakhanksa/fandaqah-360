// Renders the eight campaign reels (A–D × Arabic/English) from book/reel.html into MP4 files.
// Each frame is an exact seek of the GSAP timeline (no screen recording), so motion is smooth and repeatable.
//   1) serve the repo root:  python -m http.server 8766 --bind 127.0.0.1
//   2a) with Playwright:     node tools/render-reels.mjs            (npm i -D playwright, once; needs ffmpeg on PATH)
//   2b) or hand this file to any runner that calls `export default async (page) => …` with a Playwright page.
// Options: REELS_ONLY=A,C  REELS_LANGS=ar  REELS_FPS=30  FRAMES_DIR=<temp folder>
import { mkdirSync, rmSync } from 'node:fs';
import { spawnSync } from 'node:child_process';
import { fileURLToPath } from 'node:url';
import os from 'node:os';
import path from 'node:path';

const ORIGIN = process.env.REELS_ORIGIN || 'http://127.0.0.1:8766';
const repo = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const FOLDERS = { A: 'A-existing-customers', B: 'B-new-hotels', C: 'C-opera-pms', D: 'D-hotel-groups' };
const FPS = +(process.env.REELS_FPS || 30), DURATION = 25, POSTER_AT = 2.6;
const only = (process.env.REELS_ONLY || 'A,B,C,D').split(',');
const langs = (process.env.REELS_LANGS || 'ar,en').split(',');
const framesRoot = process.env.FRAMES_DIR || path.join(os.tmpdir(), 'fq360-reel-frames');

async function seek(page, t) {
  await page.evaluate(t => { document.body.dataset.seek = String(t); document.dispatchEvent(new Event('reel:seek')); }, t);
  await page.waitForFunction(t => document.body.dataset.frame === String(t), t);
}

export default async function run(page) {
  const done = [];
  await page.setViewportSize({ width: 1080, height: 1920 });
  for (const c of only) for (const lang of langs) {
    const out = path.join(repo, 'book', 'media', FOLDERS[c], 'reels');
    const frames = path.join(framesRoot, `${c}-${lang}`);
    rmSync(frames, { recursive: true, force: true }); mkdirSync(frames, { recursive: true }); mkdirSync(out, { recursive: true });
    await page.goto(`${ORIGIN}/book/reel.html?c=${c}&lang=${lang}&render=1`);
    await page.waitForSelector('body[data-ready="1"]', { timeout: 20000 });
    const total = Math.round(DURATION * FPS);
    for (let f = 0; f <= total; f++) {
      const t = +(f / FPS).toFixed(4);
      await seek(page, t);
      await page.screenshot({ path: path.join(frames, `${String(f).padStart(5, '0')}.jpg`), type: 'jpeg', quality: 92 });
    }
    // poster: the hook with its headline on screen
    await seek(page, POSTER_AT);
    await page.screenshot({ path: path.join(out, `${c}-reel-${lang}.jpg`), type: 'jpeg', quality: 82 });
    const mp4 = path.join(out, `${c}-reel-${lang}.mp4`);
    const ff = spawnSync('ffmpeg', ['-y', '-loglevel', 'error', '-framerate', String(FPS), '-i', path.join(frames, '%05d.jpg'),
      '-c:v', 'libx264', '-preset', 'slow', '-crf', '21', '-pix_fmt', 'yuv420p', '-profile:v', 'high', '-movflags', '+faststart',
      '-metadata', `title=Fandaqah 360 · Campaign ${c} (${lang})`, mp4], { encoding: 'utf8' });
    if (ff.status !== 0) throw new Error(`ffmpeg failed for ${c}-${lang}: ${ff.stderr}`);
    rmSync(frames, { recursive: true, force: true });
    done.push(path.relative(repo, mp4).replace(/\\/g, '/'));
  }
  return { rendered: done };
}

if (process.argv[1] && path.resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
  const { chromium } = await import('playwright');
  const browser = await chromium.launch();
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  console.log(JSON.stringify(await run(page), null, 1));
  await browser.close();
}
