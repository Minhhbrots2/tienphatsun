{if $template_type eq '_form'}
<div class="modal-dialog modal-dialog-centered">
	<form method="post" class="modal-content">
		<div class="modal-header border-bottom pb-3"> 
			<h5 class="modal-title">{if $action eq '_add'}Thêm mới{else}Cập nhật{/if} liên hệ với khách hàng</h5>
		</div>
		<div class="modal-body border-bottom">
			<div class="form-group mb-2">
				<label class="mb-1">Hình thức liên hệ</label>
				<div class="btn-group d-flex" role="group" aria-label="Sắp xếp" bis_skin_checked="1">
					{foreach from=$list_activity item = _oActivity}
					<input type="radio" class="btn-check" name="type_id" id="{$uid}_{$_oActivity.property_id}" 
						value="{$_oActivity.property_id}"{if $oneContact.type_id eq $_oActivity.property_id} checked="checked"{/if}>
					<label class="btn js-ripple btn-outline-default" for="{$uid}_{$_oActivity.property_id}">
						<i class="bx {$_oActivity.image}"></i> {$_oActivity.title}
					</label>					
					{/foreach}
				</div>
			</div>
			<div class="form-group">
				<label class="mb-1">Nội dung liên hệ</label>
				<textarea class="form-control required mb-2" name="content" cols="255" rows="5">{$oneContact.intro}</textarea>
				<div class="alert alert-warning mb-0">
					<i class='bx bx-error-alt'></i> Nhập đầy đủ & chi tiết nội dung kết quả bạn đã trao đổi với khách hàng!
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
			<button type="button" telesale_id="{$telesale_id}" contact_id="{$contact_id}" onClick="$Core.sop.save_contact(this, event)" 
			class="btn btn-outline-primary">{if $action eq '_add'}Thêm mới{else}Cập nhật{/if}</button>
		</div>
	</form>
</div>
{else}
	{if !empty($list_followups) || !empty($owner_notes)}
		{if !empty($owner_notes)}
		<div class="followup-item pb-2 mb-2{if !empty($list_followups)} border-bottom{/if}">
			<div class="d-flex mb-2 align-items-center justify-content-between">
				<div class="d-flex gap-2 align-items-center">
					<div class="rounded-pill">
						<img class="avatar rounded-pill avatar-sm" src="{$URL_IMAGES}/avatars/1.png" />
					</div>
					<div class="w-100">
						<h4 class="text-fs-14 mb-0">Sale làm việc với chủ nhà</h4>
						<small class="text-muted">Ngày tạo: {$clsISO->convertTimeToText($oneTelesale.reg_date, true)}</small>
					</div>
				</div>
			</div>
			<div class="bg-lighter p-3 rounded-2">
				{$owner_notes|nl2br}
			</div>
		</div>
		{/if}
		{foreach from=$list_followups name = i item = _oContact}
		{assign var = oProfile value = $_oContact.oProfile}
		<div class="followup-item pb-2 mb-2{if !$smarty.foreach.i.last} border-bottom{/if}">
			<div class="d-flex mb-2 align-items-center justify-content-between">
				<div class="d-flex gap-2 align-items-center">
					<div class="rounded-pill">
						<img class="avatar rounded-pill avatar-sm" src="{$clsProfile->getAvatar($_oContact.user_id,$oProfile,40,40)}" />
					</div>
					<div class="w-100">
						<h4 class="text-fs-14 mb-0">{$clsProfile->getFullName($_oContact.user_id, $oProfile)}</h4>
						<small class="text-muted">Ngày tạo: {$clsISO->getTimeAgo($_oContact.reg_date)}</small>
					</div>
				</div>
				<div class="dropdown">
					<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true">
						<i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu w-px-50 dropdown-menu-end" data-popper-placement="bottom-end">
						<a class="dropdown-item" onclick="$Core.sop.open_contact(this,event)" telesale_id="{$telesale_id}" contact_id="{$_oContact.followup_id}" href="javascript:void(0);"><i class="bx bx-pencil me-1"></i> Sửa</a>
						<a class="dropdown-item" onclick="$Core.sop.delete_contact(this,event)" telesale_id="{$telesale_id}" contact_id="{$_oContact.followup_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
					</div>
				</div>
			</div>
			<div class="bg-lighter p-3 rounded-2">
				{$_oContact.intro}
			</div>
		</div>
		{/foreach}
	{else}
		<div class="px-2 py-2">
			{$htmlNotFound}
		</div>
	{/if}
{/if}