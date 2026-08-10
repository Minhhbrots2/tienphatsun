<form id="frmIssue" method="POST" onSubmit="return false;">
	<div class="form-group mb-2">
		<div class="form-label mb-1">Tìm theo từ khóa</div>
		<div class="input-group input-group-merge mr-2">
			<span class="input-group-text"><i class="bx bx-search"></i></span>
			<input class="form-control search_keyword_field search_field" name="keysearch" 
				data-field="keysearch" onClick="this.select();" placeholder="Nhập từ khóa & enter để tìm kiếm...">
		</div>
	</div>
	<div class="form-group mb-2">	
		<div class="form-label mb-1">Tình trạng</div>
		<select class="form-control search_field iso-select2" 
			onchange="$Core.stock_hug.do_search(this, event)" multiple="true" data-width="100%"
				data-allow-clear="true" data-field="status_id[]" name="status_id">
			{$clsProperty->getSelectByProperty('_STATUS_STOCK_HUG', $smarty.const._STOCK_HUG_STATUS_DEF_ID)}
		</select>	
	</div>
	{if $deviceType eq 'phone'}
	<div class="form-group mb-2">
		<label class="form-label mb-1">Dự án</label>
		<select placeholder="Chọn dự án" class="iso-selectize search_field w-100" uid="{$uid}" 
			name="project_id" data-field="project_id" call_from="search">
			<option value="0">Dự án</option>
			{if !empty($arr_projects)}
				{foreach from=$arr_projects item = _oProject}
				<option{if $get_project_id eq $_oProject.setting_id} selected{/if} value="{$_oProject.setting_id}">{$_oProject.title}</option>
				{/foreach}
			{/if}
		</select>
	</div>
	{/if}
	<hr class="my-2" />
	<div class="form-row mb-2">
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<input type="date" class="form-control search_field" id="{$uid}" data-field="start_date" placeholder="dd/mm/yy" aria-describedby="{$uid}" />
				<label for="{$uid}">Từ ngày</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<input type="date" class="form-control search_field" data-field="to_date" id="{$uid}" placeholder="dd/mm/yy" aria-describedby="{$uid}" onchange="$Core.stock_hug.do_search(this, event)">
				<label for="{$uid}">Tới ngày</label>
			</div>
		</div>
	</div>
	<hr class="my-2" />
	<div class="form-group">
		<button gId="{$gId}" type="button" onClick="$Core.stock_hug.toggle_search(this, event)" class="btn btn-outline-primary">Tìm kiếm</button>
		<button type="reset" onClick="$Core.stock_hug.do_search(this, event)" holderG="_reset" class="btn btn-warning">{$clsISO->makeIcon('bx-refresh', 'Xóa')}</button>
	</div>
</form>