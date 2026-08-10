<div class="container-xxl flex-grow-1 container-p-y pt-2 crm_page crm-ld">
	{if !$exec_can}
	<div class="alert alert-warning d-flex align-items-center gap-2 mt-3" role="alert">
		<i class="bx bx-lock-alt fs-4"></i>
		<div>Chỉ <b>Ban điều hành / Quản lý cấp cao</b> mới xem được Bảng điều hành. <a href="/crm/" class="alert-link">Về danh sách khách</a>.</div>
	</div>
	{else}
	<!-- ===== Header ===== -->
	<div class="d-flex flex-wrap align-items-end justify-content-between mb-3 gap-2 crm-ld-head">
		<div>
			<h4 class="crm-ld-title mb-0">Tổng quan &amp; tăng trưởng</h4>
			<div class="crm-ld-sub">Chỉ đọc, tổng toàn hệ thống — đang lớn không, đang chốt không, chỗ nào chạy.</div>
		</div>
		<div class="d-flex gap-2 align-items-center">
			<a href="/crm/" class="btn btn-outline-secondary text-nowrap"><i class="bx bx-arrow-back me-1"></i> Danh sách khách</a>
		</div>
	</div>

	<!-- ===== KPI ===== -->
	<div class="row g-3 mb-3">
		<div class="col-6 col-xl-3"><div class="card crm-ld-kpi"><div class="card-body">
			<div class="crm-ld-kpi-label">Tổng khách hàng</div>
			<div class="crm-ld-kpi-val">{$ex_total_f}</div>
			<div class="crm-ld-kpi-sub"><i class="bx bx-data"></i> toàn hệ thống</div>
			<i class="bx bx-group crm-ld-kpi-ic"></i>
		</div></div></div>
		<div class="col-6 col-xl-3"><div class="card crm-ld-kpi"><div class="card-body">
			<div class="crm-ld-kpi-label">KH mới {$ex_current.label}</div>
			<div class="crm-ld-kpi-val">{$ex_current.count_f}</div>
			<div class="crm-ld-kpi-sub"><i class="bx bx-loader"></i> đang diễn ra</div>
			<i class="bx bx-user-plus crm-ld-kpi-ic"></i>
		</div></div></div>
		<div class="col-6 col-xl-3"><div class="card crm-ld-kpi"><div class="card-body">
			<div class="crm-ld-kpi-label">Tháng gần nhất {$ex_last_done.label}</div>
			<div class="crm-ld-kpi-val{if $ex_last_done.has_delta && !$ex_last_done.is_up} is-down{/if}">{$ex_last_done.count_f}</div>
			{if $ex_last_done.has_delta}
			<div class="crm-ld-kpi-sub {if $ex_last_done.is_up}is-up{else}is-down{/if}"><i class="bx bx-{if $ex_last_done.is_up}up{else}down{/if}-arrow-alt"></i> {if $ex_last_done.is_up}▲{else}▼{/if} {$ex_last_done.delta_pct}% kỳ trên kỳ</div>
			{else}<div class="crm-ld-kpi-sub"><i class="bx bx-minus"></i> kỳ trước</div>{/if}
			{if !$ex_last_done.has_delta}<i class="bx bx-minus crm-ld-kpi-ic"></i>{elseif $ex_last_done.is_up}<i class="bx bx-trending-up crm-ld-kpi-ic"></i>{else}<i class="bx bx-trending-down crm-ld-kpi-ic is-down"></i>{/if}
		</div></div></div>
		<div class="col-6 col-xl-3"><div class="card crm-ld-kpi"><div class="card-body">
			<div class="crm-ld-kpi-label">Tỉ lệ chốt (pipeline)</div>
			<div class="crm-ld-kpi-val">{$ex_close_rate}%</div>
			<div class="crm-ld-kpi-sub is-up"><i class="bx bx-check-circle"></i> {$ex_closed_f} đã chốt</div>
			<i class="bx bx-target-lock crm-ld-kpi-ic"></i>
		</div></div></div>
	</div>

	<!-- ===== Growth chart + Funnel ===== -->
	<div class="row g-3">
		<div class="col-12 col-lg-6"><div class="crm-ld-panel h-100">
			<div class="crm-ld-panel-h"><h4><i class="bx bx-bar-chart-alt-2"></i> Khách hàng mới theo tháng</h4><span class="crm-ld-hint">12 tháng · reg_date</span></div>
			<div class="p-3 pt-4">
				<div class="crm-ld-vel">
					<div class="crm-ld-vel-cell"><span class="crm-ld-vel-lbl">KH mới hôm nay</span><span class="crm-ld-vel-num">{$ex_vel_today}</span></div>
					<div class="crm-ld-vel-cell"><span class="crm-ld-vel-lbl">7 ngày qua</span><span class="crm-ld-vel-num">{$ex_vel_d7}</span>{if $ex_vel_d7_has}<span class="crm-ld-vel-delta {if $ex_vel_d7_up}is-up{else}is-down{/if}">{if $ex_vel_d7_up}▲{else}▼{/if} {$ex_vel_d7_pct}% vs kỳ trước</span>{/if}</div>
					<div class="crm-ld-vel-cell"><span class="crm-ld-vel-lbl">30 ngày qua</span><span class="crm-ld-vel-num">{$ex_vel_d30}</span>{if $ex_vel_d30_has}<span class="crm-ld-vel-delta {if $ex_vel_d30_up}is-up{else}is-down{/if}">{if $ex_vel_d30_up}▲{else}▼{/if} {$ex_vel_d30_pct}% vs kỳ trước</span>{/if}</div>
				</div>
				<div class="crm-ld-bars">
					{foreach from=$ex_series item=_g}
					<div class="crm-ld-bar">
						<span class="v">{$_g.count_f}</span>
						<span class="col{if $_g.is_current} is-cur{elseif $_g.is_peak} is-peak{/if}" style="height:{$_g.bar_px}px"></span>
						<span class="m">{$_g.short}</span>
					</div>
					{/foreach}
				</div>
				<div class="crm-ld-cap">Tổng {$ex_total_f} khách · cột cao nhất = tháng đỉnh · cột màu nâu = tháng đang diễn ra (chưa đủ)</div>
			</div>
		</div></div>
		<div class="col-12 col-lg-6"><div class="crm-ld-panel h-100">
			<div class="crm-ld-panel-h"><h4><i class="bx bx-filter"></i> Phễu trạng thái khách hàng</h4><span class="crm-ld-hint">tổng {$ex_funnel_total_f}</span></div>
			<div class="p-3">
				<div class="row">
				{foreach from=$ex_funnel item=_f}
				<div class="col-12 col-md-6"><div class="crm-ld-fn">
					<div class="crm-ld-fn-top">
						<span class="crm-ld-fn-name">{$_f.title|escape}{if $_f.has_conv && $_f.drop > 0}<span class="crm-ld-fn-drop" style="color:{if $_f.drop >= 80}var(--ld-danger){elseif $_f.drop >= 50}var(--ld-warning-ink){else}var(--ld-ink3){/if}"><i class="bx bx-down-arrow-alt"></i>{$_f.drop}%</span>{/if}</span>
						<span class="d-flex align-items-center gap-2">{if $_f.has_conv}<span class="crm-ld-fn-conv">{$_f.conv}%</span>{/if}<b class="ff-num text-dark">{$_f.cnt_f}</b></span>
					</div>
					<div class="crm-ld-track"><span style="width:{$_f.bar_pct}%;background:{$_f.bgcolor|escape}"></span></div>
				</div></div>
				{/foreach}
				</div>
				<div class="crm-ld-hint mt-1 d-flex align-items-center gap-1" style="color:var(--ld-ink4)"><i class="bx bx-info-circle"></i> Ảnh chụp phân bố hiện tại theo trạng thái (không phải conversion theo cohort).</div>
			</div>
		</div></div>
	</div>

	<!-- ===== Forecast ===== -->
	<div class="row g-3 mt-0">
		<div class="col-12 col-lg-6"><div class="crm-ld-fc">
			<div class="crm-ld-fc-eye"><i class="bx bx-bulb"></i> Dự báo doanh số kỳ tới <span class="crm-ld-fc-tag">ước tính</span></div>
			<div class="d-flex align-items-end gap-2"><span class="crm-ld-fc-big">{$ex_fc_rev}</span><span style="font-size:18px;font-weight:600;color:rgba(255,255,255,.7);padding-bottom:6px">tỷ ₫</span></div>
			<div class="crm-ld-fc-row">
				<div><div class="crm-ld-fc-k">Ước số deal</div><div class="ff-num" style="font-size:20px;font-weight:700;margin-top:3px">{$ex_fc_base}</div></div>
				<div><div class="crm-ld-fc-k">Khoảng tin cậy</div><div class="ff-num" style="font-size:14px;font-weight:600;margin-top:6px;color:rgba(255,255,255,.85)">{$ex_fc_low}–{$ex_fc_high} deal</div></div>
				<div style="flex:1"><div class="crm-ld-fc-k">Giá trị TB / deal</div><div class="ff-num" style="font-size:14px;font-weight:600;margin-top:6px;color:rgba(255,255,255,.85)">≈ {$ex_fc_avg_deal} tỷ</div></div>
			</div>
			<i class="bx bx-trending-up crm-ld-fc-ic"></i>
		</div></div>
		<div class="col-12 col-lg-6"><div class="crm-ld-panel h-100">
			<div class="crm-ld-panel-h"><h4><i class="bx bx-calculator"></i> Cách mô hình tính</h4><span class="crm-ld-hint">pipeline × tỉ lệ giả định</span></div>
			<div class="p-3">
				<div class="crm-ld-th-row head" style="grid-template-columns:1.4fr .7fr .7fr .7fr">
					<div>Giai đoạn</div><div class="text-end">Đang có</div><div class="text-end">Tỷ lệ</div><div class="text-end">Ra deal</div>
				</div>
				{foreach from=$ex_fc_rows item=_r}
				<div class="crm-ld-th-row body" style="grid-template-columns:1.4fr .7fr .7fr .7fr;cursor:default">
					<div class="p-2" style="font-size:13px;color:var(--ld-ink1);font-weight:600">{$_r.name}</div>
					<div class="p-2 text-end ff-num" style="color:var(--ld-ink2)">{$_r.count_f}</div>
					<div class="p-2 text-end ff-num" style="color:var(--ld-brand-d);font-weight:700">{$_r.rate}%</div>
					<div class="p-2 text-end ff-num" style="color:var(--ld-ink1);font-weight:700">{$_r.deals}</div>
				</div>
				{/foreach}
				<div class="d-flex align-items-center justify-content-between pt-2" style="font-size:12.5px;color:var(--ld-ink3)"><span class="d-flex align-items-center gap-1"><i class="bx bx-info-circle"></i> Tỉ lệ &amp; giá trị/deal là giả định — chỉnh trong handler khi có dữ liệu giao dịch thật.</span></div>
			</div>
		</div></div>
	</div>

	<!-- ===== Source structure ===== -->
	{if $ex_sources}
	<div class="crm-ld-panel mt-3">
		<div class="crm-ld-panel-h"><h4><i class="bx bxs-pie-chart-alt-2"></i> Cơ cấu nguồn lead toàn hệ thống</h4><span class="crm-ld-hint">12 tháng · tổng {$ex_src_total_f}</span></div>
		<div class="p-3 crm-ld-src-grid">
			{foreach from=$ex_sources item=_s}
			<div>
				<div class="crm-ld-src-top"><span class="crm-ld-src-name"><span class="crm-ld-src-dot" style="background:{$_s.color}"></span>{$_s.title|escape}</span><span class="crm-ld-src-val">{$_s.leads_f}</span></div>
				<div class="crm-ld-src-track"><span style="width:{$_s.bar_pct}%;background:{$_s.color}"></span></div>
			</div>
			{/foreach}
		</div>
	</div>
	{/if}

	<!-- ===== TTFt — Tốc độ tiếp cận theo nguồn ===== -->
	{if $ex_ttft_rows}
	<div class="crm-ld-panel mt-3">
		<div class="crm-ld-panel-h"><h4><i class="bx bx-time-five"></i> Tốc độ tiếp cận theo nguồn (TTFt)</h4><span class="crm-ld-hint">12 tháng · {$ex_ttft_total} lead có cú chạm đầu</span></div>
		<div class="p-3">
			<div class="crm-ld-th-row head" style="grid-template-columns:1.8fr .8fr .8fr .8fr 1.2fr">
				<div>Nguồn</div><div class="text-end">Lead chạm</div><div class="text-end">TB</div><div class="text-end">Trung vị</div><div>Tốc độ</div>
			</div>
			{foreach from=$ex_ttft_rows item=_t}
			<div class="crm-ld-th-row body{if $_t.is_low} is-muted{/if}" style="grid-template-columns:1.8fr .8fr .8fr .8fr 1.2fr;cursor:default">
				<div class="p-2" style="font-size:13px;color:var(--ld-ink1);font-weight:600">{$_t.title|escape}{if $_t.is_low} <span class="crm-ld-fc-tag" title="Mẫu nhỏ (dưới 5 lead) — chỉ tham khảo">n nhỏ</span>{/if}</div>
				<div class="p-2 text-end ff-num" style="color:var(--ld-ink2)">{$_t.n}</div>
				<div class="p-2 text-end ff-num" style="color:var(--ld-ink3)">{$_t.avg_label}</div>
				<div class="p-2 text-end ff-num" style="color:var(--ld-ink1);font-weight:700">{$_t.med_label}</div>
				<div class="p-2"><div class="crm-ld-track"><span style="width:{$_t.bar_pct}%;background:var(--ld-warning)"></span></div></div>
			</div>
			{/foreach}
			<div class="crm-ld-hint mt-1" style="color:var(--ld-ink4)">Trung vị = thời gian điển hình từ lúc lead vào đến cú gọi/gặp đầu tiên; TB dễ bị kéo bởi ngoại lệ. "n nhỏ" (&lt;5 lead) chỉ tham khảo.</div>
		</div>
	</div>
	{/if}
	{/if}
</div>{$scriptJs}
