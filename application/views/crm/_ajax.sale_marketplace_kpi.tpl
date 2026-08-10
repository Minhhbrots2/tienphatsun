{* AJAX partial — KPI strip Sale thị trường (nạp riêng vào #box_mkt_kpi, trên bộ lọc). Render bởi default_load_sale_marketplace(). *}
<div class="row g-3 mb-3">
	<div class="col-6 col-md-3 col-xl-3">
		<div class="card h-100 crm-kpi-card">
			<div class="card-body d-flex justify-content-between align-items-start">
				<div class="min-w-0">
					<span class="crm-kpi-label">Tổng kho MOC / MF</span>
					<h3 class="mb-0 mt-1 fw-bold">{$total_all}</h3>
					<small class="text-muted">tài khoản marketplace</small>
				</div>
				<div class="avatar flex-shrink-0"><span class="avatar-initial rounded bg-label-primary"><i class="bx bx-group fs-4"></i></span></div>
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-xl-3">
		<div class="card h-100 crm-kpi-card">
			<div class="card-body d-flex justify-content-between align-items-start">
				<div class="min-w-0">
					<span class="crm-kpi-label">Khớp bộ lọc</span>
					<h3 class="mb-0 mt-1 fw-bold">{$total}</h3>
					<small class="text-muted">kết quả sau khi lọc</small>
				</div>
				<div class="avatar flex-shrink-0"><span class="avatar-initial rounded bg-label-info"><i class="bx bx-filter-alt fs-4"></i></span></div>
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-xl-3">
		<div class="card h-100 crm-kpi-card">
			<div class="card-body d-flex justify-content-between align-items-start">
				<div class="min-w-0">
					<span class="crm-kpi-label">Có thể chuyển</span>
					<h3 class="mb-0 mt-1 fw-bold {if $cnt_pick > 0}text-primary{/if}">{$cnt_pick}</h3>
					<small class="text-muted">trên trang này</small>
				</div>
				<div class="avatar flex-shrink-0"><span class="avatar-initial rounded bg-label-success"><i class="bx bx-user-check fs-4"></i></span></div>
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-xl-3">
		<div class="card h-100 crm-kpi-card">
			<div class="card-body d-flex justify-content-between align-items-start">
				<div class="min-w-0">
					<span class="crm-kpi-label">Đã xử lý</span>
					<h3 class="mb-0 mt-1 fw-bold">{$cnt_done}</h3>
					<small class="text-muted">{$cnt_conv} đã chuyển · {$cnt_pdup} trùng SĐT</small>
				</div>
				<div class="avatar flex-shrink-0"><span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-check-shield fs-4"></i></span></div>
			</div>
		</div>
	</div>
</div>
