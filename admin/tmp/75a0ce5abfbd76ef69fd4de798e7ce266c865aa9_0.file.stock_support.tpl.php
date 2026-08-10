<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:02:10
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/stock_support.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6accc2c7fde9_18846130',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '75a0ce5abfbd76ef69fd4de798e7ce266c865aa9' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/fields/stock_support.tpl',
      1 => 1785308430,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6accc2c7fde9_18846130 (Smarty_Internal_Template $_smarty_tpl) {
?><label class="setting-general__field-label"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['label'], ENT_QUOTES, 'UTF-8', true);?>
</label>

<div class="setting-general__support">

	<div class="setting-general__support-head">

		<span>Loại bảng hàng</span>

		<span>Tài khoản chính</span>

		<span>Tài khoản phụ</span>

	</div>

	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['val']->value['support_rows'], '_oRow');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oRow']->value) {
?>

	<div class="setting-general__support-row">

		<span class="setting-general__support-name"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oRow']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</span>

		<select placeholder="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['keyword'], ENT_QUOTES, 'UTF-8', true);?>
][<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oRow']->value['property_id'], ENT_QUOTES, 'UTF-8', true);?>
]" class="form-control iso-selectizeLiveSearch" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=member&act=get_member_search">

			<?php if ($_smarty_tpl->tpl_vars['_oRow']->value['main_id']) {?><option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oRow']->value['main_id'], ENT_QUOTES, 'UTF-8', true);?>
" selected="selected"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oRow']->value['main_name'], ENT_QUOTES, 'UTF-8', true);?>
</option><?php }?>

		</select>

		<select placeholder="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['placeholder'], ENT_QUOTES, 'UTF-8', true);?>
" name="config[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value['pair_keyword'], ENT_QUOTES, 'UTF-8', true);?>
][<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oRow']->value['property_id'], ENT_QUOTES, 'UTF-8', true);?>
]" class="form-control iso-selectizeLiveSearch" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=member&act=get_member_search">

			<?php if ($_smarty_tpl->tpl_vars['_oRow']->value['extra_id']) {?><option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oRow']->value['extra_id'], ENT_QUOTES, 'UTF-8', true);?>
" selected="selected"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oRow']->value['extra_name'], ENT_QUOTES, 'UTF-8', true);?>
</option><?php }?>

		</select>

	</div>

	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

</div>
<?php }
}
