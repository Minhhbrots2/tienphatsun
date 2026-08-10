{if !empty($arr_report)}	
	{foreach from=$arr_report item=_oItem key=_field name=i}
		<tr>
			{if $deviceType ne 'phone'}<td class="text-center h-px-30">{$smarty.foreach.i.iteration}</td>{/if}
			<td class="text-left h-px-30">{$_oItem.title}</td>
			<td class="text-center fw-bold h-px-30 fs-20 text-nowrap"><a class="text-success cursor-pointer" href="{$PCMS_URL}/giao-dich.html?{$purl}&filter_by=_project&search_field={$_field}&is_success=1" target="_blank" >{$_oItem.total_yes}<span class="text-warning fs-12 d-inline-block">/{$total_billding}</span><i class="bx bx-link-external fs-14 ml-1"></i></a></td>
			<td class="text-center fw-bold h-px-30 fs-20 text-nowrap"><a class="text-main cursor-pointer" href="{$PCMS_URL}/giao-dich.html?{$purl}&search_field={$_field}&is_success=0" target="_blank" >{$_oItem.total_no}<span class="text-warning fs-12 d-inline-block">/{$total_billding}</span><i class="bx bx-link-external fs-14 ml-1"></i></a></td>
		</tr>
	{/foreach}
{else}
	<tr><td class="text-center" colspan="3">Dữ liệu trống</td></tr>
{/if}