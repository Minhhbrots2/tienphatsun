<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:02:10
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/number.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6accc2c5b319_73571937',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd19fac84d035a6b6de61ad5621bc7a1fd606207c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/number.tpl',
      1 => 1785291801,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6accc2c5b319_73571937 (Smarty_Internal_Template $_smarty_tpl) {
?><input type="number" class="form-control<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['required'])) {?> required<?php }?>" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['required'])) {?> required<?php }
if (!empty($_smarty_tpl->tpl_vars['val']->value['placeholder'])) {?> placeholder="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }
if (isset($_smarty_tpl->tpl_vars['val']->value['min'])) {?> min="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['min'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }
if (isset($_smarty_tpl->tpl_vars['val']->value['max'])) {?> max="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['max'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }
if (isset($_smarty_tpl->tpl_vars['val']->value['step'])) {?> step="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['step'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?> />
<?php }
}
