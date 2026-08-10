# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`ca.futurehomes.vn` — the back-office **CRM/ERP** for Future Homes Group (Vietnamese real estate). Legacy **MaxxCMS / ISOCMS 6.0** app: **PHP 7.4 + Smarty + ADOdb + jQuery + Redis**, Sneat (Bootstrap 5) theme. Runs on **shared cPanel hosting — no Node at runtime, no staging, no git**. Deploy = FTP upload of changed files (VS Code `uploadOnSave` to live).

> Deep, current knowledge lives in `docs/` — read these before substantial work:
> - `docs/ai-memory.md` — condensed architecture + decisions (read first)
> - `docs/coding_style.md` — the mandatory style standard (PHP/CSS/JS/Smarty)
> - `docs/crm_module_reference.md`, `docs/RULES.md`, `docs/coding_crm_rules.md` — CRM specifics

## Commands

There is no build, test runner, or package manager. Verification is per-file linting; "running" means hitting the live site (admin is behind login — the user tests).

```bash
# Lint — REQUIRED after every PHP/JS edit (php available at /c/xampp/php/php)
php -l  path/to/file.php
node --check path/to/file.js

# Read-only DB query against live (write queries need user approval)
PYTHONUTF8=1 python .claude/db_tunnel.py "SELECT id, title FROM default_price_sheets WHERE is_trash=0"

# Regenerate combined CSS/JS bundles (run only when bundle membership changes)
php minify-css.php
php minify-js.php
```

After changing any static CSS/JS, **bump `upd_version` in `application/_header.php`** (and the admin header) to bust the `?v=` cache.

## Architecture you must internalize

**Three entrypoints / module trees — grep the right one(s).** Public/front + AJAX live in `application/modules/<mod>/`; **admin is a nested app** at `admin/application/modules/<mod>/` (editing an admin screen means editing under `admin/...`, not the root tree); the standalone **REST API is a separate app** at `api/` with its own `index.php`/`init.php`/`modules/`. Same dispatch convention across all three.

**Module dispatch.** Each `modules/<mod>/index.php` reads `sub`/`act` from the query string and calls `Module->run($sub, $act)`. Handler functions are named **`{sub}_{act}`** (e.g. `default_save`) and live in `sub_<sub>.php`; view logic in `mod_<sub>.php`; templates in `application/views/<mod>/*.tpl`. JS per module is `application/views/<mod>/js/jquery.<mod>.js` exposing `$Core.<mod> = { init, ... }`.

**Models** are flat in `models/<Name>.php` (e.g. `Customer.php`, `Member.php`, `CRM.php`), backed by `DbBasic` over ADOdb. Table prefix is `default_`.

**Databases.** Primary `fhgroupt_user` via `$dbconn` (everything `default_*`). Secondary `fhgroupt_mf` via `$dbconnMF` (model `MF_Member`/`MemberPackage`). MF packages use `_MF_PACKAGE`/column `package_id`; legacy role-based packages use `_PACKAGE`/column `role_id`.

**Org tree.** Departments form a tree (`_DEPARTMENT`: KD → Vùng → FH → sale). Subtree membership is resolved by `list_department_id LIKE '%|id|%'` — not recursion.

**CA vs MF divergence.** This codebase (CA) has diverged far from the related MF repo (`dev.myfuture.vn`). CRM, `crm_search`, `notify_task`, and `Customer.php` exist **only in CA** — do not assume parity or port between them.

**Cron.** `cronjobs/*.php` (notably `notify_task.php` for follow-up reminders) run on the host scheduler, not via the web app.

**Migrations & i18n.** No migration runner — `migrations/*.sql` and `scripts/migrations/` are raw SQL applied manually against live (needs user approval; verify with `.claude/check_migration.py`). UI strings live in `lang/vn.php`.

## Hard constraints (these break production if ignored)

- **IonCube-encrypted — never edit:** anything under `/core/`, and some module `index.php`/`sub_default.php` files are minified/encoded. Byte-patch minified files by locating unique strings, not line numbers.
- **Never touch Sneat originals:** `admin.css`, `global.min.css`, `style.css`. UI overrides go in `application/themes/css/crm.css` (CRM) or `admin/application/themes/css/admin-redesign.css` (admin). No inline CSS in templates (except data-driven values like `style="width:{$pct}%"`).
- **Smarty templates:** autoescape is OFF — escape all user/DB output with `|escape`. Keep `{if}`/`{foreach}`/`{literal}` tags balanced (unbalanced tags have caused fatal errors in production — this is your responsibility on any file you touch). No business logic in `.tpl`; full-page `act`s must end with `{$scriptJs}`.
- **JS modals:** the app does not expose `window.bootstrap`. Open modals via `$Core.popup.open/openfull` or jQuery `.modal('show')` — never `new bootstrap.Modal()`.
- **PHP 7.4-safe only** — no 8.0+ syntax.
- **EOL/BOM:** preserve each file's original line endings (most CRM files are CRLF) and BOM. Upload such files in FTP **binary** mode.
- **Boxicons is an old build (<2.1):** confirm an icon exists by grepping `.bx-<name>:before` in `application/themes/vendor/fonts/boxicons.css` before using it.
- **SQL:** never `SELECT *`; cast/escape all user input (`(int)`, `qstr`); never run UPDATE/DELETE/ALTER on live without explicit user approval. No staging exists.

## Style essentials

Full rules in `docs/coding_style.md` — the load-bearing ones: reuse what exists (Bootstrap/Sneat utilities, `$Core.*`, models, `_CRM_*`/`_FOLLOWUP_*`/`_DEPARTMENT_*` constants in `config.php`) before writing new; **≤ 1 statement per line** (no crammed nested loops/conditions); no magic numbers; brace on same line; TAB indent; comments explain *why*, not *what*.
