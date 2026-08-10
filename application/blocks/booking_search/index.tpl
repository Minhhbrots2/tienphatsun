{assign var = uid value = $clsISO->getUniqid()}
<div class="dropdown-header">Tìm kiếm</div>
<div class="px-3 pt-1 pb-3">
	{if $deviceType eq 'phone'}
	<div class="form-group mb-2">
		<label class="form-label mb-1">Từ khóa</label>
		<input type="text" class="form-control search_field" placeholder="Nhập từ khóa tìm kiếm..." 
			onChange="$Core.booking.do_search(this, event)" data-field="keysearch" />
	</div>
	<div class="form-group mb-2">
		<label class="form-label mb-1">Tình trạng</label>
		<select class="iso-selectize search_field w-100" uid="{$uid}" call_from="search" 
		placeholder="Tình trạng" name="status_id" data-field="status_id">
			<option value="">Tình trạng</option>
			{$clsProperty->getSelectByProperty('_BOOKING_STATUS',0, "Tình trạng")}
		</select>
	</div>
	{if $mod == 'booking' && $act == 'default'}
	<div class="form-group mb-2">
		<label class="form-label mb-1">Phòng ban</label>
		<select data-bind="change" class="iso-selectize search_field w-100" uid="{$uid}" call_from="search" 
		placeholder="Phòng ban" onChange="$Core.booking.handle_dep_change(this, event)" name="department_id" data-field="department_id">
			<option value="0">Phòng ban</option>
			{$clsProperty->getSelectByProperty('_DEPARTMENT', $oneBooking.department_id, "Phòng ban")}
		</select>
	</div>
	<div class="form-group mb-2">
		<label class="form-label mb-1">Nhân viên</label>
		<select class="iso-selectizeImageSync search_field w-100" placeholder="Nhân viên" name="staff_id" data-field="staff_id">
			<option value="0">Nhân viên</option>
		</select>
	</div>
	{/if}
	{else}
	<div class="form-group form-row mb-2">
		<div class="col-5">
			<label class="form-label mb-1">Từ khóa</label>
			<input type="text" class="form-control search_field" placeholder="Nhập từ khóa tìm kiếm..." 
				onChange="$Core.booking.do_search(this, event)" data-field="keysearch" />
		</div>
		<div class="col-7">
			<label class="form-label mb-1">Tình trạng</label>
			<select class="iso-selectize search_field w-100" uid="{$uid}" 
			call_from="search" placeholder="Tình trạng" name="status_id" data-field="status_id">
				<option value="">Tình trạng</option>
				{$clsProperty->getSelectByProperty('_BOOKING_STATUS',0, "Tình trạng")}
			</select>
		</div>
	</div>
	{if $mod == 'booking' && $act == 'default'}
	<div class="form-group form-row mb-2">
		<div class="col-5">
			<label class="form-label mb-1">Phòng ban</label>
			<select data-bind="change" class="iso-selectize search_field w-100" uid="{$uid}" call_from="search" 
			placeholder="Phòng ban" onChange="$Core.booking.handle_dep_change(this, event)" name="department_id" data-field="department_id">
				<option value="">Phòng ban</option>
				{$clsProperty->getSelectByProperty('_DEPARTMENT', 0, "Phòng ban")}
			</select>
		</div>
		<div class="col-7">
			<label class="form-label mb-1">Nhân viên</label>
			<select class="iso-selectizeImageSync search_field w-100" placeholder="Nhân viên" name="staff_id" data-field="staff_id">
				<option value="">Nhân viên</option>
			</select>
		</div>
	</div>
	{/if}
	{/if}
	<div class="form-group mb-2">
		<label class="form-label mb-1">Dự án</label>
		<select placeholder="Chọn dự án" class="iso-selectize search_field w-100" uid="{$uid}" name="project_id" 
		data-field="project_id" onChange="$Core.booking.load_block(this, event)" call_from="search" block_id="{$get_block_id}" >
			<option value="">Dự án</option>
			{if !empty($arr_projects)}
				{foreach from=$arr_projects item = _oProject}
				<option{if $get_project_id eq $_oProject.project_id} selected{/if} value="{$_oProject.project_id}">{$_oProject.project_name}</option>
				{/foreach}
			{/if}
		</select>
	</div>
	{if $deviceType eq 'phone'}
	<div class="form-group mb-2">
		<label class="form-label mb-1">Phân khu/Block</label>
		<div class="slb_block_{$uid}">
			<select placeholder="Phân khu" class="form-control search_field iso-selectize w-100" uid="{$uid}" data-field="block_id" 
			onChange="$Core.booking.load_building(this, event)" name="block_id" call_from="search" data-optgroup="false">
				{if !empty($arr_projects)}
					{foreach from=$arr_projects item = _oBlock}
					<option{if $get_block_id eq $_oBlock.block_id} selected{/if} value="{$_oBlock.block_id}">{$_oBlock.block_name}</option>
					{/foreach}
				{/if}
			</select>
		</div>
	</div>
	<div class="form-group mb-2">
		<label class="form-label mb-1">Tòa nhà</label>
		<div class="slb_building_{$uid}">
			<select placeholder="Tòa nhà" class="form-control search_field iso-selectize w-100" uid="{$uid}" 
			data-optgroup="false" data-field="building_id" call_from="search">
				<option value="0">Tòa nhà</option>
			</select>
		</div>
	</div>
	{else}
	<div class="form-group form-row mb-2">
		<div class="col-6">
			<label class="form-label mb-1">Phân khu/Block</label>
			<div class="slb_block_{$uid}">
				<select placeholder="Phân khu" class="form-control search_field iso-selectize w-100" uid="{$uid}" data-field="block_id" 
				onChange="$Core.booking.load_building(this, event)" name="block_id" call_from="search" data-optgroup="false">
					{if !empty($arr_projects)}
						{foreach from=$arr_projects item = _oBlock}
						<option{if $get_block_id eq $_oBlock.block_id} selected{/if} value="{$_oBlock.block_id}">{$_oBlock.block_name}</option>
						{/foreach}
					{/if}
				</select>
			</div>
		</div>
		<div class="col-6">
			<label class="form-label mb-1">Tòa nhà</label>
			<div class="slb_building_{$uid}">
				<select placeholder="Tòa nhà" class="form-control search_field iso-selectize w-100" uid="{$uid}" 
				data-optgroup="false" data-field="building_id" call_from="search">
					<option value="0">Tòa nhà</option>
				</select>
			</div>
		</div>
	</div>
	{/if}
	<div class="form-group form-row">
		{*<div class="col-6">
			<label class="form-label mb-1">Thời gian</label>
			<div class="clearfix"></div>
			<div class="input-group-date w-100">
				<input type="text" class="form-control isodaterangepicker" />
			</div>
		</div>*}
		<div class="col-12">
			<label class="form-label mb-1">Trạng thái</label>
			<select class="iso-selectize search_field w-100" uid="{$uid}" placeholder="Trạng thái" 
				name="state_id" data-field="state_id">
				<option value="">Trạng thái</option>
				{$clsProperty->getSelectByProperty('_BOOKING_STATE', $get_state_id, "Trạng thái")}
			</select>
		</div>
	</div>
	<hr class="my-2" />
	<div class="d-flex align-items-center gap-2">
		<button type="button" class="btn btn-primary" onClick="$Core.booking.do_search(this, event)">
			<i class="bx bx-search"></i> Tìm kiếm</button>
	</div>
</div>