$(function(){
	if($('.owl').length){
		console.log("sss");
		$('.owl').owlCarousel({
			margin:10,
			loop:true,
			nav: true,
			lazyLoad:true,
			dots:true,
			autoplay:false,
			responsiveClass:true,
			navText: ['<i class="fa fa-angle-left"></i>',
				'<i class="fa fa-angle-right"></i>'],
			responsive:{
				0:{items:1},
				1200:{items:1},
			}
		});
	}
})
$Core.search = {
	toggle_agent: function(_this, e){
		e.preventDefault();
		var t1 = $(_this).attr('t1'),
			t2 = $(_this).attr('t2'),
			status = $(_this).hasClass('hide-agent') ? 1 : 0;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=toggle_agent', {
			'status' : status
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(parseInt(status) == 1){ // Đang ẩn
				$(_this).text(t1).removeClass('hide-agent');
				$('.re__label-agency').removeClass('d-none');
			} else {
				$('.re__label-agency').addClass('d-none');
				$(_this).text(t2).addClass('hide-agent');
			}
		});
		return false;
	},
	downloadDocument : function(_this,e) {
		e.preventDefault();
		var item_parent = $(_this).closest(".item_document");
		 var linksHtml = '<html><head><title>Links</title></head><body>';
		var newWindow = window.open('', '_blank');
		$(".item_download",item_parent).each(function(index,elm){
			let href = $(elm).attr("href");
			linksHtml += '<p><a href="' + href + '" target="_blank"></a></p>';
		});
		linksHtml += '</body></html>';
		newWindow.document.open();
		newWindow.document.write(linksHtml);
		newWindow.document.close();
	},
}
