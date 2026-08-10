<div id="{$uid}" class="table-responsive freeze-table dragscroll text-nowrap">
	<table class="table table-billing table-striped" width="100%">
		<thead><tr>
			<th class="align-center">Mã GD</th>
			<th class="align-center">Ngày cọc TC</th>
			<th class="align-center">Dự án</th>
			<th class="align-center">Mã căn</th>
			<th class="align-center">Loại hình</th>
			<th class="align-center text-right">Tổng tiền GD</th>
		</tr></thead>
		{if !empty($list_billings)}
			{foreach name=i from=$list_billings key = billing_id item = _oBilling}
			<tr class="trBilling">
				<td class="text-left">
					<a href="javascript:void(0);" onClick="$Core.crm.view_billing(this,event)" billing_id="{$billing_id}">{$_oBilling.billing_code}</a>
				</td>
				<td class="text-left">{$clsISO->formatDate($_oBilling.deposit_date,3)}</td>
				<td class="text-left">{$_oBilling.poroject_name}</td>
				<td class="text-left">{$_oBilling.stock_code}</td>
				<td class="text-left">{$_oBilling.billing_type}</td>
				<td class="text-right">
					{$clsISO->formatNumberToEasyRead($_oBilling.totalgrand)} 
					{$clsISO->getRate()}
				</td>

			</tr>
			{/foreach}
		{else}
			<tr>
				<td class="text-center" colspan="6">
					<img src="{$URL_IMAGES}/listing-empty.svg" width="80px" />
					<p>Chưa có giao dịch nào</p>
				</td>
			</tr>
		{/if}
	</table>
</div>