<?php
/* Smarty version 3.1.33, created on 2026-08-07 19:28:01
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/_ajax.compare_stock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75cf51d8fc60_96428320',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a90fb914ce713a1f3c7f57d315e180511b4d560f' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/_ajax.compare_stock.tpl',
      1 => 1784300231,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75cf51d8fc60_96428320 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-dialog-centered modal-lg modal_compare">

	<form class="modal-content" id="frmIssue" enctype="multipart/form-data">		

		<div class="modal-header card-header d-flex align-items-center justify-content-between gap-2 bg-main text-white py-3">

			<div class="card-title mb-0 text-upper flex-fill">So sánh căn hộ</div>

			<button class="btn-add-can btn btn-sm btn-default text-white" onClick="$Core.global.compare.add_stock_compare(this,event)" _tp="modal" action="open" toId="body_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" ><i class="bx bx-plus" ></i> Thêm căn</button>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body bg-lighter">

			<div class="card no-shadow border">

				<div class="card-body pt-3" id="body_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" >

					<div class="table-container overflow-x-auto text-nowrap no-shadow bg-white">

						<?php if (!empty($_smarty_tpl->tpl_vars['arr_compare']->value)) {?>

							<table cellpadding="0" cellspacing="0" width="100%" class="table table-billing table-bordered dragable mb-0">

								<thead>

									<tr>

										<th class="align-center bg-lighter h-px-40" width="150px"></th>

										<th class="align-center bg-lighter h-px-40">

											<div class="card h-100 no-shadow border">

												<div class="apt-card p-2">							

													<div class="apt-code mb-1" href="<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getLink($_smarty_tpl->tpl_vars['_oItem']->value['ms_code']);?>
" target="_blank" ><div class="animate-bg w-100 rounded-2 h-px-15"></div> <i class="fas fa-external-link-alt" style="font-size:10px"></i></div>

													<div class="apt-price mb-1 text-main fw-bold fs-5"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>

													<div class="apt-price mb-1 text-main fw-bold fs-5"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>

													<div class="apt-promo2 mb-1"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>

												</div>

											</div>

										</th>

										<th class="align-center bg-lighter h-px-40">

											<div class="card h-100 no-shadow border">

												<div class="apt-card p-2">			

													<button class="btn btn-icon btn-sm position-absolute right-0 top-0"><i class="bx bx-x" ></i></button>							

													<div class="apt-code mb-1" href="<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getLink($_smarty_tpl->tpl_vars['_oItem']->value['ms_code']);?>
" target="_blank" ><div class="animate-bg w-100 rounded-2 h-px-15"></div> <i class="fas fa-external-link-alt" style="font-size:10px"></i></div>

													<div class="apt-price mb-1 text-main fw-bold fs-5"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>

													<div class="apt-price mb-1 text-main fw-bold fs-5"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>

													<div class="apt-promo2 mb-1"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>

												</div>

											</div>

										</th>

									</tr>

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

					</div>

				</div>

			</div>

		</div>

		<div class="modal-footer justify-content-center">

			<button type="button" class="btn btn-outline-secondary d-none" id="delete_all_body_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.global.compare.delete_all_compare(this,event)" toId="body_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Xóa tất cả</button>

		</div>

	</form>

</div>



<style>

	.modal_compare .table-container .table tbody tr td {

		background: inherit !important;

	}

</style>



<?php }
}
