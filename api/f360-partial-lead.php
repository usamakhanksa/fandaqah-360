<?php
/**
 * f360-partial-lead.php — CRM call list of visitors who typed a valid phone number and consented,
 * but did not (yet) submit the F360 lead form.   second meta/ pack, 28 Oct 2026
 *
 * This is the ONLY source for manual call-backs. Pixel / Meta audiences contain no phone numbers
 * and must never be used to build a dialing list.
 *
 * Input  (JSON, from assets/js/f360-formopen.js): visit_id, phone (9665XXXXXXXX), name?, company?, email?,
 *        campaign_type, landing_page, consent ('checkbox' | 'notice'), page_url, captured_at, website (honeypot)
 * Store  : api/f360-partial-lib.php: one JSON line per visit, a file per month, in the folder api/config.php names
 *          (F360_PARTIAL_DIR, outside the web root where the host allows it; else logs/partial, denied to the web).
 * Forward: optional POST to F360_CRM_PARTIAL_WEBHOOK (CRM "Partial lead" queue).
 * Retention: rows older than F360_PARTIAL_DAYS (default 30) are dropped by f360_partial_purge().
 * Read   : api/partial-leads.php?key=… (the call list), or the CRM queue.
 *
 * On top of the pack's rules (POST only, same site, honeypot, consent mandatory, Saudi mobile only, 10 rows per address per
 * hour): this site's own host is accepted too (staging, localhost); the body is bounded; only the two F360 pages are accepted;
 * one row per visit (a second number typed in the same visit updates nothing); a number that already sent its request is not
 * listed again; PHP 7.4 compatible; answers never carry PHP notices.
 * Answers: 200 {ok} · 400 unreadable / not an F360 page · 403 other site · 405 not POST · 413 too large · 422 no consent / phone
 *          · 429 too many · 503 store not writable.
 */
declare(strict_types=1);
ini_set('display_errors', '0');
require_once __DIR__ . '/f360-partial-lib.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
function partial_answer(int $status, array $body): void { http_response_code($status); exit(json_encode($body)); }

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { header('Allow: POST'); partial_answer(405, ['ok' => false]); }

// Same site only, and the browser must say where it comes from (a phone number is personal data)
$from = (string) ($_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '');
$host = (string) parse_url($from, PHP_URL_HOST);
$hostPort = $host . (parse_url($from, PHP_URL_PORT) ? ':' . parse_url($from, PHP_URL_PORT) : '');
if ($host === '' || (!preg_match('/(^|\.)fandaqah\.com$/i', $host) && $hostPort !== ($_SERVER['HTTP_HOST'] ?? ''))) partial_answer(403, ['ok' => false]);

const MAX_BODY = 4096;
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > MAX_BODY) partial_answer(413, ['ok' => false]);
$raw = (string) file_get_contents('php://input', false, null, 0, MAX_BODY + 1);
if (strlen($raw) > MAX_BODY) partial_answer(413, ['ok' => false]);
$in = json_decode($raw, true);
if (!is_array($in)) partial_answer(400, ['ok' => false]);

// Honeypot → pretend success, store nothing
if (trim((string) ($in['website'] ?? '')) !== '') partial_answer(200, ['ok' => true]);

// Consent is mandatory
$consent = $in['consent'] ?? '';
if (!in_array($consent, ['checkbox', 'notice'], true)) partial_answer(422, ['ok' => false, 'error' => 'no_consent']);

// Saudi mobile only
$phone = f360_partial_phone($in['phone'] ?? '');
if ($phone === null) partial_answer(422, ['ok' => false, 'error' => 'phone']);

// the two Fandaqah 360 landing pages only
$pageUrl = (string) ($in['page_url'] ?? '');
if (!preg_match('~/(existing-customers|fandaqah-360-landing-v2)(/|$)~', (string) parse_url($pageUrl, PHP_URL_PATH))) partial_answer(400, ['ok' => false]);

$dir = f360_partial_dir();
if ($dir === null) partial_answer(503, ['ok' => false]);

// Simple per-IP rate limit: 10 rows / hour (counted outside the store, like the other endpoints)
$ip = trim(explode(',', (string) ($_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? ''))[0]);
$rl = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'fq-partial-' . hash('sha256', $ip . '|' . __DIR__ . '|' . date('YmdH')) . '.txt';
$n  = (int) @file_get_contents($rl);
if ($n >= 10) partial_answer(429, ['ok' => false]);
@file_put_contents($rl, (string) ($n + 1), LOCK_EX);

$clip = function ($v, int $max): string {
    $v = trim(strip_tags(preg_replace('/[\x00-\x1F\x7F]+/u', ' ', (string) $v) ?? ''));
    return function_exists('mb_substr') ? mb_substr($v, 0, $max, 'UTF-8') : substr($v, 0, $max);
};
$campaign = in_array($in['campaign_type'] ?? '', ['existing_customer', 'new_customer'], true) ? $in['campaign_type'] : 'other';
$visit = substr(preg_replace('/[^A-Za-z0-9\-]/', '', (string) ($in['visit_id'] ?? '')), 0, 64);

// one row per visit, and a number already on the list (still to call, or converted) is not added again
foreach (f360_partial_rows() as $r)
    if (($visit !== '' && ($r['visit_id'] ?? '') === $visit) || ($r['phone'] ?? '') === $phone) partial_answer(200, ['ok' => true]);

$row = [
    'status'        => 'partial',                    // partial → converted (lead.php) · called / no_answer (CRM)
    'visit_id'      => $visit,
    'phone'         => $phone,
    'name'          => $clip($in['name'] ?? '', 80),
    'company'       => $clip($in['company'] ?? '', 120),
    'email'         => filter_var(trim((string) ($in['email'] ?? '')), FILTER_VALIDATE_EMAIL) ?: '',
    'campaign_type' => $campaign,
    'landing_page'  => $clip($in['landing_page'] ?? '', 120),
    'consent'       => $consent,
    'page_url'      => $clip($pageUrl, 300),
    'captured_at'   => gmdate('c'),
];
if (!f360_partial_add($row)) partial_answer(503, ['ok' => false]);

$hook = (string) (f360_partial_settings()['webhook'] ?? '');
if ($hook !== '' && preg_match('#^https://#i', $hook) && function_exists('curl_init')) {
    $ch = curl_init($hook);
    curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($row, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'], CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_TIMEOUT => 3]);
    curl_exec($ch);
    if ((int) curl_getinfo($ch, CURLINFO_HTTP_CODE) >= 300) error_log('[partial] CRM webhook answered ' . (int) curl_getinfo($ch, CURLINFO_HTTP_CODE));
    unset($ch);   // curl_close() is deprecated in PHP 8.5
}

partial_answer(200, ['ok' => true]);
