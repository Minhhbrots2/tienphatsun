{if $template_type eq '_list'}
	{if !empty($list_reminders)}
		{foreach from=$list_reminders name=i item = _oReminder}
		<div class="p-2 rounded-2 bg-lighter{if !$smarty.foreach.i.last} mb-2{/if}">
			<div class="text-fs-12 d-flex align-items-center justify-content-between">
				<div class="d-flex flex-column gap-1">
					<div class="d-flex flex-column gap-1">
						<small><i class="bx bx-bell text-fs-12"></i> Thông báo tới</small>
						<div class="d-flex gap-1">{$_oReminder.html_admin}</div>
					</div>
					<div class="d-flex text-muted flex-wrap" style="margin-top:-15px; margin-left:30px;">vào lúc {$clsISO->convertTimeToText($_oReminder.reminder_time, true)}</div>
					<div class="intro">{$_oReminder.intro}</div>
				</div>
				<div class="dropdown">
					<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true">
						<i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu w-px-50 dropdown-menu-end" data-popper-placement="bottom-end">
						<a class="dropdown-item" onclick="$Core.sop.open_reminder(this,event)" telesale_id="{$telesale_id}" reminder_id="{$_oReminder.followup_id}" href="javascript:void(0);"><i class="bx bx-pencil me-1"></i> Sửa</a>
						<a class="dropdown-item" onclick="$Core.sop.delete_reminder(this,event)" telesale_id="{$telesale_id}" reminder_id="{$_oReminder.followup_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
					</div>
				</div>
			</div>
		</div>
		{/foreach}
		<hr />
		<div class="mt-2">
			<a onClick="$Core.sop.open_reminder(this, event)" class="btn btn-sm btn-link" telesale_id="{$telesale_id}" 
				title="Thêm nhắc nhở"><i class="bx bx-plus"></i> Thêm nhắc nhở</a>
		</div>	
	{else}
		<div class="p-3 text-center border border-dashed rounded-2">
			<div class="d-flex mb-1 align-items-center justify-content-center">
				<i class="bx bx-bell fs-2"></i>
			</div>
			<p class="text-muted">Chưa có nhắc nhở nào. <a onClick="$Core.sop.open_reminder(this, event)" 
				class="btn btn-sm btn-link" telesale_id="{$telesale_id}" title="Thêm nhắc nhở">Thêm nhắc nhở</a></p>
		</div>
	{/if}
{else}
<div class="modal-dialog modal-sm">
	<form method="post" action="#" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header border-bottom pb-3"> 
			<h5 class="modal-title gap-2">
				<i class="bx bx-bell"></i>
				{if $action eq '_add'}Thêm{else}Cập nhật{/if} nhắc nhở
			</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body border-bottom">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Ngày cần nhắc nhở</label>
				<input type="datetime-local" name="reminder_time" value="{if $action eq '_edit'}{$oneReminder.reminder_time}{/if}" class="form-control required" placeholder="dd/mm/YYYY" />
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Thông báo đến</label>
				<select class="iso-selectizeNotSearch required w-100" placeholder="Nhân viên" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" name="admin_id" data-optgroup="false">
					{if $action eq '_edit'}
					<option value="{$oneReminder.admin_id}" selected>
						{$clsProfile->getIndentity($oneReminder.admin_id)}
					</option>
					{else}
					<option value="{$profile_id}" selected>
						{$oneProfile.code}-{$oneProfile.full_name}
					</option>
					{/if}
				</select>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Nội dung</label>
				<textarea class="form-control required" name="content" placeholder="Nội dung" rows="2">{$oneReminder.intro}</textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
			<button type="button" telesale_id="{$telesale_id}" reminder_id="{$reminder_id}" onClick="$Core.sop.save_reminder(this, event)" 
			class="btn btn-outline-primary">{if $action eq '_add'}Thêm mới{else}Cập nhật{/if}</button>
		</div>
	</form>
</div>
{/if}

