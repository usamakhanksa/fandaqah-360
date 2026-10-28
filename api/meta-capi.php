<?php
/**
 * meta-capi.php — Meta Conversions API helper (Fandaqah 360 landing pages)   second meta/ pack, 28 Oct 2026
 * Dataset / pixel: 992779690541049
 *
 * The pack's helper, kept under its own name and signature, built on api/fq_meta_capi.php so the server Lead and the custom
 * events share one token, one Graph version, one sender and the same normalisers:
 *   - the token comes from api/config.php (META_CAPI_TOKEN in the environment, else api/config.local.php), never Git or JS;
 *     META_TEST_EVENT_CODE the same way, only while testing in Events Manager → Test events;
 *   - the access token travels in the POST body, not the address (addresses end up in logs);
 *   - phones accept Arabic-Indic digits; PHP 7.4 or later (the pack's str_starts_with / str_contains need PHP 8);
 *   - curl_close() is not called (deprecated in PHP 8.5; its notice would end up in the answer).
 * Never served (api/.htaccess).
 *
 * Usage:
 *   require_once __DIR__ . '/meta-capi.php';
 *   meta_capi_send('Lead', $submissionId, $sourceUrl, ['em'=>$email,'ph'=>$phone,'fn'=>$name], $meta, ['content_name'=>'Fandaqah 360']);
 * The Lead itself is already sent by lead.php (meta_lead → fq_meta_send_lead) after the CRM accepts; do not call it a second time.
 */
require_once __DIR__ . '/fq_meta_capi.php';

const META_PIXEL_ID      = FQ_META_PIXEL_ID;
const META_GRAPH_VERSION = FQ_META_GRAPH_VERSION;

/** SHA-256 of a normalised value (Meta requirement for PII). */
function meta_hash(?string $v): ?string
{
    $v = trim(function_exists('mb_strtolower') ? mb_strtolower((string) $v, 'UTF-8') : strtolower((string) $v));
    return $v === '' ? null : hash('sha256', $v);
}

/** Saudi numbers → E.164 digits without "+": 05XXXXXXXX / 5XXXXXXXX / +9665XXXXXXXX → 9665XXXXXXXX */
function meta_norm_phone(?string $p): ?string
{
    return fq_meta_norm_phone($p);
}

/** Real visitor IP behind Cloudflare. */
function meta_client_ip(): ?string
{
    return fq_meta_client_ip();
}

/** Meta's browser ids, only in the shapes Meta writes them (anything else would spoil the match). */
function meta_browser_ids(array $meta): array
{
    $fbp = (string) ($meta['fbp'] ?? '');
    $fbc = (string) ($meta['fbc'] ?? '');
    return [
        'fbp' => preg_match('/^fb\.\d\.\d+\.\d+$/', $fbp) ? $fbp : null,
        'fbc' => preg_match('/^fb\.\d\.\d+\.[A-Za-z0-9_\-]{8,500}$/', $fbc) ? $fbc : null,
    ];
}

/**
 * @param string $eventName  'F360_FormOpen_NoFill' | 'F360_FormAbandon' …
 * @param string $eventId    MUST equal the browser eventID (Lead = submission_id)
 * @param array  $pii        ['em'=>…, 'ph'=>…, 'fn'=>…, 'ln'=>…] raw values, hashed here
 * @param array  $meta       browser context: ['fbp'=>…, 'fbc'=>…]
 */
function meta_capi_send(string $eventName, string $eventId, string $sourceUrl,
                        array $pii = [], array $meta = [], array $customData = [],
                        ?int $eventTime = null): array
{
    if (!preg_match('/^[A-Za-z0-9_\-]{8,64}$/', $eventId)) return ['ok' => false, 'skipped' => 'no valid event_id'];

    // a full name in fn becomes first + last
    if (!empty($pii['fn']) && empty($pii['ln'])) {
        [$first, $last] = fq_meta_split_name($pii['fn']);
        $pii['fn'] = $first; $pii['ln'] = $last;
    }
    $email = fq_meta_norm_email($pii['em'] ?? null);
    $phone = fq_meta_norm_phone($pii['ph'] ?? null);
    $ids   = meta_browser_ids($meta);

    $ud = array_filter([
        'em'                => $email ? [fq_meta_hash($email)] : null,
        'ph'                => $phone ? [fq_meta_hash($phone)] : null,
        'fn'                => !empty($pii['fn']) ? [meta_hash($pii['fn'])] : null,
        'ln'                => !empty($pii['ln']) ? [meta_hash($pii['ln'])] : null,
        'country'           => [meta_hash('sa')],
        'external_id'       => $email ? [fq_meta_hash($email)] : null,
        'client_ip_address' => meta_client_ip(),
        'client_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? substr((string) $_SERVER['HTTP_USER_AGENT'], 0, 512) : null,
        'fbp'               => $ids['fbp'],
        'fbc'               => $ids['fbc'],
    ]);

    $event = [
        'event_name'       => $eventName,
        'event_time'       => $eventTime && abs(time() - $eventTime) < 3600 ? $eventTime : time(),
        'event_id'         => $eventId,
        'action_source'    => 'website',
        'event_source_url' => $sourceUrl,
        'user_data'        => $ud,
        'custom_data'      => (object) array_filter($customData, function ($v) { return $v !== null && $v !== ''; }),
    ];
    return fq_meta_post($event);
}
