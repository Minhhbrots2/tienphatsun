{if !empty($list_files)}
	{foreach name=i from=$list_files item = image}
		{if !empty($image)}
			<div class="awe__post-item awe__share-item" reg_date="{$_oShare.reg_date}" id="post_item_{$share_id}">							
				<div class="awe__post-item-body">
					<div class="awe__post-gallery gallery mb-2" data-fancybox="image" data-src="{$image}&sz=w1500">
						<img src="{$image}&sz=w760" alt="" class="w-100"> 
					</div>
				</div>
			</div>
		{/if} 
	{/foreach}
{/if} 