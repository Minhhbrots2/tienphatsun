<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:51:32
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/menu_profile/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315b4f2c4d3_28388432',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '359313aeabf0e110fc935a1fed607b06678135b2' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/menu_profile/index.tpl',
      1 => 1785927044,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315b4f2c4d3_28388432 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="py-2 bg-white position-sticky top-0 zindex-1">
	<a class="dropdown-item cursor-pointer" profile_id="<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
" 
		onClick="$Core.member.view_profile(this, event); return false;">
		<div class="d-flex">
			<div class="flex-shrink-0 me-3"><div class="avatar avatar-online">
				<img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value,40,40);?>
" 
					onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/avatars/1.png'" class="w-px-40 h-px-40 rounded-circle" />
			</div></div>
			<div class="flex-grow-1">
				<span class="fw-semibold d-block"><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['full_name'];?>
</span>
				<div class="d-flex gap-1 fs-13 align-items-center">
					<span class="text-warning"><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['role_name'];?>
</span>
				</div>
			</div>
		</div>
	</a>
	<div class="dropdown-divider"></div>
</div>
<ul class="overflow-y-auto list-unstyled" style="max-height:calc(100vh - 300px);">
	<li>
		<div class="dropdown-item d-flex justify-content-between align-items-center">
			<a href="javascript:void();" data-toggle="ripple" profile_id="<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
" 
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
	<li><a class="dropdown-item text-warning" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('quote');?>
" >
		<i class='bx bxs-quote-alt-left me-1'></i>
		<span class="align-middle">Lời trích dẫn</span>
	</a></li>
	<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('access_staff')) {?>
	<li><a data-toggle="ripple" class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('staff');?>
">
		<i class="bx bx-group"></i>
		<span class="align-middle">Quản lý nhân viên</span>
	</a></li>
	<?php }?>
	<!-- <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('policy_stock')) {?>
	<li><a data-toggle="ripple" href="javascript:void(0)" class="dropdown-item text-primary" 
	onClick="$Core.helper.open_policy(this, event)" tp="_update">
		<i class='bx bx-check-shield'></i>
		<span class="align-middle">Chính sách bán hàng</span>
	</a></li>
	<?php }?> -->
	<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('update_stock')) {?>
		<li><a data-toggle="ripple" href="javascript:void(0)" class="dropdown-item" onClick="$Core.global.stock.open_import(this, event)">
			<i class='bx bx-check-square'></i>
			<span class="align-middle">Cập nhật bảng hàng</span>
		</a></li>
		<li><a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('crawl_highfloor');?>
" class="dropdown-item text-success" >
			<i class='bx bxs-file-doc'></i>
			<span class="align-middle">Cập nhật cao tầng excel</span>
		</a></li>
		<li><a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('crawl_lowfloor');?>
" class="dropdown-item text-warning" >
			<i class='bx bxs-file-doc'></i>
			<span class="align-middle">Cập nhật thấp tầng excel</span>
		</a></li>
	<?php }?>
</ul>
<div class="py-2 bg-white position-sticky bottom-0 zindex-1">
	<div class="dropdown-divider"></div>
	<a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('logout');?>
">
		<i class="bx bx-power-off me-2"></i>
		<span class="align-middle">Đăng xuất</span>
	</a>
</div><?php }
}
