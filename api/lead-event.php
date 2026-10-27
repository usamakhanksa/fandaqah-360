<?php
/* Fandaqah 360 · anonymous funnel events (assets/js/lead-capture.js → here → logs/funnel-YYYY-MM.jsonl → api/funnel.php).
   Keeps only the journey: which step, on which page, from which campaign. Every field is whitelisted and bounded, anything else
   is dropped, and a label that looks like an email address or a phone number is blanked, so no personal data can be stored even
   by a hand-made request. No IP address is written. Same site only (the browser sends a beacon), POST only, rate-limited.
   Answers: 204 kept or ignored · 400 unreadable · 403 other site · 405 not POST · 413 too large · 429 too many.
   PHP 7.4 or later. */
declare(strict_types=1);
ini_set('display_errors', '0');   // notices go to the error log, never into the answer (it would break the JSON)

const EVENTS = ['page_view', 'engaged', 'cta_click', 'whatsapp_click', 'call_click', 'email_click',
                'lead_form_open', 'lead_form_submit', 'lead_success', 'newsletter_success'];
const MAX_BODY = 4096;

function done(int $status): void
{
    http_response_code($status);
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { header('Allow: POST'); done(405); }

// this site only: the beacon comes from our own pages
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && $origin !== 'null') {
    $host = parse_url($origin, PHP_URL_HOST) . (parse_url($origin, PHP_URL_PORT) ? ':' . parse_url($origin, PHP_URL_PORT) : '');
    if ($host !== ($_SERVER['HTTP_HOST'] ?? '')) done(403);
}

if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > MAX_BODY) done(413);
$raw = (string) file_get_contents('php://input', false, null, 0, MAX_BODY + 1);
if (strlen($raw) > MAX_BODY) done(413);
$in = json_decode($raw, true);
if (!is_array($in)) done(400);
$event = (string) ($in['event'] ?? '');
if (!in_array($event, EVENTS, true)) done(204);   // not a funnel step: nothing to keep

/* ---------- one address cannot flood the log (a real visit sends a dozen steps at most) ---------- */
$ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$file = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'fq-funnel-' . hash('sha256', $ip . '|' . __DIR__) . '.json';
$now  = time();
$fh   = @fopen($file, 'c+');
if ($fh && flock($fh, LOCK_EX)) {
    $hits = json_decode((string) stream_get_contents($fh), true);
    $hits = array_values(array_filter(is_array($hits) ? $hits : [], function ($t) use ($now) { return is_int($t) && $t > $now - 600; }));
    $over = count($hits) >= 150;
    if (!$over) { $hits[] = $now; ftruncate($fh, 0); rewind($fh); fwrite($fh, json_encode($hits)); }
    flock($fh, LOCK_UN); fclose($fh);
    if ($over) done(429);
}

/* ---------- only these fields, bounded ---------- */
function text($v, int $max, string $pattern = ''): ?string
{
    if (!is_string($v) && !is_int($v)) return null;
    $v = trim(preg_replace('/[\x00-\x1F\x7F]+/u', ' ', (string) $v) ?? '');
    if ($v === '' || ($pattern !== '' && !preg_match($pattern, $v))) return null;
    return function_exists('mb_substr') ? mb_substr($v, 0, $max) : substr($v, 0, $max);
}
$label = text($in['label'] ?? null, 60);
// a label is button text; if it carries an email address or a long run of digits, it is not kept
if ($label !== null && (strpos($label, '@') !== false || preg_match('/\d[\d\s\-]{6,}\d/', $label))) $label = null;

$row = array_filter([
    't'        => gmdate('c'),
    'e'        => $event,
    'v'        => text($in['visit'] ?? null, 32, '/^[A-Za-z0-9]{6,32}$/'),
    'page'     => text($in['page'] ?? null, 160, '~^/[\w\-./]*$~'),
    'c'        => text($in['campaign'] ?? null, 40, '/^[\w \/]+$/u'),
    'seg'      => text($in['segment'] ?? null, 1, '/^[ABCD]$/'),
    'lang'     => text($in['lang'] ?? null, 2, '/^(ar|en)$/'),
    'dev'      => text($in['device'] ?? null, 7, '/^(mobile|desktop)$/'),
    'ref'      => text($in['ref'] ?? null, 100, '/^[a-z0-9.\-]+$/i'),
    'sec'      => isset($in['sec']) && is_numeric($in['sec']) ? max(0, min(86400, (int) $in['sec'])) : null,
    'src'      => text($in['utm_source'] ?? null, 80),
    'med'      => text($in['utm_medium'] ?? null, 80),
    'cmp'      => text($in['utm_campaign'] ?? null, 80),
    'cnt'      => text($in['utm_content'] ?? null, 80),
    'form'     => text($in['form'] ?? null, 40, '/^[\w\-]+$/'),
    'why'      => text($in['why'] ?? null, 20, '/^[\w]+$/'),
    'label'    => $label,
], function ($v) { return $v !== null && $v !== ''; });
if (!isset($row['v'])) done(204);   // a step without a visit cannot be counted

/* ---------- the private log: logs/ answers nothing to the web (logs/.htaccess) ---------- */
$dir = getenv('FQ_FUNNEL_DIR') ?: __DIR__ . '/../logs';
if (!is_dir($dir)) @mkdir($dir, 0750, true);
@file_put_contents($dir . '/funnel-' . gmdate('Y-m') . '.jsonl', json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND | LOCK_EX);
done(204);
