---
name: crm-phone-masking-boundary
description: CRM customer-list phone masking only protects the visible text, NOT the zalo:/tel: action links — raw phone leaks there on both desktop and mobile cards
metadata:
  type: project
---

In crm/mod_default.php `default_load_customers()`, the row `phone` field holds the RAW
unmasked number (selected at the SQL via CONVERT(...) as `phone`, never overwritten).

Masking gates `$_isOwnerPhone` (owner/full-permiss) and `$_isRecvPending` (receipt not
confirmed) only control the *displayed* masked text (`$clsCustomer->mask()` / `$_phWrap`).
The Zalo (`https://zalo.me/{phone}`) and `tel:` action links use the RAW `$cus['phone']`
directly, bypassing the mask — on BOTH desktop (lines ~3115-3116) and the new mobile card
(`_ajax.list_customer.tpl` phone branch, the `is-zalo` / team-readonly `is-call` buttons).

**Why:** masking was designed to hide the digits from non-owners / pre-receipt, but the
click-to-chat hrefs were never gated. Desktop has had this leak for a long time; the mobile
rebuild faithfully replicated it (so it is NOT a desktop regression).

**How to apply:** when reviewing CRM phone exposure, the leak vector is the action hrefs,
not the masked span. The mobile `is_pending` case is safe (action buttons hidden, only the
"Xác nhận đã nhận khách" button shows). The exposed case is non-owner + non-pending
(team-readonly / shared customer): masked text but raw zalo/tel link. Fix would require
gating the zalo/tel hrefs on `is_owner_phone` too — but doing so on desktop would break the
"byte-identical desktop" mandate, so coordinate before touching desktop. See
[[crm_dispatch_conventions]].
