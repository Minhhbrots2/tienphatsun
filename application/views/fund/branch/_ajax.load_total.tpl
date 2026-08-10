<div class="form-row row-cols-1 row-col-md-2 row-cols-lg-5">
	<div class="col mb-2">
		<div class="card card-boder card_border fund_box fund_debit h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Tổng chi phí</h3>	
				<h5 class="mb-1 fs-3 fw-bold text-main">{$clsISO->shortNumber($total_branch)}</h5>
			</div>
		</div>
	</div>
	<div class="col mb-2">
		<div class="card card-boder fund_box fund_credit h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Thay đổi so với kỳ trước </h3>	
				{if $total_change lte 0}
					<h4 class="mb-1 fs-3 fw-bold text-warning">{$clsISO->shortNumber($total_change)} {if !empty($change_rate)}<span class="fs-6">({$change_rate}%)</span>{/if}</h4>
					{if $total_change gt 0}
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i> giảm tốt</span>
					{else}
						<span class="text-success fw-bold"><i class="bx bx-caret-up"></i> không đổi</span>
					{/if}
				{else}
					<h4 class="mb-1 fs-3 fw-bold text-warning">+{$clsISO->shortNumber($total_change)} {if !empty($change_rate)}<span class="fs-6">({$change_rate}%)</span>{/if}</h4>
					<span class="text-danger fw-bold"><i class="bx bx-caret-down"></i> cảnh báo</span>
				{/if}
				
			</div>
		</div>
	</div>
	<div class="col mb-2">
		<div class="card card-boder fund_box fund_assets h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Doanh thu</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-warning">{$clsISO->shortNumber($total_revenue)}</h4>
				<div class="">
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
		</div>
	</div>
	<div class="col mb-2 flex-fill">
		<div class="card card-boder fund_box fund_profit2 h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Tỷ lệ chi phí/Doanh thu</h3>	
				{if $cost_ratio eq "" && $total_branch gt 0}
				<h4 class="mb-1 fs-3 fw-bold text-success">Chưa có doanh thu</h4>
				<span class="text-danger fw-bold"><i class="bx bx-check-square"></i> báo động</span>
				{else}
					{if $cost_ratio eq 0}
					<h4 class="mb-1 fs-3 fw-bold text-success">0%</h4>
					<span class="text-success fw-bold"><i class="bx bx-check-square"></i> tuyệt vời</span>
					{else if $cost_ratio lt 0.5}
						<h4 class="mb-1 fs-3 fw-bold text-success">{$cost_ratio}%</h4>
						<span class="text-warning fw-bold"><i class="bx bx-check-square"></i> tốt</span>
					{else if $cost_ratio lt 1}
						<h4 class="mb-1 fs-3 fw-bold text-success">{$cost_ratio}%</h4>
						<span class="text-warning fw-bold"><i class="bx bx-check-square"></i> trung bình</span>
					{else}
						<h4 class="mb-1 fs-3 fw-bold text-success">{$cost_ratio}%</h4>
						<span class="text-danger fw-bold"><i class="bx bx-check-square"></i> báo động</span>
					{/if}
				{/if}
			</div>
		</div>
	</div>
	<div class="col mb-2">
		<div class="card card-boder fund_box fund_danger h-100">
			<div class="card-body">
				<h3 class="text-nowrap fs-18 text-black mb-2">Lợi nhuận</h3>	
				<h4 class="mb-1 fs-3 fw-bold text-warning">{$clsISO->shortNumber($total_profit)}</h4>
				<div class="">
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
		</div>
	</div>
</div>