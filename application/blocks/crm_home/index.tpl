<div class="form-row">
	<div class="col-12 col-lg-6 mb-2">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-0">Khách hôm nay</h5>
			</div>
			<div class="card-body ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_followup_crm" data-options="{ldelim}{rdelim}">
				<div class="p-5 text-center">
					<div class="p-2">Đang tải...</div>
				</div>
				<a class="btn bg-main btn-outline-default text-white w-100" href="{$clsISO->getLink('crm')}" >Đi đến CRM</a>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-6 mb-2">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-0">Tiến trình chăm sóc {if $deviceType eq 'phone'}KH{else}khách hàng{/if}</h5>
			</div>
			<div class="card-body ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_sales_pipeline" data-options="{ldelim}{rdelim}">
				<div class="p-5 text-center">
					<div class="p-2">Đang tải...</div>
				</div>
			</div>
		</div>
	</div>
</div>