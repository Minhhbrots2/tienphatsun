<?php
/* Smarty version 3.1.33, created on 2026-08-06 11:54:12
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/menu_bottom/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7413745cc928_77630283',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eee380fe4098f4eaab6135058c98bab21688cb4b' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/menu_bottom/index.tpl',
      1 => 1785992050,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7413745cc928_77630283 (Smarty_Internal_Template $_smarty_tpl) {
?><a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?>text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icon"><i class="menu-icon tf-icons bx bx-home me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Trang chủ</span>
</a>
<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') == '1') {?>
<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bang-hang/" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Bảng hàng</span>
</a>
<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('exclusive');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="23px" class="h-100 w-100" style="object-fit: contain"/>
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'tool') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Check-In</span>
</a>
<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('tool');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?>text-main<?php } else { ?>text-dark<?php }?>" data-toggle="ripple">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Tìm kiếm</span>
</a>
<?php } elseif ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR') == '1') {?>
<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bang-hang/"class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Bảng hàng</span>
</a>
<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('exclusive');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="23px" class="h-100 w-100" style="object-fit: contain" />
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'tool') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Check-In</span>
</a>
<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('tool');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?>text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Tìm kiếm</span>
</a>
<?php } elseif ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT') == '1') {?>
<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bang-hang/" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Bảng hàng</span>
</a>
<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('exclusive');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="23px" class="h-100 w-100" style="object-fit: contain" />
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'tool') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Check-In</span>
</a>
<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('tool');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?>text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Tìm kiếm</span>
</a>
<?php } elseif ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ACCOUNTANT') == '1') {?>
<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bang-hang/" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Bảng hàng</span>
</a>
<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('exclusive');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="23px" class="h-100 w-100" style="object-fit: contain" />
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'tool') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Check-In</span>
</a>
<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('tool');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?>text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Tìm kiếm</span>
</a>
<?php } else { ?>
<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bang-hang/" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Bảng hàng</span>
</a>
<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('exclusive');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="23px" class="h-100 w-100" style="object-fit: contain"/>
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'tool') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Check-In</span>
</a>
<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('tool');?>
" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?> text-main<?php } else { ?>text-dark<?php }?>">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm') {?>text-main<?php } else { ?>text-dark<?php }?> text-nowrap">Tìm kiếm</span>
</a>
<?php }
}
}
