<?php
/* Smarty version 3.1.33, created on 2026-08-07 14:01:16
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/staff.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7582bc8f9922_64806638',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '41a094b158a6a4937bc610a1afb8248c360fc455' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/staff.tpl',
      1 => 1786086071,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7582bc8f9922_64806638 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<form method="POST" class="w-100">
		<div class="d-flex flex-wrap mb-2 align-items-center justify-content-between">
			<div class="mb-2 mb-lg-0">
				<h4 class="fw-bold mb-1">Danh sách nhân viên</h4>
				<p class="text-muted mb-0">Tộng cộng 
					<strong class="text-main total_record"><?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
</strong> nhân viên </p>
			</div>
			<div class="d-flex justify-content-end xs:w-100 gap-1 align-items-center">
				<div class="input-group">
					<input type="text" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" class="form-control no-focus" 
					placeholder="Nhập từ khoá..." />
					<div class="btn-group dropdown">
						<button type="button" class="btn btn-icon btn-outline-default hide-arrow dropdown-toggle no-radius-left no-border-left" data-bs-toggle="dropdown" data-toggle="ripple" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
						<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-menu-end w-px-350" 
						data-popper-placement="bottom-end">
							<div class="p-3">
								<?php if ($_smarty_tpl->tpl_vars['is_access_full']->value == '1') {?>
									<div class="form-froup form-row mb-2">
										<div class="col-6 flex-fill">
											<div class="form-label mb-1">Phòng ban</div>
											<select name="department_id" class="iso-select2" data-width="100%" 
											data-placeholder="Phòng ban" data-allow-clear="false" onChange="$Core.member.select_team(this, event)">
												<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_DEPARTMENT',$_smarty_tpl->tpl_vars['department_id']->value,'Phòng ban');?>

											</select>
										</div>
										<div class="col-6 d-none">
											<div class="form-label mb-1">Đội/Nhóm</div>
											<select name="team_id" class="iso-select2" data-width="100%" 
											data-placeholder="Đội nhóm" data-allow-clear="false">
												<option value="0">Đội nhóm</option>
												<?php if (!empty($_smarty_tpl->tpl_vars['department_id']->value) && !empty($_smarty_tpl->tpl_vars['list_teams']->value)) {?>
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_teams']->value, '_oTeam');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oTeam']->value) {
?>
													<option value="<?php echo $_smarty_tpl->tpl_vars['_oTeam']->value['property_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['_oTeam']->value['property_id'] == $_smarty_tpl->tpl_vars['team_id']->value) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oTeam']->value['title'];?>
</option>
													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												<?php }?>
											</select>
										</div>
									</div>
									<?php if (!empty($_smarty_tpl->tpl_vars['lstGroupProfile']->value)) {?>
									<div class="form-froup mb-2">
										<div class="form-label mb-1">Nhóm nhân viên</div>
										<select name="group_profile_id" class="iso-select2" data-width="100%" 
										data-placeholder="Nhóm nhân viên" data-allow-clear="false">
											<option value="0">Nhóm nhân viên</option>
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstGroupProfile']->value, '_oGroup');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oGroup']->value) {
?>
												<option value="<?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['group_profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['title'];?>
</option>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										</select>
									</div>
									<?php }?>
									<div class="form-froup mb-2">
										<div class="form-label mb-1">Vai trò/ Quyền hạn</div>
										<select data-width="100%" data-placeholder="Vai trò/ Quyền hạn" 
										data-allow-clear="false" name="role_ids[]" class="iso-select2" multiple>
											<option value="0">Vai trò/ Quyền hạn</option>
											<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_ROLE',$_smarty_tpl->tpl_vars['role_ids']->value,'Vai trò/ Quyền hạn');?>

										</select>
									</div>
								<?php } elseif ($_smarty_tpl->tpl_vars['is_dir_sales']->value == '1') {?>
									<div class="form-froup mb-2 d-none">
										<div class="form-label mb-1">Đội/Nhóm</div>
										<select name="team_id" class="iso-select2" data-width="100%" 
										data-placeholder="Đội nhóm" data-allow-clear="false">
											<option value="0">Đội nhóm</option>
											<?php if (!empty($_smarty_tpl->tpl_vars['list_teams']->value)) {?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_teams']->value, '_oTeam');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oTeam']->value) {
?>
												<option value="<?php echo $_smarty_tpl->tpl_vars['_oTeam']->value['property_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['_oTeam']->value['property_id'] == $_smarty_tpl->tpl_vars['team_id']->value) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oTeam']->value['title'];?>
</option>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											<?php }?>
										</select>
									</div>
								<?php }?>
								<div class="form-row">
									<div class="col-12 mb-2">
										<div class="form-label mb-1">Tình trạng</div>
										<select name="status_ids[]" class="iso-select2" data-width="100%" data-placeholder="Tình trạng" data-allow-clear="false" multiple >
											<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_STATUS_STAFF',$_smarty_tpl->tpl_vars['status_ids']->value,'Tình trạng');?>

										</select>
										
									</div>
									<div class="col-12 mb-2">
										<div class="form-label mb-1">Lựa chọn ngày</div>
										<select class="form-control search_field form-select" data-field="date_field" name="date_field">
											<option<?php if ($_smarty_tpl->tpl_vars['date_field']->value == 'reg_date') {?> selected<?php }?> value="reg_date">Ngày tạo</option>
											<option<?php if ($_smarty_tpl->tpl_vars['date_field']->value == 'start_date') {?> selected<?php }?> value="start_date">Ngày vào làm việc</option>
											<option<?php if ($_smarty_tpl->tpl_vars['date_field']->value == 'birthday') {?> selected<?php }?> value="birthday">Ngày sinh nhật</option>
										</select>
									</div>
								</div>
								<hr class="my-2">
								<div class="form-row mb-2">
									<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
									<div class="col-6 col-md-6">
										<div class="form-floating">
											<input type="date" class="form-control search_field" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" 
											data-field="start_date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" name="start_date" placeholder="dd/mm/yy" aria-describedby="uid">
											<label for="uid">Từ ngày</label>
										</div>
									</div>
									<div class="col-6 col-md-6">
										<div class="form-floating">
											<input type="date" class="form-control search_field" data-field="to_date" 
											id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="to_date" value="<?php echo $_smarty_tpl->tpl_vars['to_date']->value;?>
" placeholder="dd/mm/yy" aria-describedby="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" >
											<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Tới ngày</label>
										</div>
									</div>
								</div>
								<hr class="my-2">
								<div class="form-group">
									<button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
									<button type="reset" class="btn btn-warning"><i class="bx bx-refresh"></i> Xóa</button>
								</div>
							</div>
						</div> 
					</div>
				</div>
				<input type="hidden" name="filter" value="filter" />
				<button data-toggle="ripple" type="submit" class="btn btn-icon btn-outline-default no-wrap">
					<i class="bx bx-search"></i> 
				</button>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('add_staff')) {?>
				<button type="button" class="btn btn-success<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-icon<?php }?> js__staff-new text-nowrap" onClick="$Core.member.open(this,event)" data-type="open" data-group_id="0"><i class="bx bx-plus"></i><?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?> Thêm<?php }?></button>
				<?php }?>
				<div  class="btn-group dropdown">
					<button data-toggle="ripple" type="button" class="btn btn-icon btn-outline-default hide-arrow dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-cog"></i></button>
					<ul class="dropdown-menu" data-popper-placement="bottom-end">
						<li><a class="dropdown-item cursor-pointer js__group-manager" onClick="$Core.member.manage_group(this,event)" 
							data-type="open" data-group_id="0">
							<div class="d-flex gap-2">
								<i class="bx bx-group mt-2"></i>
								<div class="d-flex flex-column">
									<strong>Quản lý nhóm</strong>
									<span class="text-muted text-fs-12">Đội nhóm nội bộ</span>
								</div>
							</div>
						</a></li>
						<li><a class="dropdown-item cursor-pointer" onClick="$Core.member.trans_to_dept(this,event)">
							<div class="d-flex gap-2">
								<i class="bx bx-move-horizontal mt-2"></i>
								<div class="d-flex flex-column">
									<strong>Chuyển phòng ban</strong>
									<span class="text-muted text-fs-12">Chuyển nhân sự các phòng ban</span>
								</div>
							</div>
						</a></li>
						<!-- <hr class="dropdown-divider" />
						<li><a class="dropdown-item cursor-pointer" href="/member/report.html">
							<div class="d-flex gap-2">
								<i class="bx bx-bar-chart-alt mt-2"></i>
								<div class="d-flex flex-column">
									<strong>Báo cáo nhân sự</strong>
									<span class="text-muted text-fs-12">Báo cáo tổng hợp nhân sự</span>
								</div>
							</div>
						</a></li>
						<hr class="dropdown-divider" />
						<li><a class="dropdown-item cursor-pointer" href="/member/export-loyalty.html">
							<div class="d-flex gap-2">
								<i class="bx bx-bar-chart-alt mt-2"></i>
								<div class="d-flex flex-column">
									<strong>Export</strong>
									<span class="text-muted text-fs-12">Điểm Loyalty</span>
								</div>
							</div>
						</a></li> -->
					</ul>
				</div>
			</div>
		</div>
	</form>
    <!-- Basic Bootstrap Table -->
    <div class="card">
		<div class="card-body">
			<?php if ($_smarty_tpl->tpl_vars['is_access_full']->value == '1') {?>
			<div class="briefs mb-3 gap-2 gap-xxl-3 d-flex flex-wrap">
				<div class="brief-item a1a bg-orange">
					<p class="fs-16 mb-2">Tổng nhân viên</p>
					<h3 class="fs-32 mb-1 text-white"><?php echo $_smarty_tpl->tpl_vars['arr_totals']->value['total_staff'];?>
</h3>
				</div>
				<div class="brief-item a2a bg-azure">
					<p class="fs-16 mb-2">Đang làm việc</p>
					<h3 class="fs-32 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['arr_totals']->value['total_on'];?>
</h3>
				</div>
				<div class="brief-item a3a bg-cyan">
					<p class="fs-16 mb-2">Đã nghỉ</p>
					<h3 class="fs-32 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['arr_totals']->value['total_off'];?>
</h3>
				</div>
				<div class="brief-item a4a bg-danger">
					<p class="fs-16 mb-2">Khối kinh doanh</p>
					<h3 class="fs-32 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['arr_totals']->value['total_sale'];?>
</h3>
				</div>
				<div class="brief-item a5a bg-purple">
					<p class="fs-16 mb-2">Khối văn phòng</p>
					<h3 class="fs-32 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['arr_totals']->value['total_bo'];?>
</h3>
				</div>
				<div class="brief-item a6a bg-green">
					<p class="fs-16 mb-2">Gần nhất</p>
					<h3 class="fs-32 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['arr_totals']->value['total_growth'];?>
</h3>
				</div>
			</div>
			<hr class="my-3" />
			<?php }?>
			<div class="table-container overflow-x-auto text-nowrap no-shadow">
				<table cellpadding="0" cellspacing="0" class="table dragable table-staff table-striped table-borderd">
					<thead><tr>
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
						<th class="algin-center h-px-35 bg-lighter" width="50px">Mã NV</th><?php }?>
						<th class="algin-center h-px-35 bg-lighter text-left">Họ và tên</th>
						<th class="algin-center h-px-35 bg-lighter text-left">Phòng ban</th>
						<th class="algin-center h-px-35 bg-lighter text-left">Vai trò</th>
						<!--<th class="algin-center h-px-35 bg-lighter text-right w-px-200">Doanh số</th>
						<th class="algin-center h-px-35 bg-lighter text-left">GD gần nhất</th>-->
						<!-- <th class="algin-center h-px-35 bg-lighter text-left">FPoint</th> -->
						<th class="algin-center h-px-35 bg-lighter text-left">Ngày vào</th>
						<th class="algin-center h-px-35 bg-lighter text-left">T.Trạng</th>
						<th class="algin-center h-px-35 bg-lighter text-left">Ngày nghỉ việc</th>
						<?php if ($_smarty_tpl->tpl_vars['permiss_login']->value == '1') {?>
						<th class="algin-center h-px-35 bg-lighter text-center">Login</th>
						<?php }?>
						<th class="algin-center h-px-35 bg-lighter text-left">Ngày tạo</th>
						<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
						<th class="algin-center h-px-35 bg-lighter text-left">Version</th>
						<?php }?>
						<th class="algin-center h-px-35 bg-lighter text-left w-px-50"></th>
					</tr></thead>
					<tbody class="table-border-bottom-0">
						<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_staffs']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<?php $_smarty_tpl->_assignInScope('_oneStaff', $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]);?>
						<?php $_smarty_tpl->_assignInScope('_profile_id', $_smarty_tpl->tpl_vars['_oneStaff']->value['profile_id']);?>
						<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oneStaff']->value['more_information']);?>
						<tr class="trUser" profile_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
">

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
							<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['code'];?>
</td><?php }?>
							<td class="text-left">
								<a href="javascript:void(0);" onClick="$Core.member.view_profile(this, event)" 
								profile_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" class="d-flex gap-1 align-items-center">
									<div class="d-none d-lg-block avatar avatar-xxs position-relative rounded-pill">
										<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_profile_id']->value,$_smarty_tpl->tpl_vars['_oneStaff']->value,40,40);?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" />
										<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->get_icon_verified($_smarty_tpl->tpl_vars['_profile_id']->value,$_smarty_tpl->tpl_vars['_oneStaff']->value['more_information']);?>

									</div>
									<strong><?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['full_name'];?>
</strong> <?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['state_name'];?>

								</a>
							</td>
							<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_more_information']->value['department_name'];?>

							</td>
							<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_more_information']->value['role_name'];?>
</td>
							<!-- <td class="text-right">
								<?php if ($_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['total_billings'] != '0') {?>
									<?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['total_billings'];?>

								<?php } else { ?>
									<?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['total_billings'];?>
 <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>

								<?php }?>
							</td>
							<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['deposit_date'];?>
</td>
							<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray(@constant('_PROFILE_SUPPER_ID'),$_smarty_tpl->tpl_vars['_profile_id']->value)) {?>
							<td staff_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" class="text-left cursor-pointer text-danger">
								<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/point.png" width="12px" />
								<strong>Ultimate</strong>
							</td>
							<?php } else { ?>
							<td onClick="$Core.global.open_Lpoint(this, event)" staff_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" 
								class="text-left cursor-pointer text-danger">
								<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/point.png" width="12px" />
								<strong><?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['total_Lpoint'];?>
</strong>
							</td>
							<?php }?>-->	
							<td class="text-left">
								<?php if (!empty($_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['start_date'])) {?>
									<i class="material-icons-outlined">schedule</i>
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['start_date']);?>

								<?php } else { ?>
									--
								<?php }?>
							</td>
							<td class="text-left">
								<span class="badge bg-label-<?php if ($_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['status_id'] == @constant('_STATUS_STAFF_OFF_ID')) {?>danger<?php } else { ?>primary<?php }?> me-1">
									<?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['status_name'];?>

								</span>
							</td>
							<td class="text-left">
								<?php if (!empty($_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['end_date']) && $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['status_id'] == @constant('_STATUS_STAFF_OFF_ID')) {?>
									<i class="material-icons-outlined">schedule</i>
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['end_date']);?>

								<?php } else { ?>
									--
								<?php }?>
							</td>
							<?php if ($_smarty_tpl->tpl_vars['permiss_login']->value == '1' || $_smarty_tpl->tpl_vars['profile_id']->value == 289) {?>
							<td class="text-center">
								<form action="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/login-redirect.html" target="_blank" method="post" enctype="multipart/form-data">
									<input type="hidden" name="login_step" value="login">
									<input type="hidden" name="USER" value="<?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_name'];?>
">
									<input type="hidden" name="PASSWORD" value="<?php echo $_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_pass'];?>
">
									<button<?php if ($_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['status_id'] == @constant('_STATUS_STAFF_OFF_ID')) {?> disabled<?php }?> class="btn btn-xs btn-outline-success"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-play','Đăng nhập');?>
</button>
								</form>
							</td>
							<?php }?>
							<td class="text-left">
								<i class="material-icons-outlined">more_time</i>
								<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['list_staffs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['reg_date'],true);?>

							</td>
							<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
							<td class="text-center">
								<label class="switch">
									<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['is_active_new_version'] == '1') {?> checked="checked"<?php }?> onChange="$Core.member.active_new_version(this, event)" profile_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" value="1">
									<span class="slider round"></span>
								</label>
							</td>
							<?php }?>
							<td class="text-center w-px-50">
								<a class="btn d-flex align-items-center justify-content-center btn-outline-default btn-icon btn-sm" 
									onClick="$Core.member.view_profile(this, event)" profile_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" href="javascript:void(0);">
									<i class="bx bxs-show"></i>
								</a>
								<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray(@constant('_PROFILE_SUPPER_ID'),$_smarty_tpl->tpl_vars['profile_id']->value)) {?>
								<!-- <a class="dropdown-item" onClick="$Core.member.recalc_loyalty(this, event)" profile_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" href="javascript:void(0);">
									<i class="bx bx-refresh me-1"></i>
								</a> -->
								<?php }?>
							</td>
						</tr>
						<?php
}
}
?>
					</tbody>
				</table>
			</div>
			<?php if ($_smarty_tpl->tpl_vars['total_page']->value > '1') {?>
			<div id="pager" class="d-flex justify-content-center mt-3">
				<ul class="pagination"><?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>
</ul>
			</div>
			<?php }?>
		</div>
    </div>
    <!--/ Basic Bootstrap Table -->
</div>

<style type="text/css">
	@media screen and (min-width:648px){
		.table-container .table-staff tr td:nth-child(2){
			position:sticky;
			left:64px; top:0;
		}
		.table-container .table-staff tr:nth-child(even) td:nth-child(2){
			background:var(--bs-white) !important;
		}
		.table-container .table-staff tr:nth-child(odd) td:nth-child(2){
			background:rgb(249,250,251);
		}
	}
	.modal-header>.modal-header__left .modal-title:before{
		left:-61px;
	}
</style>
<?php echo '<script'; ?>
 type="text/javascript">
$(function(){
	$('select[name=department_id]').change(function(){
		var $_this = $(this);
		$.post(path_ajax_script+"/index.php?mod="+MOD+"&act=load_option_role", {
			'department_id' : $_this.val(),"type":"show_all"
		}, function(html){
			$('select[name="role_ids[]"]').html(html);
		});
	});
});
<?php echo '</script'; ?>
>

<?php }
}
