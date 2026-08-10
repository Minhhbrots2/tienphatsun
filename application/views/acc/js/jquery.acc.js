$(function(){
	$Core.acc.init();
	$Core.acc.autoload();
	if(MOD=='acc' && SUB == 'vat') $Core.vat.list_VAT({});
	if(MOD=='acc' && SUB == 'commission') $Core.commission.list({});
});
$_document.ajaxComplete(function() {
	$Core.acc.init();
});
$Core.acc = {
	init: function(){
		if($('input.autocomplete:not(.ui-autocomplete-input)').length){
			$('input.autocomplete:not(.ui-autocomplete-input)').each((_i, _elem) => {
				var _uid = $(_elem).attr('uid'),
					_url = $(_elem).data('source');
				$(_elem).autocomplete({
					source: _url,
					minLength: 1,
					select: function(event, ui){
						$(`.${_uid}_id`).val(ui.item.id);
					}
				});
			});
		}
	},
	autoload: () => {
		if($('.autoload:not(.loaded)').length){
			$('.autoload:not(.loaded)').each((_i, _elem) => {
				var url = $(_elem).data('url'),
					gId = $(_elem).getAttr('gId', ""),
					$_adata = $(_elem).data('options') || {};
				$_adata['gId'] = gId;
				if($(_elem).hasClass('js__block-report-today')){
					var department_id = $('.js__handle-department').val();
					$_adata['department_id'] = department_id;
				}
				if($(_elem).hasClass('billing_filter')){
					var parent = $(_elem).closest(".dashboard-panel-item"),
						time_type = $(".js_choose-time.active",parent).attr("holderg");				
					$_adata['time_type'] = time_type;
				}
				if($(_elem).hasClass('billing_calendar')){
					var parent = $(_elem).closest(".dashboard-panel-item"),
						date_range = $("input[name=date_range]",parent).val();				
					$_adata['date_range'] = date_range;
				}			
				$.post(url, $_adata, function(respJson){
					let $html = respJson.html;
					if($html.indexOf('_empty') >= 0){
						if($(_elem).hasClass("home_shared")){
							$(".home_block_shared").hide();
						}
						$(_elem).remove();
					} else {
						$(_elem).addClass('loaded').html($html);
						if(respJson.drawchart == 1){
							if(typeof(respJson.multichart) != 'undefined' && respJson.multichart==1){
								$Core.chart.canvas_multi(respJson.uid,respJson.barChartData);
							} else {
								$Core.chart.canvas(respJson.uid,respJson.barChartData);
							}
						}
						if($(_elem).hasClass('dashboard_sfs_report') && $(".table-sort").length){
							$(".table-sort").tableSortable({
								cmp:(a,b) => $Core.report.toNumber(a) < $Core.report.toNumber(b) ? -1 : 1
							});
						}
						if($(_elem).hasClass("home_events")){
							$(".home_block_score").hide();
							$(".home_block_events").show();
						}
						if($(_elem).hasClass('billing_calendar')){
							$(".number_total").text(respJson.total);
						}
					}
					if(respJson.callback){
						eval(respJson.callback);
					}
				},'json');
			});
		}
	},
}
$Core.vat = {
	toggle_search_VAT: function(_this, e){
		e.preventDefault();
		var gId = $(_this).attr('gId');
		$('#'+gId).dropdown('toggle');
		$Core.vat.list_VAT({});
		return false;
	},
	do_search_VAT: function(_this, e){
		$Core.vat.list_VAT({});
	},
	do_enter_search_VAT: function(_this, e){
		var _keyCode = e.which || e.keyCode;
		if(_keyCode === 13){
			$Core.vat.list_VAT({});
		}
	},
	open_VAT: function(_this, e){
		e.preventDefault();
		var vat_id = $(_this).attr('vat_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=vat&act=open_VAT',{
			'vat_id' : vat_id
		},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		},'json');
		return false;
	},
	crawl: function(_this, e){
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=vat&act=crawl',{},function(respJson){
			$Core.util.toggleIndicatior(0);
		},'json');
		return false;
	},
	save_VAT: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			vat_id = $(_this).attr('vat_id'),
			$_adata = {'vat_id':vat_id};
		if($('select.required,input.required',_form).length){
			$('select.required,input.required',_form).each((_i,_elem) => {
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
				url: PCMS_URL + "/index.php?mod="+MOD+"&sub=vat&act=save_VAT",
				data: $_adata,
				success: function(html){
					$Core.util.toggleIndicatior(0);	
					if(html.indexOf('_success') >= 0){
						var per_page = $('input[name=per_page]').val(),
							current_page = $('input[name=current_page]').val();
						$Core.vat.list_VAT({'per_page':per_page, 'page' : current_page});
						$('.closeEv', _form).trigger('click');
						$Core.alert.success("Thêm phiếu thu/chi thành công !");
					} else if(html.indexOf('_error') >= 0){
						$Core.swal.error( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} else if(html.indexOf('_duplicated') >= 0){
						$Core.swal.error( "Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại" );
					} 
				}
			});
		}
		return false;
	},
	list_VAT: function(options){
		var $_adata = options || {};
		if($('.search_field_VAT').length){
			$('.search_field_VAT').each((_i, _elem) => {
				var name = $(_elem).attr('name');
				$_adata[name] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=vat&act=list_VAT', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.'+'holder_VAT').html(respJson.html);
			$('.total').text(respJson.total_record);
			$('input[name=per_page]').val(respJson.per_page);
			$('input[name=current_page]').val(respJson.current_page);
			$('.briefs').html(respJson.html_briefs);
			if(parseInt(respJson.total_page)){
				$('#tableVAT').freezeTable('update');	
				$_easyUI('#pager_VAT').pagination({
					total:respJson.total_record,
					pageSize:respJson.per_page,
					pageNumber : respJson.current_page,
					onRefresh : function(pageNumber, pageSize){
						$Core.vat.list_VAT($.extend(options,{'page':pageNumber,'per_page':pageSize}));
					}, onSelectPage : function(pageNumber, pageSize){
						$Core.vat.list_VAT($.extend(options,{'page':pageNumber,'per_page':pageSize}));
					}
				});
			}
		},'json');
	},
	_handle_change: function(_this, e){
		var tp = $(_this).attr('tp'),
			uid = $(_this).attr('uid'),
			_form = $(_this).closest('form');
		if(tp == 'staff_name'){
			if($Core.util.isEmpty($(_this).val())){
				$(`.${uid}_id`).val(0);
			}
		} else if(tp == 'deposit_price' || tp=='final_price'){
			var amount = $('input[name=amount]', _form).val(),
				deposit_price = $('input[name=deposit_price]', _form).val(),
				final_price = $('input[name=final_price]', _form).val();
			amount = $Core.util.toNumber(amount);
			deposit_price = $Core.util.toNumber(deposit_price);
			final_price = $Core.util.toNumber(final_price);
			if((deposit_price + final_price) > amount){
				if(tp == 'deposit_price'){
					deposit_price = amount - final_price;
					$('input[name=deposit_price]', _form).val(deposit_price);
				} else if(tp == 'final_price'){
					final_price = amount - deposit_price;
					$('input[name=final_price]', _form).val(final_price);
				}
				$Core.alert.error("Số tiền [Tạm ứng] + [Tất toán] < [Số tiền]");
			}
		}
	},
	add_line: function(_this, e){
		e.preventDefault();
		var gId = $(_this).attr('gId'),
			total_line = $('tr[gId='+gId+']').length;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=vat&act=add_line',{
			'gId' : gId,
			'total_line' : total_line
		},function(html){
			$('button[gId='+gId+']').replaceWith('<button type="button" onClick="$Core.vat.delete_line(this, event)" gId='+gId+' class="btn btn-icon btn-outline-danger"><i class="bx bx-x"></i></button>');
			$('.'+gId+':last').after(html);
		});
		return false;
	},
	delete_line: function(_this, e){
		e.preventDefault();
		$(_this).closest('tr').remove();
		return false;
	},
	delete_VAT: function(_this, e){
		e.preventDefault();
		$Core.messager.confirm("Thông báo", "Bạn chắc chắn muốn xóa?", function(){
			var vat_id = $(_this).attr('vat_id');
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=vat&act=delete_VAT',{
				'vat_id' : vat_id
			},function(html){
				var per_page = $('input[name=per_page]').val(),
					current_page = $('input[name=current_page]').val();
				$Core.vat.list_VAT({'page' : current_page, 'per_page':per_page});
			});
		});
		return false;
	},
	done_VAT: function(_this, e){
		e.preventDefault();
		e.stopPropagation();
		if($(_this).hasClass('disabled')) return false;
		$Core.messager.confirm("Thông báo", "Bạn chắc chắn muốn tất toán cho VAT này?", function(){
			var vat_id = $(_this).attr('vat_id');
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=vat&act=done_VAT',{
				'vat_id' : vat_id
			},function(html){
				var per_page = $('input[name=per_page]').val(),
					current_page = $('input[name=current_page]').val();
				$Core.vat.list_VAT({'page' : current_page, 'per_page':per_page});
			});
		});
		return false;
	}
}
$Core.commission = $.extend($Core.global.commission, {
	crawl: function(_this, e){
		e.preventDefault();
		var quarter_id = $(_this).getAttr('quarter_id', ''),
			spreadsheetId = $(_this).getAttr('spreadsheetId', ""),
			sheet_name = $(_this).getAttr('sheet_name', "");
		$Core.messager.confirm("Thông báo", "Bạn chắc chắn muốn thực hiện", function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=commission&act=crawl',{
				'quarter_id' : quarter_id,
				'spreadsheetId' : spreadsheetId,
				'sheet_name' : sheet_name
			},function(respJson){
				$Core.util.toggleIndicatior(0);
				// window.location.reload();
				// $Core.commission.list({});
			},'json');
		});
		return false;
	},
	toggle_mode: function(_this, e){
		// Chọn kiểu năm/quý → hiện đúng khối tương ứng trong thẻ năm (mode độc quyền)
		var _card = $(_this).closest('.cy-year'),
			_mode = $(_this).val();
		if(_mode == 'quarter'){
			$('.cy-blk-year', _card).hide();
			$('.cy-blk-quarter', _card).show();
		} else {
			$('.cy-blk-quarter', _card).hide();
			$('.cy-blk-year', _card).show();
		}
	},
	toggle_search: function(_this, e){
		e.preventDefault();
		var gId = $(_this).attr('gId');
		$('#'+gId).dropdown('toggle');
		$Core.commission.list({});
		return false;
	},
	do_search: function(_this, e){
		$Core.commission.list({});
	},
	do_enter_search: function(_this, e){
		var _keyCode = e.which || e.keyCode;
		if(_keyCode === 13){
			$Core.commission.list({});
		}
	},
	open_setting : (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&sub=commission&act=open_setting`, {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	update_setting: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type:'POST',
			url: `${PCMS_URL}/index.php?mod=${MOD}&sub=commission&act=update_setting`,
			success: function(html){
				$Core.util.toggleIndicatior(0);	
			}
		});
		return false;
	},
	delete: function(_this, e){
		e.preventDefault();
		$Core.messager.confirm("Thông báo", "Bạn chắc chắn muốn xoá?", function(){
			var commission_id = $(_this).attr('commission_id');
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=commission&act=delete',{
				'commission_id' : commission_id
			},function(html){
				var per_page = $('input[name=per_page]').val(),
					current_page = $('input[name=current_page]').val();
				$Core.commission.list({'page' : current_page, 'per_page':per_page});
			});
		});
		return false;
	},
	list: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var name = $(_elem).attr('name');
				$_adata[name] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=commission&act=list', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_commission').html(respJson.html);
			$('.total').text(respJson.total_record);
			$('input[name=per_page]').val(respJson.per_page);
			$('input[name=current_page]').val(respJson.current_page);
			$('.briefs').html(respJson.html_briefs);
			$('.total_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$(_elem).html(respJson[field]);
			});
			$('.total_comsission_money').html(respJson.total_comsission_money);
			$('#'+'tableCommission').freezeTable('update');
			if(parseInt(respJson.total_page)){
				$_easyUI('#pager_VAT').pagination({
					total:respJson.total_record,
					pageSize:respJson.per_page,
					pageNumber : respJson.current_page,
					pageList: [20,30,50,100],
					onRefresh : function(pageNumber, pageSize){
						$Core.commission.list($.extend(options,{'page':pageNumber,'per_page':pageSize}));
					}, onSelectPage : function(pageNumber, pageSize){
						$Core.commission.list($.extend(options,{'page':pageNumber,'per_page':pageSize}));
					}
				});
			}
		},'json');
	},
});
$Core.money = {
	load_import_logs: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(0);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=money&act=load_import_logs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_import_logs').html(respJson.html);
		}, 'json');
	}, open_import: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			_config_id = $('select[name=config_id]', _form).val();
		$Core.util.toggleIndicatior(0);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=money&act=open_import', {
			'config_id' : _config_id
		}, function(respJson){
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
				$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=money&act=open_field', {
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
				url: PCMS_URL+'/index.php?mod='+MOD+'&sub=money&act=open_config', 
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
				url: PCMS_URL+'/index.php?mod='+MOD+'&sub=money&act=do_config', 
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
				url: PCMS_URL+'/index.php?mod='+MOD+'&sub=money&act=do_import', 
				data : {},
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.msg.indexOf('_invalid') >= 0){
						$Core.popup.open('auto','auto',respJson.html_errors,respJson.uid);
					} else {
						// $Core.money.load_import_logs();
						// $('.btn-close', _form).trigger('click');
					}
				}
			});
		}
		return false;
	},
};