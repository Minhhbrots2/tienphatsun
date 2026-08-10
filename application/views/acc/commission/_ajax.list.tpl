{if !empty($list_items)}
	{foreach from=$list_items name=i item = _oC}
	{assign var = status_sale_id value = $_oC.status_sale_id}
	{assign var = status_agent_id value = $_oC.status_agent_id}
	{assign var = more_information value = $_oC.more_information}
	<!-- ondblclick="$Core.commission.open(this,event)"  -->
	<tr style="background:{$_oC.bgcolor}" commission_id="{$_oC.commission_id}">
		<td class="text-center">{$smarty.foreach.i.iteration}</td>
		<td>{$clsISO->convertTimeToText($more_information.contract_date)}</td>
		<td>{$more_information.staff_name}</td>
		<td>{$more_information.stock_code}</td>
		<td>{$more_information.agency_name}</td>
		<td>{$clsISO->formatPrice($more_information.contract_total)}</td>
		<td>{$clsISO->formatPrice($more_information.contract_comm_base)}</td>
		<td class="text-center">
			{$clsCommission->format_h2($more_information.commission_rate)}%
		</td>
		<td class="align-center text-left">
			{$clsISO->formatPrice($more_information.commission_amount)}
		</td>
		<td class="align-center noselect text-right">
			{$clsISO->formatPrice($more_information.sales_bonus_amount_in)}
		</td>
		<td class="align-center noselect text-right">
			{$clsISO->formatPrice($more_information.agent_bonus_amount_in)}
		</td>
		<td class="align-center noselect text-right">
			{$clsISO->formatPrice($more_information.vinclub_discount_amount)}
		</td>
		<td class="align-center noselect text-right">
			{$clsISO->formatPrice($more_information.project_dir_bonus_amount_in)}
		</td>
		<td class="align-center noselect text-right">
			{$clsISO->formatPrice($more_information.marketing_bonus_amount)}
		</td>
		<td class="align-center noselect text-left">
			{$clsISO->formatPrice($more_information.tax_vat_amount)}
		</td>
		<td class="align-center noselect text-left">
			{$clsISO->formatPrice($more_information.personal_tax_amount)}
		</td>
		<td class="align-center noselect bg-lighter text-right">
			{$clsISO->formatPrice($more_information.total_amount_in)}
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.advance_paid_amount)}
				{$clsISO->formatPrice($more_information.advance_paid_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.receivable_amount)}
				{$clsISO->formatPrice($more_information.receivable_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect text-center">
			{$arr_property_cached.$status_agent_id}
		</td>
		<td class="align-center noselect text-right">
			{$clsCommission->format_h2($more_information.agent_commision_rate)}%
		</td>
		<td class="align-center noselect text-right">
			<span>{$clsISO->formatPrice($more_information.agent_commision_amount)}</span>
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.agent_bonus_amount)}
				{$clsISO->formatPrice($more_information.agent_bonus_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect bg-lighter text-right">
			{if !empty($more_information.total_agent_amount)}
				{$clsISO->formatPrice($more_information.total_agent_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect text-center">
			{$arr_property_cached.$status_sale_id}
		</td>
		<td class="align-center noselect text-right">
			{$clsCommission->format_h2($more_information.pkd_commission_rate)}%
		</td>
		<td class="align-center noselect text-right">
			{$clsISO->formatPrice($more_information.pkd_commission_amount)}
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.pkd_support_amount)}
				{$clsISO->formatPrice($more_information.pkd_support_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.pkd_bonus_amount)}
				{$clsISO->formatPrice($more_information.pkd_bonus_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect bg-lighter text-right">
			{if !empty($more_information.pkd_total_amount)}
				{$clsISO->formatPrice($more_information.pkd_total_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<!-- Sale -->
		<td class="align-center noselect text-right">
			{$clsCommission->format_h2($more_information.sales_commission_rate)}%
		</td>
		<td class="align-center noselect text-right">
			{$clsISO->formatPrice($more_information.sales_commission_amount)}
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.sales_bonus_amount_out)}
				{$clsISO->formatPrice($more_information.sales_bonus_amount_out)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect bg-lighter text-right">
			{if !empty($more_information.sales_total_amount)}
				{$clsISO->formatPrice($more_information.sales_total_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<!-- End Sale -->
		<!-- Leader -->
		<td class="align-center noselect text-right">
			{if !empty($more_information.leader_commission_rate)}
				{$clsCommission->format_h2($more_information.leader_commission_rate)}%
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.leader_commission_amount)}
				{$clsISO->formatPrice($more_information.leader_commission_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.leader_commission_bonus_amount)}
				{$clsISO->formatPrice($more_information.leader_commission_bonus_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect bg-lighter text-right">
			{$clsISO->formatPrice($more_information.leader_total_amount)}
		</td>
		<!-- GĐ DA -->
		<td class="align-center noselect text-right">
			{$clsCommission->format_h2($more_information.project_dir_commission_rate)}%
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.project_dir_bonus_amount_out)}
				{$clsISO->formatPrice($more_information.project_dir_bonus_amount_out)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center bg-lighter noselect text-right">
			{if !empty($more_information.project_dir_total_amount)}
				{$clsISO->formatPrice($more_information.project_dir_total_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<!-- End GĐ DA -->
		<td class="align-center noselect text-right">
			{if !empty($more_information.extra_commission_rate)}
				{$clsISO->format_h2($more_information.extra_commission_rate)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.extra_commission_amount)}
				{$clsISO->formatPrice($more_information.extra_commission_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect text-right">
			{$more_information.project_name}
		</td>
		<td class="align-center noselect text-right">
			{$more_information.bedroom_name}
		</td>
		<td class="align-center noselect text-right">
			{if !empty($more_information.advance_paid_amount)}
				{$clsISO->formatPrice($more_information.advance_paid_amount)}
			{else}
				<span class="text-muted">0.00</span>
			{/if}
		</td>
		<td class="align-center noselect text-left">
			{if !empty($more_information.status_date)}
				{$clsISO->convertTimeToText($more_information.status_date)}
			{/if}
		</td>
		<td class="align-center noselect text-left">
			{if !empty($more_information.notes)}
				{$more_information.notes}
			{/if}
		</td>
		<!-- <td class="align-center noselect text-center">
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
					data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
				</button>
				<div class="dropdown-menu w-px-200">
					<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.commission.open(this,event)" 
						commission_id="{$_oC.commission_id}"><i class="bx bx-pencil me-1"></i> Sửa</a>
					<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.commission.delete(this,event)" 
						commission_id="{$_oC.commission_id}"><i class="bx bx-trash me-1"></i> Xóa</a>
				</div>
			</div>
		</td> -->
	</tr>
	{/foreach}
{/if}