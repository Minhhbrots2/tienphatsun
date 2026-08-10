{if $deviceType eq 'phone'}
	<div class="d-flex align-items-center gap-1 mb-1 w-100">
		<select data-field="group_id" onchange="$Core.report.do_group_search(this,event)" 
			class="form-control js__search-group-field search_field form-select">
			{if !empty($list_groups)}
				{foreach from=$list_groups item = _oG}
				<option{if $smarty.const._PROFILE_DEFAULT_GROUP_ID eq $_oG.group_profile_id} selected{/if} value="{$_oG.group_profile_id }">{$_oG.title}</option>
				{/foreach}
			{/if}
		</select>
		<select onchange="$Core.report.do_group_search(this,event)" data-field="year" 
			class="form-control js__search-year-field search_field form-select">
			{foreach from=$list_years item=_year}
			<option{if $Current_Year eq $_year} selected{/if} value="{$_year}">Năm {$_year}</option>
			{/foreach}
		</select>
		<select onchange="$Core.report.do_group_search(this,event)" data-field="month" 
			class="form-control search_field js__search-month-field form-select">
			{foreach from=$list_months item=_month}
			<option{if $Current_Month eq $_month} selected{/if} value="{$_month}">Tháng {$_month}</option>
			{/foreach}
		</select>
	</div>
	<div class="d-flex align-items-center gap-1 mb-2 w-100">
		<select onchange="$Core.report.do_group_search(this,event)" data-field="date_type" 
			class="form-control search_field js__search-date_type-field form-select">
			<option value="today">Hôm nay</option>
			<option value="yesterday">Hôm qua</option>
			<option value="7days" selected>7 ngày qua</option>
			<option value="15days">15 ngày qua</option>
			<option value="30days">30 ngày qua</option>
		</select>
		<div class="input-group w-px-250">
			<input type="hidden" data-field="call_from" class="search_field" value="_report_group" />
			<input type="date" onchange="$Core.report.do_group_search(this,event)" class="form-control js__search-start_date-field 
			js__search-date-field search_field w-px-125" data-field="start_date" value="{$start_date}" />
			<input type="date" onchange="$Core.report.do_group_search(this,event)" class="form-control js__search-end_date-field 
			js__search-date-field search_field w-px-125" data-field="end_date" value="{$end_date}" />
		</div>
	</div>
{else}
	<select data-field="group_id" class="form-control js__search-group-field search_field form-select" 
		onchange="$Core.report.loadStaff(this,event)" toId="sltStaffID">
		{if !empty($list_groups)}
			{foreach from=$list_groups item = _oG}
			<option{if $smarty.const._PROFILE_DEFAULT_GROUP_ID eq $_oG.group_profile_id} selected{/if} 
				value="{$_oG.group_profile_id }">{$_oG.title}</option>
			{/foreach}
		{/if}
	</select>
	<!-- <select data-field="staff_id" class="form-control js__search-group-field search_field form-select" 
		onchange="$Core.report.do_group_search(this,event)" id="sltStaffID">
			<option value="0">Chọn thành viên</option>
			{if !empty($list_staffs)}
				{foreach from=$list_staffs item = _oStaff}
				<option value="{$_oStaff.profile_id }">{$_oStaff.full_name}</option>
				{/foreach}
			{/if}
		</select>
		<div class="input-group w-px-400">
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->_DEV()}
			<div class="form-group w-px-150">
				<select class="form-control iso-select2 search_field search_global" data-width="100%" name="department_id" 
					data-field="department_id" onchange="$Core.report.do_group_search(this, event)">
					<option value="0">Tất cả</option>
					{$clsProperty->getListOption("_DEPARTMENT",{$oneProfile.department_id},$smarty.const._DEPARTMENT_SALE_ID)}
				</select>
			</div>
		{else}
			<input type="hidden" class="search_global" name="department_id"  data-field="department_id" value="{$oneProfile.department_id}">
		{/if} 
	</div>-->
	<select onchange="$Core.report.do_group_search(this,event)" data-field="month" 
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
	<select onchange="$Core.report.do_group_search(this,event)" data-field="date_type" 
		class="form-control search_field js__search-date_type-field form-select">
		<option value="0">Chọn nhanh</option>
		<option value="today">Hôm nay</option>
		<option value="yesterday">Hôm qua</option>
		<option value="7days">7 ngày qua</option>
		<option value="15days">15 ngày qua</option>
		<option value="30days" selected>30 ngày qua</option>
	</select>
	<div class="input-group w-px-250">
		<input type="date" onchange="$Core.report.do_group_search(this,event)" class="form-control js__search-start_date-field 
		js__search-date-field search_field w-px-125" data-field="start_date" value="{$start_date}" />
		<input type="hidden" data-field="call_from" class="search_field" value="_report_group" />
		<input type="date" onchange="$Core.report.do_group_search(this,event)" class="form-control js__search-end_date-field 
		js__search-date-field search_field w-px-125" data-field="end_date" value="{$end_date}" />
	</div>
{/if}