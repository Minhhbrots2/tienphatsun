<div class="modal-dialog modal-sm">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<label class="col-form-label">Nhập vào số năm</label>
				<input type="text" id="name" name="name" class="form-control required" placeholder="Năm">
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" uid="{$uid}" onClick="set_options(this,event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
