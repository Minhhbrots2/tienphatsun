{if !empty($target_config)}
	<div class="row row-cols-1 row-cols-md-2">
		<div class="col mb-2 mb-lg-0">
			<div class="d-flex justify-content-between align-items-center mb-2 fs-16 text-info">
				<span class="">Số giao dịch</span>
				<span class="">{$percent_qty}% ({$total_billings}/{$target_quantity} GD)</span>
			</div>
			<div class="progress w-100" style="height:12px;">
			  	<div class="progress-bar bg-info" role="progressbar" aria-valuenow="{$percent_qty}" aria-valuemin="0" aria-valuemax="100" style="width:{$percent_qty}%;"></div>
			</div>
		</div>
		<div class="col">
			<div class="d-flex justify-content-between align-items-center mb-2 fs-16 text-warning">
				<span class="">Doanh số</span>
				<span class="">{$percent_amount}% ({$clsISO->shortNumber($total_amount)}/{$clsISO->shortNumber($target_amount)})</span>
			</div>
			<div class="progress w-100" style="height:12px;">
			  	 <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="{$percent_amount}" aria-valuemin="0" aria-valuemax="100" style="width:{$percent_amount}%;"></div>
			</div>
		</div>
	</div>

{else}
	<div class="d-flex align-items-center justify-content-center mb-3 h-100">
		<button data-toggle="ripple" type="button" class="btn btn-outline-danger" title="Cài đặt mục tiêu" 
			onclick="$Core.dashboard.addTargetSales(this,event)" action="_OPEN">
			<i class="bx bx-cog"></i> Thiết lập mục tiêu
		</button>
	</div>
{/if}