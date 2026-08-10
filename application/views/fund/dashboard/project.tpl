<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="w-100 d-flex flex-wrap align-items-center justify-content-between mb-4">
		<div class="lycYJcfXJY">
			<h2 class="fw-bold mb-0 fs-20">Tài chính dự án</h2>
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
			<select class="form-control form-select search_field" name="project" gId="{$gId}" onChange="$Core.fund.dashboard.reload(this,event)">
				<option value="0">--Dự án--</option>
			</select>
		</div>
	</div>
	<div class="form-row row-cols-2 row-cols-sm-2 row-cols-md-4 row-cols-lg-4">
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Doanh thu</h3>	
					<h5 class="mb-1 fs-3 fw-bold text-black">184,3 tỷ</h5>
					<div class="sub text-success">▲ +22.2% so với tháng trước</div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10">💰</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Chi phí</h3>	
					<h4 class="mb-1 fs-3 fw-bold text-black">50 tỷ</h4>
					<div class="sub text-danger">▼ -5.0% so với tháng trước</div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10">💸</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Lợi nhuận</h3>	
					<h4 class="mb-1 fs-3 fw-bold text-black">150 tỷ</h4>
					<div class="sub text-danger">▼ -5.0% so với tháng trước</div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10">📈</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Dòng tiền</h3>	
					<h4 class="mb-1 fs-3 fw-bold text-black">100 triệu</h4>
					<div class="sub text-danger">▼ -5.0% so với tháng trước</div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10">💵</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Marketing</h3>	
					<h4 class="mb-1 fs-3 fw-bold text-black">100 triệu</h4>
					<div class="sub text-danger">▼ -5.0% so với tháng trước</div>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10">📢</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Hoa hồng nhận</h3>	
					<h4 class="mb-1 fs-3 fw-bold text-black">100 triệu</h4>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10">🤝</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Hoa hồng trả</h3>	
					<h4 class="mb-1 fs-3 fw-bold text-black">100 triệu</h4>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10">👤</span>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Quỹ ôm</h3>	
					<h4 class="mb-1 fs-3 fw-bold text-black">25 tỷ <small>(50 căn)</small></h4>
				</div>
				<span class="icon_kpi position-absolute top-10 right-10">📦</span>
			</div>
		</div>		
	</div>
	<div class="form-row row-cols-1 row-cols-lg-2">		
		<div class="col mb-2">
			<div class="card">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Doanh thu và chi phí</h5>
				</div>
				<div class="card-body">
					<div class="load_time">
						<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_project_revenue_expense" data-options='{ldelim}{rdelim}'>
							<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<div class="col mb-2">
			<div class="card">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Cơ cấu chi phí</h5>
				</div>
				<div class="card-body">
					<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_total_expense_project" data-options='{ldelim}{rdelim}'>
						<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Dòng tiền</h5>
				</div>
				<div class="card-body">
					<div class="load_time">
						<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_project_chart&type=cash" data-options='{ldelim}{rdelim}'>
							<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Hoa hồng</h5>
				</div>
				<div class="card-body">
					<div class="load_time">
						<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_project_chart&type=commission" data-options='{ldelim}{rdelim}'>
							<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card mb-2">
		{assign var = gId value = $clsISO->getUniqid()}
		<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
			<h5 class="card-title mb-2 mb-lg-0 me-2">Chi tiết dự án</h5>
		</div>
		<div class="card-body">
			<div class="table-container overflow-auto">
				<table class="table" cellpadding="0" cellspacing="0" width="100%">
					<thead><tr>
						<th class="align-center text-center h-px-40 bg-lighter" width="3%">STT</th>
						<th class="align-center h-px-40 bg-lighter">Dự án</th>
						<th class="align-center h-px-40 bg-lighter">Doanh thu</th>
						<th class="align-center h-px-40 bg-lighter">Chi phí</th>
						<th class="align-center h-px-40 bg-lighter">Lợi nhuận</th>
						<th class="align-center h-px-40 bg-lighter">Marketing</th>
						<th class="align-center h-px-40 bg-lighter">HH nhận</th>
						<th class="align-center h-px-40 bg-lighter">HH trả</th>
						<th class="align-center h-px-40 bg-lighter">Hàng ôm</th>
					</tr></thead>
					<tbody>
						<tr>
							<td class="align-center text-center">1</td>
							<td>MLS</td>
							<td>500 tỷ</td>
							<td>300 tỷ</td>
							<td>200 tỷ</td>
							<td>500 triệu</td>
							<td>150 tỷ</td>
							<td>100 tỷ</td>
							<td>50 tỷ (50 căn)</td>
						</tr>
						<tr>
							<td class="align-center text-center">2</td>
							<td>MTS</td>
							<td>500 tỷ</td>
							<td>300 tỷ</td>
							<td>200 tỷ</td>
							<td>500 triệu</td>
							<td>150 tỷ</td>
							<td>100 tỷ</td>
							<td>50 tỷ (50 căn)</td>
						</tr>
						<tr>
							<td class="align-center text-center">3</td>
							<td>Parkland</td>
							<td>500 tỷ</td>
							<td>300 tỷ</td>
							<td>200 tỷ</td>
							<td>500 triệu</td>
							<td>150 tỷ</td>
							<td>100 tỷ</td>
							<td>50 tỷ (50 căn)</td>
						</tr>
						<tr>
							<td class="align-center text-center">4</td>
							<td>MGC</td>
							<td>500 tỷ</td>
							<td>300 tỷ</td>
							<td>200 tỷ</td>
							<td>500 triệu</td>
							<td>150 tỷ</td>
							<td>100 tỷ</td>
							<td>50 tỷ (50 căn)</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="card">
		{assign var = gId value = $clsISO->getUniqid()}
		<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
			<h5 class="card-title mb-2 mb-lg-0 me-2">Danh sách thu chi</h5>
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

