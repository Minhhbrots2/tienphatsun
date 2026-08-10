<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
	<form action="" method="post" class="modal-content" encrypt="miltipart/form-data">
		<div class="modal-header"> 
			<h3 class="modal-title"><strong>Lựa chọn Sheet</strong></h3>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<table class="table">
				<thead><tr>
					<th width="5%"></th>
					<th width="10%">Sheet ID</th>
					<th >Sheet Name</th>
				</tr></thead>
				{if !empty($arr_worksheets)}
					{foreach from = $arr_worksheets key = _oKey item = _oSheet}
					<tr>
						<td class="align-center text-center">
							<label class="switch">
								<input type="radio" class="{$spreadsheetId}" name="sheet" sheet_id="{$_oKey}" value="{$_oSheet}">
								<span class="slider round"></span>
							</label>
						</td>
						<td class="align-center text-left">{$_oKey}</td>
						<td class="align-center text-left">{$_oSheet}</td>
					</tr>
					{/foreach}
				{/if}
			</table>
		</div>
		<div class="modal-footer">
			<button type="button" onclick="$Core.crm.update_sheet(this, event)" gId="{$gId}" spreadsheetId="{$spreadsheetId}" 
				title="Lưu sheet" class="btn btn-primary">
				<i class="fa fa-plus"></i> Lưu chọn
			</button>
		</div>
	</form>
</div>