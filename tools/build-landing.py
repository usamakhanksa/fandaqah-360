"""Build the Fandaqah landing site's SEO layer and its 40 feature pages (20 modules, English and Arabic).

Usage:  python -I tools/build-landing.py

1. Patches the hand-made landing pages in place (safe to re-run): unique title and description, canonical,
   Open Graph, JSON-LD, footer details, the lead dialog, module links, VAT lines, the pricing comparison and the
   privacy/terms copy.
2. Writes landing/features/<slug>.html and landing/ar/features/<slug>.html from tools/landing_features.py, reusing
   the header, mega menu and footer of landing/index.html so every page shares one chrome.
3. Writes landing/sitemap.xml (with hreflang alternates).

Change BASE when the site moves to its production address.
"""
import html, json, re, sys, pathlib

sys.stdout.reconfigure(encoding='utf-8')
TOOLS = pathlib.Path(__file__).resolve().parent
sys.path.insert(0, str(TOOLS))
from landing_features import DEPTS, PHOTOS, PACKAGES, PKG_SETS, FEATURES, BY_SLUG  # noqa: E402

ROOT = TOOLS.parent
L = ROOT / 'landing'
BASE = 'https://usamakhanksa.github.io/fandaqah-360/landing/'
ORG_URL = 'https://fandaqah.com/'
PHONE, PHONE_TXT, EMAIL = '+966920066456', '+966 9200 66456', 'info@fandaqah.com'
SOCIAL = [  # the profiles linked from fandaqah.com
    ('Facebook', 'https://www.facebook.com/pmsfandaqah/', '<path d="M15 3h-2.5A4.5 4.5 0 0 0 8 7.5V10H5.5v4H8v7h4v-7h3l.5-4H12V8a1 1 0 0 1 1-1h2V3Z"/>'),
    ('Instagram', 'https://www.instagram.com/fandaqah.pms', '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r=".6" fill="currentColor"/>'),
    ('LinkedIn', 'https://www.linkedin.com/company/fandaqah/', '<rect x="3.5" y="3.5" width="17" height="17" rx="3"/><path d="M8 10.5V16M8 7.8v.2M11.5 16v-3.2a2.3 2.3 0 0 1 4.5 0V16M11.5 10.5V16"/>'),
    ('X', 'https://twitter.com/fandaqah', '<path d="m4 4 6.6 8.8L4.3 20h1.6l5.5-6.2 4.6 6.2H20l-6.9-9.3L19 4h-1.6l-5 5.7L8.2 4H4Z"/>'),
]
VAT = 15


def esc(s):
    return html.escape(s, quote=True)


def money(x):
    """1200 -> '1,200'; 4019.25 -> '4,019.25'"""
    return f'{int(x):,}' if float(x).is_integer() else f'{x:,.2f}'


def with_vat(price):
    return round(price * (100 + VAT)) / 100


def read(p):
    return p.read_text(encoding='utf-8')


def write(p, s):
    p.parent.mkdir(parents=True, exist_ok=True)
    p.write_text(s, encoding='utf-8', newline='\n')


def ld(graph):
    return '<script type="application/ld+json">' + json.dumps({'@context': 'https://schema.org', '@graph': graph}, ensure_ascii=False, separators=(',', ':')) + '</script>'


# --------------------------------------------------------------------------------------------- validation
def validate():
    errs = []
    if len(FEATURES) != 20 or len({f['code'] for f in FEATURES}) != 20:
        errs.append('expected 20 features with unique codes')
    for f in FEATURES:
        s, P, en, ar = f['slug'], f['panel'], f['en'], f['ar']
        n = len(en['steps'])
        if len(ar['steps']) != n or len(P['steps']) != n:
            errs.append(f'{s}: steps differ between en/ar/panel')
        if sum(1 for st in en['steps'] if st.get('peak')) != 1:
            errs.append(f'{s}: needs exactly one peak step')
        times = [st['t'] for st in en['steps']]
        if times != sorted(times) or times[0] < '06:00':
            errs.append(f'{s}: step times must ascend from 06:00')
        for r in P['rows']:
            if r[2] not in P['states']:
                errs.append(f'{s}: row state {r[2]}')
        for i, patch in enumerate(P['steps']):
            for row, k in patch.items():
                if row >= len(P['rows']) or k not in P['states']:
                    errs.append(f'{s}: bad patch {i}:{row}:{k}')
        if any(k not in P['states'] for k in P['count']):
            errs.append(f'{s}: count key')
        if any(r not in BY_SLUG or r == s for r in f['related']):
            errs.append(f'{s}: related {f["related"]}')
        if f['photo'] not in PHOTOS or f['pkg'] not in PKG_SETS:
            errs.append(f'{s}: photo/pkg')
        for lang in ('en', 'ar'):
            d = f[lang]
            if len(d['title']) > 60:
                errs.append(f'{s}/{lang}: title {len(d["title"])} chars')
            if not 70 <= len(d['desc']) <= 160:
                errs.append(f'{s}/{lang}: description {len(d["desc"])} chars')
            blob = json.dumps(d, ensure_ascii=False)
            if '—' in blob:
                errs.append(f'{s}/{lang}: em dash')
        if len(en['lead'].split()) > 20:
            errs.append(f'{s}: hero lead over 20 words')
    if errs:
        sys.exit('content errors:\n  ' + '\n  '.join(errs))


# ---------------------------------------------------------------------------------- shared JSON-LD nodes
def org_node():
    return {'@type': 'Organization', '@id': BASE + '#org', 'name': 'Fandaqah', 'alternateName': 'فندقة', 'url': ORG_URL,
            'logo': BASE + 'assets/site/brand-dark-340.webp', 'email': EMAIL, 'telephone': PHONE,
            'address': {'@type': 'PostalAddress', 'streetAddress': 'Prince Turki Street, Al Janubiyah', 'addressLocality': 'Al Khobar',
                        'postalCode': '34611', 'addressCountry': 'SA'},
            'contactPoint': {'@type': 'ContactPoint', 'telephone': PHONE, 'email': EMAIL, 'contactType': 'sales', 'areaServed': 'SA',
                             'availableLanguage': ['ar', 'en']},
            'sameAs': [u for _, u, _ in SOCIAL]}


def software_node():
    offers = []
    for key, name in (('basic', 'Basic Subscription'), ('compliance', 'Compliance Package'), ('360', 'Fandaqah 360')):
        p = str(PACKAGES[key]['price'])
        offers.append({'@type': 'Offer', 'name': name, 'price': p, 'priceCurrency': 'SAR', 'url': BASE + 'pricing.html#packages',
                       'priceSpecification': {'@type': 'UnitPriceSpecification', 'price': p, 'priceCurrency': 'SAR', 'unitCode': 'ANN',
                                              'valueAddedTaxIncluded': False}})
    return {'@type': 'SoftwareApplication', '@id': BASE + '#software', 'name': 'Fandaqah', 'applicationCategory': 'BusinessApplication',
            'applicationSubCategory': 'Hotel management software', 'operatingSystem': 'Web browser', 'url': BASE,
            'publisher': {'@id': BASE + '#org'}, 'offers': offers}


def crumbs_node(items):
    return {'@type': 'BreadcrumbList', 'itemListElement': [{'@type': 'ListItem', 'position': i + 1, 'name': n, 'item': u} for i, (n, u) in enumerate(items)]}


def faq_node(qas):
    return {'@type': 'FAQPage', 'mainEntity': [{'@type': 'Question', 'name': q, 'acceptedAnswer': {'@type': 'Answer', 'text': a}} for q, a in qas]}


def page_faqs(t):
    out = []
    for q, a in re.findall(r'<details><summary>(.*?)<span>\+</span></summary><p>(.*?)</p></details>', t, re.S):
        out.append((html.unescape(re.sub(r'<[^>]+>', '', q)).strip(), html.unescape(re.sub(r'<[^>]+>', '', a)).strip()))
    return out


# ------------------------------------------------------------------------------------- existing pages
PAGES = {
    'index.html': {'title': 'Hotel Management Software in Saudi Arabia | Fandaqah', 'og': 'home',
                   'desc': 'Fandaqah is a cloud hotel management system for Saudi hotels: PMS, channel manager, booking engine and ZATCA, Shomoos and NTMP integrations in one platform.'},
    'features.html': {'title': 'Hotel Management System Features: All 20 Modules | Fandaqah', 'og': 'features',
                      'desc': 'Explore all 20 Fandaqah modules across operations, sales and finance, guests and dining, and Saudi government integrations, each with its own page.'},
    'fandaqah-360.html': {'title': 'Fandaqah 360: The Complete Hospitality Upgrade | Fandaqah', 'og': 'fandaqah-360',
                          'desc': 'Fandaqah 360 brings all 20 modules, Saudi compliance integrations, premium support and priority onboarding together for SAR 3,495 a year plus VAT.'},
    'pricing.html': {'title': 'Hotel PMS Pricing & Packages in Saudi Arabia | Fandaqah', 'og': 'pricing',
                     'desc': 'Compare Fandaqah packages: Basic SAR 1,200, Compliance SAR 3,200 and Fandaqah 360 SAR 3,495 a year, plus 15% VAT. See what each package includes.'},
    'about.html': {'title': 'About Fandaqah | Hotel Management Platform for Saudi Hotels', 'og': 'about',
                   'desc': 'Fandaqah brings reservations, distribution, guest services and property operations into one connected platform for Saudi hotels and serviced apartments.'},
    'blog.html': {'title': 'Hospitality Journal: Hotel Operations Insights | Fandaqah', 'og': 'blog',
                  'desc': 'Perspectives on running Saudi hotels: the connected front desk, distribution with a channel manager, and the complete hospitality upgrade.'},
    'contact.html': {'title': 'Contact Fandaqah | Book a Hotel PMS Demo', 'og': 'contact',
                     'desc': 'Talk to the Fandaqah team in Al Khobar: book a demo, ask about packages or get support. Call 920066456 or email info@fandaqah.com.'},
    'privacy.html': {'title': 'Privacy Policy | Fandaqah', 'og': 'home',
                     'desc': 'How the Fandaqah website handles the information you share with us, including demo requests sent to our team and preferences stored on your device.'},
    'terms.html': {'title': 'Terms and Conditions | Fandaqah', 'og': 'home',
                   'desc': 'The terms that apply to the Fandaqah website and the package and pricing information shown on it, including VAT and introduction offer terms.'},
    'features-v2.html': {'title': 'Fandaqah 360 Features (alternate layout) | Fandaqah', 'og': 'features', 'noindex': True, 'canonical': 'features.html',
                         'desc': 'An alternate layout of the Fandaqah features page, kept for review. The main features page lists all 20 modules with their own pages.'},
    'mega-menu-features.html': {'title': 'Fandaqah Mega Menu Showcase | Fandaqah', 'og': 'features', 'noindex': True, 'canonical': 'features.html',
                                'desc': 'A showcase of the Fandaqah features mega menu, kept for review. The main features page lists all 20 modules with their own pages.'},
}
CRUMB_NAMES = {'features.html': 'Features', 'fandaqah-360.html': 'Fandaqah 360', 'pricing.html': 'Packages', 'about.html': 'About Us',
               'blog.html': 'Hospitality Journal', 'contact.html': 'Contact Us', 'privacy.html': 'Privacy Policy', 'terms.html': 'Terms and Conditions'}

SEO_RE = re.compile(r'<!--seo:start-->.*?<!--seo:end-->', re.S)


def seo_block(url, title, desc, og, robots, graph, extra=''):
    og_title = title.split(' | ')[0]
    return ('<!--seo:start-->'
            f'<link rel="canonical" href="{url}">{extra}<meta name="robots" content="{robots}">'
            '<meta property="og:type" content="website"><meta property="og:site_name" content="Fandaqah">'
            f'<meta property="og:title" content="{esc(og_title)}"><meta property="og:description" content="{esc(desc)}">'
            f'<meta property="og:url" content="{url}"><meta property="og:image" content="{BASE}assets/og/{og}.jpg">'
            '<meta property="og:image:width" content="1200"><meta property="og:image:height" content="630">'
            f'<meta property="og:image:alt" content="{esc(og_title)}">'
            '<meta name="twitter:card" content="summary_large_image"><meta name="twitter:site" content="@fandaqah">'
            + ld(graph) + '<!--seo:end-->')


def icon_svg(paths, cls='icon'):
    return (f'<svg class="{cls}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" '
            f'stroke-linejoin="round" aria-hidden="true">{paths}</svg>')


SOCIAL_HTML = ('<div class="social-links">' + ''.join(
    f'<a href="{u}" target="_blank" rel="noopener" aria-label="Fandaqah on {n}">{icon_svg(p)}</a>' for n, u, p in SOCIAL) + '</div>')
CHECK = icon_svg('<path d="m5 12.5 4.2 4.2L19 7"/>', 'icon ok')
MINUS = icon_svg('<path d="M7 12h10"/>', 'icon no')


def dialog_html(lang, root):
    """The lead dialog: every [data-demo] button opens it; site.js posts the request to Fandaqah CRM."""
    T = DIALOG_TXT[lang]
    opts = ''.join(f'<option value="{v}">{esc(T["opt"][i])}</option>' for i, v in enumerate(DIALOG_OPTS))
    return (f'<dialog id="demo-dialog" aria-labelledby="demo-title"><button class="dialog-close" type="button" aria-label="{T["close"]}">×</button>'
            f'<div class="demo-step" data-demo-step="form"><span class="eyebrow">{T["eyebrow"]}</span><h2 id="demo-title">{T["h2"]}</h2>'
            '<div class="demo-package" hidden><p class="demo-package-name"></p><p class="demo-package-price"></p><ul class="demo-package-items"></ul>'
            f'<p class="demo-package-note">{T["inst"]}</p></div>'
            f'<p class="demo-intro">{T["intro"]}</p>'
            '<form id="demo-form" novalidate>'
            f'<label>{T["name"]}<input name="name" autocomplete="name" required placeholder="{T["name_ph"]}" aria-describedby="demo-err-name"><small class="field-error" id="demo-err-name" hidden>{T["e_name"]}</small></label>'
            f'<label>{T["email"]}<input name="email" type="email" autocomplete="email" required placeholder="you@hotel.com" dir="ltr" aria-describedby="demo-err-email"><small class="field-error" id="demo-err-email" hidden>{T["e_email"]}</small></label>'
            f'<label>{T["prop"]}<input name="property" autocomplete="organization" required placeholder="{T["prop_ph"]}" aria-describedby="demo-err-property"><small class="field-error" id="demo-err-property" hidden>{T["e_prop"]}</small></label>'
            f'<label>{T["phone"]}<input name="phone" type="tel" inputmode="tel" autocomplete="tel" required placeholder="05XXXXXXXX" dir="ltr" aria-describedby="demo-err-phone"><small class="field-error" id="demo-err-phone" hidden>{T["e_phone"]}</small></label>'
            f'<label class="full">{T["interest"]}<select name="interest">{opts}</select></label>'
            f'<label class="full">{T["msg"]} <span class="demo-opt">{T["optional"]}</span><textarea name="message" rows="3" maxlength="1000" placeholder="{T["msg_ph"]}"></textarea></label>'
            '<input type="hidden" name="topic" value="">'
            '<label class="demo-hp" aria-hidden="true">Website<input name="website" tabindex="-1" autocomplete="off"></label>'
            '<p class="demo-alert full" role="alert" hidden></p>'
            f'<button class="button primary full" type="submit"><span>{T["send"]}</span></button>'
            f'<small class="demo-consent full">{T["consent"]} <a href="{root}privacy.html{"?lang=ar" if lang == "ar" else ""}">{T["privacy"]}</a></small>'
            '</form></div>'
            f'<div class="demo-step" data-demo-step="done" id="demo-result" hidden><span class="eyebrow">{T["d_eyebrow"]}</span>'
            f'<h2 tabindex="-1">{T["d_h2"]}</h2><p>{T["d_p"]}</p><button class="button primary" type="button" data-demo-close>{T["d_btn"]}</button></div>'
            f'<small class="demo-foot">{T["call"]} <a href="tel:{PHONE}" dir="ltr">{PHONE_TXT}</a> <span aria-hidden="true">·</span> '
            f'<a href="https://app.fandaqah.com/register" target="_blank" rel="noopener">{T["trial"]}</a></small></dialog>')


DIALOG_OPTS = ['Fandaqah 360 demo', 'Free trial', 'Basic Subscription', 'Compliance Package', 'Partner with Fandaqah', 'Talk to support']
DIALOG_TXT = {
    'en': {'close': 'Close dialog', 'eyebrow': 'LET’S TALK HOSPITALITY', 'h2': 'See what’s possible<br> with <span>Fandaqah.</span>',
           'inst': 'Payment in instalments with tabby or tamara.',
           'intro': 'Tell us a little about your property. Our team will contact you to arrange a personal walkthrough.',
           'name': 'Your name', 'name_ph': 'Full name', 'e_name': 'Please enter your name.',
           'email': 'Work email', 'e_email': 'Please enter a valid email address.',
           'prop': 'Property name', 'prop_ph': 'Your hotel or property', 'e_prop': 'Please enter your property name.',
           'phone': 'Mobile number', 'e_phone': 'Please enter a Saudi mobile number, for example 05XXXXXXXX.',
           'interest': 'I’m interested in', 'opt': DIALOG_OPTS, 'send': 'Send my request',
           'msg': 'Your message', 'optional': '(optional)', 'msg_ph': 'Rooms, your current system, or anything you would like us to prepare for your demo.',
           'consent': 'We use these details only to contact you about your request.', 'privacy': 'Privacy Policy',
           'd_eyebrow': 'REQUEST RECEIVED', 'd_h2': 'Thank you. <span>We’ll be in touch.</span>',
           'd_p': 'Our team will contact you to arrange a suitable time. Demo hours: Sunday to Thursday, 9:00 to 17:00.', 'd_btn': 'Done',
           'call': 'Prefer a conversation?', 'trial': 'Start a free trial'},
    'ar': {'close': 'إغلاق النافذة', 'eyebrow': 'لنتحدث عن الضيافة', 'h2': 'اكتشف ما يمكن تحقيقه<br> مع <span>فندقة.</span>',
           'inst': 'الدفع بالتقسيط عبر تابي أو تمارا.',
           'intro': 'أخبرنا قليلاً عن منشأتك، وسيتواصل معك فريقنا لترتيب عرض توضيحي خاص.',
           'name': 'الاسم', 'name_ph': 'الاسم الكامل', 'e_name': 'يرجى إدخال اسمك.',
           'email': 'البريد الإلكتروني للعمل', 'e_email': 'يرجى إدخال بريد إلكتروني صحيح.',
           'prop': 'اسم المنشأة', 'prop_ph': 'اسم الفندق أو المنشأة', 'e_prop': 'يرجى إدخال اسم المنشأة.',
           'phone': 'رقم الجوال', 'e_phone': 'يرجى إدخال رقم جوال سعودي، مثل 05XXXXXXXX.',
           'interest': 'أرغب في', 
           'msg': 'رسالتك', 'optional': '(اختياري)', 'msg_ph': 'عدد الغرف، أو نظامك الحالي، أو أي شيء تودّ أن نجهّزه لعرضك التوضيحي.','opt': ['عرض توضيحي لفندقة 360', 'تجربة مجانية', 'الاشتراك الأساسي', 'باقة الامتثال', 'الشراكة مع فندقة', 'التواصل مع الدعم'],
           'send': 'أرسل طلبي',
           'consent': 'نستخدم هذه البيانات للتواصل معك بشأن طلبك فقط.', 'privacy': 'سياسة الخصوصية',
           'd_eyebrow': 'تم استلام طلبك', 'd_h2': 'شكراً لك. <span>سنتواصل معك قريباً.</span>',
           'd_p': 'سيتواصل معك فريقنا لترتيب موعد مناسب. أوقات العروض: من الأحد إلى الخميس، من 9:00 إلى 17:00.', 'd_btn': 'تم',
           'call': 'تفضّل المحادثة؟', 'trial': 'ابدأ تجربة مجانية'},
}

COMPARE_ROWS = [  # (label, basic, compliance, 360): True = included, False = not included, str = text
    ('PMS Core', True, True, True, 'features/hotel-pms.html'),
    ('Additional modules', '5 modules', 'ZATCA, Shomoos Wallet and NTMP', 'All 20 modules', None),
    ('ZATCA Integration', False, True, True, 'features/zatca-e-invoicing.html'),
    ('Shomoos Wallet', False, True, True, 'features/shomoos-integration.html'),
    ('NTMP I – II', False, True, True, 'features/ntmp-tourism-platform.html'),
    ('Support', 'Standard', 'Priority', 'Premium', 'features/support-training.html'),
    ('Training', 'Basic', 'Full', 'Full, with priority onboarding', None),
    ('Instalments with tabby or tamara', True, True, True, None),
]


def compare_html():
    cols = [('basic', 'Basic Subscription'), ('compliance', 'Compliance Package'), ('360', 'Fandaqah 360')]
    rec = {'360': ' class="is-rec"'}
    head = ''.join(f'<th scope="col"{rec.get(k, "")}><span>{n}</span><small><bdi>SAR {money(PACKAGES[k]["price"])}</bdi> <span>/ year</span></small></th>' for k, n in cols)

    def cell(v):
        if v is True:
            return f'<td>{CHECK}<span class="sr-only">Included</span></td>'
        if v is False:
            return f'<td>{MINUS}<span class="sr-only">Not included</span></td>'
        return f'<td><span>{v}</span></td>'
    rows = ''
    for label, b, c, f, href in COMPARE_ROWS:
        th = f'<a href="{href}">{label}</a>' if href else label
        rows += f'<tr><th scope="row">{th}</th>{cell(b)}{cell(c)}{cell(f)}</tr>'
    rows += '<tr><th scope="row">Price excluding VAT</th>' + ''.join(f'<td><bdi>SAR {money(PACKAGES[k]["price"])}</bdi></td>' for k, _ in cols) + '</tr>'
    rows += '<tr><th scope="row">Price including 15% VAT</th>' + ''.join(f'<td><bdi>SAR {money(with_vat(PACKAGES[k]["price"]))}</bdi></td>' for k, _ in cols) + '</tr>'
    btn = {'basic': 'Ask about Basic', 'compliance': 'Ask about Compliance', '360': 'Activate your upgrade'}
    rows += '<tr class="compare-cta"><th scope="row"><span class="sr-only">Choose a package</span></th>' + ''.join(
        f'<td><button class="button {"primary" if k == "360" else "navy"} small" data-demo data-package="{k}">{btn[k]}</button></td>' for k, _ in cols) + '</tr>'
    return ('<section class="compare section" id="compare" aria-labelledby="compare-title"><div class="container">'
            '<div class="section-heading"><div><h2 id="compare-title">Compare packages <span>side by side</span></h2>'
            '<p>What each package includes, taken from the package details above.</p></div></div>'
            '<div class="compare-scroll" tabindex="0" role="region" aria-labelledby="compare-title">'
            f'<table class="compare-table"><thead><tr><th scope="col"><span class="sr-only">Feature</span></th>{head}</tr></thead><tbody>{rows}</tbody></table>'
            '</div><p class="pricing-note">Prices are per year and exclude 15% VAT unless stated. Package inclusions are confirmed during your demo.</p>'
            '</div></section>')


PRIVACY_MAIN = ('<main id="main"><section class="page-intro section"><div class="container"><span class="eyebrow">FANDAQAH</span><h1>Privacy Policy</h1>'
                '<p>How this website handles the information you share with us.</p></div></section><section class="section"><article class="container legal-copy">'
                '<h2>Requests you send us</h2><p>When you send a demo, package or support request, the details you enter (your name, email address, mobile number, property name, the interest you select and any message you write) are sent securely to Fandaqah’s customer relationship system, together with the page you sent it from, the language you were using and any campaign tags in the page address. We use them only to respond to your request and arrange your walkthrough.</p>'
                '<h2>Newsletter</h2><p>Newsletter sign-ups send only your email address to Fandaqah’s customer relationship system, and we use it only to send you our newsletter.</p>'
                '<h2>Preferences stored on your device</h2><p>Your selected language is stored locally in your browser. This website does not use advertising cookies.</p>'
                '<h2>Third parties</h2><p>Links to social networks, the Fandaqah application and email applications are governed by those services’ own privacy practices.</p>'
                f'<h2>Contact</h2><p>For the current published policy or commercial terms, contact <a href="mailto:{EMAIL}">{EMAIL}</a> or call <a href="tel:{PHONE}">{PHONE_TXT}</a>.</p>'
                '</article></section></main>')
TERMS_MAIN = ('<main id="main"><section class="page-intro section"><div class="container"><span class="eyebrow">FANDAQAH</span><h1>Terms And Conditions</h1>'
              '<p>The terms that apply to this website and the package information shown on it.</p></div></section><section class="section"><article class="container legal-copy">'
              '<h2>Website information</h2><p>This website presents Fandaqah hospitality products and the supplied package information for review. Prices, package inclusions, integration availability and offer terms must be confirmed with the Fandaqah team before purchase.</p>'
              '<h2>Introduction offer</h2><p>Displayed prices exclude 15% VAT; prices including VAT are shown for convenience. Any activation guarantee, upgrade protection or special pricing is subject to the agreed commercial terms. This website does not accept payments or create a subscription.</p>'
              f'<h2>Contact</h2><p>For the current published policy or commercial terms, contact <a href="mailto:{EMAIL}">{EMAIL}</a> or call <a href="tel:{PHONE}">{PHONE_TXT}</a>.</p>'
              '</article></section></main>')
BLOG_LINKS = {  # card title -> (new href, link text, image alt)
    'The connected front desk': ('features/hotel-pms.html', 'Read about PMS Core', 'A hotel lobby with a reception desk'),
    'A smarter approach to distribution': ('features/channel-manager.html', 'Read about the Channel Manager', None),
    'The complete hospitality upgrade': ('fandaqah-360.html', 'Explore Fandaqah 360', 'A chalet terrace and pool shaded by palm trees'),
}


def patch_pages():
    code_to_slug = {f['code']: f['slug'] for f in FEATURES}
    pricing_faqs = []
    for name, meta in PAGES.items():
        p = L / name
        t = read(p)
        # head: title, description, SEO block, upgrade stylesheet
        t = SEO_RE.sub('', t)
        t = re.sub(r'<title>.*?</title>', f'<title>{esc(meta["title"])}</title>', t, count=1, flags=re.S)
        desc_tag = f'<meta name="description" content="{esc(meta["desc"])}">'
        t = re.sub(r'<meta name="description" content="[^"]*">', lambda m: desc_tag, t, count=1)
        url = BASE + (meta.get('canonical') or ('' if name == 'index.html' else name))
        robots = 'noindex, follow' if meta.get('noindex') else 'index, follow, max-image-preview:large'
        graph = [org_node()]
        if name == 'index.html':
            graph.append({'@type': 'WebSite', '@id': BASE + '#website', 'url': BASE, 'name': 'Fandaqah', 'inLanguage': ['en', 'ar'], 'publisher': {'@id': BASE + '#org'}})
            graph.append(software_node())
        else:
            graph.append(crumbs_node([('Home', BASE), (CRUMB_NAMES.get(name, meta['title'].split(' | ')[0]), url)]))
        if name in ('pricing.html', 'fandaqah-360.html'):
            graph.append(software_node())
        if name == 'features.html':
            graph.append({'@type': 'ItemList', 'name': 'Fandaqah modules', 'numberOfItems': len(FEATURES), 'itemListElement': [
                {'@type': 'ListItem', 'position': i + 1, 'name': f['en']['name'], 'url': BASE + f'features/{f["slug"]}.html'} for i, f in enumerate(FEATURES)]})
        if not meta.get('noindex'):
            qas = page_faqs(t)
            if qas:
                graph.append(faq_node(qas))
        t = t.replace(desc_tag, desc_tag + seo_block(url, meta['title'], meta['desc'], meta['og'], robots, graph), 1)
        if 'upgrade.css' not in t:
            t = t.replace('<link rel="stylesheet" href="assets/redesign/site.css">',
                          '<link rel="stylesheet" href="assets/redesign/site.css"><link rel="stylesheet" href="assets/redesign/upgrade.css">', 1)
        # module links: every features.html#module-X-Y now has its own page (data-ar-href is used by site.js in Arabic mode)
        t = re.sub(r'href="features\.html#module-(\d-\d)"',
                   lambda m: f'href="features/{code_to_slug[m.group(1)]}.html" data-ar-href="ar/features/{code_to_slug[m.group(1)]}.html"', t)
        # footer: official address and the real social profiles
        t = t.replace('Prince Turkey Street,<br> Aldughaither Village, Al Khobar', 'Prince Turki Street, Al Janubiyah,<br> Al Khobar 34611')
        t = t.replace('Prince Turkey Street', 'Prince Turki Street')
        t = re.sub(r'<div class="social-links">.*?</div>', lambda m: SOCIAL_HTML, t, count=1, flags=re.S)
        # lead dialog
        t = re.sub(r'<dialog id="demo-dialog".*?</dialog>', lambda m: dialog_html('en', ''), t, count=1, flags=re.S)
        # price cards: VAT-inclusive line and a package key for the dialog
        def card(m):
            kind, body = m.group(1), m.group(2)
            key = {'basic': 'basic', 'compliance': 'compliance', 'recommended': '360'}[kind]
            if 'vat-line' not in body:
                body = re.sub(r'(<p class="per-year">.*?</p>)', lambda x: x.group(1) + f'<p class="vat-line"><bdi>SAR {money(with_vat(PACKAGES[key]["price"]))}</bdi> <span>incl. 15% VAT</span></p>', body, count=1, flags=re.S)
            body = re.sub(r'<button class="button ([^"]*)" data-demo(?! data-package)', lambda x: f'<button class="button {x.group(1)}" data-demo data-package="{key}"', body)
            return f'<article class="price-card {kind}">{body}</article>'
        t = re.sub(r'<article class="price-card (basic|compliance|recommended)">(.*?)</article>', card, t, flags=re.S)
        if name == 'pricing.html' and 'id="compare"' not in t:
            i = t.index('id="packages"')
            j = t.index('</section>', i) + len('</section>')
            t = t[:j] + compare_html() + t[j:]
        if name == 'privacy.html':
            t = re.sub(r'<main id="main">.*?</main>', lambda m: PRIVACY_MAIN, t, count=1, flags=re.S)
        if name == 'terms.html':
            t = re.sub(r'<main id="main">.*?</main>', lambda m: TERMS_MAIN, t, count=1, flags=re.S)
        if name == 'blog.html':
            for title, (href, text, alt) in BLOG_LINKS.items():
                t = re.sub(r'(<img src="[^"]+" alt=")([^"]*)(" loading="lazy"><span class="eyebrow">FANDAQAH JOURNAL</span><h2>' + re.escape(title) + r'</h2><p>.*?</p><a class="text-link" href=")[^"]*("[^>]*>)[^<]*',
                           lambda m: m.group(1) + (alt or m.group(2)) + m.group(3) + href + m.group(4) + text + ' ', t, count=1, flags=re.S)
        write(p, t)
        if name == 'pricing.html':
            pricing_faqs = page_faqs(t)
    return pricing_faqs


# ------------------------------------------------------------------------------------- feature pages
CHROME_AR = [
    ('aria-label="Fandaqah home"', 'aria-label="فندقة، الصفحة الرئيسية"'), ('alt="فندقة Fandaqah"', 'alt="فندقة"'),
    ('aria-label="Main navigation"', 'aria-label="القائمة الرئيسية"'), ('>About Us<', '>من نحن<'),
    ('>Features <span class="chevron">', '>المميزات <span class="chevron">'), ('>Packages<', '>الباقات<'), ('>Blogs<', '>المدونة<'),
    ('>Contact Us<', '>تواصل معنا<'), ('>Refer & Earn<', '>شركاء النجاح<'), ('>Login<', '>دخول<'),
    ('<button class="language" aria-label="Switch to Arabic">', '<button class="language" aria-label="Switch to English" lang="en">'),
    ('<span>Ar</span></button>', '<span>EN</span></button>'),
    ('>Free Trial <', '>تجربة مجانية <'), ('aria-label="Open navigation"', 'aria-label="فتح القائمة"'),
    ('<h2>Explore <span>Fandaqah 360</span></h2>', '<h2>استكشف <span>فندقة 360</span></h2>'),
    ('One platform for every hotel department.', 'منصة واحدة لكل أقسام الفندق.'),
    ('placeholder="Search all features…" aria-label="Search menu features"', 'placeholder="ابحث في جميع المميزات…" aria-label="ابحث في مميزات القائمة"'),
    ('>Dashboard<', '>لوحة التحكم<'), ('>Booking Management<', '>إدارة الحجوزات<'), ('>Reports<', '>التقارير<'),
    ('>Guest Reviews<', '>تقييمات النزلاء<'), ('>Security & Protection<', '>الأمان والحماية<'),
    ('>Integrations & Ecosystem<', '>التكاملات والمنظومة<'), ('>Compare Packages<', '>قارن الباقات<'),
    ('<b>Fandaqah <span>360</span></b>', '<b>فندقة <span>360</span></b>'), ('The Complete Hospitality Upgrade', 'الترقية المتكاملة لإدارة الضيافة'),
    ('>View All Features <', '>استكشف جميع المميزات <'), ('>Book a Demo <', '>احجز عرضاً توضيحياً <'),
    ('aria-label="Mobile navigation"', 'aria-label="قائمة الجوال"'), ('>Home<', '>الرئيسية<'), ('>Features<', '>المميزات<'),
    ('>Fandaqah 360<', '>فندقة 360<'),
    ('Your digital interface in the world<br> of hospitality and tourism', 'واجهتك الرقمية في عالم<br> الضيافة والسياحة'),
    ('© 2026 Fandaqah. All Rights Reserved.', '© 2026 فندقة. جميع الحقوق محفوظة.'), ('<h3>Quick Links</h3>', '<h3>روابط سريعة</h3>'),
    ('>About us<', '>من نحن<'), ('>Privacy Policy<', '>سياسة الخصوصية<'), ('>Terms And Conditions<', '>الشروط والأحكام<'),
    ('<h3>Contact Info</h3>', '<h3>معلومات التواصل</h3>'),
    ('Prince Turki Street, Al Janubiyah,<br> Al Khobar 34611', 'شارع الأمير تركي، الخبر الجنوبية،<br> الخبر 34611'),
    (f'<a href="tel:{PHONE}">{PHONE_TXT}</a>', f'<a href="tel:{PHONE}" dir="ltr">{PHONE_TXT}</a>'),
    ('<h3>Join Our Newsletter</h3>', '<h3>اشترك في نشرتنا</h3>'),
    ('Sign up for our newsletter to enjoy free<br> marketing tips', 'اشترك في نشرتنا البريدية لتصلك<br> نصائح تسويقية مجانية'),
    ('>Email address<', '>البريد الإلكتروني<'), ('placeholder="Email Address"', 'placeholder="البريد الإلكتروني"'),
    ('>Sign Me Up<', '>اشترك الآن<'), ('aria-label="Contact Fandaqah support"', 'aria-label="تواصل مع دعم فندقة"'),
] + [(f'aria-label="Fandaqah on {n}"', f'aria-label="فندقة على {n}"') for n, _, _ in SOCIAL]

TXT = {
    'en': {'home': 'Home', 'features': 'Features', 'demo': 'Book a demo', 'packages': 'See packages', 'compare': 'Compare packages',
           'sample': 'Illustrative sample data', 'clock': 'Hotel time', 'jump': 'Jump to an hour of the day', 'report': 'Day report',
           'day_h': 'A day with {s}', 'day_p': 'Hour by hour, the board follows the day as you read.',
           'covers_h': 'What {s} covers', 'incl': 'Included in', 'reach_h': 'Works together with', 'all': 'All 20 features',
           'all_p': 'Four departments, one platform.', 'faq_h': 'Questions about {s}',
           'night_h': '23:59. <span>The day is closed.</span>', 'night_p': 'Tomorrow starts at 06:00. Book a demo and see your property’s day on Fandaqah.',
           'logged': 'Logged while you were here', 'basic_note': 'The Basic Subscription includes PMS Core plus five modules, confirmed during your demo.',
           'pkg_name': {'basic': 'Basic', 'compliance': 'Compliance', '360': 'Fandaqah 360'}},
    'ar': {'home': 'الرئيسية', 'features': 'المميزات', 'demo': 'احجز عرضاً توضيحياً', 'packages': 'اطّلع على الباقات', 'compare': 'قارن الباقات',
           'sample': 'بيانات توضيحية', 'clock': 'توقيت الفندق', 'jump': 'انتقل إلى ساعة من اليوم', 'report': 'تقرير اليوم',
           'day_h': 'يوم مع {s}', 'day_p': 'ساعة بساعة، تتابع اللوحة اليوم أثناء قراءتك.',
           'covers_h': 'ما تشمله {s}', 'incl': 'مشمول في', 'reach_h': 'يعمل جنباً إلى جنب مع', 'all': 'جميع المميزات العشرين',
           'all_p': 'أربعة أقسام، منصة واحدة.', 'faq_h': 'أسئلة حول {s}',
           'night_h': '23:59. <span>انتهى اليوم.</span>', 'night_p': 'يبدأ الغد عند السادسة صباحاً. احجز عرضاً توضيحياً لترى يوم منشأتك على فندقة.',
           'logged': 'سُجّل أثناء وجودك', 'basic_note': 'تشمل الباقة الأساسية نظام إدارة الفندق وخمس وحدات تُحدد خلال العرض التوضيحي.',
           'pkg_name': {'basic': 'الأساسية', 'compliance': 'الامتثال', '360': 'فندقة 360'}},
}


def pkg_faq(f, lang):
    n = f[lang]['name']
    k = f['pkg']
    if lang == 'en':
        if k == 'all':
            return (f'Is {n} included in every package?', f'Yes. {n} is part of the Basic Subscription, the Compliance Package and Fandaqah 360.')
        if k == 'compliance':
            return (f'Which packages include {n}?', f'{n} is included in the Compliance Package (SAR 3,200 a year plus VAT) and in Fandaqah 360 (SAR 3,495 a year plus VAT).')
        return (f'Which package includes {n}?', f'{n} is one of the 20 modules in Fandaqah 360 (SAR 3,495 a year plus VAT). The Basic Subscription includes PMS Core plus five modules, confirmed during your demo.')
    if k == 'all':
        return (f'هل {n} مشمول في جميع الباقات؟', f'نعم. {n} جزء من الاشتراك الأساسي وباقة الامتثال وفندقة 360.')
    if k == 'compliance':
        return (f'ما الباقات التي تشمل {n}؟', f'{n} مشمول في باقة الامتثال (3,200 ريال سنوياً غير شاملة الضريبة) وفي فندقة 360 (3,495 ريالاً سنوياً غير شاملة الضريبة).')
    return (f'ما الباقة التي تشمل {n}؟', f'{n} إحدى الوحدات العشرين في فندقة 360 (3,495 ريالاً سنوياً غير شاملة الضريبة). ويشمل الاشتراك الأساسي نظام إدارة الفندق وخمس وحدات تُحدد خلال العرض التوضيحي.')


def pkg_line(f, lang):
    n = f[lang]['name']
    k = f['pkg']
    p360, pc, pb = PACKAGES['360']['price'], PACKAGES['compliance']['price'], PACKAGES['basic']['price']
    if lang == 'en':
        if k == 'all':
            return f'{esc(n)} is part of every package, from <bdi>SAR {money(pb)}</bdi> a year plus VAT.'
        if k == 'compliance':
            return f'{esc(n)} comes with the Compliance Package (<bdi>SAR {money(pc)}</bdi> a year plus VAT) and with Fandaqah 360 (<bdi>SAR {money(p360)}</bdi>).'
        return f'{esc(n)} is part of Fandaqah 360, with all 20 modules: <bdi>SAR {money(p360)}</bdi> a year plus VAT (<bdi>SAR {money(with_vat(p360))}</bdi> including VAT).'
    if k == 'all':
        return f'{esc(n)} ضمن جميع الباقات، ابتداءً من <bdi>{money(pb)}</bdi> ريال سنوياً غير شاملة الضريبة.'
    if k == 'compliance':
        return f'{esc(n)} ضمن باقة الامتثال (<bdi>{money(pc)}</bdi> ريال سنوياً غير شاملة الضريبة) وضمن فندقة 360 (<bdi>{money(p360)}</bdi> ريال).'
    return f'{esc(n)} جزء من فندقة 360 بوحداتها العشرين: <bdi>{money(p360)}</bdi> ريال سنوياً غير شاملة الضريبة (<bdi>{money(with_vat(p360))}</bdi> ريال شاملة ضريبة القيمة المضافة).'


def module_icons():
    """Each module's icon, taken from the features hub so the pages match the site's icon set."""
    t = read(L / 'features.html')
    icons = {}
    for code, svg in re.findall(r'id="module-(\d-\d)"><span class="icon-tile">(<svg.*?</svg>)</span>', t, re.S):
        icons[code] = svg
    return icons


def board(f, lang, live):
    P = f['panel']
    li = 0 if lang == 'en' else 1
    counts = ''.join(f'<li data-count="{k}"><b>{sum(1 for r in P["rows"] if r[2] == k)}</b><span>{esc(P["states"][k][li])}</span></li>' for k in P['count'])
    rows = ''.join(f'<li data-row="{i}" data-state="{r[2]}" class="tone-{P["states"][r[2]][2]}"><span>{esc(r[li])}</span><em>{esc(P["states"][r[2]][li])}</em></li>'
                   for i, r in enumerate(P['rows']))
    return (f'<div class="fx-board{" fx-board--live" if live else ""}" aria-hidden="true" data-nosnippet>'
            f'<div class="fx-board-top"><span class="fx-board-title">{esc(P["title"][li])}</span><time class="fx-board-time" dir="ltr">06:00</time></div>'
            f'<ul class="fx-board-counts">{counts}</ul><ul class="fx-board-rows">{rows}</ul>'
            f'<p class="fx-board-foot"><span class="fx-board-tag">{TXT[lang]["sample"]}</span><span class="fx-board-log"></span></p></div>')


def relink(s, prefix, lang):
    # module links point at sibling pages; everything else is prefixed back to landing/
    s = re.sub(r'href="features/([\w-]+)\.html" data-ar-href="ar/features/\1\.html"', r'href="\1.html"', s)

    def fix(m):
        attr, url = m.group(1), m.group(2)
        if re.match(r'(https?:|mailto:|tel:|data:|#|javascript:)', url) or re.match(r'[\w-]+\.html$', url) and attr == 'href' and url[:-5] in BY_SLUG:
            return m.group(0)
        if lang == 'ar' and attr == 'href' and '.html' in url:
            path, _, frag = url.partition('#')
            url = path + ('&' if '?' in path else '?') + 'lang=ar' + ('#' + frag if frag else '')
        return f'{attr}="{prefix}{url}"'
    return re.sub(r'\b(href|src)="([^"]+)"', fix, s)


def chrome(lang, prefix, icons):
    t = read(L / 'index.html')
    header = t[t.index('<header class="site-header">'):t.index('<div class="menu-backdrop" hidden></div>') + len('<div class="menu-backdrop" hidden></div>')]
    footer = t[t.index('<footer class="site-footer">'):t.index('</footer>') + len('</footer>')]
    s0 = t.index('<button class="support-float"')
    support = t[s0:t.index('</button>', s0) + len('</button>')]
    header = header.replace('class="nav-feature "', 'class="nav-feature current"')
    if lang == 'ar':
        for d in DEPTS:
            header = header.replace(f'<span>{d["en"]}<small lang="ar">{d["ar"]}</small></span>', f'<span>{d["ar"]}</span>')
        def item(m):
            f = BY_SLUG[m.group(1)]
            return m.group(0)[:m.group(0).index('<strong>')] + f'<strong>{esc(f["ar"]["name"])}</strong><p>{esc(f["ar"]["card"])}</p>'
        header = re.sub(r'href="features/([\w-]+)\.html"[^>]*>.*?<strong>.*?</strong><p>.*?</p>', item, header, flags=re.S)
        blob = header + '\u0000' + footer + '\u0000' + support
        for en, ar in CHROME_AR:
            blob = blob.replace(en, ar)
        header, footer, support = blob.split('\u0000')
    return relink(header, prefix, lang), relink(footer, prefix, lang), relink(support, prefix, lang)


def feature_page(f, lang, icons):
    en, d, T = f['en'], f[lang], TXT[lang]
    prefix = '../' if lang == 'en' else '../../'
    q = '' if lang == 'en' else '?lang=ar'
    slug = f['slug']
    url_en, url_ar = BASE + f'features/{slug}.html', BASE + f'ar/features/{slug}.html'
    url = url_en if lang == 'en' else url_ar
    alt_rel = f'../ar/features/{slug}.html' if lang == 'en' else f'../../features/{slug}.html'
    short = d.get('short', d['name'])
    photo = PHOTOS[f['photo']]
    header, footer, support = chrome(lang, prefix, icons)
    steps = [dict(st, t=en['steps'][i]['t']) for i, st in enumerate(d['steps'])]
    peak = next(i for i, st in enumerate(en['steps']) if st.get('peak'))
    faqs = list(d['faq']) + [pkg_faq(f, lang)]

    # JSON-LD
    graph = [org_node(),
             crumbs_node([(T['home'], BASE + ('' if lang == 'en' else 'index.html?lang=ar')), (T['features'], BASE + 'features.html' + q), (d['name'], url)]),
             faq_node(faqs),
             {'@type': 'WebPage', '@id': url + '#page', 'url': url, 'name': d['title'].split(' | ')[0], 'description': d['desc'], 'inLanguage': lang,
              'isPartOf': {'@type': 'WebSite', '@id': BASE + '#website', 'url': BASE, 'name': 'Fandaqah'},
              'primaryImageOfPage': BASE + f'assets/og/{slug}.jpg', 'publisher': {'@id': BASE + '#org'},
              'about': {'@type': 'SoftwareApplication', 'name': 'Fandaqah', 'applicationCategory': 'BusinessApplication', 'operatingSystem': 'Web browser',
                        'featureList': d['covers'],
                        'offers': {'@type': 'AggregateOffer', 'priceCurrency': 'SAR', 'lowPrice': str(min(PACKAGES[k]['price'] for k in PKG_SETS[f['pkg']])),
                                   'highPrice': str(PACKAGES['360']['price']), 'offerCount': str(len(PKG_SETS[f['pkg']]))}}}]

    data = {'lang': lang, 'times': [st['t'] for st in en['steps']], 'peak': peak,
            'states': {k: {'label': v[0 if lang == 'en' else 1], 'tone': v[2]} for k, v in f['panel']['states'].items()},
            'count': f['panel']['count'], 'rows': [r[2] for r in f['panel']['rows']], 'steps': f['panel']['steps'],
            'logs': [st['log'] for st in steps]}

    hours = ''
    for i, st in enumerate(steps):
        hid = 'h-' + st['t'].replace(':', '')
        extra = f'<p class="fx-extra">{esc(st["extra"])}</p>' if st.get('extra') else ''
        hours += (f'<li class="fx-hour{" is-peak" if i == peak else ""}" id="{hid}" data-step="{i}">'
                  f'<p class="fx-time"><time datetime="{st["t"]}" dir="ltr">{st["t"]}</time></p>'
                  f'<h3>{esc(st["h"])}</h3><p>{esc(st["p"])}</p>{extra}</li>')
    menu = ''.join(f'<a href="#h-{st["t"].replace(":", "")}"><time dir="ltr">{st["t"]}</time><span>{esc(st["h"])}</span></a>' for st in steps)
    menu += f'<a href="#report"><time dir="ltr">23:59</time><span>{T["report"]}</span></a>'
    covers = ''.join(f'<li>{esc(c)}</li>' for c in d['covers'])
    incl = ''.join(f'<a class="fx-chip" href="{prefix}pricing.html{q}#packages">{T["pkg_name"][k]}</a>' for k in PKG_SETS[f['pkg']])
    note = f'<p class="fx-incl-note">{T["basic_note"]}</p>' if f['pkg'] == '360' else ''
    nodes = ''
    for rs in f['related']:
        r = BY_SLUG[rs]
        nodes += (f'<a class="fx-node" href="{rs}.html"><span class="icon-tile">{icons[r["code"]]}</span>'
                  f'<strong>{esc(r[lang]["name"])}</strong><span>{esc(r[lang]["card"])}</span></a>')
    nodes += (f'<a class="fx-node fx-node--all" href="{prefix}features.html{q}"><strong>{T["all"]}</strong><span>{T["all_p"]}</span></a>')
    qa = ''.join(f'<details><summary>{esc(qq)}<span aria-hidden="true">+</span></summary><p>{esc(aa)}</p></details>' for qq, aa in faqs)
    report = ''.join(f'<li data-step="{i}"><time dir="ltr">{st["t"]}</time><span>{esc(st["log"])}</span></li>' for i, st in enumerate(steps))

    head = (f'<!doctype html>\n<html lang="{lang}" dir="{"ltr" if lang == "en" else "rtl"}" data-lang-alt="{alt_rel}"><head><meta charset="utf-8">'
            '<meta name="viewport" content="width=device-width, initial-scale=1">'
            f'<title>{esc(d["title"])}</title><meta name="description" content="{esc(d["desc"])}"><meta name="theme-color" content="#f2f0eb">'
            + seo_block(url, d['title'], d['desc'], slug, 'index, follow, max-image-preview:large', graph,
                        extra=f'<link rel="alternate" hreflang="en" href="{url_en}"><link rel="alternate" hreflang="ar" href="{url_ar}"><link rel="alternate" hreflang="x-default" href="{url_en}">'
                        + (f'<meta property="og:locale" content="{"en_US" if lang == "en" else "ar_SA"}">'))
            + f'<link rel="icon" href="{prefix}favicon.ico">'
            f'<link rel="preload" as="image" href="{prefix}{photo["src"]}" fetchpriority="high">'
            f'<link rel="stylesheet" href="{prefix}assets/scrollcraft/scrollcraft.css">'
            f'<link rel="stylesheet" href="{prefix}assets/redesign/site.css"><link rel="stylesheet" href="{prefix}assets/redesign/upgrade.css">'
            f'<link rel="stylesheet" href="{prefix}assets/redesign/feature.css">'
            '<script>document.documentElement.classList.add("sc-js")</script>'
            f'<script src="{prefix}assets/redesign/site.js" defer></script><script src="{prefix}assets/scrollcraft/scrollcraft.js" defer></script>'
            f'<script src="{prefix}assets/redesign/feature.js" defer></script></head>')

    body = (f'<body class="fx" data-dept="{f["dept"]}"><a class="skip-link" href="#main">{"Skip to content" if lang == "en" else "تخطَّ إلى المحتوى"}</a>\n{header}\n'
            '<main id="main">'
            # dawn: the layered hero
            f'<section class="fx-hero" data-sc-act="pin" data-sc-span="1.5" aria-labelledby="fx-title"><div class="fx-stage" data-sc-stage>'
            f'<div class="fx-scene"><div class="fx-arch" style="--pos:{photo["pos"]}"><div class="fx-window">'
            f'<img src="{prefix}{photo["src"]}" alt="{esc(photo[lang])}" width="{photo["w"]}" height="{photo["h"]}" fetchpriority="high" decoding="async">'
            '<span class="fx-beam" aria-hidden="true"></span></div><span class="fx-sill" aria-hidden="true"></span></div>'
            f'<span class="fx-spill" aria-hidden="true"></span><div class="fx-board-wrap">{board(f, lang, False)}</div></div>'
            '<div class="fx-copy">'
            f'<nav class="fx-crumbs" aria-label="{"Breadcrumb" if lang == "en" else "مسار التنقل"}"><ol><li><a href="{prefix}index.html{q}">{T["home"]}</a></li>'
            f'<li><a href="{prefix}features.html{q}">{T["features"]}</a></li><li aria-current="page">{esc(short)}</li></ol></nav>'
            f'<h1 id="fx-title">{esc(d["h1"])}</h1><p class="fx-lead">{esc(d["lead"])}</p>'
            f'<div class="fx-cta"><button class="button primary" type="button" data-demo data-intent="{esc(en["name"])} demo">{T["demo"]}</button>'
            f'<a class="fx-link" href="{prefix}pricing.html{q}#packages">{T["packages"]}</a></div></div></div></section>'
            # pressure: authored quiet
            f'<section class="fx-pressure"><div class="fx-wrap"><h2 data-sc-in>{esc(d["ph"])}</h2><p data-sc-in>{esc(d["pp"])}</p></div></section>'
            # the hours, with the held board
            f'<section class="fx-day" id="day" aria-labelledby="day-title"><div class="fx-wrap fx-day-grid">'
            f'<header class="fx-day-head"><h2 id="day-title">{esc(T["day_h"].format(s=short))}</h2><p>{T["day_p"]}</p></header>'
            f'<ol class="fx-hours">{hours}</ol><div class="fx-surface">{board(f, lang, True)}</div></div></section>'
            # coverage
            f'<section class="fx-covers" aria-labelledby="cov-title"><div class="fx-wrap fx-covers-grid"><h2 id="cov-title">{esc(T["covers_h"].format(s=short))}</h2>'
            f'<ul class="fx-list" data-sc-in data-sc-stagger="45">{covers}</ul>'
            f'<div class="fx-incl"><p>{T["incl"]}</p><div class="fx-chips">{incl}</div>{note}<a class="fx-link" href="{prefix}pricing.html{q}#compare">{T["compare"]}</a></div>'
            '</div></section>'
            # reach: the lateral rail
            f'<section class="fx-reach" data-sc-act="pin" data-sc-span="1.45" aria-labelledby="reach-title"><div class="fx-reach-stage" data-sc-stage>'
            f'<div class="fx-wrap"><h2 id="reach-title">{T["reach_h"]}</h2></div><div class="fx-rail-clip"><div class="fx-rail">{nodes}</div></div></div></section>'
            # questions
            f'<section class="fx-faq" aria-labelledby="faq-title"><div class="fx-wrap fx-faq-grid"><h2 id="faq-title">{esc(T["faq_h"].format(s=short))}</h2>'
            f'<div class="fx-qa">{qa}</div></div></section>'
            # night: the day's report
            f'<section class="fx-night" id="report" aria-labelledby="night-title"><div class="fx-wrap fx-night-grid">'
            f'<div class="fx-report"><p class="fx-report-head"><span>{esc(short)}</span><span>{T["report"]}</span></p><ol class="fx-report-lines">{report}</ol>'
            f'<p class="fx-report-total">{pkg_line(f, lang)}</p><p class="fx-report-key"><i aria-hidden="true"></i>{T["logged"]}</p></div>'
            f'<div class="fx-night-copy"><h2 id="night-title">{T["night_h"]}</h2><p>{T["night_p"]}</p>'
            f'<div class="fx-cta"><button class="button primary" type="button" data-demo data-intent="{esc(en["name"])} demo">{T["demo"]}</button>'
            f'<a class="fx-link" href="{prefix}pricing.html{q}#compare">{T["compare"]}</a></div></div></div></section>'
            '</main>\n'
            # the hotel clock: the page's map and its signature
            f'<div class="fx-clock" data-nosnippet><button class="fx-clock-btn" type="button" aria-expanded="false" aria-controls="fx-clock-menu">'
            '<svg class="fx-clock-face" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path class="fx-hand-h" d="M12 12V7.5"/><path class="fx-hand-m" d="M12 12V4.6"/></svg>'
            f'<span class="fx-clock-time" dir="ltr">06:00</span><span class="fx-clock-label">{T["clock"]}</span><span class="sr-only">. {T["jump"]}</span></button>'
            f'<nav class="fx-clock-menu" id="fx-clock-menu" aria-label="{T["jump"]}" hidden>{menu}</nav></div>'
            f'<script type="application/json" id="fx-data">{json.dumps(data, ensure_ascii=False)}</script>\n'
            f'{footer}{support}{dialog_html(lang, prefix)}</body></html>\n')
    return head + body


def redirect(target, canonical):
    return ('<!doctype html>\n<html lang="en"><head><meta charset="utf-8"><title>Fandaqah features</title>'
            f'<meta name="robots" content="noindex, follow"><link rel="canonical" href="{canonical}">'
            f'<meta http-equiv="refresh" content="0; url={target}"></head><body><a href="{target}">All Fandaqah features</a></body></html>\n')


def sitemap():
    urls = []
    for name, meta in PAGES.items():
        if not meta.get('noindex'):
            urls.append(f'  <url><loc>{BASE}{"" if name == "index.html" else name}</loc><changefreq>monthly</changefreq><priority>{"1.0" if name == "index.html" else "0.8"}</priority></url>')
    for f in FEATURES:
        en, ar = BASE + f'features/{f["slug"]}.html', BASE + f'ar/features/{f["slug"]}.html'
        alts = (f'<xhtml:link rel="alternate" hreflang="en" href="{en}"/><xhtml:link rel="alternate" hreflang="ar" href="{ar}"/>'
                f'<xhtml:link rel="alternate" hreflang="x-default" href="{en}"/>')
        for u in (en, ar):
            urls.append(f'  <url><loc>{u}</loc>{alts}<changefreq>monthly</changefreq><priority>0.7</priority></url>')
    return ('<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">\n'
            + '\n'.join(urls) + '\n</urlset>\n')


OG_PAGES = {  # share-image text for the hand-made pages: (kicker, English title, Arabic title, photo)
    'home': ('Hotel management software', 'Smart hotel management for Saudi hotels', 'إدارة فندقية ذكية للفنادق السعودية', 'reception'),
    'features': ('All features', 'Twenty modules. One connected platform.', 'عشرون وحدة في منصة واحدة', 'lobby'),
    'fandaqah-360': ('Fandaqah 360', 'The complete hospitality upgrade', 'الترقية المتكاملة لإدارة الضيافة', 'resort'),
    'pricing': ('Packages', 'Packages from SAR 1,200 a year', 'باقات تبدأ من 1,200 ريال سنوياً', 'reception'),
    'about': ('About Fandaqah', 'Your hospitality. Our priority.', 'ضيافتك. أولويتنا.', 'team'),
    'blog': ('Hospitality Journal', 'Fresh perspectives. Better operations.', 'رؤى جديدة لعمليات أفضل', 'terrace'),
    'contact': ('Contact', 'Let’s build a better stay.', 'لنصنع معاً إقامة أفضل.', 'albalad'),
}


def og_data():
    """Text and photos for tools/og/studio.html; tools/render-landing-og.mjs renders them to landing/assets/og/."""
    items = [{'key': k, 'kicker': v[0], 'en': v[1], 'ar': v[2], 'sub': '', 'photo': PHOTOS[v[3]]['src'], 'pos': PHOTOS[v[3]]['pos']} for k, v in OG_PAGES.items()]
    for f in FEATURES:
        items.append({'key': f['slug'], 'kicker': DEPTS[f['dept']]['en'] + ' · ' + DEPTS[f['dept']]['ar'], 'en': f['en'].get('short', f['en']['name']),
                      'ar': f['ar'].get('short', f['ar']['name']), 'sub': f['en']['card'], 'photo': PHOTOS[f['photo']]['src'], 'pos': PHOTOS[f['photo']]['pos']})
    write(TOOLS / 'og' / 'og-data.json', json.dumps(items, ensure_ascii=False, indent=1))
    return len(items)


def main():
    validate()
    og_data()
    patch_pages()
    icons = module_icons()
    missing = [f['code'] for f in FEATURES if f['code'] not in icons]
    if missing:
        sys.exit(f'icons missing for {missing}')
    for f in FEATURES:
        write(L / 'features' / f'{f["slug"]}.html', feature_page(f, 'en', icons))
        write(L / 'ar' / 'features' / f'{f["slug"]}.html', feature_page(f, 'ar', icons))
    write(L / 'features' / 'index.html', redirect('../features.html', BASE + 'features.html'))
    write(L / 'ar' / 'features' / 'index.html', redirect('../../features.html?lang=ar', BASE + 'features.html'))
    write(L / 'ar' / 'index.html', redirect('../index.html?lang=ar', BASE))
    write(L / 'sitemap.xml', sitemap())
    print(f'patched {len(PAGES)} pages, wrote {len(FEATURES) * 2} feature pages, 3 redirects and sitemap.xml ({len(PAGES) - 2 + len(FEATURES) * 2} URLs)')


if __name__ == '__main__':
    main()
