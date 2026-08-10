{if $list_foods[0].news_id ne ''}
	<div class="product-food">
	<h2 class="title-block">Thức ăn hợp với rượu này</h2>
	<div class="owl-news food-list" id="owl-news">
		{foreach name=i from=$list_foods item = _oneNews}
		{assign var = _news_id value = $_oneNews.news_id}
		{assign var = _title value = $clsNews->getTitle($_news_id, _oneNews)}
		<div class="food-item">
			<a class="block has-link" href="{$clsNews->getLink($_news_id)}" title="{$_title}">
				<div class="thumb img-shine">
					<img src="{$clsNews->getImage($_news_id, 600,400)}" class="img-responsive" alt="{$_title}" />
				</div>
				<div class="details">
					<h2 class="title">{$_title}</h2>
				</div>
			</a>
		</div>
		{/foreach}
	</div>
</div>
{/if}