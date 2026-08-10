{* Exec — Dải KPI tăng trưởng (KH mới theo reg_date). Render bởi default_load_growth(). *}
<div class="crm-exec-growth">
	<div class="row g-2 mb-2">
		<div class="col"><div class="border rounded-2 p-2 text-center h-100"><div class="fs-11 text-muted">KH mới hôm nay</div><div class="fs-20 fw-bold text-primary">{$vel_today}</div></div></div>
		<div class="col"><div class="border rounded-2 p-2 text-center h-100"><div class="fs-11 text-muted">7 ngày qua</div><div class="fs-20 fw-bold">{$vel_d7}</div>{if $vel_d7_has}<div class="fs-11 {if $vel_d7_up}text-success{else}text-danger{/if}">{if $vel_d7_up}▲{else}▼{/if} {$vel_d7_pct}% vs kỳ trước</div>{/if}</div></div>
		<div class="col"><div class="border rounded-2 p-2 text-center h-100"><div class="fs-11 text-muted">30 ngày qua</div><div class="fs-20 fw-bold">{$vel_d30}</div>{if $vel_d30_has}<div class="fs-11 {if $vel_d30_up}text-success{else}text-danger{/if}">{if $vel_d30_up}▲{else}▼{/if} {$vel_d30_pct}% vs kỳ trước</div>{/if}</div></div>
	</div>
	<div class="d-flex align-items-center gap-2 alert-message bg-label-primary p-2 rounded-2 mb-2">
		<div class="w-px-30 p-2"><i class="fa fa-line-chart text-fs-20"></i></div>
		<div class="d-flex flex-column">
			<h3 class="mb-0 text-fs-14">Tăng trưởng khách hàng <span class="badge bg-primary ms-1">{$growth_total}</span></h3>
			<small>Khách mới theo tháng (12 tháng gần nhất) — nguồn <code>reg_date</code></small>
		</div>
	</div>
	<div class="row g-2 mb-2">
		<div class="col">
			<div class="border rounded-2 p-2 text-center h-100">
				<div class="fs-11 text-muted">Tổng khách hàng</div>
				<div class="fs-20 fw-bold">{$growth_total}</div>
			</div>
		</div>
		<div class="col">
			<div class="border rounded-2 p-2 text-center h-100">
				<div class="fs-11 text-muted">KH mới {$growth_current.label} (đang diễn ra)</div>
				<div class="fs-20 fw-bold text-primary">{$growth_current.count}</div>
			</div>
		</div>
		<div class="col">
			<div class="border rounded-2 p-2 text-center h-100">
				<div class="fs-11 text-muted">KH mới {$growth_last_done.label}</div>
				<div class="fs-20 fw-bold">{$growth_last_done.count}</div>
				{if $growth_last_done.has_delta}
				<div class="fs-11 {if $growth_last_done.is_up}text-success{else}text-danger{/if}">{if $growth_last_done.is_up}▲{else}▼{/if} {$growth_last_done.delta_pct}% so với kỳ trước</div>
				{/if}
			</div>
		</div>
	</div>
	<table class="table table-sm table-middle mb-0" width="100%">
		<thead>
			<tr class="fs-12 text-muted">
				<th>Tháng</th>
				<th>KH mới</th>
				<th class="text-end">Kỳ trên kỳ</th>
				<th style="width:42%"></th>
			</tr>
		</thead>
		{foreach from=$growth_series item=_g}
		<tr{if $_g.is_current} class="table-active"{/if}>
			<td class="fs-12">{$_g.label}{if $_g.is_current} <span class="badge bg-label-secondary">đang diễn ra</span>{/if}</td>
			<td class="fs-12 fw-bold">{$_g.count}</td>
			<td class="text-end fs-11">
				{if !$_g.has_delta}<span class="text-muted">--</span>
				{elseif $_g.is_up}<span class="text-success">▲ {$_g.delta_pct}%</span>
				{else}<span class="text-danger">▼ {$_g.delta_pct}%</span>{/if}
			</td>
			<td><div class="rounded {if $_g.is_current}bg-label-secondary{else}bg-label-primary{/if}" style="height:10px;width:{$_g.bar_pct}%;min-width:2px"></div></td>
		</tr>
		{/foreach}
	</table>
	{if $growth_sources}
	<div class="mt-3 mb-1 fs-13 fw-bold text-muted"><i class="fa fa-bullseye me-1"></i>Cơ cấu nguồn lead (12 tháng) — tổng {$growth_src_total}</div>
	<table class="table table-sm table-middle mb-0" width="100%">
		<thead>
			<tr class="fs-12 text-muted">
				<th>Nguồn</th>
				<th>Lead</th>
				<th class="text-end">Tỉ trọng</th>
				<th style="width:42%"></th>
			</tr>
		</thead>
		{foreach from=$growth_sources item=_s}
		<tr>
			<td class="fs-12">{$_s.title|escape}</td>
			<td class="fs-12 fw-bold">{$_s.leads}</td>
			<td class="text-end fs-11">{$_s.pct}%</td>
			<td><div class="rounded bg-label-info" style="height:10px;width:{$_s.bar_pct}%;min-width:2px"></div></td>
		</tr>
		{/foreach}
	</table>
	{/if}
	{if $growth_statuses}
	<div class="mt-3 mb-1 fs-13 fw-bold text-muted"><i class="bx bx-doughnut-chart me-1"></i>Phân bố trạng thái khách hàng — tổng {$growth_st_total}</div>
	<table class="table table-sm table-middle mb-0" width="100%">
		<thead>
			<tr class="fs-12 text-muted">
				<th>Trạng thái</th>
				<th>Số KH</th>
				<th class="text-end">Tỉ trọng</th>
				<th style="width:42%"></th>
			</tr>
		</thead>
		{foreach from=$growth_statuses item=_st}
		<tr>
			<td><span class="badge" style="background:{$_st.bgcolor|escape}">{$_st.title|escape}</span></td>
			<td class="fs-12 fw-bold">{$_st.cnt}</td>
			<td class="text-end fs-11">{$_st.pct}%</td>
			<td><div class="rounded" style="height:10px;width:{$_st.bar_pct}%;min-width:2px;background:{$_st.bgcolor|escape}"></div></td>
		</tr>
		{/foreach}
	</table>
	{/if}
	{if $ttft_rows}
	<div class="mt-3 mb-1 fs-13 fw-bold text-muted"><i class="bx bx-time-five me-1"></i>Tốc độ tiếp cận theo nguồn (TTFt, 12 tháng) — lead có cú gọi/gặp đầu tiên: {$ttft_total}</div>
	<table class="table table-sm table-middle mb-0" width="100%">
		<thead>
			<tr class="fs-12 text-muted">
				<th>Nguồn</th>
				<th>Lead chạm</th>
				<th class="text-end">TB</th>
				<th class="text-end">Trung vị</th>
				<th style="width:30%"></th>
			</tr>
		</thead>
		{foreach from=$ttft_rows item=_t}
		<tr{if $_t.is_low} class="text-muted"{/if}>
			<td class="fs-12">{$_t.title|escape}{if $_t.is_low} <span class="badge bg-label-secondary" title="Mẫu nhỏ (dưới 5 lead), chỉ tham khảo">n nhỏ</span>{/if}</td>
			<td class="fs-12 fw-bold">{$_t.n}</td>
			<td class="text-end fs-11 text-muted">{$_t.avg_label}</td>
			<td class="text-end fs-12 fw-bold">{$_t.med_label}</td>
			<td><div class="rounded bg-label-warning" style="height:10px;width:{$_t.bar_pct}%;min-width:2px"></div></td>
		</tr>
		{/foreach}
	</table>
	<div class="fs-11 text-muted mt-1">Chỉ tính lead đã có cú gọi/gặp đầu tiên (theo thời điểm ghi nhận tác nghiệp). Trung vị phản ánh tốc độ điển hình; TB dễ bị kéo bởi ngoại lệ.</div>
	{/if}
</div>
