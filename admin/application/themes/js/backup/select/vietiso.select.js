/*
     VietISO Select Dropdown
     
==================================*/

var iZiSelect = 99999;
(function($){
 	$.fn.extend({
        
 		vietisoSelect: function(options) {
			var defaults = {
                num: 5,
				timeOpen: 500,
				timeClose : 500,
				colorHover : '#DBF0FA'
			}
			var options =  $.extend(defaults, options);
            
            return this.each(function() {
                var opts = options;
                var obj = $(this);
                obj.find('select').each(function(){
                    iZiSelect--;
                    var iName = $(this).attr('name');
                    var titleSelect = $(this).find('option:selected').text();
                    if(titleSelect == '') titleSelect = $(this).find('option:first').text();
                    var valSelect = $(this).val();
                    var list = "<div>";
                    var i = 1;
                    $(this).find('option').each(function(){
                        if(valSelect == $(this).val()) list += "<p class='vietiso_select_active'><span class='title'>" + $(this).text() + "</span><span class='value'>" + $(this).val() + "</span></p>";
                        else list += "<p><span class='title'>" + $(this).text() + "</span><span class='value'>" + $(this).val() + "</span></p>";
                        if(i == options.num) { list += "</div><div>"; i=0; }
                        i++;
                    });
                    list += "</div>";
                    $(this).remove();
                    obj.append("<div class='vietiso_selectbox' style='z-index: " + iZiSelect + ";'><input name='" + iName + "' type='hidden' value='" + valSelect + "' /><div class='vietiso_selected'><span class='vietiso_selectbox_title'>" + titleSelect + "</span><span class='vietiso_selectbox_icon_dropdown'></span></div><div class='vietiso_select_list'><div class='vietiso_select_list_mask'>" + list + "</div></div></div>");
                    var item = obj.find('.vietiso_selectbox:last');
                    var widthSelectList = item.find('.vietiso_select_list_mask').outerWidth();
                    item.find('.vietiso_select_list').width(widthSelectList).hide();

                    item.find('.vietiso_selected').click(function(){
                            var iZ = $(this).parents('.vietiso_selectbox').find('.vietiso_select_list');
                            if(iZ.css('display') == 'none') iZ.fadeIn(opts.timeOpen);
                            else iZ.fadeOut(opts.timeOpen);
                    });
                    
                    var mouse_is_inside = false;
                    item.find('.vietiso_selected').mouseover(function(){
                        mouse_is_inside = true;
                    });
                    item.find('.vietiso_selected').mouseout(function(){
                        mouse_is_inside = false;
                    });
                    $(document).click(function() {
                        if(mouse_is_inside == false)
                            item.find('.vietiso_select_list').fadeOut(opts.timeClose);
                    });
                    
                    item.find('.vietiso_select_list p').click(function(){
                        $(this).parents('.vietiso_select_list').find('p.vietiso_select_active').removeClass('vietiso_select_active');
                        $(this).addClass('vietiso_select_active');
                        titleSelect = $(this).find('span.title').text();
                        valSelect = $(this).find('span.value').text();
                        item.find('input:hidden').val(valSelect);
                        item.find('.vietiso_selectbox_title').text(titleSelect);
                    });
                    $(".vietiso_select_list p", item).hover(function(){
                        $(this).css('background-color', opts.colorHover);
                    }, function(){
                        $(this).css('background-color', '#FFFFFF');
                    });
                    
                });

    		});
    	}
	});
})(jQuery);