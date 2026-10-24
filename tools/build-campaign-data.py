"""Write each campaign's links.csv (tagged links) and search-ads.csv (with measured character counts).

Usage:  python -I tools/build-campaign-data.py
Edit CHANNELS / SEARCH below, re-run, commit. Exits 1 if any Search ad line is over Google's limits.
"""
import csv, sys, pathlib
from urllib.parse import urlencode

sys.stdout.reconfigure(encoding='utf-8')
ROOT = pathlib.Path(__file__).resolve().parent.parent
BASE = 'https://usamakhanksa.github.io/fandaqah-360/'
FOLDERS = {'A': 'A-existing-customers', 'B': 'B-new-hotels', 'C': 'C-opera-pms', 'D': 'D-hotel-groups'}
NAMES = {'A': 'A_existing_upgrade_2610', 'B': 'B_new_hotels_2610', 'C': 'C_opera_cost_review_2610', 'D': 'D_group_discovery_2610'}

# (channel label, utm_source, utm_medium, utm_content, language) per campaign, following the playbook's channel plan
CHANNELS = {
    'A': [('Account manager message', 'account_manager', 'direct_outreach', 'am_message', 'ar'),
          ('Permissioned email 1', 'email', 'email', 'email_1', 'ar'), ('Permissioned email 1 (English)', 'email', 'email', 'email_1', 'en'),
          ('Permissioned WhatsApp', 'whatsapp', 'whatsapp', 'wa_template_1', 'ar'),
          ('Meta remarketing, ad v1', 'meta', 'remarketing', 'ad_v1_ar', 'ar'), ('Meta remarketing, ad v2', 'meta', 'remarketing', 'ad_v2_ar', 'ar'),
          ('Meta remarketing, ad v1 (English)', 'meta', 'remarketing', 'ad_v1_en', 'en')],
    'B': [('Google Search, Arabic ad group', 'google', 'paid_search', 'rsa_ar', 'ar'), ('Google Search, English ad group', 'google', 'paid_search', 'rsa_en', 'en'),
          ('Meta reel', 'meta', 'paid_social', 'reel_ar', 'ar'), ('Meta feed, ad v1', 'meta', 'paid_social', 'ad_v1_ar', 'ar'),
          ('Meta feed, ad v2', 'meta', 'paid_social', 'ad_v2_ar', 'ar'), ('Meta carousel', 'meta', 'paid_social', 'carousel_ar', 'ar'),
          ('Permitted email follow-up', 'email', 'email', 'email_1', 'ar')],
    'C': [('Google Search, English ad group', 'google', 'paid_search', 'rsa_en', 'en'), ('Google Search, Arabic ad group', 'google', 'paid_search', 'rsa_ar', 'ar'),
          ('LinkedIn sponsored content, ad v1', 'linkedin', 'paid_social', 'ad_v1_en', 'en'), ('LinkedIn sponsored content, ad v2', 'linkedin', 'paid_social', 'ad_v2_en', 'en'),
          ('Named-account email (IT)', 'email', 'direct_outreach', 'it_brief', 'en'), ('Named-account email (finance)', 'email', 'direct_outreach', 'finance_brief', 'en'),
          ('Sales LinkedIn message', 'linkedin', 'direct_outreach', 'sales_message', 'ar')],
    'D': [('Personalised account outreach', 'email', 'direct_outreach', 'account_letter', 'ar'), ('Personalised account outreach (English)', 'email', 'direct_outreach', 'account_letter', 'en'),
          ('LinkedIn sponsored content, ad v1', 'linkedin', 'paid_social', 'ad_v1_en', 'en'), ('LinkedIn sponsored content, ad v2', 'linkedin', 'paid_social', 'ad_v2_ar', 'ar'),
          ('LinkedIn carousel', 'linkedin', 'paid_social', 'carousel_en', 'en'), ('Group demo invitation', 'email', 'email', 'workshop_invite', 'ar')],
}

# Responsive search ads: Google limits headlines to 30 and descriptions to 90 characters
SEARCH = {
    'B': {
        'en': {'headline': ['Hotel PMS for Saudi Arabia', 'One Connected Hotel Platform', 'Book Your Fandaqah 360 Demo', 'Built for Saudi Hospitality',
                            'Reservations to Accounting', 'Housekeeping, POS and More', 'Shomoos and ZATCA Workflows', 'Ask About Our 2-Year Offer'],
               'description': ['Bring reservations, accounting, POS, inventory, HR and housekeeping into one platform.',
                               'Book a demo tailored to your property. Migration, training and support scoped upfront.',
                               'Built for Saudi hospitality and supported regulatory workflows.',
                               'Ask about our two-year prepaid offer. Package terms confirmed before you sign.']},
        'ar': {'headline': ['نظام إدارة فنادق في السعودية', 'منصة فندقية واحدة مترابطة', 'احجز عرضاً توضيحياً لفندقك', 'مصمَّمة لقطاع الضيافة السعودي',
                            'من الحجز حتى المحاسبة', 'تكامل شموس وهيئة الزكاة', 'اسأل عن عرض السنتين'],
               'description': ['اجمع الحجوزات والمحاسبة ونقاط البيع والمخزون والتدبير الفندقي في منصة واحدة.',
                               'احجز عرضاً توضيحياً مصمَّماً لمنشأتك، مع تقييم لنقل البيانات والتدريب والدعم.',
                               'مصمَّمة لقطاع الضيافة السعودي ومسارات العمل التنظيمية المدعومة.',
                               'اسأل عن عرضنا المدفوع مسبقاً لمدة سنتين. نؤكّد الشروط قبل التوقيع.']},
    },
    'C': {
        'en': {'headline': ['Opera Back Office Integration', 'Strengthen Your Opera PMS', 'Hotel Technology Cost Review', 'Connected Hotel Operations',
                            'Keep Your Existing PMS', 'Request a Cost Review', 'Built for Saudi Hospitality'],
               'description': ['Explore supported back-office, operations and regulatory workflows around your PMS.',
                               'We review costs, interfaces and approvals before a scoped proposal.',
                               'See where your hotel can reduce technology costs. Like-for-like scope comparison.',
                               'Compatibility and delivery are confirmed after technical assessment.']},
        'ar': {'headline': ['تكامل الإدارة الخلفية مع Opera', 'عزّز نظام Opera PMS لديك', 'مراجعة تكاليف تقنية الفندق', 'عمليات فندقية مترابطة',
                            'احتفظ بنظام PMS الحالي', 'اطلب مراجعة التكاليف'],
               'description': ['مسارات عمل مدعومة للإدارة الخلفية والتشغيل والإجراءات التنظيمية حول نظامك.',
                               'نراجع التكاليف والواجهات والاعتمادات قبل إعداد عرض سعر محدد النطاق.',
                               'اكتشف أين يمكن لفندقك خفض تكاليف التقنية بمقارنة دقيقة للنطاق.',
                               'يُؤكَّد التوافق والتسليم بعد التقييم الفني.']},
    },
}
LIMIT = {'headline': 30, 'description': 90}

over = 0
for code, rows in CHANNELS.items():
    out = ROOT / 'campaigns' / FOLDERS[code] / 'links.csv'
    out.parent.mkdir(parents=True, exist_ok=True)
    with out.open('w', newline='', encoding='utf-8-sig') as f:
        w = csv.writer(f)
        w.writerow(['campaign_code', 'channel', 'utm_campaign', 'utm_source', 'utm_medium', 'utm_content', 'language', 'url'])
        for label, src, med, content, lang in rows:
            q = {'utm_campaign': NAMES[code], 'utm_source': src, 'utm_medium': med, 'utm_content': content}
            if lang == 'ar':
                q['lang'] = 'ar'
            w.writerow([code, label, NAMES[code], src, med, content, lang, BASE + '?' + urlencode(q)])
    print(f'{out.relative_to(ROOT)}: {len(rows)} links')

for code, langs in SEARCH.items():
    out = ROOT / 'campaigns' / FOLDERS[code] / 'search-ads.csv'
    with out.open('w', newline='', encoding='utf-8-sig') as f:
        w = csv.writer(f)
        w.writerow(['language', 'asset', 'text', 'characters', 'limit', 'ok'])
        for lang, assets in langs.items():
            for kind, texts in assets.items():
                for t in texts:
                    ok = len(t) <= LIMIT[kind]
                    over += not ok
                    w.writerow([lang, kind, t, len(t), LIMIT[kind], 'yes' if ok else 'OVER'])
                    if not ok:
                        print(f'  OVER {code}/{lang} {kind} ({len(t)}>{LIMIT[kind]}): {t}')
    print(f'{out.relative_to(ROOT)}: written')
sys.exit(1 if over else 0)
