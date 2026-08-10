<div class="modal-dialog modal-dialog-centered modal-md">
	<div class="modal-content">
		<div class="modal-header py-2">
			{if !empty($meta_id)}
				<h3 class="modal-title"><strong>Sửa</strong></h3>
			{else}
				<h3 class="modal-title"><strong>Thêm mới</strong></h3>
			{/if}
		</div>
		<form action="" method="post" id="frmPayOther" encrupt="miltipart/form-data">
			<div class="modal-body py-2">
				<div class="form-group">
					<label class="col-form-label">Tiêu đề</label>
					<input type="text" required="true" placeholder="Nhập mã căn" name="title" maxlength="255" charet="UTF-8" class="form_field form-control required no-focus" value="{$oneItem.title}">
				</div>
				<div class="form-group">
					<label class="col-form-label">Nội dung</label>
					<textarea class="form-control form_field" name="iso-content" cols="255" rows="5" id="{$clsISO->getUniqid()}">{$oneItem.content}</textarea>
				</div>	
				<div class="form-group form-row">
					<label class="col-12 col-form-label">Hình ảnh*</label>
					<div class="col-12">
						<div class="d-flex gap-2">
							<input class="form_field" type="hidden" name="image_hidden" value="{$oneItem.image}">
							<input type="file" class="form_field form-control flex-fill" placeholder="Hình ảnh" name="image" value="" onchange="document.getElementById('input_image{$uid}').src = window.URL.createObjectURL(this.files[0])">
							<img class="rounded" src="{$oneItem.image}" width="36" height="36" alt="" onerror="this.src='{$URL_IMAGES}/no-image.jpg'" id="input_image{$uid}">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a type="button" class="btn btn-outline-default" data-bs-dismiss="modal">
					Huỷ
				</a>
				<input type="hidden" name="meta_id" value="{$meta_id}">
				<button type="button" class="btn btn btn-primary" onClick="$Core.broker.addMeta(this,event)" gId="{$gId}">
					{if !empty($meta_id)}
						{$core->makeIcon('check', 'Sửa')}
					{else}
						{$core->makeIcon('check', 'Thêm')}
					{/if}
				</button>
			</div>
		</form>
	</div>
</div>