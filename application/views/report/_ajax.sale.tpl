<div class="bg-eee radius-3 p-3 mb-3">
	<div class="d-flex flex-wrap gap-2 overflow-auto justify-content-between mb-2">
		<div class="lst_STATUS_BOOKING bg-white p-3 radius-3 cursor-pointer" onClick="$Core.report.do_action(this, event)" 
		billing_type="all" stt_color="#FFF">
			<p class="fs-12 text-muted">Tất cả</p>
			<div class="d-flex justify-content-between align-items-center">
				<span class="fs-16 text-bold">{$total_billings}</span>
			</div>
		</div>
		{foreach name=i from=$list_billing_types item = _oItem}
		<div class="lst_STATUS_BOOKING bg-white p-3 rounded-2 cursor-pointer" onClick="$Core.report.do_action(this, event)" 
		billing_type="{$_oItem.property_id}"{if $billing_type eq $_oItem.property_id} style="background-color:{$_oItem.bgcolor} !important;color:#FFF !important"{/if}>
			<p class="fs-12 text-nowrap">{$_oItem.title}</p>
			<div class="d-flex justify-content-between align-items-center">
				<span class="fs-16 text-bold">{$_oItem.total_billings}</span>
			</div>
		</div>
		{/foreach}
		<div class="p-3 bg-white rounded-2 cursor-pointer detail_profit_sale">
			<p>Tất cả doanh số</p>
			<h3 class="mb-0 text-primary">{$clsISO->formatNumberToEasyRead($total_sales)}</h3>
		</div>
	</div>
</div>
<div class="table-container overflow-x-auto no-shadow text-nowrap">
	<table cellpadding="0" cellspacing="0" class="table table-sort table-bordered" width="100%">
		<thead><tr>
			{if $deviceType ne 'phone'}
			<th width="30px" class="align-center h-px-35 nosort bg-lighter">STT</th>
			<th width="10%" class="align-center h-px-35 nosort text-left bg-lighter">Mã NV</th>
			<th width="20%" class="align-center h-px-35 nosort text-left bg-lighter">Họ và tên</th>
			<th width="10%" class="align-center h-px-35 nosort bg-lighter">Mã Phòng</th>
			<th width="20%" class="align-center h-px-35 nosort bg-lighter">Chức vụ</th>
			<th width="100px" class="align-center h-px-35 nosort bg-lighter">Ngày vào làm</th>
			<th width="90px" class="align-center h-px-35 sortable text-center bg-lighter">Số GD</th>
			<th class="align-center text-left h-px-35 sortable bg-lighter">Doanh số</th>
			<th width="100px" class="align-center h-px-35 nosort bg-lighter">Ngày vào làm</th>
			{else}
			<th width="45%" class="align-center h-px-35 nosort text-left bg-lighter">Họ và tên</th>
			<th width="40px" class="align-center h-px-35 sortable text-center bg-lighter">Số GD</th>
			<th class="align-center text-left h-px-35 sortable bg-lighter">Doanh số</th>
			{/if}
		</tr></thead>
		{if !empty($list_staffs)}
			{foreach name=i from=$list_staffs item = _oStaff}
			<tr>
				{if $deviceType ne 'phone'}
				<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
				<td class="align-center text-left">{$_oStaff.code}</td>
				{/if}
				<td><strong>{$clsProfile->getFullName($_oStaff.profile_id, $_oStaff)}</strong></td>
				{if $deviceType ne 'phone'}
				<td class="align-center text-center">{$_oStaff.department_name}</td>
				<td class="align-center text-left">{$_oStaff.role}</td>
				<td class="align-center text-left">{$_oStaff.rangeDate}</td>
				{/if}
				
				<td class="align-center text-center">{$_oStaff.total_billings}</td>
				<td class="align-center text-left">
					{if $deviceType eq 'phone'}
						{$clsISO->shortNumber($_oStaff.total_sales)}
					{else}
						{$clsISO->formatNumberToEasyRead($_oStaff.total_sales)} {$clsISO->getRate()}
					{/if}
				</td>
				{if $deviceType ne 'phone'}
				<td class="align-center text-left">{$_oStaff.startDate}</td>
				{/if}
			</tr>
			{/foreach}
		{/if}
	</table>
</div>