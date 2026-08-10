<div class="modal-dialog">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cập nhật tiền trong tài khoản <br />
				<span class="text-main fs-10">Cập nhật lần cuối: {$clsISO->convertTimeToText($oneCash.upd_date, true)} bởi: {$clsProfile->getFullName($oneCash.user_id_update)} </span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row">
				{foreach from=$list_bank_accounts item = _oI}
				{assign var = bank_account_id value = $_oI.property_id}
				<div class="col-6 col-md-6 mb-2">
					<label for="name" class="form-label mb-1">{$_oI.title}
						<span title="{$_oI.intro}"><i class='bx bx-help-circle fs-12'></i></span>
					</label>
					<div class="input-group input-group-merge">
						<input type="text" autocomplete="off" onClick="this.select()" name="more_information[{$bank_account_id}]" class="form-control numberonly price-In required" value="{$more_information.$bank_account_id}" placeholder="Số tiền">
						<span class="input-group-text">.00</span>
					</div>
				</div>
				{/foreach}
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary" cash_fund_id="{$cash_fund_id}" onClick="$Core.global.fund.pop_save_cash_fund(this, event)" title="Lưu lại">Lưu lại</button>
		</div>
	</form>
</div>
