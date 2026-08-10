<?php
/* Smarty version 3.1.33, created on 2026-08-06 13:23:56
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_config_group.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a74287cf1c319_31683369',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '079a5607fb18fb2bcc9f44a1387e2911cdab36ef' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_config_group.tpl',
      1 => 1785997407,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./fields/".((string)$_smarty_tpl->tpl_vars[\'_oField\']->value[\'type\']).".tpl' => 2,
  ),
),false)) {
function content_6a74287cf1c319_31683369 (Smarty_Internal_Template $_smarty_tpl) {
?><section class="setting-general__group" id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['group']->value['slug'], ENT_QUOTES, 'UTF-8', true);?>
" data-slug="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['group']->value['slug'], ENT_QUOTES, 'UTF-8', true);?>
" data-label="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['group']->value['label'], ENT_QUOTES, 'UTF-8', true);?>
">

	<div class="setting-general__group-head">

		<span class="setting-general__group-icon"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon($_smarty_tpl->tpl_vars['group']->value['icon']);?>
</span>

		<div class="setting-general__group-heading">

			<h2 class="setting-general__group-title"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['group']->value['label'], ENT_QUOTES, 'UTF-8', true);?>
</h2>

			<?php if (!empty($_smarty_tpl->tpl_vars['group']->value['description'])) {?><p class="setting-general__group-desc"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['group']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
</p><?php }?>

		</div>

	</div>

	<div class="setting-general__group-body">

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['group']->value['fields'], '_oField');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oField']->value) {
?>

		<?php $_smarty_tpl->_assignInScope('keyword', $_smarty_tpl->tpl_vars['_oField']->value['keyword']);?>

		<div class="setting-general__field<?php if (!empty($_smarty_tpl->tpl_vars['_oField']->value['raw'])) {?> setting-general__field--code<?php }?>" data-type="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oField']->value['type'], ENT_QUOTES, 'UTF-8', true);?>
" data-keyword="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-label="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oField']->value['label'], ENT_QUOTES, 'UTF-8', true);?>
">

			<?php if ($_smarty_tpl->tpl_vars['_oField']->value['bare']) {?>

			<?php $_smarty_tpl->_subTemplateRender("file:./fields/".((string)$_smarty_tpl->tpl_vars['_oField']->value['type']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('keyword'=>$_smarty_tpl->tpl_vars['keyword']->value,'val'=>$_smarty_tpl->tpl_vars['_oField']->value,'current'=>$_smarty_tpl->tpl_vars['_oField']->value['current'],'width'=>$_smarty_tpl->tpl_vars['_oField']->value['current_width'],'height'=>$_smarty_tpl->tpl_vars['_oField']->value['current_height']), 0, true);
?>

			<?php } else { ?>

			<label class="setting-general__field-label"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oField']->value['label'], ENT_QUOTES, 'UTF-8', true);
if (!empty($_smarty_tpl->tpl_vars['_oField']->value['required'])) {?> <span class="setting-general__field-required">*</span><?php }
if (!empty($_smarty_tpl->tpl_vars['_oField']->value['link'])) {?> <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oField']->value['link'], ENT_QUOTES, 'UTF-8', true);?>
" target="_blank" rel="noopener"><?php if (!empty($_smarty_tpl->tpl_vars['_oField']->value['title'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['_oField']->value['title'], ENT_QUOTES, 'UTF-8', true);
} else {
echo htmlspecialchars($_smarty_tpl->tpl_vars['_oField']->value['link'], ENT_QUOTES, 'UTF-8', true);
}?></a><?php }?></label>

			<?php $_smarty_tpl->_subTemplateRender("file:./fields/".((string)$_smarty_tpl->tpl_vars['_oField']->value['type']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('keyword'=>$_smarty_tpl->tpl_vars['keyword']->value,'val'=>$_smarty_tpl->tpl_vars['_oField']->value,'current'=>$_smarty_tpl->tpl_vars['_oField']->value['current'],'width'=>$_smarty_tpl->tpl_vars['_oField']->value['current_width'],'height'=>$_smarty_tpl->tpl_vars['_oField']->value['current_height']), 0, true);
?>

						<?php if (!empty($_smarty_tpl->tpl_vars['_oField']->value['help_display'])) {?><span class="setting-general__field-help"><?php echo $_smarty_tpl->tpl_vars['_oField']->value['help_display'];?>
</span><?php }?>

			<?php if (!empty($_smarty_tpl->tpl_vars['_oField']->value['attention'])) {?><span class="setting-general__field-help setting-general__field-help--warn"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oField']->value['attention'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php }?>

			<?php }?>

		</div>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	</div>

</section>
<?php }
}
