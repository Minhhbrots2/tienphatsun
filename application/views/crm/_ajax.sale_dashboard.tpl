{* B1.1 — Lăng kính Sale: KPI + Next-best-action + bảng SLA. Render bởi default_load_sale_dashboard(). Theme Sneat tím sẵn có. *}
<div class="crm-sale-dashboard">
	{* ===== 4 KPI card ===== *}
	<div class="row g-3 mb-3">
		<div class="col-6 col-xl-3">
			<div class="card h-100 crm-kpi-card">
				<div class="card-body d-flex justify-content-between align-items-start">
					<div>
						<span class="crm-kpi-label">Khách đang chăm</span>
						<h3 class="mb-0 mt-1 fw-bold">{$sd_active}</h3>
						<small class="text-muted">đang phụ trách</small>
					</div>
					<div class="avatar"><span class="avatar-initial rounded bg-label-primary"><i class="bx bx-user-pin fs-4"></i></span></div>
				</div>
			</div>
		</div>
		<div class="col-6 col-xl-3">
			<div class="card h-100 crm-kpi-card">
				<div class="card-body d-flex justify-content-between align-items-start">
					<div>
						<span class="crm-kpi-label">Việc hôm nay</span>
						<h3 class="mb-0 mt-1 fw-bold">{$sd_today}</h3>
						<small class="text-muted">lịch hẹn cần làm</small>
					</div>
					<div class="avatar"><span class="avatar-initial rounded bg-label-info"><i class="bx bx-calendar-check fs-4"></i></span></div>
				</div>
			</div>
		</div>
		<div class="col-6 col-xl-3">
			<div class="card h-100 crm-kpi-card">
				<div class="card-body d-flex justify-content-between align-items-start">
					<div>
						<span class="crm-kpi-label">Quá hạn</span>
						<h3 class="mb-0 mt-1 fw-bold {if $sd_overdue > 0}text-danger{/if}">{$sd_overdue}</h3>
						<small class="{if $sd_overdue > 0}text-danger{else}text-muted{/if}">cần xử lý ngay</small>
					</div>
					<div class="avatar"><span class="avatar-initial rounded bg-label-danger"><i class="bx bx-error-circle fs-4"></i></span></div>
				</div>
			</div>
		</div>
		<div class="col-6 col-xl-3">
			<div class="card h-100 crm-kpi-card">
				<div class="card-body d-flex justify-content-between align-items-start">
					<div>
						<span class="crm-kpi-label">Chưa từng chạm</span>
						<h3 class="mb-0 mt-1 fw-bold {if $sd_zero > 0}text-warning{/if}">{$sd_zero}</h3>
						<small class="{if $sd_zero > 0}text-warning{else}text-muted{/if}">chưa có tương tác</small>
					</div>
					<div class="avatar"><span class="avatar-initial rounded bg-label-warning"><i class="bx bx-user-plus fs-4"></i></span></div>
				</div>
			</div>
		</div>
	</div>

	{* ===== Next-best-action ===== *}
	{if $sd_has_nba}
	<div class="card border-0 mb-3 crm-nba">
		<div class="card-body p-0">
			<div class="row g-0">
				<div class="col-lg-8 crm-nba-hero">
					<div class="crm-nba-eyebrow mt-3"><i class="bx bx-bulb"></i> Việc nên làm tiếp theo</div>
					<div class="d-flex align-items-center gap-3 mt-3">
						<span class="avatar"><span class="avatar-initial rounded-circle crm-nba-avatar">{$sd_nba_top.initials}</span></span>
						<div class="min-w-0">
							<div class="d-flex align-items-center gap-2 flex-wrap">
								<span class="fs-5 fw-bold text-white">{$sd_nba_top.name}</span>
								{if $sd_nba_top.status}<span class="crm-nba-chip">{$sd_nba_top.status}</span>{/if}
								{if $sd_nba_top.is_hot}<span class="crm-nba-chip"><i class="bx bxs-hot"></i> Hot</span>{/if}
							</div>
							<div class="d-flex align-items-center gap-3 mt-1 crm-nba-meta">
								<span><i class="bx bx-phone"></i> {$sd_nba_top.phone_html}</span>
								<span><i class="bx bx-zap"></i> {$sd_nba_top.reason}</span>
							</div>
						</div>
					</div>
					<div class="d-flex align-items-center gap-2 mt-4">
						<a href="javascript:void(0);" customer_id="{$sd_nba_top.customer_id}" tp="follow-ups" type_id="{$smarty.const._FOLLOWUP_CALL_ID}" onClick="$Core.crm.open_activity(this, event)" class="btn btn-sm crm-nba-btn-primary"><i class="bx bxs-phone-call me-1"></i> Gọi ngay</a>
						<a href="javascript:void(0);" onClick="$Core.crm.view_customer({$sd_nba_top.customer_id})" class="btn btn-sm crm-nba-btn-ghost"><i class="bx bx-user me-1"></i> Xem hồ sơ</a>
						<a href="javascript:void(0);" customer_id="{$sd_nba_top.customer_id}" tp="follow-ups" type_id="{$smarty.const._FOLLOWUP_ZALO_ID}" onClick="$Core.crm.open_activity(this, event)" title="Nhắn Zalo" class="btn btn-sm crm-nba-btn-ghost"><i class="bx bx-message-rounded"></i></a>
					</div>
				</div>
				<div class="col-lg-4 crm-nba-queue">
					<div class="d-flex align-items-center justify-content-between mb-2">
						<span class="crm-nba-queue-title">Hàng chờ ưu tiên</span>
						<span class="crm-nba-queue-count">{$sd_nba_count}</span>
					</div>
					{if $sd_nba_queue}
					{foreach from=$sd_nba_queue item=q}
					<div class="crm-nba-queue-row" customer_id="{$q.customer_id}" onClick="$Core.crm.view_customer({$q.customer_id})">
						<span class="crm-nba-queue-av">{$q.initials}</span>
						<span class="flex-grow-1 min-w-0">
							<span class="crm-nba-queue-name">{$q.name}{if $q.is_hot} <i class="bx bxs-hot"></i>{/if}</span>
							<span class="crm-nba-queue-sub">{$q.reason}</span>
						</span>
						{if $q.lead_score > 0}<span class="crm-nba-queue-tag">{$q.lead_score}</span>{/if}
					</div>
					{/foreach}
					{else}
					<div class="crm-nba-meta">Không còn việc ưu tiên khác.</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
	{/if}

	{* ===== Bảng SLA 4 cột ===== *}
	<div class="card mb-3">
		<div class="card-header d-flex align-items-center justify-content-between py-3 cursor-pointer js__crm-sla-toggle" style="cursor:pointer">
			<h5 class="mb-0 d-flex align-items-center gap-2"><i class="bx bx-list-check text-primary"></i> Việc của tôi — hàng đợi SLA</h5>
			<span class="d-flex align-items-center gap-2">
				{if $sd_overdue > 0}<span class="badge bg-label-danger">{$sd_overdue} quá hạn</span>{/if}
				<small class="text-muted d-none d-lg-inline">bấm để mở / đóng</small>
				<i class="bx bx-chevron-down fs-4 text-muted js__crm-sla-caret"></i>
			</span>
		</div>
		<div class="row g-0 crm-sla-board d-none">
			{foreach from=$sd_buckets item=_b}
			<div class="col-6 col-lg-3 crm-sla-col">
				<div class="crm-sla-head bg-label-{$_b.color}">
					<span class="fw-bold d-flex align-items-center gap-1"><i class="bx bx-{$_b.icon}"></i> {$_b.title}</span>
					<span class="fw-bold">{$_b.count}</span>
				</div>
				<div class="crm-sla-body">
					{if $_b.rows}
					{foreach from=$_b.rows item=it}
					<div class="crm-sla-row" customer_id="{$it.customer_id}" onClick="$Core.crm.view_customer({$it.customer_id})">
						<span class="crm-sla-av">{$it.initials}</span>
						<span class="flex-grow-1 min-w-0">
							<span class="crm-sla-name">{$it.name}</span>
							<span class="crm-sla-sub">{if $it.intro}{$it.intro|escape}{else}{$it.phone_html}{/if}</span>
						</span>
						<span class="crm-sla-time {$it.time_class}">{$it.time_text}</span>
					</div>
					{/foreach}
					{if $_b.count > 6}<div class="text-center text-muted py-1" style="font-size:11px">+{$_b.count-6} nữa — lọc bảng để xem</div>{/if}
					{else}
					<div class="text-center text-muted py-3" style="font-size:12px">{$_b.empty}</div>
					{/if}
				</div>
			</div>
			{/foreach}
		</div>
	</div>
</div>
