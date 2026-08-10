<div class="modal-dialog modal-{if $p_field eq 'content' || $p_field eq 'participants'}ipad{else}sm{/if}">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Chỉnh sửa</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if $p_field eq 'assign_to_id'}
			<div class="form-group">
				<label class="form-label">Người nhận</label>
				<select class="iso-selectizeNotSearch required w-100" placeholder="Nhân viên" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" name="assign_to_id" data-optgroup="false">
					{if !empty($oneIssue.assign_to_id)}
					<option value="{$oneIssue.$oneIssue.assign_to_id}" selected="selected">{$clsProfile->getIndentity($oneIssue.assign_to_id)}</option>
					{/if}
				</select>
			</div>
			{elseif $p_field eq 'participants'}
			<div class="form-group">
				<label class="form-label">Người liên quan</label>
				<select class="iso-selectizeNotSearch required w-100" multiple="true" placeholder="Người liên quan" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" name="participants[]" data-optgroup="false">
					{$html_options}
				</select>
			</div>
			{elseif $p_field eq 'content'}
			<div class="form-group">
				<label class="form-label">Nội dung công việc</label>
				{assign var = editorId value = $clsISO->getUniqid()}
				<textarea id="{$editorId}" class="form-control isoTextArea" rows="255" cols="25" data-name="content">{$oneIssue.content}</textarea>
			</div>
			{elseif $p_field eq 'start_date' || $p_field eq 'end_date'}
			<div class="form-group">
				<label class="form-label">{if $p_field eq 'start_date'}Từ{else}Đến{/if} ngày</label>
				<input type="datetime-local" id="{$clsISO->getUniqid()}" name="{$p_field}" class="form-control required" placeholder="dd/mm/yy" value="{$oneIssue.$p_field|date_format:'%Y-%m-%dT%H:%M'}">
			</div>
			{elseif $p_field eq 'priority_id'}
			<label class="col-form-label">Độ ưu tiên</label>
			<select class="form-control form-select required" name="{$p_field}">
				{$clsProperty->getSelectByProperty('_ISSUE_PRIORITY',$oneIssue.$p_field)}
			</select>
			{elseif $p_field eq 'status_id'}
			<label class="col-form-label">Tình trạng</label>
			<select class="form-control form-select required" name="{$p_field}">
				{$clsProperty->getSelectByProperty('_ISSUE_STATUS',$oneIssue.$p_field)}
			</select>
			{/if}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" p_id="{$p_id}" p_field="{$p_field}" toId="{$toId}" onClick="pop_save_issue_edit(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>