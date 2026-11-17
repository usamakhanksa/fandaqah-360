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

  /* Lead form delivery.
     - endpoint: any URL that accepts a JSON POST (your CRM webhook,
       Formspree, Make, Zapier, HubSpot forms proxy, or your own API).
     - Leave empty to run without a backend: the visitor still gets a
       confirmation, plus one-tap buttons to send the same details to
       your team on WhatsApp (and email, if set above). */
  form: {
    endpoint: "",
    headers: {},                     // e.g. { "Authorization": "Bearer ..." }
    redirectOnSuccess: ""            // optional thank-you page URL
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
