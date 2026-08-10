<?php
/* Smarty version 3.1.33, created on 2026-08-06 14:21:22
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/_header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7435f2b1c351_59816148',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd99323be49bf00264b1a515aa4f2abd9966d3e0b' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/_header.tpl',
      1 => 1786000876,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7435f2b1c351_59816148 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- Menu -->
<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('menu');?>

<!-- / Menu -->
<!-- Layout container -->
<div class="layout-page bg_sold <?php if (!empty($_smarty_tpl->tpl_vars['stock_bg_sold']->value) && $_smarty_tpl->tpl_vars['stock_bg_sold']->value == 'dark') {?>dark<?php }?>" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone' && $_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'default' && $_smarty_tpl->tpl_vars['sub']->value == 'default') {?> style="background: url(<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('BgHomeMobile');?>
); background-size:contain;" <?php }?>>
	<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone' && $_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?>
	<div class="header-mobile h-auto layout-navbar p-0">
		<div class="container-xxl d-flex align-items-center flex-grow-1 justify-content-between py-3">
			<div class="logo">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
">
					<img width="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageWidth('LogoWhite');?>
" height="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageHeight('LogoWhite');?>
" class="sky-brand-logo" src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" alt="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('checkin_brand_name');?>
" />
				</a>
			</div>
			<div class="d-flex align-items-center gap-2">
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('search_mobile');?>
				
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
	
	<?php echo '<script'; ?>
>
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
	<?php echo '</script'; ?>
>
	
	<?php } else { ?>
	<nav id="layout-navbar"
		class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
		<div class="layout-menu-toggle navbar-nav align-items-xl-center me-2 me-xl-0 d-xl-none">
			<a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
				<i class="bx bx-menu bx-sm"></i>
			</a>
		</div>
		<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
			<!-- Search -->
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_search');?>

			<!-- /Search --> 
			<ul class="navbar-nav flex-row align-items-center ms-auto">
				<li>
					<a<?php if ($_smarty_tpl->tpl_vars['is_full_permis']->value == '1') {?> data-bs-toggle="modal" data-bs-target="#online-modal"<?php }?> 
						class="cursor-pointer text-dark badge fw-semibold text-fs-8 p-2 d-flex align-items-center gap-1 bg-gray-100">
						<div class="spinner-grow text-success me-1"></div>
						<span class="total_online">0</span>
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
							<span>online</span>
						<?php }?>
					</a>
				</li>
				<li class="nav-item me-3 me-xl-1 <?php if ($_smarty_tpl->tpl_vars['total_compare']->value == 0) {?>d-none<?php }?>">
					<a href="javascript:void(0)" class="nav-link" onClick="$Core.global.compare.compare_stock(this, event)" 
						title="So sánh căn hộ">
						<i class="bx bx-git-compare bx-sm"></i>
						<span class="badge bg-danger rounded-pill badge-notifications" id="number_compare"><?php echo $_smarty_tpl->tpl_vars['total_compare']->value;?>
</span>
					</a>
				</li>
				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
				<li class="nav-item me-3 me-xl-1">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/my-favourite/" class="nav-link" onclick="$Core.wishlist.open(this,event)" 
						title="Căn hộ yêu thích">
						<i class="bx bx-heart bx-sm"></i>
						<span class="badge bg-danger rounded-pill badge-notifications" id="number_wishlist"><?php echo $_smarty_tpl->tpl_vars['total_wishlists']->value;?>
</span>
					</a>
				</li>
				<?php }?>
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
				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
					<li class="nav-item navbar-dropdown dropdown-user dropdown">
						<a class="nav-link dropdown-toggle hide-arrow btn btn-sm btn-icon rounded-pill border" data-bs-toggle="dropdown">
							<img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value,40,40);?>
" 
									onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/avatars/1.png'" class="w-100 h-100 rounded-circle" 
									alt="<?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['full_name'];?>
" style="max-height:100%; object-fit:cover"/>
						</a>
						<div class="dropdown-menu overflow-hidden dropdown-menu-end w-px-200 py-0">
							<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('menu_profile');?>

						</div>
					</li>
				<?php } else { ?>
					<li class="nav-item navbar-dropdown dropdown-user dropdown">
						<a class="nav-link dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
							<div class="avatar avatar-online">
								<img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value,40,40);?>
" 
									onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/avatars/1.png'" class="w-px-40 h-px-40 rounded-circle" 
									alt="<?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['full_name'];?>
" style="max-height:100%; object-fit:cover"/>
							</div>
						</a>
						<div class="dropdown-menu overflow-hidden dropdown-menu-end w-px-200 py-0">
							<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('menu_profile');?>

						</div>
					</li>
				<?php }?>
			</ul>
		</div>
	</nav>
	<?php }?>
	
	<?php echo '<script'; ?>
>
		$(function(){	
			$(document).click(function (e){	
				var container = $(".box_search_header");
				if (!container.is(e.target) && container.has(e.target).length === 0 && !$('.search_header').is(e.target) )
				{
					$(".search_header").removeClass('show');
				}
			});
		});
	<?php echo '</script'; ?>
>
	
    <!-- / Navbar -->
	<div class="content-wrapper"><?php }
}
