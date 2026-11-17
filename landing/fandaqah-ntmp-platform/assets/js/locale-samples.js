/* Sample product UI stays in the selected language, including animated scenes. */
(function () {
  "use strict";
  var dictionary = {
  "Sent. Waiting for confirmation": "تم الإرسال. بانتظار التأكيد",
  "Fandaqah PMS": "نظام فندقة",
  "Booking status list": "قائمة حالات الحجوزات",
  "Today": "اليوم",
  "Booking": "الحجز",
  "Guest": "النزيل",
  "Room": "الغرفة",
  "NTMP": "منصة الرصد",
  "Accepted": "مقبول",
  "Pending": "قيد الانتظار",
  "Rejected": "مرفوض",
  "NTMP logs": "سجل منصة الرصد",
  "Live": "مباشر",
  "Error": "خطأ",
  "Guest nationality is required": "جنسية النزيل مطلوبة",
  "Field: guest.nationality": "الحقل: جنسية النزيل",
  "Fix and retry": "تصحيح وإعادة المحاولة",
  "Occupancy report sent": "تم إرسال تقرير الإشغال",
  "Financial journal": "القيد المالي",
  "Approved": "معتمد",
  "17 Nov 2026": "١٧ نوفمبر ٢٠٢٦",
  "SAR": "ر.س.",
  "Account": "الحساب",
  "Debit": "مدين",
  "Credit": "دائن",
  "Room revenue": "إيرادات الغرف",
  "VAT output 15%": "ضريبة القيمة المضافة ١٥٪",
  "Guest ledger": "حساب النزيل",
  "Total": "الإجمالي",
  "A. Al-Harbi": "عبدالله الحربي",
  "M. Khan": "محمد خان",
  "S. Al-Qahtani": "سارة القحطاني",
  "L. Haddad": "ليلى حداد",
  "F. Al-Otaibi": "فهد العتيبي",
  "Front desk / New booking": "الاستقبال / حجز جديد",
  "NTMP / Pre-send check": "منصة الرصد / التحقق قبل الإرسال",
  "NTMP / Booking status": "منصة الرصد / حالة الحجز",
  "NTMP / Error details": "منصة الرصد / تفاصيل الخطأ",
  "Finance / Journal JV-1187": "المالية / القيد JV-1187",
  "Guest name": "اسم النزيل",
  "Nationality": "الجنسية",
  "Stay": "الإقامة",
  "Abdullah Al-Harbi": "عبدالله الحربي",
  "Saudi Arabia": "المملكة العربية السعودية",
  "17 – 19 Nov 2026": "١٧ - ١٩ نوفمبر ٢٠٢٦",
  "Save booking": "حفظ الحجز",
  "Booking RSV-24817 saved": "تم حفظ الحجز RSV-24817",
  "ID number": "رقم الهوية",
  "Room and dates": "الغرفة والتواريخ",
  "Ready to send": "جاهز للإرسال",
  "NTMP status": "حالة منصة الرصد",
  "Guest nationality is required. Field: guest.nationality": "جنسية النزيل مطلوبة. الحقل: جنسية النزيل",
  "Sara Al-Qahtani": "سارة القحطاني",
  "Approve entry": "اعتماد القيد",
  "Sending to NTMP…": "جارٍ الإرسال إلى منصة الرصد…",
  "Sending to NTMP": "جارٍ الإرسال إلى منصة الرصد",
  "Retried at 07:44. Confirmed by NTMP.": "أُعيد الإرسال الساعة ٠٧:٤٤ وتم التأكيد من منصة الرصد.",
  "200 OK": "٢٠٠ نجاح",
  "RSV-24817 sent": "تم إرسال RSV-24817",
  "RSV-24821 rejected": "رُفض RSV-24821",
  "RSV-24821 accepted": "قُبل RSV-24821",
  "RSV-24810 retried": "أُعيد إرسال RSV-24810",
  "Main": "التنقل الرئيسي",
  "Footer": "روابط الصفحة",
  "Menu": "القائمة",
  "Mobile": "قائمة الهاتف",
  "Language": "اللغة",
  "Form progress": "مراحل الطلب",
  "Why disconnected reporting hurts": "تحديات التقارير المنفصلة"
}, language = "en", originals = new WeakMap();
  var reverse = Object.keys(dictionary).reduce(function (out, key) { out[dictionary[key]] = key; return out; }, {});
  function text(value, lang) { return lang === "ar" ? (dictionary[value] || value) : (reverse[value] || value); }
  function localize(root) {
    var walk = document.createTreeWalker(root, NodeFilter.SHOW_TEXT), node;
    while ((node = walk.nextNode())) {
      var current = node.nodeValue, trim = current.trim(), record = originals.get(node);
      if (!record || (current !== record.en && current !== record.ar)) {
        var key = dictionary[trim] ? trim : reverse[trim];
        if (!key) continue;
        record = { en:current.replace(trim,key), ar:current.replace(trim,dictionary[key]) }; originals.set(node,record);
      }
      var target = record[language]; if (node.nodeValue !== target) node.nodeValue = target;
    }
    root.querySelectorAll('.dm-screen').forEach(function (el) { var dir = language === "ar" ? "rtl" : "ltr"; if (el.dir !== dir) el.dir = dir; });
  }
  function refresh() { document.querySelectorAll('.ui, #demoStage').forEach(localize); }
  function boot() {
    document.querySelectorAll('.ui, #demoStage').forEach(function (root) {
      new MutationObserver(function () { localize(root); }).observe(root, { childList:true, subtree:true, characterData:true });
    });
    refresh();
  }
  window.FQSamples = { text:text, setLang:function (lang) {
    language = lang; refresh();
    document.querySelectorAll('[aria-label]').forEach(function (el) {
      var value = el.getAttribute('aria-label'); el.setAttribute('aria-label', text(value,lang));
    });
  }};
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded',boot); else boot();
})();
