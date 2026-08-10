{if $deviceType eq 'phone'}
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
			js__search-date-field search_field w-px-125" data-field="end_date" value="{$end_date}" />
		</div>
	</div>
{else}
	{if $clsISO->checkPermissionGroup('DIRECTOR')}
	<select onchange="$Core.report.do_search(this,event)" data-field="department_id" 
		class="form-control js__search-department-field search_field w-px-100 form-select">
		<option>Tất cả</option>
		{if !empty($list_sale_departments)}
			{foreach from=$list_sale_departments item = _oG}
			<option value="{$_oG.property_id}">{$_oG.title}</option>
			{/foreach}
		{/if}
	</select>
	{/if}
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
			<option value="yesterday">Hôm qua</option>
			<option value="7days">7 ngày qua</option>
			<option value="15days">15 ngày qua</option>
			<option value="30days">30 ngày qua</option>
		</select>
	</div>
	<div class="input-group">
		<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-start_date-field 
		js__search-date-field search_field w-px-125" data-field="start_date" value="{$start_date}" />
		<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-end_date-field 
		js__search-date-field search_field w-px-125" data-field="end_date" value="{$end_date}" />
	</div>
	<button type="button" title="Thêm báo cáo" onClick="$Core.report.open(this, event)" report_id="0" 
		class="btn{if $is_send_report_today eq '1' || !$clsReport->check_time_send_report()} disabled{/if} text-nowrap btn-outline-danger">+ Báo cáo</button>
{/if}