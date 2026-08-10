{if $template_type eq '_modal'}
	<div class="modal-dialog modal-ipad-xl">
		<form class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Danh sách chi tiết booking</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body pt-2">
				<div class="alert alert-warning text-center mb-1">Tổng cộng có: 
					<strong class="total_records_{$uid}">0</strong> bookings
				</div>
				<div class="table-container overflow-x-auto no-shadow mb-1">
					<table cellpadding="0" cellspacing="0" class="table table-bordered text-nowrap mb-0" width="100%">
						<thead><tr>
							<th class="h-px-35 bg-lighter">Mã</th>
							<th class="h-px-35 bg-lighter">Ngày Booking</th>
							<th class="h-px-35 bg-lighter">Tên KH</th>
							<th class="h-px-35 bg-lighter">Họ tên Sales</th>
							<th class="h-px-35 bg-lighter">Dự án</th>
							<th class="h-px-35 bg-lighter">Số tiền</th>
							<th class="h-px-35 bg-lighter">Tình trạng</th>
						</tr></thead>
						<tbody class="holder_{$uid}">
							{section name=i loop=$list_preloaders}
							<tr>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							</tr>
							{/section}
						</tbody>
					</table>
				</div>
				<div class="pager_{$uid}"></div>
			</div>
			<div class="modal-footer">
				<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Hủy bỏ</button>
			</div>
		</form>
	</div>
{elseif $template_type eq '_list'}
	{if !empty($list_bookings)}
		{foreach from=$list_bookings item = _oBooking}
		{assign var = _oStaff value = $_oBooking.oStaff}
		{assign var = _more_information value = $_oBooking.more_information}
		<tr>
			<td class="align-center text-center">
				<span class="text-link">{$_oBooking.booking_code}</span>
			</td>
			<td class="align-center">{$clsISO->convertTimeToText($_oBooking.booking_date, true)}</td>
			<td class="align-center">{$_more_information.customer_name}</td>
			<td class="align-center">{$_oStaff.department_name}-{$clsProfile->getFullName($_oBooking.staff_id, $_oStaff)}</td>
			<td class="align-center">{$_oBooking.project_name}</td>
			<td class="align-center text-right">
				{$clsISO->formatNumberToEasyRead($_oBooking.amount)} 
				{$clsISO->getRate()}
			</td>
			<td class="align-center text-center">{$_oBooking.status_name}</td>
		</tr>
		{/foreach}
	{else}
		<tr class="nohover">
			<td class="text-center" colspan="11">
				<div class="py-3">
					<img src="{$URL_IMAGES}/DataEmpty.svg" class="w-px-50" />
					<p>Không có giao dịch nào !</p>
				</div>
			</td>
		</tr>
	{/if}
{/if}