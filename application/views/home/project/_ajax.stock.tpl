{if !empty($list_stocks)}
	{foreach name=i from=$list_stocks item = _oStock}
	{assign var = stock_id value = $_oStock.stock_id}
	{assign var = status_id value = $_oStock.status_id}
	{assign var = more_information value = $_oStock.more_information}
	<tr class="p_row{if $_oStock.agency_id eq $smarty.const._AGENCY_CNCN_ID} bg-purple{elseif $_oStock.agency_id eq $smarty.const._AGENCY_FH_ID} bg-label-fh{/if}">
		<td class="text-left">
		{if $deviceType eq 'phone'}
			<a href="javascript:void(0);" class="text-link{if $_oStock.agency_id eq $smarty.const._AGENCY_CNCN_ID} text-main{/if} cursor-pointer" onClick="$Core.helper.open_stock('{$stock_id}')">{$_oStock.ms_code}</a>
			{if $view_stock_resource eq '1'}
				{assign var = agency_id value = $_oStock.agency_id}
				<span class="badge fs-11 bg-label-secondary">{$arr_props_cached.$agency_id}</span>
			{/if}
		{else}
			<a href="javascript:void(0);" class="text-link{if $_oStock.agency_id eq $smarty.const._AGENCY_CNCN_ID} text-main{/if} cursor-pointer" data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$stock_id}&stock_type={$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="{if $deviceType eq 'phone'}330{else}350{/if}">{$_oStock.ms_code}</a>
			{if $view_stock_resource eq '1'}
				{assign var = agency_id value = $_oStock.agency_id}
				<span class="badge fs-11 bg-label-secondary" title="{$arr_property_cached.$agency_id}">{$arr_props_cached.$agency_id}</span>
			{/if}
		{/if}
		</td>
		<td class="text-left">
			{if !empty($_oStock.block_id)}
				{assign var = block_id value = $_oStock.block_id}
				{$arr_property_cached.$block_id}
			{else}
				---
			{/if}
		</td>
		<td class="text-left">
			{if !empty($_oStock.building_id)}
				{assign var = building_id value = $_oStock.building_id}
				{$arr_property_cached.$building_id}
			{else}
				---
			{/if}
		</td>
		<td class="text-left">
			{if $_oStock.type_id gt '0'}
				{assign var = type_id value = $_oStock.type_id}
				{$arr_property_cached.$type_id}
			{/if}
		</td>
		
		<td class="text-center">
			{$clsISO->_roundFix($more_information.DT_TT,1)}
			m<sup>2</sup>
		</td>
		<td class="text-center">
			{$clsISO->_roundFix($more_information.DT_Tim,1)}
			m<sup>2</sup>
		</td>
		<td  class="text-left">
			{if !empty($more_information.TCBG)}
				{$more_information.TCBG}
			{/if}
		</td>
		<td class="text-center">
			{if !empty($_oStock.home_direction_id)}
				{assign var = home_direction_id value = $_oStock.home_direction_id}
				{$arr_property_cached.$home_direction_id}
			{else}
				---
			{/if}
		</td>
		<!-- <td class="text-center">
			{if !empty($more_information.total_price)}
				{$clsISO->formatPrice($more_information.total_price)}
			{/if}
		</td> -->
		<td class="text-center">
			{if !empty($more_information.total_price_vat)}
				{$clsISO->formatPriceV2($clsISO->processSmartNumber($more_information.total_price_vat))} tỷ
			{elseif !empty($more_information.total_price_early)}
				{$clsISO->formatPriceV2($clsISO->processSmartNumber($more_information.total_price_early))} tỷ(TTS)
			{elseif !empty($more_information.total_price_progress)}
				{$clsISO->formatPriceV2($clsISO->processSmartNumber($more_information.total_price_progress))} tỷ(TTTĐ)
			{elseif !empty($more_information.total_price_bank_12)}
				{$clsISO->formatPriceV2($clsISO->processSmartNumber($more_information.total_price_bank_12))} tỷ(V12T)
			{elseif !empty($more_information.total_price_bank)}
				{$clsISO->formatPriceV2($clsISO->processSmartNumber($more_information.total_price_bank))} tỷ(V24T)
			{elseif !empty($more_information.total_price_bank_36)}
				{$clsISO->formatPriceV2($clsISO->processSmartNumber($more_information.total_price_bank_36))} tỷ(V36T)
			{else}
				<strong class="text-main">Check admin</strong>
			{/if}
		</td>
		<!--<td class="text-right">
			{if !empty($more_information.total_price_bank)}
				{$clsISO->formatPriceV2($more_information.total_price_bank)} tỷ
			{/if}
		</td>
		<td class="text-right">
			{if !empty($more_information.total_price_bank_36)}
				{$clsISO->formatPriceV2($more_information.total_price_bank_36)} tỷ
			{/if}
		</td>
		<td class="text-right">
			{if !empty($more_information.total_price_progress)}
				{$clsISO->formatPriceV2($more_information.total_price_progress)} tỷ
			{/if}
		</td>
		<td class="text-right">
			{if !empty($more_information.total_price_early)}
				{$clsISO->formatPriceV2($more_information.total_price_early)} tỷ
			{/if}
		</td> -->
		<td class="text-left">
			{if !empty($more_information.csbh)}
				{$more_information.csbh}
			{/if}
		</td>
		<td class="text-left">
			{if !empty($more_information.deposit_date)}
				{$more_information.deposit_date}
			{/if}
		</td>
		<!--<td class="text-left">
			{if !empty($more_information.cs_policy_ns)}
				{$more_information.cs_policy_ns}
			{/if}
		</td> -->
		<td class="text-center">
			{if !empty($more_information.price_temporary_ns)}
				<a href="{$more_information.price_temporary_ns}" target="_blank">
					{$clsISO->makeIcon('bx-link-external','PTG TẠM TÍNH')}
				</a>
			{/if}
		</td>
		<td class="text-left">
			{if !empty($more_information.contract_type_id)}
				{assign var = contract_type_id value = $more_information.contract_type_id}
				{$arr_property_cached.$contract_type_id}
			{else}
				---
			{/if}
		</td>
		<td class="text-left">
			{if !empty($more_information.invest_fund_id)}
				{assign var = invest_fund_id value = $more_information.invest_fund_id}
				{$arr_property_cached.$invest_fund_id}
			{else}
				---
			{/if}
		</td>
		<!-- <td class="text-left">
			{if !empty($more_information.contract_subject_id)}
				{assign var = contract_subject_id value = $more_information.contract_subject_id}
				{$arr_property_cached.$contract_subject_id}
			{/if}
		</td> -->
		<td>
			{if !empty($more_information.bank_id)}
				{assign var = bank_id value = $more_information.bank_id}
				{$arr_property_cached.$bank_id}
			{else}
				---
			{/if}
		</td>
		<td>
			{if !empty($more_information.notes)}
				{$more_information.notes}
			{/if}
		</td>
		<!--<td>
			{if !empty($more_information.bank_second_id)}
				{assign var = bank_second_id value = $more_information.bank_second_id}
				{$arr_property_cached.$bank_second_id}
			{/if}
		</td>
		<td>
			{if !empty($more_information.sale_status_id)}
				{assign var = sale_status_id value = $more_information.sale_status_id}
				{$arr_property_cached.$sale_status_id}
			{/if}
		</td>
		<td>
			{if !empty($more_information.agent_lock_id)}
				{assign var = agent_lock_id value = $more_information.agent_lock_id}
				{$arr_property_cached.$agent_lock_id}
			{/if}
		</td>
		<td>
			{if !empty($more_information.deposit_agent_id)}
				{assign var = deposit_agent_id value = $more_information.deposit_agent_id}
				{$arr_property_cached.$deposit_agent_id}
			{/if}
		</td>
		 <td>
			{if !empty($more_information.layout_url)}{/if}
		</td>
		<td>
			{if !empty($_oStock.html_price_sheets)}
				{$_oStock.html_price_sheets}
			{/if}
		</td>
		<td class="text-center">
			{$_oStock.status_name}
		</td> -->
	</tr>
	{/foreach}
{else}
	<tr>
		<td colspan="25">
			<div class="p-5 text-center">
				<img src="{$URL_IMAGES}/table-no-data.png" width="150px" />
				<p>Không có dữ liệu</p>
			</div>
		</td>
	</tr>
{/if}