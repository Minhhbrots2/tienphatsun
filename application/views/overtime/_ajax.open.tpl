<div class="modal-dialog{if $deviceType eq 'phone'} modal-dialog-centered{/if} modal-ipad">
	{assign var = editorId value = $clsISO->getUniqid()}
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header border-bottom d-flex align-items-center justify-content-between">
			<h5 class="modal-title">{if $action eq '_add'}Thêm mới{else}Cập nhật{/if} tăng ca</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			{if $deviceType ne 'phone'}
			<div class="alert alert-warning">
				{$clsConfiguration->getValue('SiteMsg_Overtime_Terms')}
			</div>
			{/if}
			<div class="form-group form-row mb-2">
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Mã đăng ký</label>
					<input type="text" class="form-control required" readonly name="code" placeholder="Mã đăng ký" value="{$oneItem.code}" />
				</div>
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Ngày đăng ký</label>
					<input type="date" class="form-control required" name="regis_date" placeholder="Ngày đăng ký" value="{$oneItem.regis_date|date_format:'%Y-%m-%d'}" />
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label mb-1">Độ ưu tiên</label>
					{assign var = toId value = $clsISO->getUniqid()}
					<select id="{$toId}" class="form-control form-select required" name="priority_id">
						{$clsProperty->getSelectByProperty('_ISSUE_PRIORITY', $oneItem.priority_id)}
					</select>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Thời gian tăng ca</label>
					<input type="datetime-local" class="form-control required" name="start_date" placeholder="Từ ngày" value="{$oneItem.start_date|date_format:'%Y-%m-%dT%H:%M'}" />
				</div>
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">tới</label>
					<input type="datetime-local" class="form-control required" name="due_date" placeholder="Tới ngày" value="{$oneItem.due_date|date_format:'%Y-%m-%dT%H:%M'}" />
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label mb-1">TG. nghỉ giữa ca</label>
					<input type="text" class="form-control input_mask" data-inputmask="99:99" name="time_off" value="{if $action eq '_edit'}{$oneItem.time_off}{/if}" placeholder="--:--" />
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Lý do tăng ca</label>
				<textarea id="{$editorId}" class="form-control required" cols="25" rows="3" name="content" placeholder="Lý do tăng ca">{if $action eq '_edit'}{$oneItem.content}{/if}</textarea>
			</div>
			<div class="form-group form-row">
				<div class="col-12 col-lg-6 mb-2 mb-lg-0">
					<label class="form-label mb-1">Người duyệt</label>
					<select class="form-control iso-select2" multiple="true" name="list_approver_id[]" data-width="100%" 
						data-allow-clear="true" data-placeholder="Chọn người duyệt" data-maximum-selection-length="2">
						{if !empty($list_profile_approval)}
							{foreach from=$list_profile_approval item = _oU}
							<option{if $clsISO->checkItemInArray($_oU.profile_id, $list_approver_arrs)} selected{/if} value="{$_oU.profile_id}">{$clsProfile->getFullName($_oU.profile_id, $_oU)}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-6">
					<label class="form-label mb-1">Người duyệt kết quả</label>
					<select class="form-control iso-select2" multiple="true" name="list_confirmed_id[]" data-width="100%" 
						data-allow-clear="true" data-placeholder="Chọn người duyệt" data-maximum-selection-length="2">
						{if !empty($list_profile_confirmed)}
							{foreach from=$list_profile_confirmed item = _oU}
							<option{if $clsISO->checkItemInArray($_oU.profile_id, $list_confirmed_arrs)} selected{/if} value="{$_oU.profile_id}">{$clsProfile->getFullName($_oU.profile_id, $_oU)}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>
		</div>
		<div class="modal-footer justify-content-between">
			<div class="d-flex align-items-center gap-1">
				<label class="switch">
					<input type="checkbox" name="is_fullday" value="1"{if $oneItem.is_fullday eq '1'} checked{/if} />
					<span class="slider round"></span>
				</label>
				<span>Cả ngày(+2đ)</span>
			</div>
			<button type="button" overtime_id="{$overtime_id}" class="btn{if $deviceType eq 'phone'} btn-block btn-lg{/if} btn-primary" 
			title="Lưu lại" onClick="$Core.global.overtime.save(this, event)">Lưu lại</button>
		</div>
	</form>
</div>
		

