# TASKS PENDING — skyrealty.c-a.vn

> Cập nhật: 15/07/2026. Nguồn chi tiết: `plans/260714-frontend-perf-quickwin/` + memory `web-performance-initiative`, `profile-personnel-import`.
> Môi trường: MaxxCMS/ISOCMS 6.0 · PHP 7.4 · MariaDB 10.11 · shared cPanel (**không có SUPER**, **nginx proxy trước Apache**) · deploy FTP · **KHÔNG staging**.

---

## 🔴 P0 — LOOSE END: Import nhân sự (có thể đang SAI dữ liệu trên live)

**Vấn đề:** 3 file đã sửa xong ở local nhưng **CHƯA xác nhận upload**, và **backfill chưa chạy**. Nếu chưa upload → `regional_id` của **~568 sale đang SAI** (bị gán 12134 "Phòng Kinh doanh" thay vì đúng Khối S1/S2/S5/S6/S10/Đà Nẵng/Nha Trang).

**Cần làm:**
1. Upload 3 file (nhớ **Revert File trong VS Code trước** — file này từng bị editor ghi đè bản cũ):
   - `models/Property.php` — thêm `getBusinessAreaId($department_id)`: leo cây `parent_id` tìm tổ tiên có `more_information.is_business_area=1`; không có → 0. Có cache tĩnh.
   - `models/Profile.php` — `updateMore()` dùng helper trên cho `regional_id` (thay heuristic parent cũ bị sai).
   - `admin/application/modules/developer/sub_default.php` — action backfill.
2. Chạy backfill: `?mod=developer&act=backfill_profile_more` → preview đếm hồ sơ → xác nhận → ghi `department_name`/`role_name`/`regional_id` cho toàn bộ hồ sơ.

**Bằng chứng lỗi cũ:** dept 57(Khối S5,153 NS), 81(Đà Nẵng,114), 202(Khối S2,113), 11259(Khối S1,106), 10474/82/11016(~80) — logic cũ trả `regional_id=12134` (không phải vùng KD). Logic mới trả đúng id Khối.

**Lưu ý:** 291 hồ sơ ở dept 12134 + ~90 ở phòng ngoài KD sẽ có `regional_id=0` (đúng quy tắc — không thuộc vùng KD nào). Nếu 291 người đó thực chất thuộc 1 Khối → là **lỗi gán phòng ban**, cần sửa dữ liệu, không phải lỗi logic.

---

## 🔴 P1 — WS3: Fix query DB (tác động lớn nhất, đã có bằng chứng)

### 3.1 + 3.2 — `stock_log`: thêm index **VÀ** sửa query non-sargable (PHẢI ĐI CÙNG NHAU)
**Bằng chứng (EXPLAIN live):** `type=ALL | key=NONE | rows=69.934 | Using where` — **quét sạch 70k dòng MỖI CALL**.

**Vị trí:** [`application/modules/ajax/sub_stock.php:1017`](application/modules/ajax/sub_stock.php:1017) và [`:1570`](application/modules/ajax/sub_stock.php:1570) — **AJAX siêu nóng** (mỗi lần user thao tác quỹ căn).
```php
$clsStockLog->getByCond("`stock_type`='..' AND `agency_id`='..' AND `block_id`='..'
   AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", ...)
```
**2 lỗi chồng nhau:**
1. `default_stock_log` (69.934 dòng) **KHÔNG CÓ INDEX NÀO, kể cả PRIMARY**.
2. **`FROM_UNIXTIME(reg_date,...)` bọc lên cột → non-sargable** ⇒ *thêm index mà không sửa query thì index VẪN VÔ DỤNG*.

**Việc cần làm:**
- DDL (cần duyệt, ~70k dòng nên nhanh):
  `CREATE INDEX idx_stocklog_lookup ON default_stock_log (stock_type, agency_id, block_id, reg_date);`
  (cân nhắc thêm `(project_id, reg_date)` cho biến thể ở `:1570`; và PRIMARY/index cho `id`)
- Code: đổi `FROM_UNIXTIME(reg_date,'%d/%m/%Y')='<hôm nay>'` → **range sargable**:
  `reg_date >= <strtotime đầu ngày> AND reg_date < <strtotime đầu ngày mai>`
- Cột `stock_log`: `id, stock_type, project_id, block_id, agency_id, more_information, user_id, reg_date, is_trash`.
- Sau khi sửa: EXPLAIN lại → phải ra `type=ref/range`, `rows` nhỏ.

### 3.3 — `stock_meta` không có index
`getOne($id)` → `WHERE id='X'` **full scan** (EXPLAIN `type=ALL, key=NONE`). Hiện rẻ (170 dòng) nhưng sai cấu trúc.
→ `ALTER TABLE default_stock_meta ADD PRIMARY KEY (id);` (kiểm `id` có unique/auto_increment không trước) + index `stock_id`.

### 3.4 — Listing stock filesort
`SELECT * FROM default_stock WHERE is_trash=0 AND stock_type=178 ORDER BY upd_date DESC LIMIT 20`
→ `type=range, rows=8.944, **Using filesort**` (đọc 8.944 dòng + sort tay để lấy 20).
→ Thêm composite index khớp filter+sort, vd `(is_trash, stock_type, upd_date)`.

### 3.5 — `SELECT *` gây 97% temp-disk (rủi ro cao, làm sau/chọn lọc)
`Created_tmp_disk_tables/Created_tmp_tables = 8.15M/8.41M = **97%**` (khoẻ phải <25%).
Nguyên nhân: `DbBasic::getAll` mặc định `field="*"` (**1807 call**) kéo cột `longtext` (`more_information`/`logs`/`content`/`media`) vào bảng tạm → **MariaDB không giữ temp có TEXT/BLOB trong RAM ⇒ ép ghi đĩa**.
→ **Chỉ sửa path NÓNG** (chỉ định cột cụ thể), KHÔNG sửa cả 1807 call.

---

## 🟠 P2 — WS1b: nginx chèn `Cache-Control: no-cache` (cần cPanel/host — tôi không làm được)

**Hiện trạng:** `.htaccess` đã set đúng (`max-age=31536000` cho css/js, `2592000` cho ảnh/font, public-only) **nhưng nginx proxy thêm `Cache-Control: no-cache` SAU Apache** ⇒ browser gộp header ⇒ **vẫn revalidate mỗi asset** (~60 round-trip 304 thay vì 0 request). `.htaccess` không gỡ được.

**Ticket gửi host:**
> Website skyrealty.c-a.vn chạy nginx reverse proxy trước Apache. nginx đang thêm header `Cache-Control: no-cache` vào mọi response, ghi đè cache-control mà Apache/.htaccess đã set cho file tĩnh (css/js/ảnh/font). Nhờ bên mình **bỏ `add_header Cache-Control no-cache`** cho file tĩnh (hoặc để header từ Apache pass-through). Mục đích: cho trình duyệt cache file tĩnh, giảm tải server.

Nếu có Engintron → sửa `/etc/nginx/conf.d/` (cần SSH/root).

---

## 🟠 P3 — WS6-deep: con voi 1.28MB ở trang login (cần plan riêng)

Sau khi trim còn **1.63MB/29 asset**. Phần còn lại:
| File | gzip | Ghi chú |
|---|---|---|
| `store.min.js` | **555 KB** | **KHÔNG phải store.js chuẩn (~2KB)** — là **bundle app nhà làm**, chứa cả `$Core.swal` |
| `script.min.js` | 434 KB | |
| `bootstrap.js` | 188 KB | **chưa minify** |
| `jquery.easyui.min.js` | 103 KB | dính `$.noConflict(true)` ở `index.tpl` |

**Cần:** gỡ rối bundle minified → xác định tập tối thiểu → chiến lược test/rollback. **Vỡ login = sập đường vào hệ thống** ⇒ không cook liều.
**Đã biết:** login chỉ phụ thuộc **`$Core.swal`** (2 chỗ trong `application/views/auth/js/jquery.auth.js`). Pattern gate an toàn đã kiểm chứng: `{if $mod ne 'auth'}` trong `application/views/index.tpl`.
🔒 **RÀNG BUỘC USER: file trong `application/views/**` là code đang phát triển → KHÔNG compress/minify.** Chỉ được gate/điều kiện hoá.

---

## 🟡 P4 — Việc nhỏ / theo dõi

- **Điền số DevTools** vào `plans/260714-frontend-perf-quickwin/phase-01-baseline.md` + `phase-03`: transferred/DOMContentLoaded của trang chủ + 1 trang nặng (lần 2) — để đối chiếu trước/sau. *(Chỉ user đo được.)*
- **`stock` sẽ bloat lại** theo thời gian do churn crawl → **OPTIMIZE định kỳ** (giờ vắng; bảng nóng, khoá lúc rebuild).
- **`stock_meta` có 3 cột chết** không code nào ghi: `meta_value`, `content`, `more_information` → có thể DROP dọn schema.
- **Rác đĩa (không nạp ở public, không ảnh hưởng tốc độ)**: emojipicker CSS 5.7MB, base64.css 725KB, ca.min.css 629KB, fabric, datatables-bootstrap5.
- **WS5 — Redis**: đã có sẵn (Predis + SimpleRedisCache, `models/Cache.php`) → mở rộng cache cho dashboard counts / dropdown / cây phòng ban.
- **CDN không ghim version**: `cdn.jsdelivr.net/npm/pace-js@**latest**` trong `application/views/index.tpl` — rủi ro supply-chain, nên ghim version.
- **`.htaccess` backup**: `.htaccess.bak-260715` (rollback = upload đè). `index.tpl` backup: `index.tpl.bak` (trên FTP).

---

## ✅ ĐÃ XONG (không cần làm lại — để tham chiếu)

| WS | Kết quả | Verify |
|---|---|---|
| **WS4** dọn bloat DB | `stock_meta` 959MB→1.15MB · `stock` 119MB→12MB (**−1.06 GB**) | ✅ |
| **WS1** cache `.htaccess` | `no-store` mất → asset **111KB→304**; admin không đổi; không 500 | ✅ live |
| **WS6** trim login | **1.97MB→1.63MB**, 36→29 asset (−347KB); gate `{if $mod ne 'auth'}` | ✅ live, **login OK** |
| **WS2** đo baseline | 92 q/s · 97% temp-disk · 30.9 tỷ dòng scan · buffer pool hit 99.995% (RAM ổn) | ✅ |
| — | Xoá cột `default_profile.contract_date` (trống, không code dùng) | ✅ |
| — | Nút xoá dự án (cascade 6 nhóm) + dọn rác mồ côi (`?mod=developer&act=clean_orphan_property`) | ✅ |

**Kết luận WS2:** không có query >3s đáng kể (chỉ 144/18.75 ngày) — **chết vì ngàn nhát cắt**. RAM/IO vô tội. Lỗi ở **cách viết query** → WS3 là hướng đúng, đã có bằng chứng.
