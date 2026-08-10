---
name: crm-customer-list-render
description: default_load_customers() render loop in crm/mod_default.php — shared caches, bulk-IN maps (no N+1), phone-only card block, $oneStatus unguarded-index gotcha
metadata:
  type: project
---

`default_load_customers()` in `application/modules/crm/mod_default.php` (~line 2313) is the main CRM customer-list renderer. Single foreach over `$list_customers` (~2872) builds both desktop table cells and (gated `if ($deviceType=="phone")`, ~2954-3033) phone card fields.

**Shared caches / bulk maps — reuse these, never add per-row queries:**
- `$arr_status_cached` (~2837): all CUSTOMER_STATUS property rows incl. trashed (no is_trash=0 filter), keyed by property_id.
- `$arr_block_cached` (~2848): _PROJECT setting cache, id→title.
- `$arr_campaign_cached` (~2871): lazy-filled via `$clsCampaign->getTitle($id)`, shared between desktop (3153) and phone (2987).
- `$meta_map` (~2754): customer meta (tag/stock/campaign/purpose/need/type/bedroom/block/share) in ONE getMapByCustomerIds.
- `$followups_map` (~2770) and `$archived_map` (~2764): bulk-IN, replace prior N+1. Followups ordered `reg_date DESC` (NOT date_id).
- LIST query selects `*` (2729/2734) so `$cus` has upd_date/reg_date/status_id/more_information/admin_id/phone.

**Customer more_information:** decode once via `$clsISO->to_array_json()` → `$more_info` (~2894). `$core->get_field($more_info,'key',default)` tolerates the array. Don't confuse with `$more_information` (= profile's, ~2783).

**Gotcha — `$oneStatus = $arr_status_cached[$status_id]` at ~2948 has NO isset() guard.** A customer with status_id=0 or a hard-deleted status → undefined index notice at 2949/2950 and a null 2nd arg to getTitle(). PRE-EXISTING (desktop path 3057 does the same); phone block (2955) inherits same contract, introduces no new risk. Status_id=0 is reachable (base cond is only is_trash=0).

**Permiss for status-change menu:** `$cus['admin_id']==$profile_id || $clsCustomer->isFullPermiss()` (see [[crm_dispatch_conventions]] / [[crm_module]] in user memory). htmlspecialchars applied to bgcolor/title in status_menu_html (~3029).

**`$arr_property_cached` is dual-shape but collision-safe.** Pre-loop seed (~2846) stores FULL ROW arrays for `_AGENCY`/`_GENDER` property_ids; in-loop writes (~3075/3084/3094) and the mobile owner-dept write (~2977, `_DEPARTMENT` id) store STRINGS. Desktop reads `[$agent_id]['title']`/`[$gender_id]['title']` (~3195/3200) expect array. NO type collision possible because `_DEPARTMENT`/`_AGENCY`/`_GENDER`/`CUSTOMER_STATUS` are all `property_type` rows in ONE `default_property` table sharing a single auto-inc `property_id` — a dept id can never equal an agency/gender/status id. So a string write under a dept key never gets read as `['title']`. The `isset()` guard at 2977 only ever reads back same-shape (string) dept values. Verified via sub_default.php:2078 `makeOption(0,'_DEPARTMENT',...)`.

**Mobile owner block (~2968-2981, phone-gated):** owner_name/avatar/dept off `$arr_profile_cached` (no new query IF cached rows carry `avatar` — getAvatar re-queries only when avatar key absent). New code reads `$_owner['more_information']`/`['department_id']` which existing call-sites do NOT corroborate (they read only `full_name`), but it's defensively isset()/is_array()-guarded so missing keys degrade to empty dept, no notice/fatal.
