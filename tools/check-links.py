"""Static audit of the deployable site: every local href/src resolves and every icon id exists in the sprite.

Usage:  python -I tools/check-links.py .        (or _site, the built output)
"""
import re, sys, pathlib
from urllib.parse import urlsplit, unquote

sys.stdout.reconfigure(encoding='utf-8')
SKIP = {'campaigns', 'archive', 'tools', 'banners', '_internal', '_site', '.git', '.github', 'node_modules'}  # never deployed
site = pathlib.Path(sys.argv[1] if len(sys.argv) > 1 else '.').resolve()
sprite = (site / 'assets/icons.svg').read_text(encoding='utf-8')
symbols = set(re.findall(r'<symbol id="([^"]+)"', sprite))
problems = []

for page in sorted(p for p in site.rglob('*.html') if not SKIP & set(p.relative_to(site).parts)):
    html = page.read_text(encoding='utf-8')
    for attr, url in re.findall(r'\b(href|src)="([^"]+)"', html):
        if url.startswith(('http:', 'https:', 'mailto:', 'tel:', 'data:', '#', 'javascript:')) or '${' in url:
            continue  # external, or a template literal filled in by the page's own script
        parts = urlsplit(url)
        target = (page.parent / unquote(parts.path)).resolve()
        if parts.path.endswith('/') or target.is_dir():
            target = target / 'index.html'
        if not target.exists():
            problems.append(f'{page.relative_to(site)}: missing {attr}="{url}"')
        elif target.name == 'icons.svg' and parts.fragment and parts.fragment not in symbols:
            problems.append(f'{page.relative_to(site)}: unknown icon #{parts.fragment}')
    # icons referenced from inline scripts or CSS-free markup
    for sym in set(re.findall(r'icons\.svg(?:\?v=[0-9a-f]+)?#([\w-]+)', html)) - symbols:
        problems.append(f'{page.relative_to(site)}: unknown icon #{sym}')

css = (site / 'assets/css/site.css').read_text(encoding='utf-8')
for url in re.findall(r'url\((?!["\']?data:)["\']?([^)"\']+)', css):
    if not (site / 'assets/css' / url).resolve().exists():
        problems.append(f'assets/css/site.css: missing url({url})')

print('\n'.join(problems) if problems else f'OK: all local links, assets and {len(symbols)} icon ids resolve')
sys.exit(1 if problems else 0)
