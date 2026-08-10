<?php
/* Smarty version 3.1.33, created on 2026-08-06 17:54:07
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/select.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7467cf0f57c6_66240804',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e8c937350702fa55236a1661a54c497ce9cd1dde' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/select.tpl',
      1 => 1785291903,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7467cf0f57c6_66240804 (Smarty_Internal_Template $_smarty_tpl) {
?><select class="form-control<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['required'])) {?> required<?php }?>" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
]"<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['required'])) {?> required<?php }?>>

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
<?php }
}
