<?php
/* Fandaqah 360 · partial-lead call list, for the sales team: api/partial-leads.php?key=<partial_key>[&show=all][&format=csv]
   The visitors who typed a valid Saudi mobile on one of the two F360 pages and consented (the contact checkbox on
   existing-customers, the notice under the number on landing-v2), but did not send the form. A number whose request later
   reached the CRM is marked converted by lead.php and leaves the list. Rows are kept partial_days (default 30), then dropped.
   Switched off (404) until partial_key is set (F360_PARTIAL_KEY or api/config.local.php); the key is personal-data access,
   so share it only with the people who make the calls. noindex, no-store. PHP 7.4 or later. */
declare(strict_types=1);
ini_set('display_errors', '0');
require_once __DIR__ . '/f360-partial-lib.php';

$want = (string) (f360_partial_settings()['key'] ?? '');
$key  = (string) ($_GET['key'] ?? '');
if ($want === '' || !hash_equals($want, $key)) { http_response_code(404); exit('Not found'); }
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex, nofollow');
header('Referrer-Policy: no-referrer');

f360_partial_purge();
$all  = ($_GET['show'] ?? '') === 'all';
$rows = array_values(array_filter(f360_partial_rows(), function ($r) use ($all) { return $all || ($r['status'] ?? '') === 'partial'; }));
$cols = ['captured_at' => 'Captured (UTC)', 'status' => 'Status', 'phone' => 'Mobile', 'name' => 'Name', 'company' => 'Company', 'email' => 'Email',
         'campaign_type' => 'Campaign', 'ad_platform' => 'Ad platform', 'utm_campaign' => 'Ad campaign', 'consent' => 'Consent',
         'visit_id' => 'Clarity session', 'page_url' => 'Page'];

if (($_GET['format'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="f360-partial-leads-' . gmdate('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");   // Excel opens Arabic names correctly with the BOM
    fputcsv($out, array_values($cols));
    foreach ($rows as $r) fputcsv($out, array_map(function ($k) use ($r) {
        $v = (string) ($r[$k] ?? '');
        if ($k === 'phone' && $v !== '') $v = '+' . $v;
        return preg_match('/^[=+\-@]/', $v) && $k !== 'phone' ? "'" . $v : $v;   // no spreadsheet formulas from visitor text
    }, array_keys($cols)));
    exit;
}

$h = function ($s): string { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
$q = function (array $p) use ($key, $all, $h): string { return $h('?' . http_build_query(array_merge(['key' => $key] + ($all ? ['show' => 'all'] : []), $p))); };
$open = count(array_filter(f360_partial_rows(), function ($r) { return ($r['status'] ?? '') === 'partial'; }));
header('Content-Type: text/html; charset=utf-8');
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex, nofollow">
<title>Call-back List · Fandaqah 360</title>
<style>
:root{--bg:#F0EDE9;--surface:#FFFFFF;--ink:#18172F;--ink-2:#4A4960;--muted:#6B6A80;--line:#E3DED7;--accent:#2A273C;--ok:#2F7D5B}
@media (prefers-color-scheme:dark){:root{--bg:#141320;--surface:#1E1C2C;--ink:#F2F0EB;--ink-2:#C9C6D6;--muted:#9C99AE;--line:#2F2C40;--accent:#F2F0EB;--ok:#6BC39A}}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font:15px/1.55 "Segoe UI",system-ui,-apple-system,sans-serif}
main{max-width:1180px;margin:0 auto;padding:32px 16px 64px}
header{display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;justify-content:space-between;margin-bottom:20px}
h1{margin:0;font-size:28px;letter-spacing:-.5px}h1 small{display:block;font-size:13px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
.note{color:var(--muted);font-size:13px;margin:6px 0 0;max-width:640px}
.filters{display:flex;flex-wrap:wrap;gap:8px}.filters a{padding:7px 13px;border-radius:999px;border:1px solid var(--line);background:var(--surface);color:var(--ink-2);text-decoration:none;font-size:13px;font-weight:600}
.filters a[aria-current]{background:var(--accent);border-color:var(--accent);color:var(--bg)}
.card{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:6px 18px 12px}
.scroll{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:14px;font-variant-numeric:tabular-nums}th,td{padding:10px 8px;border-bottom:1px solid var(--line);text-align:left;vertical-align:top}
th{color:var(--muted);font-weight:600;font-size:12px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap}tr:last-child td{border-bottom:0}
td.tel a{color:var(--ink);font-weight:700;text-decoration:none;white-space:nowrap}td.tel a:hover{text-decoration:underline}
.st{font-size:12px;font-weight:700;padding:2px 8px;border-radius:999px;border:1px solid var(--line);white-space:nowrap}.st.converted{color:var(--ok);border-color:currentColor}
.page{color:var(--muted);font-size:12px;word-break:break-all;max-width:260px}
td.sid code{font:12px ui-monospace,Consolas,monospace;color:var(--ink-2);cursor:help}
.empty{color:var(--muted);padding:28px 0;text-align:center}
</style>
</head>
<body>
<main>
<header>
  <div><h1><small>Fandaqah 360</small>Call-back list</h1>
  <p class="note"><?= $open ?> to call · visitors who typed their mobile and consented to be contacted, but did not send the form.
  Kept <?= (int) (f360_partial_settings()['days'] ?? 30) ?> days. Never build an ad audience from this list.</p>
  <p class="note">To watch what a visitor did before leaving: Microsoft Clarity → Recordings → Filters → Custom tags →
  <b>f360_visit</b> = the full Clarity session id (hover it, or take it from the CSV).</p></div>
  <nav class="filters" aria-label="Show">
    <a href="<?= $h('?' . http_build_query(['key' => $key])) ?>"<?= $all ? '' : ' aria-current="true"' ?>>To call</a>
    <a href="<?= $h('?' . http_build_query(['key' => $key, 'show' => 'all'])) ?>"<?= $all ? ' aria-current="true"' : '' ?>>All, with converted</a>
    <a href="<?= $q(['format' => 'csv']) ?>">Download CSV</a>
  </nav>
</header>
<div class="card"><div class="scroll">
<?php if (!$rows): ?>
  <p class="empty">Nobody to call right now.</p>
<?php else: ?>
  <table>
    <thead><tr><?php foreach ($cols as $label) echo '<th scope="col">' . $h($label) . '</th>'; ?></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): $tel = '+' . ($r['phone'] ?? ''); ?>
      <tr>
        <td><?= $h(str_replace(['T', '+00:00'], [' ', ''], (string) ($r['captured_at'] ?? ''))) ?></td>
        <td><span class="st <?= $h($r['status'] ?? '') ?>"><?= $h($r['status'] ?? '') ?></span></td>
        <td class="tel"><a href="tel:<?= $h($tel) ?>" dir="ltr"><?= $h($tel) ?></a></td>
        <td dir="auto"><?= $h($r['name'] ?? '') ?></td>
        <td dir="auto"><?= $h($r['company'] ?? '') ?></td>
        <td><?= ($r['email'] ?? '') !== '' ? '<a href="mailto:' . $h($r['email']) . '">' . $h($r['email']) . '</a>' : '' ?></td>
        <td><?= $h(str_replace('_', ' ', (string) ($r['campaign_type'] ?? ''))) ?></td>
        <td><?= $h($r['ad_platform'] ?? '') ?></td>
        <td dir="auto"><?= $h($r['utm_campaign'] ?? '') ?></td>
        <td><?= $h($r['consent'] ?? '') ?></td>
        <td class="sid"><?= ($r['visit_id'] ?? '') !== '' ? '<code title="' . $h($r['visit_id']) . '">' . $h(substr((string) $r['visit_id'], 0, 8)) . '…</code>' : '' ?></td>
        <td class="page"><?= $h($r['page_url'] ?? '') ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
</div></div>
</main>
</body>
</html>
