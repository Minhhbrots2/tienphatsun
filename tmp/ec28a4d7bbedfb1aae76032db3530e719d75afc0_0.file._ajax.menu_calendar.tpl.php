<?php
/* Smarty version 3.1.33, created on 2026-08-08 11:52:36
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/_ajax.menu_calendar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76b61483c805_89943156',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ec28a4d7bbedfb1aae76032db3530e719d75afc0' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/_ajax.menu_calendar.tpl',
      1 => 1784300226,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76b61483c805_89943156 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['arr_billing_type']->value)) {?>

	<div class="card mb-2 sticky sssssss">

		<h5 class="card-header">Loại hình ký</h5>

		<div class="card-body">

			<div class="d-flex flex-wrap gap-2">

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_billing_type']->value, 'billing_type', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['billing_type']->value) {
?>

					<?php $_smarty_tpl->_assignInScope('lst_number_billing', $_smarty_tpl->tpl_vars['billing_type']->value['lst_number_billing']);?>

					<?php if (!empty($_smarty_tpl->tpl_vars['lst_number_billing']->value)) {?>

						<div class="gbox today flex-fill p-3 w-40">

							<h5 class="mb-2 fs-14"><?php echo $_smarty_tpl->tpl_vars['billing_type']->value['title'];?>
</h5>

							<div class="d-flex align-items-center gap-1 justify-content-between">

								<?php if ($_smarty_tpl->tpl_vars['type_of_date']->value != '_text') {?><span class="text-main fw-semibold"><?php echo $_smarty_tpl->tpl_vars['lst_number_billing']->value['total_contract'];?>
 HĐMB</span><?php }?>

								<?php if ($_smarty_tpl->tpl_vars['type_of_date']->value != '_contract') {?><span class="text-primary fw-semibold"><?php echo $_smarty_tpl->tpl_vars['lst_number_billing']->value['total_agree_date'];?>
 VBTT</span><?php }?>

							</div>

						</div>

					<?php }?>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			</div>			

		</div>

	</div>

<?php }
}
}
