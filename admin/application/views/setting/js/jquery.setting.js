function open_message(_this){
	vietiso_loading(1);
	$.post(path_ajax_script + '/index.php?mod='+mod+'&act=open_message', {}, function(html){
		vietiso_loading(0);
		makepopup('auto','auto',html, 'open_message');
		$('#'+'open_message').css('top', '100px');
	});
	return false;
}
function add_message(_this){
	var _validated = 0,
		_form = $(_this).closest('form');
	if($('input.required', _form).length){
		$('input.required', _form).each(function(){
			if($Core.util.isEmpty($(this).val())){
				_validated++;
				$(this).focus();
				return false;
			}
		});
	}
	if(_validated==0){
		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=add_message',
			dataType: 'html',
			success: function(respJson){
				window.location.reload();
			}
		});
	}
}
function delete_message(setting){
	vietiso_loading(1);
	var $_adata = {'action':'delete','setting':setting};
	$.post(path_ajax_script + '/index.php?mod='+mod+'&act=add_message', $_adata, function(html){
		vietiso_loading(0);
		window.location.reload();
	});
}
function mailconfig_active(mail_type){
	vietiso_loading(1);
	var $_adata = {'mail_type':mail_type};
	$.post(path_ajax_script + '/index.php?mod='+mod+'&act=mailconfig_active', $_adata, function(html){
		vietiso_loading(0);
		window.location.reload();
	});
}
function send_mailconfig_test(_this){
	var mail_type = $(_this).attr('mail_type'),
		$_adata = {'mail_type':mail_type};
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=sendmail_test', $_adata, function(html){
		vietiso_loading(0);
		if(html.indexOf('success') >= 0){
			var tmp = html.split('|||');
			$Core.alert.success(tmp[1]);
		} else {
			var tmp = html.split('|||');
			$Core.alert.error(tmp[1]);
		}
	});
}
function handler_captcha_change(_this){
	if($('.captcha_type:checked').val()=='reCAPTCHA'){
		$('.recaptcha__group').removeClass('hide');
	} else{
		$('.recaptcha__group').addClass('hide');
	}
}
function handler_oauth_login(oauth_type, status){
	var $_adata = {'oauth_type':oauth_type,'status':status};
	vietiso_loading(1);
	$.post(path_ajax_script + '/index.php?mod='+mod+'&act=handler_oauth_login', $_adata, function(html){
		vietiso_loading(0);
		window.location.reload(true);
	});
}
function open_setting(_this){
	var toId = $(_this).getAttr('toId', 'global'),
		_reload = $(_this).getAttr('_reload', '0'),
		for_id = $(_this).getAttr('for_id' , 0),
		parent_id = $(_this).getAttr('parent_id' , 0),
		setting_id = $(_this).attr('setting_id'),
		setting_type = $(_this).attr('setting_type'),
		$_adata = {
			'setting_id':setting_id,
			'setting_type':setting_type,
			'toId':toId,
			'_reload':_reload,
			'for_id':for_id,
			'parent_id' : parent_id
		};
	if(setting_type == "_MEETING_ROOM") {
		var office_id = $(_this).getAttr('office_id' , 0);
		$_adata["office_id"] = office_id;
	}
	toggleIndicatior(1);
	$.post(path_ajax_script+'/index.php?mod=setting&act=open_setting', $_adata, function(respJson){
		toggleIndicatior(0);
		makepopup('auto','auto', respJson.html, 'open_setting_'+setting_type);
		if(respJson.callback) eval(respJson.callback);
	}, 'json');
	return false;
}
function save_setting(_this){
	var toId = $(_this).attr('toId'),
		_reload = $(_this).attr('_reload'),
		$_form = $(_this).closest('form'),
		setting_id =  $(_this).attr('setting_id'),
		setting_type =  $(_this).attr('setting_type'),
		$_adata = {'setting_id':setting_id,'setting_type':setting_type};
	var _validated = 0;
	if($('.input.required,select.required',$_form).length){
		$('.input.required,select.required',$_form).each((_i, _elem) => {
			if($Core.util.isEmpty($(_elem).val())){
				_validated++;
				$(_elem).focus();
				return false;
			}
		});
	}
	if($('.isoTextArea',$_form).length){
		$('.isoTextArea', $_form).each((_i, _elem) => {
			var name = $(_elem).data('name'),
				editorId = $(_elem).attr('id');
			$_adata[name] = $Core.util.getTextAreaContent(editorId);
		});
	}
	//alert(_validated); return false;
	if(_validated==0){
		toggleIndicatior(1);
		$_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=setting&act=save_setting',
			data: $_adata,
			dataType: 'html',
			success: function (setting_id) {
				toggleIndicatior(0);
				if(_reload==1){
					$.post(path_ajax_script+'/index.php?mod=setting&act=load_select_setting', {
						'setting_id' : setting_id,
						'setting_type' : setting_type
					}, function(html){
						$('#'+toId).html(html).trigger('change');
					});
				} else {
					if(act == "agency") {
						$Core.setting.load_list_agency("_AGENCY",{'loading':0});
					}else{
						load_list_setting(setting_type,{'loading':0});
					}
				}
				$Core.alert.success('Saved !');
				$Core.popup.close($_form.closest(".modal"));
			}
		});
	}
	return false;
}
function delete_setting(_this){
	var setting_id =  $(_this).attr('setting_id'),
		setting_type =  $(_this).attr('setting_type');
	$Core.alert.confirm("Xác nhận xóa", "Bạn có chắc chắn muốn xóa nội dung này", function(){
		toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod=setting&act=save_setting&action=_delete',
			{'setting_type':setting_type,'setting_id':setting_id},
			function(html){
				toggleIndicatior(0);
				if(html.indexOf('_invalid') >= 0){
					$Core.alert.error('Errors');
				}else{
					if(act == "agency") {
						$Core.setting.load_list_agency("_AGENCY",{});
					}else{
						load_list_setting(setting_type,{});
					}
				}
				_this.dialog( "close" );
			}
		);
	});
}

$().ready(function(){
	// $_document.on('click','.addLine,.deleteLine',function(ev){
	// 	var $_this = $(this);
	// 	if($_this.hasClass('deleteLine')){
	// 		if($('.trButton').length > 1){
	// 			$_this.closest('tr').remove(); 
	// 		}
	// 	}else{
	// 		var clone = $('.trButton:last').clone();
	// 		$('input[type=checkbox]',clone).removeAttr('checked');
	// 		$('select,input[type=text]',clone).val("");
	// 		$('.trButton:last').after(clone);
	// 	}
	// 	if($('.trButton').length > 1){
	// 		$('.trButton').each(function(i){
	// 			var _this = $(this);
	// 			$('.button_status',_this).attr('name','buttons['+i+'][status]');
	// 			$('.button_type',_this).attr('name','buttons['+i+'][type]');
	// 			$('.button_name',_this).attr('name','buttons['+i+'][name]');
	// 			$('.button_value',_this).attr('name','buttons['+i+'][value]');
	// 		});
	// 	}
	// 	return false;
	// });
	// $Core.org_chart.init_draggable();
});
$Core.property = {
	set_status: function(_this, e){
		var property_id = $(_this).attr('property_id'),
			is_trash = $(_this).is(':checked') ? 0 : 1;
		$.post(path_ajax_script + '/index.php?mod='+mod+'&act=set_trashed', {
			'property_id':property_id, 
			'is_trash':is_trash
		}, function(html){
			
		});
	}, set_show_calendar: function(_this, e){
		var property_id = $(_this).attr('property_id'),
			is_calendar = $(_this).is(':checked') ? 1 : 0;
		$.post(path_ajax_script + '/index.php?mod='+mod+'&act=set_show_calendar', {
			'property_id':property_id, 
			'is_calendar':is_calendar
		}, function(html){
			
		});
	}, set_status_moc_point: function(_this, e){
		var property_id = $(_this).attr('property_id'),
			status_moc_point = $(_this).is(':checked') ? 1 : 0;
		$.post(path_ajax_script + '/index.php?mod='+mod+'&act=set_status_moc_point', {
			'property_id':property_id, 
			'status_moc_point':status_moc_point
		}, function(html){
			
		});
	}, get_select_worksheets: function(_this, e){
		var uid = $(_this).attr('uid'),
			spreadsheetId = $(_this).val();
		$('.slb_worksheets_'+uid).html('<option>Loading...</option>');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=get_select_worksheets', {
			'spreadsheetId' : spreadsheetId
		}, function(respJson){
			$(_this).val(respJson.spreadsheetId);
			$('.slb_worksheets_'+uid).html(respJson.html);
		}, 'json');
	}, do_select_worksheets: function(_this, e){
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			spreadsheetId = $('.spreadsheetId_'+uid).val();
		if(!$Core.util.isEmpty(spreadsheetId)){
			$('.spreadsheetId_'+uid).trigger('change');
		} else {
			$('.spreadsheetId_'+uid).focus();
		}
		return false;
	}, open_setting_field: function(_this, e){
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			spreadsheetId = $('.spreadsheetId_'+uid).val(),
			sheet_name = $('.slb_worksheets_'+uid).val(),
			columns = $('.field_worksheets_'+uid).val();
		$.post(path_ajax_script + '/index.php?mod='+mod+'&act=open_setting_field', {
			'uid':uid, 
			'spreadsheetId':spreadsheetId, 
			'sheet_name':sheet_name, 
			'columns':columns
		}, function(html){
			vietiso_loading(0);
			if(html.indexOf('_invalid') >= 0){
				$Core.alert.error("Error !");
			} else {
				$Core.popup.open('auto', 'auto', html, 'open_setting_field');
			}
		});
		return false;
	}, get_setting_field: function(_this, e){
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			form = $(_this).closest('form');
		vietiso_loading(1);
		form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=get_setting_field',
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				if(respJson.html.indexOf('_invalid') >= 0){
					$Core.alert.error("Error !");
				} else {
					$('.field_worksheets_'+uid).empty().val(respJson.html);
					$Core.popup.close(form.closest('.modal'));
				}
			}
		});	
		return false;
	}, add_folder_price_sheets: function(_this, e){
		e.preventDefault();
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_folder_price_sheets', {}, function(html){
			vietiso_loading(0);
			$('.group_price_sheets:last').after(html);
		});
		return false;
	}, add_folder_interior_ns: function(_this, e){
		e.preventDefault();
		vietiso_loading(1);
		var property_id = $(_this).attr('property_id');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_folder_interior_ns', {
			'property_id' : property_id
		}, function(html){
			vietiso_loading(0);
			$('.interior_ns_pa_'+property_id+':last').after(html);
		});
		return false;
	}, add_group_zalo: function(_this, e){
		e.preventDefault();
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_group_zalo', {}, function(html){
			vietiso_loading(0);
			$('.group_zalo:last').after(html);
		});
		return false;
	}, storage_cache: function (_this, e){
		var property_type = $(_this).attr('property_type'),
			$_adata = {'property_type':property_type}; 
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=ajax&act=storage_cache', $_adata, function(html){
			vietiso_loading(0);
			if(html.indexOf('_success')>= 0){
				alertify.success('Success !');
			} else {
				alertify.error('Error !');
			}
		});
		return false;
	}, storage_cache_all: function (_this, e){
		var property_type = $(_this).attr('property_type'),
			$_adata = {'property_type':property_type}; 
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=ajax&act=storage_cache_all', $_adata, function(html){
			vietiso_loading(0);
			if(html.indexOf('_success')>= 0){
				alertify.success('Success !');
			} else {
				alertify.error('Error !');
			}
		});
		return false;
	}, load_list_agency : function (property_type, options){
		var $_adata = options || {};
		$_adata['property_type'] = property_type;
		vietiso_loading(loading);	
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_list_agency', $_adata, function(html){
			vietiso_loading(0);
			$('.holderPropertyType_agency').html(html);
			$(".holderPropertyType_agency").sortable({
				connectWith: ".holderPropertyType_agency",
				handle: ".mySortableHandler",
				update: function (event, ui) {
					var orderNo = $(this).sortable('toArray'),
					$_adata = {"orderNo":orderNo};
					vietiso_loading(1);	
					$.post(path_ajax_script+"/index.php?mod=ajax&act=save_property&action=_saveorder",$_adata,function(html){
						vietiso_loading(0);	
					});
				}
			}).disableSelection();
		});
	}, open_hidden_stock : function (_this, e){
		e.preventDefault();
		var agency_hidden_stock_id = $(_this).attr('agency_hidden_stock_id'),
			type = $(_this).data('type');
		vietiso_loading(1);
		$.post(path_ajax_script + '/index.php?mod='+mod+'&act=open_hidden_stock', {
			'agency_hidden_stock_id':agency_hidden_stock_id,'type':type
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_hidden_stock');
		});
		return false;
	}, save_hidden_stock : function (_this, e){
		e.preventDefault();
		var agency_hidden_stock_id = $(_this).attr('agency_hidden_stock_id'),
			type = $(_this).data('type'),
			action = $(_this).data('action'),
			_form = $(_this).closest("form");
		if(action == "delete") {
			if(confirm("Bạn có chắc chắn muốn xoá tài khoản này?")) {
				vietiso_loading(1);
				$.ajax({
					type : 'POST',
					url : path_ajax_script+'/index.php?mod='+mod+'&act=save_hidden_stock',
					data: {'agency_hidden_stock_id':agency_hidden_stock_id,'type':type,'action':action},
					dataType:'html',
					success : function(respJson){
						vietiso_loading(0);
						window.location.reload(true);
					}
				});	
			}
		}else{
			vietiso_loading(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=save_hidden_stock',
				data: {'agency_hidden_stock_id':agency_hidden_stock_id,'type':type,'action':action},
				dataType:'html',
				success : function(respJson){
					vietiso_loading(0);
					window.location.reload(true);
				}
			});	
		}
		return false;
	}, select_block: function(_this, e){
		var project_id = $(_this).val(),
			toId = $(_this).attr('toId');
		$.post(path_ajax_script+'/index.php?mod=shop&act=load_block', {
			'project_id' : project_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html).trigger("chosen:updated");
			$('#slb_Building_Id').empty().trigger("chosen:updated");
		});
	}, toggleSwitch: function(_this, e){
		e.preventDefault();
		var action = $(_this).attr("action"),
			key = $(_this).attr('key'),
			_table = $(_this).closest("table");
		if(action == "hide") {
			if($(".switch_"+key+":checked",_table).length > 0) {
				$(".switch_"+key,_table).each(function(index,_elm){
					$(_elm).prop("checked",false).trigger("change");
				});				
			}
		}else if(action == "show") {
			$(".switch_"+key+":not(:checked)",_table).each(function(index,_elm){
				$(_elm).prop("checked",true).trigger("change");
			});
		}
	}, open_field_data_center : function (_this, e){
		e.preventDefault();
		var field_data_center_id = $(_this).attr('field_data_center_id');
		vietiso_loading(1);
		$.post(path_ajax_script + '/index.php?mod='+mod+'&act=open_field_data_center', {
			'field_data_center_id':field_data_center_id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_field_data_center');
		});
		return false;
	}, save_field_data_center : function (_this, e){
		e.preventDefault();
		var field_data_center_id = $(_this).attr('field_data_center_id'),
			action = $(_this).data('action'),
			_form = $(_this).closest("form");
		if(action == "delete") {
			if(confirm("Bạn có chắc chắn muốn xoá trường này?")) {
				vietiso_loading(1);
				$.ajax({
					type : 'POST',
					url : path_ajax_script+'/index.php?mod='+mod+'&act=save_field_data_center',
					data: {'field_data_center_id':field_data_center_id,'action':action},
					dataType:'html',
					success : function(respJson){
						vietiso_loading(0);
						window.location.reload(true);
					}
				});	
			}
		}else{
			vietiso_loading(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=save_field_data_center',
				data: {'field_data_center_id':field_data_center_id,'action':action},
				dataType:'html',
				success : function(respJson){
					vietiso_loading(0);
					window.location.reload(true);
				}
			});	
		}
		return false;
	}, open_agency_crawl: function (_this){
		var agency_id = $(_this).attr('agency_id'),
			stock_type = $(_this).attr('stock_type'),
			$_adata = {
				'agency_id':agency_id,
				'stock_type':stock_type,
			};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=setting&act=open_agency_crawl', $_adata, function(respJson){
			vietiso_loading(0);
			makepopup('auto','auto', respJson.html, respJson.uid);
			if(respJson.callback) eval(respJson.callback);
		}, 'json');
		return false;
	}, addColor: function (_this){
		var _type=$(_this).attr("_type"),
			project_id = $(_this).attr("project_id");
		if(_type == "color_sold") {
			$(_this).parent().before(`<div class="col-md-2">
			<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[`+project_id+`][color_sold][]" value="" placeholder="#ff0000" maxlength="255">
		</div>`);
		}else{
			$(_this).parent().before(`<div class="col-md-2">
			<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[`+project_id+`][color_dq][]" value="" placeholder="#ff0000" maxlength="255">
		</div>`);
		}
		return false;
	}, toggle: function (_this,e){
		var toid=$(_this).attr("toId");
		if($(_this).is(":checked")) {
			$("#"+toid).removeClass("d-none");
		}else{
			$("#"+toid).addClass("d-none");
		}
		return false;
	},
	save_agency_crawl: function (_this,e){
		e.preventDefault();
		var _modal = $(_this).closest('_modal'),
			_form = $(_this).closest('form'),
			agency_id = $(_this).attr("agency_id"),
			stock_type = $(_this).attr("stock_type"),
			$_adata = {
				'agency_id':agency_id,
				'stock_type':stock_type,
			};
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=save_agency_crawl',
			data: $_adata,
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				if(respJson.result){
					alertify.success('Success !');
				}else{
					alertify.error('Error!');
				}
				$Core.popup.close(_form.closest('.modal'));
//				window.location.reload(true);
			}
		});	
		return false;
	},
	setStatusCrawl: function (_this,e){
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id"),
			block_id = $(_this).attr("block_id"),
			project_id = $(_this).attr("project_id"),
			stock_type = $(_this).attr("stock_type"),
			is_crawl = $(_this).is(":checked") ? 1 : 0;
			$_adata = {
				'agency_id':agency_id, 'block_id' : block_id,'project_id' : project_id, 'is_crawl': is_crawl, 'stock_type': stock_type
			};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=setStatusCrawl', $_adata, function(respJson){
			vietiso_loading(0);
			if(respJson.result){
				alertify.success('Success !');
			}else{
				alertify.error('Error!');
			}
		}, 'json');
		return false;
	},
	crawlLowfloor: function (_this,e){
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id");
			$_adata = {'agency_id':agency_id};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=ajCrawlLowfloor', $_adata, function(respJson){
			vietiso_loading(0);
			if(respJson.result){
				alertify.success('Success !');
			}else{
				alertify.error('Error!');
			}
		}, 'json');
		return false;
	},
	start_config_column: function (_this,e){
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id"),
			block_id = $(_this).attr("block_id"),
			project_id = $(_this).attr("project_id"),
			stock_type = $(_this).attr("stock_type"),
			_parent = $(_this).closest(".group_price_sheets"),
			sheetID = $("input.sheetID",_parent).val(),
			sheet_name = $("input.sheet_name",_parent).val();
			$_adata = {'agency_id':agency_id, 'block_id' : block_id,'project_id' : project_id, 'sheetID': sheetID, 'stock_type': stock_type, 'sheet_name': sheet_name};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=start_config_column', $_adata, function(respJson){
			vietiso_loading(0);
			if(respJson.result){
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			}else{
				alertify.error('Error!');
			}
		}, 'json');
		return false;
	},
	do_config_column: function (_this,e){
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id"),
			block_id = $(_this).attr("block_id"),
			project_id = $(_this).attr("project_id"),
			stock_type = $(_this).attr("stock_type"),
			sheet_name = $(_this).attr("sheet_name"),
			_form = $(_this).closest("form");
			$_adata = {'agency_id':agency_id, 'block_id' : block_id,'project_id' : project_id,'stock_type': stock_type, 'sheet_name': sheet_name};
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=do_config_column',
			data: $_adata,
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				if(respJson.result){
					alertify.success('Success !');
				}else{
					alertify.error('Error!');
				}
//				$Core.popup.close(_form.closest('.modal'));
//				window.location.reload(true);
			}
		});	
		return false;
	},
	
	open_field_config_price : function (_this, e){
		e.preventDefault();
		var field_config_price_id = $(_this).attr('field_config_price_id');
		vietiso_loading(1);
		$.post(path_ajax_script + '/index.php?mod='+mod+'&act=open_field_config_price', {
			'field_config_price_id':field_config_price_id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_field_config_price');
		});
		return false;
	},	
	save_field_config_price : function (_this, e){
		e.preventDefault();
		var field_config_price_id = $(_this).attr('field_config_price_id'),
			action = $(_this).data('action'),
			_form = $(_this).closest("form");
		if(action == "delete") {
			if(confirm("Bạn có chắc chắn muốn xoá trường này?")) {
				vietiso_loading(1);
				$.ajax({
					type : 'POST',
					url : path_ajax_script+'/index.php?mod='+mod+'&act=save_field_config_price',
					data: {'field_config_price_id':field_config_price_id,'action':action},
					dataType:'json',
					success : function(respJson){
						vietiso_loading(0);
						alertify.success(respJson.msg);
						setTimeout(function(){
							window.location.reload(true);
						},500);
					}
				});	
			}
		}else{
			vietiso_loading(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=save_field_config_price',
				data: {'field_config_price_id':field_config_price_id,'action':action},
				dataType:'json',
				success : function(respJson){
					vietiso_loading(0);
					if(respJson.result) {
						alertify.success(respJson.msg);
						setTimeout(function(){
							window.location.reload(true);
						},500);
					}else{
						alertify.error(respJson.msg);
					}
					
				}
			});	
		}
		return false;
	}, open_ultilities: (_this, e) => {
		var _type = $(_this).attr('_type'),
			for_id = $(_this).attr('for_id');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_ultilities', {
			'_type':_type, 
			'for_id' : for_id
		}, function(respJson){
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			if(_type == "_sort") {
				$(".drag_ultilites",$("#"+respJson.uid)).sortable({
					connectWith: ".drag_ultilites",
					handle: ".btn_drag",
					items: '.ultilites-item',
					update: function (event, ui) {
						var orderNo = $(this).sortable('toArray'),
						$_adata = {"orderNo":orderNo};
						$("input[name='permiss_ultilities']",$("#open_ultilities_"+for_id)).val(JSON.stringify(orderNo))
					}
				}).disableSelection();
			}			
		}, 'json');
	},	save_permiss_ultilities: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			for_id = $(_this).attr('for_id'),
			permiss_ultilities = new Array(),
			$_adata = {for_id:for_id};
		
		if($(".ultilites-item",_form).length > 0) {
			$(".ultilites-item",_form).each(function(index,elm){
				permiss_ultilities.push($(elm).data("id"));
			});
		}
		$_adata["permiss_ultilities"] = permiss_ultilities;
		console.log($_adata);
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=save_permiss_ultilities', $_adata, function(respJson){
			vietiso_loading(0);
			$Core.popup.close(_form.closest('.modal'));
//			_form.closest('.modal').remove();
		}, 'json');
		return false;
	},
	save_list_ultilities: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			for_id = $(_this).attr('for_id'),
			$_adata = {for_id:for_id};	
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=save_list_ultilities',
			data: $_adata,
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				$Core.popup.close(_form.closest('.modal'));
			}
		});	
		return false;
	},
	add_info_agency: function(_this, e){
		e.preventDefault();
		var group_item = $(_this).closest(".group_item"),
			tp = $(_this).attr("tp");
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_info_agency', {tp:tp}, function(html){
			vietiso_loading(0);
			$(".item:last-child .btn_add_item",group_item).addClass("d-none");
			group_item.append(html);
		});
		return false;
	},
	delete_info_agency: function(_this, e){
		e.preventDefault();
		var group_item = $(_this).closest(".group_item");
		$Core.alert.confirm("Xác nhận xóa", "Bạn có chắc chắn muốn xóa nội dung này", function(){
			toggleIndicatior(1);
			$(_this).closest(".item").remove();
			$(".item:last-child .btn_add_item",group_item).removeClass("d-none");
			toggleIndicatior(0);
		});
		
		return false;
	},
}
$Core.permiss = {
	open: function(_this, e){
		var for_id = $(_this).attr('for_id'),
			profile_type = $(_this).attr('profile_type');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_permiss', {
			'for_id' : for_id,
			'profile_type':profile_type
		}, function(respJson){
			$Core.popup.open('auto', 'auto', respJson.html, 'open_permiss');
		}, 'json');
	},
	storage: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			for_id = $(_this).attr('for_id'),
			profile_type = $(_this).attr('profile_type');
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_permiss',
			data: {'for_id':for_id,'profile_type':profile_type},
			dataType:'html',
			success : function(respJson){
				vietiso_loading(0);
				$Core.popup.close(_form.closest('.modal'));
			}
		});	
		return false;
	},
};
$Core.account = {
	open: function(_this, e){
		vietiso_loading(1);
		var key = $(_this).data('key'),
			action = $(_this).data("action");
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_account', {
			'key' : key,'action' : action
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_account');
		});
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			key = $(_this).data("key"),
			$_adata = {'key':key};
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=save_account',
				data : $_adata,
				dataType:'json',
				success : function(respJson){
					vietiso_loading(0);
					_form.clearForm();
					_form.resetForm();
					if(respJson.msg.indexOf('_success') >= 0){
						$Core.alert.success('Thành công !');
						setTimeout(function(){
							window.location.reload(true);
						},500);
					} else {
						$Core.alert.error('Không thành công !');
					}
				}
			});	
		}
		return false;
	},
	delete: function (_this,e) {
		e.preventDefault();
		if(confirm("Bạn có chắc chắn muốn xoá tài khoản này?")) {
			vietiso_loading(1);
			var key = $(_this).data("key");
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete_account', {'key':key}, function(respJson){
				vietiso_loading(0);
				if(respJson.msg.indexOf('_success') >= 0){
					$Core.alert.success('Xóa thành công !');
				} else {
					$Core.alert.error('Lỗi!');
				}
				setTimeout(function(){
					window.location.reload(true);
				},500);
			},'json');
		}
	}
}
$Core.org_chart = {
	init_draggable: () => {
		if($('.control-handle:not(.ui-draggable-fh)').length){
			$('.control-handle:not(.ui-draggable-fh)').each((_i, _elem) => {
				$(_elem).addClass('ui-draggable-fh').draggable({
					containment: "#tree-level-container",
					start: function() {
						$(this).data("dragging", true);
					},
					drag: function() {
						$Core.org_chart.drawTreeConnectors();
					},
					stop: function() {
						// Đánh dấu rằng handle đã được người dùng điều chỉnh
						$(this).data("userMoved", true);
						 $(this).data('dragging', false);
						$Core.org_chart.drawTreeConnectors();
						$Core.org_chart.save_node();
					}
				});	
			});
		}
	},
	open_org: function(id,level,parent_id,common_parent_id) {
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_org', {
			'id' : id,'level' : level,'parent_id':parent_id,'common_parent_id':common_parent_id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_org');
		});
	},
	render_node: function(_this,e) {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			level = $("input[name=level]",_form).val();
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each(function(){
				if($Core.util.isEmpty($(this).val())){
					_validated++;
					$(this).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=render_node',
				dataType: 'json',
				success: function(respJson){
					vietiso_loading(0);
					if($(".tree-level[data-level='"+level+"']").length > 0) {
						if($("#"+respJson.id).length > 0) {
							$("#"+respJson.id).replaceWith(respJson.html);
						}else{
							$(".tree-level[data-level='"+level+"']").append(respJson.html);
						}
					}else{
						$("#tree-level-container").append(`<div class="tree-level" data-level="`+level+`">
							`+respJson.html+`
						</div>`);
					}
					$Core.org_chart.initDraggable("#"+respJson.id);
					$Core.org_chart.drawTreeConnectors();		
					$Core.org_chart.init_draggable();
					$Core.org_chart.save_node();
					$("button[data-dismiss='modal']",_form).trigger("click");
				}
			});
		}
	},
	initDraggable: function (elem) {
		$(elem).draggable({
			containment: "#tree-level-container",
			drag: function(event, ui) {
				// Cập nhật đường nối khi node di chuyển
			  	$Core.org_chart.drawTreeConnectors();	
			},
			stop: function(event, ui) {		
				$Core.org_chart.drawTreeConnectors();
				var $container = $("#tree-level-container");
			  	var containerWidth = $container.width();
			  	var containerHeight = $container.height();
			  	// Tính vị trí theo %, dựa trên kích thước container
			  	var leftPercent = (ui.position.left / containerWidth) * 100;
			  	var topPercent = (ui.position.top / containerHeight) * 100;
				
			  	// Cập nhật lại CSS của node theo phần trăm
			  	/*$(elem).css({
				 	left: leftPercent + "%",
				 	top: topPercent + "%"
			  	});*/						
			  	$Core.org_chart.save_node();
			}
		});
		$(".control-handle").draggable({
          containment: "#tree-level-container",
          drag: function(event, ui){ $Core.org_chart.drawTreeConnectors(); },
          stop: function(event, ui){ $Core.org_chart.drawTreeConnectors(); }
        });
	},
	// Hàm tạo path với các đoạn phải theo ngang và dọc (auto right-angle)
	createRightAnglePath: function (points) {
		var pathPoints = [];
		if (points.length === 0) return pathPoints;
		pathPoints.push(points[0]);
		for (var i = 0; i < points.length - 1; i++) {
			var A = points[i];
			var B = points[i + 1];
			// Nếu điểm A và B không thẳng hàng (cùng x hoặc cùng y)
			// ta tạo điểm trung gian: ở đây chọn đi ngang rồi dọc.
			if (A.x !== B.x && A.y !== B.y) {
				var I = { x: B.x, y: A.y };
				// Thêm điểm trung gian nếu chưa có
				if (!(Math.abs(pathPoints[pathPoints.length - 1].x - I.x) < 1 &&
				Math.abs(pathPoints[pathPoints.length - 1].y - I.y) < 1)) {
					pathPoints.push(I);
				}
			}
			pathPoints.push(B);
		}
		return pathPoints;
	},
	// Hàm vẽ tất cả các đường nối kiểu "elbow"
	drawTreeConnectors: function () {
		$("#tree-connectors").empty();
		var containerOffset = $("#tree-level-container").offset();
		$(".node",$("#tree-level-container")).each(function(index, elm) {
			var $child = $(elm);
			var level = $child.data("level");
//			console.log($child);
			if (level > 0) {
				// Nếu là node con riêng (individual)
				if ($child.attr("data-parent-id")) {
					var parentID = $child.attr("data-parent-id");
					var $parent = $('.node[data-id="' + parentID + '"]',$("#tree-level-container"));
					if ($parent.length) {
						$Core.org_chart.drawElbowLineBetween($parent, $child, containerOffset, "individual");
					}
				}
				// Nếu là node con chung (common)
				if ($child.attr("data-common-parent-ids")) {
					var arr = $child.attr("data-common-parent-ids").split(",");
					arr.forEach(function(pid) {
						var $parent = $('.node[data-id="' + pid + '"]',$("#tree-level-container"));
						if ($parent.length) {
							$Core.org_chart.drawElbowLineBetween($parent, $child, containerOffset, "common");
						}
					});
				}
			}
		});		
	},
	drawElbowLineBetween:function ($parent, $child, containerOffset, type) {		
		var parentPos = $parent.position();
		var pX = parentPos.left + $parent.outerWidth() / 2;
		var pY = parentPos.top + $parent.outerHeight() / 2  + 20;
		var parentPoint = { x: pX, y: pY };

		// Tọa độ điểm kết nối của node con: trung tâm trên
		var childPos = $child.position();
		var cX = childPos.left + $child.outerWidth() / 2;
		var cY = childPos.top;
		var childPoint = { x: cX, y: cY };
		// Nếu handle chưa tồn tại, tạo handle mới
		var parentId = $parent.data('id');
		var childId = $child.data('id');
		var $point = 'handle_'+ $parent.data("id") + '_'+$child.data('id');
			if ($("#" + $point).length === 0) {
			  var $handle = $("<div class='control-handle'></div>");
			  $handle.attr("id", $point);
				console.log($handle);
			  $("#tree-level-container").append($handle);  
			  // Mặc định: handle chưa được người dùng kéo (false)
			  $handle.data("userMoved", false);
			  
			}		
            // Lấy handle của kết nối hiện tại
            var $handle = $("#" + $point);
            
            // Nếu handle chưa được điều chỉnh, cập nhật vị trí mặc định dựa theo endpoints:
            // mặc định: hₓ = Pₓ, h_y = (P_y + C_y) / 2
            if (!$handle.data("userMoved") && !$handle.data("dragging") && !$handle.hasClass("dragged")) {
				// Cập nhật vị trí mặc định của handle
				var defaultHX = pX;
				var defaultHY = (pY + cY) / 2;
				var hW = $handle.outerWidth();
				var hH = $handle.outerHeight();
				$handle.css({
					left: (defaultHX - hW / 2) + "px",
					top: (defaultHY - hH / 2) + "px"
				});
			}
            
            // Lấy vị trí hiện tại (center) của handle
            var handlePos = $handle.position();
            var hWidth = $handle.outerWidth();
            var hHeight = $handle.outerHeight();
            var hX = handlePos.left + hWidth / 2;
            var hY = handlePos.top + hHeight / 2;
            var controlPoint = { x: hX, y: hY };
            
            // Xây dựng chuỗi lệnh cho SVG path theo thiết kế:
            // 1. Từ parent's point đến (controlPoint.x, parent's point.y) → đoạn ngang
            // 2. Từ (controlPoint.x, parent's point.y) đến (controlPoint.x, controlPoint.y) → đoạn đứng
            // 3. Từ (controlPoint.x, controlPoint.y) đến (childPoint.x, controlPoint.y) → đoạn ngang
            // 4. Từ (childPoint.x, controlPoint.y) đến childPoint → đoạn đứng
            var d = "M " + parentPoint.x + " " + parentPoint.y + " " +
                    "L " + controlPoint.x + " " + parentPoint.y + " " +
                    "L " + controlPoint.x + " " + controlPoint.y + " " +
                    "L " + childPoint.x + " " + controlPoint.y + " " +
                    "L " + childPoint.x + " " + childPoint.y;
            var $svg = $("#tree-connectors");
            // Nếu đường nối (path) chưa tồn tại, tạo mới
            var pathId = "path_" + parentId + "_" + childId;
            var $path = $("#" + pathId);
            if ($path.length === 0) {
              var newPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
              newPath.setAttribute("id", pathId);
              newPath.setAttribute("stroke", "red");
              newPath.setAttribute("stroke-width", "2");
              newPath.setAttribute("fill", "none");
              $svg.append(newPath);
              $path = $("#" + pathId);
            }		
		$path.attr("d", d);	
		
	},
	// Hàm thu thập thông tin của tất cả các node và trả về mảng đối tượng
	getNodesJSON : function () {
		var nodes = [],points = [],data = {},
			containerWidth = $("#tree-level-container").width(),
			containerHeight = $("#tree-level-container").height();
		$(".node",$("#tree-level-container")).each(function() {
			var $node = $(this);
			var pos = $node.position(); // { top: ..., left: ... } (giá trị pixel)
			var leftPercent = (pos.left / containerWidth) * 100;
			var topPercent = (pos.top / containerHeight) * 100;
			var nodeData = {
				id: $node.data("id"),
				text: $node.text().trim(),
				level: $node.data("level"),
				parentId: $node.data("parent-id") || null,
				commonParentIds: $node.data("common-parent-ids") || null,
				// Nếu cần lưu thông tin tùy biến như hướng kết nối từ node cha
				connSide: $node.data("conn-side") || null,
				role_id: $node.data("role_id") || null,
				staff_id: $node.data("staff_id") || null,
				text_name: $node.data("text_name") || null,
				// Vị trí tính theo vị trí tương đối trong container
				position: {"top":topPercent,"left":leftPercent}, // gồm { top: ..., left: ... }
				width: $node.outerWidth(),
				height: $node.outerHeight()
			};

			nodes.push(nodeData);
		});
		$(".control-handle",$("#tree-level-container")).each(function() {
			var $point = $(this);
			var posPoint = $point.position(); // { top: ..., left: ... } (giá trị pixel)
			var leftPercent = (posPoint.left / containerWidth) * 100;
			var topPercent = (posPoint.top / containerHeight) * 100;
			var pointData = {
				id: $point.attr("id"),
				// Vị trí tính theo vị trí tương đối trong container
				position: {"top":topPercent,"left":leftPercent}, // gồm { top: ..., left: ... }
				width: $point.outerWidth(),
				height: $point.outerHeight()
			};
			points.push(pointData);
		});
		data["nodes"] = nodes;
		data["points"] = points;
		return JSON.stringify(data, null, 2);
	},
	save_node :function() {
		var jsonData = $Core.org_chart.getNodesJSON();
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=save_node', {
			'data_node' : jsonData
		}, function(respJson){
			
		},"json");
	}
}
$Core.setting = {
	open_domain : (_this, e) => {
		e.preventDefault();
		var domain_id = $(_this).attr('domain_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_domain', {
			'domain_id':domain_id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_domain');
		});
		return false;
	}, save_domain : (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			domain_id = $(_this).attr('domain_id'),
			action = $(_this).data('action');
		if(action == "delete") {
			if(confirm("Bạn có chắc chắn muốn xoá trường này?")) {
				vietiso_loading(1);
				$.ajax({
					type : 'POST',
					url : path_ajax_script+'/index.php?mod='+mod+'&act=save_domain',
					data: {'domain_id':domain_id,'action':action},
					dataType:'html',
					success : function(respJson){
						vietiso_loading(0);
						window.location.reload(true);
					}
				});	
			}
		}else{
			vietiso_loading(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=save_domain',
				data: {'domain_id':domain_id,'action':action},
				dataType:'html',
				success : function(respJson){
					vietiso_loading(0);
					window.location.reload(true);
				}
			});	
		}
		return false;
	}, select_block: (_this, e) => {
		var project_id = $(_this).val(),
			toId = $(_this).attr('toId');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_block', {
			'project_id' : project_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html);
		});
	}, storage_cache: (_this, e) => {
		console.log('storage_cache');
		var setting_type = $(_this).attr('setting_type'),
			$_adata = {'setting_type':setting_type}; 
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=setting&act=storage_cache', $_adata, function(html){
			vietiso_loading(0);
			if(html.indexOf('_success')>= 0){
				alertify.success('Success !');
			} else {
				alertify.error('Error !');
			}
		});
		return false;
	}, storage_cache_all: (_this, e) => {
		var setting_type = $(_this).attr('setting_type'),
			$_adata = {'setting_type':setting_type}; 
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=setting&act=storage_cache_all', $_adata, function(html){
			vietiso_loading(0);
			if(html.indexOf('_success')>= 0){
				alertify.success('Success !');
			} else {
				alertify.error('Error !');
			}
		});
		return false;
	}, get_worksheets: (_this, e) => {
		var spreadsheetId = $(_this).val(),
			$_adata = {"spreadsheetId": spreadsheetId};
		$.post(path_ajax_script+'/index.php?mod=setting&act=get_worksheets', $_adata, function(respJson){
			vietiso_loading(0);
			$('.slb_worksheets').html(respJson.html_worksheets);
		}, 'json');
	}, open_config_field : (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			setting_id = $(_this).attr('setting_id'),
			sheet_name = $('.slb_worksheets', _form).val(),
			spreadsheetId = $('.spreadsheetId', _form).val(),
			$_adata = {"spreadsheetId": spreadsheetId, 'setting_id' : setting_id, 'sheet_name' : sheet_name};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=setting&act=open_config_field', $_adata, function(respJson){
			vietiso_loading(0);
			makepopup('auto','auto', respJson.html, 'open_config_field');
		}, 'json');
		return false;
	}, save_config_field : (_this, e) => {
		e.preventDefault();
		var _error = 0,
			setting_id = $(_this).attr('setting_id')
			_form = $(_this).closest("form");
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
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=setting&act=save_config_field', 
				data:{"setting_id":setting_id},
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						alertify.success(respJson.msg);
						$('.close_pop', _form).trigger('click');						
					} else {
						alertify.error(respJson.msg);
					}
				}
			});
		}
		return false;
	}, addProperty: (_this, e) => {
        var toId = $(_this).getAttr('toId', 'global'),
		_reload = $(_this).getAttr('_reload', '0'),
		for_id = $(_this).getAttr('for_id' , 0),
		parent_id = $(_this).getAttr('parent_id' , 0),
		setting_id = $(_this).attr('setting_id'),
		setting_type = $(_this).attr('setting_type'),
		$_adata = {
			'setting_id':setting_id,
			'setting_type':setting_type,
			'toId':toId,
			'_reload':_reload,
			'for_id':for_id,
			'parent_id' : parent_id
		};
        $.ajax({
            type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=open_add_property',
			data: $_adata,
			dataType:'json',
			success : function(respJson){
                makepopup('auto','auto', respJson.html, 'open_setting_'+setting_type);
		        if(respJson.callback) eval(respJson.callback);
			}, error: function() { },
			complete: function() { }
        })
    }, editAddProperty: (_this, e) => {
        let type = $(_this).data('type');
        let objEl = $(_this).closest('.js-item-field');
        if (type == 'add') {
            $Core.setting.addPropertyToForm(_this, e, objEl);
        } else if (type == 'delele') {
            $Core.setting.deletePropertyToForm(_this, e, objEl);
        }   
    }, addPropertyToForm: (_this, e, objEl) => {
        let clone = objEl.clone();
        clone.find('input, select, textarea').val('');
        let groupOptionEl = clone.find('.js-parent-item').first();
        if (!groupOptionEl.hasClass('d-none')) {
            groupOptionEl.addClass('d-none');
            groupOptionEl.find('input, select, textarea').prop('disabled', true);
        }
        objEl.after(clone);
        let parentItem = objEl.closest('.js-parent-item');
        let child = parentItem.children(".js-item-field");
        objEl.find('.js-btn-minus-property').first().removeClass('d-none')
        child.each(function(index, el) {
            $(this).find('.js-btn-minus-property').first().removeClass('d-none');
            if ($(this).find('.js-order-item').first()) {
                $(this).find('.js-order-item').first().text(index+1);
            }
        });
    }, deletePropertyToForm: (_this, e, objEl) => {
        let parentItem = objEl.closest('.js-parent-item');
        objEl.remove();
        let child = parentItem.children(".js-item-field");
        
        if (child.length > 0) {
            child.each(function(index, el) {
                if (child.length <= 1) {
                    if (!$(this).find('.js-btn-minus-property').first().hasClass('d-none')) {
                        $(this).find('.js-btn-minus-property').first().addClass('d-none')
                    }
                }
                if ($(this).find('.js-order-item').first()) {
                    $(this).find('.js-order-item').first().text(index+1);
                }
            });
        }
    }, saveProperty: (_this, e) => {
        let elParentProperty = $('.js-parent-item-property');
        let childrentItemField = elParentProperty.children('.js-item-field');
        if (childrentItemField.length > 0) {
            childrentItemField.each(function() {
                let code = $(this).find('.js-field-code').val();
                $(this).find('.js-field-option').attr('name', 'option['+code+'][]');
            })
        }
        let $_form = $(_this).closest('form');
        let formData = $_form.serializeArray();
        let data = {};
        $.each(formData, function(_, field) {
            let name = field.name;
            if (name.endsWith("[]")) {
                name = name.slice(0, -2);
                if (!data[name]) {
                    data[name] = [];
                }
                data[name].push(field.value);
            } else {
                data[name] = field.value;
            }
        });
		var typeSelect =  JSON.parse(typeInputDynamic);
        data['typeArrAccept'] = typeSelect;
        $.ajax({
            type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=save_add_property',
			data: data,
			dataType:'json',
			success : function(respJson){
                let status = typeof respJson.status != 'undefined' ? respJson.status : 500;
                let msg = typeof respJson.msg != 'undefined' ? respJson.msg : 'Có lỗi xảy ra!!!';
                if (status == 200) {
                    $Core.alert.success(msg);
                    $Core.popup.close($_form.closest(".modal"));
                } else {
                    $Core.alert.error(msg);
                }
			},
            error: function() {
                $Core.alert.error('Có lỗi xảy ra!!! ');
            }, 
            complete: function() {

            }
        })
    }, addOption: (_this, e) => {
        let el = $(_this).find('.has-option');
        let elItem = $(_this).parents('.item-field');
        let value = $(_this).val();
		var typeSelect =  JSON.parse(typeInputDynamic);
        if ($.inArray(value, typeSelect) !== -1) {
            $(elItem).find('.group-option-item').removeClass('d-none');
            $(elItem).find('.group-option-item').find('input, select, textarea').prop('disabled', false);
        } else {
            $(elItem).find('.group-option-item').addClass('d-none');
        }
    },
}

