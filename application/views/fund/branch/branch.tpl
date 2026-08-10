<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="w-100 d-flex flex-wrap align-items-center justify-content-between mb-4">
		<div class="lycYJcfXJY">
			<h2 class="fw-bold mb-0 fs-20">Tài chính chi nhánh</h2>
		</div>
		{assign var=gId value=$clsISO->getUniqid()}
		<div class="search d-flex flex-wrap align-items-center gap-1">	
			<div class="input-group w-px-350 flex-fill">
				<select class="form-control form-select search_field search_month" 
					name="company_id" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)"> 
					<option value="">Đơn vị</option>			
					{foreach from=$list_company item = _oI}
					<option{if $smarty.const._GROUP_COMPANY_FH_ID eq $_oI.setting_id} selected{/if} value="{$_oI.setting_id}">{$_oI.title}</option>
					{/foreach}
				</select>
				<select gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" data-field="year" name="year" 
					class="form-control js__search-year-field search_field form-select">
					{foreach from=$list_years item = _year}
						<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
					{/foreach}
				</select>
				<select gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" data-field="month" name="month" 
					class="form-control js__search-month-field search_field form-select">
					<option value="">Tháng</option>			
					{foreach from=$list_months item = _month}
						<option value="{$_month}">T{$_month}</option>
					{/foreach}
				</select>
				<select gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" data-field="date_type" name="date_type" 
					class="form-control js__search-date_type-field form-select">
					<option value="">Khoảng</option>
					<option value="7days">7 ngày qua</option>
					<option value="15days">15 ngày qua</option>
					<option value="30days">30 ngày qua</option>
				</select>
			</div>
			<div class="input-group w-auto flex-fill">
				<input type="date" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" class="form-control js__search-start_date-field 
				js__search-date-field search_field w-px-125" name="start_date" data-field="start_date" value="{$start_date}" />
				<input type="date" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" class="form-control js__search-end_date-field 
				js__search-date-field search_field w-px-125" name="end_date" data-field="end_date" value="{$end_date}" max="{$smarty.now|date_format:'%Y-%m-%d'}" />
			</div>
		</div>
	</div>
	<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_total_expense" data-options='{ldelim}{rdelim}' >
		<div class="form-row row-cols-1 row-col-md-2 row-cols-lg-5">
			<div class="col mb-2">
				<div class="card card-boder fund_box fund_debit h-100">
					<div class="card-body">
						<h3 class="text-nowrap fs-18 text-black mb-2">Tổng chi phí</h3>	
						<h5 class="mb-1 fs-3 fw-bold text-main">0đ</h5>
					</div>
				</div>
			</div>
			<div class="col mb-2">
				<div class="card card-boder fund_box fund_credit h-100">
					<div class="card-body">
						<h3 class="text-nowrap fs-18 text-black mb-2">Thay đổi so với kỳ trước </h3>	
						<h4 class="mb-1 fs-3 fw-bold text-success">0đ <span class="fs-6">(0%)</span></h4>
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i></span>
					</div>
				</div>
			</div>
			<div class="col mb-2">
				<div class="card card-boder fund_box fund_assets h-100">
					<div class="card-body">
						<h3 class="text-nowrap fs-18 text-black mb-2">Doanh thu</h3>	
						<h4 class="mb-1 fs-3 fw-bold text-success">0đ <span class="fs-6">(0%)</span></h4>
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i></span>
					</div>
				</div>
			</div>
			<div class="col mb-2 flex-fill">
				<div class="card card-boder fund_box fund_profit2 h-100">
					<div class="card-body">
						<h3 class="text-nowrap fs-18 text-black mb-2">Tỷ lệ chi phí/Doanh thu</h3>	
						<h4 class="mb-1 fs-3 fw-bold text-success">0đ <span class="fs-6">(0%)</span></h4>
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i></span>
					</div>
				</div>
			</div>
			<div class="col mb-2">
				<div class="card card-boder fund_box fund_danger h-100">
					<div class="card-body">
						<h3 class="text-nowrap fs-18 text-black mb-2">Lợi nhuận</h3>	
						<h4 class="mb-1 fs-3 fw-bold text-success">0đ <span class="fs-6">(0%)</span></h4>
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row row-cols-1">		
		<div class="col mb-2">
			<div class="card card-boder card-boder h-100">
				<div class="card-header">
					<div class="d-flex justify-content-between align-items-center gap-2 h-px-30">
						<h3 class="text-nowrap fs-18 text-black mb-0">Vùng kinh doanh</h3>	
					</div>
				</div>
				<div class="card-body">
					<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_report_regional" data-options='{ldelim}{rdelim}'>
						<div class="form-row row-cols-2 row-cols-lg-5">
							<div class="col mb-2 flex-flow">
								<div class="card card-boder no-shadow border h-100">
									<div class="card-body">									
										<h4 class="fs-16 text-black mb-2">Tổng thu bán hàng</h4>	
										<h5 class="mb-2 fs-4 fw-bold">44,3  tỷ</h5>
										<div class="">
											<span class="text-danger fw-bold">-13  tỷ </span>
											<span class="">so với kỳ trước</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col mb-2 flex-flow">
								<div class="card card-boder no-shadow border h-100">
									<div class="card-body">									
										<h4 class="fs-16 text-black mb-2">Các khoản giảm trừ</h4>	
										<h5 class="mb-1 fs-4 fw-bold">44,3  tỷ</h5>
										<div class="">
											<span class="text-success fw-bold">+13  tỷ </span>
											<span class="">so với kỳ trước</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col mb-2 flex-flow">
								<div class="card card-boder no-shadow border h-100">
									<div class="card-body">									
										<h4 class="fs-16 text-black mb-2">Doanh thu tài chính</h4>	
										<h5 class="mb-1 fs-4 fw-bold">44,3  tỷ</h5>
										<div class="">
											<span class="text-danger fw-bold">-13  tỷ </span>
											<span class="">so với kỳ trước</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col mb-2 flex-flow">
								<div class="card card-boder no-shadow border h-100">
									<div class="card-body">									
										<h4 class="fs-16 text-black mb-2">Thu nhập khác</h4>	
										<h5 class="mb-1 fs-4 fw-bold">44,3  tỷ</h5>
										<div class="">
											<span class="text-success fw-bold">+13  tỷ </span>
											<span class="">so với kỳ trước</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row row-cols-1 row-cols-lg-2">		
		<div class="col mb-2">
			<div class="card card-boder">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Chi phí văn phòng chi nhánh</h5>
				</div>
				<div class="card-body">
					<div class="load_time">
						<div gId="{$gId}" class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_branch_expense" data-options='{ldelim}{rdelim}'>
							<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col mb-2">
			<div class="card card-boder">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Cơ cấu chi phí</h5>
				</div>
				<div class="card-body">
					<div gId="{$gId}" class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_chart_branch" data-options='{ldelim}{rdelim}'>
						<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card card-boder">
		<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
			<h5 class="card-title mb-2 mb-lg-0 me-2">Danh sách thu chi</h5>
		</div>
		<div class="card-body">
			<div class="table-container overflow-x-auto text-nowrap no-shadow">
				<table cellpadding="0" cellspacing="0" class="table dragable" width="100%">
					<thead><tr>
						<th class="align-center text-center h-px-40 bg-lighter" width="3%">STT</th>
						<th class="align-center text-right h-px-40 bg-lighter">Ngày hạch toán</th>
						<th class="align-center text-right h-px-40 bg-lighter">Ngày chứng từ</th>
						<th class="align-center h-px-40 bg-lighter">Số Chứng từ</th>
						<th class="align-center h-px-40 bg-lighter">Diễn giải</th>
						<th class="align-center h-px-40 bg-lighter">Tài khoản</th>
						<th class="align-center h-px-40 bg-lighter text-right">Phát sinh nợ</th>
						<th class="align-center h-px-40 bg-lighter text-right">Phát sinh có</th>
						<th class="align-center h-px-40 bg-lighter">Dự án</th>
						<th class="align-center h-px-40 bg-lighter">Tên đơn vị</th>
					</tr></thead>
					<tbody class="ajax load_time" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=list_ops_cost" data-options='{ldelim}"type":"branch","per_page":"10"{rdelim}' >
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
				<div id="pager_fund"></div>
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

