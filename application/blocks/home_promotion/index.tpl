<div class="section section-xss offers-block" data-aos="fade-up" data-aos-duration="800" data-aos-delay="0">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-8 offset-md-2 offset-lg-2 text-center">
				<div class="o-text__heading" data-aos="fadeIn">
					<h2 class="o-text__heading-2">{$core->get_Lang('Promotions')}</h2>
					<div class="o-text__tigh">
						{assign var = Promotion_Intro_Homepage value = $clsConfiguration->getValue('SiteMsg_Promotion_Intro_Homepage')}
						{if $Promotion_Intro_Homepage ne ''}
						<div class="o-text__intro">{$Promotion_Intro_Homepage|html_entity_decode}</div>						{/if}					
					</div>
					<div class="divider">
						<img src="{$URL_IMAGES}/after_title.png" alt="{$core->get_Lang('Promotions')}" />
					</div>
				</div>
			</div>
		</div>		
		<div class="offers-list">
			<div class="row">
			{if !empty($list_promotions)}
				{if $total_promotion eq '1'}
					<div class="col-xs-12 col-sm-12 col-md-4 d-none d-md-block d-lg-block">
						<div class="awe__placeholder">
							<div class="awe__placeholder-image d-flex align-items-center justify-content-center">
								<img src="{$URL_IMAGES}/laisla.png?v={$upd_version}" />
							</div>
							<div class="awe__placeholder-body">
								<div class="awe__placeholder-title">
									<span class="awe__placeholder-line higher mb-2"></span>
									<span class="awe__placeholder-line higher"></span>
								</div>
								<div class="o-text__bottom mt-4 d-flex">
									<div class="awe__placeholder-line shorter w-100"></div>
								</div>
							</div>
						</div>
					</div>
					{section name=i loop=$list_promotions}
						{assign var = _title value = $clsPromotion->getTitle($list_promotions[i].promotion_id)}	
						{assign var = _link value = $clsPromotion->getLink($list_promotions[i].promotion_id)}
						<div class="col-xs-12 col-sm-12 col-md-4">
							<div class="offer-item">
								<div class="img-shine img-entry-thumbnail">
									<img class="img-fluid" src="{$clsPromotion->getImage($list_promotions[i].promotion_id,406,490)}" alt="{$_title}" width="100%" />
								</div>
								<div class="offer-entry-details">
									<h2 class="o-text__title">{$_title}</h2>
									<!--<p class="limit_3line">{$clsPromotion->getIntro($list_promotions[i].promotion_id)}</p> -->
									<div class="o-text__bottom mt-3 d-flex">
										<a class="o-text__link" href="{$_link}" title="{$_title}">{$core->get_Lang('View detail')} {$core->makeIcon('long-arrow-right')}</a>
									</div>				
								</div>
								
							</div>
						</div>
					{/section}
					<div class="col-xs-12 col-sm-12 col-md-4 d-none d-md-block d-lg-block">
						<div class="awe__placeholder">
							<div class="awe__placeholder-image d-flex align-items-center justify-content-center">
								<img src="{$URL_IMAGES}/laisla.png?v={$upd_version}" />
							</div>
							<div class="awe__placeholder-body">
								<div class="awe__placeholder-title">
									<span class="awe__placeholder-line higher mb-2"></span>
									<span class="awe__placeholder-line higher"></span>
								</div>
								<div class="o-text__bottom mt-4 d-flex">
									<div class="awe__placeholder-line shorter w-100"></div>
								</div>
							</div>
						</div>
					</div>
				{elseif $total_promotion eq '2'}
					{section name=i loop=$list_promotions}
						{assign var = _title value = $clsPromotion->getTitle($list_promotions[i].promotion_id)}	
						{assign var = _link value = $clsPromotion->getLink($list_promotions[i].promotion_id)}
						<div class="col-xs-12 col-sm-12 col-md-4">
							<div class="offer-item">
								<div class="img-shine img-entry-thumbnail">
									<img class="img-fluid" src="{$clsPromotion->getImage($list_promotions[i].promotion_id,406,490)}" alt="{$_title}" width="100%" />
								</div>
								<div class="offer-entry-details">
									<h2 class="o-text__title">{$_title}</h2>
									<!--<p class="limit_3line">{$clsPromotion->getIntro($list_promotions[i].promotion_id)}</p> -->
									<div class="o-text__bottom mt-3 d-flex">
										<a class="o-text__link" href="{$_link}" title="{$_title}">{$core->get_Lang('View detail')} {$core->makeIcon('long-arrow-right')}</a>
									</div>				
								</div>
								
							</div>
						</div>
						{if $smarty.section.i.index eq '0'}
						<div class="col-xs-12 col-sm-12 col-md-4 d-none d-md-block d-lg-block">
							<div class="awe__placeholder">
								<div class="awe__placeholder-image d-flex align-items-center justify-content-center">
									<img src="{$URL_IMAGES}/laisla.png?v={$upd_version}" />
								</div>
								<div class="awe__placeholder-body">
									<div class="awe__placeholder-title">
										<span class="awe__placeholder-line higher mb-2"></span>
										<span class="awe__placeholder-line higher"></span>
									</div>
									<div class="o-text__bottom mt-4 d-flex">
										<div class="awe__placeholder-line shorter w-100"></div>
									</div>
								</div>
							</div>
						</div>
						{/if}
					{/section}
				{else}
					{section name=i loop=$list_promotions}
						{assign var = _title value = $clsPromotion->getTitle($list_promotions[i].promotion_id)}	
						{assign var = _link value = $clsPromotion->getLink($list_promotions[i].promotion_id)}
						<div class="col-xs-12 col-sm-12 col-md-4">
							<div class="offer-item">
								<div class="img-shine img-entry-thumbnail">
									<img class="img-fluid" src="{$clsPromotion->getImage($list_promotions[i].promotion_id,406,490)}" alt="{$_title}" width="100%" />
								</div>
								<div class="offer-entry-details">
									<h2 class="o-text__title">{$_title}</h2>
									<!--<p class="limit_3line">{$clsPromotion->getIntro($list_promotions[i].promotion_id)}</p>-->
									<div class="o-text__bottom mt-3 d-flex">
										<a class="o-text__link" href="{$_link}" title="{$_title}">{$core->get_Lang('View detail')} {$core->makeIcon('long-arrow-right')}</a>
									</div>				
								</div>
								
							</div>
						</div>
					{/section}
				{/if}
			{else}
				{if !empty($arr_placeholders)}
					{section name=i loop=$arr_placeholders}
					<div class="col-xs-12 col-sm-12 col-md-4">
						<div class="awe__placeholder">
							<div class="awe__placeholder-image d-flex align-items-center justify-content-center">
								<img src="{$URL_IMAGES}/laisla.png?v={$upd_version}" />
							</div>
							<div class="awe__placeholder-body">
								<div class="awe__placeholder-title">
									<span class="awe__placeholder-line higher mb-2"></span>
									<span class="awe__placeholder-line higher"></span>
								</div>
								<div class="o-text__bottom mt-4 d-flex">
									<div class="awe__placeholder-line shorter w-100"></div>
								</div>
							</div>
						</div>
					</div>
					{/section}
				{/if}
			{/if}
			</div>
		</div>
	</div>
</div>