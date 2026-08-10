<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>

<div class="container-xxl flex-grow-1 pt-2 container-p-y">

	{* ===== Header ===== *}
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
		<div>
			<h4 class="fw-bold mb-1">Tổng quan Check-in</h4>
			<p class="text-muted mb-0">
				<span class="txt_time">Hôm nay {$smarty.now|date_format:"%d/%m/%Y"}</span>
			</p>
		</div>
		<div class="d-flex gap-2 align-items-center flex-wrap">
			{* Desktop: bộ lọc inline *}
			<div class="d-none d-lg-flex gap-2 align-items-center flex-wrap" id="filter_inline">
				<div class="input-group" style="width:240px">
					<span class="input-group-text"><i class="bx bx-calendar"></i></span>
					<input type="text" class="form-control" id="checkin_daterange" name="date_range"
						placeholder="Khoảng thời gian" autocomplete="off" readonly
						value="{$smarty.now|date_format:'%d/%m/%Y'} - {$smarty.now|date_format:'%d/%m/%Y'}">
				</div>
				{if $filter_mode == 'director'}
				<select class="form-select w-px-150" id="checkin_region_filter" style="min-width:140px">
					<option value="0">-- Tất cả --</option>
					{foreach from=$list_regions item=_r}
					<option value="{$_r.id}">{$_r.title}</option>
					{/foreach}
				</select>
				<select class="form-select w-px-150" id="checkin_dept_filter" style="min-width:150px">
					<option value="0">-- Tất cả khối --</option>
				</select>
				{elseif $filter_mode == 'region'}
				<select class="form-select w-px-150" id="checkin_dept_filter" style="min-width:150px">
					<option value="0">-- Tất cả phòng --</option>
					{foreach from=$list_region_depts item=_dep}
					<option value="{$_dep.id}">{$_dep.title}</option>
					{/foreach}
				</select>
				{/if}
				{if $is_director}
				<select class="form-select w-px-150" id="checkin_group_filter" style="min-width:140px">
					<option value="0">-- Tất cả nhóm --</option>
					{foreach from=$list_groups item=_grp}
					<option value="{$_grp.group_profile_id}">{$_grp.title}</option>
					{/foreach}
				</select>
				{/if}
			</div>
			{* Mobile: nút Bộ lọc *}
			<button class="btn btn-outline-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#checkin_filter_canvas">
				<i class="bx bx-filter-alt me-1"></i>Bộ lọc
			</button>
		</div>
	</div>

	{* ===== Offcanvas bộ lọc cho mobile ===== *}
	<div class="offcanvas offcanvas-start" tabindex="-1" id="checkin_filter_canvas">
		<div class="offcanvas-header">
			<h5 class="offcanvas-title">Bộ lọc</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
		</div>
		<div class="offcanvas-body d-flex flex-column gap-3">
			<div>
				<label class="form-label fw-semibold">Khoảng thời gian</label>
				<div class="input-group">
					<span class="input-group-text"><i class="bx bx-calendar"></i></span>
					<input type="text" class="form-control" id="checkin_daterange_mobile" name="date_range"
						placeholder="Khoảng thời gian" autocomplete="off" readonly
						value="{$smarty.now|date_format:'%d/%m/%Y'} - {$smarty.now|date_format:'%d/%m/%Y'}">
				</div>
			</div>
			{if $filter_mode == 'director'}
			<div>
				<label class="form-label fw-semibold">Vùng</label>
				<select class="form-select" id="checkin_region_filter_mobile">
					<option value="0">-- Tất cả vùng --</option>
					{foreach from=$list_regions item=_r}
					<option value="{$_r.id}">{$_r.title}</option>
					{/foreach}
				</select>
			</div>
			<div>
				<label class="form-label fw-semibold">Phòng</label>
				<select class="form-select" id="checkin_dept_filter_mobile">
					<option value="0">-- Tất cả phòng --</option>
				</select>
			</div>
			{elseif $filter_mode == 'region'}
			<div>
				<label class="form-label fw-semibold">Phòng</label>
				<select class="form-select" id="checkin_dept_filter_mobile">
					<option value="0">-- Tất cả phòng --</option>
					{foreach from=$list_region_depts item=_dep}
					<option value="{$_dep.id}">{$_dep.title}</option>
					{/foreach}
				</select>
			</div>
			{/if}
			{if $is_director}
			<div>
				<label class="form-label fw-semibold">Nhóm nhân viên</label>
				<select class="form-select" name="group_id" id="checkin_group_filter_mobile">
					<option value="0">-- Tất cả nhóm --</option>
					{foreach from=$list_groups item=_grp}
					<option value="{$_grp.group_profile_id}">{$_grp.title}</option>
					{/foreach}
				</select>
			</div>
			{/if}
			<button class="btn btn-primary" onclick="$Core.report_checkin.load_all({}); $('.offcanvas').offcanvas('hide');">
				<i class="bx bx-search me-1"></i>Áp dụng
			</button>
		</div>
	</div>

	<div class="clearfix"></div>

	{* ===== KPI Cards (5 ô) ===== *}
	<div id="holder_checkin_overview">
		<div class="form-row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-6 g-3 mb-3">
			<div class="col mb-2 flex-fill">
				<div class="card text-center py-3 px-2 h-100">
					<div class="card-body p-0">
						<i class="bx bx-user fs-2 text-main mb-1"></i>
						<p class="text-muted fs-12 mb-1">Tổng nhân sự</p>
						<h4 class="fw-bold text-main mb-0">--</h4>
					</div>
				</div>
			</div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-log-in fs-2 text-primary mb-1"></i><p class="text-muted fs-12 mb-1">Tổng lượt check-in</p><h4 class="fw-bold text-primary mb-0">--</h4></div></div></div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-user-check fs-2 text-success mb-1"></i><p class="text-muted fs-12 mb-1">Đã check-in</p><h4 class="fw-bold text-success mb-0">--</h4></div></div></div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-time-five fs-2 text-warning mb-1"></i><p class="text-muted fs-12 mb-1">Chưa check-in</p><h4 class="fw-bold text-warning mb-0">--</h4></div></div></div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-pie-chart-alt-2 fs-2 text-info mb-1"></i><p class="text-muted fs-12 mb-1">Tỉ lệ</p><h4 class="fw-bold text-info mb-0">--%</h4></div></div></div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-buildings fs-2 text-secondary mb-1"></i><p class="text-muted fs-12 mb-1">VP hoạt động</p><h4 class="fw-bold text-secondary mb-0">--</h4></div></div></div>
		</div>
		{* Donut + Top: JS chuyển xuống #holder_checkin_chart (dưới bảng Vùng) sau khi nạp overview *}
	</div>

	{* ===== Theo dõi Check-in theo Vùng/đơn vị ===== *}
	<div id="holder_checkin_region" class="mb-3">
		<div class="card">			
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<h5 class="mb-0"><i class="bx bx-map-alt text-primary me-1"></i>Theo dõi Check-in theo Vùng</h5>
			</div>
			<div class="card-body">
				<div class="text-center text-muted py-4">Đang tải...</div>
			</div>
		</div>
	</div>

	{* ===== Hoạt động theo địa điểm + Top (JS chuyển từ overview xuống đây, dưới bảng Vùng) ===== *}
	<div id="holder_checkin_chart" class="mb-3">
		<div class="card">
			<div class="card-body"><div class="text-center text-muted py-4">Đang tải...</div></div>
		</div>
	</div>

	{* ===== Danh sách check-in mới nhất ===== *}
	<div id="holder_checkin_list">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<h5 class="mb-0"><i class="bx bx-map-pin text-primary me-1"></i>Check-in mới nhất</h5>
				<span class="badge bg-label-primary">13 lượt</span>
			</div>
			<div class="card-body">
				<div class="text-center text-muted py-4">Đang tải...</div>
			</div>
		</div>
	</div>

</div>

{* Map vùng→phòng cho cascading (DIRECTOR) — JS đọc *}
<script type="application/json" id="checkin_region_map">{$region_dept_map_json}</script>

{* JS của page nằm trong $Core.report_checkin (application/views/report/js/jquery.report.js) — auto-init theo #holder_checkin_overview *}
