<?php
/**
 * fq-leads-lib.php — the lead record kept on this host, so no lead is ever lost between the website and the CRM.
 * api/lead.php adds one row for every request a visitor sends (after the CRM has answered, with that answer: "ok", "refused"
 * or "down"), and api/lead-center.php reads them. One JSON line per lead, a file per month (leads-YYYY-MM.jsonl), in the folder
 * api/config.php names ('leads' → dir), denied to the web. Rows are dropped after 'days' (default 180). Never served
 * (api/.htaccess). PHP 7.4 or later.
 */

function fq_leads_settings(): array
{
    static $s = null;
    if ($s === null) { $c = require __DIR__ . '/config.php'; $s = (array) ($c['leads'] ?? []); }
    return $s;
}

function fq_leads_dir(): ?string
{
    $dir = rtrim((string) (fq_leads_settings()['dir'] ?? ''), '/\\');
    if ($dir === '') return null;
    if (!is_dir($dir)) @mkdir($dir, 0750, true);
    if (!is_dir($dir) || !is_writable($dir)) { error_log('[leads] record folder not writable: ' . $dir); return null; }
    if (!is_file($dir . '/.htaccess')) @file_put_contents($dir . '/.htaccess', "<IfModule mod_authz_core.c>\n  Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n  Order allow,deny\n  Deny from all\n</IfModule>\n");
    return $dir;
}

function fq_leads_add(array $row): bool
{
    $dir = fq_leads_dir();
    if ($dir === null) return false;
    $file = sprintf('%s/leads-%s.jsonl', $dir, gmdate('Y-m'));
    $ok = @file_put_contents($file, json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND | LOCK_EX) !== false;
    @chmod($file, 0600);
    if (mt_rand(1, 100) === 1) fq_leads_purge();
    return $ok;
}

/** Month files that overlap the last $days days (all of them when $days is 0), newest first. */
function fq_leads_files(int $days = 0): array
{
    $dir = fq_leads_dir();
    if ($dir === null) return [];
    $files = glob($dir . '/leads-*.jsonl') ?: [];
    rsort($files);
    if ($days > 0) {
        $from = gmdate('Y-m', time() - $days * 86400);
        $files = array_values(array_filter($files, function ($f) use ($from) { return substr(basename($f), 6, 7) >= $from; }));
    }
    return $files;
}

/** Rows newer than $days days (all when 0), newest first. Split on "\n" only: \R also matches a byte inside Arabic letters. */
function fq_leads_rows(int $days = 0): array
{
    $since = $days > 0 ? gmdate('c', time() - $days * 86400) : '';
    $rows = [];
    foreach (fq_leads_files($days) as $file)
        foreach (array_reverse(explode("\n", (string) @file_get_contents($file))) as $line)
            if (($line = rtrim($line, "\r")) !== '' && is_array($r = json_decode($line, true)) && (string) ($r['t'] ?? '') >= $since) $rows[] = $r;
    usort($rows, function ($a, $b) { return strcmp((string) ($b['t'] ?? ''), (string) ($a['t'] ?? '')); });
    return $rows;
}

/** Retention: rows older than 'days' go; a month file left empty is removed. */
function fq_leads_purge(): int
{
    $cut = gmdate('c', time() - 86400 * max(1, (int) (fq_leads_settings()['days'] ?? 180)));
    $dropped = 0;
    foreach (fq_leads_files() as $file) {
        $fh = @fopen($file, 'c+');
        if (!$fh) continue;
        if (flock($fh, LOCK_EX)) {
            $keep = [];
            foreach (explode("\n", (string) stream_get_contents($fh)) as $line)
                if (($line = rtrim($line, "\r")) !== '' && is_array($r = json_decode($line, true))) {
                    if ((string) ($r['t'] ?? '') >= $cut) $keep[] = $line; else $dropped++;
                }
            ftruncate($fh, 0); rewind($fh);
            if ($keep) fwrite($fh, implode("\n", $keep) . "\n");
            fflush($fh); flock($fh, LOCK_UN);
        }
        fclose($fh);
        clearstatcache(true, $file);
        if (filesize($file) === 0) @unlink($file);
    }
    return $dropped;
}
