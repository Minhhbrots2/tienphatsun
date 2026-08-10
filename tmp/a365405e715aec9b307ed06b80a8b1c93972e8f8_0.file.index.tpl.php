<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:52:33
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_staff/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315f183e162_76679926',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a365405e715aec9b307ed06b80a8b1c93972e8f8' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_staff/index.tpl',
      1 => 1785927057,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315f183e162_76679926 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="dbx-card h-100">
	<div class="dbx-card__head">
		<span class="dbx-card__ic"><i class="bx bx-star"></i></span>
		<h5 class="dbx-card__title">Nhân viên xuất sắc</h5>
		<span class="dbx-card__chip">Tháng <?php echo smarty_modifier_date_format(time(),"%m/%Y");?>
</span>
	</div>
	<div class="dbx-card__body dbx-card__body--flush ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=top_staff"
	data-options='{"skin":"dbx"}'>
		<div class="loader text-center py-8">
			<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/loading.gif" />
			<p>Loading...</p>
		</div>
	</div>
</div><?php }
}
