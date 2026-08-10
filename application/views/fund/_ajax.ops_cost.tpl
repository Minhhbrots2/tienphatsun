{if $type eq "list"}
	{if !empty($list_ops_cost)}
		{foreach from=$list_ops_cost item = _oItem}
		{assign var = _more_information value = $_oItem.more_information}
		<tr>
			<td class="align-center text-left">{$_oItem.expense_date|date_format:"%d/%m/%Y"}</td>
			<td class="align-center text-left">{$_oItem.expense_code}</td>
			<td class="align-center text-left">{$clsOpsCost->short_content($_more_information.reason)}</td>
			<td class="align-center text-left">{$_oItem.type_name}</td>
			<td class="align-center text-left">{$_oItem.department_name}</td>
			<td class="align-center text-left">{$_more_information.proposer_name}</td>
			<td class="align-center text-left">{$_more_information.approver_name}</td>
			<td class="align-center text-right">{$clsISO->formatPrice($_oItem.amount)} {$clsISO->getRate()}</td>
			<td class="align-center text-left">{$_more_information.payment_method}</td>
			<td class="align-center text-left">{$_more_information.recipient_account}</td>
			<td class="align-center text-center">
				{if $_oItem.status_id}
				<label class="text-success">{$_oItem.status_name}</label>
				{else}
				<label class="text-danger">{$_oItem.status_name}</label>
				{/if}
			</td>
			<!-- <td class="align-center text-left">{$_more_information.notes}</td> -->
		</tr>
		{/foreach}
	{else}
		<tr class="nohover">
			<td colspan="11" class="text-center">
				<div class="py-3">
					<img src="{$URL_IMAGES}/table-no-data.png" width="150" />
					<p class="my-2 text-muted">Không có dữ liệu</p>
				</div>
			</td>
		</tr>
	{/if}
{elseif $type eq "dashboard"}
	{if !empty($list_ops_cost)}
		{foreach from=$list_ops_cost item = _oItem}
		{assign var = _more_information value = $_oItem.more_information}
		<tr>
			<td class="align-center text-left">{$_oItem.expense_date|date_format:"%d/%m/%Y"}</td>
			<td class="align-center text-left">{$_oItem.expense_code}</td>
			<td class="align-center text-left">{$clsOpsCost->short_content($_more_information.reason)}</td>
			<td class="align-center text-left">{$_oItem.type_name}</td>
			<td class="align-center text-left">{$_oItem.department_name}</td>
			<td class="align-center text-left">{$_more_information.proposer_name}</td>
			<td class="align-center text-left">{$_more_information.approver_name}</td>
			<td class="align-center text-left">{$clsISO->formatPrice($_oItem.amount)} {$clsISO->getRate()}</td>
			<td class="align-center text-left">{$_more_information.payment_method}</td>
			<td class="align-center text-left">{$_more_information.recipient_account}</td>
			<td class="align-center text-center">
				{if $_oItem.status_id}
				<label class="text-success">{$_oItem.status_name}</label>
				{else}
				<labelclass="text-danger">{$_oItem.status}</label>
				{/if}
			</td>
		</tr>
		{/foreach}
	{else}
		<tr class="nohover">
			<td colspan="6" class="text-center">
				<div class="py-3">
					<img src="{$URL_IMAGES}/table-no-data.png" width="150" />
					<p class="my-2 text-muted">Không có dữ liệu</p>
				</div>
			</td>
		</tr>
	{/if}
{/if}