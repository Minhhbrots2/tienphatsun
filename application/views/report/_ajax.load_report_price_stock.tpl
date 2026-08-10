{if !empty($lstItem)}
	{foreach name=i from=$lstItem name=i key=key item=_oItem}
		<tr>
			{if $deviceType ne 'phone'}
				<td class="text-center" rowspan={$_oItem.rowspan}>{$smarty.foreach.i.iteration}</td>
			{/if}
			<td class="text-left" rowspan={$_oItem.rowspan}>
				<a href="javascript:void(0);" data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="350" class="" data-target="webuiPopover0">{$_oItem.ms_code}</a>
			</td>	
		</tr>
		{if !empty($_oItem.list_logs)}
			{foreach from=$_oItem.list_logs item=_oLog}
				<tr>
					<td>Từ <strong>{$clsISO->formatPrice($_oLog.from_value)}đ</strong> thành <strong>{$clsISO->formatPrice($_oLog.to_value)}đ</strong> lúc {$_oLog.reg_date}</td>
				</tr>
			{/foreach}			
		{else}
			<tr><td></td><td></td><td></td></tr>
		{/if}
	{/foreach}
{else}
	<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}5{else}3{/if}">Dữ liệu trống</td></tr>
{/if}