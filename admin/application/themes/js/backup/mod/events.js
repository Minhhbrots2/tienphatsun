$().ready(function(){
	loadEvents('',1,10);
	$('#keyword').bind('keyup',function(){
		var $_this=$(this);
		var $page = 1;
		var $number_per_page = $('.paginate_length').val();
		loadEvents($_this.val(),$page,$number_per_page);
	});
	$('.btnCreateEvent').live('click',function(e){
		var _this = $(this);
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadAddEvent',		
			dataType:'html',
			success:function(html){
				makepopup('60%','65%',html,'LoadFormAddEvent');
				$('#'+$('.textarea_content_editor').attr('id')).isoTextArea();
				$('.isodatepicker').isodatepicker();
			}
		});
	});
	$('.ajeditEvent').live('click',function(){
		var $_this = $(this);
		var adata = {
			'event_id'	: $_this.attr('data'),
		};
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadEditEvent',
			data: adata,
			dataType:'html',
			success:function(html){
				makepopup('60%','70%',html,'LoadFormAddEvent');
				$('#'+$('.textarea_content_editor').attr('id')).isoTextArea();
				$('.isodatepicker').isodatepicker();
			}
		});
	});
	$('.ajSubmitClickEvent').live('click',function(){
		var _this = $(this);
		if($('#event_code').val()==''){
			alertify.error(event_code_required);
			$('#code').focus().addClass('error');
			return false;
		}
		if($('#title').val()==''){
			alertify.error(title_required);
			$('#title').focus().addClass('error');
			return false;
		}
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajSubmitEvents',
			data: {
				'event_id' 	: 	_this.attr('event_id'),
				'title' 		: 	$('input[name=title]').val(),
				'event_code' 	: 	$('input[name=event_code]').val(),
				'start_date' 	: 	$('input[name=start_date]').val(),
				'finish_date' 	: 	$('input[name=finish_date]').val(),
				'is_fulldate' 	: 	$('input[name=is_fulldate]').is(':checked')?1:0,
				'content' 		: 	tinyMCE.get($('.textarea_content_editor').attr('id')).getContent(),
				'event_status'	: 	$('select[name=event_status]').val()
			},
			dataType: "html",
			success: function(html){
				htm = html.replace(' ','');
				if(htm=='_SUCCESS'){
					loadEvents('',1,10);
					alertify.success('Thêm mới thành công !');
					$('.close_pop').trigger('click');
				}
				if(htm=='_EXIST'){
					alertify.success('Sửa thành công !');
				}	
				if(htm=='_ERROR'){
					alertify.success('Error !');
				}
			}
		});
		return false;
	});
	$('.btnMoveEvent').live('click',function(){
		var $_this = $(this);
		var adata = {
			'event_id'		: $_this.attr('data'),
			'direct'		: $_this.attr('direct')
		};
		vietiso_loading(1);
		$.ajax({
			type:'POST',	
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajMoveEvent',
			data: adata,
			dataType:'html',	
			success:function(html){
				var $keyword = $('#keyword').val();
				var $page = $('.paginate_current_page').val();
				var $number_per_page = $('.paginate_length').val();
				loadEvents($keyword, $page, $number_per_page);
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.btnManagerTour').live('click',function(){
		var $_this = $(this);
		vietiso_loading(1);
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadListTours',
			data: {
				'event_id' 	: $_this.attr('data'),
			},
			dataType:'html',
			success:function(html){
				makepopup('90%','87%',html,'ajBox_ManagerEventTour');
				$('#ajBox_ManagerEventTour').css('top','10px');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('select[name=country_id]').live('change',function(){
		var $_this = $(this);
		vietiso_loading(1);
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod=city&act=makeSelectboxCity',
			data: {'country_id' : $_this.val(),},
			dataType:'html',
			success:function(html){
				$('select[name=city_id]').html(html);
				var $cat_id = $('select[name=cat_id]').val();
				var $keyword = $('input[name=keypop]').val();
				var $number_per_page = $('#ajBox_ManagerEventTour .paginate_length').val();
				loadListTourPop($_this.val(),'',$cat_id,$keyword,1,$number_per_page);
				vietiso_loading(0);
			}
		});
	});
	$('select[name=city_id]').live('change',function(){
		var $_this = $(this);
		var $country_id = $('select[name=country_id]').val();
		var $cat_id = $('select[name=cat_id]').val();
		var $keyword = $('input[name=keypop]').val();
		var $number_per_page = $('#ajBox_ManagerEventTour .paginate_length').val();
		loadListTourPop($country_id,$_this.val(),$cat_id,$keyword,1,$number_per_page);
	});
	$('select[name=cat_id]').live('change',function(){
		var $_this = $(this);
		var $country_id = $('select[name=country_id]').val();
		var $city_id = $('select[name=city_id]').val();
		var $keyword = $('input[name=keypop]').val();
		var $number_per_page = $('#ajBox_ManagerEventTour .paginate_length').val();
		loadListTourPop($country_id,$city_id,$_this.val(),$keyword,1,$number_per_page);
	});
	$('#searchpop').live('click',function(){
		var $country_id = $('select[name=country_id]').val();
		var $city_id = $('select[name=city_id]').val();
		var $cat_id = $('select[name=cat_id]').val();
		var $keyword = $('input[name=keypop]').val();
		var $number_per_page = $('#ajBox_ManagerEventTour .paginate_length').val();
		loadListTourPop($country_id,$city_id,$cat_id,$keyword,1,$number_per_page);
		return false;
	});
	/* Paging */
	$('.holderEvent_tbl .paginate_length').live('change',function(){
		var $_this = $(this);
		var $keyword = $('#keyword').val();
		var $page = $_this.attr('page');
		var $number_per_page = $_this.val();
		loadEvents($keyword,$page,$number_per_page);
	});
	$('.holderEvent_tbl .paginate_button').live('click',function(){
		var $_this = $(this);
		if(!$_this.hasClass('disabled')){
			var $keyword = $('#keyword').val();
			var $page = $_this.attr('page');
			var $number_per_page = $('.paginate_length').val();
			loadEvents($keyword,$page,$number_per_page);
		}
		return false;
	});
	/* Tour in event */
	$('.btnManagerEventTour').live('click',function(){
		var event_id = $(this).attr('data');
		LoadManagerEventTour(event_id);
	});
	$('select[name=evt_country_id]').live('change',function(){
		var $_this = $(this);
		vietiso_loading(1);
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod=city&act=makeSelectboxCity',
			data: {'country_id' : $_this.val(),},
			dataType:'html',
			success:function(html){
				$('select[name=evt_city_id]').html(html);
				var $cat_id = $('select[name=evt_cat_id]').val();
				var $keyword = $('input[name=evt_keypop]').val();
				var $number_per_page = $('#box_ManagerEventTour .paginate_length').val();
				loadListEventTourPop($_this.attr('event_id'),$_this.val(),'',$cat_id,$keyword,1,$number_per_page);
				vietiso_loading(0);
			}
		});
	});
	$('select[name=evt_city_id]').live('change',function(){
		var $_this = $(this);
		var $country_id = $('select[name=evt_country_id]').val();
		var $cat_id = $('select[name=evt_cat_id]').val();
		var $keyword = $('input[name=evt_keypop]').val();
		var $number_per_page = $('#box_ManagerEventTour .paginate_length').val();
		loadListEventTourPop($country_id,$_this.val(),$cat_id,$keyword,1,$number_per_page);
	});
	$('select[name=evt_cat_id]').live('change',function(){
		var $_this = $(this);
		var $country_id = $('select[name=evt_country_id]').val();
		var $city_id = $('select[name=evt_city_id]').val();
		var $keyword = $('input[name=evt_keypop]').val();
		var $number_per_page = $('#box_ManagerEventTour .paginate_length').val();
		loadListEventTourPop($_this.attr('event_id'),$country_id,$city_id,$_this.val(),$keyword,1,$number_per_page);
	});
	$('#evt_searchpop').live('click',function(){
		var $_this = $(this);
		var $country_id = $('select[name=evt_country_id]').val();
		var $city_id = $('select[name=evt_city_id]').val();
		var $cat_id = $('select[name=evt_cat_id]').val();
		var $keyword = $('input[name=evt_keypop]').val();
		var $number_per_page = $('#box_ManagerEventTour .paginate_length').val();
		loadListEventTourPop($_this.attr('event_id'),$country_id,$city_id,$cat_id,$keyword,1,$number_per_page);
		return false;
	});
	$('.btnDelEventTour').live('click',function(){
		var $_this = $(this);
		if(confirm(confirm_delete)){
			var $event_id = $_this.attr('event_id');
			var adata = {
				'event_tour_id' : $_this.attr('data'),
				'event_id'		: $event_id
			};
			vietiso_loading(1);
			$.ajax({
				type:'POST',	
				url:path_ajax_script+'/index.php?mod='+mod+'&act=ajDeleteTourEvent',
				data: adata,
				dataType:'html',	
				success:function(html){
					var $country_id = '';
					var $city_id = '';
					var $cat_id = '';
					var $keyword = '';
					var $page = $('#box_ManagerEventTour .paginate_current_page').val();
					var $number_per_page = $('#box_ManagerEventTour .paginate_length').val();
					loadListEventTourPop($event_id,$country_id,$city_id,$cat_id,$keyword,$page,$number_per_page);
					vietiso_loading(0);
				}
			});
		}
		return false;
	});
	$('.btnMoveEventTour').live('click',function(){
		var $_this = $(this);
		var $event_id = $_this.attr('event_id');
		var adata = {
			'event_tour_id' : $_this.attr('data'),
			'event_id'		: $event_id,
			'direct'		: $_this.attr('direct')
		};
		vietiso_loading(1);
		$.ajax({
			type:'POST',	
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajMoveTourEvent',
			data: adata,
			dataType:'html',	
			success:function(html){
				var $country_id = '';
				var $city_id = '';
				var $cat_id = '';
				var $keyword = '';
				var $page = $('#box_ManagerEventTour .paginate_current_page').val();
				var $number_per_page = $('#box_ManagerEventTour .paginate_length').val();
				loadListEventTourPop($event_id,$country_id,$city_id,$cat_id,$keyword,$page,$number_per_page);
				vietiso_loading(0);
			}
		});
	});
	/* End tour event*/
	$('.clickSaveTourToEvent').live('click',function(){
		var $_this = $(this);
		var $list_id = getCheckBoxValueByClass('chkid_tour');
		if($list_id==''){
			alertify.error('Bạn chưa chọn tour nào !');
			return false;
		}
		var adata = {
			'event_id' : $_this.attr('data'),
			'list_id' : $list_id.join()
		};
		vietiso_loading(1);
		$.ajax({
			type:'POST',	
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajAddTourToEvent',
			data: adata,
			dataType:'html',	
			success:function(html){
				var $event_id = $_this.attr('data');
				var $country_id = '';
				var $city_id = '';
				var $cat_id = '';
				var $keyword = '';
				var $number_per_page = $('#box_ManagerEventTour .paginate_length').val();
				loadListEventTourPop($event_id,$country_id,$city_id,$cat_id,$keyword,1,$number_per_page);
				$('#ajBox_ManagerEventTour .close_pop').trigger('click');
				vietiso_loading(0);
			}
		});
	});
	/* Edit Tour */
	$('.btnEditEventTour').live('click',function(){
		var $_this = $(this);
		var $event_id = $_this.attr('event_id');
		var adata = {
			'event_tour_id' : $_this.attr('data'),
			'event_id'		: $event_id
		};
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=event&act=ajLoadEditEventTour",
			data: adata,
			dataType: "html",
			success: function(html){
				makepopup('80%','86%',html,'ajEditEventTour');
				$('#ajEditEventTour').css('top','10px');
				$('#'+$('.event_tour_intro').attr('id')).isoTextArea();
				$('#'+$('.event_tour_hightlight').attr('id')).isoTextArea();
				$('.isodatepicker').isodatepicker();
				$('.isoprice').isopriceformat();
				$(".mask").mask("99:99");
			}
		});
		return false;
	});
	$('#btnUpdateEventTour').live('click',function(){
		var $_this = $(this);
		var $event_id = $_this.attr('event_id');
		
		if($('input[name=title]').val()==''){
			$('input[name=title]').focus();
			alertify.error('Title Invalid !');
			return false;
		}
		if($('input[name=trip_code]').val()==''){
			$('input[name=trip_code]').focus();
			alertify.error('Trip code Invalid !');
			return false;
		}
		if($('input[name=departure_date]').val()==''){
			$('input[name=departure_date]').focus();
			alertify.error('Date of departure Invalid !');
			return false;
		}
		if($('input[name=return_date]').val()==''){
			$('input[name=return_date]').focus();
			alertify.error('Ruturn date Invalid !');
			return false;
		}
		if($('input[name=return_date]').val()==''){
			$('input[name=return_date]').focus();
			alertify.error('Ruturn date Invalid !');
			return false;
		}
		if($('input[name=transport_by]').val()==''){
			$('input[name=transport_by]').focus();
			alertify.error('Tranport Invalid !');
			return false;
		}
		if($('input[name=departure_time]').val()==''){
			$('input[name=departure_time]').focus();
			alertify.error('Time of departure Invalid !');
			return false;
		}
		if($('input[name=arrival_time]').val()==''){
			$('input[name=arrival_time]').focus();
			alertify.error('Arrival time Invalid !');
			return false;
		}
		if($('input[name=arrival_time]').val()==''){
			$('input[name=arrival_time]').focus();
			alertify.error('Arrival time Invalid !');
			return false;
		}
		if($('input[name=number_seat]').val()==''){
			$('input[name=number_seat]').focus();
			alertify.error('Number seats Invalid !');
			return false;
		}
		$('#form-post').ajaxSubmit({
			'type' : 'POST',
			'url': path_ajax_script+"/index.php?mod=event&act=ajUpdateEventTour",
			data: {
				'event_tour_id': $_this.attr('event_tour_id'),
				'event_id': $_this.attr('event_id'),
				'intro'	: tinyMCE.get($('.event_tour_intro').attr('id')).getContent(),
				'hightlight'	: tinyMCE.get($('.event_tour_hightlight').attr('id')).getContent()
			},
			dataType: "html",
			success: function(html){
				var htm = html.replace(' ','');
				if(htm=='_SUCCESS'){
					alertify.success('Success !');
					$('#ajEditEventTour .close_pop').trigger('click');
					var $keyword = $('input[name=evt_keypop]').val();
					var $page = $('#box_ManagerEventTour .paginate_current_page').val();
					var $number_per_page = $('#box_ManagerEventTour .paginate_length').val();
					loadListEventTourPop($event_id,'','','',$keyword,$page,$number_per_page);
				}
			}
		});
		return false;
	});
	$('.clkDelete').live('click',function(){
		if(confirm(confirm_delete)){
			var $_this = $(this);
			var adata = {
				'tour_by_day_id' : $_this.attr('data')
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=event&act=ajRemoveTourByDays",
				data: adata,
				dataType: "html",
				success: function(html){
					loadListTourByDays();
				}
			});
			return false;
		}
	});
});
function getCheckBoxValueByClass(classname){
	var names = [];
	$('.'+classname+':checked').each(function() {
		names.push(this.value);
	});
	return names;
}
function loadEvents($keyword, $page, $number_per_page){
	var adata = {
		'keyword' : $keyword,
		'page' : $page,
		'number_per_page' : $number_per_page
	};
	vietiso_loading(1);
	$.ajax({
		type:'POST',	
		url:path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadListEvents',
		data: adata,
		dataType:'html',	
		success:function(html){
			var htm = html.split('$$');
			$('#tblHoderEvent').html(htm[0]);
			$('#dataTable_paginate').html(htm[1]);
			vietiso_loading(0);
		}
	});
}
function loadListTourPop($country_id,$city_id,$cat_id,$keyword,$page,$number_per_page){
	var adata = {
		'country_id' : $country_id,
		'city_id' : $city_id,
		'cat_id' : $cat_id,
		'keyword' : $keyword,
		'page' : $page,
		'number_per_page' : $number_per_page
	};
	vietiso_loading(1);
	$.ajax({
		type:'POST',	
		url:path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadListToursPop',
		data: adata,
		dataType:'html',	
		success:function(html){
			var htm = html.split('$$');
			$('#tblHolderToursPop').html(htm[0]);
			$('#ajBox_ManagerEventTour #dataTable_paginate').html(htm[1]);
			vietiso_loading(0);
		}
	});
}
function loadListEventTourPop($event_id,$country_id,$city_id,$cat_id,$keyword,$page,$number_per_page){
	var adata = {
		'event_id' : $event_id,
		'country_id' : $country_id,
		'city_id' : $city_id,
		'cat_id' : $cat_id,
		'keyword' : $keyword,
		'page' : $page,
		'number_per_page' : $number_per_page
	};
	vietiso_loading(1);
	$.ajax({
		type:'POST',	
		url:path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadListEventToursPop',
		data: adata,
		dataType:'html',	
		success:function(html){
			var htm = html.split('$$');
			$('#tblHolderEventToursPop').html(htm[0]);
			$('#box_ManagerEventTour #dataTable_paginate').html(htm[1]);
			vietiso_loading(0);
		}
	});
}
function LoadManagerEventTour(event_id) {
	var adata = {
		'event_id' : event_id
	};
	vietiso_loading(1);
	$.ajax({
		type:'POST',
		url:path_ajax_script+'/index.php?mod='+mod+'&act=ajManagerEventTour',
		data:adata,
		dataType:'html',
		success:function(html){
			makepopup('94%','86%',html,'box_ManagerEventTour');
			$('#box_ManagerEventTour').css('top','10px');
			vietiso_loading(0);
		}
	});
}