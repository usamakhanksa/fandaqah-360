<?php
/**
 * Fandaqah 360 — Meta Conversions API (server-side Lead)   v1.0  (2026-10-27)
 *
 * Lives next to the existing proxy:  /landing/fandaqah-360/api/fq_meta_capi.php
 * Called from lead.php ONLY after the CRM answered 2xx.
 *
 * Settings (api/config.php → 'meta'): the token from the host environment or api/config.local.php, never a web-readable file:
 *   META_CAPI_TOKEN        Events Manager → dataset 992779690541049 → Settings → Conversions API → Generate access token
 *   META_TEST_EVENT_CODE   optional; only while testing (Events Manager → Test events). Remove after go-live.
 * On this site (meta/ pack): lead.php calls fq_meta_send_lead() for a lead SENT from one of the two F360 pages, after the
 * visitor has the answer, with _fbp/_fbc read from the same-site request (the forms need no patch). Never served (.htaccess).
 *
 * Dedup contract: event_name "Lead" + event_id == browser fbq eventID == payload.submission_id.
 */

const FQ_META_PIXEL_ID      = '992779690541049';
const FQ_META_GRAPH_VERSION = 'v24.0';   // keep on a supported Graph API version
const FQ_META_TIMEOUT_S     = 4;         // never block the visitor for long

/** The token and test code: api/config.php (environment first, then api/config.local.php). */
function fq_meta_settings(): array
{
    static $s = null;
    if ($s === null) { $c = require __DIR__ . '/config.php'; $s = (array) ($c['meta'] ?? []); }
    return $s;
}

function fq_meta_hash(?string $v): ?string
{
    return ($v === null || $v === '') ? null : hash('sha256', $v);
}

function fq_meta_norm_email($e): ?string
{
    $e = strtolower(trim((string) $e));
    return filter_var($e, FILTER_VALIDATE_EMAIL) ? $e : null;
}

/** KSA mobile → E.164 digits without "+", e.g. 9665XXXXXXXX. Accepts Arabic-Indic digits. */
function fq_meta_norm_phone($p): ?string
{
    $p = strtr((string) $p, ['٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9',
                             '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9']);
    $d = preg_replace('/\D+/', '', $p);
    if ($d === '') return null;
    if (strpos($d, '00') === 0) $d = substr($d, 2);
    if (strlen($d) === 10 && strpos($d, '05') === 0) $d = '966' . substr($d, 1);
    elseif (strlen($d) === 9 && $d[0] === '5') $d = '966' . $d;
    return strlen($d) >= 11 ? $d : null;
}

/** First + last name, lower-case, for fn/ln match keys. */
function fq_meta_split_name($full): array
{
    $full = (string) $full;
    $parts = preg_split('/\s+/u', trim(function_exists('mb_strtolower') ? mb_strtolower($full, 'UTF-8') : strtolower($full)), -1, PREG_SPLIT_NO_EMPTY);
    if (!$parts) return [null, null];
    return [$parts[0], count($parts) > 1 ? end($parts) : null];
}

function fq_meta_client_ip(): ?string
{
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $k) {   // site is behind Cloudflare
        if (!empty($_SERVER[$k])) {
            $ip = trim(explode(',', $_SERVER[$k])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
        }
    }
    return null;
}

/** Build the CAPI event from the lead payload the browser posted (fields used by both landing pages). */
function fq_meta_build_lead_event(array $payload): ?array
{
    $m   = isset($payload['meta']) && is_array($payload['meta']) ? $payload['meta'] : [];
    $eid = (string) ($m['event_id'] ?? $payload['submission_id'] ?? '');
    if (!preg_match('/^[A-Za-z0-9-]{8,64}$/', $eid)) return null;   // no valid ID → cannot dedupe → do not send

    $email = fq_meta_norm_email($payload['email'] ?? null);
    $phone = fq_meta_norm_phone($payload['phone'] ?? null);
    [$fn, $ln] = fq_meta_split_name($payload['name'] ?? '');

    $ud = array_filter([
        'em'                => $email ? [fq_meta_hash($email)] : null,
        'ph'                => $phone ? [fq_meta_hash($phone)] : null,
        'fn'                => $fn ? [fq_meta_hash($fn)] : null,
        'ln'                => $ln ? [fq_meta_hash($ln)] : null,
        'country'           => [fq_meta_hash('sa')],
        'external_id'       => [fq_meta_hash($eid)],
        'client_ip_address' => fq_meta_client_ip(),
        'client_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        'fbp'               => isset($m['fbp']) && preg_match('/^fb\.\d\.\d+\.\d+$/', $m['fbp']) ? $m['fbp'] : null,
        'fbc'               => isset($m['fbc']) && strpos((string) $m['fbc'], 'fb.') === 0 ? $m['fbc'] : null,
    ]);

    $src = $m['event_source_url'] ?? ($payload['page_url'] ?? null);

    return array_filter([
        'event_name'       => 'Lead',
        'event_time'       => time(),
        'event_id'         => $eid,
        'action_source'    => 'website',
        'event_source_url' => $src && filter_var($src, FILTER_VALIDATE_URL) ? $src : null,
        'user_data'        => $ud,
        'custom_data'      => array_filter([
            'campaign_type' => in_array($m['campaign_type'] ?? '', ['existing_customer', 'new_customer'], true) ? $m['campaign_type'] : null,
            'landing_page'  => isset($m['landing_page']) ? substr((string) $m['landing_page'], 0, 80) : null,
            'lead_id'       => $eid,
            'interest'      => isset($payload['interest']) ? substr((string) $payload['interest'], 0, 60) : null,
            // value/currency intentionally omitted until sales agrees a lead value (SAR) — add both together
        ]),
    ]);
}

/** Fire-and-log. Returns ['ok'=>bool, ...]. Never throws, never blocks more than FQ_META_TIMEOUT_S. */
function fq_meta_send_lead(array $payload): array
{
    $cfg = fq_meta_settings();
    $token = (string) ($cfg['token'] ?? '');
    if ($token === '') return ['ok' => false, 'skipped' => 'META_CAPI_TOKEN not set'];
    if (!function_exists('curl_init')) return ['ok' => false, 'skipped' => 'curl not available'];

    $event = fq_meta_build_lead_event($payload);
    if (!$event) return ['ok' => false, 'skipped' => 'no valid event_id'];

    $body = ['data' => [$event]];
    if (!empty($cfg['test_event_code'])) $body['test_event_code'] = $cfg['test_event_code'];

    // META_GRAPH_URL points a test run at a local stub; production always uses Meta
    $url = rtrim(getenv('META_GRAPH_URL') ?: 'https://graph.facebook.com', '/') . '/' . FQ_META_GRAPH_VERSION . '/' . FQ_META_PIXEL_ID . '/events';
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($body + ['access_token' => $token], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 2,
        CURLOPT_TIMEOUT        => FQ_META_TIMEOUT_S,
    ]);
    $resp = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    unset($ch);   // frees the handle (curl_close() has done nothing since PHP 8.0 and is deprecated in 8.5)

    $ok = $code === 200;
    // Log without PII: event_id + status only
    error_log(sprintf('[fq-capi] Lead event_id=%s http=%d %s', $event['event_id'], $code, $ok ? 'OK' : ('ERR ' . ($err ?: substr((string) $resp, 0, 300)))));
    return ['ok' => $ok, 'http' => $code, 'event_id' => $event['event_id']];
}

/* ------------------------------------------------------------------------
 * HOW TO WIRE INTO lead.php (3 lines)
 * ------------------------------------------------------------------------
 *   require_once __DIR__ . '/fq_meta_capi.php';
 *   $payload = json_decode(file_get_contents('php://input'), true) ?: [];   // lead.php already does this
 *   $forCrm  = $payload; unset($forCrm['meta']);                            // CRM never receives fbp/fbc
 *   ... existing code posts $forCrm to the CRM ...
 *   if ($crmHttpCode >= 200 && $crmHttpCode < 300) {
 *       if (function_exists('fastcgi_finish_request')) { echo $responseToBrowser; fastcgi_finish_request(); }
 *       fq_meta_send_lead($payload);   // after the visitor already has the response
 *   }
 * ---------------------------------------------------------------------- */
