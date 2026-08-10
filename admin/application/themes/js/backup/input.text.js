(function($){
 	$.fn.extend({
 		inputText: function() {
            return this.each(function() {
                var placeholder = $(this).val();
                $(this).addClass('js_text_default');
                $(this).focus(function(){
                    if($(this).val() == placeholder) {
                        $(this).val('');
                        $(this).removeClass('js_text_default');
                    } 
                });
                $(this).blur(function(){
                    if($(this).val() == '') {
                        $(this).val(placeholder);
                        $(this).addClass('js_text_default');
                    }
                });
    		});
    	}
	});
})(jQuery);