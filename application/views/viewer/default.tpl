<div class="container py-2">
	<div class="row"><div class="col-xs-12 col-md-8 offset-lg-2">
		<div id="slider" class="owl-carousel">
		{if !empty($list_files)}
			{foreach from=$list_files key = id item= path}
			<div class="item">
				<div class="bg-lightest{if $deviceType eq 'phone'} p-1{else} p-2{/if} text-center overflow-hidden">
					<img src="{$clsISO->genGoogleURL($id)}" class="img-fluid radius-3" style="max-height:calc(100% - 80px)" />
					<div class="py-2">{$path}</div>
				</div>
			</div>
			{/foreach}
		{/if}
		</div>
	</div></div>
</div>
{literal}
<script type="text/javascript">
	$('#slider').owlCarousel({
		items: 1,
		margin: 0,
		pagination: true,
		slideSpeed: 400,
		addClassActive: true,
		scrollPerPage: false,
		touchDrag: true,
		autoplay: false,
		autoHeight: false,
		nav: true,
		dots: false,
		loop: false,
		lazyLoad:false,
		responsive: {
			0: {items: 1},
			480: {items: 1},
			768: {items: 1},
			1200: {items: 1}
		},
		navText: [
			'<i class=\'fa fa-angle-left\'></i>',
			'<i class=\'fa fa-angle-right\'></i>'
		]
	});
</script>
{/literal}