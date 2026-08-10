<?php
/* Smarty version 3.1.33, created on 2026-08-06 18:11:56
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a746bfc984342_14787703',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2c5c8da4c5b6d400f5f3f1e7d15e5bbc95fc1dab' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/default.tpl',
      1 => 1786013131,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a746bfc984342_14787703 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
	<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("home_mobile");?>

<?php }?>
<div class="content-wrapper <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>content_mobile_wrapper<?php }?>">
    <div class="container-xxl flex-grow-1 py-2 container-p-y">
		<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('banner');?>

		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('block_honor');?>

		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('note_calendar');?>

		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
			<?php if (!empty($_smarty_tpl->tpl_vars['oneTraining']->value)) {?>
			<div class="card mb-2">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="card-title m-0"><span>Có thể có ích cho bạn</span></h5>
					<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('training');?>
" class="btn btn-icon btn-sm btn-link rounded-pill">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
				</div>
				<div class="card-body mt-0">
					<div class="item_training h-100 card no-shadow overflow-hidden cursor-pointer position-relative overflow-hidden" 
						onclick="$Core.global.training.open(this,event)" training_id="<?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['training_id'];?>
">
						<div class="image-scale">
							<img class="w-100 h-auto" src="<?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['image'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['title'];?>
" width="340" height="250">
						</div>
						<span class="position-absolute zindex-2 top-50 left-50 fs-50"  style="transform: translate(-50%,-50%);left: 50%;width: 60px" >
							<svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
								<path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f03"></path>
								<path d="M 45,24 27,14 27,34" fill="#fff"></path>
							</svg>
						</span>
						<div class="box_info position-absolute w-100 left-0 bottom-0 text-white zindex-1 <?php echo $_smarty_tpl->tpl_vars['deviceType']->value;?>
">
							<div class="p-3">
								<h3 class="title_training mb-2 fs-18 limit_1line"><?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['title'];?>
</h3>
								<div class="author fs-12">bởi <strong><?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['author'];?>
</strong></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_ranker_25');?>

			<div class="ranking-box mb-2">
				<?php $_smarty_tpl->_assignInScope('_gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<div class="card ranking mb-2">
					<div class="card-body">
						<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_ranking');?>

					</div>
				</div>
			</div>
			<div class="ranking-box mb-2">
				<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('ranking_dept',array('gId'=>$_smarty_tpl->tpl_vars['gId']->value));?>

			</div>
			<div class="ranking-box mb-2">
				<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_ranker',array('gId'=>$_smarty_tpl->tpl_vars['gId']->value));?>

			</div>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone' && !$_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
		<div class="card mb-2">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h5 class="card-title mb-0">Truy cập nhanh</h5>
				<button type="button" data-toggle="ripple" class="btn btn-sm btn-link btn-icon btn_config_menu rounded-pill text-muted" 
					title="Cấu hình" data-bs-toggle="tooltip" onClick="$Core.mobile.open_config_menu(this,event)" data-for="pc">
					<i class='bx bx-cog'></i>
				</button>
			</div>
			<div class="card-body">
				<div id="list_menu_active" class="computer">
					<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
						<?php
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if (true) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= 12; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<div class="item_menu_grid w-px-65">
							<a class="text-dark text-center text-center d-block">
								<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
									<div class="w-px-30 animate-bg h-px-30 rounded-2"></div>
								</span>
								<span class="text-dark fw-semibold fs-12">Đang tải..</span>
							</a>
						</div>
						<?php
}
}
?>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
		<?php if (!($_smarty_tpl->tpl_vars['deviceType']->value == 'phone')) {?>
					<?php }?>
		<div class="clearfix"></div>
	<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
		<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('block_honor');?>

	<?php }?>
	<div class="clearfix"></div>
		<?php $_smarty_tpl->_assignInScope('home_screen', $_smarty_tpl->tpl_vars['clsISO']->value->getHomeScreen());?>
	<?php if ($_smarty_tpl->tpl_vars['home_screen']->value != '') {?>
		<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock($_smarty_tpl->tpl_vars['home_screen']->value);?>

	<?php } else { ?>
		<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkSale() && 1 == 2) {?>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('ranking-staff');?>

		<?php }?>
		<div class="form-row mb-2">
			<div class="col-12 col-lg-8 mb-2 mb-lg-0 order-0">
				<div class="dbx-card h-100">
					<div class="dbx-card__head">
						<span class="dbx-card__ic"><i class="bx bx-group"></i></span>
						<h5 class="dbx-card__title mb-0">Hoạt động check-in</h5>
						<a href="/net-dep-lao-dong.html" title="Xem tất cả" class="btn btn-icon btn-sm btn-link rounded-pill">
							<i class="bx bx-link-external text-fs-14 text-muted"></i>
						</a>
					</div>
					<div class="card-body ajax" data-bind="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_checkin_activity" data-options="{}">
						<div class="form-row">
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
							<?php
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if (true) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= 3; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
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
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if (true) {
for ($__section_i_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_2_iteration <= 6; $__section_i_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
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
			</div>
			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
			<div class="col-12 col-lg-4 order-0 sss">
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('home_course');?>

			</div>
			<?php }?>
		</div>	
		
		<div class="form-row">
			<!-- Start Left Col -->
			<div class="col-12 col-lg-8 order-0">
				<div class="sticky">
										<?php if ($_smarty_tpl->tpl_vars['oneProfile']->value['role_id'] == @constant('_ROLE_HEAD_HR_BO')) {?>
					<div class="card mb-2">
						<div class="d-flex align-items-end">
							<div class="card-body row ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=load_info_staff" 
							data-options='{}'>
								<div class="p-5 text-center">
									<div class="p-2">Đang tải...</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone' && $_smarty_tpl->tpl_vars['clsISO']->value->checkSale()) {?>
						<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("target_sales");?>
	
					<?php }?>
					<div class="form-row mb-2">
						<div class="col-12 col-lg-6 mb-2 mb-lg-0 flex-fill">
							<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
							<div class="spb-hero h-100">
								<div class="spb-hero__head">
									<h5 class="spb-hero__title">Thống kê giao dịch <span class="spb-hero__yr"><?php echo smarty_modifier_date_format(time(),'%Y');?>
</span></h5>
									<div class="spb-hero__meta">
										<div>Giao dịch cuối: <b><?php echo $_smarty_tpl->tpl_vars['transactions_configs']->value['last_deposit_date'];?>
</b></div>
										<div>Bao lâu bạn chưa có giao dịch? <span class="spb-hero__badge"><i class="bx bx-time-five"></i> <?php echo $_smarty_tpl->tpl_vars['transactions_configs']->value['days_since_sold'];?>
 ngày</span></div>
									</div>
								</div>
								<div class="spb-hero__body ajax" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_person_billing" data-options="{}">
									<div class="row">
										<div class="col-12 col-md-6">
											<div class="animate-bg rounded-2 w-100 h-px-20 mb-2"></div>
											<div class="d-flex gap-5 align-items-center justify-content-between mb-2">
												<div class="animate-bg rounded-2 flex-fill h-px-20"></div>
												<div class="animate-bg rounded-2 flex-fill h-px-20"></div>
											</div>
											<div class="animate-bg rounded-2 w-100 h-px-20 mb-2"></div>
										</div>
										<div class="col-12 col-md-6">
											<div class="animate-bg rounded-2 w-100 h-px-20 mb-2"></div>
											<div class="d-flex gap-5 align-items-center justify-content-between mb-2">
												<div class="animate-bg rounded-2 flex-fill h-px-20"></div>
												<div class="animate-bg rounded-2 flex-fill h-px-20"></div>
											</div>
											<div class="animate-bg rounded-2 w-100 h-px-20 mb-2"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>					
					<!-- Giỏ hàng -->
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<div class="dbx-card mb-2">
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-bar-chart-alt-2"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title">Thống kê bán hàng</h5>
								<small class="dbx-card__sub">Doanh số theo tháng</small>
							</div>
							<div class="dbx-card__filter">
								<select class="form-control form-control-sm form-select" name="year" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onChange="$Core.dashboard.reload(this,event)">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_years']->value, '_year');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_year']->value) {
?>
									<option<?php if ($_smarty_tpl->tpl_vars['_year']->value == smarty_modifier_date_format(time(),"%Y")) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
">Năm <?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
</option>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</select>
							</div>
						</div>
						<div class="dbx-card__body ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_person_chart" 
						data-options="{}" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
							<div class="d-flex align-items-end justify-content-center gap-5 w-100 px-3 h-px-250">
								<div class="animate-bg w-px-50 rounded-2 h-px-200"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-200"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-150"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-100"></div>
								<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
								<div class="animate-bg w-px-50 rounded-2 h-px-50"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-150"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-200"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-50"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-100"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-150"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-100"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-250"></div>
								<?php }?>
							</div>
						</div>
					</div>
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<div class="dbx-card mb-2">
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-receipt"></i></span>
							<h5 class="dbx-card__title">Giao dịch gần đây</h5>
							<a href="/giao-dich.html" class="dbx-card__link" title="Xem tất cả giao dịch"><i class="bx bx-link-external"></i></a>
						</div>
						<div class="dbx-card__body dbx-card__body--flush">
							<div class="overflow-x-auto text-nowrap">
								<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">
									<thead><tr>
										<th>Mã căn</th>
										<th>Ngày cọc</th>
										<th>Dự án</th>
										<th>Phân khu</th>
										<th class="text-center">Loại</th>
										<th class="text-end">Giá trị</th>
										<th class="text-center">Trạng thái</th>
									</tr></thead>
									<tbody class="ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=billing_me" data-options='{}'>
										<?php
$__section_i_3_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_3_total = min(($__section_i_3_loop - 0), 5);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_3_total !== 0) {
for ($__section_i_3_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_3_iteration <= $__section_i_3_total; $__section_i_3_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
										<tr>
											<td colspan="7"><div class="animate-bg h-px-15 w-100 rounded-2"></div></td>
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
					<div class="clearfix"></div>
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<div class="dbx-card mb-2">
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-history"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title">Lịch sử tra cứu</h5>
								<small class="dbx-card__sub">10 căn bạn xem gần nhất</small>
							</div>
							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/report/stock.html" class="dbx-card__link" title="Tra cứu căn"><i class="bx bx-search-alt-2"></i></a>
						</div>
						<div class="dbx-card__body dbx-card__body--flush">
							<div class="overflow-x-auto text-nowrap">
								<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">
									<thead><tr>
										<th>Mã căn</th>
										<th>Phân khu</th>
										<th>PN · Hướng</th>
										<th class="text-end">Giá (VAT)</th>
										<th class="text-center">Trạng thái</th>
										<th class="text-end">Xem lúc</th>
									</tr></thead>
									<tbody class="ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=load_search_history" data-options='{}'>
										<?php
$__section_i_4_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_4_total = min(($__section_i_4_loop - 0), 5);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_4_total !== 0) {
for ($__section_i_4_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_4_iteration <= $__section_i_4_total; $__section_i_4_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
										<tr>
											<td colspan="6"><div class="animate-bg h-px-15 w-100 rounded-2"></div></td>
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
					<div class="clearfix"></div>
					<div class="form-row mb-2">
						<div class="col-12 col-md-6 mb-2 mb-lg-0">
							<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_billing');?>

						</div>
						<div class="col-12 col-md-6">
							<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_staff');?>

						</div>
					</div>
				</div>
			</div>
			<!-- End Left Col -->
			<!-- Start Right Col -->
			<div class="col-12 col-md-12 col-lg-4 order-1">				
				<!-- Xác nhận thay đổi GD. -->
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("home_billing_confirm");?>

			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>		
				<?php if (!empty($_smarty_tpl->tpl_vars['oneTraining']->value)) {?>
				<div class="card mb-2">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="card-title m-0"><span>Có thể có ích cho bạn</span></h5>
						<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('training');?>
" class="text-decoration-underline" title="Xem tất cả">Xem tất cả</a>
					</div>
					<div class="card-body mt-0">
						<div class="item_training h-100 card no-shadow overflow-hidden cursor-pointer position-relative overflow-hidden" onclick="$Core.global.training.open(this,event)" training_id="<?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['training_id'];?>
">
							<div class="image-scale">
								<img class="w-100 h-auto" src="<?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['image'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['title'];?>
" width="340" height="250">
							</div>
							<span class="position-absolute zindex-2 top-50 left-50 fs-50"  style="transform: translate(-50%,-50%);left: 50%;width: 60px" >
								<svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
									<path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f03"></path>
									<path d="M 45,24 27,14 27,34" fill="#fff"></path>
								</svg>
							</span>
							<div class="box_info position-absolute w-100 left-0 bottom-0 text-white zindex-1">
								<div class="p-3">
									<h3 class="title_training mb-2 limit_1line"><?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['title'];?>
</h3>
									<div class="author mb-2">bởi <strong><?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['author'];?>
</strong></div>
									<div class="d-flex flex-wrap justify-content-between">
										<div class="mb-1 w-50 flex-fill">
											<i class='bx bx-video me-1' ></i><?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['total_lesson'];?>
 bài học
										</div>	
										<div class="mb-1 time w-50 flex-fill">
											<i class='bx bx-time me-1' ></i><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeMinute($_smarty_tpl->tpl_vars['oneTraining']->value['time_training']);?>

										</div>		
										<?php if (!empty($_smarty_tpl->tpl_vars['oneTraining']->value['cat_name'])) {?>
											<div class="mb-1 time flex-fill">
												<i class='bx bx-book-content me-1'></i><?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['cat_name'];?>

											</div>
										<?php }?>
										<?php if (!empty($_smarty_tpl->tpl_vars['oneTraining']->value['total_profile_learning'])) {?>
											<div class="mb-1 time w-auto flex-fill">
												<i class='bx bx-user me-1' ></i><?php echo $_smarty_tpl->tpl_vars['oneTraining']->value['total_profile_learning'];?>
 người đã học
											</div>
										<?php }?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php }?>				
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('note_calendar');?>

				<?php if ($_smarty_tpl->tpl_vars['oneProfile']->value['role_id'] == @constant('_ROLE_HEAD_HR_BO')) {?>
					<div class="card mb-2">
						<div class="card-header">
							<h5 class="d-flex align-items-center">
								<img class="mr-2" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/birthday.png" width="20" /> 
								<span>Chúc mừng sinh nhật</span>
							</h5>
							<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
							<ul class="nav nav-pills" role="tablist">
								<li class="nav-item js__birthday-tab-item">
									<button onClick="$Core.birthday.set_time(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" holderG="7days" type="button" class="nav-link js__birthday-tab-link" role="tab" 
									data-bs-toggle="tab" aria-selected="true">7 ngày</button>
								</li>
								<li class="nav-item js__birthday-tab-item">
									<button type="button" onClick="$Core.birthday.set_time(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" holderG="30days" class="nav-link js__birthday-tab-link active" role="tab">30 ngày</button>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content p-0">
								<div class="tab-pane fade show active" role="tabpanel">
									<div class="ajax" data-bind="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=staff_birthday" data-options="{}">
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
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup("HEAD_SALE")) {?>
				<div class="log_stock ajax mb-2" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_top_search_stock" 
					data-options="{}">
					<div class="card h-100">
						<div class="card-header">
							<h5 class="card-title mb-0">Thống kê lượt tra cứu 24h qua</h5>
						</div>
						<div class="card-body" >
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						</div>
					</div>
				</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkSale() || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
					<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("target_sales");?>
				
				<?php }?>
				<div class="mb-2">
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
										<div class="card ranking h-100">
						<div class="card-body">
							<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_ranking');?>

						</div>
					</div>
				</div>
				<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('ranking_dept',array('gId'=>$_smarty_tpl->tpl_vars['gId']->value));?>

				<!-- <?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_ranker',array('gId'=>$_smarty_tpl->tpl_vars['gId']->value));?>
 -->
			<?php }?>
			</div>
		</div>
		<?php }?>
    </div>
</div>
<?php echo $_smarty_tpl->tpl_vars['scriptJs']->value;
}
}
