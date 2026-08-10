{if !empty($arr_satffs)}
	<thead class="position-sticky top-0 zindex-3">
		<tr>
			{if $deviceType eq 'computer'}
			<th class="align-center bg-lighter h-px-35 w-px-30" rowspan="2">STT</th>
			{/if}
			<th class="align-center bg-lighter h-px-35" rowspan="2">Họ và tên</th>
			<th class="align-center bg-lighter h-px-35 text-center" rowspan="2">Khách hàng</th>
			<th class="align-center bg-lighter h-px-35 text-center" colspan="{$arr_status_customer_cached|@count}">Trạng thái</th>
		</tr>
		<tr>
			{foreach from=$arr_status_customer_cached item=_oStatus key=k_st name=n_st}
				<th class="align-center text-center bg-lighter h-px-35" style="color: {$_oStatus.bgcolor} !important;position:unset !important">{$_oStatus.title}</th>
			{/foreach}
		</tr>
	</thead>
	{foreach name=i from=$arr_satffs name=i key=key item=_oItem}
	{assign var = lst_status value = $_oItem.lst_status}
	<tr class="trBilling">
		{if $deviceType eq 'computer'}
		<td class="text-center" rowspan={$_oItem.rowspan}>
			{$smarty.foreach.i.iteration}
		</td>
		{/if}
		<td class="text-left">{$clsProfile->getIndentityV2($_oItem.profile_id, $_oItem, true)}</td>
		{if !empty($_oItem.total_customer)}
			<td class="text-center fw-bold text-main">
				{$_oItem.total_customer} Khách
			</td>
		{else}
			<td class="text-center text-muted">
				0 Khách
			</td>
		{/if}
		{foreach from=$arr_status_customer_cached item=_oStatus key=k_st name=n_st}
			{if !empty($lst_status[$k_st])}
				<td class="text-center fw-bold" style="color:{$_oStatus.bgcolor}">
					{$lst_status[$k_st]} Khách
				</td>
			{else}
				<td class="text-center text-muted">
					0 Khách
				</td>
			{/if}
		{/foreach}
	</tr>
	{/foreach}
{else}
	<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}5{else}3{/if}">Dữ liệu trống</td></tr>
{/if}