<div class="d-flex gap-2 justify-content-end align-items-center mb-3">
	<label class="text-nowrap">Lọc theo:</label> 
	<div class="input-group w-px-200 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
		<select class="form-control form-select" name="month" gr="{$gr}" cat_id="{$cat_id}" onChange="$Core.fund.reload(this,event)"> 
			<option value="">Tháng</option>			
			{foreach from=$list_months item = _month}
			<option value="{$_month}">Tháng {$_month}</option>
			{/foreach}
		</select>
		<select class="form-control form-select" name="year" gr="{$gr}" cat_id="{$cat_id}" onChange="$Core.fund.reload(this,event)">
			{foreach from=$list_years item = _year}
			<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
			{/foreach}
		</select>
	</div>
</div>
{if $template_type eq '_general'}
<div class="ajax briefs mb-3 gap-2 d-flex flex-wrap" data-url="/index.php?mod={$mod}&act=load_apec_fund" data-options={ldelim}{rdelim}>
	<div class="brief-item bg-orange p-3 clickable">
		<p class="fs-16 mb-3">Quỹ đầu kỳ(₫)</p>
		<h3 class="fs-32 mb-0 text-white">
			<div class="animate-bg w-px-125 h-px-20 rounded-2"></div>
		</h3>
	</div>
	<div class="brief-item p-3 bg-azure">
		<p class="fs-16 mb-3">Tổng thu(₫)</p>
		<h3 class="fs-32 mb-0 text-white">
			<div class="animate-bg w-px-125 h-px-20 rounded-2"></div>
		</h3>
	</div>
	<div class="brief-item p-3 bg-cyan">
		<p class="fs-16 mb-3">Tổng chi(₫)</p>
		<h3 class="fs-32 mb-0 text-white">
			<div class="animate-bg w-px-125 h-px-20 rounded-2"></div>
		</h3>
	</div>
	<div class="brief-item p-3 bg-green">
		<p class="fs-16 mb-3">Tồn quỹ(₫)</p>
		<h3 class="fs-32 mb-0 text-white">
			<div class="animate-bg w-px-125 h-px-20 rounded-2"></div>
		</h3>
	</div>
</div>
<div class="form-row">
	<div class="col-12">
		<div class="dashboard-panel-item dashboard-panel-item--full">
			<div class="panel border-0 no-shadow panel-default">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Biểu đồ thống kê thu/chi</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="panel-body no-easyui px-0">
					<div class="ajax" data-url="/index.php?mod={$mod}&act=load_chart_fund" data-options={ldelim}{rdelim}>
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
	<div class="col-12 col-xxl-6">
		<div class="dashboard-panel-item dashboard-panel-item--full">
			<div class="panel border-0 no-shadow panel-default">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Biểu đồ thực chi</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="panel-body no-easyui px-0">
					<div class="ajax" data-url="/index.php?mod={$mod}&act=load_chart_expense" data-options={ldelim}{rdelim}>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-xxl-6">
		<div class="dashboard-panel-item dashboard-panel-item--full">
			<div class="panel border-0 no-shadow panel-default">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">
					<h3 class="panel-title">Biểu đồ thực thu</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="panel-body no-easyui px-0">
					<div class="ajax" data-url="/index.php?mod={$mod}&act=load_chart_realincome" data-options={ldelim}{rdelim}>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
						<div class="animate-bg w-100 h-px-20 mb-2 rounded-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="col-6">
		<div class="dashboard-panel-item dashboard-panel-item--full">
			<div class="panel border-0 no-shadow panel-default">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">
					<h3 class="panel-title">Danh sách {$titlePage}</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div class="panel-body no-easyui px-0">
				</div>
			</div>
		</div>
	</div> -->
</div>
{else}
<div class="row">
	<div class="col-12">
		<div class="dashboard-panel-item dashboard-panel-item--full">
			<div class="panel border-0 no-shadow panel-default">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Danh sách {$titlePage}</h3>
					<div class="panel-total fs-6 text-main">
						Tổng {if $gr eq 'THUCTHU'}thu{else}chi{/if}: <strong id="total_amounts_{$gr}_{$cat_id}">0</strong>
						<sup>{$clsISO->getRate()}</sup>
					</div>
				</div>
				<div class="panel-body no-easyui px-0">
					<div id="table_report_{$gr}_{$cat_id}" class="overflow-x-auto">
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
	<div class="col-12">
		<div class="dashboard-panel-item dashboard-panel-item--full">
			<div class="panel border-0 no-shadow panel-default">
				<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="panel-title">Biểu đồ thống kê {$titlePage}</h3>
					<a class="panel-help help_pop openHelp" title="Trợ giúp">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<div id="chart_report_{$gr}_{$cat_id}" class="panel-body h-px-300 no-easyui px-0">
					<div class="d-flex w-100 h-100 align-items-center justify-content-center">
						<span class="text-muted">Loading...</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{/if}