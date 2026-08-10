<div class="modal-dialog">
	<form class="modal-content" enctype="multipart/form-data" method="POST">
		<div class="modal-header"> 
			<h5 class="modal-title"><strong>Cấu hình bảng quỹ</strong></h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group form-row mb-4">
			{if !empty($group_company_arrs)}
				{foreach from=$group_company_arrs item = _OI}
				{assign var = company_id value = $_OI.setting_id}
				<div class="col-6">
					<label class="form-label mb-1 text-right">Tồn đầu kỳ {$_OI.title}</label>
					<div class="input-group input-group-merge">
						<input type="text" gId="{$uid}" class="form-control numberonly price-In" placeholder="0.00" name="cash_book_configs[{$company_id}][opening_balance]" value="{if !empty($cash_book_configs.{$company_id}.opening_balance)}{$cash_book_configs.{$company_id}.opening_balance}{/if}">
						<span class="input-group-text">{$clsISO->getRate()}</span>
					</div>
				</div>
				{/foreach}
			{/if}
			</div>
			<h3 class="text-fs-15">2. Cài đặt Cronjob</h3>
			{if !empty($group_company_arrs)}
				{foreach from=$group_company_arrs item = _OI}
				{assign var = company_id value = $_OI.setting_id}
				<div class="form-group mb-2">
					<label class="form-label mb-1 text-right">File Google Sheet {$_OI.title}</label>
					<div class="input-group">
						<input type="text" gId="{$uid}" onChange="$Core.cash_book.get_worksheets(this, event)" class="form-control spreadsheetId" 
							placeholder="https://" value="{$cash_book_configs.$company_id.spreadsheetId}" toId="sheet_name_{$company_id}_{$uid}" 
							name="cash_book_configs[{$company_id}][spreadsheetId]">
						<select name="cash_book_configs[{$company_id}][sheet_name]" id="sheet_name_{$company_id}_{$uid}" 
							class="form-control form-select sheet_name max-w-px-150">
							<option>Lựa chọn Sheet</option>
							{if !empty($cash_book_configs.$company_id.sheet_name)}
							<option selected value="{$cash_book_configs.$company_id.sheet_name}">{$cash_book_configs.$company_id.sheet_name}</option>
							{/if}
						</select>
						<button onClick="$Core.cash_book.open_config(this, event)" holderG="google.sheet" company_id="{$company_id}" 
							gId="{$uid}" type="button" class="btn btn-icon btn-outline-default"><i class="bx bx-cog"></i>
						</button>
					</div>
				</div>
				{/foreach}
			{/if}
			<div class="form-group">
				<label class="form-label mb-1 text-right">Cài đặt cron</label>
				<div class="input-group mb-1">
					<div class="form-control">{$PCMS_URL}/cronjons/cash_book.php</div>
					<input type="hidden" name="cash_book_configs[is_run_cronjob]" value="0" />
					<div class="input-group-text">
						<input class="form-check-input mt-0"{if $cash_book_configs.is_run_cronjob eq '1'} checked="checked"{/if} 
							type="checkbox" value="1" name="cash_book_configs[is_run_cronjob]">
					</div>
				</div>
				<span class="form-text">Tick vào đây để cho phép chạy cron tự động</span>
			</div>
		</div>
		<div class="modal-footer border-top">
			<button type="button" class="btn btn-default" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary" gId="{$uid}"
				onClick="$Core.cash_book.save_setting(this, event)">Cập nhật</button>
		</div>
	</form>
</div>