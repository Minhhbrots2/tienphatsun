{if $total_billing_changing_confirms gt '0'}
	<div class="card mb-2">
		<div class="card-header d-flex align-items-center justify-content-between">
			<h5 class="card-title mb-0">Giao dịch thay đổi</h5>
			<span class="badge badge-center rounded-pill bg-label-danger">{$total_billing_changing_confirms}</span>
		</div>
		<div class="card-body billing_changing_confirms ajax" data-options="{ldelim}{rdelim}" 
			data-url="{$PCMS_URL}/index.php?mod{$mod}&act=load_billing_changing_confirms">
			<div class="animate-bg w-100 h-px-15 rounded-2 mb-2"></div>
			<div class="w-100 d-flex align-items-center justify-content-between mb-2 gap-3">
				<div class="animate-bg w-100 h-px-15 rounded-2"></div>
				<div class="animate-bg w-100 h-px-15 rounded-2"></div>
			</div>
			<div class="animate-bg w-100 h-px-15 rounded-2 mb-2"></div>
			<div class="w-100 d-flex align-items-center justify-content-between gap-3">
				<div class="animate-bg w-100 h-px-15 rounded-2"></div>
				<div class="animate-bg w-100 h-px-15 rounded-2"></div>
			</div>
		</div>
	</div>
{/if}