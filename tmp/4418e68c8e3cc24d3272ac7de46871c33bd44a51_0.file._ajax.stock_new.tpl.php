<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:14:21
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/_ajax.stock_new.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a730cfdf2a5a6_64820885',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4418e68c8e3cc24d3272ac7de46871c33bd44a51' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/_ajax.stock_new.tpl',
      1 => 1784300232,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a730cfdf2a5a6_64820885 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['list_stocks']->value)) {?>

	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_stocks']->value, '_oStock', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStock']->value) {
?>

		<tr class="p_row<?php if ($_smarty_tpl->tpl_vars['_oStock']->value['agency_id'] == @constant('_AGENCY_CNCN_ID')) {?> bg-purple<?php } elseif ($_smarty_tpl->tpl_vars['_oStock']->value['agency_id'] == @constant('_AGENCY_FH_ID')) {?> bg-label-fh<?php }?>">

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_config_column']->value, '_field', false, 'key', 'i_field', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_field']->value) {
?>

				<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getFieldStock(@constant('_BLOCK_TYPE_LOWFLOOR_SALE'),$_smarty_tpl->tpl_vars['_field']->value,$_smarty_tpl->tpl_vars['_oStock']->value,$_smarty_tpl->tpl_vars['arr_props_cached']->value,$_smarty_tpl->tpl_vars['arr_property_cached']->value);?>


			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		</tr>

	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<?php } else { ?>

	<tr>

		<td colspan="<?php echo count($_smarty_tpl->tpl_vars['lst_config_column']->value);?>
">

			<div class="p-5 text-center">

				<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/table-no-data.png" width="150px" />

				<p>Không có dữ liệu</p>

			</div>

		</td>

	</tr>

<?php }
}
}
