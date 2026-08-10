<div class="container-xxl flex-grow-1 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-3 mb-2">
		<h4 class="fw-bold mb-2">Báo cáo vắng mặt {$smarty.const.BRAND_NAME}</span></h4>
		{if $clsISO->checkPermissionGroup('SALE') || $profile_id eq '9'}
		<button type="button" onClick="$Core.worktime.open(this, event)" data-bs-toggle="tooltip" 
		data-bs-placement="top" title="Thêm báo cáo" worktime_id="0" class="btn btn-outline-primary">
			+ Thêm{if $deviceType ne 'phone'} báo cáo{/if}
		</button>
		{/if}
	</div>
	
	
</div>