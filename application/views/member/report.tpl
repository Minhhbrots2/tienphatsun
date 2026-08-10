<div class="container-xxl flex-grow-1 pt-3 container-p-y">
	<form method="POST" class="w-100">
		<div class="d-flex flex-wrap mb-3 align-items-center justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<a href="{$clsISO->getLink('member')}" class="back" title="Quay lại">
					<img src="{$smarty.const.ICON_BACK}" />
				</a>
				<div class="d-flex flex-column">
					<h5 class="text-upper mb-0 fw-bold">Báo cáo nhân sự</h5>
					<div class="text-muted">Báo cáo tổng hợp nhân sự</div>
				</div>
			</div>
			<div class="d-flex justify-content-end  gap-1 align-items-center">
				<select onChange="javasctipt:_reload(this, event)" data-field="year" class="form-control search_field form-select">
					{foreach name=i from=$list_years item = _oYear}
					<option{if $current_year eq $_oYear} selected{/if} value="{$_oYear}">Năm {$_oYear}</option>
					{/foreach}
				</select>
			</div>
		</div>
	</form>
	<div class="mb-2">
		<div class="card">
			<div class="card-header">
				<h5 class="card-title mb-0">Thống kê nhân sự</h5>
			</div>
			<div class="card-body">
				<div class="briefs mb-3 gap-2 gap-xxl-3 d-flex flex-wrap">
					<div class="brief-item a1a bg-orange">
						<p class="fs-16 mb-2">Tổng nhân viên</p>
						<h3 class="fs-32 mb-1 text-white">{$arr_summary.total_staff}</h3>
					</div>
					<div class="brief-item a2a bg-azure">
						<p class="fs-16 mb-2">Đang làm việc</p>
						<h3 class="fs-32 mb-0 text-white">{$arr_summary.total_on}</h3>
					</div>
					<div class="brief-item a3a bg-cyan">
						<p class="fs-16 mb-2">Đã nghỉ</p>
						<h3 class="fs-32 mb-0 text-white">{$arr_summary.total_off}</h3>
					</div>
					<div class="brief-item a4a bg-danger">
						<p class="fs-16 mb-2">Khối kinh doanh</p>
						<h3 class="fs-32 mb-0 text-white">{$arr_summary.total_sale}</h3>
					</div>
					<div class="brief-item a5a bg-purple">
						<p class="fs-16 mb-2">Khối văn phòng</p>
						<h3 class="fs-32 mb-0 text-white">{$arr_summary.total_bo}</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title">Biến động nhân sự</h5>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=staff_changes" data-options="{ldelim}{rdelim}">
					<div class="chartContainer d-flex align-items-center justify-content-center w-100 h-px-300">
						<span class="text-muted">Đang tải dữ liệu...</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title">Số lượng nhân sự cuối tháng {$current_year}</h5>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=month_end_staff_count" data-options="{ldelim}{rdelim}">
					<div class="chartContainer d-flex align-items-center justify-content-center w-100 h-px-300">
						<span class="text-muted">Đang tải dữ liệu...</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title">Số lượng nhân sự hàng năm</h5>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=staff_count_chart" data-options="{ldelim}{rdelim}">
					<div class="chartContainer d-flex align-items-center justify-content-center w-100 h-px-300">
						<span class="text-muted">Đang tải dữ liệu...</span>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<div class="form-row">
		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title">Nhân sự theo phòng ban {$current_year}</h5>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=staff_dep_pie_chart" data-options="{ldelim}{rdelim}">
					<div class="chartContainer d-flex align-items-center justify-content-center w-100 h-px-300">
						<span class="text-muted">Đang tải dữ liệu...</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-8">
			<div class="card h-100">
				<div class="card-header">
					<h5 class="card-title">Nhân sự theo phòng ban {$current_year}</h5>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=staff_dep_line_chart" data-options="{ldelim}{rdelim}">
					<div class="chartContainer d-flex align-items-center justify-content-center w-100 h-px-300">
						<span class="text-muted">Đang tải dữ liệu...</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>