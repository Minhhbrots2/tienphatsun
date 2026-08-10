<ul class="list-unstyled d-flex flex-wrap" id="list_favourite" data-bs-popper="static">
	{if !empty($list_stocks)}
		{foreach from=$list_stocks item=_oItem name=i}
			{assign var=moreInformation value=$_oItem.more_information}
			<li class="py-1 item_pop_favourite w-50" >
				<div onClick="do_copy(this, event)" stock_id="{$_oItem.stock_id}" stock_code="{$_oItem.ms_code}" 
					class="d-flex justify-content-between align-items-center user-name" style="cursor: pointer">
					<span class="fw-medium">{$_oItem.ms_code}</span>
				</div>
			</li>
		{/foreach}							
	{else}
		<div class="d-flex flex-wrap w-100 align-items-center justify-content-center p-3">
			<img src="{$URL_IMAGES}/listing-empty.svg" width="90px">
			<p class="w-100 text-center">Danh sách trống</p>
		</div>
	{/if}
</ul>