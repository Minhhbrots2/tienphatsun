<div class="position-fixed menu_plus d-flex flex-column align-items-end">
	{if $clsISO->checkPermissionGroup('DIRECTOR') eq '1'}
		<div class="sub_menu_plus">
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onClick="$Core.global.crm.add_customer(this,event)" customer_id="0" openfrom="_crm" data-toggle="ripple" route="/customer/create/0" class="" title="Thêm mới khách hàng">
				<span class="text-nowrap me-1 text-white bg-success fs-11 px-2 py-1 rounded-1 text_circle_sub">Khách hàng</span>
				<i  class="bx bx-user-plus bg-success text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.issue.open_issue(this, event)" issue_id="0" parent_id="0" title="Thêm công việc">
				<span class="text-nowrap me-1 text-white bg-danger fs-11 px-2 py-1 rounded-1 text_circle_sub">Công việc</span>
				<i  class="bx bx-task bg-danger text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="open_news(this, event)" news_id="0" action="_add" title="Thêm bản tin">
				<span class="text-nowrap me-1 text-white bg-warning fs-11 px-2 py-1 rounded-1 text_circle_sub">Bản tin</span>
				<i  class="bx bx-news bg-warning text-white circle_icon"></i>
			</a>
		</div>
	{elseif $clsISO->checkPermissionGroup('SALE_DIRECTOR') eq '1' || $profile_id eq "289"}
		<div class="sub_menu_plus">
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onClick="$Core.global.crm.add_customer(this,event)" customer_id="0" openfrom="_crm" data-toggle="ripple" route="/customer/create/0" title="Thêm mới khách hàng">
				<span class="text-nowrap me-1 text-white bg-success fs-11 px-2 py-1 rounded-1 text_circle_sub">Khách hàng</span>
				<i  class="bx bx-user-plus bg-success text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.share.open(this, event)" holderg="share" share_id="0" action="_add" title="Thêm hoạt động tiếp khách">
				<span class="text-nowrap me-1 text-white bg-warning fs-11 px-2 py-1 rounded-1 text_circle_sub">Tiếp khách</span>
				<i  class="bx bx-image bg-warning text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.issue.open_issue(this, event)" issue_id="0" parent_id="0" title="Thêm công việc">
				<span class="text-nowrap me-1 text-white bg-danger fs-11 px-2 py-1 rounded-1 text_circle_sub">Công việc</span>
				<i  class="bx bx-task bg-danger text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center {if $is_send_report_today eq '1' || !$clsReport->check_time_send_report()}disabled{/if}" href="javascript:void(0)" onClick="$Core.global.report.open(this, event)" report_id="0" title="Báo cáo hiệu quả hàng ngày">
				<span class="text-nowrap me-1 text-white bg-primary fs-11 px-2 py-1 rounded-1 text_circle_sub">Báo cáo</span>
				<i  class="bx bx-bell-plus bg-primary text-white circle_icon"></i>
			</a>
		</div>	
	{elseif $clsISO->checkPermissionGroup('ADMIN_PROJECT') eq '1'}		
		<div class="sub_menu_plus">
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.billing.open_billing(this, event)" tp="quick" billing_id="0" title="Thêm giao dịch chốt">
				<span class="text-nowrap me-1 text-white bg-success fs-11 px-2 py-1 rounded-1 text_circle_sub">Giao dịch chốt</span>
				<i  class="bx bx-cart-add bg-success text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.issue.open_issue(this, event)" issue_id="0" parent_id="0" title="Thêm công việc">
				<span class="text-nowrap me-1 text-white bg-danger fs-11 px-2 py-1 rounded-1 text_circle_sub">Công việc</span>
				<i  class="bx bx-task bg-danger text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.overtime.open(this, event)" overtime_id="0" openFrom="menu" title="Đăng ký tăng ca">
				<span class="text-nowrap me-1 text-white bg-main fs-11 px-2 py-1 rounded-1 text_circle_sub">Đăng ký tăng ca</span>
				<i  class="bx bx-timer bg-main text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="open_news(this, event)" news_id="0" action="_add" title="Thêm bản tin">
				<span class="text-nowrap me-1 text-white bg-warning fs-11 px-2 py-1 rounded-1 text_circle_sub">Bản tin</span>
				<i  class="bx bx-news bg-warning text-white circle_icon"></i>
			</a>
		</div>	
	{elseif $clsISO->checkPermissionGroup('ACCOUNTANT') eq '1'}
		<div class="sub_menu_plus">
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)"  onclick="$Core.global.fund.open(this, event)" fund_id="0" gr="THUCTHU" title="Thêm phiếu thu">
				<span class="text-nowrap me-1 text-white bg-success fs-11 px-2 py-1 rounded-1 text_circle_sub">Phiếu thu</span>
				<i  class="bx bx-money bg-success text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)"  onclick="$Core.global.fund.open(this, event)" fund_id="0" gr="THUCCHI" title="Thêm phiếu chi">
				<span class="text-nowrap me-1 text-white bg-warning fs-11 px-2 py-1 rounded-1 text_circle_sub">Phiếu chi</span>
				<i  class="bx bx-money bg-warning text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.issue.open_issue(this, event)" issue_id="0" parent_id="0" title="Thêm công việc">
				<span class="text-nowrap me-1 text-white bg-danger fs-11 px-2 py-1 rounded-1 text_circle_sub">Công việc</span>
				<i  class="bx bx-task bg-danger text-white circle_icon"></i>
			</a>		
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.overtime.open(this, event)" overtime_id="0" openFrom="menu" title="Đăng ký tăng ca">
				<span class="text-nowrap me-1 text-white bg-primary fs-11 px-2 py-1 rounded-1 text_circle_sub">Tăng ca</span>
				<i  class="bx bx-timer bg-primary text-white circle_icon"></i>
			</a>
		</div>	
	{else}
		<div class="sub_menu_plus">
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onClick="$Core.global.crm.add_customer(this,event)" customer_id="0" openfrom="_crm" data-toggle="ripple" route="/customer/create/0" title="Thêm mới khách hàng">
				<span class="text-nowrap me-1 text-white bg-success fs-11 px-2 py-1 rounded-1 text_circle_sub">Khách hàng</span>
				<i  class="bx bx-user-plus bg-success text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.share.open(this, event)" holderg="share" share_id="0" action="_add" title="Thêm hoạt động tiếp khách">
				<span class="text-nowrap me-1 text-white bg-warning fs-11 px-2 py-1 rounded-1 text_circle_sub">Tiếp khách</span>
				<i  class="bx bx-image bg-warning text-white circle_icon"></i>
			</a>
			<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.issue.open_issue(this, event)" issue_id="0" parent_id="0" title="Thêm công việc">
				<span class="text-nowrap me-1 text-white bg-danger fs-11 px-2 py-1 rounded-1 text_circle_sub">Công việc</span>
				<i  class="bx bx-task bg-danger text-white circle_icon"></i>
			</a>
			{if $clsISO->checkSale()}
			<a class="circle_sub d-flex align-items-center {if $is_send_report_today eq '1' || !$clsReport->check_time_send_report()}disabled{/if}" href="javascript:void(0)" onClick="$Core.global.report.open(this, event)" report_id="0" title="Báo cáo hiệu quả hàng ngày">
				<span class="text-nowrap me-1 text-white bg-primary fs-11 px-2 py-1 rounded-1 text_circle_sub">Báo cáo</span>
				<i  class="bx bx-bell-plus bg-primary text-white circle_icon"></i>
			</a>
			{else}
				<a class="circle_sub d-flex align-items-center" href="javascript:void(0)" onclick="$Core.global.overtime.open(this, event)" overtime_id="0" openFrom="menu" title="Đăng ký tăng ca">
					<span class="text-nowrap me-1 text-white bg-primary fs-11 px-2 py-1 rounded-1 text_circle_sub">Tăng ca</span>
					<i  class="bx bx-timer bg-primary text-white circle_icon"></i>
				</a>
			{/if}
		</div>	
	{/if}
	
	<div class="circle cursor-pointer" onClick="$Core.menu.open_menu(this,event)">
		<i class="icon1 bx bx-plus circle_icon bg-main text-white fs-2"></i>
	</div>
</div>
