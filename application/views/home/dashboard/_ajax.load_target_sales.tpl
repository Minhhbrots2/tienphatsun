{if !empty($txt_not_target)}
	<div class="alert alert-warning">{$txt_not_target}</div>
{/if}
<div class="form-row mb-3">
	<div class="col-4">
		<div class="card">
			<div class="item_target item_quantity_achieved">
				<div class="d-flex align-items-center gap-1">
					<span class="icon_header"></span>
					<h5 class="card-title mb-0 fs-12 text-upper text-dark">Giao dịch</h5>
				</div>
				<div class="item_body">
					<div class="text-success">
						<span class="fs-20 fw-bold">{$total_billings}/{$target_quantity}</span> <span class="">GD</span>
					</div>
					<div class="progress h-px-10 mb-1">
						<div class="progress-bar" role="progressbar" style="width:{$percent_qty}%;" aria-valuenow="{$percent_qty}" 
						aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					{if !empty($target_quantity)}
						{if !empty($total_billings)}
							{if $total_billings gte $target_quantity}
								<div class=" fs-10">
									<span class="">Bạn đã đạt chỉ tiêu</span>
									<span class="">{$percent_qty}</span>
								</div>
							{else}
								<div class=" fs-10">
									<span class="">Đã chốt <strong class="text-main">{$total_billings}</strong> GD - Còn <strong class="text-warning">{$unmet_target_quantity}</strong> GD</span>
									<span class="">{$percent_qty}</span>								
								</div>
							{/if}
						{else}
							<div class=" fs-10">
								<span class="">Chưa có giao dịch nào!</span>							
							</div>
						{/if}
					{else}
						<div class="text-muted fs-10">Mục tiêu chưa thiết lập</div>
					{/if}
				</div>
			</div>			
		</div>
	</div>
	<div class="col-4">
		<div class="card">
			<div class="item_target item_price_achieved">
				<div class="d-flex align-items-center gap-1">
					<span class="icon_header"></span>
					<h5 class="card-title mb-0 fs-12 text-upper text-dark">Doanh số</h5>
				</div>
				<div class="item-body">
					<div class="text-success">
						<span class="fs-20 fw-bold">{$clsISO->shortNumber($total_amount,1,1)}</span> <span class=""> / {$clsISO->shortNumber($target_amount,1,1)}</span>
					</div>
					<div class="progress h-px-10 mb-1">
						<div class="progress-bar" role="progressbar" style="width:{$percent_amount}%;" aria-valuenow="{$percent_amount}" 
						aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					{if !empty($target_amount)}
						{if !empty($total_amount)}
							{if $total_amount gte $target_amount}
								<div class=" fs-10">
									<span class="">Bạn đã đạt chỉ tiêu</span>
									<span class="">{$percent_amount}</span>
								</div>
							{else}
								<div class=" fs-10">
									<span class="">Đã đạt {$clsISO->shortNumber($total_amount)}</span>
									<span class="">{$percent_amount}</span>								
								</div>
							{/if}
						{else}
							<div class=" fs-10">
								<span class="">Chưa có giao dịch nào!</span>							
							</div>
						{/if}
					{else}
						<div class="text-muted fs-10">Mục tiêu chưa thiết lập</div>
					{/if}
				</div>
			</div>			
		</div>
	</div>
	<div class="col-4">
		<div class="card">
			<div class="item_target item_unmet_achieved">
				<div class="d-flex align-items-center gap-1">
					<span class="icon_header"></span>
					<h5 class="card-title mb-0 fs-12 text-upper text-dark">Còn thiếu</h5>
				</div>
				<div class="item-body">
					<div class="text-warning">
						<span class="fs-20 fw-bold">{$unmet_target_quantity}</span> GD <span class=""> / {$clsISO->shortNumber($unmet_target_price,1,1)}</span>
					</div>
					<hr style="margin: 6px 0px;height: 0;border-top: 1px dashed #5a5a5a">
					{if !empty($target_amount)}
						{if $unmet_target_quantity gt 0 || $unmet_target_price gt 0}
							<div class=" fs-10">
								<span class="">Hãy tăng tốc lên nào!</span>
							</div>
						{else}
							<div class=" fs-10">
								<span class="">Bạn đã đạt chỉ tiêu</span>
							</div>
						{/if}
					{else}
						<div class="text-muted fs-10">Mục tiêu chưa thiết lập</div>
					{/if}
				</div>
			</div>			
		</div>
	</div>
</div>