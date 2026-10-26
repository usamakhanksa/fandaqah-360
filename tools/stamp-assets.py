"""Give every local script, stylesheet, data file and SVG a version stamp taken from its content: site.js?v=1a2b3c4d.

Cloudflare and browsers keep scripts and stylesheets for hours. Without a stamp, a new page can run yesterday's site.js
(the live site did exactly that: new form fields, an old script posting to the dev CRM). With one, a changed file has a
new address, so it is fetched at once, and an unchanged file keeps its cached copy.

What is stamped:
  - in every public .html page: src / href / data-src values that point at a local .js, .css, .json or .svg file;
  - the icon sprite wherever pages or scripts build its address in code (icons.svg#id → icons.svg?v=…#id).
The stamp is the first 8 hex digits of the file's SHA-256, with CRLF read as LF so Windows and CI agree.

    python tools/stamp-assets.py           update the stamps (run it after changing any asset, then tools/build-php.py)
    python tools/stamp-assets.py --check   change nothing; fail if any stamp is out of date (CI runs this)
"""
import fnmatch, hashlib, pathlib, re, sys

R = pathlib.Path(__file__).resolve().parent.parent
SKIP_DIRS = {'.git', '.github', '_internal', '_site', '_dist', 'archive', 'campaigns', 'tools', 'banners', 'api', 'node_modules', 'scrollcraft'}
EXTS = ('.js', '.css', '.json', '.svg')
SCRIPT_DIRS = ['assets/js', 'book/assets', 'landing/assets/redesign']    # scripts that build icon addresses in code

SKIP_PAGES = ('book/snap*.html',)   # the Snapchat render compositions: rendered locally to video, never browsed

def public(pattern):
    return [p for p in sorted(R.rglob(pattern)) if not set(p.relative_to(R).parts[:-1]) & SKIP_DIRS
            and not any(fnmatch.fnmatch(p.relative_to(R).as_posix(), g) for g in SKIP_PAGES)]

def digest(path):
    return hashlib.sha256(path.read_bytes().replace(b'\r\n', b'\n')).hexdigest()[:8]

SPRITE_REF = re.compile(r'icons\.svg(?:\?v=[0-9a-f]{8})?#')
ATTR_REF = re.compile(r'(\s(?:src|href|data-src)=")([^"#?:]+?(?:' + '|'.join(re.escape(e) for e in EXTS) + r'))(?:\?v=[0-9a-f]{8})?((?:#[^"]*)?")')

def stamp_sprite(text, sprite_v):
    return SPRITE_REF.sub(f'icons.svg?v={sprite_v}#', text)

def stamp_page(page, text, sprite_v):
    text = stamp_sprite(text, sprite_v)
    def ref(m):
        target = (page.parent / m.group(2)).resolve()
        if not target.is_file():
            return m.group(0)                      # check-links reports missing files; leave it as written
        return f'{m.group(1)}{m.group(2)}?v={digest(target)}{m.group(3)}'
    return ATTR_REF.sub(ref, text)

def main():
    check = '--check' in sys.argv
    sprite_v = digest(R / 'assets/icons.svg')
    stale = []
    def settle(path, new):
        raw = path.read_bytes().decode('utf-8')
        crlf = '\r\n' in raw
        if raw.replace('\r\n', '\n') == new.replace('\r\n', '\n'):
            return
        stale.append(path.relative_to(R).as_posix())
        if not check:
            path.write_bytes((new.replace('\r\n', '\n').replace('\n', '\r\n') if crlf else new.replace('\r\n', '\n')).encode('utf-8'))
    # 1. scripts that build the sprite's address: stamped first, because their own stamps depend on their content
    for d in SCRIPT_DIRS:
        for js in sorted((R / d).glob('*.js')):
            settle(js, stamp_sprite(js.read_bytes().decode('utf-8'), sprite_v))
    # 2. the pages
    for page in public('*.html'):
        settle(page, stamp_page(page, page.read_bytes().decode('utf-8'), sprite_v))
    if check:
        if stale:
            for s in stale: print('stamp out of date:', s)
            sys.exit('Asset stamps are out of date: run  python tools/stamp-assets.py  then  python tools/build-php.py')
        print('OK: every page asks for the current version of its scripts, styles, data and icons')
    else:
        print(f'{len(stale)} files restamped')

if __name__ == '__main__':
    main()
