<div class="dbx-card h-100 mb-2">
	{assign var = uid value = $clsISO->getUniqid()}
	<div class="dbx-card__head">
		<span class="dbx-card__ic"><i class="bx bx-buildings"></i></span>
		<h5 class="dbx-card__title m-0">Lượt tra cứu dự án T{$start_time|date_format:"%m/%Y"}</h5>
	</div>
	<div class="dbx-card__body">
		{if !empty($arr_log)}
		<ul class="p-0 m-0 overflow-y-auto" style="max-height: 400px">
			{foreach from=$arr_log item=_oItem key=key name=i}
			<li class="d-flex align-items-center justify-content-between gap-2{if !$smarty.foreach.i.last} pb-2 mb-2 border-bottom{/if}">
				<h6 class="mb-0 fw-semibold text-truncate">{$_oItem.block_name}</h6>
				<h6 class="mb-0 text-main fw-bold text-nowrap">{$clsISO->formatNumber2($_oItem.total)} lượt</h6>
			</li>
			{/foreach}
		</ul>
		{else}
		<div class="dbx-empty"><img src="{$smarty.const.URL_IMAGES}/no-data.png" alt="" /><div class="dbx-empty__t">Chưa có lượt tra cứu</div></div>
		{/if}
	</div>
</div>
