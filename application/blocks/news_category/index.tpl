<div class="awe__profile menu_news_cat {if $deviceType ne 'phone'}bg-white p-3{/if} radius-4">
    <ul class="awe__post-menu">
		<li class="awe__post-menu-item d-flex align-items-center justify-content-between {if empty($cat_id)}active{else}text-black{/if}">
			<a href="/ban-tin.html" title="Tất cả">Tất cả</a> 
			<span class="text-muted">{$total_records}</span> 
		</li>
		{section name=i loop=$list_category}
		<li class="awe__post-menu-item d-flex align-items-center justify-content-between {if $cat_id eq $list_category[i].property_id}active{else}text-black{/if}">
			<a href="{$list_category[i].link}" title="{$list_category[i].title}">{$list_category[i].title}</a> 
			<span class="text-muted">{$list_category[i].total_records_in}</span> 
		</li>
		{/section}
    </ul>
</div>