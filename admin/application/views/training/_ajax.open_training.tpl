<div class="modal-dialog modal-sm modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>Tạo khóa học</strong></h3>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-md-12 col-form-label">Tên khóa học*</label>
					<div class="col-xs-12">
						<input type="text" class="form-control form_field required" placeholder="Nhập tên khóa học" name="title" value="{$oneItem.title}">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="field_id" value="{$field_id}">
				<button type="button" class="btn btn btn-primary" onClick="$Core.training.addTraining(this,event)" data-type='_SAVE'>Tạo</button>
			</div>
		</form>
	</div>
</div>
