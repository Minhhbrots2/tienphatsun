<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="oMABKutTId mb-2">
		<h4 class="fw-bold mb-1">Tổng quan thị trường</h4>
		<span class="text-muted">Tổng hợp các thông tin thị trường</span>
	</div>
	<div class="form-row">
		<div class="col-12 col-md-9 col-lg-9">
			<div class="form-row mb-2">
				<div class="col-6 col-md-4 col-lg-3 mb-2 mb-lg-0">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card no-shadow">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Tổng căn còn lại</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div gId="{$gId}" class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=get_total" 
							data-options='{ldelim}"tp":"total_stock"{rdelim}'>
							<span class="fw-bold fs-3">00.0</span>
						</div>
					</div>	
				</div>
				<div class="col-6 col-md-4 col-lg-3 mb-2 mb-lg-0">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card no-shadow">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0 text-nowrap">Tổng căn bán hôm qua</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div gId="{$gId}" class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=get_total" 
							data-options='{ldelim}"tp":"total_stock_sold"{rdelim}'>
							<span class="fw-bold fs-3">00.0</span>
						</div>
					</div>	
				</div>
				<div class="col-6 col-md-4 col-lg-3 mb-2 mb-lg-0">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card no-shadow">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Căn hộ rẻ nhất</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div gId="{$gId}" class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=get_total" 
							data-options='{ldelim}"tp":"total_stock"{rdelim}'>
							<span class="fw-bold fs-3">00.0</span>
						</div>
					</div>	
				</div>
				<div class="col-6 col-md-4 col-lg-3">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card no-shadow">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Căn hộ đắt nhất</h5>
							<a data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Căn hộ đắt nhất"><i class="bx bx-help-circle"></i></a>
						</div>
						<div gId="{$gId}" class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=get_total" 
							data-options='{ldelim}"tp":"total_stock"{rdelim}'>
							<span class="fw-bold fs-3">00.0</span>
						</div>
					</div>	
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-12 col-lg-12">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card no-shadow mb-2">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Thống kê đại lý</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div class="card-body">
							<div gId="{$gId}" class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=get_agency_chart_total" 
							data-options='{ldelim}{rdelim}'><div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div></div>
						</div>
					</div>	
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-md-6 col-lg-4">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card no-shadow mb-2">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Thống kê theo dự án</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div gId="{$gId}" class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=get_project_chart" 
							data-options='{ldelim}{rdelim}'>
							<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
						</div>
					</div>	
				</div>
				<div class="col-12 col-md-6 col-lg-4">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card no-shadow mb-2">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Thống kê theo dự án</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div gId="{$gId}" class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=get_project_chart" 
							data-options='{ldelim}{rdelim}'>
							<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
						</div>
					</div>	
				</div>
				<div class="col-12 col-md-6 col-lg-4">
					<div class="card no-shadow mb-2">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Căn check trong ngày</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div gId="{$gId}" class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=load_top_stock_logs" data-options='{ldelim}{rdelim}'>
							<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
		<div class="col-12 col-md-3 col-lg-3">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card mb-2">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="card-title mb-0">Thống kê quỹ đại lý</h5>
					<a><i class="bx bx-help-circle"></i></a>
				</div>
				<div class="card-body">
					<div class="table-container no-shadow overflow-x-auto text-nowrap">
						<table class="table dragable table-bordered" cellpadding="0" cellspacing="0">
							<thead><tr>
								<th class="align-center bg-lighter h-px-35">Đại lý</th>
								<th class="align-center bg-lighter h-px-35">Hôm nay</th>
								<th class="align-center bg-lighter h-px-35">SL bán</th>
								<th class="align-center bg-lighter h-px-35">SL thêm</th>
							</tr></thead>
							<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=report&act=get_agency_table_total" 
							data-options='{ldelim}{rdelim}'>
								{section name=i loop=$list_preloaders max=25}
								<tr>
									<td class="align-center">
										<div class="animate-bg w-100 h-px-20 rounded-pill"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg w-100 h-px-20 rounded-pill"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg w-100 h-px-20 rounded-pill"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg w-100 h-px-20 rounded-pill"></div>
									</td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>	
		</div>
	</div>
 </div>