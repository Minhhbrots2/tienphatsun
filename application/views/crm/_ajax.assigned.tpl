{* AJAX partial cho /crm/assigned/ — KPI + toolbar (chip+tìm) + bảng phiếu giao + hành động. Render bởi default_load_assigned(). Theme Sneat tím. *}
<div class="crm-assigned">
	{* ===== 4 KPI card ===== *}
	<div class="row g-3 mb-3">
		<div class="col-6 col-md-3">
			<div class="card h-100 crm-kpi-card">
				<div class="card-body d-flex justify-content-between align-items-start">
					<div>
						<span class="crm-kpi-label">Tổng đã giao</span>
						<h3 class="mb-0 mt-1 fw-bold">{$ma_total}</h3>
						<small class="text-muted">phiếu giao</small>
					</div>
					<div class="avatar"><span class="avatar-initial rounded bg-label-primary"><i class="bx bx-paper-plane fs-4"></i></span></div>
				</div>
			</div>
		</div>
		<div class="col-6 col-md-3">
			<div class="card h-100 crm-kpi-card">
				<div class="card-body d-flex justify-content-between align-items-start">
					<div>
						<span class="crm-kpi-label">Đã nhận</span>
						<h3 class="mb-0 mt-1 fw-bold {if $ma_confirmed > 0}text-success{/if}">{$ma_confirmed}</h3>
						<small class="text-muted">đã xác nhận</small>
					</div>
					<div class="avatar"><span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-circle fs-4"></i></span></div>
				</div>
			</div>
		</div>
		<div class="col-6 col-md-3">
			<div class="card h-100 crm-kpi-card">
				<div class="card-body d-flex justify-content-between align-items-start">
					<div>
						<span class="crm-kpi-label">Chờ nhận</span>
						<h3 class="mb-0 mt-1 fw-bold {if $ma_pending > 0}text-warning{/if}">{$ma_pending}</h3>
						<small class="text-muted">trong hạn</small>
					</div>
					<div class="avatar"><span class="avatar-initial rounded bg-label-warning"><i class="bx bx-time-five fs-4"></i></span></div>
				</div>
			</div>
		</div>
		<div class="col-6 col-md-3">
			<div class="card h-100 crm-kpi-card">
				<div class="card-body d-flex justify-content-between align-items-start">
					<div>
						<span class="crm-kpi-label">Quá hạn</span>
						<h3 class="mb-0 mt-1 fw-bold {if $ma_overdue > 0}text-danger{/if}">{$ma_overdue}</h3>
						<small class="{if $ma_overdue > 0}text-danger{else}text-muted{/if}">cần xử lý</small>
					</div>
					<div class="avatar"><span class="avatar-initial rounded bg-label-danger"><i class="bx bx-error-circle fs-4"></i></span></div>
				</div>
			</div>
		</div>
	</div>
	{* ===== Toolbar: chip lọc (kèm số) + tìm kiếm ===== *}
	<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 crm-ma-toolbar">
		<div class="d-flex gap-2 flex-wrap crm-ma-filter">
			<button type="button" class="btn btn-sm {if $ma_status eq 'all'}btn-primary{else}btn-outline-secondary{/if}" 
				onClick="$Core.crm.load_assigned('all')">Tất cả <span class="crm-ma-cnt">{$ma_total}</span></button>
			<button type="button" class="btn btn-sm {if $ma_status eq 'pending'}btn-primary{else}btn-outline-secondary{/if}" 
				onClick="$Core.crm.load_assigned('pending')">Chờ nhận <span class="crm-ma-cnt">{$ma_pending}</span></button>
			<button type="button" class="btn btn-sm {if $ma_status eq 'confirmed'}btn-primary{else}btn-outline-secondary{/if}" 
				onClick="$Core.crm.load_assigned('confirmed')">Đã nhận <span class="crm-ma-cnt">{$ma_confirmed}</span></button>
			<button type="button" class="btn btn-sm {if $ma_status eq 'overdue'}btn-primary{else}btn-outline-secondary{/if}" 
				onClick="$Core.crm.load_assigned('overdue')">Quá hạn <span class="crm-ma-cnt">{$ma_overdue}</span></button>
		</div>
		<div class="crm-ma-search">
			<i class="bx bx-search"></i>
			<input type="text" placeholder="Tìm khách / người nhận…" onkeyup="$Core.crm.filter_assigned(this)">
		</div>
	</div>
	{* ===== Bảng phiếu giao ===== *}
	<div class="card">
		{if $ma_rows}
		<div class="table-responsive">
			<table class="table table-hover align-middle mb-0 crm-ma-table">
				<thead><tr>
					<th class="text-muted">Khách hàng</th>
					<th class="text-muted">Người nhận</th>
					<th class="text-muted">Trạng thái</th>
					<th class="text-muted text-end">Hành động</th>
				</tr></thead>
				<tbody>
					{foreach from=$ma_rows item=_o name=ma}
					<tr class="crm-ma-row" data-find="{$_o.customer_name|escape|lower} {$_o.phone_mask|escape} {$_o.recipient_name|escape|lower}">
						<td>
							<div class="d-flex align-items-center">
								<div class="avatar avatar-sm me-2"><span class="avatar-initial rounded-circle bg-label-secondary">{$_o.customer_ini|escape}</span></div>
								<div class="min-w-0">
									<a href="javascript:void(0);" class="fw-semibold d-block text-truncate text-body crm-ma-open" onClick="$Core.crm.view_customer({$_o.customer_id})">{$_o.customer_name|escape}</a>
									{if $_o.phone_mask}<small class="text-muted">{$_o.phone_mask|escape}</small>{/if}
								</div>
							</div>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div class="avatar avatar-sm me-2"><span class="avatar-initial rounded-circle bg-label-primary">{$_o.recipient_ini|escape}</span></div>
								<span class="fw-semibold">{$_o.recipient_name|escape}</span>
							</div>
						</td>
						<td>
							{if $_o.status eq 'confirmed'}
							<span class="crm-st is-confirmed"><i class="bx bx-check"></i> Đã nhận</span>
							<small class="text-muted d-block mt-1">nhận {$_o.confirmed_ago}</small>
							{elseif $_o.status eq 'overdue'}
							<span class="crm-st is-overdue"><i class="bx bx-error-circle"></i> Quá hạn</span>
							<small class="text-danger d-block mt-1">chưa nhận · giao {$_o.assigned_ago}{if $_o.remind_count > 0} · đã nhắc {$_o.remind_count}×{/if}</small>
							{else}
							<span class="crm-st is-pending"><i class="bx bx-time-five"></i> Chờ nhận</span>
							<small class="text-muted d-block mt-1">giao {$_o.assigned_ago}{if $_o.remind_count > 0} · đã nhắc {$_o.remind_count}×{/if}</small>
							{/if}
						</td>
						<td>
							<div class="d-flex gap-1 justify-content-end">
								<button type="button" class="crm-ma-act"{if !$_o.can_act} disabled{/if} customer_id="{$_o.customer_id}" 
									onClick="$Core.crm.remind_given(this, event)" title="Nhắc người nhận xác nhận"><i class="bx bx-bell"></i></button>
								<button type="button" class="crm-ma-act"{if !$_o.can_act} disabled{/if} customer_id="{$_o.customer_id}" 
									onClick="$Core.crm.reassign_given(this, event)" title="Phân lại cho người khác"><i class="bx bx-transfer-alt"></i></button>
								<button type="button" class="crm-ma-act dz"{if !$_o.can_act} disabled{/if} customer_id="{$_o.customer_id}" 
									onClick="$Core.crm.take_back_given(this, event)" title="Thu hồi về mình"><i class="bx bx-undo"></i></button>
							</div>
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
		<div class="card-footer d-flex justify-content-end py-2">
			<small class="text-muted">Hiển thị {$ma_rows|@count} / {$ma_total} phiếu</small>
		</div>
		{else}
		<div class="card-body">
			<div class="border text-center rounded-2 p-4 border-dashed text-muted">
				<i class="bx bx-package fs-3 d-block mb-2"></i>
				{if $ma_status eq 'pending'}Không có phiếu nào đang chờ nhận.{elseif $ma_status eq 'overdue'}Không có phiếu nào quá hạn.{elseif $ma_status eq 'confirmed'}Chưa có phiếu nào được nhận.{else}Bạn chưa giao khách cho ai.{/if}
			</div>
		</div>
		{/if}
	</div>
</div>
