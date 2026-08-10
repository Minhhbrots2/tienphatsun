/*
     VietISO Checkbox Plugin
     
==================================*/

var iZiCheckbox = 99999;
(function($){
 	$.fn.extend({
        
 		vietisoCheckbox: function(options) {
			var defaults = {
				timeOpen: 500,
				timeClose : 500,
				colorHover : '#DBF0FA'
			}
			var options =  $.extend(defaults, options);
            
            return this.each(function() {
                iZiCheckbox--;
                var opts = options;
                var obj = $(this);
                var checkboxTitle = obj.attr('title');
                obj.removeAttr('title');
                if(checkboxTitle == '' || checkboxTitle == undefined) buttonTitle = 'Checkbox Group';
				obj.append("<div class='vietiso_dropdown_checkbox' style='z-index: " + iZiCheckbox + "'><a class='vietiso_checkbox_title' title=''>" + checkboxTitle + "</a><span class='vietiso_dropdown_icon'></span><div class='vietiso_checkbox_list'><ul>   </ul></div></div>");
                var listCheckbox = '';
                
                obj.find('input:checkbox').each(function(){
                    var iTitle = $(this).attr('title');
                    var iVal = $(this).val();
                    if(iTitle == '' || iTitle == undefined) iTitle = iVal;
                    listCheckbox += "<li><a title=''>" + iTitle + "</a></li>";
                });
                var checkboxList = obj.find('.vietiso_checkbox_list'); 
                checkboxList.find('ul').html(listCheckbox);
                var widthCheckboxList = checkboxList.find('ul').width() + 20;
                checkboxList.width(widthCheckboxList).hide();
                obj.find('input:checkbox').hide();

                obj.find('.vietiso_checkbox_title').click(function(){
                    var dropdownList = obj.find('.vietiso_checkbox_list');
                    if(dropdownList.css('display') == 'none')
                    {
                        dropdownList.fadeIn(opts.timeOpen);
                    }
                    else
                        dropdownList.fadeOut(opts.timeClose);
                });
                
                $(".vietiso_checkbox_list li", obj).hover(function(){
                    $(this).css('background-color', opts.colorHover);
                }, function(){
                    $(this).css('background-color', '#F7F7F7');
                });
                var mouse_is_inside = false;
                obj.find('.vietiso_checkbox_title').mouseover(function(){
                    mouse_is_inside = true;
                });
                obj.find('.vietiso_checkbox_title').mouseout(function(){
                    mouse_is_inside = false;
                });
                obj.find('.vietiso_checkbox_list').mouseover(function(){
                    mouse_is_inside = true;
                });
                obj.find('.vietiso_checkbox_list').mouseout(function(){
                    mouse_is_inside = false;
                });
                obj.find('.vietiso_checkbox_list li a').click(function(){
                    var iIndex = $(this).parents('li').index();
                    var eItem = obj.find('input:checkbox:eq(' + iIndex + ')');
                    if(eItem.is(':checked')) eItem.removeAttr("checked");
                    else eItem.attr('checked', true);
                    iCheck();
                });
                $(document).click(function() {
                    if(mouse_is_inside == false)
                        obj.find('.vietiso_checkbox_list').fadeOut(opts.timeClose);
                });
                setInterval(function(){
                    iCheck();
                }, 700)
                function iCheck(){
                    obj.find('input:checkbox').each(function(){
                        var iIntdex = $(this).index();
                        var eCh = obj.find('.vietiso_checkbox_list').find('li:eq(' + iIntdex + ')').find('a');
                        if($(this).is(':checked')) eCh.addClass('vietiso_checkbox_active');
                        else eCh.removeClass('vietiso_checkbox_active');
                    });
                }
    		});
    	}
	});
})(jQuery);