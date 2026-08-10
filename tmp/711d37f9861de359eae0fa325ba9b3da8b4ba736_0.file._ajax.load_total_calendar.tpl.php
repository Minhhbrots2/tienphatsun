<?php
/* Smarty version 3.1.33, created on 2026-08-08 11:52:35
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/_ajax.load_total_calendar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76b613ebe780_58068000',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '711d37f9861de359eae0fa325ba9b3da8b4ba736' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/_ajax.load_total_calendar.tpl',
      1 => 1784300226,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76b613ebe780_58068000 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['arr_data_admin']->value)) {?>

	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_data_admin']->value, '_oAdmin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oAdmin']->value) {
?>

		<tr class="trBilling">

			<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oAdmin']->value['admin_name'];?>
</td>

			<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oAdmin']->value['total_contract'];?>
</td>

			<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oAdmin']->value['total_agree'];?>
</td>

		</tr>

	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<?php } else { ?>

	<tr class="trBilling">

		<td class="text-center" colspan="3">Danh sách trống</td>

	</tr>

<?php }
}
}
