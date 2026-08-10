{if $lstBillings}
	{foreach from=$lstBillings name=i key=key item=item}
		<tr>
			<td data-label="{$smarty.foreach.i.iteration}. Mã căn">{$item.stock_code}</td>	
			{if $deviceType ne 'phone'}		
				<td data-label="Dự án">{$item.project}</td>
				<td data-label="Số tiền">{$clsISO->shortNumber($item.price)}</td>
				<td data-label="Tên khách hàng">{$item.customer_name}</td>
			{/if}			
			<td data-label="Ngày giao dịch">{$item.date_trading}</td>
		</tr>
	{/foreach}
{else}
	<tr>
		<td class="text-center" colspan="5" tabindex="0">Danh sách trống</td>
	</tr>
{/if}