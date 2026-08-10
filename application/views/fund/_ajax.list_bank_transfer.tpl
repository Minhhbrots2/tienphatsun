{if !empty($list_bank_transfers)}
	{foreach from=$list_bank_transfers item = _oF}
	{assign var = bank_transfer_id value = $_oF.bank_transfer_id}
	<tr>
		<td class="text-left"><a href="javascript:void(0);" onClick="$Core.fund.open_bank_transfer(this,event)" bank_transfer_id="{$bank_transfer_id}">{$_oF.code}</a></td>
		<td class="text-left">{$clsISO->convertTimeToText($_oF.payment_date, true)}</td>
		<td class="text-left">{$clsISO->convertTimeToText($_oF.account_date, true)}</td>
		<td class="text-left">{$_oF.content}</td>
		<td class="text-left">{$_oF.bank_account_from_name}</td>
		<td class="text-left">{$_oF.bank_account_to_name}</td>
		<td class="text-left">
			{$clsISO->formatNumberToEasyRead($_oF.amount)}
			{$clsISO->getRate()}
		</td>
		<td class="text-center">
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"> 
					<i class="bx bx-dots-vertical-rounded"></i>
				</button>
				<div class="dropdown-menu">
					<a class="dropdown-item" onClick="$Core.fund.open_bank_transfer(this,event)" bank_transfer_id="{$bank_transfer_id}" href="javascript:void(0);"><i class="bx bx-pencil me-1"></i> Sửa</a>
					<a class="dropdown-item" onClick="$Core.fund.delete_bank_transfer(this,event)" bank_transfer_id="{$bank_transfer_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
				</div>
			</div>
		</td>
	</tr>
	{/foreach}
{else}
	<tr class="nohover">
		<td colspan="9" class="text-center">
			<div class="py-3">
				<img src="{$URL_IMAGES}/table-no-data.png" width="150" />
				<p class="my-2 text-muted">Không có dữ liệu</p>
			</div>
		</td>
	</tr>
{/if}