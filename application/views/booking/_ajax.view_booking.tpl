<div class="modal-dialog modal-dialog-scrollable modal-ipad-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Xem booking {$oneBooking.booking_code} <br />
				<div class="d-flex align-items-center gap-2 text-muted text-fs-12 font-normal">
					<span><i class="bx bx-user"></i> {$clsProfile->getFullName($oneBooking.user_id_update, $oneUserUpdated)}</span>
					<span><i class="bx bx-time"></i> {$clsISO->convertTimeToText($oneBooking.upd_date, true)}</span>
				</div>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		{if $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ACCOUNTANT')}
		<div class="highlights">
			<div class="highlight-panel{if $deviceType eq 'phone'} mobile{/if}">
				<div class="d-flex align-items-center justify-content-center p-2 gap-2 text-nowrap">
				{if $booking_type eq $smarty.const._BOOKING_TYPE_INVESTOR_ID}
					<!-- Khớp căn -->
					<button type="button" booking_id="{$booking_id}"{if $is_lock_unit_match eq '1'} disabled{/if} booking_type="{$booking_type}" onClick="$Core.booking.open_activity(this,event)" activity_id="{$oneBookingMeta.id}" data-toggle="ripple" class="btn btn-sm btn-outline-primary" holderG="unit_match"><i class="bx bx-check me-1"></i> Khớp căn</button>
					<!-- Tra soát -->
					<button type="button" booking_id="{$booking_id}"{if $is_lock_payment_audit eq '1'} disabled{/if} booking_type="{$booking_type}" onClick="$Core.booking.open_activity(this,event)" activity_id="{$oneBookingMeta.id}" data-toggle="ripple" class="btn btn-sm btn-outline-warning" holderG="payment_audit"><i class="bx bx-share me-1"></i> Tra soát TDTT</button>
					<!-- Refund -->
					<button type="button" booking_id="{$booking_id}"{if $is_lock_recall eq '1'} disabled{/if} booking_type="{$booking_type}" onClick="$Core.booking.open_activity(this,event)" activity_id="{$oneBookingMeta.id}" data-toggle="ripple" class="btn btn-sm btn-outline-secondary" holderG="recall"><i class="bx bx-share me-1"></i> Thu hồi</button>
				{else}
					{if $oneBooking.state_id eq $smarty.const._BOOKING_STATE_PENDING_ID}
					<button type="button" booking_id="{$booking_id}" booking_type="{$booking_type}" onClick="$Core.booking.approved(this,event)" data-toggle="ripple" class="btn btn-sm btn-outline-danger js__booking-activity" activity_id="{$onePriority.id}"><i class="bx bx-check me-1"></i> Phê duyệt</button>
					{/if} 
					<!-- Ưu tiên -->
					<button type="button" booking_id="{$booking_id}" booking_type="{$booking_type}" onClick="$Core.booking.open_activity(this,event)" 
					data-toggle="ripple" class="btn btn-sm btn-outline-success js__booking-activity" holderG="priority" activity_id="{$onePriority.id}"{if $is_lock_priority eq '1'} disabled{/if}><i class="bx bx-{if $is_priority_added}edit{else}plus{/if} me-1"></i>Ưu tiên </button>
					<!-- Khớp cọc -->
					<button type="button" booking_id="{$booking_id}"{if $is_lock_matched eq '1'} disabled{/if} booking_type="{$booking_type}" onClick="$Core.booking.open_activity(this,event)" activity_id="{$oneBookingMeta.id}" data-toggle="ripple" class="btn btn-sm btn-outline-primary js__booking-activity" holderG="matched"><i class="bx bx-{if $is_matched_added eq '1'}edit{else}plus{/if}"></i> Khớp cọc</button>
					{if $oneBooking.state_id eq $smarty.const._BOOKING_STATE_REFUNDING_ID && $clsISO->checkPermissionGroup('ACCOUNTANT')}
					<!-- Đã hoàn cọc -->
					<button type="button" booking_id="{$booking_id}"{if $is_lock_refund eq '1'} disabled{/if} booking_type="{$booking_type}" onClick="$Core.booking.open_activity(this,event)" activity_id="{$oneBookingMeta.id}" data-toggle="ripple" class="btn btn-sm btn-outline-success js__booking-activity" holderG="refund"><i class="bx bx-{if $is_refund_added eq '1'}edit{else}plus{/if}"></i> Hoàn cọc</button>
					{/if}
					{if $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkDEV()}
					<!-- Y/c hoàn cọc -->
					<button type="button" booking_id="{$booking_id}"{if $is_lock_refund eq '1'} disabled{/if} booking_type="{$booking_type}" onClick="$Core.booking.open_activity(this,event)" activity_id="{$oneBookingMeta.id}" data-toggle="ripple" class="btn btn-sm btn-outline-warning js__booking-activity" holderG="refund_req"><i class="bx bx-{if $is_refund_added eq '1'}edit{else}plus{/if}"></i> Y/c hoàn cọc</button>
					{/if}
				{/if}
				</div>
			</div>
		</div>
		{/if}
		<div class="modal-body">
			{if $oneBooking.status_id eq $smarty.const._BOOKING_STATUS_CANCEL_ID && !empty($cancel_info)}
			<div class="alert alert-danger">
				<div class="d-flex align-items-center gap-3">
					<i class="bx bx-trash fs-2"></i>
					<div class="d-flex flex-column gap-0">
						<h5 class="text-upper text-fs-18 mb-1">Booking này đã bị huỷ.</h5>
						<p class="mb-0">Ngày hủy: {$clsISO->convertTimeToText($cancel_info.cancel_date, true)}</p>
						<p class="mb-0">Người hủy: {$clsProfile->getFullName($cancel_info.user_id, $oneUserCanceled)} </p>
						<p class="mb-0">Lý do hủy: {$cancel_info.cancel_reason}</p>
					</div>
				</div>
			</div>
			{/if}
			<div class="content_booking_{$booking_id}">
				<p class="text-muted text-center p-3">Đang tải dữ liệu</p>
			</div>
			<div class="widget-block">
				<div class="widget-header">Lịch sử booking</div>
				<div class="widget-content">
					{if !empty($action_logs)}
					<ul class="logs">
						{foreach from=$action_logs item = _oLog}
						<li style="overflow-wrap:break-word">
							{$clsISO->convertTimeToText($_oLog.reg_date, true)} : {$_oLog.content}
						</li>
						{/foreach}
					<ul>
					{else}
						<div class="p-4 text-center">
							<div class="mb-2">
								<img src="{$URL_IMAGES}/listing-empty.svg" class="w-px-75" />
							</div>
							<p class="text-muted mt-2">Chưa có lịch sử booking</p>
						</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
</div>