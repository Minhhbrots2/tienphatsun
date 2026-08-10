<div class="modal-dialog modal-sm modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a>
			<h3 class="modal-title"><strong>Tạo lộ trình học</strong></h3>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-md-12 col-form-label">Tên lộ trình*</label>
					<div class="col-xs-12">
						<input type="text" class="form-control form_field required" placeholder="Nhập tên lộ trình" name="title" value="">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn btn-primary" onClick="$Core.learning_path.addPath(this,event)" data-type='_SAVE'>Tạo</button>
			</div>
		</form>
	</div>
</div>
