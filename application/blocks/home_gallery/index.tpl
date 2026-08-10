<div class="gallery">
	<div class="o-text__heading">
		<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 text-center">
			<h2 class="o-text__heading-2">{$core->get_Lang('Image Galleries')}</h2>
			{assign var = Gallery_Intro_Homepage value = $clsConfiguration->getValue('SiteMsg_Gallery_Intro_Homepage')}
			{if $Gallery_Intro_Homepage ne ''}
			<div class="o-text__tigh">
				<div class="o-text__intro">
					{$Gallery_Intro_Homepage|html_entity_decode}
				</div>
			</div>
			{/if}
			<div class="divider">
				<img src="{$URL_IMAGES}/after_title.png" />
			</div>
		</div>
	</div>
	<section class="section snap text-center">
		<div class="linetest line-responsive" style="margin-top:-0.1em;">
			<div class="line4-sectiontest w-100" id="line2-middle">
				<div class="gallery-image">
					<div class="cell" id="left-cell">
						<div class="slideshow2" id="slideshow1">
							<div class="slideshow__containerleft js-slideshow" :class="{literal}{active:isActive1(index)}{/literal}" v-for="(slide, index) in testLibrary">
								<img @click="prev(slide.id);" class="leftTest" :style="{literal}{'cursor':'pointer'}{/literal}" width="100%" :src="'{$PCMS_URL}'+ slide.src+'?'+numImg" />
								<div class="side-info" id="gallery-left-info">
									<h3 class="content4 text-black" id="special-text">{literal}{{slide.title}}{/literal}</h3>
									<p class="content5 text-black">24.04.2020 - 01.05.2020</p>
								</div>
								<div id="gallery-left-footer" class="side-footer"></div>
								<div id="faded-left" class="faded">
									<a class="prev" @click="prev(slide.id)">&#10094;</a>
								</div>
							</div>
						</div>
					</div>
					<div class="cell">
						<div class="slideshow1" id="slideshow1">
							<div class="slideshow__containermid js-slideshow" :class="{literal}{active:isActive0(index)}{/literal}" v-for="slide, index in testLibrary">
								<img width="100%" :id="'bigImg'+slide.id" :src="'{$PCMS_URL}'+(slide.src)+'?'+numImg" class="middletest" />
								<div class="side-info" id="gallery-middle-info">
									<h3 class="content4" id="special-text">{literal}{{slide.title}}{/literal}</h3>
									<p class="content5 text-black">24.04.2020 - 01.05.2020</p>
								</div>
								<div class="side-footer" id="gallery-middle-footer">
									<img @click="down();" src="{$URL_IMAGES}/ic_action_name.png" class="expand-icon-library" id="gallery-middle-icon" />
								</div>
								<div id="panel" class="panel1">
									<div v-for="slide, index in testLibrary" :class="{literal}{active:isActive0(index)}{/literal}" class="small-images-slider">
										<div class="row">
											<div v-for="small in slide.smalls" class="small">
												<img @click="activeSrc(small.id,slide.id);" :id="'small-slider'+small.id" class="thumbnail" :src="'{$PCMS_URL}'+small.src+'?'+numImg" />
											</div>
										</div>
									</div>
								</div>
								<div class="mover">
									<div @click="prev(slide.id);" class="run" id="previous"> {$core->makeIcon('chevron-left')}</div>
									<div class="space"></div>
									<div @click="next(slide.id);" class="run" id="next"> {$core->makeIcon('chevron-right')}</div>
								</div>
							</div>
						</div>
					</div>
					<div class="cell" id="right-cell">
						<div class="slideshow3" id="slideshow1">
							<div class="slideshow__containerright js-slideshow" :class="{literal}{active:isActive2(index)}{/literal}" v-for="slide, index in testLibrary">
								<img @click="next(slide.id);" class="rightTest" :style="{literal}{'cursor':'pointer'}{/literal}" width="100%" :src="'{$PCMS_URL}' + slide.src + '?' + numImg" />
								<div class="side-info" id="gallery-right-info">
									<h3 class="content4" id="special-text">{literal}{{slide.title}}{/literal}</h3>
									<p class="content5 text-black">24.04.2020 - 01.05.2020</p>
								</div>
								<div id="gallery-right-footer" class="side-footer"></div>
								<div id="faded-right" class="faded">
									<a class="next" @click="next(slide.id)">&#10095;</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>