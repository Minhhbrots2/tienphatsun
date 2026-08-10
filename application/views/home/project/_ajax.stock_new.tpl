{if !empty($list_stocks)}
	{foreach name=i from=$list_stocks item = _oStock}
		<tr class="p_row{if $_oStock.agency_id eq $smarty.const._AGENCY_CNCN_ID} bg-purple{elseif $_oStock.agency_id eq $smarty.const._AGENCY_FH_ID} bg-label-fh{/if}">
			{foreach from=$lst_config_column item=_field key=key name=i_field}
				{$clsStock->getFieldStock($smarty.const._BLOCK_TYPE_LOWFLOOR_SALE,$_field,$_oStock,$arr_props_cached,$arr_property_cached)}
			{/foreach}
		</tr>
	{/foreach}
{else}
	<tr>
		<td colspan="{$lst_config_column|@count}">
			<div class="p-5 text-center">
				<img src="{$URL_IMAGES}/table-no-data.png" width="150px" />
				<p>Không có dữ liệu</p>
			</div>
		</td>
	</tr>
{/if}