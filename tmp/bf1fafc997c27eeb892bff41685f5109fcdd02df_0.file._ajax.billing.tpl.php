<?php
/* Smarty version 3.1.33, created on 2026-08-07 10:21:30
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/dashboard/_ajax.billing.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a754f3a284376_54291781',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bf1fafc997c27eeb892bff41685f5109fcdd02df' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/dashboard/_ajax.billing.tpl',
      1 => 1784300228,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a754f3a284376_54291781 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['list_billings']->value)) {?>

	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_billings']->value, '_oBilling', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBilling']->value) {
?>

	<tr class="trBilling">

		<td class="text-left"><a href="javascript:void(0);" onClick="view_billing(this, event); return false;" 

			billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
"><?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_executable'] == '1') {?><span class="badge bg-label-warning">ĐQ</span><?php }?> <?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['stock_code'];?>
</a></td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oBilling']->value['deposit_date'],3);?>
</td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getIndentityV2($_smarty_tpl->tpl_vars['_oBilling']->value['staff_id'],$_smarty_tpl->tpl_vars['_oBilling']->value['oneStaff'],true);?>
</td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['project_name'];?>
</td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['block_name'];?>
</td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_type'];?>
</td>

		<td class="text-right text-main">

			<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['_oBilling']->value['totalgrand']);?>
 

			<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>


		</td>

	</tr>

	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<?php } else { ?>

	_empty

<?php }?>

<?php }
}
