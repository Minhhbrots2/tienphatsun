<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
		<div class="oOGXbGhDZt mb-2 mb-lg-0">
			<h4 class="fw-bold mb-0"><span>Báo cáo Marketing</span></h4>
			<p class="text-muted mb-0">Tổng hợp ngân sách Marketing {$smarty.const.BRAND_NAME}</p>
		</div>
		<div class="xaQwJlqAyc">
			<div class="search-top d-flex{if $deviceType eq 'phone'} w-100{/if} gap-1 align-items-center">
				{assign var = gId value = $clsISO->getUniqid()}
				<select class="form-control form-select search_field w-px-125 date_type" data-field="date_type" 
					onChange="$Core.marketing.do_change(this, event);">
					<option value="_month">Theo tháng</option>
					<option value="_quarter">Theo quý</option>
					<option value="_year">Theo năm</option>
				</select>
				<input type="month" class="js__date-field form-control search_field" value="{$current_month}" data-field="month" 
					onChange="$Core.marketing.do_change(this, event);" />
				<select class="form-control form-select"></select>
				<div class="dropdown">
					<button type="button" class="btn btn-icon btn-outline-default hide-arrow dropdown-toggle" 
						data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-plus"></i></button>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item cursor-pointer" onClick="$Core.marketing.open_import(this, event)" tp="budget_register">{$clsISO->makeIcon('bx-upload','Import đăng ký')}</a></li>
						<hr class="dropdown-divider" />
						<li><a class="dropdown-item text-danger cursor-pointer" onClick="$Core.marketing.open_import(this, event)" tp="spending">{$clsISO->makeIcon('bx-upload','Import thực chạy')}</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>	
	<hr />
	<div class="form-row">
		<div class="col-12 col-lg-9">
			<div class="briefs gap-2 gap-xxl-2 d-flex flex-wrap mb-2 ajax" 
				data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_overview" data-options="{ldelim}{rdelim}">
				<div class="brief-item bg-orange">
					<div class="d-flex align-items-center">
						<div class="w-px-50">
							<i class='bx bx-wallet-alt' style="font-size:42px"></i>
						</div>
						<div class="d-flex flex-column">
							<p class="fs-16 mb-2"> Tổng chi</p>
							<h3 class="fs-5 animate-bg w-px-100 h-px-15 rounded-2"></h3>
						</div>
					</div>
				</div>
				<div class="brief-item  bg-azure">
					<p class="fs-16 mb-2"><i class='bx bx-captions'></i> Công ty hỗ trợ</p>
					<h3 class="fs-5 animate-bg w-px-100 h-px-15 rounded-2"></h3>
				</div>
				<div class="brief-item bg-cyan" bis_skin_checked="1">
					<p class="fs-16 mb-2"><i class='bx bx-user'></i> Sale chi</p>
					<h3 class="fs-5 animate-bg w-px-100 h-px-15 rounded-2"></h3>
				</div>
				<div class="brief-item bg-danger" bis_skin_checked="1">
					<p class="fs-16 mb-2"><i class='bx bx-pie-chart'></i> Tổng chi</p>
					<h3 class="fs-5 animate-bg w-px-100 h-px-15 rounded-2"></h3>
				</div>
			</div>
			<div class="form-row mt-1 mb-2">
				<div class="col-12 col-md-5">
					<div class="card h-100 no-shadow">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between">
								<h5 class="card-title mb-0">Phân bổ ngân sách theo kênh</h5>
								<button data-toggle="ripple" class="btn btn-sm rounded-pill btn-link btn-icon text-muted">
									<i class='bx bx-dots-horizontal-rounded'></i>
								</button>
							</div>
						</div>
						<div class="card-body h-px-250 ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_budget_channel" 
							data-options="{ldelim}{rdelim}">
							<div class="d-flex align-items-center gap-3">
								<div class="p-3">
									<div class="animate-bg rounded-pill w-px-200 h-px-200"></div>
								</div>
								<div style="width: calc(100% - 235px)" class="d-flex flex-column">
									<div class="animate-bg rounded-2 h-px-15 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-15 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-15 w-100 mb-2"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-7">
					<div class="card h-100 no-shadow">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between">
								<h5 class="card-title mb-0">Phân bổ ngân sách theo dự án</h5>
								<button data-toggle="ripple" class="btn btn-sm rounded-pill btn-link btn-icon text-muted">
									<i class='bx bx-dots-horizontal-rounded'></i>
								</button>
							</div>
						</div>
						<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_budget_project" 
							data-options="{ldelim}{rdelim}">
							<div class="d-flex align-items-center gap-3">
								<div class="p-3 w-100">
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-2020 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="w-100 mb-2">
				<div class="card no-shadow">
					<div class="card-header">
						<div class="d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Phân bổ ngân sách vùng</h5>
							<button data-toggle="ripple" class="btn btn-sm rounded-pill btn-link btn-icon text-muted">
								<i class='bx bx-dots-horizontal-rounded'></i>
							</button>
						</div>
					</div>
					<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_area" 
						data-options="{ldelim}{rdelim}">
						<div class="row">
							<div class="col-12 col-md-6">
								<div class="py-3">
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
									<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="border overflow-hidden rounded-2">
									<table class="table text-nowrap">
										<thead><tr>
											<th class="h-px-35">Vùng KD</th>
											<th class="h-px-35">Ngân sách</th>
											<th class="h-px-35">Tổng chi</th>
											<th class="h-px-35">CTY hỗ trợ</th>
											<th class="h-px-35">Sale chịu</th>
										</tr></thead>
										<tbody>
											{section name=i loop=$list_preloaders max=10}
											<tr>
												<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
												<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
												<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
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
				</div>
			</div>
			<div class="card no-shadow">
				<div class="card-header">
					<div class="d-flex align-items-center justify-content-between">
						<h5 class="card-title mb-0">Ngân sách chi tiết</h5>
						<a href="{$PCMS_URL}/marketing/dang-ky.html" data-toggle="ripple" 
							class="btn btn-sm rounded-pill btn-link btn-icon text-muted"><i class='bx bx-link-external'></i></a>
					</div>
				</div>
				<div class="card-body">
					<div class="table-container no-shadow dragscroll overflow-x-auto text-nowrap">
						<table cellpadding="0" cellspacing="0" class="table table-bordered table-striped text-nowrap" width="100%">
							<thead><tr>
								<th width="3%" class="align-center bg-body text-center">STT</th>
								<th class="align-center bg-body h-px-35 text-left">Phòng ban</th>
								<th class="align-center bg-body h-px-35 text-left">Tên Sale</th>
								<th class="align-center bg-body h-px-35 text-left">Dự án</th>
								<th class="align-center bg-body h-px-35 text-right">Dự kiến</th>
								<th class="align-center bg-body h-px-35 text-right">Thực tế</th>
								<th class="align-center bg-body h-px-35 text-center w-px-100">% Chi</th>
								<th class="align-center bg-body h-px-35 text-right">Công ty HT</th>
								<th class="align-center bg-body h-px-35 text-right">Sale chịu</th>
							</tr></thead>
							<tbody class="holder_tbl_budgets" data-options="{ldelim}{rdelim}">
								{section name=i loop=$list_preloaders max=10}
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
					<div class="pager_tbl_budgets easyui-pagination"></div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-3">
			<div class="sticky top-px-75">
				<div class="card no-shadow">
					<div class="card-header">
						<div class="d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Tổng kết chi phí Marketing</h5>
						</div>
					</div>
					<div class="card-body" data-options="{ldelim}{rdelim}">
						<div class="holder_summary">
							<div class="min-height-500 d-flex justify-content-center align-items-center">
								<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw text-muted"></i>
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
	$(() => $Core.marketing._autoload());
</script>
<style type="text/css">
	.fb_ads .bxl-facebook{
		background: #173a83;
		border-radius: 50%;
		color: var(--bs-white);
		padding: 3px;
		font-size: 10px;
		transform: translateY(-1px);
	}
	.gg_ads .bxl-google{
		background: #ff4141;
		border-radius: 50%;
		color: var(--bs-white);
		padding: 3px;
		font-size: 10px;
		transform: translateY(-1px);
	}
	.zalo_ads .re__icon-zalo-white{
		background: #35e9bc;
		border-radius: 50%;
		color: var(--bs-white);
		padding: 3px;
		font-size: 10px;
		transform: translateY(-1px);
	}
	.tiktok_ads .bxl-tiktok{
		background: #00d5d7;
		border-radius: 50%;
		color: var(--bs-white);
		padding: 3px;
		font-size: 10px;
		transform: translateY(-1px);
	}
</style>
{/literal}