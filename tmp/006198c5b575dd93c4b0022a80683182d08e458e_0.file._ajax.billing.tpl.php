<?php
/* Smarty version 3.1.33, created on 2026-08-07 15:51:20
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.billing.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a759c88894c20_73453796',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '006198c5b575dd93c4b0022a80683182d08e458e' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.billing.tpl',
      1 => 1786085970,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a759c88894c20_73453796 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="table-container no-shadow overflow-x-auto text-nowrap">

	<table border="0" cellpadding="0" cellspacing="0" class="table mb-0" width="100%">

		<thead><tr>

			<th class="align-center bg-lighter h-px-35">Mã căn</th>

			<th class="align-center bg-lighter h-px-35">Ngày cọc</th>

			<th class="align-center bg-lighter h-px-35">Ngày ký HĐMB</th>

			<th class="align-center bg-lighter h-px-35">Loại hình</th>

			<th class="align-center bg-lighter h-px-35">Dự án</th>

			<th class="align-center bg-lighter h-px-35 w-px-150">Tổng tiền</th>

			<th class="align-center bg-lighter h-px-35" width="35px"></th>

		</tr></thead>

		<?php if (!empty($_smarty_tpl->tpl_vars['list_billings']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_billings']->value, '_oBilling', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBilling']->value) {
?>

			<tr class="trBilling<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_cancel'] == '1') {?> bg-cancel<?php }?>">

				

				<td class="text-left"><a href="javascript:void(0);" onClick="$Core.global.billing.view_billing(this, event); return false;" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['stock_code'];?>
</a></td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oBilling']->value['deposit_date'],3);?>
</td>

				<td class="text-left">

					<?php if (!empty($_smarty_tpl->tpl_vars['_oBilling']->value['contract_date'])) {?>

						<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oBilling']->value['contract_date'],3);?>


					<?php } else { ?>

						---

					<?php }?>

				</td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_type'];?>
</td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['poroject_name'];?>
</td>

				<td class="text-right"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['_oBilling']->value['totalgrand']);?>
</td>

				<td class="text-center">

					<div class="dropdown">

						<button type="button" class="btn p-0 dropdown-toggle hide-arrow"

							data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>

						</button>

						<div class="dropdown-menu w-px-100">

							<a class="dropdown-item" onClick="view_billing(this,event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
" 

							href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>

							<?php if ($_smarty_tpl->tpl_vars['profile_id']->value == $_smarty_tpl->tpl_vars['_oBilling']->value['staff_id'] && $_smarty_tpl->tpl_vars['_oBilling']->value['ns_confirm'] == '0' && $_smarty_tpl->tpl_vars['_oBilling']->value['ms_confirm'] == '0') {?>

							<a class="dropdown-item" href="javascript:void(0);" onClick="delete_billing(this,event)" 

								billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
">

								<i class="bx bx-trash me-1"></i> Xóa</a>

							<?php }?>

						</div>

					</div>

				</td>

			</tr>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php } else { ?>

			<tr><td class="text-center" colspan="8">

				<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/illustration-empty-results.svg" class="w-px-200" />

				<p class="text-muted">Chưa có giao dịch</p>

			</td></tr>

		<?php }?>

	</table>

</div>

<div id="pager_staff_building_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"></div><?php }
}
