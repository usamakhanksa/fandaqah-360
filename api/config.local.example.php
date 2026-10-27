<?php
/* Copy to api/config.local.php on the server and fill in the real values. config.local.php is never committed (.gitignore)
   and never served (api/.htaccess). Environment variables of the same meaning win over these. */
return [
    'api_key'                 => '',   // FQ_CRM_KEY: the CRM ingest key (rotate it in the CRM, then paste the new one here)
    'linkedin_capi_token'     => '',   // LINKEDIN_CAPI_TOKEN
    'tiktok_pixel_id'         => '',   // TIKTOK_PIXEL_ID (also set in assets/js/fandaqah-tiktok.js)
    'tiktok_events_api_token' => '',   // TIKTOK_EVENTS_API_TOKEN
    'funnel_key'              => '',   // FQ_FUNNEL_KEY: opens the funnel report, api/funnel.php?key=…
];
