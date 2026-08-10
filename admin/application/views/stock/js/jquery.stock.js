$(function(){
	$_document.on('change', '.stock_field', function(){
		var _this = $(this),
			_stock_tr = _this.closest('tr'),
			_stock_id = _this.attr('stock_id'),
			_field = _this.data('field'),
			_value = _this.val();
			
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=save_field', {
			'stock_id' : _stock_id,
			'field' : _field,
			'value' : _value
		}, function(html){
			if(_field=='code' && _stock_tr.hasClass('sop_add_line')){
				$.post(path_ajax_script+'/index.php?mod='+mod+'&act=search_in_template_storage', {
					'stock_id' : _stock_id,
					'code' : _value
				}, function(respJson){
					if(respJson.result.indexOf('_success') >= 0){
						if(!$Core.util.isEmpty(respJson.ms_code)){
							$('[data-field=ms_code]', _stock_tr).val(respJson.ms_code);
						}
						if(!$Core.util.isEmpty(respJson.DT_TT)){
							$('[data-field=DT_TT]', _stock_tr).val(respJson.DT_TT);
						}
						if(!$Core.util.isEmpty(respJson.DT_Tim)){
							$('[data-field=DT_Tim]', _stock_tr).val(respJson.DT_Tim);
						}
						if(!$Core.util.isEmpty(respJson.bedroom_id)){
							$('[data-field=bedroom_id]', _stock_tr).val(respJson.bedroom_id);
						}
						if(!$Core.util.isEmpty(respJson.home_direction_id)){
							$('[data-field=home_direction_id]', _stock_tr).val(respJson.home_direction_id);
						}
						if(!$Core.util.isEmpty(respJson.view_id)){
							$('[data-field=view_id]', _stock_tr).val(respJson.view_id);
						}	
					}
				}, 'json');
			}
			$Core.alert.success('Thành công !');
		});	
	});
	$_document.on('change', 'select[name=project_id]:not(.otherwise)', function(){
		var _this = $(this);
		vietiso_loading(1);
		$('select[name=block_id]').val(0);
		$('select[name=building_id]').val(0);
	});
	$_document.on('change', 'select[name=per_page]', function(){
		var _this = $(this),
			per_page = _this.val();
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=reload_per_page', {
			'per_page':per_page
		}, function(html){
			window.location.reload(true);
		});
	});
	$_document.on('change', '.checkAll:checkbox,.stock_item:checkbox', function(){
		var _this = $(this), _total_checked = 0;
		if(_this.hasClass('checkAll')){
			if(_this.is(':checked')){
				$('#tableCall .stock_item:checkbox').prop('checked', true);
				_total_checked = $('#tableCall .stock_item:checked').length;
			} else {
				$('#tableCall .stock_item:checkbox').prop('checked', false);
			}
		} else {
			var _checkall = true;
			$('#tableCall .stock_item:checkbox').each((_i, _elem) => {
				if($(_elem).is(':checked')){
					_total_checked++;
				} else {
					_checkall = false;
				}
			});
			$('.checkAll:checkbox').prop('checked', _checkall);
		}
		$('.total_selected').text(_total_checked);
		if(_total_checked > 0) {
			$('.do_action').removeClass('disabled');
		} else {
			$('.do_action').addClass('disabled');
		}	
	});
	$_document.on('change', '.stock_price_upload_file', function(){
		var _this = $(this),
			_uid = _this.attr('uid'),
			_form = _this.closest('form');
		
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=stock_price_upload_file',
			dataType:'html',
			success : function(html){
				vietiso_loading(0);
				_form.clearForm();
				_form.resetForm();
				$('.stock_price_image_hidden_'+_uid).val(html);
			}
		});	
	});
});
function add_line(_this, e){
	e.preventDefault();
	var $_this = $(_this),
		$_project_id = $_this.attr('project_id'),
		$_block_id = $_this.attr('block_id'),
		$_stock_type = $_this.attr('stock_type'),
		$_building_id = $_this.attr('building_id');
	if($Core.util.isEmpty($_project_id)){
		$Core.alert.error('Bạn cần chọn dự án');
	} else if($Core.util.isEmpty($_block_id)){
		$Core.alert.error('Bạn cần chọn phân khu');
	} else if($Core.util.isEmpty($_building_id) && parseInt($_stock_type) == _BLOCK_TYPE_HIGHLEVEL_SALE){
		$Core.alert.error('Bạn cần chọn tòa nhà');
	} else {
		if(!$_this.hasClass('clicked')){
			$_this.addClass('clicked');
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_line', {
				'project_id' : $_project_id,
				'block_id' : $_block_id,
				'stock_type' : $_stock_type,
				'building_id': $_building_id
			}, function(html){
				vietiso_loading(0);
				$_this.removeClass('clicked');
				if($('.sop_row').length){
					$('.sop_row:first').before(html);
				} else {
					$('.tbody').html(html);
				}
				$('.freeze-table').freezeTable('update');
			});
		}
	}
	return false;
}
function add_template(_this, e){
	e.preventDefault();
	var $_this = $(_this),
		$_block_id = $_this.attr('block_id'),
		$_project_id = $_this.attr('project_id'),
		$_building_id = $_this.attr('building_id');
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_template', {
		'project_id' : $_project_id,
		'block_id' : $_block_id,
		'building_id' : $_building_id
	}, function(html){
		vietiso_loading(0);
		if(html.indexOf('_success') >= 0){
			window.location.reload();
		}
	});
	return false;
}
function add_fill(_this, e){
	e.preventDefault();
	var $_this = $(_this),
		$_block_id = $_this.attr('block_id'),
		$_project_id = $_this.attr('project_id'),
		$_building_id = $_this.attr('building_id');
	if($Core.alert.confirm('Xác nhận', 'Hãy chắc chắn rằng dữ liệu mẫu đã chính xác?', function(){
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_fill', {
			'project_id' : $_project_id,
			'block_id' : $_block_id,
			'building_id' : $_building_id
		}, function(html){
			vietiso_loading(0);
			if(html.indexOf('_success') >= 0){
				window.location.reload();
			}
		});
	}));
	return false;
}
function delete_line(_this, e){
	e.preventDefault();
	var stock_id = $(_this).attr('stock_id');
	$Core.alert.confirm('Xác nhận', "Bạn chắc chắn có muốn xóa?", function(){
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete_line', {
			'stock_id' : $(_this).attr('stock_id')
		}, function(html){
			vietiso_loading(0);
			$('.sop_row_'+stock_id).remove();
			$('.freeze-table').freezeTable('update');
		});
	});
	return false;
}
function select_upload_file(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid');
	$('#'+uid).trigger('click');
	return false;
}
function open_import_file(_this, e){
	e.preventDefault();
	var tp = $(_this).attr('tp'),
		project_id = $(_this).attr('project_id'),
		block_id = $(_this).attr('block_id'),
		stock_type = $(_this).getAttr('stock_type', 0),
		building_id = $(_this).attr('building_id'),
		$_adata = {
			'tp':tp,
			'stock_type' : stock_type,
			'project_id':project_id,
			'block_id':block_id,
			'building_id':building_id
		};
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_import_file', $_adata, function(html){
		vietiso_loading(0);
		$Core.popup.open('auto', 'auto', html, 'open_import_file');
	});
	return false;
}
function start_import_file(_this, e){
	e.preventDefault();
	var _validated = 0,
		_tp = $(_this).attr('tp'),
		_form = $(_this).closest('form')
		project_id = $(_this).attr('project_id'),
		block_id = $(_this).attr('block_id'),
		stock_type = $(_this).attr('stock_type'),
		building_id = $(_this).attr('building_id');
	if(stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){
		project_id = $('input[name=project_id]', _form).val();
	}
	if($('select.required:visible', _form).length){
		$('select.required:visible', _form).each((_i, _elem) => {
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
			url : path_ajax_script+'/index.php?mod='+mod+'&act=import_file',
			data : {
				'project_id':project_id,
				'block_id':block_id,
				'stock_type' : stock_type,
				'building_id':building_id
			},
			dataType:'html',
			success : function(html){
				vietiso_loading(0);
				_form.clearForm();
				_form.resetForm();
				if(html.indexOf('OK') >= 0){
					if(_tp=='blank'){
						var tmp = html.split('|||');
						$Core.popup.close(_form.closest('.modal'));
						$Core.popup.open('auto', 'auto', tmp[1], 'import_read_file');
					} else {
						window.location.reload(true);
					}
				} else if(html.indexOf('_msgError') >= 0) {
					var tmp = html.split('|||');
					$Core.popup.open('auto', 'auto', tmp[1], 'import_file');
				} else {
					$Core.alert.error('Lỗi upload !!!');
				}
			}
		});
	}
	return false;
}
function do_import_file(_this, e){
	e.preventDefault();
	var _validated = 0,
		_form = $(_this).closest('form'),
		uid = $(_this).attr('uid'),
		block_id = $(_this).attr('block_id'),
		stock_type = $(_this).attr('stock_type'),
		building_id = $(_this).attr('building_id'),
		project_id = $(_this).attr('project_id');
	
	var list_field = new Array();
	if($('.stock_import_field', _form).length){
		$('.stock_import_field', _form).each((_i, _elem) => {
			if(!$Core.util.isEmpty($(_elem).val())){
				list_field.push($(_elem).val());
				_validated++;
			}
		});
	}
	if(_validated > 0 && stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){
		if($.inArray('block_id', list_field) == -1){
			_validated = 0;
			$Core.alert.error("Bạn chưa chọn Block");
			return false;
		}
	}
	if(_validated > 0){
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=do_import_file',
			data : {
				'uid':uid,
				'project_id':project_id,
				'block_id':block_id,
				'building_id':building_id
			},
			dataType:'html',
			success : function(html){
				vietiso_loading(0);
				if(html.indexOf('success') >= 0){
					var tmp = html.split('|||');
					$Core.alert.error("Thành công <br /> "+tmp[1]+" record(s)");
					setTimeout(() => {
						window.location.reload(true);
					}, 2000);
				} else if(html.indexOf('error_field') >= 0){
					$Core.alert.error("Lỗi! Bạn cần chọn ít nhất một trường dữ liệu cần import.");
				}
			}
		});
	} else {
		$Core.alert.error("Lỗi! Bạn cần chọn ít nhất một trường dữ liệu cần import.");
	}
	return false;
}
function do_action(_this, e){
	e.preventDefault();
	var cmd = $(_this).attr('cmd'),
		_total_checked = 0, stock_ids = [];
		
	if($(_this).hasClass('disabled'))
		return false;
	if($('#tableCall .stock_item:checked').length){
		$('#tableCall .stock_item:checked').each((_i, _elem) => { 
			_total_checked++;
			stock_ids.push($(_elem).val());
		});
	}
	if(_total_checked > 0){
		if(cmd=='delete'){
			$Core.alert.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
				$.post(path_ajax_script+'/index.php?mod='+mod+'&act=do_action', {
					'cmd' : cmd,
					'stock_ids' : stock_ids
				}, function(html){
					if(html.indexOf('_success') >= 0){
						window.location.reload(true);
					}
				});
			});
		} else if(cmd=='update'){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=do_action', {
				'cmd' : cmd,
				'stock_ids' : stock_ids
			}, function(html){
				if(html.indexOf('_success') >= 0){
					var tmp = html.split('|||');
					$Core.popup.open('auto','auto',tmp[1], 'do_action');
				}
			});
		}
	} else {
		$Core.alert.error("Bạn chưa chọn danh sách căn hộ !!!");
	}
	return false;
}
function open_stock(_this, e){
	e.preventDefault();
	var stock_id = $(_this).attr('stock_id');
	vietiso_loading(0);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_stock', {
		'stock_id' : stock_id
	}, function(respJson){
		vietiso_loading(0);
		$Core.popup.open('auto', 'auto', respJson.html, 'open_stock');
		load_stock_price(stock_id, {});
		if(respJson.callback) eval(respJson.callback);
	},'json');
	return false;
}
function pop_save_stock(_this, e){
	e.preventDefault();
	var _validated = 0,
		_form = $(_this).closest('form'),
		stock_id = $(_this).attr('stock_id');
	if($('input.required', _form).length){
		$('input.required', _form).each((_i, _elem) => {
			if($Core.util.isEmpty($(_elem).val())){
				_validated++;
				$(_elem).focus();
				return false;
			}
		});
	}
	var more = {};
	if($('.isoTextArea',_form).length){
		$('.isoTextArea',_form).each((_i, _elem) => {
			var name = $(_elem).data('name'),
				editorId = $(_elem).attr('id');
			more[name] = $Core.util.getTextAreaContent(editorId);
		});
	}
	if(_validated == 0){
		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_stock',
			dataType: 'html',
			data: $.extend(more, {'stock_id':stock_id}),
			success: function(html){
				vietiso_loading(0);
				if(html.indexOf('_error') >= 0){
					$Core.alert.error("Error !");
				} else {
					if(html.indexOf('_full') >= 0){
						$('.sop_row_'+stock_id).addClass('stock_mask');
					} else {
						$('.sop_row_'+stock_id).removeClass('stock_mask');
					}
				}
			}
		});
	}
	return false;
}
function start_update_field(_this, e){
	e.preventDefault();
	var _validated = 0,
		_total_checked = 0,
		_form = $(_this).closest('form');
	$('.stock__line').each((_i, _elem) => {
		if($('input:checkbox', $(_elem)).is(':checked')){
			_total_checked++;
		}
	});
	if(_total_checked == 0){
		$Core.alert.error("Chưa chọn điều kiện cập nhật");
	} else {
		if(_validated == 0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=update_stock_field',
				dataType: 'html',
				success: function(html){
					vietiso_loading(0);
					if(html.indexOf('_error') >= 0){
						$Core.alert.error("Error !");
					} else if(html.indexOf('_success') >= 0) {
						window.location.reload();
					}
				}
			});
		}
	}
	return false;
}
function delete_stock_building(_this, e){
	e.preventDefault();
	var project_id = $(_this).attr('project_id'),
		block_id = $(_this).attr('block_id'),
		building_id = $(_this).attr('building_id'),
		$_adata = {'project_id':project_id,'block_id':block_id,'building_id':building_id};
	$Core.alert.confirm('Xác nhận', 'Bạn chắc chắn muốn thực hiện thao tác này?', function(){
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete_stock_building', {
			'project_id' : project_id,
			'block_id' : block_id,
			'building_id' : building_id
		}, function(respJson){
			vietiso_loading(0);
			window.location.reload();
		},'json');
	});
	return false;
}
function do_stock_sold(_this, e){
	e.preventDefault();
	var project_id = $(_this).attr('project_id'),
		block_id = $(_this).attr('block_id'),
		building_id = $(_this).attr('building_id'),
		$_adata = {'project_id':project_id,'block_id':block_id,'building_id':building_id};
	$Core.alert.confirm('Xác nhận', 'Bạn chắc chắn muốn thực hiện thao tác này?', function(){
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=do_stock_sold', {
			'project_id' : project_id,
			'block_id' : block_id,
			'building_id' : building_id
		}, function(respJson){
			vietiso_loading(0);
			//window.location.reload();
		},'json');
	});
	return false;
}
function do_stock_once_sold(_this, e){
	var _stock_code = $(_this).val(),
		_project_id = $(_this).attr('project_id'),
		_keyCode = e.which || e.keyCode;
	if(_keyCode==13 && !$Core.util.isEmpty(_stock_code)){
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=do_stock_once_sold', {
			'stock_code' : _stock_code,
			'project_id' : _project_id
		}, function(html){
			vietiso_loading(0);
			if(html.indexOf('_success') >= 0){
				$(_this).val("").focus();
				$Core.alert.success("Cập nhật [Đã bán] thành công!");
			} else {
				$Core.alert.error("Không tìm thấy mã căn phù hợp!");
			}
		});
		e.preventDefault();
	}
}
function open_stock_price(_this, e){
	e.preventDefault();
	var uid = $(_this).getAttr('uid', ""),
		stock_id = $(_this).attr('stock_id');
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_stock_price', {
		'uid' : uid,
		'stock_id' : stock_id
	}, function(respJson){
		vietiso_loading(0);
		$Core.popup.open('auto', 'auto', respJson.html, 'open_stock_price');
		if(respJson.callback) eval(respJson.callback);
	}, 'json');
	return false;
}
function delete_stock_price(_this, e){
	e.preventDefault();
	var uid = $(_this).getAttr('uid', ""),
		stock_id = $(_this).attr('stock_id');
	$Core.alert.confirm('Xác nhận', 'Hãy chắc chắn rằng dữ liệu mẫu đã chính xác?', function(){
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete_stock_price', {
			'uid' : uid,
			'stock_id' : stock_id
		}, function(html){
			vietiso_loading(0);
			load_stock_price(stock_id, {});
		});
	});
	return false;
}
function stock_price_select_file(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid'),
		toId = $(_this).attr('toId');
	$('#'+toId).attr('uid', uid).trigger('click');
	return false;
}
function stock_price_addline(_this, e){
	e.preventDefault();
	var toId = $(_this).attr('toId'),
		stock_id = $(_this).attr('stock_id');
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=stock_price_addline', {
		'toId' : toId,
		'stock_id' : stock_id
	}, function(html){
		vietiso_loading(0);
		$('.tr_stock_price_'+toId+':last').after(html);
	});
	return false;
}
function stock_price_delete_line(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid');
	$('.tr_stock_price_'+uid).remove();
	return false;
}
function pop_save_stock_price(_this, e){
	e.preventDefault();
	var _validated = 0, 
		uid = $(_this).attr('uid'),
		stock_id = $(_this).attr('stock_id'),
		_form = $(_this).closest('form');
	if($('input.required', _form).length){
		$('input.required', _form).each((_i, _elem) => {
			if($Core.util.isEmpty($(_elem).val())){
				_validated++;
				$(_elem).focus();
				return false;
			}
		});
	}
	if(_validated==0){
		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_stock_price',
			data: {'uid':uid, 'stock_id':stock_id},
			dataType: 'html',
			success: function(html){
				vietiso_loading(0);
				load_stock_price(stock_id, {});
				$Core.popup.close(_form.closest('.modal'));
			}
		});
	}
	return false;
}
function load_stock_price(stock_id, options){
	var $_adata = options || {};
	$_adata['stock_id'] = stock_id;
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_stock_price', $_adata, function(html){
		vietiso_loading(0);
		$('.holder_stock_price_'+stock_id).html(html);
	});
}
$Core.stock = {
	update_ptg: (_this,e) => {
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id"),
			$_adata ={"agency_id":agency_id};
		vietiso_loading(1);
		$.post(`${path_ajax_script}/index.php?mod=${mod}&act=update_ptg`, $_adata, function(respJson){
			vietiso_loading(0);
			if(respJson.msg.indexOf('_success') >= 0) {
				alertify.success("Đã cập nhật PTG "+respJson.total_update+" căn");
			} else if(respJson.msg.indexOf('_empty') >= 0) {
				alertify.error("Folder bị trống");
			} else {
				alertify.error("Lỗi!");
			}
		},"json");
	},
	open_copy: function(_this, e){
		e.preventDefault();
		$Core.alert.confirm('Xác nhận', 'Bạn chắc chắn rằng muốn thực hiện?', function(){
			var project_id = $(_this).attr('project_id'),
				block_id = $(_this).attr('block_id'),
				building_id = $(_this).attr('building_id');
			
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_copy', {
				'project_id' : project_id,
				'block_id' : block_id,
				'building_id' : building_id
			}, function(respJson){
				vietiso_loading(0);
				$Core.popup.open('auto', 'auto', respJson.html, 'open_copy');
				if(respJson.callback) eval(respJson.callback);
			}, 'json');
		});
		return false;
	},
	do_copy: function(_this, e){
		e.preventDefault();
		var _validated = 0, 
			_form = $(_this).closest('form');
		if($('input.required', _form).length){
			$('input.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=do_copy',
				dataType: 'html',
				success: function(html){
					vietiso_loading(0);
					if(html.indexOf('_success') >= 0){
						window.location.reload(true);
					}
				}
			});
		}
		return false;
	},
	toggle_site: function(_this, e){
		var $cb = $(_this),
			stock_id = $cb.attr('stock_id'),
			site_code = $cb.val(),
			checked = $cb.is(':checked'),
			state = checked ? 1 : 0;
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=toggle_stock_site', {
			'stock_id': stock_id,
			'site_code': site_code,
			'state': state
		}, function(respJson){
			vietiso_loading(0);
			if(!respJson || respJson.status != '_success'){
				$cb.prop('checked', !checked);
				$Core.alert.error('Không cập nhật được hiển thị site!');
			}
		}, 'json').fail(function(){
			vietiso_loading(0);
			$cb.prop('checked', !checked);
			$Core.alert.error('Lỗi kết nối, thử lại!');
		});
		return true;
	},
	add_stock_line: function(_this, e){
		e.preventDefault();
		var stock_id = $(_this).attr('stock_id');
			$_adata = {'stock_id':stock_id};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_stock_line', $_adata, function(html){
			vietiso_loading(0);
			if($('.tr_stock_line_'+stock_id).length){
				$('.holder_property_'+stock_id+' tr:last').after(html);
			} else {
				$('.holder_property_'+stock_id).html(html);
			}
		});
		return false;
	},
	delete_stock_line:function(_this, e){
		e.preventDefault();
		var uid = $(_this).attr('uid');
		$('.tr_stock_line_'+uid).remove();
		return false;
	},
	layout_select_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			tofield = $(_this).attr('tofield'),
			stock_id = $(_this).attr('stock_id');
		$('#'+toId).attr('stock_id',stock_id).attr('tofield',tofield).trigger('click');
		return false;
	},
	layout_upload_file: function(_this, e){
		e.preventDefault();
		var form = $(_this).closest('form'),
			tofield = $(_this).attr('tofield'),
			stock_id = $(_this).attr('stock_id');
		vietiso_loading(1);
		form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=layout_upload_file',
			data: {'stock_id':stock_id,'tofield':tofield},
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				form.clearForm();
				form.resetForm();
				if(respJson.msg.indexOf('_success') >= 0){
					$('.stock_'+tofield+'_'+stock_id).val(respJson.layout_file);
				} else {
					$Core.alert.error('Không thành công !');
				}
			}
		});	
		return false;
	},
	start_import_agent: function(_this, e){
		e.preventDefault();
		var agency_id = $(_this).attr('agency_id'),
			$_adata = {'agency_id':agency_id};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=start_import_agent', $_adata, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'start_import_agent');
		}, 'json');
		return false;
	}, 
	spreadsheet : null,
	choose_image : function (_this,e){
		e.preventDefault();
		var _form = $(_this).closest("form");
		$(".file_upload",_form).trigger("click");
	},
	start_import_stock : function(_this,e){
		e.preventDefault();
		var _form = $(_this).closest("form"),
			agency_id = $(_this).attr('agency_id'),
			type = $(_this).data('type'),
			$_adata = {'agency_id':agency_id,'type':type};
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=start_import_stock',
			data : $_adata,
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				if(type == "_IMAGE") {
					$("input[type=file]",_form).val("");
				}
				if(respJson.result){
					$Core.popup.open('auto', 'auto', respJson.html, 'start_import_stock'+respJson.uid);
					$Core.stock.spreadsheet = jspreadsheet(document.getElementById('spreadsheet_'+respJson.uid), {
						data:respJson.data,
						columns: respJson.dataHead
					});
				}else{
					alertify.error(respJson.msg);
				}				
			}
		});
		return false;
	},
	start_copy_agent: function(_this, e){
		e.preventDefault();
		var agency_id = $(_this).attr('agency_id'),
			$_adata = {'agency_id':agency_id};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=start_copy_agent', $_adata, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'start_copy_agent');
			$Core.stock.spreadsheet = jspreadsheet(document.getElementById('spreadsheet_'+respJson.uid), {
				data:respJson.data,
				columns: respJson.dataHead
			});
		}, 'json');
		return false;
	}, 
	do_copy_agent : function(_this,e){
		var $_adata = {},
			_validated = 0,
			_form = $(_this).closest("form");
		var agency_id = $(_this).attr('agency_id'),
			stock_type = $(_this).attr('stock_type'),
			list_field = new Array();
		$_adata['agency_id'] = agency_id;
		$_adata['stock_type'] = stock_type;
		if($('.stock_import_field', _form).length){
			$('.stock_import_field', _form).each((_i, _elem) => {
				list_field.push($(_elem).val());
			});
		}
		if($.inArray('ms_code', list_field) == -1){
			_validated+= 1;
			$Core.alert.error("Bạn chưa chọn mã căn");
			return false;
		}
		// console.log($Core.stock.spreadsheet.getData());
		if(_validated == 0){
			$_adata['tblData'] = $Core.stock.spreadsheet.getData();
			if(confirm("Hãy chắc chắn rằng bạn đã chọn đúng dự án, phân khu!")) {
				_form.ajaxSubmit({
					type : 'POST',
					url : path_ajax_script+'/index.php?mod='+mod+'&act=do_copy_agent',
					data : $_adata,
					dataType:'json',
					success : function(respJson){
						vietiso_loading(0);
						if(respJson.result) {
							$Core.alert.success("Cập nhật thành công "+respJson.total_updated+" record(s)");	
							setTimeout(() => { window.location.reload(); }, 1000);
						}else{
							$Core.alert.confirm("Thông báo",respJson.msg);
						}
					}
				});
			}
		}
	},
	do_import_agent: function(_this, e){
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			_form = $(_this).closest("form"),
			agency_id = $(_this).attr('agency_id'),
			stock_type = $(_this).attr('stock_type'),
			opt_ignore_empty = $('input[name=opt_ignore_empty]').is(':checked') ? 1 : 0,
			$_adata = {'uid':uid, 'agency_id':agency_id,'opt_ignore_empty':opt_ignore_empty,'stock_type':stock_type};
		if(confirm("Hãy chắc chắn rằng bạn đã chọn đúng dự án, phân khu!")) {
			vietiso_loading(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=do_import_agent',
				data : $_adata,
				dataType:'json',
				success : function(respJson){
					vietiso_loading(0);
					if(respJson.result) {
						$Core.alert.success("Cập nhật thành công "+respJson.total_updated+" record(s)");	
						setTimeout(() => { window.location.reload(); }, 1000);
					}else{
						$Core.alert.confirm("Thông báo",respJson.msg);
					}
				}
			});
		}
		return false;
	},
	cron_automation_enable: function(_this, e){
		var agency_id = $(_this).attr('agency_id'),
			cron_automation_enable = $(_this).is(':checked') ? 1 : 0;
		
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=cron_automation_enable', {
			'agency_id' : agency_id,
			'cron_automation_enable' : cron_automation_enable
		}, function(html){
			vietiso_loading(0);
		});
	},
	open_import_leasing: function(_this, e){
		e.preventDefault();
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_import_leasing', {}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto','auto',respJson.html,'open_import_leasing');
		}, 'json');
		return false;
	},
	open_import_lowfloor: function(_this, e){
		e.preventDefault();
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_import_lowfloor', {}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto','auto',respJson.html,'open_import_lowfloor');
		}, 'json');
		return false;
	},
	start_import_leasing: function(_this, e){
		e.preventDefault();
		var project_id = $(_this).attr('project_id'),
			$_adata = {'project_id':project_id};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=start_import_leasing', $_adata, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'start_import_leasing');
		});
		return false;	
	},
	do_import_leasing: function(_this, e){
		var _validated = 0,
			list_field = new Array(),
			uid = $(_this).attr('uid'),
			form = $(_this).closest('form'),
			stock_type = $(_this).attr('stock_type'),
			project_id = $(_this).attr('project_id'),
			$_adata = {'uid':uid,'project_id':project_id,'stock_type':stock_type};
		
		if($('.stock_import_field', form).length){
			$('.stock_import_field', form).each((_i, _elem) => {
				if(!$Core.util.isEmpty($(_elem).val())){
					list_field.push($(_elem).val());
					_validated++;
				}
			});
		}
		if($.inArray('ms_code', list_field) == -1){
			_validated+= 1;
			$Core.alert.error("Bạn chưa chọn mã căn");
			return false;
		}
		if(_validated > 0){
			vietiso_loading(1);
			form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=do_import_leasing',
				data : $_adata,
				dataType:'html',
				success : function(html){
					vietiso_loading(0);
					if(html.indexOf('success') >= 0){
						var tmp = html.split('|||');
						$Core.alert.error("Thành công <br /> "+tmp[1]+" record(s)");
						setTimeout(() => {
							window.location.reload(true);
						}, 2000);
					} else if(html.indexOf('error_field') >= 0){
						$Core.alert.error("Lỗi! Bạn cần chọn ít nhất một trường dữ liệu cần import.");
					}
				}
			});
		} else {
			$Core.alert.error("Lỗi! Bạn cần chọn ít nhất một trường dữ liệu cần import.");
		}
		return false;
	},
	start_import_lowfloor: function(_this, e){
		e.preventDefault();
		var project_id = $(_this).attr('project_id'),
			$_adata = {'project_id':project_id};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=start_import_lowfloor', $_adata, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'start_import_lowfloor');
		});
		return false;	
	},
	do_import_lowfloor: function(_this, e){
		var _validated = 0,
			list_field = new Array(),
			uid = $(_this).attr('uid'),
			form = $(_this).closest('form'),
			stock_type = $(_this).attr('stock_type'),
			project_id = $(_this).attr('project_id'),
			$_adata = {'uid':uid,'project_id':project_id,'stock_type':stock_type};
		
		if($('.stock_import_field', form).length){
			$('.stock_import_field', form).each((_i, _elem) => {
				if(!$Core.util.isEmpty($(_elem).val())){
					list_field.push($(_elem).val());
					//_validated++;
				}
			});
		}
		// console.log(list_field); return false;
		if($.inArray('ms_code', list_field) == -1){
			_validated+= 1;
			$Core.alert.error("Bạn chưa chọn mã căn");
			return false;
		}
		if(_validated == 0){
			vietiso_loading(1);
			form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=do_import_lowfloor',
				data : $_adata,
				dataType:'html',
				success : function(html){
					vietiso_loading(0);
					if(html.indexOf('success') >= 0){
						var tmp = html.split('|||');
						$Core.alert.error("Thành công <br /> "+tmp[1]+" record(s)");
						setTimeout(() => {
							window.location.reload(true);
						}, 2000);
					} else if(html.indexOf('error_field') >= 0){
						$Core.alert.error("Lỗi! Bạn cần chọn ít nhất một trường dữ liệu cần import.");
					}
				}
			});
		} else {
			$Core.alert.error("Lỗi! Bạn cần chọn ít nhất một trường dữ liệu cần import.");
		}
		return false;
	},
	do_import_agent_advanced: function(_this, e){
		e.preventDefault();
		var agency_id = $(_this).attr('agency_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=do_import_agent_advanced', {
			'agency_id' : agency_id
		}, function(respJson){
			vietiso_loading(0);
			$Core.alert.success("Cập nhật thành công "+respJson.total_updated+" record(s)");
			window.location.reload();
		}, 'json');
		return false;
	},
	open_import_logs: function(_this, e){
		e.preventDefault();
		var agency_id = $(_this).attr('agency_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_import_logs', {
			'agency_id' : agency_id
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_import_logs');
		}, 'json');
		return false;
	},
	open_image: function (_this,e) {
		e.preventDefault();
		var project_id = $(_this).attr('project_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_image', {
			'project_id' : project_id
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_image');
		}, 'json');
		return false;
	},
	start_get_data_image : function(_this,e){
		e.preventDefault();
		let _form = $(_this).closest("form"),
			project_id = $(_this).attr('project_id'),
			$_adata = {'project_id':project_id,'type':type};
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=start_get_data_image',
			data : $_adata,
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				$("input[type=file]",_form).val("");
				if(respJson.result){
					alertify.success(respJson.msg);
					setTimeout(function(){
						window.location.reload();
					},500);
				}else{
					alertify.error(respJson.msg);
				}				
			}
		});
		return false;
	},
	closeNotify : function (_this,e){
		e.preventDefault();
		$(_this).closest(".box_notify").addClass("d-none");
		
	},
	loadBlockU : function (_this,e) {
		e.preventDefault();
		var project_id = $(_this).val(),
			stock_type = $(_this).attr("stock_type"),
			_form = $(_this).closest("form");
		$.ajax({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=load_block_user',
			data : {"stock_type":stock_type,"project_id":project_id},
			dataType:'html',
			success : function(html){
				vietiso_loading(0);
				$(".list_block_user",_form).html(html);
			}
		});
	},
	setView : function (_this,e) {
		e.preventDefault();
		var type = $(_this).data("type");
		$(".btn_view").removeClass("active");
		$(_this).addClass("active");
		$.ajax({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=setView',
			data : {"type":type},
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				if(respJson.result) {
					window.location.reload();
				}
			}
		});
	},
	updateStructCode : function (_this,e) {
		e.preventDefault();
		var building_id = $(_this).attr("building_id"),
			building_name = $(_this).attr("building_name");
		if($Core.alert.confirm('Xác nhận', 'Hãy chắc chắn rằng bạn muốn cập nhật cấu trúc mã căn cho tòa '+building_name+'?', function(){
			vietiso_loading(1);
			$.ajax({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=updateStructCode',
				data : {"building_id":building_id},
				dataType:'json',
				success : function(respJson){
					vietiso_loading(0);
					if(respJson.result) {
						$Core.alert.success('Cập nhật thành công '+respJson.total_update+' căn!');
						setTimeout(function(){
							window.location.reload();
						},500);
					}
				}
			});
		}));
	}
}