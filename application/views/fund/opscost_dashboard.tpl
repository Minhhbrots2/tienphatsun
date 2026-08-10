<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between mb-2">
		<div class="lycYJcfXJY">
			<h4 class="fw-bold mb-1">Chi phí vận hành</h4>
			<span class="text-muted">Tổng quan chi vận hành</span>
		</div>
		<div class="cBSpMCSbVA">
			<div class="d-flex justify-content-end gap-2 algin-items-center">				
				<div class="p-right ox:w-100">					
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="input-group w-px-150 ox:w-100 d-flex" role="group" aria-label="Lọc">
						<select class="form-control form-select search_field search_month" name="month" gId="{$gId}" 
							onChange="$Core.ops_cost.reloadAll(this,event)"> 
							<option value="">Tháng</option>			
							{foreach from=$list_months item = _month}
							<option value="{$_month}"{if $_month eq $current_month} selected{/if}>T{$_month}</option>
							{/foreach}
						</select>
						<select class="form-control form-select search_field search_year" name="year" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)">
							{foreach from=$list_years item = _year}
							<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="mb-2 card">
		<div class="card-body">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="form-row row-cols-lg-5 ajax" data-url="{$PCMS_URL}/index.php?mod=fund&act=get_opscost_total" 
				gId="{$gId}" data-options='{ldelim}{rdelim}'>
				{foreach from=$list_opscost_blocks item = _OI key = _OK}
				<div class="col mb-2 mb-lg-0">
					<div class="p-3 rounded-2 fund_box fund_{$_OK} relative" style="background-color:{$_OI.bgcolor}">
						<h5 class="mb-1 fs-4 fw-bold text-white">0.000 {$clsISO->getRate()}</h5>
						<hr class="w-px-100 my-2" />
						<span class="text-white text-nowrap">{$_OI.title}</span>		
					</div>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
	<div class="form-group mb-2">
		<div class="card">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
				<h5 class="card-title mb-2 mb-lg-0 me-2">Chi phí vận hàng văn phòng</h5>
			</div>
			<div class="card-body">
				<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=office_operating_cost_stats" data-options='{ldelim}{rdelim}'>
					<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-12 col-md-7 mb-2 mb-lg-0">
			<div class="card h-100">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Chi vận hàng tháng {$smarty.now|date_format:"%m/%Y"}</h5>
				</div>
				<div class="card-body">
					<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_chart_month_opscost" data-options='{ldelim}{rdelim}'>
						<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-5">
			<div class="card h-100">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Chi vận hàng <span class="badge bg-warning">5 năm</span> gần đây</h5>
				</div>
				<div class="card-body">
					<div gId="{$gId}" class="ajax not-reload" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_chart_year_opscost" data-options='{ldelim}{rdelim}'>
						<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="form-row mb-2">
		<div class="col-12 col-lg-6 mb-2 mb-lg-0">
			<div class="card">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Chi vận hành theo loại</h5>
				</div>
				<div class="card-body">
					<div class="load_time">
						<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_ops_cost_category" data-options='{ldelim}{rdelim}'>
							<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="card">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Chi vận hàng theo phòng ban</h5>
				</div>
				<div class="card-body">
					<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_chart_department_opscost" data-options='{ldelim}{rdelim}'>
						<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12"><div class="card no-shadow">
			<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
				<h5 class="card-title mb-2 mb-lg-0 me-2">Khoản chi vận hành mới nhất</h5>
				<a href="{$clsISO->getLink('chart_operation_fee')}" data-toggle="ripple" class="btn btn-icon btn-sm rounded-pill btn-link" >
					<i class='bx text-fs-12 bx-link-external'></i>
				</a>
			</div>
			<div class="card-body">
				<div class="table-container overflow-x-auto text-nowrap no-shadow table-container2">
					<table cellpadding="0" cellspacing="0" class="table dragable" width="100%">
						<thead><tr>
							<th class="align-center bg-lighter h-px-35" width="120px">Ngày chi</th>
							<th class="align-center bg-lighter text-left h-px-35">Mã chi phí</th>
							<th class="align-center bg-lighter bg-lighter h-px-35" width="200px">Nội dung chi</th>
							<th class="align-center bg-lighter bg-lighter h-px-35">Loại chi phí</th>
							<th class="align-center bg-lighter h-px-35" width="10%">Phòng ban</th>
							<th class="align-center bg-lighter text-left h-px-35">Người đề xuất</th>
							<th class="align-center bg-lighter text-left h-px-35">Người duyệt</th>
							<th class="bg-lighter h-px-35" width="10%">Số tiền (VNĐ)</th>
							<th class="align-center bg-lighter h-px-35" width="10%">PT. thanh toán</th>
							<th class="align-center bg-lighter h-px-35" width="10%">Tài khoản nhận</th>
							<th class="bg-lighter text-center h-px-35" width="10%">Tình trạng</th>
						</tr>
						</thead>
						<tbody class="ajax load_time " gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=list_ops_cost" data-options='{ldelim}"type":"dashboard","per_page":"10"{rdelim}'>
							{section name=i loop=$list_preloaders max=30}
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
				<div id="pager_fund"></div>
			</div></div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
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

