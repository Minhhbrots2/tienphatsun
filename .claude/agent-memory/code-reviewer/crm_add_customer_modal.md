---
name: crm-add-customer-modal
description: Add-customer modal save contract — pop_save_customer uses form-wide ajaxSubmit (field NAME is the contract, not markup/order); .required class is the client gate; which iso-* classes init which select
metadata:
  type: project
---

Template: `application/views/home/_ajax.customer.tpl` (the "Thêm khách hàng" add form).
Restyled into 2 panels (basic/detail) to match a mockup; PHP handler
`home/sub_default.php default_open_customer` / `pop_save_customer` is IonCube/minified, OFF-LIMITS.

**Save contract — NAME is everything, markup is free to change.**
`$Core.global.crm.pop_save_customer` (`themes/js/global.min.js:922`) does
`_form.ajaxSubmit()` (jQuery Form plugin) → serializes the WHOLE `<form>` by `name=`.
So restyles are safe as long as every `name=` survives; HTML structure/order/wrappers
don't matter. Posts to `?mod=home&act=pop_save_customer`, expects JSON `{msg,id,name}`;
`msg` contains `_success` / `_duplicated` / `_error`.

Field-name set the handler reads (verify all present on any future edit):
name, phone, facebook, begin_need, resource_id, status_id, list_bedroom_id[],
blocktype_id, list_block_id[], list_campaign_id[], admin_id, list_share_id[],
auto_create_followups, email, address, tiktok, country_id, city_id, notes.

**Client validation gate:** `$('select.required,input.required')` empty-check
(`global.min.js:930`). Adding/removing the `required` class is a real behavior change.
NOTE: the restyle added `required` to phone (mockup marks SĐT with *) — blocks
phone-less / FB-only leads. Required set now: name, phone, resource_id, status_id, admin_id.

**Enhanced-select init (selectors live in `themes/js/app.min.js`):**
- `iso-select2` (app.min.js:219/241) — list_bedroom_id[], list_block_id[], country_id, city_id
- `iso-selectizeSync` (:387) — list_campaign_id[]
- `iso-selectizeImageSearch` (:431, reads `data-url`=…list_staff) — admin_id, list_share_id[]
Strip any of these classes and the widget silently falls back to a native select.

**Wiring helpers (all confirmed to exist):** format_phone `helper.min.js:163`,
toggle_block `helper.min.js:247`, get_select_city `views/crm/js/jquery.crm.js:355`
(reads `toId` attr → updates `#{toId}` options; country `toId="slb_City_Id"` must match
city select `id`). `js__continue-add` class on "Lưu & thêm" is what global.min.js:961
checks to re-trigger `.js__add-customer`. Buttons must keep `openFrom`/`uid` attrs.

**`.form-row`+`.col-6`** = Bootstrap-4 pattern from bundled `bootstrap.css` (NOT crm.css),
used across 15+ live CRM tpls → stays 2-up at all widths (mockup wants that). Safe.

**Known minor:** status_id select seeds selected-value from `$_ss_storage.resource_id`
(copy-paste from the resource_id line above) instead of `$_ss_storage.status_id` —
harmless on a blank add-form, only matters if sticky pre-fill is added.

See [[crm_dispatch_conventions]].
