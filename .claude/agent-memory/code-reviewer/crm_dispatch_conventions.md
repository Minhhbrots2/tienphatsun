---
name: crm-dispatch-conventions
description: How CRM module handlers in mod_default.php split full-page vs AJAX, and the gate/IDOR pattern for manager-scoped data
metadata:
  type: project
---

CRM module (`application/modules/crm/mod_default.php`) handler conventions, verified during review of the `/crm/team/` manager dashboard.

**Two handler shapes:**
- Full-page (act routed via .htaccess, e.g. `default_my_team`, `default_my_desktop`): `global $smarty, $assign_list, $title_page, ...`; set `$title_page` AND `$assign_list['title_page']`; `$smarty->assign(...)`; then `return;` — framework renders `views/crm/{act}.tpl` by convention. NO echo/die.
- AJAX (e.g. `default_load_team_board`, `default_load_coaching`): `$core->build('_ajax.X.tpl')` → `echo json_encode(..., JSON_UNESCAPED_UNICODE); die();`.

**Gate + IDOR pattern for manager-scoped data:**
- Gate: `$clsCustomer->isTeamManager() || $clsCustomer->isFullPermiss()`.
- Reps resolved SERVER-SIDE from groups owned by manager: `GroupProfile->getAll("manager_profile_id='{$pid}' AND is_trash=0")`, then parse `list_profile_id` (format `|N|`) via `$clsISO->getArrayByTextSlash()`, int-cast each id. Full-permiss skips the manager_profile_id filter (`is_trash=0` only).
- Per-rep IDOR check: confirm the requested `rep_id` is in the manager's resolved rep set (skip for full-permiss).
- SQL safety relies on `$repCsv = implode(',', int-cast ids)` and all interpolated scalars being `(int)` constants/`time()`. No prepared statements in this codebase — int-casting at the boundary IS the sanitization strategy.

**Helper signatures confirmed in use:** `$clsCustomer->mask($phone, true)`, `$clsProfile->getAvatar($id, $profileArr)`, `$clsISO->getTimeAgo($ts)`, `Input::get($key, $default)`, `Input::post($key, $default)`.

Core model methods (Customer/Profile/ISO/GroupProfile) are IonCube-encoded — not readable; verify signatures by grepping existing call sites, not by reading definitions.
