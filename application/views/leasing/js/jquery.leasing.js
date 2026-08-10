$(function(){
	$Core.leasing.init();
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
		var url = "/cho-thue.html";
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
			url_request += ("&user="+options.user_id);
		}
		if(options.approved !== undefined && options.approved != ""){
			url_request += ("&approved="+options.approved);
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
	select_price_range: function(_this,e){
		var _form = $(_this).closest("form"),
			price_min = $(_this).data("min"),
			price_max = $(_this).data("max");
		$("input[name=price_min]",_form).val(price_min);
		$("input[name=price_max]",_form).val(price_max);
	},
	select_type: function(_this, e){
		var toId = $(_this).attr('toId'),
			tp = $(_this).attr('tp'),
			$_adata = {'tp':tp},
			_modal = $("#js__search-form-modal");
		if(tp=='radio'){
			var type_id = $('input[name=type_id]:checked').val();
		} else {
			var type_id = $(_this).val(),
				_text = $(_this).attr('title'),
				_group = $(_this).closest('.btn-group'),
				_dropdown = $(_this).closest('.dropdown-menu');
			$('.dropdown-toggle', _group).addClass('is-active');
			$('.select-text-content', _group).attr('title',_text).text(_text);	
		}
		if(type_id == _TYPE_LOWFLOOR) {
			$(".js__block-bedroom-list,.js__block-floor-range-list").addClass("d-none");
			$(".js__option-bedroom,.js__option-floor_range").prop("checked",false);
		}else{
			$(".js__block-bedroom-list,.js__block-floor-range-list").removeClass("d-none");
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
			$_adata = {'tp':tp,'type_id' : type_id},
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
				let countItem = 0;
				$('.form-check-input:checked', _dropdown).each((_i, _elem) => {
					++countItem;
				});
				console.log(countItem);
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
			$('input[name=price_max]',_form).val(50000000);
			$("input[name=price_range]").prop("checked",false);
			$(".slider-container .rs-container",_form).remove();
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
		$('.dropdown-toggle_'+_target).closest(".js__block-"+_target+"-list").find(".btn_do_search").trigger("click");
		
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