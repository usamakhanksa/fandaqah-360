<?php
/* Fandaqah 360 · Snap Conversions API (server half of Pixel + CAPI). Included by api/lead.php, never served (see .htaccess).

   After the CRM has accepted a lead, api/lead.php calls fq_snap_after_lead(). It answers the visitor first and only then sends one
   SIGN_UP event to Snap, so a slow or failing Snap call can never delay or lose a lead.

   Deduplication: event_id = the lead's submission_id. The browser fires the same SIGN_UP with client_dedup_id = submission_id
   (assets/js/fq-snap.js), so Snap keeps one of the two (48-hour window). A visitor without JavaScript, or with the pixel blocked
   (iOS, ad blockers), still reaches Snap through this call: that is what the server half is for.

   Never sent: lead-capture.js "partial" leads (lead_stage = partial), honeypot hits (stopped earlier in lead.php), failed CRM calls. */
declare(strict_types=1);

function fq_snap_hash(?string $v): ?string
{
    $v = $v === null ? '' : trim($v);
    return $v === '' ? null : hash('sha256', $v);
}

/* the visitor's address, not Cloudflare's: fandaqah.com sits behind Cloudflare, so REMOTE_ADDR is an edge server */
function fq_snap_client_ip(): ?string
{
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_TRUE_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $h) {
        $v = trim(explode(',', (string) ($_SERVER[$h] ?? ''))[0]);
        if ($v !== '' && filter_var($v, FILTER_VALIDATE_IP)) return $v;
    }
    return null;
}

/* Build the Snap event for a lead the CRM accepted. Pure: no network, so it can be tested on its own. */
function fq_snap_lead_event(array $lead, array $snap, bool $isForm, ?int $nowMs = null): ?array
{
    if (($lead['lead_stage'] ?? '') === 'partial') return null;
    $nowMs = $nowMs ?? (int) round(microtime(true) * 1000);

    $email = strtolower(trim((string) ($lead['email'] ?? '')));
    $phone = preg_replace('/\D/', '', (string) ($lead['phone'] ?? ''));          // lead.php already made it +966…; Snap wants digits only
    $phone = $phone !== null ? preg_replace('/^00/', '', $phone) : '';
    $name  = preg_split('/\s+/u', trim((string) ($lead['name'] ?? ''))) ?: [];
    $lower = function (string $s): string { return function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s); };
    $clean = function (string $s) use ($lower): string { return $lower(preg_replace('/[\p{P}\p{S}\s]+/u', '', $s) ?? ''); };

    $user = array_filter([
        'em' => $email !== '' ? [fq_snap_hash($email)] : null,
        'ph' => $phone !== '' ? [fq_snap_hash($phone)] : null,
        'fn' => count($name) > 0 && $name[0] !== '' ? [fq_snap_hash($clean($name[0]))] : null,
        'ln' => count($name) > 1 ? [fq_snap_hash($clean((string) end($name)))] : null,
        'country' => strpos($phone, '966') === 0 ? [fq_snap_hash('sa')] : null,
        'client_ip_address' => fq_snap_client_ip(),
        'client_user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ($snap['user_agent'] ?? '')), 0, 500) ?: null,
        'sc_cookie1' => (string) ($_COOKIE['_scid'] ?? ($snap['sc_cookie1'] ?? '')) ?: null,
        'sc_click_id' => (string) ($snap['sc_click_id'] ?? ($lead['sc_click_id'] ?? '')) ?: null,
    ], function ($v) { return $v !== null && $v !== '' && $v !== [null]; });
    if (!isset($user['em']) && !isset($user['ph']) && !isset($user['sc_cookie1']) && !isset($user['sc_click_id'])) return null;

    $url = (string) ($snap['page_url'] ?? $lead['page_url'] ?? ($_SERVER['HTTP_REFERER'] ?? ''));
    if (!preg_match('#^https?://#i', $url)) $url = 'https://fandaqah.com/landing/fandaqah-360/';

    $type = (string) ($snap['campaign_type'] ?? '');
    if ($type !== 'existing_customer' && $type !== 'new_customer') {
        $c = strtolower((string) ($lead['utm_campaign'] ?? $lead['campaign'] ?? ''));
        $type = (preg_match('/existing|upgrade|^a[_\-\s]/', $c) || strpos($url, 'existing-customers') !== false) ? 'existing_customer' : 'new_customer';
    }

    $id = (string) ($lead['submission_id'] ?? '');
    if ($id === '') $id = 'srv-' . bin2hex(random_bytes(12));            // no-JavaScript post: no pixel twin to match

    return [
        'event_name' => 'SIGN_UP',
        'action_source' => 'WEB',
        'event_time' => $nowMs,
        'event_id' => substr($id, 0, 100),
        'event_source_url' => substr($url, 0, 1000),
        'user_data' => $user,
        'custom_data' => [
            'event_tag' => $type,
            'status' => $type === 'existing_customer' ? 'returning' : 'new',
        ],
    ];
}

/* POST one or more events to Snap. Returns [http status, body]. */
function fq_snap_send(array $config, array $events): array
{
    $s = $config['snap'] ?? [];
    if (empty($s['pixel_id']) || empty($s['token']) || !$events) return [0, 'snap not configured'];
    $body = ['data' => array_values($events)];
    if (!empty($s['test_event_code'])) foreach ($body['data'] as &$e) $e['test_event_code'] = $s['test_event_code'];
    unset($e);
    $url = rtrim($s['endpoint'] ?? 'https://tr.snapchat.com/v3', '/') . '/' . rawurlencode($s['pixel_id']) . '/events?access_token=' . rawurlencode($s['token']);
    $json = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $status = 0; $answer = '';
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $json, CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'], CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_TIMEOUT => (int) ($s['timeout'] ?? 4)]);
        $answer = (string) curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
    } else {
        $ctx = stream_context_create(['http' => ['method' => 'POST', 'header' => 'Content-Type: application/json', 'content' => $json,
            'timeout' => (int) ($s['timeout'] ?? 4), 'ignore_errors' => true]]);
        $answer = (string) @file_get_contents($url, false, $ctx);
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) $status = (int) $m[1];
    }
    return [$status, $answer];
}

/* Called by lead.php once the CRM said yes: the visitor gets the answer first, Snap gets the event afterwards. */
function fq_snap_after_lead(array $config, array $lead, array $snap, bool $isForm): void
{
    $event = fq_snap_lead_event($lead, $snap, $isForm);
    if ($event === null || empty($config['snap']['pixel_id']) || empty($config['snap']['token'])) return;
    register_shutdown_function(function () use ($config, $event) {
        ignore_user_abort(true);
        if (function_exists('fastcgi_finish_request')) @fastcgi_finish_request();   // the visitor is answered; Snap is sent after
        [$status, $answer] = fq_snap_send($config, [$event]);
        if ($status < 200 || $status >= 300 || stripos($answer, '"VALID"') === false && stripos($answer, 'success') === false)
            error_log("fq snap capi: SIGN_UP {$event['event_id']} answered $status: " . substr($answer, 0, 300));
        elseif (!empty($config['snap']['debug']))
            error_log("fq snap capi: SIGN_UP {$event['event_id']} sent ($status)");
    });
}
