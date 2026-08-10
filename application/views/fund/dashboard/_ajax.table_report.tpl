{if !empty($list_items)}
<table class="table mb-1" width="100%">
	<thead><tr>
		<th class="align-center text-center" width="3%">STT</th>
		<th class="align-center text-right">Ngày {if $gr eq 'THUCTHU'}thu{else}chi{/if}</th>
		<th class="align-center text-right">Ngày hạch toán</th>
		<th class="align-center">Số Chứng từ</th>
		<th class="align-center">Diễn giải</th>
		<th class="align-center">Tài khoản quỹ</th>
		<th class="align-center">Số tiền</th>
		<th class="align-center"></th>
	</tr></thead>
	{if !empty($list_items)}
		{foreach name=i from=$list_items item = _oItem}
		<tr class="text-nowrap">
			<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
			<td class="align-center text-right">{$clsISO->convertTimeToText($_oItem.payment_date, true)}</td>
			<td class="align-center text-right">{$clsISO->convertTimeToText($_oItem.account_date, true)}</td>
			<td class="align-center text-left">{$_oItem.code}</td>
			<td class="align-center text-left">{$clsFund->short_content($_oItem.content,40)}</td>
			<td class="align-center text-left">{$_oItem.bank_account_name}</td>
			<td class="align-center text-right">
				{$clsISO->formatNumberToEasyRead($_oItem.amount)}
				{$clsISO->getRate()}
			</td>
			<td class="align-center text-center">
				<button onclick="$Core.fund.open(this,event)" gr="{$_oItem.gr}" fund_id="{$_oItem.fund_id}" class="btn btn-sm btn-icon btn-outline-default">
					<i class="bx bx-pencil"></i>
				</button>
			</td>
		</tr>
		{/foreach}
	{/if}
</table>
<div id="pager_table_report" class="easyui-pagination" pageNumber="{$current_page}" 
	pageList="[{$per_page},30,50,100]" ></div>
{else}
<div class="d-flex flex-column align-items-center justify-content-center">
	<img src="{$URL_IMAGES}/illustration-empty-results.svg" class="w-px-200" />
	<p class="mb-0 text-muted">Chưa có dữ liệu</p>
</div>
{/if}