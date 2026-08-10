<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
		<div class="mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1"><span>Thống kê điểm loyalty</span></h4>
			<p class="text-muted mb-0">Hệ thống hỗ trợ bán hàng Ocean City</p>
		</div>
		{assign var=gId value=$clsISO->getUniqid()}
		<div class="input-group d-flex {if $deviceType ne 'phone'}w-px-300{/if} ox:w-100" role="group">
			<select class="form-control form-select" name="month_total" onChange="$Core.report.load_loyalty('_TOTAL', {})" gId="{$gId}" data-type="MONTH"> 
				<option value="">Tháng</option>			
				{foreach from=$list_months item = _month}
				<option value="{$_month}">Tháng {$_month}</option>
				{/foreach}
			</select>
			<select class="form-control form-select" name="year_total" onChange="$Core.report.loadMonth(this,event)" gId="{$gId}" data-type="_TOTAL">
				{foreach from=$list_years item = _year}
				<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
				{/foreach}
			</select>
			<button title="Chuyển điểm" fpoint_id="0" holderG="_move" onClick="$Core.global.open_loyalty(this, event)" 
			class="btn btn-outline-default"><i class="bx bx-user"></i>
				{$core->makeIcon('long-arrow-right')}
				<i class="bx bx-user-plus"></i></button>
			<button title="Trừ điểm" fpoint_id="0" holderG="_minus" onClick="$Core.global.open_loyalty(this, event)" 
			class="btn btn-outline-default"><i class="bx bx-user-minus"></i></button>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="box_statistic">
		<div class="row">
			<div class="col-12 col-lg-8">
				<div class="box_depart_point" id="box_depart_point"></div>
				<div class="col-md-12">
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel border-0 no-shadow panel-default">
							<div class="panel-heading d-flex flex-wrap align-items-center justify-content-between">
								<h3 class="panel-title mb-0 fs-5 fw-semibold">Biểu đồ điểm Loyalty</h3>	
								<select gId="{$gId}" class="form-control w-px-100 form-select" name="name" >
									{foreach from=$list_years item = _year}
									<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
							</div>
							<div class="panel-body ajax" data-url="/index.php?mod={$mod}&act=load_loyalty_chart" data-options="{ldelim}{rdelim}"></div>
						</div>
					</div>
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel border-0 no-shadow panel-default">
							<div class="panel-heading d-flex flex-wrap align-items-center justify-content-between">
								<h3 class="panel-title mb-0 fs-5 fw-semibold">Điểm Loyalty nhân viên</h3>	
								
							</div>
							<div class="panel-body ajax" data-url="/index.php?mod={$mod}&act=load_loyalty_staffs" data-options="{ldelim}{rdelim}"></div>
						</div>
					</div>
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel border-0 no-shadow panel-default" id="loadListEmploy">
							<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center gap-2">
								<div class="d-flex flex-wrap align-items-center {if $deviceType eq 'phone'}w-100{/if}">
									<h3 class="panel-title me-2 fs-5 fw-semibold">Lịch sử điểm Loyalty</h3>	
									(<span class="number_point text-main fw-bold">0</span>)
								</div>
								<div class="d-flex flex-wrap align-items-center justify-content-between gap-1">
									<div class="p_right {if $deviceType eq 'phone'}flex-fill{/if}">
										<div class="input-group input-group-merge mr-1">
											<span class="input-group-text"><i class="bx bx-search"></i></span>
											<input type="text" class="form-control search_field" name="keyword" data-field="keySearch" placeholder="Search" onKeyUp="$Core.report.load_loyalty('_LIST', {})">
										</div>
									</div>	
									<div class="input-group {if $deviceType ne 'phone'}w-px-200{/if} ox:w-100 d-flex" role="group">
										<select class="form-control form-select" name="department_id" onChange="$Core.report.load_loyalty('_LIST', {})"> 
											<option value="">Phòng ban</option>			
											{foreach from=$lstDepartment key=key item =department}
											<option value="{$department.property_id}">{$department.title}</option>
											{/foreach}
										</select>
									</div>		
									{assign var=gId value=$clsISO->getUniqid()}
									<div class="input-group {if $deviceType ne 'phone'}w-px-200{/if} ox:w-100 d-flex" role="group">
										<select class="form-control form-select" name="month_list" onChange="$Core.report.load_loyalty('_LIST', {})" gId="{$gId}" data-type="MONTH"> 
											<option value="">Tháng</option>			
											{foreach from=$list_months item = _month}
											<option value="{$_month}">Tháng {$_month}</option>
											{/foreach}
										</select>
										<select class="form-control form-select" name="year_list" onChange="$Core.report.loadMonth(this,event)" gId="{$gId}" data-type="_LIST">
											{foreach from=$list_years item = _year}
											<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
											{/foreach}
										</select>
									</div>						
									<input type="hidden" name="page" value="1">
								</div>
							</div>
							<div class="panel-body px-0">
								<div id="listEmploy" class="w-100" style="min-height:300px;">
									<div class="p-5 text-center">
										<div class="p-5 text-muted">Loading...</div>
									</div>
								</div>
								<div id="pagination-container" class="pagination_ajax d-flex gap-2 justify-content-center mt-4"></div>
							</div>
						</div>
					</div>			
				</div>
			</div>
			<div class="col-12 col-lg-4">
				<div class="sticky">
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel border-0 no-shadow panel-default" id="loadListTopEmploy">
							<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center gap-2">
								<div class="d-flex flex-wrap align-items-center {if $deviceType eq 'phone'}w-100{/if}">
									<h3 class="panel-title me-2 fs-5 fw-semibold">Top 10 nhân viên có Loyalty cao nhất</h3>	
								</div>
							</div>
							<div class="panel-body p-0">
								<div id="listTopEmploy" class="w-100">
									{section name=i loop=$list_preloaders max=10}
									<div class="w-100 mb-2 d-flex justify-content-between align-items-center">
										<div class="d-flex gap-2">
											<div class="w-px-50 h-px-50 animate-bg rounded-2"></div>
											<div class="pt-1">
												<div class="w-px-100 h-px-15 mb-2 animate-bg rounded-2"></div>
												<div class="w-px-50 h-px-15 animate-bg rounded-2"></div>
											</div>
										</div>
										<div class="py-1">
											<div class="w-px-30 h-px-15 animate-bg rounded-2"></div>
										</div>
									</div>
									{/section}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
$(document).ready(function(){
	$Core.report.load_loyalty('_TOTAL', {}); 
	$Core.report.load_loyalty('_TOP', {}); 
	$Core.report.load_loyalty('_LIST', {}); 
});
</script>
{/literal}