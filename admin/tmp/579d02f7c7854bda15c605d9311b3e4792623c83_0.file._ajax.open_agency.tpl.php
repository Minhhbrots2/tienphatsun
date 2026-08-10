<?php
/* Smarty version 3.1.33, created on 2026-07-31 13:31:24
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/crawl/_ajax.open_agency.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c413c7171e2_81712722',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '579d02f7c7854bda15c605d9311b3e4792623c83' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/crawl/_ajax.open_agency.tpl',
      1 => 1784691583,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c413c7171e2_81712722 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-standard modal-ipad">

	<form action="" method="post" class="modal-content" id="frmIssue" encrupt="miltipart/form-data">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 

			<h3 class="modal-title"><strong><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
</strong></h3>

		</div>

		<div class="modal-body">

		<?php if (!empty($_smarty_tpl->tpl_vars['lst_block']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_block']->value, 'block_name', false, '_oK', 'k', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oK']->value => $_smarty_tpl->tpl_vars['block_name']->value) {
?>

				<?php $_smarty_tpl->_assignInScope('gId', (($_smarty_tpl->tpl_vars['uid']->value).("_")).($_smarty_tpl->tpl_vars['_oK']->value));?>

				<div class="form-group group_price_sheets">

					<label class="col-form-label required">Dự án <?php echo $_smarty_tpl->tpl_vars['block_name']->value;?>
</label>

					<input type="hidden" name="block_crawl[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][is_crawl]" value="<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['is_crawl'];?>
">

					<div class="form-row">

						<div class="col-md-4">

							<input type="text" class="form-control spreadsheetId_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onClick="this.select();" 

							name="block_crawl[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][sheetID]" block_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['sheetID'];?>
" placeholder="Spreadsheet ID" />

						</div>

						<div class="col-md-6">

							<div class="input-group">

								<input type="hidden" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" name="block_crawl[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][sheet_id]" value="<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['sheet_id'];?>
" class="sheet_id_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" />

								<input type="text" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-control sheet_name sheet_name_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onClick="this.select();" name="block_crawl[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][sheet_name]" value="<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['sheet_name'];?>
" placeholder="SHEET_1|SHEET_2" sheet_name="<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['sheet_name'];?>
" readonly>

								<input type="hidden" gId="is_stock_point_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" name="block_crawl[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][is_stock_point]" value="<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['is_stock_point'];?>
" class="is_stock_point_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" />

								<div class="input-group-btn">

									<button type="button" onClick="$Core.crawl.open_sheet(this, event)" 

										gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" class="btn btn-default" title="Chọn sheet">

										<i class="fa fa-cog"></i> Chọn sheet

									</button>

								</div>

							</div>

						</div>

						<div class="col-md-1">

							<label class="switch">

								<input type="checkbox" onchange="$Core.crawl.handle_status(this, event)" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" project_id="" block_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" value="1" class="switch_68513c113edc9554719929" name="block_crawl[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][is_crawl]" <?php if (!empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['is_crawl'])) {?>checked<?php }?>>

								<span class="slider round"></span>

							</label>

						</div>

						<div class="col-md-1">

							<button class="btn btn-outline-default btn-icon" type="button" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onclick="$Core.crawl.open_config_column(this,event)" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
">

								<i class="fa fa-cogs" aria-hidden="true"></i>

							</button>

						</div>

					</div>

					<?php if (!empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['is_stock_point'])) {?>						

						<?php $_smarty_tpl->_assignInScope('arr_sheet_name', $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['arr_sheet_name']);?>

						<?php $_smarty_tpl->_assignInScope('arr_sheet_id', $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['arr_sheet_id']);?>

						<?php $_smarty_tpl->_assignInScope('arr_stock_point', $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['arr_stock_point']);?>

						<?php $_smarty_tpl->_assignInScope('lst_building', $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value]['lstBuilding']);?>

						<?php if (!empty($_smarty_tpl->tpl_vars['arr_sheet_name']->value)) {?>

							<div class="list_sheet_config d-flex gap-2 flex-wrap">

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_sheet_name']->value, 'sheet_name', false, 'key_id');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key_id']->value => $_smarty_tpl->tpl_vars['sheet_name']->value) {
?>

									<?php if (!empty($_smarty_tpl->tpl_vars['arr_stock_point']->value[$_smarty_tpl->tpl_vars['key_id']->value])) {?>

										<div class="d-flex flex-wrap justify-content-between gap-2 p-2 border mt-2">

											<div class="w-100 bold text-info"><?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
</div>

											<div class="sheet_configs form-group form-row"  sheet_name="<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
">

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_building']->value, '_oBuilding');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBuilding']->value) {
?>

													<div class="col-12 col-md-4 mb-2">

														<div class="checkbox">

															<input type="checkbox" class="checkitem" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" name="block_crawl[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
][building_ids][]" data-name="building_ids[<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
" id="building_<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['key_id']->value;?>
" <?php ob_start();
echo $_smarty_tpl->tpl_vars['sheet_name']->value;
$_prefixVariable1 = ob_get_clean();
if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'],$_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_prefixVariable1]['building_ids'])) {?>checked<?php }?> >

															<label for="building_<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['key_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
</label>

														</div>

													</div>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											</div>

										</div>

									<?php }?>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</div>	

						<?php }?>

					<?php }?>

				</div>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php }?>

		<?php if (!empty($_smarty_tpl->tpl_vars['lst_project']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_project']->value, 'project_name', false, '_oK', 'k', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oK']->value => $_smarty_tpl->tpl_vars['project_name']->value) {
?>

				<?php $_smarty_tpl->_assignInScope('gId', (($_smarty_tpl->tpl_vars['uid']->value).("_")).($_smarty_tpl->tpl_vars['_oK']->value));?>

				<?php $_smarty_tpl->_assignInScope('arr_sheet_name', $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value]['arr_sheet_name']);?>

				<?php $_smarty_tpl->_assignInScope('arr_sheet_id', $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value]['arr_sheet_id']);?>

				<div class="form-group group_price_sheets">

					<label class="col-form-label required">Dự án <?php echo $_smarty_tpl->tpl_vars['project_name']->value;?>
</label>

					<input type="hidden" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][is_crawl]" value="<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value]['is_crawl'];?>
">

					<div class="form-row mb-2">

						<div class="col-md-4">

							<input type="text" class="form-control spreadsheetId_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onClick="this.select();" 

							name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][sheetID]" block_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value]['sheetID'];?>
" placeholder="Spreadsheet ID" />

						</div>

						<div class="col-md-6">

							<div class="input-group">

								<input type="hidden" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][sheet_id]" value="<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value]['sheet_id'];?>
" class="sheet_id_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" />

								<input type="text" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-control sheet_name sheet_name_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onClick="this.select();" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][sheet_name]" value="<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value]['sheet_name'];?>
" placeholder="SHEET_1|SHEET_2" sheet_name="<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value]['sheet_name'];?>
" readonly>

								<div class="input-group-btn">

									<button type="button" onClick="$Core.crawl.open_sheet(this, event)" 

										gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" class="btn btn-default" title="Chọn sheet">

										<i class="fa fa-cog"></i> Chọn sheet

									</button>

								</div>

							</div>

						</div>

						<div class="col-md-1">

							<label class="switch">

								<input type="checkbox" onchange="$Core.crawl.handle_status(this, event)" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
" block_id="" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" value="1" class="switch_68513c113edc9554719929" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][is_crawl]" <?php if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value]['is_crawl'])) {?>checked<?php }?>>

								<span class="slider round"></span>

							</label>

						</div>

						<div class="col-md-1 text-right">

							<button class="btn btn-outline-default btn-icon" type="button" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onclick="$Core.crawl.open_config_column(this,event)" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
">

								<i class="fa fa-cogs" aria-hidden="true"></i>

							</button>

						</div>

					</div>

					<div class="list_sheet_config">

						<?php if (!empty($_smarty_tpl->tpl_vars['arr_sheet_name']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_sheet_name']->value, 'sheet_name', false, 'key_id');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key_id']->value => $_smarty_tpl->tpl_vars['sheet_name']->value) {
?>

								<div class="d-flex flex-wrap justify-content-between gap-2 p-2 border mb-2">

									<div class="w-100 bold text-info"><?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
</div>

									<div class="form-group">

										<label class="col-form-label required">Màu đã bán</label>

										<div class="d-flex gap-1 lst_color">

											<?php if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_sold'])) {?>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_sold'], 'color');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['color']->value) {
?>

													<div class="w-35px">

														<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
][color_sold][]" value="<?php echo $_smarty_tpl->tpl_vars['color']->value;?>
" placeholder="#ff0000" maxlength="255">

													</div>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php } else { ?>

												<div class="w-35px">

													<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
][color_sold][]" value="<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_sold'];?>
" placeholder="#ff0000" maxlength="255">

												</div>

											<?php }?>



											<div class="input-group">

												<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.crawl.addColor(this,event)" _with="lowfloor" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" sheet_name="<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"  _type="color_sold"><i class="fa fa-plus" aria-hidden="true"></i></button>

												<button class="btn btn-outline-default btn-icon btn-danger btn-minus <?php if (count($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_sold']) == 1 || empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_sold'])) {?>d-none<?php }?>" type="button" onclick="$Core.crawl.removeColor(this,event)" _with="lowfloor" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"  _type="color_sold"><i class="fa fa-minus" aria-hidden="true"></i></button>

											</div>

										</div>

									</div>

									<div class="form-group">

										<?php $_smarty_tpl->_assignInScope('gid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

										<div class="checkbox-inline my-2">

											<input type="checkbox" class="checkitem stock_item" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
][is_color_dq]" value="1" id="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" <?php ob_start();
echo $_smarty_tpl->tpl_vars['_oK']->value;
$_prefixVariable2 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['sheet_name']->value;
$_prefixVariable3 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_prefixVariable2][$_prefixVariable3]['is_color_dq'])) {?>checked<?php }?>>

											<label for="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
">Màu độc quyền</label>

										</div>

										<div class="d-flex gap-1 lst_color">

											<?php if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_dq'])) {?>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_dq'], 'color');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['color']->value) {
?>

													<div class="w-35px">

														<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
][color_dq][]" value="<?php echo $_smarty_tpl->tpl_vars['color']->value;?>
" placeholder="#ff0000" maxlength="255">

													</div>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php } else { ?>

												<div class="w-35px">

													<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
][color_dq][]" value="" placeholder="#ff0000" maxlength="255">

												</div>

											<?php }?>

											<div class="input-group">

												<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.crawl.addColor(this,event)" _with="lowfloor" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" sheet_name="<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"  _type="color_dq"><i class="fa fa-plus" aria-hidden="true"></i></button>

												<button class="btn btn-outline-default btn-icon btn-danger btn-minus <?php if (count($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_dq']) == 1 || empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_dq'])) {?>d-none<?php }?>" type="button" onclick="$Core.crawl.removeColor(this,event)" _with="lowfloor" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"  _type="color_dq"><i class="fa fa-minus" aria-hidden="true"></i></button>

											</div>

										</div>

									</div>

									<div class="form-group">

										<?php $_smarty_tpl->_assignInScope('gid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

										<div class="checkbox-inline my-2">

											<input type="checkbox" class="checkitem stock_item" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
][is_color_break]" value="1" id="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" <?php ob_start();
echo $_smarty_tpl->tpl_vars['_oK']->value;
$_prefixVariable4 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['sheet_name']->value;
$_prefixVariable5 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_prefixVariable4][$_prefixVariable5]['is_color_break'])) {?>checked<?php }?> >

											<label for="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
">Màu ngắt dòng</label>

										</div>

										<div class="d-flex gap-1 lst_color">

											<?php ob_start();
echo $_smarty_tpl->tpl_vars['sheet_name']->value;
$_prefixVariable6 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_prefixVariable6]['color_break'])) {?>

												<?php ob_start();
echo $_smarty_tpl->tpl_vars['sheet_name']->value;
$_prefixVariable7 = ob_get_clean();
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_prefixVariable7]['color_break'], 'color');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['color']->value) {
?>

													<div class="w-35px">

														<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
][color_break][]" value="<?php echo $_smarty_tpl->tpl_vars['color']->value;?>
" placeholder="#ff0000" maxlength="255">

													</div>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php } else { ?>

												<div class="w-35px">

													<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
][color_break][]" value="" placeholder="#ff0000" maxlength="255">

												</div>

											<?php }?>

											<div class="input-group">

												<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.crawl.addColor(this,event)" _with="lowfloor" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" sheet_name="<?php echo $_smarty_tpl->tpl_vars['sheet_name']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"  _type="color_break"><i class="fa fa-plus" aria-hidden="true"></i></button>

												<button class="btn btn-outline-default btn-icon btn-danger btn-minus <?php if (count($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_break']) == 1 || empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['_oK']->value][$_smarty_tpl->tpl_vars['sheet_name']->value]['color_break'])) {?>d-none<?php }?>" type="button" onclick="$Core.crawl.removeColor(this,event)" _with="lowfloor" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"  _type="color_break"><i class="fa fa-minus" aria-hidden="true"></i></button>

											</div>

										</div>

									</div>

								</div>	

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php }?>

					</div>					

					<hr>

				</div>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php }?>				

		</div>

		<div class="modal-footer">

			<button type="button" class="btn btn-success" onClick="$Core.crawl.save_agency(this,event)" 

				stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
">Lưu lại</button>

		</div>

	</form>

</div><?php }
}
