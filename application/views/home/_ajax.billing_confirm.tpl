<div class="modal-dialog modal-dialog-centered modal-xs">
	{assign var = toId value = $clsISO->getUniqid()}
	<form class="d-none" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="hid" value="upload" />
		<input type="file" toId="{$toId}" class="upload_file_{$toId}" onChange="$Core.billing.upload_sp_file(this, event)" 
		name="upload_file" billing_id="{$billing_id}" />
	</form>
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Xác nhận thay đổi giao dịch {$stock_code}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Lý do thay đổi</label>
				<textarea class="form-control" placeholder="Nhập lý do thay đổi" name="reason" rows="2" cols="255"></textarea>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-12  col-md-6 mb-2 mb-lg-0">
					<div class="form-label mb-1 d-flex align-items-center justify-content-between">
						<label class="mb-0">Xác nhận của GĐKD</label>
						<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" onClick="select_sp_file(this,event);" 
							toId="{$toId}" to_field="sales_dir_agree_image" title="Tải ảnh nên">{$clsISO->makeIcon('bx-upload')}</a>
					</div>
					<div class="cursor-pointer">
						<a id="sales_dir_agree_image_{$toId}" onpaste="" class="d-block w-100 overflow-hidden d-flex align-items-center justify-content-center border rounded-1 h-px-150 xs:h-px-200">
							{if !empty($more_information.sales_director_approval_image)}
							<img src="{$clsISO->getGoogleUrl($more_information.sales_director_approval_image)}" class="w-100 h-100 rounded-1" />
							{else}
							<div class="text-muted text-center">
								<i class='bx bx-camera'></i> Hình ảnh tin nhắn
							</div>
							{/if}
						</a>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="form-label mb-1 d-flex align-items-center justify-content-between">
						<label class="mb-0">Xác nhận Sale</label>
						<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" onClick="select_sp_file(this,event);" 
							toId="{$toId}" to_field="sale_agree_image" title="Tải ảnh nên">{$clsISO->makeIcon('bx-upload')}</a>
					</div>
					<div class="cursor-pointer">
						<a id="sale_agree_image_{$toId}" onpaste="" class="d-block w-100 overflow-hidden border d-flex align-items-center justify-content-center rounded-1 h-px-150 xs:h-px-200">
							{if !empty($more_information.ccid_back)}
							<img src="{$clsISO->getGoogleUrl($more_information.ccid_back)}" class="w-100 h-100 rounded-1" />
							{else}
							<div class="text-muted text-center">
								<i class='bx bx-camera' ></i> Hình ảnh tin nhắn
							</div>
							{/if}
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			{foreach from=$arr_fields_change key = _oF item = _oV}
				{if $_oF eq 'dep_logs'}
					{foreach from=$_oV key = _osF item = _osV}
					<input type="hidden" name="content_change[{$_oF}][{$_osF}]" value="{$_osV}" />
					{/foreach}
				{else}
				<input type="hidden" name="content_change[{$_oF}]" value="{$_oV}" />
				{/if}
			{/foreach}
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Huỷ bỏ</button>
			<button type="button" billing_id="{$billing_id}" _uid="{$_uid}" onClick="$Core.global.billing.upd_billing_changed(this, event)" 
				class="btn flex-fill btn-primary">Tiếp tục</button>
		</div>
	</form>
</div>