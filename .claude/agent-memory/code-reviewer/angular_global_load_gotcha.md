---
name: angular-global-load-gotcha
description: AngularJS is NOT loaded globally on the front app — only worldcup/default.tpl pulls angular.min.js; index.tpl head load is commented out
metadata:
  type: project
---

AngularJS 1.8.2 is loaded **per-module, not globally**, on the front app (`application/views/index.tpl`).

- `application/views/worldcup/default.tpl:668` is the ONLY template that `<script src=".../angularjs/angular.min.js">` — so angular exists only on the worldcup page.
- `application/views/index.tpl:125` has the global angular load **commented out** (`<!-- ... -->`); line 126 loads `fnc/chat.ng.js` unconditionally (gated by `checkDEV()` block region? no — it's in head, always loaded when index.tpl renders).
- `chat.ng.js` IIFE bails immediately on `if (typeof angular === "undefined") { return; }` (line 11) → on every non-worldcup page the chat Angular module never registers and `angular.bootstrap(#fhchat)` never runs → widget dead.

**Implication for any Angular-front work:** if you move/rewrite a front feature to AngularJS, you MUST ensure angular.min.js is loaded on the pages that feature appears on. The chat widget renders in `application/views/_footer.tpl:102` (`{$core->getBlock('chat')}`, gated `{if $clsISO->checkDEV()}`) = on ALL pages for DEV users, so it needs angular loaded GLOBALLY in index.tpl head (uncomment line 125), not just on worldcup.

Render order (verified): index.tpl head defines `path_ajax_script` (135) + `profile_id` (146, a STRING `'{$profile_id}'`) BEFORE footer block (242/248) emits the chat block's `CHAT_BOOT` script + `#fhchat`. `angular.element(document).ready` fires post-DOM → CHAT_BOOT populated. So bootstrap ORDER is fine; the missing angular SCRIPT is the blocker.
