<div class="modal-dialog modal-dialog-centered modal-ipad">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label for="code" class="form-label mb-1">Mã phiếu</label>
					<input type="text" autocomplete="off" name="code" value="{if $action eq '_add'}{$clsBankTransfer->genCode()}{else}{$oneBankTransfer.code}{/if}" 
						class="form-control required" placeholder="Mã phiếu">
				</div>
				<div class="col-6 col-md-4">
					<label for="email" class="form-label mb-1">Ngày chuyển</label>
					<input type="datetime-local" autocomplete="off" name="payment_date" value="{$oneBankTransfer.payment_date}" 
						class="form-control required" placeholder="dd/mm/yyyy">
				</div>
				<div class="col-6 col-md-4">
					<label for="phone" class="form-label mb-1">Ngày hạch toán</label>
					<input type="date" autocomplete="off" name="account_date" value="{$oneBankTransfer.account_date}" 
						class="form-control required" placeholder="dd/mm/yyyy">
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label for="amout" class="form-label mb-1">Số tiền</label>
					<div class="input-group input-group-merge">
						<input type="text" autocomplete="off" onChange="$Core.fund.check_amount_bank_transfer(this, event)" name="amount" value="{if $action eq '_edit'}{$oneBankTransfer.amount}{/if}" class="form-control numberonly price-In required" placeholder="Số tiền" onClick="this.select();">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
				<div class="col-6 col-md-4">
					<label class="form-label mb-1">Tài khoản gốc</label>
					<div class="clearfix"></div>
					<select name="bank_account_from" onChange="$Core.fund.check_amount_bank_transfer(this, event)" 
					data-width="100%" class="form-control iso-select2 required">
						<option value="0">Chọn tài khoản gốc</option>
						{$clsProperty->getSelectByProperty('BANK_ACCOUNT',$oneBankTransfer.bank_account_from)}
					</select>
				</div>
				<div class="col-6 col-md-4">
					<label class="form-label mb-1">Tài khoản đích</label>
					<div class="clearfix"></div>
					<select name="bank_account_to" data-width="100%" class="form-control iso-select2 required">
						<option value="0">Chọn tài khoản đích</option>
						{$clsProperty->getSelectByProperty('BANK_ACCOUNT',$oneBankTransfer.bank_account_to)}
					</select>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-12 col-md-12">
					<label class="form-label mb-1">Lý do</label>
					<textarea name="content" class="form-control autosize" placeholder="Lý do" cols="255" rows="3">{if $action eq '_edit'}{$oneBankTransfer.content}{/if}</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
					{if !empty($list_attachments)}
						{foreach name=i from = $list_attachments item = _oFile}
						<div class="MultiFile-label">
							<a class="MultiFile-remove" href="javascript:void(0)" data-url="{$_oFile}">x</a> 
							<span><span class="MultiFile-label" title="{$_oFile}">
								<span class="MultiFile-title">{$_oFile}</span></span>
							</span>
						</div>
						{/foreach}
					{/if}
				</div>
				<div class="clearfix"></div>
				<input name="attachments[]" type="file" multiple="multiple" class="maxsize-10240" id="attachments_{$uid}" />
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<input type="hidden" name="submit" value="Update" />
			<!-- <button type="button" uid="{$uid}" bank_transfer_id="{$bank_transfer_id}" onClick="$Core.fund.save_bank_transfer(this, event)" class="btn btn-success js__continue-add">Lưu & thêm</button> -->
			<button type="button" uid="{$uid}" bank_transfer_id="{$bank_transfer_id}" onClick="$Core.fund.save_bank_transfer(this, event)" class="btn btn-primary">Cập nhật</button>
		</div>
	</form>
</div>
