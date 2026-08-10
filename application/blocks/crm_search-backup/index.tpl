<div class="p-3">
	<form name="frmIssue" method="POST" onSubmit="return false;">
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<div class="form-label mb-1">Tìm theo từ khóa</div>
				<div class="input-group input-group-merge mr-2">
					<span class="input-group-text"><i class="bx bx-search"></i></span>
					<input class="form-control search_crm_field search_crm_keyword_field {$crm_field}" name="keysearch" data-field="keysearch" 
					holderG="{$holderG}" onClick="this.select();" value="{$keysearch}" placeholder="Nhập từ khóa & enter để tìm kiếm...">
				</div>
			</div>
			<div class="col-6">
				<div class="form-label mb-1">Được tạo</div>
				<select name="reg_date_range" holderG="{$holderG}" class="form-control {$crm_field} form-select" data-field="reg_date_range" onchange="$Core.crm.do_search(this, event)">
					<option value="0">Lựa chọn</option>
					{foreach from=$list_date_ranges key = _OR item = _OT}
					<option value="{$_OR}">{$_OT}</option>
					{/foreach}
				</select>
				<input type="hidden" class="form-control {$crm_field}" name="reg_date" data-field="reg_date" holderG="{$holderG}" placeholder="dd/mm/yyyy" onchange="$Core.crm.do_search(this, event)">
			</div>
		</div>
		{if $holderG ne '_report' && $clsISO->checkPermissionGroup('DIRECTOR')}
			<div class="form-group mb-2">
				<div class="form-label mb-1">Nhóm nhân viên</div>
				<select class="iso-selectizeNotSearch {$crm_field} required" holderG="{$holderG}" data-field="group_id" onchange="$Core.crm.do_search(this, event)" data-width="100%" data-placeholder="Nhóm nhân viên" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staffs_group" data-width="100%"></select>
			</div>
			<div class="form-group mb-2">
				<div class="form-label mb-1">Người quản lý</div>
				<select class="iso-selectizeNotSearch {$crm_field} required" holderG="{$holderG}" data-field="admin_id" onchange="$Core.crm.do_search(this, event)" data-width="100%" data-placeholder="Người quản lý" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" data-width="100%"></select>
			</div>
		{/if}
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<div class="form-label mb-1">Tình trạng</div>
				<select class="form-control search_crm_status_field {$crm_field} form-select" data-width="100%" holderG="{$holderG}" onchange="$Core.crm.do_search(this, event)" data-placeholder="Tình trạng" data-width="100%" data-allow-clear="true" data-field="status_id">
					<option value="0">Tình trạng</option>
					{$clsProperty->getSelectByProperty('CUSTOMER_STATUS',0, "", true)}
				</select>	
			</div>
			<div class="col-6">
				<div class="form-label mb-1">Nguồn gốc</div>
				<select class="form-control {$crm_field} form-select" data-width="100%" holderG="{$holderG}" onchange="$Core.crm.do_search(this, event)" data-placeholder="Nguồn gốc" data-width="100%" data-allow-clear="true" data-field="resource_id">
					<option value="0">Nguồn gốc</option>
					{$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES',0, "", true)}
				</select>	
			</div>
		</div>
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<div class="form-label mb-1">Chiến dịch</div>
				<select name="campaign_id" data-placeholder="Chiến dịch" class="form-control {$crm_field} iso-select2" data-width="100%" 
				data-allow-clear="true" holderG="{$holderG}" onchange="$Core.crm.do_search(this,event)" data-field="campaign_id">
					<option value="0">Chiến dịch</option>
					{if !empty($list_campaigns)}
						{foreach from=$list_campaigns item = _oCampaign}
						<option value="{$_oCampaign.campaign_id}">{$_oCampaign.title}</option>
						{/foreach}
					{/if}
				</select>
			</div>
			<div class="col-6">
				<div class="form-label mb-1">Loại hình</div>
				<select class="form-control {$crm_field} form-select" data-width="100%" holderG="{$holderG}" onchange="$Core.crm.do_search(this, event)" data-placeholder="Nguồn gốc" data-width="100%" data-allow-clear="true" data-field="blocktype_id">
					<option value="0">Nguồn gốc</option>
					{$clsProperty->getSelectByProperty('_BLOCK_TYPE', 0, "", true)}
				</select>	
			</div>
		</div>
		<hr class="my-2" />
		<div class="form-check form-switch cursor-pointer">
			<input type="checkbox" class="form-check-input sOAKixBMcX" id="sOAKixBMcX"{if isset($oneProfile.more_information.crm_view_all) && $oneProfile.more_information.crm_view_all eq '1'} checked{/if} value="1" onchange="$Core.crm.set_all(this,event)">
			<label class="form-check-label" for="sOAKixBMcX">Hiện thị tất cả khách hàng</label>
		</div>
		<hr class="my-2" />
		<div class="form-group">
			<button type="button" holderG="{$holderG}" gId="{$gId}" onClick="$Core.crm.scrollToElem(this, event); $Core.crm.do_search(this, event)" 
			class="btn btn-outline-success do_crm_search">Tìm kiếm</button>
			<button type="button" onClick="$Core.crm.reset_search(this, event)" class="btn btn-outline-default">Xóa</button>
		</div>
	</form>
</div>