<?php
/* Smarty version 3.1.33, created on 2026-08-06 09:53:12
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/auth/_brand.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a73f71836f740_74823676',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9cd81b9495ef5bd2a159c15dee1f882b309c5601' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/auth/_brand.tpl',
      1 => 1785984788,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a73f71836f740_74823676 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="sky-auth__brand">
	<div class="sky-auth__logo">
		<img width="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageWidth('LogoWhite');?>
" height="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageHeight('LogoWhite');?>
" class="sky-brand-logo" src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" alt="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('checkin_brand_name');?>
" />
	</div>
	<div class="sky-auth__brand-body">
		<div class="sky-auth__badge"><i class='bx bxs-quote-alt-right'></i> Giá trị cốt lõi</div>
		<div class="sky-auth__slogan">
			<p class="tlt" data-in-delay="0" data-in-effect="fadeInUp">KHẲNG ĐỊNH VỊ THẾ</p>
			<p class="tlt" data-in-delay="50" data-in-effect="fadeInUp">TẠO DỰNG NIỀM TIN</p>
		</div>
		<p class="sky-auth__tagline">Hệ thống nội bộ dành cho cán bộ nhân viên <b><?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('checkin_brand_name');?>
</b> toàn quốc.</p>
	</div>
	<div class="sky-auth__foot">&copy; <?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('checkin_brand_name');?>
 &middot; Hệ thống nội bộ C-A</div>
</div>
<?php }
}
