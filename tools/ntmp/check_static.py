"""Reproduce the Pages publish shape with hardlinks, then run its actual link/i18n guards."""
from pathlib import Path
import os,subprocess,sys,tempfile

ROOT=Path(__file__).resolve().parents[2]
skip={'.git','.github','_site','_internal','_dist','campaigns','archive','tools','banners','f360-linkedin-dev-pack','tiktok','snapcha-retargeting','meta','googleads','logs','api','artifacts','node_modules','__pycache__'}
with tempfile.TemporaryDirectory(prefix='ntmp-static-') as folder:
 out=Path(folder)
 for directory,dirs,files in os.walk(ROOT):
  current=Path(directory);relative=current.relative_to(ROOT)
  if relative==Path('.'):dirs[:]=[d for d in dirs if d not in skip and not d.startswith('.git')]
  for name in files:
   if name.startswith('.git') or name in {'README.md','_config.yml','.scrollcraft.json','.htaccess'} or name.endswith('.php'):continue
   if relative==Path('.') and name.endswith(('.pdf','.zip')):continue
   src=current/name;dest=out/relative/('index.html' if name=='-index.html' else name);dest.parent.mkdir(parents=True,exist_ok=True)
   os.link(src,dest)
 for guard in ['check-i18n.py','check-links.py']:
  result=subprocess.run([sys.executable,'-I',str(ROOT/'tools'/guard),str(out)],capture_output=True,text=True,encoding='utf-8')
  if result.returncode:print(result.stdout);print(result.stderr);sys.exit(result.returncode)
  print(guard+': PASS ('+str(len(result.stdout.splitlines()))+' audit lines)')
 print('PASS: static deployment snapshot resolves after campaign index renaming; private folders are excluded.')
