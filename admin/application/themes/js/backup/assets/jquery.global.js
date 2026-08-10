$().ready(function(){
	// Begin City
	$('.ajClickAddCity').click(function(){
		var $_this = $(this);
		var $country_id = $('select[name=country_id]').val();
		if($country_id=='' || $country_id=='0'){
			alertify.error('Bạn cần lựa chọn quốc gia !');
			return false;
		}
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=city&act=ajLoadFormAddQuickCity",
			dataType: "html",
			data: {'country_id': $country_id,'forid': $_this.attr('forid')},
			success: function(html){
				makepopup('400px', '', html, 'cmsbox_BoxAddCity');
				vietiso_loading(0);
			}
		});
	});
	$('.ajSubmitQuickCity').live('click',function(){
		var $_this = $(this);
		var $forid = $_this.attr('forid');
		var $country_id = $_this.attr('country_id');
		if($('#cmsbox_BoxAddCity input[name=title]').val()==''){
			$('#cmsbox_BoxAddCity input[name=title]').focus();
			alertify.error(field_is_required);
			return false;
		}
		vietiso_loading(1);
		var adata = {
			'city_id' : $_this.attr('data'),
			'title'	: $('#cmsbox_BoxAddCity input[name=title]').val(),
			'country_id': $country_id
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=city&act=ajSubmitQuickCity",
			data: adata,
			dataType: "html",
			success: function(html){
				if(html.indexOf('_SUCCESS')>=0){
					alertify.success(insert_success);
					$('#cmsbox_BoxAddCity .close_pop').trigger('click');
					loadCity($country_id);
				}
				if(html.indexOf('_ERROR')>=0){
					alertify.success(insert_error);
				}
				if(html.indexOf('_EXIST')>=0){
					alertify.success(exist_error);
				}
				vietiso_loading(0);
			}
		});
	});
	// End city
	$('.clickToManagerProperty').live('click',function(){
		var $_this = $(this);
		
		var $_width = $_this.attr('_width')!=''? $_this.attr('_width'):'60%';
		var $_height = $_this.height('_height')!=''? $_this.height('_height'):'60%';
		var adata = {
			'property_type' : $_this.attr('data'),
			'property_form'	: $_this.attr('property_form'),
			'property_check': $_this.attr('property_check'),
			'property_value': $_this.val(),
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=property&act=ajGetBoxManagerProperty",
			data: adata,
			dataType: "html",
			success: function(html){
				makepopup($_width, $_height, html, 'cmsbox_ManagerProperty');
				$('#cmsbox_ManagerProperty').css('top','40px');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('input[class=chkitem]').live('change',function(){
		var $_this = $(this);
		if($_this.is(':checked')){
			$_this.closest('tr').addClass('hightlight');
		}else{
			$_this.closest('tr').removeClass('hightlight');
		}
	});
	$('.clickToUpdatePropertyList').live('click',function(){
		var _this = $(this);
		var list_selected_chkitem = $('#list_selected_chkitem').val();
		var property_type = _this.attr('property_type');
		var adata = {
			'property_type' : property_type,
			'list_selected_chkitem' : list_selected_chkitem
		};
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=property&act=ajSavePropertyToText",
			data: adata,
			dataType: "html",
			success: function(html){
				var html = html.replace(' ','');
				$('.loadProperty_'+property_type).val(html);
				$('#hidden_'+property_type).val(list_selected_chkitem);
				$('#hidden_property_type_'+property_type).val(list_selected_chkitem);
				_this.closest('.frmPop').find('.close_pop').click();
			}
		});
		return false;
	});
	$('.clickToAddProperty').live('click',function(){
		var _this = $(this);
		var adata = {
			'property_type': _this.attr('property_type'),
			'property_form': _this.attr('property_form')
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/?mod=property&act=ajGetAddProperty",
			data: adata,
			dataType: "html",
			success: function(html){
				makepopup('45%','auto',html,'frmAddProperty');
				makeSystemTab();
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.submitToProperty').live('click',function(){
		var $_this = $(this);
		var $property_type = $_this.attr('property_type');
		var $property_form = $_this.attr('property_form');
		/**/
		if($_this.attr('property_form')=='full'){
			
			if($('#property_code').val()==''){
				$('#property_code').addClass('error').focus();
				return false;
			}
			if($('#property_title_en').val()==''){
				$('#property_title_en').addClass('error').focus();
				return false;
			}
			var adata = {
				'property_id'	: $_this.attr('property_id'),
				'property_type'	: $property_type,
				'property_form'	: $property_form,
				'property_code'	: $('#property_code').val(),
				'title_en': $('#property_title_en').val(),
				'title_vn': $('#property_title_vn').val(),
				'intro_en': $('#property_intro_en').val(),
				'intro_vn': $('#property_intro_vn').val(),
				'order_no': $('#property_order').val()
			};
		}else{
			if($('#property_title_en').val()==''){
				$('#property_title_en').addClass('error').focus();
				return false;
			}
			var adata = {
				'property_id'	: $_this.attr('property_id'),
				'property_type'	: $property_type,
				'property_form'	: $property_form,
				'title_en': $('#property_title_en').val(),
				'title_vn': $('#property_title_vn').val(),
				'order_no': $('#property_order').val()
			};
		}
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/?mod=property&act=ajSubmitProperty",
			data: adata,
			dataType: "html",
			cache: false,
			success: function(html){
				alert(html);
				if(type == "HotelFacilities") {lstHotelFacilities();}
				var htm = parseInt(html);
				if(html==1){
					loadTablePropertyType(type);
					loadListCheckBoxPropertyType(type);
					$(".contentPop").animate({ scrollTop: $(".contentPop")[0].scrollHeight},100);
					alertify.success('Thêm mới thành công');
					$('#frmAddProperty a.close_pop').trigger('click');
				}
				if(html==2){
					loadTablePropertyType(type);
					$(".contentPop").animate({ scrollTop: $(".contentPop")[0].scrollHeight},100);
					alertify.success('Cập nhật thành công');
					$('#frmEditProperty a.close_pop').trigger('click');
				}
				if(html==3){
					alertify.error('Lỗi !');
				}
				if(htm==5){
					alertify.error('Đã tồn tại !');
				}
				vietiso_loading(0);
			}
		});
		return false;
	});
	/* PropertyPop */
	$(document).on('click', '#btnCreateNewProperty', function(ev){
		var $_this = $(this);
		var adata = {
			'type': $_this.attr('type'),
			'fromid': $_this.attr('fromid'),
			'forid': $_this.attr('forid')
		};
		$.ajax({
			type: "POST",
			url:path_ajax_script+"/index.php?mod=property&act=ajaxFrmProperty",
			data: adata,
			dataType: "html",
			success: function(html){
				makepopup('20%','',html,'pop_FrmProperty');
				makeSystemTab();
			}
		});
	});
	$(document).on('click', '.edit_pop_property', function(ev){
		var $_this = $(this);
		if(!$_this.hasClass('disabled')){
			var adata = {
				'property_id' : $_this.attr('data'),
				'fromid': $_this.attr('fromid'),
				'forid': $_this.attr('forid')
			};
			$_this.addClass('disabled');
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/?mod=property&act=ajaxFrmProperty",
				data: adata,
				dataType: "html",
				success: function(html){
					$_this.removeClass('disabled');
					makepopup('20%','',html,'pop_FrmProperty');
					makeSystemTab();
				}
			});
		}
		return false;
	});
	$(document).on('click', '#ajaxSaveProperty', function(ev){
		var $_this = $(this);
		if(!$_this.hasClass('disabled')){
			var $_form = $_this.closest('.frmPop');
			var $title = $_form.find('input[name=title]');
			var $fromid = $_this.attr('fromid');
			var $forid = $_this.attr('forid');
			
			if($title.val()==''){
				$title.addClass('error').focus();
				return false;
			}
			var adata = {
				'property_id': $_this.attr('property_id'),
				'type':$_this.attr('_type'),
				'title': $title.val()
			};
			
			vietiso_loading(1);
			$_this.addClass('disabled');
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=property&act=ajSubmitProperty",
				data: adata,
				dataType: "html",
				success: function(html){
					$_this.removeClass('disabled');
					vietiso_loading(0);
					if(html.indexOf('IN_SUCCESS')>=0){
						$_form.find('.close_pop').trigger('click');
						alertify.success('Thêm mới thành công');
						loadTableProperty($_this.attr('_type'),$fromid,$forid);
						if($fromid=='pop_HotelRoom'){
							loadSelectBoxRoomFacility($_this.attr('property_id'),$forid);
						}
						if($fromid=='HotelFacilities'){
							loadHotelFacibility($forid);
						}
						if($fromid=='HotelRating'){
							loadSelectBoxHotelRating($_this.attr('_type'), $forid);
						}
					}
					if(html.indexOf('UP_SUCCESS')>=0){
						loadTableProperty($_this.attr('_type'),$fromid,$forid);
						$_form.find('.close_pop').trigger('click');
						alertify.success('Cập nhật thành công');
						if($fromid=='pop_HotelRoom'){
							loadSelectBoxRoomFacility($_this.attr('property_id'),$forid);
						}
						if($fromid=='HotelFacilities'){
							loadHotelFacibility($forid);
						}
						if($fromid=='HotelRating'){
							loadSelectBoxHotelRating($_this.attr('_type'), $forid);
						}
					}
					if(html.indexOf('ERROR')>=0){
						alertify.error('Lỗi !');
					}
					if(html.indexOf('EXIST')>=0){
						alertify.error('Đã tồn tại !');
					}
				}
			});
		}
		return false;
	});
	$(document).on('click', '.delete_pop_property', function(ev){
		var $_this = $(this);
		if(!$_this.hasClass('disabled')){
			var $fromid = $_this.attr('fromid');
			var $forid = $_this.attr('forid');
			/**/
			if(confirm(confirm_delete)){
				var adata = {'property_id' : $_this.attr('data')};
				vietiso_loading(1);
				$_this.addClass('disabled');
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/?mod=property&act=ajDeleteProperty",
					data: adata,
					dataType: "html",
					success: function(html){
						$_this.removeClass('disabled');
						vietiso_loading(0);
						loadTableProperty($_this.attr('_type'), $_this.attr('fromid'), $_this.attr('forid'));
						if($fromid=='pop_HotelRoom'){
							loadSelectBoxRoomFacility($_this.attr('property_id'),$forid);
						}
						if($fromid=='HotelFacilities'){
							loadHotelFacibility($forid);
						}
						if($fromid=='HotelRating'){
							loadSelectBoxHotelRating($_this.attr('_type'), $forid);
						}
					}
				});
			}
		}
		return false;
	});
	if(mod != 'hotel'){
		$(document).on('click', '.createNewHotel', function(ev){
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+'/?mod=hotel&act=ajLoadCreateHotel',
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					makepopupnotresize('500px','auto',html,'CreateHotel');
				}
			});
			return false;
		});
		$(document).on('click', '.clickToAddNewHotel', function(ev){
			if($("#NewHotelTitle").val()==''){
				$("#NewHotelTitle").focus();
				alertify.error("Vui lòng nhập tên khách sạn!");
				return false;
			}
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+'/?mod=hotel&act=ajCreateNewHotel',
				data: {"title":$("#NewHotelTitle").val()},
				dataType: "html",
				success: function(html){
					if(html.indexOf('_ERROR') >= 0) {
						alertify.error("Khách sạn này đã tồn tại. Vui lòng nhập tên khách sạn khác và thử lại!");
						vietiso_loading(0);
					} else {
						location.href = html;
					}
				}
			});
			return false;
		});
	}
	else{
		
	}
});
function loadTableProperty($type, $fromid, $forid){
	var adata = {
		'type': $type,
		'fromid': $fromid,
		'forid': $forid
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+'/index.php?mod=property&act=ajaxLoadTableProperty',
		data: adata,
		dataType: "html",
		success: function(html){
			$('#tblHolderPropertyPop').html(html);
		}
	});
}
function loadSelectBoxCategory($parent_id, $el){
	var adata = {
		'parent_id': $parent_id
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/?mod=category&act=ajmakeSelectBoxOption",
		data:{"parent_id" : $_this.val()},
		dataType: "html",
		success: function(html){
		  $el.html(html);
		}
	});
}
function loadSelectBoxRoomFacility($type, $hotel_room_id){
	return 0;
}
function loadSelectBoxHotelRating($type, $forid){
	var adata = {
		'type': $type,
		'forid': $forid
	};
	$.ajax({
		type: "POST",
		url:path_ajax_script+'/index.php?mod=property&act=ajaxSelectBoxHotelRating',
		data: adata,
		dataType: "html",
		success: function(html){
			$('#slb_HotelRating').html(html);
		}
	});
}
function loadListCheckBoxPropertyType(type){
	var adata = {
		'type': type
	};
	$.ajax({
		type: "POST",
		url:path_ajax_script+'/index.php?mod=property&act=ajLoadListCheckBoxPropertyType',
		data: adata,
		dataType: "html",
		success: function(html){
			$('#room_facility').html(html);
		}
	});
}
function initGalleryGlobal($table_id, $type, $container){
	var adata = {
		'table_id'	: $table_id,
		'type'	: $type
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=media&act=ajaxInitPhotosGallery",
		data: adata,
		dataType: "html",
		cache: true,
		success: function(html){
			$('#'+$container).html(html);
			loadListGallery($table_id,$type,'');
		}
	});
}
function loadListGallery($table_id, $type, $keyword){
	var adata = {
		'table_id'	: $table_id,
		'type'	: $type,
		'keyword'	: $keyword
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=media&act=ajLoadPhotosGallery",
		data: adata,
		dataType: "html",
		success: function(html){
			$('#preview').html(html);
		}
	});
}
