<div class="d-flex align-items-center gap-1 w-px-{$w_www}">
	<!-- <select onchange="$Core.report.do_search_group(this,event)" data-field="date_type" 
		class="form-control search_field js__search-date_type-field form-select">
		<option value="_month">Tháng</option>
		<option value="_quarter">Quý</option>
		<option value="_year">Năm</option>
	</select> -->
	<select onchange="$Core.report.do_search_group(this,event)" data-field="month" 
		class="form-control search_field js__search-month-field form-select">
		<option value="0">Chọn tháng</option>
		{foreach from=$list_months item=_month}
		<option{if $Current_Month eq $_month} selected{/if} value="{$_month}">Tháng {$_month}</option>
		{/foreach}
	</select>
	<select onchange="$Core.report.do_search_group(this,event)" data-field="year" 
		class="form-control js__search-year-field search_field form-select">
		{foreach from=$list_years item=_year}
		<option{if $Current_Year eq $_year} selected{/if} value="{$_year}">Năm {$_year}</option>
		{/foreach}
	</select>
	{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')}
	<select onchange="$Core.report.do_search_group(this,event)" data-field="department_id" 
		class="form-control iso-selectize js__search-department-field w-px-250 xs:w-px-175 xs:min-w-px-175 search_field">
		{if $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')}
		<option selected value="{$oneProfile.department_id}">{$oneProfile.department_name}</option>
		{else}
		<option value="">Chọn vùng / phòng kinh doanh</option>
		{/if}
		{foreach from=$list_departments item = _oDep}
		<option value="{$_oDep.property_id}">{$_oDep.title}</option>
			{if !empty($_oDep.children)}
				{foreach from=$_oDep.children item = _oChild}
				<option value="{$_oChild.property_id}">-- Phòng KD {$_oChild.title}</option>
				{/foreach}
			{/if}
		{/foreach}
	</select>
	{else}
	<input type="hidden" class="js__search-department-field search_field" 
		data-field="department_id" value="{$oneProfile.department_id}" />
	{/if}
</div>

