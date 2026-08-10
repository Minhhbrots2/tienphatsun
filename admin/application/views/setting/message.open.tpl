<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Tìm kiếm sản phẩm</h5>
			<button type="button" class="close close_pop" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		</div>
		<form method="post">
			<div class="modal-body">
				<div class="form-group">
					<label for="" class="form-control-label">KEY</label>
					<input type="text" class="form-control required" name="setting" placeholder="Tìm kiếm sản phẩm" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary close_pop" data-dismiss="modal">{$core->get_Lang('Close')}</button>
				<button type="button" class="btn btn-success" onClick="add_message(this)">{$core->get_Lang('Update')}</button>
			</div>
		</form>
	</div>
</div>