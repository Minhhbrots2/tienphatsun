<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_JS}/fullcalendar-1.6.0/fullcalendar/fullcalendar.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
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
<div class="container-xxl flex-grow-1 container-p-y pt-2 crm_page">
	<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
		<h4 class="uganfTAavk fw-bold mb-2 mb-lg-0">
			<span class="text-muted fw-light">Trung tâm khách hàng/CRM</span>
		</h4>
		<div class="dxyjbHUtpm d-flex gap-1 xs:w-100 align-items-center">
			{if $clsISO->checkPermission('customer_new')}
			<button onclick="$Core.global.crm.add_customer(this, event)" uid="{$uid}" customer_id="0" openfrom="_crm" class="btn goLink btn-outline-success" data-toggle="ripple" route="/customer/create/0">{$clsISO->makeIcon('bx-user-plus','Thêm mới')}</button>
			{/if}
			{if $clsISO->checkPermission('import_crm')}
			<button onclick="$Core.crm.open_import(this, event)" uid="{$uid}" customer_id="0" class="btn btn-icon goLink js__open-import btn-outline-primary" data-toggle="ripple" route="/import">{$clsISO->makeIcon('bx-upload')}</button>
			{/if}
			<div class="dropdown">
				<button id="{$gId}" type="button" class="btn btn-icon btn-default hide-arrow {$gId} dropdown-crm-search dropdown-toggle" 
				data-bs-toggle="dropdown" data-toggle="ripple" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">{$clsISO->makeIcon('bx-search')}</button>
				<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-350" data-popper-placement="top-end">
					{$core->getBlock('crm_search',['gId'=>$gId, 'holderG'=>'_desktop'])}
				</div> 
			</div>
			<!-- <span class="border-end mx-1">&nbsp;</span>
			<a href="/crm/" class="btn flex-fill btn-primary">{$clsISO->makeIcon('bx-user', 'Của tôi')}</a> -->
			<!-- <button title="Hướng dẫn sử dụng" onclick="$Core.crm.open_help(this, event)" data-toggle="ripple" class="btn btn-icon btn-outline-default" data-toggle="ripple">{$clsISO->makeIcon('bx-help-circle')}</button> -->
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="form-row">
		<div class="col-12 col-md-6 col-lg-6 mb-2">
			<div class="dashboard-panel-item dashboard-panel-item--full mb-0 h-100">
				<div class="panel border-0 mb-0 panel-default h-100">
					<div class="panel-heading d-flex justify-content-between w-100 gap-2">
						<h3 class="panel-title">Khách hàng mới</h3>
						<a class="panel-help help_pop" data-bs-toggle="tooltip" title="Khách hàng mới">
							<i class="fa fa-question-circle"></i></a>
					</div>
					<div class="panel-body no-easyui">
						<div class="d-flex flex-wrap gap-2 ajax" 
							data-url="{$PCMS_URL}?mod={$mod}&act=load_new_customer_number" data-options="{ldelim}{rdelim}">
							{foreach from=$list_customer_box item = _oBox}
							<div class="gbox flex-flow px-2 py-3">
								<div class="d-flex mb-2 align-items-center justify-content-between">
									<h5 class="mb-0 fs-14">{$_oBox}</h5> 
									<a data-bs-toggle="tooltip" class="panel-help help_pop" data-bs-original-title="Số khách hàng mới {$_oBox|lower}">
										<i class="fa fa-question-circle"></i>
									</a>
								</div>
								<h3 class="fs-5 mb-0 fw-bold text-main">
									<div class="animate-bg w-px-50 h-px-15 rounded-2"></div>
								</h3>
							</div>
							{/foreach}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6 col-lg-6 mb-2">
			<div class="dashboard-panel-item dashboard-panel-item--full mb-0 h-100">
				<div class="panel border-0 mb-0 panel-default h-100">
					<div class="panel-heading d-flex w-100 justify-content-between">
						<div class="d-flex align-items-center gap-2">
							<h3 class="panel-title mb-0">Chăm sóc khách hàng</h3>
							<a class="panel-help help_pop openHelp" data-bs-toggle="tooltip" title="Thống kê khách hàng">
								<i class="fa fa-question-circle"></i></a>
						</div>
						<button class="btn btn-icon btn-sm btn-outline-default" type="button" onclick="$Core.crm.setting_field(this,event)" view_by="compact" field_name="fieldDataCustomerStatus" action="_OPEN" title="Tùy chỉnh cột"><i class="bx bx-list-plus"></i></button>
					</div>
					<div class="panel-body no-easyui">
						<div class="box_customer_stats d-flex flex-wrap gap-2 ajax" 
							data-url="{$PCMS_URL}?mod={$mod}&act=load_customer_stats" data-options="{ldelim}{rdelim}">
							<div class="gbox flex-flow px-2 py-3">	
								<div class="d-flex mb-2 align-items-center justify-content-between">
									<h5 class="mb-0 fs-14">Tổng</h5> 
									<a data-bs-toggle="tooltip" class="panel-help help_pop" title="Tổng số khách hàng">
										<i class="fa fa-question-circle"></i>
									</a>
								</div>
								<h3 class="fs-5 mb-0 fw-bold text-main">
									<span>0</span>
								</h3>
							</div>
							{foreach from=$list_customer_stats item = _oBox}
							<div class="gbox flex-flow px-2 py-3" style="background-color:{$_oBox.bgcolor}; border-color:{$_oBox.bgcolor}">
								<div class="d-flex mb-2 align-items-center justify-content-between">
									<h5 class="mb-0 fs-14 text-white">{$_oBox.title}</h5> 
									<a data-bs-toggle="tooltip" class="panel-help help_pop text-white" title="Tổng số khách hàng {$_oBox.title}">
										<i class="fa fa-question-circle"></i>
									</a>
								</div>
								<h3 class="fs-5 mb-0 fw-bold text-main">
									<span>0</span>
								</h3>
							</div>
							{/foreach}
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<div class="form-row">
		<div class="col-12 col-md-6 mb-2">
			<div class="dashboard-panel-item dashboard-panel-item--full mb-0 h-100">
				<div class="panel border-0 panel-default h-100">
					<input type="hidden" name="time_type" value="THIS_WEEK">
					<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center w-100">
						<div class="">
							<h3 class="panel-title">Thống kê giao dịch</h3>
							<a href="javascript:void(0);" class="panel-help help_pop openHelp" data-bs-toggle="tooltip" title="Thống kê giao dịch">{$core->makeIcon('question-circle')}</a>
						</div>
						<ul class="nav list pull-right">
							{foreach from = $list_filters key = key item = text}
							<li class="nav-item">
								<a href="javascript:void(0);" onclick="$Core.crm.timer_click(this,event);" holderG="{$key}" 
									class="js_choose-time fs-11{if $key eq 'THIS_WEEK'} active{/if}">{$text}</a>
							</li>
							{/foreach}
						</ul>
					</div>
					<div class="panel-body no-easyui">
						<div id="{$clsISO->getUniqid()}" style="min-height:300px" data-url="/index.php?mod={$mod}&act=load_trans_chart" class="load_desktop_chart_billings time_type w-100 ajax" data-options='{ldelim}"type":"billings"{rdelim}'>
							<div class="p-5">
								<div class="p-5">
									<div class="p-5 text-center">
										Loading...
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6 mb-2 ajax js__block-sys-staff" data-url="{$PCMS_URL}?mod={$mod}&act=load_sys_staff" data-options="{}" toIdNumber="total_employ">
			<div class="dashboard-panel-item dashboard-panel-item--full mb-2 h-100">
				<div class="panel border-0 mb-0 panel-default h-100">
					<div class="panel-heading d-flex flex-column w-100">
						<h3 class="panel-title">Danh sách nhân viên</h3>
						<small class="text-muted">Tổng <strong class="text-main fw-bold" id="total_staff_{$_oItem.group_profile_id}">0</strong> nhân viên</small>									
					</div>
					<div class="panel-body scroll-y-auto table-container no-shadow overflow-x-auto" toIdNumber="total_member_{$_oItem.group_profile_id}" style="max-height:228px; min-height:170px">
						<table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="align-center">Nhân viên</th>
									<th class="align-center">Khách hàng</th>
									<th class="align-center text-center">Giao dịch</th>
									<th class="align-center text-right">Follow-Ups</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								{section name=i loop=$list_preloaders max=30}
									<tr>
										<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
										<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
										<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
										<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
									</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<div class="form-row mb-2">
		<div class="col-12 col-md-4">
			<div class="dashboard-panel-item dashboard-panel-item--full mb-0 h-100">
				<div class="panel border-0 mb-0 panel-default h-100">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="panel-heading d-flex w-100 justify-content-between align-items-center">
						<h3 class="panel-title">Tỷ lệ chuyển đổi</h3>	
						<div class="btn-group">
							<button type="button" class="btn btn-link btn-icon rounded-pill dropdown-toggle hide-arrow" 
								data-bs-toggle="dropdown" aria-expanded="false">
								<i class="bx bx-cog text-muted"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-end">
								<li><h6 class="dropdown-header">Điều kiện lọc</h6></li>
								<li class="dropdown-divider"></li>
								{foreach from=$list_filters item = _text key = _key}
								<li><a href="javascript:void(0);" gId="{$gId}" class="dropdown-item{if $_key eq 'THIS_MONTH'} active{/if}" 
									onclick="$Core.crm.set_time(this, event)" tp="{$_key}">{$_text}</a></li>
								{/foreach}
							</ul>
						</div>
					</div>
					<div class="panel-body no-easyui ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_conversion_rate" 
						data-options='{ldelim}{rdelim}'>
						<div class="p-4 text-center">Loading...</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			<div class="dashboard-panel-item dashboard-panel-item--full mb-0 h-100">
				<div class="panel border-0 panel-default radius-3 h-100">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="panel-heading d-flex w-100 justify-content-between align-items-center">
						<h3 class="panel-title">Tăng trưởng KH tạo mới</h3>
						<div class="btn-group">
							<button type="button" class="btn btn-link btn-icon rounded-pill dropdown-toggle hide-arrow" 
								data-bs-toggle="dropdown" aria-expanded="false">
								<i class="bx bx-cog text-muted"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-end">
								<li><h6 class="dropdown-header">Điều kiện lọc</h6></li>
								<li class="dropdown-divider"></li>
								{foreach from=$list_filters item = _text key = _key}
								<li><a href="javascript:void(0);" gId="{$gId}" class="dropdown-item{if $_key eq 'THIS_MONTH'} active{/if}" 
									onclick="$Core.crm.set_time(this, event)" tp="{$_key}">{$_text}</a></li>
								{/foreach}
							</ul>
						</div>
					</div>
					<div class="panel-body no-easyui">
						<div gId="{$gId}" style="min-height:300px" class="w-100 ajax" data-options='{ldelim}{rdelim}' 
							data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_desktop_chart_cus">
							<div class="p-5">
								<div class="p-5">
									<div class="p-5 text-center">
										Loading...
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			<div class="dashboard-panel-item dashboard-panel-item--full mb-0 h-100">
				<div class="panel border-0 panel-default h-100">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="panel-heading d-flex w-100 justify-content-between align-items-center">
						<h3 class="panel-title">Tăng trưởng KH theo nguồn gốc</h3>
						<div class="btn-group">
							<button type="button" class="btn btn-link btn-icon rounded-pill dropdown-toggle hide-arrow" 
								data-bs-toggle="dropdown" aria-expanded="false">
								<i class="bx bx-cog text-muted"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-end">
								<li><h6 class="dropdown-header">Điều kiện lọc</h6></li>
								<li class="dropdown-divider"></li>
								{foreach from=$list_filters item = _text key = _key}
								<li><a href="javascript:void(0);" gId="{$gId}" class="dropdown-item{if $_key eq 'THIS_MONTH'} active{/if}" 
									onclick="$Core.crm.set_time(this, event)" tp="{$_key}">{$_text}</a></li>
								{/foreach}
							</ul>
						</div>
					</div>
					<div class="panel-body no-easyui">
						<div gId="{$gId}" style="min-height:300px" class="w-100 ajax" data-options='{ldelim}{rdelim}'
							data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_desktop_chart_res">
							<div class="p-5">
								<div class="p-5">
									<div class="p-5 text-center">
										Loading...
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	