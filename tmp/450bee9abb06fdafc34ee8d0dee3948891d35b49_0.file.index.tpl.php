<?php
/* Smarty version 3.1.33, created on 2026-08-07 10:16:02
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/home_screen_sale/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a754df28ef485_39586608',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '450bee9abb06fdafc34ee8d0dee3948891d35b49' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/home_screen_sale/index.tpl',
      1 => 1785927040,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a754df28ef485_39586608 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="form-row">
	<div class="col-12 col-lg-8 mb-2 mb-lg-0">
		<div class="sticky">		
			<div class="card dsx-shell mb-2">
				<div class="dsx">
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<div class="dsx-head">
						<span class="dsx-eyebrow"><i class="bx bx-line-chart"></i>Doanh số bán hàng</span>
						<div class="input-group-control">
							<div class="input-group d-flex w-px-175" role="group" aria-label="Lọc thời gian">
								<select class="form-control form-control-sm form-select" name="month" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
"
								onChange="$Core.dashboard.reload(this,event)">
									<option value="">Tháng</option>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_months']->value, '_month');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_month']->value) {
?>
									<option value="<?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
">Tháng <?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
</option>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</select>
								<select class="form-control form-control-sm form-select" name="year" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
"
								onChange="$Core.dashboard.reload(this,event)">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_years']->value, '_year');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_year']->value) {
?>
									<option<?php if ($_smarty_tpl->tpl_vars['_year']->value == smarty_modifier_date_format(time(),"%Y")) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
</option>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</select>
							</div>
						</div>
					</div>
					<div class="dsx-grid ajax" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=load_person_dept_sales"
						data-options='{}'>
						<div class="dsx-seg">
							<div class="animate-bg rounded-2 h-px-35 w-100 mb-2"></div>
							<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
							<div class="animate-bg rounded-2 h-px-20 w-100"></div>
						</div>
						<div class="dsx-seg">
							<div class="animate-bg rounded-2 h-px-35 w-100 mb-2"></div>
							<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
							<div class="animate-bg rounded-2 h-px-20 w-100"></div>
						</div>
					</div>
				</div>
			</div>	
			<div class="dbx-card mb-2">
				<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<div class="dbx-card__head">
					<span class="dbx-card__ic"><i class="bx bx-receipt"></i></span>
					<div class="dbx-card__ttl">
						<h5 class="dbx-card__title mb-0">Giao dịch <?php echo $_smarty_tpl->tpl_vars['department_name']->value;?>
</h5>
						<small class="dbx-card__sub">Tăng trưởng số lượng giao dịch</small>
					</div>
					<div class="input-group-control">
						<div class="input-group d-flex w-px-175" role="group" aria-label="Sắp xếp">
							<select class="form-control form-control-sm form-select" name="month" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" 
							onChange="$Core.dashboard.reload(this,event)"> 
								<option value="">Tháng</option>			
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_months']->value, '_month');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_month']->value) {
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
">Tháng <?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
</option>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</select>
							<select class="form-control form-control-sm form-select" name="year" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" 
							onChange="$Core.dashboard.reload(this,event)">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_years']->value, '_year');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_year']->value) {
?>
								<option<?php if ($_smarty_tpl->tpl_vars['_year']->value == smarty_modifier_date_format(time(),"%Y")) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
</option>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</select>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="ajax" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=load_billing_chart" 
						data-options='{}'>
						<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
					</div>
				</div>
			</div>
			<div class="dbx-card mb-2">
				<div class="dbx-card__head">
					<span class="dbx-card__ic"><i class="bx bx-group"></i></span>
					<h5 class="dbx-card__title mb-0">Hoạt động check-in</h5>
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/net-dep-lao-dong.html" title="Xem tất cả" class="btn btn-icon btn-sm btn-link rounded-pill">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
				</div>
				<div class="card-body ajax" data-bind="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_checkin_activity&holderG=_sale_director" data-options="{}">
					<div class="form-row">
					<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
						<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloader']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 3);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<div class="col-4">
							<div class="animate-bg rounded-2 w-100 h-px-100"></div>
						</div>
						<?php
}
}
?>
					<?php } else { ?>
						<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloader']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = min(($__section_i_1_loop - 0), 6);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<div class="col-2">
							<div class="animate-bg rounded-2 w-100 h-px-175"></div>
						</div>
						<?php
}
}
?>
					<?php }?>
					</div>
				</div>
			</div>
			<div class="dbx-card mb-2">
				<div class="dbx-card__head">
					<span class="dbx-card__ic"><i class="bx bx-transfer-alt"></i></span>
					<h5 class="dbx-card__title mb-0">Giao dịch mới nhất</h5>
					<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('billing');?>
" class="btn btn-icon btn-sm btn-link rounded-pill" title="Xem giao dịch">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
				</div>
				<div class="dbx-card__body dbx-card__body--flush">
					<div class="table-container overflow-x-auto no-shadow text-nowrap">
						<table cellpadding="0" cellspacing="0" class="table text-nowrap">
							<thead><tr>
								<th class="align-center h-px-35 bg-lighter">Mã căn</th>
								<th class="align-center h-px-35 bg-lighter">Ngày cọc</th>
								<th class="align-center h-px-35 bg-lighter">Sale bán</th>
								<th class="align-center h-px-35 bg-lighter">Dự án</th>
								<th class="align-center h-px-35 bg-lighter">Phân khu</th>
								<th class="align-center h-px-35 bg-lighter">Loại hình</th>
								<th class="align-center h-px-35 bg-lighter text-right">Tổng tiền</th>
							</tr></thead>
							<tbody class="ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=load_billing" 
								data-options='{"call_from":"_dashboard"}'>
								<?php
$__section_i_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloader']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_2_total = $__section_i_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_2_total !== 0) {
for ($__section_i_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_2_iteration <= $__section_i_2_total; $__section_i_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
								<tr>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								</tr>
								<?php
}
}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-xxl-6 mb-2 mb-xxl-0">
					<div class="dbx-card h-100">
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-history"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title mb-1">Lịch sử tra cứu</h5>
								<small class="dbx-card__sub">Lịch sử tra cứu gần nhất</small>
							</div>
							<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('log-sale');?>
" class="btn btn-icon btn-sm btn-link rounded-pill">
								<i class="bx bx-link-external text-fs-14 text-muted"></i>
							</a>
						</div>
						<div class="dbx-card__body dbx-card__body--flush">
							<div id="history_search" class="table-container no-shadow overflow-auto max-height-400">
								<table cellpadding="0" cellspacing="0" class="table table-bordered text-nowrap">
									<thead class="position-sticky top-0 zindex-3"><tr>
										<th class="align-center h-px-35 bg-lighter">Nhân viên</th>
										<th class="align-center h-px-35 bg-lighter">Thời gian</th>
										<th class="align-center h-px-35 bg-lighter">Mã căn</th>
									</tr></thead>
									<tbody class="table-border-bottom-0 ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=load_sale_logs" 
										data-option="{}">
										<?php
$__section_i_3_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloader']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_3_total = $__section_i_3_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_3_total !== 0) {
for ($__section_i_3_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_3_iteration <= $__section_i_3_total; $__section_i_3_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
										<tr>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
										</tr>
										<?php
}
}
?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- <div class="card ">
						<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
						<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
							<div class="card-title ox:sm-1">
								<h5 class="mb-1 me-2">Vắng mặt <?php echo $_smarty_tpl->tpl_vars['department_name']->value;?>
</h5>
								<small class="d-block text-nowrap">Nhấn 
									<a href="javascript:void(0)" onclick="$Core.worktime.open(this, event)"><u>Thêm</u></a> bắt đầu thêm báo cáo
								</small>
							</div>
							<div class="p-right">
								<div class="input-group d-flex w-px-175" role="group" aria-label="Sắp xếp">
									<select class="form-control form-select" name="month" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onChange="$Core.dashboard.reload(this,event)"> 
										<option value="">Tháng</option>			
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_months']->value, '_month');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_month']->value) {
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
">Tháng <?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
</option>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</select>
									<select class="form-control form-select" name="year" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onChange="$Core.dashboard.reload(this,event)">
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_years']->value, '_year');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_year']->value) {
?>
										<option<?php if ($_smarty_tpl->tpl_vars['_year']->value == smarty_modifier_date_format(time(),"%Y")) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
</option>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</select>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="overflow-y-auto">
								<table class="table" width="100%" cellpadding="0" cellspacing="0">
									<thead><tr>
										<th class="align-center">Nhân viên</th>
										<th width="68px" class="align-center text-center">Tổng</th>
										<td width="30px" class="align-center"></td>
									</tr></thead>
									<tbody class="ajax" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=load_report_worktime" data-options="{}">
										<?php
$__section_i_4_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloader']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_4_total = $__section_i_4_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_4_total !== 0) {
for ($__section_i_4_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_4_iteration <= $__section_i_4_total; $__section_i_4_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
										<tr>
											<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
										</tr>
										<?php
}
}
?>
									</tbody>
								</table>
							</div>
						</div>
					</div> -->
				</div>
				<div class="col-12 col-xxl-6 mb-2 mb-xxl-0">
					<div class="dbx-card">
						<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-group"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title mb-1 text-nowrap">Nhân viên <?php echo $_smarty_tpl->tpl_vars['department_name']->value;?>
</h5>
								<small class="d-block text-nowrap">Số nhân viên <?php echo $_smarty_tpl->tpl_vars['department_name']->value;?>
: 
									(<strong class="text-main total_staffs">0</strong>)
								</small>
							</div>
							<div class="p-right">
								<div class="input-group d-flex w-px-150" role="group" aria-label="Sắp xếp">
									<select class="form-control form-control-sm form-select" name="month" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" 
									onChange="$Core.dashboard.reload(this,event)"> 
										<option value="">Tháng</option>			
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_months']->value, '_month');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_month']->value) {
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
">Tháng <?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
</option>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</select>
									<select class="form-control form-control-sm form-select" name="year" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" 
									onChange="$Core.dashboard.reload(this,event)">
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_years']->value, '_year');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_year']->value) {
?>
										<option<?php if ($_smarty_tpl->tpl_vars['_year']->value == smarty_modifier_date_format(time(),"%Y")) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
</option>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</select>
								</div>
							</div>
						</div>
						<div class="dbx-card__body dbx-card__body--flush">
							<div class="table-container no-shadow max-height-400 overflow-auto" >
								<table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
									<thead class="position-sticky top-0 zindex-3"><tr>
										<th class="align-center bg-lighter h-px-35">Nhân viên</th>
										<th class="align-center bg-lighter h-px-35 text-center">Số GD</th>
										<th class="align-center bg-lighter h-px-35">Doanh số</th>
									</tr></thead>
									<tbody class="text-nowrap ajax" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options="{}" 
										data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=load_staffs">
										<?php
$__section_i_5_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloader']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_5_total = min(($__section_i_5_loop - 0), 10);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_5_total !== 0) {
for ($__section_i_5_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_5_iteration <= $__section_i_5_total; $__section_i_5_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
										<tr>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
										</tr>
										<?php
}
}
?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-xxl-6 mb-2 mb-xxl-0">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_billing');?>

				</div>
				<div class="col-12 col-xxl-6">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_staff');?>

				</div>
			</div>
		</div>
	</div>
	<!--/ Conversion rate -->
	<div class="col-12 col-lg-4">
		<div class="mb-2">
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('home_course');?>

		</div>
		<!-- Xác nhận thay đổi GD -->
		<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("home_billing_confirm");?>

		<!--- Target -->
		<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("target_sales");?>

		<!-- Meta -->
		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('note_calendar');?>

						<div class="card ranking mb-2">
				<div class="card-body">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_ranking');?>

				</div>
			</div>
			<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('ranking_dept',array('gId'=>$_smarty_tpl->tpl_vars['gId']->value));?>

			<!-- <?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_ranker',array('gId'=>$_smarty_tpl->tpl_vars['gId']->value));?>
 -->	
		<?php }?>
		<div class="dbx-card mt-2">
			<div class="dbx-card__head">
				<h5 class="dbx-card__title d-flex align-items-center mb-0">
					<img class="mr-2" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/birthday.png" width="20" /> 
					<span>Chúc mừng sinh nhật</span>
				</h5>
			</div>
			<div class="card-body">
				<div id="birthday_staffs" class="overflow-hidden">
					<div class="ajax" data-bind="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=staff_birthday" 
						data-options='{"department_id":<?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['department_id'];?>
}'>
						<div class="loader text-center py-3">
							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/loading.gif" width="66px" />
							<p>Loading...</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.avatar-bg{ background-size: cover;}
</style>
<?php }
}
