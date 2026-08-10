<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<form method="POST">
		<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2 mb-lg-0">
			<div class="title mb-lg-0">
				<h4 class="fw-bold mb-1">Hiệu suất bán hàng</span></h4>
				<span class="text-muted">Báo cáo kết quả, chăm sóc KH</span>
			</div>
			<div class="search d-flex{if $deviceType eq 'phone'} flex-wrap{/if} align-items-center gap-1">
				{if $deviceType eq 'phone'}
					{if $clsISO->checkSale()}
					<button type="button" title="Thêm báo cáo" onClick="$Core.report.open(this, event)" report_id="0" 
					class="btn{if $is_send_report_today eq '1' || !$clsReport->check_time_send_report()} disabled{/if} btn-outline-danger">+ Báo cáo</button>
					{/if}
				{else}
					{$core->getBlock('report_search')}
				{/if}
			</div>
		</div>
		{if $deviceType eq 'phone'}
		<div class="search d-flex flex-wrap align-items-center mt-2">
			{$core->getBlock('report_search')}
		</div>
		{/if}
	</form>
	<hr class="my-0" />
	<!-- BLĐ -->
	{if $clsISO->checkPermissionGroup('DIRECTOR')}
	<div class="card mb-2 mt-3">
		<div class="card-header d-flex align-items-center justify-content-between">
			<h5 class="mb-0">Báo cáo hiệu suất</h5>
			<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
				<i class="fa fa-question-circle"></i>
			</a>
		</div>
		<div class="card-body holder_sfs_reports">
			<div class="table-container">
				<table border="0" cellspacing="0" cellpadding="0" class="table w-100">
					<thead><tr>
						<th class="align-center">Nhân viên</th>
						<th class="align-center text-center">...</th>
						<th class="align-center text-center">...</th>
						<th class="align-center text-center">...</th>
						<th class="align-center text-center">...</th>
						<th class="align-center text-center">...</th>
						<th class="align-center text-center">...</th>
					</tr></thead>
					{section name=i loop=$list_preloaders max = 10}
					<tr>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
					</tr>
					{/section}
				</table>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="card-title mb-0">Gửi báo cáo hàng ngày</h5>
			<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
				<i class="fa fa-question-circle"></i>
			</a>
		</div>
		<div class="card-body holder_today_reports">
			<div class="table-wrapper">
				<table class="table table-bordered" cellpadding="0" cellspacing="0">
					<thead><tr>
						<th class="align-center bg-lighter">Họ và tên</th>
						<th class="align-center bg-lighter">..</th>
						<th class="align-center bg-lighter">..</th>
						<th class="align-center bg-lighter">..</th>
						<th class="align-center bg-lighter">..</th>
						<th class="align-center bg-lighter">..</th>
						<th class="align-center bg-lighter">..</th>
					</tr></thead>
					{section name=i loop=$list_preloaders max = 10}
					<tr>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
					</tr>
					{/section}
				</table>
			</div>
		</div>
	</div>
	<div class="form-row mt-2">
		<div class="col-12 col-lg-6">
			<div class="card h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="card-title mb-0">Top 10 nhân viên</h5>
					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="card-body holder_chart_top_reports">
					<div class="p-5 text-muted text-center">Loading...</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-6">
			<div class="card h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="card-title mb-0">Ngân sách quảng cáo</h5>
					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="card-body holder_chart_profs_reports">
					<div class="p-5 text-muted text-center">Loading...</div>
				</div>
			</div>
		</div>
	</div>
	{else}
	<div class="alert alert-warning my-2">
		<strong>Thông báo.</strong>
		<ul class="mb-0">
			<li>Không được quên gửi báo cáo 5 lần, liên tục</li>
			<li>Báo cáo công việc hàng ngày từ 18:00 hôm trước - 09:00 sáng ngày hôm sau</li>
		</ul>
	</div>
	<div class="form-row my-2">
		<!-- GĐKD -->
		{if $clsISO->checkPermissionGroup('SALE_DIRECTOR')}
		<div class="col-12 col-md-8 mb-2 mb-lg-0">
			<div class="card mb-2">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="mb-0">Báo cáo hiệu suất</h5>
					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="card-body holder_sfs_reports">
					<div class="table-container">
						<table border="0" cellspacing="0" cellpadding="0" class="table w-100">
							<thead><tr>
								<th class="align-center">Nhân viên</th>
								<th class="align-center text-center">...</th>
								<th class="align-center text-center">...</th>
								<th class="align-center text-center">...</th>
								<th class="align-center text-center">...</th>
								<th class="align-center text-center">...</th>
								<th class="align-center text-center">...</th>
							</tr></thead>
							{section name=i loop=$list_preloaders max = 10}
							<tr>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							</tr>
							{/section}
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="card-title mb-0">Gửi báo cáo hàng ngày</h5>
					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="card-body holder_today_reports">
					<div class="table-wrapper">
						<table class="table table-bordered" cellpadding="0" cellspacing="0">
							<thead><tr>
								<th class="align-center bg-lighter">Họ và tên</th>
								<th class="align-center bg-lighter">..</th>
								<th class="align-center bg-lighter">..</th>
								<th class="align-center bg-lighter">..</th>
								<th class="align-center bg-lighter">..</th>
								<th class="align-center bg-lighter">..</th>
								<th class="align-center bg-lighter">..</th>
							</tr></thead>
							{section name=i loop=$list_preloaders max = 10}
							<tr>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							</tr>
							{/section}
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			<div class="sticky top-px-75">
				<div class="card mb-2">
					<div class="card-body">
						<div class="table-wrapper">
							<table border="0" class="table table-iloocal table-bordered" width="100%">
								<thead><tr>
									<th width="120px" class="align-center text-left">Ngày BC</th>
									<th class="align-center text-left">Nội dung</th>
								</tr></thead>
								<tbody class="holder_reports">
									{section name=i loop=$list_preloaders max=15}
									<tr>
										<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									</tr>
									{/section}
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="card mb-2">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">Hiệu suất nhân viên</h5>
						<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<div class="card-body ">
						<div class="holder_chart_top_reports">
							<div class="p-5 text-muted text-center">Loading...</div>
						</div>
					</div>
				</div>
				<div class="card mb-0">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">Thống kê theo thời gian</h5>
						<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<div class="card-body holder_chart_reports">
						<div class="p-5 text-muted text-center">Loading...</div>
					</div>
				</div>
			</div>
		</div>
		{else}
		<!-- Sale -->
		<div class="col-12 col-md-8">
			<div class="card mb-2">
				<div class="card-body">
					<div class="table-wrapper">
						<table border="0" class="table table-iloocal table-bordered" width="100%">
							<thead><tr>
								<th width="120px" class="align-center text-left">Ngày báo cáo</th>
								<th class="align-center text-left">Nội dung</th>
							</tr></thead>
							<tbody class="holder_reports">
								{section name=i loop=$list_preloaders max=15}
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			<div class="sticky top-px-75">
				<div class="card mb-2">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">Thống kê báo cáo</h5>
						<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<div class="card-body holder_total_reports">
						<div class="p-5 text-muted text-center">Loading...</div>
					</div>
				</div>
				<div class="card mb-0">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">Thống kê báo cáo</h5>
						<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<div class="card-body holder_chart_reports">
						<div class="p-5 text-muted text-center">Loading...</div>
					</div>
				</div>
			</div>
		</div>
		{/if}
	</div>
	{/if}
</div>
{literal}
<style type="text/css">
	.table-responsive td{
		text-align:left;
	}
	.multiselect-native-select{
		width:100%
	}
	.ui-datepicker,
	.select2-container--open{
		z-index:9999 !important;
	}
	.table-iloocal tr td {
		font-weight: 400;
		font-size: 14px;
		line-height: 20px;
		padding: 6px 15px;
		background: var(--bs-white);
		border: 1px solid rgba(0, 0, 0, 0.1);
		height: 40px;
	}
	.table-iloocal thead tr th {
		background: #F9F9F9;
		border: 1px solid rgba(0, 0, 0, 0.1);
		white-space: nowrap;
		font-weight: 600;
		font-size: 14px;
		line-height: 20px;
		padding: 10px 15px
	}
	.table-iloocal .js__add-report:not(.text-muted) {
		font-weight: 600;
		font-size: 14px;
		line-height: 19px;
		color: #1756C8 !important;
		cursor: pointer;
	}
	.table-iloocal .js__add-report span.icon {
		display: inline-block;
		width: 14px;
		height: 14px;
		text-align: center;
		line-height: 12px;
		background: #1756C8;
		border-radius: 2px;
		-moz-border-radius: 2px;
		-webkit-border-radius: 2px;
		color: var(--bs-white);
		padding:3px;
		font-size: 10px;
	}
</style>
<script type="text/javascript">
	$(function(){
		if($('.holder_reports').length){
			$Core.report.load_reports({});
		}
		if($('.holder_sfs_reports').length){
			$Core.report.load_sfs_reports({});
		}
		if($('.holder_chart_top_reports').length){
			$Core.report.load_chart_top_reports({});
		}
		if($('.holder_today_reports').length){
			$Core.report.load_today_reports({});
		}
		$Core.report.load_total_reports({});
		$Core.report.load_chart_reports({});
	});
</script>
{/literal}