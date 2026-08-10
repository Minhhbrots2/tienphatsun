<div class="modal-dialog modal-ipad"><div class="modal-content">
	<div class="modal-header">
		<h5 class="modal-title mb-2">Quản lý tài khoản/Quỹ</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	</div>
	<div class="modal-body">
		<div class="search bg-lightest p-2 mb-2 rounded-2">
			<div class="w-full d-flex justify-content-between">
				<div class="">
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-search"></i></span>
						<input type="text" class="form-control" placeholder="Search..." aria-label="Search...">
					</div>
				</div>
				<button class="btn btn-outline-default" onClick="$Core.property.open_property(this, event)" toId="fund" property_id="0" property_type="BANK_ACCOUNT">{$core->makeIcon('plus-circle', 'Thêm')}</button>
			</div>
		</div>
		<div class="holder_setting_property_BANK_ACCOUNT">
			<div class="p-5 text-center">Loading...</div>
		</div>
	</div>
</div></div>
