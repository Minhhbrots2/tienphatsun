{foreach from=$lstItem item=_oItem key=key name=i}

	{assign var = _oneStaff value = $_oItem.oneStaff}

	<div class="item_ranker flex-flow position-relative rounded-1{if !empty($_oItem.is_none)} is_none{/if}" style="height:calc({$_oItem.col_height}% + 60px); background-color:{$_oItem.bgcolor};" title="{$_oneStaff.full_name}" profile_id="{$_oneStaff.profile_id}" >

		<div class="box_avt position-absolute d-flex align-items-center justify-content-center cursor-pointer" style="background:url('{$_oItem.bg_avt}'); background-size:100%; background-position:bottom; background-repeat:no-repeat;{if $smarty.foreach.i.last} width:calc(100% * 1.2){/if}">

			<img class="rounded-pill w-100 object-fit-cover" src="{$clsProfile->getAvatar($_oneStaff.profile_id,$_oneStaff,100,0)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" 

				alt="{$clsProfile->getFullname($_oItem.profile_id,$_oneStaff)}" width="30" height="30">

		</div>

		<div class="text-center fw-bold d-flex flex-column info_ranker">

			<span class="text-warning lh-xs">{$_oneStaff.department_name}</span>

			{if $screen_sales}<span class="text-main">{$_oItem.total_price}</span>{/if}

		</div>

	</div>

{/foreach}