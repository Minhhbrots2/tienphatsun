{assign var = gId value = $clsISO->getUniqid()}
<button id="{$gId}" data-toggle="ripple" type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" data-bs-auto-close="outside" 
aria-haspopup="true" aria-expanded="true">
	<i class="bx bx-search"></i>
</button>
<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-menu-end w-px-350" 
	data-popper-placement="top-end">
	<div class="p-3">
		<div class="form-group mb-2">
			<label class="form-label mb-1">Tìm kiếm theo từ khóa</label>
			<input type="text" placeholder="Nhập từ khóa tìm kiếm" name="keyword" 
				class="form-control search_field search_keyword_VAT" onkeyup="$Core.commission.do_search(this, event)" />
		</div>
		<div class="form-group mb-2">
			<div class="form-label mb-1">Nhân viên</div>
			<select class="iso-selectizeNotSearch" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" 
				placeholder="Nhân viên" name="staff_id" data-optgroup="false">
				{if $staff_id gt '0'}
				<option value="{$staff_id}" selected="selected">{$clsProfile->getIndentity($staff_id, false)}</option>
				{/if}
			</select>				
		</div>
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<label class="form-label mb-1">TT từ CĐT</label>
				<select class="form-control search_field form-select" name="status_company_id" onchange="$Core.commission.do_search(this, event)">
					<option value="0">Tình trạng</option>
					{$clsProperty->getSelectByProperty('COMMISSION_PAYMENT_STATUS',0)}
				</select>
			</div>
			<div class="col-6">
				<label class="form-label mb-1">TT Sale</label>
				<select class="form-control search_field form-select" name="status_sale_id" onchange="$Core.commission.do_search(this, event)">
					<option value="0">Tình trạng</option>
					{$clsProperty->getSelectByProperty('COMMISSION_PAYMENT_STATUS',0)}
				</select>
			</div>
		</div>
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<label class="form-label mb-1">Từ ngày</label>
				<input type="date" class="form-control search_field" name="start_date" onchange="$Core.commission.do_search(this, event)" />
			</div>
			<div class="col-6">
				<label class="form-label mb-1">Tới ngày</label>
				<input type="date" class="form-control search_field" name="end_date" onchange="$Core.commission.do_search(this, event)" />
			</div>
		</div>
		<hr class="my-3" />
		<div class="form-group">
			<button gid="{$gId}" type="button" onclick="$Core.commission.toggle_search(this,event)" 
				class="btn btn-outline-primary">Tìm kiếm</button>
		</div>
	</div>
</div>
