<div class="briefs mb-3 gap-2 d-flex flex-wrap">
	<div class="brief-item a1a bg-orange clickable">
		<p class="fs-16 mb-4">Tổng GD</p>
		<h3 class="fs-32 mb-0 text-white">16</h3>
	</div>
	<div class="brief-item a3a brief-item-clickable bg-cyan">
		<p class="fs-16 mb-4">Đã ký HĐMB</p>
		<h3 class="fs-32 mb-0 text-white">2</h3>
	</div>
	<div class="brief-item brief-item-clickable bg-green">
		<p class="fs-16 mb-4">Có lịch ký HĐMB </p>
		<h3 class="fs-32 mb-0 text-white">0</h3>
	</div>
	<div class="brief-item brief-item-clickable bg-purple">
		<p class="fs-16 mb-4">Chưa có lịch ký</p>
		<h3 class="fs-32 mb-0 text-white">14</h3>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-lg-4 mb-3">
		<div class="card">
			<div class="d-flex align-items-center row">
				<div class="col-8">
					<div class="card-body">
						<h6 class="card-title mb-1 text-nowrap">Xin chào <span class="text-main fw-bold">GĐKD {$clsProfile->getFullName($profile_id, $oneProfile)}</span></h6>
						<small class="d-block mb-3 text-nowrap">Doanh số bán hàng <span class="text-main">năm {$current_year}</span></small>
						<h5 class="card-title fs-2 text-main mb-1">{$clsISO->shortNumber($TOTAL_PERSON_SALES)}</h5>
						<small class="d-block mb-3 pb-1 text-muted"><strong class="text-main">{$clsHelper->getHtmlGrowth($TOTAL_PERSON_SALES, $TOTAL_PREV_PERSON_SALES)}</strong> so với năm {$prev_year}</small>
						<a href="{$clsISO->getLink('billing')}?staff_id={$profile_id}&start_date={$start_date}&to_date={$to_date}" class="btn btn-sm btn-outline-primary">Xem giao dịch cá nhân</a>
					</div>
				</div>
				<div class="col-4 pt-3 ps-0">
					<div style="background-image:url({$clsProfile->getAvatar($profile_id, $oneProfile)})" class="avatar rounded-pill avatar-bg w-px-100 h-px-100"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-4 mb-3">
		<div class="card">
			<div class="d-flex align-items-end row">
				<div class="col-8">
					<div class="card-body">
						<h6 class="card-title mb-1 text-nowrap">Doanh số phòng <span class="text-main fw-bold">{$department_name}</span></h6>
						<small class="d-block mb-3 text-nowrap">Doanh số bán hàng <span class="text-main">năm {$current_year}</span></small>
						<h5 class="card-title fs-2 text-primary mb-1">{$clsISO->shortNumber($TOTAL_SALES)}</h5>
						<small class="d-block mb-3 pb-1 text-muted">
							<strong class="text-main">{$clsHelper->getHtmlGrowth($TOTAL_SALES, $TOTAL_PREV_SALES)}</strong> so với năm {$prev_year}
						</small>
						<a href="{$clsISO->getLink('billing')}?start_date={$start_date}&to_date={$to_date}" class="btn btn-sm btn-outline-primary">Xem giao dịch {$department_name}</a>
					</div>
				</div>
				<div class="col-4 pt-3 ps-0">
					<img src="{$URL_IMAGES}/prize-light.png" width="90" height="140" class="rounded-start" alt="View Sales">
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 mb-3">
		{$core->getBlock('home_course')}
		<div class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_person_campaign" data-options="{ldelim}{rdelim}">
			<div class="animate-bg w-100 h-px-40 mb-1 rounded-2"></div>
			<div class="animate-bg h-px-40 w-100 mb-3 rounded-2"></div>
			<div class="animate-bg w-100 h-px-40 mb-1 rounded-2"></div>
			<div class="animate-bg w-100 h-px-40 rounded-2"></div>
		</div>
	</div>
</div>
<div class="card mb-3">
	<div class="card-header d-flex mb-0 justify-content-between align-items-center">
		<h5 class="card-title mb-0">Hoạt động tiếp khách</h5>
		<a href="{$PCMS_URL}/net-dep-lao-dong.html" class="btn btn-link">Xem tất cả</a>
	</div>
	<div class="card-body ajax" data-bind="{$uid}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_top_shares&holderG=_sale_director" data-options="{ldelim}{rdelim}"></div>
</div>
<div class="row">
	<div class="col-lg-8 mb-4">
		<div class="card mb-4">
			<div class="row row-bordered g-0">
				<div class="col-md-8">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
						<div class="p-left ox:sm-1">
							<h5 class="card-title mb-0">Biểu đồ tăng trưởng {$department_name}</h5>
							<small class="card-subtitle">Biểu đồ tăng trưởng số lượng giao dịch</small>
						</div>
						<div class="p-right ox:w-100">
							<div class="input-group w-px-200 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
								<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
									<option value="">Tháng</option>			
									{foreach from=$list_months item = _month}
									<option value="{$_month}">Tháng {$_month}</option>
									{/foreach}
								</select>
								<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing_chart" data-options='{ldelim}{rdelim}'>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
						<div class="p-left ox:sm-1">
							<h5 class="card-title mb-0">Thống kê</h5>
							<small class="card-subtitle">Theo loại giao dịch</small>
						</div>
						<div class="p-right ox:w-100">
							<div class="input-group w-px-200 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
								<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
									<option value="">Tháng</option>			
									{foreach from=$list_months item = _month}
									<option value="{$_month}">Tháng {$_month}</option>
									{/foreach}
								</select>
								<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="report-list row ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing_type" data-options="{ldelim}{rdelim}">
							{section name=i loop=$list_preloader max=10}
							<div class="report-list-item col-6 mb-3">
								<div class="d-flex align-items-start">
									<div class="report-list-icon shadow-sm p-2 rounded-2 me-2">
										<div class="animate-bg rounded-2 w-px-20 h-px-20"></div>
									</div>
									<div class="w-100 d-flex flex-column flex-wrap">
										<span class="text-nowrap mb-2">
											<div class="animate-bg rounded-2 w-px-50"></div>
										</span>
										<div class="d-flex gap-1 justify-content-between align-items-center">
											<h5 class="mb-0 fs-6">
												<div class="animate-bg rounded-2 w-px-50"></div>
											</h5>
											<div class="fs-6 text-muted">
												<div class="animate-bg rounded-2 w-px-50"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							{/section}
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--/ Total Income -->
		<div class="row">
			<div class="col-md-6 col-lg-6">
				<div class="card h-100">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
						<div class="card-title ox:sm-1">
							<h5 class="mb-1 me-2">Vắng mặt {$department_name}</h5>
							<small class="d-block text-nowrap">Nhấn 
								<a href="javascript:void(0)" onclick="$Core.worktime.open(this, event)"><u>Thêm</u></a> bắt đầu thêm báo cáo
							</small>
						</div>
						<div class="p-right ox:w-100">
							<div class="input-group d-flex ox:w-100" role="group" aria-label="Sắp xếp">
								<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
									<option value="">Tháng</option>			
									{foreach from=$list_months item = _month}
									<option value="{$_month}">Tháng {$_month}</option>
									{/foreach}
								</select>
								<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
							</div>
						</div>
					</div>
					<div class="card-body">
						<table class="table" width="100%" cellpadding="0" cellspacing="0">
							<thead><tr>
								<th class="align-center">Nhân viên</th>
								<th width="68px" class="align-center text-center">Tổng</th>
								<td width="30px" class="align-center"></td>
							</tr></thead>
							<tbody class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_worktime" data-options="{ldelim}{rdelim}">
								{section name=i loop=$list_preloader}
								<tr>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="card">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card-header d-flex align-items-center justify-content-between">
						<div>
							<h5 class="card-title m-1 me-2">Nhân viên {$department_name}</h5>
							<small class="d-block mb-1 text-nowrap">Tổng số nhân viên {$department_name}: 
								(<strong class="text-main total_staffs">0</strong>)
							</small>
							<small class="text-warning mb-0">
								GĐ Kinh Doanh: {$clsProfile->getFullName($oProfileGDKD.profile_id, $oProfileGDKD)}
							</small>
						</div>
						<div class="p-right">
							<div class="input-group d-flex w-px-200" role="group" aria-label="Sắp xếp">
								<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
									<option value="">Tháng</option>			
									{foreach from=$list_months item = _month}
									<option value="{$_month}"{if $_month eq $smarty.now|date_format:"%m"} selected{/if}>Tháng {$_month}</option>
									{/foreach}
								</select>
								<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div id="dashboard_staffs" class="overflow-hidden" style="max-height:350px; min-height:280px">
							<table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
								<thead><tr>
									<th class="align-center">Nhân viên</th>
									<th class="align-center">Doanh số</th>
									<th class="align-center text-center">GD</th>
									<th class="align-center text-center">TK</th>
								</tr></thead>
								<tbody class="table-border-bottom-0 ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_staffs" data-options="{ldelim}{rdelim}">
									{section name=i loop=$list_preloader}
									<tr>
										<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
										<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
										<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
										<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									</tr>
									{/section}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--/ Conversion rate -->
	<div class="col-md-12 col-lg-4">
		{assign var = target_transactions value = $clsConfiguration->getValue('total_transactions')}
		<h3 class="text-main mb-2 text-center alert bg-label-warning font-bold">
			<a class="text-black fs-5 font-bold" href="/campaign/NC1WaWV0SVNP.html">Chiến dịch {$smarty.now|date_format:"%Y"}: <span class="text-main">{$total_transactions}</span>/{$clsISO->formatNumber2($target_transactions)} giao dịch</a>
		</h3>
		<div class="card ranking mb-3">
			<div class="card-body">
				{$core->getBlock('top_ranking')}
			</div>
		</div>
	</div>
</div>
<div class="row mb-4">
	<div class="col-md-6 col-lg-4 ox:sm-4">
		<div class="card h-100">
			<div class="card-header">
				<h5 class="card-title m-1 me-2">Báo cáo tra cứu</h5>
			</div>
			<div class="card-body">
				<div id="report_search_logs" class="overflow-hidden">
					<table class="table w-100">
						<thead><tr>
							<th>Họ tên</th>
							<th>Tu.này</th>
							<th>Tu.trước</th>
							<th>Th.này</th>
							<th>Th.trước</th>
						</tr></thead>
						<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_search" data-options='{ldelim}"department_id":{$oneProfile.department_id}{rdelim}'>
							{section name=i loop=$list_preloader max = 9}
								<tr>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								</tr>
								{/section}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-4 ox:sm-4">
		<div class="card h-100">
			<div class="card-header">
				<h5 class="card-title m-1 me-2">Báo cáo tiếp khách</h5>
			</div>
			<div class="card-body">
				<div id="report_share" class="overflow-hidden">
					<table class="table w-100">
						<thead><tr>
							<th>Họ tên</th>
							<th>Tu.này</th>
							<th>Tu.trước</th>
							<th>Th.này</th>
							<th>Th.trước</th>
						</tr></thead>
						<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_share" data-options='{ldelim}"department_id":{$oneProfile.department_id}{rdelim}'>
							{section name=i loop=$list_preloader max = 9}
								<tr>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								</tr>
								{/section}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-4 mb-md-0">
		<div class="card h-100">
			<div class="card-header">
				<h5 class="d-flex align-items-center mb-0">
					<img class="mr-2" src="{$URL_IMAGES}/birthday.png" width="20" /> 
					<span>Chúc mừng sinh nhật</span>
				</h5>
			</div>
			<div class="card-body">
				<div id="birthday_staffs" class="overflow-hidden" style="max-height:425px">
					<div class="ajax" data-bind="{$toId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=staff_birthday" data-options='{ldelim}"department_id":{$oneProfile.department_id}{rdelim}'>
						<div class="loader text-center py-3">
							<img src="{$URL_IMAGES}/loading.gif" width="66px" />
							<p>Loading...</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6 col-lg-8 mb-md-0">
		<div class="card ox:sm-3">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-0 me-2"><i class="bx bx-terminal"></i> Giao dịch mới nhất</h5>
				<a href="{$clsISO->getLink('billing')}" class="btn btn-sm btn-outline-default">Xem giao dịch</a>
			</div>
			<div class="card-body">
				{if $deviceType eq 'phone'}
				<div class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing" data-option="{ldelim}{rdelim}">
					Loading...
				</div>
				{else}
				<div class="table-responsive text-nowrap">
					<table class="table text-nowrap">
						<thead><tr>
							<th class="align-center">Mã GD</th>
							<th class="align-center">Ngày cọc</th>
							<th class="align-center">Sale bán</th>
							<th class="align-center">Dự án</th>
							<th class="align-center">Mã căn</th>
							<th class="align-center">Loại hình</th>
							<th class="align-center text-right">Tổng tiền</th>
						</tr></thead>
						<tbody class="table-border-bottom-0 ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing" data-option="{ldelim}{rdelim}">
							{section name=i loop=$list_preloader}
							<tr>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							</tr>
							{/section}
						</tbody>
					</table>
				</div>
				{/if}
			</div>
		</div>
	</div>
	<!-- Total Balance -->
	<div class="col-md-6 col-lg-4">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="card-title m-0 me-2"><i class='bx bx-history'></i> Lịch sử tra cứu</h5>
				<a href="{$clsISO->getLink('log-sale')}" class="btn btn-sm btn-outline-default">Xem lịch sử</a>
			</div>
			<div class="card-body">
				<div class="table-responsive text-nowrap">
					<table class="table text-nowrap">
						<thead><tr>
							<th class="align-center">Nhân viên</th>
							<th class="align-center">Thời gian</th>
							<th class="align-center">Mã căn</th>
						</tr></thead>
						<tbody class="table-border-bottom-0 ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_sale_logs" data-option="{ldelim}{rdelim}">
							{section name=i loop=$list_preloader}
							<tr>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							</tr>
							{/section}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!--/ Total Balance -->
</div>
{literal}
<style type="text/css">
	.avatar-bg{ background-size: cover;}
</style>
{/literal}