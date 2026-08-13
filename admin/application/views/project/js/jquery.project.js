$(function(){
	$(document).on('keydown', function(e){
		if(act == "draw_map" || act == "map") {
			if ((e.key === "Delete" || e.keyCode === 46) && $('button.js__delete_shape:visible').length) {
				$('button.js__delete_shape:visible').trigger("click");
			}
			if (e.ctrlKey && (e.key === 'd' || e.key === 'D') && $('button.js__copy_shape:visible').length) {
				e.preventDefault();
				$('button.js__copy_shape:visible').trigger("click");
			}
			if ((e.key === 'Enter' || e.keyCode === 13 || e.keyCode === 32) && $('button.js__btn_add_stock:visible').length) {
				e.preventDefault();
				$('button.js__btn_add_stock:visible').trigger("click");
			}
		}      	
    });
	$Core.project.init();
	$_document.ajaxComplete(() => {
        // $Core.project.init();
    });
	if(act == 'default') {
		/*$(".table_project").sortable({
			handle: ".mySortableHandler",
			draggable: 'tr.tr_selected',
			update: function (event, ui) {
				var orderNo = $(this).sortable('toArray'),
				$_adata = {"orderNo":orderNo};
				console.log(orderNo)
				toggleIndicatior(1);	
				$.post(path_ajax_script+"/index.php?mod=project&act=save_order",$_adata,function(html){
					toggleIndicatior(0);	
				});
			}
		}).disableSelection();*/
	}
});
function gen_floor(_this, e){
	e.preventDefault();
	var toId = $(_this).attr('toId');
	$Core.alert.confirm("Tạo danh sách tầng",'<div class="form-group">'
		+ '<label class="col-form-label">Nhập số tầng muốn tạo</label>'
		+ '<input class="form-control numberonly number_floor_'+toId+'" type="number" />'
		+ '</div>', function(){
		var number_floor = $('.number_floor_'+toId).val();
		if(parseInt(number_floor) > 0){
			var floor = "";
			for(var i=1; i<=number_floor; i++){
				floor += (i==1 ? '':',') + $Core.util.parseNumber(i);
			}
			$('.'+toId).val(floor);
		} else {
			$Core.alert.error("Lỗi! Nhập vào số tầng muốn tạo danh sách.");
		}
	});
	return false;
}
function open_project(_this, e){
	e.preventDefault();
	vietiso_loading(1);
	$.ajax({
		type:'POST',
		url:path_ajax_script+'/index.php?mod='+mod+'&act=open_project',
		data: {},
		dataType:'html',
		success:function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_project');
		}
	});
	return false;
}
function pop_create_project(_this, e){
	e.preventDefault();
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
			url: path_ajax_script+'/index.php?mod='+mod+'&act=pop_create_project',
			dataType: 'json',
			success: function(respJson){
				vietiso_loading(0);
				if(respJson.msg.indexOf('_error') >= 0){
					$Core.alert.error("Error !");
				} if(respJson.msg.indexOf('_duplicate') >= 0){
					$Core.alert.error("Duplicated !");
				} else {
					window.location.href = respJson.link;
				}
			}
		});
	}
	return false;
}
function sync_search(_this, e){
	e.preventDefault();
	vietiso_loading(1);1
	$.post(path_ajax_script+'/index.php?mod=project&act=sync_search', {}, function(html){
		vietiso_loading(0);	
	});
	return false;
}
function add_property(_this, e){
	e.preventDefault();
	var _form = $(_this).closest('form'),
		project_id = $(_this).attr('project_id'),
		total_record = $('.tr_property', _form).size(),
		_holderG = $(_this).getAttr('_holderG', '_properties'),
		_openFrom = $(_this).getAttr('_openFrom', '_project');
	// alert(_holderG);
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod=project&act=add_property', {
		'project_id' : project_id,
		'total_record' : total_record,
		'_holderG' : _holderG,
		'_openFrom' : _openFrom
	}, function(html){
		vietiso_loading(0);	
		if(_holderG=='_attrs'){
			if($('.tr_attrs', _form).length==0){
				$('.tbody_attrs', _form).html(html);
			} else {
				$('.tr_attrs:last', _form).after(html);
			}
		} else {
			if($('.tr_property', _form).length==0){
				$('.tbody_property', _form).html(html);
			} else {
				$('.tr_property:last', _form).after(html);
			}
		}
	});
	return false;
}
function delete_property(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid'),
		_form = $(_this).closest('form');
	if($('.tr_property', _form).length == 1){
		$('.add_property', _form).trigger('click');
	}
	$('#'+uid).remove();
	return false;
}
function open_group(_this, e){
	e.preventDefault();
	vietiso_loading(1);
	var group_id = $(_this).attr('group_id'),
		project_id = $(_this).attr('project_id');
	$.ajax({
		type:'POST',
		url:path_ajax_script+'/index.php?mod='+mod+'&act=open_group',
		data: {'project_id' : project_id, 'group_id' : group_id,},
		dataType:'html',
		success:function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_group');
		}
	});
	return false;
}
function pop_save_group(_this, e){
	e.preventDefault();
	var group_id = $(_this).attr('group_id'),
		project_id = $(_this).attr('project_id');
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
			url: path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_group',
			data: {'project_id':project_id,'group_id':group_id},
			dataType: 'html',
			success: function(html){
				vietiso_loading(0);
				if(html.indexOf('_error') >= 0){
					$Core.alert.error("Error !");
				} else if(html.indexOf('_insert_success') >= 0){
					$Core.popup.close(_form.closest('.modal'));
					var htm = html.split('|||');
					if($('.tbody_property.is_group').length){
						$('.tbody_property.is_group:last').after(htm[1]);
					} else {
						$('.tbody_property.no_group').after(htm[1]);
					}
				} else if(html.indexOf('_update_success') >= 0){
					$Core.popup.close(_form.closest('.modal'));
					var htm = html.split('|||');
					$('#title_group_'+group_id).text(htm[1]);
				}
			}
		});
	}
	return false;
}
function delete_group(_this, e){
	e.preventDefault();
	var project_id = $(_this).attr('project_id'),
		group_id= $(_this).attr('group_id'),
		$_adata = {'group_id':group_id,'project_id':project_id};
	$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn xóa nhóm này", function(){
		$.post(path_ajax_script+"/?mod="+mod+"&act=delete_group", $_adata, function(html){
			toggleIndicatior(0);
			if(html.indexOf('_success') >= 0){
				$('#'+group_id).remove();
			}
		});
	});
	return false;
}
function select_file(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid');
	$('.selectFile').attr('uid', uid).trigger('click');
	return false;
}
function open_block(_this, e){
	e.preventDefault();
	var block_id = $(_this).attr('block_id'),
		project_id = $(_this).attr('project_id'),
		_openFrom = $(_this).getAttr('_openFrom', '_project'),
		_more = {};
	if($Core.util.isEmpty(project_id)){
		$Core.alert.error('Chưa chọn dự án !!!');
	} else {
		if(_openFrom == '_stock'){
			var toId = $(_this).attr('toId');
			_more['toId'] = toId;
		}
		vietiso_loading(0);
		$.post(path_ajax_script+'/index.php?mod=project&act=open_block', $.extend({
			'project_id' : project_id,
			'block_id' : block_id,
			'_openFrom' : _openFrom
		}, _more), function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_block');
		});
	}
	return false;
}
function pop_save_block(_this, e){
	e.preventDefault();
	var block_id = $(_this).attr('block_id'),
		project_id = $(_this).attr('project_id'),
		_openFrom = $(_this).attr('_openFrom'),
		_validated = 0,
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
	var more = {};
	if($('.isoTextArea', _form).length){
		$('.isoTextArea', _form).each((_i, _elem) => {
			var name = $(_elem).data('name'),
				editorId = $(_elem).attr('id');
			more[name] = $Core.util.getTextAreaContent(editorId);
		});
	}
	if(_validated == 0){
		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=project&act=pop_save_block',
			dataType: 'json',
			data: $.extend(more, {'block_id':block_id, 'project_id':project_id}),
			success: function(respJson){
				vietiso_loading(0);
				if(respJson.msg.indexOf('_error') >= 0){
					$Core.alert.error("Error !");
				} if(respJson.msg.indexOf('_duplicate') >= 0){
					$Core.alert.error("Duplicated !");
				} else {
					$Core.popup.close(_form.closest('.modal'));
					if(_openFrom=='_project'){
						loadBlock(project_id, {});
					} else {
						$.post(path_ajax_script+'/index.php?mod=ajax&act=load_select_property', {
							'for_id' : project_id,
							'property_id' : respJson.block_id,
							'property_type' : respJson.property_type
						}, function(html){
							var toId = $(_this).attr('toId');
							$('#'+toId).html(html).trigger('change');
						});
					}
				}
			}
		});
	}
	return false;
}
function delete_block(_this, e){
	e.preventDefault();
	var block_id = $(_this).attr('block_id'),
		project_id = $(_this).attr('project_id');
	$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn xóa phân khu này", function(){
		vietiso_loading(0);
		$.post(path_ajax_script+'/index.php?mod=project&act=delete_block', {
			'project_id' : project_id,
			'block_id' : block_id
		}, function(respJson){
			if(respJson.result.indexOf('_error') >= 0){
				$Core.alert.error(respJson.message);
			} else {
				loadBlock(project_id, {});
			}
		}, 'json');
	});
	return false;
}
function add_progress(_this, e){
	e.preventDefault();
	var _form = $(_this).closest('form'),
		_tbody = $(_this).closest('.widget-block');
		project_id = $(_this).attr('project_id'),
		total_record = $('.tr_property', _form).size(),
		_holderG = $(_this).getAttr('_holderG', '_properties'),
		_openFrom = $(_this).getAttr('_openFrom', '_project');
	// alert(_holderG);
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod=project&act=add_progress', {
		'project_id' : project_id,
		'total_record' : total_record,
		'_holderG' : _holderG,
		'_openFrom' : _openFrom
	}, function(html){
		vietiso_loading(0);
		if(_holderG=='_attrs'){
			if($('.tr_attrs', _form).length==0){
				$('.tbody_attrs', _tbody).html(html);
			} else {
				$('.tr_attrs:last', _tbody).after(html);
			}
		} else {

			if($('.tr_property', _form).length==0){
				$('.tbody_property', _tbody).html(html);
			} else {
				$('.tr_property:last', _tbody).after(html);
			}
		}
		$Core.project.init_progress_media();
	});
	return false;
}
// Project progress
function open_progress(_this, e){
	e.preventDefault();
	var block_id = $(_this).attr('block_id'),
		project_id = $(_this).attr('project_id'),
		building_id = $(_this).attr('building_id'),
		_openFrom = $(_this).getAttr('_openFrom', '_project'),
		_more = {};
	if($Core.util.isEmpty(project_id)){
		$Core.alert.error('Chưa chọn dự án !!!');
	} else {
		if(_openFrom == '_stock'){
			var toId = $(_this).attr('toId');
			_more['toId'] = toId;
		}
		vietiso_loading(0);
		$.post(path_ajax_script+'/index.php?mod=project&act=open_progress', $.extend({
			'project_id' : project_id,
			'block_id' : block_id,
			'building_id' : building_id,
			'_openFrom' : _openFrom
		}, _more), function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_progress');
			setTimeout(function(){ $Core.project.init_progress_media(); }, 200);
		});
	}
	return false;
}
function pop_save_progress(_this, e){
	e.preventDefault();
	var block_id = $(_this).attr('block_id'),
		project_id = $(_this).attr('project_id'),
		building_id = $(_this).attr('building_id'),
		_openFrom = $(_this).attr('_openFrom'),
		_validated = 0,
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
	// Đẩy nội dung mọi TinyMCE về textarea rồi đọc (fallback .val() cho editor mới thêm chưa kịp register)
	if(typeof tinyMCE !== 'undefined' && tinyMCE.triggerSave) tinyMCE.triggerSave();
	var more = {};
	$('.isoTextArea', _form).each(function(){
		var name = $(this).data('name');
		if(!name) return;
		var editorId = $(this).attr('id');
		more[name] = (typeof tinyMCE !== 'undefined' && tinyMCE.get && tinyMCE.get(editorId)) ? tinyMCE.get(editorId).getContent() : $(this).val();
	});
	if(_validated == 0){
		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=project&act=pop_save_progress',
			dataType: 'json',
			data: $.extend(more, {'block_id':block_id, 'project_id':project_id, 'building_id':building_id}),
			success: function(respJson){
				vietiso_loading(0);
				if(respJson.msg.indexOf('_error') >= 0){
					$Core.alert.error("Error !");
				} if(respJson.msg.indexOf('_duplicate') >= 0){
					$Core.alert.error("Duplicated !");
				} else {
					$Core.popup.close(_form.closest('.modal'));
					if(_openFrom=='_project'){
						loadBlock(project_id, {});
					} else {
						$.post(path_ajax_script+'/index.php?mod=ajax&act=load_select_property', {
							'for_id' : project_id,
							'property_id' : respJson.block_id,
							'property_type' : respJson.property_type
						}, function(html){
							var toId = $(_this).attr('toId');
							$('#'+toId).html(html).trigger('change');
						});
					}
					$Core.alert.success("Success !");
				}
			}
		});
	}
	return false;
}
// ===== Ảnh căn hộ theo loại × Type (Bóc mái / Nội thất) — Tòa ưu tiên, fallback Phân khu. Tái dùng media Tiến độ =====
function open_interior_ns(_this, e){
	if(e) e.preventDefault();
	var project_id = $(_this).attr('project_id'),
		building_id = $(_this).attr('building_id') || '',
		block_id = $(_this).attr('block_id') || '';
	if($Core.util.isEmpty(project_id)){ $Core.alert.error('Chưa chọn dự án !!!'); return false; }
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod=project&act=open_interior_ns', {
		'project_id': project_id, 'building_id': building_id, 'block_id': block_id
	}, function(html){
		vietiso_loading(0);
		$Core.popup.open('auto', 'auto', html, 'open_interior_ns');
		setTimeout(function(){ $Core.project.init_progress_media(); }, 200);
	});
	return false;
}
function add_interior_type(_this, e){
	if(e) e.preventDefault();
	var $modal = $(_this).closest('.modal');
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod=project&act=add_interior_type', {}, function(resp){
		vietiso_loading(0);
		if(resp.error){ $Core.alert.error(resp.error); return; }
		$modal.find('.it-empty').remove();
		$modal.find('#it_holder').append(resp.html);
		$Core.project.init_progress_media();
	}, 'json');
	return false;
}
function remove_interior_type(_this, e){
	if(e) e.preventDefault();
	$(_this).closest('.interior-type-card').remove();
	return false;
}
function save_interior_ns(_this, e){
	if(e) e.preventDefault();
	var project_id = $(_this).attr('project_id'),
		building_id = $(_this).attr('building_id') || '',
		block_id = $(_this).attr('block_id') || '',
		_form = $(_this).closest('form');
	vietiso_loading(1);
	_form.ajaxSubmit({
		type: 'POST',
		url: path_ajax_script+'/index.php?mod=project&act=save_interior_ns',
		dataType: 'json',
		data: {'project_id': project_id, 'building_id': building_id, 'block_id': block_id},
		success: function(resp){
			vietiso_loading(0);
			if(!resp || resp.msg.indexOf('_error') >= 0){ $Core.alert.error('Lỗi lưu !'); return; }
			$Core.popup.close(_form.closest('.modal'));
			$Core.alert.success('Đã lưu ảnh căn hộ theo loại.');
		}
	});
	return false;
}
function delete_progress(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid')
	var progress_id = $(_this).attr('progress_id')
	var block_id = $(_this).attr('block_id')
	var project_id = $(_this).attr('project_id')
	if (!project_id) {
		$('#' + uid).remove();
		$('.tr_media_' + uid).remove();
		return false;
	}
	$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn xóa tiến độ này", function(){
		vietiso_loading(0);
		$.post(path_ajax_script+'/index.php?mod=project&act=delete_progress', {
			'progress_id' : progress_id,
			'block_id' : block_id,
			'project_id' : project_id
		}, function(respJson){
			console.log(respJson)
			if(respJson.result.indexOf('_error') >= 0){
				$Core.alert.error(respJson.message);
			} else {
				$(_this.closest(".tbody_attrs")).html(respJson.html);
				$Core.project.init_progress_media();
			}
		}, 'json');
	});
	return false;
}

function add_progress_media(_this, e){
	e.preventDefault();
	var _form = $(_this).closest('form'),
		_tbody = $(_this).closest('.widget-block');
		project_id = $(_this).attr('project_id'),
		total_record = $('.tr_property', _form).size(),
		_holderG = $(_this).getAttr('_holderG', '_properties'),
		_openFrom = $(_this).getAttr('_openFrom', '_project');
	// alert(_holderG);
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod=project&act=add_progress_media', {
		'project_id' : project_id,
		'total_record' : total_record,
		'_holderG' : _holderG,
		'_openFrom' : _openFrom
	}, function(html){
		vietiso_loading(0);
		if(_holderG=='_attrs'){
			if($('.tr_attrs', _form).length==0){
				$('.tbody_attrs', _tbody).html(html);
			} else {
				$('.tr_attrs:last', _tbody).after(html);
			}
		} else {
			if($('.tr_property', _form).length==0){
				$('.tbody_property', _tbody).html(html);
			} else {
				$('.tr_property:last', _tbody).after(html);
			}
		}
	});
	return false;
}

function delete_progress_media(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid')
	var progress_id = $(_this).attr('progress_id')
	var block_id = $(_this).attr('block_id')
	var project_id = $(_this).attr('project_id')
	if (!project_id) {
		$(`#${uid}_media`).remove();
		return false;
	}
	$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn xóa phương tiện này", function(){
		vietiso_loading(0);
		$.post(path_ajax_script+'/index.php?mod=project&act=delete_progress_media', {
			'progress_id' : progress_id,
			'block_id' : block_id,
			'project_id' : project_id
		}, function(respJson){
			console.log(respJson)
			if(respJson.result.indexOf('_error') >= 0){
				$Core.alert.error(respJson.message);
			} else {
				$(_this.closest(".tbody_attrs_media")).html(respJson.html)
			}
		}, 'json');
	});
	return false;
}
//
function open_building(_this, e){
	e.preventDefault();
	var block_id = $(_this).attr('block_id'),
		project_id = $(_this).attr('project_id'),
		building_id = $(_this).attr('building_id');
	if($Core.util.isEmpty(project_id)){
		$Core.alert.error('Chưa chọn dự án !!!');
	} else if($Core.util.isEmpty(block_id)){
		$Core.alert.error('Chưa chọn phân khu !!!');
	} else {
		var _more = {}, _openFrom = $(_this).getAttr('_openFrom', '_project');
		if(_openFrom == '_stock'){
			var toId = $(_this).attr('toId');
			_more['toId'] = toId;
		}
		vietiso_loading(0);
		$.post(path_ajax_script+'/index.php?mod=project&act=open_building', $.extend(_more, {
			'building_id' : building_id,
			'project_id' : project_id,
			'block_id' : block_id,
			'_openFrom' : _openFrom
		}), function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_building');
			$('#'+respJson.toId).on('hide.bs.dropdown', function (e) {
				if (e.clickEvent) {
				  e.preventDefault();
				}
			});
			if(respJson.callback) eval(respJson.callback);
		},'json');	
	}	
	return false;
}
function pop_save_building(_this, e){
	e.preventDefault();
	var block_id = $(_this).attr('block_id'),
		project_id = $(_this).attr('project_id'),
		building_id = $(_this).attr('building_id'),
		stock_type = $(_this).attr('stock_type'),
		_openFrom = $(_this).getAttr('_openFrom', '_project'),
		_validated = 0, _form = $(_this).closest('form');
	if($('input.required,select.required,textarea.required', _form).length){
		$('input.required,select.required,textarea.required', _form).each((_i, _elem) => {
			if($Core.util.isEmpty($(_elem).val())){
				_validated++;
				$(_elem).focus();
				return false;
			}
		});
	}
	var more = {'stock_type':stock_type};
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
			url: path_ajax_script+'/index.php?mod=project&act=pop_save_building',
			dataType: 'json',
			data: $.extend(more, {
				'block_id':block_id,
				'project_id':project_id,
				'building_id':building_id
			}),
			success: function(respJson){
				vietiso_loading(0);
				if(respJson.msg.indexOf('_error') >= 0){
					$Core.alert.error("Error !");
				} if(respJson.msg.indexOf('_duplicate') >= 0){
					$Core.alert.error("Duplicated !");
				} else {
					if(_openFrom=='_stock'){
						$Core.alert.success("Success !");
						if($('input[name=is_locked]', _form).is(':checked')){
							$('.btn_reset_building_'+building_id).attr('disabled', true);
						} else {
							$('.btn_reset_building_'+building_id).removeAttr('disabled');
						}
						$.post(path_ajax_script+'/index.php?mod=ajax&act=load_select_property', {
							'for_id' : block_id,
							'property_id' : respJson.building_id,
							'property_type' : respJson.property_type
						}, function(html){
							var toId = $(_this).attr('toId');
							$('#'+toId).html(html);
						});
					} else {
						$Core.popup.close(_form.closest('.modal'));
						if(stock_type!=_BLOCK_TYPE_LOWFLOOR_SALE){
							if(parseInt(building_id) > 0){
								if($('input[name=is_locked]', _form).is(':checked')){
									$('.btn_delete_building_'+building_id).addClass('disabled');
								} else {
									$('.btn_delete_building_'+building_id).removeClass('disabled');
								}
							} else {
								$Core.project.open_building(respJson.building_id, {
									'project_id':project_id,
									'block_id':block_id,
									'_openFrom' : _openFrom
								});
							}
						}
						loadBlock(project_id, {});
					}
				}
			}
		});
	}
	return false;
}
function delete_building(_this, e){
	e.preventDefault();
	var block_id = $(_this).attr('block_id'),
		project_id = $(_this).attr('project_id'),
		building_id = $(_this).attr('building_id');
	if($(_this).hasClass('disabled'))
		return false;
	$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn xóa tòa nhà này", function(){
		vietiso_loading(0);
		$.post(path_ajax_script+'/index.php?mod=project&act=delete_building', {
			'building_id' : building_id,
			'project_id' : project_id,
			'block_id' : block_id
		}, function(html){
			if(html.indexOf('_success')>=0){
				loadBlock(project_id, {});
			} else {
				$Core.alert.error('Lỗi không được xóa');
			}
		});
	});
	return false;
}
function load_template_building(_this, e){
	vietiso_loading(0);
	var _form = $(_this).closest('form'),
		toId = $(_this).attr('toId'),
		building_id = $(_this).attr('building_id'),
		number_house = $('input[name=number_house]').val();
	if(parseInt(number_house) > 0){
		var $_adata = {'toId' : toId,'action' : 'add_quick','building_id' : building_id,'number_house' : number_house};
		$("input.floor_specical",_form).each(function(index,elm){
			let name = $(elm).attr('name');
			$_adata[name] = $(elm).val();
		});
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=project&act=load_template_building',
			dataType: 'json',
			data: $_adata,
			success: function(respJson){
				vietiso_loading(0);
				$.each(respJson,function(key,val){
					$("#tab_"+key).find("tbody").html(val);
				});
			}
		});
	} else {
		$Core.alert.error('Số căn hộ/tầng phải lớn hơn [0]');
	}
	return false;
}
function open_copypaste_excel(_this, e){
	e.preventDefault();
	vietiso_loading(1);
	var toId = $(_this).attr('toId'),
		building_id = $(_this).attr('building_id');
	$.post(path_ajax_script+'/index.php?mod=project&act=open_copypaste_excel', {
		'toId' : toId,
		'building_id' : building_id
	}, function(html){
		vietiso_loading(0);
		$Core.popup.open('auto','auto', html, 'open_copypaste_excel');
	});
	return false;
}
function start_copypaste_excel(_this, e){
	e.preventDefault();
	var cls = $(_this).attr('uid'),
		toId = $(_this).attr('toId'),
		building_id = $(_this).attr('building_id');
	// Get the value of copy/pasted cells from excel to textarea
	var excel_data = $('.'+cls).val();
	if(!$Core.util.isEmpty(excel_data)){
		// split data into JS-table
		let data = new Array(),
			rows = excel_data.split("\n");
		for (let y in rows) {
			// Check if row is not empty
			if (rows[y].length > 0) {
				 // Check if row is not empty
				 let arr = new Array(), cells = rows[y].split("\t"); 
				 for (let x in cells) {
					 arr[x]= $.trim(cells[x]);
				 }
				data[y] = arr;
			}
		}
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=project&act=load_template_building', {
			'data' : data,
			'toId' : toId,
			'action' : 'copypaste',
			'building_id' : building_id
		}, function(html){
			vietiso_loading(0);
			$('.holder_template_building tbody').html(html);
			$Core.popup.close($(_this).closest('.modal'));
		});
	} else {
		$Core.alert.error('Lỗi! Không có dữ liệu !');
	}
	return false;
}
$().ready(function(){
	if(mod=='project' && act=='edit'){
		loadBlock(project_id, {});
		loadListOptions(project_id, {});
		loadListFormShare(project_id, {});
		loadListDocShare(project_id, {});
		$Core.utilities.loadList(project_id, {});
	}
	$_document.on('change', '.selectFile', function(ev){
		var _this = $(this),
			_uid = _this.attr('uid'),
			_form = _this.closest('form');
		toggleIndicatior(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod=project&act=upload_file',
			dataType:'html',
			success : function(html){
				toggleIndicatior(0);
				_form.clearForm();
				_form.resetForm();
				$('.content_field_'+_uid).val(html);
			}
		});
	});
	$_document.on('click', '.ajOpenOptions,.deleteOptions', function(ev){
		var $_this = $(this),
			project_id = $_this.attr('project_id'),
			option_id = $_this.attr('option_id');
		if($_this.hasClass('deleteOptions')){
			$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn xóa", function(){
				toggleIndicatior(1);
				$.ajax({
					type: 'POST',
					url: path_ajax_script+'/index.php?mod='+mod+'&act=ajUpdateOptions&action=_delete', 
					data:{'option_id':option_id,"project_id":project_id},
					dataType:'html',
					success: function(html){
						toggleIndicatior(0);
						loadListOptions(project_id, {});
					}
				});
			});
			return false;
		} else {
			vietiso_loading(1);
			$.ajax({
				type:'POST',	
				url : path_ajax_script+'/index.php?mod='+mod+'&act=ajOpenOptions',
				data: {"project_id":project_id,'option_id':option_id},
				dataType:'html',
				success: function(html){ 
					vietiso_loading(0);
					var htm = html.split('|||');
					$Core.popup.open('auto','auto', htm[0], 'OpenOptions_'+project_id);
					if(htm[2]==='_add'){
						var crm_datepicker_format = $.extend(crm_datepicker_format, {
							minDate : new Date(Date.parse(htm[1]))
						});
					}
					$('.isodatepicker:not(.hasDatepicker)').datepicker(crm_datepicker_format);
				}
			});
		}
		return false;
	});
	$_document.on('click', '.clickToSaveOptions', function(ev){
		ev.preventDefault();
		var $_this = $(this),
			$_form = $_this.closest('form'),
			option_id = $.trim($_this.attr('option_id')),
			project_id = $.trim($_this.attr('project_id'));
		var _validated = 0;
		if($('input.required', $_form).length){
			$('input.required', $_form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			vietiso_loading(1);
			$_form.ajaxSubmit({
				type:'POST',	
				url : path_ajax_script+'/index.php?mod='+mod+'&act=ajUpdateOptions',
				data: {"project_id":project_id,'option_id':option_id},
				dataType:'html',
				success: function(html){ 
					vietiso_loading(0);
					$Core.alert.success('Saved !');
					$Core.popup.close($_form.closest(".modal"));
					loadListOptions(project_id, {});
				}
			});
		}
		return false;
	});
	$_document.on('click', '.createNewDocShare,.ajOpenDocShare', function(ev){
		ev.preventDefault();
		var $_this = $(this),
			project_id = $_this.attr('project_id'),
			doc_share_id = $_this.attr('doc_share_id');
		toggleIndicatior(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajOpenDocShare', 
			data:{"project_id":project_id,'doc_share_id':doc_share_id}, 
			dataType:'html',
			success: function(html){
				toggleIndicatior(0);
				$Core.popup.open('auto','auto', html, 'OpenDocShare_'+project_id);
				$('#InputDocShareUserView_'+doc_share_id).select2({
					allowClear : true
				});
			}
		});
		return false;
	});
	$_document.on('click', '.clickToSaveDocShare', function(ev){
		var $_this = $(this),
			project_id = $_this.attr('project_id'),
			doc_share_id = $_this.attr('doc_share_id');
		var _validated = 0, _form = $_this.closest('form');
		if($('input.required', _form).length){
			$('input.required', _form).each(function(){
				if($Core.util.isEmpty($(this).val())){
					_validated++;
					$(this).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=saveDocShare', 
				data:{"project_id":project_id,'doc_share_id':doc_share_id}, 
				dataType:'html',
				success: function(html){
					toggleIndicatior(0);
					loadListDocShare(project_id, {});
					$Core.popup.close($_this.closest(".modal"));
				}
			});
		}
		return false;
	});
	$_document.on('click', '.deleteDocShare', function(ev){
		ev.preventDefault();
		var $_this = $(this),
			tp = $_this.attr("tp"),
			pval = $_this.attr("pval"),
			doc_share_id = $_this.attr("doc_share_id");
		$("#dialog-confirm-delete").dialog({
			height:140,
			modal: true,
			resizable: false,
			buttons: {
				"Đồng ý xóa": function() {
					toggleIndicatior(1);
					var _this = $(this); 
					$.ajax({
						type: 'POST',
						url: path_ajax_request+'/index.php?mod=crm&act=saveDocShare&action=delete', 
						data:{"tp":tp,"pval":pval,"doc_share_id":doc_share_id},
						dataType:'html',
						success: function(html){
							toggleIndicatior(0);
							_this.dialog("close");
							loadListDocShare(tp,pval);
						}
					});
				}
			}
		}).closest(".ui-dialog").css("z-index",getmaxzindex());
		return false;	
	});
	$_document.on('click', '.createNewFormShare,.ajOpenFormShare', function(ev){
		ev.preventDefault();
		var $_this = $(this),
			project_id = $_this.attr('project_id'),
			form_share_id = $_this.attr('form_share_id');
		toggleIndicatior(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajOpenFormShare', 
			data:{"project_id":project_id,'form_share_id':form_share_id}, 
			dataType:'html',
			success: function(html){
				toggleIndicatior(0);
				$Core.popup.open('30%','auto', html, 'OpenFormShare_'+project_id);
			}
		});
		return false;
	});
	$_document.on('click', '.clickToSaveFormShare', function(ev){
		ev.preventDefault();
		var $_this = $(this),
			project_id = $_this.attr('project_id'),
			form_share_id = $_this.attr('form_share_id');
		var _validated = 0, _form = $_this.closest('form');
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
			toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=saveFormShare', 
				data:{"project_id":project_id,'form_share_id':form_share_id}, 
				dataType:'html',
				success: function(html){
					toggleIndicatior(0);
					if(html.indexOf('_duplicate')>=0){
						$Core.alert.error('Lỗi');
					}else{
						loadListFormShare(project_id, {});
						$Core.popup.close($_this.closest(".modal"));
					}
				}
			});
		}
		return false;
	});
	$_document.on('click', '.deleteDocShare', function(ev){
		var $_this = $(this),
			tp = $_this.attr("tp"),
			pval = $_this.attr("pval"),
			doc_share_id = $_this.attr("doc_share_id");
		$("#dialog-confirm-delete").dialog({
			height:140,
			modal: true,
			resizable: false,
			buttons: {
				"Đồng ý xóa": function() {
					toggleIndicatior(1);
					var _this = $(this); 
					$.ajax({
						type: 'POST',
						url: path_ajax_request+'/index.php?mod=crm&act=saveDocShare&action=delete', 
						data:{"tp":tp,"pval":pval,"doc_share_id":doc_share_id},
						dataType:'html',
						success: function(html){
							toggleIndicatior(0);
							_this.dialog("close");
							loadListDocShare(tp,pval);
						}
					});
				}
			}
		}).closest(".ui-dialog").css("z-index",getmaxzindex());
		return false;	
	});
	// Page Ads - Mod: Ads - Act: Default
	$('.filter-tab').on('click', function(){
		var target = $(this).attr('target');
		$('.filter-tab').removeClass('next-tab--is-active');
		$(this).addClass('next-tab--is-active');
		$('.ui-card__section').addClass('hidden');
		$('#'+target).removeClass('hidden');
		return false;
	});
	if(mod == 'ads' && act== 'default') {
			load_list_adsgroup('',1,10);
			$('#keyword_ads_group').bind('keyup change',function(){
				var $_this = $(this);
				load_list_adsgroup($_this.val(),1,10);
			});
			$('.ajSubmitAdsGroup').live('click',function(){
				var $_this = $(this);
				var $_form = $_this.closest('.frmPop');
				var adata = {
					'parent_id'		: $parent_id.val(),
					'title'			: $title.val(),
					'code'			: $code.val(),
					'width'			: $width.val(),
					'height'		: $height.val(),
					'intro'			: $intro.val(),
					'ads_group_id' 	: $_this.attr('ads_group_id'),
					'tp' : 'S'
				};
				vietiso_loading(1);
				$.ajax({
					type:'POST',
					url : path_ajax_script+'/index.php?mod='+mod+'&act=ajSiteAdsGroup',
					data:adata,
					dataType:'html',
					success:function(html){
						if(html.indexOf('_INSERT_SUCCESS')>=0){
							loadListAdsGroup('',1,10);
							$_this.closest('.frmPop').find('.close_pop').trigger('click');
						}
						if(html.indexOf('_UPDATE_SUCCESS')>=0){
							var $keyword = $('#keyword').val();
							var $page = $('.paginate_current_page').val();
							var $number_per_page = $('.paginate_length').val();
							loadListAdsGroup($keyword,$page,$number_per_page);
							$_this.closest('.frmPop').find('.close_pop').trigger('click');
						}
						if(html.indexOf('_ERROR')>=0){
							alertify.error(insert_error);
						}
						if(html.indexOf('_EXIST')>=0){
							alertify.error(exist_error);
						}
						vietiso_loading(0);
					}
				});
			});
			$('.ajDeleteAdsGroup').live('click',function(){
				var $_this = $(this);
				if(confirm(confirm_delete)){
					var adata = {
						'ads_group_id' : $_this.attr('data'),
						'tp' : 'D'
					};
					vietiso_loading(1);
					$.ajax({
						type:'POST',
						url:path_ajax_script+'/index.php?mod='+mod+'&act=ajSiteAdsGroup',
						data : adata,
						dataType:'html',
						success:function(html){
							var $keyword = $('#keyword').val();
							var $page = $('.paginate_current_page').val();
							var $number_per_page = $('.paginate_length').val();
							load_list_adsgroup($keyword,$page,$number_per_page);
							vietiso_loading(0);
						}
					});
				}
				return false;
			});
			$('.ajMoveAdsGroup').live('click',function(){
				var $_this = $(this);
				var adata = {
					'ads_group_id' : $_this.attr('data'),
					'direct' : $_this.attr('direct'),
					'parent_id' : $_this.attr('parent_id'),
					'tp' : 'M'
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/?mod="+mod+"&act=ajSiteAdsGroup",
					data: adata,
					dataType: "html",
					success: function(html){
						var $keyword = $('#keyword').val();
						var $page = $('.paginate_current_page').val();
						var $number_per_page = $('.paginate_length').val();
						load_list_adsgroup($keyword,$page,$number_per_page);
						vietiso_loading(0);
					}
				});
				return false;
			});
			$('.paginate_length').live('change',function(){
				var $_this = $(this);
				var $keyword = $('#keyword').val();
				var $page = 1;
				var $number_per_page = $_this.val();
				load_list_adsgroup($keyword,$page,$number_per_page);
			});
			$('.paginate_button').live('click',function(){
				var $_this = $(this);
				if(!$_this.hasClass('disabled')){
					var $keyword = $('#keyword').val();
					var $page = $_this.attr('page');
					var $number_per_page = $('.paginate_length').val();
					load_list_adsgroup($keyword,$page,$number_per_page);
				}
				return false;
			});
	}
});
function load_list_adsgroup($keyword, $page, $number_per_page){
	var $_adata = {
		'keyword' : $keyword,
		'page'	: $page,
		'number_per_page' : $number_per_page,
	};
	vietiso_loading(1);
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/?mod="+mod+"&act=load_list_adsgroup",
		data: $_adata,
		dataType: "html",
		success: function(html){
			vietiso_loading(0);
			var htm = html.split('$$');
			$('#tblAdsGroup').html(htm[0]);
			$('#dataTable_paginate').html(htm[1]);
		}
	});
}
function loadListFormShare(project_id, options){
	var $_adata = options || {};
	$_adata['project_id'] = project_id;
	toggleIndicatior(1);
	$.post(path_ajax_script+"/?mod="+mod+"&act=ajLoadListFormShare", $_adata, function(respJson){
		toggleIndicatior(0);
		$('.holderFormShare').html(respJson.html);
		$('.'+respJson.uid).freezeTable({
			'columnNum': 1,
			'scrollable': true,
			'columnKeep': false,
		});
	}, 'json')
}
function loadListDocShare(project_id, options){
	var $_adata = options || {};
	$_adata['project_id'] = project_id;
	toggleIndicatior(1);
	$.post(path_ajax_script+"/?mod="+mod+"&act=ajLoadListDocShare", $_adata, function(respJson){
		toggleIndicatior(0);
		$('.holderDocShare').html(respJson.html);
		$('.'+respJson.uid).freezeTable({
			'columnNum': 1,
			'scrollable': true,
			'columnKeep': false,
		});
	}, 'json')
}
function loadListOptions(project_id, options){
	var $_adata = options || {};
	$_adata['project_id'] = project_id;
	toggleIndicatior(1);
	$.post(path_ajax_script+"/?mod="+mod+"&act=ajLoadListOptions", $_adata, function(respJson){
		toggleIndicatior(0);
		$('.holderOptions').html(respJson.html);
		if($('.'+respJson.uid).length){
			$('.'+respJson.uid).freezeTable({
				'columnNum': 1,
				'scrollable': true,
				'columnKeep': false,
			});
		}
	}, 'json');
}
function loadBlock(project_id, options){
	// Trên trang Tổng quan: reload partial _overview_blocks.tpl (giữ toggle quick-menu)
	if(window.__projectView === 'overview'){
		$.get(path_ajax_script+"/?mod=project&act=overview_blocks&project_id="+project_id, function(html){
			toggleIndicatior(0);
			$('.holderBlock').html(html);
		});
		return;
	}
	var $_adata = options || {};
	$_adata['project_id'] = project_id;
	$.post(path_ajax_script+"/?mod=project&act=load_blocks", $_adata, function(respJson){
		toggleIndicatior(0);
		$('.holderBlock').html(respJson.html);
		$(".tbodyBlock").sortable({
			connectWith: ".tbodyBlock",
			handle: ".mySortableHandler",
			update: function (event, ui) {
				vietiso_loading(1);	
				var list_ids = $(this).sortable('toArray');
				$.post(path_ajax_script+"/index.php?mod="+mod+"&act=upd_order_billing", {
					"list_ids":list_ids
				},function(html){
					vietiso_loading(0);	
				});
			}
		}).disableSelection();
	}, 'json');
}
function start_create_stock(_this, e){
	var holderG = $(_this).attr('holderG'),
		project_id = $(_this).attr('project_id'),
		block_id = $(_this).attr('block_id'),
		building_id = $(_this).attr('building_id');
	$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn thực hiện thao tác này", function(){
		vietiso_loading(1);
		$.post(path_ajax_script+"/?mod=project&act=start_create_stock", {
			'holderG' : holderG,
			'project_id' : project_id,
			'block_id' : block_id,
			'building_id' : building_id,
		}, function(html){
			vietiso_loading(0);
			if(html.indexOf('floor_error') >= 0){
				$Core.alert.error("Lỗi! Chưa nhập vào danh sách tầng, nhấn [Cập nhật] rồi thực hiện lại.");
			} else if(html.indexOf('stock_template_error') >= 0){
				$Core.alert.error("Lỗi! Chưa nhập vào danh sách mẫu căn hộ, nhấn [Cập nhật] rồi thực hiện lại");
			} else if(html.indexOf('building_error') >= 0){
				$Core.alert.error("Lỗi! Chưa tạo tòa nhà này, nhấn [Cập nhật] rồi thực hiện lại");
			} else if(html.indexOf('_success') >= 0){
				$Core.alert.success('Thành công');
			}
		});
	});
}
function set_quick_menu(_this, e){
	e.preventDefault();
	var tp = $(_this).attr('tp'),
		for_id = $(_this).attr('for_id'),
		to_field = $(_this).attr('to_field'),
		status = $(_this).is(':checked') ? 1 : 0;
	$.post(path_ajax_script+"/?mod=project&act=set_quick_menu", {
		'tp' : tp,
		'to_field' : to_field,
		'status' : status,
		'for_id' : for_id
	}, function(html){
	});
}
function add_template_line(_this, e){
	e.preventDefault();
	var form = $(_this).closest('form'),
		toId = $(_this).attr('toId'),
		key = $(".nav-item_tab_floor.active").attr('key'),
		project_id = $(_this).attr('project_id'),
		building_id = $(_this).attr('building_id'),
		total_stocks = $("#tab_"+key + " .tr_template_"+building_id,form).size();
	vietiso_loading(1);
	$.post(path_ajax_script+"/?mod=project&act=add_template_line", {
		'toId' : toId,
		'key' : key,
		'project_id' : project_id,
		'building_id' : building_id,
		'total_stocks' : total_stocks
	}, function(html){
		vietiso_loading(0);
		$("#tab_"+key + " .tr_template_"+building_id+":last",form).after(html);
	});
	return false;
}
$Core.project = {
	// ===== Tiến độ — media gallery (Drive folder / upload / link lẻ; video ưu tiên + highlight) =====
	pmGet: (uid) => {
		var v = $('.pm-json[uid="'+uid+'"]').val() || '[]';
		try { return JSON.parse(v); } catch(e){ return []; }
	},
	pmSet: (uid, items) => {
		items = items || [];
		items.sort((a,b) => (a.type==='video'?0:1) - (b.type==='video'?0:1)); // video trước
		$('.pm-json[uid="'+uid+'"]').val(JSON.stringify(items));
		$Core.project.renderProgressMedia(uid, items);
	},
	renderProgressMedia: (uid, items) => {
		var $grid = $('.pm-grid[uid="'+uid+'"]');
		if(!$grid.length) return;
		if(!items || !items.length){
			$grid.html('<div class="pm-empty text-muted">Chưa có media — Đồng bộ Drive / Upload ảnh / dán link lẻ.</div>');
			return;
		}
		var html = '';
		items.forEach((it, idx) => {
			var isVid = it.type === 'video';
			var thumb = it.thumb || (isVid ? '' : it.url);
			html += '<div class="pm-thumb'+(isVid?' pm-thumb--video':'')+'" data-idx="'+idx+'">';
			html += '<a href="'+it.url+'" target="_blank" class="pm-thumb__link">';
			html += thumb ? '<img src="'+thumb+'" loading="lazy" />' : '<div class="pm-thumb__noimg"><i class="fa fa-film"></i></div>';
			if(isVid) html += '<span class="pm-play"><i class="fa fa-play"></i></span>';
			html += '<span class="pm-badge">'+(it.source||'')+'</span></a>';
			html += '<button type="button" class="pm-del" title="Xóa" onClick="$Core.project.progress_remove_media(this, event)" uid="'+uid+'" data-idx="'+idx+'">&times;</button>';
			html += '</div>';
		});
		$grid.html(html);
	},
	init_progress_media: () => {
		$('.pm-json').each(function(){
			var uid = $(this).attr('uid');
			$Core.project.renderProgressMedia(uid, $Core.project.pmGet(uid));
		});
		// TinyMCE cho ô Mô tả mỗi mốc (giữ editor đang sống, gỡ editor mồ côi khi reload)
		$('.modal textarea.isoTextArea').each(function(){
			var id = $(this).attr('id');
			if(typeof tinyMCE === 'undefined') return;
			var ed = tinyMCE.get(id);
			if(ed){
				if(ed.getContainer && $(ed.getContainer()).closest('body').length) return;
				ed.remove();
			}
			try { $(this).isoTextArea(); } catch(e){}
		});
	},
	progress_sync_drive: (_this, e) => {
		if(e) e.preventDefault();
		var uid = $(_this).attr('uid');
		var folder = $('.pm-folder[uid="'+uid+'"]').val();
		if($Core.util.isEmpty(folder)){ $Core.alert.error('Dán link folder Google Drive trước.'); return false; }
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=project&act=progress_sync_drive', {
			'folder_url': folder,
			'existing': $('.pm-json[uid="'+uid+'"]').val() || '[]'
		}, function(resp){
			vietiso_loading(0);
			if(resp.error){
				$Core.alert.error(resp.error);
				if(resp.fallback_iframe){
					$('.pm-grid[uid="'+uid+'"]').html('<iframe src="'+resp.fallback_iframe+'" class="pm-folder-iframe" frameborder="0"></iframe>');
				}
				return;
			}
			$Core.project.pmSet(uid, resp.items || []);
			$Core.alert.success('Đã đồng bộ '+(resp.count||0)+' media từ Drive.');
		}, 'json');
		return false;
	},
	progress_upload_media: (_this, e) => {
		var uid = $(_this).attr('uid');
		if(!_this.files || !_this.files.length) return;
		var fd = new FormData();
		fd.append('file', _this.files[0]);
		vietiso_loading(1);
		$.ajax({
			url: path_ajax_script+'/index.php?mod=project&act=progress_upload',
			type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json',
			success: function(resp){
				vietiso_loading(0);
				if(resp.error){ $Core.alert.error(resp.error); return; }
				var items = $Core.project.pmGet(uid); items.push(resp.item);
				$Core.project.pmSet(uid, items);
			}
		});
		$(_this).val('');
	},
	progress_add_link: (_this, e) => {
		if(e) e.preventDefault();
		var uid = $(_this).attr('uid');
		var link = $('.pm-link[uid="'+uid+'"]').val();
		if($Core.util.isEmpty(link)){ $Core.alert.error('Dán link trước.'); return false; }
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=project&act=progress_add_link', {'link': link}, function(resp){
			vietiso_loading(0);
			if(resp.error){ $Core.alert.error(resp.error); return; }
			var items = $Core.project.pmGet(uid); items.push(resp.item);
			$Core.project.pmSet(uid, items);
			$('.pm-link[uid="'+uid+'"]').val('');
		}, 'json');
		return false;
	},
	progress_remove_media: (_this, e) => {
		if(e) e.preventDefault();
		var uid = $(_this).attr('uid'), idx = parseInt($(_this).attr('data-idx'));
		var items = $Core.project.pmGet(uid);
		if(idx >= 0 && idx < items.length){ items.splice(idx, 1); $Core.project.pmSet(uid, items); }
		return false;
	},
	init: () => {
		if($('.holder_list_sop').length){
			$('.holder_list_sop').each(function(){
				var sop_id = $(this).attr('sop_id'),
					project_id = $(this).attr('project_id');
				$Core.project.load_sop_items(sop_id, project_id);
			});
		}
	}, add_floor_range_config: (_this, e) => {
		e.preventDefault();
		var project_id = $(_this).attr('project_id'),
			block_id = $(_this).attr('block_id'),
			building_id = $(_this).attr('building_id');
		var uid = $Core.util.getUniqid(),
			html = `<tr id="${uid}" class="tr_floor_range_config">
			<td class="text-center mySortableHandler">
				<i class="fa fa-bars p-3">
			</td>
			<td class="text-left">
				<select uid="${uid}" onClick="$Core.project.handle_floor_type_changed(this, event)" 
					name="floor_range_configs[${uid}][floor_type]" class="form-control">
					<option value="consecutive">Liên tục(Consecutive)</option>
					<option value="non_consecutive">Không liên tục(Non-consecutive)</option>
				</select>
			</td>
			<td class="text-left">
				<select name="floor_range_configs[${uid}][level]" class="form-control">
					<option value="low_floor">Tầng thấp</option>
					<option value="mid_floor">Tầng trung</option>
					<option value="high_floor">Tầng cao</option>
				</select>
			</td>
			<td class="text-left">
				<div class="floor_range_consecutive">
					<div class="input-group d-flex align-items-center floor_range_consecutive">
						<input type="number" name="floor_range_configs[${uid}][from]" placeholder="Từ tầng" 
							class="form-control numberonly" onClick="this.select()" />
						<input type="number" name="floor_range_configs[${uid}][to]" placeholder="Tới tầng" 
							class="form-control numberonly" onClick="this.select()" />
					</div>
				</div>
				<input type="text" name="floor_range_configs[${uid}][floor]" placeholder="Nhập các tầng cách nhau bằng dấu (,)" 
					class="form-control floor_range_non_consecutive d-none" />
			</td>
			<td class="text-center">
				<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_floor_range_config(this, event)">
					<i class="fa fa-trash" aria-hidden="true"></i>
				</button>
			</td>
		</tr>`;
		$('.tr_floor_range_config:last').after(html);
		return false;
	}, delete_floor_range_config : (_this, e) => {
		e.preventDefault();
		$Core.alert.confirm("Xác nhận xóa", "Bạn có chắc chắn muốn xóa mục này", function(){
			var _tr = $(_this).closest('tr.tr_floor_range_config');
			_tr.remove();
		});
		return false;
	}, delete_template_line : (_this, e) => {
		e.preventDefault();
		$Core.alert.confirm("Xác nhận xóa", "Bạn có chắc chắn muốn xóa mục này", function(){
			$(_this).closest('tr').remove();
		});
		return false;
	}, handle_floor_type_changed : (_this, e) => {
		var _value = $(_this).val(),
			_tr = $(_this).closest('tr.tr_floor_range_config');
		if(_value == 'consecutive'){
			$('.floor_range_non_consecutive', _tr).addClass('d-none');
			$('.floor_range_consecutive', _tr).removeClass('d-none');
		} else {
			$('.floor_range_non_consecutive', _tr).removeClass('d-none');
			$('.floor_range_consecutive', _tr).addClass('d-none');
		}
	}, load_sop_items : (sop_id, project_id, options) => {
		var $_adata = options || {};
		$_adata['sop_id'] = sop_id;
		$_adata['project_id'] = project_id;
		$.post(path_ajax_script+"/index.php?mod="+mod+"&act=load_sop_items", $_adata, (html) => {
			vietiso_loading(0);
			$('.holder_list_sop_'+sop_id).html(html);
			$(".TableListSop_"+sop_id+" tbody").sortable({
				connectWith: ".TableListSop_"+sop_id,
				handle: ".mySortableHandler",
				update: function (event, ui) {
					var orderNo = $(this).sortable('toArray'),
					$_adata = {'type_id':sop_id,"orderNo":orderNo};
					$.post(path_ajax_script+"/index.php?mod="+mod+"&act=saveSop&action=_saveorder",$_adata,function(html){});
				}
			}).disableSelection();
		});
	},
	open_sop: (_this, e) => {
		e.preventDefault();
		var $_this = $(_this),
			sop_id = $_this.attr('sop_id'),
			project_id  = $_this.attr('project_id');
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod="+mod+"&act=open_sop",
			data: {'project_id':project_id,'sop_id':sop_id},
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				var htm = html.split('|||');
				$Core.popup.open('auto','auto',htm[0],'open_sop_'+sop_id);
			}
		});
		return false;
	}, handle_field_type : (_this) => {
		if($(_this).val() == '_textarea'){
			$('.holder_template_type').addClass('hidden');
		} else {
			$('.holder_template_type').removeClass('hidden');
		}
	}, open_data_picker : (_this) => {
		var $_this = $(_this),
			sop_id = $_this.attr('sop_id'),
			project_id  = $_this.attr('project_id');
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod="+mod+"&act=open_data_picker",
			data: {'project_id':project_id,'sop_id':sop_id},
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				var htm = html.split('|||');
				$Core.popup.open('auto','auto',htm[0],'data_picker_'+sop_id);
			}
		});
		return false;
	}, save_data_items : (_this) => {
		var $_this = $(_this),
			sop_id = $_this.attr('sop_id'),
			project_id  = $_this.attr('project_id'),
			_form = $_this.closest('form'),
			utilities_ids = [];
		$('input[name="utilities_ids[]"]:checked', _form).each(function(){
			utilities_ids.push($(this).val());
		});
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod="+mod+"&act=save_data_items",
			data: {'project_id':project_id,'sop_id':sop_id,'utilities_ids':utilities_ids},
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				$Core.project.load_sop_items(sop_id, project_id, {});
				$Core.popup.close($_this.closest('.modal'));
			}
		});
		return false;
	}, move_sop : (_this, direct) => {
		var sop_id = $(_this).attr('sop_id'),
			project_id  = $(_this).attr('project_id');
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod="+mod+"&act=move_sop",
			data: {'project_id':project_id,'sop_id':sop_id, 'direct':direct},
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				window.location.reload(true);
			}
		});
	}, delete_sop : (_this) => {
		var $_this = $(_this),
			sop_id = $_this.attr('sop_id'),
			project_id  = $_this.attr('project_id');
		$Core.alert.confirm("Xác nhận xóa", "Bạn có chắc chắn muốn xóa mục này", function(){
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod="+mod+"&act=delete_sop",
				data: {'project_id':project_id,'sop_id':sop_id},
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					$(_this).closest('.ui-card').remove();
				}
			});
		});
		return false;
	}, open_sop_item : (_this) => {
		var $_this = $(_this),
			sop_id = $_this.attr('sop_id'),
			project_id  = $_this.attr('project_id'),
			sop_item_id = $_this.attr('sop_item_id');
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod="+mod+"&act=open_sop_item",
			data: {'project_id':project_id,'sop_id':sop_id,'sop_item_id':sop_item_id},
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				var htm = html.split('|||');
				$Core.popup.open('auto','auto',htm[0],'open_sop_'+sop_id);
			}
		});
		return false;
	}, delete_sop_item : (_this) => {
		var $_this = $(_this),
			sop_id = $_this.attr('sop_id'),
			project_id  = $_this.attr('project_id'),
			sop_item_id = $_this.attr('sop_item_id');
		$Core.alert.confirm("Xác nhận xóa", "Bạn có chắc chắn muốn xóa mục này", function(){
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod="+mod+"&act=delete_sop_item",
				data: {'project_id':project_id,'sop_id':sop_id,'sop_item_id':sop_item_id},
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					if(html.indexOf('_success') >= 0){
						$Core.project.load_sop_items(sop_id,project_id,{});
					}
				}
			});
		});
		return false;
	}, pop_save_sop_item : (_this) => {
		var _form = $(_this).closest('form'),
			sop_id = $(_this).attr('sop_id'),
			project_id = $(_this).attr('project_id'),
			sop_item_id = $(_this).attr('sop_item_id'),
			is_icon = $(_this).attr('is_icon'),
			$_adata = {
				'project_id':project_id,
				'sop_id':sop_id,
				'sop_item_id':sop_item_id,
				'is_icon': is_icon
			};
		var _validated = 0;
		if($('input.required', _form).length){
			$('input.required', _form).each(function(){
				if($Core.util.isEmpty($(this).val())){
					_validated++;
					$(this).focus();
					return false;
				}
			});
		}
		if($('.isoTextArea', _form).length){
			$('.isoTextArea', _form).each((_i, _elem) => {
				var editorId = $(_elem).attr('id'),
					content = $Core.util.getTextAreaContent(editorId);
				$_adata['content'] = content;
			});
		}
		if(_validated==0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type: "POST",
				url: path_ajax_script+"/index.php?mod="+mod+"&act=save_sop_item",
				data: $_adata,
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					$Core.project.load_sop_items(sop_id,project_id,{});
					$Core.popup.close(_form.closest('.modal'));
				}
			});
		}
		return false;
	}, pop_save_sop : (_this) => {
		var _validated = 0,
			_form = $(_this).closest('form'),
			sop_id = $(_this).attr('sop_id'),
			project_id = $(_this).attr('project_id');
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
				type: "POST",
				url: path_ajax_script+"/index.php?mod="+mod+"&act=pop_save_sop",
				data: {'project_id':project_id,'sop_id':sop_id},
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					if(html.indexOf('_success') >= 0){
						window.location.reload();
					}
				}
			});
		}
		return false;
	}, sync_price_sheet: (_this, e) => {
		e.preventDefault();
		vietiso_loading(1);
		$.post(path_ajax_script+"/?mod=project&act=sync_price_sheet", {}, function(html){
			vietiso_loading(0);
		});
		return false;
	},
	select_block: (_this, e) => {
		e.preventDefault();
		var project_id = $(_this).val(),
			stock_type = $(_this).attr('stock_type')
			toId = $(_this).attr('toId');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_block', {
			'project_id' : project_id,
			'stock_type' : stock_type
		}, function(html){
			vietiso_loading(0);
			$('#'+toId).html(html);
		});
		return false;
	},
	select_building: (_this, e) => {
		e.preventDefault();
		var block_id = $(_this).val(),
			toId = $(_this).attr('toId');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=policy&act=load_option_building', {
			'block_id' : block_id,
			'call_from' : '_block'
		}, function(html){
			vietiso_loading(0);
			$('#'+toId).html(html).trigger("chosen:updated");
		});
		return false;
	},
	add_layout: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			gId = $Core.util.getUniqid(),
			_table = $(_this).closest("table");
		$('tr.tr_layout:last-child',_table).after(`<tr class="tr_layout">
			<td class="text-left">
				<input type="text" name="layout_ms[${gId}][title]" 
					class="form-control requried" placeholder="Tiêu đề" />
			</td>
			<td class="text-left">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Layout tòa nhà" 
					name="layout_ms[${gId}][image]" id="layout_building_${gId}" value="" />
					<div class="input-group-btn">
						<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gid="layout_building_${gId}" toid="${toId}"><i class="fa fa-upload"></i></button>
					</div>
				</div>
			</td>
			<td class="text-center">
				<button class="btn btn-icon btn-default" onClick="$Core.project.delete_layout(this, event)">
					<i class="fa fa-trash"></i>
				</button>
			</td>
		</tr>`);
		return false;
	},
	delete_layout: (_this, e) => {
		e.preventDefault();
		$(_this).closest('tr.tr_layout').remove();
	},
	add_policy: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			gId = $Core.util.getUniqid(),
			holderG = $(_this).attr('holderG');
		if(holderG == '_block'){
			var block_id = $(_this).attr('block_id');
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod=project&act=add_policy', {
				'toId' : toId,
				'block_id' : block_id
			}, function(html){
				vietiso_loading(0);
				$('tr.tr_csbh:last-child').after(html);
			});
		} else {
			$('tr.tr_csbh:last-child').after(`<tr class="tr_csbh">
				<td class="text-left">
					<input type="text" name="sales_policy[${gId}][title]" 
						class="form-control requried" placeholder="Tiêu đề" />
				</td>
				<td class="text-left">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Hình ảnh CSBH" 
						name="sales_policy[${gId}][image]" id="sales_policy_${gId}" value="" />
						<div class="input-group-btn">
							<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gid="sales_policy_${gId}" toid="${toId}"><i class="fa fa-upload"></i></button>
						</div>
					</div>
				</td>
				<td class="text-center">
					<button type="button" class="btn btn-icon btn-default" 
						onClick="$Core.project.delete_policy(this, event)">
						<i class="fa fa-trash"></i>
					</button>
				</td>
			</tr>`);
		}
		return false;
	},
	delete_policy: (_this, e) => {
		e.preventDefault();
		$(_this).closest('tr.tr_csbh').remove();
	},
	open_copyfrom_building: function(_this, e){
		e.preventDefault();
		vietiso_loading(1);
		var project_id = $(_this).attr('building_id'),
			building_id = $(_this).attr('building_id');
		$.post(path_ajax_script+'/index.php?mod=project&act=open_copyfrom_building', {
			'project_id' : project_id,
			'building_id' : building_id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto','auto', html, 'open_copyfrom_building');
		});
		return false;
	},
	start_copyfrom_building: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			project_id = $(_this).attr('project_id'),
			building_id = $(_this).attr('building_id'),
			from_building_id = $('select[name=from_building_id]', _form).val();
		if($('select.required', _form).length){
			$('select.required', _form).each(function(){
				if($Core.util.isEmpty($(this).val())){
					_validated++;
					$(this).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod=project&act=start_copyfrom_building', {
				'project_id' : project_id,
				'building_id' : building_id,
				'from_building_id' : from_building_id
			}, function(respJson){
				vietiso_loading(0);
				console.log(respJson);
				$('.list_head_tab').html(respJson.html_tab);
				$('.lst_input_specical').html(respJson.html_input_specical);
				$('.list_content_tab').html(respJson.html_content_tab);
				$Core.popup.close($(_this).closest('.modal'));
			},'json');
		}
	},
	open_building : function(building_id, options){
		var $_adata = options || {};
		$_adata['building_id'] = building_id;
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=project&act=open_building', $_adata, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_building');
			$('#'+respJson.toId).on('hide.bs.dropdown', function (e) {
				if (e.clickEvent) { e.preventDefault(); }
			});
		},'json');	
		return false;
	},
	select_image: function(_this, e){
		e.preventDefault();
		var gId = $(_this).attr('gId'),
			toId = $(_this).attr('toId');
		$('#'+toId).attr('gId', gId).trigger('click');
		return false;
	},
	upload_image: function(_this, e){
		var gId = $(_this).attr('gId'),
			toId = $(_this).attr('toId'),
			_form = $(_this).closest('form');
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod=project&act=stock_upload_file',
			dataType:'html',
			success : function(html){
				vietiso_loading(0);
				_form.clearForm();
				_form.resetForm();
				$('#'+gId).val(html);
			}
		});	
	},
	open_interior_ns: function(_this, e){
		e.preventDefault();
		var code = $(_this).attr('code');
		$.post(path_ajax_script+'/index.php?mod=project&act=open_interior_ns', {
			'code' : code
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_interior_ns');
		});
		return false;
	},
	open_setup_floor: function(_this, e){
		e.preventDefault();
		var building_id = $(_this).attr('building_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=project&act=open_setup_floor', {
			'building_id' : building_id
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_setup_floor');
		}, 'json');
		return false;
	},
	save_setup_floor: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			building_id = $(_this).attr('building_id');
		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=project&act=save_setup_floor',
			data: {'building_id':building_id},
			success: function(html){
				vietiso_loading(0);
				$Core.popup.close(_form.closet('.modal'));
			}
		});
		return false;
	},
	addItemFloorSpecical : function (_this,e) {
		e.preventDefault();
		var _form = $(_this).closest("form");
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&act=addItemFloorSpecical',
			data: {},
			dataType:'html',
			success:function(html){
				vietiso_loading(0);
				console.log($(".lst_item",_form).length);
				$(".lst_item",_form).append(html);
			}
		});
	},
	deleteFloorSpecical : function (_this,e) {
		e.preventDefault();
		if(confirm("Bạn có chắc chắn muốn xóa cài đặt tầng này?")) {
			var _form=$(_this).closest("form"),
			toId = $(_this).attr("toId");
			$(_this).closest(".nav-item").remove();
			$("input.floor_specical."+toId,_form).remove();
			$("#tab_"+toId,_form).remove();
		}
	},
	gen_floor : function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			form = $(_this).closest("form"),
			type = $(_this).data("type"),
			building_id = $(_this).attr("building_id"),
			project_id = $(_this).attr("project_id"),
			number_house = $("input[name='number_house']",form).val(),
			template_active = $(".nav-item_tab_floor.active",form).attr("key");
		console.log(parseInt(number_house));
		if(number_house.trim() == "" || parseInt(number_house) == NaN || parseInt(number_house) == 0){
			$Core.alert.error('Số căn hộ/tầng phải lớn hơn [0]');
			return false;
		}	
		let txt_floor = "";
		if(type == "_ADD") {
			var title_pop = "Tạo danh sách tầng";
			var txt_value_floor = "";	
			$("input.floor_specical",form).each(function (index,elm){
				let floor_val = $(elm).val();
				if(floor_val.trim() != "") {
					txt_floor += ((index == 0) ? "" : ",") +  floor_val;	
				}
			});
		}else{
			var title_pop = "Sửa danh sách tầng";
			var txt_value_floor = $("."+toId).val();	
			$("input.floor_specical:not(."+toId+")",form).each(function (index,elm){
				let floor_val = $(elm).val();
				if(floor_val.trim() != "") {
					txt_floor += ((index == 0) ? "" : ",") +  floor_val;	
				}
			});
		}
		var arr_floor = (txt_floor != "") ? txt_floor.split(",") : [];
//		console.log(arr_floor);
		$Core.alert.confirm(title_pop,`
			<div class="form-row w-100">
				<label class="col-form-label">Tầng đặc biệt</label>
				<input class="form-control number_floor_`+toId+`" type="text" value="`+txt_value_floor+`">
			</div>
			<div class="help-block text-muted">Các tầng phải nhập cách nhau bởi dấu (,) hoặc các khoảng VD: 10-24,25,26</div>
		`, function(){	
			var str_floor_specical_new = $('.number_floor_'+toId).val(),
				number_house_specical = $('.number_house_'+toId).val();
			str_floor_specical_new = str_floor_specical_new.replace(/[^0-9,A,B,\-]/g, "");
			console.log(str_floor_specical_new);
			if(str_floor_specical_new.includes("-")) {
				var tmp = str_floor_specical_new.split(","),
					arrFloor = new Array();
				$.each(tmp,function(index,value){
					let range_floor = value.split("-");
					if(range_floor.length == 2) {
						let from = parseInt(range_floor[0]),
							to = parseInt(range_floor[1]);
						for(var i = from; i <= to; i++) {
							arrFloor.push(i);
						}
					}else{
						arrFloor.push(range_floor);
					}
				})
				floor_specical_new = arrFloor.join(",");
			}else{
				floor_specical_new = str_floor_specical_new;
			}
			if(floor_specical_new != ""){
				var arr_floor_specical = (floor_specical_new != "") ? floor_specical_new.split(",") : [];
				var floor = "";
				for(let i=0; i<arr_floor_specical.length; i++){
					if(arr_floor.includes($Core.util.parseNumber(arr_floor_specical[i]))){
						$Core.alert.error("Tầng số "+$Core.util.parseNumber(arr_floor_specical[i])+" đã tồn tại");
						return false;
					}					
				}
				if(type == "_ADD") {
					var $_adata = {'floor_specical_new':floor_specical_new,project_id:project_id,building_id:building_id,number_house:number_house,'template_active':template_active};
					$("#tab_"+template_active+" input,#tab_"+template_active+" select",form).each(function(index,elm){
						let name = $(elm).attr('name');
						$_adata[name] = $(elm).val();
					});
					$.ajax({
						type:'POST',
						url:path_ajax_script+'/index.php?mod='+mod+'&act=load_template_buildingV2',
						data : $_adata, 
						dataType:'json',
						success:function(respJson){
							vietiso_loading(0);
							$(".lst_input_specical",form).append(respJson.html_input_specical);
							$(".lst_input_number_house_specical",form).append(respJson.html_input_number_house_specical);
							$(".list_head_tab",form).append(respJson.html_tab_specical);
							$(".list_content_tab",form).append(respJson.html_content_specical);
						}
					});
				}else{
					let floorSpecical = "";
					$.each(arr_floor_specical,function(key,val){
						if(val.trim() != "") {
							floorSpecical += ((key == 0) ? "" : ",") +  val;	
						}
					});
					$('.'+toId).val(floorSpecical);
					$('.nav-item_tab_floor[key='+toId+'] .txt_nav').text(`Tầng `+floorSpecical);
				}
			} else {
				$Core.alert.error("Lỗi! Nhập vào tầng muốn tạo danh sách cách nhau bằng dấu phẩy. Số căn hộ lớn hơn 0");
				return false;
			}
		});
		return false;
	},
	pop_save_building : function(_this, e){
		e.preventDefault();
		var block_id = $(_this).attr('block_id'),
			project_id = $(_this).attr('project_id'),
			building_id = $(_this).attr('building_id'),
			stock_type = $(_this).attr('stock_type'),
			_openFrom = $(_this).getAttr('_openFrom', '_project'),
			_validated = 0, _form = $(_this).closest('form');
		if($('input.required,select.required,textarea.required', _form).length){
			$('input.required,select.required,textarea.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		var more = {'stock_type':stock_type};
		if($('.isoTextArea',_form).length){
			$('.isoTextArea',_form).each((_i, _elem) => {
				var name = $(_elem).data('name'),
					editorId = $(_elem).attr('id');
				more[name] = $Core.util.getTextAreaContent(editorId);
			});
		}
		if(_validated == 0){
			vietiso_loading(1);
			var form = $(_this).closest('form')[0];
			var data = formToObject(form);
			data.stock_type = stock_type;
			data.block_id = block_id;
			data.project_id = project_id;
			data.building_id = building_id;
			var formData = new FormData();
    		formData.append('data', JSON.stringify(data));
			$.ajax({
				url: path_ajax_script+'/index.php?mod=project&act=pop_save_buildingV3',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'json',
				success : function(respJson){
					vietiso_loading(0);
					if(respJson.msg.indexOf('_error') >= 0){
						$Core.alert.error("Error !");
					} if(respJson.msg.indexOf('_duplicate') >= 0){
						$Core.alert.error("Duplicated !");
					} else {
						$Core.alert.success("Success !");
						if(_openFrom=='_stock'){
							if($('input[name=is_locked]', _form).is(':checked')){
								$('.btn_reset_building_'+building_id).attr('disabled', true);
							} else {
								$('.btn_reset_building_'+building_id).removeAttr('disabled');
							}
							$.post(path_ajax_script+'/index.php?mod=ajax&act=load_select_property', {
								'for_id' : block_id,
								'property_id' : respJson.building_id,
								'property_type' : respJson.property_type
							}, function(html){
								var toId = $(_this).attr('toId');
								$('#'+toId).html(html);
							});
						} else {
							$Core.popup.close(_form.closest('.modal'));
							if(stock_type!=_BLOCK_TYPE_LOWFLOOR_SALE){
								if(parseInt(building_id) > 0){
									if($('input[name=is_locked]', _form).is(':checked')){
										$('.btn_delete_building_'+building_id).addClass('disabled');
									} else {
										$('.btn_delete_building_'+building_id).removeClass('disabled');
									}
								} else {
									$Core.project.open_building(respJson.building_id, {
										'project_id':project_id,
										'block_id':block_id,
										'_openFrom' : _openFrom
									});
								}
							}
							loadBlock(project_id, {});
						}
					}
				}
			});
		}
		return false;
	},
	shapes: [],
	save_stock_shapes: (_this, e) => {
		e.preventDefault();
		var holderG = $(_this).attr('holderG'),
			stock_type = $(_this).attr('stock_type'),
			project_id = $(_this).attr('project_id'),
			block_id = $(_this).attr('block_id'),
			$_adata = {
				'holderG':holderG, 
				'stock_type':stock_type, 
				'project_id':project_id,
				'block_id':block_id,
			};
		if(holderG == 'block' || holderG == 'stock' || holderG == 'stock_FH'){
			var building_id = $(_this).attr('building_id');
			$_adata['building_id'] = building_id;
		}
		// console.log($Core.project.shapes); 
		toggleIndicatior(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=save_stock_shapes', 
			data:$.extend($_adata, {'shapes': JSON.stringify($Core.project.shapes)}), 
			dataType:'html',
			success: function(html){
				toggleIndicatior(0);
				var block_id = $(`select[name="block_id"]`).val();
				$Core.project.listStock({"project_id":project_id,"block_id":block_id,"holderG":holderG});
			}
		});
		return false;
	},
	merge_stock_shapes: (_this, e) => {
		e.preventDefault();
		var holderG = $(_this).attr('holderG'),
			stock_type = $(_this).attr('stock_type'),
			project_id = $(_this).attr('project_id');
		$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn gộp lại không?", () => {
			toggleIndicatior(1);
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=merge_stock_shapes', 
				data: {'holderG':holderG, 'stock_type':stock_type, 'project_id':project_id}, 
				dataType:'html',
				success: function(html){
					toggleIndicatior(0);
					// window.location.reload();
				}
			});
		});
		return false;
	},
	save_stock_code_shapes: (_this, e) => {
		e.preventDefault();
		var holderG = $(_this).attr('holderG'),
			stock_type = $(_this).attr('stock_type'),
			project_id = $(_this).attr('project_id'),
			block_id = $(_this).attr('block_id'),
			building_id = $(_this).attr('building_id');
		// console.log($Core.project.shapes); 
		toggleIndicatior(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=save_stock_shapes', 
			data:{"holderG":holderG, "stock_type":stock_type, "project_id":project_id,
				'block_id':block_id, 'building_id' : building_id, 
				'shapes': JSON.stringify($Core.project.shapes)
			}, dataType:'html',
			success: function(html){
				toggleIndicatior(0);
				$Core.project.listStock({"project_id":project_id,"block_id":block_id,"holderG":holderG});
			}
		});
		return false;
	},
	do_search:  $Core.util.delay((_this, e) => {
		var uid = $(_this).attr('uid'),
			shape_id = $(_this).attr('shape_id'),
			holderG = $(_this).attr('holderG'),
			stock_type = $(_this).attr('stock_type'),
			project_id = $(_this).attr('project_id'),
			keysearch = $(_this).val();
		var $_adata = {
			'uid' : uid,
			'stock_type' : stock_type,
			'project_id' : project_id,
			'keysearch' : $.trim(keysearch)
		};
		$_adata['holderG'] = holderG;
		if(holderG == 'stock'){
			var block_id = $(_this).attr('block_id'), 
				building_id = $(_this).attr('building_id');
			$_adata['block_id'] = block_id;
			$_adata['building_id'] = building_id;
		}
		if(!$Core.util.isEmpty(keysearch)){
			var _wraper = $(_this).parent('.input-loading');
			_wraper.addClass('in');
			$.post(`${path_ajax_script}/index.php?mod=${mod}&act=search_stock`, $_adata, (respJson) => {
				_wraper.removeClass('in');
				$(`.autosugget[id=${uid}]`).html(respJson.html).removeClass('d-none');
			}, 'json');
		} else {
			$(`.autosugget[id=${uid}]`).addClass('d-none');
		}
	}, 500),
	select_stock: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			stock_id = $(_this).attr('stock_id'),
			stock_code = $(_this).text();
		$(`input[name=stock_id][uid=${uid}]`).val(stock_id);
		$(`input[name=stock_code][uid=${uid}]`).val(stock_code);
		$(`.autosugget[id=${uid}]`).addClass('d-none');
		// Tự động nhấn lưu
		$('.js__btn_add_stock').trigger('click');
		return false;
	},
	loadModalStock : function (_this, e){
		e.preventDefault();
		var _form = $(_this).closest("form"),
			project_id = $("select[name=project_id]",_form).val(),
			block_id = $("select[name=block_id]",_form).val(),
			holderG = $(_this).attr("holderG"),
			$_adata = {"project_id":project_id,"block_id":block_id,"holderG":holderG};
		$.post(`${path_ajax_script}/index.php?mod=${mod}&act=loadModalStock`, $_adata, function(respJson){
			var __w = $(window).width();
			// $Core.popup.openfull(__w*6/7,respJson.html,respJson.uid);
			$Core.popup.open('auto','0', respJson.html, respJson.uid,"right right-0");
			$("#"+respJson.uid).draggable({ handle: ".modal-header" });
			$Core.project.listStock($_adata);
		}, 'json');		
		return false;
	},
	listStock : function (options){
		var $_adata = options || {};
		$.post(`${path_ajax_script}/index.php?mod=${mod}&act=listStock`, $_adata, function(respJson){
			$(".total_stock").text(respJson.total);
			$(".list_stock_modal").html(respJson.html);
		}, 'json');		
		return false;
	},
	open_map_config: (_this, e) => {
		e.preventDefault();
		var stock_type = $(_this).attr('stock_type'),
			project_id = $(_this).attr('project_id'),
			block_id = $(_this).attr('block_id'),
			building_id = $(_this).attr('building_id'),
			stock_shape_id = $(_this).attr('stock_shape_id'),
			type = $(_this).attr('_type');
		toggleIndicatior(1);
		$.post(`${path_ajax_script}/index.php?mod=${mod}&act=open_map_config`, {
			'stock_type' : stock_type,
			'project_id' : project_id,
			'block_id' : block_id,
			'building_id' : building_id,
			'stock_shape_id' : stock_shape_id,
			'type' : type
		}, function(respJson){
			toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, 'open_config');
			$('#'+'open_config').css('z-index',10000);
		}, 'json');	
		return false;
	},
	map_save_config: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			stock_shape_id = $(_this).attr('stock_shape_id');
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each(function(){
				if($Core.util.isEmpty($(this).val())){
					_validated++;
					$(this).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=map_save_config', 
				data:{"stock_shape_id":stock_shape_id,}, 
				dataType:'html',
				success: function(html){
					vietiso_loading(0);
					window.location.reload();
				}
			});
		}
		return false;
	},
	open_setup_tooltip: (_this, e) => {
		e.preventDefault();
		var stock_type = $(_this).attr('stock_type'),
			project_id = $(_this).attr('project_id'),
			block_id = $(_this).attr('block_id'),
			building_id = $(_this).attr('building_id'),
			stock_shape_id = $(_this).attr('stock_shape_id'),
			type = $(_this).attr('_type');
		toggleIndicatior(1);
		$.post(`${path_ajax_script}/index.php?mod=${mod}&act=open_setup_tooltip`, {
			'stock_type' : stock_type,
			'project_id' : project_id,
			'block_id' : block_id,
			'building_id' : building_id,
			'stock_shape_id' : stock_shape_id,
			'type' : type,
		}, function(respJson){
			toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, 'open_setup_tooltip');
			if(respJson.callback) eval(respJson.callback);
			$('#'+'open_setup_tooltip').css('z-index',10000);
		}, 'json');
		return false;
	},
	updateOrder: function(_this,e){
		e.preventDefault();
		var block_id = $(_this).attr("block_id"),
			project_id = $(_this).attr("project_id"),
			order_no = $(_this).val();
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=updateOrder', 
			data:{'block_id':block_id,'order_no':order_no}, 
			dataType:'json',
			success: function(respJson){
				vietiso_loading(0);
				if(respJson.result) {
					console.log("ssss");
					loadBlock(project_id, {});
				}
			}
		});
	},
    open_setting_table_update: function(_this, e) {
        toggleIndicatior(1);
        let project_id = $(_this).data('project-id');
        let $_adata = {};
        $_adata["project_id"] = project_id;
        $.ajax({
            url: path_ajax_script+'/index.php?mod=ajax&act=open_setting_update_table',
            method: 'POST',
            data: $_adata,
            dataType: 'json',
            success: function(respJson) {
                toggleIndicatior(0);
                makepopup('auto','auto', respJson.html, 'open_setting_table_update');
                if(respJson.callback) eval(respJson.callback);
            },
            error: function(xhr, status, error) {
				console.log('Có lỗi xảy ra!!!! => ', error);
			},
        })
    },
    import_data_from_link_doc_gg: function(_this, e) {
        e.preventDefault();
        var _validated = 0,
			_form = $(_this).closest('form'),
			action = $(_this).attr("action");		
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each(function(){
				if($Core.util.isEmpty($(this).val())){
					_validated++;
					alertify.error($(this).data("label") + " không được để trống");
					$(this).focus();
					return false;
				}
			});
		}
		if(_validated > 0){
			return false;
		}
		// Tạo bảng hàng phải xem trước dữ liệu trước đã, popup xem trước chính là bước xác nhận
		if(action == "_CREATE"){
			$Core.project.preview_low_floor(_form);
			return false;
		}
		$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn thực hiện hành động này?", function(){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod=ajax&act=crawl_doc_sheet_by_link',
				data:{"action":action},
				dataType:'json',
				success: function(respJson) {
					vietiso_loading(0);
					let status = respJson.status;
					status = typeof status == 'undefined' ? 500 : status;
					let msg = respJson.msg;
					msg = typeof msg == 'undefined' ? 'Có lỗi xảy ra!!' : msg;
					if (status == 200 ) {
						$Core.alert.success(msg);
						$Core.popup.close(_form.closest(".modal"));
					} else {
						$Core.alert.error(msg);
					}
				}
			});
		});
    },
	// Dựng trước danh sách căn từ sheet và hiện popup để user soát, chưa ghi gì vào DB
	preview_low_floor: function(_form) {
		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=ajax&act=crawl_doc_sheet_by_link',
			data:{"action":"_PREVIEW"},
			dataType:'json',
			success: function(respJson) {
				vietiso_loading(0);
				let status = respJson.status;
				status = typeof status == 'undefined' ? 500 : status;
				let msg = respJson.msg;
				msg = typeof msg == 'undefined' ? 'Có lỗi xảy ra!!' : msg;
				if (status != 200) {
					$Core.alert.error(msg);
					return false;
				}
				$Core.popup.close($('#preview_low_floor'));
				$Core.popup.open('auto','auto', respJson.html, 'preview_low_floor');
				// Popup cấu hình vẫn mở bên dưới nên phải đẩy popup xem trước lên trên
				$('#preview_low_floor').css('z-index', 10000);
				$Core.project.init_preview_low_floor();
			}
		});
	},
	preview_page_size: 100,
	_preview: null,
	// Bảng xem trước render sẵn toàn bộ dòng, phân trang bằng ẩn/hiện tại chỗ để khỏi gọi lại server
	init_preview_low_floor: function() {
		var _modal = $('#preview_low_floor');
		if(!_modal.length){
			return false;
		}
		var _rows = _modal.find('.js__preview_low_floor tbody tr'),
			_texts = [],
			_errors = [];
		// Cache sẵn text + cờ lỗi từng dòng, nếu không mỗi lần gõ lọc phải đọc DOM cả nghìn dòng
		_rows.each(function(){
			_texts.push($(this).text().toLowerCase());
			_errors.push($(this).hasClass('js__row_error'));
		});
		$Core.project._preview = {
			modal: _modal,
			rows: _rows,
			texts: _texts,
			errors: _errors,
			matched: _rows,
			shown: $(),
			page: 1
		};
		_rows.hide();
		$Core.project.goto_preview_page(1);
	},
	goto_preview_page: function(page) {
		var _st = $Core.project._preview;
		if(!_st){
			return false;
		}
		var size = $Core.project.preview_page_size,
			total = _st.matched.length,
			pages = Math.max(1, Math.ceil(total / size));
		page = Math.min(Math.max(1, page), pages);
		// Chỉ đụng vào trang cũ + trang mới, không duyệt lại cả nghìn dòng mỗi lần chuyển trang
		_st.shown.hide();
		_st.shown = _st.matched.slice((page - 1) * size, page * size);
		_st.shown.show();
		_st.page = page;
		var from = total > 0 ? (page - 1) * size + 1 : 0,
			to = Math.min(page * size, total);
		_st.modal.find('.js__preview_pager').html($Core.project.build_preview_pager(page, pages));
		_st.modal.find('.js__preview_range').text('Hiển thị ' + from + ' - ' + to + ' / ' + total + ' căn');
		_st.modal.find('.preview-lowfloor-scroll').scrollTop(0);
	},
	build_preview_pager: function(page, pages) {
		if(pages <= 1){
			return '';
		}
		var html = $Core.project.preview_pager_item(page - 1, '&laquo;', page <= 1, false),
			end = Math.min(pages, page + 2),
			start = Math.max(1, end - 4);
		end = Math.min(pages, start + 4);
		if(start > 1){
			html += $Core.project.preview_pager_item(1, 1, false, false);
			if(start > 2){
				html += $Core.project.preview_pager_item(0, '...', true, false);
			}
		}
		for(var i = start; i <= end; i++){
			html += $Core.project.preview_pager_item(i, i, false, i == page);
		}
		if(end < pages){
			if(end < pages - 1){
				html += $Core.project.preview_pager_item(0, '...', true, false);
			}
			html += $Core.project.preview_pager_item(pages, pages, false, false);
		}
		html += $Core.project.preview_pager_item(page + 1, '&raquo;', page >= pages, false);
		return html;
	},
	preview_pager_item: function(page, label, disabled, active) {
		if(active){
			return '<li><a class="paginate_active" href="javascript:void(0);">' + label + '</a></li>';
		}
		if(disabled){
			return '<li><a class="paginate_button disabled" href="javascript:void(0);">' + label + '</a></li>';
		}
		return '<li><a class="paginate_button" href="javascript:void(0);" onclick="$Core.project.goto_preview_page(' + page + ')">' + label + '</a></li>';
	},
	// Xác nhận từ popup xem trước: gửi lại đúng form cấu hình với _CREATE để ghi thật
	create_low_floor: function(_this, e) {
		e.preventDefault();
		var _preview = $(_this).closest('.modal'),
			_form = $('#open_setting_table_update').find('form').first();
		if(!_form.length){
			$Core.alert.error("Không tìm thấy form cấu hình, vui lòng mở lại và thử lại");
			return false;
		}
		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod=ajax&act=crawl_doc_sheet_by_link',
			data:{"action":"_CREATE"},
			dataType:'json',
			success: function(respJson) {
				vietiso_loading(0);
				let status = respJson.status;
				status = typeof status == 'undefined' ? 500 : status;
				let msg = respJson.msg;
				msg = typeof msg == 'undefined' ? 'Có lỗi xảy ra!!' : msg;
				if (status == 200 ) {
					$Core.alert.success(msg);
					$Core.popup.close(_preview);
					$Core.popup.close(_form.closest(".modal"));
				} else {
					$Core.alert.error(msg);
				}
			}
		});
		return false;
	},
	// Lọc nhanh trên bảng xem trước, danh sách dài nên soát bằng mắt không xuể
	filter_preview_low_floor: function(_this, e) {
		var _st = $Core.project._preview;
		if(!_st){
			return false;
		}
		var keyword = $.trim(_st.modal.find('.js__filter_preview').val()).toLowerCase(),
			only_error = _st.modal.find('.js__filter_error').is(':checked');
		_st.matched = _st.rows.filter(function(index){
			if(only_error && !_st.errors[index]){
				return false;
			}
			return keyword === '' || _st.texts[index].indexOf(keyword) !== -1;
		});
		// Ẩn trang đang hiện trước khi đổi tập kết quả, không thì dòng cũ còn sót lại trên bảng
		_st.shown.hide();
		_st.shown = $();
		$Core.project.goto_preview_page(1);
	},
	open_sheet: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			gId = $(_this).attr('gId'),
			stock_type = $(_this).attr('stock_type'),
			spreadsheetId = $(`.spreadsheetId_${gId}`).val();
		if($Core.util.isEmpty(spreadsheetId)){
			$Core.alert.error("Bạn chưa nhập vào Google Sheet ID");
		} else {
			toggleIndicatior(1);
			$.post(path_ajax_script+'/index.php?mod=ajax&act=open_sheet', {
				'uid' : uid,
				'gId' : gId,
				'stock_type' : stock_type,
				'spreadsheetId' : spreadsheetId
			}, function(respJson){
				toggleIndicatior(0);
				$Core.popup.open('auto','auto', respJson.html, 'open_sheet');
				if(respJson.callback) 
					eval(respJson.callback);
			}, 'json');
		}
		return false;
	},
	update_sheet: (_this, e) => {
		e.preventDefault();
		var gId = $(_this).attr('gId'),
			stock_type = $(_this).attr('stock_type'),
			spreadsheetId = $(_this).attr('spreadsheetId');
		if($(`.${spreadsheetId}:checked`).length){
			var sheets = $Core.util.getCheckBoxValueByClass(spreadsheetId),
				sheet_ids = $Core.util.getCheckBoxAttrByClass(spreadsheetId, 'sheet_id');
			$(`input[gId=${gId}][type=text]`).val(sheets.join('|'));
			$(`input[gId=${gId}][type=hidden]`).val(sheet_ids.join('|'));
			$Core.popup.close($(_this).closest('.modal'));
			if(stock_type == 177) {
			}
		} else {
			$Core.alert.error("Bạn phải chọn sheet cấu hình cập nhật");
		}
		return false;
	},
    configConvert: function(_this, e) {
        e.preventDefault();
        let type = $(_this).data('type');
        let itemConfig = $(_this).parent('.convert_item');
        if (type == 'add') {
            let html = itemConfig.clone();
            html.addClass('mt-2')
            itemConfig.after(html);
            $('.btn-delete-convert').each(function() {
                if ($(this).hasClass('d-none')) {
                    $(this).removeClass('d-none');
                }
            })
        } else if (type == 'delete') {
            itemConfig.remove();
            if ($('.convert_item').length <= 1) {
                $('.btn-delete-convert').each(function() {
                    if (!$(this).hasClass('d-none')) {
                        $(this).addClass('d-none');
                    }
                })
            }
        }
    },
	change_booking: function(_this,e) {
		e.preventDefault();
		var toId = $(_this).attr("toId");
		if($(_this).is(":checked")) {
			$("#"+toId).removeClass("d-none");
		}else{
			$("#"+toId).addClass("d-none");
		}
	},
	open_config_column: function(_this, e){
		e.preventDefault();
		vietiso_loading(1);
		var project_id = $(_this).attr('project_id'),
			block_type = $(_this).attr('block_type');
		$.post(path_ajax_script+'/index.php?mod=project&act=open_config_column', {
			'project_id' : project_id,
			'block_type' : block_type
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			if($('.md_sortable',$('#'+respJson.uid)).length){
				$('.md_sortable',$('#'+respJson.uid)).sortable({
					connectWith: ".connectedSortable",
					handle: ".mySortableHandler",
					beforeStop: function(event, ui) {
						var a = ui.item.attr('id'),
							b = $(ui.placeholder).parent('tbody');
						if(b.hasClass('is_group')){
							var _group_id = b.attr('id');
							ui.item.addClass(_group_id).addClass('is_group').removeClass('no_group').attr('group_id', _group_id);
							$('.title_field_'+a).attr('name','groups['+_group_id+']['+a+'][title]');
							$('.link_field_'+a).attr('name','groups['+_group_id+']['+a+'][link]');
						} else {
							var _group_id = ui.item.attr('group_id');
							ui.item.removeClass(_group_id).removeClass('is_group').addClass('no_group').removeAttr('group_id');
							$('.title_field_'+a).attr('name','properties['+a+'][title]');
							$('.link_field_'+a).attr('name','properties['+a+'][link]');
						}
					}
				}).disableSelection();;
			}
		},"json");
		return false;
	},
	add_field: (_this, e) =>{
		e.preventDefault();
		var _form = $(_this).closest('form'),
			uid = $Core.util.getUniqid(),
			block_type = $("input[name='block_type']",_form).val();
		var html = $(".tr_attrs:last-child",_form).clone();
		$(".md_sortable",_form).append(`<tr id="${uid}" class="tr_attrs">
			<td class="text-center">
				<div class="mySortableHandler"><i class="fa fa-arrows"></i></div>
			</td>
			<td class="text-left">
				`+((block_type == _BLOCK_TYPE_HIGHLEVEL_SALE ) ? html_select_field_highfloor : html_select_field_lowfloor)+`
			</td>
			<td class="text-center">
				<a title="Xóa" href="javascript:void(0);" class="btn btn-default" uid="${uid}" onClick="$Core.project.delete_field(this,event)"><i class="fa fa-trash"></i></a>
			</td>
		</tr>`);	
		if($('.md_sortable',_form).length){
			$('.md_sortable',_form).sortable({
				connectWith: ".connectedSortable",
				handle: ".mySortableHandler",
				beforeStop: function(event, ui) {
					var a = ui.item.attr('id'),
						b = $(ui.placeholder).parent('tbody');
					if(b.hasClass('is_group')){
						var _group_id = b.attr('id');
						ui.item.addClass(_group_id).addClass('is_group').removeClass('no_group').attr('group_id', _group_id);
						$('.title_field_'+a).attr('name','groups['+_group_id+']['+a+'][title]');
						$('.link_field_'+a).attr('name','groups['+_group_id+']['+a+'][link]');
					} else {
						var _group_id = ui.item.attr('group_id');
						ui.item.removeClass(_group_id).removeClass('is_group').addClass('no_group').removeAttr('group_id');
						$('.title_field_'+a).attr('name','properties['+a+'][title]');
						$('.link_field_'+a).attr('name','properties['+a+'][link]');
					}
				}
			}).disableSelection();;
		}
		
		return false;
	},
	delete_field: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest("form");
		$(_this).closest(".tr_attrs").remove();
		return false;
	},
	save_config : (_this,e) => {
		e.preventDefault();
		var _validated = 0, _form = $(_this).closest('form');
		if($('select.required', _form).length){
			$('select.required', _form).each(function(){
				if($Core.util.isEmpty($(this).val())){
					_validated++;
					$(this).focus();
					alertify.error("Không được để trống!");
					return false;
				}
			});
		}
		if(_validated == 0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=save_config_column', 
				data:{}, 
				dataType:'json',
				success: function(respJson){
					vietiso_loading(0);
					if(respJson.result) {
						alertify.success("Thành công!");
					}else{
						alertify.error("Lỗi!");
					}
					$Core.popup.close($_this.closest(".modal"));
				}
			});
		}
		return false;
	},
};
$Core.utilities = {
	open : function (_this,e){
		e.preventDefault();
		var $_this = $(_this),
			project_id = $_this.attr('project_id'),
			utilities_id = $_this.attr('utilities_id');
		toggleIndicatior(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajOpenUtilities', 
			data:{"project_id":project_id,'utilities_id':utilities_id}, 
			dataType:'html',
			success: function(html){
				toggleIndicatior(0);
				$Core.popup.open('auto','auto', html, 'Utility_'+project_id);
			}
		});
		return false;
	},
	save : function (_this,e){
		e.preventDefault();
		var $_this = $(_this),
			project_id = $_this.attr('project_id'),
			utilities_id = $_this.attr('utilities_id');
		var _validated = 0, _form = $_this.closest('form');
		if($('input.required', _form).length){
			$('input.required', _form).each(function(){
				if($Core.util.isEmpty($(this).val())){
					_validated++;
					$(this).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=saveUtilities', 
				data:{"project_id":project_id,'utilities_id':utilities_id}, 
				dataType:'html',
				success: function(html){
					vietiso_loading(0);
					$Core.utilities.loadList(project_id, {});
					$Core.popup.close($_this.closest(".modal"));
				}
			});
		}
		return false;
	},
	delete : function (_this,e){
		e.preventDefault();
		var $_this = $(_this),
			project_id = $_this.attr("project_id"),
			utilities_id = $_this.attr("utilities_id");
		$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn xóa?", function(){
			vietiso_loading(1);
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=saveUtilities&action=delete', 
				data:{"project_id":project_id,"utilities_id":utilities_id},
				dataType:'html',
				success: function(html){
					vietiso_loading(0);
					$Core.utilities.loadList(project_id, {});
				}
			});
		});
		return false;	
	},
	filter : function (_this,e){
		e.preventDefault();
		var $_this = $(_this);
		$Core.utilities.loadList($_this.attr('project_id'), {'block_id':$_this.val()});
		return false;
	},
	loadList : function (project_id, options){
		var $_adata = options || {};
		$_adata['project_id'] = project_id;
		if(typeof $_adata['block_id'] == 'undefined'){
			$_adata['block_id'] = $('.filterUtilitiesBlock').val() || 0;
		}
		var $_box = $('.holderUtilities .ui-resize-y'),
			_height = $_box.length ? $_box[0].style.height : '';
		toggleIndicatior(1);
		$.post(path_ajax_script+"/?mod="+mod+"&act=ajLoadListUtilities", $_adata, function(respJson){
			toggleIndicatior(0);
			$('.holderUtilities').html(respJson.html);
			if(_height){
				$('.holderUtilities .ui-resize-y').css({'height':_height, 'max-height':'none'});
			}
			$('.'+respJson.uid).freezeTable({
				'columnNum': 1,
				'scrollable': true,
				'columnKeep': false,
			});
		}, 'json')
	}
}
$Core.project.map_FH = {
	addPoint: function (_this,e){
		e.preventDefault();
		var project_id = $(_this).attr("project_id"),
			block_id = $(_this).attr("block_id"),
			building_id = $(_this).attr("building_id"),
			type = $(_this).attr("_type"),
			shape_id = $(_this).attr("shape_id"),
			$_adata = {"project_id":project_id,"block_id":block_id,"building_id":building_id,"type":type,"shape_id":shape_id};
		vietiso_loading(1);
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&sub=map&act=open_map',
			data: $_adata,
			dataType:'json',
			success:function(respJson){
				vietiso_loading(0);
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			}
		});
		return false;
	},
	save_map: function (_this,e){
		e.preventDefault();
		var project_id = $(_this).attr("project_id"),
			block_id = $(_this).attr("block_id"),
			building_id = $(_this).attr("building_id"),
			type = $(_this).attr("_type"),
			action = $(_this).attr("action"),
			shape_id = $(_this).attr("shape_id"),
			$_adata = {"project_id":project_id,"block_id":block_id,"building_id":building_id,"action":action,"shape_id":shape_id},
			_form = $(_this).closest("form"),
			_modal = $(_this).closest(".modal");
//		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&sub=map&act=save_map',
			data: $_adata,
			dataType: 'json',
			success: function(respJson){
				vietiso_loading(0);
				if(action == "add") {					  
					// Lấy kích thước container
				  var containerWidth = $("#map").width();
				  var containerHeight = $("#map").height();
					if($("#item_drag_"+respJson.shap_id).length == 0) {
						$("#map").append(respJson.html);
					}
					$("#item_drag_"+respJson.shap_id).draggable({
					  // Giới hạn kéo thả bên trong container
					  containment: "#map",
					  drag: function(event, ui) {	
						  // Tính toán tọa độ theo phần trăm dựa trên vị trí hiện tại
						  var percentX = (ui.position.left / containerWidth) * 100;
						  var percentY = (ui.position.top  / containerHeight) * 100;
							$("#item_drag_"+respJson.shap_id).css({"left":percentX,"top":percentY});
						  $("#item_drag_"+respJson.shap_id).attr("left",percentX);
						  $("#item_drag_"+respJson.shap_id).attr("top",percentY);
					  },
						stop: function(event, ui) {
						  // Cập nhật hiển thị tọa độ với 2 chữ số thập phân
						  $Core.project.map_FH.update_shape();
						}
					});
				}else{
				}
				$(".close_pop",_modal).trigger("click");
			}
		});
		return false;
	},
	update_shape : function() {
		var map = $("#map"),
			shapes = {};
		$(".item_drag",map).each(function(index,elm){
			var shap_id = $(elm).attr("shap_id"),
				project_id = $(elm).attr("project_id"),
				block_id = $(elm).attr("block_id"),
				building_id = $(elm).attr("building_id"),
				left = $(elm).attr("left"),
				top = $(elm).attr("top"),
				code = $(elm).attr("code");
			shapes[shap_id] = {
				"code" : code,
				"top" : top,
				"left" : left,
			}
		});
		console.log(shapes);
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&sub=map&act=update_shape',
			data: {"project_id":project_id,"block_id":block_id,"building_id":building_id,"shapes":shapes},
			dataType:'json',
			success:function(respJson){
				console.log(1);				
			}
		});
	}
}
// Xoá vĩnh viễn dự án: xoá cả tài liệu, quỹ căn (stock/stock_meta), phân khu/tòa, chính sách bán hàng — không khôi phục được.
$(document).on('click', '.js_delete_project', function(e){
	e.preventDefault();
	var href = $(this).attr('href');
	var title = String($(this).attr('data-title') || '').replace(/</g, '&lt;');
	var name = title ? ' "' + title + '"' : '';
	if(!href){
		return false;
	}
	$Core.alert.confirm('Xóa vĩnh viễn dự án', 'Toàn bộ dữ liệu của dự án' + name + ' gồm: tài liệu, quỹ căn, phân khu/tòa và chính sách bán hàng sẽ bị XÓA VĨNH VIỄN, không thể khôi phục. Bạn chắc chắn muốn xóa?', function(){
		vietiso_loading(1);
		window.location.href = href;
	});
	return false;
});
