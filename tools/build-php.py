"""Write a PHP copy beside every public page, for a PHP host. The .html page stays the one you edit (and the one GitHub Pages serves).

Each copy is the page, plus:
  - a first line that sends the host's headers (api/page.php) and says the file is generated;
  - <meta name="fq-lead-proxy">, so the page's forms post to api/lead.php, which adds the CRM key on the server;
  - its links to other pages pointed at their PHP copies;
  - on the campaign forms (#lead-form), action/method, so they still send without JavaScript.

    python tools/build-php.py           write the copies, and remove copies whose page is gone
    python tools/build-php.py --check   change nothing; fail if any copy is missing, stale or orphaned (CI runs this)
"""
import fnmatch, pathlib, posixpath, re, sys

R = pathlib.Path(__file__).resolve().parent.parent
SKIP_DIRS = {'.git', '.github', '_internal', '_site', 'archive', 'campaigns', 'tools', 'banners', 'api', 'node_modules', 'scrollcraft'}
SKIP_PAGES = ('book/reel.html', 'book/snap*.html')   # render compositions for the reels and Snapchat ads, not pages anyone visits
MARK = 'Generated from '

def pages():
    out = []
    for p in sorted(R.rglob('*.html')):
        rel = p.relative_to(R).as_posix()
        if set(rel.split('/')[:-1]) & SKIP_DIRS or any(fnmatch.fnmatch(rel, g) for g in SKIP_PAGES):
            continue
        out.append(rel)
    return out

LINK = re.compile(r'(\s(?:href|data-ar-href|data-href-ar|data-lang-alt|action)=")([^"#?]+?\.html)((?:[?#][^"]*)?")')

def convert(rel, src, known):
    up = '../' * rel.count('/')
    here = posixpath.dirname(rel)
    def relink(m):
        target = m.group(2)
        if re.match(r'^[a-z][a-z0-9+.-]*:|^//', target, re.I):
            return m.group(0)                      # another site
        resolved = posixpath.normpath(posixpath.join(here, target)) if not target.startswith('/') else None
        if resolved not in known:
            return m.group(0)                      # a page without a PHP copy keeps its .html link
        return m.group(1) + target[:-5] + '.php' + m.group(3)
    s = LINK.sub(relink, src)
    meta = f'<meta name="fq-lead-proxy" content="{up}api/lead.php">'
    s, n = re.subn(r'(<meta charset="utf-8">)', r'\1' + meta, s, count=1, flags=re.I)
    if not n:
        raise SystemExit(f'{rel}: no <meta charset="utf-8"> to anchor the proxy tag')
    s = s.replace('<form class="form" id="lead-form" novalidate', f'<form class="form" id="lead-form" action="{up}api/lead.php" method="post" novalidate', 1)
    s = re.sub(r'<form class="form ([\w -]+)" id="lead-form" novalidate', rf'<form class="form \1" id="lead-form" action="{up}api/lead.php" method="post" novalidate', s, count=1)
    # the first line ends the way the page's lines do, so a copy checked out with CRLF still matches
    nl = '\r\n' if '\r\n' in src else '\n'
    # the page still opens if api/ was not uploaded (its forms then post straight to the CRM)
    head = (f"<?php /* {MARK}{posixpath.basename(rel)} by tools/build-php.py: edit the .html page, then run the build. */ "
            f"if (is_file(__DIR__ . '/{up}api/page.php')) require __DIR__ . '/{up}api/page.php'; ?>{nl}")
    return head + s

def main():
    check = '--check' in sys.argv
    srcs = pages()
    known = set(srcs)
    want = {}
    for rel in srcs:
        raw = (R / rel).read_bytes().decode('utf-8')
        want[rel[:-5] + '.php'] = convert(rel, raw, known).encode('utf-8')
    # copies this script wrote earlier whose page has since gone
    orphans = [p.relative_to(R).as_posix() for p in R.rglob('*.php')
               if not (set(p.relative_to(R).as_posix().split('/')[:-1]) & SKIP_DIRS)
               and p.relative_to(R).as_posix() not in want
               and MARK in p.read_text(encoding='utf-8', errors='ignore')[:300]]
    stale = [rel for rel, data in want.items() if not (R / rel).exists() or (R / rel).read_bytes() != data]
    if check:
        if stale or orphans:
            for rel in stale: print('out of date:', rel)
            for rel in orphans: print('orphaned:', rel)
            sys.exit('PHP copies are out of step with their pages: run  python tools/build-php.py')
        print(f'OK: {len(want)} PHP copies match their pages')
        return
    for rel in stale:
        (R / rel).write_bytes(want[rel])
    for rel in orphans:
        (R / rel).unlink()
    print(f'{len(want)} PHP copies: {len(stale)} written, {len(orphans)} removed')

if __name__ == '__main__':
    main()
