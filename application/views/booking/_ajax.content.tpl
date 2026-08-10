<div class="row">
	<div class="col-12 col-md-8">
		<div class="widget-block">
			<div class="widget-header">Thông tin booking</div>
			<div class="widget-content mb-3">
				<table class="clientssummarystats" width="100%">
					<tr>
						<td width="{if $deviceType eq 'phone'}40{else}30{/if}%" class="text-right" >Ngày booking</td>
						<td colspan="3">{$clsISO->convertTimeToText($oneBooking.booking_date, true)}</td>
					</tr>
					{if $booking_type eq $smarty.const._BOOKING_TYPE_INVESTOR_ID}<tr>
						<td class="text-right">Họ & tên</td>
						<td class="fw-bold" colspan="3">{$oneBooking.customer_name}</td>
					</tr>
					<tr>
						<td class="text-right">Số CCCD</td>
						<td class="fw-bold" colspan="3">{$more_information.customer_idcard}</td>
					</tr>
					{else}
					<tr>
						<td class="text-right">Sale Booking</td>
						<td class="fw-bold" colspan="3">
							{$clsProfile->getIndentityV3($more_information.staff_id, true)}
						</td>
					</tr>
					<tr>
						<td class="text-right">Họ & tên KH/UNC</td>
						<td class="fw-bold" colspan="3">{$oneBooking.customer_name}</td>
					</tr>
					{/if}
					<tr>
						<td class="text-right">Dự án</td>
						<td colspan="3">
							{if $oneBooking.project_id gt '0'}
								{$clsProject->getTitle($oneBooking.project_id)}
							{else}
								N/A
							{/if}
						</td>
					</tr>
					<tr>
						<td class="text-right">Phân khu</td>
						<td colspan="3">{$oneBooking.block_name}</td>
					</tr>
					<tr>
						<td class="text-right">Tòa nhà</td>
						<td colspan="3">{$oneBooking.building_name}</td>
					</tr>
					<tr>
						<td class="text-right">Số tiền</td>
						<td class="text-main fw-bold" colspan="3">
							{$clsISO->formatPrice($oneBooking.amount)} {$clsISO->getRate()}
						</td>
					</tr>
					<tr>
						<td class="text-right">File UNC</td>
						<td colspan="3">
							{if !empty($more_information.payment_order)}
							<a class="download" target="_blank" data-fancybox="true" href="{$more_information.payment_order}">File UNC</a>
							{else}
							---
							{/if}
						</td>
					</tr>
					<tr>
						<td class="text-right">Nội dung</td>
						<td colspan="3">{$more_information.content}</td>
					</tr>
					<tr>
						<td class="text-right">Ngày tạo</td>
						<td colspan="3">{$clsISO->convertTimeToText($oneBooking.reg_date, true)}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-4">
		<div class="card sticky mb-2">
			{if $booking_type eq $smarty.const._BOOKING_TYPE_INVESTOR_ID}
			<div class="card-header">
				<h5 class="card-title text-warning mb-0 fs-16">
					<i class="bx bx-shopping-bag"></i> Thông tin booking căn
				</h5>
			</div>
			<div class="card-body">
				<div class="d-flex flex-column gap-0 mb-2">
					<span class="text-muted text-fs-12">Loại căn:</span>
					<strong>{$oneBooking.bedroom_name}</strong>
				</div>
				<div class="d-flex flex-column gap-0 mb-2">
					<span class="text-muted text-fs-12">Trục căn:</span>
					<strong>{if !empty($more_information.unit_axis)}{$more_information.unit_axis}{else}N/A{/if}</strong>
				</div>
				<div class="d-flex flex-column gap-0 mb-2">
					<span class="text-muted text-fs-12">Khoảng tầng:</span>
					<strong>{if !empty($more_information.floor_range)}{$more_information.floor_range}{else}N/A{/if}</strong>
				</div>
				<div class="d-flex flex-column gap-0 mb-2">
					<span class="text-muted text-fs-12">Loại tầng:</span>
					<strong>
						{if !empty($more_information.floor_type)}
							{$clsBooking->getFloorType($more_information.floor_type)}
						{else}
							N/A
						{/if}
					</strong>
				</div>
			</div>
			{else}
			<div class="card-header">
				<h5 class="card-title text-warning mb-0 fs-16">
					<i class="bx bx-arrow-from-bottom"></i> Chuyển ưu tiên
				</h5>
			</div>
			<div class="card-body">
				{assign var =_more_information value = $onePriority.more_information}
				{if !empty($_more_information)}
				<div class="d-flex flex-column gap-0 mb-2">
					<span class="text-muted text-fs-12">Ngày thực hiện:</span>
					<strong>{$clsISO->convertTimeToText($onePriority.action_date, true)}</strong>
				</div>
				<div class="d-flex flex-column gap-0 mb-2">
					<span class="text-muted text-fs-12">Trục căn:</span>
					<strong>
						{if !empty($_more_information.unit_axis)}
							{$_more_information.unit_axis}
						{else}
							N/A
						{/if}
					</strong>
				</div>
				<div class="d-flex flex-column gap-0 mb-2">
					<span class="text-muted text-fs-12">Khoảng tầng:</span>
					<strong>
						{if !empty($_more_information.floor_range)}
							{$_more_information.floor_range}
						{else}
							N/A
						{/if}
					</strong>
				</div>
				<div class="d-flex flex-column gap-0">
					<span class="text-muted text-fs-12">Ưu tiên:</span>
					<strong class="text-main">
						{if !empty($_more_information.priority)}
							{$_more_information.priority}
						{else}
							N/A
						{/if}
					</strong>
				</div>
				{else}
					<div class="d-flex flex-column gap-0 mb-2">
						<span class="text-muted text-fs-12">Ngày thực hiện:</span>
						<strong class="text-muted"> --- </strong>
					</div>
					<div class="d-flex flex-column gap-0 mb-2">
						<span class="text-muted text-fs-12">Trục căn:</span>
						<strong class="text-muted"> --- </strong>
					</div>
					<div class="d-flex flex-column gap-0 mb-2">
						<span class="text-muted text-fs-12">Khoảng tầng:</span>
						<strong class="text-muted"> --- </strong>
					</div>
					<div class="d-flex flex-column gap-0">
						<span class="text-muted text-fs-12">Ưu tiên:</span>
						<strong class="text-muted"> --- </strong>
					</div>
				{/if}
			</div>
			{/if}
		</div>
	</div>
</div>
{if $booking_type eq $smarty.const._BOOKING_TYPE_INVESTOR_ID}
	{if $oneBooking.status_id eq $smarty.const._BOOKING_STATUS_RECALL_ID}
	{assign var =_more_information value = $oneBookingMeta.more_information}
	<div class="bg-secondary rounded-2 p-3 mb-3">
		<h3 class="fs-6 mb-2 text-white"> 
			<span>Thông tin thu hồi</span>
			<a><i class="bx bx-x"></i></a>
		</h3>
		<table class="clientssummarystats rounded-2 overflow-hidden" width="100%">
			<tr>
				<td width="{if $deviceType eq 'phone'}40{else}30{/if}%" class="text-right">Ngày thu hồi</td>
				<td colspan="3">{$clsISO->convertTimeToText($oneBookingMeta.action_date, true)}</td>
			</tr>
			<tr>
				<td class="text-right">Ghi chú</td>
				<td colspan="3">
					{if !empty($_more_information.notes)}
						{$_more_information.notes}
					{else}
						---
					{/if}
				</td>
			</tr>
		</table>
	</div>
	{elseif $oneBooking.status_id eq $smarty.const._BOOKING_STATUS_UNIT_MATCH_ID}
	{assign var =_more_information value = $oneBookingMeta.more_information}
	<div class="bg-label-primary rounded-2 p-3 mb-3">
		<h3 class="d-flex justify-content-between align-items-center fs-6 mb-2">
			<span>Thông tin khớp căn</span>
			<a onClick="$Core.booking.cancel_activity(this, event)" holderG="unit_match" booking_id="{$booking_id}" 
				activity_id="{$oneBookingMeta.id}" title="Huỷ" class="btn btn-sm btn-icon rounded-pill btn-link"><i class="bx bx-x"></i></a>
		</h3>
		<table class="clientssummarystats rounded-2 overflow-hidden" width="100%">
			<tr>
				<td width="{if $deviceType eq 'phone'}40{else}30{/if}%" class="text-right">Ngày khớp</td>
				<td colspan="3">{$clsISO->convertTimeToText($oneBookingMeta.action_date, true)}</td>
			</tr>
			<tr>
				<td class="text-right">Mã căn</td>
				<td colspan="3">{$_more_information.stock_code}</td>
			</tr>
			<tr>
				<td class="text-right">Người đừng tên</td>
				<td colspan="3">{$_more_information.name_owner}</td>
			</tr>
			<tr>
				<td class="text-right">Link XNDK</td>
				<td class="text-break" colspan="3">{$_more_information.reg_sign_link}</td>
			</tr>
			<tr>
				<td class="text-right">File UNC gốc</td>
				<td colspan="3">{if !empty($_more_information.payment_order)}<a class="download" target="_blank" data-fancybox="true" 
					href="{$_more_information.payment_order}">File UNC</a>{else}--{/if}</td>
			</tr>
			<tr>
				<td class="text-right">File UNC tra soát</td>
				<td colspan="3">{if !empty($_more_information.payment_order_check)}<a class="download" target="_blank" data-fancybox="true" 
					href="{$_more_information.payment_order_check}">File UNC</a>{else}--{/if}</td>
			</tr>
		</table>
	</div>
	{elseif $oneBooking.status_id eq $smarty.const._BOOKING_STATUS_PAYMENT_AUDIT_ID}
	{assign var =_more_information value = $oneBookingMeta.more_information}
	<div class="bg-label-warning rounded-2 p-3 mb-3">
		<h3 class="d-flex justify-content-between align-items-center fs-6 mb-2">
			<span>Thông tin tra soát sang TĐTT</span>
			<a onClick="$Core.booking.cancel_activity(this, event)" holderG="payment_audit" booking_id="{$booking_id}" 
				activity_id="{$oneBookingMeta.id}" title="Huỷ" class="btn btn-sm btn-icon rounded-pill btn-link"><i class="bx bx-x"></i></a>
		</h3>
		<table class="clientssummarystats rounded-2 overflow-hidden" width="100%">
			<tr>
				<td width="{if $deviceType eq 'phone'}40{else}30{/if}%" class="text-right">Ngày hoàn</td>
				<td colspan="3">{$clsISO->convertTimeToText($oneBookingMeta.action_date, true)}</td>
			</tr>
			<tr>
				<td class="text-right">Mã căn</td>
				<td colspan="3">{$_more_information.stock_code}</td>
			</tr>
			<tr>
				<td class="text-right">Nội dung</td>
				<td colspan="3">{$_more_information.content}</td>
			</tr>
			<tr>
				<td class="text-right">File UNC gốc</td>
				<td colspan="3"><a class="download" target="_blank" data-fancybox="true" 
					href="{$_more_information.payment_order}">File UNC</a></td>
			</tr>
			<tr>
				<td class="text-right">File UNC tra soát</td>
				<td colspan="3"><a class="download" target="_blank" data-fancybox="true" 
					href="{$_more_information.payment_order_check}">File UNC</a></td>
			</tr>
		</table>
	</div>
	{/if}
{else}
	{if $oneBooking.is_deposit_paid && !empty($oneDepositPaid)}
	{assign var =_more_information value = $oneDepositPaid.more_information}
	<div class="bg-label-danger rounded-2 p-3 mb-2">
		<h3 class="d-flex justify-content-between align-items-center fs-6 mb-2">
			<span>Thông tin đóng 10% vào Công ty</span>
			<a onClick="$Core.booking.cancel_activity(this, event)" holderG="matched" booking_id="{$booking_id}" 
				activity_id="{$oneDepositPaid.id}" title="Huỷ" class="btn btn-sm btn-icon rounded-pill btn-link"><i class="bx bx-x"></i></a>
		</h3>
		<table class="clientssummarystats rounded-2 overflow-hidden" width="100%">
			<tr>
				<td width="{if $deviceType eq 'phone'}40{else}30{/if}%" class="text-right">Ngày khớp</td>
				<td colspan="3">{$clsISO->convertTimeToText($oneDepositPaid.action_date, true)}</td>
			</tr>
			<tr>
				<td class="text-right">Mã căn</td>
				<td colspan="3">{$_more_information.stock_code}</td>
			</tr>
			<tr>
				<td class="text-right">Số tiền</td>
				<td colspan="3">{$clsISO->formatPrice($_more_information.amount)} {$clsISO->getRate()}</td>
			</tr>
			<tr>
				<td  class="text-right">Người đứng số</td>
				<td colspan="3">
					{$_more_information.payer_name}
				</td>
			</tr>
			<tr>
				<td  class="text-right">Mã FT</td>
				<td colspan="3">
					{$_more_information.trans_code}
				</td>
			</tr>
		</table>
	</div>
	{/if}
	{if $oneBooking.status_id eq $smarty.const._BOOKING_MATCHED_DEPOSIT_ID}
	{assign var =_more_information value = $oneBookingMeta.more_information}
	<div class="bg-label-primary rounded-2 p-3 mb-3">
		<h3 class="d-flex justify-content-between align-items-center fs-6 mb-2">
			<span>Thông tin khớp cọc</span>
			<a onClick="$Core.booking.cancel_activity(this, event)" holderG="matched" booking_id="{$booking_id}" 
				activity_id="{$oneBookingMeta.id}" title="Huỷ" class="btn btn-sm btn-icon rounded-pill btn-link"><i class="bx bx-x"></i></a>
		</h3>
		<table class="clientssummarystats rounded-2 overflow-hidden" width="100%">
			<tr>
				<td width="{if $deviceType eq 'phone'}40{else}30{/if}%" class="text-right">Ngày khớp</td>
				<td colspan="3">{$clsISO->convertTimeToText($oneBookingMeta.action_date, true)}</td>
			</tr>
			<tr>
				<td class="text-right">Mã căn</td>
				<td colspan="3">{$_more_information.stock_code}
					{if !empty($oneBilling)}
						<a href="javascript:void()" onClick="$Core.global.billing.view_billing(this, event)" billing_id="{$oneBilling.billing_id}" class="download">{$oneBilling.billing_code}</a>
					{/if}
				</td>
			</tr>
			<tr>
				<td class="text-right">Số tiền</td>
				<td colspan="3">{$clsISO->formatPrice($_more_information.amount)} {$clsISO->getRate()}</td>
			</tr>
			<tr>
				<td  class="text-right">Người đứng số</td>
				<td colspan="3">
					{$clsProfile->getFullName($_more_information.staff_sale_id)}
				</td>
			</tr>
		</table>
	</div>
	{/if}
	{if $oneBooking.status_id eq $smarty.const._BOOKING_REFUND_DEPOSIT_ID}
		{assign var =_more_information value = $oneBookingMeta.more_information}
		<div class="bg-label-warning rounded-2 p-3 mb-3">
			{if $oneBooking.state_id eq $smarty.const._BOOKING_STATE_REFUNDED_ID}
			<h3 class="d-flex justify-content-between align-items-center fs-6 mb-2">
				<span>Thông tin hoàn cọc</span>
				<a onClick="$Core.booking.cancel_activity(this, event)" holderG="refund" booking_id="{$booking_id}" 
				activity_id="{$oneBookingMeta.id}" title="Huỷ" class="btn btn-sm btn-icon rounded-pill btn-link"><i class="bx bx-x"></i></a>
			</h3>
			<table class="clientssummarystats rounded-2 overflow-hidden" width="100%">
				<tr>
					<td width="{if $deviceType eq 'phone'}40{else}30{/if}%" class="text-right">Ngày hoàn</td>
					<td colspan="3">{$clsISO->convertTimeToText($oneBookingMeta.action_date, true)}</td>
				</tr>
				<tr>
					<td class="text-right">Người nhận</td>
					<td colspan="3">{$clsProfile->getFullName($_more_information.receiver_id)}</td>
				</tr>
				<tr>
					<td class="text-right">Số tiền</td>
					<td colspan="3">{$clsISO->formatPrice($_more_information.amount)} {$clsISO->getRate()}</td>
				</tr>
				<tr>
					<td class="text-right">File UNC</td>
					<td colspan="3"><a class="download" target="_blank" data-fancybox="true" 
						href="{$_more_information.payment_order}">File UNC</a></td>
				</tr>
			</table>
			{else}
			<h3 class="d-flex justify-content-between align-items-center fs-6 mb-2">
				<span>Thông tin y/c hoàn cọc</span>
				<a onClick="$Core.booking.cancel_activity(this, event)" holderG="refund" booking_id="{$booking_id}" 
				activity_id="{$oneBookingMeta.id}" title="Huỷ" class="btn btn-sm btn-icon rounded-pill btn-link">
					<i class="bx bx-x text-muted"></i>
				</a>
			</h3>
			<table class="clientssummarystats rounded-2 overflow-hidden" width="100%">
				<tr>
					<td width="{if $deviceType eq 'phone'}40{else}30{/if}%" class="text-right">Ngày hoàn</td>
					<td colspan="3">{$clsISO->convertTimeToText($oneBookingMeta.action_date, true)}</td>
				</tr>
				<tr>
					<td class="text-right">Người nhận</td>
					<td colspan="3">{$clsProfile->getFullName($_more_information.receiver_id)}</td>
				</tr>
				<tr>
					<td class="text-right">Số tiền</td>
					<td colspan="3">{$clsISO->formatPrice($_more_information.amount)} {$clsISO->getRate()}</td>
				</tr>
				{if !empty($more_information.notes)}
				<tr>
					<td class="text-right">Ghi chú</td>
					<td colspan="3">{$more_information.notes}</td>
				</tr>
				{/if}
			</table>
			{/if}
		</div>
	{/if}
{/if}