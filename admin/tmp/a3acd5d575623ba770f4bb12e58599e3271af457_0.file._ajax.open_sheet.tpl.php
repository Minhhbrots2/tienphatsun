<?php
/* Smarty version 3.1.33, created on 2026-07-31 13:31:36
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/crawl/_ajax.open_sheet.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c414842b911_39242532',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a3acd5d575623ba770f4bb12e58599e3271af457' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/crawl/_ajax.open_sheet.tpl',
      1 => 1784691583,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c414842b911_39242532 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

	<form action="" method="post" class="modal-content" encrypt="miltipart/form-data">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 

			<h3 class="modal-title"><strong>Lựa chọn Sheet</strong></h3>

		</div>

		<div class="modal-body">

			<table class="table">

				<thead><tr>

					<th width="5%"></th>

					<th width="10%">Sheet ID</th>

					<th >Sheet Name</th>

					<th >Bảng hàng theo tầng/trục</th>

				</tr></thead>

				<?php if (!empty($_smarty_tpl->tpl_vars['arr_worksheets']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_worksheets']->value, '_oSheet', false, '_oKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oKey']->value => $_smarty_tpl->tpl_vars['_oSheet']->value) {
?>

					<tr>

						<td class="align-center text-center">

							<label class="switch">

								<input type="checkbox" class="<?php echo $_smarty_tpl->tpl_vars['spreadsheetId']->value;?>
" name="sheets[]" sheet_id="<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['_oSheet']->value;?>
">

								<span class="slider round"></span>

							</label>

						</td>

						<td class="align-center text-left"><?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
</td>

						<td class="align-center text-left"><?php echo $_smarty_tpl->tpl_vars['_oSheet']->value;?>
</td>

						<td class="align-center text-center">

							<label class="switch">

								<input type="checkbox" class="is_stock_point_<?php echo $_smarty_tpl->tpl_vars['spreadsheetId']->value;?>
" name="is_stock_point[]" value="1">

								<span class="slider round"></span>

							</label>

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

			<button type="button" onclick="$Core.crawl.update_sheet(this, event)" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" spreadsheetId="<?php echo $_smarty_tpl->tpl_vars['spreadsheetId']->value;?>
" 

				title="Lưu sheet" class="btn btn-primary">

				<i class="fa fa-plus"></i> Lưu chọn

			</button>

		</div>

	</form>

</div><?php }
}
