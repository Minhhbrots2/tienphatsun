function open_policy(_this, e){
	e.preventDefault();
	var policy_id = $(_this).attr('policy_id'),
		block_type = $(_this).attr('block_type'),
		project_id = $(_this).attr('project_id'),
		block_id = $(_this).attr('block_id'),
		building_id = $(_this).attr('building_id');
	vietiso_loading(1);
	$.ajax({
		type:'POST',
		url:path_ajax_script+'/index.php?mod='+mod+'&act=open_policy',
		data: {'policy_id':policy_id,'block_type':block_type,'project_id':project_id,'block_id':block_id,'building_id':building_id},
		dataType:'html',
		success:function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_policy');
		}
	});
	return false;
}
function add_scope(_this, e){
	e.preventDefault();
	var policy_id = $(_this).attr('policy_id'),
		block_type = $(_this).attr('block_type');
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_scope', {
		'policy_id' : policy_id,
		'block_type' : block_type
	}, function(html){
		vietiso_loading(0);
		$('.group_scopes>.scope_item:last').after(html);
	});
	return false;
}
function delete_scope(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid');
	$('.scope_item_'+uid).remove();
	return false;
}
function load_option_block(_this, e){
	var uid = $(_this).attr('uid'),
		toId = $(_this).attr('toId'),
		project_id = $(_this).val();
	
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_option_block', {
		'project_id' : project_id
	}, function(html){
		vietiso_loading(0);
		$('#'+toId).html(html).trigger("chosen:updated");
		$('#building_group_'+uid).addClass('d-none');
		$('#building_'+uid).val('').trigger("chosen:updated");
	});
}
function load_option_building(_this, e){
	var uid = $(_this).attr('uid'),
		toId = $(_this).attr('toId'),
		block_id = $(_this).val(),
		parent_id = $(_this).find('option:selected').attr('parent_id');
	
	if(parent_id==_BLOCK_TYPE_LOWFLOOR_SALE){
		$('#building_group_'+uid).addClass('d-none');
	} else {
		vietiso_loading(1);
		$('#building_group_'+uid).removeClass('d-none');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_option_building', {
			'block_id' : block_id
		}, function(html){
			vietiso_loading(0);
			$('#'+toId).html(html).trigger("chosen:updated");
		});
	}
}
function pop_save_policy(_this, e){
	e.preventDefault();
	var _validated = 0,
		_policy_id = $(_this).attr('policy_id'),
		_block_type = $(_this).attr('block_type'),
		_form = $(_this).closest('form');
	if($('input.required', _form).length){
		$('input.required', _form).each((_i, _elem) => {
			console.log($(_elem).val());
			if($Core.util.isEmpty($(_elem).val())){
				if($(_elem).hasClass('select2')){
					_validated++;
					$(_elem).trigger('chosen:open');
					event.stopPropagation();
					return false;
				} else {
					_validated++;
					$(_elem).focus();
					return false;
				}
			}
		});
	}
	if(_validated==0){
		vietiso_loading(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_policy',
			data: {'policy_id':_policy_id,'block_type':_block_type},
			dataType: 'html',
			success: function(html){
				vietiso_loading(0);
				if(html.indexOf('_error') >= 0){
					$Core.alert.error("Error !");
				} else if(html.indexOf('_success') >= 0){
					window.location.reload();
				}
			}
		});
	}
	return false;
}
function select_price_sheet(_this, e){
	e.preventDefault();
	var toId = $(_this).attr('toId');
	$('.select_price_sheet_'+toId).trigger('click');
	return false;
}
function upload_price_sheet(_this, e){
	e.preventDefault();
	var toId = $(_this).attr('id'),
		form = $(_this).closest('form');
	vietiso_loading(1);
	form.ajaxSubmit({
		type : 'POST',
		url : path_ajax_script+'/index.php?mod='+mod+'&act=upload_file',
		dataType:'json',
		success : function(respJson){
			vietiso_loading(0);
			form.clearForm();
			form.resetForm();
			if(respJson.msg.indexOf('_success') >= 0){
				$('.price_sheet_file_'+toId).val(respJson.gg_id);
				$Core.alert.success('Đã thêm file thành công !');
			} else {
				$Core.alert.error('Không thành công !');
			}
		}
	});	
	return false;
}