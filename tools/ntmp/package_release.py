"""Package only the NTMP release; never include configuration secrets or logs."""
from pathlib import Path
from datetime import datetime,timezone
from zipfile import ZipFile,ZIP_DEFLATED
import hashlib,json

ROOT=Path(__file__).resolve().parents[2]
out=ROOT/'_dist';out.mkdir(exist_ok=True)
files=[ROOT/'api/ntmp-review.php',ROOT/'tools/ntmp/RELEASE.md']
for locale in ['features','ar/features']:
 for ext in ['html','php']:files.append(ROOT/f'landing/{locale}/ntmp-tourism-platform.{ext}')
files+=list((ROOT/'landing/assets/ntmp').glob('*'))
files+=list((ROOT/'landing/assets/redesign/fonts').glob('*.woff2'))
files+=[ROOT/f'landing/assets/site/brand-{mode}-340.webp' for mode in ['dark','light']]
files.append(ROOT/'landing/favicon.ico')
files=[p for p in files if p.is_file() and p.name!='ntmp.js']
manifest={p.relative_to(ROOT).as_posix():hashlib.sha256(p.read_bytes()).hexdigest() for p in files}
stamp=datetime.now(timezone.utc).strftime('%Y%m%dT%H%M%SZ')
archive=out/f'ntmp-release-{stamp}.zip'
with ZipFile(archive,'w',ZIP_DEFLATED) as z:
 for p in files:z.write(p,p.relative_to(ROOT).as_posix())
 z.writestr('ntmp-release-manifest.json',json.dumps({'files':manifest,'deployed':False,'requires':'Existing PHP host configuration/dependencies; RELEASE.md describes access blockers.'},indent=2))
print(f'Release package: {archive} ({archive.stat().st_size:,} bytes, {len(files)} files; no secrets or logs)')
