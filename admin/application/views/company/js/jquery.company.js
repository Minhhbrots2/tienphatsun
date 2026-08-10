$Core.company = {
	open: function(_this, e){
		vietiso_loading(1);
		var company_id = $(_this).attr('company_id');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open', {
			'company_id' : company_id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_company');
		});
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			company_id = $(_this).attr('company_id'),
			$_adata = {'company_id':company_id};
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
				url : path_ajax_script+'/index.php?mod='+mod+'&act=save',
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
	select_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('.select_file_'+toId).trigger('click');
		return false;
	},	
	upload_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('id'),
			form = $(_this).closest('form');
		vietiso_loading(1);
		form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod=company&act=upload_file',
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				form.clearForm();
				form.resetForm();
				if(respJson.msg.indexOf('_success') >= 0){
					$('#content_file_'+toId).val(respJson.upload_file);
				} else {
					$Core.alert.error('Không thành công !');
				}
			}
		});	
		return false;
	},
}