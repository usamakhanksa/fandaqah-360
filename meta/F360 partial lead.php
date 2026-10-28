<?php
/**
 * f360-partial-lead.php — CRM call list of visitors who typed a valid phone number and consented,
 * but did not (yet) submit the F360 lead form.
 *
 * This is the ONLY source for manual call-backs. Pixel / Meta audiences contain no phone numbers
 * and must never be used to build a dialing list.
 *
 * Input  (JSON, from f360-formopen.js): visit_id, phone (9665XXXXXXXX), name?, company?, email?,
 *        campaign_type, landing_page, consent ('checkbox' | 'notice'), page_url, captured_at, website (honeypot)
 * Store  : one JSON line per visit in F360_PARTIAL_DIR (outside the web root), file per month.
 * Forward: optional POST to F360_CRM_PARTIAL_WEBHOOK (CRM "Partial lead" queue).
 * Retention: rows older than F360_PARTIAL_DAYS (default 30) are dropped by f360_partial_purge().
 *
 * Env:
 *   F360_PARTIAL_DIR          e.g. /var/lib/fandaqah/partial-leads   (must NOT be web-accessible)
 *   F360_CRM_PARTIAL_WEBHOOK  optional https URL
 *   F360_PARTIAL_DAYS         optional, default 30
 */

require_once __DIR__ . '/f360-partial-lib.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('{"ok":false}'); }

$host = parse_url($_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '', PHP_URL_HOST);
if (!$host || !preg_match('/(^|\.)fandaqah\.com$/i', $host)) { http_response_code(403); exit('{"ok":false}'); }

$in = json_decode(file_get_contents('php://input'), true) ?: [];

// Honeypot → pretend success, store nothing
if (!empty($in['website'])) { echo '{"ok":true}'; exit; }

// Consent is mandatory
$consent = $in['consent'] ?? '';
if (!in_array($consent, ['checkbox', 'notice'], true)) { http_response_code(422); exit('{"ok":false,"error":"no_consent"}'); }

// Saudi mobile only
$phone = preg_replace('/\D+/', '', (string)($in['phone'] ?? ''));
if (!preg_match('/^9665\d{8}$/', $phone)) { http_response_code(422); exit('{"ok":false,"error":"phone"}'); }

// Simple per-IP rate limit: 10 rows / hour
$ip  = trim(explode(',', $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '')[0]);
$dir = rtrim(getenv('F360_PARTIAL_DIR') ?: '', '/');
if ($dir === '' || !is_dir($dir) || !is_writable($dir)) { error_log('[partial] F360_PARTIAL_DIR not writable'); http_response_code(500); exit('{"ok":false}'); }
$rl = $dir . '/.rl_' . hash('sha256', $ip . date('YmdH'));
$n  = (int)@file_get_contents($rl);
if ($n >= 10) { http_response_code(429); exit('{"ok":false}'); }
@file_put_contents($rl, (string)($n + 1), LOCK_EX);

$clip = fn($v, $max) => mb_substr(trim(strip_tags((string)$v)), 0, $max, 'UTF-8');
$campaign = in_array($in['campaign_type'] ?? '', ['existing_customer', 'new_customer'], true) ? $in['campaign_type'] : 'other';

$row = [
    'status'        => 'partial',                    // partial → called / converted / no_answer (CRM)
    'visit_id'      => preg_replace('/[^A-Za-z0-9\-]/', '', (string)($in['visit_id'] ?? '')),
    'phone'         => $phone,
    'name'          => $clip($in['name'] ?? '', 80),
    'company'       => $clip($in['company'] ?? '', 120),
    'email'         => filter_var($in['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '',
    'campaign_type' => $campaign,
    'landing_page'  => $clip($in['landing_page'] ?? '', 120),
    'consent'       => $consent,
    'page_url'      => $clip($in['page_url'] ?? '', 300),
    'captured_at'   => gmdate('c'),
];

$file = sprintf('%s/partial-%s.jsonl', $dir, gmdate('Y-m'));
file_put_contents($file, json_encode($row, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND | LOCK_EX);
@chmod($file, 0600);

if ($hook = getenv('F360_CRM_PARTIAL_WEBHOOK')) {
    $ch = curl_init($hook);
    curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($row, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'], CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 3]);
    curl_exec($ch); curl_close($ch);
}

echo '{"ok":true}';