<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="w-100 d-flex flex-wrap align-items-center justify-content-between mb-4">
		<div class="lycYJcfXJY">
			<h2 class="fw-bold mb-0 fs-20">Tài chính thuế</h2>
		</div>
		<div class="input-group w-px-350 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
			<select class="form-control form-select search_field" name="month" gId="{$gId}" onChange="$Core.fund.dashboard.reload(this,event)"> 
				<option value="">Tháng</option>			
				{foreach from=$list_months item = _month}
				<option value="{$_month}">Tháng {$_month}</option>
				{/foreach}
			</select>
			<select class="form-control form-select search_field" name="year" gId="{$gId}" onChange="$Core.fund.dashboard.reload(this,event)">
				{foreach from=$list_years item = _year}
				<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="form-row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-6">
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Tổng thuế</h3>	
					<h5 class="mb-1 fs-3 fw-bold text-black">25 tỷ</h5>
					<div class="sub text-success d-flex justify-content-between align-items-center gap-2 fs-12"><span>Đã nộp</span> <span class="">20 tỷ (80%)</span></div>
					<div class="sub text-danger d-flex justify-content-between align-items-center gap-2 fs-12"><span>Chưa nộp</span> <span class="">5 tỷ (20%)</span></div>
					<div class="progress bg-label-warning" style="height: 6px;">
                      <div class="progress-bar bg-warning" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10" style="opacity:.5">💰</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Tổng phạt</h3>	
					<h5 class="mb-1 fs-3 fw-bold text-black">2,5 tỷ</h5>
					<div class="sub text-success d-flex justify-content-between align-items-center gap-2 fs-12"><span>Đã nộp</span> <span class="">2 tỷ (80%)</span></div>
					<div class="sub text-danger d-flex justify-content-between align-items-center gap-2 fs-12"><span>Chưa nộp</span> <span class="">500 triệu (20%)</span></div>
					<div class="progress bg-label-warning" style="height: 6px;">
                      <div class="progress-bar bg-warning" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10" style="opacity:.5">⚠️</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Thuế TNDN</h3>	
					<h5 class="mb-1 fs-3 fw-bold text-black">25 tỷ</h5>
					<div class="sub text-success d-flex justify-content-between align-items-center gap-2 fs-12"><span>Đã nộp</span> <span class="">2 tỷ (80%)</span></div>
					<div class="sub text-danger d-flex justify-content-between align-items-center gap-2 fs-12"><span>Chưa nộp</span> <span class="">500 triệu (20%)</span></div>
					<div class="progress bg-label-warning" style="height: 6px;">
                      <div class="progress-bar bg-warning" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10" style="opacity:.5">🏢</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Thuế TNCN</h3>	
					<h5 class="mb-1 fs-3 fw-bold text-black">25 tỷ</h5>
					<div class="sub text-success d-flex justify-content-between align-items-center gap-2 fs-12"><span>Đã nộp</span> <span class="">2 tỷ (80%)</span></div>
					<div class="sub text-danger d-flex justify-content-between align-items-center gap-2 fs-12"><span>Chưa nộp</span> <span class="">500 triệu (20%)</span></div>
					<div class="progress bg-label-warning" style="height: 6px;">
                      <div class="progress-bar bg-warning" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10" style="opacity:.5">👤</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">VAT</h3>	
					<h5 class="mb-1 fs-3 fw-bold text-black">25 tỷ</h5>
					<div class="sub text-success d-flex justify-content-between align-items-center gap-2 fs-12"><span>Đã nộp</span> <span class="">2 tỷ (80%)</span></div>
					<div class="sub text-danger d-flex justify-content-between align-items-center gap-2 fs-12"><span>Chưa nộp</span> <span class="">500 triệu (20%)</span></div>
					<div class="progress bg-label-warning" style="height: 6px;">
                      <div class="progress-bar bg-warning" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10" style="opacity:.5">🧾</span>
			</div>
		</div>		
		<div class="col mb-2">
			<div class="card fund_box h-100" >
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Thuế/Doanh thu</h3>	
					<h5 class="mb-1 fs-2 fw-bold text-black text-center mt-4">13%</h5>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10" style="opacity:.5">💵</span>
			</div>
		</div>	
	</div>
	<div class="form-row row-cols-1 row-cols-lg-3">		
		<div class="col mb-2">
			<div class="card h-100">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Trạng thái nghĩa vụ thuế</h5>
				</div>
				<div class="card-body">
					<div class="table-container overflow-auto" style="max-height: 300px">
						<table class="table" cellpadding="0" cellspacing="0" width="100%">
							<thead class="position-sticky top-0 zindex-3"><tr>
								<th class="align-center h-px-40 bg-lighter">Loại</th>
								<th class="align-center h-px-40 bg-lighter">Phải nộp</th>
								<th class="align-center h-px-40 bg-lighter">Đã nộp</th>
								<th class="align-center h-px-40 bg-lighter">Chưa nộp</th>
								<th class="align-center h-px-40 bg-lighter">Tỷ lệ nộp</th>
							</tr></thead>
							<tbody>
								<tr>
									<td class="text-nowrap">TNDN</td>
									<td>500 tỷ</td>
									<td>300 tỷ</td>
									<td>200 tỷ</td>
									<td>60%</td>
								</tr>
								<tr>
									<td class="text-nowrap">TNCN</td>
									<td>500 tỷ</td>
									<td>300 tỷ</td>
									<td>200 tỷ</td>
									<td>60%</td>
								</tr>								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card h-100">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">VAT</h5>
				</div>
				<div class="card-body">
					<div class="table-container overflow-auto" style="max-height: 300px">
						<table class="table" cellpadding="0" cellspacing="0" width="100%">
							<thead class="position-sticky top-0 zindex-3"><tr>
								<th class="align-center h-px-40 bg-lighter">Kỳ</th>
								<th class="align-center h-px-40 bg-lighter">Đầu ra</th>
								<th class="align-center h-px-40 bg-lighter">Đầu vào</th>
								<th class="align-center h-px-40 bg-lighter">Phải nộp</th>
								<th class="align-center h-px-40 bg-lighter">Ngày nộp</th>
								<th class="align-center h-px-40 bg-lighter">Hạn nộp</th>
							</tr></thead>
							<tbody>
								<tr>
									<td class="text-nowrap">Tháng 1</td>
									<td>500 tỷ</td>
									<td>300 tỷ</td>
									<td>200 tỷ</td>
									<td>30/01/2026</td>
									<td>30/03/2026</td>
								</tr>
								<tr>
									<td class="text-nowrap">Tháng 2</td>
									<td>500 tỷ</td>
									<td>300 tỷ</td>
									<td>200 tỷ</td>
									<td>30/01/2026</td>
									<td>30/03/2026</td>
								</tr>
								<tr>
									<td class="text-nowrap">Tháng 3</td>
									<td>500 tỷ</td>
									<td>300 tỷ</td>
									<td>200 tỷ</td>
									<td>30/01/2026</td>
									<td>30/03/2026</td>
								</tr>							
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>	
		<div class="col mb-2">
			<div class="card h-100">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Thuế TNCN</h5>
				</div>
				<div class="card-body">
					<div class="table-container overflow-auto" style="max-height: 300px">
						<table class="table" cellpadding="0" cellspacing="0" width="100%">
							<thead class="position-sticky top-0 zindex-3"><tr>
								<th class="align-center h-px-40 bg-lighter">Vùng KD/PKD</th>
								<th class="align-center h-px-40 bg-lighter">Lương</th>
								<th class="align-center h-px-40 bg-lighter">Thuế</th>
								<th class="align-center h-px-40 bg-lighter">Tạm nộp</th>
								<th class="align-center h-px-40 bg-lighter">Ngày nộp</th>
							</tr></thead>
							<tbody>
								<tr>
									<td class="text-nowrap">Vùng 1</td>
									<td>2 tỷ</td>
									<td>200 tỷ</td>
									<td>100 tỷ</td>
									<td>30/01/2026</td>
								</tr>
								<tr>
									<td class="text-nowrap">Vùng 1</td>
									<td>2 tỷ</td>
									<td>200 tỷ</td>
									<td>100 tỷ</td>
									<td>30/01/2026</td>
								</tr>
								<tr>
									<td class="text-nowrap">Vùng 1</td>
									<td>2 tỷ</td>
									<td>200 tỷ</td>
									<td>100 tỷ</td>
									<td>30/01/2026</td>
								</tr>							
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<div class="card">
		{assign var = gId value = $clsISO->getUniqid()}
		<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
			<h5 class="card-title mb-2 mb-lg-0 me-2">Danh sách chi tiết</h5>
		</div>
		<div class="card-body">
			<div id="table_report" class="table-container overflow-x-auto">
				<table class="table" cellpadding="0" cellspacing="0" width="100%">
					<thead><tr>
						<th class="align-center text-center h-px-40 bg-lighter" width="3%">STT</th>
						<th class="align-center text-right h-px-40 bg-lighter">Ngày {if $gr eq 'THUCTHU'}thu{else}chi{/if}</th>
						<th class="align-center text-right h-px-40 bg-lighter">Ngày hạch toán</th>
						<th class="align-center h-px-40 bg-lighter">Số Chứng từ</th>
						<th class="align-center h-px-40 bg-lighter">Diễn giải</th>
						<th class="align-center h-px-40 bg-lighter">Tài khoản quỹ</th>
						<th class="align-center h-px-40 bg-lighter">Số tiền</th>
					</tr></thead>
					<tbody>
						{section name=i loop=$list_preloaders max = 12}
						<tr>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.menu-vertical.bg-menu-theme{
		background-image: unset !important;
	}
	.input-group-date:before{
		top:8px;
	}
	.input-group-date > .isodaterangepicker{
		line-height: 1.83;
	}
	.freeze-table {
        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
	}
	.freeze-table .table{
		margin-bottom:0;
		min-width:1200px;
		max-width:16000px;
	}
	.freeze-table .table th{
		line-height:16px;
		vertical-align:middle;
	}
	.icon_kpi{
		font-size: 40px
	}
	@media screen and (min-width:648px){
		.freeze-table .table tr>th:nth-child(3),
		.freeze-table .trBilling td:nth-child(3){
			border-right:1px solid #DDD;
		}
	}
	@media screen and (max-width:991px){		
		.icon_kpi{
			font-size: 30px
		}
	}
	@media screen and (max-width:648px){
		.freeze-table .table tr>th:nth-child(1),
		.freeze-table .trBilling td:nth-child(1){
			border-right:1px solid #DDD;
		}
	}
	.textbox{
		border-radius:4px;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		-khtml-border-radius:4px;
	}
	.ui-autocomplete{
		z-index:9 !important;
		background:var(--bs-white);
		max-height:400px;
		overflow-y:auto;
		border-radius:4px;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		-khtml-border-radius:4px;
	}
	.ui-menu-item .ui-menu-item-wrapper{
		padding: 5px 10px !important;
	}
</style>
{/literal}

