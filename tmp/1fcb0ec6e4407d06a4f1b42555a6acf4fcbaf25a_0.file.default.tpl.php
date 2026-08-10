<?php
/* Smarty version 3.1.33, created on 2026-08-06 15:56:30
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/quote/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a744c3e7af604_51431577',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1fcb0ec6e4407d06a4f1b42555a6acf4fcbaf25a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/quote/default.tpl',
      1 => 1784299669,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a744c3e7af604_51431577 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 pt-2 container-p-y">

	<div class="form-row">

		<div class="col-12 col-lg-9 col-xxxl-7 mx-auto">

			<div class="card">

				<div class="card-header">

					<div class="d-flex  align-items-center justify-content-between">

						<div class="d-flex flex-column">

							<h5 class="chat-title mb-0">Danh sách trích dẫn</h5>

							<span class="text-muted fs-11">Tổng <strong class="text-main"><?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
</strong> câu trích dẫn</span>

						</div>

						<button onClick="$Core.quote.open(this, event)" table_id="0" type="button" class="btn btn-outline-default <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>btn-sm<?php }?>">

							<i class="bx bx-plus"></i> Thêm

						</button>

					</div>

				</div>

				<div class="card-body">

					<div class="table-container no-shadow overflow-x-auto mb-3">

						<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped dragable table-bordered">

							<thead><tr>

								<!--<th class="align-center h-px-35 bg-lighter">Type</th> -->

								<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

								<th class="align-center h-px-35 bg-lighter" width="40px">STT</th>

								<?php }?>

								<th class="align-center h-px-35 bg-lighter" >Nội dung</th>

								<th class="align-center h-px-35 bg-lighter" >Tác giả</th>

								<th class="align-center h-px-35 bg-lighter" >Chia sẻ</th>

								<th class="align-center h-px-35 bg-lighter text-center">Thời gian</th>

								<th class="align-center h-px-35 bg-lighter text-center">Trạng thái</th>

								<th class="align-center h-px-35 bg-lighter" width="40px"></th>

							</tr></thead>

							<tbody class="holder_chatlogs">

								<?php if (!empty($_smarty_tpl->tpl_vars['lstItem']->value)) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

										<tr class="tr">

											<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

											<td class="text-center" width="40"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

											<?php }?>

											<td class="text-left"><div class="limit_2line"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['content'];?>
</div></td>

											<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['author'];?>
</td>

											<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['html_share'];?>
</td>

											<td class="text-nowrap text-center"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oItem']->value['upd_date'],4);?>
</td>

											<td class="text-nowrap text-center"><?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['has_show'])) {?> <span class="text-success">Đã xuất hiện</span><?php } else { ?><span class="text-muted">Chưa xuất hiện</span><?php }?></td>

											<td class="text-nowrap text-center">

												<?php if ($_smarty_tpl->tpl_vars['profile_id']->value != $_smarty_tpl->tpl_vars['_oItem']->value['user_id']) {?>

													<div class="btn-group">

														<span href="javascript:void(0);" title="Sửa" class="btn btn-icon btn-sm btn-outline-default" style="cursor: no-drop;opacity:0.5"><i class="bx bx-edit-alt"></i></span>

														<span href="javascript:void(0);" title="Xóa" class="btn btn-icon btn-sm btn-outline-default" style="cursor: no-drop;opacity:0.5"><i class="bx bx-trash"></i></span>

													</div>	

												<?php } else { ?>

													<div class="btn-group ">

														<a href="javascript:void(0);" title="Sửa" class="btn btn-icon btn-sm btn-outline-default" onclick="$Core.quote.open(this,event)" table_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['id'];?>
"><i class="bx bx-edit-alt"></i></a>

														<a href="javascript:void(0);" title="Xóa" class="btn btn-icon btn-sm btn-outline-default" onclick="$Core.quote.delete(this,event)" table_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['id'];?>
"><i class="bx bx-trash"></i></a>

													</div>	

												<?php }?>

											</td>

										</tr>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?> 

								<?php } else { ?>

									<tr class="tr">

										<td colspan="4" class="text-center">Danh sách trống</td>

									</tr>

								<?php }?>

							</tbody>

						</table>

					</div>

						<?php if (!empty($_smarty_tpl->tpl_vars['html_pager']->value)) {?>

							<div class="pagination justify-content-center"><?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>
</div>

						<?php }?>

				</div>

			</div>

		</div>

	</div>

</div><?php }
}
