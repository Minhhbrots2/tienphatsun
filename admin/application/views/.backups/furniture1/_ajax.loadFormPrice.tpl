{foreach from=$arr_data key=key item=item}
<tr class="{cycle values="row1,row2"}">
	<td class="text-left"><input type="hidden" name="title_{$key}" value="{$item}">{$item}</td>
	<td class="text-left"><input type="text" class="form-control price-In" name="price_{$key}" value="{if $lstProperty[$key].price}{$lstProperty[$key].price}{else}0{/if}"></td>
</tr>
{/foreach}