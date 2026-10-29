<?php
/**
 * fq-visitor-lib.php — who sent a request and where they came from, worked out once for every endpoint.
 * Used by api/lead.php (the lead record and the CRM fields), api/lead-event.php (the visit log) and api/lead-center.php.
 * Never served (api/.htaccess). PHP 7.4 or later.
 *
 *   fq_client_ip()      the visitor's address. fandaqah.com sits behind Cloudflare, so REMOTE_ADDR is an edge server; the
 *                       CF-Connecting-IP header is believed ONLY when the request really came from a Cloudflare range
 *                       (anyone can send that header straight to the origin)
 *   fq_ip_net($ip)      the network, not the person: 203.0.113.x / 2001:db8:1::/48 (anonymous visits keep only this)
 *   fq_country()        Cloudflare's two-letter country for the visitor, when the request came through Cloudflare
 *   fq_ua($ua)          device (mobile / tablet / desktop / bot), OS, browser and the app whose in-app browser it is
 *   fq_platform($sig)   the source of a visit or lead: one of FQ_PLATFORMS, how it was decided, and the label to show
 */

const FQ_CF_RANGES = [
    // https://www.cloudflare.com/ips-v4 and /ips-v6 (they change rarely; FQ_TRUSTED_PROXIES adds more, comma-separated CIDRs)
    '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '141.101.64.0/18', '108.162.192.0/18',
    '190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13',
    '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22',
    '2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32', '2405:8100::/32', '2a06:98c0::/29', '2c0f:f248::/32',
];

/** The five ad platforms first, in the order the reports list them, then everything that is not paid. */
const FQ_PLATFORMS = [
    'google'   => 'Google Ads',
    'meta'     => 'Meta (Facebook · Instagram)',
    'tiktok'   => 'TikTok',
    'snapchat' => 'Snapchat',
    'linkedin' => 'LinkedIn',
    'search'   => 'Organic search',
    'social'   => 'Other social (X, WhatsApp…)',
    'email'    => 'Email',
    'campaign' => 'Other tagged campaign',
    'referral' => 'Other websites',
    'direct'   => 'Direct / unknown',
];
const FQ_AD_PLATFORMS = ['google', 'meta', 'tiktok', 'snapchat', 'linkedin'];

/** Each platform's click id, in the order they are believed when a visit carries more than one. */
const FQ_CLICK_IDS = ['gclid' => 'google', 'gbraid' => 'google', 'wbraid' => 'google', 'gad_campaignid' => 'google', 'gad_source' => 'google',
                      'fbclid' => 'meta', 'ttclid' => 'tiktok', 'ScCid' => 'snapchat', 'sccid' => 'snapchat', 'li_fat_id' => 'linkedin'];

function fq_ip_in(string $ip, string $cidr): bool
{
    [$net, $bits] = array_pad(explode('/', $cidr, 2), 2, null);
    $a = @inet_pton($ip); $b = @inet_pton((string) $net);
    if ($a === false || $b === false || strlen($a) !== strlen($b)) return false;
    $bits = $bits === null ? strlen($a) * 8 : (int) $bits;
    $whole = intdiv($bits, 8); $rest = $bits % 8;
    if (substr($a, 0, $whole) !== substr($b, 0, $whole)) return false;
    if ($rest === 0) return true;
    $mask = chr((0xFF << (8 - $rest)) & 0xFF);
    return (ord($a[$whole]) & ord($mask)) === (ord($b[$whole]) & ord($mask));
}

/** True when this request reached PHP through Cloudflare (or a proxy named in FQ_TRUSTED_PROXIES). */
function fq_via_proxy(): bool
{
    static $via = null;
    if ($via !== null) return $via;
    $remote = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    $ranges = array_merge(FQ_CF_RANGES, array_filter(array_map('trim', explode(',', (string) getenv('FQ_TRUSTED_PROXIES')))));
    foreach ($ranges as $r) if (fq_ip_in($remote, $r)) return $via = true;
    return $via = false;
}

function fq_client_ip(): string
{
    $remote = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    if (fq_via_proxy()) {
        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_TRUE_CLIENT_IP', 'HTTP_X_FORWARDED_FOR'] as $h) {
            $v = trim(explode(',', (string) ($_SERVER[$h] ?? ''))[0]);
            if ($v !== '' && filter_var($v, FILTER_VALIDATE_IP)) return $v;
        }
    }
    return filter_var($remote, FILTER_VALIDATE_IP) ? $remote : 'unknown';
}

function fq_ip_net(string $ip): string
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) return preg_replace('/\.\d+$/', '.x', $ip);
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        $groups = array_slice(str_split(bin2hex((string) inet_pton($ip)), 4), 0, 3);
        return implode(':', array_map(function ($g) { return ltrim($g, '0') ?: '0'; }, $groups)) . '::/48';
    }
    return '';
}

function fq_country(): string
{
    $c = strtoupper((string) ($_SERVER['HTTP_CF_IPCOUNTRY'] ?? ''));
    return fq_via_proxy() && preg_match('/^[A-Z]{2}$/', $c) && !in_array($c, ['XX', 'T1'], true) ? $c : '';
}

/** A short, honest reading of the user agent: enough to tell an iPhone in TikTok's app from Chrome on Windows. */
function fq_ua(string $ua): array
{
    $app = '';
    foreach (['TikTok' => '/musical_ly|BytedanceWebview|TikTok|trill_/i', 'Instagram' => '/Instagram/i', 'Facebook' => '/FBAN|FBAV|FB_IAB|FBIOS|FB4A/i',
              'Snapchat' => '/Snapchat/i', 'LinkedIn' => '/LinkedInApp/i', 'X' => '/Twitter|TwitterAndroid/i', 'WhatsApp' => '/WhatsApp/i',
              'Google app' => '/\bGSA\//'] as $name => $re)
        if (preg_match($re, $ua)) { $app = $name; break; }
    $bot = preg_match('/bot\b|crawl|spider|slurp|headless|lighthouse|facebookexternalhit|preview|monitor|curl\/|python-|wget/i', $ua) === 1;

    $os = preg_match('/iPhone|iPad|iPod/', $ua) ? 'iOS'
        : (preg_match('/Android/', $ua) ? 'Android'
        : (preg_match('/Windows/', $ua) ? 'Windows'
        : (preg_match('/CrOS/', $ua) ? 'ChromeOS'
        : (preg_match('/Mac OS X|Macintosh/', $ua) ? 'macOS'
        : (preg_match('/Linux/', $ua) ? 'Linux' : '')))));
    $tablet = preg_match('/iPad|Tablet|SM-T\d|Tab\b/', $ua) === 1 || ($os === 'Android' && stripos($ua, 'Mobile') === false);
    $device = $bot ? 'bot' : ($tablet ? 'tablet' : (preg_match('/Mobi|iPhone|iPod|Android/', $ua) ? 'mobile' : ($ua === '' ? '' : 'desktop')));

    $browser = $app !== '' ? $app . ' app'
        : (preg_match('/SamsungBrowser/', $ua) ? 'Samsung Internet'
        : (preg_match('/Edg(e|A|iOS)?\//', $ua) ? 'Edge'
        : (preg_match('/OPR\/|Opera/', $ua) ? 'Opera'
        : (preg_match('/Firefox\/|FxiOS/', $ua) ? 'Firefox'
        : (preg_match('/Chrome\/|CriOS/', $ua) ? 'Chrome'
        : (preg_match('/Safari\//', $ua) ? 'Safari' : ''))))));
    return ['device' => $device, 'os' => $os, 'browser' => $browser, 'app' => $app];
}

/** A host name without "www." and "l."/"m." (the short-link and mobile hosts of the social sites). */
function fq_host(string $urlOrHost): string
{
    $h = strtolower((string) (strpos($urlOrHost, '://') !== false ? parse_url($urlOrHost, PHP_URL_HOST) : $urlOrHost));
    return preg_replace(['/:\d+$/', '/^(www|l|lm|m|mobile|web)\./'], '', $h);
}

/**
 * Where a visit or lead came from. $sig (every key optional):
 *   click    [click id name => value]  every click id seen (page address, the stored first/last touch, the lead itself)
 *   utm_source, utm_medium               the campaign tags
 *   app                                   the in-app browser (fq_ua()['app'])
 *   ref                                   the referring host, if it was another site
 * Returns ['key' => one of FQ_PLATFORMS, 'label' => …, 'via' => 'click_id:gclid' | 'utm' | 'in_app' | 'referrer' | 'direct'].
 * A click id is proof the ad platform paid for the visit; tags are what the ad was set up with; the in-app browser and the
 * referrer only say which site the visitor was on, so they come last.
 */
function fq_platform(array $sig): array
{
    $out = function (string $k, string $via): array { return ['key' => $k, 'label' => FQ_PLATFORMS[$k], 'via' => $via]; };
    $click = is_array($sig['click'] ?? null) ? $sig['click'] : [];
    foreach (FQ_CLICK_IDS as $id => $p) if (isset($click[$id]) && (string) $click[$id] !== '') return $out($p, 'click_id:' . $id);

    $u = strtolower(trim((string) ($sig['utm_source'] ?? '')));
    $m = strtolower(trim((string) ($sig['utm_medium'] ?? '')));
    if ($u !== '' && $u !== 'direct/unknown') {
        if (preg_match('/google|adwords|gads|youtube|^gdn$|pmax/', $u)) return $out('google', 'utm');
        if (preg_match('/^(fb|ig|meta)$|facebook|instagram|^meta[_-]/', $u)) return $out('meta', 'utm');
        if (strpos($u, 'tiktok') !== false) return $out('tiktok', 'utm');
        if (strpos($u, 'snap') !== false) return $out('snapchat', 'utm');
        if (preg_match('/linkedin|lnkd/', $u)) return $out('linkedin', 'utm');
        if (preg_match('/mail|newsletter|crm|hubspot|mailchimp/', $u . ' ' . $m)) return $out('email', 'utm');
        if (preg_match('/^(x|twitter|whatsapp|wa|telegram|youtube_organic)$/', $u)) return $out('social', 'utm');
        return $out('campaign', 'utm');
    }

    $app = (string) ($sig['app'] ?? '');
    $byApp = ['TikTok' => 'tiktok', 'Instagram' => 'meta', 'Facebook' => 'meta', 'Snapchat' => 'snapchat', 'LinkedIn' => 'linkedin',
              'X' => 'social', 'WhatsApp' => 'social'];
    if (isset($byApp[$app])) return $out($byApp[$app], 'in_app');

    $ref = fq_host((string) ($sig['ref'] ?? ''));
    if ($ref !== '') {
        if (preg_match('/(^|\.)(googleadservices\.com|doubleclick\.net|googlesyndication\.com)$/', $ref)) return $out('google', 'referrer');
        if (preg_match('/(^|\.)(facebook\.com|fb\.com|instagram\.com|messenger\.com)$/', $ref)) return $out('meta', 'referrer');
        if (preg_match('/(^|\.)tiktok\.com$/', $ref)) return $out('tiktok', 'referrer');
        if (preg_match('/(^|\.)snapchat\.com$/', $ref)) return $out('snapchat', 'referrer');
        if (preg_match('/(^|\.)(linkedin\.com|lnkd\.in)$/', $ref)) return $out('linkedin', 'referrer');
        if (preg_match('/(^|\.)(google\.[a-z.]+|bing\.com|yahoo\.com|duckduckgo\.com|yandex\.[a-z]+|baidu\.com|ecosia\.org)$/', $ref)) return $out('search', 'referrer');
        if (preg_match('/(^|\.)(t\.co|x\.com|twitter\.com|whatsapp\.com|wa\.me|telegram\.org|t\.me|youtube\.com|reddit\.com)$/', $ref)) return $out('social', 'referrer');
        if (preg_match('/mail\./', $ref)) return $out('email', 'referrer');
        return $out('referral', 'referrer');
    }
    return $out('direct', 'direct');
}

/** Every click id in a URL's query string (the lead's page address keeps the ad's own parameters). */
function fq_click_ids_in_url(string $url): array
{
    $q = [];
    parse_str((string) parse_url($url, PHP_URL_QUERY), $q);
    $out = [];
    foreach (array_keys(FQ_CLICK_IDS) as $k) if (isset($q[$k]) && is_string($q[$k]) && $q[$k] !== '') $out[$k] = substr($q[$k], 0, 300);
    return $out;
}
