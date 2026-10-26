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
];
