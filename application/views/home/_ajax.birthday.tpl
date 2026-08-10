{if !empty($list_staffs)}
<ul id="{$uid}" class="p-0 max-height-200 m-0">
	{foreach name=i from=$list_staffs item = _oStaff}
	<li class="d-flex gap-2 align-items-center cursor-pointer{if !$smarty.foreach.i.last} border-bottom{/if} pb-2 mb-2">
		<div data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oStaff.profile_id}" data-toggle="webui-popover" data-trigger="click" data-width="300">
			<img class="avatar avatar-xs rounded-circle" src="{$clsProfile->getAvatar($_oStaff.profile_id, $_oStaff, 80,80)}" />
		</div>
		<div class="w-100">
			<h6 class="mb-1">{$clsProfile->getFullName($_oStaff.profile_id, $_oStaff)|capitalize}</h6>
			<small class="text-muted d-block">
				<span>{$clsProperty->getTitle($_oStaff.department_id)}</span>
				<span>&bull; {$_oStaff.birthday|date_format:"%d/%m/%Y"} &bull; {$_oStaff.age} tuổi &bull; 
				<span class="text-main">{$_oStaff.days_to_birth}</span></span>
			</small>
		</div>
	</li>
	{/foreach}
</ul>
{else}
    <div class="p-3 bg-lighter rounded-3">
        <div class="text-center">Không có ai sinh nhật</div>
    </div>
{/if}