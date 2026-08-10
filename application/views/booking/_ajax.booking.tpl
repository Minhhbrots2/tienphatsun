{if !empty($list_bookings)}
	{foreach name=i from=$list_bookings item = _oBooking}
	{assign var = _booking_id value = $_oBooking.booking_id}
	{assign var = _oStaff value = $_oBooking.oStaff}
	{assign var = _more_information value = $_oBooking.more_information}
	{assign var = _meta_information value = $_oBooking.meta_information}
	<tr>
		<td class="align-center text-left">
			{if $_oBooking.is_deposit_paid eq '1'}
			<span class="badge bg-label-danger">Đóng 10%</span>
			{/if}
			<a class="text-link" onClick="$Core.booking.view_booking(this, event)" booking_id="{$_booking_id}" booking_type="{$booking_type}">{$_oBooking.booking_code}</a>
		</td>
		<td class="align-center">{$clsISO->formatDate($_oBooking.booking_date,4)}</td>
		<td class="align-center">
			{if !empty($_more_information.customer_name)}
				{$_more_information.customer_name}
			{else}
				<span class="text-muted">--</span>
			{/if}
		</td>
		{if $booking_type eq $smarty.const._BOOKING_TYPE_INTERNAL_ID}
		<td class="align-center">
			{if !empty($_more_information.content)}
				{$clsBooking->short_content($_more_information.content)}
			{else}
				<span class="text-muted">--</span>
			{/if}
		</td>
		<td class="align-center">
			<span class="badge bg-label-primary">{$_oStaff.department_name}</span>
			{$clsProfile->getIndentityV2($_oBooking.staff_id, $_oStaff)}
		</td>
		<td class="align-center text-center">
			{if !empty($_meta_information.floor_range)}
				{$_meta_information.floor_range}
			{else}
				<span class="text-muted">--</span>
			{/if}
		</td>
		<td class="align-center text-center">
			{if !empty($_meta_information.unit_axis)}
				{$_meta_information.unit_axis}
			{else}
				<span class="text-muted">--</span>
			{/if}
		</td>
		<td class="align-center text-center">
			{if !empty($_meta_information.priority)}
				{$clsISO->parseNumber($_meta_information.priority)}
			{else}
				<span class="text-muted">00</span>
			{/if}
		</td>
		{else}
		<td class="align-center">{$_oBooking.bedroom_name}</td>
		<td class="align-center text-center">{$_more_information.unit_axis}</td>
		<td class="align-center text-center">{$_more_information.floor_range}</td>
		<td class="align-center text-center">{$clsBooking->get_html_floor_type($_more_information.floor_type)}</td>
		<td class="align-center">{$_more_information.trans_code}</td>
		{/if}
		<td class="align-center">{$_oBooking.project_name}</td>
		<td class="align-center text-right">
			{$clsISO->formatNumberToEasyRead($_oBooking.amount)} 
			{$clsISO->getRate()}
		</td>
		<td class="align-center text-center">{$_oBooking.status_name}</td>
		{if $booking_type eq $smarty.const._BOOKING_TYPE_INTERNAL_ID}
		<td class="align-center text-center">{$_oBooking.state_name}</td>
		{/if}
		<td class="align-center">{$clsISO->formatDate($_oBooking.reg_date,4)}</td>
		<td class="align-center text-center">
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle dropdown-button hide-arrow">
					<i class="bx bx-dots-vertical-rounded"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-end" style="min-width:10rem">
					<a class="dropdown-item cursor-pointer" onClick="$Core.booking.view_booking(this,event)" booking_id="{$_booking_id}" 
						booking_type="{$booking_type}"><i class="bx bx-bullseye me-1"></i> Xem</a>
					{if $permiss_admin eq '1' || $clsISO->checkDEV()}
					<a class="dropdown-item cursor-pointer{if $_oBooking.is_cancel eq '1'} disabled{/if}" booking_type="{$booking_type}" 
						onClick="$Core.booking.open(this,event)" booking_id="{$_booking_id}"><i class="bx bx-edit-alt me-1"></i> Cập nhật</a>
					{/if}
					{if $booking_type == $smarty.const._BOOKING_TYPE_INTERNAL_ID}
						{if $clsISO->checkDEV()}
						<a class="dropdown-item cursor-pointer text-danger{if $_oBooking.is_cancel eq '1'} disabled{/if}" booking_id="{$_booking_id}" booking_type="{$booking_type}" onClick="$Core.booking.open_state(this,event)" ><i class="bx bx-edit me-1"></i> Trạng thái <span class="badge badge-demo bg-label-danger ms-auto">DEV</span></a>
						{/if}
						{if $_oBooking.state_id eq $smarty.const._BOOKING_STATE_PENDING_ID && $permiss_admin eq '1'}
						<a class="dropdown-item cursor-pointer text-primary" onClick="$Core.booking.approved(this,event)" 
							booking_id="{$_booking_id}" booking_type="{$booking_type}"><i class="bx bx-check me-1"></i> Phê duyệt</a>
						{/if}
						{if $_oBooking.state_id eq $smarty.const._BOOKING_STATE_REFUNDING_ID && $permiss_accounting eq '1'}
						<hr size="0" class="dropdown-divider" />
						<a class="dropdown-item cursor-pointer text-success" onClick="$Core.booking.open_activity(this,event)" 
							booking_id="{$_booking_id}" booking_type="{$booking_type}" holderG="refund"><i class="bx bx-share me-1"></i> Xác nhận hoàn</a>
						{/if}
					{/if}
					{if $permiss_admin eq '1' || $clsISO->checkDEV()}
					<hr size="0" class="dropdown-divider" />
					<a class="dropdown-item cursor-pointer{if $_oBooking.is_cancel eq '1'} disabled{/if}" booking_type="{$booking_type}" 
						onClick="$Core.booking.open_confirm(this,event)" booking_id="{$_booking_id}"><i class="bx bx-no-entry me-1"></i> Hủy bỏ</a>
					{/if}
				</div>
			</div>
		</td>
	</tr>
	{/foreach}
{else}
	<tr>
		<td class="text-center" colspan="11">
			Không có giao dịch nào !
		</td>
	</tr>
{/if}