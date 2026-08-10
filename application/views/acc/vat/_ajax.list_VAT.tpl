{if !empty($list_VATs)}
	{foreach from=$list_VATs name=i item = _oVat}
	{assign var = more_information value = $_oVat.more_information}
	<tr ondblclick="$Core.vat.open_VAT(this,event)" vat_id="{$_oVat.vat_id}" style="background:{$_oVat.bgcolor}">
		{if $deviceType ne 'phone'}
		<td{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if} class="text-center">{$smarty.foreach.i.iteration}</td>
		{/if}
		<td{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$_oVat.symbol}</td>
		<td{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$_oVat.contract_code}</td>
		<td{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$clsISO->convertTimeToText($_oVat.contract_date)}</td>
		<td{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$more_information.partner_name}</td>
		{if !empty($_oVat.list_billings) && $_oVat.total_billings gt '1'}
			{foreach from=$_oVat.list_billings name = k item = _oB}
				{if $smarty.foreach.k.first}
				<td>{$_oB.stock_code}</td>
				<td>{$_oB.staff_name}</td>
				{/if}
			{/foreach}
		{else}
			{if !empty($_oVat.list_billings)}
				{foreach from=$_oVat.list_billings name = k item = _oB}
					<td>{$_oB.stock_code}</td>
					<td>{$_oB.staff_name}</td>
				{/foreach}
			{else}
				<td></td>
				<td></td>
			{/if}
		{/if}
		<td class="text-right fw-bold"{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$clsISO->formatPrice($_oVat.amount)} {$clsISO->getRate()}</td>
		<td class="text-right"{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$clsISO->formatPrice($_oVat.deposit_price)} {$clsISO->getRate()}</td>
		<td class="text-right"{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$clsISO->formatPrice($_oVat.final_price)} {$clsISO->getRate()}</td>
		<td class="text-right"{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$clsISO->formatPrice($_oVat.unpaid_price)} {$clsISO->getRate()}</td>
		<td{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$_oVat.vat_type_name}</td>
		<td{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if}>{$_oVat.status_name}</td>
		<td{if $_oVat.total_billings gt '1'} rowspan="{$_oVat.total_billings}"{/if} class="text-center">
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
					data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
				</button>
				<div class="dropdown-menu w-px-200">
					<a class="dropdown-item text-success{if $_oVat.status_id eq $smarty.const._VAT_STATUS_SOLD} disabled{/if}" href="javascript:void(0);" onClick="$Core.vat.done_VAT(this,event)" 
						vat_id="{$_oVat.vat_id}"><i class="bx bx-check me-1"></i> Đã tất toán</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.acc.open_VAT(this,event)" 
						vat_id="{$_oVat.vat_id}"><i class="bx bx-pencil me-1"></i> Sửa</a>
						
						
					<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.acc.delete_VAT(this,event)" 
						vat_id="{$_oVat.vat_id}"><i class="bx bx-trash me-1"></i> Xóa</a>
				</div>
			</div>
		</td>
	</tr>
	{if !empty($_oVat.list_billings) && $_oVat.total_billings gt '1'}
		{foreach from=$_oVat.list_billings name = k item = _oB}
			{if !$smarty.foreach.k.first}
				<tr style="background:{$_oVat.bgcolor}">
					<td>{$_oB.stock_code}</td>
					<td>{$_oB.staff_name}</td>
				</tr>
			{/if}
		{/foreach}
	{/if}
	{/foreach}
{/if}