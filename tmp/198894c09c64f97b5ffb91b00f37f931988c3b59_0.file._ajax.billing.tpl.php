<?php
/* Smarty version 3.1.33, created on 2026-08-08 19:46:11
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.billing.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7725135982a8_09956997',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '198894c09c64f97b5ffb91b00f37f931988c3b59' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.billing.tpl',
      1 => 1786016415,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7725135982a8_09956997 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="modal-dialog modal-ipad-xl">
	<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
	<form class="d-none" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="hid" value="upload" />
		<input type="hidden" name="billing_code" value="<?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['billing_code'];?>
" />
		<input type="file" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="upload_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onChange="$Core.billing.upload_sp_file(this, event)" 
		name="upload_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" />
	</form>
	<form class="modal-content bf-form">
		<div class="modal-header">
			<h5 class="modal-title"><?php if ($_smarty_tpl->tpl_vars['action']->value == '_add') {?>Thêm<?php } else { ?>Chỉnh sửa<?php }?> giao dịch</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<?php if ($_smarty_tpl->tpl_vars['is_content_changing']->value == '1' && !empty($_smarty_tpl->tpl_vars['arr_change_logs']->value)) {?>
			<div class="alert alert-warning">
				<h3 class="mb-2 text-fs-18"><i class="bx bx-bell"></i> Thay đổi đang chờ được chấp nhận</h3>
				<p class="mb-1"><strong>Lý do:</strong> <?php echo $_smarty_tpl->tpl_vars['arr_change_logs']->value['reason'];?>
</p>
				<p class="mb-1"><strong>Thời gian:</strong> <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['arr_change_logs']->value['reg_date'],true);?>
</p>
				<b>Chi tiết: </b>
				<ul class="mb-0">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_change_logs']->value['content_change'], '_oValue', false, '_oField');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oField']->value => $_smarty_tpl->tpl_vars['_oValue']->value) {
?>
						<?php if ($_smarty_tpl->tpl_vars['_oField']->value == 'totalgrand') {?>
						<li><?php echo $_smarty_tpl->tpl_vars['clsBilling']->value->getFieldName($_smarty_tpl->tpl_vars['_oField']->value);?>
 : <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatPrice($_smarty_tpl->tpl_vars['_oValue']->value);?>
</li>
						<?php } elseif ($_smarty_tpl->tpl_vars['_oField']->value == 'staff_id') {?>
						<li><?php echo $_smarty_tpl->tpl_vars['clsBilling']->value->getFieldName($_smarty_tpl->tpl_vars['_oField']->value);?>
 : <?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['_oValue']->value);?>
</li>
						<?php } elseif ($_smarty_tpl->tpl_vars['_oField']->value == 'stock_code') {?>
						<li><?php echo $_smarty_tpl->tpl_vars['clsBilling']->value->getFieldName($_smarty_tpl->tpl_vars['_oField']->value);?>
 : <?php echo $_smarty_tpl->tpl_vars['_oValue']->value;?>
</li>
						<?php }?>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</ul>
			</div>
			<?php }?>
			<div class="bf-sec"><span>1 &middot; Giao dịch</span></div>
			<div class="form-row mb-2">
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Mã GD</label>
					<input type="text" id="trans_code" name="billing_code" class="form-control required" placeholder="Mã GD" value="<?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['billing_code'];?>
"<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {?> readonly<?php }?> >
				</div>
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label for="deposit_date" class="form-label mb-1">Ngày cọc</label>
					<input type="date" id="deposit_date" name="deposit_date" class="form-control required"
					placeholder="dd/mm/yy" lang="vi-VN" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['oneBilling']->value['deposit_date'],'%Y-%m-%d');
} else {
echo smarty_modifier_date_format(time(),'%Y-%m-%d');
}?>">
				</div>
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label class="form-label mb-1">Loại hình</label>
					<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<select id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="form-control form-select required" name="billing_type">
						<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_BILLING_TYPE',$_smarty_tpl->tpl_vars['oneBilling']->value['billing_type']);?>

					</select>
				</div>
				<div class="col-6 col-md-3">
					<label class="form-label mb-1">Trạng thái</label>
					<select class="form-control iso-selectize required" name="state_id">
						<option value="">Trạng thái</option>
						<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('BILLING_STATE',$_smarty_tpl->tpl_vars['oneBilling']->value['state_id']);?>

					</select>
				</div>
			</div>
						<div class="bf-sec"><span>2 &middot; Căn bán</span></div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Dự án</label>
					<div class="clearfix"></div>
					<select placeholder="Chọn dự án" class="iso-selectizeNotSearch required w-100" name="project_id"
					data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=home&act=list_project" data-optgroup="false"
					onChange="$Core.billing.handle_stock_changed(this, event);$Core.billing.load_block(this,event)" block_id="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['block_id'];
} else { ?>0<?php }?>" toId="block<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" >
						<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['project_id'];?>
" selected="selected">
							<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getTitle($_smarty_tpl->tpl_vars['oneBilling']->value['project_id']);?>

						</option>
						<?php }?>
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Phân khu</label>
					<div class="clearfix"></div>
					<select data-placeholder="Chọn phân khu" class="form-control form-select <?php if ($_smarty_tpl->tpl_vars['action']->value != '_edit' || (!empty($_smarty_tpl->tpl_vars['oneBilling']->value['project_id']) && $_smarty_tpl->tpl_vars['oneBilling']->value['project_id'] != @constant('_PROJECT_OTHER_ID'))) {?>required<?php }?>"
						name="block_id" data-width="100%" data-allow-clear="true" id="block<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.billing.loadProjectDirector(this,event)" >
						<option value="0">Phân khu</option>
						<?php if (!empty($_smarty_tpl->tpl_vars['lstBlock']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstBlock']->value, '_oBlock', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['_oBlock']->value['property_id'] == $_smarty_tpl->tpl_vars['more_information']->value['block_id']) {?>selected<?php }?> ><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label id="product_code" class="form-label mb-1">Mã Căn</label>
					<div class="clearfix"></div>
					<input type="text" name="stock_code" autocomplete="off" placeholder="S1.01XXXX" class="form-control required"
						value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['oneBilling']->value['stock_code'];
}?>" />
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Nguồn gốc</label>
					<select class="iso-selectizeNotSearch w-100 required" placeholder="Nguồn gốc" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=ajax&sub=helper&act=get_property&property_type=_AGENCY" name="stock_resource" data-optgroup="false">
						<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['stock_resource'];?>
" selected="selected">
								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['more_information']->value['stock_resource']);?>

							</option>
						<?php } else { ?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['stock_resource'];?>
" selected="selected">
								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneBilling']->value['stock_resource']);?>

							</option>
						<?php }?>
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Nguồn quỹ</label>
					<select class="form-control form-select required" name="billing_source_id">
						<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('BILLING_SOURCE',$_smarty_tpl->tpl_vars['oneBilling']->value['billing_source_id']);?>

					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Loại giao dịch</label>
					<select class="form-control form-select" name="deal_type" onChange="$Core.billing.handle_deal_type_changed(this, event)" title="Bán cho F2 = bán sỉ cho đại lý, không phát sinh hoa hồng cá nhân, chỉ có phần công ty">
						<option value="sale"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['deal_type'] != 'channel' && $_smarty_tpl->tpl_vars['more_information']->value['deal_type'] != 'f2') {?> selected="selected"<?php }?>>Sale nội bộ</option>
						<option value="channel"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['deal_type'] == 'channel') {?> selected="selected"<?php }?>>Phát triển đối tác (PTĐT)</option>
						<option value="f2"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['deal_type'] == 'f2') {?> selected="selected"<?php }?>>Bán cho F2</option>
					</select>
				</div>
				<input type="hidden" name="sold_to_type" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oneBilling']->value['sold_to_type'], ENT_QUOTES, 'UTF-8', true);?>
">
			</div>
			<div class="widget-block mb-2 collapsed">
				<div onclick="$Core.helper.toggle_block(this,event)" class="widget-header">3 &middot; Khách hàng</div>
				<div class="widget-content">
					<div class="form-row">
						<div class="col-12 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Tên khách hàng</label>
							<input type="text" name="customer_name" class="form-control" placeholder="Nguyễn Văn A" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_name'];
}?>">
						</div>
						<div class="col-12 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Điện thoại</label>
							<input type="text" name="customer_phone" class="form-control" placeholder="Điện thoại" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_phone'];
}?>">
						</div>
						<div class="col-12 col-md-4">
							<label class="form-label mb-1">E-mail</label>
							<input type="text" name="customer_email" class="form-control" placeholder="example@gmail.com" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_email'];
}?>">
						</div>
					</div>
				</div>
			</div>
			<div class="bf-sec"><span>4 &middot; Người bán</span></div>
			<?php $_smarty_tpl->_assignInScope('_uid_deal', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Sales bán</label>
															<select class="w-100 iso-selectizeSync js__staff-select" data-placeholder="Chọn sales bán" 
						name="staff_id" data-width="100%" data-allow-clear="true" data-optgroup="false"
						onChange="$Core.billing.handle_staff_changed(this, event)">
						<option value="0">Chọn sales bán</option>
						<?php if (!empty($_smarty_tpl->tpl_vars['arr_staffs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_staffs']->value, '_oStaff');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['oneBilling']->value['staff_id'] == $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id']) {?> selected="selected"<?php }?> value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oStaff']->value['full_name'], ENT_QUOTES, 'UTF-8', true);?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Đại lý bán</label>
					<select class="iso-selectizeNotSearch w-100" placeholder="Đại lý bán" data-width="100%" name="sale_agency_id" data-optgroup="false"
						data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=ajax&sub=helper&act=get_property&property_type=_AGENCY" >
						<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_agency_id'])) {?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_agency_id'];?>
" selected="selected"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['more_information']->value['sale_agency_id']);?>
</option>
						<?php }?>
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label mb-1">Admin phụ trách</label>
					<select class="iso-selectizeImageSearch required w-100" placeholder="Nhân viên"  name="admin_id" data-optgroup="false"
						data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=home&act=list_staff&holderG=admin">
						<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['admin_id'];?>
" selected><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getIndentity($_smarty_tpl->tpl_vars['oneBilling']->value['admin_id']);?>
</option>
						<?php }?>
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-row mb-2">
				<div class="col-12">
					<?php $_smarty_tpl->_assignInScope('co_main', 100);?>
					<?php if (!empty($_smarty_tpl->tpl_vars['list_co_sellers']->value)) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_co_sellers']->value, '_cs');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_cs']->value) {
$_smarty_tpl->_assignInScope('co_main', $_smarty_tpl->tpl_vars['co_main']->value-$_smarty_tpl->tpl_vars['_cs']->value['share_ratio']);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?>
					<?php $_smarty_tpl->_assignInScope('co_show', $_smarty_tpl->tpl_vars['co_main']->value);?>
					<?php if ($_smarty_tpl->tpl_vars['co_main']->value < 0) {
$_smarty_tpl->_assignInScope('co_show', 0-$_smarty_tpl->tpl_vars['co_main']->value);
}?>
					<div class="co-box">
						<div class="co-box-header">
							<span class="fw-medium">Sale phụ (co-sale)</span>
							<span class="co-main-chip<?php if ($_smarty_tpl->tpl_vars['co_main']->value < 0) {?> is-over<?php }?>">
								<span class="co-main-label"><?php if ($_smarty_tpl->tpl_vars['co_main']->value < 0) {?>Vượt<?php } else { ?>Sale chính giữ<?php }?></span>
								<strong class="co_main_ratio"><?php echo $_smarty_tpl->tpl_vars['co_show']->value;?>
</strong>%
							</span>
						</div>
						<div class="co-box-body" id="co_sale_list">
							<?php
$__section_r_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['co_rows_count']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_r_0_total = $__section_r_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_r'] = new Smarty_Variable(array());
if ($__section_r_0_total !== 0) {
for ($__section_r_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_r']->value['index'] = 0; $__section_r_0_iteration <= $__section_r_0_total; $__section_r_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_r']->value['index']++){
?>
							<?php $_smarty_tpl->_assignInScope('_ridx', (isset($_smarty_tpl->tpl_vars['__smarty_section_r']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_r']->value['index'] : null));?>
							<?php if ($_smarty_tpl->tpl_vars['_ridx']->value < count($_smarty_tpl->tpl_vars['list_co_sellers']->value)) {
$_smarty_tpl->_assignInScope('_co', $_smarty_tpl->tpl_vars['list_co_sellers']->value[$_smarty_tpl->tpl_vars['_ridx']->value]);
} else {
$_smarty_tpl->_assignInScope('_co', false);
}?>
							<div class="co-sale-row d-flex align-items-center gap-2 mb-2<?php if ($_smarty_tpl->tpl_vars['_ridx']->value > 0 && !$_smarty_tpl->tpl_vars['_co']->value) {?> d-none<?php }?>">
								<span class="co-index"><?php echo $_smarty_tpl->tpl_vars['_ridx']->value+1;?>
</span>
								<div class="flex-grow-1">
									<select class="iso-selectizeImageSearch w-100" placeholder="Chọn sale phụ" name="co_sale[<?php echo $_smarty_tpl->tpl_vars['_ridx']->value;?>
][staff_id]" data-optgroup="false" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=home&act=list_staff&holderG=all">
										<?php if ($_smarty_tpl->tpl_vars['_co']->value) {?><option value="<?php echo $_smarty_tpl->tpl_vars['_co']->value['staff_id'];?>
" selected><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsProfile']->value->getIndentity($_smarty_tpl->tpl_vars['_co']->value['staff_id']), ENT_QUOTES, 'UTF-8', true);?>
</option><?php }?>
									</select>
								</div>
								<div class="input-group flex-shrink-0 w-px-125">
									<input type="text" class="form-control text-end co-ratio" inputmode="decimal" name="co_sale[<?php echo $_smarty_tpl->tpl_vars['_ridx']->value;?>
][ratio]" placeholder="0" value="<?php if ($_smarty_tpl->tpl_vars['_co']->value) {
echo $_smarty_tpl->tpl_vars['_co']->value['share_ratio'];
}?>" onkeyup="$Core.billing.update_co_sale_hint()">
									<span class="input-group-text">%</span>
								</div>
								<button type="button" class="co-remove" title="Xoá sale phụ" onclick="$Core.billing.remove_co_sale(this,event)"><i class="bx bx-x"></i></button>
							</div>
							<?php
}
}
?>
						</div>
						<div class="co-box-footer">
							<button type="button" class="btn btn-sm btn-link p-0 btn_add_co_sale" onclick="$Core.billing.add_co_sale(this,event)"><i class="bx bx-plus-circle"></i> Thêm sale phụ</button>
						</div>
					</div>
				</div>
			</div>
									<div class="bf-sec"><span>5 &middot; Quản lý hưởng hoa hồng</span></div>
			<div class="form-group form-row mb-2">
								<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Trưởng phòng Kinh doanh</label>
					<div class="clearfix"></div>
					<select data-placeholder="Chọn TPKD" class="form-control iso-selectizeSync" name="main_head_of_dep_id"
						data-width="100%" data-allow-clear="true">
						<option value="0">Trưởng phòng KD</option>
						<?php if (!empty($_smarty_tpl->tpl_vars['arr_staffs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_staffs']->value, '_oStaff');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['main_head_of_dep_id']->value == $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id']) {?> selected="selected"<?php }?> value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oStaff']->value['full_name'], ENT_QUOTES, 'UTF-8', true);?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Giám đốc Kinh doanh</label>
					<div class="clearfix"></div>
					<select data-placeholder="Chọn GĐKD" class="form-control iso-selectizeSync" name="main_sale_dir_id"
						data-width="100%" data-allow-clear="true">
						<option value="0">Giám đốc KD</option>
						<?php if (!empty($_smarty_tpl->tpl_vars['arr_staffs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_staffs']->value, '_oStaff');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['main_sale_dir_id']->value == $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id']) {?> selected="selected"<?php }?> value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oStaff']->value['full_name'], ENT_QUOTES, 'UTF-8', true);?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label mb-1">Chọn GĐDA</label>
					<div class="clearfix"></div>
					<select data-placeholder="Chọn GĐDA" class="form-control iso-selectizeSync" name="project_director_id"
						data-width="100%" data-allow-clear="true">
						<option value="0">Giám Đốc DA</option>
						<?php if (!empty($_smarty_tpl->tpl_vars['arr_staffs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_staffs']->value, '_oStaff');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['dep_logs']->value['project_director_id'] == $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id']) {?> selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['full_name'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</select>
				</div>
			</div>
						<div class="bf-sec"><span>6 &middot; Tiền &amp; hoa hồng</span></div>
			<div class="bg-lighter rounded-2 p-3 mb-2">
				<div class="form-group form-row mb-2">
					<div class="col-12 col-md-4 mb-2">
						<label for="totalgrand" class="form-label mb-1">Số tiền (giá trị bán)</label>
						<div class="clearfix"></div>
						<div class="input-group input-group-merge">
							<input type="text" id="totalgrand" name="totalgrand" class="form-control numberonly price-In required" autocomplete="off"
								placeholder="0.00" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['oneBilling']->value['totalgrand'];
}?>">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-12 col-md-4 mb-2">
						<label class="form-label mb-1">Giá tính hoa hồng</label>
						<div class="clearfix"></div>
						<div class="input-group input-group-merge">
							<input type="text" id="commission_value" name="commission_value" class="form-control numberonly price-In" autocomplete="off" placeholder="0.00" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['commission_value'];
} else { ?>0<?php }?>" onClick="this.select()">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-12 col-md-4 mb-2">
						<label class="form-label mb-1">% Hoa hồng Sale </label>
						<div class="clearfix"></div>
						<div class="input-group input-group-merge">
							<input type="text" id="commission" name="commission" onChange="$Core.util.is_valid_percent(this, event)" class="form-control required numberonly" autocomplete="off" placeholder="0.00" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['commission'];
}?>" onClick="this.select()">
							<span class="input-group-text">%</span>
						</div>
					</div>
										<div class="col-6 col-md-3">
						<label class="form-label mb-1">Thưởng Sale</label>
						<div class="input-group input-group-merge">
							<input type="text" id="sale_bonus" name="sale_bonus" class="form-control numberonly price-In" autocomplete="off" placeholder="0.00" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['sale_bonus'];
} else { ?>0<?php }?>" onClick="this.select()">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<label class="form-label mb-1">Thưởng đại lý</label>
						<div class="input-group input-group-merge">
							<input type="text" id="bonus_agency" name="bonus_agency" class="form-control numberonly price-In" autocomplete="off" placeholder="0.00" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['bonus_agency'];
} else { ?>0<?php }?>" onClick="this.select()">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<label class="form-label mb-1">Thưởng nóng</label>
						<div class="input-group input-group-merge">
							<input type="text" id="hot_bonus" name="hot_bonus" class="form-control numberonly price-In" autocomplete="off" placeholder="0.00" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['hot_bonus'];
} else { ?>0<?php }?>" onClick="this.select()">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<label class="form-label mb-1">Tiền hỗ trợ(nếu có)</label>
						<div class="clearfix"></div>
						<div class="input-group input-group-merge">
							<input type="text" name="support_sale" autocomplete="off" placeholder="0.00" class="form-control numberonly price-In" value="<?php if ($_smarty_tpl->tpl_vars['action']->value == '_edit') {
echo $_smarty_tpl->tpl_vars['more_information']->value['support_sale'];
} else { ?>0<?php }?>" onClick="this.select()" />
							<span class="input-group-text">.đ</span>
						</div>
					</div>
				</div>
			</div>
						<div class="widget-block mb-2 collapsed">
				<div onclick="$Core.helper.toggle_block(this,event)" class="widget-header">7 &middot; Hồ sơ &amp; ghi chú</div>
				<div class="widget-content">
					<div class="form-group form-row mb-2">
						<div class="col-12 col-md-3 mb-2 mb-lg-0">
							<label class="d-flex align-items-center justify-content-between form-label mb-1">Ảnh chụp xác nhận
								<a title="Dán ảnh từ Clipboard" contenteditable="true" class="btn btn-outline-default btn-xs btn-icon" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="capture_confirm_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onpaste="$Core.global.billing.upload_sp_file_clipboard(this, event)">
									<i class='bx bx-paste fs-12'></i></a>
							</label>
							<div class="clearfix"></div>
							<div id="capture_confirm_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="capture_confirm_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
">
								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['capture_confirm_file'])) {?>
								<a class="download w-100 limit_1line text-nowrap" data-fancybox="capture_confirm_file_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['capture_confirm_file']);?>
">
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatFileName($_smarty_tpl->tpl_vars['more_information']->value['capture_confirm_file']);?>

								</a>
								<?php } else { ?>
								<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="capture_confirm_file" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onclick="$Core.global.billing.select_sp_file(this, event)" class="w-100 btn btn-sm btn-outline-default text-none"><i class="bx bx-upload"></i> Tải file</a>
								<?php }?>
							</div>
						</div>
						<div class="col-6 col-md-3">
							<label class="d-flex align-items-center justify-content-between form-label mb-1">Ảnh CSBH
								<a title="Dán ảnh từ Clipboard" contenteditable="true" class="btn btn-outline-default btn-xs btn-icon" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="sale_policy_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onpaste="$Core.global.billing.upload_sp_file_clipboard(this, event)">
									<i class='bx bx-paste fs-12'></i></a>
							</label>
							<div class="clearfix"></div>
							<div id="sale_policy_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="sale_policy_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
">
								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file'])) {?>
								<a class="download w-100 limit_1line text-nowrap" data-fancybox="sale_policy_file_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file']);?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatFileName($_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file']);?>
</a>
								<?php } else { ?>
								<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="sale_policy_file" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onclick="$Core.global.billing.select_sp_file(this, event)" class="w-100 btn btn-sm btn-outline-default text-none"><i class="bx bx-upload"></i> Tải file</a>
								<?php }?>
							</div>
						</div>
						<div class="col-6 col-md-3">
							<label class="d-flex align-items-center justify-content-between form-label mb-1">Ảnh bảng thưởng
								<a title="Dán ảnh từ Clipboard" contenteditable="true" class="btn btn-outline-default btn-xs btn-icon" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="table_bonus_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onpaste="$Core.global.billing.upload_sp_file_clipboard(this, event)">
									<i class='bx bx-paste fs-12'></i></a>
							</label>
							<div class="clearfix"></div>
							<div id="table_bonus_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="table_bonus_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
">
								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['table_bonus_file'])) {?>
								<a class="download w-100 limit_1line text-nowrap" data-fancybox="table_bonus_file_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['table_bonus_file']);?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatFileName($_smarty_tpl->tpl_vars['more_information']->value['table_bonus_file']);?>
</a>
								<?php } else { ?>
								<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="table_bonus_file" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onclick="$Core.global.billing.select_sp_file(this, event)" class="w-100 btn btn-sm btn-outline-default text-none"><i class="bx bx-upload"></i> Tải file</a>
								<?php }?>
							</div>
						</div>
						<div class="col-6 col-md-3">
							<label class="d-flex align-items-center justify-content-between form-label mb-1">Ảnh vinh danh
								<a title="Dán ảnh từ Clipboard" contenteditable="true" class="btn btn-outline-default btn-xs btn-icon" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="image_poster" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onpaste="$Core.global.billing.upload_sp_file_clipboard(this, event)">
									<i class='bx bx-paste fs-12'></i></a>
							</label>
							<div class="clearfix"></div>
							<div id="image_poster_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="image_poster_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
">
								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['image_poster'])) {?>
								<a class="download w-100 limit_1line text-nowrap" data-fancybox="image_poster_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['image_poster']);?>
">
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatFileName($_smarty_tpl->tpl_vars['more_information']->value['image_poster'],10);?>

								</a>
								<?php } else { ?>
								<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="image_poster" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onclick="$Core.global.billing.select_sp_file(this, event)" class="w-100 btn btn-sm btn-outline-default text-none">
									<i class="bx bx-upload"></i> Tải file</a>
								<?php }?>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<label class="form-label mb-1">Ghi chú</label>
							<textarea class="form-control" placeholder="Viết ghi chú" name="staff_notes" rows="2"><?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['staff_notes'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['staff_notes'];
}?></textarea>
						</div>
					</div>
				</div>
			</div>
								</div>
		<div class="modal-footer">
			<input type="hidden" name="regional_id" value="<?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['regional_id'];?>
" />
			<input type="hidden" name="department_id" value="<?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['department_id'];?>
" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" openFrom="<?php echo $_smarty_tpl->tpl_vars['openFrom']->value;?>
" holderG="save" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" is_ignore_confirmed="0" 
				onClick="$Core.global.billing.pop_save_billing(this, event)" class="btn btn-primary js__save-billing"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('floppy-o','Lưu lại');?>
</button>
		</div>
	</form>
</div>

<style type="text/css">
	input[name=ms_date]{ width:100px !important;}
	.selectize-dropdown{ z-index:3 !important}
</style>

<?php }
}
