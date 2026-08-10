<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:02:10
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/select2.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6accc2c6b796_08292665',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca9743b59007d1afe31b163e12a5c34197f8ea95' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/select2.tpl',
      1 => 1785291908,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6accc2c6b796_08292665 (Smarty_Internal_Template $_smarty_tpl) {
?><select class="form-control iso-select2" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
]<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['multiple'])) {?>[]<?php }?>"<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['multiple'])) {?> multiple<?php }
if (!empty($_smarty_tpl->tpl_vars['val']->value['placeholder'])) {?> placeholder="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>>

	<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['placeholder']) && empty($_smarty_tpl->tpl_vars['val']->value['multiple'])) {?><option value=""><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
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

<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['multiple'])) {?><input type="hidden" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
][]" value="" /><?php }
}
}
