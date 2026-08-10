{if !empty($lstDepChild)}
	{foreach from=$lstDepChild item=_oItem key=key name=i}
		<div class="d-flex gap-2 align-items-center w-100 py-2">
			<span class="fs-14 text-right w-px-50 text-nowrap">{$_oItem.title}</span>
			<div class="d-flex flex-column" style="width:calc(100% - 125px)">
				<div class="progress w-100 h-px-15" >
				  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:{$clsISO->getRateNumber($_oItem.total_registed_hdmb,$_oItem.total_billing)}%;"></div>
				</div>
			</div>
			<span class="fs-12 text-nowrap w-px-75 text-right">
			<strong class="text-fs-15 text-main">{$_oItem.total_billing} GD</strong> {$clsISO->getRateNumber($_oItem.total_registed_hdmb,$_oItem.total_billing)}%</span>
		</div>
	{/foreach}
{/if}