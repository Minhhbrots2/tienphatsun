{* Partial: KPI cards + donut + Top hoạt động. Rendered server-side, echoed as html. *}

{* ===== 5 KPI Cards ===== *}
<div class="form-row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-6 g-3 mb-3">
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-user fs-2 text-main mb-1"></i>
				<p class="text-muted fs-12 mb-1">Tổng nhân sự</p>
				<h4 class="fw-bold text-main mb-0">{$_kpi.total_profile|default:0}</h4>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-log-in fs-2 text-primary mb-1"></i>
				<p class="text-muted fs-12 mb-1">Tổng lượt check-in</p>
				<h4 class="fw-bold text-primary mb-0">{$_kpi.total|default:0}</h4>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-user-check fs-2 text-success mb-1"></i>
				<p class="text-muted fs-12 mb-1">Đã check-in</p>
				<h4 class="fw-bold text-success mb-0">{$_kpi.checked_in|default:0}</h4>
				<a class="cursor-pointer text-decoration-underline" onClick="$Core.report_checkin.load_list_profile_checkin(this,event)" data-type="has_checkin" >Xem chi tiết</a>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-time-five fs-2 text-warning mb-1"></i>
				<p class="text-muted fs-12 mb-1">
					Chưa check-in
					{if !empty($kpi_day_label)}
					<small class="d-block text-muted fs-11">({$kpi_day_label})</small>
					{/if}
				</p>
				<h4 class="fw-bold text-warning mb-0">{$_kpi.not_checked_in|default:0}</h4>
				<a class="cursor-pointer text-decoration-underline" onClick="$Core.report_checkin.load_list_profile_checkin(this,event)" data-type="not_checkin" >Xem chi tiết</a>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-pie-chart-alt-2 fs-2 text-info mb-1"></i>
				<p class="text-muted fs-12 mb-1">Tỉ lệ</p>
				<h4 class="fw-bold text-info mb-0">{$_kpi.rate|default:0}%</h4>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-buildings fs-2 text-secondary mb-1"></i>
				<p class="text-muted fs-12 mb-1">VP hoạt động</p>
				<h4 class="fw-bold text-secondary mb-0">{$_kpi.active_offices|default:0}</h4>
			</div>
		</div>
	</div>
</div>

{* ===== Donut + Top hoạt động — JS chuyển khối này sang #holder_checkin_chart (dưới bảng Vùng) ===== *}
<div id="checkin_chart_block">
<div class="form-row">
	<div class="col-12 col-md-6 col-lg-8 col-xxl-8 mb-3">
		<div class="card h-100" id="card_donut">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<h5 class="mb-0">Hoạt động theo địa điểm</h5>
				<div class="btn-group btn-group-sm">
					<button type="button" class="btn btn-outline-primary active px-1" id="donut_btn_office"
						onclick="$Core.report_checkin.set_donut_scope('office')">Văn phòng</button>
					<button type="button" class="btn btn-outline-primary px-1" id="donut_btn_dept"
						onclick="$Core.report_checkin.set_donut_scope('dept')">Phòng ban</button>
				</div>
			</div>
			<div class="card-body">
				{if $_kpi.total == 0}
				<div class="text-center text-muted py-4">Chưa có dữ liệu</div>
				{else}
				<div class="row align-items-center">
					<div class="col-12 col-md-12 col-lg-6">
						<div id="checkin_donut_chart" style="min-height:280px"></div>
					</div>
					<div class="col-12 col-md-12 col-lg-6" id="checkin_donut_legend"></div>
				</div>
				{/if}
			</div>
		</div>
	</div>
	<div class="col-12 col-md-6 col-lg-4 col-xxl-4 mb-3">
		<div class="card h-100">
			<div class="card-header"><h5 class="mb-0"><i class="bx bx-trophy text-warning me-1"></i>Top hoạt động</h5></div>
			<div class="card-body p-0">
				{if empty($list_top_active)}
				<div class="text-center text-muted py-4">Chưa có dữ liệu</div>
				{else}
				<ul class="list-group list-group-flush">
					{foreach from=$list_top_active item=_top key=_idx}
					<li class="list-group-item d-flex align-items-center gap-2 py-2 px-3" onclick="$Core.report_checkin.load_profile_journey(this, event)" data-profile="{$_top.profile_id}">
						<span class="fw-bold text-muted me-1" style="min-width:20px">{math equation="idx+1" idx=$_idx}</span>
						<a href="javascript:void(0);" class="d-flex align-items-center gap-2 text-decoration-none flex-grow-1 text-dark">
							<img class="rounded-pill" src="{$_top.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="36" height="36" alt="">
							<span class="fw-semibold text-truncate" style="max-width:150px" title="{$_top.full_name}">{$_top.full_name}</span>
						</a>
						<span class="badge bg-label-primary ms-auto">{$_top.checkin_count} lượt</span>
					</li>
					{/foreach}
				</ul>
				{/if}
			</div>
		</div>
	</div>
</div>
</div>

{* Donut data JSON cho JS (hidden data islands) *}
<script type="application/json" id="donut_data_office">{$donut_office_json}</script>
<script type="application/json" id="donut_data_dept">{$donut_dept_json}</script>

{* Donut render trong $Core.report_checkin (js/jquery.report.js): load_overview() gọi init_donut() sau khi nạp partial; nút toggle gọi set_donut_scope() *}
