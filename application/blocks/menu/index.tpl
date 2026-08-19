<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{$PCMS_URL}" class="app-brand-link pb-1 mx-auto d-flex flex-column justify-content-center">
            <span class="app-brand-logo demo">
				<img src="{$header_configs.HeaderLogo}" height="50" alt="{$PAGE_NAME}" />
			</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1 ps ps--active-y">
        <!-- Dashboard -->
		<li class="menu-item{if $mod eq 'home' && $sub eq 'default' && $act eq 'default'} active{/if}">
			<a href="{$PCMS_URL}" class="menu-link mx-0" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-home-circle"></i>
				<div class="text-truncate" data-i18n="Analytics">Trang chủ</div>
			</a>
		</li>
		<!--		==========Bán hàng===============-->
		<li class="menu-item open">
			{assign var=gId value=$clsISO->getUniqid()}
			<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block menu-toggle mx-0 text-left">
				<i class="menu-icon tf-icons bx bx-tag text-main"></i>
				{if $menu_v2}<span class="menu-header-text">Bán hàng</span><span class="menu-count" id="{$gId}">0</span>{else}<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Bán hàng (<span id="{$gId}" >0</span>)</span>{/if}
			</a>
			<ul class="menu-sub no-before" toId="{$gId}" >
				<li class="menu-item {if $mod eq 'stock'} active{/if}">
					<a data-toggle="ripple" href="{$clsISO->getLink('exclusive')}" class="menu-link">
						<i class="menu-icon tf-icons bx bxs-heart text-main"></i>
						<div class="text-truncate" data-i18n="Bảng hàng dự án">Quỹ độc quyền </div>
						<span class="badge badge-demo bg-label-warning ms-auto">{$header_configs.CompanyNameBrief|default:'FH'}</span>
					</a>
				</li>
				<li class="menu-item {if $mod eq 'home' && $sub eq 'project' && $act eq 'stock_list'} active{/if}">
					<a data-toggle="ripple" href="{$PCMS_URL}/bang-hang/" class="menu-link text-warning">
						<i class="menu-icon tf-icons bx bx-table"></i>
						<div class="text-truncate" data-i18n="Bảng hàng dự án">Bảng hàng dự án</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'tool' and $act eq 'tool'} active{/if}">
					<a data-toggle="ripple" href="/tool.html" class="menu-link">
						 <i class="menu-icon tf-icons bx bx-search"></i>
						<div class="text-truncate" data-i18n="Tra cứu căn hộ">Tra cứu căn hộ</div>
					</a>
				</li>
				{if $clsISO->checkPermission('access_crm')}
				 <li class="menu-item{if $mod eq 'crm' && $sub eq 'default' && $act eq 'default'} active{/if}">
					<a data-toggle="ripple" href="/crm/" class="menu-link">
						<i class="menu-icon tf-icons bx bx-user-pin"></i>
						<div class="text-truncate" data-i18n="CRM">CRM/Khách hàng</div>
					</a>
				</li> 
				{/if}
				<li class="menu-item{if $mod eq 'home' && $sub eq 'project' && $act eq 'project'} active{/if}">
					<a data-toggle="ripple" href="{$PCMS_URL}/thong-tin/" class="menu-link text-primary">
						<i class="menu-icon tf-icons bx bx-info-square"></i>
						<div class="text-truncate" data-i18n="Thông tin dự án">Thông tin dự án</div>
					</a>
				</li>
				<!-- <li class="menu-item{if $mod eq 'map' && $sub eq 'default'} active{/if}">
					<a href="{$clsISO->getLink('map')}" class="menu-link text-primary" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-map"></i>
						<div class="text-truncate" data-i18n="Thông tin dự án">Bản đồ dự án</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'home' && $act eq 'zoom'} open active{/if}">
					<a data-toggle="ripple" href="javascript:void(0);" class="menu-link menu-toggle">
						<i class="menu-icon tf-icons bx bx-map-alt"></i>
						<div class="text-truncate space-1" data-i18n="Mặt bằng dự án">Mặt bằng dự án</div>
					</a>
					<ul class="menu-sub">
						{foreach from = $list_projects item = _oProject}
						<li class="menu-item{if $mod eq 'home' && $act eq 'detail' && $smarty.get.project_id eq $_oProject.project_id} active{/if}">
							<a href="{$clsProject->getLinkInfo($_oProject.project_id, 0,0,$smarty.const._PROJECT_DOCS_LAYOUT_CATID,'Mặt bằng')}" class="menu-link mx-1" data-toggle="ripple">
								<div class="text-truncate" data-i18n="{$_oI.code}">Mặt bằng {$_oProject.code}</div>
							</a>
						</li>
						{/foreach}
					</ul>			
				</li> -->		
				{if $clsISO->checkPermission('calendar_billing')}
				<li class="menu-item{if $mod eq 'home' && $sub eq 'calendar'} active{/if}">
					<a href="{$PCMS_URL}/lich-ky.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-calendar"></i>
						<div class="text-truncate" data-i18n="Basic">Lịch ký HĐMB</div>
					</a>
				</li>
				{/if}		
				<!-- <li class="menu-item{if $mod == 'booking' && $act == 'my_booking'} active{/if}">
					<a href="{$PCMS_URL}/my-booking.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-shopping-bag"></i>
						<div data-i18n="Telesale">My Booking</div>
						<span class="badge badge-demo bg-label-danger ms-auto">{$header_configs.CompanyNameBrief|default:'FH'}</span>
					</a>
				</li> -->
			</ul>			
		</li>
		<!-- =========================-->
		<!-- =============Hoạt động============-->
		<li class="menu-item open">
			{assign var=gId value=$clsISO->getUniqid()}
			<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block menu-toggle mx-0 text-left">
				<svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"  
				fill="var(--main-color)" viewBox="0 0 24 24" >
				<path d="M18.5 10H22v2h-3.5zm.05-1.17 1.5-1 1.5-1L21 6l-.55-.83-1.5 1-1.5 1L18 8zm0 4.34L18 14l-.55.83 1.5 1 1.5 1L21 16l.55-.83-1.5-1zM15 8.18V4c0-.37-.2-.71-.53-.88s-.72-.15-1.03.05L7.69 7h-1.7c-2.21 0-4 1.79-4 4 0 1.52.86 2.82 2.1 3.5l1.94 6.77 1.92-.55-1.64-5.73h1.37l5.75 3.83c.17.11.36.17.55.17.16 0 .32-.04.47-.12.33-.17.53-.51.53-.88v-4.18c1.16-.41 2-1.51 2-2.82s-.84-2.4-2-2.82Zm-2 7.95-4.45-2.96A1 1 0 0 0 8 13H6c-1.1 0-2-.9-2-2s.9-2 2-2h2c.2 0 .39-.06.55-.17L13 5.87z"></path>
				</svg>
				{if $menu_v2}<span class="menu-header-text">Hoạt động</span><span class="menu-count" id="{$gId}">0</span>{else}<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Hoạt động (<span id="{$gId}" >0</span>)</span>{/if}
			</a>
			<ul class="menu-sub no-before" toId="{$gId}">
				<li class="menu-item{if $mod eq 'billing' && $act eq 'mileston'} active{/if}">
					<a href="/m-{$smarty.now|date_format:'%Y'}/" class="menu-link text-warning fw-bold"  data-toggle="ripple" >
						<img class="menu-icon tf-icons " src="{$URL_IMAGES}/top-1.png">
						<div class="text-truncate" data-i18n="Support">Milestone {$smarty.now|date_format:"%Y"}</div>
					</a>
				</li>
				{if $clsISO->checkPermission('access_billing')}
				<li class="menu-item{if $mod eq 'home' && $act eq 'billing'} active{/if}">
					<a href="{$PCMS_URL}/giao-dich.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-terminal"></i>
						<div class="text-truncate" data-i18n="Basic">Giao dịch chốt</div>
					</a>
				</li>
				{/if}
				<!-- <li class="menu-item{if $mod eq 'home' && $act eq 'share' && $share_type eq 'share'} active{/if}">
					<a href="{$PCMS_URL}/net-dep-lao-dong.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-run"></i>
						<div class="text-truncate" data-i18n="Hoạt động tiếp khách">Hoạt động tiếp khách</div>
					</a>
				</li> -->
				<li class="menu-item{if $mod eq 'home' && $sub eq 'course' && $act eq 'default'} active{/if}">
					<a href="{$PCMS_URL}/lich-dao-tao.html" class="menu-link"data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-slideshow"></i>
						<div class="text-truncate" data-i18n="Sự kiện">Sự kiện</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'training' && $act eq 'default'} active{/if}">
					<a href="{$clsISO->getLink('training')}" class="menu-link" data-toggle="ripple">
						<i class='menu-icon bx bx-play-circle'></i>
						<div class="text-truncate" data-i18n="Đào tạo">Đào tạo</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'project_docs'} active{/if}">
					<a href="{$clsISO->getLink('project_meta')}" title="Kho tài liệu" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bxs-graduation"></i>
						<div class="text-truncate" data-i18n="Học tập + Đào tạo">Kho tài liệu</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'home' && $act eq 'news'} active{/if}">
					<a href="/ban-tin.html" class="js-ripple menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bxs-news"></i>
						<div class="text-truncate" data-i18n="Basic">Tin nội bộ</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'tool' and $act eq 'calendar'} active{/if}">
					<a href="{$PCMS_URL}/lich-phong-hop.html" class="menu-link" data-toggle="ripple">
						<i class="menu-icon tf-icons bx bx-camera-home"></i>
						<div class="text-truncate" data-i18n="Đăng ký phòng họp">Lịch phòng họp</div>
					</a>
				</li>
			</ul>			
		</li>			
		
		<!--		=========================-->
		<!--		==============Báo cáo===========-->
		<li class="menu-item open">
			{assign var=gId value=$clsISO->getUniqid()}
			<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block menu-toggle mx-0 text-left">
				<i class="menu-icon tf-icons bx bx-pie-chart text-main"></i>
				{if $menu_v2}<span class="menu-header-text">Báo cáo</span><span class="menu-count" id="{$gId}">0</span>{else}<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Báo cáo(<span id="{$gId}" >0</span>)</span>{/if}
			</a>
			<ul class="menu-sub no-before" toId="{$gId}">
				{if $clsISO->checkPermissionGroup('DIRECTOR')}
					<!--<li class="menu-item{if $mod eq 'kpi' && $sub eq 'default' && $act eq 'default'} active{/if}">
						<a href="{$PCMS_URL}/kpi.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-chart"></i>
							<div data-i18n="{$_oProject.title}">Doanh số tháng</div>
						</a>
					</li>-->
					<li class="menu-item{if $mod eq 'report' && $sub eq 'project' && $act eq 'report_dq'} active{/if}">
						<a href="{$PCMS_URL}/bao-cao-doc-quyen.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-menu"></i>
							<div data-i18n="{$_oProject.title}">Thống kê quỹ độc quyền</div>
						</a>
					</li> 
				{/if}
				<!--{if $clsISO->checkPermission('report_sale_staff')}
					<li class="menu-item{if $mod eq 'report' && $sub eq 'default' && $act eq 'sale'} active{/if}">
						<a href="{$PCMS_URL}/report/sale.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-pie-chart"></i>
							<div data-i18n="Doanh số nhân viên">Doanh số nhân viên</div>
						</a>
					</li>
				{/if} -->
					<li class="menu-item{if $mod eq 'campaign' && $act eq 'race'} active{/if}">
						<a href="{$PCMS_URL}/thi-dua-sale.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-bar-chart-square"></i>
							<div data-i18n="{$_oProject.title}">Thi đua cá nhân {$smarty.now|date_format:"%Y"}</div>
						</a>
					</li> 		
				{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BUSINESS_AREA') || $clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkDEV()}
					<li class="menu-item {if $mod eq 'report' && $act eq 'report_checkin'} active{/if}"> 
						<a href="{$clsISO->getLink('report-checkin')}"  class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-camera"></i>
							<div data-i18n="Căn bán đại lý">Tổng quan check-in</div>
						</a>
					</li>
				{/if}		
				<!-- {if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkDEV()}
					<li class="menu-item {if $mod eq 'report' && $sub eq 'project' && $act eq 'stock_sold'} active{/if}"> 
						<a href="{$PCMS_URL}/danh-sach-can-ban.html"  class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-dollar-circle"></i>
							<div data-i18n="Căn bán đại lý">Căn bán đại lý</div>
						</a>
					</li>
				{/if}
				{if $clsISO->checkPermissionGroup('BO') && $clsISO->checkPermissionGroup('DIRECTOR') && 1==2}
					<li class="menu-item{if $mod eq 'overtime' && $act eq 'report'} active{/if}">
						<a href="{$PCMS_URL}/overtime/report.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
							<div data-i18n="{$_oProject.title}">Thi đua khối BO {$smarty.now|date_format:"%Y"}</div>
						</a>
					</li>
				{/if} -->
				{if $clsISO->checkPermissionGroup('DIRECTOR') 
					|| $clsISO->checkPermission('report_sale') 
					|| $clsISO->checkPermission('report_stock_resource') 
					|| $clsISO->checkPermission('access_staff_all') 
					|| $clsISO->checkPermission('report_crm') 
					|| $clsISO->checkPermission('report_work') 
					|| $clsISO->checkPermission('report_group') 
					|| $clsISO->checkPermission('top_ten_sales') 
					|| $clsISO->checkPermission('sales_has_trans')
					|| $clsISO->checkPermission('sales_not_trans') 
					|| $clsISO->checkPermission('marketing_access') }
						{if $clsISO->checkPermission('report_sale') || $clsISO->checkPermissionGroup('DIRECTOR')}
							<li class="menu-item">
								<a href="{$PCMS_URL}/report/sales.html" class="menu-link" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-cart"></i>
									<div data-i18n="{$_oProject.title}">Báo cáo bán hàng</div>
								</a>
							</li>
						{/if}		
						<!--{if $clsISO->checkPermissionGroup('DIRECTOR') }
							<li class="menu-item{if $mod eq 'report' && $act eq 'sales_agent'} active{/if}">
								<a href="{$PCMS_URL}/report/sales_agent.html" class="menu-link" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-store"></i>
									<div data-i18n="{$_oProject.title}">Thống kê đại lý bán</div>
								</a>
							</li>
							<li class="menu-item{if $mod eq 'report' && $act eq 'report_agent'} active{/if}">
								<a href="{$PCMS_URL}/report/report_agent.html" class="menu-link" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-briefcase"></i>
									<div data-i18n="{$_oProject.title}">Báo cáo đại lý</div>
								</a>
							</li>
						{/if}
						{if $clsISO->checkPermissionGroup('DIRECTOR') }
							<li class="menu-item{if $mod eq 'home' && $sub eq 'report' && $act eq 'revenue'} active{/if}">
								<a href="{$PCMS_URL}/bao-cao-doanh-so.html" class="menu-link" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
									<div data-i18n="Báo cáo doanh số">Báo cáo doanh số</div>
								</a>
							</li>
						{/if}
						 {if $clsISO->checkPermission('report_stock_resource')}
						<li class="menu-item{if $mod eq 'report' and $act eq 'stock_resource'} active{/if}">
							<a href="{$PCMS_URL}/report/stock-resource.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-building"></i>
								<div data-i18n="Check nguồn căn">Check nguồn căn</div>
							</a>
						</li>
						{/if}
						{if $clsISO->checkPermission('access_staff_all')}
						<li class="menu-item{if $mod eq 'member' && $act eq 'report'} active{/if}">
							<a href="{$PCMS_URL}//member/report.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-group"></i>
								<div data-i18n="Nhân sự">Nhân sự</div>
							</a>
						</li>
						{/if}
						{if $clsISO->checkPermission('report_work') || $clsISO->_DEV()}
						<li class="menu-item{if $mod eq 'report' and $act eq 'work'} active{/if}">
							<a href="{$PCMS_URL}/report/work.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-coffee"></i>
								<div data-i18n="{$_oProject.title}">Hoạt động tiếp khách</div>
							</a>
						</li>
						{/if}
						{if $clsISO->checkPermissionGroup('DIRECTOR')}
						<li class="menu-item{if $mod eq 'home' && $sub eq 'report' && $act eq 'default'} active{/if}"> 
							<a href="{$PCMS_URL}/bao-cao.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-line-chart"></i>
								<div data-i18n="Báo cáo MOC">Hiệu suất bán hàng</div>
							</a>
						</li>
						{/if} -->
						{*{if $clsISO->checkPermission('top_ten_sales')}
						<li class="menu-item{if $mod eq 'report' and $act eq 'top_10'} active{/if}"> 
							<a href="{$PCMS_URL}/report/top-10.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-crown"></i>
								<div data-i18n="Báo cáo MOC">Top 10 sale</div>
							</a>
						</li>{/if}
						{if $clsISO->checkPermission('sales_has_trans')}
						<li class="menu-item{if $mod eq 'report' and $act eq 'sales_has_trans'} active{/if}"> 
							<a href="{$PCMS_URL}/report/sales-has-trans.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-check-square"></i>
								<div data-i18n="Sale đã có GD">Sale đã có GD</div>
							</a>
						</li>{/if}
						{if $clsISO->checkPermission('sales_not_trans')}
							<li class="menu-item{if $mod eq 'report' and $act eq 'top_sales'} active{/if}"> 
								<a href="{$PCMS_URL}/report/sale-no-trans.html" class="menu-link" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-minus-circle"></i>
									<div data-i18n="Sale chưa có GD">Sale chưa có GD</div>
								</a>
							</li>
						{/if}*}
						{if $clsISO->checkPermission('report_sale') || $clsISO->checkPermissionGroup('DIRECTOR')}
							<li class="menu-item{if $mod eq 'home' && $sub eq 'report' && $act eq 'report_login'} active{/if}"> 
								<a href="{$PCMS_URL}/bao-cao-dang-nhap.html" class="menu-link" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-pulse"></i>
									<div data-i18n="Thống kê tần suất sử dụng">Tần suất truy cập</div>
								</a>
							</li>
						{/if}
				
						{if $clsISO->checkPermission('report_sale')}
							<li class="menu-item d-none {if $mod eq 'report' and $act eq 'request_ptg'} active{/if}"> 
								<a href="{$PCMS_URL}/bao-cao-phan-hoi-yeu-cau-ptg.html" class="menu-link" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-request"></i>
									<div data-i18n="Báo cáo yêu cầu PTG">Phản hồi yêu cầu PTG</div>
								</a>
							</li>
						{/if}
						{assign var=listBlockPage value=$clsISO->getListBlockPage()}
						{if !empty($listBlockPage)}
							{foreach from=$listBlockPage item=_oItem key=key name=i}
							<li class="menu-item {if $mod eq 'home' && $sub eq 'report' && $act eq 'top' && $_oItem.slug eq $slug} active{/if}"> 
								<a href="{$PCMS_URL}/bang-xep-hang-{$_oItem.slug}.html" class="menu-link" data-toggle="ripple">
									<i class="menu-icon tf-icons {if $_oItem.icon}{$_oItem.icon}{else}bx bxs-up-arrow{/if} text-reset"></i>
									<div data-i18n="{$_oItem.title_page}">{$_oItem.title_menu}</div>
								</a>
							</li>
							{/foreach}
						{/if}
				{/if}
			</ul>
		</li>
	
		<!--		=========================-->
		<!--		=============Công cụ Hệ Thống============-->
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermission('issue_access') || $clsISO->checkDEV()}
		<!-- <li class="menu-item open">
				{assign var=gId value=$clsISO->getUniqid()}
				<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block menu-toggle mx-0 text-left">
					<i class="menu-icon tf-icons bx bx-cog text-main"></i>
					<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Công cụ Hệ Thống (<span id="{$gId}" >0</span>)</span>
				</a>
				<ul class="menu-sub no-before" toId="{$gId}">
					{if $clsISO->checkPermission('issue_access')}
						<li class="menu-item{if $mod eq 'issue'} active{/if}">
							<a href="/issue.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-task"></i>
								<div class="text-truncate" data-i18n="Quản lý công việc">Quản lý công việc</div>
							</a>
						</li>
					{/if}
					{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')}
						{if $clsISO->checkDEV()}
							<li class="menu-item{if $mod eq 'notification'} active{/if}">
								<a href="{$clsISO->getLink('notification')}" class="menu-link text-primary" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-bell"></i>
									<div class="text-truncate" data-i18n="Support">Thông báo App</div>
								</a>
							</li>
							<li class="menu-item{if $mod eq 'notify'} active{/if}">
								<a href="{$clsISO->getLink('notify')}" class="menu-link text-main" data-toggle="ripple">
									<i class="menu-icon tf-icons bx bx-bell"></i>
									<div class="text-truncate" data-i18n="Support">Thông báo nội bộ</div>
								</a>
							</li>
						{/if}
						<li class="menu-item{if $mod eq 'booking' and $act ne 'stock_hug'} active{/if}">
							<a href="{$PCMS_URL}/booking.html" class="menu-link text-primary" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-shopping-bag"></i>
								<div data-i18n="Telesale">QL. Booking</div>
							</a>
						</li>
						<li class="menu-item{if $act eq 'stock_hug'} active{/if}">
							<a href="{$PCMS_URL}/hug.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-hdd"></i>
								<div class="text-truncate" data-i18n="Basic">QL. Quỹ ôm</div>
							</a>
						</li>
						<li class="menu-item{if $mod eq 'zalo'} active{/if}">
							<a href="/zalo/send.html" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-message"></i>
								<div data-i18n="Gửi tin Zalo">Gửi tin Zalo</div>
							</a>
						</li>
						<li class="menu-item{if $mod eq 'crawl' && $act eq 'crawl_highfloor'} active{/if}">
							<a href="{$clsISO->getLink('crawl_highfloor')}" class="menu-link " data-toggle="ripple">
								<i class="menu-icon tf-icons bx bxs-file-doc"></i>
								<div data-i18n="Cao tầng Excel">Cao tầng Excel</div>
							</a>
						</li>
						<li class="menu-item{if $mod eq 'crawl' && $act eq 'crawl_lowfloor'} active{/if}">
							<a href="{$clsISO->getLink('crawl_lowfloor')}" class="menu-link text-warning" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bxs-file-doc"></i>
								<div data-i18n="Thấp tầng Excel">Thấp tầng Excel</div>
							</a>
						</li>
						<li class="menu-item{if $mod eq 'crawl' && $act eq 'report_crawl'} active{/if}">
							<a href="{$clsISO->getLink('report_crawl')}" class="menu-link" data-toggle="ripple">
								<i class="menu-icon tf-icons bx bx-bar-chart-alt"></i>
								<div data-i18n="Thống kê">Thống kê</div>
							</a>
						</li>
					{/if}		
					{if $clsISO->checkPermission("incentive_access") || $clsISO->_DEV()}
					<li class="menu-item{if $mod eq 'incentive'} active{/if}">
						<a href="{$clsISO->getLink('incentive')}" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bxs-medal"></i>
							<div class="text-truncate" data-i18n="Support">Chương trình thi đua</div>
						</a>
					</li>
					{/if}	
				</ul>
			</li> -->
		{/if}
		<!--		=========================-->		
        <!-- Misc -->
        <li class="menu-item open">
			{assign var=gId value=$clsISO->getUniqid()}
			<a data-toggle="ripple" href="javascript:void(0);" class="menu-link w-100 d-block menu-toggle mx-0 text-left">
				<i class="menu-icon tf-icons bx bx-dots-horizontal-rounded text-main"></i>
				{if $menu_v2}<span class="menu-header-text">Misc</span><span class="menu-count" id="{$gId}">0</span>{else}<span class="menu-header-text text-upper text-main fs-11 fw-semibold">Misc (<span id="{$gId}" >0</span>)</span>{/if}
			</a>
			<ul class="menu-sub no-before" toId="{$gId}">
				{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')}
				<li class="menu-item{if $mod eq 'request_ptg'} active{/if}">
					<a data-toggle="ripple" href="{$clsISO->getLink('request_ptg')}" class="menu-link">
						<i class="menu-icon tf-icons bx bx-vector"></i>
						<div class="text-truncate" data-i18n="CRM">Yêu cầu PTG</div>
						{if !empty($total_request_pendding)}
						<span class="badge badge-demo bg-label-danger ms-auto">{$total_request_pendding}</span>
						{/if}
					</a>
				</li>
				{/if}

				{if $clsISO->checkPermission('log_search')}
					<li class="menu-item{if $mod eq 'log' and $act eq 'log_sale'} active{/if}">
						<a href="{$PCMS_URL}/logs-sale.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-file"></i>
							<div class="text-truncate" data-i18n="Support">Logs tra cứu</div>
						</a>
					</li>
				{/if}

				{if $clsISO->checkPermission('log_search_all') && 1 eq 2}
					<li class="menu-item {if $mod eq 'log' and $act eq 'log_search'} active{/if}">
						<a href="{$PCMS_URL}/logs-search.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-file"></i>
							<div class="text-truncate" data-i18n="Support">Logs tìm kiếm</div>
						</a>
					</li>
				{/if}
				
				{if $clsISO->checkPermissionGroup('DIRECTOR')}
					<li class="menu-item{if $mod eq 'report' and $act eq 'report_activity_log'} active{/if}">
						<a href="/log-he-thong.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-git-repo-forked"></i>
							<div class="text-truncate" data-i18n="Support">Log hệ thống</div>
						</a>
					</li>
				{/if}
				<!-- <li class="menu-item{if $mod eq 'home' && $act eq 'share' && $share_type eq 'honor'} active{/if}">
					<a href="{$PCMS_URL}/vinh-danh.html" class="menu-link" data-toggle="ripple">
						 <i class="menu-icon tf-icons bx bxs-bell-ring"></i>
						<div class="text-truncate" data-i18n="Vinh danh">Vinh danh bán hàng</div>
					</a>
				</li>
				{if $clsISO->checkPermission('manager_sop') 
					|| $clsISO->checkPermission('manager_leasing') 
					|| $clsISO->checkPermission('access_telesale')}
				<li class="menu-header small text-uppercase">
					<span class="menu-header-text">Chuyển nhượng</span>
				</li>
				{if $clsISO->checkPermission('access_telesale')}
				<li class="menu-item{if $mod eq 'sop' and $act eq 'telesale'} active{/if}">
					<a href="{$PCMS_URL}/telesale.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Telesale">Quỹ căn Telesales</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('sop_chatlogs')}
				<li class="menu-item{if $mod eq 'sop' and $act eq 'chatlogs'} active{/if}">
					<a href="{$PCMS_URL}/sop/chatlogs.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Telesale">Tin nhắn Zalo Group</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('manager_sop')}
				<li class="menu-item{if $mod eq 'sop' and $act eq 'default'} active{/if}">
					<a href="{$PCMS_URL}/chuyen-nhuong.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Chuyển nhượng">Chuyển nhượng</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('manager_leasing')}
				<li class="menu-item{if $mod eq 'leasing' and $act eq 'default'} active{/if}">
					<a href="{$PCMS_URL}/cho-thue.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Cho thuê">Cho thuê</div>
					</a>
				</li>
				{/if}
				{/if} -->
				{if $clsISO->checkPermission('log_login') && $clsISO->_DEV()}
					<li class="menu-item{if $mod eq 'log' and $act eq 'login_history'} active{/if}">
						<a href="{$PCMS_URL}/login-history.html" class="menu-link" data-toggle="ripple">
							<i class="menu-icon tf-icons bx bx-objects-vertical-bottom"></i>
							<div class="text-truncate" data-i18n="Support">Logs đăng nhập</div>
						</a>
					</li>
				{/if}
			</ul>
		</li>
    </ul>
	{if $menu_v2 && $profile_id > 0}
	<div class="menu-footer">
		<a href="{$clsISO->getLink('logout')}" class="menu-footer-link">
			<div class="avatar avatar-sm flex-shrink-0">
				<img src="{$clsProfile->getAvatar($profile_id,$oneProfile,40,40)}" onerror="this.src='{$URL_IMAGES}/avatars/1.png'" class="rounded-circle" />
			</div>
			<div class="menu-footer-info d-flex flex-column min-w-0 gap-1 flex-grow-1">
				<span class="menu-footer-name">{$oneProfile.full_name|escape}</span>
				<span class="menu-footer-role">{$oneProfile.role_name|escape}</span>
			</div>
			<i class="bx bx-log-out menu-footer-out flex-shrink-0"></i>
		</a>
	</div>
	{/if}
</aside>
{literal}
<script>
	$(function(){
		$(".menu-sub.no-before").each(function(index, elm){
			var total = 0,
				toId = $(elm).attr("toId");
			$(".menu-item",$(elm)).each (function(i,_elm) {
				++total;
			});
			console.log(total);
			$("#"+toId).text(total);
			if(total == 0) {
				$(elm).closest(".menu-item").addClass("d-none")
			}
		});
	});
</script>
{/literal}