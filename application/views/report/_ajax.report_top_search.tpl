{if !empty($list_logs)}
<div class="card h-100 mb-2">
	{assign var = uid value = $clsISO->getUniqid()}
	<div class="card-header d-flex align-items-center justify-content-between">
		<h5 class="card-title m-0 me-2">Top 10 tra cứu T{$smarty.now|date_format:"%m/%Y"}</h5>
		<a><i class="bx bx-help-circle"></i></a>
	</div>
	<div class="card-body">
		<ul class="p-0 m-0">
			{foreach from=$list_logs item=_oItem key=key name=i}
				{assign var=oProfile value=$_oItem.oProfile }
				<li class="d-flex {if !$smarty.foreach.i.last}pb-1{/if}">
					<div class="avatar mt-1 avatar-sm position-relative flex-shrink-0 me-2" data-trigger="hover" data-width="300" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oItem.user_id}" data-toggle="webui-popover" >
						<img src="{$clsProfile->getAvatar($_oItem.user_id, $oProfile, 40, 40)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" alt="{$oProfile.full_name}" class="rounded-pill" />
						{$clsProfile->get_icon_verified($_oItem.user_id, $oProfile)}
					</div>
					<div class="w-100">
						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between">
							<small class="text-muted d-block">{$oProfile.department_name}</small>
							<div class="user-progress d-flex align-items-center gap-1">
								<h6 class="mb-0 text-main">{$_oItem.total_search} lượt</h6>
							</div>
						</div>
						<h6 class="mb-0">{$oProfile.full_name}</h6>
					</div>
				</li>
			{/foreach}
		</ul>
	</div>
</div>
{/if}