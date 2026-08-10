<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:32:48
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.loadFavourite.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7695500d8b37_04207349',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '51981d2daea2fbb58e633c8ca3a11ae603b53e19' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.loadFavourite.tpl',
      1 => 1784300214,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7695500d8b37_04207349 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal right fade show" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header border-bottom d-flex align-items-center justify-content-between">

				<h5 class="modal-title" id="modalTopTitle">Danh sách yêu thích</h5>

				<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('favourite');?>
" class="btn btn-outline-primary ml-2">Xem tất cả</a>

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

			</div>

			<div class="modal-body scroller">

				<ul class="list-unstyled" id="list_favourite" data-bs-popper="static">

					<?php if (!empty($_smarty_tpl->tpl_vars['list_stocks']->value)) {?>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_stocks']->value, '_oItem', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

							<?php $_smarty_tpl->_assignInScope('lstImage', $_smarty_tpl->tpl_vars['_oItem']->value['images']);?>

							<?php $_smarty_tpl->_assignInScope('moreInformation', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>

							<li class="py-1 item_pop_favourite <?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?>border-bottom<?php }?> cursor-pointer" >

								<div class="d-flex justify-content-between align-items-center user-name">

									<div class="d-flex flex-column pl-2" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>onclick="$Core.helper.open_stock(<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
)"<?php } else { ?> data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" data-toggle="webui-popover" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="600" data-target="webuiPopover<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" <?php }?>>

										<a href="javscript:void(0)" class="text-heading text-truncate">

											<span class="fw-medium"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['ms_code'];?>
</span>

										</a>

										<div class="d-flex flex-wrap gap-1 align-items-center fs-12 awe__sop-location py-1">

											<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['location'])) {?>

												<div class="mr-2 text_ellipsis d-flex align-items-center">

													<i class="re__icon-location--sm mr-1"></i>

													<span class="text-main"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['location'];?>
</span>

												</div>													

											<?php }?>

											<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['bedroom']) {?>

												<div class="mr-2 text_ellipsis d-flex align-items-center">

													<i class="re__icon-bedroom--sm mr-1"></i>

													<span class="text-main"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bedroom'];?>
</span>

												</div>

											<?php }?>

											<?php if ($_smarty_tpl->tpl_vars['moreInformation']->value['DT_TT']) {?>

												<div class="mr-2 text_ellipsis d-flex align-items-center">

													<i class="re__icon-size--sm mr-1"></i>

													<span class="text-main"><?php echo $_smarty_tpl->tpl_vars['moreInformation']->value['DT_TT'];?>
m<sup>2</sup></span>

												</div>

											<?php }?>

											<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['home_direction']) {?>

												<div class="mr-2 text_ellipsis d-flex align-items-center">

													<i class="re__icon-ying-yang--xl mr-1"></i>

													<span class="text-main"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['home_direction'];?>
</span>

												</div>

											<?php }?>

										</div>



									</div>

									<a onClick="$Core.helper.toggle_wishlist(this,event)" data-bs-toggle="tooltip" title="Loại bỏ" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" class="btn saved p-1 pop_favourite"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-heart fs-11');?>
</a>

								</div>

							</li>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>							

					<?php } else { ?>

						<div class="d-flex flex-column align-items-center justify-content-center p-3">

							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg" width="90px">

							<p>Chưa có căn hộ nào trong danh mục yêu thích..</p>

						</div>

					<?php }?>

				</ul>

			</div>

		</div>

	</div>

</div><?php }
}
