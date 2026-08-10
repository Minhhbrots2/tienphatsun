<?php
/* Smarty version 3.1.33, created on 2026-08-08 20:15:03
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/ajax/property.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a772bd7674309_34331272',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'db330ee4e6c47357aaf18b4a0fe88dd25be5769f' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/ajax/property.tpl',
      1 => 1785981779,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a772bd7674309_34331272 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['action']->value == '_form') {?>
<div class="modal-dialog modal-standard">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong><?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</strong></h3>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</label>
					<div class="col-md-4">
						<input type="text" class="form-control required" placeholder="Mã" name="property_code" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['property_code'];
}?>">
					</div>
					<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_GROUPSIZE') {?>
					<label class="col-md-2 text-right col-form-label">Tiêu đề + số thành viên</label>
					<div class="col-md-4">
						<div Class="input-group">
							<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['title'];
}?>">
							<input type="number" class="form-control required" placeholder="0" name="size_group" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['size_group'];
}?>">
						</div>
					</div>
					<?php } else { ?>
					<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
</label>
					<div class="col-md-4">
						<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['title'];
}?>">
					</div>
					<?php }?>
				</div>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_ULTILITIES') {?>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Link</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Đường dẫn" name="link" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['link'];
}?>">
						</div>
						<label class="col-md-2 text-right col-form-label">Thuộc tính</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Thuộc tính" name="attr" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['attr'];
}?>">
						</div>
					</div>
					<div class="form-group form-row">
						<label for="" class="col-md-2 text-right col-form-label">Nhóm</label>
						<div class="col-md-4">
							<select class="form-control" name="group" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['group'];
}?>">
								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectSingleProperty('_GROUP_ULTILITIES',0,$_smarty_tpl->tpl_vars['more_information']->value['group']);?>

							</select>
						</div>
						<label for="" class="col-md-2 text-right col-form-label">Class Icon</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Class Icon" name="icon" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['icon'];
}?>">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Permiss</label>
						<div class="col-md-10">
							<div class="form-row">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_roles']->value, '_oText', false, '_oRole');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oRole']->value => $_smarty_tpl->tpl_vars['_oText']->value) {
?>
								<div class="col-md-4 mb-3">
									<div class="checkbox">
										<input type="checkbox" id="<?php echo $_smarty_tpl->tpl_vars['_oRole']->value;?>
" name="role[]" class="styled" value="<?php echo $_smarty_tpl->tpl_vars['_oRole']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oRole']->value,$_smarty_tpl->tpl_vars['more_information']->value['role'])) {?>checked<?php }?>>
										<label for="<?php echo $_smarty_tpl->tpl_vars['_oRole']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_oText']->value;?>
</label>
									</div>
								</div>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</div>
						</div>
					</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE') {?>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">1 tháng</label>
						<div class="col-md-3">
							<input type="number" class="form-control required" placeholder="0" name="price_month" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['price_month'];
}?>">
						</div>
						<label class="col-md-2 text-right col-form-label">3 tháng</label>
						<div class="col-md-5">
							<input type="number" class="form-control required" placeholder="0" name="price_3month" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['price_3month'];
}?>">
						</div>
					</div>
					<div class="form-group form-row">	
						<label class="col-md-2 text-right col-form-label">6 tháng</label>
						<div class="col-md-3">
							<input type="number" class="form-control required" placeholder="0" name="price_6month" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['price_6month'];
}?>">
						</div>
						<label class="col-md-2 text-right col-form-label">12 tháng</label>
						<div class="col-md-5">
							<input type="number" class="form-control required" placeholder="0" name="price_year" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['price_year'];
}?>">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Mô tả gói</label>
						<div class="col-md-10">
							<textarea name="package_intro" id="" cols="30" rows="2" class="form-control required" placeholder="Nhập mô tả" style="resize: vertical"><?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo html_entity_decode($_smarty_tpl->tpl_vars['more_information']->value['package_intro']);
}?></textarea>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Hiển thị</label>
						<div class="col-md-3">
							<label class="checkbox-inline mt-2">
								<input type="checkbox" name="show_pricing" value="1" <?php if ($_smarty_tpl->tpl_vars['more_information']->value['show_pricing'] == 1) {?>checked<?php }?>>
								<span>Cho phép hiển thị</span>
							</label>
						</div>
						<label class="col-md-2 text-right col-form-label">Dùng thử (số ngày)</label>
						<div class="col-md-5">
							<input type="number" class="form-control required" placeholder="0" name="day_trial" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['day_trial'];
}?>">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Điều khoản dùng thử</label>
						<div class="col-md-10">
							<textarea class="form-control isoTextArea" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" cols="255" data-name="trial_terms" rows="5"><?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['trial_terms'];
}?></textarea>
						</div>
					</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DIRECTION' || $_smarty_tpl->tpl_vars['property_type']->value == '_AGENCY' || $_smarty_tpl->tpl_vars['property_type']->value == '_TYPE_VILLA' || $_smarty_tpl->tpl_vars['property_type']->value == '_REPORT_TEMPLATE' || $_smarty_tpl->tpl_vars['property_type']->value == '_BILLING_TYPE') {?>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label required">
							<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_REPORT_TEMPLATE' || $_smarty_tpl->tpl_vars['property_type']->value == '_BILLING_TYPE') {?>Tiêu đề mobile<?php } else { ?>Query DB<?php }?>
						</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title_vn" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['title_vn'];
}?>">
						</div>
						<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_REPORT_TEMPLATE') {?>
						<label class="col-md-2 text-right col-form-label required">Đơn vị</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Nhập đơn vị" name="unit_name" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['unit_name'];
}?>">
						</div>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_BILLING_TYPE') {?>
						<label class="col-md-2 text-right col-form-label required">ID Nhóm zalo</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Nhập ID nhóm Zalo" name="group_zalo_id" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['group_zalo_id'];
}?>">
						</div>
						<?php }?>
					</div>
				<?php } elseif ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label required"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Role');?>
</label>
						<div class="col-md-4">
							<select class="form-control required" name="for_id">
								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectSingleProperty('_ROLE',0,$_smarty_tpl->tpl_vars['for_id']->value);?>

							</select>
						</div>
						<input type="hidden" name="is_business_area" value="0" />
						<label class="col-md-2 text-right col-form-label required">Vùng kinh doanh</label>
						<div class="col-md-4">
							<label class="switch">
								<input type="checkbox" name="is_business_area"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_business_area'] == '1') {?> checked<?php }?> value="1">
								<span class="slider round"></span>
							</label>
						</div>
					</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value != '_PRICE_RANGE_SOP' && $_smarty_tpl->tpl_vars['property_type']->value != '_PRICE_RANGE_LEASING' && $_smarty_tpl->tpl_vars['property_type']->value != '_GROUP_ULTILITIES' && $_smarty_tpl->tpl_vars['property_type']->value != '_ULTILITIES' && $_smarty_tpl->tpl_vars['property_type']->value != '_AREA_RANGE' && $_smarty_tpl->tpl_vars['property_type']->value != '_CATEGORY_DOCS') {?>
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('BgColor');?>
</label>
					<div class="col-md-4">
						<input type="color" class="form-control required" placeholder="Màu nền" 
						name="bgcolor" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['bgcolor'];
}?>">
					</div>
					<label for="" class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('TextColor');?>
</label>
					<div class="col-md-4">
						<input type="color" class="form-control required" placeholder="Màu chữ" 
						name="textcolor" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['textcolor'];
}?>">
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Icon');?>
</label>
					<div class="col-xs-12 col-md-10">
						<div class="input-group w-100">
							<input type="text" class="form-control" name="icon" placeholder="Nhập class icon" id="icon" value="<?php echo $_smarty_tpl->tpl_vars['oneProperty']->value['icon'];?>
">
						</div>
					</div>
				</div>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value != '_ULTILITIES') {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Image');?>
</label>
					<div class="col-xs-12 col-md-10">
						<div class="input-group">
							<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh làm icon" id="isoman_url_image" value="<?php echo $_smarty_tpl->tpl_vars['oneProperty']->value['image'];?>
">
							<div class="input-group-btn">
								<button class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="<?php echo $_smarty_tpl->tpl_vars['oneProperty']->value['image'];?>
" isoman_name="image" style="padding:9px 10px"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button>
							</div>	
						</div>
					</div>
				</div><?php }?>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PRICE_RANGE_SOP' || $_smarty_tpl->tpl_vars['property_type']->value == '_PRICE_RANGE_LEASING' || $_smarty_tpl->tpl_vars['property_type']->value == '_AREA_RANGE') {?>
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label">Giá trị min</label>
					<div class="col-md-4">
						<input type="text" class="form-control required price-In" placeholder="<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PRICE_RANGE_SOP' || $_smarty_tpl->tpl_vars['property_type']->value == '_PRICE_RANGE_LEASING') {?>1.000.000.000đ<?php } else { ?>50m2<?php }?>" 
						name="min" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['min'];
}?>">
					</div>
					<label for="" class="col-md-2 text-right col-form-label">Giá trị max</label>
					<div class="col-md-4">
						<input type="text" class="form-control required price-In" placeholder="<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PRICE_RANGE_SOP' || $_smarty_tpl->tpl_vars['property_type']->value == '_PRICE_RANGE_LEASING') {?>10.000.000.000đ<?php } else { ?>500m2<?php }?>" 
						name="max" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['max'];
}?>">
					</div>
				</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value != '_CATEGORYSERVICES' && $_smarty_tpl->tpl_vars['property_type']->value != '_FURNITUREUNIT' && $_smarty_tpl->tpl_vars['property_type']->value != '_CATEGORYSFURNITURE' && $_smarty_tpl->tpl_vars['property_type']->value != '_GENDER' && $_smarty_tpl->tpl_vars['property_type']->value != '_FEATURE_MOC' && $_smarty_tpl->tpl_vars['property_type']->value != '_PRICE_RANGE_SOP' && $_smarty_tpl->tpl_vars['property_type']->value != '_PRICE_RANGE_LEASING' && $_smarty_tpl->tpl_vars['property_type']->value != '_AREA_RANGE' && $_smarty_tpl->tpl_vars['property_type']->value != '_ULTILITIES' && $_smarty_tpl->tpl_vars['property_type']->value != '_GROUP_ULTILITIES') {?>
				<div class="form-group form-row">
					<?php if ($_smarty_tpl->tpl_vars['property_type']->value == "_BEDROOM") {?>
					<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('ParentCategory');?>
</label>
					<div class="col-md-4">
						<select class="form-control" name="parent_id" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['title'];
}?>">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle($_smarty_tpl->tpl_vars['property_type']->value,$_smarty_tpl->tpl_vars['oneProperty']->value['parent_id'],"Danh mục cha");?>

						</select>
					</div>
					<label class="col-md-2 text-right col-form-label">Phòng tắm + ngủ</label>
					<div class="col-md-4">
						<div Class="input-group d-flex align-items-center">
							<input type="text" class="form-control required numberonly" 
								placeholder="Số phòng tắm" name="bathroom" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['bathroom'];
}?>">
							<input type="text" class="form-control required numberonly" 
								placeholder="Số phòng ngủ" name="ms_value" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['ms_value'];
}?>">
						</div>
					</div>
					<?php } else { ?>
					<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('ParentCategory');?>
</label>
					<div class="col-md-10">
						<select class="form-control" name="parent_id" value="<?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['title'];
}?>">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle($_smarty_tpl->tpl_vars['property_type']->value,$_smarty_tpl->tpl_vars['oneProperty']->value['parent_id'],"Danh mục cha");?>

						</select>
					</div>
					<?php }?>
				</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Văn phòng</label>
						<div class="col-md-10">
							<select class="form-control" name="office_id">
								<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectBySettingTypeTitle("_OFFICE",$_smarty_tpl->tpl_vars['more_information']->value['office_id'],"Văn phòng");?>

							</select>
						</div>
					</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_CATEGORY_DOCS') {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Hiển thị</label>
					<div class="col-md-10">
						<select class="form-control" name="view">
							<option value="grid" <?php if ($_smarty_tpl->tpl_vars['more_information']->value['view'] == 'grid') {?>selected<?php }?>>Lưới</option>
							<option value="list" <?php if ($_smarty_tpl->tpl_vars['more_information']->value['view'] == 'list') {?>selected<?php }?>>Danh sách</option>
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Image');?>
</label>
					<div class="col-xs-12 col-md-10">
						<div class="input-group">
							<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh đại diện" 
							id="isoman_url_image" value="<?php echo $_smarty_tpl->tpl_vars['oneProperty']->value['image'];?>
">
							<div class="input-group-btn">
								<button class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="<?php echo $_smarty_tpl->tpl_vars['oneProperty']->value['image'];?>
" isoman_name="image" style="padding:9px 10px"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button>
							</div>	
						</div>
					</div>
				</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Trưởng nhóm/GĐ</label>
					<div class="col-md-10">
						<select class="form-control iso-select2" data-width="100%" name="head_of_dep_id">
							<option value="0">Lựa chọn trưởng nhóm/GĐ</option>
							<?php if (!empty($_smarty_tpl->tpl_vars['list_staffs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_staffs']->value, '_oStaff');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['head_of_dep_id'] == $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'];?>
">
								<?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['code'];?>
 - <?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['full_name'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php }?>
						</select>
					</div>
				</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_BILLING_TYPE') {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Giám đốc dự án</label>
					<div class="col-md-10">
						<select class="form-control iso-select2" data-width="100%" name="project_director_id">
							<option value="0">Giám đốc dự án</option>
							<?php if (!empty($_smarty_tpl->tpl_vars['list_staffs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_staffs']->value, '_oStaff');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['project_director_id'] == $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['code'];?>
 - <?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['full_name'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Số tiền cọc</label>
					<div class="col-md-10">
						<input type="text" class="form-control price-In" name="deposit" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['deposit'];?>
">
					</div>
				</div>				
				<?php if (!empty($_smarty_tpl->tpl_vars['list_group_zalo']->value)) {?><hr />
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_group_zalo']->value, '_oP', false, '_oK', 'k', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oK']->value => $_smarty_tpl->tpl_vars['_oP']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['total'];
?>
					<div class="form-group form-row group_zalo">
						<label class="col-md-3 text-right col-form-label required">Nhóm zalo chúc mừng
							<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['last'] : null)) {?>
							<a href="javascript:void(0);" onClick="$Core.property.add_group_zalo(this,event)">+Thêm</a>
							<?php }?>
						</label>
						<div class="col-md-3">
							<select class="form-control iso-select2" data-width="100%" name="group_zalo[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][project_id]" onChange="$Core.property.select_block(this,event)" toId="block_<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
">
								<option value="0">Chọn dự án</option>
								<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getSelectOptions($_smarty_tpl->tpl_vars['_oP']->value['project_id']);?>

							</select>
						</div>
						<div class="col-md-3">
							<select class="form-control iso-select2" data-width="100%" name="group_zalo[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][block_id]" id="block_<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
">
								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyOrigin("_BLOCK",$_smarty_tpl->tpl_vars['_oP']->value['project_id'],$_smarty_tpl->tpl_vars['_oP']->value['block_id'],"Phân khu/Block");?>

							</select>
						</div>
						<div class="col-md-3">
							<input type="text" class="form-control" onClick="this.select();" name="group_zalo[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][group_zalo_id]" value="<?php echo $_smarty_tpl->tpl_vars['_oP']->value['group_zalo_id'];?>
" placeholder="Link folder" maxlength="255">
						</div>
					</div>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<hr />
				<?php }?>
				<?php }?>
				<!-- Xác nhận hoa hồng -->
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_TRANSACTION_PROJECT') {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Giám đốc dự án</label>
					<div class="col-md-4">
						<select class="form-control iso-select2" data-width="100%" name="project_director_id">
							<option value="0">Giám đốc dự án</option>
							<?php if (!empty($_smarty_tpl->tpl_vars['list_staffs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_staffs']->value, '_oStaff');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['project_director_id'] == $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['code'];?>
 - <?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['full_name'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php }?>
						</select>
					</div>
					<label class="col-md-2 text-right col-form-label">Admin dự án</label>
					<div class="col-md-4">
						<select class="form-control iso-select2" data-width="100%" name="project_admin_id">
							<option value="0">Admin dự án</option>
							<?php if (!empty($_smarty_tpl->tpl_vars['list_staffs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_staffs']->value, '_oStaff');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['project_admin_id'] == $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['code'];?>
 - <?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['full_name'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php }?>
						</select>
					</div>
				</div>
				<?php }?>
				<!-- Xác nhận hoa hồng -->
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_BEDROOM') {?>
					<?php if (!empty($_smarty_tpl->tpl_vars['list_folder_interior_ns']->value)) {?>
						<hr />
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_folder_interior_ns']->value, '_oP', false, '_oK', 'k', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oK']->value => $_smarty_tpl->tpl_vars['_oP']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['total'];
?>
						<div class="interior_ns_pa_<?php echo $_smarty_tpl->tpl_vars['property_id']->value;?>
">
							<div class="form-group form-row group_price_sheets">
								<label class="col-md-2 text-right col-form-label required">Nội thất mẫu PA<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration'] : null);?>
</label>
								<div class="col-md-5">
									<input type="text" class="form-control" onClick="this.select();" name="folder_interior_ns[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][title]" 
										value="<?php echo $_smarty_tpl->tpl_vars['_oP']->value['title'];?>
" placeholder="Tên folder" maxlength="255">
								</div>
								<div class="col-md-5">
									<input type="text" class="form-control" onClick="this.select();" name="folder_interior_ns[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][link]" 
										value="<?php echo $_smarty_tpl->tpl_vars['_oP']->value['link'];?>
" placeholder="Link folder" maxlength="255">
								</div>
							</div>
							<div class="form-group form-row">
								<label class="col-md-2 text-right col-form-label required"></label>
								<div class="col-md-10">
									<input type="text" class="form-control required" placeholder="https://www.youtube.com/watch?v=xxx" 
									name="folder_interior_ns[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][video]" onClick="this.select();" value="<?php echo $_smarty_tpl->tpl_vars['_oP']->value['video'];?>
">
								</div>
							</div>
						</div>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<div class="form-group form-row">
							<label class="col-md-2 text-right col-form-label required"></label>
							<div class="col-md-10">
								<a class="btn btn-default" href="javascript:void(0);" property_id="<?php echo $_smarty_tpl->tpl_vars['property_id']->value;?>
" onClick="$Core.property.add_folder_interior_ns(this, event)">+Thêm PA</a>
							</div>
						</div>
						<hr />
					<?php }?>
				<?php } elseif ($_smarty_tpl->tpl_vars['property_type']->value == '_AGENCY') {?>
					<?php if (!empty($_smarty_tpl->tpl_vars['list_folder_price_sheets']->value)) {?><hr />
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_folder_price_sheets']->value, '_oP', false, '_oK', 'k', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oK']->value => $_smarty_tpl->tpl_vars['_oP']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['total'];
?>
						<div class="form-group form-row group_price_sheets">
							
							<label class="col-md-2 text-right col-form-label required">Folder PTG
								<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['last'] : null)) {?>
								<a href="javascript:void(0);" onClick="$Core.property.add_folder_price_sheets(this,event)">+Thêm</a>
								<?php }?>
							</label>
							<div class="col-md-1 text-center">
								<div class="p-2">
									<input type="hidden" name="folder_price_sheets[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][status]" value="0" />
									<div class="checkbox">
										<input type="checkbox" class="checkitem" name="folder_price_sheets[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][status]" 
											value="1" value="1"<?php if ($_smarty_tpl->tpl_vars['_oP']->value['status'] == '1') {?> checked<?php }?>>
										<label></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][title]" value="<?php echo $_smarty_tpl->tpl_vars['_oP']->value['title'];?>
" placeholder="Tên folder" maxlength="255">
							</div>
							<div class="col-md-5">
								<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets[<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
][link]" value="<?php echo $_smarty_tpl->tpl_vars['_oP']->value['link'];?>
" placeholder="Link folder" maxlength="255">
							</div>
						</div>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<hr />
					<?php }?>
					<div class="group_info_agency">
						<div class="form-group form-row ">							
							<label class="col-md-2 text-right col-form-label required">Văn phòng trụ sở</label>
							<div class="col-md-10">
								<input type="text" class="form-control" onClick="this.select();" name="info_agency[head_office]" value="<?php echo $_smarty_tpl->tpl_vars['info_agency']->value['head_office'];?>
" placeholder="Trụ sở chính" maxlength="255">
							</div>
						</div>
						<hr>
						<div class="form-group group_item">	
							<?php if (!empty($_smarty_tpl->tpl_vars['info_agency']->value['branch_office'])) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['info_agency']->value['branch_office'], 'branch_office', false, 'key', 'i', array (
  'last' => true,
  'first' => true,
  'index' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['branch_office']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>
								<div class="form-row mb-2 item">
									<label class="col-md-2 text-right col-form-label required">Văn phòng chi nhánh
										<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?>
										<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="branch_office" >+Thêm</a>
										<?php }?>
									</label>
									<div class="col-md-9">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[branch_office][]" value="<?php echo $_smarty_tpl->tpl_vars['branch_office']->value;?>
" placeholder="Chi nhánh" maxlength="255">
									</div>
									<div class="col-md-1">
										<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?>
										<button class="btn btn-default" onClick="$Core.property.delete_info_agency(this,event)" tp="branch_office" type="button" ><i class="fa fa-trash"></i></button>
										<?php }?>
									</div>
								</div>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php } else { ?>
							<div class="form-row mb-2 item">
								<label class="col-md-2 text-right col-form-label required">Văn phòng chi nhánh
									<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="branch_office" >+Thêm</a>
								</label>
								<div class="col-md-9">
									<input type="text" class="form-control" onClick="this.select();" name="info_agency[branch_office][]" value="" placeholder="Chi nhánh" maxlength="255">
								</div>
							</div>
							<?php }?>
						</div>
						<hr>
						<div class="form-group group_item">	
							<?php if (!empty($_smarty_tpl->tpl_vars['info_agency']->value['project'])) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['info_agency']->value['project'], '_oProject', false, 'key', 'i', array (
  'last' => true,
  'first' => true,
  'index' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProject']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>
								<div class="form-row mb-2 item">
									<label class="col-md-2 text-right col-form-label required">Dự án bán
										<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?>
										<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="project" >+Thêm</a>
										<?php }?>
									</label>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][title]" value="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
" placeholder="Tên dự án" maxlength="255">
									</div>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][project_manager]" value="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_manager'];?>
" placeholder="Giám đốc dự án" maxlength="255">
									</div>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][project_admin]" value="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_admin'];?>
" placeholder="Giám đốc dự án" maxlength="255">
									</div>
									<div class="col-md-1">
										<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?>
										<button class="btn btn-default" onClick="$Core.property.delete_info_agency(this,event)" tp="project" type="button" ><i class="fa fa-trash"></i></button>
										<?php }?>
									</div>
								</div>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php } else { ?>
								<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
								<div class="form-row mb-2 item">
									<label class="col-md-2 text-right col-form-label required">Dự án bán
										<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="project" >+Thêm</a>
									</label>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
][title]" value="" placeholder="Tên dự án" maxlength="255">
									</div>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
][project_manager]" value="" placeholder="Giám đốc dự án" maxlength="255">
									</div>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
][project_admin]" value="" placeholder="Admin dự án" maxlength="255">
									</div>
								</div>
							<?php }?>
						</div>
						<hr>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label required">Group Zalo CT</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="https://zalo.me/g/xxx" 
							name="group_zalo" onClick="this.select();" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['group_zalo'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['group_zalo'];
}?>">
						</div>
						<label class="col-md-2 text-right col-form-label required">Group Zalo TT</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="https://zalo.me/g/xxx" 
							name="group_zalo_lowrise" onClick="this.select();" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['group_zalo_lowrise'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['group_zalo_lowrise'];
}?>">
						</div>
					</div>
					<hr />
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label required">Spreadsheet ID</label>
						<div class="col-md-10">
							<input type="text" class="form-control required" placeholder="Spreadsheet ID" name="spreadsheetId" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId'];
}?>">
							<div class="alert alert-warning mt-2 mb-0">
								<strong>Spreadsheet ID</strong> là phần bôi đậm trong đường dẫn Gooogle Sheet. https://docs.google.com/spreadsheets/d/<strong>143xVs9lPopFSF4eJQWloDYAndMor</strong>/edit
							</div>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Status');?>
</label>
						<div class="col-md-3">
							<select class="form-control required" name="stock_status_id">
								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectSingleProperty('_STATUS',0,$_smarty_tpl->tpl_vars['more_information']->value['stock_status_id']);?>

							</select>
						</div>
						<label class="col-md-3 text-right col-form-label">Ẩn khỏi User.FH</label>
						<div class="col-md-3">
							<label class="switch">
								<input type="checkbox"<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['hide_stock_globe']) && $_smarty_tpl->tpl_vars['more_information']->value['hide_stock_globe'] == '1') {?> checked<?php }?> name="hide_stock_globe" value="1" />
								<span class="slider round"></span>
							</label>
						</div>
					</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_REPORT_TEMPLATE') {?>
				<div class="form-group form-row">
					<label class="col-xs-12 col-md-2 text-right col-form-label">Vai trò</label>
					<div class="col-xs-12 col-md-10">
						<select class="form-control iso-select2" name="permiss_role[]" multiple="true">
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_roles']->value, '_oProperty');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProperty']->value) {
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['_oProperty']->value['property_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['permiss_role']->value,$_smarty_tpl->tpl_vars['_oProperty']->value['property_id'])) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oProperty']->value['title'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</select>
					</div>
				</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_OPS_COST_CAT') {?>
					<div class="form-group form-row">
						<label class="col-xs-12 col-md-2 text-right col-form-label">Văn phòng</label>
						<div class="col-xs-12 col-md-4">
							<select Class="form-control" name="office_id">
								<option value="0">Văn phòng</option>
								<?php if (!empty($_smarty_tpl->tpl_vars['arr_offices']->value)) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_offices']->value, '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>
									<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['office_id'] == $_smarty_tpl->tpl_vars['_oI']->value['setting_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['setting_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
</option>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
							</select>
						</div>
						<label class="col-xs-12 col-md-2 text-right col-form-label">Hạng mục</label>
						<div class="col-xs-12 col-md-4">
							<select Class="form-control" name="office_cost_cat_id">
								<option value="0">Hạng mục</option>
								<?php if (!empty($_smarty_tpl->tpl_vars['arr_categories']->value)) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_categories']->value, '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>
									<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['office_cost_cat_id'] == $_smarty_tpl->tpl_vars['_oI']->value['setting_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['setting_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
</option>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
							</select>
						</div>
					</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value != '_PRICE_RANGE_SOP' && $_smarty_tpl->tpl_vars['property_type']->value != '_PRICE_RANGE_LEASING' && $_smarty_tpl->tpl_vars['property_type']->value != '_AREA_RANGE' && $_smarty_tpl->tpl_vars['property_type']->value != '_ULTILITIES') {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Mô tả</label>
					<div class="col-md-10">
						<textarea class="form-control isoTextArea" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" cols="255" placeholder="Nhập giới thiệu" data-name="intro" rows="3"><?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['intro'];
}?></textarea>
					</div>
				</div>
				<?php }?>
				
				
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_OPS_COST_CAT') {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Tồn quỹ T4/2025</label>
					<div class="col-md-10">
						<input type="text" class="form-control numberonly price-In required" placeholder="0.00" name="ms_value" 
						onClick="this.select();" value="<?php if (!empty($_smarty_tpl->tpl_vars['oneProperty']->value['ms_value'])) {
echo $_smarty_tpl->tpl_vars['oneProperty']->value['ms_value'];
}?>">
					</div>
				</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_AGENCY') {?>
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Content');?>
</label>
					<div class="col-md-10">
						<textarea class="form-control isoTextArea" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" cols="255" placeholder="Nhập giới thiệu" data-name="MOC_content" rows="3"><?php if ($_smarty_tpl->tpl_vars['property_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['MOC_content'];
}?></textarea>
					</div>
				</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_GROUP_RANGE_VHGG') {?>
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label"></label>
					<div class="col-md-10">
						<select class="form-control iso-select2" name="list_ranges[]" data-width="100%" multiple="true">
							<?php echo $_smarty_tpl->tpl_vars['html_options_range']->value;?>

						</select>
					</div>
				</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == 'CUSTOMER_STATUS') {?>
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label">Hiển thị</label>
					<div class="col-md-10">
						<input type="hidden" name="is_funnel_active" value="0" />
						<div class="d-flex gap-2 align-items-center">
							<label class="switch">
								<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_funnel_active'] == '1') {?> checked="checked"<?php }?> name="is_funnel_active" value="1">
								<span class="slider round"></span>
							</label>
							<span>Cho phép hiển thị phễu</span>
						</div>
					</div>
				</div>
				<?php }?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" onClick="save_property(this)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" _reload="<?php echo $_smarty_tpl->tpl_vars['_reload']->value;?>
" 
					property_id="<?php echo $_smarty_tpl->tpl_vars['property_id']->value;?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Save'));?>

				</button>
			</div>
		</form>
	</div>
</div>
<?php } else { ?>
<div class="table-property text-nowrap overflow-x-auto">
	<table class="table table-hover table-vertical table-striped table-responsive TableListProperty_<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
" width="100%">
		<thead><tr>
			<th class="text-center" width="5%"></th>
			<th class="text-left" width="5%">No.</th>
			<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
				<th class="text-left" width="50%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
</th>
				<th class="text-left" width="20%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</th>
				<th class="text-left" width="15%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Role');?>
</th>
				<th class="text-center">Lịch họp</th>
				<th class="text-center">Tình trạng</th>
				<th class="text-left" width="25%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
</th>
			<?php } else { ?>
				<th class="text-left" width="45%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
</th>
				<th class="text-left" width="20%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</th>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_AGENCY') {?>
				<!-- MOC -->
								<th class="text-left" width="15%">Ẩn crawl excel</th>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE' || $_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
				<th class="text-left" width="15%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Permiss');?>
</th><?php }?> 
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
					<th class="text-center" width="15%" colspan="2">Tính năng</th>
				<?php }?> 
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE') {?>
				<th class="text-center">MOC Point</th><?php }?>
				<th class="text-center">Tình trạng</th>
				<th class="text-left" width="10%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
</th>
			<?php }?>
		</tr></thead>
		<tbody>
		<?php if ($_smarty_tpl->tpl_vars['lstProperty']->value[0]['property_id'] != '') {?>
			<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstProperty']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_0_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
			<?php $_smarty_tpl->_assignInScope('property_id', $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id']);?>
			<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['more_information']);?>
			<tr class="bold" id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
">
				<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>

				</td>
				<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
</td>
				<td class="text-nowrap" data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['property_id']->value);?>

					<a href="javascript:void(0);" onclick="open_property(this)" parent_id="<?php echo $_smarty_tpl->tpl_vars['property_id']->value;?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
" property_id="0"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/add.png" width="25px" /></a>
				</td>
				<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
					<?php if (!empty($_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_code'])) {?>
						<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_code'];?>

					<?php } else { ?>
					--
					<?php }?>
				</td>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_AGENCY') {?>
				<!-- MOC -->
								<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['hide_crawl_excel']) && $_smarty_tpl->tpl_vars['more_information']->value['hide_crawl_excel'] == '1') {?> checked<?php }?> to_field="hide_crawl_excel" property_id="<?php echo $_smarty_tpl->tpl_vars['property_id']->value;?>
" value="1" /> <span class="slider round"></span>
					</label>
				</td>
				<!-- End -->
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
				<th class="text-left" width="65%">
					<?php if ($_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['for_id'] > '0') {?>
						<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['for_id']);?>

					<?php } else { ?>
					 -- 
					<?php }?>
				</th>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE' || $_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
				<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
					<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE') {?>MOC<?php } else { ?>user.fh<?php }?>" for_id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
">Phân quyền</a>
				</td>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
				<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
					--
				</td>
				<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
					--
				</td>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE') {?>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['status_moc_point'] == '1') {?> checked<?php }?> onChange="$Core.property.set_status_moc_point(this, event)" 
							property_id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
					<td class="text-center">
						<label class="switch">
							<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_calendar'] == '1') {?> checked<?php }?> onChange="$Core.property.set_show_calendar(this, event)" 
								property_id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" value="1" />
							<span class="slider round"></span>
						</label>
					</td>
				<?php }?>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.property.set_status(this, event)" 
							property_id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
					<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
						<button class="btn btn-default" onClick="open_property(this)" property_id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
						<button class="btn btn-default" onClick="delete_property(this)" property_id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
					</div>
				</td>
			</tr>
			<?php $_smarty_tpl->_assignInScope('lstChild', $_smarty_tpl->tpl_vars['clsProperty']->value->getItems($_smarty_tpl->tpl_vars['property_type']->value,$_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id']));?>
			<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[0]['property_id'] != '') {?>
				<?php
$__section_j_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_j_1_total = $__section_j_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_j'] = new Smarty_Variable(array());
if ($__section_j_1_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] <= $__section_j_1_total; $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']++){
?>
				<?php $_smarty_tpl->_assignInScope('moreInformation', $_smarty_tpl->tpl_vars['clsISO']->value->to_array_json($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['more_information']));?>
				<tr id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id'];?>
">
					<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
					<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
</td>
					<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">+&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id']);?>

						<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['image'] != '') {?>
						<span class="label label-default">Icon</span>
						<?php }?>
					</td>
					<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
						<?php if (!empty($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_code'])) {?>
							<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_code'];?>

						<?php } else { ?>
						--
						<?php }?>
					</td>
					<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
					<th class="text-left" width="65%">
						<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['for_id'] > '0') {?>
							<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['for_id']);?>

						<?php } else { ?>
						 -- 
						<?php }?>
					</th>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE' || $_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
					<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
						<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE') {?>MOC<?php } else { ?>user.fh<?php }?>" for_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id'];?>
">Phân quyền</a>
					</td>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
					<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
						<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_choose" for_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id'];?>
">Lựa chọn</a>
					</td>
					<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
						<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_sort"  for_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id'];?>
">Sắp xếp</a>
					</td>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
					<td class="text-center">
						<label class="switch">
							<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['moreInformation']->value['is_calendar'] == '1') {?> checked<?php }?> onChange="$Core.property.set_show_calendar(this, event)" 
								property_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id'];?>
" value="1"  />
							<span class="slider round"></span>
						</label>
					</td>
					<?php }?>
					<td class="text-center">
						<label class="switch">
							<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.property.set_status(this, event)" 
								property_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id'];?>
" value="1" />
							<span class="slider round"></span>
						</label>
					</td>
					<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
						<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
							<button class="btn btn-default" onClick="open_property(this)" property_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
							<button class="btn btn-default" onClick="delete_property(this)" property_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
						</div>
					</td>
				</tr>
				<?php $_smarty_tpl->_assignInScope('lstSubChild', $_smarty_tpl->tpl_vars['clsProperty']->value->getItems($_smarty_tpl->tpl_vars['property_type']->value,$_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['property_id']));?>
				<?php if (!empty($_smarty_tpl->tpl_vars['lstSubChild']->value)) {?>
					<?php
$__section_k_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstSubChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_k_2_total = $__section_k_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_k'] = new Smarty_Variable(array());
if ($__section_k_2_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] <= $__section_k_2_total; $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']++){
?>
					<tr id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id'];?>
">
						<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
						<td data-label="No." class="text-left"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] : null);?>
</td>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">++&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id']);?>
</td>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
							<?php if (!empty($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_code'])) {?>
								<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_code'];?>

							<?php } else { ?>
							--
							<?php }?>
						</td>
						<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
						<th class="text-left" width="65%">
							<?php if ($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['for_id'] > '0') {?>
								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['for_id']);?>

							<?php } else { ?>
							 -- 
							<?php }?>
						</th>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE' || $_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
						<td data-label="hân quyền">
							<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE') {?>MOC<?php } else { ?>user.fh<?php }?>" for_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id'];?>
">Phân quyền</a>
						</td>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
							<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)"v for_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id'];?>
">Lựa chọn</a>
						</td>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
							<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_sort"  for_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id'];?>
">Sắp xếp</a>
						</td>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
						<td class="text-center">
							<label class="switch">
								<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_calendar'] == '1') {?> checked<?php }?> onChange="$Core.property.set_show_calendar(this, event)" 
									property_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id'];?>
" value="1" />
								<span class="slider round"></span>
							</label>
						</td>
						<?php }?>
						<td class="text-center">
							<label class="switch">
								<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.property.set_status(this, event)" 
									property_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id'];?>
" value="1" />
								<span class="slider round"></span>
							</label>
						</td>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
							<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
								<button class="btn btn-default" onClick="open_property(this)" property_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
								<button class="btn btn-default" onClick="delete_property(this)" property_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
							</div>
						</td>
					</tr>
					<?php $_smarty_tpl->_assignInScope('list_items_4', $_smarty_tpl->tpl_vars['clsProperty']->value->getItems($_smarty_tpl->tpl_vars['property_type']->value,$_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['property_id']));?>
					<?php if (!empty($_smarty_tpl->tpl_vars['list_items_4']->value)) {?>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_items_4']->value, '_oItem4', false, NULL, 'n', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem4']->value) {
?>
						<tr id="<?php echo $_smarty_tpl->tpl_vars['_oItem4']->value['property_id'];?>
">
							<td class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
							<td><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration'] : null);?>
</td>
							<td>+++&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['_oItem4']->value['property_id'],$_smarty_tpl->tpl_vars['_oItem4']->value);?>
</td>
							<td>
								<?php if (!empty($_smarty_tpl->tpl_vars['_oItem4']->value['property_code'])) {?>
									<?php echo $_smarty_tpl->tpl_vars['_oItem4']->value['property_code'];?>

								<?php } else { ?>
								--
								<?php }?>
							</td>
							<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
							<th class="text-left" width="65%">
								<?php if ($_smarty_tpl->tpl_vars['_oItem4']->value['for_id'] > '0') {?>
									<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['_oItem4']->value['for_id']);?>

								<?php } else { ?>
								 -- 
								<?php }?>
							</th>
							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE' || $_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
							<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
								<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE') {?>MOC<?php } else { ?>user.fh<?php }?>" for_id="<?php echo $_smarty_tpl->tpl_vars['_oItem4']->value['property_id'];?>
">Phân quyền</a>
							</td>
							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
							<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
								<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_choose"  for_id="<?php echo $_smarty_tpl->tpl_vars['_oItem4']->value['property_id'];?>
">Lựa chọn</a>
							</td>
							<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
								<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_sort"  for_id="<?php echo $_smarty_tpl->tpl_vars['_oItem4']->value['property_id'];?>
">Sắp xếp</a>
							</td>
							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
							<td class="text-center">
								<label class="switch">
									<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_calendar'] == '1') {?> checked<?php }?> onChange="$Core.property.set_show_calendar(this, event)" property_id="<?php echo $_smarty_tpl->tpl_vars['_oItem4']->value['property_id'];?>
" value="1" />
									<span class="slider round"></span>
								</label>
							</td>
							<?php }?>
							<td class="text-center">
								<label class="switch">
									<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['_oItem4']->value['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.property.set_status(this, event)" 
										property_id="<?php echo $_smarty_tpl->tpl_vars['_oItem4']->value['property_id'];?>
" value="1" />
									<span class="slider round"></span>
								</label>
							</td>
							<td>
								<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
									<button class="btn btn-default" onClick="open_property(this)" property_id="<?php echo $_smarty_tpl->tpl_vars['_oItem4']->value['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
									<button class="btn btn-default" onClick="delete_property(this)" property_id="<?php echo $_smarty_tpl->tpl_vars['_oItem4']->value['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
								</div>
							</td>
						</tr>
						<?php $_smarty_tpl->_assignInScope('list_items_5', $_smarty_tpl->tpl_vars['clsProperty']->value->getItems($_smarty_tpl->tpl_vars['property_type']->value,$_smarty_tpl->tpl_vars['_oItem4']->value['property_id']));?>
						<?php if (!empty($_smarty_tpl->tpl_vars['list_items_5']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_items_5']->value, '_oItem5');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem5']->value) {
?>
							<tr id="<?php echo $_smarty_tpl->tpl_vars['list_items_5']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_m']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_m']->value['index'] : null)]['property_id'];?>
">
								<td class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
								<td><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration'] : null);?>
</td>
								<td>++++&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['list_items_5']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['property_id'],$_smarty_tpl->tpl_vars['_oItem5']->value);?>
</td>
								<td>
									<?php if (!empty($_smarty_tpl->tpl_vars['_oItem5']->value['property_code'])) {?>
										<?php echo $_smarty_tpl->tpl_vars['_oItem5']->value['property_code'];?>

									<?php } else { ?>
									--
									<?php }?>
								</td>
								<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
								<th class="text-left" width="65%">
									<?php if ($_smarty_tpl->tpl_vars['_oItem5']->value['for_id'] > '0') {?>
										<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['_oItem5']->value['for_id']);?>

									<?php } else { ?>
									 -- 
									<?php }?>
								</th>
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE' || $_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
								<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
									<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_PACKAGE') {?>MOC<?php } else { ?>user.fh<?php }?>" for_id="<?php echo $_smarty_tpl->tpl_vars['_oItem5']->value['property_id'];?>
">Phân quyền</a>
								</td>
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_ROLE') {?>
								<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
									<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_choose" 
										for_id="<?php echo $_smarty_tpl->tpl_vars['_oItem5']->value['property_id'];?>
">Lựa chọn</a>
								</td>
								<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
">
									<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_sort" 
										for_id="<?php echo $_smarty_tpl->tpl_vars['_oItem5']->value['property_id'];?>
">Sắp xếp</a>
								</td>
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['property_type']->value == '_DEPARTMENT') {?>
								<td class="text-center">
									<label class="switch">
										<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_calendar'] == '1') {?> checked<?php }?> onChange="$Core.property.set_show_calendar(this, event)" property_id="<?php echo $_smarty_tpl->tpl_vars['_oItem5']->value['property_id'];?>
" value="1" />
										<span class="slider round"></span>
									</label>
								</td>
								<?php }?>
								<td class="text-center">
									<label class="switch">
										<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['_oItem5']->value['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.property.set_status(this, event)" 
											property_id="<?php echo $_smarty_tpl->tpl_vars['_oItem5']->value['property_id'];?>
" value="1" />
										<span class="slider round"></span>
									</label>
								</td>
								<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
									<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
										<button class="btn btn-default" onClick="open_property(this, event)" property_id="<?php echo $_smarty_tpl->tpl_vars['_oItem5']->value['property_id'];?>
" 
											property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
										<button class="btn btn-default" onClick="delete_property(this, event)" property_id="<?php echo $_smarty_tpl->tpl_vars['_oItem5']->value['property_id'];?>
" 
											property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
									</div>
								</td>
							</tr>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<?php }?>
					<?php
}
}
?>
				<?php }?>
				<?php
}
}
?>
			<?php }?>
			<?php
}
}
?>
		<?php } else { ?>
			<tr>
				<td colspan="7" class="text-center">
					<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->renderHTMLNoDocument($_smarty_tpl->tpl_vars['core']->value->get_Lang('Not any records(s) here'));?>

				</td>
			</tr>
		<?php }?>
		</tbody>
	</table>
</div>
<?php }
}
}
