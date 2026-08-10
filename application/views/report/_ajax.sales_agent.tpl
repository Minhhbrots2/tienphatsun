{if !empty($list_billings)}
	{foreach from=$list_billings name=i item = _oI}
	<tr>
		{if $deviceType ne 'phone'}
		<td class="text-center">{$smarty.foreach.i.iteration}</td>{/if}
		<td class="text-left">{$_oI.title}</td>
		<td class="text-center text-warning fw-bold">{$_oI.total_in_billings}</td>
		<td class="text-right text-danger fw-bold">{$clsISO->shortNumber($_oI.total_in_sales, 3)}</td>
		<td class="text-right">{$clsISO->convertTimeToText($_oI.deposit_date)}</td>
		<td class="text-center">
			<button data-toggle="webui-popover" data-content="{$_oI.html_content}" data-bs-html="true" 
				class="btn btn-link btn-sm text-muted" data-placement="auto">Xem <i class='bx bx-link-external text-fs-12'></i>
			</button>
		</td>
	</tr>
	{/foreach}
	<tr>
		{if $deviceType ne 'phone'}
		<td class="bg-lighter fw-bold text-upper text-center" colspan="2">Tổng</td>
		{else}
		<td class="bg-lighter fw-bold text-upper text-center">Tổng</td>
		{/if}
		<td class="bg-lighter text-center fw-bold text-warning">{$total_billings}</td>
		<td class="bg-lighter text-right fw-bold text-main">{$clsISO->shortNumber($total_sales, 3)}</td>
		<td class="bg-lighter"></td>
		<td class="bg-lighter"></td>
	</tr>
{else}
	<tr>
		<td class="text-center" colspan="6">
			Không có dữ liệu
		</td>
	</tr>

{/if}