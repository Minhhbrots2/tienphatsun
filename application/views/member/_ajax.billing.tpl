<div id="{$uid}" class="table-container no-shadow overflow-x-auto text-nowrap">
	<table border="0" cellpadding="0" cellspacing="0" class="table mb-0" width="100%">
		<thead><tr>
			<th class="align-center bg-lighter h-px-35">Mã căn</th>
			<th class="align-center bg-lighter h-px-35">Ngày cọc</th>
			<th class="align-center bg-lighter h-px-35">Ngày ký HĐMB</th>
			<th class="align-center bg-lighter h-px-35">Loại hình</th>
			<th class="align-center bg-lighter h-px-35">Dự án</th>
			<th class="align-center bg-lighter h-px-35 w-px-150">Tổng tiền</th>
			<th class="align-center bg-lighter h-px-35" width="35px"></th>
		</tr></thead>
		{if !empty($list_billings)}
			{foreach name=i from=$list_billings item = _oBilling}
			<tr class="trBilling{if $_oBilling.is_cancel eq '1'} bg-cancel{/if}">
				
				<td class="text-left"><a href="javascript:void(0);" onClick="$Core.global.billing.view_billing(this, event); return false;" billing_id="{$_oBilling.billing_id}">{$_oBilling.stock_code}</a></td>
				<td class="text-left">{$clsISO->formatDate($_oBilling.deposit_date,3)}</td>
				<td class="text-left">
					{if !empty($_oBilling.contract_date)}
						{$clsISO->formatDate($_oBilling.contract_date,3)}
					{else}
						---
					{/if}
				</td>
				<td class="text-left">{$_oBilling.billing_type}</td>
				<td class="text-left">{$_oBilling.poroject_name}</td>
				<td class="text-right">{$clsISO->formatNumberToEasyRead($_oBilling.totalgrand)}</td>
				<td class="text-center">
					<div class="dropdown">
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
							data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu w-px-100">
							<a class="dropdown-item" onClick="view_billing(this,event)" billing_id="{$_oBilling.billing_id}" 
							href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
							{if $profile_id eq $_oBilling.staff_id && $_oBilling.ns_confirm eq '0' && $_oBilling.ms_confirm eq '0'}
							<a class="dropdown-item" href="javascript:void(0);" onClick="delete_billing(this,event)" 
								billing_id="{$_oBilling.billing_id}">
								<i class="bx bx-trash me-1"></i> Xóa</a>
							{/if}
						</div>
					</div>
				</td>
			</tr>
			{/foreach}
		{else}
			<tr><td class="text-center" colspan="8">
				<img src="{$URL_IMAGES}/illustration-empty-results.svg" class="w-px-200" />
				<p class="text-muted">Chưa có giao dịch</p>
			</td></tr>
		{/if}
	</table>
</div>
<div id="pager_staff_building_{$uid}"></div>