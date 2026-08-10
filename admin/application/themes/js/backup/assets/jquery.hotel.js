$().ready(function(){
	loadCity();
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
	if(mod == 'hotel' && act == 'default') {
		$('#forums select').change(function(){
			$('#forums').submit();
		});
		$('select[name=iso-country_id]').change(function(){
			var _this=$(this);
			$('select[name=iso-city_id]').html('<option value="">Loading...</option>');
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/?mod=hotel&act=loadCity",
				data: {"country_id"	: _this.val()},
				dataType: "html",
				success: function(html){
				  $('select[name=iso-city_id]').html(html);
				}
			});
		});
	}
	if(mod == 'hotel' && act == 'edit') {
		loadHotelFacibility(pvalTable);
		initGalleryGlobal(pvalTable,type,'HotelGalleryHolder');
		setCounter($("#introHotel"),400,$("#introCounter"));
		loadHotelRoom();
		loadHotelPrice();
		/* FUNC */
		$('#addHotelPriceRow').live('click',function(){
			var _this = $(this);
			vietiso_loading(1);
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajLoadNewHotelPriceRow', 
				dataType:'html', 
				data: {'hotel_id':pvalTable},
				success: function(html){
					vietiso_loading(0);
					makepopup('300','',html,'NewHotelPriceRow');
				}
			});
			return false;
		});
		$('#addHotelPriceCol').live('click',function(){
			var _this = $(this);
			vietiso_loading(1);
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajLoadNewHotelPriceCol', 
				dataType:'html', 
				data: {'hotel_id':pvalTable},
				success: function(html){
					vietiso_loading(0);
					makepopup('300','',html,'NewHotelPriceCol');
				}
			});
			return false;
		});
		$('.editHotelPriceRoom').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajLoadEditHotelPriceRoom', 
				dataType:'html', 
				data: {'id':_this.attr('data')},
				success: function(html){
					makepopup('300','',html,'EditHotelPriceRow');
				}
			});
			return false;
		});
		$('.editHotelPriceCol').live('click',function(){
			var _this = $(this);
			var adata = {'hotel_id': pvalTable};
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajCheckHotelPriceCol', 
				data: adata,
				dataType:'html',
				success: function(html){
					if(html==0){
						$('#addHotelPriceCol').trigger('click');
					}else{
						$('#ajax-indicator').show();
						$.ajax({
							type: 'POST',
							url: path_ajax_script+'/index.php?mod=hotel&act=ajLoadEditHotelPriceCol', 
							dataType:'html', 
							data:{'id':_this.attr('data')},
							success: function(html){
								makepopup('300','',html,'EditHotelPriceCol');
							}
						});
					}
				}
			});
			return false;
		});
		$('.editHotelPriceVal').live('click',function(){
			var _this = $(this);
			var adata = {'hotel_id': pvalTable};
			vietiso_loading(1);
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajCheckHotelPriceCol', 
				data: adata,
				dataType:'html',
				success: function(html){
					vietiso_loading(0);
					if(html==0){
						alertify.error('Lỗi. Bạn chưa nhập tiêu đề cột !');
						$('#addHotelPriceCol').trigger('click');
					}else{
						$.ajax({
							type: 'POST',
							url: path_ajax_script+'/index.php?mod=hotel&act=ajLoadEditHotelPriceVal', 
							dataType:'html', 
							data: {
								'hotel_price_col_id':_this.attr('hotel_price_col_id'),
								'hotel_price_row_id':_this.attr('hotel_price_row_id')
							},
							success: function(html){
								makepopup('200','',html,'EditHotelPriceVal');
								$("#titleVal").priceFormat({thousandsSeparator: '.',centsLimit: 0});
							}
						});
					}
				}
			});
			return false;
		});
		$('.ajCopyPriceHotel').live('click', function(){
			$('#titleVal').val($('#price').val());
			return false;
		});
		$('select[name=iso-city_id]').change(function(){
			var _this = $(this);
			var country = $('select[name=iso-country_id]').find('option:selected').attr('title');
			var city = _this.find('option:selected').attr('title');
			var address = city+' ,'+country;
			showAddress(address); 
		});
		$('select[name=iso-country_id]').change(function(){
			var _this=$(this);
			var title = _this.find('option:selected').attr('title');
			$('select[name=iso-city_id]').html('<option value="">Loading...</option>');
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/?mod=hotel&act=loadCity",
				data: {"country_id"	: _this.val()},
				dataType: "html",
				success: function(html){
					showAddress(title); 
					$('select[name=iso-city_id]').html(html);
				}
			});
		});
		$('input[class=ajvClk]').live('change',function(){
			var $_this = $(this);
			var adata = {
				'tp' : $_this.attr('tp'),
				'tp_order' : $_this.attr('tp_order'),
				'hotel_id': $_this.attr('data'),
				'val' : $_this.is(':checked')?1:0
			};
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajUpdateHotelVr3',
				data:adata,
				dataType:'html',
				success: function(html){
				}
			});
		});
		$('#clickToAddHotelPriceRow').live('click',function(){
			var _this = $(this);
			if($('#titleRow').val()==''){
				$('#titleRow').focus().addClass('error');
				alertify.error('Bạn chưa nhập tên phòng');
				return false;
			}
			$('#ajax-indicator').show();
			var adata = {
				'hotel_id' : _this.attr('hotel_id'),
				'title': $('#titleRow').val()
			};
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajAddHotelPriceRow', 
				data: adata,
				dataType:'html',
				success: function(html){
					var htm = html.replace(' ','');
					if(htm=='_EXIST'){
						alertify.error('Tên phòng này đã tồn tại !');
					}
					if(htm=='_ERROR'){
						alertify.error('Đã xảy ra lỗi Hệ Thống. Làm ơn thử lại sau !');
					}
					if(htm=='_SUCCESS'){
						loadHotelPrice();
						$('#clickToCloseNewHotelPriceRow').click();
						$('#ajax-indicator').hide();
						alertify.success('Thêm phòng thành công !');
					}
				}
			});
			return false;
		});
		$('#clickToAddHotelPriceCol').live('click',function(){
			var _this = $(this);
			if($('#titleCol').val()==''){
				$('#titleCol').focus().addClass('error');
				alertify.error('Bạn chưa nhập tiêu đề');
				return false;
			}
			var adata = {
				'hotel_id':_this.attr('hotel_id'),
				'title': $('#titleCol').val()
			};
			$('#ajax-indicator').show();
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajAddHotelPriceCol', 
				data: adata,
				dataType:'html',
				success: function(html){
					loadHotelPrice();
					$('#clickToCloseNewHotelPriceCol').click();
					$('#ajax-indicator').hide();
				}
			});
			return false;
		});
		$('#clickToEditHotelPriceRow').live('click',function(){
			var _this = $(this);
			var adata = {
				'id':_this.attr('data'),
				'title': $('#titleRow').val()
			};
			$('#ajax-indicator').show();
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajUpdateHotelPriceRow', 
				data: adata,
				dataType:'html',
				success: function(html){
					loadHotelPrice();
					$('#clickToCloseEditHotelRoom').click();
					$('#ajax-indicator').hide();
				}
			});
			return false;
		});
		$('#clickToEditHotelPriceCol').live('click',function(){
			var _this = $(this);
			if($('#titleCol').val()==''){
				$('#titleCol').focus().addClass('error');
				alertify.error('Bạn chưa nhập tiêu đề');
				return false;
			}
			var adata = {
				'id':_this.attr('data'),
				'title': $('#titleCol').val()
			};
			$('#ajax-indicator').show();
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajUpdateHotelPriceCol', 
				data: adata,
				dataType:'html',
				success: function(html){
					loadHotelPrice();
					$('#clickToCloseEditHotelPriceCol').click();
					$('#ajax-indicator').hide();
				}
			});
			return false;
		});
		$('.deleteHotelPriceRoom').live('click',function(){
			if(confirm('Bạn có chắc chắn muốn xóa dòng này ?')){
				var _this = $(this);
				$('#ajax-indicator').show();
				var adata = {
					'id':_this.attr('data')
				};
				$.ajax({
					type: 'POST',
					url: path_ajax_script+'/index.php?mod=hotel&act=ajDeleteHotelPriceRoom', 
					data: adata,
					dataType:'html',
					success: function(html){
						loadHotelPrice();
						$('#ajax-indicator').hide();
					}
				});
			}
			return false;
		});
		$('.deleteHotelPriceCol').live('click',function(){
			if(confirm('Bạn có chắc chắn muốn xóa cột này ?')){
				var _this = $(this);
				$('#ajax-indicator').show();
				var adata = {
					'id':_this.attr('data')
				};
				$.ajax({
					type: 'POST',
					url: path_ajax_script+'/index.php?mod=hotel&act=ajDeleteHotelPriceCol', 
					data: adata,
					dataType:'html',
					success: function(html){
						loadHotelPrice();
						$('#ajax-indicator').hide();
					}
				});
			}
			return false;
		});
		$('#clickToEditHotelPriceVal').live('click',function(){
			var _this = $(this);
			var adata = {
				'hotel_id':pvalTable,
				'hotel_price_col_id':_this.attr('hotel_price_col_id'),
				'hotel_price_row_id':_this.attr('hotel_price_row_id'),
				'price': $('#titleVal').val()
			};
			$('#ajax-indicator').show();
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajUpdateHotelPriceVal', 
				data: adata,
				dataType:'html',
				success: function(html){
					loadHotelPrice();
					$('#clickToCloseUpdatePriceRoom').click();
					$('#ajax-indicator').hide();
				}
			});
			return false;
		});
		$('.moveHotelPriceRoom').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = {
				'id':_this.attr('data'),
				'direct': _this.attr('direct')
			};
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajMoveHotelPriceRow', 
				data: adata,
				dataType:'html',
				success: function(html){
					loadHotelPrice();
					$('#ajax-indicator').hide();
				}
			});
			return false;
		});
		$('.moveHotelPriceCol').live('click',function(){
			var _this = $(this);
			$('#ajax-indicator').show();
			var adata = {
				'id':_this.attr('data'),
				'direct': _this.attr('direct')
			};
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajMoveHotelPriceCol', 
				data: adata,
				dataType:'html',
				success: function(html){
					$('#ajax-indicator').hide();
					loadHotelPrice();
				}
			});
			return false;
		});
		/* Hotel Room */
		$('#clickToAddHotelRoom').live('click',function(){
			var $_this = $(this);
			var adata = {'hotel_id' : $_this.attr('hotel_id')};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=hotel&act=ajaxFrmHotelRoom",
				data: adata,
				dataType: "html",
				success: function(html){
					makepopupnotresize('60%','auto',html,'pop_FrmHotelRoom');
					$('#pop_FrmHotelRoom').css('top',60+'px');
					var editor_id = $('.textarea_content_editor').attr('id');
					$('#'+editor_id).isoTextAreaFix();
					$('#'+editor_id+'_ifr').height(120);
					$(".selectbox").chosen({
						max_selected_options: 100,
						width:'100%'
					});
					/*$("#priceInput").priceFormat({
						thousandsSeparator: '.',
						centsLimit: 0
					});*/
				}
			});
		});
		$('.clickEditHotelRoom').live('click',function(){
			var _this = $(this);
			var adata = {
				'hotel_room_id' : _this.attr('data'),
				'hotel_id'		: _this.attr('hotel_id')
			};
			vietiso_loading(1);
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=hotel&act=ajaxFrmHotelRoom', 
				data: adata,
				dataType:'html',
				success: function(html){
					vietiso_loading(0);
					makepopupnotresize('60%','auto',html,'pop_FrmHotelRoom');
					$('#pop_FrmHotelRoom').css('top',60+'px');
					var editor_id = $('.textarea_content_editor').attr('id');
					$('#'+editor_id).isoTextAreaFix();
					$('#'+editor_id+'_ifr').height(120);
					$(".selectbox").chosen({
						max_selected_options: 100,
						width:'100%'
					});
					/*$("#priceInput").priceFormat({
						thousandsSeparator: '.',
						centsLimit: 0
					});*/
				}
			});
		});
		$('.ajaxManagerProperty').live('click',function(){
			var $_this = $(this);
			var adata = {
				'type' : $_this.attr('_type'),
				'fromid' : $_this.attr('fromid'),
				'forid' : $_this.attr('forid')
			};
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=property&act=ajGetBoxManagerProperty",
				data: adata,
				dataType: "html",
				success: function(html){
					makepopup('30%','',html,'frmBoxManagerProperty');
				}
			});
			return false;
		});
		$(document).on('click', '#clickSubmitHotelRoom', function(ev){
			var $_this = $(this);
			if(!$_this.hasClass('disabled')){
				if($('input[name=hotel_room_title]').val()==''){
					$('input[name=hotel_room_title]').addClass('error').focus();
					alertify.error('Bạn chưa nhập tiêu đề !');
					return false;
				}
				var $editor_id = $('.textarea_content_editor').attr('id');
				var $content = tinyMCE.get($editor_id).getContent();
				var adata = {
					'hotel_room_id' : $_this.attr('hotel_room_id'),
					'hotel_id'		: $_this.attr('hotel_id'),
					'intro'			: $content
				};
				vietiso_loading(1);
				$_this.addClass('disabled');
				$('#frmHotelRoom').ajaxSubmit({
					type: "POST",
					url: path_ajax_script+"/index.php?mod=hotel&act=ajaxSubmitHotelRoom",
					data: adata,
					dataType: "html",
					success: function(html){
						vietiso_loading(0);
						$_this.removeClass('disabled');
						if(html.indexOf('_IN_SUCCESS')>=0){
							loadHotelRoom();
							$_this.closest('.frmPop').find('.close_pop').trigger('click');
							alertify.success('Thêm mới thành công');
						}
						if(html.indexOf('_UP_SUCCESS')>=0){
							loadHotelRoom();
							$_this.closest('.frmPop').find('.close_pop').trigger('click');
							alertify.success('Cập nhật mới thành công');
						}
						if(html.indexOf('_ERROR')>=0){
							alertify.error('Lỗi !');
						}
						if(html.indexOf('_EXIST')>=0){
							alertify.error('Đã tồn tại');
						}
					}
				});
			}
			return false;
		});
		$('.clickDeleteHotelRoom').live('click',function(){
			var _this = $(this);
			if(confirm('Bạn muốn xóa phòng khách sạn này ?')){
				var adata = {
					'hotel_room_id' : _this.attr('data')
				};
				$.ajax({
					type: 'POST',
					url: path_ajax_script+'/index.php?mod=hotel&act=ajDeleteHotelRoom', 
					data: adata,
					dataType:'html',
					success: function(html){
						loadHotelRoom();
					}
				});
			}
			return false;
		});	
	}
	$('.frmPop .clickToClose').live('click',function(){
		var idtmp =$(this).closest('.frmPop');
		$('#isoblanketpop_'+idtmp.attr('id')).remove();
		idtmp.remove();	
	});
});
function loadHotelFacibility($forid){
	var $_container = $('#fT');
	$_container.html('<div>Loading...</div>');
	$.ajax({
		type: "POST",
		url: path_ajax_script+'/?mod=hotel&act=ajaxLoadHotelFacibility',
		data: {"hotel_id"	: $forid},
		dataType: "html",
		success: function(html){
			vietiso_loading(0);
		 	$_container.html(html);
		}
	});
}
function getLocationZ(){
	var country = $('select[name=iso-country_id]').find('option:selected').attr('title');
	var city =    $('select[name=iso-city_id]').val();
	var address = '';
	address += city!='' ? city:'';
	address += country!='' ? (city!=''? ' ,'+country: country) : '';
	showAddress(address);
}
function loadCity(){
	var country_id=$('select[name=iso-country_id]').val();
	$('select[name=iso-city_id]').html('<option value="">Loading...</option>');
	$.ajax({
		type: "POST",
		url: path_ajax_script+'/?mod=hotel&act=loadCity',
		data: {"country_id"	: country_id, 'city_id':city_id},
		dataType: "html",
		success: function(html){
		  $('select[name=iso-city_id]').html(html);
		}
	});
}
function checkHotelRoomAvailable(){
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=hotel&act=ajCheckHotelRoomAvailable",
		data: {"hotel_id":pvalTable},
		dataType: "html",
		success: function(html){
			vietiso_loading(0);
			if(parseInt(html) > 0){
				$('#addHotelPriceCol').show();
			}else{
				$('#addHotelPriceCol').hide();
			}
		}
	});
}
function loadHotelPrice(){
	var adata = {'hotel_id':pvalTable};
	$.ajax({
		type: 'POST',
		url: path_ajax_script+'/index.php?mod=hotel&act=ajaxLoadHotelPrice', 
		data: adata,
		dataType:'html',
		success: function(html){
			vietiso_loading(0);
			$('#hotelPriceTable').html(html);
			checkHotelRoomAvailable();
		}
	});
}
function loadHotelRoom(){
	var adata = {'hotel_id':pvalTable};
	$.ajax({
		type: 'POST',
		url: path_ajax_script+'/index.php?mod=hotel&act=ajaxLoadHotelRoom', 
		data: adata,
		dataType:'html',
		success: function(html){
			$('#hotelRoomTable').html(html);
		}
	});
}