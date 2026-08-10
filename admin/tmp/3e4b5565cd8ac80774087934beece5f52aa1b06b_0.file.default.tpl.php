<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:43:49
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/stock/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ad685212084_10230880',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3e4b5565cd8ac80774087934beece5f52aa1b06b' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/stock/default.tpl',
      1 => 1784691756,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ad685212084_10230880 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="ui-title-bar-container ui-title-bar-container--full-width">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title w-100"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Qũy Căn Hộ Dự Án');?>
</h1>

				<p class="type--subdued"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Quản lý toàn bộ Qũy Căn Hộ Dự Án có trong Hệ Thống');?>
</p>

			</div>

		</div>

		<div class="action-bar">

			<div class="ui-title-bar__mobile-primary-actions">

				<div class="ui-title-bar__actions">

					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=project&act=edit&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="ui-button ui-button--transparent ui-title-bar__action mr-2" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Project'));?>
</a>

					<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>

					<a href="javascript:void(0)" onClick="$Core.stock.open_import_lowfloor(this, event)" stock_type="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
" class="ui-button ui-button--transparent ui-title-bar__action">Craw bảng hàng</a>

					<?php } elseif ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_STOCK_TYPE_LEASING')) {?>

					<a href="javascript:void(0)" onClick="$Core.stock.open_import_leasing(this, event)" stock_type="<?php echo @constant('_STOCK_TYPE_LEASING');?>
" class="ui-button ui-button--transparent ui-title-bar__action">Craw bảng hàng</a>

					<?php }?>

				</div>

			</div>

		</div>

	</div>

</div>

<div class="ui-layout ui-layout--full-width">

	<div class="ui-layout__sections"><div class="ui-layout__section">

		<div class="ui-layout__item"><div class="ui-card">

			<div class="next-tab__container">

				<ul class="next-tab__list filter-tab-list">

					<li class="filter-tab-item" data-tab-index="1">

						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Qũy Căn Hộ Dự Án');?>
</a>

					</li>

				</ul>

			</div>

			<div class="ui-card__section has-bulk-actions pages">

				<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

				<form class="d-none" method="post" enctype="multipart/form-data">

					<input type="file" name="layout_file" id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onChange="$Core.stock.layout_upload_file(this, event)" />

				</form>

				<form method="post" enctype="multipart/form-data">

					<div id="admincp_stat" class="block w-100 my-2">

						<div class="content stats-me w-100">

							<div class="stat-item" status="-1">

								<div class="item-outer">

									<div class="item-icon"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('compass');?>
</div>

									<div class="item-info">

										<div class="item-number"><?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
</div>

										<div class="item-text">Tổng căn/nhà</div>

									</div>

								</div>

							</div>

							<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['arrStatus']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

							<div class="stat-item" status="1">

								<div class="item-outer">

									<div class="item-icon"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('gavel');?>
</div>

									<div class="item-info">

										<div class="item-number"><?php echo $_smarty_tpl->tpl_vars['arrStatus']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['total_stock'];?>
</div>

										<div class="item-text"><?php echo $_smarty_tpl->tpl_vars['arrStatus']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'];?>
</div>

									</div>

								</div>

							</div>

							<?php
}
}
?>

						</div>

					</div>

					<div class="form-search">

						<div class="d-flex align-items-center gap-2 justify-content-between">

							<div class="d-flex align-items-center">

								<input type="hidden" name="stock_type" value="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" />

								<div class="w-200px">
									<select class="form-control mr-2 gl-reload w-200px iso-select2" name="project_id">

										<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>

										<option value="0">Lựa chọn dự án</option>

										<?php }?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, '_project', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_project']->value) {
?>

										<option value="<?php echo $_smarty_tpl->tpl_vars['_project']->value['project_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['project_id']->value == $_smarty_tpl->tpl_vars['_project']->value['project_id']) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_project']->value['title'];?>
</option>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									</select>
								</div>

								<?php if ($_smarty_tpl->tpl_vars['project_id']->value > '0') {?>

									<div class="w-150px">
										<select class="form-control iso-select2 mr-2 gl-reload w-150px" name="block_id">

											<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getOptionOrigin('_BLOCK',$_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['block_id']->value,'Phân khu');?>


										</select>
									</div>

									<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

										<?php if ($_smarty_tpl->tpl_vars['block_id']->value > '0') {?>

											<div class="w-150px">
												<select class="form-control mr-2 w-150px gl-reload iso-select2" id="slb_Building" name="building_id">

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getOptionOrigin('_BUILDING',$_smarty_tpl->tpl_vars['block_id']->value,$_smarty_tpl->tpl_vars['building_id']->value,'Tòa nhà');?>


												</select>
											</div>

										<?php }?>

										<?php if ($_smarty_tpl->tpl_vars['building_id']->value > '0') {?>


											<div class="w-150px">
												<select class="form-control custom-select w-150px gl-reload mr-2 iso-select2" name="floor">

													<?php echo $_smarty_tpl->tpl_vars['html_floor_option']->value;?>


												</select>
											</div>

										<?php }?>

									<?php } else { ?>

										<?php if ($_smarty_tpl->tpl_vars['block_id']->value > '0') {?>

											<select class="form-control mr-2 w-150px gl-reload iso-select2" id="slb_Building" name="building_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getOptionOrigin('_RANGE',$_smarty_tpl->tpl_vars['block_id']->value,$_smarty_tpl->tpl_vars['building_id']->value,'Dãy');?>


											</select>

										<?php }?>

									<?php }?>

								<?php }?>

								<div class="dropdown mega-dropdown mr-2">

									<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">&nbsp;<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-down');?>
&nbsp;</button>

									<div class="dropdown-menu mega-dropdown-menu py-3 px-2">

										<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">Loại hình</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control custom-select iso-select2" name="type_id">

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_TYPE_VILLA',$_smarty_tpl->tpl_vars['type_id']->value,$_smarty_tpl->tpl_vars['arrTypeVilla']->value);?>


												</select>

											</div>

										</div>

										<?php } else { ?>

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">Phòng ngủ</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control custom-select iso-select2" name="bedroom_id">

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_BEDROOM',$_smarty_tpl->tpl_vars['bedroom_id']->value,$_smarty_tpl->tpl_vars['arrBedRooms']->value);?>


												</select>

											</div>

										</div>

										<?php }?>

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">View</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control custom-select iso-select2" name="view_id">

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_VIEW',$_smarty_tpl->tpl_vars['view_id']->value,$_smarty_tpl->tpl_vars['arrViews']->value);?>


												</select>

											</div>

										</div>

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">Hướng BC</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control w-100 custom-select iso-select2" name="home_direction_id">

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_DIRECTION',$_smarty_tpl->tpl_vars['home_direction_id']->value,$_smarty_tpl->tpl_vars['arrDirections']->value);?>


												</select>

											</div>

										</div>

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">Đại lý</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control w-100 custom-select iso-select2" name="agency_id">

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_AGENCY',$_smarty_tpl->tpl_vars['agency_id']->value,$_smarty_tpl->tpl_vars['arrAgencies']->value);?>


												</select>

											</div>

										</div>

										<div class="form-group">

											<label class="col-form-label col-md-4">Tình Trạng</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control w-100 custom-select iso-select2" name="status_id">

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_STATUS',$_smarty_tpl->tpl_vars['status_id']->value,$_smarty_tpl->tpl_vars['arrStatus']->value);?>


												</select>

											</div>

										</div>

									</div>

								</div>

								<div class="input-group mr-2">

									<input type="text" class="form-control" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="tìm kiếm..." />

								</div>

								<input type="hidden" name="filter" value="filter" />

								<button type="submit" class="btn btn-success"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('search','Tìm');?>
</button>

							</div>

							<span class="">Kết quả: <strong class="text-danger"><?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
</strong> căn</span>

						</div>

					</div>

					<div class="hastable">

						<input type="hidden" name="filter" value="filter" />

						<div class="d-flex justify-content-between align-items-center my-3">

							<div class="p__left d-flex align-items-center">

								<div class="btn-group mr-1">

									<button class="btn btn-default" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" onClick="add_line(this,event)"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Add Line'));?>
</button>

									<button type="button" onClick="open_import_file(this, event)" tp="blank" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" title="Import bằng Excel nhưng không cần tải mẫu" data-toggle="tooltip" class="btn btn-default text-primary"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Import Excel');?>
</button>

								</div>

								<?php if (!empty($_smarty_tpl->tpl_vars['building_id']->value)) {?>

									<?php $_smarty_tpl->_assignInScope('building_name', $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['building_id']->value));?>

									<div class="btn-group mr-1">

										<button type="button" onClick="open_building(this, event)" property_type="_BUILDING" toId="slb_Building" _openFrom="_stock" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" title="Chỉnh sửa tòa nhà" data-toggle="tooltip" class="btn btn-default text-info"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
 Sửa <?php echo $_smarty_tpl->tpl_vars['building_name']->value;?>
</button>

										<button type="button"<?php if ($_smarty_tpl->tpl_vars['oneBuilding']->value['is_locked'] == '1') {?> disabled<?php }?> onClick="delete_stock_building(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" title="Xóa bảng hàng tòa nhà" data-toggle="tooltip" class="btn btn-default text-danger btn_reset_building_<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
 Xóa bảng hàng <?php echo $_smarty_tpl->tpl_vars['building_name']->value;?>
</button>

										<button type="button" onClick="do_stock_sold(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" title="Cập nhật bảng hàng<br />thành [Đã bán]" data-html="true" data-toggle="tooltip" class="btn btn-default text-primary"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check');?>
 Đã bán <?php echo $_smarty_tpl->tpl_vars['building_name']->value;?>
</button>

										<button onClick="$Core.stock.open_copy(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" class="btn btn-default text-primary" title="Copy hàng tới hàng">

											<i class="fa fa-files-o" aria-hidden="true"></i>

											<i class="fa fa-long-arrow-right" aria-hidden="true"></i>

											<i class="fa fa-files-o" aria-hidden="true"></i>

										</button>

										<button type="button" onClick="$Core.stock.updateStructCode(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" building_name="<?php echo $_smarty_tpl->tpl_vars['building_name']->value;?>
" title="Cập nhật mã căn tòa <?php echo $_smarty_tpl->tpl_vars['building_name']->value;?>
" data-html="true" class="btn btn-default text-primary"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('refresh');?>
 Cập nhật mã căn</button>

									</div>

									<input class="form-control w-120px" data-toggle="tooltip" data-html="true" title="Nhập mã căn & nhấn<br />[Enter]" placeholder="Mã căn..." project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" onkeydown="do_stock_once_sold(this, event)" />

								<?php }?>

							</div>

							<div class="d-flex align-items-center admincp-buttons-action">

								<select class="form-control mr-1 iso-select2" name="per_page">

									<option value="20"<?php if ($_smarty_tpl->tpl_vars['per_page']->value == '20') {?> selected<?php }?>>30</option>

									<option value="50"<?php if ($_smarty_tpl->tpl_vars['per_page']->value == '50') {?> selected<?php }?>>50</option>

									<option value="100"<?php if ($_smarty_tpl->tpl_vars['per_page']->value == '100') {?> selected<?php }?>>100</option>

									<option value="500"<?php if ($_smarty_tpl->tpl_vars['per_page']->value == '500') {?> selected<?php }?>>500</option>

								</select>

								<div class="btn-group d-flex">

									<button href="javascript:void(0)" class="btn btn-default do_action disabled" cmd="update" onClick="do_action(this, event);">Cập nhật</button>

									<?php if (@constant('_STOCK_DELETE_ENABLE') == '1') {?>

									<button href="javascript:void(0)" class="btn btn-default do_action disabled" cmd="delete" onClick="do_action(this, event);" >Xóa chọn</button>

									<?php }?>

								</div>

							</div>

						</div>

						<div class="freeze-table dragscroll" style="overflow-x: scroll; width:100%;">

							<table id="tableCall" cellspacing="0" class="table table-vertical table-striped no-maxwidth" width="100%">

								<thead><tr>

									<th class="text-left">

										<div class="checkbox">

											<input type="checkbox" class="checkAll" />

											<label></label>

										</div>

									</th>

									<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

									<th class="text-left">PTG</th>

									<th class="text-left"></th>

									<th class="text-left">Mã căn (FULL)</th>

									<th class="text-left">Tổng giá VAT KPBT</th>

									<th class="text-left">Giá chưa VAT&KPBT</th>

									<th class="text-left">Giá TTS</th>

									<th class="text-left">Giá TTTD</th>

									<th class="text-left">Giá Vay 80%</th>

									<th class="text-left">Giá Vay 50%</th>

									<th class="text-left">Tình trạng</th>

									<th class="text-left">Đại lý</th>

									<th class="text-left">Số tầng</th>

									<th class="text-left">Mã căn</th>

									<th class="text-left">DT_TT(m2)</th>

									<th class="text-left">DT_Tim(m2)</th>

									<th class="text-left">Số PN</th>

									<th class="text-left">Hướng BC</th>

									<th class="text-left">View</th>

									<th class="text-left">Loại hình</th>

									<th class="text-left">CSBH</th>

									<th class="text-left">Ngày ký TTĐC</th>

									<th class="text-left">Ngày ký XNĐK</th>

									<th class="text-left">Thưởng sale</th>

									<th class="text-left">Cơ chế HH</th>

									<th class="text-left" width="300px">Layout(Ưu tiên)</th>

									<th class="text-left" width="300px">Layout TS(Ưu tiên)</th>

									<?php } elseif ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>

									<th class="text-left"></th>

									<th class="text-left">Đại lý</th>

									<th class="text-left">Phân khu</th>

									<?php if ($_smarty_tpl->tpl_vars['project_id']->value > '0') {?>

									<th class="text-left">Dãy</th>

									<?php }?>

									<th class="text-left">Loại quỹ</th>

									<th class="text-left">Tình trạng</th>

									<th class="text-left">Loại hình</th>

									<th class="text-left">Mã căn</th>

									<th class="text-left">DT Đất(m2)</th>

									<th class="text-left">DT XD(m2)</th>

									<th class="text-left">TCBG</th>

									<th class="text-left">Hướng Nhà</th>

									<th class="text-left">Tổng giá VAT KPBT</th>

									<th class="text-left">Giá chưa VAT&KPBT</th>

									<th class="text-left">Giá vay 12T</th>

									<th class="text-left">Giá vay 18T</th>

									<th class="text-left">Giá Vay 24T</th>

									<th class="text-left">Giá Vay 30T</th>

									<th class="text-left">Giá vay 36T</th>

									<th class="text-left">Giá TTTD</th>

									<th class="text-left">Giá TTS</th>

									<th class="text-left">CSBH</th>

									<th class="text-left">Chính sách</th>

									<th class="text-left">Phiếu tạm tính</th>

									<th class="text-left">Loại hình ký</th>

									<th class="text-left">Qũy đầu tư</th>

									<th class="text-left">Giỏ Bank</th>

									<th class="text-left">Ngày ký cọc</th>

									<th class="text-left">Ghi chú</th>

									<th class="text-left" width="300px">Layout</th>

									<?php } else { ?>

									<th class="text-left"></th>

									<th class="text-left">Đại lý</th>

									<th class="text-left">Loại quỹ</th>

									<th class="text-left">Tình trạng</th>

									<th class="text-left">Loại hình</th>

									<th class="text-left">Mã căn</th>

									<th class="text-left">DT Đất(m2)</th>

									<th class="text-left">DT XD(m2)</th>

									<th class="text-left">TCBG</th>

									<th class="text-left">Hướng Nhà</th>

									<th class="text-left">Giá thuê</th>

									<th class="text-left">Hoàn thiện</th>

									<th class="text-left">Tiến độ TT</th>

									<th class="text-left">Link ảnh</th>

									<th class="text-left">Ghi chú</th>

									<?php }?>

								</tr></thead>

								<tbody class="tbody">

									<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_stocks']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = $__section_i_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

									<?php $_smarty_tpl->_assignInScope('stock_id', $_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['stock_id']);?>

									<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['more_information']);?>

									<tr class="sop_row sop_row_<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;
if (isset($_smarty_tpl->tpl_vars['more_information']->value['markup_price']) && $_smarty_tpl->tpl_vars['more_information']->value['markup_price'] == '1') {?> stock_mask<?php }?>">

										<td bgcolor="#EEE" width="40px">

											<div class="checkbox">

												<input type="checkbox" class="checkitem stock_item" value="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" />

												<label></label>

											</div>

										</td>

										<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

										<td bgcolor="#EEE" width="40px">

											<div class="checkbox">

												<input type="checkbox" title="Ẩn PTG" disabled<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['hide_price_sheets']) && $_smarty_tpl->tpl_vars['more_information']->value['hide_price_sheets'] == '1') {?> checked<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" />

												<label></label>

											</div>

										</td>

										<?php }?>

										<td bgcolor="#EEE"  width="120px">

											<div class="btn-group d-flex">

												<?php if (@constant('_STOCK_DELETE_ENABLE') == '1') {?>

												<button type="button" class="btn btn-icon btn-xs btn-default" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Delete');?>
" onClick="delete_line(this, event)" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button><?php }?>

												<button type="button" class="btn btn-icon btn-xs btn-default" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Edit');?>
" onClick="open_stock(this, event)" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>

											</div>

										</td>

										<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

										<td class="text-left">

											<input type="text" class="form-control stock_field text-bold w-100px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="ms_code" value="<?php echo $_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['ms_code'];?>
" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_vat" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_vat'];?>
" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price'];?>
" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_early" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_early'];?>
" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_progress" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_progress'];?>
" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_bank" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_bank'];?>
" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_bank_half" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_bank_half'];?>
" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

											data-field="status_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_STATUS',$_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['status_id'],$_smarty_tpl->tpl_vars['arrStatus']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

											data-field="agency_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_AGENCY',$_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['agency_id'],$_smarty_tpl->tpl_vars['arrAgencies']->value);?>


											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-50px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="floor" value="<?php echo $_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['floor'];?>
" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-50px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="code" 

											value="<?php echo $_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['code'];?>
" />

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="DT_TT" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_TT'];?>
" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="DT_Tim" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_Tim'];?>
" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="bedroom_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_BEDROOM',$_smarty_tpl->tpl_vars['more_information']->value['bedroom_id'],$_smarty_tpl->tpl_vars['arrBedRooms']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="home_direction_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_DIRECTION',$_smarty_tpl->tpl_vars['more_information']->value['home_direction_id'],$_smarty_tpl->tpl_vars['arrDirections']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="view_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_VIEW',$_smarty_tpl->tpl_vars['more_information']->value['view_id'],$_smarty_tpl->tpl_vars['arrViews']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="type_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_TYPE',$_smarty_tpl->tpl_vars['more_information']->value['type_id'],$_smarty_tpl->tpl_vars['arrTypes']->value);?>


											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="csbh" 

											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['csbh'];?>
" placeholder="dd/mm/yyy" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-100px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="date_deposit_sign" placeholder="dd/mm/yyy" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['date_deposit_sign'];?>
" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-100px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="reg_confirm_date" placeholder="dd/mm/yyy" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['reg_confirm_date'];?>
" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-100px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="sale_bonus" 

											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_bonus'];?>
" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<div class="input-group">

												<input type="text" class="form-control stock_field w-50px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="sale_commission" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_commission'];?>
" placeholder="0" />

												<span class="input-group-addon">%</span>

											</div>

										</td>

										<td class="text-center">

											<div class="input-group">

												<input type="text" placeholder="Nhập ảnh..." class="form-control stock_layout_<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
 w-100px" maxlength="255" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['layout'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['layout'];
}?>" />

												<div class="input-group-btn">

													<button type="button" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="$Core.stock.layout_select_file(this, event)" 

													stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" tofield="layout" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Chọn');?>
</button>

												</div>

											</div>

										</td>

										<td class="text-center">

											<div class="input-group">

												<input type="text" placeholder="Nhập ảnh..." class="form-control stock_layout_ns_<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
 w-100px" maxlength="255" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['layout_ns'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['layout_ns'];
}?>" />

												<div class="input-group-btn">

													<button type="button" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" tofield="layout_ns" onClick="$Core.stock.layout_select_file(this, event)" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Chọn');?>
</button>

												</div>

											</div>

										</td>

										<?php } elseif ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

											data-field="agency_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_AGENCY',$_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['agency_id'],$_smarty_tpl->tpl_vars['arrAgencies']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="block_id">

												<?php if ($_smarty_tpl->tpl_vars['project_id']->value > '0') {?>

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_BLOCK',$_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['block_id'],$_smarty_tpl->tpl_vars['arrBlock']->value);?>


												<?php } else { ?>

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectFromSource($_smarty_tpl->tpl_vars['arrBlock']->value,$_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['block_id'],'_BLOCK');?>


												<?php }?>

											</select>

										</td>

										<?php if ($_smarty_tpl->tpl_vars['project_id']->value > '0') {?>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="building_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectFromSource($_smarty_tpl->tpl_vars['arrBlock']->value,$_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['building_id'],'_RANGE');?>


											</select>

										</td>

										<?php }?>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

											data-field="stock_hold_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_STOCK_HOLD',$_smarty_tpl->tpl_vars['more_information']->value['stock_hold_id'],$_smarty_tpl->tpl_vars['arrStockHold']->value);?>


											</select>

										</td>

										<!-- <td class="text-left">

											<input type="text" class="form-control stock_field w-100px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="block_name" 

											value="<?php echo $_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['block_name'];?>
" />

										</td> -->

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

											data-field="status_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_STATUS',$_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['status_id'],$_smarty_tpl->tpl_vars['arrStatus']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="type_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_TYPE',$_smarty_tpl->tpl_vars['more_information']->value['type_id'],$_smarty_tpl->tpl_vars['arrTypes']->value);?>


											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field text-bold w-100px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

												   data-field="ms_code" value="<?php echo $_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['ms_code'];?>
" />

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

													   data-field="DT_TT" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_TT'];?>
" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

													   data-field="DT_Tim" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_Tim'];?>
" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

												   data-field="TCBG" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['TCBG'];?>
" placeholder="Tiêu chuẩn bàn giao" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="home_direction_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_DIRECTION',$_smarty_tpl->tpl_vars['more_information']->value['home_direction_id'],$_smarty_tpl->tpl_vars['arrDirections']->value);?>


											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_vat" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_vat'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_bank_12" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_bank_12'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_bank_18" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_bank_18'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_bank" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_bank'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_bank_30" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_bank_30'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_bank_36" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_bank_36'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_progress" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_progress'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_early" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['total_price_early'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="csbh" 

											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['csbh'];?>
" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="cs_policy_ns" 

											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['cs_policy_ns'];?>
" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="price_temporary_ns" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['price_temporary_ns'];?>
" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="contract_type_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_CONTRACT_TYPE',$_smarty_tpl->tpl_vars['more_information']->value['contract_type_id'],$_smarty_tpl->tpl_vars['arrContractType']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="invest_fund_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_INVEST_FUND',$_smarty_tpl->tpl_vars['more_information']->value['invest_fund_id'],$_smarty_tpl->tpl_vars['arrInvestFund']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="bank_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_BANK',$_smarty_tpl->tpl_vars['more_information']->value['bank_id'],$_smarty_tpl->tpl_vars['arrBank']->value);?>


											</select>

										</td>

										<td class="text-left">

											<!-- input_mask -->

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="deposit_date" placeholder="dd/mm/yyyy" data-inputmask="99/99/9999"  value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['deposit_date'];?>
" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="notes" 

											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['notes'];?>
" />

										</td>

										<td class="text-center">

											<div class="input-group">

												<input type="text" placeholder="Nhập ảnh..." class="form-control stock_layout_<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
 w-100px" maxlength="255" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['layout'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['layout'];
}?>" />

												<div class="input-group-btn">

													<button type="button" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="$Core.stock.layout_select_file(this, event)" 

													stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" tofield="layout" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Chọn');?>
</button>

												</div>

											</div>

										</td>

										<?php } else { ?>

										<!-- LEASING -->

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

											data-field="agency_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_AGENCY',$_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['agency_id'],$_smarty_tpl->tpl_vars['arrAgencies']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

											data-field="stock_hold_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_STOCK_HOLD',$_smarty_tpl->tpl_vars['more_information']->value['stock_hold_id'],$_smarty_tpl->tpl_vars['arrStockHold']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

											data-field="status_leasing_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_STATUS',$_smarty_tpl->tpl_vars['more_information']->value['status_leasing_id'],$_smarty_tpl->tpl_vars['arrStatus']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="type_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_TYPE',$_smarty_tpl->tpl_vars['more_information']->value['type_id'],$_smarty_tpl->tpl_vars['arrTypes']->value);?>


											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field text-bold w-100px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

												   data-field="ms_code" value="<?php echo $_smarty_tpl->tpl_vars['list_stocks']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['ms_code'];?>
" />

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

													   data-field="DT_TT" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_TT'];?>
" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

													   data-field="DT_Tim" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_Tim'];?>
" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" 

												   data-field="TCBG" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['TCBG'];?>
" placeholder="Tiêu chuẩn bàn giao" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="home_direction_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_DIRECTION',$_smarty_tpl->tpl_vars['more_information']->value['home_direction_id'],$_smarty_tpl->tpl_vars['arrDirections']->value);?>


											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="total_price_vat" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['dg_price'];?>
" placeholder="0.00" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="completed_floor_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_COMPLETED_FLOOR',$_smarty_tpl->tpl_vars['more_information']->value['completed_floor_id'],$_smarty_tpl->tpl_vars['arrCompleteFloors']->value);?>


											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="payment_progress_id">

												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectOptimizeProperty('_STATUS_PAYMENT_PROGRESS',$_smarty_tpl->tpl_vars['more_information']->value['payment_progress_id'],$_smarty_tpl->tpl_vars['arrStatusPaymentProgress']->value);?>


											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="link_image" 

											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['link_image'];?>
" placeholder="https://drive.google.com/xxxx" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-field="notes" 

											value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['notes'];?>
" />

										</td>

										<?php }?>

									</tr>

									<?php
}
}
?>

								</tbody>

							</table>

						</div>

						<?php if ($_smarty_tpl->tpl_vars['total_page']->value > '1') {?>

						<div class="d-flex justify-content-center">

							<ul class="pagination">

								<?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>


							</ul>

						</div>

						<?php }?>

					</div>

				</form>

			</div></div>

		</div></div>

	</div>

</div>



<style type="text/css">

	.w-100px{width:100px !important;}

	.w-120px{width:120px !important;}

	.w-250px{width:250px !important;}

	.table{margin-bottom:0;max-width:18000px;}

	.input-group-suffix .suffix{ right:10px;}

	.freeze-table {

        user-select: none;

        -moz-user-select: none;

        -khtml-user-select: none;

        -webkit-user-select: none;

        -o-user-select: none;

	}

	tr.stock_mask > td:first-child{

		position:relative;

	} 

	tr.stock_mask > td:first-child:before{

		content: "";

		position: absolute;

		left: -8px; top: -3px;

		border-bottom: 10px solid #C00000;

		border-left: 10px solid transparent;

		border-right: 10px solid transparent;

		transform: rotate(-45deg);

		-moz-transform: rotate(-45deg);

		-webkit-transform: rotate(-45deg);

	}

	.mega-dropdown-menu{

		min-width:300px;

	}

	.dropdown-menu > li > a.disabled{

		color:gray;

		opacity:0.2l

		filter:alpha(opacity=20);

	}

</style>

<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		$(".mega-dropdown-menu").click(function(e){

		   e.stopPropagation();

		});

		setTimeout(() => {

			$('.freeze-table').freezeTable({

				'columnNum': 5,

				'scrollable': true

			});

		},1000);

		$_document.on('change', '.gl-reload', function(){

			var _this = $(this),

				_form = _this.closest('form');

			$('button[type=submit]', _form).trigger('click');

			return false;

		});

	});

<?php echo '</script'; ?>
>



<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_VIEWS']->value;?>
/project/js/jquery.project.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
><?php }
}
