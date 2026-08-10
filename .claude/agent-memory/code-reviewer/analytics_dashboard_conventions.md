---
name: analytics-dashboard-conventions
description: BOD analytics dashboard (module analytics) — sub_default.php builds $assign_list bars/cards, default.tpl crm-ld-* Sneat markup, analytics.css; stacked-column flex-direction order gotcha
metadata:
  type: project
---

Module `analytics` = Dashboard BOD on ca.futurehomes.vn (PHP 7.4, Smarty 3, Sneat/Bootstrap5, ADOdb `$dbconn` DB `fhgroupt_user`). Online-only, no test framework. Verify via `php -l`, Smarty tag balance, and the approved `previews/_preview_analytics_*.html` mockups.

**Why:** dashboard is iterated section-by-section; each round splices one block into the single-page tab template.

**How to apply when reviewing:**
- `sub_default.php` `analytics_dashboard()` computes everything into `$assign_list[...]`; `$pre=DB_PREFIX`, `global $dbconn`. Loop var `$r` is reused in every `foreach((array)$rows as $r)` block (DAU/cohort/proj/region/investor/aff/growth) — each reassigns before use, so name reuse across blocks is harmless. Per-block temps (`$grows/$pgmax/$gv/$gp/$gf/$tot`) stay local to their block.
- Bar-height pattern: `max(min_px, round(count*MAXpx/scaleMax))` with `scaleMax` seeded to 1 (div-by-zero safe), `0` when count==0. DAU uses 120px, gen/growth use 150px.
- `$pkg_tiers` (3 cards, snapshot of all users by package_id: VVIP=12020/Pro=12019/Free=rest) and `$pkg_growth` (12-month stacked, `array_slice(...,-12)`, month via `substr(ym,5,2)`) are SNAPSHOT (not filter-period). `$total_users` is the unfiltered base.
- Template: `crm-ld-*` classes. Stacked column = `.crm-ld-bar > .v(total) + .crm-ld-stack(.crm-ld-seg×n) + .m(month)`. Smarty tolerates a newline between `{if` and its expression (a line-broken `{if\n$g.free_h>0}` is valid). `max($pct%,4px)` works inside a style attr.
- **Stacked-column order gotcha:** `.crm-ld-stack` flex direction decides which package caps the top (rounded `border-radius:5px 5px 0 0`). With DOM order vvip→pro→free: `flex-direction:column` => vvip top / free bottom; `column-reverse` => free top / vvip bottom. The approved preview and shipped CSS have differed here before — always diff `.crm-ld-stack` flex-direction between `previews/_preview_analytics_pkg.html` and `analytics.css`.
