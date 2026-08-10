<?php
/* Smarty version 3.1.33, created on 2026-08-06 19:10:49
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/editor.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7479c940af56_45783889',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4235631486b0f4046c47a3f80cf7a291a044d807' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/editor.tpl',
      1 => 1785295352,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7479c940af56_45783889 (Smarty_Internal_Template $_smarty_tpl) {
?><textarea class="form-control textarea_intro_editor" id="config-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
]" rows="<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['rows'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['rows'], ENT_QUOTES, 'UTF-8', true);
} else { ?>8<?php }?>"<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['placeholder'])) {?> placeholder="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current']->value, ENT_QUOTES, 'UTF-8', true);?>
</textarea>
<?php }
}
