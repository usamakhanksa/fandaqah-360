<?php
/**
 * meta-event.php — server copy (CAPI) of browser-side custom events.   second meta/ pack, 28 Oct 2026
 * Receives {event_name, event_id, event_time, event_source_url, fbp, fbc, custom_data}
 * from assets/js/f360-formopen.js and forwards to Meta with the SAME event_id → deduplicated.
 * No PII is accepted here (form not submitted yet) — matching relies on fbp/fbc/IP/UA.
 *
 * On top of the pack's rules (POST only, two whitelisted names, same site): this site's own host is accepted too (the copy on
 * a staging host or localhost), the body is bounded, one address is rate-limited, the event must come from one of the two
 * Fandaqah 360 pages, every custom value is whitelisted and bounded, and nothing is sent until META_CAPI_TOKEN is set.
 * Answers: 200 {ok} · 400 not an F360 event · 403 other site · 405 not POST · 413 too large · 429 too many. PHP 7.4 or later.
 */
declare(strict_types=1);
ini_set('display_errors', '0');   // notices go to the error log, never into the answer
require_once __DIR__ . '/meta-capi.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
function answer(int $status, bool $ok): void { http_response_code($status); exit(json_encode(['ok' => $ok])); }

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { header('Allow: POST'); answer(405, false); }

// Same site only: fandaqah.com (and its subdomains) or the host serving this file
$from = (string) ($_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '');
if ($from !== '' && $from !== 'null') {
    $host = (string) parse_url($from, PHP_URL_HOST);
    $hostPort = $host . (parse_url($from, PHP_URL_PORT) ? ':' . parse_url($from, PHP_URL_PORT) : '');
    if (!preg_match('/(^|\.)fandaqah\.com$/i', $host) && $hostPort !== ($_SERVER['HTTP_HOST'] ?? '')) answer(403, false);
}

const MAX_BODY = 4096;
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > MAX_BODY) answer(413, false);
$raw = (string) file_get_contents('php://input', false, null, 0, MAX_BODY + 1);
if (strlen($raw) > MAX_BODY) answer(413, false);
$in = json_decode($raw, true);
if (!is_array($in)) answer(400, false);

$ALLOWED = ['F360_FormOpen_NoFill', 'F360_Phone_Captured', 'F360_FormAbandon'];   // micro events only — Lead goes through lead.php
$name = (string) ($in['event_name'] ?? '');
$id   = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) ($in['event_id'] ?? ''));
if (!in_array($name, $ALLOWED, true) || strlen($id) < 8 || strlen($id) > 64) answer(400, false);

// only the two Fandaqah 360 landing pages send these
$src = (string) ($in['event_source_url'] ?? '');
if (!preg_match('~/(existing-customers|fandaqah-360-landing-v2)(/|$)~', (string) parse_url($src, PHP_URL_PATH))) answer(400, false);
if (!preg_match('#^https://(www\.)?fandaqah\.com/#i', $src)) $src = 'https://fandaqah.com/landing/fandaqah-360/';
$src = substr($src, 0, 1000);

/* ---------- one address cannot flood the dataset (a real visit sends two of these at most) ---------- */
$ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$file = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'fq-metaev-' . hash('sha256', $ip . '|' . __DIR__) . '.json';
$now  = time();
$fh   = @fopen($file, 'c+');
if ($fh && flock($fh, LOCK_EX)) {
    $hits = json_decode((string) stream_get_contents($fh), true);
    $hits = array_values(array_filter(is_array($hits) ? $hits : [], function ($t) use ($now) { return is_int($t) && $t > $now - 600; }));
    $over = count($hits) >= 30;
    if (!$over) { $hits[] = $now; ftruncate($fh, 0); rewind($fh); fwrite($fh, json_encode($hits)); }
    flock($fh, LOCK_UN); fclose($fh);
    if ($over) answer(429, false);
}

// the custom values the pack sends, each a short plain word (array_filter in meta_capi_send keeps false: it is neither null nor '')
$custom = [];
foreach (['campaign_type', 'landing_page', 'form_name', 'trigger'] as $k) {
    $v = $in['custom_data'][$k] ?? null;
    if (is_string($v) && preg_match('/^[A-Za-z0-9_\-\/.]{1,80}$/', $v)) $custom[$k] = $v;
}
foreach (['phone_valid', 'phone_given'] as $k)   // yes/no flags only: no phone, name or email ever reaches Meta from here
    if (is_bool($in['custom_data'][$k] ?? null)) $custom[$k] = $in['custom_data'][$k];

$r = meta_capi_send($name, $id, $src, [], [
        'fbp' => is_string($in['fbp'] ?? null) ? $in['fbp'] : null,
        'fbc' => is_string($in['fbc'] ?? null) ? $in['fbc'] : null,
     ], $custom, isset($in['event_time']) && is_numeric($in['event_time']) ? (int) $in['event_time'] : null);

answer(200, !empty($r['ok']));
