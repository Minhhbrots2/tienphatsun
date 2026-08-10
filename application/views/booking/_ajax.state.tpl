<div class="modal-dialog modal-dialog-centered modal-xs">
	<form method="POST" action="#" class="modal-content">
		<div class="modal-header border-bottom bg-lighter">
			<h5 class="modal-title">Cập nhật trạng thái booking</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<label class="form-label mb-1">Tình trạng</label>
			<select class="form-control iso-selectizeSync form-select" name="state_id">
				{$clsProperty->makeSelect('_BOOKING_STATE', $oneBooking.state_id, $state_arrs)}
			</select>
		</div>
		<div class="modal-footer border-top bg-lighter">
			<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Hủy bỏ</button>
			<button data-toggle="ripple" type="button" booking_id="{$booking_id}" booking_type="{$booking_type}" 
			onClick="$Core.booking.do_upd_state(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>