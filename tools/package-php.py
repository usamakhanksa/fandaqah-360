"""Pack the PHP-host version of the site into one zip, ready to upload (e.g. to fandaqah.com/landing/fandaqah-360/).

Everything the site needs and nothing it shouldn't serve: the pages and their PHP copies, api/ (the lead proxy), .htaccess,
assets, media. Internal material stays out, the same list GitHub Pages leaves out: campaigns, tools, archive, banners,
_internal, documents and source files. The stamps and PHP copies must be current, so a half-updated site can't ship.

    python tools/package-php.py      refreshes the stamps and PHP copies, then writes _dist/fandaqah-360-php.zip
                                     (upload it and extract it over the site folder)
"""
import pathlib, subprocess, sys, zipfile

R = pathlib.Path(__file__).resolve().parent.parent
OUT = R / '_dist' / 'fandaqah-360-php.zip'
SKIP_DIRS = {'.git', '.github', '_internal', '_site', '_dist', 'archive', 'campaigns', 'tools', 'banners', 'node_modules', 'scrollcraft'}
SKIP_FILES = {'_config.yml', '.scrollcraft.json', '.gitignore', '.gitattributes'}
SKIP_EXT = {'.md', '.py', '.pdf', '.psd', '.docx', '.zip', '.mjs', '.log'}
STORED = {'.mp4', '.m4a', '.mp3', '.jpg', '.jpeg', '.png', '.webp', '.woff2', '.gif'}   # already compressed

def main():
    # bring the stamps and the PHP copies up to date first, so the upload is always one consistent version
    for tool in ('stamp-assets.py', 'build-php.py'):
        r = subprocess.run([sys.executable, '-I', str(R / 'tools' / tool)], capture_output=True, text=True)
        if r.returncode:
            sys.exit(r.stdout + r.stderr)
        print(r.stdout.strip())
    files = []
    for p in sorted(R.rglob('*')):
        rel = p.relative_to(R)
        if not p.is_file() or set(rel.parts[:-1]) & SKIP_DIRS or rel.name in SKIP_FILES or p.suffix.lower() in SKIP_EXT:
            continue
        files.append(rel)
    OUT.parent.mkdir(exist_ok=True)
    with zipfile.ZipFile(OUT, 'w') as z:
        for rel in files:
            z.write(R / rel, rel.as_posix(), compress_type=zipfile.ZIP_STORED if rel.suffix.lower() in STORED else zipfile.ZIP_DEFLATED)
    must = ['.htaccess', 'api/lead.php', 'api/config.php', 'api/page.php', 'index.php', 'existing-customers/index.php',
            'existing-customers/modules.json', 'assets/js/site.js', 'assets/js/lead-capture.js', 'new-hotels/index.php', 'fandaqah-360-landing-v2/index.php', 'hotel-groups/index.php', 'opera-pms/index.php']
    missing = [m for m in must if pathlib.PurePosixPath(m) not in {pathlib.PurePosixPath(f.as_posix()) for f in files}]
    if missing:
        sys.exit(f'Package is missing {missing}')
    print(f'{OUT.relative_to(R).as_posix()}: {len(files)} files, {OUT.stat().st_size / 1e6:.1f} MB')

if __name__ == '__main__':
    main()
