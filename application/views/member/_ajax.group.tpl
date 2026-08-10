<div class="modal-dialog">
	<form method="POST" class="modal-content">
		<div class="modal-header border-bottom d-flex align-items-center justify-content-between">
			<h5 class="modal-title">{if !empty($oneGroup)}Sửa nhóm nhân viên{else}Thêm mới nhóm nhân viên{/if}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="form-group mb-2">
				<label class="col-form-label">Tên nhóm</label>
				<input type="text" class="form-control fw-bold autofocus required" name="title" 
				data-val-required="Bạn chưa nhập vào tên nhóm nhân viên" value="{$oneGroup.title}" 
				maxlength="255" placeholder="Tên nhóm" />
			</div>
			<div class="form-group">
				<label class="col-form-label">Người tham gia</label>
				<div class="clearfix"></div>
				<select class="iso-select2 required" data-width="100%" multiple="multiple" name="staff_ids[]" 
				placeholder="Tên công việc" >
				{if !empty($list_staffs)}
					{foreach from = $list_staffs item = _oStaff}
					<option value="{$_oStaff.profile_id}" {if $clsISO->checkItemInArray($_oStaff.profile_id,$clsISO->getArrayByTextSlash($oneGroup.list_profile_id))}selected{/if}>{$_oStaff.full_name}</option>
					{/foreach}
				{/if}
				</select>
				<div class="clearfix"></div>
				<div class="d-inline-block pt-2 group_participants_{$group_id}"></div>
			</div>
		</div>
		<div class="modal-footer border-top">
			<button type="button" data-group_id="{$group_id}" data-type="save" class="btn btn-primary" 
			title="Lưu lại" onClick="$Core.member.add_group(this, event)">Lưu lại</button>
		</div>
	</form>
</div>