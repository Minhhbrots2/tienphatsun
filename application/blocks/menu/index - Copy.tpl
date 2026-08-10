<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{$PCMS_URL}" class="app-brand-link mx-auto d-flex flex-column justify-content-center">
            <span class="app-brand-logo demo">
				<img class="img-fluid" src="{$URL_IMAGES}/logo-icon.png" width="50" />
			</span>
			<span class="app-brand-text demo">Hệ thống quản trị nội bộ</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1 ps ps--active-y">
        <!-- Dashboard -->
		<li class="menu-item{if $mod eq 'home' && $sub eq 'default' && $act eq 'default'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-home-circle"></i>
				<div class="text-truncate" data-i18n="Analytics">Trang chủ</div>
			</a>
		</li>				
		<li class="menu-item{if $mod eq 'home' and $act eq 'ultilities'} active{/if}">
            <a data-toggle="ripple" href="/tien-ich/" class="menu-link">
                <i class="menu-icon tf-icons bx bx-sun"></i>
                <div class="text-truncate" data-i18n="Support">Trung tâm tiện ích</div>
            </a>
        </li>
		<li class="menu-item{if $mod eq 'training'} active{/if}">
			<a data-toggle="ripple" href="{$clsISO->getLink('training')}" class="menu-link">
				<i class='menu-icon bx bx-play-circle'></i>
				<div class="text-truncate" data-i18n="Trung tâm đào tạo">Trung tâm đào tạo</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'home' && $act eq 'news'} active{/if}">
			<a data-toggle="ripple" href="/ban-tin.html" class="menu-link">
				<i class="menu-icon tf-icons bx bxs-news"></i>
				<div class="text-truncate" data-i18n="Basic">Bản tin FG</div>
			</a>
		</li>
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkDEV()}
			<li class="menu-item{if $mod eq 'report' and $act eq 'report_activity_log'} active{/if}">
				<a data-toggle="ripple" href="/log-he-thong.html" class="menu-link">
					<i class="menu-icon tf-icons bx bx-git-repo-forked"></i>
					<div class="text-truncate" data-i18n="Support">Log hệ thống</div>
				</a>
			</li>
		{/if}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Bán hàng</span>
		</li>
		{if $sale_policy.status eq '1' && 1==2}
		<li class="menu-item">
			<a data-toggle="ripple" href="javascript:void(0);" route="/sale-policy" onClick="$Core.helper.open_policy(this, event)" 
			tp="_view" class="menu-link">
				<i class='menu-icon tf-icons bx bx-shield-alt-2'></i>
				<div data-i18n="Chính sách bán hàng">Chính sách bán hàng</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('search_stock')}
		<li class="menu-item{if $mod eq 'tool' and $act eq 'tool'} active{/if}">
			<a data-toggle="ripple" href="/tool.html" class="menu-link">
				 <i class="menu-icon tf-icons bx bx-search"></i>
				<div class="text-truncate" data-i18n="Tra cứu căn hộ">Tra cứu căn hộ</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('access_crm')}
		<li class="menu-item{if $mod eq 'crm' && $sub eq 'default' && $act eq 'default'} active{/if}">
			<a data-toggle="ripple" href="/crm/" class="menu-link">
				<i class="menu-icon tf-icons bx bx-user-pin"></i>
				<div class="text-truncate" data-i18n="CRM">CRM/Khách hàng</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $profile_id eq 289}
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
		<li class="menu-item {if $mod eq 'home' && $sub eq 'project' && $act eq 'stock_list'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/bang-hang/" class="menu-link text-main">
				<i class="menu-icon tf-icons bx bx-table"></i>
				<div class="text-truncate" data-i18n="Bảng hàng dự án">Bảng hàng dự án</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'home' && $sub eq 'project' && $act eq 'project'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/thong-tin/" class="menu-link text-primary">
				<i class="menu-icon tf-icons bx bx-info-square"></i>
				<div class="text-truncate" data-i18n="Thông tin dự án">Thông tin dự án</div>
			</a>
		</li>
		<!-- {foreach name=i from=$list_projects item = _oProject}
		<li class="menu-item">
			<a data-toggle="ripple" href="{$_oProject.link}" class="menu-link">
				{if !empty($_oProject.logo)}
					<img class="menu-icon tf-icons menu_image" width="20" src="{$PCMS_URL}{$_oProject.logo}">
				{else}
					<i class="menu-icon tf-icons bx bx-table"></i>
				{/if}
				<div class="text-truncate" data-i18n="Dự án {$_oProject.code}">Dự án {$_oProject.code}</div>
			</a>
		</li>
		{/foreach} -->
		{*<li class="menu-item open{if $mod eq 'home' && $act eq 'stock'} active{/if}">
				<a data-toggle="ripple" href="javascript:void(0);" class="menu-link text-main menu-toggle">
					 <i class="menu-icon tf-icons bx bx-table"></i>
					<div class="text-truncate" data-i18n="Bảng hàng dự án">Bảng hàng dự án</div>
				</a>
				{if !empty($list_projects)}
				<ul class="menu-sub">		
					<li class="menu-item"> 
						<a href="{$PCMS_URL}/report/report_stock_price.html" class="menu-link">
							<div data-i18n="Tổng quan giá các phân khu">Tổng quan giá phân khu</div>
						</a>
					</li>
					{if !empty($list_quick_menus)}
						{section name=i loop=$list_quick_menus}
						<li class="menu-item">
							<a data-toggle="ripple" href="{$list_quick_menus[i].link}" class="menu-link">
								<div class="text-truncate" data-i18n="{$list_quick_menus[i].title}">{$list_quick_menus[i].title}</div>
							</a>
						</li>
						{/section}
					{/if}
					{if !empty($list_lsb_builings)}
					<li class="menu-item">
						<a data-toggle="ripple" href="javascript:void(0);" class="menu-link menu-toggle">
							<div class="text-truncate" data-i18n="Lumière SpringBay">Lumière SpringBay</div>
						</a>
						<ul class="menu-sub">
							{section name=i loop=$list_lsb_builings}
							<li class="menu-item">
								<a data-toggle="ripple" class="menu-link" href="{$list_lsb_builings[i].link}">
									<div data-i18n="{$list_lsb_builings[i].title}">{$list_lsb_builings[i].title}</div>
								</a>
							</li>
							{/section}
						</ul>
					</li>
					{/if}
					{if !empty($list_mga_builings)}
					<li class="menu-item">
						<a data-toggle="ripple" href="javascript:void(0);" class="menu-link menu-toggle">
							<div class="text-truncate" data-i18n="Masteri Grand Avenue">Masteri Grand Avenue</div>
						</a>
						<ul class="menu-sub">
							{section name=i loop=$list_mga_builings}
							<li class="menu-item">
								<a data-toggle="ripple" class="menu-link" href="{$list_mga_builings[i].link}">
									<div data-i18n="{$list_mga_builings[i].title}">{$list_mga_builings[i].title}</div>
								</a>
							</li>
							{/section}
						</ul>
					</li>
					{/if}
					{if !empty($list_mwf_builings) && 1==2}
					<li class="menu-item">
						<a data-toggle="ripple" href="javascript:void(0);" class="menu-link menu-toggle">
							<div class="text-truncate" data-i18n="Masteri Waterfront">Masteri Waterfront</div>
						</a>
						<ul class="menu-sub">
							{section name=i loop=$list_mwf_builings}
							<li class="menu-item">
								<a data-toggle="ripple" class="menu-link" href="{$list_mwf_builings[i].link}">
									<div data-i18n="{$list_mwf_builings[i].title}">{$list_mwf_builings[i].title}</div>
								</a>
							</li>
							{/section}
						</ul>
					</li>
					{/if}
					{foreach name=i from=$list_projects item = _oProject}
					<li class="menu-item">
						<a data-toggle="ripple" href="{$_oProject.link}" class="menu-link">
							<div data-i18n="Dự án {$_oProject.code}">Dự án {$_oProject.code}</div>
						</a>
					</li>
					{/foreach}
				</ul>
				{/if}
			</li>*}
		{if $clsISO->checkDEV()}
		<li class="menu-item d-none {if $mod eq 'client'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/xac-nhan-hoa-hong.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-bitcoin"></i>
				<div class="text-truncate" data-i18n="Xác nhận hoa hồng">Xác nhận hoa hồng</div>
				<span class="badge badge-demo bg-label-danger ms-auto">5</span>
			</a>
		</li>
		<!-- <li class="menu-item{if $mod eq 'client'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/quan-ly-khach-hang.html" class="menu-link">
				<i class='menu-icon tf-icons bx bx-user'></i>
				<div class="text-truncate" data-i18n="Basic">Quản lý khách hàng</div>
			</a>
		</li> -->
		{/if}
		<li class="menu-item{if $mod eq 'home' && $act eq 'share' && $share_type eq 'share'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/net-dep-lao-dong.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-image"></i>
				<div class="text-truncate" data-i18n="Hoạt động tiếp khách">Hoạt động tiếp khách</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'home' && $act eq 'share' && $share_type eq 'honor'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/vinh-danh.html" class="menu-link">
				 <i class="menu-icon tf-icons bx bxs-bell-ring"></i>
				<div class="text-truncate" data-i18n="Vinh danh">Vinh danh bán hàng</div>
			</a>
		</li>
		{if $clsISO->checkPermissionGroup('DIRECTOR')}
		<li class="menu-item{if $mod eq 'home' && $act eq 'share' && $share_type eq 'secret'} active{/if}">
			<a data-toggle="ripple" href="/thong-tin-mat.html" class="menu-link">
				 <i class="menu-icon tf-icons bx bx-check-shield"></i>
				<div class="text-truncate" data-i18n="Thông tin mật">Thông tin mật</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('access_billing')}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Giao dịch chốt</span>
		</li>
		<li class="menu-item{if $mod eq 'home' && $act eq 'billing'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/giao-dich.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-terminal"></i>
				<div class="text-truncate" data-i18n="Basic">Giao dịch chốt</div>
			</a>
		</li>
		{if $clsISO->checkDEV()}
		<li class="menu-item{if $mod eq 'home' && $sub eq 'calendar'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/lich-ky.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-calendar"></i>
				<div class="text-truncate" data-i18n="Basic">Lịch ký HĐMB</div>
			</a>
		</li>
		{/if}
		<li class="menu-item{if $mod eq 'report' && $act eq 'billing' && $report_type eq 'common'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/billing/report.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-doughnut-chart"></i>
				<div class="text-truncate" data-i18n="Basic">Báo cáo bán hàng</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'report' && $act eq 'billing' && $report_type eq 'mwf'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/billing/report/mwf.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-pie-chart-alt-2"></i>
				<div class="text-truncate" data-i18n="Basic">Báo cáo quỹ F1</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'stock_hug'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/hug.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-hdd"></i>
				<div class="text-truncate" data-i18n="Basic">Quỹ ôm Masteri</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('okrs_access') && 1==2}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">OKRs</span>
		</li>
		<li class="menu-item{if $mod eq 'okrs' and $act eq 'default'} active{/if}">
			<a data-toggle="ripple" href="/okrs.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-shape-circle"></i>
				<div class="text-truncate" data-i18n="OKRs">OKRs</div>
			</a>
		</li>
		<li class="menu-item">
			<a data-toggle="ripple" href="/okrs/check-ins.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-shape-circle"></i>
				<div class="text-truncate" data-i18n="Check-ins">Check-ins</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('fund_access')}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Kế toán</span>
		</li>
		<li class="menu-item{if $mod eq 'fund' && $act ne 'report'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/fund.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-money"></i>
				<div class="text-truncate" data-i18n="Thu chi nội bộ">Thu chi nội bộ</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'acc' && $sub eq 'vat'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/vat.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-shape-circle"></i>
				<div class="text-truncate" data-i18n="VAT đã xuất">VAT đã xuất</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'acc' && $sub eq 'commission'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/commission.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-shape-circle"></i>
				<div class="text-truncate" data-i18n="Hoa hồng bán mới">Hoa hồng bán mới</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'fund' && $act eq 'report'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/fund/report.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
				<div class="text-truncate" data-i18n="Thu chi nội bộ">Báo cáo thu chi</div>
			</a>
		</li>
		{/if}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Hoạt động</span>
		</li>
		{if $clsISO->checkPermission('access_course')}
		<li class="menu-item{if $mod eq 'home' && $sub eq 'course' && $act eq 'default'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/lich-dao-tao.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-slideshow"></i>
				<div class="text-truncate" data-i18n="Sự kiện+Đào tạo">Sự kiện+Đào tạo</div>
			</a>
		</li>
		{/if}
		<li class="menu-item{if $mod eq 'tool' and $act eq 'calendar'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/lich-phong-hop.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-camera-home"></i>
				<div class="text-truncate" data-i18n="Đăng ký phòng họp">Đăng ký phòng họp</div>
			</a>
		</li>
		<li class="menu-item d-none">
			<a data-toggle="ripple" href="{$PCMS_URL}/worktime-staff.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-run"></i>
				<div class="text-truncate" data-i18n="Basic">Nghỉ phép(NV)</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'home' & $sub eq 'worktime'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/worktime.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-run"></i>
				<div class="text-truncate" data-i18n="Basic">Nghỉ phép</div>
			</a>
		</li>
		{if $clsISO->checkDEV()}
			<li class="menu-item{if $mod eq 'home' && $act eq 'today'} active{/if}">
				<a data-toggle="ripple" href="/can-ho-noi-bat.html" class="menu-link">
					<i class='menu-icon bx bx-donate-heart'></i>
					<div class="text-truncate" data-i18n="Basic">Căn hộ nổi bật</div>
				</a>
			</li>
		{/if}
		{*{if $clsISO->checkDEV()}
			<li class="menu-item{if $mod eq 'home' && $act eq 'gratitude'} active{/if}">
				<a data-toggle="ripple" href="/tri-an.html" class="menu-link">
					<i class='menu-icon bx bx-donate-heart'></i>
					<div class="text-truncate" data-i18n="Basic">Tri ân</div>
				</a>
			</li>
		{/if}*}
		<li class="menu-item{if $mod eq 'home' && $sub eq 'course' && $act eq 'slide'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/hoc-tap.html" title="Kho tài liệu" class="menu-link">
				<i class="menu-icon tf-icons bx bxs-graduation"></i>
				<div class="text-truncate" data-i18n="Học tập + Đào tạo">Kho tài liệu</div>
			</a>
		</li>
		{if $clsISO->checkPermissionGroup('BO') || $clsISO->checkPermissionGroup('DIRECTOR')}
		<li class="menu-item{if $mod eq 'overtime' && $act eq 'default'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/overtime.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-timer"></i>
				<div class="text-truncate" data-i18n="Làm thêm giờ">Đăng ký tăng ca</div>
			</a>
		</li>
		{/if}
		<li class="menu-item{if $mod eq 'issue'} active{/if}">
			<a data-toggle="ripple" data-toggle="ripple" data-toggle="ripple" href="/issue.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-task"></i>
				<div class="text-truncate" data-i18n="Quản lý công việc">Quản lý công việc</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'report' && $act eq 'register_mwf'} active{/if}">
			<a data-toggle="ripple" data-toggle="ripple" href="/report/register_mwf.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-registered"></i>
				<div class="text-truncate" data-i18n="ĐK. Xem nhà mẫu">ĐK. Xem nhà mẫu</div>
			</a>
		</li>
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Báo cáo</span>
		</li>
		{if $clsISO->checkPermission('report_sale_month')}
		<li class="menu-item{if $mod eq 'kpi' && $sub eq 'default' && $act eq 'default'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/kpi.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-chart"></i>
				<div data-i18n="{$_oProject.title}">Doanh số tháng</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('report_sale_staff')}
		<li class="menu-item{if $mod eq 'report' && $sub eq 'default' && $act eq 'sale'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/report/sale.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-pie-chart"></i>
				<div data-i18n="Doanh số nhân viên">Doanh số nhân viên</div>
			</a>
		</li>
		{/if}
		<li class="menu-item{if $mod eq 'campaign' && $sub eq 'default' && $act eq 'view' && $string eq 'NS1WaWV0SVNP'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/campaign/NS1WaWV0SVNP.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-doughnut-chart"></i>
				<div data-i18n="{$_oProject.title}">Thi đua team {$smarty.now|date_format:"%Y"}</div>
			</a>
		</li>
        <li class="menu-item{if $mod eq 'campaign' && $sub eq 'default' && $act eq 'view' && $string eq 'NC1WaWV0SVNP'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/campaign/NC1WaWV0SVNP.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-bar-chart-square"></i>
				<div data-i18n="{$_oProject.title}">Thi đua cá nhân {$smarty.now|date_format:"%Y"}</div>
			</a>
		</li>
		{if $clsISO->checkPermissionGroup('BO') || $clsISO->checkPermissionGroup('DIRECTOR')}
		<li class="menu-item{if $mod eq 'overtime' && $act eq 'report'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/overtime/report.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
				<div data-i18n="{$_oProject.title}">Thi đua khối BO {$smarty.now|date_format:"%Y"}</div>
			</a>
		</li>
		{/if}
		<li class="menu-item">
			<a data-toggle="ripple" href="javascript:void(0);" class="menu-link menu-toggle">
				 <i class="menu-icon tf-icons bx bx-line-chart"></i>
				<div class="text-truncate" data-i18n="Báo cáo khác">Báo cáo khác</div>
			</a>
			<ul class="menu-sub">
				{if $clsISO->checkPermission('report_sale')}
				<li class="menu-item">
					<a href="{$PCMS_URL}/report/sale_month.html" class="menu-link">
						<div data-i18n="{$_oProject.title}">KQ. kinh doanh {$smarty.now|date_format:"%Y"}</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_stock_resource')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'stock_resource'} active{/if}">
					<a href="{$PCMS_URL}/report/stock-resource.html" class="menu-link">
						<div data-i18n="CRM">Check nguồn căn</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_stock_sold')}
				<li class="menu-item{if $mod eq 'report' && $sub eq 'default' && $act eq 'stock'} active{/if}">
					<a href="{$PCMS_URL}/report/stock.html" class="menu-link">
						<div data-i18n="CRM">Báo cáo bán hàng</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_crm')}
				<li class="menu-item{if $mod eq 'crm' && $sub eq 'default' && $act eq 'report'} active{/if}">
					<a href="{$PCMS_URL}/crm/report.html" class="menu-link">
						<div data-i18n="CRM">Khách hàng/CRM</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_work')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'work'} active{/if}">
					<a href="{$PCMS_URL}/report/work.html" class="menu-link">
						<div data-i18n="{$_oProject.title}">Nét đẹp lao động</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_MOC')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'report_moc'} active{/if}"> 
					<a href="{$PCMS_URL}/report/report_moc.html" class="menu-link">
						<div data-i18n="Báo cáo MOC">Báo cáo MOC</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_group')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'report_group'} active{/if}"> 
					<a href="{$PCMS_URL}/report/report_group.html" class="menu-link">
						<div data-i18n="Báo cáo MOC">Báo cáo nhóm/CLBNS</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermissMs()}
				<li class="menu-item{if $mod eq 'report' and $act eq 'worktime'} active{/if}">
					<a href="{$PCMS_URL}/report/worktime.html" class="menu-link">
						<div data-i18n="{$_oProject.title}">Vắng mặt</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkDEV()}
				<li class="menu-item{if $mod eq 'report' and $act eq 'order_package'} active{/if}">
					<a href="{$PCMS_URL}/report/report-order.html" class="menu-link">
						<div data-i18n="{$_oProject.title}">Nâng cấp gói MOC</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'report' and $act eq 'loyalty'} active{/if}">
					<a href="{$PCMS_URL}/loyalty.html" class="menu-link">
						<div data-i18n="{$_oProject.title}">Thống kê điểm loyalty</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_user')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'report_user'} active{/if}"> 
					<a href="{$clsISO->getLink('report_user')}" class="menu-link">
						<div data-i18n="Báo cáo MOC">Người dùng</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermissionGroup('DIRECTOR')}
				<li class="menu-item{if $mod eq 'home' && $sub eq 'report' && $act eq 'default'} active{/if}"> 
					<a href="/bao-cao.html" class="menu-link">
						<div data-i18n="Báo cáo MOC">Hiệu suất bán hàng</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'report' and $act eq 'top_10'} active{/if}"> 
					<a href="/report/top-10.html" class="menu-link">
						<div data-i18n="Báo cáo MOC">Top 10 sale</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkDEV()}
				<li class="menu-item{if $mod eq 'home' && $sub eq 'report' && $act eq 'report_login'} active{/if}"> 
					<a href="/bao-cao-dang-nhap.html" class="menu-link">
						<div data-i18n="Thống kê tần suất sử dụng">Tần suất truy cập</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkDEV()}
				<li class="menu-item d-none {if $mod eq 'report' and $act eq 'request_ptg'} active{/if}"> 
					<a href="/bao-cao-phan-hoi-yeu-cau-ptg.html" class="menu-link">
						<div data-i18n="Báo cáo yêu cầu PTG">Báo cáo yêu cầu PTG</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkDEV()}
				<li class="menu-item {if $mod eq 'report' and $sub eq 'project'} active{/if}"> 
					<a href="/tinh-trang-thong-tin-du-an.html" class="menu-link">
						<div data-i18n="Tình trạng thông tin dự án">Tình trạng thông tin DA</div>
					</a>
				</li>
				{/if}
			</ul>
		</li>
		{if $clsISO->checkPermission('manager_sop') || $clsISO->checkPermission('manager_leasing')}
			<li class="menu-item {if $mod eq 'sop' || $mod eq 'leasing'} active open{/if}">
				<a data-toggle="ripple" href="javascript:void(0);" class="menu-link menu-toggle">
					<i class="menu-icon tf-icons bx bx-door-open"></i>
					<div class="text-truncate" data-i18n="Báo cáo khác">Dịch vụ</div>
				</a>
				<ul class="menu-sub">
					{if $clsISO->checkPermission('manager_sop')}
						<li class="menu-item{if $mod eq 'sop' and $act eq 'default'} active{/if}">
							<a data-toggle="ripple" href="{$PCMS_URL}/chuyen-nhuong.html" class="menu-link">
								<div data-i18n="Chuyển nhượng">Chuyển nhượng</div>
							</a>
						</li>
					{/if}
					{if $clsISO->checkPermission('manager_leasing')}
						<li class="menu-item{if $mod eq 'leasing' and $act eq 'default'} active{/if}">
							<a data-toggle="ripple" href="{$PCMS_URL}/cho-thue.html" class="menu-link">
								<div data-i18n="Cho thuê">Cho thuê</div>
							</a>
						</li>
					{/if}
				</ul>
			</li>
		{/if}
        <!-- Misc -->
        <li class="menu-header small text-uppercase">
			<span class="menu-header-text">Misc</span>
		</li>
		{if $clsISO->checkPermission('log_search')}
		<li class="menu-item{if $mod eq 'log' and $act eq 'log_sale'} active{/if}">
            <a data-toggle="ripple" href="/logs-sale.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div class="text-truncate" data-i18n="Support">Logs tra cứu</div>
            </a>
        </li>
		{/if}
		{if $clsISO->checkPermission('log_search_all')}
		<li class="menu-item{if $mod eq 'log' and $act eq 'log_search'} active{/if}">
            <a data-toggle="ripple" href="/logs-search.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div class="text-truncate" data-i18n="Support">Logs tìm kiếm</div>
            </a>
        </li>
		{/if}
		{if $clsISO->checkPermission('log_login')}
		<li class="menu-item{if $mod eq 'log' and $act eq 'login_history'} active{/if}">
            <a data-toggle="ripple" href="/login-history.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-objects-vertical-bottom"></i>
                <div class="text-truncate" data-i18n="Support">Logs đăng nhập</div>
            </a>
        </li>
		{/if}
       <li class="menu-item">
            <a data-toggle="ripple" href="#support" class="menu-link">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div class="text-truncate" data-i18n="Support">Hỗ trợ</div>
            </a>
        </li>
    </ul>
</aside>