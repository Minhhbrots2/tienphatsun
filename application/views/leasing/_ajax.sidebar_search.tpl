{if !empty($lstAreaRange)}
<div class="card no-shadow border mb-4">
	<div class="card-header">
		<h5 class="card-title mb-0">Khoảng diện tích</h5>
	</div>
	<div class="card-body d-flex flex-wrap">
		{foreach from=$lstAreaRange item=_oItem}
			<a class="card-item item_area item_area_{$_oItem.property_id} w-50 text-black mb-1 {if $area_min eq $_oItem.min && $area_max eq $_oItem.max}active{/if}" href="javascript:void(0)" onClick="$Core.leasing.search_sidebar(this,event)" data-id="{$_oItem.property_id}" data-target="area" data-min="{$_oItem.min}" data-max="{$_oItem.max}">{$_oItem.title}m<sup>2</sup> ({$_oItem.total})</a>
		{/foreach}
	</div>
</div>
{/if}
{if !empty($lstPriceRangeLeasing)}
	<div class="card no-shadow border mb-4">
		<div class="card-header">
			<h5 class="card-title mb-0">Mức giá</h5>
		</div>
		<div class="card-body d-flex flex-wrap">
			{foreach from=$lstPriceRangeLeasing item=_oItem}
				<a class="card-item item_price item_price_{$_oItem.property_id} w-50 text-black mb-1 {if $price_min eq $_oItem.min && $price_max eq $_oItem.max}active{/if}" href="javascript:void(0)" onClick="$Core.leasing.search_sidebar(this,event)" data-id="{$_oItem.property_id}" data-target="price" data-min="{$_oItem.min}" data-max="{$_oItem.max}">{$_oItem.title} ({$_oItem.total})</a>
			{/foreach}
		</div>	
	</div>
{/if}