<div class="modal-dialog{if $deviceType eq 'phone'} modal-dialog-centered{/if} modal-sm">
	<form class="modal-content" id="frmIssue" enctype="multipart/form-data">
		<div class="modal-header border-bottom">
			<h5 class="modal-title">Báo cáo vắng mặt {$smarty.const.BRAND_NAME}<br />
				<span class="text-danger fs-13">
					{$clsISO->makeIcon('bx-user-plus', 'Người tạo: ')}
					{$clsProfile->getFullName($profile_id,$oneProfile)}
				</span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body bg-lighter">
			{assign var = toId value = $clsISO->getUniqid()}
			<div class="d-flex align-items-center">
				{assign var = uid value = $clsISO->getUniqid()}
				<label for="{$uid}" class="we-radio w-50">
					<input toId="{$toId}" type="radio" id="{$uid}" onchange="$Core.timesheet.set_status(this, event)" 
					name="status" checked value="1"> 
					<span class="font-bold text-center">Đi làm</span> 
				</label>
				{assign var = uid value = $clsISO->getUniqid()}
				<label for="{$uid}" class="we-radio w-50">
					<input toId="{$toId}" type="radio" id="{$uid}" onchange="$Core.timesheet.set_status(this, event)" 
					name="status" value="2"> 
					<span class="font-bold text-center">Vắng mặt</span> 
				</label>
			</div>
			<div id="{$toId}" class="d-none">
				<div class="form-group mt-3">
					<label class="form-label mb-1">Thời gian</label>
					<select name="content[{$_profile_id}][type]" class="form-control form-select">
						<option value="_ALLDAY">Cả ngày</option>
						<option value="_MORNING">Buổi sáng</option>
						<option value="_AFTERNOON">Buổi chiều</option>
					</select>
				</div>
				<div class="form-group mt-3">
					<label class="form-label mb-1">Lý do</label>
					<textarea class="form-control" name="reason" placeholder="Lý do..." rows="2"></textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer border-top">
			<input type="hidden" name="submit" value="Update" />
			<input type="hidden" name="department_id" value="{$department_id}" />
			<button type="button" worktime_id="{$worktime_id}" 
			onClick="$Core.worktime.save(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>