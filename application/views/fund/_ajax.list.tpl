{if !empty($list_funds)}
	{foreach from=$list_funds item = _oF}
	{assign var = fund_id value = $_oF.fund_id}
	<tr>
		<td class="text-left">
			{$_oF.payment_date|date_format:"%d/%m/%Y %H:%M"}
		</td>
		<td class="text-left d-flex align-items-center gap-1">
			{if $_oF.gr eq 'THUCCHI'}
				<span class="badge bg-label-danger">PC</span>
			{else}
				<span class="badge bg-label-primary">PT</span>
			{/if}
			<a href="javascript:void(0);" onClick="$Core.fund.view(this,event)" 
				gr="{$_oF.gr}" fund_id="{$fund_id}">{$_oF.code}</a>	
		</td>
		<td class="text-left" ondblclick="$Core.fund.open(this, event)" gr="{$_oF.gr}" fund_id="{$fund_id}">
			{$clsFund->short_content($_oF.content)}
		</td>
		<td class="text-left">{$_oF.bank_account_name}</td>
		<td class="text-left">
			{if $_oF.gr eq 'THUCTHU'}
				{$clsISO->formatNumberToEasyRead($_oF.amount)}
				{$clsISO->getRate()}
			{/if}
		</td>
		<td class="text-left">
			{if $_oF.gr eq 'THUCCHI'}
				{$clsISO->formatNumberToEasyRead($_oF.amount)}
				{$clsISO->getRate()}
			{/if}
		</td>
		<td class="text-left">
			{$clsISO->formatNumberToEasyRead($_oF.balance)}
			{$clsISO->getRate()}
		</td>
		<!-- <td class="text-center">
			{if !empty($_oF.content)}
			<a data-bs-toggle="tooltip" title="{$_oF.content}" data-bs-placement="auto" data-bs-html="true" data-bs-trigger="hover">Xem</a>
			{/if}
		</td> -->
		<td class="text-center">
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
					data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
				</button>
				<div class="dropdown-menu">
					<!-- <a class="dropdown-item" onClick="$Core.fund.view(this,event)" gr="{$_oF.gr}" fund_id="{$fund_id}" href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
					<a class="dropdown-item" onClick="$Core.fund.print(this,event)" gr="{$_oF.gr}" fund_id="{$fund_id}" href="javascript:void(0);"><i class="bx bx-printer me-1"></i> In</a>
					<hr class="dropdown-divider" /> -->
					{if $permiss_edit eq '1'}
					<a class="dropdown-item" onClick="$Core.fund.open(this,event)" gr="{$_oF.gr}" fund_id="{$fund_id}" href="javascript:void(0);"><i class="bx bx-pencil me-1"></i> Sửa</a>
					
					<a class="dropdown-item" onClick="$Core.fund.duplicate(this,event)" gr="{$_oF.gr}" fund_id="{$fund_id}" href="javascript:void(0);"><i class="bx bx-copy me-1"></i> Nhân bản</a>
					<a class="dropdown-item" onClick="$Core.fund.cre_bank_tranfer(this,event)" gr="{$_oF.gr}" fund_id="{$fund_id}" href="javascript:void(0);"><i class="bx bx-transfer me-1"></i> Tạo chuyển quỹ</a>
					{/if}
					{if $permiss_delete eq '1'}
					<a class="dropdown-item" onClick="$Core.fund.delete(this,event)" gr="{$_oF.gr}" fund_id="{$fund_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>{/if}
				</div>
			</div>
		</td>
	</tr>
	{/foreach}
{else}
	<tr class="nohover">
		<td colspan="10" class="text-center">
			<div class="py-3">
				<img src="{$URL_IMAGES}/table-no-data.png" width="150" />
				<p class="my-2 text-muted">Không có dữ liệu</p>
			</div>
		</td>
	</tr>
{/if}