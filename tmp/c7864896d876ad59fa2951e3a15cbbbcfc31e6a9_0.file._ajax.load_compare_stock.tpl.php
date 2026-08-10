<?php
/* Smarty version 3.1.33, created on 2026-08-07 19:28:02
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/_ajax.load_compare_stock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75cf522c8b28_67522052',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c7864896d876ad59fa2951e3a15cbbbcfc31e6a9' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/_ajax.load_compare_stock.tpl',
      1 => 1784300231,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75cf522c8b28_67522052 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="table-container overflow-x-auto text-nowrap no-shadow bg-white">

	<?php if (!empty($_smarty_tpl->tpl_vars['arr_compare']->value)) {?>

		<table cellpadding="0" cellspacing="0" width="100%" class="table table-billing table-bordered dragable mb-0">

			<thead>

				<tr>

					<th class="align-center bg-lighter h-px-40" width="150px">Tiêu chí</th>

					<?php if (!empty($_smarty_tpl->tpl_vars['arr_compare']->value)) {?>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_compare']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

							<th class="align-center bg-lighter h-px-40">

								<div class="card h-100 no-shadow border">

									<div class="apt-card p-2">			

										<button class="btn btn-icon btn-sm position-absolute right-0 top-0" type="button" onclick="$Core.global.compare.add_stock_compare(this,event)" _tp="modal" action="delete" stock_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"><i class="bx bx-x" ></i></button>							

										<a class="apt-code" href="<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getLink($_smarty_tpl->tpl_vars['_oItem']->value['ms_code']);?>
" target="_blank" ><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_code'];?>
 <i class="fas fa-external-link-alt" style="font-size:10px"></i></a>

										<div class="apt-price text-main fw-bold fs-5"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_price_vat'];?>
</div>

										<div class="apt-promo text-warning fs-12" style="text-transform: initial">Giá gồm VAT &amp; KPBT</div>

										<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['csbh'])) {?><div class="apt-promo2">CSBH: <span class="fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['csbh'];?>
</span></div><?php }?>

									</div>

								</div>

							</th>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<?php }?>

				</tr>

			</thead>

			<tbody class="table-border-bottom-0">

				<?php if (!empty($_smarty_tpl->tpl_vars['arr_aciteria']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_aciteria']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

						<tr class="trBilling nohover" <?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['bgcolor'])) {?> style="background-color:<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bgcolor'];?>
"<?php }?> >

							<td class="align-center"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_compare']->value, '_oCompare', false, 'k_compare', 'i_compare', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_compare']->value => $_smarty_tpl->tpl_vars['_oCompare']->value) {
?>

								<td class="align-center text-center <?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['stock_min']) && $_smarty_tpl->tpl_vars['_oItem']->value['stock_min'] == $_smarty_tpl->tpl_vars['k_compare']->value) {?>text-success fw-bold<?php } elseif (!empty($_smarty_tpl->tpl_vars['_oItem']->value['stock_min']) || $_smarty_tpl->tpl_vars['_oItem']->value['stock_max'] == $_smarty_tpl->tpl_vars['k_compare']->value) {?>text-danger fw-bold<?php }?>"><?php echo $_smarty_tpl->tpl_vars['_oCompare']->value[$_smarty_tpl->tpl_vars['key']->value];?>
 <?php echo $_smarty_tpl->tpl_vars['_oItem']->value['unit'];?>
</td>

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

					<tr class=" nohover">

						<td class="text-center" colspan="3">

							Chưa có tiêu chí

						</td>

					</tr>

				<?php }?>

			</tbody>

		</table>

	<?php } else { ?>

		<table cellpadding="0" cellspacing="0" width="100%" class="table table-billing table-bordered dragable mb-0">

			<thead>

				<tr>

					<th class="align-center bg-lighter h-px-40" width="150px">Tiêu chí</th>

					<th class="align-center bg-lighter h-px-40"><div class="animate-bg w-100 rounded-2 h-px-15"></div></th>

					<th class="align-center bg-lighter h-px-40"><div class="animate-bg w-100 rounded-2 h-px-15"></div></th>

				</tr> 

			</thead>

			<tbody class="table-border-bottom-0">

				<?php if (!empty($_smarty_tpl->tpl_vars['arr_aciteria']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_aciteria']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

						<tr class="trBilling ">

							<td class="align-center"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>

							<td class="align-center text-center"><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

							<td class="align-center text-center"><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

						</tr>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php } else { ?>

					<tr>

						<td class="text-center" colspan="3">

							Chưa có tiêu chí

						</td>

					</tr>

				<?php }?>

			</tbody>

		</table>

	<?php }?>

</div><?php }
}
