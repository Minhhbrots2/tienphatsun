<div class="p-3">
	<form name="frmIssue" method="POST" onSubmit="return false;">
		<input type="hidden" class="{$crm_field}" name="staff_id" data-field="staff_id" value="{$staff_id}">
		<div class="form-group mb-2">
			<div class="form-label mb-1">Tìm theo từ khóa</div>
			<div class="input-group input-group-merge mr-2">
				<span class="input-group-text"><i class="bx bx-search"></i></span>
				<input type="text" class="form-control search_crm_field search_crm_keyword_field {$crm_field}" name="keysearch" 
				data-field="keysearch" holderG="{$holderG}" onClick="this.select();" value="{$keysearch}" placeholder="Nhập từ khóa & enter để tìm kiếm...">
			</div>
		</div>
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<div class="form-label mb-1">Được tạo</div>
				<select name="reg_date_range" holderG="{$holderG}" class="form-control {$crm_field} form-select" data-field="reg_date_range" onchange="$Core.crm.do_search(this, event)">
					<option value="0">Tất cả</option>
					{foreach from=$list_date_ranges key = _OR item = _OT}
					<option value="{$_OR}">{$_OT}</option>
					{/foreach}
				</select>
			</div>
			<div class="col-6">
				<div class="form-label mb-1">Đã chăm sóc</div>
				<select name="reg_date_range" holderG="{$holderG}" class="form-control {$crm_field} form-select" 
					data-field="cared_at" onchange="$Core.crm.do_search(this, event)">
					<option value="0">Tất cả</option>
					{foreach from=$list_date_ranges key = _OR item = _OT}
					<option value="{$_OR}">{$_OT}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<div class="form-label mb-1">Từ ngày</div>
				<input type="date" class="form-control {$crm_field}" holderG="{$holderG}" data-field="reg_from" onchange="$Core.crm.do_search(this, event)">
			</div>
			<div class="col-6">
				<div class="form-label mb-1">Tới ngày</div>
				<input type="date" class="form-control {$crm_field}" holderG="{$holderG}" data-field="reg_to" onchange="$Core.crm.do_search(this, event)">
			</div>
		</div>
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermission('full_permiss_crm')}
			<div class="form-group mb-2">
				<div class="form-label mb-1">Nhóm nhân viên</div>
				<select class="iso-selectizeNotSearch {$crm_field} required" holderG="{$holderG}" data-field="group_id" onchange="$Core.crm.do_search(this, event)" data-width="100%" data-placeholder="Nhóm nhân viên" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staffs_group" data-width="100%">
					{if $get_group_id gt '0'}
					<option value="{$get_group_id}" selected>{$clsGroupProfile->getTitle($get_group_id)}</option>	
					{else}
					<option value="">Nhóm nhân viên</option>
					{/if}
				</select>
			</div>
			<div class="form-group mb-2 js-manager-wrap{if $team_reps && $tab eq 'team'} d-none{/if}">
				<div class="form-label mb-1">Người quản lý</div>
				<select class="iso-selectizeNotSearch {$crm_field} required" holderG="{$holderG}" data-field="admin_id" onchange="$Core.crm.do_search(this, event)" data-width="100%" data-placeholder="Người quản lý" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" data-width="100%">
					{if $get_admin_id gt '0'}
					<option value="{$get_admin_id}" selected>{$clsProfile->getFullName($get_admin_id)}</option>
					{else}
					<option value="">Người quản lý</option>
					{/if}
				</select>
			</div>
		{/if}
		{if $team_reps}
		<div class="form-group mb-2 js-team-rep-wrap{if $tab ne 'team'} d-none{/if}">
			<div class="form-label mb-1">Nhân viên trong nhóm</div>
			<select class="form-control {$crm_field} iso-select2" data-placeholder="Tìm nhân viên..." data-allow-clear="true" data-width="100%" holderG="{$holderG}" data-field="admin_id" onchange="$Core.crm.do_search(this, event)">
				<option value="0">— Tất cả nhân viên —</option>
				{foreach from=$team_reps item=_rp}
				<option value="{$_rp.profile_id}"{if $get_admin_id == $_rp.profile_id} selected{/if}>{$_rp.full_name|escape}{if $_rp.dept_name} — {$_rp.dept_name|escape}{/if}</option>
				{/foreach}
			</select>
		</div>
		{/if}
		{if $is_tp_mkt}
		<div class="form-group mb-2">
			<div class="form-label mb-1">Nhân viên Marketing (người tạo)</div>
			<select class="form-control {$crm_field} form-select" holderG="{$holderG}" data-field="admin_id" onchange="$Core.crm.do_search(this, event)" data-width="100%" data-placeholder="Nhân viên Marketing" data-allow-clear="true">
				<option value="0">— Tất cả NV Marketing —</option>
				{foreach from=$mkt_staffs item=_ms}
				<option value="{$_ms.profile_id}"{if $get_admin_id == $_ms.profile_id} selected{/if}>{$_ms.text|escape}</option>
				{/foreach}
			</select>
		</div>
		{/if}
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<div class="form-label mb-1">Tình trạng</div>
				<select class="form-control search_crm_status_field {$crm_field} form-select" data-width="100%" holderG="{$holderG}" onchange="$Core.crm.do_search(this, event)" data-placeholder="Tình trạng" data-width="100%" data-allow-clear="true" data-field="status_id">
					<option value="0">Tình trạng</option>
					{$clsProperty->getSelectByProperty('CUSTOMER_STATUS',$get_status_id, "", true)}
				</select>	
			</div>
			<div class="col-6">
				<div class="form-label mb-1">Nguồn gốc</div>
				<select class="form-control {$crm_field} form-select" data-width="100%" holderG="{$holderG}" onchange="$Core.crm.do_search(this, event)" data-placeholder="Nguồn gốc" data-width="100%" data-allow-clear="true" data-field="resource_id">
					<option value="0">Nguồn gốc</option>
					{$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES',$get_resource_id, "", true)}
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
						<option{if $get_campaign_id eq $_oCampaign.campaign_id} selected{/if} 
							value="{$_oCampaign.campaign_id}">{$_oCampaign.title}</option>
						{/foreach}
					{/if}
				</select>
			</div>
			<div class="col-6">
				<div class="form-label mb-1">Loại hình</div>
				<select class="form-control {$crm_field} form-select" data-width="100%" holderG="{$holderG}" onchange="$Core.crm.do_search(this, event)" data-placeholder="Loại hình" data-width="100%" data-allow-clear="true" data-field="blocktype_id">
					<option value="0">Loại hình</option>
					{$clsProperty->getSelectByProperty('_BLOCK_TYPE', $get_blocktype_id, "", true)}
				</select>	
			</div>
		</div>
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<label class="form-label mb-1">Dự án/Phân khu</label>
				<div class="clearfix"></div>
				<select name="block_id" data-field="block_id" data-placeholder="Dự án/Phân khu" data-allow-clear="true" data-width="100%" 
					class="form-control {$crm_field} iso-select2" onchange="$Core.crm.do_search(this, event)">
					<option value="0">Dự án/Phân khu</option>
					{if !empty($arr_projects)}
						{foreach from=$arr_projects item = _oI}
						<option value="{$_oI.setting_id}">{$_oI.title}</option>
						{/foreach}
					{/if}
				</select>
			</div>
			<div class="col-6">
				<label class="form-label mb-1">Loại căn</label>
				<div class="clearfix"></div>
				<select name="bedroom_id" data-field="bedroom_id" data-placeholder="Phân khu" data-allow-clear="true" data-width="100%" 
					class="form-control {$crm_field} iso-select2" onchange="$Core.crm.do_search(this, event)">
					<option value="0">Loại căn</option>
					{if !empty($arr_bedrooms)}
						{foreach from=$arr_bedrooms item = _oI}
						<option{if $clsISO->checkItemInArray($_oI.property_id, $_ss_storage.list_bedroom_id)} selected{/if} value="{$_oI.property_id}">{$_oI.title}</option>
						{/foreach}
					{/if}
				</select>
			</div>
		</div>
		{if !empty($group_customer_sale) && $clsCustomer->isRootProfile()}
		<div class="form-group form-row mb-2">
			<div class="col-12">
				<div class="form-label mb-1">Nhóm đã chia</div>
				<select name="group_customer_sale" data-placeholder="Nhóm đã chia" class="form-control {$crm_field} iso-select2" data-width="100%" 
				data-allow-clear="true" holderG="{$holderG}" onchange="$Core.crm.do_search(this,event)" data-field="group_customer_sale">
					<option value="0">Chọn</option>
					{foreach from=$group_customer_sale key=key item = _oItem}
					<option value="{$key}">{$_oItem.title}</option>
					{/foreach}
				</select>
			</div>
		</div>
		{/if}
		<hr class="my-2" />
		{assign var = pId value = $clsISO->getUniqid()}
		<div class="form-check form-switch cursor-pointer mb-2">
			<input type="checkbox" id="{$pId}" class="form-check-input {$pId}" p_field="crm_view_all" 
				onchange="$Core.crm.set_field(this, event)" name="is_all"{if $crm_view_all eq '1'} checked{/if} value="1" />
			<label class="form-check-label" for="{$pId}">Hiển thị khách hàng lưu trữ</label>
		</div>
		<hr class="my-2" />
		<div class="form-group">
			<button type="button" holderG="{$holderG}" gId="{$gId}" onClick="$Core.crm.scrollToElem(this, event); $Core.crm.do_search(this, event)" 
			class="btn btn-outline-success do_crm_search">Tìm kiếm</button>
			<button type="button" onClick="$Core.crm.reset_search(this, event)" holderG="{$holderG}" class="btn btn-outline-default">Xóa</button>
		</div>
	</form>
</div>