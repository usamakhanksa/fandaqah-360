<?php
/**
 * meta-capi.php — Meta Conversions API helper (Fandaqah 360 landing pages)
 * Dataset / pixel: 992779690541049
 *
 * Put the token in the server environment — NEVER in Git or JS:
 *   META_CAPI_TOKEN      = (Events Manager → fandaqah → Settings → Conversions API → Generate access token)
 *   META_TEST_EVENT_CODE = TEST12345   (optional — only while testing in "Test events", remove after)
 *
 * Usage:
 *   require_once __DIR__ . '/meta-capi.php';
 *   meta_capi_send('Lead', $submissionId, $sourceUrl, ['em'=>$email,'ph'=>$phone,'fn'=>$name], $meta, ['content_name'=>'Fandaqah 360']);
 */

const META_PIXEL_ID      = '992779690541049';
const META_GRAPH_VERSION = 'v23.0';   // update to the current Graph API version when you deploy

/** SHA-256 of a normalised value (Meta requirement for PII). */
function meta_hash(?string $v): ?string {
    $v = trim(mb_strtolower((string)$v, 'UTF-8'));
    return $v === '' ? null : hash('sha256', $v);
}

/** Saudi numbers → E.164 digits without "+": 05XXXXXXXX / 5XXXXXXXX / +9665XXXXXXXX → 9665XXXXXXXX */
function meta_norm_phone(?string $p): ?string {
    $d = preg_replace('/\D+/', '', (string)$p);
    if ($d === '') return null;
    if (str_starts_with($d, '00')) $d = substr($d, 2);
    if (str_starts_with($d, '05') && strlen($d) === 10) $d = '966' . substr($d, 1);
    elseif (str_starts_with($d, '5') && strlen($d) === 9) $d = '966' . $d;
    return $d;
}

/** Real visitor IP behind Cloudflare. */
function meta_client_ip(): ?string {
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $k) {
        if (!empty($_SERVER[$k])) return trim(explode(',', $_SERVER[$k])[0]);
    }
    return null;
}

/**
 * @param string $eventName  'Lead' | 'F360_FormOpen_NoFill' | 'F360_FormAbandon' …
 * @param string $eventId    MUST equal the browser eventID (Lead = submission_id)
 * @param array  $pii        ['em'=>…, 'ph'=>…, 'fn'=>…, 'ln'=>…] raw values, hashed here
 * @param array  $meta       browser context: ['fbp'=>…, 'fbc'=>…]
 */
function meta_capi_send(string $eventName, string $eventId, string $sourceUrl,
                        array $pii = [], array $meta = [], array $customData = [],
                        ?int $eventTime = null): array {
    $token = getenv('META_CAPI_TOKEN');
    if (!$token) { error_log('[CAPI] META_CAPI_TOKEN missing — event not sent'); return ['ok' => false, 'error' => 'no_token']; }

    // split full name → first / last
    if (!empty($pii['fn']) && empty($pii['ln']) && str_contains(trim($pii['fn']), ' ')) {
        [$pii['fn'], $pii['ln']] = preg_split('/\s+/', trim($pii['fn']), 2);
    }

    $ud = array_filter([
        'em'                => !empty($pii['em']) ? [meta_hash($pii['em'])] : null,
        'ph'                => !empty($pii['ph']) ? [meta_hash(meta_norm_phone($pii['ph']))] : null,
        'fn'                => !empty($pii['fn']) ? [meta_hash($pii['fn'])] : null,
        'ln'                => !empty($pii['ln']) ? [meta_hash($pii['ln'])] : null,
        'country'           => [meta_hash('sa')],
        'external_id'       => !empty($pii['em']) ? [meta_hash($pii['em'])] : null,
        'client_ip_address' => meta_client_ip(),
        'client_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        'fbp'               => $meta['fbp'] ?? null,
        'fbc'               => $meta['fbc'] ?? null,
    ]);

    $event = [
        'event_name'       => $eventName,
        'event_time'       => $eventTime && abs(time() - $eventTime) < 3600 ? $eventTime : time(),
        'event_id'         => $eventId,
        'action_source'    => 'website',
        'event_source_url' => $sourceUrl,
        'user_data'        => $ud,
        'custom_data'      => (object)array_filter($customData, fn($v) => $v !== null && $v !== ''),
    ];

    $payload = ['data' => [$event]];
    if ($test = getenv('META_TEST_EVENT_CODE')) $payload['test_event_code'] = $test;

    $url = sprintf('https://graph.facebook.com/%s/%s/events?access_token=%s',
        META_GRAPH_VERSION, META_PIXEL_ID, urlencode($token));

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 4,      // never block the visitor
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code !== 200) error_log("[CAPI] $eventName $eventId HTTP $code $body");
    return ['ok' => $code === 200, 'http' => $code, 'body' => $body];
}
