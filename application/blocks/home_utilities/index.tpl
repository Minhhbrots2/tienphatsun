<section class="section section-xss home-utilities-block">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-8 col-md-offset-2 offset-lg-2 text-center">
				<div class="o-text__heading" data-aos="fadeIn">
					<h2 class="o-text__heading-2">Hiện thực giấc mơ</h2>
					<div class="o-text__tigh">
						{assign var = Food_Intro_Homepage value = $clsConfiguration->getValue('SiteMsg_Food_Intro_Homepage')}
						{if $Food_Intro_Homepage ne ''}
						<div class="o-text__intro">
							{$Food_Intro_Homepage|html_entity_decode}
						</div>{/if}					
					</div>
					<div class="divider">
						<img src="{$URL_IMAGES}/after_title.png" alt="{$core->get_Lang('Promotions')}" />
					</div>
				</div>
			</div>
		</div>
		{if !empty($blocks_info)}		
		<div class="home-utilities-body">
			<div class="row">
				{foreach name=i from=$blocks_info item = block}
				<div class="col-xs-12 col-md-4">
					<a href="{$block.link}" title="{$block.title}" class="awe__util-item">
						<img class="awe__util-item-image" src="{$block.image}" />
						<div class="figure">
							<span class="awe__util-item-subded">{$block.label}</span>
							<h3 class="awe__util-item-title">{$block.title}</h3>
						</div>
					</a>
				</div>
				{/foreach}
			</div>
		</div>
		{/if}
	</div>
</section>

