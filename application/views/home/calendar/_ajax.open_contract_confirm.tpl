<div class="modal-dialog modal-dialog-centered modal-sm">
	<form method="POST" action="#" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cập nhật thông tin ký HĐMB</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row">
				<div class="col-6 col-md-12 mb-2">
					<label for="stock_code" class="form-label mb-1">Mã căn</label>
					<input type="text" name="stock_code" class="form-control required" disabled  placeholder="Nhập mã căn" value="{$oneBilling.stock_code}">
				</div>
				<div class="col-6 col-md-12 mb-2">
					{assign var=toId value=$clsISO->getUniqid()}
					<div class="d-flex justify-content-between align-items-center">
						<label for="totalgrand" class="form-label mb-1">Số tiền</label>
						<div class="">{$clsISO->priceFormat($oneBilling.totalgrand)}đ <button class="btn btn-icon btn-xs" type="button" totalgrand="{$oneBilling.totalgrand}" onClick="$Core.calendar.getTotalgrand(this,event)" toId="{$toId}" title="Chọn" ><i class="bx bx-copy" /></i></button></div>
					</div>
					
					<div class="input-group input-group-merge">
						<input type="text" name="totalgrand" class="form-control numberonly price-In required" autocomplete="off" 
							placeholder="0.00" value="" id="{$toId}">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
				<div class="col-6 col-md-12 mb-2">
					<label class="form-label mb-1">Phương án TT</label>
					<select class="form-control form-select required" name="billing_method">
						<option value="0">Lựa chọn phương án TT</option>
						{$clsProperty->getSelectByProperty('_BILLING_METHOD',$oneBilling.billing_method)}
					</select>
				</div>
				<div class="col-6 col-md-12 mb-2">
					<label class="form-label mb-1">Bảo lãnh NH</label>
					<select class="form-control form-select required" name="bank_guarantee_id">
						<option value="0">Lựa chọn bảo lãnh NH</option>
						{$clsProperty->getSelectByProperty('_BANK_GUARANTEE',$more_information.bank_guarantee_id)}
					</select>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="tp" value="{$tp}" />
			<input type="hidden" name="cal_id" value="{$cal_id}" />
			<input type="hidden" name="sign_type" value="{$sign_type}" />
			<input type="hidden" name="billing_id" value="{$billing_id}" />
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn flex-fill btn-primary" cal_id="{$cal_id}" 
				onClick="$Core.calendar.save_contract_confirm(this, event)" tp="{$tp}">Cập nhật</button>
		</div>
	</form>
</div>
