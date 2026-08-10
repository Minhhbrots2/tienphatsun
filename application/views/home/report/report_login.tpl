<div class="container-xxl flex-grow-1 container-p-y pt-2">	
	<div class="form-row my-2">
		<div class="col-12 col-md-8 mx-auto">
			<form method="POST">
				<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
					<div class="title mb-lg-0">
						<h4 class="fw-bold mb-1">Báo cáo tần suất truy cập</span></h4>
						<span class="text-muted fs-12">Danh sách truy cập nhân viên</span>
					</div>
					{if $deviceType ne 'phone'}
					<div class="search d-flex flex-wrap align-items-center gap-1">
						<select onchange="$Core.report.do_search(this,event)" data-field="department_id" 
							class="form-control js__search-department-field search_field w-px-100 form-select">
							<option>Tất cả</option>
							{if !empty($list_sale_departments)}
								{foreach from=$list_sale_departments item = _oG}
								<option value="{$_oG.property_id}">{$_oG.title}</option>
								{/foreach}
							{/if}
						</select>
						<div class="input-group w-px-300">
							<select onchange="$Core.report.do_search(this,event)" data-field="year" 
								class="form-control js__search-year-field search_field form-select">
								{foreach from=$list_years item=_year}
								<option{if $Current_Year eq $_year} selected{/if} value="{$_year}">Năm {$_year}</option>
								{/foreach}
							</select>
							<select onchange="$Core.report.do_search(this,event)" data-field="month" 
								class="form-control search_field js__search-month-field form-select">
								{foreach from=$list_months item=_month}
								<option{if $Current_Month eq $_month} selected{/if} value="{$_month}">Tháng {$_month}</option>
								{/foreach}
							</select>
							<select onchange="$Core.report.do_search(this,event)" data-field="date_type" 
								class="form-control search_field js__search-date_type-field form-select">
								<option value="today">Hôm nay</option>
								<option value="yesterday">Hôm qua</option>
								<option value="7days">7 ngày qua</option>
								<option value="15days">15 ngày qua</option>
								<option value="30days">30 ngày qua</option>
							</select>
						</div>
						<div class="input-group w-auto">
							<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-start_date-field 
							js__search-date-field search_field w-px-125" data-field="start_date" value="{$start_date}" />
							<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-end_date-field 
							js__search-date-field search_field w-px-125" data-field="end_date" value="{$end_date}" max="{$smarty.now|date_format:'%Y-%m-%d'}" />
						</div>
					</div>
					{/if}
				</div>
				{if $deviceType eq 'phone'}
				<div class="search d-flex flex-wrap align-items-center mt-2">
					<div class="input-group w-100 mb-1">
						<select onchange="$Core.report.do_search(this,event)" data-field="year" 
							class="form-control js__search-year-field search_field form-select">
							{foreach from=$list_years item=_year}
							<option{if $Current_Year eq $_year} selected{/if} value="{$_year}">Năm {$_year}</option>
							{/foreach}
						</select>
						<select onchange="$Core.report.do_search(this,event)" data-field="month" 
							class="form-control search_field js__search-month-field form-select">
							{foreach from=$list_months item=_month}
							<option{if $Current_Month eq $_month} selected{/if} value="{$_month}">Tháng {$_month}</option>
							{/foreach}
						</select>
						<select onchange="$Core.report.do_search(this,event)" data-field="date_type" 
							class="form-control search_field js__search-date_type-field form-select">
							<option value="yesterday">Hôm qua</option>
							<option value="7days">7 ngày qua</option>
							<option value="15days">15 ngày qua</option>
							<option value="30days">30 ngày qua</option>
						</select>
					</div>
					<div class="d-flex align-items-center gap-1 mb-2 w-100">
						<select onchange="$Core.report.do_search(this,event)" data-field="department_id" 
							class="form-control js__search-department-field flex-fill search_field form-select">
							<option>Tất cả</option>
							{if !empty($list_sale_departments)}
								{foreach from=$list_sale_departments item = _oG}
								<option value="{$_oG.property_id}">{$_oG.title}</option>
								{/foreach}
							{/if}
						</select>
						<div class="input-group flex-fill flex-nowrap">
							<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-start_date-field 
							js__search-date-field search_field w-px-125" data-field="start_date" value="{$start_date}" />
							<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-end_date-field 
							js__search-date-field search_field w-px-125" data-field="end_date" value="{$end_date}" max="this.max=new Date().toISOString().split('T')[0]"/>
						</div>
					</div>
				</div>
				{/if}
			</form>
			<div class="card mb-2">
				<div class="card-body">
					<div class="table-container no-shadow overflow-x-auto text-nowrap">
						<table cellpadding="0" cellspacing="0" class="table table-bordered" width="100%">
							<thead><tr>
								{if $deviceType ne "phone"}
								<th width="6px" class="align-center text-left">STT</th>
								{/if}
								<th class="align-center bg-lighter h-px-35 text-left">Họ và tên</th>
								<th class="align-center bg-lighter h-px-35 text-center" width="150px">Phòng ban</th>
								<th class="align-center bg-lighter h-px-35 text-center" width="120px">Truy cập</th>
								<th class="align-center bg-lighter h-px-35 text-center" width="120px">Tra cứu</th>
							</tr></thead>
							<tbody class="holder_reports_login">
								{section name=i loop=$list_preloaders max=25}
								<tr>
									{if $deviceType ne "phone"}
									<td class="text-center">{$smarty.section.i.iteration}</td>
									{/if}
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
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
	@media screen and (max-width:767px) {
		.table-iloocal thead tr th, .table-iloocal tbody tr td {
			padding: 5px;
		}
	}
</style>
<script type="text/javascript">
	$(function(){
		$Core.report.load_reports_login({});
	});
</script>
{/literal}