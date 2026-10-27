<?php
/* Fandaqah 360 · lead funnel report, for the team: api/funnel.php?key=<funnel_key>[&days=30][&c=Existing]
   Reads the anonymous funnel log (api/lead-event.php → logs/funnel-YYYY-MM.jsonl) and shows, per campaign, how many visits
   reached each step, where they came from, and who to retarget. Visits are counted by their random visit id; no person is
   identified anywhere. Switched off (404) until funnel_key is set (FQ_FUNNEL_KEY or api/config.local.php). PHP 7.4 or later. */
declare(strict_types=1);

$config = require __DIR__ . '/config.php';
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex, nofollow');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
$key = (string) ($_GET['key'] ?? '');
if ($config['funnel_key'] === '' || !hash_equals($config['funnel_key'], $key)) { http_response_code(404); exit('Not found'); }

$days = (int) ($_GET['days'] ?? 30);
if (!in_array($days, [7, 30, 90], true)) $days = 30;
$only = isset($_GET['c']) ? substr((string) $_GET['c'], 0, 40) : '';
$since = gmdate('c', time() - $days * 86400);
$dir = getenv('FQ_FUNNEL_DIR') ?: __DIR__ . '/../logs';

/* ---------- read the months that overlap the window ---------- */
$rows = [];
for ($m = strtotime(gmdate('Y-m-01', time() - $days * 86400)); $m <= time(); $m = strtotime('+1 month', $m)) {
    $f = $dir . '/funnel-' . gmdate('Y-m', $m) . '.jsonl';
    if (!is_file($f)) continue;
    foreach (new SplFileObject($f) as $line) {
        $r = json_decode((string) $line, true);
        if (is_array($r) && ($r['t'] ?? '') >= $since && isset($r['v'], $r['e'])) $rows[] = $r;
    }
}

/* ---------- visits per step ---------- */
const STEPS = [
    'page_view'        => 'Visited the page',
    'engaged'          => 'Read on (half the page or 30 s)',
    'click'            => 'Clicked a call to action',
    'lead_form_open'   => 'Opened the form',
    'lead_form_submit' => 'Pressed send',
    'lead_success'     => 'Request received by the CRM',
];
$CLICKS = ['cta_click', 'whatsapp_click', 'call_click', 'email_click'];
$campaigns = [];
$visit = [];   // visit id → [campaign, source, steps set, first day]
foreach ($rows as $r) {
    $v = $r['v'];
    if (!isset($visit[$v])) $visit[$v] = ['c' => $r['c'] ?? 'Other', 'src' => $r['src'] ?? ($r['ref'] ?? 'Direct'), 'day' => substr($r['t'], 0, 10), 's' => []];
    $e = in_array($r['e'], $CLICKS, true) ? 'click' : $r['e'];
    $visit[$v]['s'][$e] = true;
    $visit[$v]['s'][$r['e']] = true;
    $campaigns[$visit[$v]['c']] = true;
}
ksort($campaigns);
$pick = array_filter($visit, function ($x) use ($only) { return $only === '' || $x['c'] === $only; });
$count = function (array $set, string $step): int { $n = 0; foreach ($set as $x) if (!empty($x['s'][$step])) $n++; return $n; };

// a funnel narrows: each visit counts at every step up to the furthest one it reached (a visitor can open an on-page form
// without clicking a button first, so the steps are not counted one by one)
$order = array_keys(STEPS);
$funnel = array_fill_keys($order, 0);
foreach ($pick as $x) {
    $deepest = 0;
    foreach ($order as $i => $step) if (!empty($x['s'][$step])) $deepest = $i;
    for ($i = 0; $i <= $deepest; $i++) $funnel[$order[$i]]++;
}
$top = max(1, $funnel['page_view']);
$sources = [];
foreach ($pick as $x) {
    $s = $x['src'];
    $sources[$s] = $sources[$s] ?? ['visits' => 0, 'open' => 0, 'lead' => 0, 'wa' => 0];
    $sources[$s]['visits']++;
    $sources[$s]['open'] += empty($x['s']['lead_form_open']) ? 0 : 1;
    $sources[$s]['lead'] += empty($x['s']['lead_success']) ? 0 : 1;
    $sources[$s]['wa'] += empty($x['s']['whatsapp_click']) ? 0 : 1;
}
uasort($sources, function ($a, $b) { return $b['visits'] <=> $a['visits']; });
$daily = [];
for ($d = $days - 1; $d >= 0; $d--) $daily[gmdate('Y-m-d', time() - $d * 86400)] = ['visits' => 0, 'leads' => 0];
foreach ($pick as $x) if (isset($daily[$x['day']])) { $daily[$x['day']]['visits']++; $daily[$x['day']]['leads'] += empty($x['s']['lead_success']) ? 0 : 1; }
$dayMax = max(array_merge([1], array_column($daily, 'visits')));
$pool = ['opened' => 0, 'pressed' => 0, 'whatsapp' => 0];
foreach ($pick as $x) {
    if (empty($x['s']['lead_success'])) {
        if (!empty($x['s']['lead_form_open']) && empty($x['s']['lead_form_submit'])) $pool['opened']++;
        if (!empty($x['s']['lead_form_submit'])) $pool['pressed']++;
    }
    if (!empty($x['s']['whatsapp_click'])) $pool['whatsapp']++;
}
$whatsapp = $count($pick, 'whatsapp_click'); $calls = $count($pick, 'call_click');

$h = function ($s): string { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
$pct = function (int $a, int $b): string { return $b > 0 ? round($a / $b * 100, 1) . '%' : '–'; };
$link = function (array $q) use ($key, $days, $only, $h): string {
    return $h('?' . http_build_query(array_merge(['key' => $key, 'days' => $days, 'c' => $only], $q)));
};
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex, nofollow">
<title>Lead Funnel · Fandaqah 360</title>
<style>
:root{--bg:#F0EDE9;--surface:#FFFFFF;--ink:#18172F;--ink-2:#4A4960;--muted:#6B6A80;--line:#E3DED7;--bar:#C9413B;--track:#F4F1EC;--accent:#2A273C}
@media (prefers-color-scheme:dark){:root{--bg:#141320;--surface:#1E1C2C;--ink:#F2F0EB;--ink-2:#C9C6D6;--muted:#9C99AE;--line:#2F2C40;--bar:#E4675F;--track:#262437;--accent:#F2F0EB}}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font:15px/1.55 "Segoe UI",system-ui,-apple-system,sans-serif}
main{max-width:1080px;margin:0 auto;padding:32px 16px 64px}
header{display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;justify-content:space-between;margin-bottom:24px}
h1{margin:0;font-size:28px;letter-spacing:-.5px}h1 small{display:block;font-size:13px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
h2{font-size:16px;margin:0 0 14px}.note{color:var(--muted);font-size:13px;margin:6px 0 0}
.filters{display:flex;flex-wrap:wrap;gap:8px}.filters a{padding:7px 13px;border-radius:999px;border:1px solid var(--line);background:var(--surface);color:var(--ink-2);text-decoration:none;font-size:13px;font-weight:600}
.filters a[aria-current]{background:var(--accent);border-color:var(--accent);color:var(--bg)}
.row{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px}
.tiles{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:20px}
.tile,.card{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:18px}
.tile b{display:block;font-size:30px;line-height:1.1;letter-spacing:-.5px;font-variant-numeric:tabular-nums}.tile span{color:var(--muted);font-size:13px}
.card{margin-bottom:16px}
.step{display:grid;grid-template-columns:minmax(150px,240px) 1fr 120px;gap:12px;align-items:center;padding:7px 0}
.step .lab{color:var(--ink-2);font-size:14px}.track{background:var(--track);border-radius:4px;height:26px;overflow:hidden}
.bar{height:100%;background:var(--bar);border-radius:0 4px 4px 0;min-width:2px}
.num{text-align:right;font-variant-numeric:tabular-nums;font-size:14px}.num b{display:block;color:var(--ink)}.num span{color:var(--muted);font-size:12px}
table{width:100%;border-collapse:collapse;font-size:14px;font-variant-numeric:tabular-nums}th,td{padding:9px 8px;border-bottom:1px solid var(--line);text-align:right}
th:first-child,td:first-child{text-align:left}th{color:var(--muted);font-weight:600;font-size:12px;text-transform:uppercase;letter-spacing:.04em}
.days{display:flex;align-items:flex-end;gap:2px;height:120px;border-bottom:1px solid var(--line)}
.days i{flex:1;background:var(--bar);border-radius:3px 3px 0 0;min-height:1px;opacity:.9}.days i:hover{opacity:1;outline:2px solid var(--surface)}
.axis{display:flex;justify-content:space-between;color:var(--muted);font-size:12px;margin-top:6px}
.empty{color:var(--muted);padding:24px 0;text-align:center}
.scroll{overflow-x:auto;margin:0 -4px;padding:0 4px}
@media (max-width:640px){.step{grid-template-columns:1fr 92px}.step .track{grid-column:1/-1;grid-row:2}}
</style>
</head>
<body>
<main>
<header>
  <div><h1><small>Fandaqah 360</small>Lead funnel</h1>
  <p class="note">Last <?= $days ?> days · <?= $only === '' ? 'all campaigns' : $h($only) ?> · anonymous visits, no personal data</p></div>
  <nav class="filters" aria-label="Period">
    <?php foreach ([7, 30, 90] as $d): ?><a href="<?= $link(['days' => $d]) ?>"<?= $d === $days ? ' aria-current="true"' : '' ?>><?= $d ?> days</a><?php endforeach; ?>
  </nav>
</header>
<nav class="filters row" aria-label="Campaign">
  <a href="<?= $link(['c' => '']) ?>"<?= $only === '' ? ' aria-current="true"' : '' ?>>All campaigns</a>
  <?php foreach (array_keys($campaigns) as $c): ?><a href="<?= $link(['c' => $c]) ?>"<?= $only === $c ? ' aria-current="true"' : '' ?>><?= $h($c) ?></a><?php endforeach; ?>
</nav>

<section class="tiles" aria-label="Headline numbers">
  <div class="tile"><b><?= number_format($funnel['page_view']) ?></b><span>Visits</span></div>
  <div class="tile"><b><?= number_format($funnel['lead_success']) ?></b><span>Requests received</span></div>
  <div class="tile"><b><?= $pct($funnel['lead_success'], $funnel['page_view']) ?></b><span>Visit → request</span></div>
  <div class="tile"><b><?= number_format($whatsapp) ?></b><span>WhatsApp chats opened</span></div>
  <div class="tile"><b><?= number_format($calls) ?></b><span>Calls tapped</span></div>
</section>

<section class="card" aria-labelledby="f-h">
  <h2 id="f-h">Where visits drop off</h2>
  <p class="note" style="margin:-8px 0 10px">Visits that got at least this far.</p>
  <?php if (!$funnel['page_view']): ?><p class="empty">No visits recorded in this period yet.</p><?php else: $prev = null; foreach (STEPS as $step => $label): $n = $funnel[$step]; ?>
  <div class="step" title="<?= $h($label) ?>: <?= $n ?> visits (<?= $pct($n, $top) ?> of all visits)">
    <span class="lab"><?= $h($label) ?></span>
    <div class="track" role="img" aria-label="<?= $h($label) ?>: <?= $n ?> visits, <?= $pct($n, $top) ?> of all"><div class="bar" style="width:<?= round($n / $top * 100, 2) ?>%"></div></div>
    <span class="num"><b><?= number_format($n) ?></b><span><?= $prev === null ? '100%' : $pct($n, $prev) . ' of previous' ?></span></span>
  </div>
  <?php $prev = $n; endforeach; endif; ?>
</section>

<section class="card" aria-labelledby="r-h">
  <h2 id="r-h">Retargeting audiences (build them in each ad platform)</h2>
  <table>
    <thead><tr><th>Audience</th><th>Visits</th></tr></thead>
    <tbody>
      <tr><td>Opened the form, did not press send</td><td><?= number_format($pool['opened']) ?></td></tr>
      <tr><td>Pressed send, no request received (check the form or the CRM)</td><td><?= number_format($pool['pressed']) ?></td></tr>
      <tr><td>Opened a WhatsApp chat</td><td><?= number_format($pool['whatsapp']) ?></td></tr>
    </tbody>
  </table>
  <p class="note">Counted here to size each audience; the audiences themselves live in TikTok, LinkedIn, Snapchat and Meta, built from the same steps.</p>
</section>

<section class="card" aria-labelledby="s-h">
  <h2 id="s-h">By source</h2>
  <?php if (!$sources): ?><p class="empty">No visits recorded in this period yet.</p><?php else: ?>
  <div class="scroll"><table>
    <thead><tr><th>Source</th><th>Visits</th><th>Form opened</th><th>Requests</th><th>Visit → request</th><th>WhatsApp</th></tr></thead>
    <tbody><?php foreach (array_slice($sources, 0, 15, true) as $s => $x): ?>
      <tr><td><?= $h($s) ?></td><td><?= number_format($x['visits']) ?></td><td><?= number_format($x['open']) ?></td><td><?= number_format($x['lead']) ?></td><td><?= $pct($x['lead'], $x['visits']) ?></td><td><?= number_format($x['wa']) ?></td></tr>
    <?php endforeach; ?></tbody>
  </table></div><?php endif; ?>
</section>

<section class="card" aria-labelledby="d-h">
  <h2 id="d-h">Visits per day</h2>
  <div class="days" role="img" aria-label="Visits per day, last <?= $days ?> days">
    <?php foreach ($daily as $day => $x): ?><i style="height:<?= round($x['visits'] / $dayMax * 100, 2) ?>%" title="<?= $h($day) ?>: <?= $x['visits'] ?> visits, <?= $x['leads'] ?> requests"></i><?php endforeach; ?>
  </div>
  <div class="axis"><span><?= $h(array_key_first($daily)) ?></span><span>peak <?= number_format($dayMax) ?> visits</span><span><?= $h(array_key_last($daily)) ?></span></div>
  <details><summary class="note">Show as a table</summary>
    <table><thead><tr><th>Day</th><th>Visits</th><th>Requests</th></tr></thead><tbody>
    <?php foreach (array_reverse($daily, true) as $day => $x): ?><tr><td><?= $h($day) ?></td><td><?= $x['visits'] ?></td><td><?= $x['leads'] ?></td></tr><?php endforeach; ?>
    </tbody></table></details>
</section>
</main>
</body>
</html>
