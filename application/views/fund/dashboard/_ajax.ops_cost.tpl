{if !empty($list_ops_cost)}
	{foreach from=$list_ops_cost item = _oItem name=i}
		{assign var = _more_information value = $_oItem.more_information}
		<tr>
			<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
			<td class="align-center text-left">{$_oItem.accounting_date|date_format:"%d/%m/%Y"}</td>
			<td class="align-center text-left">{$_oItem.document_date|date_format:"%d/%m/%Y"}</td>
			<td class="align-center text-left">{$_oItem.document_no}</td>
			<td class="align-center text-left text-nowrap">{$clsOpsCost->short_content($_oItem.description)}</td>
			<td class="align-center text-left">{$_oItem.account_name}</td>
			<td class="align-center text-right">{$clsISO->formatPrice($_oItem.debit_amount)} {$clsISO->getRate()}</td>
			<td class="align-center text-right">{$clsISO->formatPrice($_oItem.credit_amount)} {$clsISO->getRate()}</td>
			<td class="align-center text-left">{$_oItem.project_name}</td>
			<td class="align-center text-left">{$_oItem.department_name}</td>
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