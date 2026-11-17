/* =====================================================================
   Fandaqah NTMP site configuration
   ---------------------------------------------------------------------
   This is the only file you need to edit. Everything on the page reads
   from here at load time: contact buttons, WhatsApp, socials, the lead
   form destination and the product demo video.
   Values were pre-filled from fandaqah.com on 17 Nov 2026.
   ===================================================================== */
window.FQ_CONFIG = {
  brand: "Fandaqah",

  /* "en", "ar", or "auto" (follows the visitor's browser language).
     Visitors can always switch with the EN | العربية toggle, and a
     link ending in ?lang=ar opens the Arabic version directly. */
  defaultLanguage: "auto",

  contact: {
    phone: "+966555947522",          // shown as a tap-to-call link
    whatsapp: "966555947522",        // international format, digits only
    email: "",                       // e.g. "sales@fandaqah.com" (optional)
    whatsappGreeting: {
      en: "Hello Fandaqah, I'd like to book a free NTMP readiness review.",
      ar: "مرحبًا فندقة، أرغب في حجز مراجعة مجانية لجاهزية الربط مع منصة الرصد السياحي."
    }
  },

  /* Browser-visible, lead-ingestion-only CRM configuration.
     This static page has no server-side secret store. Never use an admin key. */
  form: {
    endpoint: "https://crm.fandaqah.com/api/ingest/lead",
    headers: { "X-API-Key": "fdq_d0930f18b74a5153c68b174748b5ed7ba17b8723ca05c21e" },
    platform: "fandaqah_tourism_platform_contact",
    timeoutMs: 15000,
    redirectOnSuccess: ""
  },

  /* Product demo. Paste a YouTube link or an .mp4 URL to play your own
     recording. Leave empty to use the built-in animated walkthrough. */
  demoVideoUrl: "",

  social: {
    linkedin: "https://www.linkedin.com/company/fandaqah/",
    x: "https://twitter.com/fandaqah",
    instagram: "https://www.instagram.com/fandaqah.pms",
    youtube: "",
    facebook: "https://www.facebook.com/pmsfandaqah/"
  },

  links: {
    home: "https://fandaqah.com/",
    about: "https://fandaqah.com/store/about_us",
    contact: "https://fandaqah.com/store/contact_us",
    privacy: "https://fandaqah.com/store/policy",
    terms: "https://fandaqah.com/store/policy"
  }
};
