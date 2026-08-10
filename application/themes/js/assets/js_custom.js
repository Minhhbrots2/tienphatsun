$(document).ready(function(){
	"use strict";	
	$("#menu").mmenu({
		navbars: [{
			position: 'bottom',
			content: [
				'<a href="#" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>',
				'<a href="#" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>',
				'<a href="#" target="_blank"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a>',
				'<a href="#" target="_blank"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a>',
				'<a href="#" target="_blank"><i class="fa fa-pinterest-square" aria-hidden="true"></i></a>',
				'<a href="#" target="_blank"><i class="fa fa-youtube-square" aria-hidden="true"></i></a>',
			]}
		]
	});
	/* SlideShow OWL */
	$(".slidehow_owl").owlCarousel({
		navigation: true,
		slideSpeed : 300,
		paginationSpeed : 800,
		rewindSpeed : 1000,
		singleItem : true,
		transitionStyle : "fade",
		addClassActive: true,
		autoHeight: true,
		autoPlay : 5000,
		responsive:true,
		navigationText: [
			"<a class='flex-prev-slideshow'><i class='fa fa-chevron-left'></i></a>",
			"<a class='flex-next-slideshow'><i class='fa fa-chevron-right'></i></a>"
		],
		afterMove: previousslide,
		beforeMove: nextslide,
	});
	function previousslide() {
		jQuery(".slidehow_owl .owl-item.active .slider-content").addClass('animated fadeInDown');
	}
	function nextslide() {
		jQuery(".slidehow_owl .owl-item .slider-content").removeClass('animated fadeInDown');
	}
	$('.btn-scroll').on('click', function(event) {
		var target = $(this).parent('.module-home');
		if(target.length) {
			event.preventDefault();
			$('html, body').animate({
				scrollTop: target.offset().top + target.outerHeight()
			}, 500);
		}
	});
	if($(window).width < 992){
		$('.slidehow_product').owlCarousel({
			singleItem : true,
			navigation: true,
			pagination : true,
			items:1,
			touchDrag: false,
			autoHeight: false,
			transitionStyle : "fade",
			slideSpeed : 200,
			paginationSpeed : 800,
			rewindSpeed : 1000,
			autoPlay : false,
			itemsCustom:[[480,2],[320,1],[768,2],[767,2],[991,3],[1200,4]],
			responsive:true,
			navigationText: [
				"<a class='flex-prev-slideshow'><i class='fa fa-long-arrow-left'></i></a>",
				"<a class='flex-next-slideshow'><i class='fa fa-long-arrow-right'></i></a>"
			]
		});
	}else{
		$('.slidehow_product').owlCarousel({
			singleItem : true,
			navigation: true,
			pagination : true,
			items:1,
			touchDrag: true,
			autoHeight: true,
			transitionStyle : "fade",
			slideSpeed : 200,
			paginationSpeed : 800,
			rewindSpeed : 1000,
			autoPlay : false,
			itemsCustom:[[480,2],[320,1],[768,2],[767,2],[991,3],[1200,4]],
			responsive:true,
			navigationText: [
				"<a class='flex-prev-slideshow'><i class='fa fa-long-arrow-left'></i></a>",
				"<a class='flex-next-slideshow'><i class='fa fa-long-arrow-right'></i></a>"
			]
		});
	}
	$('.about-block-1-slide').owlCarousel({
		singleItem : true,
		navigation: false,
		pagination : true,
		items:1,
		autoHeight: false,
		transitionStyle : "fade",
		slideSpeed : 200,
		paginationSpeed : 800,
		rewindSpeed : 1000,
		autoPlay : false,
		itemsCustom:[[480,2],[320,1],[768,2],[767,2],[991,3],[1200,4]],
		responsive:true,
		navigationText: [
			"<a class='flex-prev-slideshow'><i class='fa fa-long-arrow-left'></i></a>",
			"<a class='flex-next-slideshow'><i class='fa fa-long-arrow-right'></i></a>"
		]
	});
	$('.slider-favorite').owlCarousel({
		singleItem : true,
		navigation: true,
		pagination : true,
		items:1,
		autoHeight: false,
		transitionStyle : "fade",
		slideSpeed : 200,
		paginationSpeed : 800,
		rewindSpeed : 1000,
		autoPlay : false,
		responsive:true,
		navigationText: [
			"<a class='flex-prev-slideshow'><i class='fa fa-long-arrow-left'></i></a>",
			"<a class='flex-next-slideshow'><i class='fa fa-long-arrow-right'></i></a>"
		]
	});	
	$(".products_owl").owlCarousel({
		navigation: true,
		pagination : true,
		items:4,
		slideSpeed : 200,
		paginationSpeed : 800,
		rewindSpeed : 1000,
		//Autoplay
		autoPlay : false,
		itemsCustom:[[480,2],[320,1],[768,2],[767,2],[991,3],[1200,4]],
		responsive:true,
		navigationText: [
			"<a class='flex-prev-slideshow'><i class='fa fa-chevron-left'></i></a>",
			"<a class='flex-next-slideshow'><i class='fa fa-chevron-right'></i></a>"
		]
	});
	$(".list-review").owlCarousel({
		navigation: true,
		pagination : false,
		items:4,
		slideSpeed : 200,
		paginationSpeed : 800,
		rewindSpeed : 1000,
		autoPlay : false,
		itemsCustom:[[480,2],[320,1],[768,2],[767,2],[991,3],[1200,4]],
		responsive:true,
		navigationText: [
			"<a class='flex-prev-slideshow'><i class='fa fa-chevron-left'></i></a>",
			"<a class='flex-next-slideshow'><i class='fa fa-chevron-right'></i></a>"
		]
	});
	$(".list-video").owlCarousel({
		navigation: true,
		pagination : false,
		items:3,
		slideSpeed : 200,
		paginationSpeed : 800,
		rewindSpeed : 1000,
		autoPlay : false,
		itemsCustom:[[480,1],[320,1],[768,2],[767,2],[991,3],[1200,3]],
		responsive:true,
		navigationText: [
			"<a class='flex-prev-slideshow'><i class='fa fa-chevron-left'></i></a>",
			"<a class='flex-next-slideshow'><i class='fa fa-chevron-right'></i></a>"
		]
	});
	if($(window).width() > 991) {
		$(".products_owl_fix").owlCarousel({
			navigation: true,
			pagination : true,
			items:4,
			slideSpeed : 200,
			paginationSpeed : 800,
			rewindSpeed : 1000,
			//Autoplay
			autoPlay : false,
			itemsCustom:[[480,2],[250,1],[768,2],[767,2],[991,3],[1200,4]],
			responsive:true,
			navigationText: [
				"<a class='flex-prev-slideshow'><i class='fa fa-chevron-left'></i></a>",
				"<a class='flex-next-slideshow'><i class='fa fa-chevron-right'></i></a>"
			]
		});
	};
	$(".products_top_owl").owlCarousel({
		navigation: true,
		pagination : false,
		items:4,
		slideSpeed : 200,
		paginationSpeed : 800,
		rewindSpeed : 1000,
		//Autoplay
		autoPlay : false,
		itemsCustom:[[480,2],[250,1],[768,2],[767,2],[991,3],[1200,4]],
		responsive:true,
		navigationText: [
			"<a class='flex-prev-slideshow'><i class='fa fa-long-arrow-left'></i></a>",
			"<a class='flex-next-slideshow'><i class='fa fa-long-arrow-right'></i></a>"
		]
	});
	$(".products_owl_2").owlCarousel({
		navigation: true,
		pagination : true,
		items:1,
		slideSpeed : 200,
		paginationSpeed : 800,
		rewindSpeed : 1000,
		//Autoplay
		autoPlay : false,
		itemsCustom:[[480,2],[250,1],[768,2],[767,2],[991,3],[1200,4]],
		responsive:true,
		navigationText: [
			"<a class='flex-prev-slideshow'><i class='fa fa-chevron-left'></i></a>",
			"<a class='flex-next-slideshow'><i class='fa fa-chevron-right'></i></a>"
		]
	});	
	$("#products_owl_false").owlCarousel({
		navigation: true,
		pagination : false,
		items:4,
		slideSpeed : 200,
		paginationSpeed : 800,
		rewindSpeed : 1000,
		//Autoplay
		autoPlay : true,
		itemsCustom:[[480,2],[250,1],[768,2],[767,2],[991,3],[1200,4]],
		responsive:true,
		navigationText: [
			"<a class='flex-prev-slideshow'><i class='fa fa-chevron-left'></i></a>",
			"<a class='flex-next-slideshow'><i class='fa fa-chevron-right'></i></a>"
		]
	});		
});
// Hover Menu
$(".nav-item").hover(function(){
	var w = $(this).offset().left - $(this).parents('.container').offset().left + $(this).width()/2;
	$(this).find('.fixscaret').css('left',w);	
})
// Scroll To top
$(document).ready(function(){
	if ($('.back-to-top').length) {
		var scrollTrigger = 100, // px
			backToTop = function () {
				var scrollTop = $(window).scrollTop();
				if (scrollTop > scrollTrigger) {
					$('.back-to-top').addClass('show');
				} else {
					$('.back-to-top').removeClass('show');
				}
			};
		backToTop();
		$(window).on('scroll', function () {
			backToTop();
		});
		$('.back-to-top').on('click', function (e) {
			e.preventDefault();
			$('html,body').animate({
				scrollTop: 0
			}, 1000);
		});
	}
}); 
// Slider pro
$(document).ready(function() {
	$( '#example5' ).sliderPro({
		width: 680,
		height: 520,
		orientation: 'vertical',
		loop: false,
		arrows: true,
		buttons: false,
		autoplay: false,
		thumbnailsPosition: 'right',
		thumbnailPointer: true,
		thumbnailWidth: 130,
		breakpoints: {
			800: {
				thumbnailsPosition: 'bottom',
				thumbnailWidth: 200,
				thumbnailHeight: 100
			},
			500: {
				thumbnailsPosition: 'bottom',
				thumbnailWidth: 150,
				thumbnailHeight: 50
			}
		}
	});});
//Scroll fixed top change navabar//
$(document).ready(function(){
	var header = $('.category_products_menu');
	$(window).scroll(function(){
		var scroll = $(window).scrollTop();
		if(scroll >= 100){
			header.addClass('fixed');
		}
		else{
			header.removeClass('fixed');
		}
	});
}); 
//Scroll fixed top change navabar//
$(document).ready(function(){
	$('.block_categories_list li').click(function(){
		$('.block_categories_list li ul').slideToggle(400);
	});
});      
//Footer menu
$(document).ready(function(){
	$('.foot_mobile > .widget-item').click(function(){
		$(this).next().slideToggle('slow');
	});
});
//Fitter mobile
$('#show_danhmuc').click(function() {
	$('#list-collection').slideToggle();
	$('#fitter-mobile').hide();
	return false;
});
$('#show_boloc_mb').click(function() {
	$('#fitter-mobile').show();
	$('.header-filter-back').show();
	$('#list-collection').hide();
	$('.header-filter').hide();
	return false;
});
$('#hidden_boloc_mb').click(function() {
	$('#fitter-mobile').hide();
	$('.header-filter-back').hide();
	$('#list-collection').hide();
	$('.header-filter').show();
	return false;
});
$(document).ready(function() {
	$.fn.responsiveTabs = function() {
		this.addClass('responsive-tabs');
		this.append($('<span class="glyphicon glyphicon-triangle-bottom"><i class="fa fa-angle-down" aria-hidden="true"></i></span>'));
		this.append($('<span class="glyphicon glyphicon-triangle-top"><i class="fa fa-angle-up" aria-hidden="true"></i></span>'));
		this.on('click', 'li.active > a, span.glyphicon', function() {
			this.toggleClass('open');
		}.bind(this));
		this.on('click', 'li:not(.active) > a', function() {
			this.removeClass('open');
		}.bind(this));
	};
	$('.box_products_brands .nav.nav-tabs').responsiveTabs();
});