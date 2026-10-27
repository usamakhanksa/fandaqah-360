<?php
/* Settings for api/lead.php and api/lead-event.php. This file is public (the repo is public), so it holds NO secrets.
   Secrets come from the host's environment variables, or from api/config.local.php beside this file: a copy of
   config.local.example.php with the real values, kept out of git (.gitignore), never served (api/.htaccess), and included
   in the upload package (tools/package-php.py) when it exists. An environment variable wins over config.local.php. */
$local = is_file(__DIR__ . '/config.local.php') ? (array) require __DIR__ . '/config.local.php' : [];
$secret = function (string $env, string $key) use ($local): string { return (string) (getenv($env) ?: ($local[$key] ?? '')); };

return [
    'crm_url'  => getenv('FQ_CRM_URL') ?: ($local['crm_url'] ?? 'https://crm.fandaqah.com/api/ingest/lead'),
    // the CRM ingest key: never in this file and never in the browser
    'api_key'  => $secret('FQ_CRM_KEY', 'api_key'),
    'platform' => 'fandaqah_tourism_platform_contact',
    // other sites allowed to post here (scheme://host[:port]); the site's own host is always allowed. The GitHub Pages preview
    // has no server of its own, so its forms post here; FQ_ALLOWED_ORIGINS (comma-separated) adds more
    'allowed_origins' => array_values(array_unique(array_filter(array_map('trim', array_merge(
        ['https://usamakhanksa.github.io'], explode(',', getenv('FQ_ALLOWED_ORIGINS') ?: '')))))),
    // requests per visitor address in the window (seconds); a person sends one or two
    'rate_limit' => ['max' => (int) (getenv('FQ_RATE_MAX') ?: 8), 'window' => 600],
    'timeout'  => 12,
    // LinkedIn Conversions API (f360-linkedin-dev-pack): a token from the LinkedIn developer app (scope rw_conversions).
    // Empty = the server twin is simply not sent; everything else works as before.
    'linkedin_capi_token'  => $secret('LINKEDIN_CAPI_TOKEN', 'linkedin_capi_token'),
    // a supported YYYYMM version; LinkedIn retires each about 12 months after release
    'linkedin_api_version' => getenv('LINKEDIN_API_VERSION') ?: '202610',
    // TikTok Events API (tiktok/ pack): the pixel id (the same one as in assets/js/fandaqah-tiktok.js) and its Events API token.
    // Either empty = the server Lead is simply not sent.
    'tiktok_pixel_id'          => getenv('TIKTOK_PIXEL_ID') ?: ($local['tiktok_pixel_id'] ?? ''),
    'tiktok_events_api_token'  => $secret('TIKTOK_EVENTS_API_TOKEN', 'tiktok_events_api_token'),
    // the CRM Event Set for stage events (api/tiktok-crm-events.php, called by the CRM), when it exists
    'tiktok_crm_event_set_id'  => getenv('TIKTOK_CRM_EVENT_SET_ID') ?: ($local['tiktok_crm_event_set_id'] ?? ''),
    // the funnel report (api/funnel.php?key=…): empty = the report is switched off
    'funnel_key' => $secret('FQ_FUNNEL_KEY', 'funnel_key'),
];
