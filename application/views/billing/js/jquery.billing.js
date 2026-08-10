$(function(){
	if(ACT == "mileston") {
		var alreadyScroll = 0;
		$Core.mileston.load_mileston({'page':1,'year':year});
		$(window).scroll(function(){
			if(isScrolledIntoView('#showmorethisresult') && $Core.mileston.scrolled == 0){
				$Core.mileston.scrolled = 1;
				$('.showmorethisresult').removeClass('d-none').trigger('click');
			}
		});
	}
	
}); 
$Core.mileston = {
	scrolled : !1,
	load_more: (_this, e) => {
		e.preventDefault();
		var page = $(_this).attr('page');
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.mileston.load_mileston({'page':page,'year':year}, "more");
		}
		return false;
	}, load_mileston: (options, type='') => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_mileston', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.showmorethisresult').addClass('d-none').removeClass('clicked');
			if(parseInt(respJson.total_record) <= (parseInt(respJson.per_page) * parseInt(respJson.current_page))){
				$Core.mileston.scrolled = 1;
			} else {
				$Core.mileston.scrolled = 0;
			}
			if(type == 'more'){
				$('.holder_mileston').append(respJson.html);
			}else{
				$('.holder_mileston').html(respJson.html);
				$(".total_record").text(respJson.total_record);
				$(".total_sale").text(respJson.total_sale);
				$(".total_bill").text(respJson.total_bill);
			}
			$('.showmorethisresult').attr('page', parseInt(respJson.current_page)+1);
		},'json');
	}
}