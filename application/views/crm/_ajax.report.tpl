<div class="py-3 px-3">
	{if $cell eq '1'}
		{if !empty($list_status_reports)}
			{foreach name=i from=$list_status_reports item = _oLine}
			<div class="form-group line my-1 form-row">
				<div class="col-6">{$_oLine.title}</div>
				<div class="col-6 text-right">{$_oLine.total_customers} 
					<span class="green">({$_oLine.percent}%)</span>
				</div>
			</div>
			{/foreach}
		{/if}
	{elseif $cell eq '2'}
		{if !empty($list_criterias)}
			{foreach name=i from=$list_criterias item = _oLine}
			<div class="form-group my-1 line form-row">
				<div class="col-6">{$_oLine.title}</div>
				<div class="col-6 text-right">{$_oLine.total} </div>
			</div>
			{/foreach}
		{/if}
	{elseif $cell eq '4'}
		{if !empty($list_products)}
			{foreach name=i from=$list_products item = _oLine}
			<div class="form-group line my-1 form-row">
				<div class="col-6">{$_oLine.title}</div>
				<div class="col-6 fw-bold text-main text-right">{$_oLine.total_billings} </div>
			</div>
			{/foreach}
		{/if}
	{elseif $cell eq '5'}
		{if !empty($list_products)}
			{foreach name=i from=$list_products item = _oLine}
			<div class="form-group line my-1 form-row">
				<div class="col-6">{$_oLine.title}</div>
				<div class="col-6 fw-bold text-main text-right">{$clsISO->priceFormat($_oLine.total_grands)} </div>
			</div>
			{/foreach}
		{/if}
	{/if}
</div>