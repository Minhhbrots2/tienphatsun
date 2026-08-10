<?php
/* Smarty version 3.1.33, created on 2026-08-07 13:10:38
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/menu/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7576de922278_49006817',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4d31cb1ffe6fb3dd6a868b0a166eb0f6c60f089c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/menu/index.tpl',
      1 => 1786083034,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7576de922278_49006817 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
" class="app-brand-link pb-1 mx-auto d-flex flex-column justify-content-center">
            <span class="app-brand-logo demo">
				<img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('HeaderLogo');?>
" height="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageHeight('HeaderLogo');?>
" alt="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['CompanyName'];?>
" />
			</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1 ps ps--active-y">
        <!-- Dashboard -->
		<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?> active<?php }?>">
			<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
" class="menu-link mx-0" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-home-circle"></i>
				<div class="text-truncate" data-i18n="Analytics">Trang chủ</div>
			</a>
		</li>
		<!--		==========Bán hàng===============-->
		<li class="menu-item open <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'data_central') {?> active<?php }?>">
			<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block mx-0 text-left">
				<i class="menu-icon tf-icons bx bx-tag text-main"></i>
				<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Bán hàng (<span id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" >0</span>)</span>
			</a>
			<ul class="menu-sub no-before" toId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" >
				<li class="menu-item <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'stock') {?> active<?php }?>">
					<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('exclusive');?>
" class="menu-link">
						<i class="menu-icon tf-icons bx bxs-heart text-main"></i>
						<div class="text-truncate" data-i18n="Bảng hàng dự án">Quỹ độc quyền </div>
						<span class="badge badge-demo bg-label-warning ms-auto"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['header_configs']->value['CompanyNameBrief'])===null||$tmp==='' ? 'FH' : $tmp);?>
</span>
					</a>
				</li>
				<li class="menu-item <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_list') {?> active<?php }?>">
					<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bang-hang/" class="menu-link text-warning">
						<i class="menu-icon tf-icons bx bx-table"></i>
						<div class="text-truncate" data-i18n="Bảng hàng dự án">Bảng hàng dự án</div>
					</a>
				</li>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'tool' && $_smarty_tpl->tpl_vars['act']->value == 'tool') {?> active<?php }?>">
					<a data-toggle="ripple" href="/tool.html" class="menu-link">
						 <i class="menu-icon tf-icons bx bx-search"></i>
						<div class="text-truncate" data-i18n="Tra cứu căn hộ">Tra cứu căn hộ</div>
					</a>
				</li>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('access_crm')) {?>
				<!-- <li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crm' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?> active<?php }?>">
					<a data-toggle="ripple" href="/crm/" class="menu-link">
						<i class="menu-icon tf-icons bx bx-user-pin"></i>
						<div class="text-truncate" data-i18n="CRM">CRM/Khách hàng</div>
					</a>
				</li> -->
				<?php }?>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'project') {?> active<?php }?>">
					<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/thong-tin/" class="menu-link text-primary">
						<i class="menu-icon tf-icons bx bx-info-square"></i>
						<div class="text-truncate" data-i18n="Thông tin dự án">Thông tin dự án</div>
					</a>
				</li>
				<!-- <li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'map' && $_smarty_tpl->tpl_vars['sub']->value == 'default') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('map');?>
" class="menu-link text-primary" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-map"></i>
						<div class="text-truncate" data-i18n="Thông tin dự án">Bản đồ dự án</div>
					</a>
				</li>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'zoom') {?> open active<?php }?>">
					<a data-toggle="ripple" href="javascript:void(0);" class="menu-link menu-toggle">
						<i class="menu-icon tf-icons bx bx-map-alt"></i>
						<div class="text-truncate space-1" data-i18n="Mặt bằng dự án">Mặt bằng dự án</div>
					</a>
					<ul class="menu-sub">
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, '_oProject');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
?>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'detail' && $_GET['project_id'] == $_smarty_tpl->tpl_vars['_oProject']->value['project_id']) {?> active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkInfo($_smarty_tpl->tpl_vars['_oProject']->value['project_id'],0,0,@constant('_PROJECT_DOCS_LAYOUT_CATID'),'Mặt bằng');?>
" class="menu-link mx-1" data-toggle="ripple">
								<div class="text-truncate" data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['code'];?>
">Mặt bằng <?php echo $_smarty_tpl->tpl_vars['_oProject']->value['code'];?>
</div>
							</a>
						</li>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					</ul>			
				</li> -->		
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('calendar_billing')) {?>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'calendar') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/lich-ky.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-calendar"></i>
						<div class="text-truncate" data-i18n="Basic">Lịch ký HĐMB</div>
					</a>
				</li>
				<?php }?>		
				<!-- <li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'booking' && $_smarty_tpl->tpl_vars['act']->value == 'my_booking') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/my-booking.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-shopping-bag"></i>
						<div data-i18n="Telesale">My Booking</div>
						<span class="badge badge-demo bg-label-danger ms-auto"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['header_configs']->value['CompanyNameBrief'])===null||$tmp==='' ? 'FH' : $tmp);?>
</span>
					</a>
				</li> -->
			</ul>			
		</li>
		<!-- =========================-->
		<!-- =============Hoạt động============-->
		<li class="menu-item open <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'data_central') {?> active<?php }?>">
			<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block mx-0 text-left">
				<svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"  
				fill="var(--main-color)" viewBox="0 0 24 24" >
				<path d="M18.5 10H22v2h-3.5zm.05-1.17 1.5-1 1.5-1L21 6l-.55-.83-1.5 1-1.5 1L18 8zm0 4.34L18 14l-.55.83 1.5 1 1.5 1L21 16l.55-.83-1.5-1zM15 8.18V4c0-.37-.2-.71-.53-.88s-.72-.15-1.03.05L7.69 7h-1.7c-2.21 0-4 1.79-4 4 0 1.52.86 2.82 2.1 3.5l1.94 6.77 1.92-.55-1.64-5.73h1.37l5.75 3.83c.17.11.36.17.55.17.16 0 .32-.04.47-.12.33-.17.53-.51.53-.88v-4.18c1.16-.41 2-1.51 2-2.82s-.84-2.4-2-2.82Zm-2 7.95-4.45-2.96A1 1 0 0 0 8 13H6c-1.1 0-2-.9-2-2s.9-2 2-2h2c.2 0 .39-.06.55-.17L13 5.87z"></path>
				</svg>
				<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Hoạt động (<span id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" >0</span>)</span>
			</a>
			<ul class="menu-sub no-before" toId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'billing' && $_smarty_tpl->tpl_vars['act']->value == 'mileston') {?> active<?php }?>">
					<a href="/m-<?php echo smarty_modifier_date_format(time(),'%Y');?>
/" class="menu-link text-warning fw-bold"  data-toggle="ripple" >
						<img class="menu-icon tf-icons " src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/top-1.png">
						<div class="text-truncate" data-i18n="Support">Milestone <?php echo smarty_modifier_date_format(time(),"%Y");?>
</div>
					</a>
				</li>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('access_billing')) {?>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'billing') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/giao-dich.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-terminal"></i>
						<div class="text-truncate" data-i18n="Basic">Giao dịch chốt</div>
					</a>
				</li>
				<?php }?>
				<!-- <li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'share' && $_smarty_tpl->tpl_vars['share_type']->value == 'share') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/net-dep-lao-dong.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-run"></i>
						<div class="text-truncate" data-i18n="Hoạt động tiếp khách">Hoạt động tiếp khách</div>
					</a>
				</li> -->
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'course' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/lich-dao-tao.html" class="menu-link"data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-slideshow"></i>
						<div class="text-truncate" data-i18n="Sự kiện">Sự kiện</div>
					</a>
				</li>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'training' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('training');?>
" class="menu-link" data-toggle="ripple">
						<i class='menu-icon bx bx-play-circle'></i>
						<div class="text-truncate" data-i18n="Đào tạo">Đào tạo</div>
					</a>
				</li>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'project_docs') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('project_meta');?>
" title="Kho tài liệu" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bxs-graduation"></i>
						<div class="text-truncate" data-i18n="Học tập + Đào tạo">Kho tài liệu</div>
					</a>
				</li>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'news') {?> active<?php }?>">
					<a href="/ban-tin.html" class="js-ripple menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bxs-news"></i>
						<div class="text-truncate" data-i18n="Basic">Tin nội bộ</div>
					</a>
				</li>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'tool' && $_smarty_tpl->tpl_vars['act']->value == 'calendar') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/lich-phong-hop.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-camera-home"></i>
						<div class="text-truncate" data-i18n="Đăng ký phòng họp">Lịch phòng họp</div>
					</a>
				</li>
			</ul>			
		</li>			
		
		<!--		=========================-->
		<!--		==============Báo cáo===========-->
		<li class="menu-item open <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'data_central') {?> active<?php }?>">
			<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block mx-0 text-left">
				<i class="menu-icon tf-icons bx bx-pie-chart text-main"></i>
				<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Báo cáo(<span id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" >0</span>)</span>
			</a>
			<ul class="menu-sub no-before" toId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
					<!--<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'kpi' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/kpi.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-chart"></i>
							<div data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">Doanh số tháng</div>
						</a>
					</li>-->
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'report_dq') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bao-cao-doc-quyen.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-menu"></i>
							<div data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">Thống kê quỹ độc quyền</div>
						</a>
					</li> 
				<?php }?>
				<!--<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_sale_staff')) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['act']->value == 'sale') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/report/sale.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-pie-chart"></i>
							<div data-i18n="Doanh số nhân viên">Doanh số nhân viên</div>
						</a>
					</li>
				<?php }?> -->
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'campaign' && $_smarty_tpl->tpl_vars['act']->value == 'race') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/thi-dua-sale.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-bar-chart-square"></i>
							<div data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">Thi đua cá nhân <?php echo smarty_modifier_date_format(time(),"%Y");?>
</div>
						</a>
					</li> 		
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BUSINESS_AREA') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>
					<li class="menu-item <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'report_checkin') {?> active<?php }?>"> 
						<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('report-checkin');?>
"  class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-camera"></i>
							<div data-i18n="Căn bán đại lý">Tổng quan check-in</div>
						</a>
					</li>
				<?php }?>		
				<!-- <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>
					<li class="menu-item <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['sub']->value == 'project' && $_smarty_tpl->tpl_vars['act']->value == 'stock_sold') {?> active<?php }?>"> 
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/danh-sach-can-ban.html"  class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-dollar-circle"></i>
							<div data-i18n="Căn bán đại lý">Căn bán đại lý</div>
						</a>
					</li>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BO') && $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') && 1 == 2) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'overtime' && $_smarty_tpl->tpl_vars['act']->value == 'report') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/overtime/report.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
							<div data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">Thi đua khối BO <?php echo smarty_modifier_date_format(time(),"%Y");?>
</div>
						</a>
					</li>
				<?php }?> -->
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_sale') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_stock_resource') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('access_staff_all') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_crm') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_work') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_group') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('top_ten_sales') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('sales_has_trans') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('sales_not_trans') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('marketing_access')) {?>
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_sale') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
						<li class="menu-item <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['act']->value == 'sale_month') {?>active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/report/sales.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-cart"></i>
								<div data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">Báo cáo bán hàng</div>
							</a>
						</li>
					<?php }?>		
					<!--<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'sales_agent') {?> active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/report/sales_agent.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-store"></i>
								<div data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">Thống kê đại lý bán</div>
							</a>
						</li>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'report_agent') {?> active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/report/report_agent.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-briefcase"></i>
								<div data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">Báo cáo đại lý</div>
							</a>
						</li>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'revenue') {?> active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bao-cao-doanh-so.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
								<div data-i18n="Báo cáo doanh số">Báo cáo doanh số</div>
							</a>
						</li>
					<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_stock_resource')) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'stock_resource') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/report/stock-resource.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-building"></i>
							<div data-i18n="Check nguồn căn">Check nguồn căn</div>
						</a>
					</li>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('access_staff_all')) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'member' && $_smarty_tpl->tpl_vars['act']->value == 'report') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
//member/report.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-group"></i>
							<div data-i18n="Nhân sự">Nhân sự</div>
						</a>
					</li>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_work') || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'work') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/report/work.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-coffee"></i>
							<div data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">Hoạt động tiếp khách</div>
						</a>
					</li>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?> active<?php }?>"> 
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bao-cao.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-line-chart"></i>
							<div data-i18n="Báo cáo MOC">Hiệu suất bán hàng</div>
						</a>
					</li>
					<?php }?> -->
										<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_sale') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'report_login') {?> active<?php }?>"> 
							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bao-cao-dang-nhap.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-pulse"></i>
								<div data-i18n="Thống kê tần suất sử dụng">Tần suất truy cập</div>
							</a>
						</li>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('report_sale')) {?>
						<li class="menu-item d-none <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'request_ptg') {?> active<?php }?>"> 
							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bao-cao-phan-hoi-yeu-cau-ptg.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-request"></i>
								<div data-i18n="Báo cáo yêu cầu PTG">Phản hồi yêu cầu PTG</div>
							</a>
						</li>
					<?php }?>
					<?php $_smarty_tpl->_assignInScope('listBlockPage', $_smarty_tpl->tpl_vars['clsISO']->value->getListBlockPage());?>
					<?php if (!empty($_smarty_tpl->tpl_vars['listBlockPage']->value)) {?>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['listBlockPage']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
							<li class="menu-item <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'top' && $_smarty_tpl->tpl_vars['_oItem']->value['slug'] == $_smarty_tpl->tpl_vars['slug']->value) {?> active<?php }?>"> 
								<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bang-xep-hang-<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['slug'];?>
.html" class="menu-link" data-toggle="ripple">
									<i class="menu-icon tf-icons <?php if ($_smarty_tpl->tpl_vars['_oItem']->value['icon']) {
echo $_smarty_tpl->tpl_vars['_oItem']->value['icon'];
} else { ?>bx bxs-up-arrow<?php }?> text-reset"></i>
									<div data-i18n="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title_page'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title_menu'];?>
</div>
								</a>
							</li>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<?php }?>
				<?php }?>
			</ul>
		</li>
		<!--		=========================-->
		<!--		=============Công cụ Hệ Thống============-->
		<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('issue_access') || $_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>
		<!-- <li class="menu-item <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'data_central') {?> active<?php }?>">
				<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block menu-toggle mx-0 text-left">
					<i class="menu-icon tf-icons bx bx-cog text-main"></i>
					<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Công cụ Hệ Thống (<span id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" >0</span>)</span>
				</a>
				<ul class="menu-sub no-before" toId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('issue_access')) {?>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'issue') {?> active<?php }?>">
							<a href="/issue.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-task"></i>
								<div class="text-truncate" data-i18n="Quản lý công việc">Quản lý công việc</div>
							</a>
						</li>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT')) {?>
						<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>
							<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'notification') {?> active<?php }?>">
								<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('notification');?>
" class="menu-link text-primary" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-bell"></i>
									<div class="text-truncate" data-i18n="Support">Thông báo App</div>
								</a>
							</li>
							<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'notify') {?> active<?php }?>">
								<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('notify');?>
" class="menu-link text-main" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-bell"></i>
									<div class="text-truncate" data-i18n="Support">Thông báo nội bộ</div>
								</a>
							</li>
						<?php }?>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'booking' && $_smarty_tpl->tpl_vars['act']->value != 'stock_hug') {?> active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/booking.html" class="menu-link text-primary" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-shopping-bag"></i>
								<div data-i18n="Telesale">QL. Booking</div>
							</a>
						</li>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['act']->value == 'stock_hug') {?> active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/hug.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-hdd"></i>
								<div class="text-truncate" data-i18n="Basic">QL. Quỹ ôm</div>
							</a>
						</li>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'zalo') {?> active<?php }?>">
							<a href="/zalo/send.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-message"></i>
								<div data-i18n="Gửi tin Zalo">Gửi tin Zalo</div>
							</a>
						</li>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crawl' && $_smarty_tpl->tpl_vars['act']->value == 'crawl_highfloor') {?> active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('crawl_highfloor');?>
" class="menu-link " data-toggle="ripple">
								<i class="menu-icon tf-icons bx bxs-file-doc"></i>
								<div data-i18n="Cao tầng Excel">Cao tầng Excel</div>
							</a>
						</li>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crawl' && $_smarty_tpl->tpl_vars['act']->value == 'crawl_lowfloor') {?> active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('crawl_lowfloor');?>
" class="menu-link text-warning" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bxs-file-doc"></i>
								<div data-i18n="Thấp tầng Excel">Thấp tầng Excel</div>
							</a>
						</li>
						<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'crawl' && $_smarty_tpl->tpl_vars['act']->value == 'report_crawl') {?> active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('report_crawl');?>
" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-bar-chart-alt"></i>
								<div data-i18n="Thống kê">Thống kê</div>
							</a>
						</li>
					<?php }?>		
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission("incentive_access") || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'incentive') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('incentive');?>
" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bxs-medal"></i>
							<div class="text-truncate" data-i18n="Support">Chương trình thi đua</div>
						</a>
					</li>
					<?php }?>	
				</ul>
			</li> -->
		<?php }?>
		<!--		=========================-->		
        <!-- Misc -->
        <li class="menu-item open <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'data_central') {?> active<?php }?>">
			<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block mx-0 text-left">
				<i class="menu-icon tf-icons bx bx-dots-horizontal-rounded text-main"></i>
				<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Misc (<span id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" >0</span>)</span>
			</a>
			<ul class="menu-sub no-before" toId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT')) {?>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'request_ptg') {?> active<?php }?>">
					<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('request_ptg');?>
" class="menu-link">
						<i class="menu-icon tf-icons bx bx-vector"></i>
						<div class="text-truncate" data-i18n="CRM">Yêu cầu PTG</div>
						<?php if (!empty($_smarty_tpl->tpl_vars['total_request_pendding']->value)) {?>
						<span class="badge badge-demo bg-label-danger ms-auto"><?php echo $_smarty_tpl->tpl_vars['total_request_pendding']->value;?>
</span>
						<?php }?>
					</a>
				</li>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('log_search')) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'log' && $_smarty_tpl->tpl_vars['act']->value == 'log_sale') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/logs-sale.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-file"></i>
							<div class="text-truncate" data-i18n="Support">Logs tra cứu</div>
						</a>
					</li>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('log_search_all') && 1 == 2) {?>
					<li class="menu-item <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'log' && $_smarty_tpl->tpl_vars['act']->value == 'log_search') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/logs-search.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-file"></i>
							<div class="text-truncate" data-i18n="Support">Logs tìm kiếm</div>
						</a>
					</li>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR')) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report' && $_smarty_tpl->tpl_vars['act']->value == 'report_activity_log') {?> active<?php }?>">
						<a href="/log-he-thong.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-git-repo-forked"></i>
							<div class="text-truncate" data-i18n="Support">Log hệ thống</div>
						</a>
					</li>
				<?php }?>
				<!-- <li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'share' && $_smarty_tpl->tpl_vars['share_type']->value == 'honor') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/vinh-danh.html" class="menu-link" data-toggle="ripple">
						 <i class="menu-icon tf-icons bx bxs-bell-ring"></i>
						<div class="text-truncate" data-i18n="Vinh danh">Vinh danh bán hàng</div>
					</a>
				</li>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('manager_sop') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('manager_leasing') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('access_telesale')) {?>
				<li class="menu-header small text-uppercase">
					<span class="menu-header-text">Chuyển nhượng</span>
				</li>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('access_telesale')) {?>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'sop' && $_smarty_tpl->tpl_vars['act']->value == 'telesale') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/telesale.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Telesale">Quỹ căn Telesales</div>
					</a>
				</li>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('sop_chatlogs')) {?>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'sop' && $_smarty_tpl->tpl_vars['act']->value == 'chatlogs') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/sop/chatlogs.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Telesale">Tin nhắn Zalo Group</div>
					</a>
				</li>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('manager_sop')) {?>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'sop' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/chuyen-nhuong.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Chuyển nhượng">Chuyển nhượng</div>
					</a>
				</li>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('manager_leasing')) {?>
				<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'leasing' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?> active<?php }?>">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/cho-thue.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Cho thuê">Cho thuê</div>
					</a>
				</li>
				<?php }?>
				<?php }?> -->
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('log_login') && $_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
					<li class="menu-item<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'log' && $_smarty_tpl->tpl_vars['act']->value == 'login_history') {?> active<?php }?>">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/login-history.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-objects-vertical-bottom"></i>
							<div class="text-truncate" data-i18n="Support">Logs đăng nhập</div>
						</a>
					</li>
				<?php }?>
			</ul>
		</li>
    </ul>
</aside>

	<?php echo '<script'; ?>
>
		$(function(){
			$(".menu-sub.no-before").each(function(index, elm){
				var total = 0,
					toId = $(elm).attr("toId");
				$(".menu-item",$(elm)).each (function(i,_elm) {
					++total;
				});
				$("#"+toId).text(total);
				if(total == 0) {
					$(elm).closest(".menu-item").addClass("d-none")
				}
			});
		});
	<?php echo '</script'; ?>
>
<?php }
}
