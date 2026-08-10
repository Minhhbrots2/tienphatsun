{if !empty( $lstProperty)}
	{section name=i loop=$lstProperty}
		{assign var = property_id value = $lstProperty[i].property_id}
		{assign var = more_information value = $lstProperty[i].more_information}
		<tr class="bold" id="{$lstProperty[i].property_id}">
			<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">
				{$core->makeIcon('bars')}
			</td>
			<td data-label="{$core->get_Lang('Actions')}">
				<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
					<button class="btn btn-default" onClick="open_property(this)" type="button" property_id="{$lstProperty[i].property_id}" property_type="{$property_type}">{$core->makeIcon('pencil')}</button>
					<button class="btn btn-default" onClick="delete_property(this)" type="button" property_id="{$lstProperty[i].property_id}" property_type="{$property_type}">{$core->makeIcon('trash')}</button>
				</div>
			</td>
			<td data-label="No.">{$smarty.section.i.iteration}</td>
			<td class="text-nowrap" data-label="{$core->get_Lang('Name')}">{$clsProperty->getTitle($property_id)}
				<a href="javascript:void(0);" onclick="open_property(this)" parent_id="{$property_id}" property_type="{$property_type}" property_id="0"><img src="{$URL_IMAGES}/add.png" width="25px" /></a>
			</td>
			<td data-label="{$core->get_Lang('Name')}">
				{if !empty($lstProperty[i].property_code)}
					{$lstProperty[i].property_code}
				{else}
				--
				{/if}
			</td>
			<td data-label="{$core->get_Lang('Name')}">
				{if !empty($lstProperty[i].title_vn)}
					{$lstProperty[i].title_vn}
				{else}
				--
				{/if}
			</td>
			<!-- MOC -->
				{if !empty($agency_hidden_stock_MOC)}
					{foreach from=$agency_hidden_stock_MOC item=hidden_stock key=key}
						{if !empty($hidden_stock.is_vin)}
							{assign var="key_hide_stock" value="MOC_stock_vin"}
						{else}
							{assign var="key_hide_stock" value="MOC_`$property_id`_`$hidden_stock.block_id`"}
						{/if}
						<td class="text-center">
							<label class="switch">
								<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.$key_hide_stock) && $more_information.$key_hide_stock eq '1'} checked{/if} to_field="{$key_hide_stock}" property_id="{$property_id}" value="1" class="switch_{$key}" />
								<span class="slider round"></span>
							</label>
						</td>
					{/foreach}
				{/if}
				<!-- user.FH -->										
				{if !empty($agency_hidden_stock_FH)}
					{foreach from=$agency_hidden_stock_FH item=hidden_stock key=key}
						{if !empty($hidden_stock.is_vin)}
							{assign var="key_hide_stock" value="FH_stock_vin"}
						{else}
							{assign var="key_hide_stock" value="FH_`$property_id`_`$hidden_stock.block_id`"}
						{/if}
						<td class="text-center">
							<label class="switch">
								<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.$key_hide_stock) && $more_information.$key_hide_stock eq '1'} checked{/if} to_field="{$key_hide_stock}" property_id="{$property_id}" value="1" class="switch_{$key}" />
								<span class="slider round"></span>
							</label>
						</td>
					{/foreach}
				{/if}
			
			<!-- End -->
			<td class="text-center">
				<label class="switch">
					<input type="checkbox"{if $lstProperty[i].is_trash eq '0'} checked{/if} onChange="$Core.property.set_status(this, event)" 
						property_id="{$lstProperty[i].property_id}" value="1" />
					<span class="slider round"></span>
				</label>
			</td>
		</tr>
	{/section}
{else}
	<tr>
		<td colspan="7" class="text-center">
			{$clsISO->renderHTMLNoDocument($core->get_Lang('Not any records(s) here'))}
		</td>
	</tr>
{/if}