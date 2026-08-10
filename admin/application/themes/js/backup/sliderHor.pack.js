(function($){
 	$.fn.extend({
 		sliderHorPack: function(numShow, iWidth) {
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
                        iActive++;
                        iScoll+=iWidth;
                        obj.find('.mask').animate({scrollLeft: iScoll});
                    }
                    if(iActive>=iTotal-show+1) $(this).css({opacity: 0.5});
                    return false;
                });
                obj.find('.btn_left').click(function(){
                    obj.find('.btn_right').css({opacity: 1});
                    if(iActive>1) {
                        iActive--;
                        iScoll-=iWidth;
                        obj.find('.mask').animate({scrollLeft: iScoll});
                    }
                    if(iActive<=1) $(this).css({opacity: 0.5});
                    return false;
                });
    		});
    	}
	});
})(jQuery);