<?php
/**
 * meta-event.php — server copy (CAPI) of browser-side custom events.
 * Receives {event_name, event_id, event_time, event_source_url, fbp, fbc, custom_data}
 * from f360-formopen.js and forwards to Meta with the SAME event_id → deduplicated.
 * No PII is accepted here (form not submitted yet) — matching relies on fbp/fbc/IP/UA.
 */
require_once __DIR__ . '/meta-capi.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('{"ok":false}'); }

// Same-site only
$host = parse_url($_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '', PHP_URL_HOST);
if ($host && !preg_match('/(^|\.)fandaqah\.com$/i', $host)) { http_response_code(403); exit('{"ok":false}'); }

$in = json_decode(file_get_contents('php://input'), true) ?: [];

$ALLOWED = ['F360_FormOpen_NoFill', 'F360_FormAbandon'];   // whitelist — nothing else gets through
$name = (string)($in['event_name'] ?? '');
$id   = preg_replace('/[^A-Za-z0-9_\-]/', '', (string)($in['event_id'] ?? ''));
if (!in_array($name, $ALLOWED, true) || strlen($id) < 8) { http_response_code(400); exit('{"ok":false}'); }

$src = (string)($in['event_source_url'] ?? '');
if (!preg_match('#^https://(www\.)?fandaqah\.com/#i', $src)) $src = 'https://fandaqah.com/landing/fandaqah-360/';

$custom = array_intersect_key((array)($in['custom_data'] ?? []),
    array_flip(['campaign_type', 'landing_page', 'form_name', 'trigger']));

$r = meta_capi_send($name, $id, $src, [], [
        'fbp' => $in['fbp'] ?? null,
        'fbc' => $in['fbc'] ?? null,
     ], $custom, isset($in['event_time']) ? (int)$in['event_time'] : null);

echo json_encode(['ok' => $r['ok']]);
