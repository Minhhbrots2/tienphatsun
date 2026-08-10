#!/usr/bin/env python3
"""MySQL HTTP-tunnel client for ca.futurehomes.vn/core/tunel.php (SQLyog-style protocol).

Reads DB creds from configs/config.php, sends SQL queries over the HTTP tunnel,
decodes the custom binary response, prints rows as JSON.

Usage:
  python db_tunnel.py "SELECT 1"            # run one query
  python db_tunnel.py --conn                # connection test (actn=C)
  echo "SELECT ..." | python db_tunnel.py - # read query from stdin
"""
import sys, os, re, json, base64, ssl, urllib.request, urllib.parse

# Server cert chain is misconfigured (CA basic-constraints not critical); this is the
# user's own domain and they explicitly asked to connect, so skip cert verification.
SSL_CTX = ssl.create_default_context()
SSL_CTX.check_hostname = False
SSL_CTX.verify_mode = ssl.CERT_NONE

TUNNEL = "https://ca.futurehomes.vn/core/tunel.php"
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CONFIG = os.path.join(ROOT, "config.php")


def load_cfg():
    txt = open(CONFIG, "r", encoding="utf-8", errors="replace").read()
    _dbf = os.path.join(ROOT, "configs", "database.php")
    if os.path.isfile(_dbf):
        txt += "\n" + open(_dbf, "r", encoding="utf-8", errors="replace").read()
    def grab(k):
        m = re.search(r"define\(\s*'%s'\s*,\s*'(.*?)'\s*\)" % k, txt)
        return m.group(1) if m else None
    return {
        "host": grab("DB_HOST") or "localhost",
        "name": grab("DB_NAME"),
        "user": grab("DB_USER"),
        "pass": grab("DB_PASS"),
        "prefix": grab("DB_PREFIX") or "",
    }


class Reader:
    def __init__(self, b):
        self.b = b
        self.p = 0
    def take(self, n):
        d = self.b[self.p:self.p + n]
        if len(d) < n:
            raise EOFError("unexpected end of stream at %d (+%d, have %d)" % (self.p, n, len(self.b)))
        self.p += n
        return d
    def u32(self):
        return int.from_bytes(self.take(4), "big")
    def u16(self):
        return int.from_bytes(self.take(2), "big")
    def byte(self):
        return self.take(1)[0]
    def block(self):
        b0 = self.byte()
        if b0 == 0xFE:
            ln = self.u32()
        else:
            ln = b0
        return self.take(ln)
    def value(self):
        # peek
        if self.b[self.p] == 0xFF:
            self.p += 1
            return None
        return self.block().decode("utf-8", "replace")


def call(cfg, actn, queries=None):
    fields = {
        "actn": actn,
        "host": cfg["host"],
        "port": "",
        "login": cfg["user"],
        "password": cfg["pass"],
        "db": cfg["name"],
        "encodeBase64": "1",
    }
    data = urllib.parse.urlencode(fields)
    if queries:
        # q[] entries, base64 encoded
        qpart = "&".join("q%5B%5D=" + urllib.parse.quote(base64.b64encode(q.encode("utf-8")).decode())
                         for q in queries)
        data = data + "&" + qpart
    req = urllib.request.Request(TUNNEL, data=data.encode(), headers={
        "Content-Type": "application/x-www-form-urlencoded",
        "Accept-Encoding": "identity",
        "User-Agent": "fh-db-tunnel/1.0",
    })
    with urllib.request.urlopen(req, timeout=300, context=SSL_CTX) as r:
        return r.read()


def parse(raw, actn, nqueries):
    r = Reader(raw)
    magic = r.u32(); _short = r.u16(); errno = r.u32(); r.take(6)
    out = {"magic": magic, "conn_errno": errno}
    if errno > 0:
        out["error"] = r.block().decode("utf-8", "replace")
        return out
    if actn == "C":
        out["host_info"] = r.block().decode("utf-8", "replace")
        out["proto_info"] = r.block().decode("utf-8", "replace")
        out["server_info"] = r.block().decode("utf-8", "replace")
        return out
    results = []
    for qi in range(nqueries):
        qerrno = r.u32(); affected = r.u32(); insertid = r.u32(); numfields = r.u32(); numrows = r.u32(); r.take(12)
        res = {"errno": qerrno, "affected_rows": affected, "insert_id": insertid,
               "num_fields": numfields, "num_rows": numrows}
        if qerrno > 0:
            res["error"] = r.block().decode("utf-8", "replace")
        elif numfields > 0:
            cols = []
            for _ in range(numfields):
                name = r.block().decode("utf-8", "replace")
                table = r.block().decode("utf-8", "replace")
                ftype = r.u32(); flags = r.u32(); length = r.u32()
                cols.append(name)
            rows = []
            for _ in range(numrows):
                rows.append([r.value() for _ in range(numfields)])
            res["columns"] = cols
            res["rows"] = rows
        else:
            res["info"] = r.block().decode("utf-8", "replace")
        results.append(res)
        # separator byte
        sep = r.byte()
        res["_sep"] = sep
    out["results"] = results
    return out


def main():
    cfg = load_cfg()
    args = sys.argv[1:]
    if not args or args[0] in ("-h", "--help"):
        print(__doc__); return
    if args[0] == "--conn":
        raw = call(cfg, "C")
        print(json.dumps(parse(raw, "C", 0), ensure_ascii=False, indent=2))
        return
    if args[0] == "-":
        queries = [sys.stdin.read()]
    else:
        queries = [args[0]]
    raw = call(cfg, "Q", queries)
    res = parse(raw, "Q", len(queries))
    print(json.dumps(res, ensure_ascii=False, indent=2))


if __name__ == "__main__":
    main()
