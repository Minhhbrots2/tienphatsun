<div class="py-2 bg-white position-sticky top-0 zindex-1">
	<a class="dropdown-item cursor-pointer" profile_id="{$profile_id}" 
		onClick="$Core.member.view_profile(this, event); return false;">
		<div class="d-flex">
			<div class="flex-shrink-0 me-3"><div class="avatar avatar-online">
				<img src="{$clsProfile->getAvatar($profile_id,$oneProfile,40,40)}" 
					onerror="this.src='{$URL_IMAGES}/avatars/1.png'" class="w-px-40 h-px-40 rounded-circle" />
			</div></div>
			<div class="flex-grow-1">
				<span class="fw-semibold d-block">{$oneProfile.full_name}</span>
				<div class="d-flex gap-1 fs-13 align-items-center">
					<span class="text-warning">{$oneProfile.role_name}</span>
					<span>-</span>
					<span data-bs-toggle="tooltip" title="Điểm Loyalty"{if $clsISO->checkPermissionGroup('DIRECTOR')} onClick="$Core.global.open_Lpoint(this, event)" staff_id="{$profile_id}"{/if} class="d-flex gap-1 cursor-pointer align-items-center">
						<img src="{$URL_IMAGES}/point.png" width="12px" /> 
						<strong class="text-warning fs-6">
							{if $clsISO->checkPermissionGroup('DIRECTOR')}
								Ultimate
							{else}
								{$oneProfile.total_Lpoint}
							{/if}
						</strong>
					</span>
				</div>
			</div>
		</div>
	</a>
	<div class="dropdown-divider"></div>
</div>
<ul class="overflow-y-auto list-unstyled" style="max-height:calc(100vh - 300px);">
	<li>
		<div class="dropdown-item d-flex justify-content-between align-items-center">
			<a href="javascript:void();" data-toggle="ripple" profile_id="{$profile_id}" 
				onClick="$Core.member.view_profile(this, event); return false;">
				<i class="bx bx-user me-1"></i>
				<span class="align-middle">Hồ sơ</span>
			</a>		
			<div class="d-flex gap-1 align-items center">							
				{if !empty($link_profile_sale)}
				<a class="btn btn-sm btn-success fs-11" href="{$link_profile_sale}" target="_blank" >
					<span class="align-middle">Xem</span>
				</a>
				{/if}
				<a class="btn btn-sm btn-primary fs-11" href="{$clsISO->getLink('edit_MOC')}" target="_blank" >
					<i class="bx bx-pencil fs-12"></i>
					<span class="align-middle">Edit MOC</span>
				</a>
			</div>
		</div>
	</li>	
	<li><a href="https://docs.google.com/document/d/1aSEgRZoSULwOaKuBtLIBVLgZtamT9AOYVDkfHmcvs24/edit?tab=t.0" target="_blank" class="dropdown-item text-main fw-bold" title="Chứng nhận đại lý">
		<i class="bx bx-check-shield"></i>
		<span class="align-middle">Chứng nhận đại lý</span>
	</a></li>
	{if $clsISO->checkSale() || $clsISO->_DEV()}
		<li >
			<a href="{$clsISO->getLink('dashboard_sale')}" class="dropdown-item cursor-pointer d-flex align-items-center  text-warning" data-toggle="ripple">
				<svg class="menu-icon tf-icons" xmlns="http://www.w3.org/2000/svg" width="20" height="20"  
				fill="currentColor" viewBox="0 0 24 24" >
				<path d="M20 11h-6c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1m-1 8h-4v-6h4zm-9-4H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1m-1 4H5v-2h4zM20 3h-6c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1m-1 4h-4V5h4zm-9-4H4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1m-1 8H5V5h4z"></path>
				</svg>
				<div class="text-truncate" data-i18n="Support">Tổng quan hiệu suất cá nhân</div>
			</a>
		</li>
	{/if}
	{if $clsISO->checkSale() || $clsISO->checkDEV()}
	<li><a data-toggle="ripple" class="dropdown-item cursor-pointer text-danger" 
		title="Đăng ký ngân sách MKT" onClick="$Core.marketing.open_regis(this, event)">
		<i class="bx bxl-meta"></i>
		<span class="align-middle">Đăng ký ngân sách MKT</span>
	</a></li>
	{/if}
	{if $clsISO->checkPermissionGroup('DIRECTOR')}
	<!-- <li><a data-toggle="ripple" class="dropdown-item js__add-report-today {if $is_send_report_today eq '1' || !$clsReport->check_time_send_report()}disabled{else}text-danger{/if}" onClick="$Core.global.report.open(this, event)" 
		href="javascript:void(0);">
		<i class="bx bx-bell-plus"></i>
		<span class="align-middle">Thêm báo cáo hàng ngày</span>
	</a></li>
	<li><a data-toggle="ripple" class="dropdown-item text-primary" href="/bao-cao.html" 
		title="Báo cáo hiệu suất bán hàng">
		<i class="bx bxs-report"></i>
		<span class="align-middle">Báo cáo hiệu quả hàng ngày</span>
	</a></li> -->
	{/if}
	{if $clsISO->checkPermissionGroup('DIRECTOR')}
	<li><a data-toggle="ripple" class="dropdown-item text-main" href="{$clsISO->getLink('report_ns_club')}" 
		title="Báo cáo lan tỏa CLB ngôi sao">
		<i class='bx bx-objects-horizontal-left'></i>
		<span class="align-middle">Báo cáo lan tỏa CLB ngôi sao</span>
	</a></li>
	<li><a data-toggle="ripple" onClick="$Core.global.crm.req_customer(this, event)" 
	href="javascript:void(0);" class="dropdown-item text-info" title="Yêu cầu cấp DATA">
		<i class='bx bx-user-voice'></i>
		<span class="align-middle">QL. Yêu cầu cấp khách hàng</span>
	</a></li>
	{/if}
	<li><a class="dropdown-item text-warning" href="{$clsISO->getLink('quote')}" >
		<i class='bx bxs-quote-alt-left me-1'></i>
		<span class="align-middle">Lời trích dẫn</span>
	</a></li>
	<li><a href="{$clsISO->getLink('training')}" class="dropdown-item" title="Trung tâm đào tạo">
		<i class='bx bx-play-circle'></i>
		<span class="align-middle">Trung tâm đào tạo</span>
	</a></li>
	<li><a href="{$clsISO->getLink('course')}" class="dropdown-item" title="Sự kiện+Đào tạo">
		<i class='bx bx-slideshow'></i>
		<span class="align-middle">Sự kiện+Đào tạo</span>
	</a></li>
	{if $clsISO->checkPermission("message_sale")}
	<li><a data-toggle="ripple" class="dropdown-item" href="javascript:void()" profile_id="{$profile_id}" 
	onClick="$Core.today.open_stock_today(this, event);" data-action="_open">
		<i class='bx bx-message-rounded-dots me-1'></i>
		<span class="align-middle">Căn hộ nổi bật mỗi ngày</span>
	</a></li>
	{/if}
	{if $clsISO->checkPermission('access_staff')}
	<li><a data-toggle="ripple" class="dropdown-item" href="{$clsISO->getLink('staff')}">
		<i class="bx bx-group"></i>
		<span class="align-middle">Quản lý nhân viên</span>
	</a></li>
	{/if}
	{if $clsISO->checkPermission('issue_access')}
	<li><a data-toggle="ripple" class="dropdown-item" href="{$clsISO->getLink('issue')}">
		<i class="bx bx-task"></i>
		<span class="align-middle">Quản lý công việc</span>
	</a></li>
	{/if}
	{*<li><a data-toggle="ripple" class="dropdown-item" href="{$PCMS_URL}/my-favourite/" class="nav-link" 
		title="Căn hộ yêu thích" onclick="$Core.wishlist.open(this,event)">
		<i class="bx bx-heart"></i>
		<span class="align-middle">Căn hộ yêu thích</span>
	</a></li>*}
	{if $clsISO->checkPermissionGroup('ACCOUNTANT')}
	<li><a data-toggle="ripple" class="dropdown-item text-main" href="javascript:void(0)" class="nav-link" title="Căn hộ yêu thích" onclick="$Core.global.fund.open_cash(this,event)">
		<i class='bx bx-wallet-alt'></i>
		<span class="align-middle">Cập nhật tiền tài khoản</span>
	</a></li>
	{/if}
	{if $clsISO->checkPermission('okrs_access')}
	<li><a data-toggle="ripple" class="dropdown-item" href="/okrs.html">
		<i class="bx bx-shape-circle"></i>
		<span class="align-middle text-truncate" data-i18n="OKRs">OKRs</span>
	</a></li>
	{/if}
	{if $clsISO->checkPermission('manage_stock_agency')}
	<li><a data-toggle="ripple" class="dropdown-item" href="{$clsISO->getLink('agency')}">
		<i class='bx bx-command'></i>
		<span class="align-middle text-truncate" data-i18n="OKRs">Quản lý đại lý</span>
	</a></li>
	{/if}	
	{if $clsISO->checkPermission('zalo_group_access')}
	<li><a data-toggle="ripple" class="dropdown-item " href="/zalo-group/manager.html">
		<i class='bx bx-code'></i>
		<span class="align-middle text-truncate" data-i18n="OKRs">Nhóm Zalo check nguồn</span>
	</a></li>
	{/if}
	<!-- {if $clsISO->checkPermission('access_quiz') && $clsISO->checkDEV()}
	<li><a class="dropdown-item" href="{$clsISO->getLink('quiz',1)}">
		<i class='bx bx-question-mark'></i>
		<span class="align-middle">Quản lý trắc nghiệm</span>
	</a></li>
	{/if}					
	{if $clsISO->checkDEV()}
	<li><a class="dropdown-item text-warning" href="{$clsISO->getLink('quiz',0)}me">
		<i class='bx bxs-pen' ></i>
		<span class="align-middle">Trắc nghiệm</span>
	</a></li>
	{/if} -->
	{if $clsISO->checkPermission('update_stock_sold')}
	<hr class="dropdown-divider" />
	<li><a data-toggle="ripple" href="javascript:void(0)" class="dropdown-item text-danger" 
	onClick="$Core.helper.open_stock_sold(this, event)">
		<i class='bx bx-shopping-bag'></i>
		<span class="align-middle">Cập nhật căn bán</span>
	</a></li>
	{/if}
	{if $clsISO->checkDEV()}
	<li><a data-toggle="ripple" href="javascript:void(0)" class="dropdown-item text-danger" 
		onClick="$Core.helper.stock_lock.open_stock_lock(this, event)">
		<i class='bx bx-shopping-bag'></i>
		<span class="align-middle">Khóa căn độc quyền</span>
	</a></li>
	{/if}
	{if $clsISO->checkPermission('attendance_access')}
	<li><a data-toggle="ripple" href="/attendance.html" class="dropdown-item">
		<i class='bx bx-cycling'></i>
		<span class="align-middle">Import Chấm công</span>
	</a></li>
	{/if}
	{if $clsISO->checkPermission('policy_stock')}
	<li><a data-toggle="ripple" href="javascript:void(0)" class="dropdown-item text-primary" 
	onClick="$Core.helper.open_policy(this, event)" tp="_update">
		<i class='bx bx-check-shield'></i>
		<span class="align-middle">Chính sách bán hàng</span>
	</a></li>
	{/if}
	{if $clsISO->checkPermission('policy_stock') || $profile_id eq $smarty.const._PROFILE_BTTH_ID}
	<li><a data-toggle="ripple" href="javascript:void(0)" class="dropdown-item" 
	onClick="$Core.global.stock.open_import(this, event)">
		<i class='bx bx-check-square'></i>
		<span class="align-middle">Cập nhật bảng hàng</span>
	</a></li>
	<li><a data-toggle="ripple" href="{$clsISO->getLink('crawl_highfloor')}" class="dropdown-item text-success" >
		<i class='bx bxs-file-doc'></i>
		<span class="align-middle">Cập nhật cao tầng excel</span>
	</a></li>
	<li><a data-toggle="ripple" href="{$clsISO->getLink('crawl_lowfloor')}" class="dropdown-item text-warning" >
		<i class='bx bxs-file-doc'></i>
		<span class="align-middle">Cập nhật thấp tầng excel</span>
	</a></li>
	{/if}	
	{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkDEV()}
	<li><a class="dropdown-item cursor-pointer" data-toggle="ripple" 
		onClick="$Core.helper.open_config_time(this, event)" title="Cài đặt thời gian">
		<i class='bx bx-time-five'></i>
		<span class="align-middle">Cài đặt thời gian</span>
	</a></li>
	{/if}
</ul>
<div class="py-2 bg-white position-sticky bottom-0 zindex-1">
	<div class="dropdown-divider"></div>
	<a class="dropdown-item" href="{$clsISO->getLink('logout')}">
		<i class="bx bx-power-off me-2"></i>
		<span class="align-middle">Đăng xuất</span>
	</a>
</div>