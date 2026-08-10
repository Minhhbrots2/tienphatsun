{if !empty($lstItem)}
	{foreach from=$lstItem item=_oItem key=key name=i}
		{assign var=oneStaff value=$_oItem.oneStaff}
		{assign var=arr_status value=$_oItem.arr_status}
		<tr>
			{if $deviceType ne 'phone'}
				<td class="text-center">{$smarty.foreach.i.iteration}</td>
			{/if}
			<td class="align-center">
				<div class="d-flex align-items-center gap-1 cursor-pointer" data-trigger="hover" data-width="300" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oItem.staff_id}" data-toggle="webui-popover" data-target="webuiPopover34">
					<div class="avatar avatar-xs position-relative flex-shrink-0">
						<img src="{$clsProfile->getAvatar($_oItem.staff_id,$oneStaff,40,40)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" alt="{$_oItem.staff_name}" class="rounded-pill">
					</div>
					<div class="flex-fill text-info">
						<h6 class="m-0">{$_oItem.staff_name}</h6>
					</div>
				</div>
			</td>
			<td class="text-center align-center fw-bold text-warning">{$_oItem.total_data}</td>
			{foreach from=$arr_status_cached item=_oStatus key=k_stt name=n_stt}
				{if $_oStatus.property_id ne $smarty.const._DATA_STATUS_DONTCARE_ID}
					<td class="text-center align-center fw-bold {if !empty($arr_status[$_oStatus.property_id])}text-success{else}text-main{/if}">{$arr_status[$_oStatus.property_id]}</td>	
				{/if}
			{/foreach}
		</tr>
	{/foreach}
{else}
	<tr>
		{if $deviceType ne 'phone'}
			<td class="text-center" colspan="{$arr_status_cached|@count + 2}">Không có dữ liệu</td>
		{else}
			<td class="text-center" colspan="{$arr_status_cached|@count + 1}">Không có dữ liệu</td>
		{/if}
	</tr>
{/if}