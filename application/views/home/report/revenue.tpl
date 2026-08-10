<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<form method="POST">
		<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2 mb-lg-0 gap-2">
			<div class="title mb-lg-0">
				<h4 class="fw-bold mb-1">Doanh số bán hàng</span></h4>
				<span class="text-muted">Báo cáo kết quả bán hàng</span>
			</div>
			<div class="search d-flex{if $deviceType eq 'phone'} flex-wrap{/if} align-items-center gap-1">
				{assign var=gId value=$clsISO->getUniqid()}
				{if $deviceType eq 'phone'}
					<div class="input-group w-100 mb-1">
						<select id="{$uid}" name="billing_source" data-field="billing_source" class="form-control form-select search_field w-px-100 flex-fill"  onchange="$Core.dashboard.load_profile(this,event)" gId="{$gId}" >
							<option value="0">Nguồn quỹ</option>
							{$clsProperty->getSelectByProperty('BILLING_SOURCE',$billing_source)}
						</select>
						<select onchange="$Core.dashboard.load_profile(this,event)" name="department_id" data-field="department_id" 
							class="form-control js__search-department-field search_field w-px-100 form-select flex-fill" gId="{$gId}" >
							<option value="0">Chọn vùng</option>
							{if !empty($list_sale_departments)}
								{foreach from=$list_sale_departments item = _oG}
								<option value="{$_oG.property_id}">{$_oG.title}</option>
								{/foreach}
							{/if}
						</select>
						<select class="form-control form-select search_field w-px-150 flex-fill" name="profile_id" id="profile_{$gId}" gId="{$gId}" onChange="$Core.report.do_search(this,event)" style="display: none;"> 
							<option value="">Nhân viên</option>			
							{foreach from=$lstProfile item = _oProfile}
							<option value="{$_oProfile.profile_id}">{$_oProfile.full_name}</option>
							{/foreach}
						</select>
					</div>
					<div class="d-flex align-items-center gap-1 mb-2 w-100">
						<select class="form-control form-select search_field w-px-100 flex-fill" name="date_type" gId="{$gId}" 
							onChange="$Core.dashboard.reload(this,event)"> 
							<option value="_month">Tháng</option>
							<option value="_quarter">Quý</option>
							<option value="_half_year">Nửa năm</option>
						</select>
						<select onchange="$Core.report.do_search(this,event)" name="month" data-field="month" 
							class="form-control search_field js__search-month-field w-px-100 form-select flex-fill" gId="{$gId}">
							{foreach from=$list_months item=_month}
							<option{if $Current_Month eq $_month} selected{/if} value="{$_month}">Tháng {$_month}</option>
							{/foreach}
						</select>
						<select class="form-control search_field form-select w-px-100 flex-fill" name="year" data-field="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
							{foreach from=$list_years item = _year}
							<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
							{/foreach}
						</select>
					</div>
				{else}
					<div class="input-group">
						<select id="{$uid}" name="billing_source" data-field="billing_source" class="form-control search_field form-select w-px-100"  onchange="$Core.dashboard.load_profile(this,event)" gId="{$gId}" >
							<option value="0">Nguồn quỹ</option>
							{$clsProperty->getSelectByProperty('BILLING_SOURCE',$billing_source)}
						</select>
						<select onchange="$Core.dashboard.load_profile(this,event)" name="department_id" data-field="department_id" 
							class="form-control js__search-department-field search_field w-px-100 form-select" gId="{$gId}" >
							<option value="0">Chọn vùng</option>
							{if !empty($list_sale_departments)}
								{foreach from=$list_sale_departments item = _oG}
								<option value="{$_oG.property_id}">{$_oG.title}</option>
								{/foreach}
							{/if}
						</select>
						<select class="form-control form-select search_field w-px-150" name="profile_id" id="profile_{$gId}" gId="{$gId}" onChange="$Core.report.do_search(this,event)" style="display: none;"> 
							<option value="">Nhân viên</option>			
							{foreach from=$lstProfile item = _oProfile}
							<option value="{$_oProfile.profile_id}">{$_oProfile.full_name}</option>
							{/foreach}
						</select>
						<select class="form-control search_field form-select w-px-100" name="date_type" gId="{$gId}" 
							onChange="$Core.dashboard.reload(this,event)"> 
							<option value="_month">Tháng</option>
							<option value="_quarter">Quý</option>
							<option value="_half_year">Nửa năm</option>
						</select>
						<select onchange="$Core.report.do_search(this,event)" name="month" data-field="month" 
							class="form-control search_field js__search-month-field w-px-100 form-select" gId="{$gId}">
							{foreach from=$list_months item=_month}
							<option{if $Current_Month eq $_month} selected{/if} value="{$_month}">Tháng {$_month}</option>
							{/foreach}
						</select>
						<select class="form-control search_field form-select w-px-100" name="year" data-field="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
							{foreach from=$list_years item = _year}
							<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
							{/foreach}
						</select>
					</div>
				{/if}
			</div>
		</div>
	</form>
	<hr class="my-0" />
	<div class="ajax holder_revenue_reports" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=load_revenue" data-options='{ldelim}{rdelim}'>
		<div class="card mb-2">
			<div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
				<h5 class="card-title mb-2 mb-lg-0 me-2">Biểu đồ thống kê doanh số</h5>
			</div>
			<div class="card-body">
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
		<div class="card mb-2">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="mb-0">Thống kê doanh số</h5>
				<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
					<i class="fa fa-question-circle"></i>
				</a>
			</div>
			<div class="card-body">
				<div class="table-container">
					<table border="0" cellspacing="0" cellpadding="0" class="table w-100">
						<thead><tr>
							<th class="align-center">Nhân viên</th>
							<th class="align-center text-center">...</th>
							<th class="align-center text-center">...</th>
							<th class="align-center text-center">...</th>
							<th class="align-center text-center">...</th>
							<th class="align-center text-center">...</th>
							<th class="align-center text-center">...</th>
						</tr></thead>
						{section name=i loop=$list_preloaders max = 10}
						<tr>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						</tr>
						{/section}
					</table>
				</div>
			</div>
		</div>
	</div>
	
	
</div>
{literal}
<style type="text/css">
	.table-responsive td{
		text-align:left;
	}
	.multiselect-native-select{
		width:100%
	}
	.ui-datepicker,
	.select2-container--open{
		z-index:9999 !important;
	}
	.table-iloocal tr td {
		font-weight: 400;
		font-size: 14px;
		line-height: 20px;
		padding: 6px 15px;
		background: var(--bs-white);
		border: 1px solid rgba(0, 0, 0, 0.1);
		height: 40px;
	}
	.table-iloocal thead tr th {
		background: #F9F9F9;
		border: 1px solid rgba(0, 0, 0, 0.1);
		white-space: nowrap;
		font-weight: 600;
		font-size: 14px;
		line-height: 20px;
		padding: 10px 15px
	}
	.table-iloocal .js__add-report:not(.text-muted) {
		font-weight: 600;
		font-size: 14px;
		line-height: 19px;
		color: #1756C8 !important;
		cursor: pointer;
	}
	.table-iloocal .js__add-report span.icon {
		display: inline-block;
		width: 14px;
		height: 14px;
		text-align: center;
		line-height: 12px;
		background: #1756C8;
		border-radius: 2px;
		-moz-border-radius: 2px;
		-webkit-border-radius: 2px;
		color: var(--bs-white);
		padding:3px;
		font-size: 10px;
	}
</style>
{/literal}