<?php
/* Smarty version 3.1.33, created on 2026-08-07 10:40:57
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.sale.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7553c988f059_51085860',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a60d77df86799ffae0a98c748132d524f83e9936' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.sale.tpl',
      1 => 1784299673,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7553c988f059_51085860 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="bg-eee radius-3 p-3 mb-3">

	<div class="d-flex flex-wrap gap-2 overflow-auto justify-content-between mb-2">

		<div class="lst_STATUS_BOOKING bg-white p-3 radius-3 cursor-pointer" onClick="$Core.report.do_action(this, event)" 

		billing_type="all" stt_color="#FFF">

			<p class="fs-12 text-muted">Tất cả</p>

			<div class="d-flex justify-content-between align-items-center">

				<span class="fs-16 text-bold"><?php echo $_smarty_tpl->tpl_vars['total_billings']->value;?>
</span>

			</div>

		</div>

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_billing_types']->value, '_oItem', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

		<div class="lst_STATUS_BOOKING bg-white p-3 rounded-2 cursor-pointer" onClick="$Core.report.do_action(this, event)" 

		billing_type="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['billing_type']->value == $_smarty_tpl->tpl_vars['_oItem']->value['property_id']) {?> style="background-color:<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bgcolor'];?>
 !important;color:#FFF !important"<?php }?>>

			<p class="fs-12 text-nowrap"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</p>

			<div class="d-flex justify-content-between align-items-center">

				<span class="fs-16 text-bold"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_billings'];?>
</span>

			</div>

		</div>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<div class="p-3 bg-white rounded-2 cursor-pointer detail_profit_sale">

			<p>Tất cả doanh số</p>

			<h3 class="mb-0 text-primary"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['total_sales']->value);?>
</h3>

		</div>

	</div>

</div>

<div class="table-container overflow-x-auto no-shadow text-nowrap">

	<table cellpadding="0" cellspacing="0" class="table table-sort table-bordered" width="100%">

		<thead><tr>

			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

			<th width="30px" class="align-center h-px-35 nosort bg-lighter">STT</th>

			<th width="10%" class="align-center h-px-35 nosort text-left bg-lighter">Mã NV</th>

			<th width="20%" class="align-center h-px-35 nosort text-left bg-lighter">Họ và tên</th>

			<th width="10%" class="align-center h-px-35 nosort bg-lighter">Mã Phòng</th>

			<th width="20%" class="align-center h-px-35 nosort bg-lighter">Chức vụ</th>

			<th width="100px" class="align-center h-px-35 nosort bg-lighter">Ngày vào làm</th>

			<th width="90px" class="align-center h-px-35 sortable text-center bg-lighter">Số GD</th>

			<th class="align-center text-left h-px-35 sortable bg-lighter">Doanh số</th>

			<th width="100px" class="align-center h-px-35 nosort bg-lighter">Ngày vào làm</th>

			<?php } else { ?>

			<th width="45%" class="align-center h-px-35 nosort text-left bg-lighter">Họ và tên</th>

			<th width="40px" class="align-center h-px-35 sortable text-center bg-lighter">Số GD</th>

			<th class="align-center text-left h-px-35 sortable bg-lighter">Doanh số</th>

			<?php }?>

		</tr></thead>

		<?php if (!empty($_smarty_tpl->tpl_vars['list_staffs']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_staffs']->value, '_oStaff', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

			<tr>

				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

				<td class="align-center text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

				<td class="align-center text-left"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['code'];?>
</td>

				<?php }?>

				<td><strong><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'],$_smarty_tpl->tpl_vars['_oStaff']->value);?>
</strong></td>

				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

				<td class="align-center text-center"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['department_name'];?>
</td>

				<td class="align-center text-left"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['role'];?>
</td>

				<td class="align-center text-left"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['rangeDate'];?>
</td>

				<?php }?>

				

				<td class="align-center text-center"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['total_billings'];?>
</td>

				<td class="align-center text-left">

					<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

						<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->shortNumber($_smarty_tpl->tpl_vars['_oStaff']->value['total_sales']);?>


					<?php } else { ?>

						<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['_oStaff']->value['total_sales']);?>
 <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>


					<?php }?>

				</td>

				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

				<td class="align-center text-left"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['startDate'];?>
</td>

				<?php }?>

			</tr>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php }?>

	</table>

</div><?php }
}
