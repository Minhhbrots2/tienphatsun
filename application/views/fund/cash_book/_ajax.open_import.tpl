<div class="modal-dialog">
	<form class="modal-content" enctype="multipart/form-data" method="POST">
		<div class="modal-header"> 
			<h5 class="modal-title"><strong>Import bảng quỹ</strong></h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1 text-right">Công ty</label>
				<div class="clearfix"></div>
				<div class="btn-group text-nowrap w-100" role="group">
				{if !empty($group_company_arrs)}
					{foreach from=$group_company_arrs item = _oI}
					<input type="radio" class="btn-check"{if $_oI.setting_id eq $smarty.const._GROUP_COMPANY_FH_ID} checked{/if} 
						name="company_id" value="{$_oI.setting_id}" id="rdo_{$_oI.setting_id}_{$uid}" 
						spreadsheetId="{$_oI.spreadsheetId}" sheet_name="{$_oI.sheet_name}" onChange="$Core.cash_book.do_change(this, event)">
					<label class="btn btn-outline-default{if $_oI.setting_id eq $smarty.const._GROUP_COMPANY_FH_ID} active{/if}"
						data-toggle="ripple" title="{$_oI.title}" for="rdo_{$_oI.setting_id}_{$uid}">{$_oI.title}</label>
					{/foreach}
				{/if}
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1 text-right">File Import</label>
				<div class="input-group">
					<input type="file" class="form-control" name="fileImport">
					<button onClick="$Core.cash_book.open_config(this, event)" tp="file.upload" gId="{$uid}" type="button" 
						class="btn btn-icon btn-outline-default"><i class="bx bx-cog"></i>
					</button>
				</div>
			</div>
			<div class="divider my-2">
				<div class="divider-text">Hoặc</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1 text-right">File Google Sheet</label>
				<div class="input-group">
					<input type="text" gId="{$uid}" uid="{$uid}" onChange="$Core.marketing.crawl(this, event)" 
						class="form-control spreadsheetId" placeholder="https://" name="spreadsheetId" value="{$one_configs.spreadsheetId}">
					<select name="sheet_name" uid="{$uid}" gId="{$uid}" class="form-control form-select sheet_name max-w-px-150">
						<option>Lựa chọn Sheet</option>
						{if !empty($one_configs.sheet_name)}
						<option value="{$one_configs.sheet_name}" selected="selected">{$one_configs.sheet_name}</option>
						{/if}
					</select>
					<button onClick="$Core.cash_book.open_config(this, event)" holderG="google.sheet" gId="{$uid}" type="button" 
						class="btn btn-icon btn-outline-default"><i class="bx bx-cog"></i>
					</button>
				</div>
			</div>
		</div>
		<div class="modal-footer border-top">
			<button type="button" class="btn btn-default" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary" uid="{$uid}"
				onClick="$Core.cash_book.do_import(this, event)">Thực hiện</button>
		</div>
	</form>
</div>