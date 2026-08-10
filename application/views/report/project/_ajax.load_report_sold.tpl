{if !empty($list_stock_sold)}
	{foreach from=$list_stock_sold item=_oItem key=key name=i}
		<tr class="nohover">
			{if $deviceType ne "phone"}<td class="text-nowrap h-px-30 text-center">{$smarty.foreach.i.iteration}</td>{/if}
			<td class="text-nowrap h-px-30 text-left"><a href="javascript:void(0);" data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="350" >{$_oItem.ms_code}</a></td>
			<td class="text-nowrap h-px-30 text-left">{$_oItem.project_name}</td>
			<td class="text-nowrap h-px-30 text-left">{$_oItem.block_name}</td>
			<td class="text-nowrap h-px-30 text-center">{$_oItem.building_name}</td>
			{if $_oItem.stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
				<td class="text-nowrap h-px-30 text-center">Cao tầng</td>
			{else}
				<td class="text-nowrap h-px-30 text-center">Thấp tầng</td>
			{/if}
			<td class="text-nowrap h-px-30 text-center">{$_oItem.type_name}</td>
			<td class="text-nowrap h-px-30 text-left">{$_oItem.agency_name}</td>
		</tr>
	{/foreach}
{else}
{/if}