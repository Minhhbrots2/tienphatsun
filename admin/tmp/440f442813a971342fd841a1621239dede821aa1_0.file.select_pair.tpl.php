<?php
/* Smarty version 3.1.33, created on 2026-08-06 17:54:07
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/select_pair.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7467cf0ffdf8_86509638',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '440f442813a971342fd841a1621239dede821aa1' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/select_pair.tpl',
      1 => 1786009703,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7467cf0ffdf8_86509638 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="setting-general__pair">

	<select class="form-control setting-general__pair-main" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
][<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['sub_keyword'], ENT_QUOTES, 'UTF-8', true);?>
]" data-pair-show="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['pair']['show_when'], ENT_QUOTES, 'UTF-8', true);?>
">

		<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['placeholder'])) {?><option value=""><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
</option><?php }?>

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['val']->value['select'], '_optionLabel', false, '_optionKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_optionKey']->value => $_smarty_tpl->tpl_vars['_optionLabel']->value) {
?>

		<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_optionKey']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php if (isset($_smarty_tpl->tpl_vars['val']->value['current_map'][$_smarty_tpl->tpl_vars['_optionKey']->value])) {?> selected="selected"<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_optionLabel']->value, ENT_QUOTES, 'UTF-8', true);?>
</option>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	</select>

	<div class="setting-general__pair-extra<?php if ($_smarty_tpl->tpl_vars['val']->value['pair']['is_open']) {?> is-open<?php }?>">

				<select class="form-control iso-select2" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
][<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['pair']['keyword'], ENT_QUOTES, 'UTF-8', true);?>
]">

			<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['pair']['placeholder'])) {?><option value=""><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['pair']['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
</option><?php }?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['val']->value['pair']['select'], '_optionLabel', false, '_optionKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_optionKey']->value => $_smarty_tpl->tpl_vars['_optionLabel']->value) {
?>

			<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_optionKey']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php if (isset($_smarty_tpl->tpl_vars['val']->value['pair']['current_map'][$_smarty_tpl->tpl_vars['_optionKey']->value])) {?> selected="selected"<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_optionLabel']->value, ENT_QUOTES, 'UTF-8', true);?>
</option>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		</select>

	</div>

</div>
<?php }
}
