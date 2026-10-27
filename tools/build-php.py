"""Write a PHP copy beside every public page, for a PHP host. The .html page stays the one you edit (and the one GitHub Pages serves).

Each copy is the page, plus:
  - a first line that sends the host's headers (api/page.php) and says the file is generated;
  - <meta name="fq-lead-proxy">, so the page's forms post to api/lead.php, which adds the CRM key on the server;
  - its links to other pages pointed at their PHP copies;
  - on the campaign forms (#lead-form), action/method, so they still send without JavaScript;
  - assets/js/lead-capture.js (every copy), which recovers visitors who type their number or email and leave unsent;
  - the Google Ads tag + Meta Pixel (assets/js/fq-ads.js), the Snap Pixel (assets/js/fq-snap.js), the LinkedIn Insight Tag
    (assets/js/linkedin-insight.js) and the TikTok pixel (assets/js/fandaqah-tiktok.js) in the <head>,
    with their conversion events on the two F360 pages;
  - the WhatsApp button (customer pages).
A page named -index.html (kept off GitHub Pages' folder address) is published as its folder's index.php.

    python tools/build-php.py           write the copies, and remove copies whose page is gone
    python tools/build-php.py --check   change nothing; fail if any copy is missing, stale or orphaned (CI runs this)
"""
import fnmatch, hashlib, pathlib, posixpath, re, sys

R = pathlib.Path(__file__).resolve().parent.parent
SKIP_DIRS = {'.git', '.github', '_internal', '_site', 'archive', 'campaigns', 'tools', 'banners', 'api', 'node_modules', 'scrollcraft',
             'f360-linkedin-dev-pack', 'tiktok', 'snapcha-retargeting', 'meta', 'googleads'}   # the LinkedIn and TikTok hand-off packs: reference material, wired in via assets/js/ and api/
SKIP_PAGES = ('book/reel.html', 'book/snap*.html')   # render compositions for the reels and Snapchat ads, not pages anyone visits
MARK = 'Generated from '

# The WhatsApp button every copy carries (owner, 27 Oct 2026): fixed bottom-left, in the owner's markup
# (group-whatsapp / btn-whatsapp / img-whatsapp). Self-contained: it follows the page's language, rises above the
# hotel clock (feature pages) and the phone call-to-action bar, and logs a whatsapp_click to the dataLayer.
NO_WHATSAPP = ('book/index.html', 'linkdein-message.html')   # internal tools, not customer pages
WA_NUMBER = '966920066456'
WHATSAPP = """<!-- WhatsApp chat button (added to every PHP copy by tools/build-php.py) -->
<style>
.fq-wa{position:fixed;z-index:90;left:max(20px,env(safe-area-inset-left));bottom:calc(22px + env(safe-area-inset-bottom));margin:0;display:flex;align-items:center;transition:bottom .35s cubic-bezier(.2,.8,.2,1);animation:fq-wa-in .55s cubic-bezier(.2,.8,.2,1) .9s both}
.fq-wa .btn-whatsapp{position:relative;display:grid;place-items:center;width:58px;height:58px;border-radius:50%;background:#25D366;box-shadow:0 14px 28px -10px rgba(18,140,74,.8),0 2px 6px rgba(0,0,0,.18);text-decoration:none;outline:none;transition:transform .2s ease,box-shadow .2s ease}
.fq-wa .img-whatsapp{display:grid;place-items:center;width:31px;height:31px}
.fq-wa .img-whatsapp svg{width:100%;height:100%;fill:#fff}
.fq-wa .btn-whatsapp::before{content:"";position:absolute;inset:0;border-radius:50%;border:2px solid #25D366;opacity:0;animation:fq-wa-ring 6s ease-out 2.6s infinite}
.fq-wa .fq-wa-tip{position:absolute;left:70px;top:50%;white-space:nowrap;padding:10px 16px;border-radius:999px;background:#2a273c;color:#fff;font-size:14px;font-weight:600;line-height:1.2;box-shadow:0 12px 24px -12px rgba(42,39,60,.7);opacity:0;transform:translate(-6px,-50%);pointer-events:none;transition:opacity .2s ease,transform .2s ease}
.fq-wa .btn-whatsapp:hover,.fq-wa .btn-whatsapp:focus-visible{transform:translateY(-2px) scale(1.04)}
.fq-wa .btn-whatsapp:focus-visible{box-shadow:0 0 0 3px #fff,0 0 0 6px #25D366}
.fq-wa:hover .fq-wa-tip,.fq-wa:focus-within .fq-wa-tip{opacity:1;transform:translate(0,-50%)}
@keyframes fq-wa-in{from{opacity:0;transform:translateY(18px) scale(.86)}to{opacity:1;transform:none}}
@keyframes fq-wa-ring{0%{opacity:.7;transform:scale(1)}16%,100%{opacity:0;transform:scale(1.6)}}
body:has(.fx-clock:not(.is-docked)) .fq-wa{bottom:calc(86px + env(safe-area-inset-bottom))}
@media (hover:none){.fq-wa .fq-wa-tip{display:none}}
@media (max-width:760px){.fq-wa .btn-whatsapp{width:54px;height:54px}body:has(.m-cta.is-on) .fq-wa{bottom:calc(94px + env(safe-area-inset-bottom))}}
@media (prefers-reduced-motion:reduce){.fq-wa{animation:none;transition:none}.fq-wa .btn-whatsapp{transition:none}.fq-wa .btn-whatsapp::before{animation:none}}
@media print{.fq-wa{display:none}}
</style>
<div class="group-whatsapp flex-end mtop-1 fq-wa">
  <a href="https://wa.me/%(num)s" target="_blank" rel="noopener" class="btn-whatsapp" aria-label="اتصل بنا عبر واتساب">
    <div class="img-whatsapp" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg></div>
  </a>
  <span class="fq-wa-tip" aria-hidden="true">تواصل معنا عبر واتساب</span>
</div>
<script>
(function () {
  var box = document.querySelector('.fq-wa'); if (!box) return;
  var a = box.querySelector('a'), tip = box.querySelector('.fq-wa-tip'), root = document.documentElement;
  var T = { ar: { label: 'اتصل بنا عبر واتساب', tip: 'تواصل معنا عبر واتساب', msg: 'مرحباً فندقة، أود الاستفسار عن فندقة 360.' },
            en: { label: 'Chat with us on WhatsApp', tip: 'Chat with us on WhatsApp', msg: 'Hello Fandaqah, I would like to ask about Fandaqah 360.' } };
  // the label, the tooltip and the opening message follow the page language, and switch with it
  function sync() {
    var t = T[(root.lang || 'ar').slice(0, 2) === 'en' ? 'en' : 'ar'];
    a.setAttribute('aria-label', t.label); tip.textContent = t.tip;
    a.href = 'https://wa.me/%(num)s?text=' + encodeURIComponent(t.msg);
  }
  sync();
  new MutationObserver(sync).observe(root, { attributes: true, attributeFilter: ['lang'] });
  a.addEventListener('click', function () { if (window.dataLayer) window.dataLayer.push({ event: 'whatsapp_click', page: location.pathname }); });
})();
</script>
""".replace('%(num)s', WA_NUMBER)

# Unfinished-form lead capture (owner, 27 Oct 2026): on every copy, no page skipped. It posts through the page's lead proxy, so the
# CRM key stays on the server; the ?v= stamp changes with the file, so browsers never keep an old copy.
CAPTURE = 'assets/js/lead-capture.js'
CAPTURE_TAG = '<script src="%s' + CAPTURE + '?v=' + hashlib.sha1((R / CAPTURE).read_bytes()).hexdigest()[:8] + '" defer></script>\n'

# LinkedIn Insight Tag (f360-linkedin-dev-pack, 27 Oct 2026): in the <head> of every copy, no page skipped; the two Fandaqah 360
# landing pages also send the F360 conversion events (CTA click, lead submit). The .html pages (GitHub Pages preview) never carry it.
INSIGHT = 'assets/js/linkedin-insight.js'
F360_EVENT_PAGES = ('existing-customers/-index.html', 'fandaqah-360-landing-v2/-index.html')
INSIGHT_TAG = '<script src="%s' + INSIGHT + '?v=' + hashlib.sha1((R / INSIGHT).read_bytes()).hexdigest()[:8] + '" defer%s></script>\n'
INSIGHT_NOSCRIPT = ('<noscript><img height="1" width="1" style="display:none;" alt="" '
                    'src="https://px.ads.linkedin.com/collect/?pid=11189657&fmt=gif"></noscript>\n')

# TikTok Pixel + Events API bridge (tiktok/ pack, 27 Oct 2026): in the <head> of every copy, no page skipped; the conversion
# events (ViewContent, Contact, LeadFormOpen/Start, Lead) on the same two F360 pages. It stays silent until its pixel id is set.
TIKTOK = 'assets/js/fandaqah-tiktok.js'
TIKTOK_TAG = '<script src="%s' + TIKTOK + '?v=' + hashlib.sha1((R / TIKTOK).read_bytes()).hexdigest()[:8] + '" defer%s></script>\n'

# Google Ads + Meta Pixel (assets/js/fq-ads.js): the tags that were pasted by hand into the two live F360 pages, now built in, so an
# upload keeps them; their lead conversions on the two F360 pages only. Snap Pixel (snapcha-retargeting pack, assets/js/fq-snap.js):
# PAGE_VIEW on every page, its conversion events on the two F360 pages. Both stay as written until their ids are filled in.
ADS = 'assets/js/fq-ads.js'
# A plain tag (not async/defer), so its Consent Mode defaults and configs are queued before every other tag (googleads/ pack).
ADS_TAG = '<script src="%s' + ADS + '?v=' + hashlib.sha1((R / ADS).read_bytes()).hexdigest()[:8] + '"></script>\n'
# Google measurement layer (googleads/ pack, assets/js/f360-measurement.js): the lead conversion with Enhanced Conversions, the eight
# secondary conversions and GA4 events, on the two F360 pages only; click ids and UTMs on every page.
MEASURE = 'assets/js/f360-measurement.js'
MEASURE_TAG = '<script src="%s' + MEASURE + '?v=' + hashlib.sha1((R / MEASURE).read_bytes()).hexdigest()[:8] + '" defer%s></script>\n'
# Meta event layer (meta/ pack, assets/js/fq-meta-events.js): after the base pixel in fq-ads.js; ViewContent, Contact,
# InitiateCheckout, LeadFormStart and the Lead that pairs with api/fq_meta_capi.php, on the two F360 pages only.
META_EVENTS = 'assets/js/fq-meta-events.js'
META_EVENTS_TAG = '<script src="%s' + META_EVENTS + '?v=' + hashlib.sha1((R / META_EVENTS).read_bytes()).hexdigest()[:8] + '" defer%s></script>\n'
SNAP = 'assets/js/fq-snap.js'
SNAP_TAG = '<script src="%s' + SNAP + '?v=' + hashlib.sha1((R / SNAP).read_bytes()).hexdigest()[:8] + '" async%s></script>\n'
META_NOSCRIPT = ('<noscript><img height="1" width="1" style="display:none" alt="" '
                 'src="https://www.facebook.com/tr?id=992779690541049&amp;ev=PageView&amp;noscript=1"></noscript>\n')

def php_name(rel):
    # x/-index.html → x/index.php (the copies' published names, as on the host); every other page keeps its own name
    return rel[:-len('-index.html')] + 'index.php' if posixpath.basename(rel) == '-index.html' else rel[:-5] + '.php'

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
        return m.group(1) + (target[:-len('-index.html')] + 'index.php' if posixpath.basename(target) == '-index.html' else target[:-5] + '.php') + m.group(3)
    s = LINK.sub(relink, src)
    meta = f'<meta name="fq-lead-proxy" content="{up}api/lead.php">'
    s, n = re.subn(r'(<meta charset="utf-8">)', r'\1' + meta, s, count=1, flags=re.I)
    if not n:
        raise SystemExit(f'{rel}: no <meta charset="utf-8"> to anchor the proxy tag')
    s = s.replace('<form class="form" id="lead-form" novalidate', f'<form class="form" id="lead-form" action="{up}api/lead.php" method="post" novalidate', 1)
    s = re.sub(r'<form class="form ([\w -]+)" id="lead-form" novalidate', rf'<form class="form \1" id="lead-form" action="{up}api/lead.php" method="post" novalidate', s, count=1)
    # the first line ends the way the page's lines do, so a copy checked out with CRLF still matches
    nl = '\r\n' if '\r\n' in src else '\n'
    # just before the page closes: the lead capture (every page), then the WhatsApp button (customer pages only)
    end = s.rfind('</body>')
    if end == -1:
        raise SystemExit(f'{rel}: no </body> to place the lead capture before')
    tail = INSIGHT_NOSCRIPT + META_NOSCRIPT + (CAPTURE_TAG % up) + (WHATSAPP if rel not in NO_WHATSAPP else '')
    s = s[:end] + tail.replace('\n', nl) + s[end:]
    # the ad platforms' tags, last in the <head>: Google Ads + Meta (base pixel, then its event layer), Snap, LinkedIn, TikTok
    head_end = s.find('</head>')
    if head_end == -1:
        raise SystemExit(f'{rel}: no </head> to place the LinkedIn and TikTok tags in')
    f360 = rel in F360_EVENT_PAGES
    tag = (ADS_TAG % up + MEASURE_TAG % (up, ' data-google-events' if f360 else '')
           + META_EVENTS_TAG % (up, ' data-meta-events' if f360 else '')
           + SNAP_TAG % (up, ' data-snap-events' if f360 else '')
           + INSIGHT_TAG % (up, ' data-f360-events' if f360 else '') + TIKTOK_TAG % (up, ' data-tt-events' if f360 else ''))
    s = s[:head_end] + tag.replace('\n', nl) + s[head_end:]
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
        want[php_name(rel)] = convert(rel, raw, known).encode('utf-8')
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
