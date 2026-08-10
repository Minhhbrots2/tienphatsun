<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="w-100 d-flex flex-wrap align-items-center justify-content-between mb-4">
		<div class="lycYJcfXJY">
			<h2 class="fw-bold mb-0 fs-20">Tài chính chi nhánh</h2>
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
			<select class="form-control form-select search_field" name="office_id" gId="{$gId}" onChange="$Core.fund.dashboard.reload(this,event)">
				<option value="0">--Chi nhánh--</option>
				{$clsSetting->getSelectBySetting("_OFFICE",$office_id,"",1)}
			</select>
		</div>
	</div>
	<div class="form-row row-cols-1 row-col-md-2 row-cols-lg-5">
		<div class="col mb-2">
			<div class="card fund_box fund_debit h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Tổng chi phí</h3>	
					<h5 class="mb-1 fs-3 fw-bold text-main">184,3 tỷ</h5>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box fund_credit h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Thay đổi so với kỳ trước </h3>	
					<h4 class="mb-1 fs-3 fw-bold text-warning">-100 triệu <span class="fs-6">(-10%)</span></h4>
					<span class="text-success fw-bold"><i class="bx bx-caret-up"></i> giảm tốt</span>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box fund_danger h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Chi nhánh vượt ngân sách</h3>	
					<h4 class="mb-1 fs-4 fw-bold text-info"><span class="fs-3">3</span> chi nhánh</h4>
					<span class="text-danger fw-bold"><i class="bx bx-info-square"></i> cảnh báo</span>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card fund_box fund_assets h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Doanh thu</h3>	
					<h4 class="mb-1 fs-3 fw-bold text-warning">184,3 tỷ</h4>
					<div class="">
						<span class="text-danger fw-bold"><i class="bx bx-caret-down"></i>12,8% </span>
						<span class="">so với kỳ trước</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2 flex-fill">
			<div class="card fund_box fund_profit2 h-100">
				<div class="card-body">
					<h3 class="text-nowrap fs-18 text-black mb-2">Tỷ lệ chi phí/Doanh thu</h3>	
					<h4 class="mb-1 fs-3 fw-bold text-success">40%</h4>
					<span class="text-warning fw-bold"><i class="bx bx-check-square"></i> trung bình</span>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row row-cols-1 row-cols-lg-2">		
		<div class="col mb-2">
			<div class="card">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Chi phí chi nhánh</h5>
				</div>
				<div class="card-body">
					<div class="load_time">
						<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_branch_expense" data-options='{ldelim}{rdelim}'>
							<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card h-100">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Hiệu quả chi nhánh</h5>
				</div>
				<div class="card-body">
					<table class="table" cellpadding="0" cellspacing="0" width="100%">
						<thead><tr>
							<th class="align-center text-center h-px-40 bg-lighter" width="3%">STT</th>
							<th class="align-center h-px-40 bg-lighter">Chi nhánh</th>
							<th class="align-center h-px-40 bg-lighter">Doanh thu</th>
							<th class="align-center h-px-40 bg-lighter">Chi phí</th>
							<th class="align-center h-px-40 bg-lighter">Lợi nhuận</th>
							<th class="align-center h-px-40 bg-lighter text-center">Margin</th>
							<th class="align-center h-px-40 bg-lighter text-center">Trạng thái</th>
						</tr></thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Chi nhánh 1</td>
								<td>500 triệu</td>
								<td>300 triệu</td>
								<td>200 triệu</td>
								<td class="text-center text-success">40%</td>
								<td class="text-center text-success">Tốt</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Chi nhánh 2</td>
								<td>700 triệu</td>
								<td>650 triệu</td>
								<td>50 triệu</td>
								<td class="text-center text-warning">7.1%</td>
								<td class="text-center text-warning">Cảnh báo</td>
							</tr>
							<tr>
								<td>3</td>
								<td>Chi nhánh 3</td>
								<td>300 triệu</td>
								<td>400 triệu</td>
								<td>-100 triệu</td>
								<td class="text-center text-danger">-25%</td>
								<td class="text-center text-danger">Báo động</td>
							</tr>
						</tbody>
					</table>
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
					<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_chart_branch" data-options='{ldelim}{rdelim}'>
						<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
					</div>
				</div>
			</div>
		</div>	
		<div class="col mb-2">
			<div class="card">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Chi phí theo tháng</h5>
				</div>
				<div class="card-body">
					<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_chart_month_expense" data-options='{ldelim}{rdelim}'>
						<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
					</div>
				</div>
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
	@media screen and (min-width:648px){
		.freeze-table .table tr>th:nth-child(3),
		.freeze-table .trBilling td:nth-child(3){
			border-right:1px solid #DDD;
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

