<div class="form-row pb-2 border-bottom">
	<div class="col-6">
		<div class="d-flex align-items-center gap-2 border-end">
			<div class="icon-circle"><i class='bx bx-group text-info' style="font-size: 40px" ></i></div>
			<div>
				<h2 class="mb-0 fw-bold">{$total_sale_active}</h2>
				{if $total_sale_active gt $total_sale_active_prev}
					<small class="text-success">+{$total_sale_active - $total_sale_active_prev} tăng so tháng trước</small>
				{elseif $total_sale_active eq $total_sale_active_prev}
					<small class="text-warning">Không đổi so tháng trước</small>
				{else}
					<small class="text-danger">{$total_sale_active - $total_sale_active_prev} giảm so tháng trước</small>
				{/if}
			</div>
		</div>
	</div>
	<div class="col-6">
		<div class="text-center">
			{assign var=rate value=$clsISO->getRateNumber($total_sale_active - $total_sale_active_prev , $total_sale_active_prev,1 )}
			{if $rate gt 0}
				<h4 class="text-success fw-bold mb-2">+{$rate}%</h4>
				<small class="text-muted">+{$total_sale_new} mới</small>
			{elseif $rate lt 0}
				<h4 class="text-danger fw-bold mb-2">{$rate}%</h4>
				<small class="text-muted">-{$total_sale_out} nghỉ việc</small>
			{/if}
			
		</div>
	</div>
	<div class="col-12">
		<div id="staffChart" style="height: 240px;"></div>
	</div>
</div>
<div class="d-flex flex-wrap justify-content-between gap-2 py-2 align-items-center">
	<div class=""><span class="text-success fw-bold">+{$total_sale_new}</span> Nhân sự mới</div>
	<div class=""><span class="text-danger fw-bold">-{$total_sale_out}</span> Nhân sự nghỉ</div>
</div>