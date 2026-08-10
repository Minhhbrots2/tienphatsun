<tr class="sop_row sop_add_line sop_row_{$stock_id}">
	<td width="50px">
		<div class="checkbox">
			<input type="checkbox" class="checkitem stock_item" value="{$stock_id}" />
			<label></label>
		</div>
	</td>
	<td width="120px">
		<div class="btn-group d-flex">
			<button type="button" class="btn btn-xs btn-default" title="{$core->get_Lang('Delete')}" onClick="delete_line(this, event)" stock_id="{$stock_id}">{$core->makeIcon('trash')}</button>
			<button type="button" class="btn btn-xs btn-default" title="{$core->get_Lang('Edit')}" onClick="open_stock(this, event)" stock_id="{$stock_id}">{$core->makeIcon('pencil')}</button>
		</div>
	</td>
	<td class="text-left">
		<input type="text" class="form-control text-bold stock_field w-100px" stock_id="{$stock_id}" data-field="ms_code" />
	</td>
	<td class="text-left">
		<input type="text" class="form-control stock_field price-In w-120px" stock_id="{$stock_id}" data-field="total_price_vat" />
	</td>
	<td class="text-left">
		<input type="text" class="form-control stock_field price-In w-120px" stock_id="{$stock_id}" data-field="total_price" />
	</td>
	<td class="text-left">
		<select class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="status_id">
			{$clsProperty->getSelectByProperty('_STATUS')}
		</select>
	</td>
	<td class="text-left">
		<select class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="agency_id">
			{$clsProperty->getSelectByProperty('_AGENCY')}
		</select>
	</td>
	{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
	<td class="text-left">
		<input type="text" class="form-control stock_field numberonly w-60px" stock_id="{$stock_id}" data-field="floor" />
	</td>
	<td class="text-left">
		<input type="text" class="form-control stock_field numberonly w-60px" stock_id="{$stock_id}" data-field="code" />
	</td>
	{else}
	<td class="text-left">
		<input type="text" class="form-control stock_field w-100px" stock_id="{$stock_id}" data-field="block_name" 
		value="{$list_stocks[i].block_name}" />
	</td>
	{/if}
	<td class="text-left">
		<div class="input-group-suffix">
			<input type="text" class="form-control stock_field numberonly w-90px" stock_id="{$stock_id}" data-field="DT_TT" />
			<span class="suffix">m2</span>
		</div>
	</td>
	<td class="text-left">
		<div class="input-group-suffix">
			<input type="text" class="form-control stock_field numberonly w-90px" stock_id="{$stock_id}" data-field="DT_Tim" />
			<span class="suffix">m2</span>
		</div>
	</td>
	{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
	<td class="text-left">
		<select class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="bedroom_id">
			{$clsProperty->getSelectByProperty('_BEDROOM')}
		</select>
	</td>
	{/if}
	<td class="text-left">
		<select class="form-control stock_field w-100px" stock_id="{$stock_id}" data-field="home_direction_id">
			{$clsProperty->getSelectByProperty('_DIRECTION')}
		</select>
	</td>
	<td class="text-left">
		<select class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="type_id">
			{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
				{$clsProperty->getSelectByProperty('_TYPE')}
			{else}
				{$clsProperty->getSelectByProperty('_TYPE_VILLA')}
			{/if}
		</select>
	</td>
	{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
	<td class="text-left">
		<select class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="view_id">
			{$clsProperty->getSelectByProperty('_VIEW')}
		</select>
	</td>
	{/if}
	<td class="text-left">
		<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="CSBH" value="" />
	</td>
	{if $block_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
	<td class="text-left">
		<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="deposit_date" value="" />
	</td>
	<td class="text-left">
		<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="bank_cart" value="" />
	</td>
	{/if}
	<td class="text-center">
		<button class="btn btn-xs btn-default" stock_id="{$stock_id}" onClick="open_notes(this, event)" title="{$core->get_Lang('Add Notes')}">{$core->makeIcon('plus-circle', $core->get_Lang('Notes'))}</button>
	</td>
</tr>