<?php
/* Smarty version 3.1.33, created on 2026-08-05 16:11:14
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72fe32184ee7_91955655',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1eecda58311fb4e3694c318163956cc53e376080' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/default.tpl',
      1 => 1784300226,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72fe32184ee7_91955655 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/fullcalendar-6.1.15/index.global.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/daterangepicker/moment.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>

<div class="container-xxl flex-grow-1 pt-2 container-p-y calendar_page" style="overflow-y: auto">

	<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

	<form method="POST" class="frm_search_calendar">

		<input type="hidden" name="current_time" value="0" id="current_time" class="search_calender">

		<input type="hidden" name="start" value="0" id="start_time" class="search_calender">

		<input type="hidden" name="end" value="0" id="end_time" class="search_calender">

		<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">

			<div class="vRHjwnrkGa d-flex gap-2 mb-2 mb-lg-0 xs:w-100">

				<a href="/giao-dich.html" class="back mr-1 goToPage">

					<img src="<?php echo @constant('ICON_BACK');?>
" />

				</a>

				<div class="ysXwpeiJqL d-flex flex-column">

					<h4 class="fw-bold mb-0">Lịch ký HĐMB & VBTT</h4>

					<span class="text-muted">Tổng hợp lịch ký giao dịch</span>

				</div>

			</div>

			<div class="d-flex gap-2 align-items-center flex-wrap justify-content-end">	

				<div class="btn-group d-flex xs:w-100" role="group" aria-label="Sắp xếp">

					<input type="radio" class="btn-check js__filter-date" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.calendar.do_reload(this, event)" 

						name="date_type" id="all_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="_all" checked="checked">

					<label data-toggle="ripple" class="btn btn-outline-default" for="all_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Tất cả</label>

					<input type="radio" class="btn-check js__filter-date" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.calendar.do_reload(this, event)" 

						name="date_type" id="HDMB_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="_contract">

					<label data-toggle="ripple" class="btn btn-outline-default" for="HDMB_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">HĐMB</label>

					<input type="radio" class="btn-check js__filter-date" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.calendar.do_reload(this, event)" 

						name="date_type" id="VBTT_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="_text">

					<label data-toggle="ripple" class="btn btn-outline-default" for="VBTT_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">VBTT</label>

				</div>

				<div class="btn-group dropdown" bis_skin_checked="1">

					<button type="button" class="btn btn-icon btn-outline-default dropdown-toggle hide-arrow" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>

					<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">

						<div class="p-3" bis_skin_checked="1">

							<div class="form-group mb-2">

								<label class="form-label mb-1">Dự án</label>

								<div class="clearfix"></div>

								<select type="select" placeholder="Chọn dự án" class="form-control search_calender iso-select2" name="project_id" 

								onChange="$Core.calendar.do_reload(this,event);" is_project_dir="<?php echo $_smarty_tpl->tpl_vars['is_project_dir']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-width="100%">

									<option value="0">Chọn dự án</option>

									<?php if (!empty($_smarty_tpl->tpl_vars['arr_projects']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_projects']->value, '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

										<option value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['project_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
</option>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php }?>

								</select>

							</div>		

							<div class="form-group">

								<label class="form-label mb-1">Loại hình</label>

								<select type="select" class="form-control search_calender iso-select2" name="billing_type" 

								onChange="$Core.calendar.do_reload(this,event);" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-width="100%">

									<option value="0">Chọn loại hình</option>

									<?php if (!empty($_smarty_tpl->tpl_vars['arr_billing_type']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_billing_type']->value, '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

										<option value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
</option>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php }?>

								</select>

							</div>

							<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT')) {?>

							<div class="form-group mt-2">

								<label class="form-label mb-1">Lịch ký của tôi</label>

								<div class="form-check form-switch">

									<input class="form-check-input search_calender w-px-40 mr-2" type="checkbox" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.calendar.do_reload(this,event);" name="is_me" value="1">

									<label class="form-check-label" for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Hiển thị lịch ký của tôi</label>

								</div>

							</div>

							<?php }?>

						</div>

					</div>

				</div>

			</div>

		</div>

	</form>

    <!-- Basic Bootstrap Table -->

	<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

	<div class="form-row">

		<div class="col-12 col-lg-8 col-xxl-9 order-lg-2">

			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

			<div class="dashboard-panel-item dashboard-panel-item--full">

				<div class="panel border-0 no-shadow panel-default">

					<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">

						<h3 class="panel-title">Lịch ký <span class="text-muted fs-11">(<strong class="text-main number_total">0</strong> lịch ký)</span></h3>

						<div class="w-px-125">

							<input gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" type="text" data-bind="change" class="form-control search_field datepick is_icon text-fs-11" 

							holderg="_desktop" name="sign_date" data-field="sign_date" onchange="$Core.dashboard.reload(this, event)" 

							value="<?php echo smarty_modifier_date_format(time(),'%d/%m/%Y');?>
" readonly />

						</div>

					</div>						

					<div class="panel-body px-0 scroll-y-auto table-container no-shadow overflow-x-auto" >

						<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%" >

							<thead><tr>

								<th class="align-center" style="background:#FFF !important;">Mã căn</th>

								<th class="align-center">Admin</th>

								<th class="align-center text-right">T.Trạng</th>

								<th class="align-center text-right">Loại</th>

								<th class="align-center text-right">Ghi chú</th>

							</tr></thead>

							<tbody gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="table-border-bottom-0 ajax loaded billing_calendar" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=calendar&act=load_calendar_today" data-options="{}">

								<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 10);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

								<tr>

									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

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

			<?php }?>

			<div class="sticky">

				<div class="card mb-2">

					<div class="card-body">

						<div id="calendar" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=calendar&act=load_calendar" 

						data-options='{"gid":"<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"}'>

							<div class="table-wrapper">

								<table width="100%" class="table table-bordered">

									<thead><tr>

										<th class="align-center text-center h-px-40">CN</th>

										<th class="align-center text-center h-px-40">T2</th>

										<th class="align-center text-center h-px-40">T3</th>

										<th class="align-center text-center h-px-40">T4</th>

										<th class="align-center text-center h-px-40">T5</th>

										<th class="align-center text-center h-px-40">T6</th>

										<th class="align-center text-center h-px-40">T7</th>

									</tr></thead>

									<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = min(($__section_i_1_loop - 0), 5);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

									<tr>

										<?php
$__section_k_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_k_2_total = min(($__section_k_2_loop - 0), 7);
$_smarty_tpl->tpl_vars['__smarty_section_k'] = new Smarty_Variable(array());
if ($__section_k_2_total !== 0) {
for ($__section_k_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] = 0; $__section_k_2_iteration <= $__section_k_2_total; $__section_k_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']++){
?>

										<td class="h-px-150">

											<div class="animate-bg h-px-20 mb-1 w-100 rounded-2"></div>

											<div class="animate-bg h-px-20 mb-1 w-100 rounded-2"></div>

											<div class="animate-bg h-px-20 w-100 rounded-2"></div>

										</td>

										<?php
}
}
?>

									</tr>

									<?php
}
}
?>

								</table>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="col-12 col-lg-4 col-xxl-3 order-sm-2 order-lg-1" >

			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

			<div class="dashboard-panel-item dashboard-panel-item--full">

				<div class="panel border-0 no-shadow panel-default">

					<div class="panel-heading d-flex justify-content-between align-items-center">

						<h3 class="panel-title">Lịch ký <span class="text-muted text-fs-11">(<strong class="text-main number_total">0</strong> lịch ký)</span></h3>

						<div class="w-px-125">

							<input type="text" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-bind="change" class="form-control search_field datepick is_icon text-fs-11" 

							holderg="_desktop" name="sign_date" data-field="sign_date" onchange="$Core.dashboard.reload(this, event)" 

							value="<?php echo smarty_modifier_date_format(time(),'%d/%m/%Y');?>
" readonly>

						</div>

					</div>						

					<div class="panel-body px-0 scroll-y-auto">

						<div class="table-container no-shadow overflow-x-auto">

							<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%" >

								<thead><tr>

									<th class="align-center" style="background: #FFF !important;">Mã căn</th>

									<th class="align-center">Admin</th>

									<th class="align-center text-right">T.Trạng</th>

									<th class="align-center text-right">Loại</th>

									<th class="align-center text-right">Ghi chú</th>

								</tr></thead>

								<tbody gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="table-border-bottom-0 ajax loaded billing_calendar" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=calendar&act=load_calendar_today" data-options="{}">

									<?php
$__section_i_3_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_3_total = min(($__section_i_3_loop - 0), 10);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_3_total !== 0) {
for ($__section_i_3_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_3_iteration <= $__section_i_3_total; $__section_i_3_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

									<tr>

										<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

										<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

										<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

										<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

										<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>

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

			<?php }?>

			<div class="dashboard-panel-item dashboard-panel-item--full">

				<div class="panel border-0 no-shadow panel-default">

					<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">

						<h3 class="panel-title">Thống kê admin</h3>

						<div class="dropdown">

							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">

								<i class="bx bx-dots-vertical-rounded"></i>

							</button>

							<div class="dropdown-menu lst_time">

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_filters']->value, 'text', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['text']->value) {
?>

								<a href="javascript:void(0);" onclick="$Core.calendar.timer_click(this,event);" holderG="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" class="dropdown-item js_choose-time<?php if ($_smarty_tpl->tpl_vars['key']->value == 'THIS_WEEK') {?> active<?php }?>"><?php echo $_smarty_tpl->tpl_vars['text']->value;?>
</a>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</div>

						</div>

					</div>						

					<div class="panel-body px-0">

						<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">

							<thead><tr>

								<th class="align-center">Admin</th>

								<th class="align-center text-center">HĐMB</th>

								<th class="align-center text-center">VBTT</th>

							</tr></thead>

							<tbody class="table-border-bottom-0 billing_filter ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=calendar&act=load_total_calendar" data-options="{}">

							</tbody>

						</table>

					</div>

				</div>

			</div>			

			<div class="dashboard-panel-item dashboard-panel-item--full mb-2">

				<div class="panel border-0 no-shadow panel-default">

					<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">

						<h3 class="panel-title">Thống kê lịch ký</h3>						

						<div class="dropdown">

							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">

								<i class="bx bx-dots-vertical-rounded"></i>

							</button>

							<div class="dropdown-menu lst_time">

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_filters']->value, 'text', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['text']->value) {
?>

								<a href="javascript:void(0);" onclick="$Core.calendar.timer_click(this,event);" holderG="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" 

									class="dropdown-item js_choose-time<?php if ($_smarty_tpl->tpl_vars['key']->value == 'THIS_WEEK') {?> active<?php }?>"><?php echo $_smarty_tpl->tpl_vars['text']->value;?>
</a>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</div>

						</div>

					</div>						

					<div class="panel-body px-0">

						<div id="chart_billing_type" class="billing_filter h-px-300 w-100 ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=calendar&act=load_chart_billing" data-options='{}'>

							<div class="p-5 text-center">

								<div class="p-5 text-muted">Loading...</div>

							</div>

						</div>

					</div>

				</div>

			</div>	

		</div>

	</div>

</div>

<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>

	<?php echo '<script'; ?>
>

		var DEV=1;

	<?php echo '</script'; ?>
>

<?php }?>



<style type="text/css">

	.fc .fc-daygrid-event{

		z-index:auto !important;

	}

	.fc .fc-daygrid-day-number {

		width: 100%;

	}

</style>

<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		$Core.calendar.load_calendar('<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
',{});

	});

<?php echo '</script'; ?>
>

<?php }
}
