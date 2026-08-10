$(function(){
	$Core.leasing.init();
	$(window).scroll(function(){
		if(isScrolledIntoView('#showmorethisresult') && $Core.leasing.alreadyScroll == 0){
			$Core.leasing.alreadyScroll = 1;
			$('.showmorethisresult').removeClass('d-none').trigger('click');
		}
	});
	if($('.leasing__checkbox-radio').length){
		$('.leasing__checkbox-radio').each((_i, _elem) => {
			var total_checked = 0,
				ref_id = $(_elem).attr('ref_id');
			if($('input[type=radio][ref_id='+ref_id+']:checked').length){
				$('#'+ref_id).removeClass('d-none');
			}
		});
	}
	$("seclect.select2").select2();
});
$Core.leasing = {
	alreadyScroll : 0,
	shortNumber: function(num){
		if(!$Core.util.isEmpty(num)){
			if (typeof(num) !== 'number') {
				num = $Core.util.toNumber(num);
			}
			return $Core.util.roundNumber(num/1000000);
		}
		return 0;
	},
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
						_min = $Core.leasing.shortNumber(_min);
						_max = $Core.leasing.shortNumber(_max);
						_values = [0,3,4.5,6,7.5,9,10,12,14,16,18,20,25,50];
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
								_minValue = _min_value*1000000;
								_maxValue = _max_value*1000000;
								_min_value = $Core.chart.formatPrice(_min_value*1000000);
								_max_value = $Core.chart.formatPrice(_max_value*1000000);
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
			var _rsSlider;
			$('#js__search-form-modal').on('shown.bs.modal', function(){
				var min = $('input[name=price_min]').val(),
					max = $('input[name=price_max]').val();
				min = min.replace(/\./g, '');
				min = parseInt(min)/1000000000;
				max = max.replace(/\./g, '');
				max = parseInt(max)/1000000000;
				_rsSlider = new rSlider({
					target: '#price-range',
					values: [0,3,4.5,6,7.5,9,10,12,14,16,18,20,25,50],
					range: true,
					tooltip: false,
					labels: true,
					set: [min,max],
					onChange: function (vals) {
						var tmp = vals.split(',');
						$('input[name=price_min]').val(tmp[0]*1000000);
						$('input[name=price_max]').val(tmp[1]*1000000);
					}
				});
			}).on('hidden.bs.modal', function(){
				_rsSlider.destroy();
			});
		}
		if(MOD=='leasing' && ACT == 'default'){			
			$Core.leasing.list_leasing({}, "append", false);	
		}
	},
	checkUtilities: function(_this){
		if($(_this).val() == 1){
			$("#box_lst_utilities").removeClass("d-none");
		}else{
			$("#box_lst_utilities").addClass("d-none");
		}
	},	
	isNumber: function(e) {
		for (var t = 0; t < e.length; t++) {
			var n = e.substring(t, t + 1);
			if (!(n >= "0" && n <= "9")) return !1
		}
		return !0
	},
	convertNumberToText: function(e) {
		if (!$Core.leasing.isNumber(e)) return "";
		var t = parseInt(e / 1e9, 0),
			n = parseInt(e % 1e9 / 1e6, 0),
			i = parseInt(e % 1e9 % 1e6 / 1e3, 0),
			r = parseInt(e % 1e9 % 1e6 % 1e3, 0),
			a = "";
		return t > 0 && parseInt(e, 0) > 9e8 && (a = a + t + (n > 0 ? "," + n / 100 : "") + " Tỷ "), 0 === t && n > 0 && (a = a + n + (i > 0 ? "," + i / 100 : "") + " Triệu "), 0 === n && i > 0 && (a = a + i + (r > 0 ? "," + r / 100 : "") + " Nghìn "), 0 === i && r > 0 && (a = a + r + " Đồng"), a = a.replace(/\./g, "")
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
			e = $(".js-price-text-view");
		if(!$Core.util.isEmpty(p)){
			p = p.replaceAll(".","");
			if(!$Core.util.isNumber(p)) e.html("");
			var s = $Core.leasing.convertNumberToText(p);
			e.html("Tổng giá trị: " + s);
		} else {
			e.html("");
		}
	},
	getLink: function(leasing_id, stock_code){
		return ('/ct/%c-%s.html')
			.replace('%c', stock_code)
			.replace('%s', leasing_id);	
	},
	getLinkSearch: function(options){
		var view = $("input[name='view']:checked").val(),
			url_request = "";
		if(type_list == "manager"){
			var url = "/ct/manager/";
		}else if(type_list == "me"){
			var url = "/ct/me/";
		}else{
			var url = "/ct/";
		}
		if(view !== undefined){
			url += view
		}
		if(options.type_id != undefined){
			url_request += ("&type="+options.type_id);
		}
		if(options.project_id != undefined){
			url_request += ("&project="+options.project_id);
		}
		if(options.block_id != undefined){
			url_request += ("&block="+options.block_id);
		}		
		if(options.building_ids.length > 0){
			url_request += ("&building="+options.building_ids.toString());
		}
		if(options.price_min != "" && options.price_max != ""){
			min = options.price_min;
			min = min.replace(/\./g, '');
			max = options.price_max;
			max = max.replace(/\./g, '');
			if(parseInt(max) > 0){
				url_request += ("&price_min="+min);
				url_request += ("&price_max="+max);
			}
		}
		if(options.area_min != "" && options.area_max != ""){
			min = options.area_min;
//			min = min.replace(/\./g, '');
			max = options.area_max;
//			max = max.replace(/\./g, '');
			if(parseInt(max) > 0){
				url_request += ("&area_min="+min);
				url_request += ("&area_max="+max);
			}
		}
		if(options.bedroom_ids.length > 0){
			url_request += ("&bedroom="+options.bedroom_ids.toString());
		}
		if(options.home_direction_ids.length > 0){
			url_request += ("&direction="+options.home_direction_ids.toString());
		}
		if(options.floor_range.length > 0){
			url_request += ("&floor_range="+options.floor_range.toString());
		}
		if(options.keyword !== undefined && options.keyword != ""){
			url_request += ("&keyword="+options.keyword);
		}
		if(options.user_id !== undefined && options.user_id != ""){
			url_request += ("&user_id="+options.user_id);
		}
		if(url_request != ""){			
			if(options.sort_by !== undefined){
				url_request += ("&sort_by="+options.sort_by);
			}
			if(options.page !== undefined && parseInt(options.page) > 1){
				url_request+= ('&page='+options.page); 
			}
			url += "?s=search"+url_request;
		}else{
			if(options.page !== undefined && parseInt(options.page) > 1){
				url+= ('?page='+options.page); 
			}
		}
		return url;
	},	
	select_type: function(_this, e){
		var toId = $(_this).attr('toId'),
			tp = $(_this).getAttr('tp', 'option'),
			$_adata = {'tp':tp};
		if(tp=='radio'){
			var type_id = $('input[name=type_id]:checked').val();
		} else {
			var type_id = $(_this).val(),
				_text = $(_this).attr('title'),
				_group = $(_this).closest('.btn-group'),
				_dropdown = $(_this).closest('.dropdown-menu');
			$('.dropdown-toggle', _group).addClass('is-active');
			$('.select-text-content', _group).attr('title',_text).text(_text);	
			if(type_id == _TYPE_LOWFLOOR) {
				$(".js__block-bedroom-list,.js__block-floor-range-list").addClass("d-none");
				$(".js__option-bedroom,.js__option-floor_range").prop("checked",false);
			}else{
				$(".js__block-bedroom-list,.js__block-floor-range-list").removeClass("d-none");
			}
		}
		$_adata['type_id'] = type_id;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_project', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if(tp=='radio'){
				if(type_id === undefined || type_id==0){
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
	},
	select_project: function(_this, e){
		var toId = $(_this).attr('toId'),
			tp = $(_this).getAttr('tp', 'option'),
			type_id = $("input[name=type_id]:checked").val(),
			$_adata = {'tp':tp,'type_id' : type_id};
		if(tp=='radio'){
			var project_id = $('input[name=project_id]:checked').val();
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
			type_id = $("input[name=type_id]:checked").val(),
			$_adata = {'tp':tp,'type_id' : type_id};
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
				$('.js__block-building-list').find('.select-text-content').text('Tòa/Dãy');
				$('.js__block-building-list').find('.dropdown-toggle').removeClass("is-active");
				$('#'+toId).html(html);
			}
		});
	},
	set_view: function(_this, e){
		var view = $('input[name=view]:checked').val(),
			page=$('input[name=page]').val();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=set_view', {
			'view' : view
		}, function(respJson){
			var link = respJson.link;
			if(page > 1) {
				link += ("?page="+page);
			}
//			$Core.util.popstate(link);
			$Core.util.toggleIndicatior(0);
			window.location.href= link;
		},"json");
	},
	load_more: function(_this, e){
		e.preventDefault();
		var page = $(_this).attr('page');
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.leasing.list_leasing({'page':page}, "more");
		}
		return false;
	},
	list_leasing: function(options, action="append", full_url=true){
		var $_adata = options || {},
			type_id = $('.js__option-type:checked').val(),
			project_id = $('.js__option-project:checked').val(),
			block_id = $('.js__option-block:checked').val(),
			building_ids = $Core.util.getCheckBoxValueByClass('js__option-building'),
			status_ids = $Core.util.getCheckBoxValueByClass('js__option-status'),
			bedroom_ids = $Core.util.getCheckBoxValueByClass('js__option-bedroom'),
			home_direction_ids = $Core.util.getCheckBoxValueByClass('js__option-home_direction'),
			floor_range = $Core.util.getCheckBoxValueByClass('js__option-floor_range'),
			rental_term = $Core.util.getCheckBoxValueByClass('chk_rental_term'),
			user_id = $(".iso-selectizeNotSearch").val(),
			sort_by = $('.zHXOaDjwDk.active').attr('holderG');
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$_adata['sort_by'] = sort_by;
		$_adata['type_list'] = type_list;
		$_adata['type_id'] = type_id;
		$_adata['project_id'] = project_id;
		$_adata['block_id'] = block_id;
		$_adata['status_ids'] = status_ids;
		$_adata['bedroom_ids'] = bedroom_ids;
		$_adata['building_ids'] = building_ids;
		$_adata['floor_range'] = floor_range;
		$_adata['rental_term'] = rental_term;
		$_adata['home_direction_ids'] = home_direction_ids;
		$Core.leasing.load_sidebar_search($_adata);
		var url = $Core.leasing.getLinkSearch($_adata);
		
		$Core.util.toggleIndicatior(1);
        $.post(PCMS_URL+'/ct/list.cfg', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(full_url) {
				$Core.util.popstate(url);
			}
			$('.holder_leasing').html(respJson.html);
			$('.total-stock').text(respJson.total_record);
			$Core.leasing.init_slide();
			if(parseInt(respJson.total_page) > 1) {
				$('#pagination-container').pagination({
					items: parseInt(respJson.total_page),
					itemsOnPage: respJson.perPage,
					prevText: "&laquo;",
					nextText: "&raquo;",				
					displayedPages: 6,
					edges: 0,
					currentPage : parseInt(respJson.current_page),
					onPageClick: function (pageNumber) {
						$_adata['page'] = pageNumber;
						$('input[name="page"]').val(pageNumber);
						$Core.leasing.list_leasing($_adata,"pagination")
					}
				});
			}else{
				$('#pagination-container').html("");
			}
        }, 'json');
	},
	load_hot_leasing: function(){
		$.post(PCMS_URL+'/ct/list.cfg', $_adata, function(respJson){
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
			$('.leasing__filter-cnd').html('<span class="leasing__search-select-button sliwENIGcR d-flex align-items-center border radius-4 mr-2">'+full_name+'<a class="delete bx bx-x" onClick="$Core.leasing.delete_filter(this, event)" title="Xoá điều kiện"></a><input type="hidden" class="search_field" data-field="user_id" value="'+user_id+'" /></span>');
			$Core.util.popstate(current_page);
			$Core.popup.close($(_this).closest('.modal'));
		}
		$Core.leasing.reload({});
		return false;
	},
	delete_filter: function(_this, e){
		e.preventDefault();
		$(_this).parent().remove();
		$Core.leasing.reload({});
		return false;
	},
	reload : function(options){
		var params = options || {};
		$('.showmorethisresult').attr('page', 2);		
		$('input[name="page"]').val(1);
		clearTimeout(_timeOut);
		_timeOut = setTimeout(() => {
			$Core.leasing.list_leasing(params);
		}, 500);
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
					//centerMode: true,
					//focusOnSelect: true,
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
		$Core.leasing.list_leasing({});
		return false;
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
				var titles = new Array();
				$('.form-check-input:checked', _dropdown).each((_i, _elem) => {
					var title = $(_elem).attr('title');
					titles.push(title);
				});
				if(titles.length > 0){
					$('.dropdown-toggle', _group).addClass('is-active');
					$('.select-text-content', _group).attr('title',titles.join(',')).text(titles.join(','));
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
		$Core.leasing.reload();
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
					$('input[name="price_max"]', _group).val(50000000);
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
			
			$Core.leasing.reload({});
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
			if($('.js__option-building:checked,js__option-bedroom:checked').length){
				$('.js__option-building:checked,js__option-bedroom:checked').each((_i, _elem) => {
					$(_elem).prop('checked', false);
				});
			}
			if($('.js__option-home_direction:checked,.js__option-floor_range:checked').length){
				$('.js__option-home_direction:checked,.js__option-floor_range:checked').each((_i, _elem) => {
					$(_elem).prop('checked', false);
				});
			}
			$('input[name=price_min]',_form).val(0);
			$('input[name=price_max]',_form).val("");
			$(".slider-container .rs-container",_form).remove();
			rsSlider = new rSlider({
				target: '#price-range',
				values: [0,3,4.5,6,7.5,9,10,12,14,16,18,20,25,50],
				range: true,
				tooltip: false,
				labels: true,
				set: [0,30],
				onChange: function (vals) {
					var tmp = vals.split(',');
					$('input[name=price_min]').val(tmp[0]*1000000);
					$('input[name=price_max]').val(tmp[1]*1000000);
				}
			});
			if($('#js__search-form-modal').length > 0){					
				$('#js__search-form-modal').on('hidden.bs.modal', function(){
					rsSlider.destroy();
				});
			}
			$Core.leasing.reload({}, "append", false);
		}
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
	open_leasing: function(_this, e){
		e.preventDefault();
		var leasing_id = $(_this).getAttr('leasing_id', 0),
			stock_id = $(_this).getAttr('stock_id', 0),
			stock_code = $(_this).getAttr('stock_code', 0),
			return_url = window.location.href;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/ct/open.cfg', {
			'stock_id' : stock_id,
			'leasing_id' : leasing_id,
			'type_list' : type_list,
			'return_url' : return_url
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			$Core.util.popstate($Core.leasing.getLink(leasing_id, stock_code));
			$Core.leasing.update_click(leasing_id,'view');
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				var _modal = $(this);
				$('.tinyContentx', _modal).readmore({
					speed: 75, 
					maxHeight: 75
				});
				if($('.slideshow',_modal).length){
					$('.slideshow',_modal).owlCarousel({
						margin:0,
						loop:false,
						nav: true,
						lazyLoad:true,
						dots:true,
						autoplay:false,
						responsiveClass:true,
						navText: [
							'<i class="fa fa-angle-left"></i>',
							'<i class="fa fa-angle-right"></i>'
						],
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
	update_click : function(leasing_id,type){
		$.ajax({
			url: PCMS_URL+"/ct/update-click.cfg",
			method: "POST",
			data: {leasing_id:leasing_id,type:type},
			dataType : 'json',
			success: function () {
				
			}
		});
	},
	handleLike : function(_this,leasing_id,type){
		var like_id = $(_this).data("like_id");
		$Core.checkLoginUser();
		$Core.util.toggleIndicatior(1);
		$.ajax({
			url: PCMS_URL+"/ct/handleLike.cfg",
			method: "POST",
			data: {leasing_id:leasing_id,type:"like"},
			dataType : 'json',
			success: function (res) {
				$Core.util.toggleIndicatior(0);
				if(res.result){
					if(type == 'detail'){
						$("#"+like_id).toggleClass("liked").attr("data-bs-original-title",res.title).tooltip("hide");	
					}	
					$(_this).toggleClass("liked").attr("data-bs-original-title",res.title).tooltip("hide");
				}				
			}
		});
	},
	alert: function(_this, e){
		e.preventDefault();
		$Core.swal.info("Thông báo", "Bạn cần đăng nhập để xem thông tin người rao thuê");
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
	showShop : function(_this){
		$(_this).tooltip('hide');
		var building_id = $(_this).data("building_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=showShop',{building_id:building_id}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('500','500', respJson.html, respJson.uid);
		}, 'json');
	},
	showService : function(_this){
		$(_this).tooltip('hide');
		var leasing_id = $(_this).data("leasing_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=showService',{leasing_id:leasing_id}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('500','500', respJson.html, respJson.uid);
		}, 'json');
	},
	select_file : function(_this, e) {
		e.preventDefault();
		var total_images = 0,
			toId = $(_this).attr('toId');
		if($('input[name=total_images]').length){
			total_images = $('input[name=total_images]').val();
		}
		// alert(total_images);
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
			url: PCMS_URL+"/ct/upload-image.cfg",
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
		$Core.messager.confirm('Message', 'Bạn chắc chắn muốn xóa', function(){
			$.post(PCMS_URL+'/ct/delete-image.cfg', {
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
			leasing_id = $(_this).attr('leasing_id'),
			is_deleted = $(_this).data('is_deleted');
		if(!$(_this).hasClass('preventDefault')){
			if(is_deleted == 1){
				var msg = 'Bạn chắc chắn muốn khôi phục tin này?';
			}else{
				var msg = 'Bạn chắc chắn muốn xóa tin này?';
			}
			$Core.messager.confirm('Xác nhận', msg, function(){
				$Core.util.toggleIndicatior(1);
				$.post(PCMS_URL+'/ct/delete.cfg', {'leasing_id':leasing_id}, function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result){
						alertify.success(respJson.msg);
					}else{
						alertify.error(respJson.msg);
					}
					$Core.util.toggleIndicatior(0);
					$('.leasing__icon-'+leasing_id).html(respJson.icon);
					$('.leasing__menu-delete-'+leasing_id).replaceWith(respJson.html);
					$('.leasing__menu-lock-'+leasing_id).toggleClass('preventDefault');
					$('.leasing__menu-sold-'+leasing_id).toggleClass('preventDefault');
					if($('tr.awe__leasing-item-'+leasing_id).length){
						$('.awe__leasing-item-'+leasing_id).toggleClass('deleted');
					} else {
						$('.awe__leasing-item-'+leasing_id).toggleClass('d-none');
					}
				},"json");
			});
		}
		return false;
	},
	mark_lock: function(_this, e){
		e.preventDefault();
		var leasing_id = $(_this).attr('leasing_id');
		if(!$(_this).hasClass('preventDefault')){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/ct/lock.cfg', {'leasing_id':leasing_id}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$(_this).replaceWith(respJson.html);
				$('.leasing__icon-'+leasing_id).html(respJson.icon);
				$('.leasing__menu-delete-'+leasing_id).toggleClass('preventDefault');
			}, 'json');
		}
		
		return false;
	},
	mark_sold: function(_this, e){
		e.preventDefault();
		var leasing_id = $(_this).attr('leasing_id');
		var is_solded = $(_this).attr('is_solded');
		if(!$(_this).hasClass('preventDefault')){
			if(is_solded == 1){
				var msg= 'Bạn chắc chắn muốn mở cho thuê căn này?';
			}else{
				var msg= 'Bạn chắc chắn muốn báo đã cho thuê căn này?';
			}
			$Core.messager.confirm('Xác nhận', msg, function(){
				$Core.util.toggleIndicatior(1);
				$.post(PCMS_URL+'/ct/sold.cfg', {'leasing_id':leasing_id}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$(_this).replaceWith(respJson.html);
					$('.leasing__icon-'+leasing_id).html(respJson.icon);
					$('.leasing__menu-lock-'+leasing_id).toggleClass('preventDefault');
				}, 'json');
			});
		}
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
			var block_id = $(_this).val();
			$_adata['block_id'] = block_id;
		}
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_building', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if(tp=='radio'){
				if(block_id === undefined || block_id==0){
					$('#'+toId+'_buidling_group').addClass('d-none');
				}else{
					$('#'+toId+'_buidling_group').removeClass('d-none');
				}
				
			}else{		
				$(".dropdown-building").closest(".btn-group").removeClass('d-none');
				$(".dropdown-building").removeClass("is-active");
			}
			$('#'+toId).html(html);
		});
	},
	check_stock_code: function(_this, e){
		var stock_code = $(_this).val(),
			stock_code_hide = $(_this).data("stock_code_hide"),
			_form = $(_this).closest("form"),
			leasing_id = $("input[name='leasing_id']").val(),
			sop_type = $("input[name='sop_type']:checked").val();
		if(!$Core.util.isEmpty(stock_code)){
				$Core.util.toggleIndicatior(1);
				$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=check_stock_code', {
					'stock_code' : stock_code,
					'leasing_id' : leasing_id,
					'sop_type' : sop_type
				}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$("input[name=sop_type]",_form).prop("disabled",false);
					if(respJson.msg.indexOf('_invalid') >= 0){
						console.log(1);
						$('input[name=stock_id]').val(0);
						var price = $("input[name='price']",_form).val().replaceAll(".","");
						$('button.btn-submit',_form).removeAttr('disabled');					
						$(_this).addClass('is-valid').removeClass('is-invalid');
						$('.bs-webui-popover').webuiPopover('hideAll');	
					}else if(respJson.msg.indexOf('_exist') >= 0){
						console.log(2);
						$Core.swal.link('Thông báo', "Mã căn "+stock_code+" đã tồn tại. Hãy đi đến chỉnh sửa tin đăng!",respJson.url);
						$('button.btn-submit',_form).attr('disabled','disabled')
						$(_this).addClass('is-invalid',_form).removeClass('is-valid');
					} else {
						console.log(3);
						$('input[name=stock_id]').val(respJson.stock_id);
						var price = $("input[name='price']",_form).val().replaceAll(".","");
						$('button.btn-submit',_form).removeAttr('disabled');		
						$(_this).addClass('is-valid').removeClass('is-invalid');						
						$('.bs-webui-popover').webuiPopover('hideAll');	
						if(stock_code_hide == 1){
							$("select[name='project_id']",_form).val(respJson.project_id);
							$("select[name='type_villa']",_form).val(respJson.type_id);
							$("#sop_type"+respJson.sop_type,_form).trigger("click");
							$Core.leasing.loadStockCode(_this, event,{"project_id":respJson.project_id,"block_id":respJson.block_id,"field":"block_id",'sop_type':respJson.sop_type});
							$Core.leasing.loadStockCode(_this, event,{"block_id":respJson.block_id,"building_id":respJson.building_id,"field":"building_id",'sop_type':respJson.sop_type});
							$("input[name='floor_range']",_form).val(respJson.floor);
							$("input[name='DT_TT']",_form).val(respJson.DT_TT);
							$("select[name='bedroom_id']",_form).val(respJson.bedroom_id);
							$("select[name='home_direction_id']",_form).val(respJson.home_direction_id);
							$("input[name='code']",_form).val(respJson.code);
						}
						$Core.leasing.loadHidecode({sop_type:respJson.sop_type});
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
	addPaymentLeasing : function(_this){
		var _form = $(_this).closest("form");
		var title = $("input[name='title_pay_other']",_form).val();
		var html = "";
		if(title != ''){
			let index = $("#lst_pay").find("label:last-child").data("index");
			html = `<label class="we-radio" for="rdo_`+(index+1)+`" data-index="`+(index+1)+`">
						<input type="radio" id="rdo_`+(index+1)+`" name="txt_rental_term" value="`+title+`">
						<span>`+title+`</span>
					</label>`;
			$("#lst_pay").append(html);
			$(_this).closest(".modal").modal("hide");
			
			return false;
		}
	},
	openAddPayOther : function(_this){
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=openAddPayOther', function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('500','500', respJson.html, respJson.uid);
		}, 'json');
	},
	approved: function(_this, e){
		var leasing_id = $(_this).attr('leasing_id'),
			tp = $(_this).is(':checked') ? 'agree' : 'refuse';
		$Core.util.toggleIndicatior(1);
		if(tp=='refuse'){
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_notes', {
				'leasing_id' : leasing_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			}, 'json');
		} else {
			var $_adata = {'tp':tp, 'leasing_id':leasing_id};
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=approved', $_adata, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.alert.success("Thành công !");
				$('.leasing__icon-'+leasing_id).html(respJson.icon);
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
			leasing_id = $(_this).attr('leasing_id'),
			reason_not_approved = $('textarea[name=reason_not_approved]', _form).val();
			
		var $_adata = {'tp':tp, 'leasing_id':leasing_id};
		$_adata['reason_not_approved'] = reason_not_approved;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=approved', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.alert.success("Thành công !");
			$('.leasing__icon-'+leasing_id).html(respJson.icon);
			$('.btn-close', _form).trigger('click');
		}, 'json');
		return false;
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
		var leasing_id = $(_this).attr("leasing_id"),
			$_adata = {'leasing_id' : leasing_id};
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
			$('.chk_leasing_device', block).prop('checked', true);
		} else if($.trim(tp)=='uncheckall'){
			$('.chk_leasing_device', block).prop('checked', false);
		}
		return false;
	},
	verified: function(_this, e){
		var leasing_id = $(_this).attr('leasing_id'),
			is_verified = $(_this).is(':checked') ? 1: 0;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=verified', {
			'leasing_id' : leasing_id,
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
		var leasing_id = $(_this).attr("leasing_id"),
			type = $(_this).attr("type"),
			href = $(_this).data("href");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=addLog', {
			'leasing_id' : leasing_id,
			'type' : type
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result){
				if(href == ""){
					$Core.leasing.alert(_this,e)
				}else{
					window.location.href = href;
				}
				
			}
		}, "json");
	},
	showLog : function(_this,e) {
		e.preventDefault();
		var leasing_id = $(_this).attr("leasing_id");
		$(_this).tooltip("hide");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=showLog', {
			'leasing_id' : leasing_id
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
		var copyContent = $(_this).closest(".box_information_leasing").find(".content-copy").text();
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
		$("select.required,input.required").each(function(index, elm){
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
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadStockCode', $_adata, function(html){
				$Core.util.toggleIndicatior(0);
//				console.log(html);return false;
				if(html.indexOf('empty') >=0) {
					alertify.error("Không có mã căn phù hợp");
					$("#stock_code").val('');
					return false;
				}else {
					$("#stock_code").val(html).change();
				}
			});
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
			url: PCMS_URL+"/ct/upload-video.cfg",
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
		var leasing_id = $(_this).attr('leasing_id'),
			$_adata = {'leasing_id' : leasing_id};
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
		var leasing_id = $(_this).data('leasing_id'),
			$_adata = {'leasing_id' : leasing_id};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=share', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				ZaloSocialSDK.reload()
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
		console.log(min,max);
		$('.dropdown-toggle_'+_target).closest(".js__block-"+_target+"-list").find(".btn_do_search").trigger("click");
		
	},	
	load_sidebar_search : function (options) {
		var $_adata = options ||{};
		$Core.util.toggleIndicatior(1);
        $.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_sidebar_search', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$("#box_sidebar_search").html(respJson.html);
        }, 'json');
	},
	loadSoptype : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			sop_type = $(_this).val(),
			project_id = $("select[name='project_id']",_form).val();
		$Core.leasing.loadStockCode(_this, event,{"project_id":project_id,"field":"project_id",'sop_type':sop_type});
		$Core.leasing.loadStockCode(_this, event,{"project_id":project_id,"block_id":0,"field":"block_id",'sop_type':sop_type});			
		if(sop_type == high_level) {
			$(".box_high_level",_form).removeClass("d-none");
			$(".box_lowfloor",_form).addClass("d-none");
		}else{
			$(".box_high_level",_form).addClass("d-none");
			$(".box_lowfloor",_form).removeClass("d-none");
			$(".box_floor_range,.box_bedroom,.box_building,.box_block",_form).find("select,input").val('');
		}
		$Core.leasing.loadHidecode({sop_type:sop_type});
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
		var inp_price = $("input[name=price]",_form),
			price = inp_price.val().replaceAll(".","");
		if(parseInt(price) <= 0) {
			_validated++;
			inp_price.trigger("focus");
			alertify.error("Giá cho thuê phải lớn hơn 0");
			return false;
		}
		if(_validated > 0) {
			return false;
		}
		/*if(!$("input[name='price_negotiable']",_form).is(":checked")) {
			var inp_price = $("input[name=price]",_form),
				price = inp_price.val().replaceAll(".","");
			if(parseInt(price) < 1000000000) {
				_validated++;
				inp_price.trigger("focus");
				alertify.error("Giá bán tối thiểu là 1 tỷ");
				return false;
			}
		}*/
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
};