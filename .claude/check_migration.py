#!/usr/bin/env python3
"""Dry-run / safety check for ADDITIVE DDL on the live DB (replacement for the missing
db_tunnel --check-migration). Validates preconditions via SHOW (clean reads) instead of
executing the DDL (tunnel garbles write-responses), so it works in this environment.

Supports: ALTER TABLE t ADD COLUMN c ... | ADD [UNIQUE] INDEX/KEY name (cols) | DROP INDEX name
Verdict: SAFE / CONFLICT / DUP / UNKNOWN. With --apply: if SAFE, run the DDL then re-verify.

Usage:
  python .claude/check_migration.py "ALTER TABLE default_customer ADD INDEX idx_phone (phone)"
  python .claude/check_migration.py --apply "ALTER TABLE ... ADD COLUMN task_due_date int(8) NULL"
"""
import sys, os, re
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import db_tunnel as T

cfg = T.load_cfg()

def run(sql):
    raw = T.call(cfg, "Q", [sql])
    return T.parse(raw, "Q", 1)["results"][0]

def col_idx(r, name):
    return r["columns"].index(name)

def indexes(table):
    r = run(f"SHOW INDEX FROM `{table}`")
    if r.get("error"): return None, r["error"]
    ki = col_idx(r, "Key_name")
    return set(row[ki] for row in r.get("rows", [])), None

def columns(table):
    r = run(f"SHOW COLUMNS FROM `{table}`")
    if r.get("error"): return None, r["error"]
    fi = col_idx(r, "Field")
    return set(row[fi] for row in r.get("rows", [])), None

def parse_cols(s):
    return [c.strip().strip("`").strip() for c in s.split(",") if c.strip()]

def check(ddl):
    mt = re.search(r"ALTER\s+TABLE\s+`?(\w+)`?\s+(.*)", ddl, re.I | re.S)
    if not mt:
        return ("UNKNOWN", "Không phải ALTER TABLE — không validate được", None)
    table, rest = mt.group(1), mt.group(2).strip().rstrip(";")
    cols, err = columns(table)
    if err: return ("CONFLICT", f"Bảng `{table}` lỗi: {err}", table)
    idxs, _ = indexes(table)

    m = re.search(r"ADD\s+COLUMN\s+`?(\w+)`?", rest, re.I)
    if m:
        c = m.group(1)
        if c in cols: return ("CONFLICT", f"Cột `{c}` ĐÃ tồn tại trên `{table}`", table)
        return ("SAFE", f"ADD COLUMN `{c}` — cột chưa có, INSTANT", table)

    m = re.search(r"DROP\s+INDEX\s+`?(\w+)`?", rest, re.I)
    if m:
        n = m.group(1)
        if n not in idxs: return ("CONFLICT", f"Index `{n}` KHÔNG tồn tại để drop", table)
        return ("SAFE", f"DROP INDEX `{n}` — tồn tại, drop được", table)

    m = re.search(r"ADD\s+(UNIQUE)?\s*(?:INDEX|KEY)?\s+`?(\w+)`?\s*\(([^)]+)\)", rest, re.I)
    if m:
        uniq, name, colspec = m.group(1), m.group(2), m.group(3)
        want = parse_cols(colspec)
        missing = [c for c in want if c not in cols]
        if missing: return ("CONFLICT", f"Cột không tồn tại: {missing}", table)
        if name and name in idxs: return ("CONFLICT", f"Index `{name}` ĐÃ tồn tại", table)
        if uniq:
            colsql = ", ".join(f"`{c}`" for c in want)
            r = run(f"SELECT {colsql}, COUNT(*) c FROM `{table}` GROUP BY {colsql} HAVING c>1 LIMIT 5")
            if r.get("error"): return ("UNKNOWN", f"Dedup check lỗi: {r['error']}", table)
            ndup = r.get("num_rows", len(r.get("rows", [])))
            if ndup and int(ndup) > 0:
                return ("DUP", f"Còn {ndup}+ nhóm trùng ({want}) — phải dedupe TRƯỚC khi add UNIQUE", table)
            return ("SAFE", f"ADD UNIQUE `{name}` ({want}) — 0 nhóm trùng, an toàn", table)
        return ("SAFE", f"ADD INDEX `{name}` ({want}) — cột có, tên trống, INSTANT", table)

    return ("UNKNOWN", "Không nhận dạng được dạng DDL", table)

def verify_after(ddl, table):
    # confirm the change is now present
    mcol = re.search(r"ADD\s+COLUMN\s+`?(\w+)`?", ddl, re.I)
    mdrop = re.search(r"DROP\s+INDEX\s+`?(\w+)`?", ddl, re.I)
    madd = re.search(r"ADD\s+(?:UNIQUE\s+)?(?:INDEX|KEY)?\s+`?(\w+)`?\s*\(", ddl, re.I)
    if mcol:
        cols, _ = columns(table); return mcol.group(1) in (cols or set())
    if mdrop:
        idxs, _ = indexes(table); return mdrop.group(1) not in (idxs or set())
    if madd:
        idxs, _ = indexes(table); return madd.group(1) in (idxs or set())
    return None

def main():
    args = sys.argv[1:]
    apply = False
    if args and args[0] == "--apply":
        apply = True; args = args[1:]
    if not args:
        print(__doc__); return
    ddl = args[0]
    verdict, msg, table = check(ddl)
    print(f"[{verdict}] {msg}")
    if apply:
        if verdict != "SAFE":
            print("  -> NOT applied (chỉ áp khi SAFE).")
            return
        T.call(cfg, "Q", [ddl])  # write-response garbled; ignore
        ok = verify_after(ddl, table)
        print(f"  -> applied; verify present = {ok}")

if __name__ == "__main__":
    main()
