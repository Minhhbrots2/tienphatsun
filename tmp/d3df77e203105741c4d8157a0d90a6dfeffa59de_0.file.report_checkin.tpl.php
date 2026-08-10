<?php
/* Smarty version 3.1.33, created on 2026-08-05 16:13:32
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/report_checkin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72febcae8c22_01498777',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd3df77e203105741c4d8157a0d90a6dfeffa59de' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/report_checkin.tpl',
      1 => 1784299670,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72febcae8c22_01498777 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/daterangepicker/daterangepicker.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/daterangepicker/moment.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/daterangepicker/daterangepicker.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>

<div class="container-xxl flex-grow-1 pt-2 container-p-y">

		<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
		<div>
			<h4 class="fw-bold mb-1">Tổng quan Check-in</h4>
			<p class="text-muted mb-0">
				<span class="txt_time">Hôm nay <?php echo smarty_modifier_date_format(time(),"%d/%m/%Y");?>
</span>
			</p>
		</div>
		<div class="d-flex gap-2 align-items-center flex-wrap">
						<div class="d-none d-lg-flex gap-2 align-items-center flex-wrap" id="filter_inline">
				<div class="input-group" style="width:240px">
					<span class="input-group-text"><i class="bx bx-calendar"></i></span>
					<input type="text" class="form-control" id="checkin_daterange" name="date_range"
						placeholder="Khoảng thời gian" autocomplete="off" readonly
						value="<?php echo smarty_modifier_date_format(time(),'%d/%m/%Y');?>
 - <?php echo smarty_modifier_date_format(time(),'%d/%m/%Y');?>
">
				</div>
				<?php if ($_smarty_tpl->tpl_vars['filter_mode']->value == 'director') {?>
				<select class="form-select w-px-150" id="checkin_region_filter" style="min-width:140px">
					<option value="0">-- Tất cả --</option>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_regions']->value, '_r');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_r']->value) {
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['_r']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_r']->value['title'];?>
</option>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</select>
				<select class="form-select w-px-150" id="checkin_dept_filter" style="min-width:150px">
					<option value="0">-- Tất cả khối --</option>
				</select>
				<?php } elseif ($_smarty_tpl->tpl_vars['filter_mode']->value == 'region') {?>
				<select class="form-select w-px-150" id="checkin_dept_filter" style="min-width:150px">
					<option value="0">-- Tất cả phòng --</option>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_region_depts']->value, '_dep');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_dep']->value) {
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['_dep']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_dep']->value['title'];?>
</option>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</select>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['is_director']->value) {?>
				<select class="form-select w-px-150" id="checkin_group_filter" style="min-width:140px">
					<option value="0">-- Tất cả nhóm --</option>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_groups']->value, '_grp');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_grp']->value) {
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['_grp']->value['group_profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_grp']->value['title'];?>
</option>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</select>
				<?php }?>
			</div>
						<button class="btn btn-outline-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#checkin_filter_canvas">
				<i class="bx bx-filter-alt me-1"></i>Bộ lọc
			</button>
		</div>
	</div>

		<div class="offcanvas offcanvas-start" tabindex="-1" id="checkin_filter_canvas">
		<div class="offcanvas-header">
			<h5 class="offcanvas-title">Bộ lọc</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
		</div>
		<div class="offcanvas-body d-flex flex-column gap-3">
			<div>
				<label class="form-label fw-semibold">Khoảng thời gian</label>
				<div class="input-group">
					<span class="input-group-text"><i class="bx bx-calendar"></i></span>
					<input type="text" class="form-control" id="checkin_daterange_mobile" name="date_range"
						placeholder="Khoảng thời gian" autocomplete="off" readonly
						value="<?php echo smarty_modifier_date_format(time(),'%d/%m/%Y');?>
 - <?php echo smarty_modifier_date_format(time(),'%d/%m/%Y');?>
">
				</div>
			</div>
			<?php if ($_smarty_tpl->tpl_vars['filter_mode']->value == 'director') {?>
			<div>
				<label class="form-label fw-semibold">Vùng</label>
				<select class="form-select" id="checkin_region_filter_mobile">
					<option value="0">-- Tất cả vùng --</option>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_regions']->value, '_r');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_r']->value) {
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['_r']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_r']->value['title'];?>
</option>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</select>
			</div>
			<div>
				<label class="form-label fw-semibold">Phòng</label>
				<select class="form-select" id="checkin_dept_filter_mobile">
					<option value="0">-- Tất cả phòng --</option>
				</select>
			</div>
			<?php } elseif ($_smarty_tpl->tpl_vars['filter_mode']->value == 'region') {?>
			<div>
				<label class="form-label fw-semibold">Phòng</label>
				<select class="form-select" id="checkin_dept_filter_mobile">
					<option value="0">-- Tất cả phòng --</option>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_region_depts']->value, '_dep');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_dep']->value) {
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['_dep']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_dep']->value['title'];?>
</option>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</select>
			</div>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['is_director']->value) {?>
			<div>
				<label class="form-label fw-semibold">Nhóm nhân viên</label>
				<select class="form-select" name="group_id" id="checkin_group_filter_mobile">
					<option value="0">-- Tất cả nhóm --</option>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_groups']->value, '_grp');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_grp']->value) {
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['_grp']->value['group_profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_grp']->value['title'];?>
</option>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</select>
			</div>
			<?php }?>
			<button class="btn btn-primary" onclick="$Core.report_checkin.load_all({}); $('.offcanvas').offcanvas('hide');">
				<i class="bx bx-search me-1"></i>Áp dụng
			</button>
		</div>
	</div>

	<div class="clearfix"></div>

		<div id="holder_checkin_overview">
		<div class="form-row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-6 g-3 mb-3">
			<div class="col mb-2 flex-fill">
				<div class="card text-center py-3 px-2 h-100">
					<div class="card-body p-0">
						<i class="bx bx-user fs-2 text-main mb-1"></i>
						<p class="text-muted fs-12 mb-1">Tổng nhân sự</p>
						<h4 class="fw-bold text-main mb-0">--</h4>
					</div>
				</div>
			</div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-log-in fs-2 text-primary mb-1"></i><p class="text-muted fs-12 mb-1">Tổng lượt check-in</p><h4 class="fw-bold text-primary mb-0">--</h4></div></div></div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-user-check fs-2 text-success mb-1"></i><p class="text-muted fs-12 mb-1">Đã check-in</p><h4 class="fw-bold text-success mb-0">--</h4></div></div></div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-time-five fs-2 text-warning mb-1"></i><p class="text-muted fs-12 mb-1">Chưa check-in</p><h4 class="fw-bold text-warning mb-0">--</h4></div></div></div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-pie-chart-alt-2 fs-2 text-info mb-1"></i><p class="text-muted fs-12 mb-1">Tỉ lệ</p><h4 class="fw-bold text-info mb-0">--%</h4></div></div></div>
			<div class="col mb-2 flex-fill"><div class="card text-center py-3 px-2"><div class="card-body p-0"><i class="bx bx-buildings fs-2 text-secondary mb-1"></i><p class="text-muted fs-12 mb-1">VP hoạt động</p><h4 class="fw-bold text-secondary mb-0">--</h4></div></div></div>
		</div>
			</div>

		<div id="holder_checkin_region" class="mb-3">
		<div class="card">			
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<h5 class="mb-0"><i class="bx bx-map-alt text-primary me-1"></i>Theo dõi Check-in theo Vùng</h5>
			</div>
			<div class="card-body">
				<div class="text-center text-muted py-4">Đang tải...</div>
			</div>
		</div>
	</div>

		<div id="holder_checkin_chart" class="mb-3">
		<div class="card">
			<div class="card-body"><div class="text-center text-muted py-4">Đang tải...</div></div>
		</div>
	</div>

		<div id="holder_checkin_list">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<h5 class="mb-0"><i class="bx bx-map-pin text-primary me-1"></i>Check-in mới nhất</h5>
				<span class="badge bg-label-primary">13 lượt</span>
			</div>
			<div class="card-body">
				<div class="text-center text-muted py-4">Đang tải...</div>
			</div>
		</div>
	</div>

</div>

<?php echo '<script'; ?>
 type="application/json" id="checkin_region_map"><?php echo $_smarty_tpl->tpl_vars['region_dept_map_json']->value;
echo '</script'; ?>
>

<?php }
}
