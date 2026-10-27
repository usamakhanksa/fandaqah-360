<?php
/**
 * Fandaqah CRM → Meta Conversions API (offline funnel stages)   v1.0  (2026-10-27)
 *
 * Purpose: tell Meta which leads became Qualified / Opportunity / Customer, so that
 *  (1) both instant-form campaigns can switch to "Maximise number of conversion leads"
 *      (Meta's Conversion Leads optimisation — the "Connect CRM" prompt in both ad sets), and
 *  (2) QualifiedLead / Customer audiences exist for exclusions and lookalikes.
 *
 * Call it from the CRM (webhook on stage change) or from a cron that reads stage changes.
 * Requirement for instant-form leads: the CRM must store Meta's 15–17 digit "lead_id"
 * (leadgen_id) for every lead that came from a Meta instant form. Without it, matching
 * falls back to hashed email / phone only and Conversion Leads optimisation is not available.
 *
 * Settings: the same token and test code as the website Lead (api/config.php → 'meta': META_CAPI_TOKEN, META_TEST_EVENT_CODE,
 * or api/config.local.php). Never served (api/.htaccess): the CRM includes this file, or runs it from the command line.
 */

require_once __DIR__ . '/fq_meta_capi.php';   // reuses hashing / normalisation helpers

const FQ_CRM_STAGE_MAP = [
    // CRM stage (lower-case)  => Meta event_name (custom names — never "Purchase" on this account)
    'qualified'    => 'QualifiedLead',
    'sql'          => 'QualifiedLead',
    'opportunity'  => 'SalesOpportunity',
    'proposal'     => 'SalesOpportunity',
    'won'          => 'CustomerWon',
    'closed won'   => 'CustomerWon',
];

/**
 * @param array  $lead  ['crm_id'=>..., 'meta_lead_id'=>..., 'email'=>..., 'phone'=>..., 'campaign_type'=>'existing_customer|new_customer']
 * @param string $stage CRM stage name
 * @param int    $changedAt unix time of the stage change (must be within 7 days unless upload window extended)
 */
function fq_crm_stage_to_capi(array $lead, string $stage, int $changedAt): array
{
    $eventName = FQ_CRM_STAGE_MAP[strtolower(trim($stage))] ?? null;
    if (!$eventName) return ['ok' => false, 'skipped' => 'stage not mapped: ' . $stage];

    $cfg = fq_meta_settings();
    $token = (string) ($cfg['token'] ?? '');
    if ($token === '') return ['ok' => false, 'skipped' => 'META_CAPI_TOKEN not set'];

    $email = fq_meta_norm_email($lead['email'] ?? null);
    $phone = fq_meta_norm_phone($lead['phone'] ?? null);
    $metaLeadId = preg_match('/^\d{15,17}$/', (string) ($lead['meta_lead_id'] ?? '')) ? (int) $lead['meta_lead_id'] : null;

    $userData = array_filter([
        'lead_id'     => $metaLeadId,                                         // instant-form leads: strongest key
        'em'          => $email ? [fq_meta_hash($email)] : null,
        'ph'          => $phone ? [fq_meta_hash($phone)] : null,
        'external_id' => !empty($lead['crm_id']) ? [fq_meta_hash((string) $lead['crm_id'])] : null,
        'country'     => [fq_meta_hash('sa')],
    ]);
    if (!$metaLeadId && !$email && !$phone) return ['ok' => false, 'skipped' => 'no match key'];

    $event = [
        'event_name'    => $eventName,
        'event_time'    => min($changedAt, time()),
        'event_id'      => 'crm-' . ($lead['crm_id'] ?? $metaLeadId) . '-' . $eventName,   // idempotent: re-sends are deduped
        'action_source' => 'system_generated',
        'user_data'     => $userData,
        'custom_data'   => array_filter([
            'event_source'      => 'crm',
            'lead_event_source' => 'Fandaqah CRM',
            'lead_stage'        => $stage,
            'campaign_type'     => $lead['campaign_type'] ?? null,
        ]),
    ];

    $body = ['data' => [$event], 'access_token' => $token];
    if (!empty($cfg['test_event_code'])) $body['test_event_code'] = $cfg['test_event_code'];

    $ch = curl_init(rtrim(getenv('META_GRAPH_URL') ?: 'https://graph.facebook.com', '/') . '/' . FQ_META_GRAPH_VERSION . '/' . FQ_META_PIXEL_ID . '/events');
    curl_setopt_array($ch, [
        CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]);
    $resp = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    unset($ch);
    error_log(sprintf('[fq-capi-crm] %s event_id=%s http=%d', $eventName, $event['event_id'], $code));
    return ['ok' => $code === 200, 'http' => $code, 'response' => $code === 200 ? null : substr((string) $resp, 0, 300)];
}
