<div class="container-xxl flex-grow-1 container-p-y pt-2 crm_page crm-ld crm-cm">
	{if $cm_denied}
	<div class="alert alert-warning d-flex align-items-center gap-2 mt-3" role="alert">
		<i class="bx bx-lock-alt fs-4"></i>
		<div>Chỉ <b>Ban điều hành / Quản lý nhóm / Marketing</b> mới xem được mục này. <a href="/crm/" class="alert-link">Về danh sách khách</a>.</div>
	</div>
	{else}
	<div class="d-flex flex-wrap align-items-end justify-content-between mb-3 gap-2 crm-ld-head">
		<div>
			<h4 class="crm-ld-title mb-0">Giám sát chăm sóc khách</h4>
			<div class="crm-ld-sub">Bấm một tư vấn viên để xem chi tiết từng khách. Kỳ áp cho cột "lần chăm".</div>
		</div>
		<div class="d-flex gap-2 align-items-center flex-wrap">
			<div class="btn-group js__cm-period" role="group" aria-label="Kỳ báo cáo">
				<button type="button" data-period="last_7_days" class="btn btn-outline-default" onClick="$Core.crm.cm_set_period(this)">7 ngày</button>
				<button type="button" data-period="last_30_days" class="btn btn-primary" onClick="$Core.crm.cm_set_period(this)">30 ngày</button>
				<button type="button" data-period="this_month" class="btn btn-outline-default" onClick="$Core.crm.cm_set_period(this)">Tháng này</button>
				<button type="button" data-period="last_month" class="btn btn-outline-default" onClick="$Core.crm.cm_set_period(this)">Tháng trước</button>
			</div>
			<a href="/crm/" class="btn btn-outline-default text-nowrap"><i class="bx bx-arrow-back me-1"></i> Khách</a>
		</div>
	</div>
	<div class="crm-ld-panel mb-3"><div class="d-flex flex-wrap gap-2 align-items-center p-2 px-3">
		<select class="form-select form-select-sm w-auto js__cm-sale" onChange="$Core.crm.cm_filter_sale(this)">
			<option value="0">Tất cả tư vấn viên</option>
			{if !empty($cm_sales)}{foreach from=$cm_sales item=_s}<option value="{$_s.id}">{$_s.name|escape}</option>{/foreach}{/if}
		</select>
		<label class="fs-12 d-inline-flex align-items-center gap-1 mb-0"><input type="checkbox" class="js__cm-overdue"> Chỉ quá hạn</label>
		<label class="fs-12 d-inline-flex align-items-center gap-1 mb-0">Idle &gt; <input type="number" min="0" class="form-control form-control-sm js__cm-idle" value="0" style="width:42px"> ngày</label>
		<span class="fs-11 text-muted">(bộ lọc quá hạn / idle áp khi mở chi tiết một tư vấn viên)</span>
	</div></div>
	<details class="crm-cm-legend mb-3">
		<summary>Giải nghĩa thuật ngữ</summary>
		<div class="crm-cm-legend-body">
			<div><b>Lần chăm</b>: số lượt chăm sóc đã hoàn thành trong kỳ — <i class="bx bx-phone"></i> gọi · <i class="bx bx-message-rounded"></i> Zalo.</div>
			<div><b>Chạm gần nhất</b>: thời điểm hoạt động gần nhất với khách (đỏ = lâu chưa chạm).</div>
			<div><b>Quá hạn</b>: số lịch hẹn (follow-up) đã tới hạn nhưng <b>chưa thực hiện</b>.</div>
			<div><b>Chưa chạm</b>: số khách đang chăm <b>chưa được liên hệ lần nào</b>.</div>
			<div><b>TTFt</b> (Time-to-first-touch): thời gian trung bình từ khi khách vào → <b>lần chạm đầu tiên</b> (đo tốc độ phản hồi).</div>
			<div><b>Ngày vào/giao</b>: ngày khách được giao cho tư vấn viên; dấu <b>~</b> = ước lượng theo ngày tạo khách.</div>
			<div><b>Idle</b>: số ngày kể từ lần chạm gần nhất.</div>
		</div>
	</details>
	<div id="box_care_monitor">
		<div class="text-center py-5 text-muted"><span class="spinner-border spinner-border-sm"></span> Đang tải…</div>
	</div>
	{literal}<script>
	$(function () { 
		if (window.$Core && $Core.crm) { 
			$Core.crm.load_care_monitor(); 
		}
	});</script>{/literal}
	{/if}
</div>
{$scriptJs}
