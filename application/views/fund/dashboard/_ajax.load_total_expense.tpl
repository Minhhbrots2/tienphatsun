<div class="form-row row-cols-1 row-col-md-2 row-cols-lg-4">
	<div class="col mb-2">
		<div class="card card-boder fund_box fund_debit">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Tổng thu</h3>	
				<h5 class="mb-1 fs-3 fw-bold text-main">{$clsISO->shortNumber($total_income)}</h5>
				<div class="">
					<span class="text-success fw-bold">
						<i class="bx bx-caret-up"></i> 0% 
					</span>
					<span class="text-muted">so với kỳ trước</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col mb-2">
		<div class="card card-boder card-boder fund_box fund_credit">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Tổng chi</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-warning">{$clsISO->shortNumber($total_expense)}</h4>
				<div class="">
					<span class="text-danger fw-bold">
						<i class="bx bx-caret-down"></i> 0% 
					</span>
					<span class="text-muted">so với kỳ trước</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col mb-2">
		<div class="card card-boder fund_box fund_assets">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Tổng tài sản</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-info">{$clsISO->shortNumber($total_assets)}</h4>
				<div class="">
					<span class="text-success fw-bold">+0 tỷ </span>
					<span class="text-muted">so với kỳ trước</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col mb-2">
		<div class="card card-boder fund_box fund_profit">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Lợi nhuận</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-success">{$clsISO->shortNumber($total_profit)}</h4>
				<div class="">
					<span class="text-danger fw-bold">0 tỷ </span>
					<span class="text-muted">so với kỳ trước</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row g-2 mb-2">
<div class="col-6 col-sm-3">
	<a class="kpi-card kpi-green text-white" href="{$clsMoney->getLinkDashboard('marketing')}" target="_blank">
		<div class="kpi-label">💵 Chi phí MKT</div>
		<div><span class="kpi-value">{$clsISO->shortNumber($total_mkt)}</span><span class="kpi-badge">{if $total_mkt gte $total_mkt_prev}▲{else}▼{/if}12%</span></div>
	</a>
</div>
<div class="col-6 col-sm-3">
	<a class="kpi-card kpi-blue text-white" href="{$clsMoney->getLinkDashboard('branch')}" target="_blank">
		<div class="kpi-label">🏦 Chi phí chi nhánh</div>
		<div><span class="kpi-value">{$clsISO->shortNumber($total_branch)}</span><span class="kpi-badge">{if $total_branch gte $total_branch_prev}▲{else}▼{/if}%</span></div>
	</a>
</div>
<div class="col-6 col-sm-3">
	<a class="kpi-card kpi-orange text-white" href="{$clsMoney->getLinkDashboard('project')}" target="_blank">
		<div class="kpi-label">📋 Chi phí dự án</div>
		<div><span class="kpi-value">8,1 tỷ</span><span class="kpi-badge">▼%</span></div>
	</a>
</div>
<div class="col-6 col-sm-3">
	<a class="kpi-card kpi-red text-white" href="{$clsMoney->getLinkDashboard('cash_flow')}" target="_blank">
		<div class="kpi-label">📉 Dòng tiền</div>
		<div><span class="kpi-value">6,4 tỷ</span><span class="kpi-badge">▼2%</span></div>
	</a>
</div>
</div>