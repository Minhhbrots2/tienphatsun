<div class="modal-dialog modal-ipad">
	<form class="modal-content">
		<div class="modal-header position-relative">
			<h5 class="modal-title">Trung tâm trợ giúp</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label for="nameSlideTop" class="form-label">Nội dung</label>
				<textarea id="{$clsISO->getUniqid()}" class="form-control isoTextArea" cols="255" rows="25" data-name="content">{$helper_page.content}</textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
			<button data-toggle="ripple" type="button" onClick="$Core.helper.edit_helper(this, event)" action="_EDIT" mod_page="{$mod_page}" sub_page="{$sub_page}" act_page="{$act_page}" class="btn btn-primary">Lưu</button>
		</div>
	</form>
</div>