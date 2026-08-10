$(function(){
	$Core.sop.init();
	$('.chatlogs').on('scroll', function(e){
		var scrollTop = $(this).scrollTop(),
			offsetHeight = $(this).outerHeight(),
			scrollHeight = $(this)[0].scrollHeight;
		// kiểm tra nếu scroll đến cuối
		if ((scrollTop + offsetHeight >= (scrollHeight-1)) && $Core.sop.scrolled == 0) {
			$Core.sop.scrolled = 1;
			$('.showmorethis').removeClass('d-none').trigger('click');
		}
	});
});
$Core.sop = $.extend($Core.global.sop, {
	scrolled : !1,
	init: function(){
		if($('input[data-bind=true]').length){
			$('input[data-bind=true]').each((_i, _elem) => {
				$(_elem).trigger('keyup');
			});
		}
		if($('.js__block-rs_slider:not(.rs-installed)').length){
			$('.js__block-rs_slider:not(.rs-installed)').each((_i, _elem) => {
				var _rsSlider = new Array(),
					_id = $(_elem).find('button.dropdown-toggle').attr('id'),
					_target = $(_elem).find('button.dropdown-toggle').data('target');
				$(_elem).addClass('rs-installed');
				$('#'+_id).on('shown.bs.dropdown', function(){
					var _values = [0,30,50,80,100,150,180,200,250,300,500],
						_min = $('input[name='+_target+'_min]').val(),
						_max = $('input[name='+_target+'_max]').val();
					if(_target=='price'){
						_min = $Core.util.shortNumber(_min);
						_max = $Core.util.shortNumber(_max);
						_values = [0,1,2,3,5,7,9,10,12,15,20,25,30,35,40,50];
					} else {
						_min = parseInt(_min);
						_max = parseInt(_max);
					}
					_rsSlider[_i] = new rSlider({
						target: '#'+_target+'-range',
						values: _values,
						range: true,
						labels: true,
						tooltip: false,
						set: [_min,_max],
						onChange: function (vals) {
							var _tmp = vals.split(','),
								_min_value = _tmp[0],
								_minValue = _tmp[0],
								_max_value = _tmp[1],
								_maxValue = _tmp[1];
							if(_target == 'price'){
								_minValue = _min_value*1000000000;
								_maxValue = _max_value*1000000000;
								_min_value = $Core.chart.formatPrice(_min_value*1000000000);
								_max_value = $Core.chart.formatPrice(_max_value*1000000000);
							}
							$('input[name='+_target+'_min]').val(_min_value);
							$('input[name='+_target+'_max]').val(_max_value);
							$(".item_"+_target).removeClass("active");
							$(".item_"+_target).each(function (id,el) {
								let min= $(el).data("min"),
									max= $(el).data("max");
								if(_minValue == min && _maxValue == max) {
									$(el).addClass("active");
								}
							});
						}
					});
				}).on('hidden.bs.dropdown', function(){
					_rsSlider[_i].destroy();
				});
			})
		}
		if($('.js__search-form-modal').length){
			var _rsSlider,
				_modal = $('.js__search-form-modal');
			$('#js__search-form-modal').on('shown.bs.modal', function(){
				var price_min = $('input[name=price_min]').val(),
					price_max = $('input[name=price_max]').val();
				$("input[name=price_range]",_modal).each(function (id,el) {
					let min= $(el).data("min"),
						max= $(el).data("max");
					if(price_min == min && price_max == max) {
						$(el).prop("checked",true);
					}else{
						$(el).prop("checked",false);
					}
				});
			}).on('hidden.bs.modal', function(){
				_rsSlider.destroy();
			});
		}
		if(MOD=='sop' && ACT == 'default'){
			$Core.sop.list_sop({}, "append", false);
		}
		if(MOD=='sop' && ACT == 'telesale'){
			$Core.sop.list_telesale({});
		}
	},
	load_chatlogs_more : (_this, e) => {
		e.preventDefault();
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			var reg_date = $('.awe__chat-item:last').data('reg_date');
			$Core.sop.load_chatlogs({
				'action':'_more',
				'reg_date':reg_date
			});
		}
		return false;
	},
	add_sop: (_this, e) => {
		e.preventDefault();
		var sop_chatlog_id = $(_this).attr('sop_chatlog_id');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=add_sop', {
			'sop_chatlog_id' : sop_chatlog_id
		}, function(respJson){
			
		}, 'json');
		return false;
	},
	isNumber: (e) => {
		for (var t = 0; t < e.length; t++) {
			var n = e.substring(t, t + 1);
			if (!(n >= "0" && n <= "9")) return !1
		}
		return !0
	},
	convertNumberToText: function(e) {
		if (!$Core.sop.isNumber(e)) return "";
		var t = parseInt(e / 1e9, 0),
			n = parseInt(e % 1e9 / 1e6, 0),
			i = parseInt(e % 1e9 % 1e6 / 1e3, 0),
			r = parseInt(e % 1e9 % 1e6 % 1e3, 0),
			a = "";
		return t > 0 && parseInt(e, 0) > 9e8 && (a = a + t + (n > 0 ? "," + n / 100 : "") + " Tỷ "), 0 === t && n > 0 && (a = a + n + (i > 0 ? "," + i / 100 : "") + " Triệu "), 0 === n && i > 0 && (a = a + i + (r > 0 ? "," + r / 100 : "") + " Ngìn "), 0 === i && r > 0 && (a = a + r + " Đồng"), a = a.replace(/\./g, "")
	},
	strPrice: function(e) {
		var t = e.toString().replace("", ""),
			n = !1,
			i = [],
			r = 1;
		t.indexOf(".") > 0 && (n = t.split("."), t = n[0]);
		for (var a = 0, s = (t = t.split("").reverse()).length; a < s; a++) "." !== t[a] && (i.push(t[a]), r % 3 == 0 && a < s - 1 && i.push("."), r++);
		return i.reverse().join("") + (n ? "." + n[1].substr(0, 2) : "")
	},
	_handleInputPrice: function(_this, e){
		var p = $(_this).val(),
			e = $(".js-price-text-view"),
			_form = $(_this).closest("form");
		if(!$Core.util.isEmpty(p)){
			p = p.replaceAll(".","");
			if($("input[name='stock_code']",_form).hasClass("is-valid") && parseInt(p) >= 1000000000) {
				$('button.btn-submit',_form).removeAttr('disabled');	
			}else{
				$('button.btn-submit',_form).attr('disabled',true);	
			}
			if(!$Core.util.isNumber(p)) e.html("");
			var s = $Core.sop.convertNumberToText(p);
			e.html("Tổng giá trị: " + s);
		} else {
			e.html("");
		}
	},
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
	mark_hot: (_this, e) => {
		e.preventDefault();
		var sop_id = $(_this).attr('sop_id'),
			is_hot = $(_this).is(':checked') ? 1 : 0;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=mark_hot', {
			'sop_id' : sop_id,
			'is_hot' : is_hot
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
		});
		return false;
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
			url = "/chuyen-nhuong.html";
		if(params.sop_type != undefined){
			url_request += ("&sop_type="+params.sop_type);
		}
		if(params.project_id != undefined){
			url_request += ("&project="+params.project_id);
		}
		if(params.block_id != undefined){
			url_request += ("&block="+params.block_id);
		}
		if(params.building_ids.length > 0){
			url_request += ("&building="+params.building_ids.toString());
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
		if(params.area_min != "" && params.area_max != ""){
			min = params.area_min;
			//min = min.replace(/\./g, '');
			max = params.area_max;
			//max = max.replace(/\./g, '');
			if(parseInt(max) > 0){
				url_request += ("&area_min="+min);
				url_request += ("&area_max="+max);
			}
		}
		if(!$Core.util.isEmpty(params.user_id)){
			url_request+= ('&user='+params.user_id); 
		}		
		if(params.sort_by !== undefined){
			url_request += ("&sort_by="+params.sort_by);
		}
		if(params.page !== undefined && parseInt(params.page) > 1){
			url_request+= ('&page='+params.page); 
		}
		if(url_request!= ""){	
			url += "?s=search"+url_request;
		}
		
		return url;
	},
	select_price_range: function(_this,e){
		var _form = $(_this).closest("form"),
			price_min = $(_this).data("min"),
			price_max = $(_this).data("max");
		$("input[name=price_min]",_form).val(price_min);
		$("input[name=price_max]",_form).val(price_max);
	},
	select_type: function(_this, e){
		var tp = $(_this).attr('tp'),
			toId = $(_this).attr('toId'),
			$_adata = {'tp':tp};
		if(tp=='radio'){
			var sop_type = $('input[name=sop_type]:checked').val();
		} else {
			var sop_type = $(_this).val(),
				_text = $(_this).attr('title'),
				_group = $(_this).closest('.btn-group'),
				_dropdown = $(_this).closest('.dropdown-menu');
			$('.dropdown-toggle', _group).addClass('is-active');
			$('.select-text-content', _group).attr('title',_text).text(_text);	
		}
		if(sop_type == _SOP_TYPE_LOWFLOOR) {
			$(".js__block-bedroom-list,.js__block-floor-range-list").addClass("d-none");
			$(".js__option-bedroom,.js__option-floor_range").prop("checked",false);
		}else{
			$(".js__block-bedroom-list,.js__block-floor-range-list").removeClass("d-none");
		}
		$_adata['sop_type'] = sop_type;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_project', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if(tp=='radio'){
				if(sop_type === undefined || sop_type==0){
					$('#'+toId+'_group').addClass('d-none');
				}else{
					$('#'+toId+'_group').removeClass('d-none');
				}				
				$('#js__dropdown-block_group,#js__dropdown-building_group').addClass('d-none');
				$('#js__dropdown-block-list,#js__dropdown-building-list').html('');
				$('#'+toId+"-list").html(html);
			}else{
				$('.js__block-project-list').removeClass('d-none');
				$('.js__block-block-list,.js__block-building-list').addClass('d-none');
				$("#js__dropdown-block-list,#js__dropdown-building-list").html("");
				$('.js__block-project-list').find('.select-text-content').text('Dự án');
				$('.js__block-project-list').find('.dropdown-toggle').removeClass("is-active");
				$('#'+toId).html(html);
			}			
		});
		$Core.sop.do_search(_this, e);
	},
	select_project: function(_this, e){
		var toId = $(_this).attr('toId'),
			tp = $(_this).getAttr('tp', 'option'),
			sop_type = $("input[name=sop_type]:checked").val(),
			$_adata = {'tp':tp,'sop_type' : sop_type},
			_modal = $("#js__search-form-modal");
		if(tp=='radio'){
			var project_id = $('input[name=project_id]:checked').val();
			$('input[name=project_id][value='+project_id+']',_modal).prop("checked",true);
		} else {
			var project_id = $(_this).val(),
				_text = $(_this).attr('title'),
				_group = $(_this).closest('.btn-group'),
				_dropdown = $(_this).closest('.dropdown-menu');
			$('.dropdown-toggle', _group).addClass('is-active');
			$('.select-text-content', _group).attr('title',_text).text(_text);	
		}
		$_adata['project_id'] = project_id;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_block', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if(tp=='radio'){
				if(project_id === undefined || project_id==0){
					$('#'+toId+'_group').addClass('d-none');
				}else{
					$('#'+toId+'_group').removeClass('d-none');
				}
				$('#js__dropdown-building_group').addClass('d-none');
				$('#js__dropdown-building-list').html('');
				$('#'+toId+"-list").html(html);
			}else{
				$('.js__block-block-list').removeClass('d-none');
				$('.js__block-building-list').addClass('d-none');
				$("#js__dropdown-building-list").html("");
				$('.js__block-block-list').find('.select-text-content').text('Phân khu');
				$('.js__block-block-list').find('.dropdown-toggle').removeClass("is-active");
				$('#'+toId).html(html);
			}
			
		});
	},
	select_block: function(_this, e){
		var toId = $(_this).attr('toId'),
			tp = $(_this).getAttr('tp', 'option'),
			sop_type = $("input[name=sop_type]:checked").val(),
			$_adata = {'tp':tp,'sop_type' : sop_type};
		if(tp=='radio'){
			var block_id = $('input[name=block_id]:checked').val();
		} else {
			var block_id = $(_this).val(),
				_text = $(_this).attr('title'),
				_group = $(_this).closest('.btn-group'),
				_dropdown = $(_this).closest('.dropdown-menu');
			$('.dropdown-toggle', _group).addClass('is-active');
			$('.select-text-content', _group).attr('title',_text).text(_text);	
		}
		$_adata['block_id'] = block_id;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_building', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if(tp=='radio'){
				if(block_id === undefined || block_id==0){
					$('#'+toId+'_group').addClass('d-none');
				}else{
					$('#'+toId+'_group').removeClass('d-none');
				}
				$('#'+toId+"-list").html(html);
			}else{
				$('.js__block-building-list').removeClass('d-none');
				$('.js__block-building-list').find('.select-text-content').text('Tòa/dãy');
				$('.js__block-building-list').find('.dropdown-toggle').removeClass("is-active");
				$('#'+toId).html(html);
			}
		});
	},
	do_search: function(_this, e){
		e.preventDefault();
		var _tp = $(_this).attr('tp');
		if(_tp == '_dropdown'){
			var $_adata = {},
				_group = $(_this).closest('.btn-group'),
				_dropdown = $('.dropdown-menu', _group),
				_text = $('.select-text-content', _group).attr('text');
			if($('.form-check-input', _dropdown).length){
				let countItem = 0;
				$('.form-check-input:checked', _dropdown).each((_i, _elem) => {
					++countItem;
				});
				if(countItem > 0){
					$('.dropdown-toggle', _group).addClass('is-active');
					$('.select-text-content', _group).attr('title',_text + `(`+countItem+`)`).text(_text + `(`+countItem+`)`);
				} else {
					$('.dropdown-toggle', _group).removeClass('is-active');
					$('.select-text-content', _group).attr('title',"").text(_text);
				}
			} else {
				$('.dropdown-toggle', _group).addClass('is-active');
			}
			$('.dropdown-toggle', _group).dropdown('toggle');
		} else {
			
		}
		if($('#js__search-form-modal').length > 0){
			$('#js__search-form-modal').modal("hide");	
		}
		$Core.sop.reload();
		return false;
	}, 
	clear_search: function(_this, e){
		e.preventDefault();
		var _tp = $(_this).attr('tp');
		if(_tp == '_dropdown'){
			var _group = $(_this).closest('.btn-group'),
				_dropdown = $(_this).closest('.dropdown-menu'),
				_text = $('.select-text-content', _group).attr('text');
			if(_group.hasClass('js__block-block-list')){
				if($('.form-check-input', _dropdown).length){
					$('.form-check-input', _dropdown).prop('checked', false);
				}
			} else if(_group.hasClass('js__block-price-list')){
				if($('input[name="price_min"]', _group).length){
					$('input[name="price_min"]', _group).val(0);
				}
				if($('input[name="price_max"]', _group).length){
					$('input[name="price_max"]', _group).val(50000000000);
				}
				$(".item_price").removeClass("active");
			} else if(_group.hasClass('js__block-area-list')){
				if($('input[name="area_min"]', _group).length){
					$('input[name="area_min"]', _group).val(0);
				}
				if($('input[name="area_max"]', _group).length){
					$('input[name="area_max"]', _group).val(500);
				}
				$(".item_area").removeClass("active");
			} else {
				if($('.form-check-input', _dropdown).length){
					$('.form-check-input', _dropdown).prop('checked', false);
				}
			}
			$('.dropdown-toggle', _group).dropdown('toggle');
			$('.dropdown-toggle', _group).removeClass('is-active');
			$('.select-text-content', _group).attr('title',"").text(_text);	
			if(_group.hasClass("js__block-sop_type-list")) {
				$('.js__block-project-list,.js__block-block-list,.js__block-building-list').addClass('d-none');
				$('.js__block-project-list,.js__block-block-list,.js__block-building-list').find(".dropdown-toggle").removeClass("is-active");
				$('#js__dropdown-project-list,#js__dropdown-block-list,#js__dropdown-building-list').html('');
			}else if(_group.hasClass("js__block-project-list")) {
				$('.js__block-block-list,.js__block-building-list').addClass('d-none');
				$('.js__block-block-list,.js__block-building-list').find(".dropdown-toggle").removeClass("is-active");
				$('#js__dropdown-block-list,#js__dropdown-building-list').html('');
			}else if(_group.hasClass("js__block-block-list")) {
				$('.js__block-building-list').addClass('d-none');
				$('.js__block-building-list').find(".dropdown-toggle").removeClass("is-active");
				$('#js__dropdown-building-list').html('');
			}
			
			$Core.sop.reload({});
		} else {
			var _form = $(_this).closest(".modal-content");
			if($('.js__option-type:checked').length){
				$('.js__option-type:checked').prop('checked', false).trigger('change');
			}
			if($('.js__option-project:checked').length){
				$('.js__option-project:checked').prop('checked', false).trigger('change');
			}
			if($('.js__option-block:checked').length){
				$('.js__option-block:checked').prop('checked', false).trigger('change');
			}
			if($('.js__option-building:checked,.js__option-bedroom:checked').length){
				$('.js__option-building:checked,.js__option-bedroom:checked').each((_i, _elem) => {
					$(_elem).prop('checked', false);
				});
			}
			if($('.js__option-home_direction:checked,.js__option-floor_range:checked').length){
				$('.js__option-home_direction:checked,.js__option-floor_range:checked').each((_i, _elem) => {
					$(_elem).prop('checked', false);
				});
			}
			$('input[name=price_min]',_form).val(0);
			$('input[name=price_max]',_form).val(50000000000);
			$("input[name=price_range]").prop("checked",false);
			$(".slider-container .rs-container",_form).remove();
			if($('#js__search-form-modal').length > 0){					
				$('#js__search-form-modal').on('hidden.bs.modal', function(){
					rsSlider.destroy();
				});
			}
			$Core.sop.reload({}, "append", false);
		}
		return false;
	},
	list_sop: function(options, action="append", full_url=true ){
		var $_adata = options || {},
			www = $(window).outerWidth(false),
			return_url = window.location.href,
			sop_type = $('.js__option-type:checked').val(),
			project_id = $('.js__option-project:checked').val(),
			block_id = $('.js__option-block:checked').val(),
			source_id = $('.js__option-source:checked').val(),  
			building_ids = $Core.util.getCheckBoxValueByClass('js__option-building'),
			status_ids = $Core.util.getCheckBoxValueByClass('js__option-status'),
			bedroom_ids = $Core.util.getCheckBoxValueByClass('js__option-bedroom'),
			home_direction_ids = $Core.util.getCheckBoxValueByClass('js__option-home_direction'),
			floor_range = $Core.util.getCheckBoxValueByClass('js__option-floor_range'),
			sort_by = $('.zHXOaDjwDk.active').attr('holderG');
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		// console.log(return_url);
		$_adata['www'] = www;
		$_adata['sort_by'] = sort_by;
		$_adata['sop_type'] = sop_type;
		$_adata['project_id'] = project_id;
		$_adata['block_id'] = block_id;
		$_adata['source_id'] = source_id;
		$_adata['status_ids'] = status_ids;
		$_adata['building_ids'] = building_ids;
		$_adata['bedroom_ids'] = bedroom_ids;
		$_adata['floor_range'] = floor_range;
		$_adata['return_url'] = return_url;
		$_adata['home_direction_ids'] = home_direction_ids;
		$Core.util.toggleIndicatior(1);
		var url = $Core.sop.get_url_search($_adata);
        $.post(PCMS_URL+'/cn/list.cfg', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			full_url && $Core.util.popstate(url);
			$('.holder_sop').html(respJson.html);
			$('.total-stock').text(respJson.total_record);
			$Core.sop.init_slide();
			$_document.on('click', '.dropdown-button', function (event) {
				 event.stopPropagation(); 
				let button = $(this),
					offset = button.offset(),
					dropdown = button.siblings('.dropdown-menu').clone();
				// Xóa dropdown cũ trước khi mở cái mới
				$('.dropdown-menu-floating').remove();
				// Thêm vào body và hiển thị tạm thời để lấy kích thước chính xác
				dropdown.addClass('dropdown-menu-floating')
					.css({ position: 'absolute', display: 'block'})
					.appendTo('body');
				// Lấy kích thước dropdown
				let dropdownWidth = dropdown.outerWidth(),
					buttonWidth = button.outerWidth();
				// Căn dropdown về bên phải của nút
				dropdown.css({
					top: offset.top + button.outerHeight(), // Ngay dưới nút
					left: offset.left + buttonWidth - dropdownWidth, // Căn phải
					zIndex: 1000
				});
				// Đóng dropdown khi click ra ngoài
				$_document.on('click', function () {
					$('.dropdown-menu-floating').remove();
				});
			});
			if(parseInt(respJson.total_page) > 1) {
				$('#pagination-container').pagination({
					items: parseInt(respJson.total_page),
					itemsOnPage: respJson.perPage,
					prevText: "&laquo;",
					nextText: "&raquo;",					
					displayedPages: 5,
					edges: 0,
					currentPage : parseInt(respJson.current_page),
					onPageClick: function (pageNumber) {
						$_adata['page'] = pageNumber;
						$('input[name="page"]').val(pageNumber);
						$Core.sop.list_sop($_adata,"pagination")
					}
				});
			}else{
				$('#pagination-container').html("");
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
		$('input[name="page"]').val(1);
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
	update_click : function(sop_id,type){
		$.post(PCMS_URL+'/cn/update-click.cfg', {
			'sop_id' : sop_id,
			'type' : type
		}, function(html){
			// console.log('Success !')
		});
	},
	alert: function(_this, e){
		e.preventDefault();
		$Core.swal.info("Thông báo", "Bạn cần đăng nhập để xem thông tin người rao bán");
		return false;
	},
	close_pop: function(_this, e){
		e.preventDefault();
		$Core.util.popstate(current_page);
		if($(document).find(".modal.show").length > 0){
			setTimeout(function(){
				$("body").addClass("modal-open");
			},15);
		}
		return false;
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
	check_stock_code: function(_this, e){
		var stock_code = $(_this).val(),
			stock_code_hide = $(_this).data("stock_code_hide"),
			_form = $(_this).closest("form"),
			sop_id = $("input[name='sop_id']").val(),
			sop_type = $("input[name='sop_type']:checked").val();
		if(!$Core.util.isEmpty(stock_code)){
				$Core.util.toggleIndicatior(1);
				$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=check_stock_code', {
					'stock_code' : stock_code,
					'sop_id' : sop_id,
					'sop_type' : sop_type
				}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$("input[name=sop_type]",_form).prop("disabled",false);
					if(respJson.msg.indexOf('_invalid') >= 0){
						$('input[name=stock_id]').val(0);
						var price = $("input[name='price']",_form).val().replaceAll(".","");
						if(parseInt(price) >= 1000000000) {
							$('button.btn-submit',_form).removeAttr('disabled')
						}					
						$(_this).addClass('is-valid').removeClass('is-invalid');
						$('.bs-webui-popover').webuiPopover('hideAll');	
					}else if(respJson.msg.indexOf('_exist') >= 0){
						$Core.swal.link('Thông báo', "Mã căn "+stock_code+" đã tồn tại. Hãy đi đến chỉnh sửa tin đăng!",respJson.url);
						$('button.btn-submit',_form).attr('disabled','disabled')
						$(_this).addClass('is-invalid',_form).removeClass('is-valid');
					} else {
						$('input[name=stock_id]').val(respJson.stock_id);
						var price = $("input[name='price']",_form).val().replaceAll(".","");
						if(parseInt(price) >= 1000000000) {
							$('button.btn-submit',_form).removeAttr('disabled')
						}		
						$(_this).addClass('is-valid').removeClass('is-invalid');						
						$('.bs-webui-popover').webuiPopover('hideAll');	
						if(stock_code_hide == 1){
							$("select[name='project_id']",_form).val(respJson.project_id);
							$("select[name='type_villa']",_form).val(respJson.sop_type);
							$("#sop_type"+respJson.sop_type,_form).trigger("click");
							$Core.sop.loadStockCode(_this, event,{"project_id":respJson.project_id,"block_id":respJson.block_id,"field":"block_id",'sop_type':respJson.sop_type});
							$Core.sop.loadStockCode(_this, event,{"block_id":respJson.block_id,"building_id":respJson.building_id,"field":"building_id",'sop_type':respJson.sop_type});
							$("input[name='floor_range']",_form).val(respJson.floor);
							$("input[name='DT_TT']",_form).val(respJson.DT_TT);
							$("select[name='bedroom_id']",_form).val(respJson.bedroom_id);
							$("select[name='home_direction_id']",_form).val(respJson.home_direction_id);
							$("input[name='code']",_form).val(respJson.code);
						}
						$Core.sop.loadHidecode({sop_type:respJson.sop_type});
						$("input[name=sop_type]:not(:checked)",_form).prop("disabled",true);
					}
				}, "json");
			
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
	loadStockCode : function(_this,e,options) {
		e.preventDefault();
		var $_adata = options || {};
		var _form = $(_this).closest("form");
		if(Object.keys($_adata).length == 0) {
			var project_id = $("select[name='project_id']",_form).val();
			var block_id = $("select[name='block_id']",_form).val();
			var building_id = $("select[name='building_id']",_form).val();
			var floor_range = $("select[name='floor_range']",_form).val();
			var stock_code = $("select[name='stock_code']",_form).val();
			var sop_type = $("input[name='sop_type']:checked",_form).val();
			var field = $(_this).data("field");
			$_adata['project_id'] = project_id;
			$_adata['block_id'] = block_id;
			$_adata['building_id'] = building_id;
			$_adata['floor_range'] = floor_range;
			$_adata['stock_code'] = stock_code;
			$_adata['sop_type'] = sop_type;
			$_adata['field'] = field;
		}		
		$Core.util.toggleIndicatior(1);
		if($(_this).attr("name") == "sop_type") {
			if(sop_type == high_level) {
				$(".box_floor_range",_form).removeClass("d-none");
			}else{
				$(".box_floor_range",_form).addClass("d-none");
				$(".box_floor_range",_form).find("input").val('');
			}
			$("select[name=building_id]",_form).html('');
		}	
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadStockCode', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(field == "block_id"){
				$("select[name='building_id']",_form).html("<option value=''>Tòa/Dãy</option>");
			}
			if(field == "building_id"){
				$("select[name='floor_range']",_form).html("<option value=''>Tầng</option>");
			}
			$("select[name='"+$_adata['field']+"']",_form).html(respJson.html);
		},"json");
	},
	addStockCode : function(_this,e) {
		e.preventDefault();
		var _form = $(_this).closest("form");
		var stock_code = $("input[name='stockCode']",_form).val(),
			block_id = $("select[name='block_id']",_form).val(),
			building_id = $("select[name='building_id']",_form).val(),
			floor_range = $("select[name='floor_range']",_form).val();
		var check = 1;
		$("select.required,input.required",_form).each(function(index, elm){
			if($(elm).val() == ""){
				$(elm).focus();
				check = 0;
				return false;
			}
		});
		if(check == 1){
			$_adata = {
				"block_id" : block_id,
				"building_id" 	: building_id,
				"floor_range"	: floor_range,
				"stock_code" 	: stock_code,
				"action"	 	: "_SHOW_STOCK_CODE",
			};
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadStockCode', $_adata, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(!respJson.result) {
					alertify.error("Không có mã căn phù hợp");
					$("#stock_code").val('');
					return false;
				}else {
					$("#stock_code").val(respJson.ms_code).change();
				}
			},"json");
		}
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
	share: function(_this, e){
		e.preventDefault();
		var sop_id = $(_this).data('sop_id'),
			$_adata = {'sop_id' : sop_id};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=share', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				ZaloSocialSDK.reload();
				$(".sharer-icons[uid="+respJson.uid+"]").each((_i, _elem) => {
					var link_share = $(_elem).data('link_share'),
						title_share = $(_elem).data('title_share');
					$(_elem).empty().sharer({
						networks: ["facebook"],
						title : respJson.title_share,
						url : respJson.link_share
					});
				});
			});
		},"json");
		return false;
	},
	search_sidebar : function (_this,event) {
		var min = $(_this).data("min"),
			max = $(_this).data("max"),
			_target = $(_this).data("target"),
			id = $(_this).data("id");
		$('input[name='+_target+'_min]').val(min);
		$('input[name='+_target+'_max]').val(max);
		$(".item_"+_target).removeClass("active");
		$(".item_"+_target+"_"+id).addClass("active");
		$('.dropdown-toggle_'+_target).closest(".js__block-"+_target+"-list").find(".btn_do_search").trigger("click");
		
	},
	loadSoptype : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			sop_type = $(_this).val(),
			project_id = $("select[name='project_id']",_form).val();
		$Core.sop.loadStockCode(_this, event,{"project_id":project_id,"field":"project_id",'sop_type':sop_type});				
		$Core.sop.loadStockCode(_this, event,{"project_id":project_id,"block_id":0,"field":"block_id",'sop_type':sop_type});
		if(sop_type == high_level) {
			$(".box_high_level",_form).removeClass("d-none");
			$(".box_lowfloor",_form).addClass("d-none");
		}else{
			$(".box_high_level",_form).addClass("d-none");
			$(".box_lowfloor",_form).removeClass("d-none");
			$(".box_floor_range,.box_bedroom,.box_building,.box_block",_form).find("select,input").val('');
		}
		$Core.sop.loadHidecode({sop_type:sop_type});
	},
	loadHidecode: function(options){
		var $_adata = options || {};
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadHideCode', $_adata, function(respJson){
			if(respJson.result) {
				$(".box_hideCode").html(respJson.html);	
			}			
			return false;
		},"json");
	},
	loadNumberRadio : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			field = $(_this).data("field"),
			number = parseInt($(_this).val());
		if(number > 5) {
			$("input[name="+field+"]",_form).attr("disabled",true).prop("checked",false);
			$(".box_number_"+field,_form).find(".lbl_text").addClass("cursor-not-allowed");
		}else {
			$("input[name="+field+"]",_form).removeAttr("disabled");
			$(".box_number_"+field,_form).find(".lbl_text").removeClass("cursor-not-allowed");
			$(_this).val("");
			$("input[name="+field+"][value='"+number+"']",_form).prop("checked",true);
		}
	},
	validate : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			field = $(_this).data("field"),
			_validated = 0;
		if($('select.required,input.required',_form).length){
			$('select.required,input.required',_form).each((_i,_elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).trigger("focus");
					alertify.error("Các trường có dấu * không được để trống");
					if($(_elem).hasClass("iso-select2")) {
						$(_elem).select2("open"); 
					}
					return false;
				}
			});
		}
		if(_validated > 0) {
			return false;
		}
		if($("input[name=sop_type]:checked",_form).val() == high_level && $Core.util.isEmpty($("select[name='bedroom_id']",_form).val())) {
			_validated++;		
			$("select[name='bedroom_id']",_form).select2("open"); 
			alertify.error("Các trường có dấu * không được để trống");	
			return false;
		}
		if(_validated > 0) {
			return false;
		}
		if(!$("input[name='price_negotiable']",_form).is(":checked")) {
			var inp_price = $("input[name=price]",_form),
				price = inp_price.val().replaceAll(".","");
			if(parseInt(price) < 1000000000) {
				_validated++;
				inp_price.trigger("focus");
				alertify.error("Giá bán tối thiểu là 1 tỷ");
				return false;
			}
		}
		if(_validated == 0) {
			_form.submit();
		}
	},
	priceNegotiable : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			inp_price = $("input[name=price]",_form),
			inp_stock_code = $("input[name='stock_code']",_form);
		if($(_this).is(":checked")) {
			inp_price.prop("disabled",true);
			if($("input[name='stock_code']",_form).hasClass("is-valid")) {
				$('button.btn-submit',_form).prop('disabled',false);
			}else{
				$('button.btn-submit',_form).prop("disabled",true);
			}			
			$(".js-price-text-view").html(); 
		}else{
			inp_price.prop("disabled",false);
			var price = inp_price.val().replaceAll(".","");
			if($("input[name='stock_code']",_form).hasClass("is-valid") && parseInt(price) >= 1000000000) {
				$('button.btn-submit',_form).prop('disabled',false);
			}else{
				$('button.btn-submit',_form).prop("disabled",true);
			}
		}
	},
	do_crawl: (_this, e) => {
		e.preventDefault();
		$Core.messager.confirm('Message', 'Bạn chắc chắn muốn thực hiện thao tác này', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=do_crawl', {}, function(respJson){
				$Core.util.toggleIndicatior(0);
				setTimeout(() => {
					window.location.reload(true);
				}, 3000);
			},"json");
		});
		return false;
	},
	do_tt_crawl : (_this, e) => {
		e.preventDefault();
		$Core.messager.confirm('Message', 'Bạn chắc chắn muốn thực hiện thao tác này', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=do_tt_crawl', {}, function(respJson){
				$Core.util.toggleIndicatior(0);
				setTimeout(() => {
					window.location.reload(true);
				}, 3000);
			},"json");
		});
		return false;
	},
	gen_content: (_this, e) => {
		e.preventDefault();
		var sop_id = $(_this).attr('sop_id');
		$Core.messager.confirm('Message', 'Bạn chắc chắn muốn thực hiện thao tác này', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=gen_content', {
				'sop_id' : sop_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
			},"json");
		});
		return false;
	},
	editInlineField : (_this, e) => {
		e.preventDefault();
		var p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_edit_inline_field', {
			'p_id' : p_id,
			'p_field' : p_field
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$(_this).closest('.InputCRMHandler').html(html);
		});
		return false;
	},
	save_edit_inline_field: function (_this, e){
		e.preventDefault();
		var _more = {},
			p_id= $(_this).attr('p_id'),
			p_field= $(_this).attr('p_field'),
			p_cell = $(_this).closest('.InputCRMHandler');
		//alert(p_value); return false;
		var p_value = $('.edit_sop_field_'+p_field+'_'+p_id).val();
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_edit_inline_field', $.extend(_more, {
			'p_id' : p_id,
			'p_field' : p_field,
			'p_value' : p_value,
			'p_action' : '_save'
		}), function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_error_code') >= 0){
				$Core.messager.alert('Thông báo', 'Mã nhân viên đã tồn tại !');
			} else if(html.indexOf('_error_email') >= 0){
				$Core.messager.alert('Thông báo', 'Địa chỉ email này đã tồn tại !');
			} else {
				p_cell.html(html);
			}
		});
		return false;
	},
	cancel_edit_inline_field: function(_this, e){
		e.preventDefault();
		var p_id= $(_this).attr('p_id'),
			p_field= $(_this).attr('p_field'),
			p_cell = $(_this).closest('.InputCRMHandler');
		p_cell.html('<img src="'+URL_IMAGES+'/ripple-loading.svg" />');
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_edit_inline_field', {
			'p_id' : p_id,
			'p_field' : p_field,
			'p_action' : '_cancel'
		}, function(html){
			p_cell.html(html);
		});
		return false;
	},
	run_search: (_this, e) => {
		if($(_this).hasClass('slbStockType')){
			var stock_type = $(_this).val(),
				project_id = $('.slbProject').val();
			$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=get_select_options', {
				'holderG' : 'project',
				'project_id' : project_id,
				'stock_type' : stock_type 
			}, function(respJson){
				if(stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
					$('.col\\:finish_status').addClass('d-none');
				} else {
					$('.col\\:finish_status').removeClass('d-none');
				}
				$('.slbBlock').html(respJson.html_blocks).multiselect('rebuild');
				$('.slbProject').html(respJson.html_options).multiselect('rebuild');
			}, 'json');
		} else if($(_this).hasClass('slbProject')){
			var stock_type = $('.slbStockType').val();
			$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=get_select_options', {
				'holderG' : 'block',
				'stock_type' : stock_type,
				'project_id' : $(_this).val()
			}, function(respJson){
				$('.slbBlock').html(respJson.html_options).multiselect('rebuild');
				$('.slbBuilding').html('<option value="0">Toà nhà/Dãy</option>').multiselect('rebuild');
			}, 'json');
		} else if($(_this).hasClass('slbBlock')){
			var stock_type = $('.slbStockType').val();
			$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=get_select_options', {
				'holderG' : 'building',
				'stock_type' : stock_type,
				'block_id' : $(_this).val()
			}, function(respJson){
				$('.slbBuilding').html(respJson.html_options).multiselect('rebuild');
			}, 'json');
		}
		$Core.sop.list_telesale({});
	},
	load_contact: (telesale_id, options) => {
		var $_adata = options || {};
		$_adata['telesale_id'] = telesale_id;
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_contact', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(`.holder_contact-${telesale_id}`).html(respJson.html);
		}, 'json');
	},
	load_reminders: (telesale_id, options) => {
		var $_adata = options || {};
		$_adata['telesale_id'] = telesale_id;
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_reminders', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(`.holder_reminders-${telesale_id}`).html(respJson.html);
		}, 'json');
	},
	open_reminder: (_this, e) => {
		e.preventDefault();
		var reminder_id = $(_this).getAttr('reminder_id', 0),
			telesale_id = $(_this).attr('telesale_id');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=open_reminder', {
			'reminder_id' : reminder_id,
			'telesale_id' : telesale_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	save_reminder: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			reminder_id = $(_this).attr('reminder_id'),
			telesale_id = $(_this).attr('telesale_id');
		
		var _validated = 0;
		if($("select.required,input.required,textarea.required",_form).length){
			$("select.required,input.required,textarea.required",_form).each((index, elm) => {
				if($Core.util.isEmpty($(elm).val())){
					_validated ++;
					$(elm).focus();
					return false;
				}
			});	
		}
		if(_validated == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				method: "POST",
				url: `${PCMS_URL}/index.php?mod=${MOD}&act=save_reminder`,
				data: {'reminder_id':reminder_id, 'telesale_id':telesale_id},
				dataType : 'html',
				success: (respJson) => {
					_form.resetForm();
					$Core.util.toggleIndicatior(0);
					$Core.sop.load_reminders(telesale_id, {});
					$('.btn-close', _form).trigger('click');
				}
			});
		}
		return false;
	},
	delete_reminder: (_this, e) => {
		e.preventDefault();
		var reminder_id = $(_this).attr('reminder_id'),
			telesale_id = $(_this).attr('telesale_id');
		$Core.messager.confirm('Thông báo', 'Bạn chắc chắn muốn xoá không?', function(){
			$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=delete_reminder`, {
				'reminder_id' : reminder_id,
				'telesale_id' : telesale_id
			}, function(){
				$Core.util.toggleIndicatior(0);
				$Core.sop.load_reminders(telesale_id, {});
			});
		});
		return false;
	},
	open_contact: (_this, e) => {
		e.preventDefault();
		var contact_id = $(_this).attr('contact_id'),
			telesale_id = $(_this).attr('telesale_id');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=open_contact', {
			'contact_id' : contact_id,
			'telesale_id' : telesale_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	save_contact: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			contact_id = $(_this).attr('contact_id'),
			telesale_id = $(_this).attr('telesale_id');
		
		var _validated = 0;
		if($("textarea.required,input.required",_form).length){
			$("textarea.required,input.required",_form).each((index, elm) => {
				if($Core.util.isEmpty($(elm).val())){
					_validated ++;
					$(elm).focus();
					return false;
				}
			});	
		}
		if(_validated == 0){
			_form.ajaxSubmit({
				method: "POST",
				url: `${PCMS_URL}/index.php?mod=${MOD}&act=save_contact`,
				data: {'contact_id':contact_id, 'telesale_id':telesale_id},
				dataType : 'html',
				success: (respJson) => {
					_form.resetForm();
					$Core.util.toggleIndicatior(0);
					$Core.sop.load_contact(telesale_id, {});
					$('[data-bs-dismiss="modal"]', _form).trigger('click');
				}
			});
		}
		return false;
	},
	save_setting: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form');
			
		if(_validated == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				method: "POST",
				url: `${PCMS_URL}/index.php?mod=${MOD}&act=save_setting`,
				dataType : 'html',
				success: (respJson) => {
					$Core.util.toggleIndicatior(0);
				}
			});
		}
		return false;
	},
	formatCash: (n) => {
		if (typeof n === 'string') {
			n = Number(n.replace(/\./g, ''));
		}
		const units = [
			{ value: 1e9,  label: 'tỷ' },
			{ value: 1e6,  label: 'triệu' },
			{ value: 1e3,  label: 'nghìn' }
		];
		for (let unit of units) {
			if (n >= unit.value) {
				let result = n / unit.value;
				return `${parseFloat(result.toFixed(3))}${unit.label}`;
			}
		}
		return n.toString();
	},
	close_dropdown: (_this, e) => {
		e.preventDefault();
		var _field = $(_this).data('field'),
			_dropdown = $(`.js__dropdown-${_field}`);
		$('.dropdown-toggle', _dropdown).dropdown('toggle');
		return false;
	},
	set_dropdown: (_this, e) => {
		e.preventDefault();
		var _field = $(_this).data('field'),
			_dropdown = $(`.js__dropdown-${_field}`),
			_min = $(`input[name=${_field}_min]`).val(),
			_max = $(`input[name=${_field}_max]`).val();
		if(!$Core.util.isEmpty(_min) || !$Core.util.isEmpty(_max)){
			var _min = $Core.util.toNumber(_min),
				_min_format = $Core.sop.formatCash(_min),
				_max = $Core.util.toNumber(_max),
				_max_format = $Core.sop.formatCash(_max);	
			$(`.js__dropdown-${_field}-text`).text(`${_min_format}-${_max_format}`);
		} else {
			$(`.js__dropdown-${_field}-text`).text('Khoảng giá');
		}
		$('.dropdown-toggle', _dropdown).dropdown('toggle');
		$Core.sop.list_telesale({});
		return false;
	},
	select_project : (_this, e) => {
		e.preventDefault();
		$('.nav-link.active').removeClass('active');
		$(_this).addClass('active');
		$Core.sop.list_telesale({});
		return false;
	},
	link_map: (_this, e) => {
		e.preventDefault();
		var link_map = $('.tab_project-link.active').attr('link_map');
		window.location.href = link_map.toString();
		return false;
	},
	tele_sort: (_this, e) => {
		e.preventDefault();
		var _curr_by = $(_this).attr('field'),
			_sort_by = $('input[name=sort_by]').val();
		if(_curr_by != _sort_by){
			$(`.sortClick[field=${_sort_by}]`).removeClass('asc desc');
		}
		$('input[name=sort_by]').val(_curr_by);
		if($(_this).hasClass('desc')){
			$(_this).removeClass('desc');
			$('input[name=sort_type]').val('DESC');
			$('input[name=sort_by]').val('reg_date');
		} else if($(_this).hasClass('asc')){
			$(_this).removeClass('asc').addClass('desc');
			$('input[name=sort_type]').val('DESC');
		} else if(!$(_this).hasClass('asc') && !$(_this).hasClass('desc')){
			$(_this).addClass('asc');
			$('input[name=sort_type]').val('ASC');
		}
		$Core.sop.list_telesale({});
		return false;
	},
	list_telesale: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		var sort_by = $('input[name=sort_by]').val(),
			sort_type = $('input[name=sort_type]').val();
		$_adata['sort_by'] = sort_by;
		$_adata['sort_type'] = sort_type;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=list_telesale', $_adata, (respJson) => {
			$Core.util.toggleIndicatior(0);
			$('#'+'holder_telesale').html(respJson.html);
			$('#'+'sum').html(respJson.html_blocks);
			$('.total_record').html(respJson.total_record);	
			if(respJson.stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
				$('.col\\:finish_status').addClass('d-none');
			} else {
				$('.col\\:finish_status').removeClass('d-none');
			}
			$_easyUI('#pager_telesale').pagination({
				total: respJson.total_record,
				pageSize: respJson.per_page,
				onRefresh: function(pageNumber,pageSize){
					$Core.sop.list_telesale({'page':pageNumber, 'per_page':pageSize});
				}, onSelectPage : function(pageNumber,pageSize){
					$Core.sop.list_telesale({'page':pageNumber, 'per_page':pageSize});
				}
			});
		}, 'json');
	},
	open_stock: (_this, e) => {
		e.preventDefault();
		var stock_id = $(_this).getAttr('stock_id', 0),
			stock_code = $(_this).getAttr('stock_code', 0),
			telesale_id = $(_this).getAttr('telesale_id', 0);
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_stock', {
			'stock_id' : stock_id,
			'stock_code' : stock_code,
			'telesale_id' : telesale_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			$Core.helper.load_list_notes(telesale_id, 'Telesale', {});
			$Core.sop.load_contact(telesale_id, {});
			$Core.sop.load_reminders(telesale_id, {});
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				var _modal = $(this);
				if($('.slideshow',_modal).length && parseInt(respJson.total_images) > 1){
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
	render_back_button: (project_id) => {
		if (document.getElementById("backButton")) return;
		var button = L.DomUtil.create('button', 'btn leaflet-back');
		button.id = "backButton";
		button.innerText = 'Quay lại';
		button.onclick = function () {
			$('.loading').stop('false', true).fadeIn();
			currentImage.setOpacity(0.3);
			drawnItems.clearLayers();
			map.removeLayer(drawnItems);
			map.removeLayer(currentImage);
			map.removeControl(currentMiniMap);
			var imgHome = new Image();
			imgHome.onload = () => { 
				var natWidth = imgHome.naturalWidth,
					natHeight = imgHome.naturalHeight,
					imageBounds = [[0,0], [natHeight, natWidth]],
					maxBounds = [[0,0], [natHeight+50, natWidth+50]];
				currentImage = L.imageOverlay(image_maps.project, imageBounds).addTo(map);
				map.fitBounds(imageBounds, {animate:false, duration:0});
				map.setMaxBounds(imageBounds); 
				currentImage.setOpacity(1);
				currentMiniMap = new L.Control.MiniMap(L.imageOverlay(image_maps.project, imageBounds),{
					toggleDisplay: true, 
					position: 'bottomleft',
					zoomLevelOffset: -1,
					width: 120,
					height: 80,
				}).addTo(map);
				$('.loading').stop('false', true).fadeOut();
				$Core.sop.get_shapes(project_id, {}, drawnItems);
			};
			imgHome.src = image_maps.project;
			map.addLayer(drawnItems);
			document.getElementById("backButton").remove();
			
		};
		var control = L.control({ position: 'topleft' });
		control.onAdd = function () { 
			return button; 
		};
		control.addTo(map);
	},
	get_shapes: (project_id, options, drawnItems) => {
		var $_adata = options || {};
		$_adata['project_id'] = project_id;
		$.post(`${path_ajax_script}/index.php?mod=${MOD}&act=get_shapes`, $_adata, (respJson) => {
			if(respJson.msg.indexOf('_success') >= 0){
				$('#'+'sum').html(respJson.html_blocks);
				$('.total_record').text(respJson.total_record);
				drawnItems.clearLayers();
				respJson.shapes.forEach(function(shape) {
					var layer, coordinates = shape.coordinates;
					if(respJson.holderG == 'block'){
						var style = {weight:2, fillOpacity : 0.5};
					} else {
						var style = {weight:1, 
							fillOpacity : 0.7, 
							color: 'rgb(177,2,2)', 
							fillColor : 'rgb(177,2,2)', 
							dashArray: '8,4'
						};
						if(shape.status_id == _TELESALE_STATUS_SOLD_ID){
							var style = {weight:0, 
								fillOpacity : 0.7, 
								color: 'rgb(90,50,134)', 
								fillColor : 'rgb(90,50,134)', 
								dashArray: '8,4'
							};
						} else if(shape.status_id == _TELESALE_STATUS_CHECKED_ID) {
							var style = {weight:0, 
								fillOpacity : 0.7, 
								color: 'rgb(17,115,75)', 
								fillColor : 'rgb(17,115,75)', 
								dashArray: '8,4'
							};
						}
					}
					if (shape.shape_type === 'polygon') {
						layer = L.polygon(coordinates, style);
						layer.setStyle({ className: 'animated-border' });
					} else if (shape.shape_type === 'rectangle') {
						layer = L.rectangle(coordinates, style);
						layer.setStyle({ className: 'animated-border' });
					}
					if(respJson.holderG == 'block'){
						layer.bindTooltip(`Phân khu ${shape.name}`, {
							permanent: (deviceType=='phone' ? false : false),
							direction: 'center',
							className : 'leaflet-tooltip'
						});
						layer.on('mouseover',function () {
							this.setStyle({ fillColor: '#265AA4', fillOpacity: 0.7 });
						});
						layer.on('mouseout', function() {
							this.setStyle({ fillColor: '#265AA4', fillOpacity: 0.5 });
						});
					} else {
						layer.bindTooltip(`${shape.stock_code}`, {
							permanent: false,
							direction: 'center',
							className : 'leaflet-tooltip'
						});
					}
					layer.on('click', (e) => {
						if(respJson.holderG == 'block'){
							$('.loading').stop('false', true).fadeIn();
							currentImage.setOpacity(0.3);
							drawnItems.clearLayers();
							map.removeLayer(drawnItems);
							map.removeLayer(currentImage);
							map.removeControl(currentMiniMap);
							var imgDetail = new Image();
							imgDetail.onload = () => { 
								var natWidth = imgDetail.naturalWidth,
									natHeight = imgDetail.naturalHeight,
									imageBounds = [[0,0], [natHeight, natWidth]],
									maxBounds = [[-50,-50], [natHeight+50, natWidth+50]];  
								currentImage = L.imageOverlay(image_maps[shape.block_id], imageBounds).addTo(map);
								map.fitBounds(imageBounds, {animate: false, duration: 0 });
								//map.flyToBounds(imageBounds, {animate: false, duration: 0 });
								map.setMaxBounds(imageBounds); 
								currentImage.setOpacity(1);
								currentMiniMap = new L.Control.MiniMap(L.imageOverlay(image_maps[shape.block_id], imageBounds),{
									toggleDisplay: true, 
									position: 'bottomleft',
									zoomLevelOffset: -1,
									width: 120,
									height: 80,
								}).addTo(map);
								$('.loading').stop('false', true).fadeOut();
								$Core.sop.get_shapes(project_id, {'block_id' : shape.block_id}, drawnItems);
							};
							imgDetail.src = image_maps[shape.block_id];
							map.addLayer(drawnItems);
							$Core.sop.render_back_button(project_id);
						} else {
							if(!$Core.util.isEmpty(shape.stock_id)){
								$Core.helper.open_stock(shape.stock_id);
							}
						}
					});
					layer.holderG = respJson.holderG;
					layer.block_id = shape.block_id;
					drawnItems.addLayer(layer);
				});
			}
		}, 'json');
	},
});