$().ready(function(){
	loadItinerary(tour_id);
	// Add Itinerary
	$('#clickToAddItinerary').live('click',function(){
		var _this = $(this);
		var adata = {
			'tour_id' : tour_id
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?mod=tour&act=ajLoadFormAddItinerary',
			data: adata,
			dataType: "html",
			success: function(html){
				makepopupnotresize('75%','360px',html,'box_AddItinerary');
				$('#box_AddItinerary').css('top','40px');
				$('#'+$('.textarea_content_editor').attr('id')).isoTextArea();
				$('#'+$('.textarea_content_editor').attr('id')+'_ifr').height(100);
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.clickEditItinerary').live('click',function(){
		var $_this = $(this);
		var adata = {
			'tour_id'		: 	tour_id,
			'itinerary_id'	:	$_this.attr('data')
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?mod=tour&act=ajLoadFormEditItinerary',
			data: adata,
			dataType: "html",
			success: function(html){
				makepopupnotresize('75%','500px',html,'box_EditItinerary');
				$('#box_EditItinerary').css('top','10px')
				$('#'+$('.textarea_content_editor').attr('id')).isoTextArea();
				$('#'+$('.textarea_content_editor').attr('id')+'_ifr').height(100);
				loadHotelRecommend($_this.attr('data'),'');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.submitItineration').live('click',function(){
		var $_this = $(this);
		var $title = $('input[name=title]').val();
		var $day = $('input[name=day]').val();
		var $meals = getCheckBoxValueByClass('chkid_meal');
		var $content = tinyMCE.get($('.textarea_content_editor').attr('id')).getContent();
		/**/
		if($day==''){
			alertify.error(field_is_required);
			$('input[name=day]').focus().addClass('error');
			return false;
		}
		if($title==''){
			alertify.error(field_is_required);
			$('input[name=title]').focus().addClass('error');
			return false;
		}
		if($meals==''){
			alertify.error(field_is_required);
			$('input[class=chkid_meal]:first').focus();
			return false;
		}
		/**/
		var adata = {
			'itinerary_id' 	: 	$_this.attr('itinerary_id'),
			'tour_id' 		: 	tour_id,
			'day' 			: 	$day,
			'title' 		: 	$title,
			'meals' 		: 	$meals.join(),
			'content'	  	: 	$content
		};
		vietiso_loading(1);
		$('#frmItinerary').ajaxSubmit({
			type: "POST",
			url:path_ajax_script+'/index.php?mod=tour&act=ajSubmitItinerary',
			data: adata,
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				if(html.indexOf('_INSERT_SUCCESS')>=0){
					loadItinerary(tour_id);
					alertify.success(insert_success);
					$('#box_AddItinerary .close_pop').trigger('click');
				}
				if(html.indexOf('_UPDATE_SUCCESS')>=0){
					alertify.success(update_success);
					loadItinerary(tour_id);
					$('#box_EditItinerary .close_pop').trigger('click');
				}
				if(html.indexOf('_ERROR')>=0){ alertify.error(insert_error);}
				if(html.indexOf('_EXIST')>=0){ alertify.error(exist_error);}
			}
		});
		return false;
	});
	$(".btnAddCustomHotel").live("click",function(){
		var $_this = $(this);
		var adata = {
			'tour_itinerary_id' : $_this.attr('itinerary_id')
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?mod=tour&act=ajGetFormAddCustomHotel',
			data: adata,
			dataType: "html",
			cache: true,
			success: function(html){
				makepopupnotresize('900px','470px',html,'box_CreateCustomHotel');
				$('#box_CreateCustomHotel').css('top','40px');
				$('#'+$('.hotel_content_editor').attr('id')).isoTextArea();
				$('#'+$('.hotel_content_editor').attr('id')+'_ifr').height(120);
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.btnSubmitCustomHotel').live('click',function(){
		var $_this = $(this);
		/**/
		var $tour_itinerary_id = $_this.attr('tour_itinerary_id');
		var $title = $('#box_CreateCustomHotel input[name=title]').val();
		var $country_id = $('#box_CreateCustomHotel select[name=country_id]').val();
		var $city_id = $('#box_CreateCustomHotel select[name=city_id]').val();
		var $address = $('#box_CreateCustomHotel input[name=address]').val();
		var $star = $('#box_CreateCustomHotel select[name=star]').val();
		var $price = $('#box_CreateCustomHotel input[name=price]').val();
		var $intro = $('#box_CreateCustomHotel textarea[name=intro]').val();
		var $content = tinyMCE.get($('.hotel_content_editor').attr('id')).getContent();
		/* Check valid */
		if($title==''){
			alertify.error(field_is_required);
			$('#box_CreateCustomHotel input[name=title]').focus();
			return false;
		}
		if($country_id==''){
			alertify.error(field_is_required);
			$('#box_CreateCustomHotel select[name=country_id]').addClass('error').focus();
			return false;
		}
		if($city_id==''){
			alertify.error(field_is_required);
			$('#box_CreateCustomHotel select[name=city_id]').addClass('error').focus();
			return false;
		}
		if($address==''){
			alertify.error(field_is_required);
			$('#box_CreateCustomHotel input[name=address]').addClass('error').focus();
			return false;
		}
		if($star==''){
			alertify.error(field_is_required);
			$('#box_CreateCustomHotel select[name=star]').addClass('error').focus();
			return false;
		}
		if($price==''){
			alertify.error(field_is_required);
			$('#box_CreateCustomHotel input[name=price]').addClass('error').focus();
			return false;
		}
		var adata = {
			'title': $title,
			'country_id': $country_id,
			'city_id': $city_id,
			'address': $address,
			'star': $star,
			'price': $price,
			'intro': $intro,
			'content': $content,
			'tour_itinerary_id': $tour_itinerary_id,
			'hotel_id': $_this.attr('hotel_id'),
			'is_pop':1, 
			'is_online':1
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?mod=tour&act=ajSubmitCustomHotel',
			data: adata,
			dataType: "html",
			success: function(html){
				if(html.indexOf('_SUCCESS')>=0){
					alertify.success(insert_success);
					$('#box_CreateCustomHotel .close_pop').trigger('click');
					$('#frmLoadBoxSelectHotel .close_pop').trigger('click');
					loadHotelRecommend($tour_itinerary_id,'');
				}
				if(html.indexOf('_EXIST')>=0){
					alertify.error(exist_error);
				}
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.clickDeleteItinerary').live('click',function(){
		var _this = $(this);
		if(confirm(confirm_delete)){
			var adata = {
				'tour_id'		: 	tour_id,
				'itinerary_id'	:	_this.attr('data')
			};
			vietiso_loading(1);
			$.ajax({
				type:'POST',	
				url:path_ajax_script+'/index.php?mod=tour&act=ajDeleteItinerary',	
				data: adata,	
				dataType:'html',	
				success:function(html){
					loadItinerary(tour_id);
					alertify.success(delete_success);
					vietiso_loading(0);
				}
			});
		}
		return false;
	});
	$('.moveItinerary').live('click',function(){
		var _this = $(this);
		var adata = {
			'itinerary_id'   		: 	_this.attr('data'),
			'tour_id' 				: 	tour_id,
			'direct'				: 	_this.attr('direct')
		};
		$.ajax({
			type:'POST',	
			url:path_ajax_script+'/index.php?mod=tour&act=ajMoveItinerary',	
			data: adata,	
			dataType:'html',	
			success:function(html){
				loadItinerary(tour_id);
			}
		});
	});
	$('.btnDeleteTourHotel').live('click',function(){
		var $_this = $(this);
		if(confirm(confirm_delete)){
			var $tour_itinerary_id = $_this.attr('_tour_itinerary_id');
			var adata = {
				'tour_hotel_id' : $_this.attr('data')
			};
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url:path_ajax_script+'/index.php?mod=tour&act=ajDeleteTourHotel',
				data: adata,
				dataType: "html",
				success: function(html){
					loadHotelRecommend($tour_itinerary_id,'');
					vietiso_loading(0);
					alertify.error(delete_success);
				}
			});
		}
		return false;
	});
	/* Image Gallery Management */
	$('#clickToMamagerGallery').click(function(){
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=media&act=ajPhotosGalleryManage",
			data: {
				"table_id":tour_id,
				'type':type
			},
			dataType: "html",
			success: function(html){
				makepopup('80%','80%',html,'box_PhotosGalleryManage');
				$('#box_PhotosGalleryManage').css('top','20px');
				/* Caculator Pop Loading */
				var w = $('#box_PhotosGalleryManage').outerWidth()-12;
				var h = $('#box_PhotosGalleryManage').outerHeight()-12;
				$('.loading_pop').css({'width':w,'height':h});
				getListGallery(tour_id,type,'');
			}
		});
		return false;
	});
	if(act=='new'){
		
	}
	// Edit
	if(act=='edit'){
		loadTourExtension(tour_id);
		loadDestination(tour_id);
		countPhotosGallerty(tour_id,type);
		loadTourPrice();
		loadListCustomField();
		loadListTourByDays('','',1,2);
		
		$("#searchkey").bind('keyup change',function(){
			var _this = $(this);
			if(_this.val()!=''){
				clearTimeout(aj_search);	
				search_tour();
			}else{
				$("#quickSearch").html('');	
			}
		});
		
		$('.clickChooiseTour').live('click',function(){
			var _this = $(this);
			var adata = {
				'tour_1_id' : tour_id,
				'tour_2_id': _this.attr('data')
			};
			$.ajax({
				type:'POST',	
				url:path_ajax_script+'/index.php?mod=tour&act=ajAddTourExtension',	
				data: adata,	
				dataType:'html',	
				success:function(html){
					htm = html.replace(' ','');
					if(htm=='_SUCCESS'){
						_this.remove();
						loadTourExtension(tour_id);
					}
					if(htm=='_EXIST'){
						alert('Tour này đã tồn tại !');
					}
				}
			});
		});
		$('.moveTourExtension').live('click',function(){
			var _this = $(this);
			var adata = {
				'tour_extension_id' : 	_this.attr('data'),
				'tour_1_id' 		: 	tour_id,
				'direct'			: 	_this.attr('direct')
			};
			$.ajax({
				type:'POST',	
				url:path_ajax_script+'/index.php?mod=tour&act=ajMoveTourExtension',	
				data: adata,	
				dataType:'html',	
				success:function(html){
					loadTourExtension(tour_id);
				}
			});
		});
		$('.clickDeleteTourExtension').live('click',function(){
			var _this = $(this);
			if(confirm(confirm_delete)){
				var adata = {
					'tour_extension_id'	: _this.attr('data')
				};
				$.ajax({
					type:'POST',	
					url:path_ajax_script+'/index.php?mod=tour&act=ajDeleteTourExtension',	
					data: adata,	
					dataType:'html',	
					success:function(html){
						loadTourExtension(tour_id);
						alert('Bạn đã xóa thành công !');
					}
				});
			}
			return false;
		});
		$('.moveDestination').live('click',function(){
			var _this = $(this);
			var adata = {
				'tour_destination_id'   : 	_this.attr('data'),
				'tour_id' 				: 	tour_id,
				'direct'				: 	_this.attr('direct')
			};
			$.ajax({
				type:'POST',	
				url:path_ajax_script+'/index.php?mod=tour&act=ajMoveDestination',	
				data: adata,	
				dataType:'html',	
				success:function(html){
					loadDestination(tour_id);
				}
			});
		});
		$('.clickDeleteDestination').live('click',function(){
			var _this = $(this);
			if(confirm(confirm_delete)){
				var adata = {
					'tour__id'	: tour_id,
					'tour_destination_id':_this.attr('data')
				};
				$.ajax({
					type:'POST',	
					url:path_ajax_script+'/index.php?mod=tour&act=ajDeleteSelectedCity',	
					data: adata,	
					dataType:'html',	
					success:function(html){
						loadDestination(tour_id);
						alertify.success('Bạn đã xóa thành công !');
					}
				});
			}
			return false;
		});
		$('#clickToManagerDestination').click(function(){
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=tour&act=ajLoadFormManagerDestination",
			data: {
				'tour_id'	: tour_id	
			},
			dataType: "html",
			success: function(html){
				makepopup($(window).width()/1.6,$(window).height()/1.5,html,'frmManagerDestination');
			}
		});
		return false;
	});
	$('#clickAddQuickCity').live('click',function(){
		var _this = $(this);
		var country_id = $('input[class=chkid_country]:checked').val();
		if(country_id==undefined){
			alertify.error('Bạn phải lựa chọn một Quốc Gia !');
			return false;
		}else{
			var adata = {
				'country_id' : country_id
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=city&act=ajLoadFormAddQuickCity",
				data: adata,
				dataType: "html",
				success: function(html){
					makepopup($(window).width()/3.5,$(window).height()/4,html,'frmFormAddQuickCity');
				}
			});
		}
		return false;
	});
	$('#clickSubmitQuickCity').live('click',function(){
		var _this = $(this);
		if($('#title').val()==''){
			$('#title').focus().addClass('error');
			alertify.error('Bạn chưa nhập tên Thành phố/ Tỉnh thành');
			return false;
		}
		var adata = {
			'title'	: $('#title').val(),
			'country_id': _this.attr('country_id'),
			'city_id' : _this.attr('city_id')
		};
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=city&act=ajSubmitQuickCity",
			data: adata,
			dataType: "html",
			success: function(html){
				$('#frmFormAddQuickCity .closeEv').trigger('click');
				alertify.success('Cập nhật thành công');
				getListCityByCountry(_this.attr('country_id'),tour_id);
			}
		});
	});
	
	$('input[class=chkid_country]').live('change',function(){
		var _this=$(this);
		var checked=_this.attr('checked');
		if(checked || checked=='checked'){
			$('input[class=chkid_country]').removeAttr('checked');
			_this.attr('checked',true);
			getListCityByCountry(_this.val(),tour_id);
		}
	});
	
	$('input[class=chkid_city]').live('change',function(){
		var _this=$(this);
		var val=_this.val();
		var action='';
		var checked=_this.attr('checked');
		if(checked){
			action='add';
		}else{
			action='remove';
		}
		var adata={
			'tour_id':tour_id,
			'city_id':val,
			'action':action
		};
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=tour&act=ajSaveDestionation",
			data: adata,
			dataType: "html",
			success: function(html){
				loadDestination(pvalTable);
			}
		});
	});
	$('#addTourPriceRow').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		$.ajax({
			type: 'POST',
			url:path_ajax_script+'/index.php?mod=tour&act=ajLoadNewTourPriceRow',	 
			dataType:'html', 
			data: {
				'tour_id':tour_id
			},
			success: function(html){
				$('.dropdown-toggle').trigger('click');
				makepopup('400','',html,'NewTourPriceRow');
			}
		});
		return false;
	});
	
	$('#addTourPriceCol').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		$.ajax({
			type: 'POST',
			url:path_ajax_script+'/index.php?mod=tour&act=ajLoadNewTourPriceCol', 
			dataType:'html', 
			data: {
				'tour_id':tour_id
			},
			success: function(html){
				$('.dropdown-toggle').trigger('click'); 
				makepopup('400','',html,'NewTourPriceCol');
			}
		});
		return false;
	});
	
	$('#clickToAddTourPriceCol').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		var adata = 
		{
			'tour_id':tour_id,
			'title': $('#titleCol').val()
		};
		$('#clickToCloseNewTourPriceCol').click();
		$.ajax({
			type: 'POST',
			url:path_ajax_script+'/index.php?mod=tour&act=ajAddTourPriceCol',  
			data: adata,
			dataType:'html',
			success: function(html){
				$('#ajax-indicator').hide();
				$('.close_pop').trigger('click');
				loadTourPrice();
			}
		});
		return false;
	});
	
	$('.clickToAddTourPriceRow').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		var adata = 
		{
			'tour_id':tour_id,
			'title': $('#titleRow').val()
		};
		$.ajax({
			type: 'POST',
			url:path_ajax_script+'/index.php?mod=tour&act=ajAddTourPriceRow',   
			data: adata,
			dataType:'html',
			success: function(html){
				$('#ajax-indicator').hide();
				loadTourPrice();
				$('.closeEv').click();
			}
		});
		return false;
	});
	
	$('.editTourPriceCol').live('click',function(){
		var _this = $(this);
		var adata = {
			'tour_id': tour_id
		};
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=tour&act=ajCheckTourPriceCol', 
			data: adata,
			dataType:'html',
			success: function(html){
				if(html==0){
					$('#addTourPriceCol').trigger('click');
				}else{
					$('#ajax-indicator').show();
					$.ajax({
						type: 'POST',
						url:path_ajax_script+'/index.php?mod=tour&act=ajLoadEditTourPriceCol',
						dataType:'html', 
						data: {
							'id':_this.attr('data')
						},
						success: function(html){
							makepopup('400','',html,'EditTourPriceCol');
						}
					});
				}
			}
		});
		return false;
	});
	$('.editTourPriceRow').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		$.ajax({
			type: 'POST',
			url:path_ajax_script+'/index.php?mod=tour&act=ajLoadEditTourPriceRow', 
			dataType:'html', 
			data: {
				'id':_this.attr('data')
			},
			success: function(html){
				makepopup('400','',html,'EditTourPriceRow');
			}
		});
		return false;
	});
	
	$('#clickToEditTourPriceRow').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		var adata = 
		{
			'id':_this.attr('data'),
			'title': $('#titleRow').val()
		};
		$.ajax({
			type: 'POST',
			url:path_ajax_script+'/index.php?mod=tour&act=ajUpdateTourPriceRow', 
			data: adata,
			dataType:'html',
			success: function(html){
				$('#ajax-indicator').hide();
				loadTourPrice();
				$('.closeEv').click();
			}
		});
		return false;
	});
	$('#clickToEditTourPriceCol').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		var adata = 
		{
			'id':_this.attr('data'),
			'title': $('#titleCol').val()
		};
		$.ajax({
			type: 'POST',
			url:path_ajax_script+'/index.php?mod=tour&act=ajUpdateTourPriceCol', 
			data: adata,
			dataType:'html',
			success: function(html){
				$('#ajax-indicator').hide();
				loadTourPrice();
				$('.closeEv').click();
			}
		});
		return false;
	});
	$('.deleteTourPriceRow').live('click',function(){
		if(confirm('Bạn có chắc chắn muốn xóa dòng này ?')){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = 
			{
				'id':_this.attr('data')
			};
			$.ajax({
				type: 'POST',
				url:path_ajax_script+'/index.php?mod=tour&act=ajDeleteTourPriceRow', 
				data: adata,
				dataType:'html',
				success: function(html){
					$('#ajax-indicator').hide();
					loadTourPrice();
				}
			});
		}
		return false;
	});
	$('.deleteTourPriceCol').live('click',function(){
		if(confirm('Bạn có chắc chắn muốn xóa cột này ?')){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = 
			{
				'id':_this.attr('data')
			};
			$.ajax({
				type: 'POST',
				url:path_ajax_script+'/index.php?mod=tour&act=ajDeleteTourPriceCol',  
				data: adata,
				dataType:'html',
				success: function(html){
					$('#ajax-indicator').hide();
					loadTourPrice();
				}
			});
		}
		return false;
	});
	
	$('.editTourPriceVal').live('click',function(){
		var _this = $(this);
		var adata = {
			'tour_id': tour_id
		};
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=tour&act=ajCheckTourPriceCol', 
			data: adata,
			dataType:'html',
			success: function(html){
				if(html==0){
					alert('Bạn chưa nhập tiêu đề cột');
					return false;
				}else{
					$('#ajax-indicator').show();
					$.ajax({
						type: 'POST',
						url:path_ajax_script+'/index.php?mod=tour&act=ajLoadEditTourPriceVal',  
						dataType:'html', 

						data: {
							'tour_price_col_id':_this.attr('tour_price_col_id'),
							'tour_price_row_id':_this.attr('tour_price_row_id')
						},
						success: function(html){
							$('#ajax-indicator').hide(); 
							makepopup('400','',html,'EditTourPriceVal');
						}
					});
				}
			}
		});
		return false;
	});
	$('#clickToEditTourPriceVal').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		var adata = 
		{
			'tour_':tour_id,
			'tour_price_col_id':_this.attr('tour_price_col_id'),
			'tour_price_row_id':_this.attr('tour_price_row_id'),
			'price': $('#titleVal').val()
		};
		$.ajax({
			type: 'POST',
			url:path_ajax_script+'/index.php?mod=tour&act=ajUpdateTourPriceVal',   
			data: adata,
			dataType:'html',
			success: function(html){
				$('#ajax-indicator').hide();
				loadTourPrice();
				$('.closeEv').click();
			}
		});
		return false;
	});
	$('.moveTourPriceRow').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		var adata = 
		{
			'id':_this.attr('data'),
			'direct': _this.attr('direct')
		};
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=tour&act=ajMoveTourPriceRow', 
			data: adata,
			dataType:'html',
			success: function(html){
				$('#ajax-indicator').hide();
				loadTourPrice();
			}
		});
		return false;
	});
	$('.moveTourPriceCol').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		var adata = 
		{
			'id':_this.attr('data'),
			'direct': _this.attr('direct')
		};
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=tour&act=ajMoveTourPriceCol', 
			data: adata,
			dataType:'html',
			success: function(html){
				$('#ajax-indicator').hide();
				loadTourPrice();
			}
		});
		return false;
	});
	$('#resetTourPriceDefault').live('click',function(){
		if(confirm("Bạn có chắc muốn xóa bỏ bảng giá này")){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = 
			{
				'tour_id' : tour_id
			};
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=tour&act=ajResetTourPriceDefault', 
				data: adata,
				dataType:'html',
				success: function(html){
					$('.dropdown-toggle').click();
					loadTourPrice();
				}
			});
		}
		return false;
	});
	
	$('.frmPop .clickToClose').live('click',function(){
		var idtmp =$(this).closest('.frmPop');
		$('#isoblanketpop_'+idtmp.attr('id')).remove();
		idtmp.remove();	
	});
	/* Depart Date */
	$('#clickToAddMoreInformation').live('click',function(){
			var _this = $(this);
			if(_this.attr('action')=='_ADD'){
				if($('#type').val()==''){
					$('#type').addClass('error').focus();
					alertify.error('Select type add !');
					return false;
				}
				var adata = {
					'type' : $('#type').val(),
					'tour_id': tour_id
				};
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/index.php?mod=tour&act=ajLoadFieldAddInformation",
					data: adata,
					dataType: "html",
					success: function(html){
						loadListCustomField();
						makeSelectBoxTypeCustomField();
					}
				});	
			}else{
				if($('#type').val()==''){
					$('#type').addClass('error').focus();
					alertify.error('Select type add !');
					return false;
				}
				var _type = $('#type').val();
				if(_type=='_CUSTOM'){
					var adata = {
						'type' : $('#type').val(),
						'tour_id': tour_id
					};
					$.ajax({
						type: "POST",
						url: path_ajax_script+"/index.php?mod=tour&act=ajGetFormAddTourCustomField",
						data: adata,
						dataType: "html",
						success: function(html){
							makepopup($(window).width()/4,$(window).height()/4,html,'frmFormAddTourCustomField');
						}
					});	
				}else{
					/*auto save*/
					autoSaveCustomField();
					var adata = {
						'type' : $('#type').val(),
						'tour_id': tour_id
					};
					$.ajax({
						type: "POST",
						url: path_ajax_script+"/index.php?mod=tour&act=ajLoadFieldAddInformation",
						data: adata,
						dataType: "html",
						success: function(html){
							loadListCustomField();
							makeSelectBoxTypeCustomField();
						}
					});	
				}
				return false;
			}
			return false;
		});
		$('#clickAddCustomField').live('click',function(){
			var _this = $(this);
			if($('#field_name').val()==''){
				$('#field_name').addClass('error').focus();
				alertify.error('Please enter your title');
				return false;
			}
			var adata = {
				'tour_id' 	: _this.attr('tour_id'),
				'type'		:	_this.attr('_type'),
				'field'		: $('#field_name').val(),
				'field_type': $('select[name=field_type]').val(),
				'is_editor'	: $('input[name=is_editor]:checked').val()
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=tour&act=ajSubmitAddCustomField",
				data: adata,
				dataType: "html",
				success: function(html){
					loadListCustomField();
					$('.closeEv').trigger('click');
				}
			});	
		});
		$('.clickEditCustomField').live('click',function(){
			var _this = $(this);
			var adata = {
				'tour_custom_field_id'	: _this.attr('data')
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=tour&act=ajLoadFormEditCustomField",
				data: adata,
				dataType: "html",
				success: function(html){
					makepopup($(window).width()/4,$(window).height()/5,html,'frmEditItinerary');
				}
			});	
		});
		$('#clickToSaveCustomField').live('click',function(){
			var _this = $(this);
			var adata = {
				"clsTable" : 'TourCustomField',
				"pkey" : 'tour_custom_field_id',
				"pvalTable" : _this.attr('tour_custom_field_id'),
				"toField" : _this.attr('toField'),
				"val" : $('#custom_field_name').val(),
				'allowDuplicate':'1'
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=ajax&act=saveField",
				data: adata,
				dataType: "html",
				success: function(html){
					alertify.success('Saved !');
					$('.closeEv').click();
					loadListCustomField();
				}
			});
		});
		$('.clickMoveCustomField').live('click',function(){
			var _this = $(this);
			var adata = {
				'tour_custom_field_id'	: _this.attr('data'),
				'direct'				: _this.attr('direct')
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=tour&act=ajMoveTourCustomField",
				data: adata,
				dataType: "html",
				success: function(html){
					loadListCustomField();
				}
			});	
			return false;
		});
		$('.clickDeleteCustomField').live('click',function(){
			if(confirm(confirm_delete)){
				var _this = $(this);
				var adata = {
					'tour_custom_field_id'	: _this.attr('data')
				};
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/index.php?mod=tour&act=ajDeleteTourCustomField",
					data: adata,
					dataType: "html",
					success: function(html){
						loadListCustomField();
						makeSelectBoxTypeCustomField();
					}
				});
			}
			return false;	
		});
		$('.clickAotoSave').live('click',function(){
			var _this = $(this);
			var content = '';
			if(_this.attr('_field_type')=='_TEXTAREA'){
				var editor_id = _this.attr('rel');
				content= tinyMCE.get(editor_id).getContent();
				if(content==''){content = $('#'+editor_id).val();}
			}else if(_this.attr('_field_type')=='_TEXTAREA_NOT_EDITOR'){
				var el = _this.attr('rel');
				content= $('#'+el).val();
			}else{
				var el = _this.attr('rel');
				content= $('#'+el).val();
			}
			var adata = {
				"clsTable" : 'TourCustomField',
				"pkey" : 'tour_custom_field_id',
				"pvalTable" : _this.attr('data'),
				"toField" : _this.attr('toField'),
				"val" : content,
				'allowDuplicate':'1'
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=ajax&act=saveField",
				data: adata,
				dataType: "html",
				success: function(html){
					alertify.success('Saved !');
				}
			});
		});
		$('.clickToSaveAll').live('click',function(){
			autoSaveCustomField();
		});
		$('#deleteFile').live('click',function(){
			$('#img_src').attr('src','');
			var adata = {
				'itinerary_id' : $(this).attr('data')
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=tour&act=ajRemoveImageItinerary",
				data: adata,
				dataType: "html",
				success: function(html){
					alertify.success('Success !');
				}
			});
			return false;
		});
		$('#ajaddTourByDay').click(function(){
			var $_this = $(this);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=tour&act=ajloadAddTourByDay",
				data: {'tour_id': $_this.attr('tour_id')},
				dataType: "html",
				success: function(html){
					makepopup('80%','64%',html,'cmsBox_AddTourByDay');
					$('.isodatepicker').isodatepicker();
					$('.isoprice').isopriceformat();
					$(".mask").mask("99:99");
				}
			});
			return false;
		});
		$('.clkEdit').live('click',function(){
			var $_this = $(this);
			var adata = {
				'tour_by_day_id' : $_this.attr('data')
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=tour&act=ajloadEditTourByDay",
				data: adata,
				dataType: "html",
				success: function(html){
					makepopup('80%','64%',html,'cmsBox_EditTourByDay');
					$('.isodatepicker').isodatepicker();
					$('.isoprice').isopriceformat();
					$(".mask").mask("99:99");
				}
			});
			return false;
		});
		$('#clickToSubmitTourByDay').live('click',function(){
			var $_this = $(this);
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
			if($('input[name=price]').val()==''){
				$('input[name=price]').focus();
				alertify.error('Price tour Invalid !');
				return false;
			}
			if($('input[name=number_seat]').val()==''){
				$('input[name=number_seat]').focus();
				alertify.error('Number seats Invalid !');
				return false;
			}
			$('#form-post').ajaxSubmit({
				'type' : 'POST',
				'url': path_ajax_script+"/index.php?mod=tour&act=ajSubmitTourByDay",
				data: {
					'tour_id': $_this.attr('tour_id'),
					'tour_by_day_id': $_this.attr('tour_by_day_id')
				},
				dataType: "html",
				success: function(html){
					var htm = html.replace(' ','');
					if(htm=='_SUCCESS'){
						alertify.success('Success !');
						$('.close_pop').trigger('click');
						var $number_per_page = $('.paginate_length').val();
						loadListTourByDays('','',1,$number_per_page);
					}
				}
			});
		});
		$('.clkDelete').live('click',function(){
			if(confirm(confirm_delete)){
				var $_this = $(this);
				var adata = {
					'tour_by_day_id' : $_this.attr('data')
				};
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/index.php?mod=tour&act=ajRemoveTourByDays",
					data: adata,
					dataType: "html",
					success: function(html){
						loadListTourByDays();
					}
				});
				return false;
			}
		});
		$('.dataTable_length').live('change',function(){
			var $_this = $(this);
			var $keyword = $('input[name=searchkey]').val();
			var $departure_date = $('input[name=departure_date]').val();
			loadListTourByDays($keyword,$departure_date,1,$_this.val());
		});	
	}
});
// End Ready here !!
function loadHotelRecommend($tour_itinerary_id,$keyword){
	var adata = {
		'tour_itinerary_id': $tour_itinerary_id,
		'keyword' : $keyword
	};
	vietiso_loading(1);
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=tour&act=ajListHotelRecommend",
		data: adata,
		dataType: "html",
		success: function(html){
			$('#tblHotelRecommend').html(html);
			vietiso_loading(0);
		}
	});
}
function search_tour(){
	aj_search = setTimeout(function(){
		$.ajax({
			type:'POST',	
			url:path_ajax_script+'/index.php?mod=tour&act=ajGetSearch',	
			data:{"keyword":$("#searchkey").val(),"tour_id":tour_id},	
			dataType:'html',	
			success:function(html){
				$("#quickSearch").html(html);	
			}
		});
	},500);
}
function loadTourExtension(tour_id){
	$.ajax({
		type:'POST',	
		url:path_ajax_script+'/index.php?mod=tour&act=ajLoadTourExtension',	
		data: {"tour_1_id": tour_id},	
		dataType:'html',	
		success:function(html){
			$('#tblTourExtension').html(html);
		}
	});
}
function loadDestination(tour_id){
	$.ajax({
		type: "POST",
		url:path_ajax_script+'/index.php?mod=tour&act=ajGetListSelectedDestination',	
		data: {'tour_id' : tour_id},
		dataType: "html",
		success: function(html){
			$('#tblDestination').html(html);
		}
	});
}
function loadItinerary(tour_id){
	$.ajax({
		type: "POST",
		url:path_ajax_script+'/index.php?mod=tour&act=ajGetListItinerary',	
		data: {'tour_id' : tour_id},
		dataType: "html",
		success: function(html){
			$('#tblTourItinerary').html(html);
		}
	});
}
function func_updateItinerary(tour_id){
	var adata = {
		'tour_id':tour_id
	};
	$.ajax({
		type: "POST",
		url:path_ajax_script+'/index.php?mod=tour&act=ajUpdateTourItinerary',	
		data: adata,
		dataType: "html",
		success: function(html){
			var htm = html.split('$$');
			if(htm[0]=='NOT_CUSTOM'){
				alert(htm[1]);
				$('input[name=number_day]').val(htm[1]);
				$('input[name=number_night]').val(htm[2]);
			}
		}
	});
}
function getListCityByCountry(country_id,tour_id){
	var adata = {
		'country_id' : country_id,
		'tour_id'	:tour_id
	};	
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=tour&act=ajMakeListCityByCountry",
		data: adata,
		dataType: "html",
		success: function(html){
			$('#rightPop').html(html);
		}
	});
}
function getListGallery(table_id, type, keyword){
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=media&act=ajLoadPhotosGallery",
		data: {
			'table_id':table_id,
			'type':type,
			'keyword': keyword
		},
		dataType: "html",
		success: function(html){
			$('#preview').html(html);
			countPhotosGallerty(table_id,type);
		}
	});
}
function countPhotosGallerty(table_id,type){
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=media&act=ajCountPhotosGallerty",
		data: {
			'table_id':table_id,
			'type':type
		},
		dataType: "html",
		success: function(html){
			$('#counter_photos_gallery').html(parseInt(html));
		}
	});
}
function loadTourPrice(){
	$('#ajax-indicator').show();
	var adata = {
		'tour_id':tour_id
	};
	$.ajax({
		type: 'POST',
		url:path_ajax_script+'/index.php?mod=tour&act=ajLoadTourPrice',
		data: adata,
		dataType:'html',
		success: function(html){
			$('#ajax-indicator').hide();
			$('#tblTourPrice').html(html);
		}
	});
}
function generateItinerary(){
	var adata = {
		'tour_id': tour_id
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=tour&act=ajDynamicCalulationItinerary",
		data: adata,
		dataType: "html",
		success: function(html){}
	});	
}
function autoSaveCustomField(){
	$('.clickAotoSave').trigger('click');
}
function loadListCustomField(){
	var adata = {
		'tour_id': tour_id
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=tour&act=ajLoadListCustomField",
		data: adata,
		dataType: "html",
		success: function(html){
			$('#lstTourCustom').html(html);
			$('.editor').each(function(){
				var _this = $(this);
				var editor_id = _this.attr('id');
				$('#'+editor_id).isoTextArea();
			});
		}
	});	
}
function makeSelectBoxTypeCustomField(){
	var adata = {
		'tour_id': tour_id
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=tour&act=ajMakeSelectBoxTypeCustomField",
		data: adata,
		dataType: "html",
		success: function(html){
			$('#type').html(html);
		}
	});	
}
function loadListTourByDays($keyword,$departure_date,$page,$number_per_page){
	var adata = {
		'tour_id': tour_id,
		'keyword': $keyword,
		'departure_date': $departure_date,
		'page': $page,
		'number_per_page': $number_per_page
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=tour&act=ajLoadListToursByDays",
		data: adata,
		dataType: "html",
		success: function(html){
			var htm = html.split('$$');
			$('#tblHolderTourDay').html(htm[0]);
			$('#dataTable_paginate').html(htm[1]);
		}
	});
}
function getCheckBoxValueByClass(classname){
	var names = [];
	$('.'+classname+':checked').each(function() {
		names.push(this.value);
	});
	return names;
}