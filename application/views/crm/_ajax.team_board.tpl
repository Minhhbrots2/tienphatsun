{* Lăng kính Quản lý — Hiệu suất & Sức khỏe nhóm (F5). Render bởi default_load_team_board(). Kỳ: $tb_period. *}
<div class="crm-team-board">
	<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
		<div class="d-flex align-items-center gap-2">
			<span class="avatar"><span class="avatar-initial rounded bg-label-warning"><i class="bx bx-bar-chart-alt-2 fs-4"></i></span></span>
			<div class="d-flex flex-column">
				<h5 class="mb-0">Hiệu suất &amp; Sức khỏe nhóm <span class="badge bg-label-warning ms-1">{$tb_total_reps} nhân sự</span></h5>
				<small class="text-muted">{$tb_group_count} nhóm · kỳ: {$tb_period_label} · xếp theo chốt &amp; hoạt động</small>
			</div>
		</div>
		<div class="btn-group btn-group-sm" role="group" aria-label="Kỳ báo cáo">
			<button type="button" onClick="$Core.crm.load_team_board('this_month')" class="btn btn-{if $tb_period eq 'this_month'}primary{else}outline-secondary{/if}">Tháng này</button>
			<button type="button" onClick="$Core.crm.load_team_board('last_month')" class="btn btn-{if $tb_period eq 'last_month'}primary{else}outline-secondary{/if}">Tháng trước</button>
			<button type="button" onClick="$Core.crm.load_team_board('last_7_days')" class="btn btn-{if $tb_period eq 'last_7_days'}primary{else}outline-secondary{/if}">7 ngày</button>
			<button type="button" onClick="$Core.crm.load_team_board('last_30_days')" class="btn btn-{if $tb_period eq 'last_30_days'}primary{else}outline-secondary{/if}">30 ngày</button>
		</div>
	</div>

	{* KPI strip — kỳ: {$tb_period_label} *}
	<div class="row g-2 mb-3">
		<div class="col-6 col-xl">
			<div class="card h-100 crm-kpi-card"><div class="card-body py-2 px-3">
				<span class="crm-kpi-label">Khách đang chăm</span>
				<h4 class="mb-0 mt-1 fw-bold">{$tb_kpi.active}</h4>
			</div></div>
		</div>
		<div class="col-6 col-xl">
			<div class="card h-100 crm-kpi-card"><div class="card-body py-2 px-3">
				<span class="crm-kpi-label">Chốt kỳ</span>
				<h4 class="mb-0 mt-1 fw-bold text-success">{$tb_kpi.chot_period}</h4>
			</div></div>
		</div>
		<div class="col-6 col-xl">
			<div class="card h-100 crm-kpi-card"><div class="card-body py-2 px-3">
				<span class="crm-kpi-label">Hoạt động kỳ</span>
				<h4 class="mb-0 mt-1 fw-bold text-primary">{$tb_kpi.act_total}</h4>
			</div></div>
		</div>
		<div class="col-6 col-xl">
			<div class="card h-100 crm-kpi-card"><div class="card-body py-2 px-3">
				<span class="crm-kpi-label">Khách mới</span>
				<h4 class="mb-0 mt-1 fw-bold">{$tb_kpi.new_period}</h4>
			</div></div>
		</div>
		<div class="col-6 col-xl">
			<div class="card h-100 crm-kpi-card"><div class="card-body py-2 px-3">
				<span class="crm-kpi-label">Quá hạn</span>
				<h4 class="mb-0 mt-1 fw-bold {if $tb_kpi.overdue > 0}text-danger{/if}">{$tb_kpi.overdue}</h4>
			</div></div>
		</div>
	</div>

	{if $tb_rows}
	<div class="table-responsive">
	<table class="table table-sm table-middle mb-0" width="100%">
		<thead>
			<tr class="fs-12 text-muted">
				<th class="text-left" width="34">#</th>
				<th class="text-left">Nhân sự</th>
				<th class="text-end">Khách active</th>
				<th class="text-end">Chốt kỳ</th>
				<th class="text-end">Hoạt động</th>
				<th class="text-end">Khách mới</th>
				<th class="text-end">Tỷ lệ chốt</th>
				<th class="text-end">Chưa chạm</th>
				<th class="text-end">Quá hạn</th>
				<th>Gần nhất</th>
			</tr>
		</thead>
		{foreach from=$tb_rows item=_r name=tb}
		<tr>
			<td class="text-left text-muted fs-12">{$smarty.foreach.tb.iteration}</td>
			<td class="text-left text-nowrap">
				<img class="avatar avatar-xs rounded-pill me-1" src="{$_r.rep_avatar}">
				<span class="fs-12 fw-bold">{$_r.rep_name|escape}</span>
			</td>
			<td class="text-end fs-12 fw-bold">{$_r.active}</td>
			<td class="text-end fs-12">{if $_r.chot_period > 0}<span class="badge bg-label-success">{$_r.chot_period}</span>{else}<span class="text-muted">0</span>{/if}</td>
			<td class="text-end fs-12">{if $_r.act_total > 0}<span class="fw-bold text-primary">{$_r.act_total}</span>{if $_r.act_call > 0 || $_r.act_zalo > 0}<small class="text-muted d-block" style="font-size:10px"><i class="bx bxs-phone-call"></i> {$_r.act_call} · <i class="bx bx-message-rounded"></i> {$_r.act_zalo}</small>{/if}{else}<span class="text-muted">0</span>{/if}</td>
			<td class="text-end fs-12">{$_r.new_period}</td>
			<td class="text-end fs-12">{if $_r.conv_rate > 0}<span class="fw-bold">{$_r.conv_rate}%</span>{else}<span class="text-muted">0%</span>{/if}</td>
			<td class="text-end fs-12">{if $_r.no_touch > 0}<span class="badge bg-label-danger">{$_r.no_touch}</span>{else}<span class="text-muted">0</span>{/if}</td>
			<td class="text-end fs-12">{if $_r.overdue > 0}<span class="badge bg-label-warning">{$_r.overdue}</span>{else}<span class="text-muted">0</span>{/if}</td>
			<td class="fs-11 text-nowrap">
				{if $_r.days_idle < 0}<span class="text-muted">Chưa có</span>
				{else}<span class="{if $_r.days_idle >= 7}text-danger fw-bold{elseif $_r.days_idle >= 3}text-warning{/if}">{$_r.last_text}</span>{/if}
			</td>
		</tr>
		{/foreach}
	</table>
	</div>
	{else}
	<div class="border text-center rounded-2 p-3 border-dashed text-muted">Nhóm của bạn chưa có nhân sự (hoặc chưa gán list_profile_id).</div>
	{/if}
</div>
