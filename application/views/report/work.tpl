<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-lg-12 col-xxl-10 offset-xxl-1 d-flex justify-content-between align-items-center mb-2">
			<div class="p__left">
				<h4 class="fw-bold mb-1"><span>Báo cáo tiếp khách</span></h4>
				<p class="text-muted mb-0">Phòng ban kinh doanh {$smarty.const.BRAND_NAME}</p>
			</div>
			<div class="p__right">
				{if $deviceType ne 'phone'}
				<div class="d-flex form-inline">
					<div class="mr-2">
						<input type="month" class="form-control search_field" data-field="month" 
							onChange="$Core.report.do_share_search(this, event)" name="month" value="{$current_month}" />
					</div>
					{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO') || $clsISO->checkPermissionGroup('BUSINESS_AREA')}
					<div class="form-group w-px-150">
						<select class="form-control iso-select2 search_field" data-width="100%" name="department_id" 
							data-field="department_id" onchange="$Core.report.do_share_search(this, event)">
							<option value="0">Tất cả</option>
							{if $clsISO->checkPermissionGroup("BUSINESS_AREA")}
								{$clsProperty->getListOption("_DEPARTMENT",0,$oneProfile.department_id)}
							{else}
								{$clsProperty->getListOption("_DEPARTMENT",0,$smarty.const._DEPARTMENT_SALE_ID)}
							{/if}
						</select>
					</div>
					{/if}
				</div>
				{/if}
			</div>
		</div>
		<div class="col-12 col-lg-8 col-xxl-7 offset-xxl-1 mb-2 mb-lg-0">
			<div class="card">
				{if $deviceType eq 'phone'}
				<div class="card-header position-relative border-bottom mb-2">
					<div class="d-flex justify-content-center">
						<div class="w-px-200 mr-2">
							<input type="month" class="form-control search_field" data-field="month" 
								onChange="$Core.report.do_share_search(this, event)" name="month" value="{$current_month}" />
						</div>
						{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO') || $clsISO->checkPermissionGroup('BUSINESS_AREA')}
						<select class="form-control iso-select2 search_field" data-width="100%" name="department_id" 
							data-field="department_id" onchange="$Core.report.do_share_search(this, event)">
							<option value="0">Tất cả</option>
							{if $clsISO->checkPermissionGroup("BUSINESS_AREA")}
								{$clsProperty->getListOption("_DEPARTMENT",0,$oneProfile.department_id)}
							{else}
								{$clsProperty->getListOption("_DEPARTMENT",0,$smarty.const._DEPARTMENT_SALE_ID)}
							{/if}
						</select>
						{/if}
					</div>
				</div>
				{/if}
				<div id="holder_report_share" class="card-body{if $deviceType eq 'phone'} p-2{/if}">
					<div class="table-container no-shadow overflow-x-auto text-nowrap mb-2">		
						<table class="table table-iloocal table-computer table-bordered" width="100%" cellpadding="0" cellspacing="0">
							<thead><tr>
								<th width="30px" class="align-center text-center bg-lighter">STT</th>
								<th class="align-center bg-lighter" width="30%">Họ và tên</th>
								{if $deviceType ne 'phone'}
								<th width="15%" class="align-center text-center bg-lighter">Mã nhóm</th>
								<th class="align-center bg-lighter" width="20%">Vị trí</th>
								{/if}
								<th width="68px" class="align-center text-center bg-lighter">Số ảnh</th>
								<th class="align-center text-right bg-lighter" width="20%">Số tiền</th>
							</tr></thead>
							<tbody>
								{section loop=20 name=i start=1 step=1}
								<tr>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									{if $deviceType ne 'phone'}
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									{/if}
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-xxl-3 col-md-4">
			<div class="sticky top-px-75">
				{$core->getBlock('home_news')}
				<div id="holder_report_chart">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title mb-0">
								<div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div>
							</h3>
						</div>
						<div class="card-body">
							<div class="card p-2 mb-3">
								<div class="form-row mb-2" style="row-gap: 10px">
									<div class="col-6">
										<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
											<i class='bx bxs-group text-info'></i>
											<span>Lượt tiếp khách</span>
											<span><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
										</div>
									</div>
									<div class="col-6">
										<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
											<i class='bx bxs-group text-success'></i>
											<span>Tổng khách</span>
											<span><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
										</div>
									</div>
									<div class="col-6">
										<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
											<i class='bx bxs-map text-success'></i>
											<span>Sale có hoạt động</span>
											<span><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
										</div>
									</div>
									<div class="col-6">
										<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
											<i class='bx bx-building text-info'></i>
											<span>Phòng KD hoạt động</span>
											<span><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
										</div>
									</div>
								</div>
								<div class="alert alert-success py-2 mb-0">
									<i class='bx bx-trending-up'></i> 
									<div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div>
								</div>
							</div>
							<div class="box_progess">
								<h3><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></h3>
								<div class="mb-3">
									<div class="d-flex gap-2 align-items-center">
										<span class="fs-16 fw-semibold w-15" style="max-width: 50px">
											<div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div>
										</span>
										<div class="progress w-70" style="height: 15px;">
										  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
										</div>
										<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
									</div>
									<div class="d-flex gap-2 align-items-center">
										<span class="fs-16 fw-semibold w-15" style="max-width: 50px">
											<div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div>
										</span>
										<div class="progress w-70" style="height: 15px;">
										  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
										</div>
										<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
									</div>
									<div class="d-flex gap-2 align-items-center">
										<span class="fs-16 fw-semibold w-15" style="max-width: 50px">
											<div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div>
										</span>
										<div class="progress w-70" style="height: 15px;">
										  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
										</div>
										<span class="fs-14">
											<div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div>
										</span>
									</div>
								</div>
								<div class="alert alert-warning py-2 mb-0 text-main">
									<i class='bx bx-info-circle'></i> 
									<div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div>
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
	$(function(){
		$Core.report.load_content_share({});
		$Core.report.load_report_share({});
	});
</script>
{/literal}