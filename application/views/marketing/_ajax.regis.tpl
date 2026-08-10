{if !empty($list_registers)}
	{foreach from=$list_registers item = _oRegis name = i}
	<tr>
		{if $deviceType ne 'phone'}
		<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
		{/if}
		<td class="align-center">{$_oRegis.staff_name}</td>
		<td class="align-center">{$_oRegis.department_name}</td>
		<td class="align-center">{$_oRegis.project_name}</td>
		<td class="align-center text-right">{$clsISO->formatPrice($_oRegis.fb_ads)}</td>
		<td class="align-center text-right">{$clsISO->formatPrice($_oRegis.gg_ads)}</td>
		<td class="align-center text-right">{$clsISO->formatPrice($_oRegis.zalo_ads)}</td>
		<td class="align-center text-right">{$clsISO->formatPrice($_oRegis.tiktok_ads)}</td>
		<td class="align-center text-center">{$clsISO->convertTimeToText($_oRegis.reg_date, true)}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="{if $deviceType ne 'phone'}4{else}3{/if}" class="text-right bg-lighter fw-bold">TỔNG CỘNG</td>
		<td class="text-right bg-lighter fw-bold">{$clsISO->formatPrice($total_fb_ads)} {$clsISO->getRate()}</td>
		<td class="text-right bg-lighter fw-bold">{$clsISO->formatPrice($total_gg_ads)} {$clsISO->getRate()}</td>
		<td class="text-right bg-lighter fw-bold">{$clsISO->formatPrice($total_zalo_ads)} {$clsISO->getRate()}</td>
		<td class="text-right bg-lighter fw-bold">{$clsISO->formatPrice($total_tiktok_ads)} {$clsISO->getRate()}</td>
		<td class="text-right bg-lighter fw-bold"></td>
	</tr>					
{else}

{/if}