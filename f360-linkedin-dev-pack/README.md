# CAPI wiring — where each call goes

## 1. Web lead (server twin of the browser event)

In `landing/fandaqah-360/api/lead.php`, **after** the CRM returned success:

```php
require_once __DIR__ . '/f360_linkedin_capi.php';
[$first, $last] = array_pad(preg_split('/\s+/', trim($in['name'] ?? ''), 2), 2, null);
f360_capi_send('lead_submit', [
    'submission_id' => $in['submission_id'] ?? '',   // SAME id the browser used as event_id
    'email'         => $in['email'] ?? '',
    'li_fat_id'     => $in['li_fat_id'] ?? '',
    'first_name'    => $first, 'last_name' => $last,
    'company'       => $in['company'] ?? null,
]);
```

Send it only when `utm_source` / `li_fat_id` indicate LinkedIn, or always — LinkedIn only attributes members it can match to an ad interaction.

## 2. CRM stage changes (crm.fandaqah.com)

Trigger on stage change → call `f360_capi_send()` with:

| CRM stage | `$stage` | Conversion ID |
|---|---|---|
| Qualified (MQL/SQL accepted by sales) | `qualified` | 32466633 |
| Opportunity / proposal sent | `opportunity` | 32466641 |
| Closed won / contract signed | `customer` | 32466649 |

Payload must include: `submission_id`, `email`, `li_fat_id`, `first_name`, `last_name`, `company`, `occurred_at_ms` (time the stage changed), optional `value_sar`.

The CRM must therefore **store** `li_fat_id`, `submission_id`, `campaign_type` and the hashed-able email on the lead record (see patches B/C).

## 3. Lead Gen Form leads (the main lead source — all 3 ad sets use the Lead Generation objective)

Today LinkedIn form leads stay inside Campaign Manager; nothing syncs them to the CRM, so they can never become Qualified/Opportunity/Customer conversions.

Pick one:
- **LinkedIn Lead Sync API** (`r_marketing_leadgen_automation`) → poll/receive new form responses → create CRM lead with `li_lead_urn`, email, name, company, job title, form ID, ad set ID.
- **Zapier** "LinkedIn Lead Gen Forms → Webhook" into the CRM ingest endpoint (fastest, no code).

Then the same stage-change job sends CAPI events with `email` + `li_lead_urn`.

## 4. Deduplication rule

Browser rule 32466609 and server rule 32466657 are separate conversion rules (one per data source, as specified). Both carry the same `submission_id` as event ID.
- In reporting, **never add them together** — read the Insight Tag rule as primary and use the CAPI rule to see what the browser missed (ad blockers, ITP).
- Do not set both as optimisation goals.

## 5. Go-live test

1. Submit a test lead from `…/fandaqah-360-landing-v2/?utm_source=linkedin&li_fat_id=TEST123`.
2. Expect: Insight Tag rule 32466609 → "Active" within ~24h; CAPI call returns HTTP **201**; `F360_Lead_Submit | CAPI (server)` changes from "Awaiting activity".
3. Move the test lead to Qualified in the CRM → `F360_Qualified_Lead` receives an event. Then delete the test lead.
