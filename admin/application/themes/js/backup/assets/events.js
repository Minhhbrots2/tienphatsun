$().ready(function(){
	if(mod=='event'){
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
				url:path_ajax_script+'/index.php?mod='+mod+'&act=ajCreateAddEvent',		
				dataType:'html',
				success:function(html){
					makepopup(800,400,html,'LoadFormAddEvent');
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
					makepopup(800,400, html,'LoadFormAddEvent');
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
					'is_fulldate' 	: 	$('select[name=is_fulldate]').val(),
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
		$('.ajDeleteEvent').live('click',function(){
			var $_this = $(this);
			if(confirm(confirm_delete)){
				var $event_id = $_this.attr('data');
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod='+mod+'&act=ajDeleteEvent',
					data : {'event_id': $event_id},
					success: function(html){
						var $keyword = $('#keyword').val();
						var $page = $('.paginate_current_page').val();
						var $number_per_page = $('.paginate_length').val();
						loadEvents($keyword, $page, $number_per_page);
					}
				});
			};
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
	}
	/* Mod Event Tour */
	if(mod=='event_tour'){
		$('.btnManagerTour').live('click',function(){
			var $_this = $(this);
			vietiso_loading(1);
			$.ajax({
				type:'POST',
				url:path_ajax_script+'/index.php?mod=event&act=ajLoadListTours',
				data:{
					'event_id' 	: $_this.attr('data')
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
				url:path_ajax_script+'/index.php?mod=event&act=ajAddTourToEvent',
				data: adata,
				dataType:'html',	
				success:function(html){
					vietiso_loading(0);
					window.location.reload(true);
				}
			});
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
					url:path_ajax_script+'/index.php?mod=event&act=ajDeleteTourEvent',
					data: adata,
					dataType:'html',	
					success:function(html){
						window.location.reload(true);
					}
				});
			}
			return false;
		});
		$('.btnMoveEventTour').live('click',function(){
			var $_this = $(this);
			var $event_id = $_this.attr('event_id');
			var $vent_tour_id = $_this.attr('data');
			var $direct = $_this.attr('direct');
			/**/
			var adata = {
				'event_tour_id' : $vent_tour_id,
				'event_id'		: $event_id,
				'direct'		: $direct
			};
			vietiso_loading(1);
			$.ajax({
				type:'POST',	
				url:path_ajax_script+'/index.php?mod=event&act=ajMoveTourEvent',
				data: adata,
				dataType:'html',	
				success:function(html){
					vietiso_loading(0);
					window.location.reload(true);
				}
			});
		});
		/**/
		$('#ajBox_ManagerEventTour select[name=cat_id]').live('change',function(){
			var $_this= $(this);
			var $cat_id = $_this.val();
			var $depart_point_id = $('#ajBox_ManagerEventTour select[name=depart_point_id]').val();
			var $tour_type_id = $('#ajBox_ManagerEventTour select[name=tour_type_id]').val();
			var $number_day = $('#ajBox_ManagerEventTour select[name=number_day]').val();
			var $price_range = $('#ajBox_ManagerEventTour select[name=price_range_id]').val();
			var $keypop = $('#ajBox_ManagerEventTour input[name=keypop]').val();
			var $page = 1;
			var $number_per_page = $('#ajBox_ManagerEventTour select[name=paginate_length]').val();
			/**/
			loadDepartPoint($cat_id);
			loadListTourPop($cat_id, $depart_point_id, $tour_type_id, $number_day, $price_range, $keypop, $page, $number_per_page);
		});
		
		$('#ajBox_ManagerEventTour select[name=depart_point_id]').live('change',function(){
			var $_this= $(this);
			var $cat_id = $('#ajBox_ManagerEventTour select[name=cat_id]').val();
			var $depart_point_id = $_this.val();
			var $tour_type_id = $('#ajBox_ManagerEventTour select[name=tour_type_id]').val();
			var $number_day = $('#ajBox_ManagerEventTour select[name=number_day]').val();
			var $price_range = $('#ajBox_ManagerEventTour select[name=price_range_id]').val();
			var $keypop = $('#ajBox_ManagerEventTour input[name=keypop]').val();
			var $page = 1;
			var $number_per_page = $('#ajBox_ManagerEventTour select[name=paginate_length]').val();
			/**/
			loadListTourPop($cat_id, $depart_point_id, $tour_type_id, $number_day, $price_range, $keypop, $page, $number_per_page);
		});
		$('#ajBox_ManagerEventTour select[name=tour_type_id]').live('change',function(){
			var $_this= $(this);
			var $cat_id = $('#ajBox_ManagerEventTour select[name=cat_id]').val();
			var $depart_point_id = $('#ajBox_ManagerEventTour select[name=depart_point_id]').val();
			var $tour_type_id = $_this.val();
			var $number_day = $('#ajBox_ManagerEventTour select[name=number_day]').val();
			var $price_range = $('#ajBox_ManagerEventTour select[name=price_range_id]').val();
			var $keypop = $('#ajBox_ManagerEventTour input[name=keypop]').val();
			var $page = 1;
			var $number_per_page = $('#ajBox_ManagerEventTour select[name=paginate_length]').val();
			/**/
			loadListTourPop($cat_id, $depart_point_id, $tour_type_id, $number_day, $price_range, $keypop, $page, $number_per_page);
		});
		$('#ajBox_ManagerEventTour select[name=number_day]').live('change',function(){
			var $_this= $(this);
			var $cat_id = $('#ajBox_ManagerEventTour select[name=cat_id]').val();
			var $depart_point_id = $('#ajBox_ManagerEventTour select[name=depart_point_id]').val();
			var $tour_type_id =$('#ajBox_ManagerEventTour select[name=tour_type_id]').val();
			var $number_day = $_this.val();
			var $price_range = $('#ajBox_ManagerEventTour select[name=price_range_id]').val();
			var $keypop = $('#ajBox_ManagerEventTour input[name=keypop]').val();
			var $page = 1;
			var $number_per_page = $('#ajBox_ManagerEventTour select[name=paginate_length]').val();
			/**/
			loadListTourPop($cat_id, $depart_point_id, $tour_type_id, $number_day, $price_range, $keypop, $page, $number_per_page);
		});
		$('#ajBox_ManagerEventTour select[name=price_range_id]').live('change',function(){
			var $_this= $(this);
			var $cat_id = $('#ajBox_ManagerEventTour select[name=cat_id]').val();
			var $depart_point_id = $('#ajBox_ManagerEventTour select[name=depart_point_id]').val();
			var $tour_type_id =$('#ajBox_ManagerEventTour select[name=tour_type_id]').val();
			var $number_day = $('#ajBox_ManagerEventTour select[name=number_day]').val();
			var $price_range = $_this.val();
			var $keypop = $('#ajBox_ManagerEventTour input[name=keypop]').val();
			var $page = 1;
			var $number_per_page = $('#ajBox_ManagerEventTour select[name=paginate_length]').val();
			/**/
			loadListTourPop($cat_id, $depart_point_id, $tour_type_id, $number_day, $price_range, $keypop, $page, $number_per_page);
		});
		$('#searchpop').live('click',function(){
			var $cat_id = $('#ajBox_ManagerEventTour select[name=cat_id]').val();
			var $depart_point_id = $('#ajBox_ManagerEventTour select[name=depart_point_id]').val();
			var $tour_type_id =$('#ajBox_ManagerEventTour select[name=tour_type_id]').val();
			var $number_day = $('#ajBox_ManagerEventTour select[name=number_day]').val();
			var $price_range = $('#ajBox_ManagerEventTour select[name=price_range_id]').val();
			var $keypop = $('#ajBox_ManagerEventTour input[name=keypop]').val();
			var $page = 1;
			var $number_per_page = $('#ajBox_ManagerEventTour select[name=paginate_length]').val();
			/**/
			loadListTourPop($cat_id, $depart_point_id, $tour_type_id, $number_day, $price_range, $keypop, $page, $number_per_page);
			return false;
		});
		/* Paging */
		/* Paging */
		$('#ajBox_ManagerEventTour .paginate_length').live('change',function(){
			var $_this = $(this);
			var $cat_id = $('#ajBox_ManagerEventTour select[name=cat_id]').val();
			var $depart_point_id = $('#ajBox_ManagerEventTour select[name=depart_point_id]').val();
			var $tour_type_id =$('#ajBox_ManagerEventTour select[name=tour_type_id]').val();
			var $number_day = $('#ajBox_ManagerEventTour select[name=number_day]').val();
			var $price_range = $('#ajBox_ManagerEventTour select[name=price_range_id]').val();
			var $keypop = $('#ajBox_ManagerEventTour input[name=keypop]').val();
			var $page = 1;
			var $number_per_page = $_this.val();
			/**/
			loadListTourPop($cat_id, $depart_point_id, $tour_type_id, $number_day, $price_range, $keypop, $page, $number_per_page);
		});
		$('#ajBox_ManagerEventTour .paginate_button').live('click',function(){
			var $_this = $(this);
			if(!$_this.hasClass('disabled')){
				var $cat_id = $('#ajBox_ManagerEventTour select[name=cat_id]').val();
				var $depart_point_id = $('#ajBox_ManagerEventTour select[name=depart_point_id]').val();
				var $tour_type_id =$('#ajBox_ManagerEventTour select[name=tour_type_id]').val();
				var $number_day = $('#ajBox_ManagerEventTour select[name=number_day]').val();
				var $price_range = $('#ajBox_ManagerEventTour select[name=price_range_id]').val();
				var $keypop = $('#ajBox_ManagerEventTour input[name=keypop]').val();
				var $page = $_this.attr('page');
				var $number_per_page = $('#ajBox_ManagerEventTour select[name=paginate_length]').val();
				/**/
				loadListTourPop($cat_id, $depart_point_id, $tour_type_id, $number_day, $price_range, $keypop, $page, $number_per_page);
			}
			return false;
		});
	}
	/* Tour in event */
});
function getCheckBoxValueByClass(classname){
	var names = [];
	$('.'+classname+':checked').each(function() {
		names.push(this.value);
	});
	return names;
}
function loadEvents($keyword, $page, $number_per_page)
{
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
function loadListTourPop($cat_id, $depart_point_id, $tour_type_id, $number_day, $price_range, $keyword, $page, $number_per_page)
{
	var adata = {
		'event_id' : event_id,
		'cat_id' : $cat_id,
		'depart_point_id' : $depart_point_id,
		'tour_type_id' : $tour_type_id,
		'number_day' : $number_day,
		'price_range' : $price_range,
		'keyword' : $keyword,
		'page' : $page,
		'number_per_page' : $number_per_page
	};
	vietiso_loading(1);
	$.ajax({
		type:'POST',	
		url:path_ajax_script+'/index.php?mod=event&act=ajLoadListToursPop',
		data: adata,
		dataType:'html',	
		success:function(html)
		{
			console.log(html);
			var htm = html.split('$$');
			$('#tblHolderToursPop').html(htm[0]);
			$('#ajBox_ManagerEventTour #dataTable_paginate').html(htm[1]);
			vietiso_loading(0);
		}
	});
}
function loadDepartPoint($cat_id)
{
	var $el = $('#ajBox_ManagerEventTour select[name=depart_point_id]');
	var adata = {
		'cat_id' : $cat_id
	};
	$el.html('<option>Loading...</option>');
	$.ajax({
		type: "POST",
		url: path_ajax_script+'/?mod=tour&act=ajLoadDepartPoint',
		data: adata,
		dataType: "html",
		success: function(html){
			$el.html(html);
		}
	});
}