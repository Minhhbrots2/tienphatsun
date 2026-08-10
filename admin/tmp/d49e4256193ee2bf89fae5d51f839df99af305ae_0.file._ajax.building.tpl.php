<?php
/* Smarty version 3.1.33, created on 2026-08-06 17:45:53
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.building.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7465e124cab3_66429073',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd49e4256193ee2bf89fae5d51f839df99af305ae' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.building.tpl',
      1 => 1784691720,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7465e124cab3_66429073 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?> modal-lg<?php }?>">

	<div class="modal-content">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 

			<h3 class="modal-title"><strong><?php echo $_smarty_tpl->tpl_vars['titlePgae']->value;?>
</strong></h3>

		</div>

		<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

		<form method="POST" class="frmIssue d-none" enctype="multipart/form-data">

			<input type="file" onchange="$Core.project.upload_image(this, event)" name="image" 

			maxlength="255" id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" />

		</form> 

		<form method="post" action="" enctype="multipart/form-data">

			<div class="modal-body">

				<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>

					<div class="form-group form-row">

						<div class="col-md-3">

							<label class="col-form-label">Mã dãy<span class="text-red">*</span></label>

							<input type="text" class="form-control required" placeholder="Mã dãy nhà" name="property_code" value="<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['property_code'];?>
" />

						</div>

						<div class="col-md-6">

							<label class="col-form-label">Tên dãy<span class="text-red">*</span></label>

							<input type="text" class="form-control required" placeholder="Nhập dãy nhà" name="title" value="<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
" />

						</div>

						<div class="col-md-3">

							<label class="col-form-label">Phân khu<span class="text-red">*</span></label>

							<select class="form-control required  iso-select2" name="ns_block_id">

								<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock');
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

						</div>

					</div>

					<div class="form-group">

						<label class="col-form-label">Giới thiệu</label>

						<textarea class="form-control" placeholder="Giới thiệu" name="intro"></textarea>

					</div>

				<?php } else { ?>

				<div class="form-group form-row">

					<div class="col-md-2">

						<label class="col-form-label">Mã tòa nhà<span class="text-red">*</span></label>

						<input type="text" class="form-control required" placeholder="Mã tòa nhà" name="property_code" value="<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['property_code'];?>
" />

					</div>

					<div class="col-md-2">

						<label class="col-form-label">Tên tòa nhà<span class="text-red">*</span></label>

						<input type="text" class="form-control required" placeholder="Nhập tòa nhà" name="title" value="<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
" />

					</div>

					<div class="col-md-2">

						<label class="col-form-label">Tên rút gọn<span class="text-red">*</span></label>

						<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title_vn" value="<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title_vn'];?>
">

					</div>

					<div class="col-md-3">

						<label class="col-form-label">Mẫu mã căn hộ<span class="text-red">*</span></label>

						<textarea id="inputor" style="height:34px" onchange="$(this).val($(this).val().replace('%',''))" title="Gõ % để lựa chọn" data-toggle="tooltip" class="form-control disabled-resize-y required" placeholder="Mẫu mã căn hộ" name="stock_template"><?php if ($_smarty_tpl->tpl_vars['building_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['stock_template'];
}?></textarea>

					</div>

					<div class="col-md-1">

						<label class="col-form-label">Tầng/tòa<span class="text-red">*</span></label>

						<input type="number" class="form-control required" placeholder="Số căn hộ/tầng" name="number_floor" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['number_floor'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['number_floor'];
}?>" />

					</div>

					<div class="col-md-2">

						<label class="col-form-label">Căn hộ/tầng<span class="text-red">*</span></label>

						<input type="number" class="form-control required" placeholder="Số căn hộ/tầng" name="number_house" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['number_house'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['number_house'];
}?>" />

					</div>

				</div>

				<div class="alert p-3 alert-info">

					Ghi chú<br />

					<ul>

						<li>Mẫu mã căn hộ: Mẫu được định nghĩa để tự động sinh mã căn hộ</li>

						<li>Số căn hộ/tầng: Số căn hộ mở bán trên mỗi tầng</li>

						<li>Tạo bảng hàng: Hệ thống sẽ dựa vào số tầng, mẫu thuộc tính căn hộ để tạo bảng hàng ban đầu.</li>

					</ul>

				</div>

				<div class="form-group form-row">

					<div class="col-xs-12 col-md-3">

						<label class="col-form-label">Số thang máy</label>

						<input type="text" class="form-control" placeholder="Số thang máy" name="number_of_elevator" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['number_of_elevator'];?>
">

					</div>

					<div class="col-xs-12 col-md-3">

						<label class="col-form-label">Số tầng hầm</label>

						<input type="text" class="form-control" placeholder="Số tầng hầm" name="number_of_besement" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['number_of_besement'];?>
">

					</div>

					<div class="col-xs-12 col-md-3">

						<label class="col-form-label">Phong cách xây dựng</label>

						<input type="text" class="form-control" placeholder="Nhập tiêu đề" name="construction_style" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['construction_style'];?>
">

					</div>

					<div class="col-xs-12 col-md-3">

						<label class="col-form-label">Thời gian bàn giao</label>

						<input type="text" class="form-control" placeholder="Tháng/năm" name="handover_time" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['handover_time'];?>
">

					</div>

				</div>

				<div class="form-group form-row">

					<div class="col-xs-12 col-md-2">

						<label class="col-form-label">Khoảng giá</label>

						<input type="text" class="form-control" placeholder="Giá bán" name="price_range" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['price_range'];?>
">

					</div>

					<div class="col-xs-12 col-md-2">

						<label class="col-form-label">Mở bán</label>

						<div class="d-flex align-items-center gap-2">

							<label class="switch">

								<input type="checkbox" name="on_sale"<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['on_sale']) && $_smarty_tpl->tpl_vars['more_information']->value['on_sale'] == '1') {?> checked<?php }?> value="1">

								<span class="slider round"></span>

							</label>

							<span class="text-muted">Mở bán</span>

						</div>

					</div>

					<div class="col-xs-12 col-md-2">

						<label class="col-form-label">Symbol tòa</label>

						<div class="d-flex align-items-center gap-2">

							<label class="switch">

								<input type="checkbox" name="is_symbol"<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['is_symbol']) && $_smarty_tpl->tpl_vars['more_information']->value['is_symbol'] == '1') {?> checked<?php }?> value="1">

								<span class="slider round"></span>

							</label>

							<span class="text-muted">Có Symbol</span>

						</div>

					</div>

					<div class="col-xs-12 col-md-2">

						<label class="col-form-label">Symbol tầng</label>

						<div class="d-flex align-items-center gap-2">

							<label class="switch">

								<input type="checkbox" name="is_symbol_floor"<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['is_symbol_floor']) && $_smarty_tpl->tpl_vars['more_information']->value['is_symbol_floor'] == '1') {?> checked<?php }?> value="1">

								<span class="slider round"></span>

							</label>

							<span class="text-muted">Có Symbol</span>

						</div>

					</div>

					<div class="col-xs-12 col-md-4">

						<label class="col-form-label">Tầng thứ cấp</label>

						<select name="floor_hierarchy[]" id="" class="iso-select2" multiple>

							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getFloor($_smarty_tpl->tpl_vars['more_information']->value['number_floor'],$_smarty_tpl->tpl_vars['more_information']->value['floor_hierarchy']);?>


						</select>

					</div>

				</div>

				<div class="form-group form-row">

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['info_more']->value, '_oItem', false, 'key', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>

						<div class="col-md-3">

							<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</label>

							<input type="hidden" name="info_more[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][title]" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
" >

							<input type="hidden" name="info_more[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][class]" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['class'];?>
" >

							<input type="hidden" name="info_more[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][placeholder]" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['placeholder'];?>
" >

							<div class="d-flex gap-2 align-items-center">

								<input class="form-control <?php echo $_smarty_tpl->tpl_vars['_oItem']->value['class'];?>
" type="text" name="info_more[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][value]" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['value'];?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['placeholder'];?>
">

							</div>

						</div>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				</div>

				<div class="form-group form-row">

					<div class="col-md-6">

						<label class="col-form-label">Tiêu đề bảng hàng</label>

						<input type="text" class="form-control" placeholder="Tiêu đề bảng hàng" 

						name="title_ts" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['title_ts'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['title_ts'];
}?>" />

					</div>

					<div class="col-md-6">

						<?php $_smarty_tpl->_assignInScope('for_id', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

						<label class="col-form-label">Danh sách tầng <a href="javascript:void(0);" 

						onClick="gen_floor(this, event)" data-toggle="tooltip" title="Tạo nhanh danh sách tầng" 

						toId="<?php echo $_smarty_tpl->tpl_vars['for_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle');?>
</a> (cách nhau bởi dấu [,])</label>

						<input autocomplete="off" class="<?php echo $_smarty_tpl->tpl_vars['for_id']->value;?>
 form-control required" placeholder="Số tầng" name="floor" 

						value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['floor'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['floor'];
}?>" />

					</div>

				</div>

				<div class="form-group form-row">

					<div class="col-md-6">

						<label class="col-form-label">Hình ảnh</label>

						<div class="input-group">

							<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh đại diện" id="isoman_url_image" value="<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['image'];?>
">

							<div class="input-group-btn">

								<button class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['image'];?>
" isoman_name="image" style="padding:9px 10px"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button>

							</div>	

						</div>

					</div>

					<div class="col-md-6">

						<label class="col-form-label mr-3">Nhân viên CS:</label>

						<select placeholder="Gõ tên [OR] email để tìm kiếm" name="stock_support_id" 

						class="form-control iso-selectizeLiveSearch" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=member&act=get_member_search">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['stock_support_id'])) {?>

							<option value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['stock_support_id'];?>
" selected="selected"><?php echo $_smarty_tpl->tpl_vars['clsMember']->value->getFullName($_smarty_tpl->tpl_vars['more_information']->value['stock_support_id']);?>
</option>

							<?php }?>

						</select>

					</div>

				</div>

				<div class="form-group form-row">

					<div class="col-md-6">

						<label class="col-form-label">Layout Map</label>

						<div class="input-group">

							<input type="text" class="form-control" name="layout_map" placeholder="Chọn hình ảnh" 

								id="isoman_url_layout_map" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['layout_map'];?>
">

							<div class="input-group-btn">

								<button class="btn btn-default ajOpenDialog" isoman_for_id="layout_map" isoman_val="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['layout_map'];?>
" isoman_name="layout_map" style="padding:9px 10px"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button>

							</div>	

						</div>

					</div>

					<div class="col-md-6">

						<label class="col-form-label">Layout Map độc quyền</label>

						<div class="input-group">

							<input type="text" class="form-control" name="layout_map_FH" placeholder="Chọn hình ảnh" 

								id="isoman_url_layout_map_FH" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['layout_map_FH'];?>
">

							<div class="input-group-btn">

								<button class="btn btn-default ajOpenDialog" isoman_for_id="layout_map_FH" isoman_val="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['layout_map_FH'];?>
" isoman_name="layout_map_FH" style="padding:9px 10px"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button>

							</div>	

						</div>

					</div>					

					
				</div>

				<div class="form-row form-group">

					<div class="col-md-3">

						<div class="d-flex align-items-center bg-gray p-3 radius-4">

							<label class="col-form-label mr-3">Không cho phép xóa:</label>

							<label class="switch">

								<input type="checkbox" name="is_locked" value="1"<?php if ($_smarty_tpl->tpl_vars['oneBuilding']->value['is_locked'] == '1') {?> checked<?php }?>>

								<span class="slider round"></span>

							</label>

						</div>

					</div>

					<div class="col-md-3">

						<div class="d-flex align-items-center bg-gray p-3 radius-4">

							<label class="col-form-label mr-3">Ẩn bảng hàng:</label>

							<label class="switch">

								<input type="checkbox" name="is_trash" value="1"<?php if ($_smarty_tpl->tpl_vars['oneBuilding']->value['is_trash'] == '1') {?> checked<?php }?>>

								<span class="slider round"></span>

							</label>

						</div>

					</div>

					<div class="col-md-3">

						<div class="d-flex align-items-center bg-gray p-3 radius-4">

							<label class="col-form-label mr-3">Ẩn header lặp:</label>

							<label class="switch">

								<input type="checkbox" name="hide_row_floor_special" value="1"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['hide_row_floor_special'] == '1') {?> checked<?php }?>>

								<span class="slider round"></span>

							</label>

						</div>

					</div>

					<div class="col-md-3">

						<a class="btn btn-block btn-lg btn-default" onClick="$Core.project.open_setup_floor(this, event)" 

						building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('cog','Cài đặt tầng đặc biệt');?>
</a>

					</div>

				</div>

				<ul class="nav nav-tabs nav-tabs-bordered" id="myTab" role="tablist">

					<li class="nav-item active">

						<a href="#content" class="nav-link" data-toggle="tab" role="tab">Giới thiệu</a>

					</li>

					<li class="nav-item">

						<a href="#profile" class="nav-link" data-toggle="tab" role="tab">Mẫu thuộc tính căn hộ</a>

					</li>

					<li class="nav-item">

						<a href="#layout" class="nav-link" data-toggle="tab" role="tab">Layout</a>

					</li>

					<li class="nav-item">

						<a href="#floor_range" class="nav-link" data-toggle="tab" role="tab">Khoảng tầng</a>

					</li>

					<li class="nav-item d-none">

						<a href="#layout_map_floor" class="nav-link" data-toggle="tab" role="tab">Layout map tầng</a>

					</li>

					<li class="nav-item d-none">

						<a href="#csbh" class="nav-link" data-toggle="tab" role="tab">CSBH</a>

					</li>

				</ul>

				<div class="tab-content">

					<div class="tab-pane fade py-3 active in" id="content" role="tabpanel" aria-labelledby="content-tab">

						<div class="widget-block mb-5">

							<div class="widget-header">

								<div class="d-flex align-items-center justify-content-between">

									<strong class="mb-0">Tổng quan</strong>

									<a onClick="add_property(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" _openFrom="_building" _holderG="_attrs">+ Thêm</a>

								</div>

							</div>

							<div class="widget-content p-0">

								<table width="100%" class="table table-vertical mb-0 table-stripped">

									<thead><tr>

										<th width="5%">No.</th>

										<th width="35%">Tên thuộc tính</th>

										<th width="55%">Giá trị</th>

										<th width="5%"></th>

									</tr></thead>

									<tbody class="tbody_attrs no_group">

										<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['attrs'])) {?>

											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['attrs'], '_Item', false, 'uid', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['uid']->value => $_smarty_tpl->tpl_vars['_Item']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>

											<tr id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="tr_attrs">

												<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

												<td class="text-center">

													<input class="form-control title_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][title]" placeholder="Nhập tiêu đề" value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['title'];?>
" type="text" />

												</td>

												<td class="text-center">

													<input class="form-control content_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][content]" placeholder="Nhập giá trị" value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['content'];?>
" type="text" />

												</td>

												<td class="text-center">

													<a class="btn btn-icon btn-default" title="Xóa" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="delete_property(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</a>

												</td>

											</tr>

											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

										<?php } else { ?>

											<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

											<tr id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="tr_attrs">

												<td class="text-center">1</td>

												<td class="text-center">

													<input class="form-control title_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][title]" placeholder="Nhập tiêu đề" type="text" /></td>

												<td class="text-center">

													<input class="form-control content_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][content]" placeholder="Nhập giá trị" type="text" />

												</td>

												<td class="text-center">

													<a class="btn btn-icon btn-default" title="Xóa" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="delete_property(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</a>

												</td>

											</tr>

										<?php }?>

									</tbody>

								</table>

							</div>

						</div>

						<div class="widget-block">

							<div class="widget-header">

								<strong class="mb-0">Giới thiệu</strong>

							</div>

							<div class="widget-content p-0">

								<textarea id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" data-name="intro" class="isoTextArea" rows="15" cols="255" style="width:100%"><?php if ($_smarty_tpl->tpl_vars['building_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneBuilding']->value['intro'];
}?></textarea>

							</div>

						</div>

					</div>

					<div class="tab-pane py-3 fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

						<div class="d-flex mb-3 align-items-center">							

							<button type="button" class="btn btn-default mr-2" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="load_template_building(this, event)" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle','Tạo căn hộ');?>
</button>

							<button type="button" class="btn btn-default mr-2" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="add_template_line(this, event)" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle','Thêm dòng');?>
</button>

							<button type="button" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" class="btn btn-default mr-2" onClick="$Core.project.open_copyfrom_building(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('clipboard','Copy từ tòa khác');?>
</button>

							<button type="button" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="btn btn-default mr-2 d-none" onClick="open_copypaste_excel(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('clipboard','Copy/Paste');?>
</button>

							<?php if ($_smarty_tpl->tpl_vars['building_id']->value > '0') {?><button type="button" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" class="btn btn-default mr-2" onClick="start_create_stock(this, event)" holderG="create" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus','Tạo bảng hàng');?>
</button>

							<button type="button" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" class="btn btn-default" holderG="update" onClick="start_create_stock(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('clipboard','Cập nhật bảng hàng');?>
</button>

							<?php }?>

						</div>

						<div class="holder_template_table_floor mt-3">	

							<div class="d-flex d-flex align-items-start">

								<ul class="nav nav-tabs nav-tabs-bordered list_head_tab flex-wrap">

									<li class="nav-item active nav-item_tab_floor" key="general"><a class="nav-link" data-toggle="tab" href="#tab_general">Tầng chung</a></li>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['floor_specical'], 'floor_specical', false, 'key', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['floor_specical']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>

										<li class="nav-item nav-item_tab_floor" key="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">

											<a class="nav-link" data-toggle="tab" href="#tab_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><span class="txt_nav">Tầng <?php echo $_smarty_tpl->tpl_vars['floor_specical']->value;?>
</span> 

												<button class="btn btn-sm btn-default ml-2 border-0 px-1" type="button" title="Xóa tầng" onclick="$Core.project.deleteFloorSpecical(this,event)" toId="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>

												<button class="btn btn-sm btn-default ml-1 border-0 px-1" type="button" title="Sửa tầng" onclick="$Core.project.gen_floor(this,event)" toId="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-type="_EDIT"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

											</a>											

										</li>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								</ul>

								<button class="btn btn-icon ml-2 border" type="button" onClick="$Core.project.gen_floor(this,event)" data-type="_ADD" data-toggle="tooltip" title="" toid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-original-title="Thêm mới tầng đặc biệt" building_id='<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
' project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" style="background: #FFF"><i class="fa fa-plus" aria-hidden="true"></i></button>

								<div class="lst_input_specical">

								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['floor_specical'])) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['floor_specical'], 'floor_specical', false, 'key', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['floor_specical']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>

										<input type="hidden" autocomplete="off" class="form-control required mr-2 floor_specical <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" placeholder="Số tầng" name="floor_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['floor_specical']->value;?>
">

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php }?>

								</div>

								
							</div>

							<div class="tab-content list_content_tab">

								<div id="tab_general" class="tab-pane fade in active overflow-x-auto holder_template_building">

									<table class="table no-maxwidth" cellpadding="0" cellspacing="0" style="width: calc(100% + 200px)">

										<thead><tr>

											<th width="7%">Căn số</th>

											<th width="7%">Symbol</th>

											<th width="10%">Số PN</th>

											<th width="10%">Hướng BC</th>

											<th width="10%">DT_TT(m2)</th>

											<th width="10%">DT_Tim(m2)</th>

											<th width="10%">View</th>

											<th width="15%">Layout</th>

											<th width="15%">Layout chi tiết</th>

											<th width="15%">Video</th>

											<th width="45px"></td>

										</tr></thead>

										<tbody class="holder_template_general_building">

											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['template'])) {?>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['template'], '_oItem', false, '_code');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_code']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

													<tr class="tr_template_<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
">

														<td class="text-left">

															<input type="text" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][code]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['code'];?>
" />

														</td>

														<td class="text-left">

															<input type="text" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][symbol]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['symbol'];?>
" />

														</td>

														<td class="text-left">

															<select class="form-control iso-select2" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][bedroom_id]">

																<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_BEDROOM',$_smarty_tpl->tpl_vars['_oItem']->value['bedroom_id'],$_smarty_tpl->tpl_vars['arrBedRooms']->value);?>


															</select>

														</td>

														<td class="text-left">

															<select class="form-control iso-select2" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][home_direction_id]">

																<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_DIRECTION',$_smarty_tpl->tpl_vars['_oItem']->value['home_direction_id'],$_smarty_tpl->tpl_vars['arrDirections']->value);?>


															</select>

														</td>

														<td class="text-left">

															<div class="input-group-suffix">

																<input type="text" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][DT_TT]" class="form-control w-100px" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['DT_TT'];?>
" />

																<span class="suffix">m2</span>

															</div>

														</td>

														<td class="text-left">

															<div class="input-group-suffix">

																<input type="text" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][DT_Tim]" class="form-control w-100px" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['DT_Tim'];?>
" />

																<span class="suffix">m2</span>

															</div>

														</td>

														<td class="text-left">

															<select class="form-control iso-select2" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][view_id]">

																<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_VIEW',$_smarty_tpl->tpl_vars['_oItem']->value['view_id'],$_smarty_tpl->tpl_vars['arrViews']->value);?>


															</select>

														</td>

														<td width="15%">

															<div class="input-group">

																<input type="text" id="layout_<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][layout]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['layout'];?>
" placeholder="URL Layout" />

																<div class="input-group-btn"><button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','&nbsp;');?>
</button></div>

															</div>

														</td>

														<td width="15%">

															<div class="input-group">

																<input type="text" id="layout_ns_<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][layout_ns]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['layout_ns'];?>
" placeholder="URL Layout" />

																<div class="input-group-btn"><button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','&nbsp;');?>
</button></div>

															</div>

														</td>

														<td width="15%">

															<input type="text" name="template[<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][video]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['video'];?>
" maxlength="255" placeholder="URL Youtube" />

														</td>

														<td class="text-center">

															<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_template_line(this, event)"><i class="fa fa-trash"></i></button>

														</td>

													</tr>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php }?>

										</tbody>

									</table>

								</div>

								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['template_specical'])) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['template_specical'], 'template_specical', false, 'key', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['template_specical']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>

										<div id="tab_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" class="tab-pane fade overflow-x-auto">

											<table class="table no-maxwidth" cellpadding="0" cellspacing="0" style="width: calc(100% + 200px)">

												<thead><tr>

													<th width="7%">Căn số</th>

													<th width="7%">Symbol</th>

													<th width="10%">Số PN</th>

													<th width="10%">Hướng BC</th>

													<th width="10%">DT_TT(m2)</th>

													<th width="10%">DT_Tim(m2)</th>

													<th width="10%">View</th>

													<th width="15%">Layout</th>

													<th width="15%">Layout chi tiết</th>

													<th width="15%">Video</th>

													<th width="45px"></td>

												</tr></thead>

												<tbody>

													<?php if (!empty($_smarty_tpl->tpl_vars['template_specical']->value)) {?>

														<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['template_specical']->value, '_oItem', false, '_code');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_code']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

															<tr class="tr_template_<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
">

																<td class="text-left">

																	<input type="text" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][code]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['code'];?>
" />

																</td>

																<td class="text-left">

																	<input type="text" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][symbol]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['symbol'];?>
" />

																</td>

																<td class="text-left">

																	<select class="form-control iso-select2" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][bedroom_id]">

																		<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_BEDROOM',$_smarty_tpl->tpl_vars['_oItem']->value['bedroom_id'],$_smarty_tpl->tpl_vars['arrBedRooms']->value);?>


																	</select>

																</td>

																<td class="text-left">

																	<select class="form-control iso-select2" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][home_direction_id]">

																		<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_DIRECTION',$_smarty_tpl->tpl_vars['_oItem']->value['home_direction_id'],$_smarty_tpl->tpl_vars['arrDirections']->value);?>


																	</select>

																</td>

																<td class="text-left">

																	<div class="input-group-suffix">

																		<input type="text" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][DT_TT]" class="form-control w-100px" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['DT_TT'];?>
" />

																		<span class="suffix">m2</span>

																	</div>

																</td>

																<td class="text-left">

																	<div class="input-group-suffix">

																		<input type="text" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][DT_Tim]" class="form-control w-100px" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['DT_Tim'];?>
" />

																		<span class="suffix">m2</span>

																	</div>

																</td>

																<td class="text-left">

																	<select class="form-control iso-select2" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][view_id]">

																		<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_VIEW',$_smarty_tpl->tpl_vars['_oItem']->value['view_id'],$_smarty_tpl->tpl_vars['arrViews']->value);?>


																	</select>

																</td>

																<td width="15%">

																	<div class="input-group">

																		<input type="text" id="layout_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][layout]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['layout'];?>
" placeholder="URL Layout" />

																		<div class="input-group-btn"><button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','&nbsp;');?>
</button></div>

																	</div>

																</td>

																<td width="15%">

																	<div class="input-group">

																		<input type="text" id="layout_ns_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][layout_ns]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['layout_ns'];?>
" placeholder="URL Layout" />

																		<div class="input-group-btn"><button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','&nbsp;');?>
</button></div>

																	</div>

																</td>

																<td width="15%">

																	<input type="text" name="template_specical[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
][video]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['video'];?>
" maxlength="255" placeholder="URL Youtube" />

																</td>

																<td class="text-center">

																	<button onClick="$Core.project.delete_template_line(this, event)" type="button" class="btn btn-icon btn-default"><i class="fa fa-trash"></i></button>

																</td>

															</tr>

														<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

													<?php }?>

												</tbody>

											</table>

										</div>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php }?>

							</div>

						</div>

					</div>

					<div class="tab-pane fade py-3" id="layout" role="tabpanel" aria-labelledby="home-tab">

						<table class="table table-bordered">

							<thead><tr>

								<th class="align-center text-left">Tiêu đề</th>

								<th class="align-center text-left">Giá trị</th>

								<th class="align-center text-left" width="60px"></th>

							</tr></thead>

							<tr class="tr_layout">

								<td class="text-left">

									<input type="text" readonly value="Layout điển hình" class="form-control" />

								</td>

								<td class="text-left">

									<div class="input-group">

										<input type="text" class="form-control" placeholder="Layout tòa nhà" name="layout_ns" id="layout_building_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['layout_ns'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['layout_ns'];
}?>" />

										<div class="input-group-btn">

											<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="layout_building_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"><i class="fa fa-upload"></i></button>

										</div>

									</div>

								</td>

								<td class="text-center">

									<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.add_layout(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus');?>
</button>

								</td>

							</tr>

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['layout_ms'])) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['layout_ms'], '_oLayout', false, '_oKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oKey']->value => $_smarty_tpl->tpl_vars['_oLayout']->value) {
?>

								<tr class="tr_layout">

									<td class="text-left">

										<input type="text" name="layout_ms[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][title]" placeholder="Layout tầng..." class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oLayout']->value['title'];?>
" />

									</td>

									<td class="text-left">

										<div class="input-group">

											<input type="text" class="form-control" placeholder="Layout tòa nhà" name="layout_ms[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][image]" id="layout_building_<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['_oLayout']->value['image'];?>
" />

											<div class="input-group-btn">

												<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="layout_building_<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"><i class="fa fa-upload"></i></button>

											</div>

										</div>

									</td>

									<td class="text-center">

										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.delete_layout(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

									</td>

								</tr>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php }?>

						</table>

					</div>

					<div class="tab-pane fade py-3" id="floor_range" role="tabpanel" 

						aria-labelledby="home-tab">

						<div class="form-row mb-2">

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['floor_level_configs']->value, '_oI', false, '_oK');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oK']->value => $_smarty_tpl->tpl_vars['_oI']->value) {
?>

							<div class="xol-xs-12 col-md-4">

								<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
</label>

								<input type="hidden" name="floor_level_configs[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][title]" value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
" />

								<div class="p-3 border radius-3">

									<div class="form-group form-row mb-2">

										<label class="col-form-label col-xs-4 text-right">Từ tầng</label>

										<div class="col-xs-12 col-md-8">

											<input type="number" class="form-control" name="floor_level_configs[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][from]" value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['from'];?>
" placeholder="Số tầng" />

										</div>

									</div>

									<div class="form-group form-row mb-0">

										<label class="col-form-label col-xs-4 text-right">Tới tầng</label>

										<div class="col-xs-12 col-md-8">

											<input type="number" name="floor_level_configs[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][to]" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['to'];?>
" placeholder="Số tầng" />

										</div>

									</div>

								</div>

							</div>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</div>

						<table class="table table-striped" cellpadding="0" cellspacing="0">

							<thead><tr>

								<th class="align-center text-left" width="60px">STT</th>

								<th class="align-center text-left" width="20%">Loại</th>

								<th class="align-center text-left" width="20%">Loại tầng</th>

								<th class="align-center text-left">Khoảng tầng</th>

								<th class="align-center text-left" width="60px"></th>

							</tr></thead>

							<tbody class="holder_floor_range_configs">

								<?php if (!empty($_smarty_tpl->tpl_vars['floor_range_configs']->value)) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['floor_range_configs']->value, '_oI', false, 'uid');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['uid']->value => $_smarty_tpl->tpl_vars['_oI']->value) {
?>

									<tr id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="tr_floor_range_config">

										<td class="text-center mySortableHandler">

											<i class="fa fa-bars p-3">

										</td>

										<td class="text-left">

											<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.project.handle_floor_type_changed(this, event)" 

												name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][floor_type]" class="form-control iso-select2">

												<option<?php if ($_smarty_tpl->tpl_vars['_oI']->value['floor_type'] == 'consecutive') {?> selected<?php }?> value="consecutive">Liên tục(Consecutive)</option>

												<option<?php if ($_smarty_tpl->tpl_vars['_oI']->value['floor_type'] == 'non_consecutive') {?> selected<?php }?> value="non_consecutive">Không liên tục(Non-consecutive)</option>

											</select>

										</td>

										<td class="text-left">

											<select name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][level]" class="form-control iso-select2">

												<option<?php if ($_smarty_tpl->tpl_vars['_oI']->value['level'] == 'low_floor') {?> selected<?php }?> value="low_floor">Tầng thấp</option>

												<option<?php if ($_smarty_tpl->tpl_vars['_oI']->value['level'] == 'mid_floor') {?> selected<?php }?> value="mid_floor">Tầng trung</option>

												<option<?php if ($_smarty_tpl->tpl_vars['_oI']->value['level'] == 'high_floor') {?> selected<?php }?> value="high_floor">Tầng cao</option>

											</select>

										</td>

										<td class="text-left">

											<div class="floor_range_consecutive<?php if ($_smarty_tpl->tpl_vars['_oI']->value['floor_type'] == 'non_consecutive') {?> d-none<?php }?>">

												<div class="input-group d-flex align-items-center">

													<input type="number" placeholder="Từ tầng" name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][from]" 

														class="form-control numberonly" value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['from'];?>
" onClick="this.select()" />

													<input type="number" placeholder="Tới tầng" name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][to]" 

														class="form-control numberonly" value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['to'];?>
" onClick="this.select()" />

												</div>

											</div>

											<input type="text" placeholder="Nhập các tầng cách nhau bằng dấu (,)" name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][floor]" value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['floor'];?>
" class="form-control floor_range_non_consecutive<?php if ($_smarty_tpl->tpl_vars['_oI']->value['floor_type'] == 'consecutive') {?> d-none<?php }?>" />

										</td>

										<td class="text-center">

											<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_floor_range_config(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

										</td>

									</tr>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php } else { ?>

									<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

									<tr id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="tr_floor_range_config">

										<td class="text-center mySortableHandler">

											<i class="fa fa-bars p-3">

										</td>

										<td class="text-left">

											<select uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.project.handle_floor_type_changed(this, event)" 

												name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][floor_type]" class="form-control iso-select2">

												<option value="consecutive">Liên tục(Consecutive)</option>

												<option value="non_consecutive">Không liên tục(Non-consecutive)</option>

											</select>

										</td>

										<td class="text-left">

											<select name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][level]" class="form-control iso-select2">

												<option value="low">Tầng thấp</option>

												<option value="middle">Tầng trung</option>

												<option value="high">Tầng cao</option>

											</select>

										</td>

										<td class="text-left">

											<div class="floor_range_consecutive">

												<div class="input-group d-flex align-items-center">

													<input type="number" placeholder="Từ tầng" name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][from]" 

														class="form-control numberonly" onClick="this.select()" />

													<input type="number" placeholder="Tới tầng" name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][to]" 

														class="form-control numberonly" onClick="this.select()" />

												</div>

											</div>

											<input type="text" placeholder="Nhập các tầng cách nhau bằng dấu (,)" name="floor_range_configs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][floor]" class="form-control floor_range_non_consecutive d-none" />

										</td>

										<td class="text-center">

											<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_floor_range_config(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

										</td>

									</tr>

								<?php }?>

							</tbody>

							<tfoot><tr>

								<td colspan="4">

									<button type="button" onClick="$Core.project.add_floor_range_config(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" class="btn btn-default">Thêm khoảng tầng</button>

								</td>

							</tr></tfoot>

						</table>

					</div>

					<div class="tab-pane fade py-3" id="layout_map_floor" role="tabpanel" aria-labelledby="home-tab">

						<table class="table table-bordered">

							<thead><tr>

								<th class="align-center text-left">Tiêu đề</th>

								<th class="align-center text-left">Giá trị</th>

								<th class="align-center text-left" width="60px"></th>

							</tr></thead>

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['layout_map_floor'])) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['layout_map_floor'], '_oLayout', false, '_oKey', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oKey']->value => $_smarty_tpl->tpl_vars['_oLayout']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>

								<tr class="tr_layout">

									<td class="text-left">

										<input type="text" name="layout_map_floor[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][title]" placeholder="Layout tầng..." 

											class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oLayout']->value['title'];?>
" />

									</td>

									<td class="text-left">

										<div class="input-group">

											<input type="text" class="form-control" name="layout_map_floor[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][image]" placeholder="Chọn hình ảnh" 

												id="isoman_url_layout_map_floor[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][image]" value="<?php echo $_smarty_tpl->tpl_vars['_oLayout']->value['image'];?>
">

											<div class="input-group-btn">

												<button class="btn btn-default ajOpenDialog" isoman_for_id="layout_map_floor[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][image]" isoman_val="<?php echo $_smarty_tpl->tpl_vars['_oLayout']->value['image'];?>
" isoman_name="layout_map_floor[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][image]" style="padding:9px 10px"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button>

											</div>	

										</div>

									</td>

									<td class="text-center">

										<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?>

											<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.add_layout_map_floor(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus');?>
</button>

										<?php } else { ?>

											<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.delete_layout(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

										<?php }?>

									</td>

								</tr>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php } else { ?>

								<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

								<tr class="tr_layout">

									<td class="text-left">

										<input type="text" name="layout_map_floor[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][title]" placeholder="Layout map tầng..." 

											class="form-control" value="" />

									</td>

									<td class="text-left">

										<div class="input-group">

											<input type="text" class="form-control" name="layout_map_floor[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][image]" placeholder="Chọn hình ảnh" 

												id="isoman_url_layout_map_floor_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="">

											<div class="input-group-btn">

												<button class="btn btn-default ajOpenDialog" isoman_for_id="layout_map_floor_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" isoman_val="" isoman_name="layout_map_floor[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][image]" style="padding:9px 10px"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button>

											</div>	

										</div>

									</td>

									<td class="text-center">

										<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.add_layout_map_floor(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus');?>
</button>

									</td>

								</tr>

							<?php }?>

						</table>

					</div>

					<div class="tab-pane fade py-3" id="csbh" role="tabpanel" aria-labelledby="csbh-tab">

						<table class="table table-bordered">

							<thead><tr>

								<th class="align-center text-left">Tiêu đề</th>

								<th class="align-center text-left">Giá trị</th>

								<th class="align-center text-left" width="60px"></th>

							</tr></thead>

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sales_policy'])) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['sales_policy'], '_oPolicy', false, 'toId', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['toId']->value => $_smarty_tpl->tpl_vars['_oPolicy']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>

								<tr class="tr_csbh">

									<td class="text-left">

										<input type="text" name="sales_policy[<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
][title]" 

											class="form-control" placeholder="Tiêu đề" value="<?php echo $_smarty_tpl->tpl_vars['_oPolicy']->value['title'];?>
" />

									</td>

									<td class="text-left">

										<div class="input-group">

											<input type="text" class="form-control" placeholder="Layout tòa nhà" name="sales_policy[<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
][image]" id="sales_policy_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['_oPolicy']->value['image'];?>
" />

											<div class="input-group-btn">

												<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="sales_policy_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"><i class="fa fa-upload"></i></button>

											</div>

										</div>

									</td>

									<td class="text-center">

										<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?>

										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.add_policy(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus');?>
</button>

										<?php } else { ?>

										<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_policy(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

										<?php }?>

									</td>

								</tr>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php } else { ?>

								<tr class="tr_csbh">

									<td class="text-left">

										<input type="text" name="sales_policy[<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
][title]" 

											class="form-control" placeholder="Tiêu đề" />

									</td>

									<td class="text-left">

										<div class="input-group">

											<input type="text" class="form-control" placeholder="Hình ảnh CSBH" name="sales_policy[<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
][image]" id="sales_policy_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" value="" />

											<div class="input-group-btn">

												<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="sales_policy_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"><i class="fa fa-upload"></i></button>

											</div>

										</div>

									</td>

									<td class="text-center">

										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.add_policy(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus');?>
</button>

									</td>

								</tr>

							<?php }?>

						</table>

					</div>

				</div>

				<?php }?>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-success pull-right" onClick="$Core.project.pop_save_building(this, event)" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_openFrom']->value == '_stock') {?> toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"<?php }?> _openFrom="<?php echo $_smarty_tpl->tpl_vars['_openFrom']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
">Cập nhật</button>

				<button type="button" class="btn btn-default mr-2 pull-right" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Close');?>
</button>

			</div>

		</form>

	</div>

</div>

<?php }
}
