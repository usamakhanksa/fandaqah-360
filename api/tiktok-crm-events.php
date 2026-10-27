<?php
/**
 * Fandaqah 360 — CRM stage events → TikTok CRM Event Set (Events API 2.0)   v1.0.0
 * ----------------------------------------------------------------------------
 * Call from your CRM webhook / cron when a lead changes stage.
 * event_source = "crm", event_source_id = TIKTOK_CRM_EVENT_SET_ID.
 *
 * ⚠ The event names below MUST match the funnel stages you define when you
 *   create the CRM Event Set in Events Manager. Rename here if you name them
 *   differently there. Never map any stage to Purchase / CompletePayment.
 */

declare(strict_types=1);
require_once __DIR__ . '/tiktok-events-api.php';

const F360_TT_CRM_STAGE_MAP = [
    // your CRM stage        => TikTok CRM event name (as configured in the CRM Event Set)
    'new'                    => 'Lead',
    'qualified'              => 'QualifiedLead',
    'demo_done'              => 'QualifiedLead',
    'opportunity'            => 'Opportunity',
    'won'                    => 'ConvertedLead',
];

/**
 * @param array $lead [
 *   'id'              => internal lead id (string, required — dedup + external_id),
 *   'stage'           => one of F360_TT_CRM_STAGE_MAP keys,
 *   'email'           => string|null,
 *   'phone'           => string|null,
 *   'tiktok_lead_id'  => Instant Form lead_id if the lead came from a TikTok Instant Form,
 *   'campaign_type'   => existing_customer|new_customer,
 *   'deal_value_sar'  => float|null (optional, for opportunity/won),
 *   'changed_at'      => unix ts (optional, defaults now)
 * ]
 */
function f360_tt_crm_stage(array $lead, ?string $testCode = null): array
{
    $stage = strtolower((string)($lead['stage'] ?? ''));
    if (!isset(F360_TT_CRM_STAGE_MAP[$stage]) || empty($lead['id'])) {
        return ['ok' => false, 'http' => 0, 'body' => 'unknown stage or missing id'];
    }
    $eventName = F360_TT_CRM_STAGE_MAP[$stage];

    $event = f360_tt_clean([
        'event'      => $eventName,
        'event_time' => (int)($lead['changed_at'] ?? time()),
        'event_id'   => 'crm-' . $lead['id'] . '-' . $stage,          // stable → retries never double count
        'lead'       => [
            'lead_id'           => $lead['tiktok_lead_id'] ?? null,  // links back to Instant Form lead
            'lead_event_source' => 'Fandaqah CRM',
        ],
        'user'       => [
            'email'       => f360_tt_hash(f360_tt_norm_email($lead['email'] ?? null)),
            'phone'       => f360_tt_hash(f360_tt_norm_phone($lead['phone'] ?? null)),
            'external_id' => f360_tt_hash((string)$lead['id']),
        ],
        'properties' => [
            'currency'      => 'SAR',
            'value'         => isset($lead['deal_value_sar']) ? (float)$lead['deal_value_sar'] : null,
            'campaign_type' => $lead['campaign_type'] ?? null,
            'crm_stage'     => $stage,
        ],
    ]);

    return f360_tt_send([$event], 'crm', null, $testCode);
}

/* CLI smoke test:  php tiktok-crm-events.php 12345 qualified someone@example.com 0550000000 TEST12345 */
if (PHP_SAPI === 'cli' && isset($argv) && realpath($argv[0]) === __FILE__ && count($argv) >= 3) {
    $r = f360_tt_crm_stage([
        'id' => $argv[1], 'stage' => $argv[2], 'email' => $argv[3] ?? null, 'phone' => $argv[4] ?? null,
    ], $argv[5] ?? null);
    echo json_encode($r, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), PHP_EOL;
}
