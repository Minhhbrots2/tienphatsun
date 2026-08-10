<?php
/* Smarty version 3.1.33, created on 2026-07-30 17:55:19
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/project/report_dq.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6b2d978a2b28_66039324',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5243688869b9c06ce4b54631bc1732c43f415dba' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/project/report_dq.tpl',
      1 => 1784300241,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6b2d978a2b28_66039324 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 pt-2 container-p-y">

	<div class="form-row">

		<div class="col-12 col-lg-9 col-xxxl-7 mx-auto">

			<div class="card">

				<div class="card-header"><h4 class="fw-bold mb-1"><span>Danh sách quỹ độc quyền dự án</span></h4></div>

				<div class="card-body">

					<div id="list_stock_DQ" class="table-container no-shadow overflow-x-auto mb-3">

						<table class="table table-striped dragable table-bordered installed" width="100%" cellspacing="0" cellpadding="0">

							<thead><tr>

								<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

								<th width="30px" class="align-center h-px-40 align-center nosort bg-lighter">STT</th>

								<?php }?>

								<th class="align-center h-px-40 text-left bg-lighter">Quỹ độc quyền</th>

								<th class="align-center h-px-40 text-center bg-lighter">Số căn</th>

								<th class="align-center h-px-40 text-center bg-lighter">Tổng giá VAT</th>

							</tr></thead>

							<tbody>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_stock']->value, '_oItem', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

									<tr>

										<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

											<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

										<?php }?>

										<td><a href="javaxcript:void(0)" onClick="$Core.report.view_stock_dq(this,event)" block_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['block_id'];?>
" class="text-link"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</a></td>

										<td  class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total'];?>
</td>

										<td  class="text-center"><?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {
echo $_smarty_tpl->tpl_vars['clsISO']->value->shortNumberV2($_smarty_tpl->tpl_vars['_oItem']->value['total_price_vat'],2);
} else {
echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormat($_smarty_tpl->tpl_vars['_oItem']->value['total_price_vat']);?>
 VNĐ<?php }?></td>

									</tr>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								

							</tbody>

							<tfooter>

								<tr class="nohover">

									<td class="h-px-40 text-center text-upper fw-bold text-main" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>2<?php } else { ?>1<?php }?>">Tổng cộng</td>

									<td class="h-px-40 text-center text-upper fw-bold text-main"><?php echo $_smarty_tpl->tpl_vars['total']->value;?>
</td>

									<td class="h-px-40 text-center text-upper fw-bold text-main"><?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {
echo $_smarty_tpl->tpl_vars['clsISO']->value->shortNumberV2($_smarty_tpl->tpl_vars['total_price']->value,2);
} else {
echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormat($_smarty_tpl->tpl_vars['total_price']->value);?>
 VNĐ<?php }?></td>

								</tr>

							</tfooter>

						</table>

					</div>

				</div>

			</div>

		</div>

	</div>

</div><?php }
}
