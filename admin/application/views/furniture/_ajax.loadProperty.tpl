{if !empty($lstFurniture)}
	{foreach from=$lstFurniture key=k item=itemFurniture}
		{assign var=property value=$itemFurniture.property}
		<div class="col-md-6 mb-3">
			<div class="border p-3 h-100 item_property" data-furniture_id="{$itemFurniture.furniture_id}">
				<label class="bold item_title" for="">{$itemFurniture.title}</label>
				{if !empty($property)}
					<div class="d-flex justify-content-end align-items-center">
						<input class="item_price" type="hidden" name="prices[{$itemFurniture.furniture_id}][]" value="{$property[0].price}">
						<span class="txt_price mr-2">{$clsISO->priceFormat($property[0].price)}đ</span> x 
						<input type="number" min="1" value="1" name="number[{$itemFurniture.furniture_id}][]" class="item_number text-center ml-2" style="width:50px">
					</div>
					<div class="lst_property">
						{foreach from=$property name=i item=item key=key}
							<label class="we-radio w-100" for="rdo_{$k}_{$key}">
								<input class="rdo_property" type="radio" id="rdo_{$k}_{$key}" name="property[{$itemFurniture.furniture_id}]" onChange="$Core.furniture.loadPrice(this,event,'radio')" value="{$item.title}" data-price="{$item.price}" {if $smarty.foreach.i.first}checked{/if}>
								<span>{$item.title}</span>
							</label>
						{/foreach}
					</div>
				{else}
					<div class="d-flex justify-content-end align-items-center">
						<input class="item_price" type="hidden" name="prices[{$itemFurniture.furniture_id}][]" value="{$itemFurniture.price}">
						<span class="txt_price mr-2">{$clsISO->priceFormat($itemFurniture.price)}đ</span> x 
						<input type="number" min="1" value="1" name="number[{$itemFurniture.furniture_id}][]" class="item_number text-center ml-2" style="width:50px">
					</div>
				{/if}
			</div>
		</div>
	{/foreach}
{/if}