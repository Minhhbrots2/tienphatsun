$(function(){
	$(window).scroll(function(){
		if(isScrolledIntoView('#showmorethisresult') && $Core.sop.scrolled == 0){
			$Core.sop.scrolled = 1;
			$('.showmorethisresult').removeClass('d-none').trigger('click');
		}
	});
	if($('.sop__checkbox-radio').length){
		$('.sop__checkbox-radio').each((_i, _elem) => {
			var total_checked = 0,
				ref_id = $(_elem).attr('ref_id');
			if($('input[type=radio][ref_id='+ref_id+']:checked').length){
				$('#'+ref_id).removeClass('d-none');
			}
		});
	}
});
$Core.sop = {
	scrolled : !1,
	getLink: function(sop_id, stock_code){
		return ('/cn/%c-%s.html')
			.replace('%c', stock_code)
			.replace('%s', sop_id);	
	},
	set_view: function(_this, e){
		var view = $('input[name=view]:checked').val();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=set_view', {'view' : view }, function(respJson){
			$Core.util.toggleIndicatior(0);
			window.location.reload(true);
		});
	},
	set_device: function(_this, e){
		var gId = $(_this).attr('gId'),
			having_ns = $('input[name=having_ns]:checked').val();
		if(having_ns=='yes'){
			$('.'+gId).removeClass('d-none');
		} else {
			$('.'+gId).addClass('d-none');
		}
	},
	load_more: function(_this, e){
		e.preventDefault();
		var page = $(_this).attr('page');
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.sop.list_sop({'page':page}, "more");
		}
		return false;
	},
	get_url_search: function(params){
		var url_request = "",
			url = "/cn/";;
		if(type_list == "manager"){
			url = "/cn/manager/";
		}else if(type_list == "me"){
			url = "/cn/me/";
		}
		if(params.block_id != undefined){
			url_request += ("&block="+params.block_id);
		}
		if(params.price_min != "" && params.price_max != ""){
			min = params.price_min;
			min = min.replace(/\./g, '');
			max = params.price_max;
			max = max.replace(/\./g, '');
			if(parseInt(max) > 0){
				url_request += ("&price_min="+min);
				url_request += ("&price_max="+max);
			}
		}
		if(params.bedroom_ids.length > 0){
			url_request += ("&bedroom="+params.bedroom_ids.toString());
		}
		if(params.home_direction_ids.length > 0){
			url_request += ("&direction="+params.home_direction_ids.toString());
		}
		if(params.floor_range.length > 0){
			url_request += ("&floor_range="+params.floor_range.toString());
		}
		if(!$Core.util.isEmpty(params.user_id)){
			url_request+= ('&user='+params.user_id); 
		}
		if(url_request!= ""){			
			if(params.sort_by !== undefined){
				url_request += ("&sort_by="+params.sort_by);
			}
			url += "?s=search"+url_request;
		}
		return url;
	},
	list_sop: function(options, action="append"){
		var $_adata = options || {},
			www = $(window).outerWidth(false),
			block_id = $('.rdo_block:checked').val(),
			status_ids = $Core.util.getCheckBoxValueByClass('chk_status'),
			bedroom_ids = $Core.util.getCheckBoxValueByClass('chk_bedroom'),
			home_direction_ids = $Core.util.getCheckBoxValueByClass('chk_home_direction'),
			floor_range = $Core.util.getCheckBoxValueByClass('chk_floor_range'),
			sort_by = $('.zHXOaDjwDk.active').attr('holderG');
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$_adata['www'] = www;
		$_adata['sort_by'] = sort_by;
		$_adata['type_list'] = type_list;
		$_adata['block_id'] = block_id;
		$_adata['status_ids'] = status_ids;
		$_adata['bedroom_ids'] = bedroom_ids;
		$_adata['floor_range'] = floor_range;
		$_adata['home_direction_ids'] = home_direction_ids;
		$Core.util.toggleIndicatior(1);
		var url = $Core.sop.get_url_search($_adata);
        $.post(PCMS_URL+'/cn/list.cfg', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.showmorethisresult')
				.addClass('d-none')
				.removeClass('clicked');
			$Core.util.popstate(url);
			if(respJson.html.indexOf('empty') >=0) {
				$Core.sop.scrolled = 1;
				if(action=='append'){
					$('.holder_sop').html(respJson.html);
					$('.total-stock').text(respJson.total_record);
				}
			}else{
				if(parseInt(respJson.total_record) <= (parseInt(respJson.per_page) * parseInt(respJson.current_page))){
					$Core.sop.scrolled = 1;
				} else {
					$Core.sop.scrolled = 0;
				}
				if($('.holder_sop .awe__sop-item').length && action=="more"){
					$('.holder_sop .awe__sop-item:last').after(respJson.html);
				} else {
					$('.holder_sop').html(respJson.html);
					$('.total-stock').text(respJson.total_record);
				}
				$Core.sop.init_slide();
				$('.showmorethisresult').attr('page', parseInt(respJson.current_page)+1);
			}
        }, 'json');
	},
	load_hot_sop: function(){
		$.post(PCMS_URL+'/cn/list.cfg', $_adata, function(respJson){
			$('a').html(respJson.html);
		}, 'json');
	},
	add_filter: function(_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			modal = $(_this).closest('.modal');
		if(tp=='user'){
			var user_id = $(_this).attr('user_id'),
				full_name = $(_this).attr('full_name');
			$('.sop__filter-cnd').html('<span class="sop__search-select-button sliwENIGcR d-flex align-items-center border radius-4 mr-2">'+full_name+'<a class="delete bx bx-x" onClick="$Core.sop.delete_filter(this, event)" title="Xoá điều kiện"></a><input type="hidden" class="search_field" data-field="user_id" value="'+user_id+'" /></span>');
			$Core.util.popstate(current_page);
			$Core.popup.close($(_this).closest('.modal'));
		}
		$Core.sop.reload({});
		return false;
	},
	delete_filter: function(_this, e){
		e.preventDefault();
		$(_this).parent().remove();
		$Core.sop.reload({});
		return false;
	},
	reload : function(options){
		var params = options || {};
		$('.showmorethisresult').attr('page', 2);
		clearTimeout(_timeOut);
		_timeOut = setTimeout(() => {
			$Core.sop.list_sop(params);
		}, 500);
	},
	handleLike : function(_this, e){
		var sop_id = $(_this).attr("sop_id"),
			holderG = $(_this).getAttr("holderG", 'list'),
			type = $(_this).hasClass('liked') ? 'unlike' : 'like';
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+"/cn/like.cfg", {
			type : type,
			sop_id:sop_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.msg.indexOf('_success') >= 0){
				$(_this).toggleClass("liked").attr("data-bs-original-title",respJson.title).tooltip("hide");
			}
		}, 'json');
	},
	init_slide: function(){
		if($('.slick-slider:not(.slick-initialized)').length){
			$('.slick-slider:not(.slick-initialized)').each((_i, _elem) => {
				var sliderId = $(_elem).attr('id');
				$('#'+sliderId).slick({
					infinite: true,
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: true,
					lazyLoad:'progressive',
					prevArrow:'<button class="btn slick-prev btn-icon"><i class="fa fa-angle-left"></i></button>',
					nextArrow:'<button class="btn slick-next btn-icon"><i class="fa fa-angle-right"></i></button>'
				});
			});
		}
	},
	do_sort: function(_this, e){
		e.preventDefault();
		var html = $(_this).html(),
			toId = $(_this).attr('toId');
		$('.'+toId).removeClass('active');
		$(_this).addClass('active');
		$('#'+toId).html(html);
		$Core.sop.list_sop({});
		return false;
	},
	do_search: function(_this, e){
		e.preventDefault();
		var $_adata = {},
			tp = $(_this).getAttr('tp', "");
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		if(tp == 'dropdown'){
			var _gId = $(_this).attr('gId'),
				_field = $(_this).attr('field'),
				_group = $(_this).closest('.btn-group');
			if($('.chk_'+_field).length){
				var _total_checked = 0,
					_text = $('span[id='+_gId+']').attr('text');
				$('.chk_'+_field).each((_i, _elem) => {
					if($(_elem).is(':checked')){
						_total_checked++;
					}
				});
				if(_total_checked > 0){
					$('span[id='+_gId+']').text(_text+'('+_total_checked+')'); 
					$('.dropdown-toggle', _group).addClass('is-active');
				} else {
					$('span[id='+_gId+']').text(_text); 
					$('.dropdown-toggle', _group).removeClass('is-active');
				}
			} else if($('.rdo_'+_field).length){
				var _total_checked = 0;
				$('.rdo_'+_field).each((_i, _elem) => {
					if($(_elem).is(':checked')){
						_total_checked++;
					}
				});
				if(_total_checked > 0){
					$('.dropdown-toggle', _group).addClass('is-active');
				} else {
					$('.dropdown-toggle', _group).removeClass('is-active');
				}		  
			} else if(_field.indexOf('price_range') >= 0){
				var price_min = $('input[name=price_min]').val(),
					price_max = $('input[name=price_max]').val(),
					price_min = price_min.replace(/\./g, ''),
					price_max = price_max.replace(/\./g, '');
				if(parseInt(price_min) > 0 || parseInt(price_max) < 10000000000){
					$('.dropdown-toggle', _group).addClass('is-active');
				}
			}
			$('.dropdown-toggle', _group).dropdown('toggle');
		}
		$Core.sop.reload();
		return false;
	}, 
	clear_search: function(_this, e){
		e.preventDefault();
		var tp = $(_this).getAttr('tp', "");
		if(tp == 'dropdown'){
			var _gId = $(_this).attr('gId'),
				_field = $(_this).attr('field'),
				_text = $('span[id='+_gId+']').attr('text'),
				_group = $(_this).closest('.btn-group');
			if($('.chk_'+_field).length){
				$('.chk_'+_field).prop('checked', false);
			} else if($('.rdo_'+_field).length){
				$('.rdo_'+_field).prop('checked', false);
			} else if(_field=='price_range'){
				_rsSlider.setValues(0,10);
			}
			$('span[id='+_gId+']').text(_text);
			$('.dropdown-toggle', _group).dropdown('toggle');
			$('.dropdown-toggle', _group).removeClass('is-active');
		} else {
			if($('.rdo_block:checked').length){
				$('.rdo_block:checked').prop('checked', false).trigger('change');
			}
			if($('.chk_bedroom:checked,.chk_home_direction:checked,.chk_floor_range:checked').length){
				$('.chk_bedroom:checked,.chk_home_direction:checked,.chk_floor_range:checked').each((_i, _elem) => {
					$(_elem).prop('checked', false);
				});
			}
		}
		$Core.sop.reload({});
		return false;
	},
	clear_checked: function(_this, e){
		e.preventDefault();
		var ref_id = $(_this).attr('ref_id');
		$('input[ref_id='+ref_id+']').prop('checked', false);
		$(_this).addClass('d-none');
		return false;
	},
	fireEvent: function(_this, e){
		var _total_checked = 0,
			ref_id = $(_this).attr('ref_id');
		$('input[ref_id='+ref_id+']').each((_i, _elem) => {
			if($(_elem).is(':checked')){
				_total_checked += 1;
			}
		});
		if(_total_checked > 0){
			$('#'+ref_id).removeClass('d-none');
		} else {
			$('#'+ref_id).addClass('d-none');
		}
	},
	update_click : function(sop_id,type){
		$.post(PCMS_URL+'/cn/update-click.cfg', {
			'sop_id' : sop_id,
			'type' : type
		}, function(html){
			// console.log('Success !')
		});
	},
	open_sop: function(_this, e){
		e.preventDefault();
		var sop_id = $(_this).getAttr('sop_id', 0),
			stock_id = $(_this).getAttr('stock_id', 0),
			stock_code = $(_this).getAttr('stock_code', 0),
			return_url = window.location.href;
			
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/cn/open.cfg', {
			'sop_id' : sop_id,
			'stock_id' : stock_id,
			'type_list' : type_list,
			'return_url' : return_url
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.sop.update_click(sop_id, 'view');
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			$Core.util.popstate($Core.sop.getLink(sop_id, stock_code));
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				var _modal = $(this);
				$('.tinyContentx', _modal).readmore({
					speed: 75, 
					maxHeight: 75
				});
				if($('.slideshow',_modal).length && parseInt(respJson.total_medias) > 1){
					$('.slideshow', _modal).owlCarousel({
						margin:0,
						loop:true,
						nav: true,
						lazyLoad:true,
						dots:true,
						autoplay:false,
						responsiveClass:true,
						navText: ['<i class="fa fa-angle-left"></i>',
							'<i class="fa fa-angle-right"></i>'],
						responsive:{
							0:{items:1},
							1200:{items:1},
						}
					});
				}
			});
		}, 'json');
		return false;
	},
	alert: function(_this, e){
		e.preventDefault();
		$Core.swal.info("Thông báo", "Bạn cần đăng nhập để xem thông tin người rao bán");
		return false;
	},
	close_pop: function(_this, e){
		e.preventDefault();
		$Core.util.popstate(current_page);
	},
	select_file : function(_this, e) {
		e.preventDefault();
		var total_images = 0,
			toId = $(_this).attr('toId');
		if($('input[name=total_images]').length){
			total_images = $('input[name=total_images]').val();
		}
		if(parseInt(total_images,10) >= 20){
			$Core.messager.alert("Thông báo", "Bạn không được sử dụng quá 20 hình ảnh");
		} else {
			$('.select_file_'+toId).trigger('click');
		}
		return false;
	},
	do_upload: function(_this, e){
		var total_images = 0,
			formData = new FormData(),
			files = $(_this).prop('files');
		if($('input[name=total_images]').length){
			total_images = $('input[name=total_images]').val();
		}
		if (files && files.length) {
			for (var i = 0; i < files.length; i++) {
				formData.append('images[]', files[i]);
			}
		}
		formData.append('total_images',total_images);
		$Core.util.toggleIndicatior(1);
		$.ajax({
			url: PCMS_URL+"/cn/upload-image.cfg",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			dataType : 'json',
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_success') >= 0){
					$('input[name=total_images]').val(respJson.total_images);
					if($('.imageList .item').length){
						$('.imageList .item:last').after(respJson.html);
					} else {
						$('.imageList').html(respJson.html);
					}
				}
			}
		});
	},			
	delete_image: function(_this, e){
		e.preventDefault();
		var total_images = $('input[name=total_images]').val();
		$Core.messager.confirm('Message', 'Bạn chắc chắn muốn xóa hình ảnh này', function(){
			$.post(PCMS_URL+'/cn/delete-image.cfg', {
				'src' : $(_this).attr('src')
			}, function(){
				$(_this).closest('.item').remove();
				$('input[name=total_images]').val(parseInt(total_images,10) - 1);
			});
		});
		return false;
	},
	delete: function(_this, e){
		e.preventDefault();
		var form = $(_this).closest('form'),
			sop_id = $(_this).attr('sop_id');
		if(!$(_this).hasClass('preventDefault')){
			$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa tin này?', function(){
				$Core.util.toggleIndicatior(1);
				$.post(PCMS_URL+'/cn/delete.cfg', {'sop_id':sop_id}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$('.sop__icon-'+sop_id).html(respJson.icon);
					$('.sop__menu-delete-'+sop_id).replaceWith(respJson.html);
					$('.sop__menu-lock-'+sop_id).toggleClass('preventDefault');
					$('.sop__menu-sold-'+sop_id).toggleClass('preventDefault');
					if($('tr.awe__sop-item-'+sop_id).length){
						$('.awe__sop-item-'+sop_id).toggleClass('deleted');
					} else {
						$('.awe__sop-item-'+sop_id).toggleClass('d-none');
					}
				}, 'json');
			});
		}
		return false;
	},
	mark_lock: function(_this, e){
		e.preventDefault();
		var sop_id = $(_this).attr('sop_id');
		$Core.util.toggleIndicatior(1);
		if(!$(_this).hasClass('preventDefault')){
			$.post(PCMS_URL+'/cn/lock.cfg', {'sop_id':sop_id}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$(_this).replaceWith(respJson.html);
				$('.sop__icon-'+sop_id).html(respJson.icon);
				$('.sop__menu-delete-'+sop_id).toggleClass('preventDefault');
			}, 'json');
		}
		return false;
	},
	mark_sold: function(_this, e){
		e.preventDefault();
		var sop_id = $(_this).attr('sop_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn báo bán căn này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/cn/sold.cfg', {'sop_id':sop_id}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$(_this).replaceWith(respJson.html);
				$('.sop__icon-'+sop_id).html(respJson.icon);
				$('.sop__menu-lock-'+sop_id).toggleClass('preventDefault');
			}, 'json');
		});
		return false;
	},
	select_building: function(_this, e){
		var toId = $(_this).attr('toId'),
			tp = $(_this).getAttr('tp', 'option'),
			$_adata = {'tp':tp};
		if(tp=='radio'){
			var block_id = $('input[name=block_id]:checked').val();
			$_adata['block_id'] = block_id;
		} else {
			var list_block_ids = $(_this).val();
			$_adata['list_block_ids'] = list_block_ids;
		}
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_building', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if(tp=='radio'){
				$('#'+toId+'_buidling_group').removeClass('d-none');
			}
			$('#'+toId).html(html);
		});
	},
	check_stock_code: function(_this, e){
		var stock_code = $(_this).val();
		if(!$Core.util.isEmpty(stock_code)){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=check_stock_code', {
				'stock_code' : stock_code
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_invalid') >= 0){
					$('button[type=submit]').attr('disabled','disabled');
					$(_this).addClass('is-invalid').removeClass('is-valid');
					$Core.swal.error('Thông báo', "Mã căn "+stock_code+" không tồn tại");
				} else {
					$('input[name=stock_id]').val(respJson.stock_id);
					$(_this).addClass('is-valid').removeClass('is-invalid');
					$('button[type=submit]').removeAttr('disabled');					
					$('.bs-webui-popover').webuiPopover('hideAll');
				}
			}, 'json');
		} else {
			$(_this).removeClass('is-valid');
			$(_this).removeClass('is-invalid');
			$('button[type=submit]').attr('disabled','disabled');
		}
	},
	select_checkbox: function(_this, e){
		e.preventDefault();
		var _total_checked = 0,
			gId = $(_this).attr('gId');
		$('input[gId='+gId+']').each((_i, _elem) => {
			if($(_elem).is(':checked')){
				_total_checked += 1;
			}
		});
		$('.btn-primary[gId='+gId+']').text('Áp dụng'+(_total_checked > 0 ? '('+_total_checked+')' : ''));
		return false;
	},
	copyToClipboard: function(_this, e) {
		e.preventDefault();
		/* Get the text field */
		var copyText = $(_this).data('link');
		console.log(copyText);
		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText);
		return false;
	},
	approved: function(_this, e){
		var sop_id = $(_this).attr('sop_id'),
			tp = $(_this).is(':checked') ? 'agree' : 'refuse';
		$Core.util.toggleIndicatior(1);
		if(tp=='refuse'){
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_notes', {
				'sop_id' : sop_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			}, 'json');
		} else {
			var $_adata = {'tp':tp, 'sop_id':sop_id};
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=approved', $_adata, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.alert.success("Thành công !");
				$('.sop__icon-'+sop_id).html(respJson.icon);
				if(tp=='confirm_refuse'){
					$('.btn-close', _form).trigger('click');
				}
			}, 'json');
		}
	},
	confirm_refuse: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			tp = $(_this).attr('tp'),
			sop_id = $(_this).attr('sop_id'),
			reason_not_approved = $('textarea[name=reason_not_approved]', _form).val(),
			$_adata = {'tp':tp, 'sop_id':sop_id};
			
		$_adata['reason_not_approved'] = reason_not_approved;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=approved', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.alert.success("Thành công !");
			$('.btn-close', _form).trigger('click');
			$('.sop__icon-'+sop_id).html(respJson.icon);
		}, 'json');
		return false;
	},
	advanced_approved: function(_this, e){
		var tp = $(_this).attr('tp'),
			sop_id = $(_this).attr('sop_id');
		$Core.util.toggleIndicatior(1);
		if(tp=='refuse'){
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_notes', {
				'sop_id' : sop_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			}, 'json');
		} else {
			e.preventDefault();
			var $_adata = {'tp':tp, 'sop_id':sop_id};
			if(tp=='confirm_refuse'){
				var _form = $(_this).closest('form'),
					reason_not_approved = $('textarea[name=reason_not_approved]', _form).val();
				$_adata['reason_not_approved'] = reason_not_approved;
			}	
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=approved', $_adata, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.alert.success("Thành công !");
				if(tp=='confirm_refuse'){
					$('.btn-close', _form).trigger('click');
				}
			});
		}
	},
	open_shop : function(_this, e){
		e.preventDefault();
		$(_this).tooltip('hide');
		var building_id = $(_this).attr("building_id"),
			$_adata = {'building_id' : building_id};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_shop', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
	},
	open_utilities : function(_this, e){
		e.preventDefault();
		$(_this).tooltip('hide');
		var sop_id = $(_this).attr("sop_id"),
			$_adata = {'sop_id' : sop_id};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_utilities', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
	},
	checkall: function(_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			block = $(_this).closest('.widget-block');
		if($.trim(tp)=='checkall'){
			$('.chk_sop_device', block).prop('checked', true);
		} else if($.trim(tp)=='uncheckall'){
			$('.chk_sop_device', block).prop('checked', false);
		}
		return false;
	},
	verified: function(_this, e){
		var sop_id = $(_this).attr('sop_id'),
			is_verified = $(_this).is(':checked') ? 1: 0;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=verified', {
			'sop_id' : sop_id,
			'is_verified' : is_verified
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_success') >= 0){
				$Core.alert.success('Xác minh thành công!');
			} else {
				$Core.alert.success('Xác minh thất bại!');
			}
		});
	},
	addLog : function(_this,e) {
		e.preventDefault();
		var sop_id = $(_this).attr("sop_id"),
			type = $(_this).attr("type"),
			href = $(_this).data("href");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=addLog', {
			'sop_id' : sop_id,
			'type' : type
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result){
				if(href == ""){
					$Core.sop.alert(_this,e)
				}else{
					window.location.href = href;
				}
				
			}
		}, "json");
	},
	showLog : function(_this,e) {
		e.preventDefault();
		var sop_id = $(_this).attr("sop_id");
		$(_this).tooltip("hide");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=showLog', {
			'sop_id' : sop_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result){
				$Core.popup.open('auto','auto', respJson.html, respJson.uid,"right");				
			}
		}, "json");
	},
	copyContentToClipboard: function(_this, e) {
		e.preventDefault();
		/* Get the text field */
		$(_this).tooltip("hide");
		var copyContent = $(_this).closest(".content-copy").find(".content_sop").text();
		var copyLink = $(_this).data('link');
		copyContent += "\nThông tin thêm: "+copyLink;
		// Copy the text inside the text field
		navigator.clipboard.writeText(copyContent);
		return false;
	},
	loadStockCode : function(_this,e) {
		e.preventDefault();
		var _form = $(_this).closest("form");
		var block_id = $("select[name='block_id']",_form).val();
		var building_id = $("select[name='building_id']",_form).val();
		var floor_range = $("select[name='floor_range']",_form).val();
		var stock_code = $("select[name='stock_code']",_form).val();
		var field = $(_this).data("field");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadStockCode', {
			'block_id' 	: block_id,
			'building_id' 	: building_id,
			'floor_range' 	: floor_range,
			'stock_code' 	: stock_code,
			'field' 		: field,
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(field == "building_id"){
				$("select[name='floor_range']").html("<option value=''>Tầng</option>");
			}
			$("select[name='"+field+"']").html(html);
		});
	},
	addStockCode : function(_this,e) {
		e.preventDefault();
		var _form = $(_this).closest("form");
		var stock_code = $("input[name='stockCode']",_form).val().toString();
		var building_id = $("select[name='building_id']",_form).find("option:selected").text();
		var floor_range = $("select[name='floor_range']",_form).find("option:selected").text();
		var check = 1;
		$("select.required,input.required").each(function(index, elm){
			if($(elm).val() == ""){
				$(elm).focus();
				check = 0;
				return false;
			}
		});
		if(check == 0){
			return false;
		}
		$("#stock_code").val(building_id+floor_range+stock_code).change();
	},
	select_video: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('#'+toId).trigger('click');
		return false;
	},
	upload_video: function(_this, e){
		var form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);
		form.ajaxSubmit({
			method: "POST",
			url: PCMS_URL+"/cn/upload-video.cfg",
			dataType : 'json',
			success: function (respJson) {
				form.resetForm();
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_success') >= 0){
					$('.video-player').html(respJson.html);
				}
			}
		});
	},
	cancel_video: function(_this, e){
		e.preventDefault();
		var sop_id = $(_this).attr('sop_id'),
			$_adata = {'sop_id' : sop_id};
		$Core.messager.confirm('Message', 'Bạn chắc chắn muốn xóa video này', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=cancel_video', $_adata, function(html){
				$Core.util.toggleIndicatior(0);
				$('.video-player').html(html);
			});
		});
		return false;
	},
	set_checked: function(_this, e){
		e.stopPropagation();
		$(_this).find('input[type=radio]').prop('checked', true);
	},
};