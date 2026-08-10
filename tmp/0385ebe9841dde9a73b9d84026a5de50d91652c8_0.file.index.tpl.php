<?php
/* Smarty version 3.1.33, created on 2026-08-06 08:34:30
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/billing_search/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a73e4a6d22376_96377504',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0385ebe9841dde9a73b9d84026a5de50d91652c8' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/billing_search/index.tpl',
      1 => 1785927027,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a73e4a6d22376_96377504 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="p-3">
	<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ACCOUNTANT') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('view_all_billing')) {?>
	<div class="form-group form-row mb-2"> 
		<div class="col-12 col-md-4 mb-0 nb-lg-2">
			<div class="form-label mb-1">Phòng ban</div>
			<select class="iso-selectizeSync" onChange="$Core.billing.handle_dep_changed(this, event)" 
				data-width="100%" placeholder="Phòng ban" name="dept_id">
				<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_DEPARTMENT',$_smarty_tpl->tpl_vars['dept_id']->value,'Phòng ban');?>

			</select>
		</div>
		<div class="col-12 col-md-8">
			<div class="form-label mb-1">Nhân viên</div>
			<select class="iso-selectizeImageSearch" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=home&act=list_staff" 
				placeholder="Nhân viên" name="staff_id" data-optgroup="false">
				<?php if ($_smarty_tpl->tpl_vars['staff_id']->value > '0') {?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['staff_id']->value;?>
" selected="selected"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getIndentity($_smarty_tpl->tpl_vars['staff_id']->value,false);?>
</option>
				<?php }?>
			</select>
		</div>
	</div>
	<?php } elseif ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('PROJECT_DIRECTOR')) {?>
	<div class="form-group mb-2">
		<label class="form-label mb-1">Lọc theo GĐDA</label>
		<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
		<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
			<input type="radio" class="btn-check" onChange="$Core.billing.filter_by_changed(this, event)" name="filter_by" 
			id="TYPE_LIST_PROJECT_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="_project"<?php if ($_smarty_tpl->tpl_vars['filter_by']->value == '_project') {?> data-bind="change" checked<?php }?>>
			<label class="btn text-nowrap btn-outline-default" for="TYPE_LIST_PROJECT_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Dự án</label>
			<input type="radio" class="btn-check" onChange="$Core.billing.filter_by_changed(this, event)" name="filter_by" 
			id="TYPE_LIST_DEPT_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="_dept"<?php if ($_smarty_tpl->tpl_vars['filter_by']->value == '_dept') {?> data-bind="change" checked<?php }?>>
			<label class="btn text-nowrap btn-outline-default" for="TYPE_LIST_DEPT_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Phòng ban</label>
		</div>
	</div>
	<div class="form-group form-row mb-2">
		<div class="col-12 col-md-5 mb-2 mb-lg-0">
			<div class="form-label mb-1">Phòng ban</div>
			<select class="iso-selectizeNotSearch" onChange="$Core.billing.handle_dep_changed(this, event)" 
			placeholder="Chọn phòng ban" name="dept_id" staff_id="<?php echo $_smarty_tpl->tpl_vars['staff_id']->value;?>
" dept_id="<?php echo $_smarty_tpl->tpl_vars['dept_id']->value;?>
">
				<option value="0">Chọn phòng ban</option>
			</select>
		</div>
		<div class="col-12 col-md-7 mb-2 mb-lg-0">
			<label class="form-label mb-1">Nhân viên</label>
			<select class="form-control iso-selectizeSync" placeholder="Nhân viên" name="staff_id" staff_id="<?php echo $_smarty_tpl->tpl_vars['staff_id']->value;?>
">
				<?php if ($_smarty_tpl->tpl_vars['staff_id']->value > '0') {?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['staff_id']->value;?>
" selected="selected"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getIndentity($_smarty_tpl->tpl_vars['staff_id']->value,false);?>
</option>
				<?php }?>
			</select>
		</div>
	</div>
	<hr class="my-2" />
	<?php } elseif ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR')) {?>
	<div class="form-group mb-2">
		<div class="form-label mb-1">Nhân viên</div>
		<select class="iso-selectizeNotSearch" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=home&act=list_staff&holderG=permiss" 
			placeholder="Nhân viên" name="staff_id" data-optgroup="false">
			<?php if (!empty($_smarty_tpl->tpl_vars['staff_id']->value)) {?>
			<option value="<?php echo $_smarty_tpl->tpl_vars['staff_id']->value;?>
" selected="selected"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getIndentity($_smarty_tpl->tpl_vars['staff_id']->value,false);?>
</option>
			<?php }?>
		</select>
	</div>
	<?php }?>
	<div class="form-row mb-2">
		<div class="col-6 col-md-6">
			<div class="form-floating">
				<select class="form-control" name="contract_status_id">
					<option value="0">Tình trạng HĐMB</option>
					<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_STATUS_CONTRACT',$_smarty_tpl->tpl_vars['contract_status_id']->value);?>

				</select>
				<label for="floatingInput">Tình trạng</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			<div class="form-floating">
				<select class="form-control" name="billing_type">
					<option value="0">Loại hình</option>
					<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_BILLING_TYPE',$_smarty_tpl->tpl_vars['billing_type']->value);?>

				</select>
				<label for="floatingInput">Loại hình</label>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-6 col-md-6">
			<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="form-floating">
				<input type="date" class="form-control" name="start_date" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" placeholder="dd/mm/yy" 
				aria-describedby="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="<?php if (!empty($_smarty_tpl->tpl_vars['start_date']->value)) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['start_date']->value,'%Y-%m-%d');
}?>">
				<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Từ ngày</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="form-floating">
				<input type="date" class="form-control" name="to_date" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" placeholder="dd/mm/yy" 
				aria-describedby="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="<?php if (!empty($_smarty_tpl->tpl_vars['to_date']->value)) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['to_date']->value,'%Y-%m-%d');
}?>">
				<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Tới ngày</label>
			</div>
		</div>
	</div>
	<div class="form-group form-row mb-2">
		<div class="col-6 col-md-6">
			<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="form-floating">
				<select id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-bind="change" name="project_id" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" 
					onChange="$Core.billing.load_block(this, event)" class="form-control w-100">
					<option value="0">Chọn dự án</option>
					<?php if (!empty($_smarty_tpl->tpl_vars['lstProject']->value)) {?>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstProject']->value, '_oProject', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProject']->value) {
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['project_id']->value == $_smarty_tpl->tpl_vars['_oProject']->value['project_id']) {?>selected<?php }?> ><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</option>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<?php }?>
				</select>
				<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Dự án</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="form-floating">
				<select id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="block_id" class="form-control w-100">
					<option value="0">Tất cả</option>
				</select>
				<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Phân khu</label>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-6 col-md-6">
			<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="form-floating">
				<select id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="billing_source" class="form-control w-100">
					<option value="0">Tất cả</option>
					<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('BILLING_SOURCE',$_smarty_tpl->tpl_vars['billing_source']->value);?>

				</select>
				<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Nguồn quỹ</label>
			</div>
		</div>
		<div class="col-6 col-md-6">
			<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="form-floating">
				<select id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="sort_by" class="form-control w-100">
					<option<?php if ($_smarty_tpl->tpl_vars['sort_by']->value == 'reg_date') {?> selected<?php }?> value="reg_date">Ngày tạo</option>
					<option<?php if ($_smarty_tpl->tpl_vars['sort_by']->value == 'contract_date') {?> selected<?php }?> value="contract_date">Ngày ký HĐMB</option>
				</select>
				<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Sắp xếp theo</label>
			</div>
		</div>
	</div>
	<!-- <hr class="my-2" />
	<div class="form-row mb-2">
		<div class="col-6 col-md-6">
			<label class="form-label mb-1">Nộp Cọc cứng(PCC)</label>
			<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendpale" id="PILE_ALL_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="_all" autocomplete="off"<?php if ($_smarty_tpl->tpl_vars['is_sendpale']->value == '_all') {?> checked<?php }?>>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="PILE_ALL_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">ALL</label>
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendpale" id="PILE_YES_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="1" autocomplete="off"<?php if ($_smarty_tpl->tpl_vars['is_sendpale']->value == '1') {?> checked<?php }?>>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="PILE_YES_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">YES</label>
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendpale" id="PILE_NO_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="0" autocomplete="off"<?php if ($_smarty_tpl->tpl_vars['is_sendpale']->value == '0') {?> checked<?php }?>>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="PILE_NO_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">NO</label>
			</div>
		</div>
		<div class="col-6 col-md-6"> 
			<label class="form-label mb-1">Gửi email</label>
			<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendemail" id="SENDEMAIL_ALL_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="_all" autocomplete="off"<?php if ($_smarty_tpl->tpl_vars['is_sendemail']->value == '_all') {?> checked<?php }?>>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="SENDEMAIL_ALL_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">All</label>
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendemail" id="SENDEMAIL_YES_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="1" autocomplete="off"<?php if ($_smarty_tpl->tpl_vars['is_sendemail']->value == '1') {?> checked<?php }?>>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="SENDEMAIL_YES_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">YES</label>
				<input type="radio" class="btn-check js__fund-filter-list" name="is_sendemail" id="SENDEMAIL_NO_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="0" autocomplete="off"<?php if ($_smarty_tpl->tpl_vars['is_sendemail']->value == '0') {?> checked<?php }?>>
				<label data-toggle="ripple" class="btn fs-13 text-nowrap btn-outline-default" for="SENDEMAIL_NO_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">NO</label>
			</div>
		</div>
	</div> -->
	<hr class="my-3" />
	<input type="hidden" name="filter" value="filter" />
	<div class="d-flex align-items-center justify-content-between">
		<div class="d-flex align-items-center gap-2">
			<button type="submit" class="btn btn-primary"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-search','Áp dụng');?>
</button>
			<button type="reset"  class="btn btn-warning"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-refresh','Xóa');?>
</button>
		</div>
		<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT') || $_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>
		<div class="form-check form-switch cursor-pointer">
			<input type="checkbox" class="form-check-input w-px-50 me-2" name="is_action_visible" 
				onChange="$Core.billing.set_action_visible(this, event)"<?php if ($_smarty_tpl->tpl_vars['billing_configs']->value['is_action_visible'] == '1') {?> checked<?php }?> value="1">
			<label class="form-check-label"><?php if ($_smarty_tpl->tpl_vars['billing_configs']->value['is_action_visible'] == '1') {?>Ẩn<?php } else { ?>Thêm<?php }?> công cụ</label>
		</div>
		<?php }?>
	</div>
</div>
<?php }
}
