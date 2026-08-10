(function($){
 	$.fn.extend({
 		sliderHor: function(numShow, iWidth) {
            return this.each(function() {
                var obj = $(this);
                var show = numShow;
                var iTotal = obj.find('li').size();
                var iActive = 1;
                if(iWidth == 0) iWidth = obj.find('li').outerWidth();
                var iScoll = 0;
                obj.find('ul').width(iTotal * iWidth);
                obj.find('.btn_left').css({opacity: 0.5});
                obj.find('.btn_right').click(function(){
                    obj.find('.btn_left').css({opacity: 1});
                    if(iActive<iTotal-show+1) {
                    
                        $('.forcus .js_photoz .photo img').animate({width: 455, height: 202}, 250);
                        $('.forcus .js_photoz .photo').animate({right: 50, top: 17}, 250, function(){
                            $('.forcus .js_photoz').removeClass('photoizi').html('');
                            $('.forcus li:eq('+(iActive-1)+') .js_photo').show();
                            iActive++; iScoll+=iWidth; if(iActive>=iTotal-show+1) obj.find('.btn_right').css({opacity: 0.5}); /**/
                            obj.find('.mask').animate({scrollLeft: iScoll}, 500, 'easeOutCirc', function(){
                                $('.forcus li:eq('+(iActive-1)+') .js_photo').hide();
                                $('.forcus .js_photoz').html($('.forcus li:eq('+(iActive-1)+') .js_photo').html());
                                $('.forcus .js_photoz .photo img').animate({width: 505, height: 222}, 1000, 'easeOutBounce');
                                $('.forcus .js_photoz .photo').animate({right: 25, top: 20}, 1000, 'easeOutBounce', function(){
                                    $('.forcus .js_photoz').addClass('photoizi');
                                });
                            });
                        
                        });
                    }
                    return false;
                });
                obj.find('.btn_left').click(function(){
                    obj.find('.btn_right').css({opacity: 1});
                    if(iActive>1) {
                        /**/
                        $('.forcus .js_photoz .photo img').animate({width: 455, height: 202}, 250);
                        $('.forcus .js_photoz .photo').animate({right: 50, top: 17}, 250, function(){
                            $('.forcus .js_photoz').removeClass('photoizi').html('');
                            $('.forcus li:eq('+(iActive-1)+') .js_photo').show();
                            iActive--; iScoll-=iWidth; if(iActive<=1) obj.find('.btn_left').css({opacity: 0.5});
                            obj.find('.mask').animate({scrollLeft: iScoll}, 500, 'easeOutCirc', function(){
                                $('.forcus li:eq('+(iActive-1)+') .js_photo').hide();
                                $('.forcus .js_photoz').html($('.forcus li:eq('+(iActive-1)+') .js_photo').html());
                                $('.forcus .js_photoz .photo img').animate({width: 505, height: 222}, 1000, 'easeOutBounce');
                                $('.forcus .js_photoz .photo').animate({right: 25, top: 20}, 1000, 'easeOutBounce', function(){
                                    $('.forcus .js_photoz').addClass('photoizi');
                                });
                            });
                        });
                        /**/
                    }
                    return false;
                });
    		});
    	}
	});
})(jQuery);