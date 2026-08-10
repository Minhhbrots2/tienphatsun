<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_add'}Thêm mới{else}Sửa{/if} thông báo</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Tiêu đề</label>
				<input type="text" class="form-control required" name="title" maxlength="255" 
				placeholder="Nhập tiêu đề" value="{if $action eq '_edit'}{$oneItem.title}{/if}">
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Nội dung</label>
				<textarea id="{$clsISO->getUniqid()}" class="form-control" cols="255" rows="5" name="content">{if $action eq '_edit'}{$oneItem.content}{/if}</textarea>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Link</label>
				<input type="text" class="form-control required" name="link" maxlength="255" 
				placeholder="Nhập đường dẫn" value="{if $action eq '_edit'}{$oneItem.link}{/if}">
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" onClick="$Core.notification.save(this, event)" notification_id="{$notification_id}" 
			class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>