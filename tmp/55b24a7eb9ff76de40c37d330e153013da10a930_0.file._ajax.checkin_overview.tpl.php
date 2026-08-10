<?php
/* Smarty version 3.1.33, created on 2026-08-07 17:38:00
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.checkin_overview.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75b588cbe485_04634202',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '55b24a7eb9ff76de40c37d330e153013da10a930' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.checkin_overview.tpl',
      1 => 1784299672,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75b588cbe485_04634202 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.math.php','function'=>'smarty_function_math',),));
?>

<div class="form-row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-6 g-3 mb-3">
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-user fs-2 text-main mb-1"></i>
				<p class="text-muted fs-12 mb-1">Tổng nhân sự</p>
				<h4 class="fw-bold text-main mb-0"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['_kpi']->value['total_profile'])===null||$tmp==='' ? 0 : $tmp);?>
</h4>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-log-in fs-2 text-primary mb-1"></i>
				<p class="text-muted fs-12 mb-1">Tổng lượt check-in</p>
				<h4 class="fw-bold text-primary mb-0"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['_kpi']->value['total'])===null||$tmp==='' ? 0 : $tmp);?>
</h4>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-user-check fs-2 text-success mb-1"></i>
				<p class="text-muted fs-12 mb-1">Đã check-in</p>
				<h4 class="fw-bold text-success mb-0"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['_kpi']->value['checked_in'])===null||$tmp==='' ? 0 : $tmp);?>
</h4>
				<a class="cursor-pointer text-decoration-underline" onClick="$Core.report_checkin.load_list_profile_checkin(this,event)" data-type="has_checkin" >Xem chi tiết</a>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-time-five fs-2 text-warning mb-1"></i>
				<p class="text-muted fs-12 mb-1">
					Chưa check-in
					<?php if (!empty($_smarty_tpl->tpl_vars['kpi_day_label']->value)) {?>
					<small class="d-block text-muted fs-11">(<?php echo $_smarty_tpl->tpl_vars['kpi_day_label']->value;?>
)</small>
					<?php }?>
				</p>
				<h4 class="fw-bold text-warning mb-0"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['_kpi']->value['not_checked_in'])===null||$tmp==='' ? 0 : $tmp);?>
</h4>
				<a class="cursor-pointer text-decoration-underline" onClick="$Core.report_checkin.load_list_profile_checkin(this,event)" data-type="not_checkin" >Xem chi tiết</a>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-pie-chart-alt-2 fs-2 text-info mb-1"></i>
				<p class="text-muted fs-12 mb-1">Tỉ lệ</p>
				<h4 class="fw-bold text-info mb-0"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['_kpi']->value['rate'])===null||$tmp==='' ? 0 : $tmp);?>
%</h4>
			</div>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card text-center py-3 px-2 h-100">
			<div class="card-body p-0">
				<i class="bx bx-buildings fs-2 text-secondary mb-1"></i>
				<p class="text-muted fs-12 mb-1">VP hoạt động</p>
				<h4 class="fw-bold text-secondary mb-0"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['_kpi']->value['active_offices'])===null||$tmp==='' ? 0 : $tmp);?>
</h4>
			</div>
		</div>
	</div>
</div>

<div id="checkin_chart_block">
<div class="form-row">
	<div class="col-12 col-md-6 col-lg-8 col-xxl-8 mb-3">
		<div class="card h-100" id="card_donut">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<h5 class="mb-0">Hoạt động theo địa điểm</h5>
				<div class="btn-group btn-group-sm">
					<button type="button" class="btn btn-outline-primary active px-1" id="donut_btn_office"
						onclick="$Core.report_checkin.set_donut_scope('office')">Văn phòng</button>
					<button type="button" class="btn btn-outline-primary px-1" id="donut_btn_dept"
						onclick="$Core.report_checkin.set_donut_scope('dept')">Phòng ban</button>
				</div>
			</div>
			<div class="card-body">
				<?php if ($_smarty_tpl->tpl_vars['_kpi']->value['total'] == 0) {?>
				<div class="text-center text-muted py-4">Chưa có dữ liệu</div>
				<?php } else { ?>
				<div class="row align-items-center">
					<div class="col-12 col-md-12 col-lg-6">
						<div id="checkin_donut_chart" style="min-height:280px"></div>
					</div>
					<div class="col-12 col-md-12 col-lg-6" id="checkin_donut_legend"></div>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-6 col-lg-4 col-xxl-4 mb-3">
		<div class="card h-100">
			<div class="card-header"><h5 class="mb-0"><i class="bx bx-trophy text-warning me-1"></i>Top hoạt động</h5></div>
			<div class="card-body p-0">
				<?php if (empty($_smarty_tpl->tpl_vars['list_top_active']->value)) {?>
				<div class="text-center text-muted py-4">Chưa có dữ liệu</div>
				<?php } else { ?>
				<ul class="list-group list-group-flush">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_top_active']->value, '_top', false, '_idx');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_idx']->value => $_smarty_tpl->tpl_vars['_top']->value) {
?>
					<li class="list-group-item d-flex align-items-center gap-2 py-2 px-3" onclick="$Core.report_checkin.load_profile_journey(this, event)" data-profile="<?php echo $_smarty_tpl->tpl_vars['_top']->value['profile_id'];?>
">
						<span class="fw-bold text-muted me-1" style="min-width:20px"><?php echo smarty_function_math(array('equation'=>"idx+1",'idx'=>$_smarty_tpl->tpl_vars['_idx']->value),$_smarty_tpl);?>
</span>
						<a href="javascript:void(0);" class="d-flex align-items-center gap-2 text-decoration-none flex-grow-1 text-dark">
							<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['_top']->value['avatar'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" width="36" height="36" alt="">
							<span class="fw-semibold text-truncate" style="max-width:150px" title="<?php echo $_smarty_tpl->tpl_vars['_top']->value['full_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['_top']->value['full_name'];?>
</span>
						</a>
						<span class="badge bg-label-primary ms-auto"><?php echo $_smarty_tpl->tpl_vars['_top']->value['checkin_count'];?>
 lượt</span>
					</li>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</ul>
				<?php }?>
			</div>
		</div>
	</div>
</div>
</div>

<?php echo '<script'; ?>
 type="application/json" id="donut_data_office"><?php echo $_smarty_tpl->tpl_vars['donut_office_json']->value;
echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="application/json" id="donut_data_dept"><?php echo $_smarty_tpl->tpl_vars['donut_dept_json']->value;
echo '</script'; ?>
>

<?php }
}
