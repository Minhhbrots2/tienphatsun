<div class="modal-dialog modal-dialog-centered">
	<form method="POST" action="#" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Huỷ {$titlePage}</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body bg-lighter">
			<div class="form-form">
				<label for="reason_{$uid}" class="form-label mb-1">Ly do huỷ</label>
				<textarea id="reason_{$uid}" class="form-control required" placeholder="Nhập lý do huỷ" name="reason" rows="2"></textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
			<button data-toggle="ripple" type="button" holderG="{$holderG}" activity_id="{$activity_id}" booking_id="{$booking_id}" 
				onClick="$Core.booking.do_cancel_activity(this, event)" class="btn btn-primary"><i class="bx bx-check"></i> Thực hiện</button>
		</div>
	</form>
</div>