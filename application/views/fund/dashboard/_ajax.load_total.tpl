<div class="col mb-2">
	<div class="card fund_box fund_debit">
		<div class="card-body">
			<h3 class="text-nowrap text-fs-18 text-black mb-2">Tổng đã thu</h3>	
			<h5 class="mb-1 text-fs-4 fw-bold text-main">{$clsISO->shortNumber($total_income)}</h5>
			<div class="">
				{if $total_income lt $total_income_prev}
					<span class="text-danger fw-bold">
						<i class="bx bx-caret-down"></i> {$ratio_income}
					</span>
				{else}
					<span class="text-success fw-bold">
						<i class="bx bx-caret-up"></i> {$ratio_income}
					</span>
				{/if}
				<span class="text-muted">so với kỳ trước</span>
			</div>
		</div>
	</div>
</div>
<div class="col mb-2">
	<div class="card fund_box fund_credit">
		<div class="card-body">
			<h3 class="text-nowrap text-fs-18 text-black mb-2">Tổng đã chi</h3>	
			<h4 class="mb-1 text-fs-4 fw-bold text-warning">{$clsISO->shortNumber($total_expense)}</h4>
			<div class="">
				{if $total_expense lt $total_expense_prev}
					<span class="text-danger fw-bold">
						<i class="bx bx-caret-down"></i> {$ratio_expense}
					</span>
				{else}
					<span class="text-success fw-bold">
						<i class="bx bx-caret-up"></i> {$ratio_expense}
					</span>
				{/if}
				<span class="text-muted">so với kỳ trước</span>
			</div>
		</div>
	</div>
</div>
<div class="col mb-2">
	<div class="card fund_box fund_credit h-100">
		<div class="card-body">
			<h3 class="text-nowrap text-fs-18 text-black mb-2">Dự chi</h3>	
			<h4 class="mb-1 fs-3 fw-bold text-danger">{$clsISO->shortNumber($total_expected_expense)}</h4>
		</div>
	</div>
</div>
<div class="col mb-2">
	<div class="card card-boder fund_box fund_assets">
		<div class="card-body">
			<h3 class="text-nowrap text-fs-18 text-black mb-2">Tổng tài sản</h3>	
			<h4 class="mb-1 text-fs-4 fw-bold text-info">{$clsISO->shortNumber($total_assets)}</h4>
			<div class="">
				{if $total_assets lt $total_assets_prev}
					<span class="text-danger fw-bold">
						<i class="bx bx-caret-down"></i> {$ratio_assets}
					</span>
				{else}
					<span class="text-success fw-bold">
						<i class="bx bx-caret-up"></i> {$ratio_assets}
					</span>
				{/if}
				<span class="text-muted">so với kỳ trước</span>
			</div>
		</div>
	</div>
</div>
<div class="col mb-2">
	<div class="card card-boder fund_box fund_profit">
		<div class="card-body">
			<h3 class="text-nowrap text-fs-18 text-black mb-2">Lợi nhuận</h3>	
			<h4 class="mb-1 text-fs-4 fw-bold text-success">{$clsISO->shortNumber($total_profit)}</h4>
			<div class="">
				{if $total_profit lt $total_profit_prev}
					<span class="text-danger fw-bold">
						<i class="bx bx-caret-down"></i> {$ratio_profit}
					</span>
				{else}
					<span class="text-success fw-bold">
						<i class="bx bx-caret-up"></i> {$ratio_profit}
					</span>
				{/if}
				<span class="text-muted">so với kỳ trước</span>
			</div>
		</div>
	</div>
</div>