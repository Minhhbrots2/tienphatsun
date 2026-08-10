$(function(){
	if(ACT == 'default'){
		$Core.booking.load_bookings({});
		$(window).on('resize', () => {
			$Core.booking.sticky_fixed();
		});
	} else if(ACT == 'my_booking'){
		$Core.booking.load_mybookings({});
	} else if(ACT == 'stock_hug') {
		$Core.stock_hug.list({});
		$('.dropdown-stock-search').on('show.bs.dropdown', () => {
			setTimeout(() => {
				$('.search_keyword_field').select().focus();
			}, 500);
		});
		$_document.on('keyup','.search_keyword_field', function(ev){
			var _keyCode = ev.keyCode || ev.which;
			if(_keyCode==13){
				$Core.stock_hug.list({});
				ev.preventDefault();
				return false;
			}
		});
	}
	$_document.on('click', '.dropdown-button', e => {
		e.stopPropagation();
		const b = $(e.currentTarget), o = b.offset(),
			  m = b.siblings('.dropdown-menu').clone()
				  .addClass('dropdown-menu-floating')
				  .css({position:'absolute',display:'block'})
				  .appendTo('body');
		$('.dropdown-menu-floating').not(m).remove();
		m.css({top:o.top+b.outerHeight(),left:o.left+b.outerWidth()-m.outerWidth(),zIndex:1000});
		$_document.one('click',()=>m.remove());
	});
});
$Core.booking = $.extend($Core.global.booking,{
	parse_url: (url) => {
		try {
			const s = url || window.location.href; // nếu không có URL -> lấy hiện tại
			const u = new URL(s);
			return u.pathname;
		} catch (err) {
			console.error("URL không hợp lệ:", url);
			return "";
		}
	}, to_query_string: (obj, prefix) => {
		const pairs = [];
		for (const key in obj) {
			if (!obj.hasOwnProperty(key)) continue;
			const value = obj[key];
			const fullKey = prefix ? `${prefix}[${key}]` : key;
			if (typeof value === "object" && value !== null) {
				pairs.push(to_query_string(value, fullKey));
			} else {
				pairs.push(encodeURIComponent(fullKey) + "=" + encodeURIComponent(value));
			}
		}
		return '?'+pairs.join("&");
	}, open_billing: (_this, e) => {
		e.preventDefault();
		var holderG = $(_this).attr('holderG'),
			month = $(_this).getAttr('month', 'all_month'),
			project_id = $(_this).attr('project_id'),
			billing_type = $(_this).attr('billing_type');
		$Core.util.toggleIndicatior(0);
		$.post('/index.php?mod='+MOD+'&act=open_billing', {
			'holderG' : holderG,
			'month' : month,
			'project_id' : project_id,
			'billing_type' : billing_type
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, select_type: (_this, e) => {
		e.preventDefault();
		var booking_type = $(_this).val();
		if(booking_type == 'investor')
			window.location.href = `/booking/${booking_type}.html`;
		else
			window.location.href = `/booking.html`;
	}, select_project: (_this, e) => {
		e.preventDefault();
		var project_id = $(_this).attr('project_id'),
			block_id = $(_this).getAttr('block_id', 0);
		$('.tab_project').removeClass('active');
		$(_this).addClass('active');
		if(ACT == 'report'){
			$Core.booking.load_report({
				'project_id' : project_id,
				'block_id' : block_id
			});
		} else {
			if($(`.slb_block`).length > 0) {
				$(`.slb_project`).attr("block_id",block_id);
				$(`.slb_project`).val(project_id).trigger("change").multiselect('rebuild');
				$(`.slb_block`).val(block_id).trigger("change").multiselect('rebuild');
			}else{				
				$Core.booking.do_search(_this, e);
				var $_select = $('.search_field[name=project_id]').selectize(),
					$_selectize = $_select[0].selectize;
				$('.js__create-booking').attr('project_id', project_id);
				if(parseInt(block_id, 10) > 0){
					$('.js__create-booking').attr('block_id', block_id);
					$_select.attr({'block_id':block_id,'is_reload':1});
					$_selectize.setValue(project_id);
				} else {
					$_selectize.setValue(project_id);
					$Core.booking.do_search(_this, e);
				}
			}
		}
		return false;
	}, crawl:(_this, e) => {
		e.preventDefault();
		$.post('/index.php?mod='+MOD+'&act=crawl', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
		}, 'json');
		return false;
	}, open: (_this, e) => {
		e.preventDefault();
		var booking_id = $(_this).attr('booking_id'),
			booking_type = $(_this).attr('booking_type'),
			project_id = $(_this).getAttr('project_id', 0),
			block_id = $(_this).getAttr('block_id', 0),
			building_id = $(_this).getAttr('building_id', 0);
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=open', {
			'booking_type' : booking_type,
			'booking_id' : booking_id,
			'project_id' : project_id,
			'block_id' : block_id,
			'building_id' : building_id,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, handle_dep_change: (_this, e) => {
		var department_id = $(_this).val(),
			staff_id = $(_this).getAttr('staff_id', 0),
			call_from = $(_this).getAttr('call_from', 'add_edit');
		if(parseInt(department_id, 10) == 10000){
			$('.group_staff').addClass('d-none');
			$('.group_partner').removeClass('d-none');
		} else {
			$('.group_partner').addClass('d-none');
			$('.group_staff').removeClass('d-none');
			$.post('/index.php?mod='+MOD+'&act=load_staff', {
				'staff_id' : staff_id,
				'department_id' : department_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(call_from == 'search'){
					var $_select = $('.search_field[name=staff_id]').selectize(),
						$_selectize = $_select[0].selectize;
				} else {
					var _form = $(_this).closest('form'),
						$_select = $('select[name=staff_id]', _form).selectize(),
						$_selectize = $_select[0].selectize;
				}
				$_selectize.clearCache('option');
				$_selectize.clearOptions();
				$_selectize.addOption(respJson);
				$_selectize.refreshOptions(false);
				if(call_from == 'add_edit' 
					&& typeof(staff_id) !== 'undefined' && parseInt(staff_id, 10) > 0){
					$_selectize.addItem(staff_id);
				}
			}, 'json');
		}
	}, load_block: (_this, e) => {
		var uid = $(_this).attr('uid'),
			block_id = $(_this).getAttr('block_id', 0),
			is_reload = $(_this).getAttr('is_reload', 0),
			call_from = $(_this).getAttr('call_from', 'add_edit');
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=load_block`, {
			'uid' : uid,
			'block_id' : block_id,
			'call_from' : call_from,
			'project_id':$(_this).val()
		}, function(html){
			$(`.slb_block_${uid}`).html(html);
			if(parseInt(is_reload, 10) == 1){
				$Core.booking.load_bookings({});
				$('.search_field[name=block_id]').trigger('change');
			} else {
				if($(_this).hasClass("multiselect")) {
					console.log("ss");
					$(`.slb_block_${uid}`).multiselect('rebuild');
					$(`.slb_building_${uid}`).empty().multiselect('rebuild');
				}
				$Core.booking.load_building(_this, e);
			}
		});
	}, load_building: (_this, e) => {
		var uid = $(_this).attr('uid'),
			call_from = $(_this).getAttr('call_from', 'add_edit');
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=load_building`, {
			'uid' : uid,
			'call_from' : call_from,
			'block_id':$(_this).val()
		}, function(html){
			$(".tab_project.active").removeClass("active");
			$(".tab_project[block_id='"+$(_this).val()+"']").tab("show");
//			$(".tab_project[block_id='"+$(_this).val()+"']").trigger();
			$(`.slb_building_${uid}`).html(html);
			if($(_this).hasClass("multiselect")) {
				$(`.slb_building_${uid}`).multiselect('rebuild');
			}
		});
	}, do_changed : (_this, e) => {
		var _uid = $(_this).attr('uid'),
			_key = $(_this).getAttr('key', "");
		if(_key == 'unit_axis'){
			var bedroom_id = $('option:selected', _this).attr('bedroom_id'),
				$_select = $(`select[name=bedroom_id][uid=${_uid}]`).selectize(),
				$_selectize = $_select[0].selectize;
			$_selectize.setValue(bedroom_id);
		} else if(_key == 'floor_level'){
			var level = $('option:selected', _this).attr('level');
			$(`select[name=floor_type][uid=${_uid}]`).val(level);
		} else if(_key == 'floor_range'){
			var building_id = $(_this).val();
			$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=load_floor_range`, {
				'_uid' : _uid,
				'building_id' : building_id
			}, function(respJson){
				$(`select[name=unit_axis][uid=${_uid}]`).html(respJson.html_stock_options);
				$(`select[name=floor_range][uid=${_uid}]`).html(respJson.html_floor_options);
			}, 'json');
		}	
	}, load_floor_level : (_this, e) => {
		var uid = $(_this).attr('uid'),
			level = $('option:selected', _this).attr('level');
		$(`select[name=floor_type][uid=${uid}]`).val(level);
	}, select_image: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			to_field = $(_this).attr('to_field'),
			modal = $(_this).closest('.modal');
		$('.upload_image_'+toId, modal).attr('to_field', to_field).trigger('click');
		return false;
	}, upload_image: (_this, e) => {
		var _form = $(_this).closest('form'),
			toId = $(_this).attr('toId'),
			to_field = $(_this).attr('to_field'),
			booking_id = $(_this).attr('booking_id');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=upload_image",
			data: {'booking_id':booking_id,'to_field':to_field, 'toId':toId},
			dataType: "html",
			success: function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0 ){
					var tmp = html.split('|||');
					$('.'+to_field+'_'+toId).val(tmp[1]);
					if(to_field == 'ccid_front' || to_field == 'ccid_back'){
						var img = `<img src="${tmp[1]}" class="w-100 h-100 rounded-1" />`
						$(`#ccid_front_${toId}`).html(img);
					}else if(to_field == "payment_order") {
						var id_modal = $(_this).closest(".modal").attr("id");
						$Core.booking.loadDataImageCrawl(tmp[1],id_modal);						
					}
				}
			}
		});
	},
	loadDataImageCrawl: (image,toId) => {
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=ajax&sub=helper&act=loadDataImageCrawl', {"image":image}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.content) {
				$("input[name='content']",$("#"+toId)).val(respJson.content);	
			}
			if(respJson.price) {
				$("input[name='amount']",$("#"+toId)).val(respJson.price);	
			}			
		},"json");	
	}, upload_image_clipboard: (_this, e) => {
		for (var i = 0 ; i < e.clipboardData.items.length ; i++) {
			var item = e.clipboardData.items[i];
			if (item.type.indexOf("image") != -1) {
				$Core.booking.do_upload_image_clipboard(_this, item.getAsFile());
			}
		}
	}, do_upload_image_clipboard: (_this, file) => {
		var formData = new FormData(),
			toId = $(_this).attr('toId'),
			to_field = $(_this).attr('to_field'),
			booking_id = $(_this).attr('booking_id');
		formData.append('hid', 'upload'); 
		formData.append('to_field', to_field); 
		formData.append('booking_id', booking_id); 
		formData.append('upload_image', file); 
		$Core.util.toggleIndicatior(1);
		$.ajax({
			url: "/index.php?mod="+MOD+"&act=upload_image",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0 ){
					var tmp = html.split('|||');
					$('.'+to_field+'_'+toId).val(tmp[1]);
					if(to_field == 'ccid_front' || to_field == 'ccid_back'){
						var img = `<img src="${tmp[1]}" class="w-100 h-100 rounded-1" />`
						$(`#ccid_front_${toId}`).html(img);
					}else if(to_field == "payment_order") {
						var id_modal = $(_this).closest(".modal").attr("id");
						$Core.booking.loadDataImageCrawl(tmp[1],id_modal);						
					}
				}
			}
		});
	}, do_search: (_this, e) => {
		if($('.my_booking').length){
			$Core.booking.load_mybookings({});
		} else {
			$Core.booking.load_bookings({});
		}
	}, load_bookings: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field'),
					_value = $(_elem).val();
				if(_field == "block_id") {
					$(".js__create-booking").attr("block_id",_value);
					$(".slb_project").attr("block_id",_value);
				}
				if(_field == "project_id") {
					$(".js__create-booking").attr("project_id",_value);
				}
				console.log(_field,_value);
				if(typeof(_field) !== 'undefined' && !$Core.util.isEmpty(_value)){
					$_adata[_field] = _value;
				}
			});
		}
		var query_string = $Core.booking.to_query_string($_adata);
		if(!$Core.util.isEmpty(query_string)){
			var _path = $Core.booking.parse_url();
			$Core.util.popstate(_path+query_string);
		}
		$_adata['booking_type'] = booking_type;
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=load_bookings`, $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_bookings').html(respJson.html);
			$('.briefs').html(respJson.html_briefs);
			$Core.booking.sticky_fixed();
		});
	}, load_mybookings: (options) => {
		var $_adata = options || {},
			view_by = $('input[name=view_by]:checked').val();
		$_adata['view_by'] = view_by;
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field'),
					_value = $(_elem).val();
				if(typeof(_field) !== 'undefined' && !$Core.util.isEmpty(_value)){
					$_adata[_field] = _value;
				}
			});
		}
		var query_string = $Core.booking.to_query_string($_adata);
		if(!$Core.util.isEmpty(query_string)){
			var _path = $Core.booking.parse_url();
			$Core.util.popstate(_path+query_string);
		}
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=load_mybookings`, $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_mybookings').html(respJson.html);
			$('.briefs').html(respJson.html_briefs);
			//$Core.booking.sticky_fixed();
			const wrapper = document.querySelector('.table-container');
			const fixedRows = wrapper.querySelectorAll('.group-row-fixed');
			fixedRows.length && wrapper.addEventListener('scroll', () => {
				const scrollLeft = wrapper.scrollLeft;
				fixedRows.forEach(row => {
					row.style.left = `${scrollLeft}px`;
				});
			});
		});
	}, set_status: (_this, e) => {
		e.preventDefault();
		var status_id = $(_this).attr('status_id'),
			$_select = $('.search_field[name=status_id]').selectize(),
			$_selectize = $_select[0].selectize;
		$_selectize.setValue(status_id);
		$Core.booking.load_bookings({});
		return false;
	}, sticky_fixed : () => {
		if($('.table-stock:not(.notFixedTable)').length){
			$('.table-stock:not(.notFixedTable)').each((_i, _elem) => {
				var _www = $(_elem).find('.th_range:first').outerWidth(false);
				$(_elem).find('td.th_ut').css('left', _www);
			});
		} else {
			var _www = $('.table-booking').find('tr:first th:first').outerWidth(false);
			$('.table-booking tr td:nth-child(2),.table-booking tr th:nth-child(2)').css('left', _www);
		}
	}, save_booking: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form'),
			holderG = $(_this).attr('holderG'),
			booking_id = $(_this).attr('booking_id'),
			booking_type = $(_this).attr('booking_type'),
			$_adata = {'booking_id':booking_id, 'booking_type':booking_type}; 
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error = 1;
					console.log(_elem);
					if($(_elem).hasClass('selectize-control')){
						var $_select = $(_elem).selectize(),
							$_selectize = $_select[0].selectize;
						$_selectize.refreshOptions();
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
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_booking",
				data: $_adata,
				dataType:"json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(ACT == 'my_booking'){
						$Core.booking.load_mybookings({});
					} else {
						$Core.booking.load_bookings({});
					}
					$('.btn-close', _form).trigger('click');
					if(holderG == 'continue'){
						$Core.alert.success("Thêm mới thành công !");
						$('.create_booking').trigger('click');
					}
				}
			});
		}
		return false;
	}, select_this: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			money = $(_this).data('money');
		$('.'+toId).val(money);
		return false;
	}, open_activity: (_this, e) => {
		e.preventDefault();
		var holderG = $(_this).attr('holderG'),
			booking_id = $(_this).attr('booking_id'),
			booking_type = $(_this).attr('booking_type'),
			activity_id = $(_this).getAttr('activity_id', 0);
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=open_activity`, {
			'holderG' : holderG,
			'booking_id' : booking_id,
			'booking_type' : booking_type,
			'activity_id' : activity_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$('#attachments_'+respJson.uid).MultiFile({
				list: '#MultiFile-preview_'+respJson.uid
			});
		});
		return false;
	}, save_activity: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form'),
			holderG = $(_this).attr('holderG'),
			booking_id = $(_this).attr('booking_id'),
			activity_id = $(_this).attr('activity_id'),
			$_adata = {'holderG':holderG, 'booking_id':booking_id, 'activity_id':activity_id};
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error = 1;
					console.log(_elem);
					if($(_elem).hasClass('selectize-control')){
						var $_select = $(_elem).selectize(),
							$_selectize = $_select[0].selectize;
						$_selectize.refreshOptions();
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
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_activity",
				data: $_adata,
				dataType:"json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					$Core.alert.success('Thành công !');
					$('.btn-close', _form).trigger('click');
					$Core.booking.load_content(booking_id);
				}
			});
		}
		return false;
	}, cancel_activity: (_this, e) => {
		e.preventDefault();
		var holderG = $(_this).attr('holderG'),
			booking_id = $(_this).attr('booking_id'),
			activity_id = $(_this).attr('activity_id');
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=cancel_activity`, {
			'holderG' : holderG,
			'booking_id' : booking_id,
			'activity_id' : activity_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, do_cancel_activity: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form'),
			holderG = $(_this).attr('holderG'),
			booking_id = $(_this).attr('booking_id'),
			activity_id = $(_this).attr('activity_id');
		if($('input.required,textarea.required', _form).length){
			$('input.required,textarea.required', _form).each((_i, _elem) => {
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
				type:'POST',
				url: `${PCMS_URL}/index.php?mod=${MOD}&act=do_cancel_activity`,
				data: {'holderG' : holderG, 'booking_id' : booking_id, 'activity_id' : activity_id},
				success: function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$('.btn-close', _form).trigger('click');
						$Core.alert.success('Thành công !');
						$Core.booking.load_content(booking_id);
					}
				}
			});
		}
		return false;
	}, open_confirm: (_this, e) => {
		e.preventDefault();
		var booking_id = $(_this).attr('booking_id');
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=open_confirm`, {
			'booking_id' : booking_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, do_cancel: (_this, e) => {
		e.preventDefault();
		var _validated = 1,
			_form = $(_this).closest('form'),
			booking_id = $(_this).attr('booking_id'),
			cancel_reason = $('textarea[name=cancel_reason]', _form).val();
		if($Core.util.isEmpty(cancel_reason)){
			_validated = 0;
			$('textarea[name=cancel_reason]', _form).focus();
			return false;
		}
		if(_validated == 1){
			$Core.util.toggleIndicatior(1);
			$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=cancel_booking`, {
				'booking_id' : booking_id,
				'cancel_reason' : cancel_reason
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.booking.load_bookings({});
				$('.btn-close', _form).trigger('click');
			}, 'json');
		}
		return false;
	}, load_report: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		if($Core.util.isEmpty($_adata)){
			var tab_active = $('.tab_project.active'),
				project_id = tab_active.attr('project_id'),
				block_id = tab_active.attr('block_id');
			$_adata['project_id'] = project_id;
			$_adata['block_id'] = block_id;
		}
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=load_report`, $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_report').html(respJson.html);
		}, 'json');
	}, open_state: (_this, e) => {
		e.preventDefault();
		var booking_id = $(_this).attr('booking_id'),
			booking_type = $(_this).attr('booking_type');
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=open_state`, {
			'booking_id' : booking_id,
			'booking_type' : booking_type
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');	
		return false;
	}, do_upd_state : (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form'),
			booking_id = $(_this).attr('booking_id'),
			booking_type = $(_this).attr('booking_type');
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=upd_state",
			data: {'booking_id':booking_id, 'booking_type' : booking_type},
			dataType:'html',
			success: function(html){
				$Core.util.toggleIndicatior(0);
				$Core.booking.load_bookings({});
				$('.btn-close', _form).trigger('click');
			}
		});
		return false;
	}, approved : (_this, e) => {
		e.preventDefault();
		$Core.swal.confirm('Xác nhận', "Bạn chắc chắn muốn thực hiện", () => {
			var booking_id = $(_this).attr('booking_id');
			$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=approved`, {
				'booking_id' : booking_id
			}, function(html){
				if(html.indexOf('_success') >= 0){
					$Core.booking.load_bookings({});
					$Core.booking.load_content(booking_id);
					$('.js__booking-activity').removeAttr('disabled');
				} else if(html.indexOf('_error') >= 0) {
					$Core.swal.error('Thông báo', "Đã xảy ra lỗi trong quá trình thực hiện");
				}
			});
		});
		return false;
	},
})
$Core.stock_hug = {
	open_import : (_this, e) => {
		e.preventDefault();
		var $_adata = {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_import', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			if(respJson.callback) eval(respJson.callback);
		},'json');
		return false;
	}, do_import: (_this, e) => {
		e.preventDefault();
		var project_id = $(_this).attr('project_id'),
			$_adata = {"project_id" : project_id};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=do_import', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.stock_hug.list({});
		},'json');
		return false;
	}, open : (_this, e) => {
		e.preventDefault();
		var stock_hug_id = $(_this).attr('stock_hug_id'),
			$_adata = {'stock_hug_id':stock_hug_id};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_stock_hug', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			if(respJson.callback) eval(respJson.callback);
		},'json');
		return false;
	}, delete: (_this, e) => {
		e.preventDefault();
		var stock_hug_id = $(_this).attr('stock_hug_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xoá?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete_stock_hug', {
				'stock_hug_id' : stock_hug_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				var page = $('input[name=current_page]').val(),
					per_page = $('input[name=per_page]').val();
				$Core.stock_hug.list({'page':page, 'per_page':per_page});
			});
		});
	}, save: (_this, e) => {
		e.preventDefault();
		var _error = 0, 
			_form = $(_this).closest('form'),
			_holderG = $(_this).attr('holderG'),
			stock_hug_id  = $(_this).attr('stock_hug_id'),
			$_adata = {'stock_hug_id':stock_hug_id};
		if($('select.required,input.required', _form).length){
			$('select.required,input.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error++;
					if($(_elem).hasClass('selectized')){
						var $_select = $(_elem).selectize(),
							$_selectize = $_select[0].selectize;
						$_selectize.refreshOptions();
					} else {
						$(_elem).focus();
					}
					return false;
				}
			});
		}
		if(_error==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_stock_hug",
				data: $_adata,
				dataType:'html',
				success: function(html){
					$Core.util.toggleIndicatior(0);		
					if(html.indexOf('_success') >= 0){
						if(_holderG=='continue'){
							$Core.alert.success('Thêm mới thành công');
							$('.btn-close', _form).trigger('click');
							$('.create_quick_stock_hug').trigger('click');
						} else {
							var page = $('input[name=current_page]').val(),
								per_page = $('input[name=per_page]').val();
							$Core.stock_hug.list({'page':page, 'per_page':per_page});
							$('.btn-close', _form).trigger('click');
						}
					} else if(html.indexOf('_error') >= 0){
						swal( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại","error");
					} else if(html.indexOf('_duplicated') >= 0){
						swal( "Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại","error");
					} 
				}
			});
		}
		return false;
	}, hanlde_stock_code: (_this, e) => {
		var stock_code = $(_this).val();
		if(!$Core.util.isEmpty(stock_code)){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=get_bedroom_type', {
				'stock_code' : stock_code
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$('select[name=bedroom_type]').val(respJson.bedroom_id);
			}, 'json');
		}
	}, do_search: (_this, e) => {
		var holderG = $(_this).getAttr('holderG', "_search");
		if(holderG == "_reset"){
			$('#'+'frmIssue')[0].reset();
		}
		e.preventDefault();
		$Core.stock_hug.list({});
		return false;
	}, toggle_search: (_this, e) => {
		e.preventDefault();
		var gId = $(_this).attr('gId');
		$('#'+gId).dropdown('toggle');
		$Core.stock_hug.list({});
		return false;
	}, set_status : (_this, e) => {
		e.preventDefault();
		var _field = $(_this).attr('field'),
			_value = $(_this).attr(_field);
		$('select[name='+_field+']').val(_value).trigger('change');
		return false;
	}, list: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_stock_hug', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holderStockHug').html(respJson.html);
			$('.briefStockHug').html(respJson.html_brief);
			$('.total-record').text(respJson.total_record);
			$('input[name=per_page]').val(respJson.per_page);
			$('input[name=current_page]').val(respJson.current_page);
			if(parseInt(respJson.total_page)){
				$('#pager_stock_hug').pagination({
					total:respJson.total_record,
					pageSize:respJson.per_page,
					pageNumber : respJson.current_page,
					onRefresh : function(pageNumber,pageSize){
						$Core.stock_hug.list($.extend(options, {'page':pageNumber,'per_page':pageSize}));
					}, onSelectPage : function(pageNumber,pageSize){
						$Core.stock_hug.list($.extend(options, {'page':pageNumber,'per_page':pageSize}));
					}
				});
			}
		},'json');
	},
};