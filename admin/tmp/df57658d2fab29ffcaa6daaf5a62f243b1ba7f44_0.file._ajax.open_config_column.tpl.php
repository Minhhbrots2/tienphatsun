<?php
/* Smarty version 3.1.33, created on 2026-07-31 13:31:48
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/crawl/_ajax.open_config_column.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c41540bdaa8_20643054',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'df57658d2fab29ffcaa6daaf5a62f243b1ba7f44' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/crawl/_ajax.open_config_column.tpl',
      1 => 1784691583,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c41540bdaa8_20643054 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-dialog-scrollable modal-xl">

	<form class="modal-content" method="POST">

		<div class="modal-header">

			<h3 class="modal-title"><strong>Cấu hình cột</strong></h3>

			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 

		</div>

		<div class="modal-body">

			<ul class="nav nav-tabs mb-2">

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_data']->value, 'item', false, 'key', 'i', array (
  'first' => true,
  'iteration' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>

				<li class="nav-item<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?> active<?php }?>">

					<a href="#tabcontent_<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
" title="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" 

						class="nav-link" data-toggle="tab"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</a>

				</li>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			</ul>

			<div class="tab-content">

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_data']->value, 'lstData', false, 'key', 'i', array (
  'first' => true,
  'iteration' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['lstData']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>

					<?php $_smarty_tpl->_assignInScope('number_column', $_smarty_tpl->tpl_vars['number_column_sheet']->value[$_smarty_tpl->tpl_vars['key']->value]);?>

					<?php $_smarty_tpl->_assignInScope('columns_sheet', $_smarty_tpl->tpl_vars['column_data']->value[$_smarty_tpl->tpl_vars['key']->value]);?>

					<?php $_smarty_tpl->_assignInScope('row_check', $_smarty_tpl->tpl_vars['number_check']->value[$_smarty_tpl->tpl_vars['key']->value]);?>



					<?php if (!empty($_smarty_tpl->tpl_vars['arr_stock_points']->value[$_smarty_tpl->tpl_vars['key']->value])) {?>

						<div id="tabcontent_<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
" class="tab-pane fade<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?> in active<?php }?>">

							<ul class="nav nav-tabs mb-2">

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_sheet_building']->value[$_smarty_tpl->tpl_vars['key']->value], 'building_name', false, NULL, 'i_b', array (
  'first' => true,
  'iteration' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['building_name']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['index'];
?>

								<li class="nav-item<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['first'] : null)) {?> active<?php }?>">

									<a href="#tabcontent_building_<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['iteration'] : null);?>
" title="<?php echo $_smarty_tpl->tpl_vars['building_name']->value;?>
" 

										class="nav-link" data-toggle="tab">Tòa <?php echo $_smarty_tpl->tpl_vars['building_name']->value;?>
</a>

								</li>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</ul>

							<div class="tab-content">

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_sheet_building']->value[$_smarty_tpl->tpl_vars['key']->value], 'building_name', false, 'building_id', 'i_b', array (
  'first' => true,
  'iteration' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['building_id']->value => $_smarty_tpl->tpl_vars['building_name']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['index'];
?>

									<?php $_smarty_tpl->_assignInScope('gid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

									<div id="tabcontent_building_<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['iteration'] : null);?>
" class="tab-pane fade<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_b']->value['first'] : null)) {?> in active<?php }?>">

										<div class="overflow-auto h-max-350px w-100">

											<table class="table text-nowrap table-bordered table-striped">

												<thead>

													<tr>

														<th></th>

														<th>Chọn cột tầng</th>

														<?php
$__section_i_col_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['number_column']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_col_0_start = min(0, $__section_i_col_0_loop);
$__section_i_col_0_total = min(($__section_i_col_0_loop - $__section_i_col_0_start), $__section_i_col_0_loop);
$_smarty_tpl->tpl_vars['__smarty_section_i_col'] = new Smarty_Variable(array());
if ($__section_i_col_0_total !== 0) {
for ($__section_i_col_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i_col']->value['index'] = $__section_i_col_0_start; $__section_i_col_0_iteration <= $__section_i_col_0_total; $__section_i_col_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i_col']->value['index']++){
?>

														<?php $_smarty_tpl->_assignInScope('col', (isset($_smarty_tpl->tpl_vars['__smarty_section_i_col']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i_col']->value['index'] : null));?>

														<th class="p-0 sticky bg-white" style="min-width:120px;vertical-align: middle" width="120px">

															<div class="checkbox">

																<input type="radio" class="checkitem" name="floor_index[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['col']->value;?>
" id="number_col_index_<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['col']->value;?>
" <?php ob_start();
echo $_smarty_tpl->tpl_vars['target_id']->value;
$_prefixVariable1 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['key']->value;
$_prefixVariable2 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['building_id']->value;
$_prefixVariable3 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['target_id']->value;
$_prefixVariable4 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['key']->value;
$_prefixVariable5 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['building_id']->value;
$_prefixVariable6 = ob_get_clean();
if (isset($_smarty_tpl->tpl_vars['code_floor_ind']->value[$_prefixVariable1][$_prefixVariable2][$_prefixVariable3]['floor_index']) && $_smarty_tpl->tpl_vars['code_floor_ind']->value[$_prefixVariable4][$_prefixVariable5][$_prefixVariable6]['floor_index'] == $_smarty_tpl->tpl_vars['col']->value) {?> checked<?php }?>>

																<label for="number_col_index_<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['col']->value;?>
"></label>

															</div>

														</th>

														<?php
}
}
?>

													</tr>														

													<tr>

														<th>

															Chọn dòng <br> trục căn

														</th>

														<?php
$__section_index_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['number_column']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_index_1_start = min(0, $__section_index_1_loop);
$__section_index_1_total = min(($__section_index_1_loop - $__section_index_1_start), $__section_index_1_loop);
$_smarty_tpl->tpl_vars['__smarty_section_index'] = new Smarty_Variable(array());
if ($__section_index_1_total !== 0) {
for ($__section_index_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index'] = $__section_index_1_start; $__section_index_1_iteration <= $__section_index_1_total; $__section_index_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index']++){
?>

														<th class="text-left"></th>

														<?php
}
}
?>

													</tr>

												</thead>

												<tbody>

													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstData']->value, 'rowData', false, NULL, 'i_row', array (
  'index' => true,
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['rowData']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['index']++;
?>

														<?php $_smarty_tpl->_assignInScope('row', (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['index'] : null));?>

														<tr>

															<td>

																<div class="checkbox">

																	<input type="radio" class="checkitem" name="arr_code_index[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['row']->value;?>
" id="number_check_<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
_<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['iteration'] : null);?>
" <?php ob_start();
echo $_smarty_tpl->tpl_vars['target_id']->value;
$_prefixVariable7 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['key']->value;
$_prefixVariable8 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['building_id']->value;
$_prefixVariable9 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['target_id']->value;
$_prefixVariable10 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['key']->value;
$_prefixVariable11 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['building_id']->value;
$_prefixVariable12 = ob_get_clean();
if (isset($_smarty_tpl->tpl_vars['code_floor_ind']->value[$_prefixVariable7][$_prefixVariable8][$_prefixVariable9]['code_index']) && $_smarty_tpl->tpl_vars['code_floor_ind']->value[$_prefixVariable10][$_prefixVariable11][$_prefixVariable12]['code_index'] == $_smarty_tpl->tpl_vars['row']->value) {?> checked<?php }?>>

																	<label for="number_check_<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
_<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['iteration'] : null);?>
"></label>

																</div>

															</td>

															<td>

															</td>

															<?php
$__section_index_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['number_column']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_index_2_start = min(0, $__section_index_2_loop);
$__section_index_2_total = min(($__section_index_2_loop - $__section_index_2_start), $__section_index_2_loop);
$_smarty_tpl->tpl_vars['__smarty_section_index'] = new Smarty_Variable(array());
if ($__section_index_2_total !== 0) {
for ($__section_index_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index'] = $__section_index_2_start; $__section_index_2_iteration <= $__section_index_2_total; $__section_index_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index']++){
?>

															<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['rowData']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_index']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index'] : null)];?>
</td>

															<?php
}
}
?>

														</tr>

													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

												</tbody>	

											</table>

										</div>

									</div>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</div>

						</div>

					<?php } else { ?>

						<div id="tabcontent_<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
" class="tab-pane fade<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?> in active<?php }?>">

							<div class="overflow-auto h-max-350px w-100">

								<table class="table text-nowrap table-bordered table-striped">

									<thead>

										<tr>

											<th></th>

											<?php
$__section_index_3_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['number_column']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_index_3_start = min(0, $__section_index_3_loop);
$__section_index_3_total = min(($__section_index_3_loop - $__section_index_3_start), $__section_index_3_loop);
$_smarty_tpl->tpl_vars['__smarty_section_index'] = new Smarty_Variable(array());
if ($__section_index_3_total !== 0) {
for ($__section_index_3_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index'] = $__section_index_3_start; $__section_index_3_iteration <= $__section_index_3_total; $__section_index_3_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index']++){
?>

											<?php $_smarty_tpl->_assignInScope('col', (isset($_smarty_tpl->tpl_vars['__smarty_section_index']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index'] : null));?>

											<th class="p-0 sticky bg-white" style="min-width:120px" width="120px">

												<select name="columns[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['col']->value;?>
]" class="form-control border-0 radius-0 stock_import_field">

													<option value="">Lựa chọn</option>

													<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getHtmlColumnFieldCrawl($_smarty_tpl->tpl_vars['stock_type']->value,$_smarty_tpl->tpl_vars['columns_sheet']->value[$_smarty_tpl->tpl_vars['col']->value]);?>


												</select>

											</th>

											<?php
}
}
?>

										</tr>

									</thead>

									<tbody>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstData']->value, 'rowData', false, NULL, 'i_row', array (
  'index' => true,
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['rowData']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['index']++;
?>

											<?php $_smarty_tpl->_assignInScope('gid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

											<tr>

												<td>

													<div class="checkbox">

														<input type="radio" class="checkitem" name="number_check[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]" value="<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['index'] : null);?>
" id="number_check_<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" <?php ob_start();
echo $_smarty_tpl->tpl_vars['key']->value;
$_prefixVariable13 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['key']->value;
$_prefixVariable14 = ob_get_clean();
if (isset($_smarty_tpl->tpl_vars['number_check']->value[$_prefixVariable13]) && $_smarty_tpl->tpl_vars['number_check']->value[$_prefixVariable14] == (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_row']->value['index'] : null)) {?> checked<?php }?>>

														<label for="number_check_<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
"></label>

													</div>

												</td>

												<?php
$__section_index_4_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['number_column']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_index_4_start = min(0, $__section_index_4_loop);
$__section_index_4_total = min(($__section_index_4_loop - $__section_index_4_start), $__section_index_4_loop);
$_smarty_tpl->tpl_vars['__smarty_section_index'] = new Smarty_Variable(array());
if ($__section_index_4_total !== 0) {
for ($__section_index_4_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index'] = $__section_index_4_start; $__section_index_4_iteration <= $__section_index_4_total; $__section_index_4_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index']++){
?>

												<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['rowData']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_index']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_index']->value['index'] : null)];?>
</td>

												<?php
}
}
?>

											</tr>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									</tbody>	

								</table>

							</div>

						</div>

					<?php }?>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>	

			</div>

		</div>

		<div class="modal-footer">				

			<div class="p__right d-flex justify-content-end">

				<button type="button" class="btn btn-success" agency_id="<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
" gid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['target_id']->value;?>
" 

					stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" onClick="$Core.crawl.do_config_column(this, event)" sheet_name="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">

					<span>Cập nhật</span>

				</button>

			</div>

		</div>

	</form>

</div><?php }
}
