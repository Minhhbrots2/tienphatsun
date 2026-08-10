<div class="modal-dialog modal-dialog-centered modal-sm">
	<div class="modal-content">
		<div class="modal-header py-2"> 
			<h3 class="modal-title"><strong>Thêm</strong></h3>
		</div>
		<form action="" method="post" id="frmPayOther" encrupt="miltipart/form-data">
			<div class="modal-body py-2">
				<div class="form-group form-row">
					<label class="col-12 col-form-label">Tiêu đề</label>
					<div class="col-12">
						<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title_pay_other" value="">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a type="button" class="btn btn-outline-default" onClick="$Core.leasing.close_pop(this, event)" data-bs-dismiss="modal">
					Huỷ
				</a>
				<button type="button" class="btn btn btn-primary" onClick="$Core.leasing.addPaymentLeasing(this)">
					{$core->makeIcon('check', 'Thêm')}
				</button>
			</div>
		</form>
	</div>
</div>