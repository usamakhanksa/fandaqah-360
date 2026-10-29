# crm.fandaqah.com: showing where each lead came from

**For:** the developer of crm.fandaqah.com. **From:** the Fandaqah 360 website team, 29 Oct 2026.

## The problem

Every lead now reaches `POST /api/ingest/lead` with its full source. The lead screen still shows **Source: inbound** for all of them, so sales can't see whether a lead came from TikTok, Google, Snapchat, Meta or LinkedIn.

Nothing changes in how the website calls the ingest endpoint. The fields below are already in every request body (JSON, `X-API-Key` header). The CRM needs to **store** them, **show** them on the lead and **filter** by them.

## Fields in every lead (new or newly reliable)

| Field | Example | Meaning |
|---|---|---|
| `ad_platform` | `tiktok` | One of: `google`, `meta`, `tiktok`, `snapchat`, `linkedin` (paid); then `search`, `social`, `email`, `campaign`, `referral`, `direct` |
| `ad_platform_label` | `TikTok` | Ready-to-show name (`Google Ads`, `Meta (Facebook · Instagram)`, `TikTok`, `Snapchat`, `LinkedIn`, `Organic search`, …) |
| `attribution_via` | `click_id:ttclid` | How we know: `click_id:<name>` (proof of a paid click), `utm` (campaign tags), `page_store` (ad click kept by the browser, 90 days), `in_app` (opened inside that app), `referrer`, `direct` |
| `utm_source`, `utm_medium`, `utm_campaign`, `utm_content`, `utm_term`, `utm_id` | `tiktok`, `paid_social`, `f360_existing_customers_upgrade`, `ar_leadform_thankyou`, `hotel pms` | Campaign tags (`utm_term` = the Google keyword) |
| `gclid`, `gbraid`, `wbraid`, `gad_campaignid`, `gad_source` | `EAIaIQob…`, `24404418601` | Google Ads click ids and campaign id (for offline conversion imports) |
| `fbclid`, `ttclid`, `ScCid`, `li_fat_id` | | Meta, TikTok, Snapchat and LinkedIn click ids |
| `visitor_ip` | `5.42.1.9` | The visitor's real address (Cloudflare's `CF-Connecting-IP`, trusted only from Cloudflare) |
| `visitor_country` | `SA` | Cloudflare's country code |
| `device_type`, `device_os`, `device_browser`, `in_app_browser` | `mobile`, `iOS`, `TikTok app`, `TikTok` | The device the form was sent from |
| `user_agent` | | Full browser string (≤ 400 characters) |
| `page_url`, `landing_url`, `referrer_host` | | The page the form was sent from, the visit's first page, the site the visitor came from |
| `campaign_type`, `segment`, `language` | `existing_customer`, `A`, `ar` | Which landing page and offer |
| `submission_id` | UUID | Unique per request: de-duplicate on it. Also the Microsoft Clarity tag `submission_id` (the session recording) |
| `visit_id` | `a81f…` | The website visit behind the lead (the Lead Center shows its journey) |
| `message` | | Unchanged, plus a readable **"——— Lead source ———"** block at the end with all of the above |

Unknown fields must keep being accepted (never a 4xx for an extra field), because the site adds fields over time.

## What to change in the CRM

1. **Source.** Set the lead's Source from `ad_platform_label` instead of `inbound`. Keep `inbound` as the channel if the CRM needs it.
2. **New "Source & device" card** in the lead drawer, under "Lead information":

   ```
   Source & device
   Platform        TikTok  (ad click · ttclid)
   Campaign        f360_existing_customers_upgrade · ar_leadform_thankyou
   Keyword         hotel pms                        (only when utm_term exists)
   Page            Existing customers  ·  AR
   Device          Mobile · iOS · TikTok app
   IP / country    5.42.1.9 · SA
   Click ids       ttclid E_C_P_CrgB…               (collapsed)
   Recording       Clarity → custom tag submission_id = <submission_id>
   ```

3. **List filters and columns** on Leads: Platform (`ad_platform`), Campaign (`utm_campaign`), Page (`campaign_type`), Device (`device_type`), Country, created date. Add a "Platform" column with the label.
4. **Store the raw fields** as their own columns (or a JSON `attribution` column) on `leads`, so reports can group by platform and campaign.
5. **Report:** leads and won deals per `ad_platform` and per `utm_campaign`, for the period.
6. **Check the Role field.** In the screenshot of 28 Oct, Role shows the hotel name (`فندق الهداية الكبرى`), the same value as Name. The website sends `role` only when the visitor fills it in, so the CRM may be copying another field into Role.

## Already on the website side (nothing to do in the CRM)

- **Every lead is also kept on the website server**, including any the CRM refused or couldn't receive. It's in the **Lead Center** (`/landing/fandaqah-360/api/lead-center.php`, key-protected), so a CRM outage loses nothing. Leads marked "Not in the CRM" there need entering by hand, or re-sending.
- **Visits, WhatsApp taps, call taps and submit presses** are counted per platform in the same Lead Center.
- **Stages back to the ad platforms:** when a lead becomes qualified, an opportunity or a customer, the CRM can call `api/fq_crm_stage_capi.php` and `api/tiktok-crm-events.php` (see their headers) with the click ids above.
