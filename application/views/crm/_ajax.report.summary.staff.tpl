<div class="py-3 px-3">
	{if $cell eq '1'}
		{if !empty($list_status_reports)}
			{foreach name=i from=$list_status_reports item = _oLine}
			<div class="form-group line form-row{if $smarty.foreach.i.iteration gt '10'} hidden{/if}">
				<div class="col-md-5">{$_oLine.title}</div>
				<div class="col-md-7 text-right">{$_oLine.total_customers} 
					<span class="green">({$_oLinepercent}%)</span>
				</div>
			</div>
			{/foreach}
		{/if}
	{elseif $cell eq '2'}
		{if !empty($list_couters)}
			{foreach name=i from=$list_couters item = _oLine}
			<div class="form-group line form-row{if $smarty.foreach.i.iteration gt '10'} hidden{/if}">
				<div class="col-md-5">{$_oLine.title}</div>
				<div class="col-md-7 text-right">{$_oLine.total} </div>
			</div>
			{/foreach}
		{/if}
	{elseif $cell eq '4'}
		{if !empty($list_products)}
			{foreach name=i from=$list_products item = _oLine}
			<div class="form-group line form-row{if $smarty.foreach.i.iteration gt '10'} hidden{/if}">
				<div class="col-md-5">{$_oLine.title}</div>
				<div class="col-md-7 text-right">{$_oLine.total} </div>
			</div>
			{/foreach}
		{/if}
	{elseif $cell eq '5'}
		{if !empty($list_products)}
			{foreach name=i from=$list_products item = _oLine}
			<div class="form-group line form-row{if $smarty.foreach.i.iteration gt '10'} hidden{/if}">
				<div class="col-md-5">{$_oLine.title}</div>
				<div class="col-md-7 text-right">{$clsISO->priceFormat($_oLine.total)} </div>
			</div>
			{/foreach}
		{/if}
	{/if}
</div>