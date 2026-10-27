<?php
/* Settings for api/lead.php. An environment variable wins over the value written here, so on the production host the key can
   live outside the code: set FQ_CRM_KEY there (and rotate it once GitHub Pages, which ships the same key in the browser, is retired). */
return [
    'crm_url'  => getenv('FQ_CRM_URL') ?: 'https://crm.fandaqah.com/api/ingest/lead',
    'api_key'  => getenv('FQ_CRM_KEY') ?: 'fdq_d0930f18b74a5153c68b174748b5ed7ba17b8723ca05c21e',
    'platform' => 'fandaqah_tourism_platform_contact',
    // other sites allowed to post here, comma-separated (scheme://host[:port]); the site's own host is always allowed
    'allowed_origins' => array_values(array_filter(array_map('trim', explode(',', getenv('FQ_ALLOWED_ORIGINS') ?: '')))),
    // requests per visitor address in the window (seconds); a person sends one or two
    'rate_limit' => ['max' => (int) (getenv('FQ_RATE_MAX') ?: 8), 'window' => 600],
    'timeout'  => 12,
    // LinkedIn Conversions API (f360-linkedin-dev-pack): a token from the LinkedIn developer app (scope rw_conversions), set as
    // LINKEDIN_CAPI_TOKEN on the host. Empty = the server twin is simply not sent; everything else works as before.
    'linkedin_capi_token'  => getenv('LINKEDIN_CAPI_TOKEN') ?: '',
    // a supported YYYYMM version; LinkedIn retires each about 12 months after release
    'linkedin_api_version' => getenv('LINKEDIN_API_VERSION') ?: '202610',
    // TikTok Events API (tiktok/ pack): the pixel id (the same one as in assets/js/fandaqah-tiktok.js) and its Events API token,
    // set as TIKTOK_PIXEL_ID / TIKTOK_EVENTS_API_TOKEN on the host. Either empty = the server Lead is simply not sent.
    'tiktok_pixel_id'          => getenv('TIKTOK_PIXEL_ID') ?: '',
    'tiktok_events_api_token'  => getenv('TIKTOK_EVENTS_API_TOKEN') ?: '',
    // the CRM Event Set for stage events (api/tiktok-crm-events.php, called by the CRM), when it exists
    'tiktok_crm_event_set_id'  => getenv('TIKTOK_CRM_EVENT_SET_ID') ?: '',
];
