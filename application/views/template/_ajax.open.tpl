<div class="modal-dialog modal-dialog-centered modal-md">
	<form class="modal-content">
		<div class="modal-header">				
			{if !empty($template_id)}
				<h5 class="modal-title fs-{if $deviceType eq 'phone'}5{else}4{/if} text-main">Chỉnh sửa mẫu</h5>
			{else}
				<h5 class="modal-title fs-{if $deviceType eq 'phone'}5{else}4{/if} text-main">Thêm mới mẫu</h5>
			{/if}
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body pt-0">	
			<div class="form-group mb-2">
				<label class="w-100 form-label mb-1">Tiêu đề</label>
				<input type="text" placeholder="Nhập tiêu đề" name="title" maxlength="255" charet="UTF-8" class="form_field form-control required no-focus" value="{$oneItem.title}">	
			</div>
			<div class="form-group mb-2">
				<label class="w-100  form-label mb-1">Nội dung</label>
				<textarea id="textarea_{$uid}" class="form-control required" name="content" cols="255" rows="10">{$oneItem.content|html_entity_decode}</textarea>
			</div>
			<div class="lst_tag d-flex flex-wrap gap-1 align-items-center">
				<label class="form-label mb-1">Thêm tag: </label>
				<button class="btn btn-sm btn-default" type="button" data-tag="[DANH_XUNG]" onClick="$Core.template.addTag(this,event)" toId="textarea_{$uid}">Danh xưng</button>
				<button class="btn btn-sm btn-default" type="button" data-tag="[HO_TEN]" onClick="$Core.template.addTag(this,event)" toId="textarea_{$uid}">Họ tên</button>
				<button class="btn btn-sm btn-default" type="button" data-tag="[DU_AN]" onClick="$Core.template.addTag(this,event)" toId="textarea_{$uid}">Dự án</button>
				<button class="btn btn-sm btn-default" type="button" data-tag="[MA_CAN]" onClick="$Core.template.addTag(this,event)" toId="textarea_{$uid}">Mã căn</button>
				<button class="btn btn-sm btn-default" type="button" data-tag="[GIA_TRI]" onClick="$Core.template.addTag(this,event)" toId="textarea_{$uid}">Giá</button>
			</div>
			
		</div>
		<div class="modal-footer justify-content-between">	
			<div class="d-flex align-items-center">
				<div class="d-flex gap-1 align-items-center">
					<label class="switch">
						<input type="checkbox" name="is_share" value="1" {if $oneItem.is_share eq 1} checked{/if}>
						<span class="slider round"></span>
					</label>
					<label class="col-form-label mr-2">Chia sẻ mẫu</label>
				</div>
			</div>
			<div class="d-flex align-items-center">
				<input type="hidden" name="submit" value="Update" />
				<input type="hidden" name="template_id" value="{$template_id}" />
				<button type="button" onClick="$Core.template.saveTemplate(this, event)" class="btn btn-primary">
					{if !empty($template_id)}
						Cập nhật
					{else}
						Thêm
					{/if}
				</button>
			</div>
		</div>
	</form>
</div>