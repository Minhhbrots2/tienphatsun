{assign var=gId value=$clsISO->getUniqid()}
<div class="d-flex gap-2 justify-content-end align-items-center mb-3">
	<label class="text-nowrap">Lọc theo:</label> 
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
		{if $_type eq 'total_branch'}
			<select class="form-control form-select search_field" name="office_id" gId="{$gId}" onChange="$Core.fund.dashboard.reload(this,event)">
				<option value="0">--Chi nhánh--</option>
				{$clsSetting->getSelectBySetting("_OFFICE",$office_id,"",1)}
			</select>
		{/if}
	</div>
</div>
{if $_type eq 'total_branch'}
<div class="form-row">
	<div class="col-12 col-xxl-12 mb-2">
		<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
			<div class="panel border-0 no-shadow panel-default mb-0">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Biểu đồ chi phí</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="panel-body no-easyui px-0">
					<div class="ajax" data-url="/index.php?mod={$mod}&sub=dashboard&act=load_chart_branch" gId="{$gId}" data-options={ldelim}{rdelim}>
						<div class="p-5 text-center">
							<div class="p-5">
								<div class="p-5">
									Đang tải dữ liệu...
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<div class="col-12 col-xxl-6 mb-2">
		<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
			<div class="panel border-0 no-shadow panel-default mb-0">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Chi phí theo tháng</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="panel-body no-easyui px-0">
					<div class="ajax" data-url="/index.php?mod={$mod}&sub=dashboard&act=load_chart_branch_time" gId="{$gId}" data-options={ldelim}{rdelim}>
						<div class="p-5 text-center">
							<div class="p-5">
								<div class="p-5">
									Đang tải dữ liệu...
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-xxl-6 mb-2">
		<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
			<div class="panel border-0 no-shadow panel-default mb-0">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Hiệu quả</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="panel-body no-easyui px-0">
					<div class="ajax" data-url="/index.php?mod={$mod}&sub=dashboard&act=load_chart_branch_effective" gId="{$gId}" data-options={ldelim}{rdelim}>
						<div class="p-5 text-center">
							<div class="p-5">
								<div class="p-5">
									Đang tải dữ liệu...
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{elseif $_type eq 'total_project'}
<div class="form-row row-cols-1 row-cols-lg-3">
	<div class="col mb-2">
		<div class="card p-3  fund_box fund_total relative text-main">
			<span class="text-nowrap ">Ngân sách</span>	
			<hr class="w-px-100 my-2">	
			<h5 class="mb-1 fs-4 fw-bold">100.000.000.000đ</h5>
		</div>
	</div>
	<div class="col mb-2">
		<div class="card p-3  fund_box fund_total relative  text-warning">
			<span class="text-nowrap">Doanh thu</span>	
			<hr class="w-px-100 my-2">	
			<h5 class="mb-1 fs-4 fw-bold">120.000.000.000đ</h5>
		</div>
	</div>
	<div class="col mb-2">
		<div class="card p-3 fund_box fund_total relative text-success">
			<span class="text-nowrap">Hiệu quả</span>	
			<hr class="w-px-100 my-2">	
			<h5 class="mb-1 fs-4 fw-bold">+20.000.000.000đ</h5>
		</div>
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-xxl-12 mb-2">
		<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
			<div class="panel border-0 no-shadow panel-default mb-0">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Hiệu quả đầu tư</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="panel-body no-easyui px-0">
					<div class="ajax" data-url="/index.php?mod={$mod}&sub=dashboard&act=load_chart_project" gId="{$gId}" data-options={ldelim}{rdelim}>
						<div class="p-5 text-center">
							<div class="p-5">
								<div class="p-5">
									Đang tải dữ liệu...
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<div class="col-12 col-xxl-12 mb-2">
		<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
			<div class="panel border-0 no-shadow panel-default mb-0">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Chi phí theo tháng</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="panel-body no-easyui px-0">
					<div class="ajax" data-url="/index.php?mod={$mod}&sub=dashboard&act=load_chart_branch_time" gId="{$gId}" data-options={ldelim}{rdelim}>
						<div class="p-5 text-center">
							<div class="p-5">
								<div class="p-5">
									Đang tải dữ liệu...
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{else}

{/if}
<div class="row">
	<div class="col-12">
		<div class="dashboard-panel-item dashboard-panel-item--full">
			<div class="panel border-0 no-shadow panel-default">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Danh sách {$titlePage}</h3>
					<div class="panel-total fs-6 text-main">
						Tổng {if $gr eq 'THUCTHU'}thu{else}chi{/if}: <strong id="total_amounts">0</strong>
						<sup>{$clsISO->getRate()}</sup>
					</div>
				</div>
				<div class="panel-body no-easyui px-0">
					<div id="table_report" class="overflow-x-auto">
						<table class="table" width="100%">
							<thead><tr>
								<th class="align-center text-center" width="3%">STT</th>
								<th class="align-center text-right">Ngày {if $gr eq 'THUCTHU'}thu{else}chi{/if}</th>
								<th class="align-center text-right">Ngày hạch toán</th>
								<th class="align-center">Số Chứng từ</th>
								<th class="align-center">Diễn giải</th>
								<th class="align-center">Tài khoản quỹ</th>
								<th class="align-center">Số tiền</th>
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
	</div>
</div>