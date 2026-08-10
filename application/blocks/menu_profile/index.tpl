<div class="py-2 bg-white position-sticky top-0 zindex-1">
	<a class="dropdown-item cursor-pointer" profile_id="{$profile_id}" 
		onClick="$Core.member.view_profile(this, event); return false;">
		<div class="d-flex">
			<div class="flex-shrink-0 me-3"><div class="avatar avatar-online">
				<img src="{$clsProfile->getAvatar($profile_id,$oneProfile,40,40)}" 
					onerror="this.src='{$URL_IMAGES}/avatars/1.png'" class="w-px-40 h-px-40 rounded-circle" />
			</div></div>
			<div class="flex-grow-1">
				<span class="fw-semibold d-block">{$oneProfile.full_name}</span>
				<div class="d-flex gap-1 fs-13 align-items-center">
					<span class="text-warning">{$oneProfile.role_name}</span>
				</div>
			</div>
		</div>
	</a>
	<div class="dropdown-divider"></div>
</div>
<ul class="overflow-y-auto list-unstyled" style="max-height:calc(100vh - 300px);">
	<li>
		<div class="dropdown-item d-flex justify-content-between align-items-center">
			<a href="javascript:void();" data-toggle="ripple" profile_id="{$profile_id}" 
				onClick="$Core.member.view_profile(this, event); return false;">
				<i class="bx bx-user me-1"></i>
				<span class="align-middle">Hồ sơ</span>
			</a>	
		</div>
	</li>	
	<!--<li><a href="https://docs.google.com/document/d/1aSEgRZoSULwOaKuBtLIBVLgZtamT9AOYVDkfHmcvs24/edit?tab=t.0" target="_blank" class="dropdown-item text-main fw-bold" title="Chứng nhận đại lý">
		<i class="bx bx-check-shield"></i>
		<span class="align-middle">Chứng nhận đại lý</span>
	</a></li>-->
	<li><a class="dropdown-item text-warning" href="{$clsISO->getLink('quote')}" >
		<i class='bx bxs-quote-alt-left me-1'></i>
		<span class="align-middle">Lời trích dẫn</span>
	</a></li>
	{if $clsISO->checkPermission('access_staff')}
	<li><a data-toggle="ripple" class="dropdown-item" href="{$clsISO->getLink('staff')}">
		<i class="bx bx-group"></i>
		<span class="align-middle">Quản lý nhân viên</span>
	</a></li>
	{/if}
	<!-- {if $clsISO->checkPermission('policy_stock')}
	<li><a data-toggle="ripple" href="javascript:void(0)" class="dropdown-item text-primary" 
	onClick="$Core.helper.open_policy(this, event)" tp="_update">
		<i class='bx bx-check-shield'></i>
		<span class="align-middle">Chính sách bán hàng</span>
	</a></li>
	{/if} -->
	{if $clsISO->checkPermission('update_stock')}
		<li><a data-toggle="ripple" href="javascript:void(0)" class="dropdown-item" onClick="$Core.global.stock.open_import(this, event)">
			<i class='bx bx-check-square'></i>
			<span class="align-middle">Cập nhật bảng hàng</span>
		</a></li>
		<li><a data-toggle="ripple" href="{$clsISO->getLink('crawl_highfloor')}" class="dropdown-item text-success" >
			<i class='bx bxs-file-doc'></i>
			<span class="align-middle">Cập nhật cao tầng excel</span>
		</a></li>
		<li><a data-toggle="ripple" href="{$clsISO->getLink('crawl_lowfloor')}" class="dropdown-item text-warning" >
			<i class='bx bxs-file-doc'></i>
			<span class="align-middle">Cập nhật thấp tầng excel</span>
		</a></li>
	{/if}
</ul>
<div class="py-2 bg-white position-sticky bottom-0 zindex-1">
	<div class="dropdown-divider"></div>
	<a class="dropdown-item" href="{$clsISO->getLink('logout')}">
		<i class="bx bx-power-off me-2"></i>
		<span class="align-middle">Đăng xuất</span>
	</a>
</div>