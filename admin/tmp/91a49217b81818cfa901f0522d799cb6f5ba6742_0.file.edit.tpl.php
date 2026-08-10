<?php
/* Smarty version 3.1.33, created on 2026-07-30 10:25:54
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/edit.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ac44205cb42_67903555',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '91a49217b81818cfa901f0522d799cb6f5ba6742' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/edit.tpl',
      1 => 1784691719,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ac44205cb42_67903555 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
	var project_id = '<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
';
	var map_la = '<?php echo $_smarty_tpl->tpl_vars['more_information']->value['map_la'];?>
';
	var map_lo = '<?php echo $_smarty_tpl->tpl_vars['more_information']->value['map_lo'];?>
';
<?php echo '</script'; ?>
>
<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default ui-breadcrumb">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>

					<span class="ui-breadcrumb__item">Dự án</span>
				</a>
			</div>
			<button type="button" onClick="$Core.project.open_setting_table_update(this, event)"
				data-project-id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"
				class="btn btn-default ui-breadcrumb"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('cloud-upload mr-3');?>
<span
					class="ui-breadcrumb__item">Tạo bảng hàng thấp tầng</span></button>
		</div>
	</div>
	<div class="ui-title-bar__main-group">
		<div class="ui-title-bar__heading-group">
			<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {?>
				<h1 class="ui-title-bar__title w-100"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
</h1>
				<div class="action-bar__item action-bar__item--link-container"></div>
			<?php } else { ?>
				<h1 class="ui-title-bar__title">Thêm dự án</h1>
			<?php }?>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<form class="form-upload d-none" method="post" action="" enctype="multipart/form-data">
	<input type="file" name="attachment" class="selectFile" />
</form>
<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="form-row">
			<div class="col-md-8 col-xs-12">
				<div class="ui-layout__item">
					<div class="ui-card">
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group mb-2 form-row">
									<div class="col-md-4">
										<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</label>
										<input type="text" class="form-control required" name="iso-code"
											value="<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneItem']->value['code'];
}?>" required maxlength="255"
											placeholder="Tên viết tắt dự án" />
									</div>
									<div class="col-md-4">
										<label class="col-form-label">Loại hình<span class="text-red">*</span></label>
										<select name="block_type[]" multiple="true"
											class="form-control iso-select2 required">
											<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_BLOCK_TYPE',$_smarty_tpl->tpl_vars['block_type_arrs']->value,'Loại hình');?>

										</select>
									</div>
									<div class="col-md-4">
										<label class="col-form-label">Chủ đầu tư</label>
										<select class="form-control iso-select2" name="investor_id">
											<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_INVESTOR',$_smarty_tpl->tpl_vars['more_information']->value['investor_id']);?>

										</select>
									</div>
								</div>
								<div class="form-group form-row">
									<div class="col-12 col-md-4">
										<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Title');?>
</label>
										<input type="text" class="form-control required" name="iso-title"
											value="<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];
}?>" required maxlength="255"
											placeholder="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Title');?>
" />
									</div>
									<div class="col-12 col-md-4">
										<label class="col-form-label">Tỉnh</label>
										<input type="text" class="form-control required" name="project_area"
											value="<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['project_area'];
}?>" required
											maxlength="255" placeholder="Khu vực" />
									</div>
									<div class="col-12 col-md-4">
										<label class="col-form-label">Khu vực</label>
										<select class="form-control iso-select2" name="iso-area_id">
											<?php echo $_smarty_tpl->tpl_vars['clsSetting']->value->getOptionOrigin('_AREA',0,$_smarty_tpl->tpl_vars['oneItem']->value['area_id']);?>

										</select>
									</div>
								</div>
								<div class="form-group form-row">
									<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Address');?>
</label>
									<input type="text" class="form-control required" name="address"
										value="<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['address'];
}?>" required
										maxlength="255" placeholder="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Address');?>
" />
								</div>


								<div class="form-group form-row">
									<div class="col-md-6">
										<label class="col-form-label">Link</label>
										<input class="form-control" name="iso-link"
											value="<?php if (!empty($_smarty_tpl->tpl_vars['oneItem']->value['link'])) {
echo $_smarty_tpl->tpl_vars['oneItem']->value['link'];
} else {
echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getLink($_smarty_tpl->tpl_vars['pvalTable']->value);
}?>"
											maxlength="255" />
									</div>
									<div class="col-md-6">
										<div class="d-flex align-items-center justify-content-between">
											<label class="col-form-label">Link Tiles</label>
											<div class="d-flex gap-2 align-items-center">
												<div class="d-flex gap-2 align-items-center justify-content-between">
													<input type="hidden" name="is_tiles" value="0" />
													<label class="switch small">
														<input type="checkbox" name="is_tiles"
															<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_tiles'] == '1') {?> checked<?php }?>
															value="1">
														<span class="slider round"></span>
													</label>
													<Span>Sử dụng tiles</span>
												</div>
												<div class="d-flex gap-2 align-items-center">
													<input type="hidden" name="is_map_tiles" value="0" />
													<label class="switch small">
														<input type="checkbox" name="is_map_tiles"
															<?php if ($_smarty_tpl->tpl_vars['more_information']->value['is_map_tiles'] == '1') {?> checked<?php }?>
															value="1">
														<span class="slider round"></span>
													</label>
													<Span>Map tiles</span>
												</div>
											</div>
										</div>
										<input name="tiles_link" class="form-control"
											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['tiles_link'];?>
" maxlength="255" />
									</div>
								</div>
								<div class="form-group form-row">
									<div class="col-md-3">
										<label class="col-form-label">Max Zoom</label>
										<input type="number" class="form-control" name="max_zoom"
											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['max_zoom'];?>
" maxlength="255" />
									</div>
									<div class="col-md-3">
										<label class="col-form-label">Vị trí trung tâm</label>
										<input class="form-control" name="center_point"
											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['center_point'];?>
" maxlength="255" />
									</div>
									<div class="col-md-3">
										<label class="col-form-label">Vùng bound</label>
										<input class="form-control" name="max_bound"
											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['max_bound'];?>
" maxlength="255" />
									</div>
									<div class="col-md-3">
										<label class="col-form-label">TMS</label>
										<select class="form-control" name="tms_enable" value="">
											<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['tms_enable'] == '0') {?> selected<?php }?> value="0">NO
												</option>
												<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['tms_enable'] == '1') {?> selected<?php }?> value="1">
													YES</option>
										</select>
									</div>
								</div>
								<hr />
								<div class="form-group form-row">
									<div class="col-md-6">
										<label class="col-form-label">VR360</label>
										<input class="form-control" name="vr_link" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['vr_link'];?>
"
											maxlength="255" />
									</div>
									<div class="col-md-6">
										<label class="col-form-label">Nguồn VR360</label>
										<input class="form-control" name="vr_source"
											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['vr_source'];?>
" maxlength="255" />
									</div>
								</div>
								<hr />
								<fieldset>
									<legend>Admin</legend>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_groups']->value, '_oI', false, '_oG');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oG']->value => $_smarty_tpl->tpl_vars['_oI']->value) {
?>
										<?php $_smarty_tpl->_assignInScope('_oField', $_smarty_tpl->tpl_vars['_oI']->value['field']);?>
										<div class="form-row mb-2">
											<label class="col-form-label text-right col-md-2"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
</label>
											<div class="col-md-4">
												<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['field'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value[$_smarty_tpl->tpl_vars['_oField']->value];?>
"
													class="form-control" />
											</div>
											<div class="col-md-6">
												<select class="form-control iso-select2" name="list_admin_id[<?php echo $_smarty_tpl->tpl_vars['_oG']->value;?>
][]"
													multiple="true">
													<?php if (!empty($_smarty_tpl->tpl_vars['list_admins']->value)) {?>
														<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_admins']->value, '_admin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_admin']->value) {
?>
															<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['_oI']->value['list_admins'],$_smarty_tpl->tpl_vars['_admin']->value['profile_id'])) {?>
																selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_admin']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_admin']->value['full_name'];?>

																</option>
															<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
														<?php }?>
												</select>
											</div>
										</div>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</fieldset>
								<div class="form-group">
									<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Description');?>
</label>
									<textarea class="form-control" cols="255" rows="4"
										name="iso-intro"><?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneItem']->value['intro'];
}?></textarea>
								</div>
								<div class="form-group">
									<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Content');?>
</label>
									<textarea class="form-control isoTextArea" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
"
										name="iso-content"><?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneItem']->value['content'];
}?></textarea>
								</div>
								<div class="row">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_meta_fields']->value, '_oF', false, 'field', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['field']->value => $_smarty_tpl->tpl_vars['_oF']->value) {
?>
										<div class="col-xs-12 col-md-6">
											<div class="form-group">
												<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['_oF']->value['title'];?>
.</label>
												<input type="text" placeholder="<?php echo $_smarty_tpl->tpl_vars['_oF']->value['placeholder'];?>
"
													name="more_info_field[<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
]"
													class="form-control<?php if ($_smarty_tpl->tpl_vars['field']->value == 'dg_price') {?> price-In numberonly<?php }?>"
													value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value[$_smarty_tpl->tpl_vars['field']->value])) {
echo $_smarty_tpl->tpl_vars['more_information']->value[$_smarty_tpl->tpl_vars['field']->value];
}?>" />
											</div>
										</div>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</div>
							</div>
						</div>
					</div>
					<!-- Section -->
					<div class="ui-card">
						<div class="ui-card__section">
							<header class="ui-stack ui-stack--wrap mb-3">
								<h2 class="ui-stack-item ui-stack-item--fill ui-heading">0. Tổng quan</h2>
								<div class="ui-stack-item">
									<button type="button" class="btn btn-default" onClick="add_property(this, event)"
										_openFrom="_project" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" _holderG="_attrs">+ Thêm</button>
								</div>
							</header>
							<div class="ui-type-container">
								<table width="100%" class="table table-vertical mb-0 table-stripped">
									<thead>
										<tr>
											<th width="5%">No.</th>
											<th width="35%">Tên thuộc tính</th>
											<th width="55%">Giá trị</th>
											<th width="5%"></th>
										</tr>
									</thead>
									<tbody class="tbody_attrs connectedSortable ui-sortable">
										<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['attrs'])) {?>
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['attrs'], '_Item', false, 'uid', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['uid']->value => $_smarty_tpl->tpl_vars['_Item']->value) {
?>
												<tr id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="tr_attrs">
													<td class="text-center">
														<div class="mySortableHandler"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('arrows');?>
</div>
													</td>
													<td class="text-left">
														<input class="form-control title_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
															name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][title]" placeholder="Nhập tiêu đề"
															value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['title'];?>
" type="text" />
													</td>
													<td class="text-left">
														<input class="form-control content_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
															name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][content]" placeholder="Nhập giá trị"
															value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['content'];?>
" type="text" />
													</td>
													<td class="text-center">
														<a title="Xóa" href="javascript:void(0);" class="btn btn-default"
															uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
															onClick="delete_property(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
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
												<td class="text-center">
													<div class="mySortableHandler"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('arrows');?>
</div>
												</td>
												<td class="text-left">
													<input class="form-control title_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
														name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][title]" placeholder="Nhập tiêu đề"
														type="text" />
												</td>
												<td class="text-left">
													<input class="form-control content_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
														name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][content]" placeholder="Nhập giá trị"
														type="text" />
												</td>
												<td class="text-center">
													<a title="Xóa" href="javascript:void(0);" class="btn btn-default"
														uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
														onClick="delete_property(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</a>
												</td>
											</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Section -->
					<div class="ui-card">
						<div class="ui-card__section d-none">
							<header class="ui-stack ui-stack--wrap mb-3">
								<h2 class="ui-stack-item ui-stack-item--fill ui-heading">1. Thuộc tính</h2>
								<div class="ui-stack-item">
									<button type="button" class="btn btn-default add_property"
										onClick="add_property(this, event)" _openFrom="_project"
										project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
">+ Thêm</button>
								</div>
							</header>
							<div class="ui-type-container">
								<table width="100%" class="table table-vertical mb-0 table-stripped">
									<thead>
										<tr>
											<th width="5%">No.</th>
											<th width="35%">Tên thuộc tính</th>
											<th width="55%">Giá trị</th>
											<th>HOT</th>
											<th width="5%"></th>
										</tr>
									</thead>
									<tbody class="tbody_property no_group connectedSortable ui-sortable">
										<?php if (!empty($_smarty_tpl->tpl_vars['list_props']->value)) {?>
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_props']->value, '_Item', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_Item']->value) {
?>
												<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
												<tr id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="tr_property no_group">
													<td class="text-center">
														<div class="mySortableHandler"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('arrows');?>
</div>
													</td>
													<td class="text-left">
														<input type="hidden" name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][id]"
															value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['id'];?>
" />
														<input type="hidden" name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][lock]"
															value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['lock'];?>
" />
														<input class="form-control title_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
															name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][title]" placeholder="Nhập tiêu đề"
															value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['title'];?>
" type="text" />
													</td>
													<td class="text-left">
														<input class="form-control content_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
															name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][content]" placeholder="Nhập giá trị"
															value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['content'];?>
" type="text" />
													</td>
													<td class="text-center">
														<label class="switch">
															<input type="checkbox" name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][is_hot]" value="1"
																<?php if ($_smarty_tpl->tpl_vars['_Item']->value['is_hot'] == '1') {?> checked<?php }?>>
															<span class="slider round"></span>
														</label>
													</td>
													<td class="text-center">
														<div class="btn-group">
															<button type="button" class="btn btn-xs btn-default dropdown-toggle"
																data-toggle="dropdown">
																<i class="icon-cog"></i>
																<span class="caret"></span>
															</button>
															<ul class="dropdown-menu"
																style="right:0px !important;left:auto; min-width:130px">
																<li><a title="Tải File" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
																		onClick="select_file(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Tải File');?>
</a>
																</li>
																<li><a title="Xóa" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
																		onClick="delete_property(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash','Xóa');?>
</a>
																</li>
															</ul>
														</div>
													</td>
												</tr>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										<?php } else { ?>
											<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
											<tr id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="tr_property no_group">
												<td class="text-center">
													<div class="mySortableHandler"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('arrows');?>
</div>
												</td>
												<td class="text-left">
													<input type="hidden" name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][id]" value="0" />
													<input type="hidden" name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][lock]" value="0" />
													<input class="form-control title_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
														name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][title]" placeholder="Nhập tiêu đề"
														type="text" />
												</td>
												<td class="text-left">
													<input class="form-control content_field_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
														name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][content]" placeholder="Nhập giá trị"
														type="text" />
												</td>
												<td class="text-center">
													<label class="switch">
														<input type="checkbox" name="properties[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][is_hot]" value="1">
														<span class="slider round"></span>
													</label>
												</td>
												<td class="text-center">
													<div class="btn-group">
														<button class="btn btn-xs btn-default dropdown-toggle" type="button"
															data-toggle="dropdown">
															<i class="icon-cog"></i>
															<span class="caret"></span>
														</button>
														<ul class="dropdown-menu"
															style="right:0px !important;left:auto; min-width:130px">
															<li><a title="Tải File" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
																	onClick="select_file(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Tải File');?>
</a>
															</li>
															<li><a title="Xóa" href="javascript:void(0);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"
																	onClick="delete_property(this, event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash','Xóa');?>
</a>
															</li>
														</ul>
													</div>
												</td>
											</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="ui-card d-none">
						<div class="ui-card__section">
							<header class="ui-stack ui-stack--wrap mb-3">
								<h2 class="ui-stack-item ui-stack-item--fill ui-heading">Banner căn hộ độc quyền</h2>
								<div class="ui-stack-item">
									<button type="button" class="ui-button ui-button--link" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"
										banner_stock_id=""
										onClick="$Core.global.project.open_banner(this,event)">Thêm</button>
								</div>
							</header>
							<div class="ui-type-container">
								<div class="holderBannerStock">
									Loading...
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card">
						<div class="ui-card__section">
							<header class="ui-stack ui-stack--wrap mb-3">
								<h2 class="ui-stack-item ui-stack-item--fill ui-heading">5. Tiện ích dự án</h2>
								<div class="ui-stack-item">
									<button type="button" class="ui-button ui-button--link" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"
										utilities_id="" onClick="$Core.utilities.open(this,event)">Thêm</button>
								</div>
							</header>
							<div class="ui-type-container">
								<div class="holderUtilities">
									Loading...
								</div>
							</div>
						</div>
					</div>
					<div class="box_list_sop">
						<?php if (!empty($_smarty_tpl->tpl_vars['list_sops']->value)) {?>
							<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_sops']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_i']->value['first'] = ($__section_i_0_iteration === 1);
$_smarty_tpl->tpl_vars['__smarty_section_i']->value['last'] = ($__section_i_0_iteration === $__section_i_0_total);
?>
								<?php $_smarty_tpl->_assignInScope('_sop_Id', $_smarty_tpl->tpl_vars['list_sops']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id']);?>
								<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['list_sops']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['more_information']);?>
								<div
									class="ui-card mt-half item_sop <?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
 <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['first'] : null)) {?>item_first<?php }?> <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['last'] : null)) {?>item_last<?php }?>">
									<header class="ui-card__header">
										<div class="ui-stack d-flex align-items-center justify-content-between ui-stack--wrap">
											<div class="ui-stack-item">
												<div class="d-flex align-items-center gap-2">
													<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['_more_information']->value['title'];?>
</h2>
													<a href="javascript:void(0);"
														class="text-black btn btn-icon btn-xs btn-default"
														onclick="$Core.project.open_sop(this, event)" sop_id="<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
"
														project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" data-toggle="tooltip"
														title="Nhấn lưu trước khi thay đổi"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('cog');?>
</a>
													<a href="javascript:void(0);"
														class="text-black btn btn-icon btn-xs btn-default"
														onclick="$Core.project.delete_sop(this)" sop_id="<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
"
														project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" data-toggle="tooltip"
														title="Nhấn lưu trước khi thay đổi"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</a>

													<a href="javascript:void(0);" onclick="$Core.project.move_sop(this, 'up')"
														class="text-black btn btn-icon btn-xs btn-default btn_moveup <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['first'] : null)) {?>d-none<?php }?>"
														sop_id="<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" data-toggle="tooltip"
														title="Nhấn lưu trước khi thay đổi vị trí"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('arrow-up');?>
</a>
													<a href="javascript:void(0);" onclick="$Core.project.move_sop(this, 'down')"
														class="text-black btn btn-icon btn-xs btn-default btn_movedown <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['last'] : null)) {?>d-none<?php }?>"
														sop_id="<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" data-toggle="tooltip"
														title="Nhấn lưu trước khi thay đổi vị trí"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('arrow-down');?>
</a>
												</div>
											</div>
											<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['field_type'] == '_list') {?>
												<div class="ui-stack-item">
													<button type="button" onClick="$Core.project.open_sop_item(this)"
														sop_item_id="0" sop_id="<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"
														class="ui-button ui-button--link"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
</button>
												</div>
											<?php }?>
										</div>
									</header>
									<div class="ui-card__section">
										<div class="ui-type-container">
											<div class="form-group">
												<label class="col-form-label col-md-2 text-right">&nbsp;</label>
												<div class="col-md-10">
													<div class="row">
														<div class="col-12 col-md-6">
															<div class="icheck-primary">
																<input type="checkbox" name="sop[<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
][is_image]"
																	value="1"
																	<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['is_image'] == '1') {?>checked<?php }?>
																	id="checkbox_sop_is_image_<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
" />
																<label for="checkbox_sop_is_image_<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
"> Cho phép hiển thị
																	hình ảnh.</label>
															</div>
														</div>
														<div class="col-12 col-md-6">
															<div class="icheck-primary">
																<input type="checkbox" name="sop[<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
][is_background]"
																	value="1"
																	<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['is_background'] == '1') {?>checked<?php }?>
																	id="checkbox_sop_is_background_<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
" />
																<label for="checkbox_sop_is_background_<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
"> Đặt hình ảnh
																	làm ảnh nền.</label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-form-label col-md-2 text-right">Màu background</label>
												<div class="col-md-10">
													<div class="input-group">
														<input type="color" name="sop[<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
][background_color]"
															value="<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['background_color'];?>
">
													</div>
												</div>
											</div>
											<div class="form-group">
												<label
													class="col-form-label col-md-2 text-right"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Image');?>
</label>
												<div class="col-md-5">
													<div class="input-group">
														<input type="text" class="form-control" name="sop[<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
][image]"
															placeholder="Chọn hình ảnh làm icon"
															id="isoman_url_sop_image_<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
"
															value="<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['image'];?>
">
														<div class="input-group-btn">
															<button class="btn btn-default ajOpenDialog"
																isoman_for_id="sop_image_<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
"
																isoman_val="<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['image'];?>
"
																isoman_name="sop_image_<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
"
																style="padding:9px 10px 9px"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</button>
														</div>
													</div>
												</div>
												<label class=" col-form-label col-md-2 text-right">Vị trí ảnh</label>
												<div class="col-md-3">
													<select class="form-control" name="sop[<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
][position]">
														<option <?php if ($_smarty_tpl->tpl_vars['_more_information']->value['position'] == 'left') {?>selected<?php }?>
															value="left">Trái</option>
														<option <?php if ($_smarty_tpl->tpl_vars['_more_information']->value['position'] == 'right') {?>selected<?php }?>
															value="right">Phải</option>
														<option
															<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['position'] == 'center') {?>selected<?php }?>
															value="center">Giữa</option>
													</select>
												</div>
										</div>
										<div class="form-group lines-bar">
											<label class="col-form-label col-md-2 text-right">Nội dung</label>
											<div class="col-md-10">
												<textarea class="isoTextArea form-control"
													name="sop[<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
][content]" id="mce_sop_<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
" cols="255"
													rows="10"><?php echo $_smarty_tpl->tpl_vars['_more_information']->value['content'];?>
</textarea>
											</div>
										</div>
										<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['field_type'] != '_textarea') {?>
										<div class="infobox">
											<b>Ghi chú</b><br />
											Mỗi một block sẽ bao gồm một danh sách các Item. Click <strong>Thêm
												mới</strong>
											để bắt đầu thêm mới.
										</div>
										<div class=" holder_list_sop holder_list_sop_<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
"
											project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" sop_id="<?php echo $_smarty_tpl->tpl_vars['_sop_Id']->value;?>
">
											Loading...
										</div>
										<?php }?>
									</div>
								</div>
							</div>
							<?php
}
}
?>
							<?php }?>
						</div>
						<div class="d-flex align-items-center py-3">
							<button type="button" class="btn btn-default" onClick="$Core.project.open_sop(this, event)"
								sop_id="0"
								project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew'));?>
</button>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="ui-card">
						<div class="ui-card__section">
							<div class="form-group">
								<div class="custom-radio-wrapper core-radio-custom">
									<label class="">
										<input
											<?php if ($_smarty_tpl->tpl_vars['more_information']->value['project_has_block'] == '1' || !isset($_smarty_tpl->tpl_vars['more_information']->value['project_has_block']) || $_smarty_tpl->tpl_vars['pvalTable']->value == '0') {?>
											checked="checked" <?php }?> name="project_has_block" value="1" type="radio">
										<span class="custom-radio custom-icon"></span>
									</label> Có phân khu
								</div>
							</div>
							<div class="form-group">
								<div class="custom-radio-wrapper core-radio-custom">
									<label class="">
										<input<?php if ($_smarty_tpl->tpl_vars['more_information']->value['project_has_block'] == '0' && $_smarty_tpl->tpl_vars['pvalTable']->value > '0') {?>
											checked="checked" <?php }?> name="project_has_block" value="0" type="radio">
											<span class="custom-radio custom-icon"></span>
									</label> Không có phân khu
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading">Menu thông tin dự án</h2>
						</header>
						<div class="ui-card__section">
							<div class="form-row">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_category_menu']->value, '_oCat');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oCat']->value) {
?>
								<div class="col-xs-6">
									<div class="form-group">
										<div class="checkbox">
											<input <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oCat']->value['property_id'],$_smarty_tpl->tpl_vars['project_cat_menu']->value)) {?>
												checked="checked" <?php }?> name="project_cat_menu[]"
												value="<?php echo $_smarty_tpl->tpl_vars['_oCat']->value['property_id'];?>
" type="checkbox" id="<?php echo $_smarty_tpl->tpl_vars['_oCat']->value['property_id'];?>
">
											<label for="<?php echo $_smarty_tpl->tpl_vars['_oCat']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oCat']->value['title'];?>
</label>
										</div>
									</div>
								</div>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</div>
						</div>
					</div>
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Image');?>
</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div id="article-image-drop" class="article-image-drop">
									<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive">
										<input type="hidden" id="isoman_hidden_image" name="isoman_url_image"
											value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['image'];?>
" />
										<img class="aspect-ratio__content" id="isoman_show_image"
											src="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['image'];?>
">
									</div>
									<div class="clearfix"></div>
									<div class="ui-stack ui-stack--wrap">
										<div class="ui-stack-item ui-stack-item--fill">
											<button type="button" class="ui-button btn--link ajOpenDialog"
												isoman_for_id="image" isoman_val="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['image'];?>
"
												isoman_name="image"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Change');?>
</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Logo');?>
</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div id="article-image-drop" class="article-image-drop">
									<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive">
										<input type="hidden" id="isoman_hidden_logo" name="isoman_url_logo"
											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['logo'];?>
" />
										<img class="aspect-ratio__content" id="isoman_show_logo"
											src="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['logo'];?>
">
									</div>
									<div class="clearfix"></div>
									<div class="ui-stack ui-stack--wrap">
										<div class="ui-stack-item ui-stack-item--fill">
											<button type="button" class="ui-button btn--link ajOpenDialog"
												isoman_for_id="logo" isoman_val="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['logo'];?>
"
												isoman_name="logo"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Change');?>
</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Banner');?>
</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div id="article-image-drop" class="article-image-drop">
									<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive">
										<input type="hidden" id="isoman_hidden_banner" name="isoman_url_banner"
											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['banner'];?>
" />
										<img class="aspect-ratio__content" id="isoman_show_banner"
											src="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['banner'];?>
">
									</div>
									<div class="clearfix"></div>
									<div class="ui-stack ui-stack--wrap">
										<div class="ui-stack-item ui-stack-item--fill">
											<button type="button" class="ui-button btn--link ajOpenDialog"
												isoman_for_id="banner" isoman_val="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['banner'];?>
"
												isoman_name="banner"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Change');?>
</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading">Mặt bằng tổng thể</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div id="article-image-drop" class="article-image-drop">
									<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive">
										<input type="hidden" id="isoman_hidden_layout" name="isoman_url_layout"
											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['layout'];?>
" />
										<img class="aspect-ratio__content" id="isoman_show_layout"
											src="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['layout'];?>
">
									</div>
									<div class="clearfix"></div>
									<div class="ui-stack ui-stack--wrap">
										<div class="ui-stack-item ui-stack-item--fill">
											<button type="button" class="ui-button btn--link ajOpenDialog"
												isoman_for_id="layout" isoman_val="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['layout'];?>
"
												isoman_name="layout"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Change');?>
</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="ui-card__section">
							<h3 class="ui-heading">Phân khu map</h3>
							<div class="clearfix"></div>
							<div class="form-row mb-2">
								<div class="col-xs-6">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input
												<?php if ($_smarty_tpl->tpl_vars['more_information']->value['has_block'] == '1' || !isset($_smarty_tpl->tpl_vars['more_information']->value['has_block']) || $_smarty_tpl->tpl_vars['pvalTable']->value == '0') {?>
												checked="checked" <?php }?> name="has_block" value="1" type="radio"> <span
												class="custom-radio custom-icon"></span>
										</label> Có
									</div>
								</div>
								<div class="col-xs-6">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input<?php if ($_smarty_tpl->tpl_vars['more_information']->value['has_block'] == '0' && $_smarty_tpl->tpl_vars['pvalTable']->value > '0') {?>
												checked="checked" <?php }?> name="has_block" value="0" type="radio">
												<span class="custom-radio custom-icon"></span>
										</label> Không
									</div>
								</div>
							</div>
						</div>
						<div class="ui-card__section">
							<h3 class="ui-heading">Hiển thị trên domain</h3>
							<div class="clearfix"></div>
							<div class="form-row">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_domains']->value, 'domain', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['domain']->value) {
?>
								<div class="col-xs-6">
									<div class="form-group">
										<div class="custom-checkbox-wrapper">
											<label class="">
												<input name="site_manager_ids[]" value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" type="checkbox"
													<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['key']->value,$_smarty_tpl->tpl_vars['more_information']->value['site_manager_ids'])) {?>
													checked="checked" <?php }?> />
												<span class="custom-checkbox"></span>
												<?php echo $_smarty_tpl->tpl_vars['domain']->value['title'];?>

											</label>
										</div>
									</div>
								</div>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</div>
						</div>
						<div class="ui-card__section">
							<h3 class="ui-heading mb-2">Nhận Booking</h3>
							<div class="clearfix"></div>
							<div class="form-row mb-2">
								<div class="col-xs-6">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['is_booking']) && $_smarty_tpl->tpl_vars['more_information']->value['is_booking'] == '1') {?>
												checked="checked" <?php }?> name="is_booking" value="1" type="radio"> <span
													class="custom-radio custom-icon"></span>
										</label> Có
									</div>
								</div>
								<div class="col-xs-6">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['is_booking']) && $_smarty_tpl->tpl_vars['more_information']->value['is_booking'] == '0') {?>
												checked="checked" <?php }?> name="is_booking" value="0" type="radio">
												<span class="custom-radio custom-icon"></span>
										</label> Không
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label mr-3">Admin dự án</label>
								<select data-placeholder="Admin dự án" multiple="true" name="project_admins[]"
									class="form-control iso-select2">
									<option value="0">--Chọn--</option>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_profile']->value, '_oProfile', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProfile']->value) {
?>
									<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['arr_project_admins']->value,$_smarty_tpl->tpl_vars['_oProfile']->value['profile_id'])) {?>
										selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['full_name'];?>
</option>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</select>
							</div>
						</div>
						<div class="ui-card__section">
							<h3 class="ui-heading">Màu sắc</h3>
							<div class="form-row">
								<div class="col-xs-6">
									<label class="col-form-label">Màu nền</label>
									<input type="color" name="bgcolor" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['bgcolor'];?>
"
										class="form-control" />
								</div>
								<div class="col-xs-6">
									<label class="col-form-label">Màu chữ</label>
									<input type="color" name="textcolor" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['textcolor'];?>
"
										class="form-control" />
								</div>
							</div>
						</div>
						<div class="ui-card__section">
							<h3 class="ui-heading mb-3">Cấu hình cột hiển thị bảng hàng tìm kiếm</h3>
							<div class="form-row">
								<div class="col-xs-6">
									<button class="btn btn-default w-100"
										onClick="$Core.project.open_config_column(this,event)" type="button"
										block_type="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
"
										project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
">Cao tầng</button>
								</div>
								<div class="col-xs-6">
									<button class="btn btn-default w-100"
										onClick="$Core.project.open_config_column(this,event)" type="button"
										block_type="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
"
										project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
">Thấp tầng</button>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card">
						<div class="ui-card__section">
							<header class="ui-card__header d-flex align-items-center justify-content-between">
								<h2 class="ui-heading">Phân khu</h2>
								<div class="ui-stack-item">
									<button class="ui-button ui-button--link" onClick="open_block(this, event)"
										project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" block_id="0">Thêm</button>
								</div>
							</header>
							<div class="ui-card__section" style="max-height: 400px;overflow-y: auto">
								<div class="holderBlock ui-type-container">
									<div class="p-5 text-center">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('map_project');?>

		</div>
		<div class="ui-page-actions ui-page-actions--has-secondary"
			style="position: sticky;bottom: 0;z-index: 1;background: #f4f6f8">
			<input value="Update" name="submit" type="hidden">
			<div class="ui-page-actions__container">
				<div class="ui-page-actions__actions ui-page-actions__actions--secondary">
					<div class="ui-page-actions__button-group">
						<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {?><a class="btn btn-warning" onClick="delete_globe(this)" clsTable="Slide"
							pval_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" pkey="<?php echo $_smarty_tpl->tpl_vars['pkeyTable']->value;?>
"
							return_url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&message=DeletedSuccess"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Delete');?>
</a><?php }?>
					</div>
				</div>
				<div class="ui-page-actions__actions ui-page-actions__actions--primary">
					<div class="ui-page-actions__button-group">
						<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value == '0') {?>
						<a class="btn btn-default"
							href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Calcel');?>
</a>
						<?php }?>
						<?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['saveList']->value;?>

					</div>
				</div>
			</div>
		</div>
</form>
<?php echo '<script'; ?>
>
	var html_select_field_highfloor = `<?php echo $_smarty_tpl->tpl_vars['html_select_field_highfloor']->value;?>
`;
	var html_select_field_lowfloor = `<?php echo $_smarty_tpl->tpl_vars['html_select_field_lowfloor']->value;?>
`;
	const modal_icon = `<?php echo $_smarty_tpl->tpl_vars['modal_icon']->value;?>
`
<?php echo '</script'; ?>
>

<style type="text/css">
	.text-upper {
		text-transform: uppercase;
	}
</style>
	<?php echo '<script'; ?>
 type="text/javascript">
		$(function () {
			$Core.global.project.loadListBannerStock(project_id, {});
			if ($('.ui-sortable').length) {
				$('.ui-sortable').sortable({
					connectWith: ".connectedSortable",
					handle: ".mySortableHandler",
					beforeStop: function (event, ui) {
						var a = ui.item.attr('id'),
								b = $(ui.placeholder).parent('tbody');
						if (b.hasClass('is_group')) {
							var _group_id = b.attr('id');
							ui.item.addClass(_group_id).addClass('is_group').removeClass('no_group').attr('group_id', _group_id);
							$('.title_field_' + a).attr('name', 'groups[' + _group_id + '][' + a +'][title]');
							$('.link_field_' + a).attr('name', 'groups[' + _group_id + '][' + a +'][link]');
						} else {
							var _group_id = ui.item.attr('group_id');
							ui.item.removeClass(_group_id).removeClass('is_group').addClass('no_group')
									.removeAttr('group_id');
							$('.title_field_' + a).attr('name', 'properties[' + a + '][title]');
							$('.link_field_' + a).attr('name', 'properties[' + a + '][link]');
						}
					}
				}).disableSelection();
			}
		})
	<?php echo '</script'; ?>
>
<?php }
}
