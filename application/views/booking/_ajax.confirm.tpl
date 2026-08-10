<div class="modal-dialog">
	<form method="POST" action="#" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Xác nhận hủy booking <br />
				<div class="d-flex align-items-center gap-2 text-muted text-fs-12 font-normal">
					<span><i class="bx bx-user"></i> {$clsProfile->getFullName($oneBooking.user_id_update)}</span>
					<span><i class="bx bx-time"></i> {$clsISO->convertTimeToText($oneBooking.upd_date, true)}</span>
				</div>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="alert alert-warning text-center">
				Mã booking: {$oneBooking.booking_code}
			</div>
			<div class="form-group">
				<label class="form-label requried mb-1">Nhập lý do</label>
				<textarea class="form-control requried" name="cancel_reason" rows="3" cols="255" placeholder="Nhập lý do hủy"></textarea>
			</div>
		</div>
		<div class="modal-footer border-top bg-lighter">
			<button data-toggle="ripple" type="button" class="btn flex-fill btn-outline-default" data-bs-dismiss="modal">Hủy bỏ</button>
			<button data-toggle="ripple" type="button" booking_id="{$booking_id}" 
				onClick="$Core.booking.do_cancel(this, event)" class="btn flex-fill btn-primary">Thực hiện</button>
		</div>
	</form>
</div>