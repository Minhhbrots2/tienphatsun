{if !empty($lst_staff_share)}
<div class="card h-100 mb-2">
	{assign var = uid value = $clsISO->getUniqid()}
	<div class="card-header d-flex align-items-center justify-content-between">
		<h5 class="card-title m-0 me-2">Top 10 sale tiếp khách</h5>
		<a><i class="bx bx-help-circle"></i></a>
	</div>
	<div class="card-body">
		<ul class="p-0 m-0">
			{foreach from=$lst_staff_share item=_oItem key=key name=i}
				<li class="d-flex {if !$smarty.foreach.i.last}pb-2{/if}">
					<div class="avatar mt-1 avatar-sm position-relative flex-shrink-0 me-2" data-trigger="hover" data-width="300" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oItem.profile_id}" data-toggle="webui-popover" >
						<img src="{$clsProfile->getAvatar($_oItem.profile_id, $_oItem, 40, 40)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" alt="{$_oItem.full_name}" class="rounded-pill" />
						{$clsProfile->get_icon_verified($_oItem.profile_id, $_oItem.more_information)}
					</div>
					<div class="w-100">
						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
							<small class="text-muted d-block">{$_oItem.code}-{$_oItem.department_name}</small>
							<div class="user-progress d-flex align-items-center gap-1">
								<h6 class="mb-0 text-main">{$_oItem.total_share} lượt</h6>
							</div>
						</div>
						<h6 class="mb-0">{$_oItem.full_name}</h6>
					</div>
				</li>
				{if $smarty.foreach.i.iteration eq 10}{break}{/if}
			{/foreach}
		</ul>
	</div>
</div>
{/if}
{if !empty($list_dep_area)}
<div class="card h-100 mb-2">
	{assign var = uid value = $clsISO->getUniqid()}
	<div class="card-header d-flex align-items-center justify-content-between">
		<h5 class="card-title m-0 me-2">Top vùng kinh doanh tiếp khách</h5>
		<a><i class="bx bx-help-circle"></i></a>
	</div>
	<div class="card-body">
		<div class="mb-3">
			{foreach from=$list_dep_area item=_oArea key=key name=i}
				<div class="d-flex gap-2 align-items-center w-100 mb-1">
					<span class="fs-14 text-right w-px-75 text-nowrap">{$clsISO->replace($_oArea.title, 'kinh doanh', '')}</span>
					<div class="d-flex flex-column" style="width:calc(100% - 150px)">
						<div class="progress w-100" style="height:12px;">
						  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" 
							style="width:{$_oArea.total_share_area*100/$total_share}%;"></div>
						</div>
					</div>
					<span class="fs-12 text-nowrap w-px-75 text-right"><strong class="text-fs-18 text-main">{$_oArea.total_share_area}</strong> lượt</span>
				</div>
			{/foreach}
		</div>
	</div>
</div>
{/if}