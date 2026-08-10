$(function(){
	if(ACT == "default") {
		$Core.data_central.init();
		$_document.ajaxComplete(function() {
			$Core.data_central.init();
		});
		$Core.data_central.load_data_central();
		$(window).scroll(() => {
			if(isScrolledIntoView('#showmorethisresult') 
				&& $Core.data_central.scrolled == 0){
				$Core.data_central.scrolled = 1;
				$('.showmorethisresult').removeClass('d-none').trigger('click');
			}
		});
	} else if(ACT == "manager") {
		$Core.data_central._autoload();
	} else if(ACT == "campaign") {
		$_document.ajaxComplete(function() {
			$Core.data_central.init();
		});
		$Core.data_central.load_data_central({"_tp":"_campaign"});
		$Core.data_central.load_desktop_followups();
		$Core.data_central.load_converted_rates({});
		$Core.data_central._autoload();
	}
	if(SUB == "default" && ACT == "default" && 1==2) {
		socket.on('calling post', function(data){
			var data_id = data.data_id;
			if($(`.data_status_${data_id}`).length){
				$(`.data_status_${data_id}`).html(`<span class="btn btn-sm btn-warning w-100 text-nowrap" type="button">Đang gọi</span>`);
				console.log("sss");
			}
		});
	}
});
$Core.data_central = {
	scrolled : !1,
	init: () => {
		if($(".tags:not(.tagged)").length){
			console.log("sss");
			$(".tags:not(.tagged)").each((_i, _elem) => {
				var _values = {};
				$.getJSON(PCMS_URL+'/index.php?mod='+MOD+'&act=load_tags', {}, function(data){
					$(_elem).addClass('tagged').inputTags({
						autocomplete: { values: data, only: false },
						create: function() {
							console.log('Tag added !');
						}
					});
				});
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
			m.css({
				top:o.top+b.outerHeight(),
				left: m.hasClass('dropdown-menu-start') 
					? o.left : o.left + b.outerWidth() - m.outerWidth(),
				zIndex:1000
			});
			$_document.one('click',() => m.remove() );
		});
	}, do_search : () => {
		if(ACT == "campaign") {
			var _tp = "_campaign";	
		}else{
			var _tp = "";
		}
		$Core.data_central.load_data_central({'_tp':_tp});
		if(_tp == "_campaign") {
//			$(".nav-link.active").trigger("click");
			$Core.data_central.load_desktop_followups({});
			$Core.data_central.load_converted_rates({});
			if($(".lst_performance_campaign.loaded").length > 0) {
				$(".lst_performance_campaign.loaded").removeClass("loaded");
				$Core.data_central._autoload();
			}
		}
	}, reload: function(_this,e){
		e.preventDefault();
		var _item = $(_this).closest(".dashboard-panel-item"),
			_item_all = $(_this).closest(".dashboard-panel-item-all");
		
		var options = {},
			gId = $(_this).attr('gId'),
			name = $(_this).attr('name');
		if(name== 'date_type'){
			var date_type = $(_this).val(),
				year = $("select[name='year'][gId='"+gId+"']").val();
			$.post(PCMS_URL+'/index.php?mod=home&sub=dashboard&act=load_month', {
				'date_type' : date_type,'year':year
			}, function(html){
				$('select[name=month][gId='+gId+']').html(html);
				var params = $.extend(options, {'gId':gId});
				if($('select[gId='+gId+'],input[gId='+gId+']').length){
					$('select[gId='+gId+'],input[gId='+gId+']').each((_i, _elem) => {
						var p_name = $(_elem).attr('name'),
							p_value = $(_elem).val();
						if(!$Core.util.isEmptyZero(p_value)){
							params[p_name] = p_value;
						}
					});
				}
				$('.ajax[gId='+gId+']').data('options',params);
				$('.ajax[gId='+gId+']').removeClass('loaded');
			});
		} else if(name== 'year'){
			var date_type = '_month',
				year = $(_this).val();
			if($('select[name=date_type][gId='+gId+']').length){
				date_type = $('select[name=date_type][gId='+gId+']').val();
			}
			if(date_type == '_quater' || date_type == '_half'){
				var params = $.extend(options, {'gId':gId});
				if($('select[gId='+gId+'],input[gId='+gId+']').length){
					$('select[gId='+gId+'],input[gId='+gId+']').each((_i, _elem) => {
						var p_name = $(_elem).attr('name'),
							p_value = $(_elem).val();
						if(!$Core.util.isEmptyZero(p_value)){
							params[p_name] = p_value;
						}
					});
				}
				$('.ajax[gId='+gId+']').data('options',params);
				$('.ajax[gId='+gId+']').removeClass('loaded');
			} else {
				$.post(PCMS_URL+'/index.php?mod=home&sub=dashboard&act=load_month', {
					'date_type' : date_type,
					'year' : year,
				}, function(html){
					$('select[name=month][gId='+gId+']').html(html);
					if($('select[name=quarter][gId='+gId+']').length) {
						$('select[name=quarter][gId='+gId+']').val("");	
					}					
					var params = $.extend(options, {'gId':gId});
					if($('select[gId='+gId+'],input[gId='+gId+']').length){
						$('select[gId='+gId+'],input[gId='+gId+']').each((_i, _elem) => {
							var p_name = $(_elem).attr('name'),
								p_value = $(_elem).val();
							if(!$Core.util.isEmptyZero(p_value)){
								params[p_name] = p_value;
							}
						});
					}
					$('.ajax[gId='+gId+']').data('options',params);
					$('.ajax[gId='+gId+']').removeClass('loaded');
				});
			}
		}
		
		if(_item_all.length > 0) {
			$(".ajax.loaded",_item_all).removeClass("loaded");
		}else{
			$(".ajax.loaded",_item).removeClass("loaded");
		}		
		$Core.data_central._autoload();
	}, _autoload : () => {
		if($('.ajax:not(.loaded)').length){
			$('.ajax:not(.loaded)').each((_i, _elem) => {
				var url = $(_elem).data('url'),
					gId = $(_elem).getAttr('gId', ""),
					$_adata = $(_elem).data('options') || {};
				$_adata['gId'] = gId;
				$("input.search_field,select.search_field").each(function(index,elm){
					let field = $(elm).attr("name"),
						value = $(elm).val();
					$_adata[field] = value;
				});			
				$.post(url, $_adata, function(respJson){
					let $html = respJson.html;
					if($html.indexOf('_empty') >= 0){
						$(_elem).remove();
					} else {
						$(_elem).addClass('loaded').html($html);
						if(respJson.drawchart == 1){
							if(typeof(respJson.multichart) != 'undefined' && respJson.multichart==1){
								console.log(1);
								$Core.chart.canvas_multi(respJson.uid,respJson.barChartData);
							} else {
								console.log("2");
								$Core.chart.canvas(respJson.uid,respJson.barChartData);
							}
						}
					}
					if(respJson.callback){
						eval(respJson.callback);
					}
				},'json');
			});
		}
	}, load_more: (_this, e) => {
		e.preventDefault();
		var page = $(_this).attr('page');
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.data_central.load_data_central({'page':page}, "more");
		}
		return false;
	}, open_import : (_this,e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=open_import', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open("auto","auto",respJson.html,respJson.uid);
			if($(".input-tags").length){
				$(".input-tags").selectize({
					delimiter: ",",
					persist: false,
					preload: true,
					valueField: 'text',
					labelField: 'text',
					searchField: 'text',
					create: function (input) {
						return { value: input, text: input};
					}, load: function(query, callback) {
						var self = $(this);
						$.ajax({
							url:path_ajax_script+'/index.php?mod='+MOD+'&act=search_tag',
							type: 'GET',
							dataType:'json',
							cache: true,
							error: function() {
								callback();
							}, success: function(res) {
								callback(res);
							}
						});
					}
				});
			}
		},"json");
	}, get_sheets : (_this,e) => {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			spreadsheetId = $(_this).val(),
			toId = $(_this).attr("toId");
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=get_sheets', {
			"spreadsheetId":spreadsheetId, 
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(!respJson.result) {
				$Core.alert.error(respJson.msg);
			} else {
				$("#"+toId).html(respJson.html);
			}
		}, 'json');
		return false;		
	}, open_config : (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			spreadsheetId = $('input[name=spreadsheetId]', _form).val(),
			sheet_name = $('select[name=sheet_name]', _form).val();
		if($Core.util.isEmpty(spreadsheetId) || $Core.util.isEmpty(sheet_name)){
			$Core.swal.error("Thông báo", "Tên File hoặc Tên sheet không được trống");
		} else {
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=open_config', {
				'spreadsheetId' : spreadsheetId,
				'sheet_name' : sheet_name,
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			},"json");
		}
		return false;
	}, save_config: (_this, e) => {
		e.preventDefault();
		var _uid = $(_this).attr("uid"),
			_form = $(_this).closest("form");
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL+'/index.php?mod='+MOD+'&act=save_config', 
			data:{'uid':_uid},
			dataType:'json',
			success: function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result) {
					alertify.success(respJson.msg);
					$(".btn-close", _form).trigger("click");
				}else{
					alertify.error(respJson.msg);
				}
			}
		});
		return false;
	}, loadLog :  (_this,e) => {
		e.preventDefault();
		var id = $(_this).data("id");
		$.post('/index.php?mod='+MOD+'&act=loadLog', {"id":id}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(__w*2/3,respJson.html,respJson.uid);
		},"json");
	}, import_data: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form');	
		if($("select.required,input.required", _form).length){
			$("select.required,input.required", _form).each((index, elm) => {
				if($Core.util.isEmpty($(elm).val())){
					$(elm).focus();
					_error ++;
					return false;
				}
			});
		}
		if(_error == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: "POST",
				url: '/index.php?mod='+MOD+'&act=import_data',
				data: {},
				// async: false,
				dataType: "json",
				success: function(respJson) {
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						$Core.swal.success("Thông báo","Update thành công "+respJson.total_update+" thông tin cư dân");	
					}else{
						$Core.alert.error("Lỗi!");
					}
				}
			});	
		}	
		return false;
	}, log_call: (_this,e) => {
		e.preventDefault();
		var href = $(_this).data("href"),
			id = $(_this).data("id");
		$.post('/index.php?mod='+MOD+'&act=log_call', {"id":id}, function(respJson){
			 window.open(href, '_blank');
			$(".status_call_"+id).addClass("text-success").text("Đã liên hệ").removeClass("text-warning");
		},"json");
	}, log: (_this, e) => {
		e.preventDefault();
		var id = $(_this).data("id"),			
			type = $(_this).data("type");	
		$.post('/index.php?mod='+MOD+'&act=log', {"id":id,"type":type}, function(respJson){
			if(type == "view_phone"){
				var toId = $(_this).attr("toId"),
					phone = $(_this).data("phone");
				$(`.${toId}`).each((index,elm) => {
					let phone = $(elm).data("phone");
					$(elm).text(phone);
				});
				var url = $(".load_more_"+toId).data("url");
				$(".load_more_"+toId).attr("data-url",url+"&type=full");
				
			}else if(type == "call_log") {
				var href = $(_this).data("href");
				window.open(href, '_blank');
				$(".status_call_"+id).addClass("text-success").text("Đã liên hệ").removeClass("text-warning");
				socket.emit('calling post', {'data_id' : id});
			}else if(type == "call_success") {
				$(_this).replaceWith(`<span class="btn btn-sm btn-success text-nowrap w-100" type="button">Đã gọi</span>`);
			}			
		},"json");
		return false;
	}, update_tags: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			p_id = $(_this).attr('p_id'),
			form = $(_this).closest('form'),
			lst_tag_selected = $("select[name=tags].search_field").val();
		$Core.util.toggleIndicatior(1);
		form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL+'/index.php?mod='+MOD+'&act=update_tags',
			data: {'uid':uid,'p_id':p_id,'lst_tag_selected':lst_tag_selected},
			dataType: "json",
			success: function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result){
					$('.bs-webui-popover').webuiPopover('hide');
					$('#list_tag_'+p_id).html(respJson.html_tag);
					$(".box_search_tag select.multiselect").html(respJson.html_option).multiselect('rebuild');;
					$(".box_search_tag").removeClass("d-none");
				}
			}
		});
		return false;
	}, potentialCRM: (_this, e) => {
		e.preventDefault();
		var id = $(_this).data('id');	
		$Core.messager.confirm('Xác nhận', 'Xác nhận chuyển khách hàng tiềm năng CRM?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=potential_CRM', {"id":id}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result) {
					alertify.success(respJson.msg);
					$(_this).remove();
				}else{
					alertify.error(respJson.msg);
				}
			},"json");
		});
		return false;
	}, load_block: (_this, e) => {
		var block_id = $(_this).attr('block_id'),
			project_id = $(_this).val();
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod=home&act=load_block', {
			'block_id' : block_id,	
			'project_id' : project_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('select[name=block_id]').html(html);
		});
	}, load_building: (_this, e) => {
		var toId = $(_this).attr('toId');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_building', {
			'block_id' : $(_this).val()
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('select[name=building_id]').html(html);
		});
	}, select_block: (_this, e) => {
		var project_id = $(_this).val(),
			toId = $(_this).attr('toId');
		$.post(PCMS_URL+'/index.php?mod=tool&act=load_block', {
			'project_id' : project_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html).multiselect('rebuild');
			$('#slb_Building_Id').empty().multiselect('rebuild');
			if(deviceType == 'computer') {
				$Core.data_central.load_data_central();	
			}
			
		});
	}, select_building: (_this, e) => {
		var toId = $(_this).attr('toId');
		$.post(PCMS_URL+'/index.php?mod=tool&act=load_building', {
			'list_block_ids' : $(_this).val()
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html);
			$('#'+toId).multiselect('rebuild');
			$("#fund_type_search").addClass("d-none");
			$(".form-check-input:checked",$("#fund_type_search")).prop("checked",false);
			$("select",$("#fund_type_search")).val("");
			if(deviceType == 'computer') {
				$Core.data_central.load_data_central();	
			}
		});
	}, load_data_central: (options,type='search') => {
		var $_adata = options || {};
		$_adata['type'] = type;
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_data_central', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.showmorethisresult').addClass('d-none').removeClass('clicked');
			if(parseInt(respJson.total_record) <= (parseInt(respJson.per_page) * parseInt(respJson.current_page))){
				$Core.data_central.scrolled = 1;
			} else {
				$Core.data_central.scrolled = 0;
			}
			if(type == 'more'){
				$('.tbody_data_central tr:last').after(respJson.html);
			}else{
				$('.tbody_data_central').html(respJson.html);
				if($('#'+'box_warning').length > 0) {
					$('#'+'box_warning').html(respJson.html_warning);	
				}				
			}
			$('.showmorethisresult').attr('page', parseInt(respJson.current_page)+1);
			$('.total_record').html(respJson.total_record);
			$('input[name=page]').val(parseInt(respJson.current_page)+1);
			if(respJson.html_briefs != "" && $('.briefs').length > 0) {
				$('.briefs').html(respJson.html_briefs);
			}
			
			if(respJson.callback) eval(respJson.callback);
			if(parseInt(respJson.total_record) >= 1 && (deviceType != 'phone')){
				//$('#tableStock').freezeTable('update');
			}
			$Core.util.popstate(respJson.url);
		},'json');
	},set_status: (_this, e) => {
		e.preventDefault();
		var status_id = $(_this).attr('status_id');
		console.log(status_id,$('.search_data_status_field').length);
		$('.search_data_status_field').val(status_id).trigger('change');
		$(".do_search").trigger("click");
		return false;
	}, log_call_success: function(_this,e){
		e.preventDefault();
		var type=$(_this).data("type"),
			id=$(_this).data("id"),
			$_adata = {"type":type,"id":id};
		if(type == "_SAVE"){
			var _form = $(_this).closest("form");
			var notes = $("textarea",_form).val();
			$_adata['notes'] = notes;
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=log_call_success', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(type == "_OPEN") {
				$Core.popup.open("auto","auto",respJson.html,respJson.uid);	
			}else{
				$(_this).closest(".modal").find(`.btn-close`).trigger("click");
				$Core.alert.success("Thành công");
				window.location.reload();
			}			
		},'json');
	}, add_setting_field : (_this,e) => {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			title = $(_this).data("title"),
			toId = $(_this).attr("toId"),
			id = $(_this).attr("id"),
			key = $(_this).data("key"),
			limit = parseInt($("input[name=limit]",_form).val());
		if($(_this).is(":checked")) {
			if($(".item_field_selected",_form).length >= limit) {
				$(_this).prop("checked",false);
				alertify.error(`Tối đa cho phép ${limit} trường`);
				return false;
			}
			if($(".item_field_selected",_form).length == 0){
				$(".list_field-selected",_form).html(``);
			}
			$(".list_field-selected",_form).append(`<li id="${toId}" class="item_field_selected d-flex justify-content-between align-items-center p-2 bg-lighter rounded-1 mb-2 text-black cursor-pointer" title="${title}" key="${key}">
				<div class="crm-flex filed-select">
					<i class='bx bx-grid-vertical'></i>
					<span class="title-ellipsis text misa-label">${title}</span>
				</div>
				<button class="btn btn-sm text-main p-0" type="button" onClick="$Core.data_central.delete_setting_field(this,event)" toId="${id}">
					<i class='bx bx-x'></i></button>
			</li>`);
		}else{
			$("#"+toId,_form).remove();
		}	
		$(".text_number",_form).text($(".item_field_selected",_form).length)
	}, setting_field: (_this, e) => {
		e.preventDefault();
		var action = $(_this).attr('action'),
			view_by = $(_this).attr('view_by'),
			field_name = $(_this).attr('field_name');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=setting_field', {
			'action' : action,
			'view_by':view_by,
			'field_name':field_name
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var _www = $(window).width();
			$Core.popup.openfull(_www-450,respJson.html,respJson.uid);
			$('.modal-backdrop').remove();			
			$('#'+respJson.uid).on('shown.bs.modal',function (e) {
				$('#'+respJson.uid).find(".list_field-selected").sortable({
					update: function(event, ui) {
						let ids = $('#'+respJson.uid).find(".list_field-selected .item_field_selected").map(function() {
							return $(this).attr("key");
						}).get();						
					}
				});
				$(".text_number",$('#'+respJson.uid)).text($(".item_field_selected",$('#'+respJson.uid)).length);
			});
		}, 'json');
		return false;
	}, delete_setting_field: (_this, e) => {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			var toId = $(_this).attr("toId");
			$("#"+toId).prop("checked",false);
			$(_this).closest(".item_field_selected").remove();
		});
		return false;
	}, delete_all_field_selected : (_this,e) => {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa các trường đã chọn?', function(){
			var _form = $(_this).closest("form"),
				title = $(_this).data("title"),
				toId = $(_this).attr("toId"),
				id = $(_this).attr("id"),
				key = $(_this).data("key");
			$(".list_field-selected",_form).html(`<p class="text-center mb-0 fs-14 fw-italic">Không có trường nào được chọn</p>`);
			$(".text_number",_form).text($(".item_field_selected",_form).length);
			$(".list-checkbox-item input",_form).prop("checked", false);
		});
	},	search_field: $Core.util.delay(function(_this, e){
		var	_form = $(_this).closest("form"),
			_gid = $("input[name=gid]",_form).val(),
			keyword = $("input[name=keyword]",_form).val(),
			field_name = $("input[name=field_name]",_form).val();
		var list_field_data = $(".item_field_selected",_form).map(function() {
			return $(this).attr("key");
		}).get();
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_setting_field', {
			'gid':_gid,
			'type':"SEARCH",
			'keyword' : keyword,
			'list_field_data':list_field_data,
			'field_name':field_name
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(".list-checkbox-item", _form).html(respJson.html);
		}, 'json');
	}, 1000),		
	load_setting_default_field : (_this, e) => {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn sử dụng các trường mặc định?', function(){
			var $_this = $(_this),
				_form = $_this.closest("form"),
				_gid = $("input[name=gid]",_form).val(),
				view_by = $("input[name=view_by]",_form).val(),
				field_name = $("input[name=field_name]",_form).val();
			$Core.util.toggleIndicatior(1);
			$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_setting_field', {
				'gid':_gid,
				'type':"DEFAULT",
				'view_by' : view_by,
				'field_name':field_name
			}, function(respJson){
				$(".list_field-selected",_form).html(respJson.html);
				$(".text_number",_form).text($(".item_field_selected",_form).length);
				$Core.data_central.search_field($_this, e);
			}, 'json');
		});
		return false;
	}, save_setting_field : (_this,e) => {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			list_field_data = $(".item_field_selected",_form).map(function() {
				return $(this).attr("key");
			}).get();
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=save_setting_field', {
			"list_field_data":list_field_data, 
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result) {
				$Core.alert.success('Lưu thành công!');
				window.location.reload();
			}else{
				$Core.alert.error('ERROR!');
			}
		}, 'json');
		return false;		
	}, resetForm : (_this) => {
		var _form = $(_this).closest("form");
		$("select",_form).val("");
		$("select[multiple].multiselect",_form).each(function(index,elm) {
			$(elm).val("").multiselect('rebuild');
		});
		$("select[name=project_id]",_form).val(1).multiselect('rebuild');
		$("input[name=keyword],input[type=date]",_form).val("");
		if(ACT == "campaign") {
			var _tp = "_campaign";
			$Core.data_central.load_desktop_followups({'tp' : _tp});
		}else{
			var _tp = "";
		}
		$Core.data_central.load_data_central({'_tp':_tp});
		return false;		
	}, check_item: (_this, e) => {
		e.stopPropagation();
		var _tp = $(_this).attr('tp');
		if(_tp=='all'){
			var _checked = $(_this).is(':checked') ? 1 : 0;
			$('.chk_customer').prop('checked', _checked);
			$('.btn_customer_campaign').prop('disabled', !_checked);
		} else {
			var _checkall = 1, _total_checked =0;
			$('.chk_customer').each((_i, _elem) => {
				if(!$(_elem).is(':checked')){
					_checkall = 0;
				} else {
					_total_checked += 1;
				}
			});
			$('input[tp=all]').prop('checked', _checkall);
			$('.btn_customer_campaign').prop('disabled', (_total_checked > 0) ? 0 : 1);
		}
	}, open_campaign: (_this, e) => {
		e.preventDefault();
		var campaign_id = $(_this).attr("campaign_id");
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=open_campaign', {
			'campaign_id' : campaign_id,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			$Core.data_central.load_share_staffs('_staff', {});
		}, 'json');
		return false;
	}, add_campaign : (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form');
		if($('select.required,input.required,textarea.required', _form).length){
			$('select.required,input.required,textarea.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_error==0){
			var start_date = $(`input[name='start_date']`,_form).val(),
				end_date   = $(`input[name='end_date']`,_form).val(),
				startDate = new Date(start_date),
				endDate   = new Date(end_date);
			if (startDate >= endDate) {
				alertify.error("Thời gian kết thúc phải lớn hơn bắt đầu!");
				$(`input[name='end_date']`,_form).focus();
				return false;
			}else{
				$Core.util.toggleIndicatior(1);
				_form.ajaxSubmit({
					type:'POST',
					url: PCMS_URL + "/index.php?mod="+MOD+"&act=add_campaign",
					dataType : "JSON",
					success: function(respJson){
						$Core.util.toggleIndicatior(0);
						if(respJson.result) {
							$Core.swal.success("Thông báo","Thành công!");
							$(".btn-close",_form).trigger("click");
						}else{
							if(respJson.msg.indexOf('_invalid') >= 0){
								$Core.swal.error("Oops","Chiến dịch đã tồn tại");
							}else{
								$Core.swal.error("Oops","Quá trình gửi yêu cầu bị lỗi. Xin vui lòng thử lại");
							}							
						}
					}
				});
			}
			
		}
		return false;
	}, check_data_campaign : (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form'),
			toId = $(_this).attr("toId");
		if($('select.required,input.required,textarea.required', _form).length){
			$('select.required,input.required,textarea.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error++;
					$(_elem).focus();
					if($(_elem).attr("name") == "staff_id") {
						alertify.error("Bạn cần chọn nhân viên được giao");
					}
					return false;
				}
			});
		}
		if(_error==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=check_data_campaign",
				dataType : "JSON",
				data : {"toId":toId},
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					var $_adata = respJson.data;
					if(respJson.is_exist) {
						Swal.fire({
							title: "Thông báo",
							text: "Có "+respJson.total_exist+" dữ liệu khách hàng có người khác phụ trách cho chiến dịch này. Bạn có muốn chuyển giao người phụ trách không?",
							showDenyButton: true,
							showCancelButton: true,
							confirmButtonText: "Đồng ý",
							denyButtonText: `Không`
						}).then((result) => {
							if (result.isConfirmed) {
								$_adata["is_confirm"] = 1;
								$Core.data_central.add_data_campaign($_adata);
							} else if (result.isDenied) {
								$_adata["is_confirm"] = 0;
								$Core.data_central.add_data_campaign($_adata);
							}
						});
					}else{
						$Core.data_central.add_data_campaign($_adata);					
					}
					
				}
			});			
		}
		return false;
	}, add_data_campaign : (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
			$.ajax({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=add_data_campaign",
				dataType : "JSON",
				data : $_adata,
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						$Core.swal.error("Thông báo","Thành công!");
						$(".btn-close",$("#"+respJson.toId)).trigger("click");
					}else{
						$Core.swal.error("Oops","Quá trình gửi yêu cầu bị lỗi. Xin vui lòng thử lại");						
					}
				}
			});	
		return false;
	}, open_data_campaign: (_this, e) => {
		e.preventDefault();
		var list_ids = $Core.util.getCheckBoxValueByClass('chk_customer'),
			campaign_id = $(_this).attr("campaign_id");
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=open_data_campaign', {
			'list_ids' : list_ids,
			'campaign_id' : campaign_id,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
		return false;
	}, load_share_staffs : (_this, e) =>{
		e.preventDefault();
		var toId = $(_this).attr("toId"),
			department_id = $(_this).val(),
			$_adata = {"department_id" : department_id};
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_share_staffs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(`#${toId}`).html(respJson.html);
			
		},'json');
	}, do_share_search : (_this, e) => {
		var _holderG = $(_this).attr('holderG');
		if(_holderG == '_staff'){
			$('.js__selected_staff').text(0);
			$Core.data_central.load_share_staffs(_holderG, {});
		} else if(_holderG == '_customer'){
			$('.js__selected_customer').text(0);
			$Core.data_central.load_share_customers(_holderG, '_load', {});
		}
	}, enter_share_search: (_this, e) => {
		var _keyCode = e.keyCode || e.which;
		if(_keyCode==13){
			$Core.data_central.do_share_search(_this, e);
			e.preventDefault();
			return false;
		}
	}, remove_selected_staff: (_this, e) => {
		e.preventDefault();
		$('.js__staff_item')
			.prop('checked', false)
			.trigger('change');
		return false;
	}, check_all : (_this, e) => {
		var holderG = $(_this).attr('holderG');
		if(holderG == '_customer'){
			$('.js__customer_item')
				.prop('checked', true)
				.trigger('change');
		} else if(holderG == '_staff'){
			$('.js__staff_item')
				.prop('checked', true)
				.trigger('change');
		}
		$(_this).tooltip('hide');
		// return false;
	}, uncheck_all : (_this, e) => {
		// e.preventDefault();
		var holderG = $(_this).attr('holderG');
		if(holderG == '_customer'){
			$('.js__customer_item')
				.prop('checked', false)
				.trigger('change');
		} else if(holderG == '_staff'){
			$('.js__staff_item')
				.prop('checked', false)
				.trigger('change');
		}
		$(_this).tooltip('hide');
		// return false;
	}, handle_selected_staff : (_this, e) => {
		var total_checked = 0,
			uid = $(_this).attr('uid');
		total_checked = $('.js__staff_item[uid='+uid+']:checked').length;
		$('.js__selected_staff').text(total_checked);
		if(parseInt(total_checked, 10) > 0){
			$('.js__remove_selected_staff').removeClass('d-none');
		} else {
			$('.js__remove_selected_staff').addClass('d-none');
		}
//		$Core.crm.handle_message(_this, e);
	}, open_activity: (_this, e) => {
		e.preventDefault();
		var options = {},
			tp = $(_this).attr('tp'),
			type_id = $(_this).attr('type_id'),
			campaign_id = $(_this).attr('campaign_id'),
			customer_id = $(_this).attr('customer_id');
		
		if(tp=='follow-ups'){
			var followup_id = $(_this).attr('followup_id');
			options['followup_id'] = followup_id;
		}
		$(_this).addClass('active');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=open_activity', $.extend(options,{
			'tp' : tp,
			'type_id' : type_id,
			'customer_id' : customer_id,
			'campaign_id':campaign_id
		}), function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal',function (e) {
				$('textarea[name=intro]').focus();
			});
		}, 'json');
		return false;
	}, save_activity : (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			tp = $(_this).attr('tp'),
			customer_id = $(_this).attr('customer_id'),
			$_adata = {'tp':tp, 'customer_id':customer_id};
		if(tp=='notes'){
			note_id = $(_this).attr('note_id');
			$_adata['note_id'] = note_id;
		} else {
			followup_id = $(_this).attr('followup_id');
			$_adata['followup_id'] = followup_id;
		}
		if($('select.required,textarea.required',_form).length){
			$('select.required,textarea.required',_form).each((_i,_elem) => {
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
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_activity",
				data: $_adata,
				dataType:'html',
				success: function(html){
					$Core.util.toggleIndicatior(0);	
					if(html.indexOf('_success') >= 0){
						$Core.alert.success('Thành công');
						$('.btn-close', _form).trigger('click');
						if(tp=='_data_central'){
							$Core.data_central.load_activity(customer_id, {});
							$Core.data_central.load_desktop_followups({});
						} else {
							$('.js__tab-activity[customer_id='+customer_id+'][tp=notes]').trigger('click');
						}
					} else {
						$Core.alert.success('Đã xảy ra lỗi');
					}
				}
			});
		}	
		return false;
	}, set_timerange: (_this, e) => {
		var _form = $(_this).closest('form'),
			after_time = $(_this).val(),
			reminder_before = $("input[name=reminder_before]",_form).val(),
			is_reminder_time = $("input[name=is_reminder]",_form).is(':checked') ? 1: 0;
		$.post(path_ajax_script+'/index.php?mod=crm&act=set_timerange', {
			'after_time' : after_time,'is_reminder_time':is_reminder_time,'reminder_before':reminder_before
		}, function(respJson){
			$('input[name=time_id]', _form).val(respJson.time);
			$('input[name=date_id]', _form).val(respJson.date).trigger('change');
			$('input[name=reminder_time_id]', _form).val(respJson.time_before);
			$('input[name=reminder_date_id]', _form).val(respJson.date_before);
			$('input[name=reminder_before]', _form).val(respJson.reminder_before);
		}, 'json');
	}, set_change: (_this, e) => {
		var toId = $(_this).attr('toId'),
			_form = $(_this).closest('form');
		var date_id = $('input[name=date_id]', _form).val(),
			time_id = $('input[name=time_id]', _form).val();
		$.post(path_ajax_script+'/index.php?mod=crm&act=campare_date_now', {
			'date_id' : date_id,
			'time_id' : time_id
		}, function(html){
			if(parseInt(html)==1){
				$('#'+toId).prop('checked', true);
			} else {
				$('#'+toId).prop('checked', false);
			}
		});
	}, view_activity: function(_this, e){
		e.preventDefault();
		e.stopPropagation();
		var route = $(_this).attr('route'),
			campaign_id = $("select.search_field[name='campaign_id']").val(),
			customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=view_activity', {
			'customer_id' : customer_id,'campaign_id':campaign_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var _www = $(window).width();
			$Core.popup.openfull(_www-480,respJson.html,respJson.uid);
			$('.modal-backdrop').remove();
			$Core.data_central.load_activity(customer_id, {});
		}, 'json');
		return false;
	}, load_activity: function(customer_id, options){
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_activity',$_adata,function(respJson){
			$('.holder_activity_'+customer_id).html(respJson.html);
		}, 'json');
	}, sw_activity: function(_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			tabid = $(_this).attr('tabid'),
			customer_id = $(_this).attr('customer_id');
		$('.'+tabid).removeClass('active');
		$(_this).addClass('active');
		if(tp=='activity'){
			$Core.data_central.load_activity(customer_id, {});
		} else if(tp=='notes') {
			$Core.helper.load_list_notes(customer_id, 'DataCentral', {});
		} else if(tp=='consulting'){
			$Core.data_central.load_consulting(customer_id, {});
		} else if(tp=='logs'){
			$Core.data_central.load_logs(customer_id, {});
		}
		return false;
	}, delete_activity: function(_this, e){
		e.preventDefault();
		var followup_id = $(_this).attr('followup_id'),
			customer_id = $(_this).attr('customer_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete_activity', {
				'followup_id' : followup_id,
				'customer_id':customer_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.data_central.load_activity(customer_id, {});
			});
		});
		return false;
	}, load_consulting: function(customer_id, options){
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_consulting',$_adata,function(respJson){
			$('.holder_activity_'+customer_id).html(respJson.html);
		}, 'json');
	}, load_logs: function(customer_id, options){
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_logs',$_adata,function(respJson){
			$('.holder_activity_'+customer_id).html(respJson.html);
		}, 'json');
	}, done_followup: (_this, e) => {
		e.preventDefault();
		var followup_id = $(_this).attr('followup_id'),
			customer_id = $(_this).attr('customer_id');
		if($(_this).hasClass('disabled')){
			return false;
		} else {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=done_followup', {
				'followup_id' : followup_id,
				'customer_id' : customer_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.data_central.load_activity(customer_id, {});
				$Core.data_central.load_desktop_followups({});
			}, 'json');
		}
		return false;
	}, load_desktop_followups : (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if($_adata.hasOwnProperty(field) == false){
					$_adata[field] = $(_elem).val();
				}
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_desktop_followups', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_followups_desktop').html(respJson.html);
			$('.total_followups_desktop').html(respJson.total_record);
			setTimeout(() => {
				const verticalExample = document.getElementById('followups_desktop');
				if (verticalExample) {
					new PerfectScrollbar(verticalExample, {
						wheelPropagation: false
					});
				}
			},500);
		}, 'json');
	}, set_view_desktop_followup: (_this, e) => {
		e.preventDefault();
		var tp = $(_this).attr('tp');
		$('.hnnyecGoRk.active').removeClass('active');
		$(_this).addClass('active');
		$Core.data_central.load_desktop_followups({'tp' : tp});
		return false;
	}, load_converted_rates : (options) => {
		var campaign_id = $("select.search_field[name='campaign_id']").val(),
			$_adata = options || {};
		$_adata['campaign_id'] = campaign_id
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_converted_rates', $_adata, function(respJson){
			$('#'+'holder_converted_rates').html(respJson.html);
		}, 'json');
	}
};
$Core.manager_central = {
	do_search: function (_this,e) {
		e.preventDefault();
		var item_load_ajax = $(_this).closest(".item_load_ajax");
		$(".ajax",item_load_ajax).removeClass("loaded");
		_autoload();
	}
}