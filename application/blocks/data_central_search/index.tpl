{if $deviceType eq 'computer'}
<div class=" bg-white rounded-2 d-flex align-items-center gap-1">
	<div class="w-px-150">
		<select class="form-control search_field multiselect" onChange="$Core.data_central.select_block(this, event)"
			name="project_id" id="slb_Project_Id" toId="slb_Block_Id" data-width="100%" data-field="project_id" data-placeholder="Dự án">
			<option value="0">Dự án</option>
			{foreach name=i from=$list_projects item=project}
			<option value="{$project.project_id}">{$project.code}</option>
			{/foreach}
		</select>
	</div>
	<div class="w-px-140">
		<select class="form-control search_field multiselect" onChange="$Core.data_central.select_building(this, event)" data-placeholder="Phân khu" data-width="100%" data-header="true" data-filter="true" multiple id="slb_Block_Id" toId="slb_Building_Id" data-field="blocks_ids[]">
			{if !empty($list_ss_blocks)}
				{foreach name=i from=$list_ss_blocks item=block}
				<option{if $clsISO->checkInArray($_ss_blocks_ids, $block.property_id)} selected{/if} value="{$block.property_id}">{$block.title}</option>
				{/foreach}
			{/if}
		</select>
	</div>
	<div class="w-px-140">
		<select class="form-control search_field multiselect" data-placeholder="Tòa căn hộ" data-width="100%" data-header="true" data-filter="true" onChange="$Core.data_central.do_search(this, event)" toId="fund_type_search" multiple id="slb_Building_Id" data-field="building_ids[]">
			{if !empty($_ss_blocks_ids)}
				{foreach from=$list_ss_buildings key = block_id item = list_buildings}
				<optgroup label="{$clsProperty->getTitle($block_id)}">
					{if !empty($list_buildings)}
						{foreach name=i from=$list_buildings item=building}
						<option{if $clsISO->checkInArray($_ss_building_ids,$building.property_id)} selected{/if} value="{$building.property_id}">{$building.title}</option>
						{/foreach}
					{/if}
				</optgroup>
				{/foreach}
			{/if}
		</select>
	</div>
	<div class="search-block w-full d-flex align-item-center">
		<div class="input-group">
			<div class="input-group input-group-merge w-px-150">
				<span class="input-group-text"><i class="bx bx-search"></i></span>
				<input type="text" name="keyword" data-field="keyword" value="{$keyword}" onChange="$Core.data_central.do_search(this, event)" 
					class="form-control search_field no-radius-right" placeholder="Nhập từ khoá">
			</div>
		</div>
		<div class="dropdown">
			<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow no-radius-left border-left-0" data-bs-toggle="dropdown" 
				data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
			<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-400" data-popper-placement="top-end">
				<div class="p-3"><div class="form-group form-row mb-2">
					<div class="col-6 col-md-6">
						{assign var = uid value = $clsISO->getUniqid()}
						<div class="form-floating">
							<input type="date" class="form-control search_field" name="birthday" data-field="birthday" id="{$uid}" 
							placeholder="dd/mm/yy" max="{$smarty.now|date_format:'%Y-%m-%d'}">
							<label for="{$uid}">Ngày sinh</label>
						</div>
					</div>
					<div class="col-6 col-md-6">
						{assign var = uid value = $clsISO->getUniqid()}
						<div class="form-floating">
							<input type="date" class="form-control search_field" name="date_call" data-field="date_call" 
								id="{$uid}" placeholder="dd/mm/yy" max="{$smarty.now|date_format:'%Y-%m-%d'}">
							<label for="{$uid}">Liên hệ</label>
						</div>
					</div>
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-6 col-md-6">
						{assign var = uid value = $clsISO->getUniqid()}
						<div class="form-floating form-floating-multiselect">
							<select class="form-select form-control search_field" name="status_id" data-width="100%" data-field="status_id">
								<option value="">Tình trạng</option>
								<option {if $status_id eq "1"} selected{/if} value="1">Đã liên hệ</option>
								<option {if $status_id eq "0"} selected{/if} value="0">Chưa liên hệ</option>
							</select>
							<label for="{$uid}">Tình trạng</label>
						</div>
					</div>
					<div class="col-6 col-md-6 box_search_tag">
						{assign var = uid value = $clsISO->getUniqid()}
						<div class="form-floating form-floating-multiselect">
							<select class="form-control search_field multiselect" name="tags" onChange="$Core.data_central.do_search(this, event)" data-placeholder="Tags" data-width="100%" data-header="true" data-filter="true" multiple data-field="tags[]">
							{if !empty($list_tags)}
								{foreach from=$list_tags item=_oTag}
								<option value="{$_oTag.tag_id}"{if $clsISO->checkInArray($_ss_tag_ids,$_oTag.tag_id)}selected{/if} >{$_oTag.title}</option>
								{/foreach}
							{/if}
							</select>
							<label for="{$uid}">Tags</label>
						</div>
					</div>
				</div>
				<hr class="my-3" />
				<div class="d-flex align-items-center gap-2">
					<button type="button" onClick="$Core.data_central.do_search(this, event)" class="btn btn-primary">
						{$clsISO->makeIcon('bx-search', 'Áp dụng')}
					</button>
					<button type="button" class="btn btn-warning" onClick="$Core.data_central.resetForm(this,event)">
						{$clsISO->makeIcon('bx-refresh', 'Xóa')}
					</button>
				</div></div>
			</div> 
		</div>
	</div>
	{if $clsISO->checkPermission("import_data_central")}
	<button type="button" class="btn btn-icon btn-default" onclick="$Core.data_central.open_import(this, event)" 
		title="Cập nhật dữ liệu"><i class="fa fa-upload"></i>
	</button>			
	{/if}
</div>
{else}
<div class="p-2">
	<div class="form-group form-row mb-2">
		<div class="col-12">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<input type="text" name="keyword" data-field="keyword" value="{$keyword}" 
					class="form-control search_field" placeholder="Nhập từ khoá & nhấn Enter..." />
				<label for="{$uid}">Từ khóa</label>
			</div>
		</div>
	</div>
	<div class="form-group form-row mb-2">
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating form-floating-multiselect">
				<select class="form-control search_field multiselect" onChange="$Core.data_central.select_block(this, event)" 
				name="project_id" id="slb_Project_Id" toId="slb_Block_Id" data-width="100%" data-field="project_id">
					{foreach name=i from=$list_projects item=project}
						<option{if $project.project_id eq $_ss_project_id} selected{/if} value="{$project.project_id}">{$project.code}</option>
					{/foreach}
				</select>
				<label for="{$uid}">Dự án</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating form-floating-multiselect">
				<select class="form-control search_field multiselect" onChange="$Core.data_central.select_building(this, event)" data-placeholder="Phân khu" data-width="100%" data-header="true" data-filter="true" multiple id="slb_Block_Id" toId="slb_Building_Id" data-field="blocks_ids[]">
					{if !empty($list_ss_blocks)}
						{foreach name=i from=$list_ss_blocks item=block}
						<option{if $clsISO->checkInArray($_ss_blocks_ids, $block.property_id)} selected{/if} value="{$block.property_id}">{$block.title}</option>
						{/foreach}
					{/if}
				</select>
				<label for="{$uid}">Phân khu</label>
			</div>
		</div>
	</div>
	<div class="form-group form-row mb-2">
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating form-floating-multiselect">
				<select class="form-control search_field multiselect" data-placeholder="Tòa căn hộ" data-width="100%" data-header="true" 
					data-filter="true" toId="fund_type_search" multiple id="slb_Building_Id" data-field="building_ids[]">
				{if !empty($_ss_blocks_ids)}
					{foreach from=$list_ss_buildings key = block_id item = list_buildings}
					<optgroup label="{$clsProperty->getTitle($block_id)}">
						{if !empty($list_buildings)}
							{foreach name=i from=$list_buildings item=building}
							<option{if $clsISO->checkInArray($_ss_building_ids,$building.property_id)} selected{/if} value="{$building.property_id}">{$building.title}</option>
							{/foreach}
						{/if}
					</optgroup>
					{/foreach}
				{/if}
				</select>
				<label for="{$uid}">Tòa/Dãy</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<input type="date" class="form-control search_field" name="birthday" data-field="birthday" id="{$uid}" 
					placeholder="dd/mm/yy" max="{$smarty.now|date_format:'%Y-%m-%d'}">
				<label for="{$uid}">Ngày sinh</label>
			</div>
		</div>
	</div>
	<div class="form-group form-row mb-2">
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<input type="date" class="form-control search_field" name="date_call" data-field="date_call" id="{$uid}" 
					placeholder="dd/mm/yy" max="{$smarty.now|date_format:'%Y-%m-%d'}">
				<label for="{$uid}">Liên hệ</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating form-floating-multiselect">
				<select class="form-select form-control search_field" name="status_id" data-width="100%" data-field="status_id">
					<option value="">Tình trạng</option>
					<option {if $status_id eq "1"} selected{/if} value="1">Đã liên hệ</option>
					<option {if $status_id eq "0"} selected{/if} value="0">Chưa liên hệ</option>
				</select>
				<label for="{$uid}">Tình trạng</label>
			</div>
		</div>
	</div>
	<div class="form-group form-row mb-2">
		<div class="col-6 col-md-6 box_search_tag">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating form-floating-multiselect">
				<select class="form-control search_field multiselect" name="tags" onChange="$Core.data_central.do_search(this, event)" 
					data-placeholder="Tags" data-width="100%" data-header="true" data-filter="true" multiple data-field="tags[]">
				{if !empty($list_tags)}
					{foreach from=$list_tags item=_oTag}
					<option value="{$_oTag.tag_id}" {if $clsISO->checkInArray($_ss_tag_ids,$_oTag.tag_id)}selected{/if} >{$_oTag.title}</option>
					{/foreach}
				{/if}
				</select>
				<label for="{$uid}">Tags</label>
			</div>
		</div>
	</div>
	<hr class="my-3" />
	<div class="d-flex align-items-center justify-content-end gap-2">
		<button type="button" onClick="$Core.data_central.do_search(this, event)" 
			class="btn btn-primary">{$clsISO->makeIcon('bx-search', 'Áp dụng')}</button>
		<button type="button"  class="btn btn-warning" onClick="$Core.data_central.resetForm(this,event)">
			{$clsISO->makeIcon('bx-refresh', 'Xóa')}
		</button>
	</div>
</div>
{/if}