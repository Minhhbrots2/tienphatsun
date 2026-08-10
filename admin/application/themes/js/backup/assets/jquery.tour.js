$().ready(function(){
	$(".createNewTour").click(function(){
		var adata = {};
		if(mod=='tour'){
			adata['domain_id'] = $('select[name=domain_id]').val();
			adata['cat_id'] = $('select[name=cat_id]').val();
		}
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/?mod=tour&act=ajLoadCreateTour",
			data: adata,
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				makepopupnotresize('500px','auto',html,'CreateTour');
			}
		});
		return false;
	});
	$(".clickToAddNewTour").live("click",function(){
		if($("#NewTourTitle").val()==''){
			$("#NewTourTitle").focus();
			alertify.error("Vui lòng nhập tên tour!");
			return false;
		}
		vietiso_loading(1);
		$('#frmCrxTour').ajaxSubmit({
			type: "POST",
			url: path_ajax_script+"/?mod=tour&act=ajCreateNewTour",
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				if(html.indexOf('_ERROR') >= 0) {
					alertify.error("Tour này đã tồn tại. Vui lòng nhập tên tour khác và thử lại!");
				} else {
					location.href = html;
				}
			}
		});
		return false;
	});
	
	if(mod=='tour'){
		if(act=='default'){
			$('#forums select').change(function(){
				$('#forums').submit();
			});
		}
		if(act == 'edit'){
			loadListHotelRecommend(tour_id);
			loadListTourOperator(tour_id,0);
			loadTourPrice();
			loadTourCruisePrice();
			/* Init function */
			
			$('input[name=is_show_price_table]').live("change",function(){
				vietiso_loading(1);
				var $_this = $(this);
				var adata = {
					"clsTable" : 'Tour',
					"pkey" : 'tour_id',
					"pvalTable" : tour_id,
					"toField" : 'is_show_price_table',
					"val" : $_this.val(),
					"allowDuplicate":'1'
				};
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/index.php?mod=ajax&act=saveField",
					data: adata,
					dataType: "html",
					success: function(html){
						vietiso_loading(0);
					}
				});
			});
			$('select[name=tour_type_id]').change(function(){
				var $_this = $(this);
				var $tour_id = $('#hid_tour_id').val();
				var adata = {
					"clsTable" : 'Tour',
					"pkey" : 'tour_id',
					"pvalTable" : $tour_id,
					"toField" : 'tour_type_id',
					"val" : $_this.val().join('|'),
					'allowDuplicate':'1'
				};
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/index.php?mod=ajax&act=saveField",
					data: adata,
					dataType: "html",
					success: function(html){
					}
				});
			});
			$("#EditTourLink").live('click',function(){
				var $_this = $(this);
				var adata = {
					'tour_id' : $_this.attr('tour_id')
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod=tour&act=ajLoadFormEditTourLink',
					data: adata,
					dataType: "html",
					success: function(html){
						makepopupnotresize('55%','auto',html,'box_EditTourLink');
						$('#box_EditTourLink').css('top','120px');
						vietiso_loading(0);
					}
				});
				return false;
			});
			$('.clickToUpdateTourLink').live('click',function(){
				vietiso_loading(1);
				var $_this = $(this);
				var adata = {
					'tour_id'		: 	$_this.attr('tour_id'),
					'permalink'	:	$("#TourLink").val()
				};
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod=tour&act=ajSaveTourLink',
					data: adata,
					dataType: "html",
					success: function(html){
						vietiso_loading(0);
						if(html.replace(' ','')=='0'){
							$("#errTourLink").fadeIn();
						}
						else{
							$('#box_EditTourLink .close_pop').trigger('click');
							$("#FULL_URL").text(html).attr('href',html);
						}
					}
				});
				return false;
			});
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
						makepopupnotresize('68%','auto',html,'box_AddItinerary');
						$('#box_AddItinerary').css('top','20px');
						$('#'+$('.textarea_content_editor').attr('id')).isoTextAreaFix();
						vietiso_loading(0);
					}
				});
				return false;
			});
			$('.clickEditItinerary').live('click',function(){
				var $_this = $(this);
				var adata = {
					'tour_id'		: tour_id,
					'itinerary_id'	: $_this.attr('data')
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod=tour&act=ajLoadFormEditItinerary',
					data: adata,
					dataType: "html",
					success: function(html){
						makepopupnotresize('68%','auto',html,'box_EditItinerary');
						$('#box_EditItinerary').css('top','20px')
						$('#'+$('.textarea_content_editor').attr('id')).isoTextAreaFix();
						loadListHotelItinerary(tour_id,$_this.attr('data'),'');
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
				/*var $transport_id = $('select[name=transport_id]');*/
				var $content = tinyMCE.get($('.textarea_content_editor').attr('id')).getContent();
				var $image = $('#imgTourItinerary_hidden').val();
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
				/*if($meals==''){
					alertify.error(field_is_required);
					$('input[class=chkid_meal]:first').focus();
					return false;
				}*/
				/*if($transport_id.val()==''){
					alertify.error(field_is_required);
					$transport_id.focus().addClass('error');
					return false;
				}*/
				/**/
				var adata = {
					'itinerary_id' 	: 	$_this.attr('itinerary_id'),
					'tour_id' 		: 	tour_id,
					'day' 			: 	$day,
					'title' 		: 	$title,
					'meals' 		: 	$meals.join(),
					/*'transport_id' 	: 	$transport_id.val(),*/
					'content'	  	: 	$content,
					'image'	  		: 	$image
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
			$('.change_transport_property').live('change',function(){
				var $_this = $(this);
				if($_this.val()==''){
					$('.preview_transport_img').html('');
				}
				var adata = {'transport_id' : $_this.val()};
				$.ajax({
					type:'POST',	
					url:path_ajax_script+'/index.php?mod=tour&act=ajPreviewTransportImg',	
					data: adata,	
					dataType:'html',	
					success:function(html){
						$('.preview_transport_img').html(html);
					}
				});
			});
			/* Tour Hotels */
			$('.ajaxOpenChoiceHotel').live('click',function(){
				var $_this = $(this);
				var adata = {};
				adata['tour_id'] = $_this.attr('tour_id');
				adata['itinerary_id'] = $_this.attr('itinerary_id');
				adata['tour_hotel_id'] = $_this.attr('tour_hotel_id');
				
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/index.php?mod=tour&act=ajaxCheckDomainExist",
					dataType: "html",
					data :adata,
					success: function(html){
						vietiso_loading(0);
						if(html.indexOf('_EXIST')>=0){
							$.ajax({
								type: "POST",
								url: path_ajax_script+"/index.php?mod=tour&act=ajaxGetBoxHotelRecommend",
								dataType: "html",
								data :adata,
								success: function(html){
									makepopup('80%','auto',html,'pop_HotelRecommend');
									$('#pop_HotelRecommend').css('top','30px');
									vietiso_loading(0);
								}
							});
						}else{
							alert('First! You can select domain for this tour');
						}
					}
				});
				return false;
			});
			$(document).on('change', '#pop_HotelRecommend select[name=domain_id]', function(ev){
				var $_this = $(this);
				var $domain_id = $_this.val();
				
				vietiso_loading(1);
				$('#pop_HotelRecommend select[name=country_id]').html('<option>Loading...</option>');
				$.ajax({
					type:'POST',	
					url:path_ajax_script+"/index.php?mod=tour&act=ajaxSelectCountry",	
					data:{'domain_id': $domain_id},	
					dataType:'html',	
					success:function(html){
						$('#pop_HotelRecommend select[name=country_id]').html(html);
						vietiso_loading(0);
					}
				});
				var $keyword = $('#pop_HotelRecommend input[name=keypop]').val();
				var $number_per_page = $('#pop_HotelRecommend .paginate_length').val();
				var $star = $('#pop_HotelRecommend select[name=star]').val();
				var $tour_id = $('#pop_HotelRecommend #hid_tour_id').val();
				var $itinerary_id = $('#pop_HotelRecommend #hid_itinerary_id').val();
				reloadListHotel($domain_id, 0, 0, $star, $keyword, $tour_id, $itinerary_id, 1, $number_per_page);
			});
			$(document).on('change', '#pop_HotelRecommend select[name=country_id]', function(ev){
				var $_this = $(this);
				var $domain_id = $('#pop_HotelRecommend select[name=domain_id]').val();
				var $country_id = $_this.val();
				var $city_id = 0;
				var $star = $('#pop_HotelRecommend select[name=star]').val();
				var $keyword = $('#pop_HotelRecommend input[name=keypop]').val();
				var $number_per_page = $('#pop_HotelRecommend .paginate_length').val();
				var $tour_id = $('#pop_HotelRecommend #hid_tour_id').val();
				var $itinerary_id = $('#pop_HotelRecommend #hid_itinerary_id').val();
				
				if(parseInt($country_id)==0){
					$('#pop_HotelRecommend select[name=city_id]').html('<option>-- Lựa chọn --</option>');
					$('#pop_HotelRecommend select[name=star]').html('<option>-- Lựa chọn --</option>');
					return false;
				}
				/* Make combobox city */
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/?mod=city&act=makeSelectboxCity",
					data:{"country_id":$country_id},
					dataType: "html",
					success: function(html){
					  $('#pop_HotelRecommend select[name=city_id]').html(html);
					}
				});
				reloadListHotel($domain_id, $country_id, $city_id, $star, $keyword, $tour_id, $itinerary_id, 1, $number_per_page);
			});
			$(document).on('change', '#pop_HotelRecommend select[name=city_id]', function(ev){
				var $_this = $(this);
				var $domain_id = $('#pop_HotelRecommend select[name=domain_id]').val();
				var $country_id = $('#pop_HotelRecommend select[name=country_id]').val();
				var $keyword = $('#pop_HotelRecommend input[name=keypop]').val();
				var $number_per_page = $('#pop_HotelRecommend .paginate_length').val();
				var $star = $('#pop_HotelRecommend select[name=star]').val();
				var $tour_id = $('#pop_HotelRecommend #hid_tour_id').val();
				var $itinerary_id = $('#pop_HotelRecommend #hid_itinerary_id').val();
				
				reloadListHotel($domain_id, $country_id, $_this.val(), $star, $keyword, $tour_id, $itinerary_id, 1, $number_per_page);
			});
			$(document).on('change', '#pop_HotelRecommend select[name=star]', function(ev){
				var $_this = $(this);
				var $domain_id = $('#pop_HotelRecommend select[name=domain_id]').val();
				var $country_id = $('#pop_HotelRecommend select[name=country_id]').val();
				var $city_id = $('#pop_HotelRecommend select[name=city_id]').val();
				var $keyword = $('#pop_HotelRecommend input[name=keypop]').val();
				var $number_per_page = $('#pop_HotelRecommend .paginate_length').val();
				var $tour_id = $('#pop_HotelRecommend #hid_tour_id').val();
				var $itinerary_id = $('#pop_HotelRecommend #hid_itinerary_id').val();
				
				reloadListHotel($domain_id, $country_id, $city_id, $_this.val(), $keyword, $tour_id, $itinerary_id, 1, $number_per_page);
			});
			/* Ajax change keyword */
			$(document).on('click', '#pop_HotelRecommend .searchpop', function(ev){
				var $domain_id = $('#pop_HotelRecommend select[name=domain_id]').val();
				var $country_id = $('#pop_HotelRecommend select[name=country_id]').val();
				var $city_id = $('#pop_HotelRecommend select[name=city_id]').val();
				var $keyword = $('#pop_HotelRecommend input[name=keypop]').val();
				var $star = $('#pop_HotelRecommend select[name=star]').val();
				var $number_per_page = $('#pop_HotelRecommend .paginate_length').val();
				var $tour_id = $('#pop_HotelRecommend #hid_tour_id').val();
				var $itinerary_id = $('#pop_HotelRecommend #hid_itinerary_id').val();
				
				reloadListHotel($domain_id, $country_id, $city_id, $star, $keyword, $tour_id, $itinerary_id, 1, $number_per_page);
			});
			$(document).on('click', '#pop_HotelRecommend .paginate_button', function(ev){
				var $_this = $(this);
				var $domain_id = $('#pop_HotelRecommend select[name=domain_id]').val();
				var $country_id = $('#pop_HotelRecommend select[name=country_id]').val();
				var $city_id = $('#pop_HotelRecommend select[name=city_id]').val();
				var $star = $('#pop_HotelRecommend select[name=star]').val();
				var $keyword = $('#pop_HotelRecommend input[name=keypop]').val();
				var $tour_id = $('#pop_HotelRecommend #hid_tour_id').val();
				var $itinerary_id = $('#pop_HotelRecommend #hid_itinerary_id').val();
				var $number_per_page = $('#pop_HotelRecommend .paginate_length').val();
				
				reloadListHotel($domain_id,$country_id,$city_id,$star,$keyword,$tour_id,$itinerary_id,$_this.attr('page'),$number_per_page);
				return false;
			});
			$(document).on('click', '#pop_HotelRecommend select[class=paginate_length]', function(ev){
				var $_this = $(this);
				var $domain_id = $('#pop_HotelRecommend select[name=domain_id]').val();
				var $country_id = $('#pop_HotelRecommend select[name=country_id]').val();
				var $city_id = $('#pop_HotelRecommend select[name=city_id]').val();
				var $keyowrd = $('#pop_HotelRecommend input[name=keypop]').val();
				var $star = $('#pop_HotelRecommend select[name=star]').val();
				var $tour_id = $('#pop_HotelRecommend #hid_tour_id').val();
				var $itinerary_id = $('#pop_HotelRecommend #hid_itinerary_id').val();
				
				reloadListHotel($domain_id,$country_id,$city_id,$star,$keyword,$tour_id,$itinerary_id,1,$_this.val());
				return false;
			});
			$(document).on('click', '.btnChooiseHotel', function(ev){
				var $_this = $(this);
				var $tour_id = $_this.attr('tour_id');
				var $itinerary_id = $_this.attr('itinerary_id');
				var $list_id = $('#list_selected_chkitem').val();
				
				var adata = {};
				adata['tour_id'] = $tour_id;
				adata['itinerary_id'] = $itinerary_id;
				adata['list_id'] = $list_id;
				
				vietiso_loading(1);
				$.ajax({
					type:'POST',	
					url:path_ajax_script+"/index.php?mod=tour&act=ajaxSaveTourHotel",	
					data: adata,	
					dataType:'html',	
					success:function(html){
						if(html.indexOf('_EMPTY')>=0){
							alertify.error('You must choose hotel !');
						}
						if(html.indexOf('_SUCCESS')>=0){
							/*loadListHotelRecommend($tour_id,'');*/
							loadListHotelItinerary(tour_id,$itinerary_id,'');
							$('#pop_HotelRecommend .closeEv').trigger('click');
						}
					}
				});
				return false;
			});
			$('.btn_delete_hotel_itinerary').live('click',function(){
				var $_this = $(this);
				if(confirm(confirm_delete)){
					var $tour_id = $_this.attr('_tour_id');
					var $itinerary_id = $_this.attr('_itinerary_id');
					
					vietiso_loading(1);
					$.ajax({
						type: "POST",
						url:path_ajax_script+'/index.php?mod=tour&act=ajaxDeleteHotelItinerary',
						data: {'tour_hotel_id' : $_this.attr('data')},
						dataType: "html",
						success: function(html){
							vietiso_loading(0);
							loadListHotelItinerary(tour_id,$itinerary_id,'');
						}
					});
				}
				return false;
			});
			
			$('.btnDeleteTourHotel').live('click',function(){
				var $_this = $(this);
				if(confirm(confirm_delete)){
					var $tour_id = $_this.attr('_tour_id');
					var adata = {'tour_hotel_id' : $_this.attr('data')};
					vietiso_loading(1);
					$.ajax({
						type: "POST",
						url:path_ajax_script+'/index.php?mod=tour&act=ajDeleteTourHotel',
						data: adata,
						dataType: "html",
						success: function(html){
							loadListHotelRecommend($tour_id,'');
							vietiso_loading(0);
							alertify.error(delete_success);
						}
					});
				}
				return false;
			});
			$(".btnCreateNewCustomHotel").live("click",function(){
				var $_this = $(this);
				var adata = {
					'tour_id':$_this.attr('_tour_id'),
					'tour_start_date_id':$_this.attr('_tour_start_date_id'),
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod=tour&act=ajCreateNewCustomHotel',
					data: adata,
					dataType: "html",
					cache: true,
					success: function(html){
						makepopupnotresize('960px','470px',html,'box_customHotel');
						$('#box_customHotel').css('top','20px');
						$(".price").priceFormat({thousandsSeparator: '.',centsLimit: 0});
						$('#'+$('.hotel_intro_editor').attr('id')).isoTextArea();
						$('#'+$('.hotel_intro_editor').attr('id')+'_ifr').height(120);
						vietiso_loading(0);
					}
				});
				return false;
			});
			$(".btnEditHotel").live("click",function(){
				var $_this = $(this);
				var adata = {
					'hotel_id':$_this.attr('data'),
					'tour_id': $_this.attr('_tour_id'),
					'tour_start_date_id' : $_this.attr('_tour_start_date_id')
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod=tour&act=ajLoadEditCustomHotel',
					data: adata,
					dataType: "html",
					cache: true,
					success: function(html){
						makepopupnotresize('960px','470px',html,'box_customHotel');
						$('#box_customHotel').css('top','20px');
						$(".price").priceFormat({thousandsSeparator: '.',centsLimit: 0});
						$('#'+$('.hotel_intro_editor').attr('id')).isoTextArea();
						$('#'+$('.hotel_intro_editor').attr('id')+'_ifr').height(120);
						vietiso_loading(0);
					}
				});
				return false;
			});
			$('.clkSubmitHotel').live('click',function(){
				var $_this = $(this);
				/**/
				var $tour_id = $_this.attr('_tour_id');
				var $tour_start_date_id = $_this.attr('_tour_start_date_id');
				var $title = $('#box_customHotel input[name=title]');
				var $country_id = $('#box_customHotel select[name=country_id]');
				var $city_id = $('#box_customHotel select[name=city_id]');
				var $address = $('#box_customHotel input[name=address]');
				var $star = $('#box_customHotel select[name=star]');
				var $price = $('#box_customHotel input[name=price]');
				var $image = $('#box_customHotel #imgHotel_hidden');
				var $intro = tinyMCE.get($('.hotel_intro_editor').attr('id')).getContent();
				/* Check valid */
				if($title.val()==''){
					alertify.error(field_is_required);
					$title.focus();
					return false;
				}
				if($country_id.val()==''){
					alertify.error(field_is_required);
					$country_id.focus();
					return false;
				}
				if($city_id.val()==''){
					alertify.error(field_is_required);
					$city_id.focus();
					return false;
				}
				if($address.val()==''){
					alertify.error(field_is_required);
					$address.focus();
					return false;
				}
				if($star.val()==''){
					alertify.error(field_is_required);
					$address.focus();
					return false;
				}
				if($price.val()==''){
					alertify.error(field_is_required);
					$price.focus();
					return false;
				}
				var adata = {
					'title': $title.val(),
					'country_id': $country_id.val(),
					'city_id': $city_id.val(),
					'address': $address.val(),
					'phone': $('input[name=phone]').val(),
					'website': $('input[name=website]').val(),
					'star': $star.val(),
					'price': $price.val(),
					'intro': $intro,
					'image': $image.val(),
					'tour_id': $tour_id,
					'tour_start_date_id': $tour_start_date_id,
					'hotel_id': $_this.attr('hotel_id')
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod=tour&act=ajSubmitHotel',
					data: adata,
					dataType: "html",
					success: function(html){
						if(html.indexOf('_INSERT_SUCCESS')>=0){
							$('#box_SelectHotelRecommend .close_pop').trigger('click');
							$('#box_customHotel .close_pop').trigger('click');
							loadListHotelRecommend($tour_id,'');
						}
						if(html.indexOf('_UPDATE_SUCCESS')>=0){
							$('#box_customHotel .close_pop').trigger('click');
							var $country_id = $('#box_SelectHotelRecommend select[name=country_id]').val();
							var $city_id = $('#box_SelectHotelRecommend select[name=city_id]').val();
							var $star = $('#box_SelectHotelRecommend select[name=star]').val();
							var $keyword = $('#box_SelectHotelRecommend input[name=keypop]').val();
							var $number_per_page = $('#box_SelectHotelRecommend select[name=paginate_length]').val();
							var $page = $('#box_SelectHotelRecommend .paginate_current_page').val();
							reloadListHotel($country_id,$city_id,$star,$keyword,$page,$number_per_page);
							loadListHotelRecommend($tour_id,'');
						}
						if(html.indexOf('_EXIST')>=0){
							alertify.error(exist_error);
						}
						vietiso_loading(0);
					}
				});
				return false;
			});
			$('.btnDeleteHotel').live('click',function(){
				var $_this = $(this);
				if(confirm(confirm_delete)){
					var $tour_id = $_this.attr('_tour_id');
					var $tour_start_date_id = $_this.attr('_tour_start_date_id');
					var adata = {'hotel_id': $_this.attr('data')};
					vietiso_loading(1);
					$.ajax({
						type: "POST",
						url:path_ajax_script+'/index.php?mod=tour&act=ajdeleteOneHotel',
						data: adata,
						dataType: "html",
						success: function(html){
							var $country_id = $('#box_SelectHotelRecommend select[name=country_id]').val();
							var $city_id = $('#box_SelectHotelRecommend select[name=city_id]').val();
							var $star = $('#box_SelectHotelRecommend select[name=star]').val();
							var $keyword = $('#box_SelectHotelRecommend input[name=keypop]').val();
							var $number_per_page = $('#box_SelectHotelRecommend select[name=paginate_length]').val();
							var $page = $('#box_SelectHotelRecommend .paginate_current_page').val();
							reloadListHotel($country_id,$city_id,$star,$keyword,$page,$number_per_page);
							loadListHotelRecommend($tour_id,'');
						}
					});
				}
				return false;
			});
			/* @Tour Operator */
			$('#clickToAddOperator').click(function(){
				var $_this = $(this);
				var adata = {
					'tour_id' : $_this.attr('_tour_id'),
					'tour_start_date_id': $_this.attr('_tour_start_date_id')
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod=tour&act=ajGetSearchCompanyStaff',
					data: adata,
					dataType: "html",
					cache: true,
					success: function(html){
						makepopupnotresize('800px','380px',html,'box_ChooseTourOperator');
						vietiso_loading(0);
					}
				});
			});
			$('.clickaddOperator').live('click',function(){
				var $_this = $(this);
				var $tour_id = $_this.attr('_tour_id');
				var $tour_start_date_id = $_this.attr('_tour_start_date_id');
				var adata = {
					'company_staff_id' : $_this.attr('data'),
					'tour_id' : $tour_id,
					'tour_start_date_id': $tour_start_date_id
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod=tour&act=ajaddTourOperator',
					data: adata,
					dataType: "html",
					cache: true,
					success: function(html){
						loadListTourOperator($tour_id,$tour_start_date_id);
						/* @Load again list */
						var $keyword = $('#box_ChooseTourOperator input[name=keypop]').val();
						var $page = $('#box_ChooseTourOperator .paginate_current_page').val();
						var $number_per_page = $('#box_ChooseTourOperator .paginate_length').val();
						reloadListCompanyStaff($keyword,$page,$number_per_page);
						vietiso_loading(0);
					}
				});
			});
			$('.clickdelOperator').live('click',function(){
				var $_this = $(this);
				if(confirm(confirm_delete)){
					var $tour_id = $_this.attr('_tour_id');
					var $tour_start_date_id = $_this.attr('_tour_start_date_id');
					var adata = {
						'tour_operator_id' : $_this.attr('data')
					};
					vietiso_loading(1);
					$.ajax({
						type: "POST",
						url:path_ajax_script+'/index.php?mod=tour&act=ajdelTourOperator',
						data: adata,
						dataType: "html",
						cache: true,
						success: function(html){
							loadListTourOperator($tour_id,$tour_start_date_id);
							vietiso_loading(0);
						}
					});
				}
			});
			$('.moveTourOperator').live('click',function(){
				var $_this = $(this);
				var $tour_id = $_this.attr('_tour_id');
				var $tour_start_date_id = $_this.attr('_tour_start_date_id');
				var adata = {
					'tour_operator_id' : $_this.attr('data'),
					'direct' : $_this.attr('direct'),
					'tour_id' : $tour_id,
					'tour_start_date_id': $tour_start_date_id
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod=tour&act=ajmoveTourOperator',
					data: adata,
					dataType: "html",
					cache: true,
					success: function(html){
						loadListTourOperator($tour_id,$tour_start_date_id);
						vietiso_loading(0);
					}
				});
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
			// Tour liên quan
			$("#searchkey").bind('keyup change',function(){
				var _this = $(this);
				if(_this.val()!=''){
					clearTimeout(aj_search);	
					search_tour();
				}else{
					$("#quickSearch").html('');	
				}
			});
		}
		// Edit
		if(act=='edit'){
			loadTourExtension(tour_id);
			loadDestination(tour_id);
			countPhotosGallerty(tour_id,type);
			loadListCustomField();
			loadListTourByDays('','',1,2);
			$('.clickChooiseTour').live('click',function(){
				vietiso_loading(1);
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
						vietiso_loading(0);
						htm = html.replace(' ','');
						if(htm=='_SUCCESS'){
							_this.remove();
							loadTourExtension(tour_id);
						}
						if(htm=='_EXIST'){
							alertify.error('Tour này đã tồn tại !');
						}
					}
				});
			});
			$('.moveTourExtension').live('click',function(){
				var _this = $(this);
				vietiso_loading(1);
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
						vietiso_loading(0);
						loadTourExtension(tour_id);
					}
				});
			});
			$('.clickDeleteTourExtension').live('click',function(){
				var _this = $(this);
				vietiso_loading(1);
				var adata = {
					'tour_extension_id'	: _this.attr('data')
				};
				$.ajax({
					type:'POST',	
					url:path_ajax_script+'/index.php?mod=tour&act=ajDeleteTourExtension',	
					data: adata,	
					dataType:'html',	
					success:function(html){
						vietiso_loading(0);
						loadTourExtension(tour_id);
					}
				});
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
					makepopup('260','',html,'NewTourPriceRow');
				}
			});
			return false;
		});
		$('#addTourCruisePriceRow').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			$.ajax({
				type: 'POST',
				url:path_ajax_script+'/index.php?mod=tour&act=ajLoadNewTourCruisePriceRow',	 
				dataType:'html', 
				data: {
					'tour_id':tour_id
				},
				success: function(html){
					$('.dropdown-toggle').trigger('click');
					makepopup('260','',html,'NewTourCruisePriceRow');
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
				data: {'tour_id':tour_id	},
				success: function(html){
					$('.dropdown-toggle').trigger('click'); 
					makepopup('260','',html,'NewTourPriceCol');
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
		$('.clickToAddTourCruisePriceRow').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = 
			{
				'tour_id':tour_id,
				'title': $('#titleRow').val()
			};
			$.ajax({
				type: 'POST',
				url:path_ajax_script+'/index.php?mod=tour&act=ajAddTourCruisePriceRow',   
				data: adata,
				dataType:'html',
				success: function(html){
					$('#ajax-indicator').hide();
					loadTourCruisePrice();
					$('.closeEv').click();
				}
			});
			return false;
		});
		$('.editTourPriceCol').live('click',function(){
			var _this = $(this);
			var adata = {'tour_id': tour_id};
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
							data: {'id':_this.attr('data')},
							success: function(html){
								makepopup('260','',html,'EditTourPriceCol');
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
				data: {'id':_this.attr('data')},
				success: function(html){
					makepopup('300','',html,'EditTourPriceRow');
				}
			});
			return false;
		});
		$('.editTourCruisePriceRow').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			$.ajax({
				type: 'POST',
				url:path_ajax_script+'/index.php?mod=tour&act=ajLoadEditTourCruisePriceRow', 
				dataType:'html', 
				data: {'id':_this.attr('data')},
				success: function(html){
					makepopup('300','',html,'EditTourCruisePriceRow');
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
		$('#clickToEditTourCruisePriceRow').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = 
			{
				'id':_this.attr('data'),
				'title': $('#titleRow').val()
			};
			$.ajax({
				type: 'POST',
				url:path_ajax_script+'/index.php?mod=tour&act=ajUpdateTourCruisePriceRow', 
				data: adata,
				dataType:'html',
				success: function(html){
					$('#ajax-indicator').hide();
					loadTourCruisePrice();
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
		$('.deleteTourCruisePriceRow').live('click',function(){
			if(confirm('Bạn có chắc chắn muốn xóa dòng này ?')){
				var _this = $(this);
				$('#ajax-indicator').show();
				var adata = 
				{
					'id':_this.attr('data')
				};
				$.ajax({
					type: 'POST',
					url:path_ajax_script+'/index.php?mod=tour&act=ajDeleteTourCruisePriceRow', 
					data: adata,
					dataType:'html',
					success: function(html){
						$('#ajax-indicator').hide();
						loadTourCruisePrice();
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
			var adata = {'tour_id': tour_id};
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
							data: {'tour_price_col_id':_this.attr('tour_price_col_id'),'tour_price_row_id':_this.attr('tour_price_row_id')},
							success: function(html){
								$('#ajax-indicator').hide(); 
								makepopup('200','',html,'EditTourPriceVal');
								$("#titleVal").priceFormat({thousandsSeparator: '.',centsLimit: 0});
							}
						});
					}
				}
			});
			return false;
		});
		$('.editTourCruisePriceVal').live('click',function(){
			var _this = $(this);
			var adata = {'tour_id': tour_id};
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=tour&act=ajCheckTourCruisePriceCol', 
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
							url:path_ajax_script+'/index.php?mod=tour&act=ajLoadEditTourCruisePriceVal',  
							dataType:'html', 
							data: {'tour_cruise_price_col_id':_this.attr('tour_cruise_price_col_id'),'tour_cruise_price_row_id':_this.attr('tour_cruise_price_row_id')},
							success: function(html){
								$('#ajax-indicator').hide(); 
								makepopup('200','',html,'EditTourCruisePriceVal');
								$("#titleVal").priceFormat({thousandsSeparator: '.',centsLimit: 0});
							}
						});
					}
				}
			});
			return false;
		});
		$(".ajCopyPriceCustomer").live("click",function(){
			var $_this = $(this);
			$("#titleVal").val($("#trip_price").val());
			return false;
		});					
		$('#clickToEditTourPriceVal').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = {
				'tour_id':tour_id,
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
		$('#clickToEditTourCruisePriceVal').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = {
				'tour_id':tour_id,
				'tour_cruise_price_col_id':_this.attr('tour_cruise_price_col_id'),
				'tour_cruise_price_row_id':_this.attr('tour_cruise_price_row_id'),
				'price': $('#titleVal').val()
			};
			$.ajax({
				type: 'POST',
				url:path_ajax_script+'/index.php?mod=tour&act=ajUpdateTourCruisePriceVal',   
				data: adata,
				dataType:'html',
				success: function(html){
					$('#ajax-indicator').hide();
					loadTourCruisePrice();
					$('.closeEv').click();
				}
			});
			return false;
		});
		$('.moveTourPriceRow').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = {
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
		$('.moveTourCruisePriceRow').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = {
				'id':_this.attr('data'),
				'direct': _this.attr('direct')
			};
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=tour&act=ajMoveTourCruisePriceRow', 
				data: adata,
				dataType:'html',
				success: function(html){
					$('#ajax-indicator').hide();
					loadTourCruisePrice();
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
	}
	$(document).on('click', '.btn_edit_tbl_cruise_header', function(ev){
		var $_this = $(this);
		var $tour_id = $_this.attr('tour_id');
		var $toField = $_this.attr('toField');
		var adata = {
			'tour_id'	: $tour_id,
			'toField'	: $toField
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=tour&act=ajaxFrmTableCruiseHeader",
			data: adata,
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				makepopup('400px','auto',html,'frmEditItinerary');
			}
		});	
	});
	$(document).on('click', '#clickSaveTableCruiseHeader', function(ev){
		var $_this = $(this);
		var $_form = $_this.closest('.frmPop');
		var $tour_id = $_this.attr('tour_id');
		var $toField = $_this.attr('tofield');
		var $table_cruise_header = $_form.find('input[name=table_cruise_header]');
		
		if($table_cruise_header.val()==''){
			$table_cruise_header.focus();
			alertify.error(field_is_required);
			return false;
		}
		var adata = {
			"clsTable" : 'Tour',
			"pkey" : 'tour_id',
			"pvalTable" : $tour_id,
			"toField" : $toField,
			"val" : $table_cruise_header.val(),
			"allowDuplicate":'1'
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=ajax&act=saveField",
			data: adata,
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				$_form.find('.close_pop').trigger('click');
				loadTourCruisePrice();
			}
		});	
	});
});
// End Ready here !!
function loadListHotelRecommend($tour_id,$keyword){
	var adata = {
		'tour_id': $tour_id,
		'keyword' : $keyword
	};
	vietiso_loading(1);
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=tour&act=ajListHotelRecommend",
		data: adata,
		dataType: "html",
		success: function(html){
			$('#tblHotelRecommendGlobal').html(html);
			vietiso_loading(0);
		}
	});
}
function reloadListHotel($domain_id, $country_id, $city_id, $star, $keyword, $tour_id, $itinerary_id, $page, $number_per_page){
	var adata = {};
	adata['domain_id'] = $domain_id;
	adata['country_id'] = $country_id;
	adata['city_id'] = $city_id;
	adata['keyword'] = $keyword;
	adata['star'] = $star;
	adata['tour_id'] = $tour_id;
	adata['itinerary_id'] = $itinerary_id;
	adata['page'] = $page;
	adata['number_per_page'] = $number_per_page;
	
	vietiso_loading(1);
	$.ajax({
		type:'POST',	
		url:path_ajax_script+"/index.php?mod=tour&act=ajaxLoadListHotel",	
		data:adata,	
		dataType:'html',	
		success:function(html){
			var htm = html.split('$$');
			$('#pop_HotelRecommend #tblHolderHotel').html(htm[0]);
			$('#pop_HotelRecommend #dataTable_paginate').html(htm[1]);
			vietiso_loading(0);
		}
	});
}
function loadListTourOperator($tour_id,$tour_start_date_id){
	var adata = {
		'tour_id' : $tour_id,
		'tour_start_date_id' : $tour_start_date_id
	};
	vietiso_loading(1);
	$.ajax({
		type:'POST',	
		url:path_ajax_script+"/index.php?mod=tour&act=ajLoadTourOperator",	
		data:adata,	
		dataType:'html',	
		success:function(html){
			$('#tblTourOperator').html(html);
			vietiso_loading(0);
		}
	});
}
function reloadListCompanyStaff($keyword, $page, $number_per_page){
	var $tour_id = $('#box_ChooseTourOperator #hid_tour_id').val();
	var $tour_start_date_id = $('#box_ChooseTourOperator  #hid_tour_start_date_id').val();
	var adata = {
		'tour_id' : $tour_id,
		'tour_start_date_id' : $tour_start_date_id,
		'keyword' : $keyword,
		'page' : $page,
		'number_per_page' : $number_per_page
	};
	vietiso_loading(1);
	$.ajax({
		type:'POST',	
		url:path_ajax_script+"/index.php?mod=tour&act=ajLoadListCompanyStaff",	
		data:adata,	
		dataType:'html',	
		success:function(html){
			var htm = html.split('$$');
			$('#box_ChooseTourOperator #tblHolderCompanyStaff').html(htm[0]);
			$('#box_ChooseTourOperator #dataTable_paginate_pop').html(htm[1]);
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
			if(html.replace(' ','')==''){
				$("#tab5Note").removeClass("iso-check").addClass("iso-check-disabled");
			}
			else{
				$('#tblTourExtension').html(html);
				$("#tab5Note").addClass("iso-check").removeClass("iso-check-disabled");
			}
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
			if(html.replace(" ","")==""){
				$("#holderCopyItinerary").hide();
				$("#tab3Note").addClass("iso-check-disabled").removeClass("iso-check");
			}
			else{
				$("#holderCopyItinerary").show();
				$("#tab3Note").removeClass("iso-check-disabled").addClass("iso-check");
			}
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
function loadTourCruisePrice(){
	var adata = {'tour_id':tour_id};
	$('#ajax-indicator').show();
	$.ajax({
		type: 'POST',
		url:path_ajax_script+'/index.php?mod=tour&act=ajLoadTourCruisePrice',
		data: adata,
		dataType:'html',
		success: function(html){
			$('#ajax-indicator').hide();
			$('#tblTourCruisePrice').html(html);
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
function loadListHotelItinerary($tour_id,$itinerary_id,$keyword){
	var adata = {
		'tour_id': $tour_id,
		'itinerary_id': $itinerary_id,
		'keyword' : $keyword
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=tour&act=ajaxLoadHotelItinerary",
		data: adata,
		dataType: "html",
		success: function(html){
			$('#lstHotel').html(html);
			vietiso_loading(0);
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
function loadTourPrice(){
	$('#ajax-indicator').show();
	var adata = {
		'tour_id':tour_id
	};
	$.ajax({
		type: 'POST',
		url:path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadTourPrice',
		data: adata,
		dataType:'html',
		success: function(html){
			$('#ajax-indicator').hide();
			$('#tblTourPrice').html(html);
		}
	});
}