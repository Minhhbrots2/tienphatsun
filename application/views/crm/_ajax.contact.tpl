{if $template_type eq '_form'}
<div class="modal-dialog modal-dialog-centered">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">{if $action eq '_add'}Thêm{else}Sửa{/if} liên hệ</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col mb-3">
					<label class="form-label mb-1">Tên liên hệ</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-user"></i></span>
						<input type="text" id="name" name="name" class="form-control required" value="{if $action eq '_edit'}{$oneContact.name}{/if}" placeholder="Họ và tên">
					</div>
				</div>
			</div>
			<div class="form-row mb-3">
				<div class="col mb-0">
					<label class="form-label mb-1">Điện thoại</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-phone"></i></span>
						<input type="text" id="phone" name="phone" class="form-control" value="{if $action eq '_edit'}{$oneContact.phone}{/if}" placeholder="Điện thoại">
					</div>
				</div>
				<div class="col mb-0">
					<label class="form-label mb-1">Email</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-envelope"></i></span>
						<input type="text" id="email" name="email" value="{if $action eq '_edit'}{$oneContact.email}{/if}" class="form-control" placeholder="example@gmail.com">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col mb-3">
					<label class="form-label mb-1">Địa chỉ</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-buildings"></i></span>
						<input type="text" id="address" name="address" class="form-control" value="{if $action eq '_edit'}{$oneContact.address}{/if}" placeholder="Địa chỉ">
					</div>
				</div>
			</div>
			<div class="widget-block collapsed">
				<div onClick="$Core.helper.toggle_block(this,event)" class="widget-header">Thông tin thêm</div>
				<div class="widget-content">
					<div class="form-group">
						<label class="form-label mb-1">Ghi chú</label>
						<textarea name="notes" class="form-control" cols="255" rows="3">{if $action eq '_edit'}{$oneContact.notes}{/if}</textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" uid="{$uid}" contact_id="{$contact_id}" customer_id="{$customer_id}" onClick="$Core.crm.save_contact(this, event)" class="btn flex-fill btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
{else}
	{if !empty($list_contacts)}
		{foreach name=i from=$list_contacts item = _oContact}
		<tr class="text-nowrap trProject_{$potential_id}">
			{if $deviceType ne 'phone'}
			<td class="text-center" class="text-center">{$smarty.foreach.i.iteration}</td>{/if}
			<td class="text-left"><strong>{$_oContact.name}</strong></td>
			<!-- <td data-label="Công ty">{$_oContact.companyname}</td> -->
			<td class="text-left">{$_oContact.email}</td>
			<td class="text-left">{$_oContact.phone}</td>
			<td class="text-left">{$_oContact.address}</td>
			<td class="text-right">{$clsISO->convertTimeToText($_oContact.reg_date, true)}</td>
			<td class="text-center">
				<div class="d-flex align-items-center gap-1">
					<a href="javascript:;" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.open_contact(this, event);" customer_id="{$customer_id}" contact_id="{$_oContact.contact_id}">{$clsISO->makeIcon('bx-pencil')}</a>
					<a href="javascript:;" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.delete_contact(this, event)" customer_id="{$customer_id}" contact_id="{$_oContact.contact_id}">{$clsISO->makeIcon('bx-trash')}</a>
				</div>
			</td>
		</tr>
		{/foreach}
	{else}
		<tr>
			<td colspan="7" class="text-center">
				Chưa có liên hệ
			</td>
		</tr>
	{/if}
{/if}