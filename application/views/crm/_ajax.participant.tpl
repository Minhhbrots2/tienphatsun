<div class="modal-dialog modal-sm modal-dialog-centered">
	<form class="modal-content" method="POST" enctype="multipart/form-data">
		<div class="modal-header border-bottom">
			<h5 class="modal-title">Thêm người liên quan</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Lựa chọn</label>
				<select class="iso-selectizeImageSearch required" multiple="true" name="user_participants[]" data-width="100%" 
					data-placeholder="Người tham gia"  data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&hoderG=active" data-width="100%">
					{$html_user_participants}
				</select>
			</div>
		</div>
		<div class="modal-footer border-top pt-2">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" customer_id="{$customer_id}" onClick="$Core.crm.save_participant(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>