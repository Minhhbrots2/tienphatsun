<div class="modal-dialog modal-sm">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center justify-content-between">
				<div class="modal-header__left">
					<h5 class="modal-title">Ghi chú cuộc gọi</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
				</div>
			</div>
			<div class="modal-body">	
				<div class="form-group form-row mb-2">
					<div class="col-12 mb-2">
						<textarea name="" id="" cols="30" rows="5" class="form-control w-100" placeholder="Nhập nội dung ghi chú"></textarea>
					</div>
				</div>	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" onClick="$Core.data_central.log_call_success(this,event)" data-id="{$id}" data-type="_SAVE">Gửi</button>
			</div>
		</div>
	</form>
</div>