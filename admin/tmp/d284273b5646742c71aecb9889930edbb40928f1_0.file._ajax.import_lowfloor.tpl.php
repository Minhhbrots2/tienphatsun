<?php
/* Smarty version 3.1.33, created on 2026-07-31 09:41:57
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/stock/_ajax.import_lowfloor.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c0b75051128_63387640',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd284273b5646742c71aecb9889930edbb40928f1' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/stock/_ajax.import_lowfloor.tpl',
      1 => 1784691763,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c0b75051128_63387640 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-md">

	<div class="modal-content">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 

			<h3 class="modal-title"><strong>Nhập bảng hàng thấp tầng</strong></h3>

		</div>

		<form method="POST" action="" enctype="multipart/form-data">

			<div class="modal-body">

				<table class="table" width="100%">

					<thead><tr>

						<th class="text-center" width="3%">No.</th>

						<th>Dự án</th>

						<th class="text-center" width="120px">Craw</th>

					</tr></thead>

					<?php if (!empty($_smarty_tpl->tpl_vars['list_projects']->value)) {?>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, '_oProject', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

						<?php $_smarty_tpl->_assignInScope('project_id', $_smarty_tpl->tpl_vars['_oProject']->value['project_id']);?>

						<tr>

							<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

							<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
 <br />

								<?php if (!empty($_smarty_tpl->tpl_vars['_oProject']->value['spreadsheetId'])) {?>

								<a href="https://docs.google.com/spreadsheets/d/<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['spreadsheetId'];?>
/edit" target="_blank">https://docs.google.com/spreadsheets/d/<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['spreadsheetId'];?>
/edit</a>

								<?php } else { ?>

								<span class="text-muted"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('exclamation-triangle','Chưa có file cài đặt');?>
</span>

								<?php }?>

							</td>

							<td class="text-center">

								<div class="d-flex box_crawl">

									<button onclick="$Core.stock.start_import_lowfloor(this, event)"<?php if (!empty($_smarty_tpl->tpl_vars['_oProject']->value['spreadsheetId'])) {
} else { ?> disabled<?php }?> project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" 

									spreadsheetId="<?php echo $_smarty_tpl->tpl_vars['spreadsheetId']->value;?>
" class="btn btn-success mr-2">Crawl</button>

									<button onclick="$Core.stock.open_image(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="btn btn-danger"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Image to GG driver');?>
</button>

									<input type="file" name="images[]" class="file_upload d-none" data-type="_IMAGE" onChange="$Core.stock.start_import_stock_lowfloor(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" multiple="true"/>

								</div>

							</td>

						</tr>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<?php }?>

				</table>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Close');?>
</button>

			</div>

		</form>

	</div>

</div><?php }
}
