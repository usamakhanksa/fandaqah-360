<?php
/* Fandaqah · Lead Center, for the sales and marketing leads: api/lead-center.php
   One screen for every visit, click and lead on the site's PHP pages: visitors, WhatsApp and call taps, form opens, submit clicks,
   call-backs and leads, per ad platform (Google Ads, Meta, TikTok, Snapchat, LinkedIn, then everything unpaid), with each lead's
   name, contact details, source, campaign, page, device, browser, address and the journey before it, and whether the CRM took it.
   Filters: period, platform, page, device, lead status and a search; CSV export of leads and visits.
   Reads the lead record (fq-leads-lib.php, written by api/lead.php), the visit log (api/lead-event.php → logs/funnel-*.jsonl)
   and the call-back list (f360-partial-lib.php). Switched off (404) until leads_key is set (FQ_LEADS_KEY or api/config.local.php);
   the key is asked once and kept in a same-site, HTTP-only cookie for this folder, so it never sits in the address bar or the
   browser history. noindex, no-store. PHP 7.4 or later. */
declare(strict_types=1);
ini_set('display_errors', '0');
require_once __DIR__ . '/fq-visitor-lib.php';
require_once __DIR__ . '/fq-leads-lib.php';
require_once __DIR__ . '/f360-partial-lib.php';
$config = require __DIR__ . '/config.php';

header('Cache-Control: no-store');
header('X-Robots-Tag: noindex, nofollow');
header('Referrer-Policy: no-referrer');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

/* ---------- the key: once, then a cookie ---------- */
$want = (string) (fq_leads_settings()['key'] ?? '');
if ($want === '') { http_response_code(404); exit('Not found'); }
$self   = strtok((string) ($_SERVER['REQUEST_URI'] ?? ''), '?') ?: 'lead-center.php';
$https  = ($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? '') !== 'off' || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
$sig    = hash_hmac('sha256', 'fq-lead-center', $want);
$cookie = function (string $v, int $until) use ($self, $https): void {
    setcookie('fq_lc', $v, ['expires' => $until, 'path' => dirname($self), 'secure' => $https, 'httponly' => true, 'samesite' => 'Strict']);
};
if (isset($_GET['logout'])) { $cookie('', time() - 3600); header('Location: ' . $self); exit; }
$tried = $_SERVER['REQUEST_METHOD'] === 'POST' ? (string) ($_POST['key'] ?? '') : (string) ($_GET['key'] ?? '');
if ($tried !== '') {
    // five wrong keys from one address in ten minutes, then it waits
    $ip = fq_client_ip();
    $lock = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'fq-lc-' . hash('sha256', $ip . '|' . __DIR__) . '.json';
    $fails = array_values(array_filter((array) json_decode((string) @file_get_contents($lock), true), function ($t) { return is_int($t) && $t > time() - 600; }));
    if (count($fails) >= 5) { http_response_code(429); exit('Too many attempts. Try again in ten minutes.'); }
    if (hash_equals($want, $tried)) {
        $cookie($sig, time() + 12 * 3600);
        header('Location: ' . $self); exit;
    }
    $fails[] = time(); @file_put_contents($lock, json_encode($fails), LOCK_EX);
    $badKey = true;
}
$h = function ($s): string { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
if (!hash_equals($sig, (string) ($_COOKIE['fq_lc'] ?? ''))) {
    http_response_code(isset($badKey) ? 403 : 200); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex, nofollow">
<title>Lead Center · Fandaqah</title>
<style>:root{--bg:#F0EDE9;--surface:#fff;--ink:#18172F;--muted:#6B6A80;--line:#E3DED7;--accent:#C9413B}
@media (prefers-color-scheme:dark){:root{--bg:#141320;--surface:#1E1C2C;--ink:#F2F0EB;--muted:#9C99AE;--line:#2F2C40;--accent:#E4675F}}
*{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px 16px;background:var(--bg);color:var(--ink);font:15px/1.55 "Segoe UI",system-ui,sans-serif}
form{width:100%;max-width:380px;background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:28px}
h1{margin:0 0 4px;font-size:22px}p{margin:0 0 18px;color:var(--muted);font-size:14px}label{display:block;font-weight:600;font-size:13px;margin-bottom:6px}
input{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:10px;background:var(--bg);color:var(--ink);font:inherit}
button{margin-top:14px;width:100%;padding:11px;border:0;border-radius:999px;background:var(--accent);color:#fff;font:600 15px/1 inherit;cursor:pointer}
.err{color:var(--accent);font-weight:600}</style></head>
<body><form method="post" action="<?= $h($self) ?>"><h1>Lead Center</h1><p>Fandaqah · personal data, for the sales and marketing leads.</p>
<?php if (isset($badKey)): ?><p class="err" role="alert">That key is not right.</p><?php endif; ?>
<label for="k">Access key</label><input id="k" name="key" type="password" autocomplete="current-password" required autofocus>
<button type="submit">Open</button></form></body></html>
<?php exit; }

/* ---------- filters ---------- */
$days   = in_array((int) ($_GET['days'] ?? 30), [1, 7, 30, 90, 180], true) ? (int) ($_GET['days'] ?? 30) : 30;
$fPlat  = array_key_exists((string) ($_GET['plat'] ?? ''), FQ_PLATFORMS) ? (string) $_GET['plat'] : '';
$fDev   = in_array($_GET['dev'] ?? '', ['mobile', 'tablet', 'desktop'], true) ? (string) $_GET['dev'] : '';
$fState = in_array($_GET['state'] ?? '', ['ok', 'problem', 'newsletter', 'all'], true) ? (string) $_GET['state'] : '';
$fQ     = trim(substr((string) ($_GET['q'] ?? ''), 0, 80));
$GROUPS = [
    'existing-customers' => 'Existing customers', 'fandaqah-360-landing-v2' => 'New customers (landing v2)', 'new-hotels' => 'New hotels',
    'opera-pms' => 'Opera / PMS', 'hotel-groups' => 'Hotel groups', 'Fandaqah_Campaign_D' => 'Hotel groups', '/landing/' => 'Fandaqah website',
];
$group = function (string $pathOrUrl) use ($GROUPS): string {
    $p = (string) (strpos($pathOrUrl, '://') !== false ? parse_url($pathOrUrl, PHP_URL_PATH) : $pathOrUrl);
    foreach ($GROUPS as $k => $label) if ($k !== '' && strpos($p, $k) !== false) return $label;
    return 'Campaign site (A–D)';
};
$fPage  = in_array($_GET['page'] ?? '', array_merge(array_values($GROUPS), ['Campaign site (A–D)']), true) ? (string) $_GET['page'] : '';
$riyadh = new DateTimeZone('Asia/Riyadh');
$since  = gmdate('c', $days === 1 ? (new DateTime('today', $riyadh))->getTimestamp() : time() - $days * 86400);   // "today" = the Riyadh day
$own    = fq_host((string) ($_SERVER['HTTP_HOST'] ?? ''));
$RANK   = ['click_id' => 5, 'utm' => 4, 'page_store' => 3, 'in_app' => 2, 'referrer' => 1, 'direct' => 0];
$rank   = function (string $via) use ($RANK): int { return $RANK[explode(':', $via)[0]] ?? 0; };

/* ---------- visits: the visit log, grouped by visit ---------- */
$visits = [];
$dir = getenv('FQ_FUNNEL_DIR') ?: __DIR__ . '/../logs';
for ($m = strtotime(gmdate('Y-m-01', strtotime($since))); $m <= time(); $m = strtotime('+1 month', $m)) {
    $f = $dir . '/funnel-' . gmdate('Y-m', $m) . '.jsonl';
    if (!is_file($f)) continue;
    foreach (explode("\n", (string) file_get_contents($f)) as $line) {
        if (!is_array($r = json_decode($line, true)) || ($r['t'] ?? '') < $since || empty($r['v']) || empty($r['e'])) continue;
        if (!isset($r['plat'])) {   // a step logged before the platform was worked out on the server
            $p = fq_platform(['utm_source' => $r['src'] ?? '', 'utm_medium' => $r['med'] ?? '', 'ref' => fq_host($r['ref'] ?? '') === $own ? '' : ($r['ref'] ?? '')]);
            $r['plat'] = $p['key']; $r['via'] = $p['via'];
        }
        $v = $r['v'];
        if (!isset($visits[$v])) $visits[$v] = ['v' => $v, 't' => $r['t'], 'page' => $r['page'] ?? '', 'plat' => $r['plat'], 'via' => $r['via'] ?? 'direct',
            'dev' => !empty($r['tab']) ? 'tablet' : ($r['dev'] ?? ''), 'os' => $r['os'] ?? '', 'br' => $r['br'] ?? '', 'net' => $r['net'] ?? '', 'cc' => $r['cc'] ?? '',
            'tags' => array_filter(['src' => $r['src'] ?? '', 'med' => $r['med'] ?? '', 'cmp' => $r['cmp'] ?? '', 'cnt' => $r['cnt'] ?? '', 'trm' => $r['trm'] ?? '']),
            'bot' => !empty($r['bot']), 's' => [], 'pages' => [], 'sec' => 0, 'last' => $r['t']];
        $x = &$visits[$v];
        $x['s'][$r['e']] = ($x['s'][$r['e']] ?? 0) + 1;
        if (!empty($r['page'])) $x['pages'][$r['page']] = true;
        $x['sec'] = max($x['sec'], (int) ($r['sec'] ?? 0));
        $x['last'] = max($x['last'], $r['t']);
        if ($rank($r['via'] ?? 'direct') > $rank($x['via'])) { $x['plat'] = $r['plat']; $x['via'] = $r['via']; $x['tags'] = array_filter(['src' => $r['src'] ?? '', 'med' => $r['med'] ?? '', 'cmp' => $r['cmp'] ?? '', 'cnt' => $r['cnt'] ?? '', 'trm' => $r['trm'] ?? '']); }
        unset($x);
    }
}
$bots = count(array_filter($visits, function ($x) { return $x['bot']; }));
$visits = array_filter($visits, function ($x) { return !$x['bot']; });

/* ---------- leads: the lead record, one row per request (a retried request keeps its CRM-accepted row) ---------- */
$byId = [];
foreach (fq_leads_rows($days === 1 ? 2 : $days) as $r) {
    if (($r['t'] ?? '') < $since) continue;
    $id = (string) ($r['id'] ?? '') ?: 't' . ($r['t'] ?? '') . ($r['phone'] ?? '');
    if (!isset($byId[$id]) || (($r['crm'] ?? '') === 'ok' && ($byId[$id]['crm'] ?? '') !== 'ok')) $byId[$id] = $r;
}
foreach ($byId as &$r) {
    $vis = isset($r['visit']) ? ($visits[$r['visit']] ?? null) : null;
    // a lead whose own request carried no source takes its visit's (the visit log saw the first page, with its tags)
    if ($vis && $rank($vis['via']) > $rank((string) ($r['via'] ?? 'direct'))) { $r['plat'] = $vis['plat']; $r['via'] = $vis['via'] . ' (visit)'; }
    $r['group'] = $group((string) ($r['page'] ?? ''));
    $r['journey'] = $vis;
}
unset($r);
$leadsAll = array_values($byId);

/* ---------- call-backs: typed a mobile with consent, did not send (the two F360 pages) ---------- */
$partials = array_values(array_filter(f360_partial_rows(), function ($r) use ($since) { return (string) ($r['captured_at'] ?? '') >= $since; }));
foreach ($partials as &$p) {
    $k = (string) ($p['ad_platform'] ?? '');
    $p['plat'] = array_key_exists($k, FQ_PLATFORMS) ? $k : ($k === '' || $k === 'none' ? 'direct' : 'campaign');
    $p['group'] = $group((string) ($p['page_url'] ?? ''));
}
unset($p);

/* ---------- apply the filters ---------- */
$devOf = function (array $r): string { return (string) ($r['dev'] ?? $r['device'] ?? ''); };
$match = function (array $r, bool $usePlat = true) use ($fPlat, $fDev, $fPage, $devOf): bool {
    return (!$usePlat || $fPlat === '' || ($r['plat'] ?? '') === $fPlat) && ($fDev === '' || $devOf($r) === $fDev) && ($fPage === '' || ($r['group'] ?? '') === $fPage);
};
foreach ($visits as &$x) $x['group'] = $group($x['page']);
unset($x);
$search = function (array $r) use ($fQ): bool {
    if ($fQ === '') return true;
    $hay = implode(' ', array_map('strval', array_filter([$r['name'] ?? '', $r['company'] ?? '', $r['email'] ?? '', $r['phone'] ?? '', $r['ip'] ?? '', $r['net'] ?? '', $r['id'] ?? '', $r['cc'] ?? ''], 'is_scalar')));
    // a mobile matches however it is typed: 0559…, 559…, +966559…
    $digits = ltrim((string) preg_replace('/\D/', '', $fQ), '0');
    return stripos($hay, $fQ) !== false || (strlen($digits) >= 5 && strpos((string) preg_replace('/\D/', '', $hay), $digits) !== false);
};
$stateOk = function (array $r) use ($fState): bool {
    $kind = $r['kind'] ?? 'lead';
    if ($fState === 'all') return true;
    if ($fState === 'newsletter') return $kind === 'newsletter';
    if ($kind === 'newsletter') return false;
    if ($fState === 'ok') return ($r['crm'] ?? '') === 'ok';
    if ($fState === 'problem') return ($r['crm'] ?? '') !== 'ok';
    return true;
};
$leads    = array_values(array_filter($leadsAll, function ($r) use ($match, $search, $stateOk) { return $match($r) && $search($r) && $stateOk($r); }));
$vShown   = array_filter($visits, function ($x) use ($match, $search) { return $match($x) && $search($x); });
$pShown   = array_values(array_filter($partials, function ($r) use ($match, $search) { return $match($r) && $search($r); }));
$salesLeads = function (array $set): array { return array_values(array_filter($set, function ($r) { return ($r['kind'] ?? 'lead') !== 'newsletter'; })); };

/* ---------- numbers ---------- */
$has = function (array $x, string $e): bool { return !empty($x['s'][$e]); };
$tile = [
    'visits'   => count($vShown),
    'whatsapp' => count(array_filter($vShown, function ($x) use ($has) { return $has($x, 'whatsapp_click'); })),
    'calls'    => count(array_filter($vShown, function ($x) use ($has) { return $has($x, 'call_click'); })),
    'submit'   => count(array_filter($vShown, function ($x) use ($has) { return $has($x, 'lead_form_submit'); })),
    'leads'    => count(array_filter($salesLeads($leads), function ($r) { return ($r['crm'] ?? '') === 'ok'; })),
    'problem'  => count(array_filter($salesLeads($leadsAll), function ($r) use ($match) { return ($r['crm'] ?? '') !== 'ok' && $match($r); })),
    'callback' => count(array_filter($pShown, function ($r) { return ($r['status'] ?? '') === 'partial'; })),
];
$pct = function (int $a, int $b): string { return $b > 0 ? round($a / $b * 100, 1) . '%' : '–'; };

// every platform, side by side (the platform filter does not apply here: this table is the comparison)
$byPlat = [];
foreach (array_keys(FQ_PLATFORMS) as $k) $byPlat[$k] = ['visits' => 0, 'engaged' => 0, 'whatsapp' => 0, 'calls' => 0, 'open' => 0, 'submit' => 0, 'leads' => 0, 'callback' => 0, 'clickid' => 0];
foreach ($visits as $x) {
    if (!$match($x, false) || !$search($x)) continue;
    $b = &$byPlat[$x['plat']];
    $b['visits']++; $b['engaged'] += $has($x, 'engaged') ? 1 : 0; $b['whatsapp'] += $has($x, 'whatsapp_click') ? 1 : 0; $b['calls'] += $has($x, 'call_click') ? 1 : 0;
    $b['open'] += $has($x, 'lead_form_open') ? 1 : 0; $b['submit'] += $has($x, 'lead_form_submit') ? 1 : 0;
    unset($b);
}
foreach ($salesLeads($leadsAll) as $r) {
    if (!$match($r, false) || !$search($r) || !isset($byPlat[$r['plat'] ?? ''])) continue;
    if (($r['crm'] ?? '') === 'ok') $byPlat[$r['plat']]['leads']++;
    foreach (array_keys((array) ($r['clicks'] ?? [])) as $ck) if ((FQ_CLICK_IDS[$ck] ?? '') === $r['plat']) { $byPlat[$r['plat']]['clickid']++; break; }
}
foreach ($partials as $p) if ($match($p, false) && $search($p) && ($p['status'] ?? '') === 'partial' && isset($byPlat[$p['plat']])) $byPlat[$p['plat']]['callback']++;
$maxVisits = max(1, max(array_column($byPlat, 'visits')));

// per day (Riyadh)
$dayOf = function (string $iso) use ($riyadh): string { try { return (new DateTime($iso))->setTimezone($riyadh)->format('Y-m-d'); } catch (\Exception $e) { return ''; } };
$daily = [];
for ($d = max(1, $days) - 1; $d >= 0; $d--) $daily[(new DateTime('@' . (time() - $d * 86400)))->setTimezone($riyadh)->format('Y-m-d')] = ['visits' => 0, 'leads' => 0, 'whatsapp' => 0];
foreach ($vShown as $x) { $k = $dayOf($x['t']); if (isset($daily[$k])) { $daily[$k]['visits']++; $daily[$k]['whatsapp'] += $has($x, 'whatsapp_click') ? 1 : 0; } }
foreach ($salesLeads($leads) as $r) { $k = $dayOf((string) $r['t']); if (isset($daily[$k]) && ($r['crm'] ?? '') === 'ok') $daily[$k]['leads']++; }
$dayMaxV = max(1, max(array_column($daily, 'visits'))); $dayMaxL = max(1, max(array_column($daily, 'leads')));

/* ---------- the tracking check: is every platform's tag and server link set? ---------- */
$js = function (string $file): string { return (string) @file_get_contents(__DIR__ . '/../assets/js/' . $file); };
$ads = $js('fq-ads.js'); $tt = $js('fandaqah-tiktok.js'); $sn = $js('fq-snap.js'); $li = $js('linkedin-insight.js');
preg_match("/GOOGLE_ADS = '(AW-\d+)'/", $ads, $g); preg_match("/META_PIXEL = '(\d+)'/", $ads, $mp);
preg_match("/pixelId\) \|\| '([^']*)'/", $tt, $tp); preg_match("/pixelId\) \|\| '([^']*)'/", $sn, $sp); preg_match("/_linkedin_partner_id = '(\d+)'/", $li, $lp);
$set = function ($v): bool { return is_string($v) && $v !== ''; };
$CHECK = [
    'google'   => ['tag' => $g[1] ?? '', 'tag_ok' => !empty($g[1]), 'server' => 'Click ids (gclid, gbraid, wbraid) reach the CRM for offline imports; Enhanced Conversions in the browser', 'server_ok' => true],
    'meta'     => ['tag' => 'Pixel ' . ($mp[1] ?? '–'), 'tag_ok' => !empty($mp[1]), 'server' => 'Conversions API (META_CAPI_TOKEN)', 'server_ok' => $set($config['meta']['token'] ?? '')],
    'tiktok'   => ['tag' => preg_match('/^[A-Z0-9]{15,25}$/', $tp[1] ?? '') ? 'Pixel ' . $tp[1] : 'Pixel ID not pasted yet', 'tag_ok' => (bool) preg_match('/^[A-Z0-9]{15,25}$/', $tp[1] ?? ''),
                   'server' => 'Events API (TIKTOK_PIXEL_ID + TIKTOK_EVENTS_API_TOKEN)', 'server_ok' => $set($config['tiktok_pixel_id'] ?? '') && $set($config['tiktok_events_api_token'] ?? '')],
    'snapchat' => ['tag' => ($sp[1] ?? '') !== '' ? 'Pixel ' . $sp[1] : 'Pixel ID not pasted yet', 'tag_ok' => ($sp[1] ?? '') !== '',
                   'server' => 'Conversions API (FQ_SNAP_PIXEL_ID + FQ_SNAP_CAPI_TOKEN)', 'server_ok' => $set($config['snap']['pixel_id'] ?? '') && $set($config['snap']['token'] ?? '')],
    'linkedin' => ['tag' => 'Insight tag ' . ($lp[1] ?? '–'), 'tag_ok' => !empty($lp[1]), 'server' => 'Conversions API (LINKEDIN_CAPI_TOKEN)', 'server_ok' => $set($config['linkedin_capi_token'] ?? '')],
];

/* ---------- labels ---------- */
$when = function (string $iso) use ($riyadh): string { try { return (new DateTime($iso))->setTimezone($riyadh)->format('d M · H:i'); } catch (\Exception $e) { return $iso; } };
$dur = function (int $s): string { return $s <= 0 ? '–' : ($s < 60 ? $s . ' s' : floor($s / 60) . ' min ' . ($s % 60 ? $s % 60 . ' s' : '')); };
$VIA = function (string $via): string {
    $base = explode(' ', $via)[0];
    $txt = strpos($base, 'click_id:') === 0 ? 'ad click (' . substr($base, 9) . ')' : (['utm' => 'campaign tags', 'page_store' => 'stored ad click', 'in_app' => 'in-app browser', 'referrer' => 'referring site', 'direct' => 'no source'][$base] ?? $base);
    return $txt . (strpos($via, '(visit)') !== false ? ', from the visit' : '');
};
$STEPS = ['page_view' => 'Viewed', 'engaged' => 'Read on', 'cta_click' => 'CTA', 'whatsapp_click' => 'WhatsApp', 'call_click' => 'Call', 'email_click' => 'Email',
          'lead_form_open' => 'Form', 'lead_form_submit' => 'Submit', 'lead_success' => 'Lead'];
$CRM = ['ok' => ['In CRM', 'ok'], 'refused' => ['CRM refused', 'bad'], 'down' => ['CRM unreachable', 'bad']];
$q = function (array $over = []) use ($days, $fPlat, $fDev, $fState, $fQ, $fPage, $h): string {
    return $h('?' . http_build_query(array_filter(array_merge(['days' => $days, 'plat' => $fPlat, 'page' => $fPage, 'dev' => $fDev, 'state' => $fState, 'q' => $fQ], $over), function ($v) { return $v !== '' && $v !== null; })));
};

/* ---------- CSV ---------- */
if (in_array($_GET['export'] ?? '', ['leads', 'visits'], true)) {
    $what = $_GET['export'];
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="fandaqah-' . $what . '-' . gmdate('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");   // Excel reads the Arabic correctly with the BOM
    $safe = function ($v): string { $v = is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : (string) $v; return preg_match('/^[=+\-@\t\r]/', $v) && !preg_match('/^\+\d+$/', $v) ? "'" . $v : $v; };
    if ($what === 'leads') {
        fputcsv($out, ['Received (Riyadh)', 'Type', 'CRM', 'Name', 'Company', 'Role', 'City', 'Mobile', 'Email', 'Platform', 'How we know', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'Click ids', 'Page group', 'Page', 'Landing page', 'Referrer', 'Device', 'OS', 'Browser', 'IP', 'Country', 'Seconds on site', 'Steps', 'Note', 'Submission id', 'CRM id']);
        foreach ($leads as $r) fputcsv($out, array_map($safe, [$when((string) $r['t']), $r['kind'] ?? 'lead', $CRM[$r['crm'] ?? ''][0] ?? ($r['crm'] ?? ''), $r['name'] ?? '', $r['company'] ?? '', $r['role'] ?? '', $r['city'] ?? '',
            $r['phone'] ?? '', $r['email'] ?? '', FQ_PLATFORMS[$r['plat'] ?? 'direct'] ?? '', $VIA((string) ($r['via'] ?? 'direct')), $r['utm']['utm_source'] ?? '', $r['utm']['utm_medium'] ?? '', $r['utm']['utm_campaign'] ?? '',
            $r['utm']['utm_content'] ?? '', $r['utm']['utm_term'] ?? '', $r['clicks'] ?? '', $r['group'], $r['page'] ?? '', $r['landing'] ?? '', $r['ref'] ?? '', $r['dev'] ?? '', $r['os'] ?? '', $r['br'] ?? '',
            $r['ip'] ?? '', $r['cc'] ?? '', $r['journey']['sec'] ?? '', $r['journey'] ? implode(' > ', array_keys($r['journey']['s'])) : '', $r['note'] ?? '', $r['id'] ?? '', $r['crm_id'] ?? '']));
    } else {
        fputcsv($out, ['Started (Riyadh)', 'Platform', 'How we know', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'Page group', 'Landing page', 'Pages', 'Device', 'OS', 'Browser', 'Network', 'Country', 'Seconds', 'Steps', 'WhatsApp', 'Submit clicks']);
        foreach ($vShown as $x) fputcsv($out, array_map($safe, [$when($x['t']), FQ_PLATFORMS[$x['plat']] ?? '', $VIA($x['via']), $x['tags']['src'] ?? '', $x['tags']['med'] ?? '', $x['tags']['cmp'] ?? '', $x['tags']['cnt'] ?? '', $x['tags']['trm'] ?? '',
            $x['group'], $x['page'], count($x['pages']), $x['dev'], $x['os'], $x['br'], $x['net'], $x['cc'], $x['sec'], implode(' > ', array_keys($x['s'])), $x['s']['whatsapp_click'] ?? 0, $x['s']['lead_form_submit'] ?? 0]));
    }
    exit;
}
$vList = array_values($vShown);
usort($vList, function ($a, $b) { return strcmp($b['t'], $a['t']); });
$vList = array_slice($vList, 0, 300);
$periodLabel = $days === 1 ? 'Today' : "Last $days days";
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex, nofollow">
<title>Lead Center · Fandaqah</title>
<style>
:root{--bg:#F0EDE9;--surface:#FFFFFF;--sunk:#F7F5F2;--ink:#18172F;--ink-2:#4A4960;--muted:#6B6A80;--line:#E3DED7;--bar:#C9413B;--bar-2:#2A273C;--track:#F1EDE7;--ok:#1F7A4D;--ok-bg:#E3F3EA;--bad:#B3261E;--bad-bg:#FBE4E2;--warn:#8A5A00;--warn-bg:#FFF1D6;--accent:#18172F}
@media (prefers-color-scheme:dark){:root{--bg:#141320;--surface:#1E1C2C;--sunk:#191827;--ink:#F2F0EB;--ink-2:#C9C6D6;--muted:#9C99AE;--line:#2F2C40;--bar:#E4675F;--bar-2:#C9C6D6;--track:#262437;--ok:#6FD3A0;--ok-bg:#16352A;--bad:#FF8A80;--bad-bg:#3A1B1D;--warn:#F2C26B;--warn-bg:#3A2E14;--accent:#F2F0EB}}
*{box-sizing:border-box}html{-webkit-text-size-adjust:100%}body{margin:0;background:var(--bg);color:var(--ink);font:14.5px/1.5 "Segoe UI",system-ui,-apple-system,sans-serif}
main{max-width:1320px;margin:0 auto;padding:28px 16px 72px}
a{color:inherit}h1{margin:0;font-size:28px;letter-spacing:-.5px}h1 small{display:block;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
h2{font-size:16px;margin:0}.sub{color:var(--muted);font-size:13px;margin:4px 0 0}
header.top{display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;justify-content:space-between;margin-bottom:18px}
.pills{display:flex;flex-wrap:wrap;gap:6px}.pills a{padding:7px 13px;border-radius:999px;border:1px solid var(--line);background:var(--surface);color:var(--ink-2);text-decoration:none;font-size:13px;font-weight:600}
.pills a[aria-current]{background:var(--accent);border-color:var(--accent);color:var(--bg)}
form.filters{display:flex;flex-wrap:wrap;gap:8px;align-items:end;background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:12px;margin-bottom:16px}
.filters label{display:flex;flex-direction:column;gap:3px;font-size:11.5px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em}
.filters select,.filters input{min-width:150px;padding:8px 10px;border:1px solid var(--line);border-radius:9px;background:var(--sunk);color:var(--ink);font:500 14px/1.2 inherit;text-transform:none;letter-spacing:0}
.filters input[type=search]{min-width:230px}.filters .go{padding:9px 16px;border:0;border-radius:999px;background:var(--bar);color:#fff;font:700 13.5px/1 inherit;cursor:pointer}
.filters .reset{font-size:13px;color:var(--muted);padding:9px 4px}
.tiles{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px;margin-bottom:16px}
.tile{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:14px 16px}
.tile b{display:block;font-size:28px;line-height:1.1;letter-spacing:-.5px;font-variant-numeric:tabular-nums}.tile span{color:var(--muted);font-size:12.5px}
.tile.alert{border-color:var(--bad);background:var(--bad-bg)}.tile.alert b,.tile.alert span{color:var(--bad)}
.card{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:18px;margin-bottom:16px}
.card header{display:flex;flex-wrap:wrap;justify-content:space-between;gap:8px;align-items:baseline;margin-bottom:12px}
.scroll{overflow-x:auto;margin:0 -6px;padding:0 6px}
table{width:100%;border-collapse:collapse;font-variant-numeric:tabular-nums}th,td{padding:9px 8px;border-bottom:1px solid var(--line);text-align:left;vertical-align:top}
th{color:var(--muted);font-weight:700;font-size:11.5px;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap}
td.n,th.n{text-align:right}tbody tr:hover td{background:var(--sunk)}
.plat-row a{text-decoration:none;font-weight:600}.plat-row.paid td:first-child a::before{content:"";display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--bar);margin-right:8px;vertical-align:middle}
.plat-row.free td:first-child a::before{content:"";display:inline-block;width:7px;height:7px;border-radius:50%;border:1.5px solid var(--muted);margin-right:8px;vertical-align:middle}
.mini{display:flex;align-items:center;gap:8px;min-width:150px}.mini i{display:block;height:10px;background:var(--bar);border-radius:0 4px 4px 0;min-width:2px}
.chip{display:inline-block;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:700;white-space:nowrap;background:var(--track);color:var(--ink-2)}
.chip.ok{background:var(--ok-bg);color:var(--ok)}.chip.bad{background:var(--bad-bg);color:var(--bad)}.chip.warn{background:var(--warn-bg);color:var(--warn)}
.who b{display:block}.who span,.dim{color:var(--muted);font-size:12.5px}
.contact a{display:inline-block;margin-right:8px;text-decoration:none;font-weight:600}.contact a:hover{text-decoration:underline}
.steps{display:flex;flex-wrap:wrap;gap:3px}.steps i{font-style:normal;font-size:11px;padding:1px 6px;border-radius:5px;background:var(--track);color:var(--muted)}.steps i.on{background:var(--accent);color:var(--bg)}
details.more summary{cursor:pointer;color:var(--muted);font-size:12.5px}details.more dl{display:grid;grid-template-columns:max-content 1fr;gap:3px 12px;margin:8px 0 0;font-size:12.5px}
details.more dt{color:var(--muted)}details.more dd{margin:0;word-break:break-all}
.check td:nth-child(2),.check td:nth-child(3){min-width:220px}
.days{display:flex;align-items:flex-end;gap:2px;height:96px;border-bottom:1px solid var(--line)}.days i{flex:1;background:var(--bar);border-radius:3px 3px 0 0;min-height:1px}
.days.l i{background:var(--bar-2)}.days i:hover{outline:2px solid var(--surface);opacity:.85}
.axis{display:flex;justify-content:space-between;color:var(--muted);font-size:12px;margin:6px 0 14px}
.empty{color:var(--muted);padding:22px 0;text-align:center}
.legend{display:flex;gap:14px;flex-wrap:wrap;color:var(--muted);font-size:12.5px}.legend span::before{content:"";display:inline-block;width:10px;height:10px;border-radius:3px;margin-right:6px;vertical-align:-1px;background:var(--bar)}
.legend span.l::before{background:var(--bar-2)}
@media (max-width:720px){.filters select,.filters input,.filters input[type=search]{min-width:0;width:100%}.filters label{flex:1 1 45%}h1{font-size:24px}}
</style>
</head>
<body>
<main>
<header class="top">
  <div><h1><small>Fandaqah · sales &amp; marketing</small>Lead Center</h1>
  <p class="sub"><?= $h($periodLabel) ?> · times in Riyadh · every visit, click and lead from the site's pages<?= $bots ? ' · ' . $bots . ' bot visits left out' : '' ?></p></div>
  <nav class="pills" aria-label="Period">
    <?php foreach ([1 => 'Today', 7 => '7 days', 30 => '30 days', 90 => '90 days', 180 => '180 days'] as $d => $label): ?><a href="<?= $q(['days' => $d]) ?>"<?= $d === $days ? ' aria-current="true"' : '' ?>><?= $label ?></a><?php endforeach; ?>
    <a href="?logout=1">Sign out</a>
  </nav>
</header>

<form class="filters" method="get" role="search" aria-label="Filters">
  <input type="hidden" name="days" value="<?= $days ?>">
  <label>Platform<select name="plat"><option value="">All platforms</option><?php foreach (FQ_PLATFORMS as $k => $label): ?><option value="<?= $k ?>"<?= $fPlat === $k ? ' selected' : '' ?>><?= $h($label) ?></option><?php endforeach; ?></select></label>
  <label>Page<select name="page"><option value="">All pages</option><?php foreach (array_unique(array_merge(array_values($GROUPS), ['Campaign site (A–D)'])) as $g2): ?><option<?= $fPage === $g2 ? ' selected' : '' ?>><?= $h($g2) ?></option><?php endforeach; ?></select></label>
  <label>Device<select name="dev"><option value="">All devices</option><?php foreach (['mobile' => 'Mobile', 'tablet' => 'Tablet', 'desktop' => 'Desktop'] as $k => $label): ?><option value="<?= $k ?>"<?= $fDev === $k ? ' selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></label>
  <label>Lead status<select name="state"><option value="">Leads (all)</option><option value="ok"<?= $fState === 'ok' ? ' selected' : '' ?>>In the CRM</option><option value="problem"<?= $fState === 'problem' ? ' selected' : '' ?>>Not in the CRM</option><option value="newsletter"<?= $fState === 'newsletter' ? ' selected' : '' ?>>Newsletter sign-ups</option><option value="all"<?= $fState === 'all' ? ' selected' : '' ?>>Everything</option></select></label>
  <label>Search<input type="search" name="q" value="<?= $h($fQ) ?>" placeholder="Name, mobile, email, company, IP"></label>
  <button class="go" type="submit">Apply</button><a class="reset" href="?days=<?= $days ?>">Reset</a>
</form>

<section class="tiles" aria-label="Headline numbers">
  <div class="tile"><b><?= number_format($tile['visits']) ?></b><span>Visitors</span></div>
  <div class="tile"><b><?= number_format($tile['whatsapp']) ?></b><span>Tapped WhatsApp</span></div>
  <div class="tile"><b><?= number_format($tile['calls']) ?></b><span>Tapped call</span></div>
  <div class="tile"><b><?= number_format($tile['submit']) ?></b><span>Pressed submit</span></div>
  <div class="tile"><b><?= number_format($tile['leads']) ?></b><span>Leads in the CRM</span></div>
  <div class="tile"><b><?= $pct($tile['leads'], $tile['visits']) ?></b><span>Visitor → lead</span></div>
  <div class="tile"><b><?= number_format($tile['callback']) ?></b><span>Call-backs waiting</span></div>
  <div class="tile<?= $tile['problem'] ? ' alert' : '' ?>"><b><?= number_format($tile['problem']) ?></b><span><?= $tile['problem'] ? 'Not in the CRM: enter by hand' : 'Every lead reached the CRM' ?></span></div>
</section>

<section class="card" aria-labelledby="p-h">
  <header><h2 id="p-h">Every platform, side by side</h2><p class="sub">Visits that reached each step. ● paid platform ○ unpaid. Pick a row to filter the whole page.</p></header>
  <div class="scroll"><table>
    <thead><tr><th>Platform</th><th>Visitors</th><th class="n">Read on</th><th class="n">WhatsApp</th><th class="n">Call</th><th class="n">Form opened</th><th class="n">Pressed submit</th><th class="n">Leads</th><th class="n">Visitor → lead</th><th class="n">Call-backs</th></tr></thead>
    <tbody><?php foreach ($byPlat as $k => $b): if (!in_array($k, FQ_AD_PLATFORMS, true) && !$b['visits'] && !$b['leads'] && !$b['callback']) continue; ?>
      <tr class="plat-row <?= in_array($k, FQ_AD_PLATFORMS, true) ? 'paid' : 'free' ?>">
        <td><a href="<?= $q(['plat' => $fPlat === $k ? '' : $k]) ?>"<?= $fPlat === $k ? ' aria-current="true"' : '' ?>><?= $h(FQ_PLATFORMS[$k]) ?></a></td>
        <td><div class="mini"><i style="width:<?= round($b['visits'] / $maxVisits * 120, 1) ?>px" aria-hidden="true"></i><?= number_format($b['visits']) ?></div></td>
        <td class="n"><?= number_format($b['engaged']) ?></td><td class="n"><?= number_format($b['whatsapp']) ?></td><td class="n"><?= number_format($b['calls']) ?></td>
        <td class="n"><?= number_format($b['open']) ?></td><td class="n"><?= number_format($b['submit']) ?></td><td class="n"><b><?= number_format($b['leads']) ?></b></td>
        <td class="n"><?= $pct($b['leads'], $b['visits']) ?></td><td class="n"><?= number_format($b['callback']) ?></td>
      </tr>
    <?php endforeach; ?></tbody>
  </table></div>
</section>

<section class="card" aria-labelledby="c-h">
  <header><h2 id="c-h">Tracking check: is every platform connected?</h2><p class="sub">What each ad platform receives. A red line means that platform is learning nothing from this site yet.</p></header>
  <div class="scroll"><table class="check">
    <thead><tr><th>Platform</th><th>Browser tag (both F360 pages)</th><th>Server conversions</th><th class="n">Leads with its click id</th></tr></thead>
    <tbody><?php foreach ($CHECK as $k => $c): ?>
      <tr><td><b><?= $h(FQ_PLATFORMS[$k]) ?></b></td>
        <td><span class="chip <?= $c['tag_ok'] ? 'ok' : 'bad' ?>"><?= $c['tag_ok'] ? 'Live' : 'Off' ?></span> <span class="dim"><?= $h($c['tag']) ?></span></td>
        <td><span class="chip <?= $c['server_ok'] ? 'ok' : 'warn' ?>"><?= $c['server_ok'] ? 'On' : 'Token missing' ?></span> <span class="dim"><?= $h($c['server']) ?></span></td>
        <td class="n"><?= number_format($byPlat[$k]['clickid']) ?></td></tr>
    <?php endforeach; ?></tbody>
  </table></div>
  <p class="sub">The visitor, click and lead counts on this page come from the site itself, so they work for every platform even while a tag or token is missing.</p>
</section>

<section class="card" aria-labelledby="d-h">
  <header><h2 id="d-h">Per day</h2><div class="legend"><span>Visitors</span><span class="l">Leads in the CRM</span></div></header>
  <div class="days" role="img" aria-label="Visitors per day"><?php foreach ($daily as $day => $x): ?><i style="height:<?= round($x['visits'] / $dayMaxV * 100, 2) ?>%" title="<?= $h($day) ?>: <?= $x['visits'] ?> visitors, <?= $x['whatsapp'] ?> WhatsApp, <?= $x['leads'] ?> leads"></i><?php endforeach; ?></div>
  <div class="axis"><span><?= $h(array_key_first($daily)) ?></span><span>peak <?= number_format($dayMaxV) ?> visitors</span><span><?= $h(array_key_last($daily)) ?></span></div>
  <div class="days l" role="img" aria-label="Leads per day" style="height:56px"><?php foreach ($daily as $day => $x): ?><i style="height:<?= round($x['leads'] / $dayMaxL * 100, 2) ?>%" title="<?= $h($day) ?>: <?= $x['leads'] ?> leads"></i><?php endforeach; ?></div>
  <div class="axis"><span><?= $h(array_key_first($daily)) ?></span><span>peak <?= number_format($dayMaxL) ?> leads</span><span><?= $h(array_key_last($daily)) ?></span></div>
  <details><summary class="sub">Show as a table</summary><div class="scroll"><table><thead><tr><th>Day</th><th class="n">Visitors</th><th class="n">WhatsApp</th><th class="n">Leads</th></tr></thead><tbody>
  <?php foreach (array_reverse($daily, true) as $day => $x): ?><tr><td><?= $h($day) ?></td><td class="n"><?= $x['visits'] ?></td><td class="n"><?= $x['whatsapp'] ?></td><td class="n"><?= $x['leads'] ?></td></tr><?php endforeach; ?>
  </tbody></table></div></details>
</section>

<section class="card" aria-labelledby="l-h" id="leads">
  <header><h2 id="l-h">Leads (<?= number_format(count($leads)) ?>)</h2><a class="chip" href="<?= $q(['export' => 'leads']) ?>">Download CSV</a></header>
  <?php if (!$leads): ?><p class="empty">No leads match these filters.</p><?php else: ?>
  <div class="scroll"><table>
    <thead><tr><th>Received</th><th>Lead</th><th>Contact</th><th>Source</th><th>Page</th><th>Device · IP</th><th>Journey</th><th>CRM</th></tr></thead>
    <tbody><?php foreach (array_slice($leads, 0, 500) as $r): $j = $r['journey']; $phone = (string) ($r['phone'] ?? ''); $wa = preg_replace('/\D/', '', $phone); $u = (array) ($r['utm'] ?? []); $crm = $CRM[$r['crm'] ?? ''] ?? [(string) ($r['crm'] ?? '?'), 'warn']; ?>
      <tr>
        <td><b><?= $h($when((string) $r['t'])) ?></b><?php if (($r['kind'] ?? '') !== 'lead'): ?><br><span class="chip"><?= $h($r['kind'] ?? '') ?></span><?php endif; ?></td>
        <td class="who"><b><?= $h($r['name'] ?? '–') ?></b><span><?= $h(implode(' · ', array_filter([$r['company'] ?? '', $r['role'] ?? '', $r['city'] ?? '']))) ?></span></td>
        <td class="contact"><?php if ($phone !== ''): ?><a href="tel:<?= $h($phone) ?>"><?= $h($phone) ?></a><a href="https://wa.me/<?= $h($wa) ?>" target="_blank" rel="noopener">WhatsApp</a><br><?php endif; ?><?php if (!empty($r['email'])): ?><a href="mailto:<?= $h($r['email']) ?>"><?= $h($r['email']) ?></a><?php endif; ?></td>
        <td><b><?= $h(FQ_PLATFORMS[$r['plat'] ?? 'direct'] ?? '') ?></b><br><span class="dim"><?= $h($VIA((string) ($r['via'] ?? 'direct'))) ?></span><?php if ($u): ?><br><span class="dim"><?= $h(implode(' · ', array_filter([$u['utm_campaign'] ?? '', $u['utm_content'] ?? '', isset($u['utm_term']) ? '“' . $u['utm_term'] . '”' : '']))) ?></span><?php endif; ?></td>
        <td><?= $h($r['group']) ?><br><span class="dim"><?= $h(($r['lang'] ?? '') ? strtoupper((string) $r['lang']) : '') ?></span></td>
        <td><?= $h(implode(' · ', array_filter([ucfirst((string) ($r['dev'] ?? '')), $r['os'] ?? '', $r['br'] ?? '']))) ?><br><span class="dim"><?= $h(implode(' · ', array_filter([$r['ip'] ?? '', $r['cc'] ?? '']))) ?></span></td>
        <td><?php if ($j): ?><span class="dim"><?= $h($dur((int) $j['sec'])) ?> · <?= count($j['pages']) ?> page<?= count($j['pages']) === 1 ? '' : 's' ?></span><div class="steps"><?php foreach ($STEPS as $e => $label): if ($e === 'lead_success') continue; ?><i class="<?= !empty($j['s'][$e]) ? 'on' : '' ?>"><?= $label ?></i><?php endforeach; ?></div><?php else: ?><span class="dim">–</span><?php endif; ?></td>
        <td><span class="chip <?= $crm[1] ?>"><?= $h($crm[0]) ?></span>
          <details class="more"><summary>Details</summary><dl>
            <dt>Submission</dt><dd><?= $h($r['id'] ?? '–') ?></dd><?php if (!empty($r['crm_id'])): ?><dt>CRM id</dt><dd><?= $h($r['crm_id']) ?></dd><?php endif; ?>
            <?php foreach ($u as $k => $v): ?><dt><?= $h($k) ?></dt><dd><?= $h($v) ?></dd><?php endforeach; ?>
            <?php foreach ((array) ($r['clicks'] ?? []) as $k => $v): ?><dt><?= $h($k) ?></dt><dd><?= $h($v) ?></dd><?php endforeach; ?>
            <?php if (!empty($r['ref'])): ?><dt>Came from</dt><dd><?= $h($r['ref']) ?></dd><?php endif; ?>
            <dt>Page</dt><dd><?= $h($r['page'] ?? '–') ?></dd><?php if (!empty($r['landing']) && ($r['landing'] ?? '') !== ($r['page'] ?? '')): ?><dt>First page</dt><dd><?= $h($r['landing']) ?></dd><?php endif; ?>
            <?php if (!empty($r['interest'])): ?><dt>Interest</dt><dd><?= $h($r['interest']) ?></dd><?php endif; ?>
            <?php if (!empty($r['note'])): ?><dt>Their note</dt><dd><?= $h($r['note']) ?></dd><?php endif; ?>
            <?php if (isset($r['consent'])): ?><dt>Marketing</dt><dd><?= $r['consent'] ? 'agreed' : 'not agreed' ?></dd><?php endif; ?>
            <dt>Browser</dt><dd><?= $h($r['ua'] ?? '–') ?></dd>
            <dt>Recording</dt><dd>Microsoft Clarity → Filters → Custom tags → submission_id = <?= $h($r['id'] ?? '') ?></dd>
          </dl></details></td>
      </tr>
    <?php endforeach; ?></tbody>
  </table></div><?php if (count($leads) > 500): ?><p class="sub">Showing the newest 500; the CSV has all <?= number_format(count($leads)) ?>.</p><?php endif; endif; ?>
</section>

<section class="card" aria-labelledby="cb-h">
  <header><h2 id="cb-h">Call-backs: typed a mobile, did not send (<?= number_format(count($pShown)) ?>)</h2><p class="sub">With the visitor's consent, on the two F360 pages. A number that later sends the form moves to “converted”.</p></header>
  <?php if (!$pShown): ?><p class="empty">None in this period.</p><?php else: ?>
  <div class="scroll"><table>
    <thead><tr><th>Typed</th><th>Status</th><th>Name</th><th>Mobile</th><th>Source</th><th>Page</th><th>Device</th></tr></thead>
    <tbody><?php foreach (array_slice($pShown, 0, 300) as $p): $ph = '+' . ltrim((string) ($p['phone'] ?? ''), '+'); ?>
      <tr><td><?= $h($when((string) ($p['captured_at'] ?? ''))) ?></td>
        <td><span class="chip <?= ($p['status'] ?? '') === 'converted' ? 'ok' : 'warn' ?>"><?= ($p['status'] ?? '') === 'converted' ? 'Converted' : 'Call back' ?></span></td>
        <td class="who"><b><?= $h(($p['name'] ?? '') ?: '–') ?></b><span><?= $h($p['company'] ?? '') ?></span></td>
        <td class="contact"><a href="tel:<?= $h($ph) ?>"><?= $h($ph) ?></a><a href="https://wa.me/<?= $h(ltrim($ph, '+')) ?>" target="_blank" rel="noopener">WhatsApp</a></td>
        <td><?= $h(FQ_PLATFORMS[$p['plat']] ?? '') ?><br><span class="dim"><?= $h($p['utm_campaign'] ?? '') ?></span></td>
        <td><?= $h($p['group']) ?></td>
        <td><?= $h(implode(' · ', array_filter([ucfirst((string) ($p['device'] ?? '')), $p['os'] ?? '', $p['browser'] ?? '']))) ?></td></tr>
    <?php endforeach; ?></tbody>
  </table></div><?php endif; ?>
</section>

<section class="card" aria-labelledby="v-h">
  <header><h2 id="v-h">Visitors (<?= number_format(count($vShown)) ?>)</h2><a class="chip" href="<?= $q(['export' => 'visits']) ?>">Download CSV</a></header>
  <p class="sub" style="margin:-6px 0 10px">Anonymous until they send a form: the network shows the address without its last part. Newest 300 shown.</p>
  <?php if (!$vList): ?><p class="empty">No visits match these filters.</p><?php else: ?>
  <div class="scroll"><table>
    <thead><tr><th>Started</th><th>Source</th><th>Landing page</th><th>Device</th><th>Network</th><th>Time</th><th>Steps</th></tr></thead>
    <tbody><?php foreach ($vList as $x): ?>
      <tr><td><?= $h($when($x['t'])) ?></td>
        <td><b><?= $h(FQ_PLATFORMS[$x['plat']] ?? '') ?></b><br><span class="dim"><?= $h($VIA($x['via'])) ?><?= !empty($x['tags']['cmp']) ? ' · ' . $h($x['tags']['cmp']) : '' ?><?= !empty($x['tags']['trm']) ? ' · “' . $h($x['tags']['trm']) . '”' : '' ?></span></td>
        <td><?= $h($x['group']) ?><br><span class="dim"><?= $h($x['page']) ?></span></td>
        <td><?= $h(implode(' · ', array_filter([ucfirst($x['dev']), $x['os'], $x['br']]))) ?></td>
        <td><?= $h($x['net'] ?: '–') ?><br><span class="dim"><?= $h($x['cc']) ?></span></td>
        <td class="n"><?= $h($dur((int) $x['sec'])) ?></td>
        <td><div class="steps"><?php foreach ($STEPS as $e => $label): ?><i class="<?= !empty($x['s'][$e]) ? 'on' : '' ?>"><?= $label ?></i><?php endforeach; ?></div></td></tr>
    <?php endforeach; ?></tbody>
  </table></div><?php endif; ?>
</section>
<p class="sub">Leads are kept on this server for <?= (int) (fq_leads_settings()['days'] ?? 180) ?> days, visits for as long as the monthly visit logs are kept. The CRM keeps its own copy of every lead marked “In CRM”.</p>
</main>
</body>
</html>
