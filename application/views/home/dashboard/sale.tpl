<link rel="stylesheet" type="text/css" href="{$URL_JS}/fullcalendar-1.6.0/fullcalendar/fullcalendar.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/fullcalendar-1.6.0/fullcalendar/fullcalendar.min.js?v={$upd_version}"></script>
<script type="text/javascript">
    var crm_date_format="dd/mm/yy",
		crm_datepicker_format = {
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat :crm_date_format,
			yearRange: "1900:2050"
		};
</script>
{assign var = uid value = $clsISO->getUniqid()}
{assign var = gId value = $clsISO->getUniqid()}
{assign var = pId value = $clsISO->getUniqid()}
<div class="container-xxl flex-grow-1 container-p-y pt-2 crm_page">
	<div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-2 {if $deviceType eq 'phone'}mobile_header_search{/if}">
		<h3 class="uganfTAavk d-flex flex-column gap-1 mb-2 mb-lg-0">
			<span class="fw-bold fw-light {if $deviceType eq 'phone'}fs-18{/if}">Tổng quan hiệu suất sale</span>
			<small class="text-muted text-fs-12">Báo cáo hiệu suất</small>
		</h3>
		<div class="p__right {if $deviceType eq 'phone'}flex-fill{/if}">
			<div class="input-group {if $deviceType eq 'phone'}w-100{else}w-px-350{/if} d-flex align-items-center" role="group">
				<select class="form-control form-select search_field" name="date_type" gId="{$gId}" 
					onChange="$Core.dashboard.reload(this,event)"> 
					<option{if $curr_date_type eq '_month'} selected{/if} value="_month">Tháng</option>
					<option{if $curr_date_type eq '_quarter'} selected{/if} value="_quarter">Quý</option>
					<option{if $curr_date_type eq '_half_year'} selected{/if} value="_half_year">Nửa năm</option>
				</select>
				<select class="form-control form-select search_field" name="month" gId="{$gId}" 
					onChange="$Core.dashboard.reload(this,event)"> 
					{if $curr_date_type eq '_quarter'}
						<option value="">Chọn Quý</option>			
						{foreach from=$list_quaters item = _quarter}
						<option{if $current_quater eq $_quarter} selected{/if} value="{$_quarter}">Qúy {$_quarter}</option>
						{/foreach}
					{else}
						<option value="">Chọn tháng</option>			
						{foreach from=$list_months item = _month}
						<option value="{$_month}">T{$_month}</option>
						{/foreach}
					{/if}
				</select>
				<select class="form-control form-select search_field" name="year" gId="{$gId}" 
					onChange="$Core.dashboard.reload(this,event)">
					{foreach from=$list_years item = _year}
					<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
					{/foreach}
				</select>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	{if $deviceType eq 'phone'}
	<div class="form-row">
		<div class="col-12 mb-2">
			<div class="card h-100 radius-5 border border-info border-left-width-1">
				<div class="card-body p-3">
					<p class="fs-16 text-black mb-0">Doanh số bán hàng</p>
					<span class="text-info fw-bold fs-30 total_sale">20.000.000.000đ</span>
					<hr class="my-2" style="height: 3px">
					<p class="mb-0 fs-16"><span class="text-success fw-bold fs-18 total_billing">20</span> căn đã bán</p>
				</div>
			</div>
		</div>
		<div class="col-6 mb-2">
			<div class="card h-100 radius-5 border border-primary border-left-width-1">
				<div class="card-body p-3"> 
					<p class="fs-16 mb-2 text-black">Khách phụ trách</p>
					<div class="d-flex align-items-center justify-content-between gap-2">
						<span class="text-primary fw-bold fs-30 total_customer">50</span>
						<svg class="text-primary" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users" aria-hidden="true" data-source-pos="47:63-47:82" data-source-name="Users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle></svg>					
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 mb-2">
			<div class="card h-100 radius-5 border border-success border-left-width-1">
				<div class="card-body p-3"> 
					<p class="fs-16 mb-2 text-black">Số khách đã tiếp</p>
					<div class="d-flex align-items-center justify-content-between gap-2">
						<span class="text-success fw-bold fs-30 total_share_customer">20</span>
						<svg class="text-success" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-check text-green-500" aria-hidden="true" data-source-pos="75:12-75:52" data-source-name="UserCheck"><path d="m16 11 2 2 4-4"></path><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>						
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 mb-2">
			<div class="card h-100 radius-5 border border-info border-left-width-1">
				<div class="card-body p-3"> 
					<p class="fs-16 mb-2 text-black">Lượt tiếp khách</p>
					<div class="d-flex align-items-center justify-content-between gap-2">
						<span class="text-info fw-bold fs-30 total_share">20</span>
						<svg class="text-info" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list text-blue-500" aria-hidden="true" data-source-pos="86:12-86:55" data-source-name="ClipboardList"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><path d="M12 11h4"></path><path d="M12 16h4"></path><path d="M8 11h.01"></path><path d="M8 16h.01"></path></svg>						
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 mb-2">
			<div class="card h-100 radius-5 border border-warning border-left-width-1">
				<div class="card-body p-3"> 
					<p class="fs-16 mb-2 text-black">Chờ duyệt</p>
					<div class="d-flex align-items-center justify-content-between gap-2">
						<span class="text-warning fw-bold fs-30 total_share_waiting">20</span>
						<svg class="text-warning" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock text-orange-500" aria-hidden="true" data-source-pos="97:12-97:49" data-source-name="Clock"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>					
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 mb-2">
			<div class="card h-100 radius-5 border border-danger border-left-width-1">
				<div class="card-body p-3"> 
					<p class="fs-18 mb-2 text-black">Tổng hoạt động</p>
					<div class="d-flex align-items-center justify-content-between gap-2">
						<span class="text-danger fw-bold fs-30 total_followups">20</span>
						<div class="btn btn-icon btn-lg rounded-3 alert alert-danger mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone" aria-hidden="true" data-source-pos="48:56-48:75" data-source-name="Phone"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg></div>						
					</div>
				</div>
			</div>
		</div>
	</div>
	{else}
	<div class="form-row row-cols-1 row-cols-md-2 row-cols-xxxl-4">
		<div class="col mb-2 mb-xxxl-0">
			<div class="card h-100 radius-5">
				<div class="card-body p-4">
					<div class="d-flex align-items-center justify-content-between gap-2">
						<div class="">
							<p class="fs-16 mb-2 text-black">Số căn đã bán</p>
							<span class="text-success fw-bold fs-24 total_billing">20</span>
						</div>
						<div class="btn btn-icon btn-lg rounded-3 alert alert-success mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house" aria-hidden="true" data-source-pos="45:54-45:72" data-source-name="Home"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2 mb-xxxl-0">
			<div class="card h-100 radius-5">
				<div class="card-body p-4">
					<div class="d-flex align-items-center justify-content-between gap-2">
						<div class="">
							<p class="fs-16 mb-2 text-black">Doanh số</p>
							<span class="text-info fw-bold fs-24 total_sale">20.000.000.000đ</span>
						</div>
						<div class="btn btn-icon btn-lg rounded-3 alert alert-info mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign" aria-hidden="true" data-source-pos="46:70-46:94" data-source-name="DollarSign"><line x1="12" x2="12" y1="2" y2="22"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2 mb-xxxl-0">
			<div class="card h-100 radius-5">
				<div class="card-body p-4">
					<div class="d-flex align-items-center justify-content-between gap-2">
						<div class="">
							<p class="fs-16 mb-2 text-black">Khách đang phụ trách</p>
							<span class="text-primary fw-bold fs-24 total_customer">50</span>
						</div>
						<div class="btn btn-icon btn-lg rounded-3 alert alert-primary mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users" aria-hidden="true" data-source-pos="47:63-47:82" data-source-name="Users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle></svg></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2 mb-xxxl-0">
			<div class="card h-100 radius-5">
				<div class="card-body p-4">
					<div class="d-flex align-items-center justify-content-between gap-2">
						<div class="">
							<p class="fs-16 mb-2 text-black">Tổng hoạt động</p>
							<span class="text-danger fw-bold fs-24 total_followups">20</span>
						</div>
						<div class="btn btn-icon btn-lg rounded-3 alert alert-danger mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone" aria-hidden="true" data-source-pos="48:56-48:75" data-source-name="Phone"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg></div>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<div class="form-row row-cols-1 row-cols-md-3">
		<div class="col mb-2">
			<div class="card h-100 radius-5 border border-success border-left-width-1">
				<div class="card-body p-4">
					<div class="d-flex align-items-center justify-content-between gap-2">
						<div class="">
							<p class="fs-16 mb-1 text-black">Số khách đã tiếp</p>
							<p class="text-success fw-bold fs-24 total_share_customer mb-1">20</p>
							<p class="text-muted fs-14 mb-0">Trong kỳ đã chọn</p>
						</div>
						<svg class="text-success" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-check text-green-500" aria-hidden="true" data-source-pos="75:12-75:52" data-source-name="UserCheck"><path d="m16 11 2 2 4-4"></path><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card h-100 radius-5 border border-info border-left-width-1">
				<div class="card-body p-4">
					<div class="d-flex align-items-center justify-content-between gap-2">
						<div class="">
							<p class="fs-16 mb-1 text-black">Lượt tiếp khách</p>
							<p class="text-info fw-bold fs-24 total_share mb-1">20</p>
							<p class="text-muted fs-14 mb-0">Bao gồm đã tiếp và chờ duyệt</p>
						</div>
						<svg class="text-info" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list text-blue-500" aria-hidden="true" data-source-pos="86:12-86:55" data-source-name="ClipboardList"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><path d="M12 11h4"></path><path d="M12 16h4"></path><path d="M8 11h.01"></path><path d="M8 16h.01"></path></svg>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card h-100 radius-5 border border-warning border-left-width-1">
				<div class="card-body p-4">
					<div class="d-flex align-items-center justify-content-between gap-2">
						<div class="">
							<p class="fs-16 mb-1 text-black">Tiếp khách chờ duyệt</p>
							<p class="fw-bold fs-24 total_share_waiting text-warning mb-1">50</p>
							<p class="text-muted fs-14 mb-0">Chưa được xác nhận</p>
						</div>
						<svg class="text-warning" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock text-orange-500" aria-hidden="true" data-source-pos="97:12-97:49" data-source-name="Clock"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
					</div>
				</div>
			</div>
		</div>		
	</div>
	{/if}
	
	<div class="form-row">
		<div class="col-12 col-lg-6 col-xxxl-6 mb-2">
			<div class="card radius-5">
				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
					<div class="card-title mb-0">
						<div class="d-flex align-items-center gap-2">
							<img src="{$URL_IMAGES}/marketing.png" class="w-px-40" />
							<div class="d-flex gap-1 flex-column">
								<h5 class="mb-0">Hoạt động Marketing</h5>
							</div>
						</div>
					</div>
					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="card-body">
					<div class="rounded-2 bg-lighter p-3 ajax search_global" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_marketing" 
						 data-options="{ldelim}{rdelim}"  gId="{$gId}">
						<div class="row">
							<div class="col-12 col-md-5 mb-2 mb-lg-0">
								<div class="d-flex align-items-center gap-3 text-fs-15 mb-2">
									<span class="w-px-200"><i class='bx bx-bar-chart-alt text-fs-26'></i> Ngân sách dự kiến</span>
									<span class="animate-bg w-px-100 rounded-2 h-px-15"></span>
								</div>
								<div class="d-flex align-items-center gap-3 text-fs-15">
									<span class="w-px-200 text-warning"><i class='bx bx-dollar-circle  text-fs-26'></i> Ngân sách thực tế</span>
									<span class="animate-bg w-px-100 rounded-2 h-px-15"></span>
								</div>
							</div>
							<div class="col-1 d-none d-lg-block border-end"></div>
							<div class="col-1 d-none d-lg-block"></div>
							<div class="col-12 col-md-5">
								<div class="d-flex align-items-center gap-3 text-fs-15 mb-2">
									<span class="w-px-200"><i class='bx bx-building text-primary text-fs-26' ></i> Công ty hỗ trợ</span>
									<span class="animate-bg w-px-100 rounded-2 h-px-15"></span>
								</div>
								<div class="d-flex align-items-center gap-3 text-fs-15">
									<span class="w-px-200"><i class='bx bx-user text-success text-fs-26' ></i> Sale chịu</span>
									<span class="animate-bg w-px-100 rounded-2 h-px-15"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-6 col-xxxl-6 mb-2">
			<div class="card radius-5 h-100" id="group_search">
				{assign var=uId value=$clsISO->getUniqid()}
				<div class="card-header {if $deviceType ne 'phone'}p-4 pb-2{/if}">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center gap-2">
							<svg class="text-info" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target text-blue-600" aria-hidden="true" data-source-pos="107:14-107:60" data-source-name="Target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
							<h5 class="card-title text-black mb-0">{if $deviceType eq 'phone'}Chỉ tiêu cá nhân{else}Tiến độ hoàn thành chỉ tiêu{/if}</h5>
						</div>
						<div class="d-flex align-items-center gap-1 flex-fill justify-content-end ">
							{if $clsISO->checkSale() || $clsISO->_DEV()}
								<button type="button" class="btn btn-outline-default btn-icon btn-sm" title="Cài đặt" onclick="$Core.dashboard.addTargetSales(this,event)" action="_OPEN">
									<i class="bx bx-list-plus"></i>
								</button>
							{/if}
							<div class="{if $deviceType eq 'phone'}w-px-130{else}w-px-150{/if}">
								<input type="month" class="form-control form-control-sm field_item" name="month" value="{$smarty.now|date_format:'%Y-%m'}" gid="{$uId}" onchange="$Core.dashboard.reload(this,event)">
							</div>
						</div>
					</div>
				</div>
				<div class="card-body ajax search_group box_target_sale {if $deviceType ne 'phone'}p-4{/if} pt-0" toId="group_search" gId="{$uId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_target_sale_report" data-options='{ldelim}{rdelim}' >
					<div class="row row-cols-1 row-cols-md-2">
						<div class="col mb-2 mb-lg-0">
							<div class="d-flex justify-content-between align-items-center mb-2 fs-16 text-info">
								<span class="">Số giao dịch</span>
								<span class="">75% hoàn thành</span>
							</div>
							<div class="progress w-100" style="height:12px;">
							  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:75%;"></div>
							</div>
						</div>
						<div class="col">
							<div class="d-flex justify-content-between align-items-center mb-2 fs-16 text-warning">
								<span class="">Doanh số</span>
								<span class="">80% hoàn thành</span>
							</div>
							<div class="progress w-100" style="height:12px;">
							  <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-6 col-xxxl-6 mb-2">
			<div class="card h-100 dashboard-panel-item radius-5">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 {if $deviceType ne 'phone'}p-4 pb-2{/if}">
					<div class="d-flex align-items-center gap-2">
						<svg class="text-success" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up text-green-600" aria-hidden="true" data-source-pos="121:14-121:65" data-source-name="TrendingUp"><path d="M16 7h6v6"></path><path d="m22 7-8.5 8.5-5-5L2 17"></path></svg>
						<h5 class="card-title mb-2 mb-lg-0 me-2 text-black">Biểu đồ doanh số</h5>
					</div>
				</div>
				<div class="card-body pb-0 {if $deviceType ne 'phone'}px-4{/if}">
					<div class="ajax search_global" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing_chart_sale" data-options='{ldelim}{rdelim}'>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-6 col-xxxl-6 mb-2">
			{assign var=toId value=$clsISO->getUniqid()}
			<div class="card h-100 dashboard-panel-item radius-5" id="{$toId}" >
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 {if $deviceType ne 'phone'}p-4 pb-2{/if}">
					<div class="d-flex align-items-center gap-2">
						<svg class="text-danger" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone text-orange-600" aria-hidden="true" data-source-pos="138:14-138:61" data-source-name="Phone"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
						<h5 class="card-title text-black mb-2 mb-lg-0 me-2">Hoạt động khách hàng</h5>
					</div>
					<div class="d-flex flex-wrap gap-2">
						<div class="dropdown">
							<button class="btn btn-icon p-0 h-auto" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="bx bx-dots-vertical-rounded"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
								<div class="p-3">
									<div class="form-group mb-2" role="group" aria-label="Nguồn gốc">
										<div class="form-label mb-1">Nguồn gốc</div>
										<select class="form-control {$crm_field} form-select field_item" data-width="100%" holderG="{$holderG}" onchange="$Core.dashboard_sale.reload(this,event)" data-placeholder="Nguồn gốc" data-width="100%" data-allow-clear="true" name="resource_id" data-field="resource_id" gId="{$gId}" toId="{$toId}" >
											<option value="0">Nguồn gốc</option>
											{$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES',$get_resource_id, "", true)}
										</select>
									</div>
									<div class="form-group mb-2" role="group" aria-label="Chiến dịch">
										<div class="form-label mb-1">Chiến dịch</div>
										<select name="campaign_id" data-placeholder="Chiến dịch" class="form-control {$crm_field}  field_item" data-width="100%" 
										data-allow-clear="true" holderG="{$holderG}" onchange="$Core.dashboard_sale.reload(this,event)" data-field="campaign_id" gId="{$gId}" toId="{$toId}" >
											<option value="0">Chiến dịch</option>
											{if !empty($list_campaigns)}
												{foreach from=$list_campaigns item = _oCampaign}
												<option value="{$_oCampaign.campaign_id}">{$_oCampaign.title}</option>
												{/foreach}
											{/if}
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body pb-0 {if $deviceType ne 'phone'}px-4{/if}">
					<div class="ajax search_global search_group" toId="{$toId}" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_followup_action" data-options='{ldelim}{rdelim}'>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-lg-6 col-xxxl-4 mb-2 mb-xxxl-0">
			<div class="card h-100 radius-5">
				<div class="card-header d-flex align-items-center justify-content-between {if $deviceType ne 'phone'}p-4 pb-2{/if}">
					<div class="d-flex align-items-center gap-2">
						<i class="bx bx-user fs-24 text-warning" ></i>
						<h5 class="card-title mb-0 text-black">Khách hôm nay</h5>
					</div>
				</div>
				<div class="card-body ajax search_global pt-0 {if $deviceType ne 'phone'}px-4{/if}" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod=home&act=load_followup_crm" data-options="{ldelim}{rdelim}">
					<div class="p-5 text-center">
						<div class="p-2">Đang tải...</div>
					</div>
					<a class="btn bg-main btn-outline-default text-white w-100" href="{$clsISO->getLink('crm')}" >Đi đến CRM</a>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-6 col-xxxl-4 mb-2 mb-xxxl-0">
			<div class="card h-100 radius-5">
				<div class="card-header d-flex align-items-center justify-content-between {if $deviceType ne 'phone'}p-4 pb-2{/if}">
					<div class="d-flex align-items-center gap-2">
						<svg class="text-info" xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
						fill="currentColor" viewBox="0 0 24 24" >
						<path d="M19 9c-1.3 0-2.4.84-2.82 2H13V2h-2v3H7.82A2.99 2.99 0 0 0 5 3C3.35 3 2 4.35 2 6s1.35 3 3 3c1.3 0 2.4-.84 2.82-2H11v10H7.82A2.99 2.99 0 0 0 5 15c-1.65 0-3 1.35-3 3s1.35 3 3 3c1.3 0 2.4-.84 2.82-2H11v3h2v-9h3.18c.41 1.16 1.51 2 2.82 2 1.65 0 3-1.35 3-3s-1.35-3-3-3M5 7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m0 12c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m14-6c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1"></path>
						</svg>
						<h5 class="card-title mb-0 text-black">Tiến trình chăm sóc {if $deviceType eq 'phone'}KH{else}khách hàng{/if}</h5>
					</div>
				</div>
				<div class="card-body ajax search_global pt-0 {if $deviceType ne 'phone'}px-4{/if}" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod=home&act=load_sales_pipeline" data-options="{ldelim}{rdelim}">
					<div class="p-5 text-center">
						<div class="p-2">Đang tải...</div>
					</div>
				</div>
			</div>
		</div>		
		<div class="col-12 col-lg-6 col-xxxl-4 mb-2 mb-xxxl-0">
			<div class="card h-100 radius-5">
				<div class="card-header {if $deviceType ne 'phone'}p-4 pb-2{/if}">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center gap-2">
							<svg class="text-main fs-24" xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
							fill="currentColor" viewBox="0 0 24 24" >
							<path d="M16 16H2v2h14v4l6-5-6-5zM8 1 2 6l6 5V7h14V5H8z"></path>
							</svg>
							<h5 class="card-title mb-2 mb-lg-0 me-2 text-black">Tỉ lệ chuyển đổi</h5>
						</div>
					</div>
				</div>
				<div id="holder_converted_rates" class="card-body pt-0 {if $deviceType ne 'phone'}px-4{/if} ajax search_global" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod=crm&act=load_converted_rates" >
					<div class="p-5 text-center">
						<div class="p-2">Đang tải...</div>
					</div>
				</div>
			</div>
		</div>					
		<div class="col-12 col-lg-6 col-xxxl-4 mb-2 mb-xxxl-0" >
			<div class="card h-100 radius-5">
				<div class="card-header d-flex align-items-center justify-content-between {if $deviceType ne 'phone'}p-4 pb-2{/if}">
					<div class="d-flex align-items-center gap-2">
						<i class="bx bx-search-alt fs-24 text-success" ></i>
						<h5 class="card-title mb-0 text-black">Tra cứu gần đây</h5>
					</div>
				</div>
				<div class="card-body pt-0 {if $deviceType ne 'phone'}px-4{/if}">
					<div class="table-container no-shadow overflow-auto">
						<table cellpadding="0" cellspacing="0" class="table table-bordered text-nowrap">
							<thead class="position-sticky top-0 zindex-3">
								<th width="40" class="align-center text-center h-px-35 bg-lighter">STT</th>
								<th class="align-center h-px-35 bg-lighter">Mã căn</th>
								<th class="align-center h-px-35 bg-lighter">Thời gian</th>
							</thead>
							<tbody class="ajax search_global" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_search_stock" data-options='{ldelim}{rdelim}' >
								{section name=i loop=$list_preloaders max=10}
									<tr>
										<td><div class="animate-bg w-100 rounded-2"></div></td>
										<td><div class="animate-bg w-100 rounded-2"></div></td>
										<td><div class="animate-bg w-100 rounded-2"></div></td>
									</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-6 col-xxxl-4 mb-2 mb-xxxl-0">
			<div class="card h-100 radius-5">
				<div class="card-header d-flex align-items-center justify-content-between {if $deviceType ne 'phone'}p-4 pb-2{/if}">
					<div class="d-flex align-items-center gap-2">
						<i class="bx bx-terminal text-primary fs-24"></i>
						<h5 class="card-title mb-0">Giao dịch gần đây</h5>
					</div>
					<a href="/giao-dich.html" target="_blank" class="btn btn-icon btn-sm btn-link rounded-pill">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
				</div>
				<div class="card-body ajax search_global pt-0 {if $deviceType ne 'phone'}px-4{/if}" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=billing_me" 
				data-options='{ldelim}{rdelim}'>
					{section name=i loop=$list_preloaders max=5}
					<div class="w-100 mb-2">
						<div class="animate-bg w-75 rounded-2 h-px-20 mb-2"></div>
						<div class="w-100 gap-2 d-flex align-items-center justify-content-between">
							<div class="animate-bg w-40 rounded-2 h-px-20"></div>
							<div class="animate-bg w-50 rounded-2 h-px-20"></div>
						</div>
					</div>
					{/section}
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-6 col-xxxl-4 mb-2 mb-xxxl-0">
			<div class="card h-100 radius-5">
				<div class="card-header d-flex align-items-center justify-content-between {if $deviceType ne 'phone'}p-4 pb-2{/if}">
					<div class="d-flex align-items-center gap-2">
						<i class="bx bx-cart fs-24 text-warning" ></i>
						<h5 class="card-title mb-0">Booking gần đây</h5>
					</div>
					<a href="{$clsISO->getLink('my_booking')}" target="_blank" class="btn btn-icon btn-sm btn-link rounded-pill">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
				</div>
				<div class="card-body pt-0 {if $deviceType ne 'phone'}px-4{/if}">
					<div class="table-container holder_mybookings overflow-auto text-nowrap no-shadow h-px-300 ajax search_global" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod=booking&act=load_mybookings" 
				data-options='{ldelim}{rdelim}'>
						{section name=i loop=$list_preloaders max=5}
						<div class="w-100 mb-2">
							<div class="animate-bg w-75 rounded-2 h-px-20 mb-2"></div>
							<div class="w-100 gap-2 d-flex align-items-center justify-content-between">
								<div class="animate-bg w-40 rounded-2 h-px-20"></div>
								<div class="animate-bg w-50 rounded-2 h-px-20"></div>
							</div>
						</div>
						{/section}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<style>
	.radius-5 {
		border-radius: 1rem
	}
	.border-left-width-1 {
		 border-left-width: 0.25rem !important
	}
	.w-px-130{
		width:130px
	}
	.mobile_header_search {		
		position: sticky;
		top: 61px;
		background: #FFF;
		padding: 15px;
		border-bottom-left-radius: 5px;
		border-bottom-right-radius: 5px;
		z-index: 10000 !important;
		box-shadow: 0 0 5.175rem 0.25rem rgba(161, 172, 184, 0.15);
	}
</style>
<script>
$(function(){
	setTimeout(() => {
		$Core.dashboard_sale.load_total_dashboard();
		_autoload();
	}, 500);
	$_document.on('click', '.dropdown-button', e => {
		e.stopPropagation();
		const b = $(e.currentTarget), o = b.offset(),
			  m = b.siblings('.dropdown-menu').clone()
				  .addClass('dropdown-menu-floating')
				  .css({position:'absolute',display:'block'})
				  .appendTo('body');
		$('.dropdown-menu-floating').not(m).remove();
		m.css({top:o.top+b.outerHeight(),left:o.left+b.outerWidth()-m.outerWidth(),zIndex:1000});
		$_document.one('click',()=>m.remove());
	});
});
</script>
{/literal}