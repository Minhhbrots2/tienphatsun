<div class="lst_lesson">
	{if !empty($lstProfile)}
	<ul class="p-0 m-0">
		{foreach from=$lstProfile item=_oProfile key=key name=i}
			<li class="d-flex mb-2 pb-1 align-items-center">
				<div class="avatar position-relative flex-shrink-0 me-2" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oProfile.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400" data-target="webuiPopover55">
					<img src="{$clsProfile->getAvatar($_oProfile.profile_id,$_oProfile)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" alt="{$clsProfile->getFullName($_oProfile.profile_id,$_oProfile)}" class="rounded-pill">
				</div>
				<div class="w-100">
					<small class="text-muted d-block">[{$_oProfile.department_name}] {$_oProfile.role_name}</small>
					<h6 class="mb-0">{$clsProfile->getFullName($_oProfile.profile_id,$_oProfile)}</h6>
					{if $deviceType eq 'phone'}
						<div class="d-flex gap-2 flex-wrap">
							<div class="mb-1 d-flex align-items-center">
								<i class='bx bx-door-open me-1'></i>{$_oProfile.total_view} lần
							</div>
							<div class="mb-1 d-flex align-items-center">
								<i class='bx bx-timer me-1' ></i>{$_oProfile.time_last}
							</div>
						</div>
					{else}
						<div class="d-flex gap-2 flex-wrap">
							<div class="mb-1 d-flex align-items-center">
								<i class='bx bx-door-open me-1'></i>Số lần vào học: {$_oProfile.total_view}
							</div>
							<div class="mb-1 d-flex align-items-center">
								<i class='bx bx-timer me-1' ></i>Lần học cuối: {$_oProfile.time_last}
							</div>
						</div>
					{/if}
				</div>
			</li>
		{/foreach}
	</ul>
	{else}
		<ul class="list-unstyled" id="list_favourite" data-bs-popper="static"><div class="d-flex flex-column align-items-center justify-content-center p-3">
			<img src="{$URL_IMAGES}/listing-empty.svg" width="90px">
			<p>Danh sách trống</p>
		</div></ul>
	{/if}
</div>