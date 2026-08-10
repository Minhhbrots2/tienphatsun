<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
		<div class="p__left xs:w-100 mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1"><span>Báo cáo nhóm/CLB NS</span></h4>
			<p class="text-muted mb-0">Báo cáo kết quả theo nhóm/CLB</p>
		</div>
		<div class="search xs:w-100 d-flex{if $deviceType eq 'phone'} flex-wrap{/if} gap-1 align-items-center">
			{$core->getBlock('group_search', ['x' => 'y'])}
		</div>
	</div>	
	{assign var = gId value = $clsISO->getUniqid()}
	<div class="card no-shadow mb-2">
		<div class="card-body">
			<div class="briefs gap-2 gap-xxl-2 d-flex flex-wrap ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=report_group_total" data-options='{ldelim}{rdelim}'>
				{foreach from=$list_blocks item = _oI key = _OK}
				<div class="brief-item {$_oI.class}">
					<p class="fs-16 mb-2">{$_oI.title}</p>
					<h3 class="fs-24 mb-1 text-white">0.00</h3>
					<small class="text-white">Đang tải.......</small>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
	<div class="row mb-2">
		<div class="col-12 col-xxl-12">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card">
				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="cart-title mb-0">Biểu đồ tăng trưởng doanh số</h3>
					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="card-body">
					<div class="ajax pb-2 w-100" uid="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_billing_chart" data-options="{ldelim}{rdelim}">
						<div class="chartContainer d-flex align-items-center justify-content-center w-100 h-px-300">
							<span class="text-muted">Đang tải dữ liệu...</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-12 col-md-4 mb-2 mb-lg-0">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card h-100 no-shadow">
				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="cart-title mb-0">Doanh số theo năm</h3>
					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="card-body">
					<div class="ajax pb-2 w-100" uid="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_group_month_chart" data-options="{ldelim}{rdelim}">
						<div class="chartContainer d-flex align-items-center justify-content-center w-100 h-px-300">
							<span class="text-muted">Đang tải dữ liệu...</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-8 mb-2 mb-lg-0">
			<div class="card h-100 no-shadow">
				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
					<h5 class="card-title mb-0">Giao dịch mới nhất</h5>
					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="card-body">
					<div class="table-container no-shadow text-nowrap overflow-x-auto">
						<table class="table table-bordered dragable " width="100%" cellpadding="0" cellspacing="0">
							<thead><tr>
								<th class="align-center bg-lighter h-px-35">Mã giao dịch</th>
								<th class="align-center bg-lighter h-px-35">Ngày cọc</th>
								<th class="align-center bg-lighter h-px-35">Sales bán</th>
								<th class="align-center bg-lighter h-px-35">Dự án</th>
								<th class="align-center bg-lighter h-px-35">Mã căn</th>
								<th class="align-center bg-lighter h-px-35">Loại hình</th>
								<th class="align-center bg-lighter h-px-35">Doanh số</th>
							</tr></thead>
							<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod=home&sub=dashboard&act=load_billing" data-options='{ldelim}"call_from":"report_group", "group_id":"{$smarty.const._PROFILE_DEFAULT_GROUP_ID}"{rdelim}'>
								{section name=i loop=$list_preloaders max=12}
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card mb-2">
		{assign var = gId value = $clsISO->getUniqid()}
		<div class="card-body">
			<div class="d-flex align-items-center mb-3 justify-content-between">
				<div class="p-left">
					<h5 class="card-title mb-0 text-nowrap">Doanh số cá nhân</h5>
					<small class="d-block text-nowrap">Doanh số bán hàng</small>
				</div>
				<div class="p-right">
					{if $deviceType ne 'phone'}
					<div class="input-group w-px-200 d-flex align-items-center" role="group">
						<select class="form-control search_sales_group_field form-control-sm form-select" 
							data-field="billing_source_id" gId="{$gId}" onChange="$Core.report.do_group_search(this,event)"> 
							<option value="0">Loại quỹ</option>
							{$clsProperty->getSelectByProperty('BILLING_SOURCE',0)}
						</select>
						<select class="form-control search_sales_group_field form-control-sm form-select" 
							data-field="billing_type_id" gId="{$gId}" onChange="$Core.report.do_group_search(this,event)"> 
							<option value="0">Loại hình</option>
							{$clsProperty->getSelectByProperty('_BILLING_TYPE',0)}
						</select>
					</div>
					{else}
					<div class="dropdown">
						<button class="btn btn-icon p-0 h-auto" type="button" data-bs-toggle="dropdown">
							<i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
							<div class="p-3">
								<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
									<select class="form-control form-control-sm form-select" data-field="billing_source_id" 
										gId="{$gId}" onChange="$Core.report.do_group_search(this,event)" > 
										<option value="0">Loại quỹ</option>
										{$clsProperty->getSelectByProperty('BILLING_SOURCE',0)}
									</select>
								</div>
								<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
									<select class="form-control form-control-sm form-select" data-field="billing_type_id" 
										gId="{$gId}" onChange="$Core.report.do_group_search(this,event)" > 
										<option value="0">Loại hình</option>
										{$clsProperty->getSelectByProperty('_BILLING_TYPE',0)}
									</select>
								</div>
							</div>
						</div>
					</div>
					{/if}
				</div>
			</div>
			<div class="table-container no-shadow text-nowrap overflow-auto">
				<table class="table table-bordered dragable " width="100%" cellpadding="0" cellspacing="0">
					<thead class="position-sticky top-0 zindex-3"><tr>
						{if $deviceType eq 'computer'}
						<th class="align-center bg-lighter h-px-35 w-px-30">STT</th>
						{/if}
						<th class="align-center bg-lighter h-px-35">Họ và tên</th>
						<th class="align-center bg-lighter h-px-35">MXH</th>
						<th class="align-center bg-lighter h-px-35">Phòng ban</th>
						<th data-field="total_share" onClick="$Core.report.do_sort(this, event)" 
							class="align-center sortable text-center bg-lighter h-px-35">Tiếp khách</th>
						<th data-field="total_search" onClick="$Core.report.do_sort(this, event)" 
							class="align-center sortable text-center bg-lighter h-px-35">Check căn</th>
						<th data-field="total_billings" onClick="$Core.report.do_sort(this, event)" 
							class="align-center sortable text-center bg-lighter h-px-35">Giao dịch</th>
						<th data-field="total_sales" onClick="$Core.report.do_sort(this, event)" 
							class="align-center sortable desc text-right bg-lighter h-px-35">Doanh số</th>
						<th data-field="total_post" onClick="$Core.report.do_sort(this, event)" 
							class="align-center sortable text-center bg-lighter h-px-35">Lan toả</th>
						<th data-field="total_customer" onClick="$Core.report.do_sort(this, event)" 
							class="align-center sortable text-center bg-lighter h-px-35">Khách hàng</th>
						<th data-field="total_work_unit" onClick="$Core.report.do_sort(this, event)" 
							class="align-center sortable text-center bg-lighter h-px-35">Tổng công</th>
					</tr></thead>
					<tbody class="ajax search_KPI report_sales_group" data-url="{$PCMS_URL}/index.php?mod=report&act=load_report_sales_group" data-options='{ldelim}"group_id":"{$smarty.const._PROFILE_DEFAULT_GROUP_ID}"{rdelim}' gId="{$gId}">
						{section name=i loop=$list_preloaders max=12}
						<tr>
							{if $deviceType eq 'computer'}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							{/if}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="card">
		{assign var = gId value = $clsISO->getUniqid()}
		<div class="card-body">
			<div class="d-flex align-items-center mb-3 justify-content-between">
				<div class="p-left">
					<h5 class="card-title mb-0 text-nowrap">Thống kê CRM</h5>
					<small class="d-block text-nowrap">Tình trạng chăm khách</small>
				</div>
				<div class="p-right">
					{if $deviceType eq 'phone'}
					<div class="input-group w-px-200">
						<input type="date" onchange="$Core.report.do_group_search(this,event)" class="form-control js__search-start_date-field js__search-date-field search_sales_group_field w-px-100 form-control-sm" data-field="start_time" value="{$start_date}">
						<input type="date" onchange="$Core.report.do_group_search(this,event)" class="form-control js__search-end_date-field js__search-date-field search_sales_group_field w-px-100 form-control-sm" data-field="end_time" value="{$end_date}">
					</div>
					{else}
					<div class="input-group w-px-250">
						<input type="date" onchange="$Core.report.do_group_search(this,event)" class="form-control js__search-start_date-field js__search-date-field search_sales_group_field w-px-125" data-field="start_time" value="{$start_date}">
						<input type="date" onchange="$Core.report.do_group_search(this,event)" class="form-control js__search-end_date-field js__search-date-field search_sales_group_field w-px-125" data-field="end_time" value="{$end_date}">
					</div>
					{/if}
				</div>
			</div>
			<div class="table-container no-shadow text-nowrap overflow-auto">
				<table class="table table-bordered dragable ajax report_sales_group" width="100%" cellpadding="0" cellspacing="0" data-url="{$PCMS_URL}/index.php?mod=report&act=load_report_sales_group_CRM" data-options='{ldelim}"group_id":"{$smarty.const._PROFILE_DEFAULT_GROUP_ID}"{rdelim}' gId="{$gId}">
					<thead class="position-sticky top-0 zindex-3">
						<tr>
							{if $deviceType eq 'computer'}
							<th class="align-center bg-lighter h-px-35 w-px-30" rowspan="2">STT</th>
							{/if}
							<th class="align-center bg-lighter h-px-35" rowspan="2">Họ và tên</th>
							<th class="align-center bg-lighter h-px-35" rowspan="2">Khách hàng</th>
							<th class="align-center bg-lighter h-px-35" colspan="{$arr_status_customer_cached|@count}">Trạng thái</th>
						</tr>
						<tr>
							{foreach from=$arr_status_customer_cached item=_oStatus key=k_st name=n_st}
								<th class="align-center bg-lighter h-px-35">{$_oStatus.title}</th>
							{/foreach}
						</tr>
					</thead>
					<tbody>
						{section name=i loop=$list_preloaders max=12}
						<tr>
							{if $deviceType eq 'computer'}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							{/if}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>