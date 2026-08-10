<?php
/* Smarty version 3.1.33, created on 2026-08-06 18:34:13
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/billing.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a747135b7e728_60602895',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f55dcee54f4719db1428bf3467cb56e5449c6919' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/billing.tpl',
      1 => 1786016044,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a747135b7e728_60602895 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/billing.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" media="all" />
<?php $_smarty_tpl->_assignInScope('_uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
<div class="container-xxl flex-grow-1 pt-2 container-p-y biiling_page">
	<form action="#" method="POST">
		<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
			<div class="p__left mb-2 mb-lg-0">
				<h4 class="fw-bold mb-1">Giao dịch chốt</h4>
				<span class="text-muted">Tổng cộng <strong class="text-main"><?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
</strong> giao dịch chốt <?php echo @constant('BRAND_NAME');?>
</span>
			</div>
			<div class="d-flex gap-1 xs:w-100 align-items-center">
				<a class="btn btn-icon btn-outline-default text-muted cursor-pointer" onclick="$Core.util.toggle_block(this, event)" 
					callback="_autoload()" toId="toggle_block_<?php echo $_smarty_tpl->tpl_vars['_uid']->value;?>
"><i class="bx bx-chevron-down"></i></a>
				<?php if ($_smarty_tpl->tpl_vars['billing_configs']->value['is_action_visible'] == '1') {?>
				<div class="d-none d-md-block dropdown btn-group">
					<button data-bs-toggle="dropdown" disabled class="btn btn-outline-default dropdown-toggle js__dropdown-action">
						<i class="bx bx-play"></i> Hành động
					</button>
					<ul class="dropdown-menu dropdown-menu-start">
						<li><a onClick="$Core.billing.do_action(this, event)" action="contract_signed" 
							class="dropdown-item cursor-pointer">Đã ký HĐMB</a></li>
					</ul>
				</div>
				<?php }?>
				<div class="search-block w-full d-flex align-item-center">
					<div class="input-group">
						<div class="input-group input-group-merge">
							<span class="input-group-text"><i class="bx bx-search"></i></span>
							<input type="text" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" class="form-control no-radius-right" 
							placeholder="Nhập từ khoá & nhấn Enter..." />
						</div>
					</div>
					<div class="btn-group dropdown">
						<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow no-radius-left no-border-left" 
						data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
							<i class="bx bx-filter-alt"></i>
						</button>
						<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-350 desktop:w-px-500" data-popper-placement="top-end">
							<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('billing_search');?>

						</div> 
					</div>
				</div>
				<?php if ($_smarty_tpl->tpl_vars['permiss_add']->value == '1' || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('PROJECT_DIRECTOR')) {?>
				<button type="button" title="Thêm nhanh" onClick="$Core.global.billing.open_billing(this, event)" billing_id="0" 
					class="d-flex align-items-center btn text-nowrap<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-icon<?php }?> btn-outline-primary create_billing">
					<i class="bx bx-plus"></i>
					<span class="d-none d-lg-block">Thêm mới</span>
				</button>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['permiss_export']->value == '1' || $_smarty_tpl->tpl_vars['permiss_add']->value == '1' || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('PROJECT_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
				<div class="dropdown">
					<button type="button" class="btn btn-icon btn-outline-default hide-arrow dropdown-toggle"
						data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-cog"></i></button>
					<ul class="dropdown-menu w-px-250">
						<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
						<li><a class="dropdown-item cursor-pointer" onClick="$Core.commissionTier.open(this,event)">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-line-chart','Quản lý commission');?>
</a></li>
						<li><a class="dropdown-item cursor-pointer" onClick="$Core.backofficeRate.open(this,event)">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-buildings','Tỷ lệ chi phí công ty');?>
</a></li>
						<li><hr class="dropdown-divider m-0"></li>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['permiss_add']->value == '1') {?>
						<li><a class="dropdown-item cursor-pointer" onClick="$Core.billingImport.open(this,event)">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-spreadsheet','Import Google Sheet');?>
</a></li>
						<li><hr class="dropdown-divider m-0"></li>
						<li><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/billing/export.html<?php echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
" class="dropdown-item">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Xuất Excel');?>
</a></li>
						<li><a href="/lich-ky.html" class="dropdown-item"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-calendar','Lịch ký HĐMB');?>
</a></li>
						<!-- <li><a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/billing/report.html">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-bar-chart-alt-2','Báo cáo Quỹ');?>
</a></li>-->
						<!-- <li><a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/billing/report/score.html">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx bx-terminal','Báo cáo nhập thông tin');?>
</a></li>-->
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>
						<!-- <li><a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('template');?>
">
							<i class="menu-icon tf-icons bx bx-file"></i>Mẫu chúc mừng</a>
						</li>-->
						<?php }?>
					</ul>
				</div>
				<?php }?>
			</div>
		</div>
		<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
		<div id="toggle_block_<?php echo $_smarty_tpl->tpl_vars['_uid']->value;?>
" class="d-none">
			<div class="report_billing ajax" data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_report_billing">
				<div class="form-row">
					<div class="col-12 col-md-6 col-xxl-3 mb-2">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_total_billing"></span>
										<span class="">Tổng giao dịch</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_total_grand"></span>
										<span class="">Tổng doanh số</span>
									</div>
									<span class="fs-8 fw-semibold">0đ</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_primary"></span>
										<span class="">Sơ cấp</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_transfer"></span>
										<span class="">Thứ cấp, CN</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_contract"></span>
										<span class="">Đã ký HĐMB</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_calendar"></span>
										<span class="">Có lịch ký HĐMB</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_no_calendar"></span>
										<span class="">Chưa có lịch ký</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between ">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_ratio"></span>
										<span class="">Tỷ lệ ký</span>
									</div>
									<span class="fs-8 fw-semibold">0%</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 col-xxl-9 mb-2">
						<div class="lst_area_billing form-row h-100" data-md-slide="3" data-sm-slide="2" 
							data-lg-slide="4" data-dots="1" data-nav="0" data-loop="0" data-margin="10">
							<?php
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if (true) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= 4; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
							<div class="col-12 col-md-4 col-lg-3">
								<div class="item_area_billing card h-100">
									<div class="card-header item_top pb-2" style="background-color: green;color: white">
										<h4 class="mb-2">Vùng KD</h4>
										<p class="mb-0 fs-20">0 GD</p>
									</div>
									<div class="card-body item_body p-2 pb-3">
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_total_grand"></span>
												<span class="">Tổng doanh số</span>
											</div>
											<span class="fs-8 fw-semibold">0đ</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_primary"></span>
												<span class="">Sơ cấp</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_transfer"></span>
												<span class="">Thứ cấp, CN</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_contract"></span>
												<span class="">Đã ký HĐMB</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_calendar"></span>
												<span class="">Có lịch ký HĐMB</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_no_calendar"></span>
												<span class="">Chưa có lịch ký</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between ">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_ratio"></span>
												<span class="">Tỷ lệ ký</span>
											</div>
											<span class="fs-8 fw-semibold">0%</span>
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
				</div>
			</div>		
			<!-- <div class="form-row">
				<div class="col-12 col-md-6 col-lg-4 mb-2">
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<div class="card h-100" id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Hiệu suất phòng kinh doanh</h5>
							<div class="w-px-100">
								<select name="department_id" id="" class="form-control form-control-sm form-select field_item" 
									onChange="$Core.dashboard.reload(this,event)" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstDepChild']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
									<option value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</option>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</select>
							</div>
						</div>
						<div class="card-body search_group ajax" toId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_report_billing_dep" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
							<div class="d-flex gap-2 align-items-center w-100 mb-2">
								<span class="fs-14 text-right w-px-50 text-nowrap">PKD</span>
								<div class="d-flex flex-column" style="width:calc(100% - 125px)">
									<div class="progress w-100 h-px-15" >
									  <div class="progress-bar bg-info" role="progressbar" style="width:14.583333333333%;"></div>
									</div>
								</div>
								<span class="fs-12 text-nowrap w-px-75 text-right">
								<strong class="text-fs-15 text-main">120 GD</strong> 15%</span>
							</div>
							<div class="d-flex gap-2 align-items-center w-100">
								<span class="fs-14 text-right w-px-50 text-nowrap">PKD</span>
								<div class="d-flex flex-column" style="width:calc(100% - 125px)">
									<div class="progress w-100 h-px-15" >
										<div class="progress-bar bg-info" role="progressbar" style="width:14.583333333333%;"></div>
									</div>
								</div>
								<span class="fs-12 text-nowrap w-px-75 text-right">
								<strong class="text-fs-15 text-main">120 GD</strong> 15%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-4 mb-2">
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<div class="ajax h-100" data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_report_warning_dep">
						<div class="card h-100">
							<div class="card-body">
								<div class="alert py-2 mb-0">
									<i class='bx bx-info-circle text-danger me-2'></i> Cảnh báo điều hành
								</div>
								<div class="alert alert-warning py-2 mb-2">
									<i class='bx bx-info-circle me-2'></i>3 vùng có tỷ lệ ký &lt; 40%
								</div>
								<div class="alert alert-warning py-2 mb-2">
									<i class='bx bx-info-circle me-2'></i>6 vùng kinh doanh tồn &gt; 40 giao dịch chưa ký
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>
		<?php }?>
		<!-- Basic Bootstrap Table -->
		<div class="card">
			<div class="card-body">
				<div class="briefs mb-2 gap-2 gap-lg-3 d-flex flex-wrap">
					<div class="brief-item a1a bg-orange clickable">
						<p class="text-fs-14 mb-0">Tổng giao dịch</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['total_billings']->value;?>
 GD</h3>
					</div>
					<div class="brief-item a2a brief-item-clickable bg-azure">
						<p class="text-fs-14 mb-0">Tổng doanh số</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->shortNumber($_smarty_tpl->tpl_vars['total_grand']->value,3);?>
</h3>
					</div>
					<div class="brief-item a6a brief-item-clickable bg-solid">
						<p class="text-fs-14 mb-0">Thứ cấp, CN</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['total_trans_billings']->value;?>
 GD</h3>
					</div>
					<div class="brief-item a3a brief-item-clickable bg-cyan">
						<p class="text-fs-14 mb-0">Đã ký HĐMB</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['total_registed_hdmb']->value;?>
 GD</h3>
					</div>
					<div class="brief-item a4a brief-item-clickable bg-green">
						<p class="text-fs-14 mb-0">Có lịch ký HĐMB </p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['total_unregisted_hdmb']->value;?>
 GD</h3>
					</div>
					<div class="brief-item a5a brief-item-clickable bg-purple">
						<p class="text-fs-14 mb-0">Chưa có lịch ký</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white"><?php echo $_smarty_tpl->tpl_vars['total_not_schedule_hdmb']->value;?>
 GD</h3>
					</div>
				</div>
				<?php if ($_smarty_tpl->tpl_vars['billing_configs']->value['is_action_visible'] == '1') {?>
				<div class="d-md-none mb-2">
					<div class="dropdown w-100 btn-group">
						<button data-bs-toggle="dropdown" class="btn  btn-outline-default dropdown-toggle">
							<i class="bx bx-play"></i> Hành động
						</button>
						<ul class="dropdown-menu dropdown-menu-start w-100">
							<li><a class="dropdown-item cursor-pointer">Đã ký hợp đồng mua bán</a></li>
						</ul>
					</div>
				</div>
				<?php }?>
				<div class="table-container overflow-x-auto text-nowrap no-shadow">
					<table cellpadding="0" cellspacing="0" width="100%" class="table table-billing table-bordered dragable mb-0">
						<thead><tr>
							<?php if ($_smarty_tpl->tpl_vars['billing_configs']->value['is_action_visible'] == '1') {?>
							<th class="align-center bg-lighter h-px-40 overflow-visible">
								<input type="checkbox" onChange="$Core.billing.do_checked(this, event)" 
									class="form-check-input chk_all" value="1" style="font-size:0.8675rem;">
							</th>
							<?php }?>
							<th class="align-center bg-lighter h-px-40" width="150px">Ngày tạo</th>
							<th class="align-center bg-lighter h-px-40">Mã căn</th>
							<th class="align-center bg-lighter h-px-40" width="150px">Ngày cọc</th>
							<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1') {?><th class="align-center bg-lighter h-px-40 text-center">Q/E</th><?php }?>
							<th class="align-center bg-lighter h-px-40">Bán</th>
							<th class="align-center bg-lighter h-px-40">Ký VBTT</th>
							<th class="align-center bg-lighter h-px-40">Ký HĐMB</th>
							<th class="align-center bg-lighter h-px-40">Sale bán</th>
							<th class="align-center bg-lighter h-px-40">Admin</th>
							<th class="align-center bg-lighter h-px-40">Loại hình</th>
							<th class="align-center bg-lighter h-px-40">Dự án</th>
							<th class="align-center bg-lighter h-px-40">Phân khu</th>
							<th class="align-center bg-lighter h-px-40 text-right">Doanh số</th>
							<th class="align-center bg-lighter h-px-40 text-center">Điểm</th>
							<th class="align-center bg-lighter h-px-40 text-center" width="45px"></th>
						</tr> </thead>
						<tbody class="table-border-bottom-0">
							<?php if (!empty($_smarty_tpl->tpl_vars['list_billings']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_billings']->value, '_oBilling', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBilling']->value) {
?>
							<?php $_smarty_tpl->_assignInScope('billing_id', $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id']);?>
							<?php $_smarty_tpl->_assignInScope('_oProfile', $_smarty_tpl->tpl_vars['_oBilling']->value['oProfile']);?>
							<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oBilling']->value['more_information']);?>
							<tr class="trBilling <?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['class'];?>
"<?php if ($_smarty_tpl->tpl_vars['permiss_view']->value == '1') {?> 
								ondblclick="view_billing(this, event)"<?php }?> billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
">
								<?php if ($_smarty_tpl->tpl_vars['billing_configs']->value['is_action_visible'] == '1') {?>
								<td class="align-center text-center">
									<input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" name="list_ids[]" 
										class="form-check-input chk_item" onChange="$Core.billing.do_checked(this, event)">
								</td>
								<?php }?>
								<td class="align-center">
									<?php if (!empty($_smarty_tpl->tpl_vars['_oBilling']->value['is_commission'])) {?>
									<i class='bx bxs-check-circle text-success me-1 text-fs-12'></i>
									<?php }?> 
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oBilling']->value['reg_date'],4);?>

								</td>
								<td class="align-center">
									<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_cancel'] == '1') {?>
									<span class="label bg-purple">Hủy</span>
									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_deposit_paid'] == '1') {?>
									<span class="badge bg-label-danger">10%</span>
									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_interest_accrued'] == '1') {?>
									<span class="badge bg-label-info">Lãi</span>
									<?php }?>
									<strong><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['stock_code'];?>
</strong>
									<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1') {
}?>
									<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_source'];?>

									<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_alliance'] == '1') {?>
									<span class="label d-inline-block bg-success" style="font-size:65%; transform:translateY(-2px)">LM</span>
									<?php }?>
								</td>
								<td class="align-center">
									<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1') {?>
									<a class="btn btn-icon btn-sm btn-outline-default<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_cancel'] == '1') {?> disabled<?php }?>" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Bổ xung thông tin giao dịch" onClick="$Core.billing.add_info(this,event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-edit-alt');?>
</a> <?php }
echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oBilling']->value['deposit_date'],3);?>

								</td>
								<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1') {?>
								<td class="align-center text-center bg-lighter px-1 py-0">
									<button class="btn btn-sm btn-icon btn-outline-default"<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> onClick="$Core.global.billing.open_billing_done(this, event)"<?php } else { ?> data-toggle="webui-popover" data-trigger="click" data-type="async" data-placement="left-bottom" data-closeable="false" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_billing_done&billing_id=<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
"<?php }?> billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
"><i class="bx bx-pencil"></i></button>
								</td>
								<?php }?>
								<td class="align-center text-center"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['sold_to_type'];?>
</td>
								<td class="align-center">
									<span class="agree_date_<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
">
										<?php if (!empty($_smarty_tpl->tpl_vars['_oBilling']->value['agree_date'])) {?>
											<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oBilling']->value['agree_date'],3);?>

										<?php } else { ?>
											<span class="text-muted">
												<i class='bx bx-error text-muted'></i> Chưa có
											</span>
										<?php }?>
									</span>
								</td>
								<td class="align-center">
									<span class="contract_date_<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
">
									<?php if (!empty($_smarty_tpl->tpl_vars['_oBilling']->value['contract_date'])) {?>
										<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oBilling']->value['contract_date'],3);?>

									<?php } else { ?>
										<span class="text-muted">
											<i class='bx bx-error text-muted'></i> Chưa ký
										</span>
									<?php }?>
									</span>
								</td>
								<td class="align-center">
									<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['staff_id'] > 0) {?>
										<span class="badge bg-label-purple"><?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['more_information']['department_name'];?>
</span>
										<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getIndentityV2($_smarty_tpl->tpl_vars['_oBilling']->value['staff_id'],$_smarty_tpl->tpl_vars['_oProfile']->value);?>

									<?php } elseif (!empty($_smarty_tpl->tpl_vars['more_information']->value['seller_name'])) {?>
										<span class="badge bg-label-secondary">Ngoài HT</span> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['more_information']->value['seller_name'], ENT_QUOTES, 'UTF-8', true);?>

									<?php } elseif (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_agency_id'])) {?>
																				<span class="badge bg-label-warning">Đại lý</span> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['more_information']->value['sale_agency_id']), ENT_QUOTES, 'UTF-8', true);?>

									<?php }?>
									<?php if (!empty($_smarty_tpl->tpl_vars['_oBilling']->value['co_sellers'])) {?>
									<div class="mt-1 text-fs-11">
										<span class="text-muted">Sale phụ:</span>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oBilling']->value['co_sellers'], '_cs');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_cs']->value) {
?>
										<span class="badge bg-label-info fw-normal"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_cs']->value['seller_name'], ENT_QUOTES, 'UTF-8', true);?>
 · <?php echo $_smarty_tpl->tpl_vars['_cs']->value['share_ratio'];?>
%</span>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</div>
									<?php }?>
								</td>
								<td class="align-center text-center">
									<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['admin_id'];?>
" 
										data-toggle="webui-popover" data-trigger="hover" data-width="350" data-target="webuiPopover10">
										<img class="avaatr avatar-xs rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oBilling']->value['admin_id'],$_smarty_tpl->tpl_vars['oAdminProfile']->value,40,40);?>
" />
										<span class="cre"><i class="fa fa-plus"></i></span>
									</a>
								</td>
								<td class="align-center"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_type'];?>
</td>
								<td class="align-center"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['project_name'];?>
</td>
								<td class="align-center"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['block_name'];?>
</td>
								<td class="align-center text-right">
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['_oBilling']->value['totalgrand']);?>
 
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>

								</td>
								<td class="align-center text-center"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['score'];?>
</td>
								<td class="align-center text-center">
									<div class="dropdown">
										<button type="button" class="btn p-0 dropdown-toggle dropdown-button hide-arrow">
											<i class="bx bx-dots-vertical-rounded"></i>
										</button>
										<div class="dropdown-menu">
											<a class="dropdown-item cursor-pointer" onClick="view_billing(this,event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
">
												<i class="bx bx-bullseye me-1"></i> Xem</a>
											<hr size="0" class="dropdown-divider" />
											<!-- <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>
											<a class="dropdown-item text-danger" onClick="$Core.global.transaction.open(this,event)" openFrom="_billing" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
" transaction_id="0" href="javascript:void(0);"><i class="bx bx-plus-circle me-1"></i> Xác nhận hoa hồng</a>
											<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['commission_id']) && !empty($_smarty_tpl->tpl_vars['more_information']->value['commission_id'])) {?>
											<a class="dropdown-item text-primary" onClick="$Core.global.commission.open(this,event)" openFrom="_billing" commission_id="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['commission_id'];?>
" href="javascript:void(0);"><i class="bx bx-plus-circle me-1"></i> Cập nhật hoa hồng</a>
											<?php }?>
											<a class="dropdown-item text-warning" onClick="$Core.billing.sync_commission(this,event)" openFrom="_billing" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
" transaction_id="0" href="javascript:void(0);"><i class="bx bx-plus-circle me-1"></i> Tạo hoa hồng</a>
											<?php }?> -->
											<!-- <a class="dropdown-item cursor-pointer" tp="quick" onClick="open_confirm_deposit(this,event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
">
												<i class='bx bx-mail-send me-1'></i>Mẫu mail xác nhận</a> -->
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1' || $_smarty_tpl->tpl_vars['_oBilling']->value['admin_id'] == $_smarty_tpl->tpl_vars['profile_id']->value) {?>
											<a class="dropdown-item cursor-pointer" onClick="$Core.global.billing.open_billing(this,event)" 
												billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
"><i class="bx bx-edit-alt me-1"></i> Cập nhật</a>
											<?php if ($_smarty_tpl->tpl_vars['permiss_settlement']->value == '1') {?>
											<a class="dropdown-item cursor-pointer text-primary" onClick="$Core.billingSettlement.open(this,event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
">
												<i class="bx bx-calculator me-1"></i> Quyết toán</a>
											<?php }?>
											<a class="dropdown-item cursor-pointer" onClick="$Core.billing.add_info(this,event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
">
												<i class="bx bx-plus-circle me-1"></i> Thêm thông tin</a>
											<a class="dropdown-item text-danger cursor-pointer" onClick="$Core.global.billing.open_activity(this,event)" 
												billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
" holderG="deposit_paid"><i class="bx bx-plus-circle me-1"></i> 
												<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_deposit_paid'] == '1') {?>Sửa<?php } else { ?>Thêm<?php }?> 10% vào CTY</a>
											<a class="dropdown-item cursor-pointer text-warning" onClick="$Core.global.billing.open_activity(this,event)" 
												billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
" holderG="interest_accrued"><i class="bx bx-plus-circle me-1"></i> Cập nhật lãi phát sinh</a>
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_cancel']->value == '1' || ($_smarty_tpl->tpl_vars['_oBilling']->value['admin_id'] == $_smarty_tpl->tpl_vars['profile_id']->value || $_smarty_tpl->tpl_vars['clsISO']->value->checkDEV())) {?>
											<a class="dropdown-item cursor-pointer<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_cancel'] == '1') {?> disabled<?php }?>" onClick="cancel_billing(this,event)" 
												billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
"><i class="bx bx-no-entry me-1"></i> Hủy</a>
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_delete']->value == '1' || ($_smarty_tpl->tpl_vars['_oBilling']->value['admin_id'] == $_smarty_tpl->tpl_vars['profile_id']->value || $_smarty_tpl->tpl_vars['clsISO']->value->checkDEV())) {?>
											<a class="dropdown-item cursor-pointer" onClick="delete_billing(this,event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
">
												<i class="bx bx-trash me-1"></i> Xóa</a>
											<?php }?>
										</div>
									</div>
								</td>
							</tr>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php } else { ?>
								<tr>
									<td class="text-center" colspan="11">
										Không có giao dịch nào !
									</td>
								</tr>
							<?php }?>
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
	</form>
</div>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/billing_import.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 type="text/javascript">
	$().ready(() => {
		$Core.billing.init();
	});
<?php echo '</script'; ?>
>
<style type="text/css">
	tr.bg-cancel td{
		background:#fbe9e9 !important;
		--bs-table-accent-bg:#fbe9e9 !important;
	}
	.no-radius-right .selectize-input{
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		border-right: 0px !important;
	}
	.selectize-input,
	.selectize-control.single .selectize-input.focus{
		padding:7px !important;
		min-height:36.5px !important;
	}
	@media screen and (min-width:648px){
	<?php if ($_smarty_tpl->tpl_vars['billing_configs']->value['is_action_visible'] == '1') {?>
		.table-billing tr td:nth-child(2),
		.table-billing tr th:nth-child(2){
			position:sticky;
			left:41px; top:0;
			background:var(--bs-white);
		}
	<?php } else { ?>
		.table-billing tr td:nth-child(2),
		.table-billing tr th:nth-child(2){
			position:sticky;
			left:136.8px; top:0;
			background:var(--bs-white);
		}
	<?php }?>
	}
	.jconfirm.jconfirm-light .jconfirm-box .jconfirm-buttons{
		width:100%;
		display: -webkit-box!important;
		display: -ms-flexbox!important;
		display: flex!important;
		-webkit-box-pack: end!important;
		-ms-flex-pack: end!important;
		justify-content: flex-end!important;
	}
</style>
<?php }
}
