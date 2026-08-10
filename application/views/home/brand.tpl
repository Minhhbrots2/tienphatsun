<link rel="stylesheet" type="text/css" href="{$URL_CSS}/brand.css?v={$upd_version}" />
<div class="hero-banner position-relative">
	{$core->getBlock('header')}
	<img class="hero-bg" src="{$banners_info.brand.image}" alt="{$titlePage}" />
	<div class="hero-content">
		<div class="hero-item__outline">
			<span>{$banners_info.brand.label}</span>
		</div>
		<h1 class="hero-item__heading">{$banners_info.brand.title}</h1>
		<div class="hero-item__caption">
			{$banners_info.brand.intro}
		</div>
	</div>
</div>
<div class="page_container bg-white">
	<section class="section section-xss">
		<div class="container">
			<div class="row awe__brand JS-amenitie">
				{section name=i loop=$list_brands}
				{assign var = brand_id value = $list_brands[i].partner_id}
				<div class="col-xs-12 col-lg-3">
					<div class="awe__brand-item">
						<span href="#amenitie{$brand_id}-wrap{$smarty.section.i.index}" class="awe__brand-item-link JS-amenitie{$brand_id}">
							<div class="awe__brand-logo">
								<img class="img-responsive" src="{$list_brands[i].image}" />
							</div>
							<div class="awe__brand-body text-center">
								<h2 class="awe__brand-title">{$list_brands[i].title}</h2>
							</div>
						</span>
					</div>
				</div>
				{/section}
			</div>
		</div>
	</section>
	{literal}
	<script type="text/javascript">
		$(function(){
			$('.JS-amenitie').magnificPopup({
				delegate: 'span',
				type: 'inline',
				mainClass: 'mfp-fade',
				removalDelay: 200,
				preloader: false,
				gallery: {
					enabled:true
				}
			});
		});
	</script>
	{/literal}	
	<section>
		{section name=i loop=$list_brands}
		{assign var = brand_id value = $list_brands[i].partner_id}
		<div id="amenitie{$brand_id}-wrap{$smarty.section.i.index}" class="mfp-hide">
			<div class="popup-wrap d-flex w-100">
				<div class="imgWrap">
					<img class="img-responsive" src="{$list_brands[i].image}" width="100%" height="auto" />
				</div>
				<div class="text">
					<h3 class="awe__mfp-title">
						<p class="title">{$list_brands[i].title}</p>
						<p class="title_cat">Giới thiệu</p>
					</h3>
					<div class="tinymce_content">
						{$list_brands[i].intro|html_entity_decode}
					</div>
				</div>
			</div>
		</div>	
		{/section}
	</section>
</div>

