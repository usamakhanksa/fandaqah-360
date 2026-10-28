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
    // Snap Conversions API (snapcha-retargeting pack, api/snap-capi.php). Pixel ID: Events Manager → the Fandaqah website pixel (the
    // same value as PIXEL_ID in assets/js/fq-snap.js). Token: the pixel → Conversions API → Generate token. Either empty = no
    // server events (the browser pixel still works).
    'snap' => [
        'pixel_id'        => getenv('FQ_SNAP_PIXEL_ID') ?: ($local['snap_pixel_id'] ?? ''),
        'token'           => $secret('FQ_SNAP_CAPI_TOKEN', 'snap_capi_token'),
        'test_event_code' => getenv('FQ_SNAP_TEST_CODE') ?: ($local['snap_test_event_code'] ?? ''),   // only while testing (Test Events)
        'endpoint'        => getenv('FQ_SNAP_ENDPOINT') ?: 'https://tr.snapchat.com/v3',
        'timeout'         => 4,
        'debug'           => (bool) getenv('FQ_SNAP_DEBUG'),
    ],
    // Meta Conversions API (meta/ pack, api/fq_meta_capi.php): dataset 992779690541049 (fixed in that file and in assets/js/fq-ads.js).
    // Token: Events Manager → the dataset → Settings → Conversions API → Generate access token. Empty = no server Lead.
    'meta' => [
        'token'           => $secret('META_CAPI_TOKEN', 'meta_capi_token'),
        'test_event_code' => getenv('META_TEST_EVENT_CODE') ?: ($local['meta_test_event_code'] ?? ''),   // only while testing
    ],
    // the funnel report (api/funnel.php?key=…): empty = the report is switched off
    'funnel_key' => $secret('FQ_FUNNEL_KEY', 'funnel_key'),
    // Partial leads (second meta/ pack, api/f360-partial-lead.php): numbers typed WITH consent on the two F360 pages before the form
    // was sent, the call-back list. Stored as one JSON line per visit, a file per month, in a folder no browser can open: outside the
    // web root if the host allows it (F360_PARTIAL_DIR), else logs/partial (logs/.htaccess denies the web). Kept partial_days.
    'partial' => [
        'dir'     => getenv('F360_PARTIAL_DIR') ?: ($local['partial_dir'] ?? (__DIR__ . '/../logs/partial')),
        'webhook' => $secret('F360_CRM_PARTIAL_WEBHOOK', 'partial_webhook'),   // optional: the CRM's "Partial lead" queue (https)
        'days'    => max(1, (int) (getenv('F360_PARTIAL_DAYS') ?: ($local['partial_days'] ?? 30))),
        'key'     => $secret('F360_PARTIAL_KEY', 'partial_key'),               // opens the call list, api/partial-leads.php?key=…
    ],
];
