<?php
/**
 * f360-partial-lib.php — the partial-lead store (second meta/ pack, 28 Oct 2026; named in the pack's patches, written here).
 * One JSON line per visit, a file per month (partial-YYYY-MM.jsonl), in the folder api/config.php names ('partial' → dir).
 * Used by api/f360-partial-lead.php (adds rows), api/lead.php (marks a number converted once its request reached the CRM)
 * and api/partial-leads.php (the call list). Never served (api/.htaccess). PHP 7.4 or later.
 *
 *   f360_partial_mark_converted($phone)  every row of that number becomes "converted": it leaves the call list
 *   f360_partial_purge()                 drops rows older than 'days' (default 30) and empties old month files;
 *                                         also runs by itself now and then, so it works without the daily cron:
 *   php -r "require '/path/to/landing/fandaqah-360/api/f360-partial-lib.php'; f360_partial_purge();"
 */

function f360_partial_settings(): array
{
    static $s = null;
    if ($s === null) { $c = require __DIR__ . '/config.php'; $s = (array) ($c['partial'] ?? []); }
    return $s;
}

/** The store folder, created on first use (0750) with its own deny-all, in case it sits inside the web root. */
function f360_partial_dir(): ?string
{
    $dir = rtrim((string) (f360_partial_settings()['dir'] ?? ''), '/\\');
    if ($dir === '') return null;
    if (!is_dir($dir)) @mkdir($dir, 0750, true);
    if (!is_dir($dir) || !is_writable($dir)) { error_log('[partial] store folder not writable: ' . $dir); return null; }
    if (!is_file($dir . '/.htaccess')) @file_put_contents($dir . '/.htaccess', "<IfModule mod_authz_core.c>\n  Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n  Order allow,deny\n  Deny from all\n</IfModule>\n");
    return $dir;
}

/** Any Saudi mobile spelling (05…, 5…, +9665…, 009665…, Arabic-Indic digits) → 9665XXXXXXXX, else null. */
function f360_partial_phone($p): ?string
{
    $p = strtr((string) $p, ['٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9',
                             '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9']);
    $d = preg_replace('/\D+/', '', $p);
    if (strpos($d, '00') === 0) $d = substr($d, 2);
    if (strpos($d, '966') === 0) $d = substr($d, 3);
    if (strpos($d, '0') === 0) $d = substr($d, 1);
    return preg_match('/^5\d{8}$/', $d) ? '966' . $d : null;
}

/** The month files, newest first. */
function f360_partial_files(): array
{
    $dir = f360_partial_dir();
    if ($dir === null) return [];
    $files = glob($dir . '/partial-*.jsonl') ?: [];
    rsort($files);
    return $files;
}

/** Rewrite one month file under its lock: $fn gets the rows and returns the rows to keep. */
function f360_partial_rewrite(string $file, callable $fn): void
{
    $fh = @fopen($file, 'c+');
    if (!$fh) return;
    if (flock($fh, LOCK_EX)) {
        $rows = [];
        // split on "\n" only: a byte-mode \R also matches 0x85, which sits inside Arabic letters (م = D9 85) and broke those rows
        foreach (explode("\n", (string) stream_get_contents($fh)) as $line)
            if (($line = rtrim($line, "\r")) !== '' && is_array($r = json_decode($line, true))) $rows[] = $r;
        $keep = $fn($rows);
        ftruncate($fh, 0); rewind($fh);
        foreach ($keep as $r) fwrite($fh, json_encode($r, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
        fflush($fh);
        flock($fh, LOCK_UN);
    }
    fclose($fh);
}

/** Append one row (the endpoint has already checked consent, the number and the honeypot). */
function f360_partial_add(array $row): bool
{
    $dir = f360_partial_dir();
    if ($dir === null) return false;
    $file = sprintf('%s/partial-%s.jsonl', $dir, gmdate('Y-m'));
    $ok = @file_put_contents($file, json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND | LOCK_EX) !== false;
    @chmod($file, 0600);
    if (mt_rand(1, 50) === 1) f360_partial_purge();   // the cron's job, now and then, in case no cron was set up
    return $ok;
}

/** A visitor whose request reached the CRM is no longer a call-back: all their rows become "converted". */
function f360_partial_mark_converted($phone): int
{
    $p = f360_partial_phone($phone);
    if ($p === null) return 0;
    $n = 0;
    foreach (array_slice(f360_partial_files(), 0, 2) as $file)   // this month and the last (the store keeps ~30 days)
        f360_partial_rewrite($file, function (array $rows) use ($p, &$n) {
            foreach ($rows as &$r) if (($r['phone'] ?? '') === $p && ($r['status'] ?? '') === 'partial') { $r['status'] = 'converted'; $r['converted_at'] = gmdate('c'); $n++; }
            return $rows;
        });
    return $n;
}

/** Retention: rows older than 'days' go; a month file left empty is removed. */
function f360_partial_purge(): int
{
    $cut = time() - 86400 * (int) (f360_partial_settings()['days'] ?? 30);
    $dropped = 0;
    foreach (f360_partial_files() as $file) {
        f360_partial_rewrite($file, function (array $rows) use ($cut, &$dropped) {
            $keep = array_values(array_filter($rows, function ($r) use ($cut) { return strtotime((string) ($r['captured_at'] ?? '')) >= $cut; }));
            $dropped += count($rows) - count($keep);
            return $keep;
        });
        clearstatcache(true, $file);
        if (filesize($file) === 0) @unlink($file);
    }
    return $dropped;
}

/** Every row still kept, newest first. */
function f360_partial_rows(): array
{
    $rows = [];
    foreach (f360_partial_files() as $file)
        foreach (array_reverse(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) as $line)
            if (is_array($r = json_decode($line, true))) $rows[] = $r;
    return $rows;
}
