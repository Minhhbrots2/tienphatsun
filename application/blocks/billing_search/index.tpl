<div class="p-3">
	{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ACCOUNTANT') || $clsISO->checkPermission('view_all_billing')}
	<div class="form-group form-row mb-2"> 
		<div class="col-12 col-md-4 mb-0 nb-lg-2">
			<div class="form-label mb-1">Phòng ban</div>
			<select class="iso-selectizeSync" onChange="$Core.billing.handle_dep_changed(this, event)" 
				data-width="100%" placeholder="Phòng ban" name="dept_id">
				{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$dept_id,'Phòng ban')}
			</select>
		</div>
		<div class="col-12 col-md-8">
			<div class="form-label mb-1">Nhân viên</div>
			<select class="iso-selectizeImageSearch" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" 
				placeholder="Nhân viên" name="staff_id" data-optgroup="false">
				{if $staff_id gt '0'}
				<option value="{$staff_id}" selected="selected">{$clsProfile->getIndentity($staff_id, false)}</option>
				{/if}
			</select>
		</div>
	</div>
	{elseif $clsISO->checkPermissionGroup('PROJECT_DIRECTOR')}
	<div class="form-group mb-2">
		<label class="form-label mb-1">Lọc theo GĐDA</label>
		{assign var = uid value = $clsISO->getUniqid()}
		<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
			<input type="radio" class="btn-check" onChange="$Core.billing.filter_by_changed(this, event)" name="filter_by" 
			id="TYPE_LIST_PROJECT_{$uid}" value="_project"{if $filter_by eq '_project'} data-bind="change" checked{/if}>
			<label class="btn text-nowrap btn-outline-default" for="TYPE_LIST_PROJECT_{$uid}">Dự án</label>
			<input type="radio" class="btn-check" onChange="$Core.billing.filter_by_changed(this, event)" name="filter_by" 
			id="TYPE_LIST_DEPT_{$uid}" value="_dept"{if $filter_by eq '_dept'} data-bind="change" checked{/if}>
			<label class="btn text-nowrap btn-outline-default" for="TYPE_LIST_DEPT_{$uid}">Phòng ban</label>
		</div>
	</div>
	<div class="form-group form-row mb-2">
		<div class="col-12 col-md-5 mb-2 mb-lg-0">
			<div class="form-label mb-1">Phòng ban</div>
			<select class="iso-selectizeNotSearch" onChange="$Core.billing.handle_dep_changed(this, event)" 
			placeholder="Chọn phòng ban" name="dept_id" staff_id="{$staff_id}" dept_id="{$dept_id}">
				<option value="0">Chọn phòng ban</option>
			</select>
		</div>
		<div class="col-12 col-md-7 mb-2 mb-lg-0">
			<label class="form-label mb-1">Nhân viên</label>
			<select class="form-control iso-selectizeSync" placeholder="Nhân viên" name="staff_id" staff_id="{$staff_id}">
				{if $staff_id gt '0'}
				<option value="{$staff_id}" selected="selected">{$clsProfile->getIndentity($staff_id, false)}</option>
				{/if}
			</select>
		</div>
	</div>
	<hr class="my-2" />
	{else if $clsISO->checkPermissionGroup('SALE_DIRECTOR')}
	<div class="form-group mb-2">
		<div class="form-label mb-1">Nhân viên</div>
		<select class="iso-selectizeNotSearch" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" 
			placeholder="Nhân viên" name="staff_id" data-optgroup="false">
			{if !empty($staff_id)}
			<option value="{$staff_id}" selected="selected">{$clsProfile->getIndentity($staff_id, false)}</option>
			{/if}
		</select>
	</div>
	{/if}
	<div class="form-row mb-2">
		<div class="col-6 col-md-6">
			<div class="form-floating">
				<select class="form-control" name="contract_status_id">
					<option value="0">Tình trạng HĐMB</option>
					{$clsProperty->getSelectByProperty('_STATUS_CONTRACT',$contract_status_id)}
				</select>
				<label for="floatingInput">Tình trạng</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			<div class="form-floating">
				<select class="form-control" name="billing_type">
					<option value="0">Loại hình</option>
					{$clsProperty->getSelectByProperty('_BILLING_TYPE',$billing_type)}
				</select>
				<label for="floatingInput">Loại hình</label>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<input type="date" class="form-control" name="start_date" id="{$uid}" placeholder="dd/mm/yy" 
				aria-describedby="{$uid}" value="{if !empty($start_date)}{$start_date|date_format:'%Y-%m-%d'}{/if}">
				<label for="{$uid}">Từ ngày</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<input type="date" class="form-control" name="to_date" id="{$uid}" placeholder="dd/mm/yy" 
				aria-describedby="{$uid}" value="{if !empty($to_date)}{$to_date|date_format:'%Y-%m-%d'}{/if}">
				<label for="{$uid}">Tới ngày</label>
			</div>
		</div>
	</div>
	<div class="form-group form-row mb-2">
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<select id="{$uid}" data-bind="change" name="project_id" project_id="{$project_id}" block_id="{$block_id}" 
					onChange="$Core.billing.load_block(this, event)" class="form-control w-100">
					<option value="0">Chọn dự án</option>
					{if !empty($lstProject)}
						{foreach from=$lstProject item=_oProject key=key name=i}
							<option value="{$_oProject.project_id}" {if $project_id eq $_oProject.project_id}selected{/if} >{$_oProject.title}</option>
						{/foreach}
					{/if}
				</select>
				<label for="{$uid}">Dự án</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<select id="{$uid}" name="block_id" class="form-control w-100">
					<option value="0">Tất cả</option>
				</select>
				<label for="{$uid}">Phân khu</label>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<select id="{$uid}" name="billing_source" class="form-control w-100">
					<option value="0">Tất cả</option>
					{$clsProperty->getSelectByProperty('BILLING_SOURCE',$billing_source)}
				</select>
				<label for="{$uid}">Nguồn quỹ</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-floating">
				<select id="{$uid}" name="sort_by" class="form-control w-100">
					<option{if $sort_by eq 'reg_date'} selected{/if} value="reg_date">Ngày tạo</option>
					<option{if $sort_by eq 'contract_date'} selected{/if} value="contract_date">Ngày ký HĐMB</option>
				</select>
				<label for="{$uid}">Sắp xếp theo</label>
			</div>
		</div>
	</div>
	<!-- <hr class="my-2" />
	<div class="form-row mb-2">
		<div class="col-6 col-md-6">
			<label class="form-label mb-1">Nộp Cọc cứng(PCC)</label>
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendpale" id="PILE_ALL_{$uid}" value="_all" autocomplete="off"{if $is_sendpale eq '_all'} checked{/if}>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="PILE_ALL_{$uid}">ALL</label>
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendpale" id="PILE_YES_{$uid}" value="1" autocomplete="off"{if $is_sendpale eq '1'} checked{/if}>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="PILE_YES_{$uid}">YES</label>
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendpale" id="PILE_NO_{$uid}" value="0" autocomplete="off"{if $is_sendpale eq '0'} checked{/if}>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="PILE_NO_{$uid}">NO</label>
			</div>
		</div>
		<div class="col-6 col-md-6"> 
			<label class="form-label mb-1">Gửi email</label>
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendemail" id="SENDEMAIL_ALL_{$uid}" value="_all" autocomplete="off"{if $is_sendemail eq '_all'} checked{/if}>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="SENDEMAIL_ALL_{$uid}">All</label>
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendemail" id="SENDEMAIL_YES_{$uid}" value="1" autocomplete="off"{if $is_sendemail eq '1'} checked{/if}>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="SENDEMAIL_YES_{$uid}">YES</label>
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendemail" id="SENDEMAIL_NO_{$uid}" value="0" autocomplete="off"{if $is_sendemail eq '0'} checked{/if}>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="SENDEMAIL_NO_{$uid}">NO</label>
			</div>
		</div>
	</div> -->
	<hr class="my-3" />
	<input type="hidden" name="filter" value="filter" />
	<div class="d-flex align-items-center justify-content-between">
		<div class="d-flex align-items-center gap-2">
			<button type="submit" class="btn btn-primary">{$clsISO->makeIcon('bx-search', 'Áp dụng')}</button>
			<button type="reset"  class="btn btn-warning">{$clsISO->makeIcon('bx-refresh', 'Xóa')}</button>
		</div>
		{if $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkDEV()}
		<div class="form-check form-switch cursor-pointer">
			<input type="checkbox" class="form-check-input w-px-50 me-2" name="is_action_visible" 
				onChange="$Core.billing.set_action_visible(this, event)"{if $billing_configs.is_action_visible eq '1'} checked{/if} value="1">
			<label class="form-check-label">{if $billing_configs.is_action_visible eq '1'}Ẩn{else}Thêm{/if} công cụ</label>
		</div>
		{/if}
	</div>
</div>
