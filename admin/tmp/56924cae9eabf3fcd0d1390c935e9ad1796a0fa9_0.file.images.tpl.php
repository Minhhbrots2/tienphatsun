<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:02:10
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/images.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6accc2c4cc04_19133954',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '56924cae9eabf3fcd0d1390c935e9ad1796a0fa9' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/images.tpl',
      1 => 1785309079,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6accc2c4cc04_19133954 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="cfg-imgfield">

	<img class="isoman_img_pop" id="isoman_show_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['preview_src'], ENT_QUOTES, 'UTF-8', true);?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['label'], ENT_QUOTES, 'UTF-8', true);?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/none_image.png'" />

	<input type="hidden" id="isoman_hidden_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current']->value, ENT_QUOTES, 'UTF-8', true);?>
" />

	<input type="text" class="form-control" id="isoman_url_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php if (!empty($_smarty_tpl->tpl_vars['val']->value['placeholder'])) {?> placeholder="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?> />

	<input type="number" class="form-control cfg-imgfield__size" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['width_keyword'], ENT_QUOTES, 'UTF-8', true);?>
]" value="<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" min="0" step="1" title="Bề ngang (px)" aria-label="Bề ngang (px)" />

	<input type="number" class="form-control cfg-imgfield__size" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['height_keyword'], ENT_QUOTES, 'UTF-8', true);?>
]" value="<?php echo $_smarty_tpl->tpl_vars['height']->value;?>
" min="0" step="1" title="Chiều cao (px)" aria-label="Chiều cao (px)" />

	<a href="#" class="ajOpenDialog cfg-pick" isoman_for_id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
" isoman_val="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current']->value, ENT_QUOTES, 'UTF-8', true);?>
" isoman_name="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['label'], ENT_QUOTES, 'UTF-8', true);?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/general/folder-32.png" alt="Open" /></a>

</div>
<?php }
}
