<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{$PCMS_URL}" class="app-brand-link pb-1 mx-auto d-flex flex-column justify-content-center">
            <span class="app-brand-logo demo">
				<img class="img-fluid" src="{$URL_IMAGES}/logo-icon.png" width="60" />
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
			<a href="{$PCMS_URL}" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-home-circle"></i>
				<div class="text-truncate" data-i18n="Analytics">Trang chủ</div>
			</a>
		</li>
		{if $clsISO->checkPermission("incentive_access") || $clsISO->_DEV()}
		<li class="menu-item{if $mod eq 'incentive'} active{/if}">
            <a href="{$clsISO->getLink('incentive')}" class="menu-link" data-toggle="ripple">
                <i class="menu-icon tf-icons bx bxs-medal"></i>
                <div class="text-truncate" data-i18n="Support">Chương trình thi đua</div>
            </a>
        </li>
		{/if}	
		<li class="menu-item{if $mod eq 'billing' && $act eq 'mileston'} active{/if}">
            <a href="/m-{$smarty.now|date_format:'%Y'}/" class="menu-link text-warning fw-bold"  data-toggle="ripple" >
				<img class="menu-icon tf-icons " src="{$URL_IMAGES}/top-1.png">
                <div class="text-truncate" data-i18n="Support">Milestone {$smarty.now|date_format:"%Y"}</div>
            </a>
        </li>
		<li class="menu-item{if $mod eq 'home' && $act eq 'share' && $share_type eq 'share'} active{/if}">
			<a href="{$PCMS_URL}/net-dep-lao-dong.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-run"></i>
				<div class="text-truncate" data-i18n="Hoạt động tiếp khách">Hoạt động tiếp khách</div>
			</a>
		</li>	
		<li class="menu-item{if $mod eq 'home' && $sub eq 'course' && $act eq 'default'} active{/if}">
			<a href="{$PCMS_URL}/lich-dao-tao.html" class="menu-link"data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-slideshow"></i>
				<div class="text-truncate" data-i18n="Sự kiện+Đào tạo">Sự kiện+Đào tạo</div>
			</a>
		</li>
		{if $clsISO->_DEV()}
		<li class="menu-item{if $mod eq 'incentive'} active{/if}">
            <a href="javascript:void(0)" class="menu-link text-main fw-bold"  data-toggle="ripple" onClick="$Core.market_data.open(this,event)">
                <i class="menu-icon tf-icons bx bxs-cart"></i>
                <div class="text-truncate" data-i18n="Support">Chợ data</div>
            </a>
        </li>
		{/if}			
		{if $deviceType eq 'phone'}
		<li class="menu-item{if $mod eq 'home' and $act eq 'ultilities'} active{/if}">
            <a href="/tien-ich/" class="menu-link" data-toggle="ripple">
                <i class="menu-icon tf-icons bx bx-sun"></i>
                <div class="text-truncate" data-i18n="Support">Trung tâm tiện ích</div>
            </a>
        </li>
		{/if}
		<li class="menu-item{if $mod eq 'training' && $act eq 'default'} active{/if}">
			<a href="{$clsISO->getLink('training')}" class="menu-link" data-toggle="ripple">
				<i class='menu-icon bx bx-play-circle'></i>
				<div class="text-truncate" data-i18n="Trung tâm đào tạo">Trung tâm đào tạo</div>
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
				<div class="text-truncate" data-i18n="Basic">Bản tin FG</div>
			</a>
		</li>
		{if $clsISO->checkPermissionGroup('DIRECTOR')}
		<li class="menu-item{if $mod eq 'home' && $act eq 'share' && $share_type eq 'secret'} active{/if}">
			<a href="/thong-tin-mat.html" class="menu-link" data-toggle="ripple">
				 <i class="menu-icon tf-icons bx bx-check-shield"></i>
				<div class="text-truncate" data-i18n="Thông tin mật">Thông tin mật</div>
			</a>
		</li>
		{/if}
        {if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkDEV()}
		<li class="menu-item{if $mod eq 'analytics'} active{/if}">
			<a href="/index.php?mod=analytics" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
				<div class="text-truncate" data-i18n="Dashboard BOD">Dashboard MF</div>
				<span class="badge badge-demo bg-label-danger ms-auto">BOD</span>
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
		<li class="menu-item{if $mod eq 'document'} active{/if}">
			<a href="/van-ban-he-thong.html" class="menu-link" data-toggle="ripple">
				<i class='menu-icon tf-icons bx bx-file'></i>
				<div class="text-truncate" data-i18n="Basic">Văn bản hệ thống</div>
			</a>
		</li>
		{if $clsISO->checkPermission("data_central")}
			<li class="menu-item{if $mod eq 'data_central'} open active{/if}">
				<a data-toggle="ripple" href="javascript:void(0);" class="menu-link menu-toggle">
					<i class='menu-icon tf-icons bx bx-data'></i>
					<div class="text-truncate" data-i18n="Basic">Data Cư dân VHOP</div>
				</a>
				<ul class="menu-sub">
					<li class="menu-item {if $mod eq 'data_central' && $act eq 'default'} active{/if}">
						<a href="{$clsISO->getLink('data_central')}" class="menu-link mx-1" data-toggle="ripple">
							<div class="text-truncate" data-i18n="Basic">Danh sách data</div>
						</a>
					</li>
					<li class="menu-item {if $mod eq 'data_central' && $act eq 'campaign'} active{/if}">
						<a href="{$clsISO->getLink('campaign_data')}" class="menu-link mx-1" data-toggle="ripple">
							<div class="text-truncate" data-i18n="Basic">Chiến dịch data</div>
						</a>
					</li>
				</ul>			
			</li>
		{/if}
		{if $clsISO->checkDEV()}
			<li class="menu-item{if $mod eq 'training' && $act eq inspire} active{/if}">
				<a href="{$clsISO->getLink('inspire')}" class="menu-link" data-toggle="ripple">
					<i class='menu-icon tf-icons bx bxl-upwork'></i>
					<div class="text-truncate" data-i18n="Basic">Truyền cảm hứng</div>
				</a>
			</li>
		{/if}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Bán hàng</span>
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
		{if $clsISO->checkPermission('sale_market_crm')}
		<li class="menu-item{if $mod eq 'crm' && $act eq 'sale_marketplace_crm'} active{/if}">
			<a data-toggle="ripple" href="/crm/sale-thi-truong.html" class="menu-link">
				<i class="menu-icon tf-icons bx bx-user-pin"></i>
				<div class="text-truncate" data-i18n="CRM">Sale thị trường</div>
			</a>
		</li>
		{/if}
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
		<li class="menu-item {if $mod eq 'stock'} active{/if}">
			<a data-toggle="ripple" href="{$clsISO->getLink('exclusive')}" class="menu-link">
				<i class="menu-icon tf-icons bx bxs-heart text-main"></i>
				<div class="text-truncate" data-i18n="Bảng hàng dự án">Quỹ độc quyền </div>
				<span class="badge badge-demo bg-label-warning ms-auto">FH</span>
			</a>
		</li>
		<li class="menu-item {if $mod eq 'home' && $sub eq 'project' && $act eq 'stock_list'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/bang-hang/" class="menu-link text-warning">
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
		<li class="menu-item{if $mod eq 'map' && $sub eq 'default'} active{/if}">
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
		</li>
		<li class="menu-item {if $mod eq 'report' && $sub eq 'project' && $act eq 'report_stock_highfloor'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/tong-quan-gia-cao-tang.html" class="menu-link text-main fw-bold">
				<i class="menu-icon tf-icons bx bx-table"></i>
				<div class="text-truncate" data-i18n="Bảng giá /m2">Bảng giá /m<sup>2</sup></div>
			</a>
		</li>
		<li class="menu-item {if $mod eq 'report' && $sub eq 'project' && $act eq 'report_stock_lowfloor'} active{/if}">
			<a data-toggle="ripple" href="{$PCMS_URL}/tong-quan-gia-thap-tang.html" class="menu-link text-info fw-bold">
				<i class="menu-icon tf-icons bx bx-table"></i>
				<div class="text-truncate" data-i18n="Bảng hàng dự án">T.Quan giá thấp tầng</div>
			</a>
		</li>
		{if $clsISO->checkDEV()}
		<li class="menu-item d-none {if $mod eq 'client'} active{/if}">
			<a href="{$PCMS_URL}/xac-nhan-hoa-hong.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-bitcoin"></i>
				<div class="text-truncate" data-i18n="Xác nhận hoa hồng">Xác nhận hoa hồng</div>
				<span class="badge badge-demo bg-label-danger ms-auto">5</span>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'client'} active{/if}">
			<a href="{$PCMS_URL}/quan-ly-khach-hang.html" class="js-ripple menu-link">
				<i class='menu-icon tf-icons bx bx-user'></i>
				<div class="text-truncate" data-i18n="Basic">Quản lý khách hàng</div>
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
		{if $clsISO->checkPermission('access_billing')}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Giao dịch chốt</span>
		</li>
		<li class="menu-item{if $mod eq 'home' && $act eq 'billing'} active{/if}">
			<a href="{$PCMS_URL}/giao-dich.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-terminal"></i>
				<div class="text-truncate" data-i18n="Basic">Giao dịch chốt</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('calendar_billing')}
		<li class="menu-item{if $mod eq 'home' && $sub eq 'calendar'} active{/if}">
			<a href="{$PCMS_URL}/lich-ky.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-calendar"></i>
				<div class="text-truncate" data-i18n="Basic">Lịch ký HĐMB</div>
			</a>
		</li>
		{/if}
		<li class="menu-item{if $mod == 'booking' && $act == 'my_booking'} active{/if}">
			<a href="{$PCMS_URL}/my-booking.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-shopping-bag"></i>
				<div data-i18n="Telesale">My Booking</div>
				<span class="badge badge-demo bg-label-danger ms-auto">FH</span>
			</a>
		</li>
		{if $clsISO->checkPermission('report_billing')}
		<li class="menu-item{if $mod eq 'report' && $act eq 'billing' && $report_type eq 'common'} active{/if}">
			<a href="{$PCMS_URL}/billing/report.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-doughnut-chart"></i>
				<div class="text-truncate" data-i18n="Basic">Báo cáo bán hàng</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'report' && $act eq 'billing' && $report_type eq 'mwf'} active{/if}">
			<a href="{$PCMS_URL}/billing/report/mwf.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-pie-chart-alt-2"></i>
				<div class="text-truncate" data-i18n="Basic">Báo cáo quỹ F1</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('okrs_access') && 1==2}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">OKRs</span>
		</li>
		<li class="menu-item{if $mod eq 'okrs' and $act eq 'default'} active{/if}">
			<a href="/okrs.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-shape-circle"></i>
				<div class="text-truncate" data-i18n="OKRs">OKRs</div>
			</a>
		</li>
		<li class="menu-item">
			<a href="/okrs/check-ins.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-shape-circle"></i>
				<div class="text-truncate" data-i18n="Check-ins">Check-ins</div>
			</a>
		</li>
		{/if}	
		{if $clsISO->checkPermission('fund_access') || $clsISO->checkPermission('salary_access')}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Kế toán</span>
		</li>
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ACCOUNTANT')}
		<li class="menu-item{if $mod eq 'acc' and $sub eq 'report'} active{/if}">
			<a href="{$clsISO->getLink('finance')}" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-money-withdraw"></i>
				<div class="text-truncate" data-i18n="Tài chính">Tài chính & Tài sản</div>
				<span class="badge badge-demo bg-label-danger ms-auto">FH</span>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'acc' and $sub eq 'report'} active{/if}">
			<a href="/tai-chinh.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-money-withdraw"></i>
				<div class="text-truncate" data-i18n="Tài chính">Tài chính</div>
				<span class="badge badge-demo bg-label-warning ms-auto">NEW</span>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermission('salary_access')}
		<li class="menu-item{if $mod eq 'tool' and $act eq 'salary'} active{/if}">
			<a href="{$clsISO->getLink('salary')}" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-money-withdraw"></i>
				<div class="text-truncate" data-i18n="Tài chính">Lương lãnh đạo KD</div>
				<span class="badge badge-demo bg-label-danger ms-auto">FH</span>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('fund_access')}
		<li class="menu-item{if $mod eq 'fund' && ($act eq 'ops_cost' || $act eq 'opscost_dashboard')} active{/if}">
			<a href="{$clsISO->getLink('operation_fee')}" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-money"></i>
				<div class="text-truncate" data-i18n="Chi phí vận hành">Chi phí vận hành</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'fund' && ($sub eq 'cash_book')} active{/if}">
			<a href="{$clsISO->getLink('cash_book')}" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-money"></i>
				<div class="text-truncate" data-i18n="Chi phí vận hành">Quỹ tiền mặt</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'fund' && $sub eq 'default' && $act eq 'default'} active{/if}">
			<a href="{$PCMS_URL}/fund.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-wallet"></i>
				<div class="text-truncate" data-i18n="Thu chi nội bộ">Thu chi nội bộ</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'acc' && $sub eq 'vat'} active{/if}">
			<a href="{$PCMS_URL}/vat.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-shape-circle"></i>
				<div class="text-truncate" data-i18n="VAT đã xuất">VAT đã xuất</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'acc' && $sub eq 'commission'} active{/if}">
			<a href="{$PCMS_URL}/commission.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-shape-circle"></i>
				<div class="text-truncate" data-i18n="Hoa hồng bán mới">Hoa hồng môi giới</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'fund' && $act eq 'report'} active{/if}">
			<a href="{$PCMS_URL}/fund/report.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
				<div class="text-truncate" data-i18n="Thu chi nội bộ">Báo cáo thu chi</div>
			</a>
		</li>
		{/if}
		{/if}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Hoạt động</span>
		</li>		
		{*<li class="menu-item{if $mod eq 'home' & $sub eq 'course' & $act eq 'event'} active{/if}">
			<a href="{$clsISO->getLink('event')}" title="Sự kiện" class="menu-link" data-toggle="ripple">
				 <i class="menu-icon tf-icons bx bx-calendar-alt"></i>
				<div class="text-truncate" data-i18n="Sự kiện">Sự kiện</div>
			</a>
		</li>*}		
		<li class="menu-item{if $mod eq 'tool' and $act eq 'calendar'} active{/if}">
			<a href="{$PCMS_URL}/lich-phong-hop.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-camera-home"></i>
				<div class="text-truncate" data-i18n="Đăng ký phòng họp">Đăng ký phòng họp</div>
			</a>
		</li>
		{*<li class="menu-item d-none">
			<a href="{$PCMS_URL}/worktime-staff.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-run"></i>
				<div class="text-truncate" data-i18n="Basic">Nghỉ phép(NV)</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'home' & $sub eq 'worktime'} active{/if}">
			<a href="{$PCMS_URL}/worktime.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-run"></i>
				<div class="text-truncate" data-i18n="Basic">Nghỉ phép</div>
			</a>
		</li>*}
		{if $clsISO->checkPermissionGroup('ADMIN_PROJECT')}
		<li class="menu-item{if $mod eq 'home' && $act eq 'today'} active{/if}">
			<a href="/can-ho-noi-bat.html" class="menu-link" data-toggle="ripple">
				<i class='menu-icon bx bx-donate-heart'></i>
				<div class="text-truncate" data-i18n="Basic">Căn hộ nổi bật</div>
			</a>
		</li>
		{/if}
		{*{if $clsISO->checkDEV()}
			<li class="menu-item{if $mod eq 'home' && $act eq 'gratitude'} active{/if}">
				<a href="/tri-an.html" class="menu-link" data-toggle="ripple">
					<i class='menu-icon bx bx-donate-heart'></i>
					<div class="text-truncate" data-i18n="Basic">Tri ân</div>
				</a>
			</li>
		{/if}*}
		{*<li class="menu-item{if $mod eq 'home' && $sub eq 'course' && $act eq 'slide'} active{/if}">
			<a href="{$PCMS_URL}/hoc-tap.html" title="Kho tài liệu" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bxs-graduation"></i>
				<div class="text-truncate" data-i18n="Học tập + Đào tạo">Kho tài liệu</div>
			</a>
		</li>*}
		{if $clsISO->checkPermissionGroup('BO') && $clsISO->checkPermissionGroup('DIRECTOR') && 1==2}
		<li class="menu-item{if $mod eq 'overtime' && $act eq 'default'} active{/if}">
			<a href="{$PCMS_URL}/overtime.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-timer"></i>
				<div class="text-truncate" data-i18n="Làm thêm giờ">Đăng ký tăng ca</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('issue_access')}
		<li class="menu-item{if $mod eq 'issue'} active{/if}">
			<a href="/issue.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-task"></i>
				<div class="text-truncate" data-i18n="Quản lý công việc">Quản lý công việc</div>
			</a>
		</li>
		{/if}
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">Báo cáo</span>
		</li>
		{if $clsISO->checkPermissionGroup('DIRECTOR')}
		<li class="menu-item{if $mod eq 'kpi' && $sub eq 'default' && $act eq 'default'} active{/if}">
			<a href="{$PCMS_URL}/kpi.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-chart"></i>
				<div data-i18n="{$_oProject.title}">Doanh số tháng</div>
			</a>
		</li>
		<li class="menu-item{if $mod eq 'report' && $sub eq 'project' && $act eq 'report_dq'} active{/if}">
			<a href="{$PCMS_URL}/bao-cao-doc-quyen.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-menu"></i>
				<div data-i18n="{$_oProject.title}">Thống kê quỹ độc quyền</div>
			</a>
		</li>
		{/if}
		{if $clsISO->checkPermission('report_sale_staff')}
		<li class="menu-item{if $mod eq 'report' && $sub eq 'default' && $act eq 'sale'} active{/if}">
			<a href="{$PCMS_URL}/report/sale.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bx-pie-chart"></i>
				<div data-i18n="Doanh số nhân viên">Doanh số nhân viên</div>
			</a>
		</li>
		{/if}
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
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkDEV()}
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
		{/if}
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
		<li class="menu-item{if $mod eq 'marketing'} open{/if}">
			<a href="javascript:void(0);" class="menu-link menu-toggle" data-toggle="ripple">
				 <i class="menu-icon tf-icons bx bx-line-chart"></i>
				<div class="text-truncate" data-i18n="Báo cáo khác">Báo cáo khác</div>
			</a>
			<ul class="menu-sub">
				{if $clsISO->checkPermission('report_sale')}
				<li class="menu-item">
					<a href="{$PCMS_URL}/report/sales.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="{$_oProject.title}">Báo cáo bán hàng</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermissionGroup('DIRECTOR') }
				<li class="menu-item{if $mod eq 'report' && $act eq 'sales_agent'} active{/if}">
					<a href="{$PCMS_URL}/report/sales_agent.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="{$_oProject.title}">Thống kê đại lý bán</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'report' && $act eq 'report_agent'} active{/if}">
					<a href="{$PCMS_URL}/report/report_agent.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="{$_oProject.title}">Báo cáo đại lý</div>
					</a>
				</li>
				<li class="menu-item">
					<a href="{$PCMS_URL}/bao-cao-doanh-so.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="{$_oProject.title}">Báo cáo doanh số</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_stock_resource')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'stock_resource'} active{/if}">
					<a href="{$PCMS_URL}/report/stock-resource.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="CRM">Check nguồn căn</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('access_staff_all')}
				<li class="menu-item{if $mod eq 'member' && $act eq 'report'} active{/if}">
					<a href="{$PCMS_URL}//member/report.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Nhân sự">Nhân sự</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_crm')}
				<li class="menu-item{if $mod eq 'crm' && $sub eq 'default' && $act eq 'report'} active{/if}">
					<a href="{$PCMS_URL}/crm/report.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="CRM">Khách hàng/CRM</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_work') || $clsISO->_DEV()}
				<li class="menu-item{if $mod eq 'report' and $act eq 'work'} active{/if}">
					<a href="{$PCMS_URL}/report/work.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="{$_oProject.title}">Hoạt động tiếp khách</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('report_group')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'report_group'} active{/if}"> 
					<a href="{$PCMS_URL}/report/report_group.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Báo cáo CLB NS">Câu lạc bộ NS</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermissMs()}
				<li class="menu-item{if $mod eq 'report' and $act eq 'worktime'} active{/if}">
					<a href="{$PCMS_URL}/report/worktime.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="{$_oProject.title}">Vắng mặt</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermissionGroup('DIRECTOR')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'loyalty'} active{/if}">
					<a href="{$PCMS_URL}/loyalty.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="{$_oProject.title}">Thống kê điểm loyalty</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'home' && $sub eq 'report' && $act eq 'default'} active{/if}"> 
					<a href="{$PCMS_URL}/bao-cao.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Báo cáo MOC">Hiệu suất bán hàng</div>
					</a>
				</li>{/if}
				{if $clsISO->checkPermission('top_ten_sales')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'top_10'} active{/if}"> 
					<a href="{$PCMS_URL}/report/top-10.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Báo cáo MOC">Top 10 sale</div>
					</a>
				</li>{/if}
				{if $clsISO->checkPermission('sales_has_trans')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'sales_has_trans'} active{/if}"> 
					<a href="{$PCMS_URL}/report/sales-has-trans.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Sale đã có GD">Sale đã có GD</div>
					</a>
				</li>{/if}
				{if $clsISO->checkPermission('sales_not_trans')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'top_sales'} active{/if}"> 
					<a href="{$PCMS_URL}/report/sale-no-trans.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Sale chưa có GD">Sale chưa có GD</div>
					</a>
				</li>{/if}
				{if $clsISO->checkPermission('report_sale')}
				<li class="menu-item{if $mod eq 'home' && $sub eq 'report' && $act eq 'report_login'} active{/if}"> 
					<a href="{$PCMS_URL}/bao-cao-dang-nhap.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Thống kê tần suất sử dụng">Tần suất truy cập</div>
					</a>
				</li>
				<li class="menu-item d-none {if $mod eq 'report' and $act eq 'request_ptg'} active{/if}"> 
					<a href="{$PCMS_URL}/bao-cao-phan-hoi-yeu-cau-ptg.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Báo cáo yêu cầu PTG">Yêu cầu PTG</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')}
				<li class="menu-item {if $mod eq 'report' and $sub eq 'project'} active{/if}"> 
					<a href="{$PCMS_URL}/tinh-trang-thong-tin-du-an.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Tình trạng thông tin dự án">Tình trạng thông tin DA</div>
					</a>
				</li>
				{/if}
				{if $clsISO->checkPermission('marketing_access') || $clsISO->checkDEV() }
				<li class="menu-item {if $mod eq 'marketing'} active{/if}"> 
					<a href="{$PCMS_URL}/marketing.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Chi phí Marketing">Chi phí Marketing</div>
					</a>
				</li>
				{/if}
			</ul>
		</li>
		{/if}
		{assign var=listBlockPage value=$clsISO->getListBlockPage()}
		{if !empty($listBlockPage)}
			<li class="menu-item{if $mod eq 'marketing'} open{/if}">
				<a href="javascript:void(0);" class="menu-link menu-toggle text-main fw-bold" data-toggle="ripple">
					 <i class="menu-icon tf-icons bx bxs-up-arrow"></i>
					<div class="text-truncate" data-i18n="Báo cáo khác">BXH {$smarty.now|date_format:"%Y"}</div>
				</a>
				<ul class="menu-sub">
					{foreach from=$listBlockPage item=_oItem key=key name=i}
						<li class="menu-item {if $mod eq 'home' && $sub eq 'report' && $act eq 'top' && $_oItem.slug eq $slug} active{/if}"> 
							<a href="{$PCMS_URL}/bang-xep-hang-{$_oItem.slug}.html" class="menu-link" data-toggle="ripple">
								<div data-i18n="{$_oItem.title_page}">{$_oItem.title_menu}</div>
							</a>
						</li>
					{/foreach}
				</ul>
			</li>
		{/if}
		{if $clsISO->checkPermissionGroup('DIRECTOR')}
		<li class="menu-item">
			<a href="javascript:void(0);" class="menu-link menu-toggle" data-toggle="ripple">
				 <i class="menu-icon tf-icons bx bx-line-chart"></i>
				<div class="text-truncate" data-i18n="Báo cáo khác">Báo cáo MOC</div>
			</a>
			<ul class="menu-sub">
				<li class="menu-item{if $mod eq 'report' and $act eq 'report_moc'} active{/if}"> 
					<a href="{$PCMS_URL}/report/report_moc.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="Báo cáo MOC">Báo cáo MOC</div>
					</a>
				</li>
				<li class="menu-item{if $mod eq 'report' and $act eq 'order_package'} active{/if}">
					<a href="{$PCMS_URL}/report/report-order.html" class="menu-link" data-toggle="ripple">
						<div data-i18n="{$_oProject.title}">Nâng cấp gói MOC</div>
					</a>
				</li>
				{if $clsISO->checkPermission('report_user')}
				<li class="menu-item{if $mod eq 'report' and $act eq 'report_user'} active{/if}"> 
					<a href="{$clsISO->getLink('report_user')}" class="menu-link" data-toggle="ripple">
						<div data-i18n="Báo cáo MOC">Người dùng</div>
					</a>
				</li>
				{/if}
			</ul>
		</li>
		{/if}		
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkDEV()}
			<li class="menu-header small text-uppercase">
				<span class="menu-header-text">Công cụ Hệ Thống</span>
			</li>
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
        <!-- Misc -->
        <li class="menu-header small text-uppercase">
			<span class="menu-header-text">Misc</span>
		</li>
		{if $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('PROJECT_DIRECTOR')}
		<li class="menu-item {if $mod eq 'report' and $sub eq 'default' && $act eq 'report_price_stock'} active{/if}"> 
			<a href="{$PCMS_URL}/log-thay-doi-gia.html" class="menu-link" data-toggle="ripple">
				<div data-i18n="Tình trạng thông tin dự án">Lịch sử thay đổi giá</div>
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
		{if $clsISO->checkPermission('log_search_all')}
		<li class="menu-item{if $mod eq 'log' and $act eq 'log_search'} active{/if}">
            <a href="{$PCMS_URL}/logs-search.html" class="menu-link" data-toggle="ripple">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div class="text-truncate" data-i18n="Support">Logs tìm kiếm</div>
            </a>
        </li>
		{/if}
		{if $clsISO->checkPermission('log_login')}
		<li class="menu-item{if $mod eq 'log' and $act eq 'login_history'} active{/if}">
            <a href="{$PCMS_URL}/login-history.html" class="menu-link" data-toggle="ripple">
                <i class="menu-icon tf-icons bx bx-objects-vertical-bottom"></i>
                <div class="text-truncate" data-i18n="Support">Logs đăng nhập</div>
            </a>
        </li>
		{/if}
        <li class="menu-item">
			<a href="/co-cau-to-chuc.html" class="menu-link" data-toggle="ripple">
				<i class="menu-icon tf-icons bx bxs-component"></i>
				<div class="text-truncate" data-i18n="Support">Cơ cấu tổ chức</div>
			</a>
		</li>
       <li class="menu-item">
            <a href="#support" class="menu-link" data-toggle="ripple">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div class="text-truncate" data-i18n="Support">Hỗ trợ</div>
            </a>
        </li>
		{if $clsISO->checkPermission("add_page_helper")}
		<li class="menu-item">
            <a href="javascript:void(0)" class="menu-link" data-toggle="ripple" onClick="$Core.helper.edit_helper(this,event)" action="_OPEN" mod_page="{$mod}" sub_page="{$sub}" act_page="{$act}" title="Chỉnh sửa hướng dẫn">
                <i class="menu-icon tf-icons bx bx-edit"></i>
                <div class="text-truncate" data-i18n="Support">Hướng dẫn</div>
            </a>
        </li>
		{/if}
    </ul>
</aside>