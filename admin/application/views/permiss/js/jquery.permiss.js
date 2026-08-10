$Core.permiss = {
	open_permiss(_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			parent_id = $(_this).attr('parent_id'),
			permiss_id = $(_this).attr('permiss_id'),
			profile_type = $(_this).attr('profile_type'),
			$_adata = {'tp':tp, 'parent_id' : parent_id, 'permiss_id':permiss_id,'profile_type':profile_type};
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_permiss', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',html,'open_permiss');
		});
		return false;
	},
	save_permiss: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			parent_id = $(_this).attr('parent_id'),
			profile_type = $(_this).attr('profile_type'),
			permiss_id = $(_this).attr('permiss_id');
			
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if( _validated== 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_permiss',
				data: {'parent_id':parent_id, 'permiss_id':permiss_id,'profile_type':profile_type},
				success: function(html){
					$Core.util.toggleIndicatior(0);
					window.location.reload(true);
				}
			});
		}
		return false;
	},
	delete_permiss: function(_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			permiss_id = $(_this).attr('permiss_id'),
			msg = (tp == 'group') ? 'Xoá nhóm quyền này và toàn bộ quyền con?' : 'Xoá quyền này?';
		if(!confirm(msg)) return false;
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete_permiss', {'tp':tp,'permiss_id':permiss_id}, function(res){
			$Core.util.toggleIndicatior(0);
			window.location.reload(true);
		});
		return false;
	},
	toggle_permiss: function(_this, e){
		var permiss_id = $(_this).attr('permiss_id'),
			tp = $(_this).attr('tp'),
			is_active = $(_this).is(':checked') ? 1 : 0,
			gId = $(_this).attr("gId");
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=toggle_permiss', {'permiss_id':permiss_id,'tp':tp,'is_active':is_active}, function(res){
			$Core.util.toggleIndicatior(0);
			if(tp == "group") {
				$(`input[gId='${gId}'][tp='permiss']`).prop("checked",is_active);
			}
			$(_this).prop("checked",is_active);
			//window.location.reload(true);
		});
		return false;
	},
}