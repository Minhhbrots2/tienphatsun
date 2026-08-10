$(function(){
	var alreadyScroll = 0;
	$(window).scroll(function(){
		if(isScrolledIntoView('#showmorethisresult') && $Core.tool.scrolled == 0){
			$Core.tool.scrolled = 1;
			$('.showmorethisresult').removeClass('d-none').trigger('click');
		}
	});
	if(MOD=='tool' && ACT == 'calendar'){
		$Core.calendar.init();
	} else if(MOD=='tool' && SUB=='lucky_wheel' && ACT=='default'){
		$Core.lucky_wheel.load_lucky_wheel({});
	} else if(MOD=='tool' && SUB=='lucky_wheel' && ACT=='detail'){
		$Core.lucky_wheel.load_spinner(wheel_id, {}, true);
	}
});
$Core.tool = {
	scrolled : !1,
	iso_search_field: (_this, e) => {
		var toClass = $(_this).attr('toClass'),
			searchTxt = $(_this).val().toLowerCase();
		if($('.'+toClass).length){
			$('.'+toClass).each((_i, _elem) => {
				var _found,
					_text = $(_elem).text();
				if(_text.toLowerCase().indexOf(searchTxt) != -1){
					_found=1;
				}
				if(_found==1){
					$(_elem).show();	 	
				}else{
					$(_elem).hide();
				}
			});
		}
	}, load_more: (_this, e) => {
		e.preventDefault();
		var page = $(_this).attr('page');
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.tool.do_search({'page':page}, "more");
		}
		return false;
	}, toogle_agency: (_this, e) => {
		e.preventDefault();
		if($(_this).hasClass('btn-outline-primary')){
			$(_this)
				.addClass('btn-outline-default')
				.removeClass('btn-outline-primary')
				.text('Ẩn ĐL');
			$('.label-agency').removeClass('d-none');
		} else {
			$(_this)
				.removeClass('btn-outline-default')
				.addClass('btn-outline-primary')
				.text('Hiện ĐL');
			$('.label-agency').addClass('d-none');
		}
		return false;
	}, select_block: (_this, e) => {
		var toId = $(_this).attr('toId'),
			project_id = $(_this).val(),
			stock_type = $(_this).getAttr('stock_type', 0);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_block', {
			'project_id' : project_id,
			'stock_type' : stock_type
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html).multiselect('rebuild');
			$('#slb_Building_Id').empty().multiselect('rebuild');
			$Core.tool.do_search();
		});
	}, select_building: (_this, e) => {
		var toId = $(_this).attr('toId');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_building', {
			'list_block_ids' : $(_this).val()
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html);
			$('#'+toId).multiselect('rebuild');
			$("#fund_type_search").addClass("d-none");
			$(".form-check-input:checked",$("#fund_type_search")).prop("checked",false);
			$("select",$("#fund_type_search")).val("");
			$Core.tool.do_search();
		});
	}, loadFundType: (_this, e) => {
		var toId = $(_this).attr('toId');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadFundType', {
			'list_building_ids' : $(_this).val()
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.is_fund_type) {
				$("#"+toId).removeClass("d-none");
			}else{
				$("#"+toId).addClass("d-none");
				$(".form-check-input:checked",$("#"+toId)).prop("checked",false);
				$("select",$("#"+toId)).val("");
			}
			$Core.tool.do_search();
		},"json");
	}, load_wishlist: (options) => {
		var $_adata = options || {};
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_wishlist', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_wishlist').html(respJson.html);
		}, 'json');
	}, do_sort: (_this, e) => {
		e.preventDefault();
		var sort_by = "";
		if($(_this).hasClass('desc')){
			sort_by = 'asc';
		} else {
			sort_by = 'desc';
		}
		$(_this).removeClass('asc').removeClass('desc').addClass(sort_by);
		$('input[name=sort_by]').val(sort_by);
		$Core.tool.do_search();
		return false;
	}, clear_search: (_this, e) => {
		e.preventDefault();
		var _group = $(_this).closest('.btn-group'),
			_field = $(_this).data("field"),
			_dropdown = $('.dropdown-menu', _group),
			_text = $('.multiselect-selected-text', _group).attr('text');
		if(_field == "price") {
			$('input[name='+_field+'_min]').val(0*1000000000);
			$('input[name='+_field+'_max]').val(15*1000000000);
		}else{
			$('input[name='+_field+'_min]').val(0);
			$('input[name='+_field+'_max]').val(500);
		}
		
		$('.dropdown-toggle', _group).dropdown('toggle');
		$('.multiselect-selected-text', _group).text(_text);
		$Core.tool.do_search();
		return false;
	}, start_search: (_this, e) => {
		e.preventDefault();
		var _group = $(_this).closest('.btn-group'),
			_dropdown = $('.dropdown-menu', _group),
			_field = $(_this).data("field"),
			_text = $('.multiselect-selected-text', _group).attr('text');
		var _min_field = $('input[name='+_field+'_min]').val(),
			_max_field = $('input[name='+_field+'_max]').val();
		if(_field == "price") {
			var _min = $Core.util.toNumber(_min_field)/1000000000,
				_max = $Core.util.toNumber(_max_field)/1000000000;
			if(parseInt(_min) == 0 && parseInt(_max) == 15){
				$('.multiselect-selected-text', _group).text(_text);
			} else {
				$('.multiselect-selected-text', _group).text(`${_min}-${_max} tỷ`);
			}
		}else{
			var _min = $Core.util.toNumber(_min_field),
				_max = $Core.util.toNumber(_max_field);
			if(parseInt(_min) == 0 && parseInt(_max) == 500){
				$('.multiselect-selected-text', _group).text(_text);
			} else {
				$('.multiselect-selected-text', _group).text(`${_min}-${_max} m2`);
			}
		}
		
		
		$('.dropdown-toggle', _group).dropdown('toggle');
		$Core.tool.do_search();
		return false;
	}, do_search: (options,type='search') => {
		var $_adata = options || {};
		$_adata['is_toggle_agency'] = $('.btn-toggle-agency').hasClass('btn-outline-primary') ? 1 : 0;
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=do_search', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.showmorethisresult').addClass('d-none').removeClass('clicked');
			if(parseInt(respJson.total_record) <= (parseInt(respJson.per_page) * parseInt(respJson.current_page))){
				$Core.tool.scrolled = 1;
			} else {
				$Core.tool.scrolled = 0;
			}
			if(type == 'more'){
				$('.holder_search tr:last').after(respJson.html);
			}else{
				$('.holder_search').html(respJson.html);
			}
			$('.showmorethisresult').attr('page', parseInt(respJson.current_page)+1);
			$('.total_results').html(respJson.total_record);
			$('input[name=page]').val(parseInt(respJson.current_page)+1);
			if(respJson.callback) eval(respJson.callback);
			if(parseInt(respJson.total_record) >= 1 && (deviceType != 'phone')){
				//$('#tableStock').freezeTable('update');
			}
			$Core.util.popstate("/tool.html");
		},'json');
	}, edit_stock_field: (_this, e) => {
		e.preventDefault();
		var p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field'),
			clsTable = $(_this).attr('clsTable');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=edit_stock_field', {
			'p_id' : p_id,
			'p_field' : p_field,
			'clsTable' : clsTable
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, update_stock_field: (_this, e) => {
		e.preventDefault();
		var form = $(_this).closest('form'),
			p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field');
		$Core.util.toggleIndicatior(1);
		form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL+'/index.php?mod='+MOD+'&act=update_stock_field',
			data: {'p_id':p_id,'p_field':p_field},
			success: function(html){
				$Core.util.toggleIndicatior(0);
				window.location.reload(true);
			}
		});
	}, open_stock_video: (_this, e) => {
		e.preventDefault();
		var media_id = $(_this).getAttr('media_id',""),
			stock_meta_id = $(_this).attr('stock_meta_id'),
			$_adata = {'media_id':media_id, 'stock_meta_id':stock_meta_id};
			
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_stock_video', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, add_stock_video: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			media_id = $(_this).attr('media_id'),
			stock_meta_id = $(_this).attr('stock_meta_id');
			
		if($('textarea.required:visible,input.required:visible', _form).length){
			$('textarea.required:visible,input.required:visible', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&act=add_stock_video',
				data: {'media_id':media_id,'stock_meta_id':stock_meta_id},
				success: function(html){
					_form.resetForm();
					$Core.util.toggleIndicatior(0);
					$Core.tool.load_stock_media(stock_meta_id, {});
					$('.btn-close', _form).trigger('click');
				}
			});
		}
		return false;
	}, add_stock_image: (_this, e) => {
		e.preventDefault();
		if($(_this).hasClass('add_stock_image')){
			var _validated = 0,
				_form = $(_this).closest('form'),
				stock_meta_id = $(_this).attr('stock_meta_id');
			if($('textarea.required:visible,input.required:visible', _form).length){
				$('textarea.required:visible,input.required:visible', _form).each((_i, _elem) => {
					if($Core.util.isEmpty($(_elem).val())){
						_validated++;
						$(_elem).focus();
						return false;
					}
				});
			}
			if(_validated==0){
				$Core.util.toggleIndicatior(1);
				_form.ajaxSubmit({
					type: 'POST',
					url: PCMS_URL+'/index.php?mod='+MOD+'&act=add_stock_image',
					data: {'stock_meta_id':stock_meta_id},
					success: function(html){
						$Core.util.toggleIndicatior(0);
						$Core.tool.load_stock_media(stock_meta_id, {});
						$('.btn-close', _form).trigger('click');
					}
				});
			}
		} else {
			var toId = $(_this).attr('toId');
			$('#'+toId).trigger('click');
		}
		return false;
	}, upload_stock_image: (_this, e) => {
		var _form = $(_this).closest('form'),
			stock_meta_id = $(_this).attr('stock_meta_id'),
			action = $(_this).hasClass('upload_stock_image') 
				? 'uploadImage' : 'upload_stock_image';
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL+'/index.php?mod='+MOD+'&act='+action,
			data: {'stock_meta_id':stock_meta_id},
			dataType: 'json',
			success: function(respJson){
				_form.resetForm();
				$Core.util.toggleIndicatior(0);
				if(action=='upload_stock_image'){
					$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
				} else {
					var toId = $(_this).attr('toId');
					console.log(toId);
					$('.hidden_'+toId).val(respJson.image);
					$('.image_'+toId).attr('src', respJson.image);
				}
			}
		});
	}, edit_stock_media: (_this, e) => {
		e.preventDefault();
		var media_id = $(_this).attr('media_id'), 
			stock_meta_id = $(_this).attr('stock_meta_id');
		
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=edit_stock_media', {
			'media_id' : media_id,
			'stock_meta_id' : stock_meta_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, delete_stock_media: (_this, e) => {
		e.preventDefault();
		var media_id = $(_this).attr('media_id'),
			stock_meta_id = $(_this).attr('stock_meta_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa tin này?', function(){
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete_stock_media', {
				'media_id' : media_id,
				'stock_meta_id' : stock_meta_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.tool.load_stock_media(stock_meta_id, {});
			});
		});
		return false;
	}, load_stock_media: (stock_meta_id, options) => {
		var $_adata = options || {};
		$_adata['stock_meta_id'] = stock_meta_id;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_stock_media', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_stock_media_'+stock_meta_id).html(respJson.html);
		}, 'json');
	}, add_stock_file: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('#'+toId).trigger('click');
		return false;
	}, upload_stock_file: (_this, e) => {
		var _form = $(_this).closest('form'),
			stock_meta_id = $(_this).attr('stock_meta_id');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL+'/index.php?mod='+MOD+'&act=upload_file',
			data: {'stock_meta_id':stock_meta_id},
			success: function(html){
				_form.resetForm();
				$Core.util.toggleIndicatior(0);
				$Core.member.load_list_files(stock_meta_id, 'StockMeta', {});
			}
		});
	}, set_banner: (_this, e) => {
		e.preventDefault();
		e.stopPropagation();
		var banner = $(_this).attr('banner'),
			stock_meta_id = $(_this).attr('stock_meta_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=set_banner', {
			'banner' : banner,
			'stock_meta_id' : stock_meta_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('success') >= 0){
				$Core.swal.success('Thông báo','Thành công !');
				window.location.reload();
			} else {
				$Core.swal.success('Thông báo','Đã có lỗi xảy ra !');
			}
		});
	},
}
$Core.lucky_wheel = {
	open: (_this, e) => {
		e.preventDefault();
		var wheel_id = $(_this).attr('wheel_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=open', {
			'wheel_id' : wheel_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, handle_prize_type: (_this, e) => {
		var _form = $(_this).closest('form'),
			_prize_type = $('input[name=prize_type]:checked').val();
		if(_prize_type == 'gift'){
			$('input[name=prize_name]', _form).val("").focus();
			$('input[name=quantity]', _form).attr('readonly', false);
		} else {
			$('input[name=prize_name]', _form).val("Thêm +1 lượt");
			$('input[name=quantity]', _form).val(1).attr('readonly', true);
		}
	}, save : (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form'),
			wheel_id = $(_this).attr('wheel_id');
		if($('input.required:not(:disabled),select.required:not(:disabled)', _form).length){
			$('input.required:not(:disabled),select.required:not(:disabled)', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error += 1;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_error == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=save',
				data: {'wheel_id':wheel_id},
				dataType: 'json',
				success: function(respJson){
					_form.resetForm();
					$Core.util.toggleIndicatior(0);
					$Core.lucky_wheel.load_lucky_wheel({});
					$('.btn-close', _form).trigger('click');
				}
			});
		}
		return false;
	}, load_lucky_wheel: (options, isLoading=false) => {
		var $_adata = options || {};
		isLoading && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=load_lucky_wheel', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_lucky_wheel').html(respJson.html);
		}, 'json');
	}, view_wheel_prizes: (_this, e) => {
		e.preventDefault();
		var wheel_id = $(_this).attr('wheel_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=view_wheel_prizes', {
			'wheel_id' : wheel_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			if(__w > 1600){
				$Core.popup.openfull(__w*2/3,respJson.html,respJson.uid);
			} else {
				$Core.popup.openfull(__w*2/3,respJson.html,respJson.uid);
			}
			$Core.lucky_wheel.load_lucky_wheel_prizes(wheel_id, {}, false);
		}, 'json');
		return false;
	}, open_wheel_prizes: (_this, e) => {
		e.preventDefault();
		var wheel_id = $(_this).attr('wheel_id'),
			prize_id = $(_this).getAttr('prize_id', 0);
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=open_wheel_prizes', {
			'wheel_id' : wheel_id,
			'prize_id' : prize_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, select_image: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('.'+toId).trigger('click');
		return false;
	}, select_image_upload: function(_this, e){
		var _uid = $(_this).attr('uid'),
			_form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);	
		_form.ajaxSubmit({
			type : 'POST',
			url : '/index.php?mod='+MOD+'&sub=lucky_wheel&act=upload_image',
			dataType:'html',
			success : function(html){
				$Core.util.toggleIndicatior(0);
				$(`.image_${_uid}`).val(html);
				$(`.image_show_${_uid}`).attr('src', html);
				_form.clearForm();
				_form.resetForm();
			}
		});	
	}, save_prize : (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form'),
			wheel_id = $(_this).attr('wheel_id'),
			prize_id = $(_this).attr('prize_id');
		if($('input.required:not(:disabled),select.required:not(:disabled)', _form).length){
			$('input.required:not(:disabled),select.required:not(:disabled)', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error += 1;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_error == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=save_prize',
				data: {'prize_id':prize_id, 'wheel_id':wheel_id},
				dataType: 'json',
				success: function(respJson){
					_form.resetForm();
					$Core.util.toggleIndicatior(0);
					$('.btn-close', _form).trigger('click');
					$Core.lucky_wheel.load_lucky_wheel_prizes(wheel_id, {}, false);
				}
			});
		}
		return false;
	}, load_lucky_wheel_prizes: (wheel_id, options, isLoading=true) => {
		var $_adata = options || {};
		$_adata['wheel_id'] = wheel_id;
		isLoading && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=load_lucky_wheel_prizes', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_lucky_wheel_prizes').html(respJson.html);
			$(`.table_lucky_wheel_prizes_${wheel_id} tbody`).sortable({
				connectWith: `table_lucky_wheel_prizes_${wheel_id}`,
				handle: ".mySortableHandler",
				items: "tr:not(.not-sortable)",
				update: function (event, ui) {
					var order_no = $(this).sortable('toArray');
					// console.log(order_no);
				}
			}).disableSelection();
		}, 'json');
	}, delete_prize : (_this, e) => {
		e.preventDefault();
		var wheel_id = $(_this).attr('wheel_id'),
			prize_id = $(_this).attr('prize_id');
		$Core.messager.confirm("Thông báo", "Bạn có chắc chắn muốn xoá", function() {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=delete_prize', {
				'prize_id' : prize_id,
				'wheel_id' : wheel_id,
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.lucky_wheel.load_lucky_wheel_prizes(wheel_id, {}, false);
			}, 'json');
		});
	}, spinner: (res) => {
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=spinner', {
			'wheel_id' : wheel_id,
			'prize_id' : res.prize_id
		}, function(respJson){
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			$Core.lucky_wheel.load_spinner(wheel_id, {}, false);
			// Preloaders IMG
			preloadImages(respJson.sectors).then(imgs=>{
				preloadedImages = imgs;
				// set text style globally
				ctx.textBaseline = 'middle';
				ctx.textAlign = 'center';
				ctx.font = "bold 18px 'Segoe UI', Roboto, Arial, sans-serif";
				drawWheel(0);
				animate();
			});
			// Giảm lượt quay
			$('.max_spin_per_user').text(respJson.max_spin_per_user);
			if(parseInt(respJson.max_spin_per_user) == 0){
				$('.wheelOfFortune').addClass('pe-none');
				$('.no_spin_message').removeClass('d-none');
			}
		}, 'json');
	}, load_spinner: (wheel_id, options, isLoading=true) => {
		var $_adata = options || {};
		$_adata['wheel_id'] = wheel_id;
		isLoading && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=lucky_wheel&act=load_spinner', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_lucky_wheel_spinner').html(respJson.html);
		}, 'json');
	},
}
$Core.calendar = {
	_src: '',
	getRoom: () => {
		var v = $('.js__room-filter').val() || '0:0',
			p = v.split(':');
		return { office_id: p[0] || 0, room_id: p[1] || 0 };
	},
	buildSrc: () => {
		var r = $Core.calendar.getRoom();
		return `${PCMS_URL}/index.php?mod=${MOD}&act=load_calendar&office_id=${r.office_id}&room_id=${r.room_id}`;
	},
	init: () => {
		$Core.calendar._src = $Core.calendar.buildSrc();
		var oSettings = {
			selectable: true,
			unselectAuto: false,
			contentHeight: 600,
			initialView:'week',
			header: {left: 'today', center: 'title', right: 'prev,next'},
			events: $Core.calendar._src,
			eventRender : function(event, element, view){
				element.find(".fc-event-title").remove();
				element.append(event.html);
			}, eventClick: function(event, jsEvent, view){
			}, dayClick: function(date, jsEvent, view){
			}
		};
		$('#'+'fh-calendar').empty().fullCalendar(oSettings);
	}, change_room: (_this, e) => {
		var cal = $('#'+'fh-calendar');
		cal.fullCalendar('removeEventSource', $Core.calendar._src);
		$Core.calendar._src = $Core.calendar.buildSrc();
		cal.fullCalendar('addEventSource', $Core.calendar._src);
	}, open: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		var r = $Core.calendar.getRoom(),
			more = {'office_id': r.office_id, 'room_id': r.room_id},
			openFrom = $(_this).attr('openFrom'),
			calendar_id = $(_this).getAttr('calendar_id', 0);
		if(openFrom == '_calendar'){
			more['regis_date'] = $(_this).attr('regis_date');
		}
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_calendar', $.extend({
			'openFrom' : openFrom,
			'calendar_id' : calendar_id
		}, more), function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, save: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			calendar_id = $(_this).attr('calendar_id'),
			combo = ($('.js__room-select', _form).val() || '0:0').split(':');
		if($('textarea.required:visible,input.required:visible', _form).length){
			$('textarea.required:visible,input.required:visible', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&act=save_calendar',
				data: {'calendar_id':calendar_id, 'office_id':combo[0], 'room_id':combo[1]},
				success: function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$('.btn-close', _form).trigger('click');
						$('#'+'fh-calendar').fullCalendar("refetchEvents");
						$Core.swal.success("Thông báo", "Đăng ký lịch thành công!");
					} else if(html.indexOf('_duplicated') >= 0){
						$Core.swal.error("Thông báo", "Phòng đã có lịch trùng khung giờ này!");
					} else if(html.indexOf('_invalid') >= 0){
						$Core.swal.error("Thông báo", "Đăng ký thời gian không hợp lệ!");
					}
				}
			});
		}
		return false;
	}, set_fullday: (_this, e) => {
		var _form = $(_this).closest('form');
		$('input[name=start_time]', _form).val('09:00');
		$('input[name=end_time]', _form).val('18:00');
	}, delete: (_this, e) => {
		e.preventDefault();
		var calendar_id = $(_this).attr('calendar_id');
		$Core.messager.confirm("Thông báo", "Bạn chắc chắn muốn xóa", () => {
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete_calendar', {
				'calendar_id' : calendar_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0 ){
					$('#'+'fh-calendar').fullCalendar("refetchEvents");
				} else {
					$Core.swal.error("Thông báo", "Đã xảy ra lỗi !");
				}
			});
		});
		return false;
	}
, loadTotal_calendar :  (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		var $_adata = {},
			type = $(_this).data('type'),
			room_id = $("select[name='room_id']").val();
		$_adata["type"] = type;
		$_adata["room_id"] = room_id;
		if(type == "_SEARCH") {
			var _form = $(_this).closest("form");
			var month = $("select[name=month]",_form).val(), 
				year = $("select[name=year]",_form).val();
			$_adata['month'] = month;
			$_adata['year'] = year;
		}
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadTotal_calendar', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(type == "_OPEN") {
				$Core.popup.open('auto','auto', respJson.html, respJson.uid);	
			}else{
				$(".lst_department",_form).html(respJson.html)
			}
		}, 'json');
		return false;
	}
};
$Core.zalo = $.extend($Core.global.zalo, {
	sync_zalo: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=sync_zalo', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
		}, 'json');
		return false;
	}, load_chatlogs: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_chatlogs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_chatlogs').html(respJson.html);
		}, 'json');
	}, reload: (_this, e) => {
		e.preventDefault();
		$Core.tool.load_chatlogs({});
		return false;
	},
});
$Core.salary = {
	list: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(0);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=list_salary', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_salary').html(respJson.html);
			$('.total_results').html(respJson.total_dep_staffs);
		}, 'json');
	}, do_search: (_this, e) => {
		$Core.salary.list({});
	},
};
$Core.attendance = {
	load_import_logs: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(0);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=attendance&act=load_import_logs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_import_logs').html(respJson.html);
		}, 'json');
	}, open_import: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(0);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=attendance&act=open_import', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, do_change : (_this, e) => {
		var _uid = $(_this).attr('uid'),
			_value = $(_this).val(),
			_today = new Date();
		if($(_this).hasClass('js__do_config-change')){
			if(_value == '_new'){
				$Core.util.toggleIndicatior(0);
				$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=attendance&act=open_field', {
					'gId' : _uid,	
				}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$Core.popup.open('auto','auto',respJson.html,respJson.uid);
					$(_this).val(0).trigger('change');
				}, 'json');	
			} else {
				var _title = $(_this).find('option:selected').text();
				$(`input[name=config_name][uid=${_uid}]`).val(_title);
			}
		} else {
			if(_value == 'month'){
				var month = $Core.util.plz(_today.getMonth() + 1),
					year = _today.getFullYear();
				$(`.${_uid}`).html(`<input type="month" value="${year}-${month}" name="date_value" class="form-control" />`);
			} else if(_value == 'week'){
				var _week = $Core.util.getISOWeekNumber(),
					week = $Core.util.plz(_week),
					year = _today.getFullYear();
				$(`.${_uid}`).html(`<input type="week" value="${year}-W${week}" name="date_value" class="form-control" />`);
			}
		}
	}, save_field: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_uid = $(_this).attr('uid'),
			_gId = $(_this).attr('gId'),
			_form = $(_this).closest('form');
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error += 1;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_error == 0){
			// Create the DOM option that is pre-selected by default
			var _title = $('input[name=title]', _form).val(),
				_option = new Option(_title, _uid, true, true);
			// Close popup
			$('.btn-close', _form).trigger('click');
			// Append it to the select
			$(`input[name=config_name][uid=${_gId}]`).val(_title);
			$(_option).insertBefore($(`.slb_configs_${_gId} option[value="_new"]`));
			$(`.slb_configs_${_gId}`).trigger('change');
		}
		return false;
	}, open_config : (_this, e) => {
		e.preventDefault();
		var _gId = $(_this).attr('gId'),
			_form = $(_this).closest('form');
		if($("input[name='fileimport']",_form)[0].files.length > 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&sub=attendance&act=open_config', 
				data : {'gId' : _gId},
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					$Core.popup.open('auto','auto',respJson.html,respJson.uid);
				}
			});
		}else{
			$Core.swal.error("Thông báo", "Bạn cần chọn file dữ liệu cần import");
		}	
		return false;
	}, do_config: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_gId = $(_this).attr("gId"),
			_form = $(_this).closest("form");
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error += 1;
					if($(_elem).hasClass('select2-hidden-accessible')){
						$(_elem).select2('open');
					} else {
						$(_elem).focus();
					}
					return false;
				}
			});
		}
		if(_error == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&sub=attendance&act=do_config', 
				data:{'gId':_gId},
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						alertify.success(respJson.msg);
						$(".btn-close", _form).trigger("click");
						if(respJson.holderG.indexOf('_new') >= 0){
							var _option = new Option(respJson.config_name, respJson.config_id, true, true);
							$('select[name=config_id]').append(_option).trigger('change');
						} else {
							$('select[name=config_id]').val(respJson.config_id).trigger('change');
						}
					}else{
						alertify.error(respJson.msg);
					}
				}
			});
		}
		return false;
	}, do_import: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest("form");
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error += 1;
					if($(_elem).hasClass('select2-hidden-accessible')){
						$(_elem).select2('open');
					} else {
						$(_elem).focus();
					}
					return false;
				}
			});
		}
		if(_error == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&sub=attendance&act=do_import', 
				data : {},
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.msg.indexOf('_invalid') >= 0){
						$Core.popup.open('auto','auto',respJson.html_errors,respJson.uid);
					} else {
						// $Core.attendance.load_import_logs();
						// $('.btn-close', _form).trigger('click');
					}
				}
			});
		}
		return false;
	},
};