---
name: permiss-mod-trust-boundary
description: How permiss_mod is populated/merged in _header.php and the trust implications for checkPermission-gated features
metadata:
  type: project
---

`permiss_mod` is the JSON-feature-flag permission map for admin profiles. Verified during care-monitor plan review.

**Population/merge (`application/_header.php:108-126`):**
- Own keys = `default_profile.permiss_mod` (JSON) via `$clsISO->to_array_json()`.
- Role keys = role property `more_information.permiss_mod`.
- Merge = OWN WINS: role keys only fill keys the profile does NOT already have (`if(!isset($permiss_mod[$key]))`). Own profile JSON can therefore set ANY key — there is no allow-list of which keys a profile may carry. A profile-level edit (or any path that writes `default_profile.permiss_mod`) can grant an arbitrary feature flag.

**Gate (`models/ISO.php:2821 checkPermission($key)`):**
- `_PROFILE_SUPPER_ID` / `_PROFILE_ROOT_ID` short-circuit to 1.
- Otherwise returns 1 only if `(int)permiss_mod[$key]==1`. Absent key or empty map → 0.

**Implication for new screens:** gating a data-exposure screen purely on a fresh `permiss_mod['x']` key means anyone who can write their own profile JSON (or anyone the role grants) gets in. For sensitive scoping, the server gate must ALSO bind the data scope to a server-resolved identity (own `user_id`, manager's resolved rep set) — never trust a second permiss_mod key (e.g. `*_all`) to widen scope without re-deriving scope server-side.

**Existing CRM reality:** there is NO "MKT role" tier. `default_load_marketing_control` (mod_default.php:410) and `default_load_team_board` (:873) gate on `isFullPermiss()` / `isTeamManager()` ONLY — not on any permiss_mod key. The "Khách tôi đẩy" UI link (`views/crm/default.tpl:29`) is inside `{if isFullPermiss()}`. Any plan that introduces an MKT permiss_mod tier is adding a NEW trust boundary, not reusing an existing one.

Related: [[crm-dispatch-conventions]].
