<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Báo cáo nâng cấp gói dịch vụ MOC</span></h4>
			<p class="text-muted mb-0">Hệ thống hỗ trợ bán hàng Ocean City</p>
		</div>
	</div>
	<div class="box_statistic">
		
		<div class="form-row">
			<div class="col-12 col-lg-6">
				<div class="card h-100 no-shadow">
					<div class="card-body">
						<div class="d-flex align-items-center mb-3 justify-content-between">
							<div class="p-left">
								<h5 class="card-title mb-0 text-nowrap">Thành viên</h5>
							</div>
						</div>
						<div >
							<div class="form-row">
								<div class="col-6 col-md-4 flex-fill mb-2">
									<div class="gbox gotoLink px-2 py-3 h-100">
										<h5 class="mb-2 fs-14">Gói dùng thử</h5> 
										<h3 class="fs-5 mb-0 fw-bold text-main">
											<span data-from="0" data-to="39010893816">{$clsISO->formatNumber2($totalUserPackageTrial)}</span>
										</h3>
									</div>
								</div>
								<div class="col-6 col-md-4 flex-fill mb-2">
									<div class="gbox gotoLink px-2 py-3 h-100">
										<h5 class="mb-2 fs-14">Gói PRO</h5> 
										<h3 class="fs-5 mb-0 fw-bold text-main">
											<span data-from="0" data-to="39010893816">{$clsISO->formatNumber2($totalUserPackagePro)}</span>
										</h3>
									</div>
								</div>
								<div class="col-6 col-md-4 flex-fill mb-2">									
									<div class="gbox gotoLink px-2 py-3 h-100">
										<h5 class="mb-2 fs-14">Gói VIP</h5> 
										<h3 class="fs-5 mb-0 fw-bold text-main">
											<span data-from="0" data-to="39010893816">{$clsISO->formatNumber2($totalUserPackageVip)}</span>
										</h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-6">
				<div class="card h-100 no-shadow">
					<div class="card-body">
						<div class="d-flex align-items-center mb-3 justify-content-between">
							<div class="p-left">
								<h5 class="card-title mb-0 text-nowrap">Order</h5>
							</div>
						</div>
						<div >
							<div class="form-row">
								<div class="col-6 col-md-3 flex-fill mb-2">
									<div class="gbox gotoLink px-2 py-3 h-100">
										<h5 class="mb-2 fs-14">Tổng nâng cấp</h5> 
										<h3 class="fs-5 mb-0 fw-bold text-main">
											<span data-from="0" data-to="39010893816">{$clsISO->formatNumber2($total_order)}</span>
										</h3>
									</div>
								</div>
								<div class="col-6 col-md-3 flex-fill mb-2">
									<div class="gbox gotoLink px-2 py-3 h-100">
										<h5 class="mb-2 fs-14">Tỷ lệ gia hạn</h5> 
										<h3 class="fs-5 mb-0 fw-bold text-main">
											<span data-from="0" data-to="39010893816">{$clsISO->formatNumber2($total_extend)}%</span>
										</h3>
									</div>
								</div>
								<div class="col-6 col-md-3 flex-fill mb-2">
									<div class="gbox gotoLink px-2 py-3 h-100">
										<h5 class="mb-2 fs-14">Nâng cấp thành công</h5> 
										<h3 class="fs-5 mb-0 fw-bold text-main">
											<span data-from="0" data-to="39010893816">{$clsISO->formatNumber2($total_success)}</span>
										</h3>
									</div>
								</div>
								<div class="col-6 col-md-3 flex-fill mb-2">							
									<div class="gbox gotoLink px-2 py-3 h-100">
										<h5 class="mb-2 fs-14">Doanh thu</h5> 
										<h3 class="fs-5 mb-0 fw-bold text-main">
											<span data-from="0" data-to="39010893816">{$clsISO->formatNumber2($total_revenue)}đ</span>
										</h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row mt-2">
			<div class="col-md-12">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default" id="loadChartNumber">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title">Biểu đồ thống kê order</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="input-group w-px-200 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
									<select class="form-control form-select" name="month" onChange="$Core.report.load_report_order('NUMBER_CHART', event)"> 
										<option value="">Tháng</option>			
										{foreach from=$list_months item = _month}
										<option value="{$_month}">Tháng {$_month}</option>
										{/foreach}
									</select>
									<select class="form-control form-select" name="year" onChange="$Core.report.load_report_order('NUMBER_CHART', event)">
										{foreach from=$list_years item = _year}
										<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="chartNumber" class="w-100 a" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>				
			</div>	
			
			<div class="col-md-12">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default" id="loadListMember">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center gap-3">
							<h3 class="panel-title">Danh sách tài khoản</h3>
							<div class="d-flex flex-wrap align-items-center justify-content-between flex-fill gap-3">
								<ul class="tab_package nav d-flex flex-wrap gap-2 list pull-right">													
									<li class="nav-item">										
										<input type="radio" name="package_id" onchange="$Core.report.load_report_order('MEMBER',event)" value="1" id="tried" checked>
										<label href="javascript:void(0);" for="tried" class="js_choose-time cursor-pointer">Gói dùng thử ({$totalUserPackageTrial})</label>
									</li>													
									<li class="nav-item">										
										<input type="radio" name="package_id" onchange="$Core.report.load_report_order('MEMBER',event)" value="{$smarty.const._MEMBER_PARKAGE_PRO_ID}" id="PRO">
										<label href="javascript:void(0);" for="PRO" class="js_choose-time cursor-pointer">Gói chuyên nghiệp (Pro) ({$totalUserPackagePro})</label>
									</li>													
									<li class="nav-item">										
										<input type="radio" name="package_id" onchange="$Core.report.load_report_order('MEMBER',event)" value="{$smarty.const._MEMBER_PARKAGE_VVIP_ID}" id="VIP">
										<label href="javascript:void(0);" for="VIP" class="js_choose-time cursor-pointer">Gói tinh hoa (VIP) ({$totalUserPackageVip})</label>
									</li>
								</ul>
								<div class="p_right {if $deviceType eq 'phone'}flex-fill{/if}">
									<div class="input-group mr-1 input-group-merge">
										<span class="input-group-text"><i class="bx bx-search"></i></span>
										<input type="text" class="form-control search_field" name="keyword" data-field="keySearch" placeholder="Search" onKeyUp="$Core.report.load_report_order('MEMBER',event)">
									</div>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="listMember" class="w-100" style="min-height:300px;">
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
</div>
{literal}
<script type="text/javascript">
$(document).ready(function(){
	$Core.report.load_report_order('NUMBER_CHART', event);
	$Core.report.load_report_order('MEMBER', event); 
	$Core.report.load_report_order('MEMBER_TRIAL', event); 
	$Core.report.load_report_order('MEMBER_PRO', event);   
});
</script>
{/literal}