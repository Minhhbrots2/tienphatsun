<?php
/* Smarty version 3.1.33, created on 2026-08-05 13:57:16
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/color.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72deccdc51c4_94815166',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2db76d49dae9fd03ac8f8838df37f187ab1efc6f' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/color.tpl',
      1 => 1785291801,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72deccdc51c4_94815166 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="input-group">

	<input type="text" class="form-control config-color__value" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['placeholder'])) {?> placeholder="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
"<?php } else { ?> placeholder="#000000"<?php }?> />

	<div class="input-group-btn">

		<input type="color" class="form-control config-color__picker" value="<?php if (!empty($_smarty_tpl->tpl_vars['current']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['current']->value, ENT_QUOTES, 'UTF-8', true);
} else { ?>#000000<?php }?>" aria-label="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['label'], ENT_QUOTES, 'UTF-8', true);?>
" />

	</div>

</div>
<?php }
}
