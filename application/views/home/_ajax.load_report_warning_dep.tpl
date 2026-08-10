{if !empty($total_dep_rate_low) || !empty($total_dep_not_contract) || !empty($total_billing_not_contract) }
	<div class="card h-100">
		<div class="card-body" >
			<div class="alert  py-2 mb-0">
				<i class='bx bx-info-circle text-danger me-2'></i> Cảnh báo điều hành
			</div>
			{if !empty($total_dep_rate_low)}
				<div class="alert alert-warning py-2 mb-2">
					<i class='bx bx-info-circle me-2'></i>{$total_dep_rate_low} vùng có tỷ lệ ký &lt; 40%
				</div>
			{/if}
			{if !empty($total_dep_not_contract)}
			<div class="alert alert-warning py-2 mb-2">
				<i class='bx bx-info-circle me-2'></i>{$total_dep_not_contract} vùng kinh doanh tồn &gt; 40 giao dịch chưa ký
			</div>
			{/if}
			{if !empty($total_billing_not_contract)}
			<div class="alert alert-warning py-2 mb-0">
				<i class='bx bx-info-circle me-2'></i>{$total_billing_not_contract} giao dịch chưa có lịch ký &gt; 7 ngày
			</div>
			{/if}
		</div>
	</div>
{/if}