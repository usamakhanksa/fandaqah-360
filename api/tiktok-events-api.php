<?php
/**
 * Fandaqah 360 — TikTok Events API 2.0 (server side)            v1.0.0
 * ------------------------------------------------------------------
 * Endpoint : POST https://business-api.tiktok.com/open_api/v1.3/event/track/
 * Auth     : Access-Token header (generated in Events Manager → Pixel → Settings)
 *
 * Secrets live in the server environment ONLY — never in browser JS:
 *   TIKTOK_PIXEL_ID              e.g. C0ABC123DEF456GHI789
 *   TIKTOK_EVENTS_API_TOKEN      long-lived access token
 *   TIKTOK_CRM_EVENT_SET_ID      (optional) CRM Event Set ID for offline stages
 *
 * Dedup contract with fandaqah-tiktok.js:
 *   browser  ttq.track('Lead', …, {event_id: X})
 *   server   data[0].event_id = X   (read from $payload['tt']['event_id'])
 *   TikTok keeps one (48h window; server arriving < 5 min merges user data).
 */

declare(strict_types=1);

const F360_TT_ENDPOINT = 'https://business-api.tiktok.com/open_api/v1.3/event/track/';
// one line per send; logs/ answers nothing to the web (logs/.htaccess), and TIKTOK_LOG_FILE can move it outside the site
define('F360_TT_LOG', getenv('TIKTOK_LOG_FILE') ?: __DIR__ . '/../logs/tiktok-events.log');

// lead.php runs on PHP 7.4 or later; these two arrived in PHP 8
if (!function_exists('str_starts_with')) {
    function str_starts_with(string $h, string $n): bool { return $n === '' || strncmp($h, $n, strlen($n)) === 0; }
}
if (!function_exists('str_contains')) {
    function str_contains(string $h, string $n): bool { return $n === '' || strpos($h, $n) !== false; }
}

/* ───────────────────────── Normalisation & hashing ───────────────────────── */

function f360_tt_hash(?string $v): ?string
{
    $v = $v === null ? '' : trim($v);
    return $v === '' ? null : hash('sha256', $v);
}

function f360_tt_norm_email(?string $email): ?string
{
    $e = strtolower(trim((string)$email));
    return filter_var($e, FILTER_VALIDATE_EMAIL) ? $e : null;
}

/** Saudi-first E.164 (+9665XXXXXXXX). Converts Arabic-Indic digits. */
function f360_tt_norm_phone(?string $phone): ?string
{
    $p = strtr((string)$phone, [
        '٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9',
        '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9',
    ]);
    $p = preg_replace('/[^\d+]/', '', $p) ?? '';
    if ($p === '') return null;
    if (str_starts_with($p, '00')) $p = '+' . substr($p, 2);
    if (str_starts_with($p, '+'))  return strlen($p) >= 10 ? $p : null;
    if (preg_match('/^9665\d{8}$/', $p)) return '+' . $p;
    if (preg_match('/^05\d{8}$/', $p))   return '+966' . substr($p, 1);
    if (preg_match('/^5\d{8}$/', $p))    return '+966' . $p;
    return strlen($p) >= 9 ? '+' . $p : null;
}

function f360_tt_client_ip(): ?string
{
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = trim(explode(',', (string)$_SERVER[$k])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
        }
    }
    return null;
}

function f360_tt_uuid(): string
{
    $d = random_bytes(16);
    $d[6] = chr((ord($d[6]) & 0x0f) | 0x40);
    $d[8] = chr((ord($d[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($d), 4));
}

function f360_tt_clean(array $a): array
{
    foreach ($a as $k => $v) {
        if (is_array($v)) { $a[$k] = f360_tt_clean($v); if ($a[$k] === []) unset($a[$k]); }
        elseif ($v === null || $v === '') unset($a[$k]);
    }
    return $a;
}

/* ───────────────────────────── Event builders ───────────────────────────── */

/**
 * Build the web Lead event from the JSON body lead.php received.
 *
 * @param array       $payload  decoded JSON body (contains name/email/phone/... and `tt` from the browser)
 * @param string|null $leadId   your internal lead/reference id (hashed → external_id)
 */
function f360_tt_build_lead_event(array $payload, ?string $leadId = null): array
{
    $tt   = is_array($payload['tt'] ?? null) ? $payload['tt'] : [];
    $path = (string)parse_url((string)($tt['page_url'] ?? ''), PHP_URL_PATH);

    $campaignType = in_array($tt['campaign_type'] ?? '', ['existing_customer', 'new_customer'], true)
        ? $tt['campaign_type']
        : (str_contains($path, 'existing-customers') ? 'existing_customer' : 'new_customer');

    $email = f360_tt_norm_email($payload['email'] ?? null);
    $phone = f360_tt_norm_phone($payload['phone'] ?? null);

    return f360_tt_clean([
        'event'      => 'Lead',
        'event_time' => time(),
        'event_id'   => preg_match('/^[A-Za-z0-9-]{8,64}$/', (string)($tt['event_id'] ?? '')) ? $tt['event_id'] : f360_tt_uuid(),
        'user'       => [
            'ttclid'      => $tt['ttclid'] ?? ($_COOKIE['f360_ttclid'] ?? null),
            'ttp'         => $tt['ttp'] ?? ($_COOKIE['_ttp'] ?? null),
            'email'       => f360_tt_hash($email),
            'phone'       => f360_tt_hash($phone),
            'external_id' => f360_tt_hash($leadId),
            'ip'          => f360_tt_client_ip(),
            'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'locale'      => 'ar_SA',
        ],
        'page'       => [                                      // required for web events
            'url'      => $tt['page_url'] ?? null,
            'referrer' => $tt['referrer'] ?? null,
        ],
        'properties' => [
            'contents'        => [[
                'content_id'   => 'fandaqah-360',
                'content_name' => 'Fandaqah 360',
                'content_type' => 'product',
            ]],
            'content_type'    => 'product',
            'currency'        => 'SAR',
            'campaign_type'   => $campaignType,
            'landing_variant' => $tt['landing_variant'] ?? null,
            'lead_source'     => 'landing_form',
        ],
    ]);
}

/* ─────────────────────────────── Transport ─────────────────────────────── */

/**
 * Send events. $source: web | crm | offline | app.
 * Returns ['ok'=>bool, 'http'=>int, 'body'=>mixed]. Never throws.
 */
function f360_tt_send(array $events, string $source = 'web', ?string $sourceId = null, ?string $testCode = null): array
{
    $token = getenv('TIKTOK_EVENTS_API_TOKEN') ?: '';
    $sourceId = $sourceId ?? ($source === 'crm' ? getenv('TIKTOK_CRM_EVENT_SET_ID') : getenv('TIKTOK_PIXEL_ID')) ?: '';
    if ($token === '' || $sourceId === '' || $events === []) {
        f360_tt_log('SKIP missing token/source id/events');
        return ['ok' => false, 'http' => 0, 'body' => 'not configured'];
    }

    $body = ['event_source' => $source, 'event_source_id' => $sourceId, 'data' => array_values($events)];
    if ($testCode && preg_match('/^[A-Za-z0-9]{4,32}$/', $testCode)) $body['test_event_code'] = $testCode;

    if (!function_exists('curl_init')) {
        f360_tt_log('SKIP curl not available');
        return ['ok' => false, 'http' => 0, 'body' => 'curl not available'];
    }
    // TIKTOK_EVENTS_API_URL points a test run at a local stub; production always uses TikTok
    $ch = curl_init(getenv('TIKTOK_EVENTS_API_URL') ?: F360_TT_ENDPOINT);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 2,
        CURLOPT_TIMEOUT        => 4,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Access-Token: ' . $token],
        CURLOPT_POSTFIELDS     => json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]);
    $raw  = curl_exec($ch);
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    $json = is_string($raw) ? json_decode($raw, true) : null;
    $ok   = $http === 200 && is_array($json) && (int)($json['code'] ?? -1) === 0;

    f360_tt_log(sprintf('%s %s events=%d id=%s http=%d code=%s msg=%s%s',
        $ok ? 'OK ' : 'ERR', $source, count($events), $events[0]['event_id'] ?? '-', $http,
        $json['code'] ?? '-', $json['message'] ?? '-', $err ? ' curl=' . $err : ''));

    return ['ok' => $ok, 'http' => $http, 'body' => $json ?? $raw];
}

function f360_tt_log(string $line): void
{
    @file_put_contents(F360_TT_LOG, '[' . gmdate('c') . '] ' . $line . PHP_EOL, FILE_APPEND | LOCK_EX);
}

/**
 * One-liner for lead.php — call AFTER the lead is stored successfully and
 * AFTER the success response has been sent (see INTEGRATION.md).
 */
function f360_tt_track_lead(array $payload, ?string $leadId = null): array
{
    $event = f360_tt_build_lead_event($payload, $leadId);
    $test  = is_array($payload['tt'] ?? null) ? ($payload['tt']['test_event_code'] ?? null) : null;
    return f360_tt_send([$event], 'web', null, $test);
}
