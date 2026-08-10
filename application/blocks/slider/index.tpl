{if $lstSlide[0].slide_id}
<div id="owl-slider" class="owl-slider">
	<!-- Slidehow Main -->
	{section name=i loop=$lstSlide}
	<div class="slideshow">
		<a class="d-block" href="{$lstSlide[i].link}" title="{$lstSlide[i].title}">
			<img src="{$lstSlide[i].image}?v={$upd_version}" width="100%" height="100%" alt="{$lstSlide[i].title}" class="attachment-original size-original" srcset="{$lstSlide[i].image}?v={$upd_version} 1144w, {$lstSlide[i].image}?v={$upd_version} 800w, {$lstSlide[i].image}?v={$upd_version} 768w" sizes="(max-width:1144px) 100vw, 1144px">
			<div class="figure">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-md-10 offset-md-1">
							<div class="figcaption{if $smarty.section.i.first} animated fadeInUp{/if}">
								<p class="subded">{$lstSlide[i].slogan}</p>
								<span class="big">{$lstSlide[i].intro}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	{/section}
</div>
{/if}