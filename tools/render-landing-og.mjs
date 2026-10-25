// Render the landing site's share images (1200x630 JPEG) from tools/og/studio.html.
// Needs the local server from the README (repo root on port 8766) and the browser-automation runner:
//   python -I tools/build-landing.py        (writes tools/og/og-data.json)
//   node <browser-automation>/browser.mjs http://127.0.0.1:8766/tools/og/studio.html --script tools/render-landing-og.mjs
import { readFileSync, mkdirSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const HERE = path.dirname(fileURLToPath(import.meta.url));
const ORIGIN = process.env.ORIGIN || 'http://127.0.0.1:8766';
const OUT = path.join(HERE, '..', 'landing', 'assets', 'og');

export default async function run(page) {
  const items = JSON.parse(readFileSync(path.join(HERE, 'og', 'og-data.json'), 'utf8'));
  mkdirSync(OUT, { recursive: true });
  await page.setViewportSize({ width: 1200, height: 630 });
  const done = [];
  for (let i = 0; i < items.length; i++) {
    await page.goto(`${ORIGIN}/tools/og/studio.html?i=${i}`, { waitUntil: 'networkidle' });
    await page.waitForFunction(key => document.body.dataset.ready === key, items[i].key, { timeout: 15000 });
    await page.screenshot({ path: path.join(OUT, `${items[i].key}.jpg`), type: 'jpeg', quality: 84 });
    done.push(items[i].key);
  }
  return { rendered: done.length, out: OUT };
}
