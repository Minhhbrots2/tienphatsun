var iZiSelect = 9999;
(function($){
 	$.fn.extend({
        
 		select: function(options) {
			var defaults = {
				timeOpen: 0,
				timeClose : 0,
                height: 0,
                width: 0,
                title: ''
			}
			var options =  $.extend(defaults, options);
            var scoll = true; if(options.height == 0) scoll = false;
            return this.each(function() {
                var opts = options;
                iZiSelect--;
                var iName = $(this).attr('name');
                var iWidth;
                if(options.width == 0) iWidth = $(this).width();
                else iWidth = options.width;                    
                var titleSelect = $(this).find('option:selected').text();
                if(titleSelect == '') titleSelect = $(this).find('option:first').text();
                var valSelect = $(this).val();
                var list = "";
                var i = 1;
                $(this).find('option').each(function(){
                    if($(this).attr('selected') == 'selected')
                        list += "<p class='li'><a href='#' title='' ref='"+$(this).val()+"' class='"+$(this).val()+" active'>"+$(this).text()+"</a></p>";
                    else
                        list += "<p class='li'><a href='#' title='' ref='"+$(this).val()+"' class='"+$(this).val()+"'>"+$(this).text()+"</a></p>";
                });
                var style_ul;
                if(options.height != 0) style_ul = "height: " + options.height + "px;";
                $(this).after("<div id='js-select-"+iZiSelect+"' class='js-select-text "+ opts.iClass +"' style='z-index: " + iZiSelect + "; width: " + iWidth + ";'><a href='#' class='title'><span>"+titleSelect+"</span><b></b></a><div class='ul' style='"+style_ul+"'>"+list+"</div><input name='" + iName + "' type='hidden' value='" + valSelect + "' /></div>");
                $(this).remove();
                var item = $('#js-select-'+iZiSelect);
                if(scoll == true)
                    item.find('.ul').jScrollPane({showArrows: true});
                item.find('.ul').hide();

                item.find('a.title').click(function(){
                    $('.js-select-text').find('.ul').hide();
                    var iZ = $(this).parents('.js-select-text').find('.ul');
                    if(iZ.css('display') == 'none') iZ.fadeIn(opts.timeOpen);
                    else iZ.fadeOut(opts.timeOpen);
                    return false;
                });
                var fun_hide;
                var mou = false;
                $(".js-select-text").mouseout(function() {
                    mou = false;
                    fun_hide = setTimeout(function(){
                        item.find('.ul').fadeOut(opts.timeClose);
                    }, 1000)
                    $(this).find('span.select-tip').hide();
                });
                $(".js-select-text").mousemove(function(){
                    mou = true;
                    clearTimeout(fun_hide);
                    $(this).find('span.select-tip').show();
                });
                $(document).click(function(){
                    if(mou == false) {
                        item.find('.ul').hide();
                    }
                });
                
                item.find('.li a').click(function(){
                    $(this).parents('.ul').find('.active').removeClass('active');
                    $(this).addClass('active');
                    titleSelect = $(this).text();
                    var valSelect = $(this).attr('class');
					
                    item.find('input:hidden').val(titleSelect);
                    item.find('a.title span').text(titleSelect);
                    $(this).parents('.ul').hide();
                    return false;
                });
                
                if(opts.title != '') {
                    item.append("<span class='select-tip'>"+opts.title+"</span>");
                    item.find('span.select-tip').hide();
                }
    		});
    	}
	});
})(jQuery);