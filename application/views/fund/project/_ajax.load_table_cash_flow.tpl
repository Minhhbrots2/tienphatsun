{if !empty($data_table)}
	{foreach name=i from=$data_table item = _oItem}
	<tr class="text-nowrap">
		{if $deviceType ne 'phone'}
			<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
		{/if}
		<td class="align-center text-left">{$_oItem.project_name}</td>
		<td class="align-center text-right">{$_oItem.total_REVENUE}</td>
		<td class="align-center text-right">{$_oItem.total_THUCCHI}</td>
		<td class="align-center text-right">{$_oItem.total_profit}</td>
		<td class="align-center text-right">{$_oItem.total_MKT}</td>
		<td class="align-center text-right">{$_oItem.total_commission}</td>
	</tr>
	{/foreach}
{else}
	<tr> <td class="text-center" colspan="{if $deviceType ne 'phone'}8{else}7{/if}" >Danh sách trống</td></tr>
{/if}