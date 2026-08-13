$(function(){
	if(act=='default'){
//		$Core.docs.load_docs({});
	}
	$('.select_file').change(function(){
		var _this = $(this),
			_form = _this.closest('form');
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=do_import_file',
			dataType:'html',
			success : function(html){
				vietiso_loading(0);
				_form.clearForm();
				_form.resetForm();
				if(html.indexOf('_success') >= 0){
					window.location.reload();
				} else {
					$Core.alert.error('Không thành công !');
				}
			}
		});	
	});
});
$Core.docs = {
	toggle_advanced: function(_this, e){
		if(e) e.preventDefault();
		var $adv = $('.docs-advanced'); $(_this).toggleClass('open');
		$adv.slideToggle(150, function(){
			if($adv.is(':visible') && !$adv.data('init')){
				$adv.data('init', 1);
				$('.docs-advanced textarea.isoTextArea').each(function(){
					var $ta = $(this), id = $ta.attr('id');
					if(typeof tinyMCE === 'undefined' || !tinyMCE.get(id)){ try { $ta.isoTextArea(); } catch(err){} }
				});
			}
		});
		return false;
	},
	cleanup: function(_this, e){
		if(e) e.preventDefault();
		if(!confirm('Làm sạch toàn bộ dữ liệu tài liệu (re-crawl Google Drive + cập nhật tìm kiếm)? Có thể mất vài phút, đừng đóng trang.')) return false;
		var $p = $('.cleanup_progress'), total = 0;
		function step(last_id){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=db_cleanup_run', {'last_id': last_id}, function(r){
				total += (r.processed||0);
				$p.text('Đã xử lý '+total+' — còn '+(r.remaining||0)+'...').show();
				if((r.processed||0) > 0 && (r.remaining||0) > 0){ step(r.last_id); }
				else { $p.text('✓ Xong! Đã làm sạch '+total+' tài liệu. Đang tải lại...'); setTimeout(function(){ window.location.reload(); }, 1500); }
			}, 'json').fail(function(){ $p.text('Lỗi — dừng tại '+total+'. Bấm lại để tiếp tục.'); });
		}
		$p.text('Bắt đầu...').show();
		step(0);
		return false;
	},
	load_docs: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i,_elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_docs', $_adata, function(respJson){
			$('.holder_docs').html(respJson.html);
		}, 'json');
	},
	open: function(_this, e){
		e.preventDefault();
		var project_meta_id = $(_this).attr('project_meta_id'),
			project_id = $(_this).attr('project_id'),
			block_id = $(_this).attr('block_id'),
			building_id = $(_this).attr('building_id'),
			cat_id = $(_this).attr('cat_id'),
			$_adata = {'project_meta_id':project_meta_id,'project_id': project_id,'block_id':block_id,'building_id':building_id,'cat_id':cat_id};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open', $_adata, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto','auto', respJson.html, 'open_docs');
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
							url:path_ajax_script+'/index.php?mod='+mod+'&act=search_tag',
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
		}, 'json');
		return false;
	},
	create_folder: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			toId = $(_this).attr('toId'),
			folder_name = $('input[name=title]', _form).val();
		if($Core.util.isEmpty(folder_name)){
			$('input[name=title]', _form).focus();
			return false;
		} else {
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=create_folder', {
				'folder_name' : folder_name
			}, function(respJson){
				console.log(toId,respJson.folder_id);
				if(respJson.msg.indexOf('_success') >= 0){
					$('input[name=content]').val(respJson.folder_link);
					$(`button[toId=${toId}]`).attr('folder_id', respJson.folder_id);
					$('.js__docs_folder').replaceWith(`<input type="hidden" name="folder_id" value="${respJson.folder_id}" />
					<a href="javascript:void(0);" toId="${toId}" class="js__docs_folder" onClick="$Core.docs.delete_folder(this, event)" title="Xóa folder">Xóa folder</a>`);
				} else {
					$Core.alert.error('Không thành công !');
				}
			}, 'json');
		}
		return false;
	},
	delete_folder: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			_form = $(_this).closest('form');
		$('#'+toId).removeAttr('folder_id');
		$('input[name=content]', _form).val('');
		$('input[name=folder_id]', _form).remove();
		$(`button[toId=${toId}]`).removeAttr('folder_id');
		$('.js__docs_folder').html(`<a href="javascript:void(0);" class="js__docs_folder" onClick="$Core.docs.create_folder(this, event)" title="Thêm folder" toId="`+toId+`">Thêm folder</a>`);
		return false;
	},
	select_block: function(_this, e){
		var toId = $(_this).attr('toId'),
			$block = $('#'+toId),
			buildingId = $block.attr('toId'); // toà phụ thuộc phân khu -> nạp lại theo phân khu còn lại
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_block', {
			'project_ids' : $(_this).val(),
			'selected_ids' : $block.val(),
			'is_multiple' : $block.prop('multiple') ? 1 : 0
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$block.html(html).trigger("chosen:updated");
			if(buildingId){
				$Core.docs.load_building($block, $('#'+buildingId));
			}
		});
	},
	select_building: function(_this, e){
		var toId = $(_this).attr('toId');
		$Core.docs.load_building($(_this), $('#'+toId));
	},
	load_building: function($block, $building){
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_building', {
			'list_block_ids' : $block.val(),
			'selected_ids' : $building.val(),
			'is_multiple' : $building.prop('multiple') ? 1 : 0
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$building.html(html).trigger("chosen:updated");
		});
	},
	do_search: function(_this, e){
		e.preventDefault();
		$Core.docs.load_docs();
		return false;
	},
	select_file: function(_this, e){
		e.preventDefault();
		var _modal = $(_this).closest(".modal"),
			toId = $(_this).attr('toId'),
			folder_id = $(_this).attr('folder_id');
		console.log(folder_id);
		$('.select_file_'+toId,_modal).attr('folder_id', folder_id).trigger('click');
		return false;
	},
	upload_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('id'),
			folder_id = $(_this).attr('folder_id'),
			form = $(_this).closest('form');
		vietiso_loading(1);
		form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=upload_file',
			data : {'folder_id': folder_id},
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				form.clearForm();
				form.resetForm();
				if(respJson.msg.indexOf('_success') >= 0){
					if($Core.util.isEmpty(folder_id)){
						$('#content_file_'+toId).val(respJson.upload_file);
					} else {
						$Core.alert.success('Đã thêm file thành công !');
					}
				} else {
					$Core.alert.error('Không thành công !');
				}
			}
		});	
		return false;
	},
	select_image: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('.select_image_'+toId).trigger('click');
		return false;
	},
	upload_image: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('id'),
			form = $(_this).closest('form');
		vietiso_loading(1);
		form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=upload_image',
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				form.clearForm();
				form.resetForm();
				if(respJson.msg.indexOf('_success') >= 0){
					$('#image'+toId).val(respJson.upload_image);
				} else {
					$Core.alert.error('Không thành công !');
				}
			}
		});	
		return false;
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			project_meta_id = $(_this).attr('project_meta_id'),
			$_adata = {'project_meta_id':project_meta_id};
		
		if($('input.required,select.required').length){
			$('input.required,select.required').each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if($('.isoTextArea', _form).length){
			$('.isoTextArea', _form).each((_i, _elem) => {
				var name = $(_elem).data('name'),
					editorId = $(_elem).attr('id');
				$_adata[name] = (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorId)) ? tinyMCE.get(editorId).getContent() : $(_elem).val();
			});
		}
		if(_validated==0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=save',
				data : $_adata,
				dataType:'html',
				success : function(html){
					vietiso_loading(0);
					if(html.indexOf('_success')>= 0){
						if($(_this).hasClass('continue_add')){
							$Core.popup.close(_form.closest('.modal'));
							$Core.docs.load_docs({});
							$('.js_create_add').trigger('click');
						} else {
							window.location.reload();
						}
					} else {
						$Core.alert.error('Lỗi thêm/cập nhật dữ liệu');
					}
				}
			});	
		}
		return false;
	},
	// Tài liệu thuộc nhiều dự án bị server chặn xoá -> hiện lý do, không reload
	after_delete: function(respJson){
		if(respJson.msg.indexOf('_success') >= 0){
			window.location.reload();
		} else {
			$Core.alert.error(respJson.message || 'Không thành công !');
		}
	},
	delete: function(_this,e){
		e.preventDefault();
		var project_meta_id = $(_this).attr('project_meta_id');
		$Core.alert.confirm('Xác nhận', "Chuyển tài liệu này vào Thùng rác?", function(){
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete', {
				'project_meta_id' : project_meta_id
			}, function(respJson){
				vietiso_loading(0);
				$Core.docs.after_delete(respJson);
			}, 'json');
		});
		return false;
	},
	restore: function(_this,e){
		e.preventDefault();
		var project_meta_id = $(_this).attr('project_meta_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=restore', {
			'project_meta_id' : project_meta_id
		}, function(html){
			vietiso_loading(0);
			window.location.reload();
		});
		return false;
	},
	force_delete: function(_this,e){
		e.preventDefault();
		var project_meta_id = $(_this).attr('project_meta_id');
		$Core.alert.confirm('Xác nhận', "Xoá VĨNH VIỄN tài liệu này? Không thể khôi phục.", function(){
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=force_delete', {
				'project_meta_id' : project_meta_id
			}, function(respJson){
				vietiso_loading(0);
				$Core.docs.after_delete(respJson);
			}, 'json');
		});
		return false;
	},
	delete_all: function(_this,e){
		e.preventDefault();
		var _btn = $(_this),
			type_list = _btn.attr('type_list') || '',
			ids = $('.chkitem:checked').map(function(){ return $(this).val(); }).get();
		if(!ids.length){ $Core.alert.error('Chưa chọn tài liệu nào.'); return false; }
		var msg = (type_list=='trash') ? ('Xoá VĨNH VIỄN '+ids.length+' tài liệu đã chọn?') : ('Chuyển '+ids.length+' tài liệu vào Thùng rác?');
		$Core.alert.confirm('Xác nhận', msg, function(){
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete_all', {
				'p_key' : ids, 'type_list' : type_list
			}, function(respJson){
				vietiso_loading(0);
				$Core.docs.after_delete(respJson);
			}, 'json');
		});
		return false;
	},
}