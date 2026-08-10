{if !empty($arr_data_admin)}
	{foreach from=$arr_data_admin item=_oAdmin}
		<tr class="trBilling">
			<td class="text-left">{$_oAdmin.admin_name}</td>
			<td class="text-center">{$_oAdmin.total_contract}</td>
			<td class="text-center">{$_oAdmin.total_agree}</td>
		</tr>
	{/foreach}
{else}
	<tr class="trBilling">
		<td class="text-center" colspan="3">Danh sách trống</td>
	</tr>
{/if}