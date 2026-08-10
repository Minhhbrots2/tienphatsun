<?php
/* Smarty version 3.1.33, created on 2026-08-05 14:28:18
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/policy/_ajax.policy.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72e6128f70f9_99841098',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8836a38f1dc783068cc3f457214c5179040e1933' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/policy/_ajax.policy.tpl',
      1 => 1784691715,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72e6128f70f9_99841098 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-ipad">

	<form class="modal-content" method="post" action="" enctype="multipart/form-data">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 

			<h3 class="modal-title"><strong><?php if ($_smarty_tpl->tpl_vars['action']->value == '_add') {?>Thêm mới<?php } else { ?>Chỉnh sửa<?php }?> CSBH <?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['block_type']->value);?>
</strong></h3>

		</div>

		<div class="modal-body">

			<div class="form-group form-row">

				<div class="col-md-9">

					<label class="col-form-label">Tên CSBH <span class="text-red">*</span></label>

					<input type="text" class="form-control required" placeholder="Nhập tên..." name="title" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];
}?>" />

				</div>

				<div class="col-md-3">

					<label class="col-form-label">Ngày áp dụng <span class="text-red">*</span></label>

					<input type="text" class="form-control datepicker required" placeholder="dd/mm/yy" name="ms_date" value="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['oneItem']->value['ms_date']);?>
" />

				</div>

			</div>

			<div class="form-group form-row">

				<div class="col-md-4">

					<label class="col-form-label">Link CSBH <span class="text-red">*</span></label>

					<input type="text" class="form-control" placeholder="Nhập link CSBH" name="link_ns" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['oneItem']->value['link_ns'];
}?>" />

				</div>

				<div class="col-md-4">

					<label class="col-form-label">Link PTG <span class="text-red">*</span></label>

					<input type="text" class="form-control" placeholder="Nhập link PTG" name="link_ms" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['oneItem']->value['link_ms'];
}?>" />

				</div>

				<div class="col-md-4">

					<label class="col-form-label">Loại quỹ <span class="text-red">*</span></label>

					<select name="applicable_fund_type" id="" class="form-control form-select required">

						<option value="0" <?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit' && $_smarty_tpl->tpl_vars['oneItem']->value['applicable_fund_type'] == '0') {?>selected<?php }?>>Sơ cấp</option>

						<option value="1" <?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit' && $_smarty_tpl->tpl_vars['oneItem']->value['applicable_fund_type'] == '1') {?>selected<?php }?>>Thứ cấp</option>

					</select>

				</div>

			</div>

			<div class="form-group form-row">

				<div class="col-md-8">

					<label class="col-form-label">ID phiếu thính giá<span class="text-red">*</span></label>

					<div class="input-group">

						<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

						<input type="text" class="form-control price_sheet_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" 

							value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['price_sheet_id'];
}?>" name="price_sheet_id" placeholder="ID bảng tính" />

						<input type="file" name="upload_file" onChange="upload_price_sheet(this, event)" 

							class="d-none select_price_sheet_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" />

						<div class="input-group-btn">

							<button type="button" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="select_price_sheet(this, event)" 

								class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Tải Excel');?>
</button>

						</div>

					</div>

				</div>

				<div class="col-md-4">

					<label class="col-form-label">Ô điền mã căn<span class="text-red">*</span></label>

					<input type="text" class="form-control" 

						value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['spreadsheet_cell_stock'];
}?>" 

						placeholder="BG!C1" name="spreadsheet_cell_stock" />

				</div>

			</div>

			<div class="form-group">

				<label class="col-form-label">Mô tả</label>

				<textarea class="form-control" placeholder="Mô tả" name="intro" rows="2" cols="255"><?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['oneItem']->value['intro'];
}?></textarea>

			</div>

			<fieldset>

				<legend>Áp dụng</legend>

				<div class="group_scopes">

				<?php if (!empty($_smarty_tpl->tpl_vars['list_scopes']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_scopes']->value, '_oScope', false, 'uid', 'k', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['uid']->value => $_smarty_tpl->tpl_vars['_oScope']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['index'];
?>

					<?php $_smarty_tpl->_assignInScope('list_blocks', $_smarty_tpl->tpl_vars['_oScope']->value['list_blocks']);?>

					<?php $_smarty_tpl->_assignInScope('list_buildings', $_smarty_tpl->tpl_vars['_oScope']->value['list_buildings']);?>

					<div class="scope_item scope_item_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">

						<div class="form-group form-row">

							<div class="col-md-6">

								<label class="col-form-label">Chọn dự án</label>

								<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onchange="load_option_block(this,event)" name="scope[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][project_id]" toId="block_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="form-control iso-select2 required">

									<option>Chọn dự án</option>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, 'project', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['project']->value) {
?>

									<option<?php if ($_smarty_tpl->tpl_vars['project']->value['project_id'] == $_smarty_tpl->tpl_vars['_oScope']->value['project_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['project']->value['title'];?>
</option>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								</select>

							</div>

							<div class="col-md-6">

								<label class="col-form-label">Chọn phân khu</label>

								<?php if ($_smarty_tpl->tpl_vars['block_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>

								<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" id="block_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="scope[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][block_id][]" multiple="multiple" class="form-control required iso-select2">

									<option>Chọn phân khu</option>

									<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>

										<option<?php if ($_smarty_tpl->tpl_vars['_oBlock']->value['selected'] == '1') {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php }?>

								</select>

								<?php } else { ?>

								<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" id="block_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onchange="load_option_building(this,event)" toId="building_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="scope[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][block_id]" class="form-control required iso-select2">

									<option>Chọn phân khu</option>

									<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>

										<option<?php if ($_smarty_tpl->tpl_vars['_oScope']->value['block_id'] == $_smarty_tpl->tpl_vars['_oBlock']->value['property_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
">(<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_code'];?>
) <?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php }?>

								</select>

								<?php }?>

							</div>

						</div>

						<?php if ($_smarty_tpl->tpl_vars['block_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

							<div id="building_group_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="form-group">

								<label class="col-form-label">Chọn tòa áp dụng</label>

								<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" multiple="multiple" id="building_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-placeholder="Chọn tòa" 

								name="scope[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][building_id][]" class="form-control required iso-select2">

									<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oBuilding', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBuilding']->value) {
?>

										<option<?php if ($_smarty_tpl->tpl_vars['_oBuilding']->value['selected']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
</option>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php }?>

								</select>

							</div>

						<?php }?>

						<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['first'] : null)) {?>

						<div class="d-flex">

							<button type="button" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="delete_scope(this,event)" 

							class="btn btn-sm btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash','Xóa');?>
</button>

						</div>

						<?php }?>

					</div>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php } else { ?>

					<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

					<div class="scope_item scope_item_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">

						<div class="form-group form-row">

							<div class="col-md-6">

								<label class="col-form-label">Chọn dự án</label> <?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>


								<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onchange="load_option_block(this,event)" name="scope[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][project_id]" toId="block_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="form-control iso-select2 required" data-error="Chưa chọn dự án">

									<option value="0">Chọn dự án</option>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, 'project', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['project']->value) {
?>

									<option <?php if ($_smarty_tpl->tpl_vars['project_id']->value == $_smarty_tpl->tpl_vars['project']->value['project_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['project']->value['title'];?>
</option>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								</select>

							</div>

							<div class="col-md-6">

								<label class="col-form-label">Chọn phân khu</label>

								<?php if ($_smarty_tpl->tpl_vars['block_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>

								<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" id="block_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" toId="building_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="scope[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][block_id][]" multiple="multiple" class="form-control required iso-select2" data-error="Chưa chọn phân khu">

									<option value="0">Chọn phân khu</option>

									<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>

										<option<?php if ($_smarty_tpl->tpl_vars['block_id']->value == $_smarty_tpl->tpl_vars['_oBlock']->value['property_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php }?>

								</select>

								<?php } else { ?>

								<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" id="block_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onchange="load_option_building(this,event)" toId="building_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="scope[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][block_id]" class="form-control required iso-select2" data-error="Chưa chọn phân khu">

									<option value="0">Chọn phân khu</option>

									<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>

										<option<?php if ($_smarty_tpl->tpl_vars['block_id']->value == $_smarty_tpl->tpl_vars['_oBlock']->value['property_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php }?>

								</select>

								<?php }?>

							</div>

						</div>

						<?php if ($_smarty_tpl->tpl_vars['block_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

						<div id="building_group_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="form-group <?php if ($_smarty_tpl->tpl_vars['action']->value == '_add' && empty($_smarty_tpl->tpl_vars['building_id']->value)) {?>d-none<?php }?>">

							<label class="col-form-label">Chọn tòa áp dụng</label>

							<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" multiple="multiple" id="building_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-placeholder="Chọn tòa nhà" 

							name="scope[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][building_id][]" class="form-control iso-select2" data-error="Chưa chọn tòa nhà">

								<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oBuilding', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBuilding']->value) {
?>

									<option <?php if ($_smarty_tpl->tpl_vars['building_id']->value == $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
" ><?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
</option>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php }?>

							</select>

						</div>

						<?php }?>

					</div>

				<?php }?>

				</div>

			</fieldset>

			<button type="button" onClick="add_scope(this, event)" block_type="<?php echo $_smarty_tpl->tpl_vars['block_type']->value;?>
" class="btn btn-default text-danger">Thêm áp dụng</button>

		</div>

		<div class="modal-footer">

			<button type="button" class="btn btn-success pull-right" block_type="<?php echo $_smarty_tpl->tpl_vars['block_type']->value;?>
" onClick="pop_save_policy(this, event)" 

				policy_id="<?php echo $_smarty_tpl->tpl_vars['policy_id']->value;?>
">Cập nhật</button>

			<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">

				<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Close');?>


			</button>

		</div>

	</form>

</div>

<style type="text/css">

	.datepicker{ max-width:100%}

	.form-group{ margin-bottom:10px !important;}

	.scope_item{ padding:10px; margin-bottom:5px; border:1px solid #DDD; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; -khtml-border-radius:3px; }

</style><?php }
}
