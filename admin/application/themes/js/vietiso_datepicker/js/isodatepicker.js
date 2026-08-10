/*
 * VietISO Date Picker plug-in 1.1
 * http://www.vietiso.com/
 * Copyright (c) 2006 - 2013 Dung Luong Tien
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
//$(document).ready(function() {	
function initDateInOut(id){
	$('#'+id).find('.iso_checkin_hidden').datepicker({
		showOn: "button",
		dateFormat: 'yy-mm-dd',
		buttonImageOnly: false,
		showWeek: true,
		firstDay: 1,		  
		minDate: 0, maxDate: "+11M",
		altField: ".iso_checkin_alternate",
		altFormat: "yy-m-d",
		onSelect: function(dateStr){
			setCheckIn($(this));							
		}
	});
	$('#'+id).find('.iso_checkout_hidden').datepicker({
		showOn: "button",
		dateFormat: 'yy-mm-dd',
		buttonImageOnly: false,
		showWeek: true,
		firstDay: 1,		  
		minDate: "+1D", maxDate: "+11M",
		altField: ".iso_checkout_alternate",
		altFormat: "yy-m-d",
		onSelect: function(dateStr){
			setCheckOut($(this));	
		}
	});
	$('#'+id).find('.iso_checkin_day').change(function(){
		var _this = $(this);
		if(_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month').val()!=0){
			_this.closest('.tblCalendarHolder').find('.iso_checkin_alternate,.iso_checkin_hidden').val(_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month').val()+'-'+_this.closest('.tblCalendarHolder').find('.iso_checkin_day').val());
			loadCheckInOutChange(_this);	
		}
	});
	$('#'+id).find('.iso_checkin_year_month').change(function(){
		var _this = $(this);
		$.ajax({
			type: 'POST',
			url: path_ajax_datepicker+'/ajaxdatepicker.php',
			data:{'year_month':_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month').val(),'slted_day':_this.closest('.tblCalendarHolder').find('.iso_checkin_day').val(),'type':'getDay'},
			dataType:'html',
			success: function(html){
				_this.closest('.tblCalendarHolder').find('.iso_checkin_day').html(html);
				if(_this.closest('.tblCalendarHolder').find('.iso_checkin_day').val()!=0){
					_this.closest('.tblCalendarHolder').find('.iso_checkin_alternate,.iso_checkin_hidden').val(_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month').val()+'-'+_this.closest('.tblCalendarHolder').find('.iso_checkin_day').val());
					loadCheckInOutChange(_this);
				}
			}
		});			
	});
	$('#'+id).find('.iso_checkin_hidden').change(function(){
		var _this = $(this);
		_this.closest('.tblCalendarHolder').find('.iso_checkin_alternate').val(_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month').val()+'-'+_this.closest('.tblCalendarHolder').find('.iso_checkin_day').val());
	});
	$('#'+id).find('.iso_checkout_day').change(function(){
		var _this = $(this);
		if(_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month').val()!=0){
			_this.closest('.tblCalendarHolder').find('.iso_checkout_alternate,.iso_checkout_hidden').val(_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month').val()+'-'+_this.closest('.tblCalendarHolder').find('.iso_checkout_day').val());
			loadCheckOutInChange(_this);	
		}
	});
	$('#'+id).find('.iso_checkout_year_month').change(function(){
		var _this = $(this);
		$.ajax({
			type: 'POST',
			url: path_ajax_datepicker+'/ajaxdatepicker.php',
			data:{'year_month':_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month').val(),'slted_day':_this.closest('.tblCalendarHolder').find('.iso_checkout_day').val(),'type':'getDay'},
			dataType:'html',
			success: function(html){
				_this.closest('.tblCalendarHolder').find('.iso_checkout_day').html(html);
				if(_this.closest('.tblCalendarHolder').find('.iso_checkout_day').val()!=0){
					_this.closest('.tblCalendarHolder').find('.iso_checkout_alternate,.iso_checkout_hidden').val(_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month').val()+'-'+_this.closest('.tblCalendarHolder').find('.iso_checkout_day').val());
					loadCheckOutInChange(_this);
				}
			}
		});			
	});	
	$('#'+id).find('.iso_checkout_hidden').change(function(){
		_this.closest('.tblCalendarHolder').find('.iso_checkout_alternate').val(_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month').val()+'-'+_this.closest('.tblCalendarHolder').find('.iso_checkout_day').val());
	});
	$.ajax({
		type: 'POST',
		url: path_ajax_datepicker+'/ajaxdatepicker.php', 
		data:{'type':'initDateInOut','checkin':$('#'+id).find('.iso_checkin_hidden').val(),'checkout':$('#'+id).find('.iso_checkout_hidden').val()},
		dataType:'html',
		success: function(html){
			var htm = html.split('$$');
			$('#'+id).find('.iso_checkin_day').html(htm[0]);
			$('#'+id).find('.iso_checkin_year_month').html(htm[1]);
			$('#'+id).find('.iso_checkout_day').html(htm[2]);
			$('#'+id).find('.iso_checkout_year_month').html(htm[3]);
		}
	});
} 	
/*Check In*/
function setCheckIn(_this){
	var iso_checkin_alternate = _this.closest('.tblCalendarHolder').find('.iso_checkin_alternate').val().split('-');			
	_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month').val(iso_checkin_alternate[0]+'-'+iso_checkin_alternate[1]);
	$.ajax({
		type: 'POST',
		url: path_ajax_datepicker+'/ajaxdatepicker.php',
		data:{'year_month':_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month').val(),'slted_day':iso_checkin_alternate[2],'type':'getDay'},
		dataType:'html',
		success: function(html){
			_this.closest('.tblCalendarHolder').find('.iso_checkin_day').html(html);
			_this.closest('.tblCalendarHolder').find('.iso_checkin_day').val(iso_checkin_alternate[2]);
			loadCheckInOutChange(_this);	
		}
	});	
}	
/*Check Out*/
function setCheckOut(_this){
	var iso_checkout_alternate = _this.closest('.tblCalendarHolder').find('.iso_checkout_alternate').val().split('-');	
	_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month').val(iso_checkout_alternate[0]+'-'+iso_checkout_alternate[1]);
	$.ajax({
		type: 'POST',
		url: path_ajax_datepicker+'/ajaxdatepicker.php',
		data:{'year_month':_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month').val(),'slted_day':iso_checkout_alternate[2],'type':'getDay'},
		dataType:'html',
		success: function(html){
			_this.closest('.tblCalendarHolder').find('.iso_checkout_day').html(html);
			_this.closest('.tblCalendarHolder').find('.iso_checkout_day').val(iso_checkout_alternate[2]);
			loadCheckOutInChange(_this);	
		}  
	});	
}
/**/
function loadCheckInOutChange(_this){
	if(_this.closest('.tblCalendarHolder').find('.iso_checkin_day').val()!=0&&_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month').val()!=0)
	$.ajax({
		type: 'POST',
		url: path_ajax_datepicker+'/ajaxdatepicker.php',
		data:{'checkin':_this.closest('.tblCalendarHolder').find('.iso_checkin_hidden').val(),'checkout':_this.closest('.tblCalendarHolder').find('.iso_checkout_hidden').val(),'type':'checkRange','actSubmit':'InOut'},
		dataType:'html',
		success: function(html){
			var htm = html.split('$$');
			if(htm[0]=='\nempty_out'){  
				_this.closest('.tblCalendarHolder').find('.iso_checkout_hidden,.iso_checkout_alternate').val(htm[1]);
				_this.closest('.tblCalendarHolder').find('.iso_checkin_hidden,.iso_checkin_alternate').val(_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month').val()+'-'+_this.closest('.tblCalendarHolder').find('.iso_checkin_day').val());
				var valDate = htm[1].split('-');
				_this.closest('.tblCalendarHolder').find('.iso_checkout_day option[value='+valDate[2]+']').attr('selected', 'selected');
				_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month option[value='+valDate[0]+'-'+valDate[1]+']').attr('selected', 'selected').trigger('change');
				_this.closest('.tblCalendarHolder').find('.iso_checkin_hidden,.iso_checkout_hidden').trigger('change');
			}
		}
	}); 
}
function loadCheckOutInChange(_this){
	if(_this.closest('.tblCalendarHolder').find('.iso_checkout_day').val()!=0&&_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month').val()!=0)
	$.ajax({
		type: 'POST',
		url: path_ajax_datepicker+'/ajaxdatepicker.php',
		data:{'checkin':_this.closest('.tblCalendarHolder').find('.iso_checkin_hidden').val(),'checkout':_this.closest('.tblCalendarHolder').find('.iso_checkout_hidden').val(),'type':'checkRange','actSubmit':'OutIn'},
		dataType:'html',
		success: function(html){
			var htm = html.split('$$');
			if(htm[0]=='\nempty_in'){
				_this.closest('.tblCalendarHolder').find('.iso_checkin_hidden,.iso_checkin_alternate').val(htm[1]);
				_this.closest('.tblCalendarHolder').find('.iso_checkout_hidden,.iso_checkout_alternate').val(_this.closest('.tblCalendarHolder').find('.iso_checkout_year_month').val()+'-'+_this.closest('.tblCalendarHolder').find('.iso_checkout_day').val());
				var valDate = htm[1].split('-');
				_this.closest('.tblCalendarHolder').find('.iso_checkin_day option[value='+valDate[2]+']').attr('selected', 'selected');
				_this.closest('.tblCalendarHolder').find('.iso_checkin_year_month option[value='+valDate[0]+'-'+valDate[1]+']').attr('selected', 'selected').trigger('change');
				_this.closest('.tblCalendarHolder').find('.iso_checkout_hidden,.iso_checkin_hidden').trigger('change');
			}
		}
	});
}
//});