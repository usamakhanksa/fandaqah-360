<?php
require '/home/claude/fq/repo/api/snap-capi.php';
$_SERVER['HTTP_CF_CONNECTING_IP'] = '94.98.1.2'; $_SERVER['REMOTE_ADDR'] = '172.68.0.1'; $_SERVER['HTTP_USER_AGENT'] = 'UA-test';
$fail = 0; $t = function ($ok, $msg) use (&$fail) { echo ($ok ? "PASS " : "FAIL ") . $msg . "\n"; if (!$ok) $fail++; };
$e = fq_snap_lead_event(['submission_id' => 'abc-1', 'email' => ' Person@Example.com ', 'phone' => '+966501234567', 'name' => 'محمد العتيبي',
  'page_url' => 'https://fandaqah.com/landing/fandaqah-360/existing-customers/?utm_campaign=A_snap_existing_upgrade_oct2026'], ['sc_click_id' => 'clk1', 'campaign_type' => 'existing_customer'], false, 1700000000000);
$t($e['event_name'] === 'SIGN_UP' && $e['action_source'] === 'WEB', 'SIGN_UP / WEB');
$t($e['event_id'] === 'abc-1', 'event_id = submission_id (dedup with pixel client_dedup_id)');
$t($e['user_data']['em'][0] === hash('sha256', 'person@example.com'), 'email normalised + hashed');
$t($e['user_data']['ph'][0] === hash('sha256', '966501234567'), 'phone digits-only + hashed');
$t($e['user_data']['country'][0] === hash('sha256', 'sa'), 'country sa hashed');
$t($e['user_data']['client_ip_address'] === '94.98.1.2', 'client IP from Cloudflare header, not edge IP');
$t($e['user_data']['sc_click_id'] === 'clk1', 'click id passed');
$t($e['custom_data'] === ['event_tag' => 'existing_customer', 'status' => 'returning'], 'campaign tag existing/returning');
$t($e['event_time'] === 1700000000000, 'ms timestamp');
$t(fq_snap_lead_event(['submission_id' => 'p', 'lead_stage' => 'partial', 'email' => 'a@b.co'], [], false) === null, 'partial lead → no event');
$t(fq_snap_lead_event(['submission_id' => 'x'], [], false) === null, 'no identifiers → no event');
$n = fq_snap_lead_event(['email' => 'a@b.co', 'utm_campaign' => 'B_snap_hotelowners'], [], true);
$t(strpos($n['event_id'], 'srv-') === 0 && $n['custom_data']['event_tag'] === 'new_customer', 'no-JS post → server id, new_customer');
exit($fail ? 1 : 0);
