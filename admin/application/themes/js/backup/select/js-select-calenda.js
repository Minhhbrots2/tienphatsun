var iZiSelect = 9999;
(function($){
 	$.fn.extend({
        
 		select_calenda: function(options) {
			var defaults = {
				timeOpen: 0,
				timeClose : 0,
                height: 0,
                width: 0                
			}
			var options =  $.extend(defaults, options);
            
            return this.each(function() {
                var opts = options;
                var obj = $(this);
                obj.find('select:eq(0)').select({
                    iClass: 'js-select-day',
                    width: '40px',
                    title: 'Day'
                });
                obj.find('select:eq(0)').select({
                    iClass: 'js-select-month',
                    width: '40px',
                    title: 'Month'
                });
                obj.find('select:eq(0)').select({
                    iClass: 'js-select-year',
                    width: '60px',
                    height: 100,
                    title: 'Year'
                });
                obj.css('display', 'inline-block');
                var selectedmonth = obj.find('.js-select-month input').val();
                obj.find('.js-select-month .li a').click(function(){
                    var sMonth = $(this).text();
                    var sYear = parseInt(obj.find('.js-select-year a.title span').text());
                    var dayOfMonth = 0;
                    var month31 = ['01','03','05','07','08','10','12'];
                    var month30 = ['04','06','09','11'];
                    if(sMonth == '02') {dayOfMonth = 28;}
                    if(sMonth == '02' && (sYear%4)==0) {dayOfMonth = 29;}
                    if(sMonth != '02' && jQuery.inArray(sMonth,month31) > -1) {dayOfMonth = 31;}
                    if(sMonth != '02' && jQuery.inArray(sMonth,month30) > -1) {dayOfMonth = 30;}
                    
                    var eDay = obj.find('.js-select-day .ul');
                    if(dayOfMonth == 28) {
                        eDay.find('.li:eq(27)').show();
                        eDay.find('.li:eq(28)').hide();
                        eDay.find('.li:eq(29)').hide();
                        eDay.find('.li:eq(30)').hide();
                    }
                    if(dayOfMonth == 29) {
                        eDay.find('.li:eq(27)').show();
                        eDay.find('.li:eq(28)').show();
                        eDay.find('.li:eq(29)').hide();
                        eDay.find('.li:eq(30)').hide();
                    }
                    if(dayOfMonth == 30) {
                        eDay.find('.li:eq(27)').show();
                        eDay.find('.li:eq(28)').show();
                        eDay.find('.li:eq(29)').show();
                        eDay.find('.li:eq(30)').hide();
                    }
                    if(dayOfMonth == 31) {
                        eDay.find('.li:eq(27)').show();
                        eDay.find('.li:eq(28)').show();
                        eDay.find('.li:eq(29)').show();
                        eDay.find('.li:eq(30)').show();
                    }
                    var titleDay = parseInt(obj.find('.js-select-day a.title span').text());
                    if(titleDay>dayOfMonth)
                        obj.find('.js-select-day .li:eq(0) a').click();
                });
                obj.find('.js-select-month .li:eq('+(selectedmonth-1)+') a').click();
    		});
    	}
	});
})(jQuery);