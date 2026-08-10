<div class="form-row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 row-cols-xxl-5">
	<div class="col mb-2 flex-fill">
		<div class="card card-boder fund_box h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Chi phí</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-main">{$clsISO->shortNumber($total_expense)}</h4>
				{if $change_rate eq ""}
					<span class="text-success fw-bold"><i class="bx bx-caret-up"></i>{$clsISO->shortNumber($total_expense)}</span>
				{else}
					{if $change_rate gt 0}
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i>{$change_rate}% </span>
					{else}
						<span class="text-danger fw-bold"><i class="bx bx-caret-down"></i>{$change_rate}% </span>
					{/if}
				{/if}
			</div>
			<span class="icon_kpi position-absolute top-10 right-10">💸</span>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card card-boder fund_box h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Doanh thu</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-warning">{$clsISO->shortNumber($total_revenue)}</h4>
				<div class="fs-11">
					{if $change_rate_revenue eq ""}
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i>{$clsISO->shortNumber($total_revenue)}</span>
					{else}
						{if $change_rate_revenue gt 0}
							<span class="text-success fw-bold"><i class="bx bx-caret-up"></i>{$change_rate_revenue}% </span>
						{else}
							<span class="text-danger fw-bold"><i class="bx bx-caret-down"></i>{$change_rate_revenue}% </span>
						{/if}
					{/if}
					<span class="">so với kỳ trước</span>
				</div>
			</div>
			<span class="icon_kpi position-absolute top-10 right-10">💰</span>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card card-boder fund_box h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Lợi nhuận</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-success">{$clsISO->shortNumber($total_profit)}</h4>
				<div class="fs-11">
					{if $change_rate_profit eq ""}
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i>{$clsISO->shortNumber($total_profit)}</span>
					{else}
						{if $change_rate_profit gt 0}
							<span class="text-success fw-bold"><i class="bx bx-caret-up"></i>{$change_rate_profit}% </span>
						{else}
							<span class="text-danger fw-bold"><i class="bx bx-caret-down"></i>{$change_rate_profit}% </span>
						{/if}
					{/if}
					<span class="">so với kỳ trước</span>
				</div>
			</div>
			<span class="icon_kpi position-absolute top-10 right-10">📈</span>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card card-boder fund_box h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Marketing</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-danger">{$clsISO->shortNumber($total_mkt)}</h4>
				<div class="fs-11">
					{if $change_rate_mkt eq ""}
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i>{$clsISO->shortNumber($total_mkt)}</span>
					{else}
						{if $change_rate_mkt gt 0}
							<span class="text-success fw-bold"><i class="bx bx-caret-up"></i>{$change_rate_mkt}% </span>
						{else}
							<span class="text-danger fw-bold"><i class="bx bx-caret-down"></i>{$change_rate_mkt}% </span>
						{/if}
					{/if}
					<span class="">so với kỳ trước</span>
				</div>
			</div>
			<span class="icon_kpi position-absolute top-10 right-10">📢</span>
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card card-boder fund_box h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Hoa hồng</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-info">{$clsISO->shortNumber($total_commission)}</h4>
			</div>
			<span class="icon_kpi position-absolute top-10 right-10">🤝</span>
		</div>
	</div>	
</div>