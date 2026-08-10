---
name: crm-myteam-ajax-split
description: MyTeam/Leader Dashboard (act=my_team) AJAX-box architecture — shell + 6 box handlers via _crm_my_team_scope(), and the .ajax+.js__mt-box double-load gotcha
metadata:
  type: project
---

MyTeam Leader Dashboard refactor (mod_default.php L1954–2453, my_team.tpl, 6 `_ajax.mt_*.tpl`, jquery.crm.js).

**Architecture:** `default_my_team()` = thin shell (gate + period header only). 6 box handlers `default_mt_{overview,lists,donut,urgent,momentum,feed}` each call `_crm_my_team_scope()` (shared gate+scope+period resolver), run their queries, echo `{error,html}` + `die()`. IDOR-safe: scope re-derived server-side; repCsv never from client.

**Gate:** `checkPermissionGroup('SALE_DIRECTOR_ONLY') || checkPermissionGroup('REGIONAL_DIRECTOR') || isFullPermiss()`. On fail, scope helper returns `array('team_can'=>0)` ONLY (no other keys). Handlers/shell MUST guard `team_can` before reading other keys (they do).

**`.ajax` + `.js__mt-box` DOUBLE-LOAD GOTCHA (confirmed bug):** shell boxes carry BOTH classes (`class="ajax js__mt-box"`). `init()` calls BOTH `_autoload()` (selects `.ajax:not(.loaded)`) AND `crm_autoload()` (selects `.js__mt-box:not(.loaded)`) in same pass → each box fetched twice. `_autoload` posts with NO period param (data-options='{}'); `crm_autoload` posts WITH period. Race: whichever resolves last wins; `_autoload` (no period) may overwrite period-correct result, and doubles DB load on the heavy overview aggregate. Fix: shell boxes should NOT carry `.ajax` class (use only `.js__mt-box`), OR `crm_autoload` should claim+mark them before `_autoload` runs. Both handlers tolerate a missing period (scope helper falls back to this_month) so it's not fatal — but it is wasteful + nondeterministic.

**Period resolver:** POST-first then GET fallback (`Input::post('period') ?: Input::get('period','this_month')`). custom = valid `YYYY-MM-DD` from+to (regex-guarded, strtotime). switch supports today/this_week/this_month/last_month/last_7_days/last_30_days but SHELL only renders 3 pills (today/this_week/this_month) — other periods reachable only via direct POST, not UI.

**Var contract verified:** every Smarty var each box tpl reads is assigned by its handler (mt_kpi.* incl dang_cham/bo_quen/chua_data accumulated in rep loop; mt_rows, mt_dept_performance, mt_new/uncalled/interested_list, mt_donut*, mt_urgent, mt_momentum, mt_feed). No used-but-unassigned. No literal `_empty` in any box tpl (would auto-remove box).
