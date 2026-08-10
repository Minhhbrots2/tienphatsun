{if $view_by == '_table'}
<table cellpadding="0" cellspacing="0" class="table table-booking table-striped table-bordered dragable mb-0" width="100%">
	<thead class="position-sticky top-0 zindex-3"><tr>
		<th class="text-center bg-lighter h-px-40">Mã BK</th>
		<th class="align-center bg-lighter h-px-40">Ngày Booking</th>
		<th class="align-center bg-lighter h-px-40">Họ tên KH / UNC</th>
		<th class="align-center bg-lighter h-px-40">Nội dung UNC</th>
		<th class="align-center bg-lighter h-px-40">Dự án/Phân khu</th>
		<th class="align-center bg-lighter h-px-40">K.Tầng</th>
		<th class="align-center bg-lighter h-px-40">Trục</th>
		<th class="align-center bg-lighter h-px-40 text-right">Số tiền</th>
		<th class="align-center bg-lighter h-px-40 text-center">T.Trạng</th>
		<th class="align-center bg-lighter h-px-40 text-center">T.thái</th>
		<th class="align-center bg-lighter h-px-40 text-center" width="35px"></th>
	</tr> </thead>
	<tbody>
	{if !empty($list_bookings)}
		{foreach name=i from=$list_bookings item = _oBooking}
			{assign var = _booking_id value = $_oBooking.booking_id}
			{assign var = _oStaff value = $_oBooking.oStaff}
			{assign var = _more_information value = $_oBooking.more_information}
			{assign var = _meta_information value = $_oBooking.meta_information}
			{assign var = _booking_type 	value = $_oBooking.booking_type}
			<tr>
				<td class="align-center text-left">
					{if $_oBooking.is_deposit_paid eq '1'}
					<span class="badge bg-label-danger">Đóng 10%</span>
					{/if}
					<a class="text-link" onClick="$Core.global.booking.view_booking(this, event)" booking_id="{$_booking_id}" 
						booking_type="{$_booking_type}">{$_oBooking.booking_code}</a>
				</td>
				<td class="align-center">{$clsISO->formatDate($_oBooking.booking_date,4)}</td>
				<td class="align-center">
					{if !empty($_more_information.customer_name)}
						{$_more_information.customer_name}
					{else}
						<span class="text-muted">--</span>
					{/if}
				</td>
				<td class="align-center">
					{if !empty($_more_information.content)}
						{$clsBooking->short_content($_more_information.content)}
					{else}
						<span class="text-muted">--</span>
					{/if}
				</td>
				<td class="align-center">{$_oBooking.project_name}</td>
				<td class="align-center text-center">{$_meta_information.floor_range}</td>
				<td class="align-center text-center">{$_meta_information.unit_axis}</td>
				<td class="align-center text-right">
					{$clsISO->formatNumberToEasyRead($_oBooking.amount)} {$clsISO->getRate()}
				</td>
				<td class="align-center text-center">{$_oBooking.status_name}</td>
				<td class="align-center text-center">{$_oBooking.state_name}</td>
				<td class="align-center text-center">
					<div class="dropdown">
						<button type="button" class="btn p-0 dropdown-toggle dropdown-button hide-arrow">
							<i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu w-px-125">
							<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.global.booking.view_booking(this,event)" 
							booking_id="{$_booking_id}" booking_type="{$_booking_type}"><i class="bx bx-bullseye me-1"></i> Xem</a>
							{if $_oBooking.status_id eq $smarty.const._BOOKING_STATUS_PENDING_ID}
							<hr size="0" class="dropdown-divider" />
							<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.booking.open(this,event)" 
								booking_id="{$_booking_id}" booking_type="{$_booking_type}"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
							<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.booking.cancel(this,event)" booking_id="{$_booking_id}" booking_type="{$_booking_type}"><i class="bx bx-no-entry me-1"></i> Hủy</a>
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
	</tbody>
<//table>
{else}
<table cellpadding="0" cellspacing="0" class="table table-booking table-bordered dragable mb-0" width="100%">
	<thead><tr>
		<th class="text-center bg-lighter h-px-40">Mã BK</th>
		<th class="align-center bg-lighter h-px-40">Ngày Booking</th>
		<th class="align-center bg-lighter h-px-40">Họ tên KH / UNC</th>
		<th class="align-center bg-lighter h-px-40">Nội dung UNC</th>
		<th class="align-center bg-lighter h-px-40">K. Tầng</th>
		<th class="align-center bg-lighter h-px-40">Trục</th>
		<th class="align-center bg-lighter h-px-40 text-right">Số tiền</th>
		<th class="align-center bg-lighter h-px-40 text-center">T.Trạng</th>
		<th class="align-center bg-lighter h-px-40 text-center">T.thái</th>
		<th class="align-center bg-lighter h-px-40 text-center" width="35px"></th>
	</tr></thead>
	<tbody>
		<tr><td colspan="10" class="h-px-10"></td></tr>
		{if !empty($arr_projects)}
		{foreach from = $arr_projects item = _oProject}
			{assign var = list_bookings value = $_oProject.list_bookings}
			{if !empty($list_bookings)}
			<tr>
				<th class="bg-label-warning h-px-40 fw-bold" colspan="10">
					<div class="sticky group-row-fixed top-0" style="width:fit-content;">
						<span class="text-main">{$_oProject.block_name} - {$_oProject.project_name}</span>
						<span class="badge bg-label-primary text-white rounded-pill text-fs-12">{$list_bookings|@count}</span>
					</div>
				</th>
			</tr>
			{foreach name=i from=$list_bookings item = _oBooking name=_i_booking}
				{assign var = _booking_id value = $_oBooking.booking_id}
				{assign var = _more_information value = $_oBooking.more_information}
				{assign var = _meta_information value = $_oBooking.meta_information}
				{assign var = _booking_type 	value = $_oBooking.booking_type}
				<tr class="{if $smarty.foreach._i_booking.index%2==0}even{else}odd{/if}">
					<td class="align-center text-left">
						{if $_oBooking.is_deposit_paid eq '1'}
						<span class="badge bg-label-danger">Đóng 10%</span>
						{/if}
						<a class="text-link" onClick="$Core.booking.view_booking(this, event)" booking_id="{$_booking_id}" booking_type="{$_booking_type}">{$_oBooking.booking_code}</a>
					</td>
					<td class="align-center">{$clsISO->formatDate($_oBooking.booking_date,4)}</td>
					<td class="align-center">
						{if !empty($_more_information.customer_name)}
							{$_more_information.customer_name}
						{else}
							<span class="text-muted">--</span>
						{/if}
					</td>
					<td class="align-center">
						{if !empty($_more_information.content)}
							{$clsBooking->short_content($_more_information.content)}
						{else}
							<span class="text-muted">--</span>
						{/if}
					</td>
					<td class="align-center text-center">{$_meta_information.floor_range}</td>
					<td class="align-center text-center">{$_meta_information.unit_axis}</td>
					
					<td class="align-center text-right">
						{$clsISO->formatNumberToEasyRead($_oBooking.amount)} 
						{$clsISO->getRate()}
					</td>
					<td class="align-center text-center">{$_oBooking.status_name}</td>
					<td class="align-center text-center">{$_oBooking.state_name}</td>
					<td class="align-center text-center">
						<div class="dropdown">
							<button type="button" class="btn p-0 dropdown-toggle dropdown-button hide-arrow">
								<i class="bx bx-dots-vertical-rounded"></i>
							</button>
							<div class="dropdown-menu w-px-125">
								<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.booking.view_booking(this,event)" 
								booking_id="{$_booking_id}" booking_type="{$_booking_type}"><i class="bx bx-bullseye me-1"></i> Xem</a>
								{if $_oBooking.status_id eq $smarty.const._BOOKING_STATUS_PENDING_ID}
								<hr size="0" class="dropdown-divider" />
								<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.booking.open(this,event)" 
									booking_id="{$_booking_id}" booking_type="{$_booking_type}"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
								<a class="dropdown-item{if $_oBilling.is_cancel eq '1'} disabled{/if}" href="javascript:void(0);" onClick="$Core.booking.cancel(this,event)" booking_id="{$_booking_id}" booking_type="{$_booking_type}"><i class="bx bx-no-entry me-1"></i> Hủy</a>
								{/if}
							</div>
						</div>
					</td>
				</tr>
			{/foreach}
			{/if}	
		{/foreach}
		{else}
			<tr>
				<td class="text-center" colspan="9">
					Không có booking nào !
				</td>
			</tr>
		{/if}
		</tbody>
	</table>
	<style>
		.group-row-fixed {
			will-change: transform;
			transform: translateZ(0);
		}
	</style>
{/if}