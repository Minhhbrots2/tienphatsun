<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cấu hình ẩn quỹ đại lý</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body holder_setting_field overflow-y-auto" style="max-height:calc(100% - 100px)">

			</div>
			<div class="modal-footer">
				<button type="button" p_id="{$p_id}" p_field="{$p_field}" toId="{$toId}" onClick="$Core.agency.open_setting_field(this, event)" class="btn btn-primary">Thêm mới</button>
			</div>
		</div>
	</div>
</div>