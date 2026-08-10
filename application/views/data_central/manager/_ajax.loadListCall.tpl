{if !empty($lstDataCenter)}
	{foreach from=$lstDataCenter item=_oItem key=key name=i}
		{assign var=_oProfile value=$_oItem.profile}
		<tr>
			{if $deviceType ne 'phone'}<td class="text-center">{$smarty.foreach.i.iteration}</td>{/if}
			<td>{$_oItem.full_name}</td>
			<td class="text-center border-end" data-label="{$_oColumn.title}:">
				<div class="d-flex align-items-center gap-1">
					<a href="javascript:void(0)" onClick="$Core.data_central.log(this,event)" data-type="call_log" data-id="{$_oItem.id}" data-href="https://zalo.me/{$_oItem.phone}" class="text-nowrap"><span class="zalo_chat me-1"><img src="{$URL_IMAGES}/logo_white_s_40.png" width="12" height="12" alt=""></span>{$_oItem.phone}</a>
				{if !empty($_oItem.phone2)} <span class="btn btn-icon btn-default btn-xs text-main" data-url="/index.php?mod=data_central&act=load_more&id={$_oItem.id}&field=phone&type=full" data-toggle="webui-popover" data-trigger="hover" data-width="130">+{$_oItem.phone2|@count}</span>{/if}
				</div>
			</td>
			<td>
				<a href="javascript:void(0);" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oProfile.profile_id}" data-toggle="webui-popover" data-trigger="hover" class="text-nowrap" data-target="webuiPopover118">
				<img class="avatar avatar-xxs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oProfile.profile_id,$_oProfile)}">{$clsProfile->getFullName($_oProfile.profile_id,$_oProfile)}</a>
			</td>
			<td>{$_oItem.time_call}</td>
		</tr>
	{/foreach}
{else}
	<tr><td class="text-center" colspan="{if $deviceType eq 'phone'}5{else}6{/if}">Danh sách trống!</td></tr>
{/if}