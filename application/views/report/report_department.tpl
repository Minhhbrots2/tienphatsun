<div class="container-xxl flex-grow-1 pt-2 container-p-y">

	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">

		<div class="p__left xs:w-100 mb-2 mb-lg-0">

			<h4 class="fw-bold mb-1">

				<span id="title_page">Báo cáo phòng kinh doanh {if $clsISO->checkSale()}{$oneDep.title}{/if}</span>

			</h4>

			<p class="text-muted mb-0 txt_time">Báo cáo kết quả theo phòng kinh doanh</p>

		</div>

		<div class="search xs:w-100 d-flex{if $deviceType eq 'phone'} flex-wrap{/if} align-items-center gap-1">

			{$core->getBlock('report_dept_search')}

		</div>

	</div>	

	<div class="clearfix"></div>

	{assign var = gId value = $clsISO->getUniqid()}

	<div class="card no-shadow mb-2">

		<div class="card-body">

			<div gId="{$gId}" class="report_briefs briefs gap-2 gap-xxl-2 d-flex flex-wrap ajax" 

				data-options='{ldelim}"department_id":{$oneProfile.department_id}{rdelim}' 

				data-url="{$PCMS_URL}/index.php?mod={$mod}&act=report_dep_total">

				{foreach from=$list_blocks item = _oI}

				<div class="brief-item {$_oI.class}">

					<p class="fs-16 mb-2">{$_oI.title}</p>

					<h3 class="fs-4 mb-2 text-white">

						<div class="animate-bg w-px-50 h-px-15 rounded-2"></div>

					</h3>

					<div class="animate-bg w-px-100 h-px-15 rounded-2"></div>

				</div>

				{/foreach}

			</div>

		</div>

	</div>

	<div class="clearfix"></div>

	<div class="form-row mb-2">

		<div class="col-12 col-lg-8 col-xxl-9">

			<div class="report_targets mb-2">

				<div class="form-row">

					<div class="col-12 col-md-4 mb-2 mb-lg-0">

						<div class="p-3 rounded-3 bg-label-success">

							<div class="d-flex gap-2 align-items-center mb-1">

								<i class='bx bx-check-circle text-success text-fs-40'></i>

								<div class="d-flex gap-1 flex-column">

									<small>Đã hoàn thành 0% KPI trong năm {$Current_Year}</small>

									<div class="progress">

									  <div class="progress-bar bg-primary" role="progressbar" style="width:20%"></div>

									</div>

								</div>

							</div>

							<div class="d-flex align-items-center text-fs-12 justify-content-between">

								<span class="text-muted">Số giao dịch hiện tại: </span>

								<span>Còn thiếu <strong class="text-warning">0</strong> GĐ</span>

							</div>

						</div>

					</div>

					<div class="col-12 col-md-4 mb-2 mb-lg-0">

						<div class="p-3 rounded-3 bg-label-danger">

							<div class="d-flex gap-2 align-items-center mb-1">

								<i class='bx bx-dollar-circle text-success text-fs-40'></i>

								<div class="d-flex gap-1 flex-column">

									<small>Đã hoàn thành 0% KPI trong năm {$Current_Year}</small>

									<div class="progress">

									  <div class="progress-bar bg-primary" role="progressbar" style="width:20%"></div>

									</div>

								</div>

							</div>

							<div class="d-flex align-items-center text-fs-12 justify-content-between">

								<span class="text-muted">Doanh số hiện tại: 0</span>

								<span>Còn thiếu <strong class="text-warning">0 tỷ</strong></span>

							</div>

						</div>

					</div>

					<div class="col-12 col-md-4">

						<div class="p-3 rounded-3 bg-label-primary">

							<div class="d-flex gap-2 align-items-center mb-1">

								<i class='bx bx-user-circle text-success text-fs-40'></i>

								<div class="d-flex gap-1 flex-column" style="width:calc(100% - 50px)">

									<small>Đã hoàn thành 0% KPI trong năm {$Current_Year}</small>

									<div class="progress">

										<div class="progress-bar bg-primary" role="progressbar" style="width:20%"></div>

									</div>

								</div>

							</div>

							<div class="d-flex align-items-center text-fs-12 justify-content-between">

								<span class="text-muted">Số nhân sự hiện tại: 0</span>

								<span>Còn thiếu <strong class="text-warning">0</strong> nhân sự</span>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="card no-shadow mb-2 group_search">

				<div class="card-header d-flex align-items-center justify-content-between">

					<div class="title_box_KPI">

						<h3 class="card-title mb-0">Kết quả kinh doanh</h3>

					</div>

					<a href="javascript:void(0);" class="text-muted help_pop" title="Trợ giúp">

						<i class="fa fa-question-circle"></i>

					</a>

				</div>

				<div class="card-body">

					<div class="table-container no-shadow text-nowrap overflow-auto">

						<table class="table table-bordered dragable" width="100%" cellpadding="0" cellspacing="0">

							<thead class="position-sticky top-0 zindex-3">

								<tr>

									<th class="align-center bg-lighter h-px-35" width="40" rowspan="2">STT</th>

									<th class="align-center bg-lighter h-px-35" rowspan="2">Họ và tên</th>
									<th class="align-center bg-lighter h-px-35" rowspan="2" width="80">MXH</th>

									<th class="align-center bg-lighter h-px-35 sortable" width="100px" rowspan="2" 

										data-field="total_share" onclick="$Core.report.do_sort(this, event)">

										Tiếp khách

									</th>

									<th class="align-center bg-lighter h-px-35 sortable" onclick="$Core.report.do_sort(this, event)" 

										data-field="total_search" width="100px" rowspan="2">Check căn</th>

									<th class="align-center text-center bg-lighter h-px-35" colspan="3">Giao dịch</th>

									<th class="align-center text-center bg-lighter h-px-35" colspan="3">Doanh số</th>

									<th class="align-center text-center bg-lighter h-px-35 sortable" rowspan="2" width="80" 

										onclick="$Core.report.do_sort(this, event)" data-field="total_work_unit">T.Công</th>

								</tr>

								<tr>

									<th class="align-center text-center bg-lighter h-px-35 sortable" data-field="target_quantity" 

										onclick="$Core.report.do_sort(this, event)">

										<a class="cursor-pointer">KPI</a>

									</th>

									<th class="align-center text-center bg-lighter h-px-35 sortable" data-field="target_achieved_quantity" 

										onclick="$Core.report.do_sort(this, event)">

										<a class="cursor-pointer">Thực tế</a>

									</th>

									<th class="align-center text-center bg-lighter h-px-35" width="80">Hoàn thành</th>

									<th class="align-center text-center bg-lighter h-px-35 sortable" data-field="target_amount" 

										onclick="$Core.report.do_sort(this, event)">

										<a class="cursor-pointer">KPI</a>	

									</th>

									<th class="align-center text-center bg-lighter h-px-35 sortable desc" data-field="target_achieved_amount" 

										onclick="$Core.report.do_sort(this, event)">

										<a class="cursor-pointer">Thực tế</a>

									</th>

									<th class="align-center text-center bg-lighter h-px-35 sortable" width="80">Hoàn thành</th>

								</tr>

							</thead>

							<tbody class="ajax search_KPI"  data-url="{$PCMS_URL}/index.php?mod=report&act=load_KPI" gId="{$gId}" >

								{section name=i loop=$list_preloaders max=10}

								<tr>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

								</tr>

								{/section}

							</tbody>

						</table>

					</div>

				</div>

			</div>

			<div class="clearfix"></div>

			<div class="card no-shadow mb-2">

				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">

					<div class="card-title mb-0">

						<div class="d-flex align-items-center gap-2">

							<img src="{$URL_IMAGES}/marketing.png" class="w-px-40" />

							<div class="d-flex gap-1 flex-column">

								<h5 class="mb-0">Hoạt động Marketing</h5>

								<small class="text-muted text-fs-14">Tổng quan ngân sách Marketing</small>

							</div>

						</div>

					</div>

					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">

						<i class="fa fa-question-circle"></i>

					</a>

				</div>

				<div class="card-body">

					<div class="rounded-2 bg-lighter p-3 ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_marketing" 

						 data-options="{ldelim}{rdelim}">

						<div class="row">

							<div class="col-12 col-md-5 mb-2 mb-lg-0">

								<div class="d-flex align-items-center gap-3 text-fs-15 mb-2">

									<span class="w-px-200"><i class='bx bx-bar-chart-alt text-fs-26'></i> Ngân sách dự kiến</span>

									<span class="animate-bg w-px-100 rounded-2 h-px-15"></span>

								</div>

								<div class="d-flex align-items-center gap-3 text-fs-15">

									<span class="w-px-200 text-warning"><i class='bx bx-dollar-circle  text-fs-26'></i> Ngân sách thực tế</span>

									<span class="animate-bg w-px-100 rounded-2 h-px-15"></span>

								</div>

							</div>

							<div class="col-1 d-none d-lg-block border-end"></div>

							<div class="col-1 d-none d-lg-block"></div>

							<div class="col-12 col-md-5">

								<div class="d-flex align-items-center gap-3 text-fs-15 mb-2">

									<span class="w-px-200"><i class='bx bx-building text-primary text-fs-26' ></i> Công ty hỗ trợ</span>

									<span class="animate-bg w-px-100 rounded-2 h-px-15"></span>

								</div>

								<div class="d-flex align-items-center gap-3 text-fs-15">

									<span class="w-px-200"><i class='bx bx-user text-success text-fs-26' ></i> Sale chịu</span>

									<span class="animate-bg w-px-100 rounded-2 h-px-15"></span>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="clearfix"></div>

			<div class="card no-shadow">

				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">

					<h5 class="card-title mb-0">Giao dịch mới nhất</h5>

					<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">

						<i class="fa fa-question-circle"></i>

					</a>

				</div>

				<div class="card-body">

					<div class="table-container no-shadow text-nowrap overflow-x-auto">

						<table class="table table-bordered dragable " width="100%" cellpadding="0" cellspacing="0">

							<thead><tr>

								<th class="align-center bg-lighter h-px-35">Mã giao dịch</th>

								<th class="align-center bg-lighter h-px-35">Ngày cọc</th>

								<th class="align-center bg-lighter h-px-35">Sales bán</th>

								<th class="align-center bg-lighter h-px-35">Dự án</th>

								<th class="align-center bg-lighter h-px-35">Phân khu</th>

								<th class="align-center bg-lighter h-px-35">Loại hình</th>

								<th class="align-center bg-lighter h-px-35">Doanh số</th>

							</tr></thead>

							<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod=home&sub=dashboard&act=load_billing" 

								data-options='{ldelim}"call_from":"_DEPARTMENT"{rdelim}'>

								{section name=i loop=$list_preloaders max=12}

								<tr>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

								</tr>

								{/section}

							</tbody>

						</table>

					</div>

				</div>

			</div>

			<div class="card d-none mb-2">

				<div class="card-header position-relative">

					<div class="d-flex align-items-center justify-content-between">

						<h4 class="fw-bold mb-1"><span>Báo cáo tiếp khách</span></h4>

						<a href="javascript:void(0);" class="text-muted help_pop" title="Trợ giúp">

							<i class="fa fa-question-circle"></i>

						</a>

					</div>

				</div>

				<div id="holder_report_share" class="card-body{if $deviceType eq 'phone'} p-2{/if}">

					<div class="table-container no-shadow overflow-x-auto text-nowrap mb-2">		

						<table class="table table-striped table-bordered" width="100%" cellpadding="0" cellspacing="0">

							<thead><tr>

								<th width="30px" class="align-center text-center bg-lighter">STT</th>

								<th class="align-center bg-lighter" width="30%">Họ và tên</th>

								{if $deviceType ne 'phone'}

								<th width="15%" class="align-center text-center bg-lighter">Mã nhóm</th>

								<th class="align-center bg-lighter" width="20%">Vị trí</th>{/if}

								<th width="68px" class="align-center text-center bg-lighter">Số ảnh</th>

								<th class="align-center text-right bg-lighter" width="20%">Số tiền</th>

							</tr></thead>

							<tbody>

								{section loop=20 name=i start=1 step=1}

								<tr>

									<td class="text-left"><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

									<td class="text-left"><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td class="text-left"><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

									{if $deviceType ne 'phone'}

									<td class="text-left"><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

									<td class="text-left"><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>{/if}

									<td class="text-left"><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

									<td class="text-left"><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

								</tr>

								{/section}

							</tbody>

						</table>

					</div>

				</div>

			</div>

		</div>

		<div class="col-12 col-lg-4 col-xxl-3">

			<div class="sticky top-px-75">

				<div class="card mb-2 no-shadow">

					{assign var = gId value = $clsISO->getUniqid()}

					<div class="card-header">

						<h5 class="card-title mb-0">

							<svg class="animated-icon" width="22" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.1019 8.96657C18.9791 8.64576 18.7005 8.4104 18.3639 8.34302L13.8437 7.43845L12.0622 3.87587C11.8928 3.53699 11.5466 3.32288 11.1676 3.32288C10.7887 3.32288 10.4425 3.53699 10.2731 3.87587L8.49155 7.43845L3.97142 8.34302C3.63474 8.4104 3.35593 8.64576 3.23337 8.96657C3.1108 9.28738 3.16134 9.64871 3.3674 9.92362L6.09891 13.566L5.18703 18.1277C5.11549 18.4871 5.24611 18.8567 5.52786 19.0913C5.80936 19.3257 6.19657 19.3875 6.53691 19.2522L11.1676 17.4006L15.7962 19.2508C16.1368 19.386 16.524 19.3242 16.8055 19.0899C17.087 18.8552 17.2178 18.4856 17.1463 18.1262L16.2342 13.5646L18.9679 9.92362C19.1739 9.64871 19.2245 9.28738 19.1019 8.96657Z" fill="#F48120"></path></svg> 

							<span class="text-uppercase">TOP 5 cá nhân</span>

						</h5>

					</div>

					<div class="card-body">

						<div class="table-container no-shadow text-nowrap overflow-auto">

							<table class="table table-bordered dragable" width="100%" cellpadding="0" cellspacing="0">

								<thead class="position-sticky top-0 zindex-3"><tr>

									<th class="align-center bg-lighter h-px-35">STT</th>

									<th class="align-center bg-lighter h-px-35">Họ và tên</th>

									<th class="align-center bg-lighter h-px-35">Doanh số</th>

								</tr></thead>

								<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod=report&act=load_top_sales_group" 

									data-options='{ldelim}{rdelim}' gId="{$gId}">

									{section name=i loop=$list_preloaders max=5}

									<tr>

										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>

									</tr>

									{/section}

								</tbody>

							</table>

						</div>

					</div>

				</div>

				<div class="mb-2 ajax" data-url="{$PCMS_URL}/index.php?mod=report&act=load_report_share" 

					data-options='{ldelim}"show":"report_department"{rdelim}'>

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

									<span class="fs-14">

										<div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div>

									</span>

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

				<div class="card h-100 no-shadow box_share_group">

					<div class="card-header d-flex flex-wrap justify-content-between align-items-center">

						<h3 class="cart-title mb-0">Biểu đồ tiếp khách</h3>

						<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">

							<i class="fa fa-question-circle"></i>

						</a>

					</div>

					<div class="card-body">

						<div class="ajax pb-2 w-100 share_group" uid="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_share_group&department_id={$oneProfile.department_id}" data-options="{ldelim}{rdelim}">

							<div class="chartContainer d-flex align-items-center justify-content-center w-100 h-px-350">

								<span class="text-muted">Đang tải dữ liệu...</span>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>