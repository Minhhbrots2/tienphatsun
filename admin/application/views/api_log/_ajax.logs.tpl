{if !empty($lstItem)}
	{foreach name=i from=$lstItem item = _oItem}
	<tr>
		<td><strong class="font-bold">{$clsUserAdmin->getFullName($_oItem.user_id)}</strong></td>
		<td>{$clsISO->formatDate($_oItem.reg_date,4)}</td>
	</tr>
	{/foreach}
{/if}