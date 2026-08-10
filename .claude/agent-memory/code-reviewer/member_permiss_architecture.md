---
name: member-permiss-architecture
description: Member "Phân quyền" storage — default_member_source (MemberSource model) for module member; member_moc writes default_member.permiss_mod instead; MemberMeta.php is a stale permiss-clone pointing at nonexistent default_member_meta
metadata:
  type: project
---

Member permission ("Phân quyền") storage as of 2026-07-02:

- Module `admin/application/modules/member` (MF+MOC via `source` param) stores per-member overrides in **`default_member_source`** via `models/MemberSource.php` (upsert on member_id+source; `permiss_mod` = JSON **DIFF vs package default**, code=>1 add / 0 remove). Table exists on live only (8220 seeded rows, no UNIQUE(member_id,source) yet — as-built doc `migrations/member_source_permiss.sql` recommends the ALTER). No CA code besides the two popup handlers reads this table — the runtime consumer is presumed the MF/MOC front (separate repo).
- Module `admin/application/modules/member_moc` uses a DIFFERENT older mechanism: writes full map to `default_member.permiss_mod` column directly (sub_default.php:1357). Two divergent write paths for the same members — do not assume parity.
- `models/MemberMeta.php` is NOT the broker post-meta model anymore: its body is a clone of MemberSource (permiss methods) with `tbl = default_member_meta`, a table that does NOT exist on live. Broker (`application/modules/broker/sub_default.php` 5 call sites) only uses inherited DbBasic methods so no fatals at class level, but its meta feature hits a missing table — pre-existing, flagged for cleanup.
- `default_property.property_type` package tags are string LITERALS `'_MF_PACKAGE'` / `'_PACKAGE'` (verified member/sub_default.php:1651), not PHP constants.
- DbBasic facts verified: `updateOne` returns ADOdb RecordSet (truthy even with 0 affected rows — no-change saves still succeed); `insert` returns 1/0 and qstr-escapes values; `getByCond` appends no LIMIT (embedding `LIMIT 0,1` in $cond is the house pattern) and returns 0 when not found.

**Why:** popup save bug 2026-07-02 was handlers writing to nonexistent `default_member_meta`; fix swapped to MemberSource/default_member_source.
**How to apply:** when reviewing member/broker/permiss changes, check which of the 3 storage locations is being read/written and whether the diff-vs-package semantics match the consumer.
