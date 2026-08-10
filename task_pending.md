# Task Pending

> Các mục hoãn / làm sau. Sacrifice grammar for concision.

## Co-sale (chức năng nhiều sale chung 1 căn)

Trạng thái: Phase 1–2 (import+engine), Phase 3 (form thủ công), Phase 4 (hiển thị list) — **XONG**.

Pending:

- [ ] **Visibility (Phase 4b)** — co-seller thấy GD mình tham gia trong list của họ. Cần đổi WHERE query liệt kê billing (`sub_default.php` billing-list handler ~L906 + các query list billing khác) để JOIN/subquery `default_billing_sale`. **Rủi ro cao**: đụng query chính dùng cho mọi user → làm cẩn thận, có duyệt.
- [ ] **`default_upd_billing_changed`** (`sub_default.php` ~L3630) — luồng "yêu cầu thay đổi nội dung GD" (sau hết hạn sửa) CHƯA gọi `_save_billing_shares` → sửa co-sale qua luồng này không lưu. Wire nếu cần.
- [ ] **Chia hoa hồng / loyalty theo %** — commission sync đang comment (2 hook insert/update trong `default_pop_save_billing`); FPoint loyalty chỉ tính sale chính. Đã lưu sẵn `share_ratio` + `share_value` mỗi sale để chia. Cần: tính commission + điểm loyalty cho co-seller theo `share_ratio`.
- [ ] **Badge co-seller ở list** — hiện hiển thị `seller_name` (full name). Nếu muốn code (vd S1152) → đổi sang `getIndentity($staff_id)` (thêm resolve từ `$arr_profile_cached`).

### Từ review toàn diện (14/07) — đã fix H1/H2/H3/L2; còn deferred:

- [ ] **M1 — billing_sale ghi theo giá trị CHỜ DUYỆT**: khi user đổi staff/tổng tiền cần duyệt (luồng confirm `is_ignore_confirmed`), billing giữ giá trị cũ (chờ duyệt) NHƯNG `_save_billing_shares` ghi primary/share_value theo staff/total MỚI → billing_sale lệch với billing suốt cửa sổ chờ. Fix: nhánh `is_confirmed==true` không rewrite share theo giá trị mới.
- [ ] **M2 — validate Σ% ≤ 100 phía server**: helper chỉ clamp main=0, vẫn ghi co-sellers khi Σ>100% (share_value tổng >100% căn). Import CÓ cảnh báo ≠100% nhưng form thủ công thì không. Fix: chặn/cảnh báo Σ>100 server-side.
- [ ] **M3 — orphan billing_sale khi xoá/huỷ billing**: `default_delete_billing` + `default_cancel_billing` không gọi `BillingSale::deleteByBilling` → dòng billing_sale mồ côi. Fix: gọi deleteByBilling khi hard-delete/cancel.
- [ ] **L1 — getMaxId race**: `BillingSale::saveShares` tự set pkey qua getMaxId (không atomic) dù cột auto_increment → 2 save đồng thời trùng id. Bỏ set pkey, để auto_increment tự cấp.
- [ ] **SQLi pre-existing (ngoài scope co-sale)**: `$stock_code` thô còn ở handler KHÁC (sub_default.php ~1822, ~3156, ~4077, ~6397 — getByCond/stock_hug). Nên qstr toàn bộ (đợt dọn security riêng).
- [ ] **Thiếu migration `default_billing_sale`**: docs ghi "đã tạo live" nhưng repo `migrations/` không có SQL nguồn → thêm file migration để tái tạo môi trường.

## Dashboard redesign (Sale screen — chỉ đổi chrome)

- [ ] Tiếp tục convert chrome dbx cho các box còn lại màn Sale (`views/home/default.tpl` else-block): box 2 "Thống kê giao dịch", box 3 "Thống kê bán hàng", box 4 "Giao dịch gần đây", box 5 "Thống kê quỹ căn", box 6 "Quỹ căn HOT nhất", box 7 "Giao dịch mới nhất" (làm cẩn thận từng box, verify screenshot mỗi box).
- [ ] Màn Admin (`home_screen_admin`) + Kế toán (`home_screen_accountant`) — đổi chrome dbx.
- [ ] Box check-in màn Sale: xác nhận scope (toàn công ty vs office/phòng của sale).
