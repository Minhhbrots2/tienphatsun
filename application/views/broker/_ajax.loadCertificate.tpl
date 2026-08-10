<div class="owl-carousel box_certificate">
	<div class="box_item_certificate">
		<div class="box_image"><img src="" alt=""></div>
		<div class="box_body"></div>
	</div>
</div>
{literal}
<script>
	$(document).ready(function(){
		$('.box_certificate').owlCarousel({
			loop:true,
			margin:10,
			responsiveClass:true,
			responsive:{
				0:{
					items:2,
					nav:true
				},
				600:{
					items:3,
					nav:false
				},
				992:{
					items:3,
					nav:true,
					loop:false
				},
				1200:{
					items:4,
					nav:true,
					loop:false
				}
			}
		})
	});
</script>
{/literal}