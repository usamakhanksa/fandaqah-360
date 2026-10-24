// Renders every campaign creative and the site's link-preview images from studio.html.
// 1) serve the repo root:   python -m http.server 8767 --bind 127.0.0.1      (run in the repo root)
// 2a) with Playwright:      node campaigns/_studio/render.mjs                 (npm i -D playwright, once)
// 2b) or pass this file to any runner that calls `export default async (page) => …` with a Playwright page.
// Optional filters: STUDIO_ONLY=A,C  STUDIO_LANGS=ar  STUDIO_SKIP_OG=1
import { mkdirSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const ORIGIN = process.env.STUDIO_ORIGIN || 'http://127.0.0.1:8767';
const here = path.dirname(fileURLToPath(import.meta.url));
const repo = path.resolve(here, '..', '..');
const FOLDERS = { A: 'A-existing-customers', B: 'B-new-hotels', C: 'C-opera-pms', D: 'D-hotel-groups' };
const SIZE = { feed: [1080, 1350], story: [1080, 1920], og: [1200, 630] };
const only = (process.env.STUDIO_ONLY || 'A,B,C,D').split(',');
const langs = (process.env.STUDIO_LANGS || 'ar,en').split(',');

function jobs() {
  const list = [];
  for (const c of only) for (const lang of langs) {
    const dir = path.join(repo, 'campaigns', FOLDERS[c], 'creatives');
    for (const i of [1, 2]) list.push({ q: { c, lang, kind: 'ad', i, fmt: 'feed' }, out: path.join(dir, 'feed', `${c}-ad-v${i}-${lang}.jpg`) });
    list.push({ q: { c, lang, kind: 'ad', i: 1, fmt: 'story' }, out: path.join(dir, 'story', `${c}-story-v1-${lang}.jpg`) });
    for (const i of [1, 2, 3, 4]) list.push({ q: { c, lang, kind: 'slide', i, fmt: 'feed' }, out: path.join(dir, 'carousel', `${c}-carousel-${i}-${lang}.jpg`) });
    for (const i of [1, 2, 3, 4]) list.push({ q: { c, lang, kind: 'slide', i, fmt: 'story' }, out: path.join(dir, 'reel-frames', `${c}-reel-${i}-${lang}.jpg`) });
  }
  if (!process.env.STUDIO_SKIP_OG) for (const key of ['home', 'existing-customers', 'new-hotels', 'opera-pms', 'hotel-groups'])
    list.push({ q: { kind: 'og', i: key, fmt: 'og' }, out: path.join(repo, 'site', 'assets', 'img', 'og', `${key}.jpg`) });
  return list;
}

export default async function run(page) {
  const done = [];
  for (const { q, out } of jobs()) {
    const [w, h] = SIZE[q.fmt];
    await page.setViewportSize({ width: w, height: h });
    await page.goto(`${ORIGIN}/campaigns/_studio/studio.html?${new URLSearchParams(q)}`);
    await page.waitForSelector('body[data-ready="1"]', { timeout: 15000 });
    await page.waitForTimeout(150);
    mkdirSync(path.dirname(out), { recursive: true });
    await page.locator('#art').screenshot({ path: out, type: 'jpeg', quality: 86 });
    done.push(path.relative(repo, out).replace(/\\/g, '/'));
  }
  return { rendered: done.length, files: done };
}

// standalone: node campaigns/_studio/render.mjs
if (process.argv[1] && path.resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
  const { chromium } = await import('playwright');
  const browser = await chromium.launch();
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  console.log(JSON.stringify(await run(page), null, 1));
  await browser.close();
}
