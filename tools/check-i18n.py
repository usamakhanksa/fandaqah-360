"""Check that every translatable string on every page has an Arabic translation.

Usage:  python -I tools/check-i18n.py .        (or _site, the built output)
Looks at data-i18n / data-i18n-aria / data-i18n-alt / data-i18n-ph keys in each page and checks them against the
shared dictionary in assets/js/site.js plus the page's own window.FQ_PAGE.ar block. Exits 1 when something is missing.
"""
import re, sys, pathlib

sys.stdout.reconfigure(encoding='utf-8')
SKIP = {'campaigns', 'archive', 'tools', 'banners', '_internal', '_site', '.git', '.github', 'node_modules', 'f360-linkedin-dev-pack', 'tiktok', 'snapcha-retargeting', 'meta'}  # never deployed
site = pathlib.Path(sys.argv[1] if len(sys.argv) > 1 else '.')
KEY = re.compile(r"""['"]([\w.]+)['"]\s*:""")  # dictionary keys in single or double quotes

js = (site / 'assets/js/site.js').read_text(encoding='utf-8')
shared = set(KEY.findall(js[js.index('const SHARED_AR'):js.index('const JS_TXT')]))

missing_total = 0
for page in sorted(p for p in site.rglob('*.html') if not SKIP & set(p.relative_to(site).parts)):
    html = page.read_text(encoding='utf-8')
    used = set(re.findall(r'data-i18n(?:-aria|-alt|-ph)?="([\w.]+)"', html))
    if not used:
        continue
    standalone = re.search(r'const AR = \{(.*?)\n  \};', html, re.S)
    if standalone:  # self-contained page (the v2 landing) carries its whole dictionary inline
        own, base = set(KEY.findall(standalone.group(1))), set()
    else:
        m = re.search(r'ar:\s*\{(.*?)\n\s*\}', html, re.S)  # the dictionary closes on a line of its own, at any indent
        own, base = (set(KEY.findall(m.group(1))) if m else set()), shared
    missing = sorted(used - base - own)
    missing_total += len(missing)
    status = 'OK ' if not missing else 'MISSING'
    print(f'{status} {page.relative_to(site)}  ({len(used)} keys)' + (f'  -> {", ".join(missing)}' if missing else ''))
sys.exit(1 if missing_total else 0)
