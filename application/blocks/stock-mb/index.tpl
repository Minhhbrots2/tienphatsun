<div class="modal bottom fade menu-more" tabindex="-1" aria-modal="true" role="dialog" > 
	<div class="modal-dialog modal-dialog-scrollable modal-bottom">
		<div class="modal-content">
			<div class="modal-body overflow-y-auto"  style="max-height: 90vh">
				{if $clsISO->checkPermissionGroup('DIRECTOR') eq '1'}
					<p class="text-upper mb-1 fs-12">Bán hàng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/crm/">
							<span class="icon"><i class="menu-icon tf-icons bx bx-user-pin me-1"></i></span>
							<span class="">CRM</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/ban-tin.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-news me-1"></i></span>
							<span class="">Bản tin FH</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/lich-dao-tao.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-slideshow me-1"></i></span>
							<span class="">Sự kiện đào tạo</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/report_stock_price.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-money me-1"></i></span>
							<span class="">Tổng quan giá phân khu</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/quan-ly-khach-hang.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-user me-1"></i></span>
							<span class="">Quản lý khách hàng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/net-dep-lao-dong.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-image me-1"></i></span>
							<span class="">Hoạt động tiếp khách</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/vinh-danh.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-bell-ring me-1"></i></span>
							<span class="">Vinh danh bán hàng</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Giao dịch chốt</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/giao-dich.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-terminal me-1"></i></span>
							<span class="">Giao dịch chốt</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/billing/report.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-doughnut-chart me-1"></i></span>
							<span class="">Báo cáo bán hàng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/billing/report/mwf.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-pie-chart-alt-2 me-1"></i></span>
							<span class="">Báo cáo quỹ F1</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/hug.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-hdd me-1"></i></span>
							<span class="">Quỹ ôm Masteri</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Báo cáo</p>
					<div class="d-flex flex-wrap gap-2">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/sale_month.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-slideshow me-1"></i></span>
							<span class="">Kết quả kinh doanh</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/stock-resource.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-news me-1"></i></span>
							<span class="">Check nguồn căn</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/stock.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-slideshow me-1"></i></span>
							<span class="">Báo cáo bán hàng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/crm/report.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-slideshow me-1"></i></span>
							<span class="">Khách hàng /CRM</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/work.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-table me-1"></i></span>
							<span class="">Nét đẹp lao động</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/worktime.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-check-shield me-1"></i></span>
							<span class="">Vắng mặt</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/report_user.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-user me-1"></i></span>
							<span class="">Người dùng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/bao-cao.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-aperture me-1"></i></span>
							<span class="">Hiệu suất bán hàng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/bao-cao-dang-nhap.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-line-chart me-1"></i></span>
							<span class="">Tần suất truy cập</span>
						</a>
					</div>
				{elseif $clsISO->checkPermissionGroup('SALE_DIRECTOR') eq '1'}
					<p class="text-upper mb-1 fs-12">Bán hàng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/tool.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-search me-1"></i></span>
							<span class="">Tra cứu căn hộ</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/crm/">
							<span class="icon"><i class="menu-icon tf-icons bx bx-user-pin me-1"></i></span>
							<span class="">CRM</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/ban-tin.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-news me-1"></i></span>
							<span class="">Bản tin FH</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/net-dep-lao-dong.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-image me-1"></i></span>
							<span class="">Hoạt động tiếp khách</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/lich-phong-hop.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-camera-home me-1"></i></span>
							<span class="">Đăng ký phòng họp</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Dự án thấp tầng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p2.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
							<span class="">VHOP2</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p3.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
							<span class="">VHOP3</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p9.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
							<span class="">VHGG</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Dự án cao tầng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p1/b83.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
							<span class="">VHOP1</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p2/b8577.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
							<span class="">VHOP2</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p9/b8659.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
							<span class="">MGA</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Giao dịch chốt</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/giao-dich.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-terminal me-1"></i></span>
							<span class="">Giao dịch chốt</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/billing/report.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-doughnut-chart me-1"></i></span>
							<span class="">Báo cáo bán hàng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/billing/report/mwf.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-pie-chart-alt-2 me-1"></i></span>
							<span class="">Báo cáo quỹ F1</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/hug.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-hdd me-1"></i></span>
							<span class="">Quỹ ôm Masteri</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Báo cáo</p>
					<div class="d-flex flex-wrap gap-2">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/campaign/NS1WaWV0SVNP.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-doughnut-chart me-1"></i></span>
							<span class="">Thi đua team {$smarty.now|date_format:"%Y"}</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/campaign/NC1WaWV0SVNP.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-bar-chart-square me-1"></i></span>
							<span class="">Thi đua cá nhân {$smarty.now|date_format:"%Y"}</span>
						</a>
					</div>
				{elseif $clsISO->checkPermissionGroup('ADMIN_PROJECT') eq '1'}
					<p class="text-upper mb-1 fs-12">Bán hàng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/tool.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-search me-1"></i></span>
							<span class="">Tra cứu căn hộ</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/ban-tin.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-news me-1"></i></span>
							<span class="">Bản tin FH</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/lich-dao-tao.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-slideshow me-1"></i></span>
							<span class="">Sự kiện đào tạo</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/lich-phong-hop.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-camera-home me-1"></i></span>
							<span class="">Đăng ký phòng họp</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Dự án thấp tầng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p2.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
							<span class="">VHOP2</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p3.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
							<span class="">VHOP3</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p9.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
							<span class="">VHGG</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Dự án cao tầng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p1/b83.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
							<span class="">VHOP1</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p2/b8577.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
							<span class="">VHOP2</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p9/b8659.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
							<span class="">MGA</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Giao dịch chốt</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/giao-dich.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-terminal me-1"></i></span>
							<span class="">Giao dịch chốt</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/billing/report.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-doughnut-chart me-1"></i></span>
							<span class="">Báo cáo bán hàng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/billing/report/mwf.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-pie-chart-alt-2 me-1"></i></span>
							<span class="">Báo cáo quỹ F1</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/hug.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-hdd me-1"></i></span>
							<span class="">Quỹ ôm Masteri</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Báo cáo</p>
					<div class="d-flex flex-wrap gap-2">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/kpi.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-chart me-1"></i></span>
							<span class="">Doanh số tháng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/sale.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-pie-chart me-1"></i></span>
							<span class="">Doanh số nhân viên</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/campaign/NS1WaWV0SVNP.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-doughnut-chart me-1"></i></span>
							<span class="">Thi đua team {$smarty.now|date_format:"%Y"}</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/campaign/NC1WaWV0SVNP.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-bar-chart-square me-1"></i></span>
							<span class="">Thi đua cá nhân {$smarty.now|date_format:"%Y"}</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/overtime/report.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-bar-chart-alt-2 me-1"></i></span>
							<span class="">Thi đua khối BO</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/stock.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-slideshow me-1"></i></span>
							<span class="">Báo cáo bán hàng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/crm/report.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-slideshow me-1"></i></span>
							<span class="">Khách hàng /CRM</span>
						</a>
					</div>
				{elseif $clsISO->checkPermissionGroup('ACCOUNTANT') eq '1'}				
					<p class="text-upper mb-1 fs-12">Kế toán</p>
					<div class="d-flex flex-wrap gap-2 mb-3">					
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/fund.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-money me-1"></i></span>
							<span class="">Thu chi nội bộ</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/vat.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-shape-circle me-1"></i></span>
							<span class="">VAT đã xuất</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/commission.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-shape-circle me-1"></i></span>
							<span class="">Hoa hồng bán mới</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/fund/report.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-bar-chart-alt-2 me-1"></i></span>
							<span class="">Báo cáo thu chi</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Giao dịch chốt</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/giao-dich.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-terminal me-1"></i></span>
							<span class="">Giao dịch chốt</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/billing/report.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-doughnut-chart me-1"></i></span>
							<span class="">Báo cáo bán hàng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/billing/report/mwf.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-pie-chart-alt-2 me-1"></i></span>
							<span class="">Báo cáo quỹ F1</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/hug.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-hdd me-1"></i></span>
							<span class="">Quỹ ôm Masteri</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Hoạt động</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/worktime.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-run me-1"></i></span>
							<span class="">Nghỉ phép</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/hoc-tap.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-graduation me-1"></i></span>
							<span class="">Kho tài liệu</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/overtime.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-timer me-1"></i></span>
							<span class="">Đăng ký tăng ca</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/issue.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-task me-1"></i></span>
							<span class="">Quản lý công việc</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Báo cáo</p>
					<div class="d-flex flex-wrap gap-2">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/kpi.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-chart me-1"></i></span>
							<span class="">Doanh số tháng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/sale.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-pie-chart me-1"></i></span>
							<span class="">Doanh số nhân viên</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/campaign/NS1WaWV0SVNP.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-doughnut-chart me-1"></i></span>
							<span class="">Thi đua team {$smarty.now|date_format:"%Y"}</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/campaign/NC1WaWV0SVNP.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-bar-chart-square me-1"></i></span>
							<span class="">Thi đua cá nhân {$smarty.now|date_format:"%Y"}</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/overtime/report.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-bar-chart-alt-2 me-1"></i></span>
							<span class="">Thi đua khối BO</span>
						</a>
					</div>
				{else}
					<p class="text-upper mb-1 fs-12">Bán hàng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/tool.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-search me-1"></i></span>
							<span class="">Tra cứu căn hộ</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/crm/">
							<span class="icon"><i class="menu-icon tf-icons bx bx-user-pin me-1"></i></span>
							<span class="">CRM/Khách hàng</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/ban-tin.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-news me-1"></i></span>
							<span class="">Bản tin FH</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/lich-dao-tao.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-slideshow me-1"></i></span>
							<span class="">Sự kiện đào tạo</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/lich-phong-hop.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-camera-home me-1"></i></span>
							<span class="">Đăng ký phòng họp</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/net-dep-lao-dong.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-image me-1"></i></span>
							<span class="">Hoạt động tiếp khách</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/vinh-danh.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-bell-ring me-1"></i></span>
							<span class="">Vinh danh bán hàng</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Dự án thấp tầng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p2.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
							<span class="">VHOP2</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p3.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
							<span class="">VHOP3</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p9.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
							<span class="">VHGG</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Dự án cao tầng</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p1/b83.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
							<span class="">VHOP1</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p2/b8577.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
							<span class="">VHOP2</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p9/b8659.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
							<span class="">MGA</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Hoạt động</p>
					<div class="d-flex flex-wrap gap-2 mb-3">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/worktime.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-run me-1"></i></span>
							<span class="">Nghỉ phép</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/hoc-tap.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-graduation me-1"></i></span>
							<span class="">Kho tài liệu</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/issue.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-task me-1"></i></span>
							<span class="">Quản lý công việc</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/register_mwf.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-registered me-1"></i></span>
							<span class="">Đăng ký xem nhà mẫu</span>
						</a>
					</div>
					<p class="text-upper mb-1 fs-12">Báo cáo</p>
					<div class="d-flex flex-wrap gap-2">
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/campaign/NS1WaWV0SVNP.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-doughnut-chart me-1"></i></span>
							<span class="">Thi đua team {$smarty.now|date_format:"%Y"}</span>
						</a>
						<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/campaign/NC1WaWV0SVNP.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-bar-chart-square me-1"></i></span>
							<span class="">Thi đua cá nhân {$smarty.now|date_format:"%Y"}</span>
						</a>
					</div>	
				{/if}
			</div>	
		</div>
	</div>
</div>