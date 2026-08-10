<?php
/* Smarty version 3.1.33, created on 2026-07-31 09:19:32
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/_ajax.load_model.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c0634cfc234_36728537',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0f61790fb15a6474ae8028f8c5ae3da7b96d6b7a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/_ajax.load_model.tpl',
      1 => 1784300231,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c0634cfc234_36728537 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal right fade show w-100" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="dialog">

	<div class="modal-dialog modal-dialog-scrollabe">

		<div class="modal-content overflow-y">

			<div class="modal-header border-bottom">

				<h5 class="modal-title" id="modalTopTitle"><?php if ($_smarty_tpl->tpl_vars['view_type']->value == 'is_handoverSpecs') {?>Tiêu chuẩn bàn giao<?php } else { ?>Hình ảnh, video nhà mẫu<?php }?></h5>

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

			</div>

			<div class="modal-body overflow-y">

				<?php if (!empty($_smarty_tpl->tpl_vars['list_docs']->value)) {?>

					<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxxl-5">

						<!-- End Post -->

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_docs']->value, '_oDoc', false, 'k_doc', 'n_doc', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_doc']->value => $_smarty_tpl->tpl_vars['_oDoc']->value) {
?>

						<div class="col mb-2">

							<?php $_smarty_tpl->_assignInScope('oneItem', $_smarty_tpl->tpl_vars['_oDoc']->value);?>

							<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('item_doc',array('_type'=>"detail",'oneItem'=>$_smarty_tpl->tpl_vars['_oDoc']->value));?>


						</div>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					</div>

				<?php } else { ?>

					<div class="d-flex justify-content-center">

						<div class="text-center p-4">

							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-data.png" height="200">

							<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>

						</div>

					</div>

				<?php }?>

			</div>

		</div>

	</div>

</div><?php }
}
