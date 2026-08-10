<div class="container-xxl flex-grow-1 pt-2 container-p-y">
    <div class="col-12 col-md-8 mx-auto offset-lg-2 col-xxl-6">
		<div class="alert alert-warning text-center mb-2">
			<a href="javascript:;" class="text-main d-flex font-bold text-upper align-items-center justify-content-center fs-20" campaign_id="{$oneCampaign.campaign_id}"><img src="{$URL_IMAGES}/gift-icon-hot.gif" class="w-px-30" /> {$title_block_page}</a>
		</div>
		{assign var = gId value = $clsISO->getUniqid()}
		{if $block_name eq 'top_ranking'}
			<div class="card ranking h-100">
				<div class="card-body">
					{$core->getBlock('top_ranking', ['gId' => $gId])}
				</div>
			</div>
		{else}
			{$core->getBlock($block_name, ['gId' => $gId,'type'=>$type])}
		{/if}
    </div>
</div>