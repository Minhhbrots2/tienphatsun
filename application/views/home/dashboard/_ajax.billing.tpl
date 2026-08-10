{if !empty($list_billings)}
	{foreach name=i from=$list_billings item = _oBilling}
	<tr class="trBilling">
		<td class="text-left"><a href="javascript:void(0);" onClick="view_billing(this, event); return false;" 
			billing_id="{$_oBilling.billing_id}">{if $_oBilling.is_executable eq '1'}<span class="badge bg-label-warning">ĐQ</span>{/if} {$_oBilling.stock_code}</a></td>
		<td class="text-left">{$clsISO->formatDate($_oBilling.deposit_date,3)}</td>
		<td class="text-left">{$clsProfile->getIndentityV2($_oBilling.staff_id, $_oBilling.oneStaff, true)}</td>
		<td class="text-left">{$_oBilling.project_name}</td>
		<td class="text-left">{$_oBilling.block_name}</td>
		<td class="text-left">{$_oBilling.billing_type}</td>
		<td class="text-right text-main">
			{$clsISO->formatNumberToEasyRead($_oBilling.totalgrand)} 
			{$clsISO->getRate()}
		</td>
	</tr>
	{/foreach}
{else}
	_empty
{/if}
