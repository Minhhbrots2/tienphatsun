<!-- Menu -->
{$core->getBlock('menu')}
<!-- / Menu -->
<!-- Layout container -->
<div class="layout-page bg_sold {if !empty($stock_bg_sold) && $stock_bg_sold eq 'dark'}dark{/if}" {if $deviceType eq 'phone' && $mod eq 'home' && $act eq 'default' && $sub eq 'default'} style="background: url({$clsConfiguration->getValue('BgHomeMobile')}); background-size:contain;" {/if}>
	{if $deviceType eq 'phone' && $mod eq 'home' && $sub eq 'default' && $act eq 'default'}
	<div class="header-mobile h-auto layout-navbar p-0">
		<div class="container-xxl d-flex align-items-center flex-grow-1 justify-content-between py-3">
			<div class="logo">
				<a href="{$PCMS_URL}">
					<img width="{$clsConfiguration->getImageWidth('LogoWhite')}" height="{$clsConfiguration->getImageHeight('LogoWhite')}" class="sky-brand-logo" src="{$clsConfiguration->getValue('LogoWhite')}" alt="{$clsConfiguration->getValue('checkin_brand_name')}" />
				</a>
			</div>
			<div class="d-flex align-items-center gap-2">
				{$core->getBlock('search_mobile')}				
				<div class="dropdown">
					<a data-toggle="ripple" class="btn btn-icon icon_menu rounded-pill text-white dropdown-toggle hide-arrow" 
						data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
						<i class="bx bx-bell bx-sm"></i>
						<span class="badge bg-danger kGpMjUFvgQ rounded-pill badge-notifications">0</span>
					</a>
					<ul class="dropdown-menu dropdown-menu-arrow dropdown-menu-end py-0 w-px-300" data-bs-popper="static">
						<li class="dropdown-menu-header border-bottom">
							<div class="dropdown-header d-flex align-items-center py-3">
								<h5 class="text-body mb-0 me-auto">Thông báo</h5>
								<a href="javascript:void(0);" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Đánh dấu tất cả đã đọc" onClick="$Core.notify.mark_all_read(this, event)"><i class="bx fs-4 bx-envelope-open"></i></a>
							</div>
						</li>
						<li class="dropdown-notifications-list scrollable-container overflow-y-auto">
							<ul class="list-group list-group-flush list-notifications">
								<li class="text-center p-2">Loading...</li>
							</ul>
						</li>
					</ul>
				</div>
				<button type="button" data-toggle="ripple" class="btn btn-icon icon_menu rounded-pill text-white layout-menu-toggle">
					<i class='bx bx-menu' ></i>
				</button>
			</div>
		</div>
	</div>
	{literal}
	<script>
		$(function(){			
			$(window).scroll(function(){
				if ($(window).scrollTop() >= 30) {
					$(".header-mobile").addClass("menu_scroll");
				} else {
					$(".header-mobile").removeClass("menu_scroll");
				}
			});			
			$_document.click(function (e){	
				var container = $(".show_search");
				if (!container.is(e.target) 
					&& !$('.btn_search').is(e.target) 
					&& container.has(e.target).length === 0
					&& !$('.top_search_suggestion *,.top_search_suggestion').is(e.target) ) {
					container.removeClass('show_search');
					$(".top_search_suggestion").stop(false, true).addClass('d-none');
				}
			});
		});
	</script>
	{/literal}
	{else}
	<link rel="stylesheet" type="text/css" href="{$URL_CSS}/topbar.css?v={$upd_version}" />
	<nav id="layout-navbar"
		class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
		{* header full-width: logo chuyen tu aside len day (chi desktop >=1200px,
		   mobile giu nguyen header-mobile cu) *}
		<div class="tb-brand d-none d-xl-flex">
			<a href="{$PCMS_URL}" class="tb-brand__link" title="{$header_configs.CompanyName}">
				<img src="{$clsConfiguration->getValue('HeaderLogo')}" height="{$clsConfiguration->getImageHeight('HeaderLogo')}" alt="{$header_configs.CompanyName}" />
			</a>
			{* nut thu gon aside: icon-only, sat vien ngan cach (class layout-menu-toggle de menu.js xu ly) *}
			<a href="javascript:void(0);" class="layout-menu-toggle tb-toggle" title="Thu gọn / mở rộng menu">
				<i class="bx bx-chevrons-left"></i>
			</a>
		</div>
		<div class="layout-menu-toggle navbar-nav align-items-xl-center me-2 me-xl-0 d-xl-none">
			<a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
				<i class="bx bx-menu bx-sm"></i>
			</a>
		</div>
		<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
			<!-- Search -->
			{$core->getBlock('top_search')}
			<!-- /Search --> 
			<ul class="navbar-nav flex-row align-items-center ms-auto">
				<li>
					<a{if $is_full_permis eq '1'} data-bs-toggle="modal" data-bs-target="#online-modal"{/if} 
						class="cursor-pointer text-dark badge fw-semibold text-fs-8 p-2 d-flex align-items-center gap-1 bg-gray-100">
						<div class="spinner-grow text-success me-1"></div>
						<span class="total_online">0</span>
						{if $deviceType ne 'phone'}
							<span>online</span>
						{/if}
					</a>
				</li>
				<li class="nav-item me-3 me-xl-1">
					<a href="javascript:void(0)" class="nav-link" onClick="$Core.global.compare.compare_stock(this, event)" 
						title="So sánh căn hộ">
						<i class="bx bx-git-compare bx-sm"></i>
						<span class="badge bg-danger rounded-pill badge-notifications" id="number_compare">{$total_compare}</span>
					</a>
				</li>
				{if $deviceType ne 'phone'}
				<li class="nav-item me-3 me-xl-1">
					<a href="{$PCMS_URL}/my-favourite/" class="nav-link" onclick="$Core.wishlist.open(this,event)" 
						title="Căn hộ yêu thích">
						<i class="bx bx-heart bx-sm"></i>
						<span class="badge bg-danger rounded-pill badge-notifications" id="number_wishlist">{$total_wishlists}</span>
					</a>
				</li>
				{/if}
				<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
					<a href="javascript:;" class="nav-link dropdown-toggle hide-arrow" 
						data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
						<i class="bx bx-bell bx-sm"></i>
						<span class="badge bg-danger kGpMjUFvgQ rounded-pill badge-notifications">0</span>
					</a>
					<ul class="dropdown-menu dropdown-menu-arrow dropdown-menu-end py-0" data-bs-popper="static">
						<li class="dropdown-menu-header border-bottom">
							<div class="dropdown-header d-flex align-items-center py-3">
								<h5 class="text-body mb-0 me-auto">Thông báo</h5>
								<a href="javascript:void(0);" class="dropdown-notifications-all text-body" 
									data-bs-toggle="tooltip" data-bs-placement="top" title="Đánh dấu tất cả đã đọc" 
									onClick="$Core.notify.mark_all_read(this, event)"><i class="bx fs-4 bx-envelope-open"></i></a>
							</div>
						</li>
						<li class="dropdown-notifications-list scrollable-container overflow-y-auto">
							<ul class="list-group list-group-flush list-notifications">
								<li class="text-center p-2">Loading...</li>
							</ul>
						</li>
					</ul>
				</li>
				{if $deviceType eq 'phone'}
					<li class="nav-item navbar-dropdown dropdown-user dropdown">
						<a class="nav-link dropdown-toggle hide-arrow btn btn-sm btn-icon rounded-pill border" data-bs-toggle="dropdown">
							<img src="{$clsProfile->getAvatar($profile_id,$oneProfile,40,40)}" 
									onerror="this.src='{$URL_IMAGES}/avatars/1.png'" class="w-100 h-100 rounded-circle" 
									alt="{$oneProfile.full_name}" style="max-height:100%; object-fit:cover"/>
						</a>
						<div class="dropdown-menu overflow-hidden dropdown-menu-end w-px-200 py-0">
							{$core->getBlock('menu_profile')}
						</div>
					</li>
				{else}
					<li class="nav-item navbar-dropdown dropdown-user dropdown">
						<a class="nav-link dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
							<div class="avatar avatar-online">
								<img src="{$clsProfile->getAvatar($profile_id,$oneProfile,40,40)}" 
									onerror="this.src='{$URL_IMAGES}/avatars/1.png'" class="w-px-40 h-px-40 rounded-circle" 
									alt="{$oneProfile.full_name}" style="max-height:100%; object-fit:cover"/>
							</div>
						</a>
						<div class="dropdown-menu overflow-hidden dropdown-menu-end w-px-200 py-0">
							{$core->getBlock('menu_profile')}
						</div>
					</li>
				{/if}
			</ul>
		</div>
	</nav>
	{/if}
	{literal}
	<script>
		$(function(){	
			$(document).click(function (e){	
				var container = $(".box_search_header");
				if (!container.is(e.target) && container.has(e.target).length === 0 && !$('.search_header').is(e.target) )
				{
					$(".search_header").removeClass('show');
				}
			});
		});
	</script>
	{/literal}
    <!-- / Navbar -->
	<div class="content-wrapper">