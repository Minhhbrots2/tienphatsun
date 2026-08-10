<?php
/* Smarty version 3.1.33, created on 2026-08-08 17:31:50
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.block.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a77059607c2a8_13026925',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e532dcafd78d9d5992f9495624b0f41bebaf5d58' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.block.tpl',
      1 => 1784691720,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a77059607c2a8_13026925 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['template_type']->value == '_form') {?>
<div class="modal-dialog modal-lg">
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
				<div class="form-group form-row">
					<div class="col-md-1">
						<label class="col-form-label">Vị trí</label>
						<input type="number" class="form-control required" placeholder="Vị trí" name="order_no" value="<?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['order_no'];?>
" />
					</div>
					<div class="col-md-2">
						<label class="col-form-label">Mã phân khu<span class="text-red">*</span></label>
						<input type="text" class="form-control required" placeholder="Mã phân khu" name="property_code" value="<?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['property_code'];?>
" />
					</div>
					<div class="col-md-6">
						<label class="col-form-label">Tên phân khu<span class="text-red">*</span></label>
						<input type="text" class="form-control required" placeholder="Nhập tên dự án" name="title" value="<?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
" />
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Loại hình<span class="text-red">*</span></label>
						<select name="parent_id" class="form-control required">
							<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_BLOCK_TYPE',$_smarty_tpl->tpl_vars['oneBlock']->value['parent_id'],'Loại hình');?>

						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_price_fields']->value, '_oText', false, '_oKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oKey']->value => $_smarty_tpl->tpl_vars['_oText']->value) {
?>
					<div class="col-md-3">
						<div class="d-flex align-items-center bg-gray p-3 radius-4 mb-2">
							<label class="switch mr-3"><input type="checkbox" name="price_field_configs[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][status]"<?php if (!empty($_smarty_tpl->tpl_vars['price_field_configs']->value[$_smarty_tpl->tpl_vars['_oKey']->value]['status']) && $_smarty_tpl->tpl_vars['price_field_configs']->value[$_smarty_tpl->tpl_vars['_oKey']->value]['status'] == '1') {?> checked<?php }?> value="1">
								<span class="slider round"></span></label>
							<?php if (!empty($_smarty_tpl->tpl_vars['price_field_configs']->value[$_smarty_tpl->tpl_vars['_oKey']->value]['status'])) {?>
							<input type="text" class="form-control w-125px" name="price_field_configs[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][title]" value="<?php echo $_smarty_tpl->tpl_vars['price_field_configs']->value[$_smarty_tpl->tpl_vars['_oKey']->value]['title'];?>
" />
							<?php } else { ?>
							<input type="text" class="form-control w-125px" name="price_field_configs[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][title]" value="<?php echo $_smarty_tpl->tpl_vars['_oText']->value;?>
" />
							<?php }?>
						</div>
					</div>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</div>
				<div class="form-group form-row">
					<div class="col-md-2">
						<label class="col-form-label">Mở bán</label>
						<div class="d-flex align-items-center">
							<label class="switch mr-3">
								<input type="checkbox" name="on_sale"<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['on_sale']) && $_smarty_tpl->tpl_vars['more_information']->value['on_sale'] == '1') {?> checked<?php }?> value="1">
								<span class="slider round"></span>
							</label>
							<span class="text-muted">Đang mở bán</span>
						</div>
					</div>
					<div class="col-md-2">
						<label class="col-form-label">Dự án</label>
						<div class="d-flex gap-2 align-items-center">
							<label class="switch">
								<input type="checkbox" name="is_project"<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['is_project']) && $_smarty_tpl->tpl_vars['more_information']->value['is_project'] == '1') {?> checked<?php }?> value="1">
								<span class="slider round"></span>
							</label>
							<span class="text-muted">Phân khu = dự án</span>
						</div>
					</div>
					<div class="col-md-5">
						<label class="col-form-label">Quỹ</label>
						<div class="d-flex align-items-center gap-3">
							<div class="d-flex align-items-center">
								<label class="switch mr-1">
									<input type="radio" name="is_stock_fund_of"<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['is_stock_fund_of']) && $_smarty_tpl->tpl_vars['more_information']->value['is_stock_fund_of'] == 'mas') {?> checked<?php }?> value="mas">
									<span class="slider round"></span>
								</label>
								<span class="text-muted">Quỹ Mas</span>
							</div>
							<div class="d-flex align-items-center">
								<label class="switch mr-1">
									<input type="radio" name="is_stock_fund_of"<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['is_stock_fund_of']) && $_smarty_tpl->tpl_vars['more_information']->value['is_stock_fund_of'] == 'vin') {?> checked<?php }?> value="vin">
									<span class="slider round"></span>
								</label>
								<span class="text-muted">Quỹ Vin</span>
							</div>
							<div class="d-flex align-items-center">
								<label class="switch mr-1">
									<input type="radio" name="is_stock_fund_of"<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['is_stock_fund_of']) && $_smarty_tpl->tpl_vars['more_information']->value['is_stock_fund_of'] == 'other') {?> checked<?php }?> value="other">
									<span class="slider round"></span>
								</label>
								<span class="text-muted">Quỹ Khác</span>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-4">
						<label class="col-form-label">Chủ đầu tư</label>
						<select class="form-control iso-select2" name="investor_id">
							<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_INVESTOR',$_smarty_tpl->tpl_vars['more_information']->value['investor_id']);?>

						</select>
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Nhận booking</label>
						<div class="d-flex gap-2 align-items-center">
							<label class="switch">
								<input type="checkbox" name="is_booking"<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['is_booking']) && $_smarty_tpl->tpl_vars['more_information']->value['is_booking'] == '1') {?> checked<?php }?> value="1" onChange="$Core.project.change_booking(this,event)" toId="time_booking_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" >
								<span class="slider round"></span>
							</label>
							<span class="text-muted">Nhận booking</span>
						</div>
					</div>		
					<div class="col-md-5">
						<div class="form-row <?php if (empty($_smarty_tpl->tpl_vars['more_information']->value['is_booking'])) {?> d-none<?php }?>" id="time_booking_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
							<div class="col-md-6">
								<label class="col-form-label">Từ</label>
								<div class="d-flex gap-2 align-items-center">
									<input class="form-control" type="datetime-local" name="start_booking" value="<?php echo $_smarty_tpl->tpl_vars['start_booking']->value;?>
">
								</div>
							</div>			
							<div class="col-md-6">
								<label class="col-form-label">Đến</label>
								<div class="d-flex gap-2 align-items-center">
									<input class="form-control" type="datetime-local" name="end_booking" value="<?php echo $_smarty_tpl->tpl_vars['end_booking']->value;?>
">
								</div>
							</div>
						</div>
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
					<div class="col-md-4">
						<label class="col-form-label">Hình ảnh đại diện</label>
						<div class="input-group">
							<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh đại diện" 
							id="isoman_url_image" value="<?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['image'];?>
">
							<div class="input-group-btn"><button class="btn btn-icon btn-default ajOpenDialog" isoman_for_id="image" isoman_val="<?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['image'];?>
" isoman_name="image"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button></div>	
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Mặt bằng fullsize</label>
						<div class="input-group">
							<input type="text" class="form-control" name="layout_ms" placeholder="Mặt bằng fullsize" 
								id="isoman_url_layout_ms" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['layout_ms'];?>
">
							<div class="input-group-btn"><button class="btn btn-icon btn-default ajOpenDialog" isoman_for_id="layout_ms" isoman_val="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['layout_ms'];?>
" isoman_name="layout_ms"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button></div>	
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Mặt bằng<span class="text-red">*</span></label>
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Layout phân khu" name="layout_ns" 
								id="layout_block_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['layout_ns'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['layout_ns'];
}?>" />
							<div class="input-group-btn"><button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="layout_block_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"><i class="fa fa-upload"></i></button></div>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-3">
						<label class="col-form-label">VR360<span class="text-red">*</span></label>
						<input type="text" class="form-control" placeholder="Nhập URL VR360" name="vr_link" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['vr_link'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['vr_link'];
}?>" />	
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Nguồn VR360<span class="text-red">*</span></label>
						<input type="text" class="form-control" placeholder="Nhập nguồn VR360" name="vr_source" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['vr_source'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['vr_source'];
}?>" />	
					</div>
					<div class="col-xs-12 col-md-3">
						<div class="form-row">
							<div class="col-xs-6 col-md-6">
								<label for="" class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('BgColor');?>
</label>
								<input type="color" class="form-control required" placeholder="Màu nền" 
								name="bgcolor" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['bgcolor'];?>
">
							</div>
							<div class="col-xs-6 col-md-6">
							<label for="" class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('TextColor');?>
</label>
								<input type="color" class="form-control required" placeholder="Màu chữ" 
								name="textcolor" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['textcolor'];?>
">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<label class="col-form-label mr-3">Giám đốc dự án</label>
						<select placeholder="Giám đốc dự án" name="project_manager" class="form-control iso-select2">
							<option value="0">--Chọn--</option>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_profile']->value, '_oProfile', false, 'key', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProfile']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['profile_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['more_information']->value['project_manager'] == $_smarty_tpl->tpl_vars['_oProfile']->value['profile_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['full_name'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-6">
						<label class="col-form-label mr-3">Admin dự án</label>
						<select data-placeholder="Admin dự án" multiple="true" name="project_admins[]" class="form-control iso-select2">
							<option value="0">--Chọn--</option>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_profile']->value, '_oProfile', false, 'key', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProfile']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
								<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['arr_project_admins']->value,$_smarty_tpl->tpl_vars['_oProfile']->value['profile_id'])) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['full_name'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</select>
					</div>
					<div class="col-md-3">
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
					<div class="col-md-3">
						<label class="col-form-label mr-3">Loại giao dịch:</label>
						<select placeholder="" name="billing_type" class="form-control iso-select2">
							<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_BILLING_TYPE',$_smarty_tpl->tpl_vars['more_information']->value['billing_type']);?>

						</select>
					</div>
				</div>
				<div class="p-3 bg-gray radius-3">
					<div class="form-group form-row">
						<div class="col-xs-12 col-lg-6">
							<label class="col-form-label">Link Tiles</label>
							<input class="form-control" name="tiles_link" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['tiles_link'];?>
" maxlength="255" />
						</div>
						<div class="col-xs-12 col-lg-6">
							<label class="col-form-label">Config Tiles</label>
							<div class="form-row">
								<div class="col-xs-12 col-lg-6">
									<div class="d-flex gap-2 align-items-center">
										<input type="hidden" name="is_tiles" value="0" />
										<label class="switch">
											<input type="checkbox" name="is_tiles"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_tiles'] == '1') {?> checked<?php }?> value="1">
											<span class="slider round"></span>
										</label>
										<Span>Sử dụng tiles</span>
									</div>
								</div>
								<div class="col-xs-12 col-lg-6">
									<div class="d-flex gap-2 align-items-center">
										<input type="hidden" name="is_map_tiles" value="0" />
										<label class="switch">
											<input type="checkbox" name="is_map_tiles"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_map_tiles'] == '1') {?> checked<?php }?> value="1">
											<span class="slider round"></span>
										</label>
										<Span>Map tiles</span>
									</div>
								</div>
							</div>
						</div>							
					</div>
					<div class="form-group form-row">
						<div class="col-md-3">
							<label class="col-form-label">Max Zoom</label>
							<input type="number" class="form-control" name="max_zoom" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['max_zoom'];?>
" maxlength="255" />
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Vị trí trung tâm</label>
							<input class="form-control" name="center_point" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['center_point'];?>
" maxlength="255" />
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Vùng bound</label>
							<input class="form-control" name="max_bound" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['max_bound'];?>
" maxlength="255" />
						</div>
						<div class="col-md-3">
							<label class="col-form-label">TMS</label>
							<select class="form-control" name="tms_enable" value="">
								<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['tms_enable'] == '0') {?> selected<?php }?> value="0">NO</option>
								<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['tms_enable'] == '1') {?> selected<?php }?> value="1">YES</option>
							</select>
						</div>
					</div>
				</div>
				<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
					<li class="nav-item active">
						<a href="#content" class="nav-link" data-toggle="tab" role="tab">Giới thiệu</a>
					</li>
					<li class="nav-item">
						<a href="#csbh" class="nav-link" data-toggle="tab" role="tab">Chính sách bán hàng</a>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade py-3 active in" id="content" role="tabpanel" aria-labelledby="content-tab">
						<div class="widget-block mb-5">
							<div class="widget-header">
								<div class="d-flex align-items-center justify-content-between">
									<strong class="mb-0">Tổng quan</strong>
									<a onClick="add_property(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" _openFrom="_block" _holderG="_attrs">+ Thêm</a>
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
													<a class="btn btn-default" title="Xóa" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
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
													<a class="btn btn-default" title="Xóa" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
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
" style="width:100%" class="isoTextArea" data-name="intro" cols="255" rows="10"><?php if ($_smarty_tpl->tpl_vars['block_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneBlock']->value['intro'];
}?></textarea>
							</div>
						</div>
					</div>
					<div class="tab-pane fade py-3" id="csbh" role="tabpanel" aria-labelledby="csbh-tab">
						<table class="table table-bordered">
							<thead><tr>
								<th class="align-center text-left">Tiêu đề</th>
								<th class="align-center text-left">Giá trị</th>
								<th class="align-center text-left" width="300px">Tòa áp dụng</th>
								<th class="align-center text-center" width="60px">...</th>
							</tr></thead>
							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sales_policy'])) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['sales_policy'], '_oPolicy', false, 'gId', 'i', array (
  'iteration' => true,
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['gId']->value => $_smarty_tpl->tpl_vars['_oPolicy']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
								<tr class="tr_csbh">
									<td class="text-left">
										<input type="text" name="sales_policy[<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
][title]" 
											class="form-control" placeholder="Tiêu đề" value="<?php echo $_smarty_tpl->tpl_vars['_oPolicy']->value['title'];?>
" />
									</td>
									<td class="text-left">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Layout tòa nhà" name="sales_policy[<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
][image]" id="sales_policy_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['_oPolicy']->value['image'];?>
" />
											<div class="input-group-btn">
												<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="sales_policy_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"><i class="fa fa-upload"></i></button>
											</div>
										</div>
									</td>
									<td width="100px" class="text-left">
										<select class="form-control iso-select2" 
											name="sales_policy[<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
][building][]" multiple="true">
											<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_obuilding');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_obuilding']->value) {
?>
												<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['_oPolicy']->value['building'],$_smarty_tpl->tpl_vars['_obuilding']->value['property_id'])) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['title'];?>
</option>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											<?php }?>
										</select>
									</td>
									<td class="text-center">
										<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?>
										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.add_policy(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" holderG="_block" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
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
									<td width="100px" class="text-left">
										<select class="form-control iso-select2" 
											name="sales_policy[<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
][building][]" multiple="true">
											<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_obuilding');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_obuilding']->value) {
?>
												<option value="<?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['title'];?>
</option>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											<?php }?>
										</select>
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.add_policy(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" holderG="_block" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus');?>
</button>
									</td>
								</tr>
							<?php }?>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="pop_save_block(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" _openFrom="<?php echo $_smarty_tpl->tpl_vars['_openFrom']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_openFrom']->value == '_stock') {?> toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"<?php }?>>Cập nhật</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Close');?>
</button>
			</div>
		</form>
	</div>
</div>
<?php } else { ?>
	<table width="100%" class="table table-vertical mb-0 table-stripped">
		<thead><tr>
			<th width="4%">No.</th>
			<th>Tên phân khu</th>
			<th width="25%"></th>
			<th class="text-center" width="60px">Vị trí</th>
			<th width="5%"></th>
		</tr></thead>
		<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>
			<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_blocks']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_0_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
			<?php $_smarty_tpl->_assignInScope('_block_id', $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id']);?>
			<?php $_smarty_tpl->_assignInScope('list_buildings', $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['list_buildings']);?>
			<tbody class="tbodyProject">
				<tr class="tr_selected">
					<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
</td>
					<td class="text-left"><strong><?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'];?>
</strong></td>
					<td class="text-left"><a href="javascript:void(0)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" title="Thêm tòa nhà" data-toggle="tooltip" onClick="open_building(this, event)">+ Thêm</a></td>
					<td class="text-center">
						<input type="text" class="form-control numberonly" name="order_no" onChange="$Core.project.updateOrder(this,event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['order_no'];?>
">
					</td>
					<td class="text-center">
						<div class="btn-group">
							<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
							<ul class="dropdown-menu" style="right:0px !important;left:auto; min-width:130px">
								<li><a title="Chỉnh sửa" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="open_block(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil','Chỉnh sửa');?>
</a></li>
								<li><a title="Tiến độ" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="open_progress(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars','Tiến độ');?>
</a></li>
								<li><a title="Xóa" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" onClick="delete_block(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash','Xóa');?>
</a></li>
							</ul>
						</div>
					</td>
				</tr>
				<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>
				<tbody class="tbodyBlock tbodyBlock_<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_obuilding');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_obuilding']->value) {
?>
					<tr id="<?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['property_id'];?>
">
						<td><a class="mySortableHandler" href="javascript:void(0)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('arrows');?>
</a></td>
						<td><?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['title'];
if ($_smarty_tpl->tpl_vars['_obuilding']->value['is_trash'] == '1') {?> [Trashed]<?php }?></td>
						<td><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=stock&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
&block_id=<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
&building_id=<?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['property_id'];?>
" title="QL.Tình trạng bán hàng tòa nhà" data-toggle="tooltip"  class="label label-default">Q.Lý</a></td>
						<td class="text-center"></td>
						<td class="text-center">
							<div class="btn-group">
								<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
								<ul class="dropdown-menu" style="right:0px !important;left:auto; min-width:130px">
									<li><a href="javascript:void(0);" title="Chỉnh sửa" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="open_building(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil','Chỉnh sửa');?>
</a></li>
									<li><a title="Tiến độ" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="open_progress(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars','Tiến độ');?>
</a></li>
									<li><a href="javascript:void(0);" title="Xóa" class="btn_delete_building_<?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['property_id'];
if ($_smarty_tpl->tpl_vars['_obuilding']->value['is_locked'] == '1') {?> disabled<?php }?>" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="delete_building(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['list_blocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['_obuilding']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash','Xóa');?>
</a></li>
								</ul>
							</div>
						</td>
					</tr>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</tbody>
				<?php }?>
			</tbody>
			<?php
}
}
?>
		<?php }?>		
	</table>
<?php }
}
}
