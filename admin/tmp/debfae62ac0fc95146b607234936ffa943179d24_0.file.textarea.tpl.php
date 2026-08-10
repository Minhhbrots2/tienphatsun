<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:02:10
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/textarea.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6accc2c3d8d7_53963406',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'debfae62ac0fc95146b607234936ffa943179d24' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/textarea.tpl',
      1 => 1785296727,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6accc2c3d8d7_53963406 (Smarty_Internal_Template $_smarty_tpl) {
?><textarea class="form-control<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['required'])) {?> required<?php }?>" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
]" rows="<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['rows'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['rows'], ENT_QUOTES, 'UTF-8', true);
} else { ?>3<?php }?>"<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['required'])) {?> required<?php }
if (!empty($_smarty_tpl->tpl_vars['val']->value['placeholder'])) {?> placeholder="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current']->value, ENT_QUOTES, 'UTF-8', true);?>
</textarea>
<?php }
}
