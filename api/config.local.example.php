<?php
/* Copy to api/config.local.php on the server and fill in the real values. config.local.php is never committed (.gitignore)
   and never served (api/.htaccess). Environment variables of the same meaning win over these. */
return [
    'api_key'                 => '',   // FQ_CRM_KEY: the CRM ingest key (rotate it in the CRM, then paste the new one here)
    'linkedin_capi_token'     => '',   // LINKEDIN_CAPI_TOKEN
    'tiktok_pixel_id'         => '',   // TIKTOK_PIXEL_ID (also set in assets/js/fandaqah-tiktok.js)
    'tiktok_events_api_token' => '',   // TIKTOK_EVENTS_API_TOKEN
    'snap_pixel_id'           => '',   // FQ_SNAP_PIXEL_ID (also set in assets/js/fq-snap.js)
    'snap_capi_token'         => '',   // FQ_SNAP_CAPI_TOKEN
    'snap_test_event_code'    => '',   // FQ_SNAP_TEST_CODE: only while checking Test Events, then empty again
    'meta_capi_token'         => '',   // META_CAPI_TOKEN: Meta dataset 992779690541049 → Conversions API
    'meta_test_event_code'    => '',   // META_TEST_EVENT_CODE: only while checking Test events, then empty again
    'funnel_key'              => '',   // FQ_FUNNEL_KEY: opens the funnel report, api/funnel.php?key=…
    'partial_key'             => '',   // F360_PARTIAL_KEY: opens the partial-lead call list, api/partial-leads.php?key=… (long, random)
    'partial_webhook'         => '',   // F360_CRM_PARTIAL_WEBHOOK: optional, the CRM's "Partial lead" queue
    // 'partial_dir'          => '/home/<account>/f360-partial-leads',   // F360_PARTIAL_DIR: best outside the web root
    // 'partial_days'         => 30,   // F360_PARTIAL_DAYS: how long a number is kept
];
