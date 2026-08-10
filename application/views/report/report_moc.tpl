<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Báo cáo tăng trưởng năm {$smarty.now|date_format:"%Y"}</span></h4>
			<p class="text-muted mb-0">Hệ thống hỗ trợ bán hàng Ocean City</p>
		</div>
	</div>
	<div class="box_statistic">
		<div class="form-row">
			<div class="col-6 col-md-4 col-lg-2 flex-fill mb-2">
				<div class="box_item_statistic h-100 {if $deviceType eq 'phone'}p-2{else}p-3{/if}" style="background:#eba000"> 
					<p class="title_statistic fs-6 mb-2">Tổng tra cứu</p>
					<div class="number_total fs-3">{$clsISO->formatNumber2($numberView)} <span class="fs-14">lượt</span></div>
				</div>
			</div>
			<div class="col-6 col-md-4 col-lg-2 flex-fill mb-2">
				<div class="box_item_statistic h-100 {if $deviceType eq 'phone'}p-2{else}p-3{/if}" style="background:#1d6a01">
					{if $deviceType eq 'phone'}
					<p class="title_statistic fs-6 mb-2">Thành viên</p>
					{else}
					<p class="title_statistic fs-6 mb-2">Tổng số thành viên</p>
					{/if}
					<div class="number_total fs-3">{$clsISO->formatNumber2($numberUser)} <span class="fs-14">thành viên</span></div>
				</div>
			</div>
			<div class="col-6 col-md-4 col-lg-2 flex-fill mb-2">
				<div class="box_item_statistic h-100 {if $deviceType eq 'phone'}p-2{else}p-3{/if} bg-danger">
					<p class="title_statistic fs-6 mb-2">Chuyển nhượng</p>
					<div class="number_total fs-3">{$clsISO->formatNumber2($numberSop)} <span class="fs-14">căn</span></div>
				</div>
			</div>
			<div class="col-6 col-md-4 col-lg-2 flex-fill mb-2">
				<div class="box_item_statistic h-100 {if $deviceType eq 'phone'}p-2{else}p-3{/if} bg-warning">
					<p class="title_statistic fs-6 mb-2">Cho thuê</p>
					<div class="number_total fs-3">{$clsISO->formatNumber2($numberLeasing)} <span class="fs-14">căn</span></div>
				</div>
			</div>
			<div class="col-6 col-md-4 col-lg-2 flex-fill mb-2">
				<div class="box_item_statistic h-100 {if $deviceType eq 'phone'}p-2{else}p-3{/if} bg-info">
					<p class="title_statistic fs-6 mb-2">Nội thất</p>
					<div class="number_total fs-3">{$clsISO->formatNumber2($numberInterior)} <span class="fs-14">công trình</span></div>
				</div>
			</div>
			<div class="col-6 col-md-4 col-lg-2 flex-fill mb-2">
				<div class="box_item_statistic h-100 {if $deviceType eq 'phone'}p-2{else}p-3{/if} bg-primary">
					<p class="title_statistic fs-6 mb-2">Dịch vụ</p>
					<div class="number_total fs-3">{$clsISO->formatNumber2($numberService)} <span class="fs-14">đơn vị</span></div>
				</div>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-xxl-12">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title">Người dùng mới, Tra cứu nhiều</h3>
						</div>
						<div class="panel-body px-0">
							<div id="user_access_MOC" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-xxl-12">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title">Chuyển nhượng, nội thất, cho thuê, dịch vụ</h3>
						</div>
						<div class="panel-body px-0">
							<div id="service_MOC" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">
							<h3 class="panel-title">Biểu đồ thống kê số lượt tra cứu</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
									<input type="radio" class="btn-check search_stock_view_moc_field" name="time_type_view_MOC" id="sortby_view_1" value="THIS_MONTH" autocomplete="off" data-field="time_type" checked="" data-type="view_moc" onchange="$Core.report.load_stock_MOC_chart('view_moc',event);">
									<label class="btn btn-outline-default" for="sortby_view_1">Tháng</label> 
									
									<input type="radio" class="btn-check search_stock_view_moc_field" name="time_type_view_MOC" id="sortby_view_2" value="THIS_YEAR" autocomplete="off" data-field="time_type" data-type="view_moc" onchange="$Core.report.load_stock_MOC_chart('view_moc',event);"> 
									<label class="btn btn-outline-default" for="sortby_view_2">Năm</label>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="chartViewMOC" class="chartContainer w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title">Biểu đồ tăng trưởng thành viên</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
									<input type="radio" class="btn-check search_stock_sales_moc_field" name="time_type_sales_MOC" id="sortby_sale_1" value="THIS_MONTH" autocomplete="off" data-field="time_type" checked="" data-type="sales_moc" onchange="$Core.report.load_stock_MOC_chart('sales_moc',event);">
									<label class="btn btn-outline-default" for="sortby_sale_1">Tháng</label> 
									
									<input type="radio" class="btn-check search_stock_sales_moc_field" name="time_type_sales_MOC" id="sortby_sale_2" value="THIS_YEAR" autocomplete="off" data-field="time_type" data-type="sales_moc" onchange="$Core.report.load_stock_MOC_chart('sales_moc',event);"> 
									<label class="btn btn-outline-default" for="sortby_sale_2">Năm</label>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="chartSalesMOC" class="chartContainer w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">
							<h3 class="panel-title">Biểu đồ thống kê số lượt truy cập</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="btn-group d-flex" role="group">
									<input type="text" readonly class="form-control datepicker" name="date_access_log_url" placeholder="dd/mm/yyyy" onChange="$Core.report.load_stock_MOC_chart('date_access_log_url',event)" value="{$smarty.now|date_format:'%d/%m/%Y'}">
								</div>
							</div>
						</div>
						
						<div class="panel-body px-0">
							<div id="chartViewAccessLogMOC" class="chartContainer w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">
							<h3 class="panel-title">Biểu đồ thống kê số lượt truy cập tin</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="btn-group d-flex" role="group">
									<input type="text" readonly class="form-control datepicker" name="date_access_transaction" placeholder="dd/mm/yyyy" onChange="$Core.report.load_stock_MOC_chart('date_access_transaction',event)" value="{$smarty.now|date_format:'%d/%m/%Y'}">
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="chartViewAccessTransactionLogMOC" class="chartContainer w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title">Top 10 trang truy cập nhiều nhất</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="input-group d-flex" role="group" aria-label="Sắp xếp">
									<select name="month_top_url" class="form-control form-select" style="width:100px" onChange="$Core.report.load_stock_MOC_chart('access_url_logs',event)"> 
										<option value="">Tháng</option>
										{assign var=curent_month value=$smarty.now|date_format:"m"}
										{section name=i loop=12 start=0 step=1}
											<option value="{$smarty.section.i.iteration}">Tháng {$smarty.section.i.iteration}</option>
										{/section}
									</select>
									
										{assign var=curent_year value=$smarty.now|date_format:"Y"}
									<select name="year_top_url"  class="form-control form-select" style="width:80px" onChange="$Core.report.load_stock_MOC_chart('access_url_logs',event)">
										{section name=i loop=$curent_year+1 start=2024 step=1}
											<option value="{$smarty.section.i.index}">{$smarty.section.i.index}</option>
										{/section}
									</select>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="chartPageTopUrl" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 d-none">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title">Top 10 user truy cập nhiều nhất</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="btn-group d-flex" role="group">
									<input type="radio" class="btn-check search_stock_view_moc_field" name="time_top_user_MOC" id="sortby_top_user_1" value="THIS_MONTH" autocomplete="off" data-field="time_type" checked="" data-type="view_moc" onchange="$Core.report.load_stock_MOC_chart('access_user_logs',event);">
									<label class="btn btn-outline-default" for="sortby_top_user_1">Tháng</label> 
									
									<input type="radio" class="btn-check search_stock_view_moc_field" name="time_top_user_MOC" id="sortby_top_user_2" value="THIS_YEAR" autocomplete="off" data-field="time_type" data-type="view_moc" onchange="$Core.report.load_stock_MOC_chart('access_user_logs',event);"> 
									<label class="btn btn-outline-default" for="sortby_top_user_2">Năm</label>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="chartUserTopMOC" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title">Top 10 user mới nhất</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
									<div class="input-group mr-1 input-group-merge">
										<span class="input-group-text"><i class="bx bx-search"></i></span>
										<input type="text" class="form-control search_field" name="search_user_new" data-field="keySearch" placeholder="Search" onKeyUp="$Core.report.load_stock_MOC_chart('user_new',event)">
									</div>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="listUserNew" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title">Top 10 user tra cứu nhiếu nhất</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
									<div class="input-group mr-1 input-group-merge">
										<span class="input-group-text"><i class="bx bx-search"></i></span>
										<input type="text" class="form-control search_field" name="search_user_view_stock_sale" data-field="keySearch" placeholder="Search" onKeyUp="$Core.report.load_stock_MOC_chart('user_view_stock_sale',event)">
									</div>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="list_user_view_stock_sale" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title" style="white-space: break-spaces">Top 10 user futurehomes tra cứu nhiếu nhất</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
									<div class="input-group mr-1 input-group-merge">
										<span class="input-group-text"><i class="bx bx-search"></i></span>
										<input type="text" class="form-control search_field" name="search_user_view_stock_fh" data-field="keySearch" placeholder="Search" onKeyUp="$Core.report.load_stock_MOC_chart('user_view_stock_fh',event)">
									</div>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="list_user_view_stock_fh" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-12 flex-fill">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title" style="white-space: break-spaces">Danh sách đại lý Lumière SpringBay</h3>
						</div>
						<div class="panel-body px-0">
							<div id="list_agent" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title" style="white-space: break-spaces">Danh sách cập nhật đại lý</h3>
						</div>
						<div class="panel-body px-0">
							<div id="list_agent_log" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title" style="white-space: break-spaces">Danh sách căn độc quyền {$smarty.const.BRAND_NAME}</h3>
						</div>
						<div id="list_stock_DQ" class="panel-body px-0">
							<table class="table table-bordered mb-0" width="100%">
								<thead><tr>
									{if $deviceType ne 'phone'}
									<th width="30px" class="align-center nosort bg-lighter">STT</th>
									{/if}
									<th class="align-center text-left bg-lighter" style="width:100px">Mã căn</th>
									{if $deviceType eq 'phone'}
									<th class="align-center text-left bg-lighter" style="width:200px">Địa chỉ</th>
									{else}
									<th class="align-center text-left bg-lighter">Dự án</th>
									<th class="align-center text-left bg-lighter">Phân khu</th>
									<th class="align-center text-left bg-lighter">Toà nhà</th>
									{/if}
									<th class="align-center text-left bg-lighter">User.FH</th>
									<th class="align-center text-left bg-lighter">MOC</th>
								</tr></thead>
								<tbody>
									{section name=i loop=$list_preloaders max=20}
									<tr>
										{if $deviceType ne 'phone'}
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										{/if}
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										{if $deviceType eq 'phone'}
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										{else}
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										{/if}
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
									</tr>
									{/section}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="col-12">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title" style="white-space: break-spaces">Danh sách truy cập trong ngày</h3>
							<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
								<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
									<div class="input-group mr-1 input-group-merge">
										<span class="input-group-text"><i class='bx bx-calendar' ></i></span>
										<input type="text" readonly class="form-control datepicker" name="date_access_log" placeholder="dd/mm/yyyy" onChange="$Core.report.load_stock_MOC_chart('date_access_log',event)" value="{$smarty.now|date_format:'%d/%m/%Y'}">
									</div>
								</div>
							</div>
						</div>
						<div class="panel-body px-0">
							<div id="list_date_access_log" class="w-100" style="min-height:300px;">
								<div class="p-5 text-center">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
$(document).ready(function(){
	$Core.report.load_stock_MOC_chart('user_access_MOC', event);
	$Core.report.load_stock_MOC_chart('service_MOC', event);
	$Core.report.load_stock_MOC_chart('view_moc',event);
	$Core.report.load_stock_MOC_chart('sales_moc',event);
	$Core.report.load_stock_MOC_chart('user_new',event); 
	$Core.report.load_stock_MOC_chart('access_url_logs',event);
	$Core.report.load_stock_MOC_chart('date_access_log_url',event);
	$Core.report.load_stock_MOC_chart('date_access_transaction',event)
	$Core.report.load_stock_MOC_chart('user_view_stock_sale',event); 
	$Core.report.load_stock_MOC_chart('user_view_stock_fh',event); 
	$Core.report.load_stock_MOC_chart('agent',event); 
	$Core.report.load_stock_MOC_chart('agent_log',event); 
	$Core.report.load_stock_MOC_chart('date_access_log',event); 
	$Core.report.load_stock_MOC_chart('list_stock_DQ',event);
});
</script>
{/literal}