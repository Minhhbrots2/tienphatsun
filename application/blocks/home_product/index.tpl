<section class="section section-xss our-beers-slider-block">
	<div class="container">
		<div class="text-center o-text__heading" data-aos="fadeIn">
			<h2 class="o-text__heading-2 fs-42">Martens Beers</h2>
			{assign var = Product_Intro_Homepage value = $clsConfiguration->getValue('SiteMsg_Product_Intro_Homepage')}
			{if $Product_Intro_Homepage ne ''}
			<div class="o-text__tigh">
				<div class="o-text__intro">
					{$Product_Intro_Homepage|html_entity_decode}
				</div>
			</div>
			{/if}
			<div class="divider">
				<img src="{$URL_IMAGES}/after_title.png" />
			</div>
		</div>
	</div>	
	<div class="container-fluid">
		<div class="row">
			<div class="beer-slider" id="beer-slider">
			{foreach name=i from=$list_products item = product}
				{assign var = _product_id value = $product.product_id}
				{assign var = _title value = $clsProduct->getTitle($_product_id)}
				{assign var = _link value = $clsProduct->getLink($_product_id)}
				<div class="slider-item position-relative">
					<a class="d-block" href="{$_link}">
						<img src="{$clsProduct->getImage($_product_id, 400, 500)}" width="100%" class="img-fluid" alt="{$_title}" />
					</a>
					<div class="product-slide animated fadeInUp">
						<div class="index">{$smarty.foreach.i.iteration}</div>
						<span class="title">{$_title}</span>
						<a href="{$_link}" class="text-orange" title="{$_title}">
							{$core->get_Lang('View detail')} {$core->makeIcon('long-arrow-right')}
						</a>
					</div>
				</div>
			{/foreach}
			</div>
		</div>
	</div>
</section>
