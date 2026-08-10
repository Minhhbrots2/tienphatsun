$Core.package = {
	open: (_this, e) => {
		e.preventDefault();
		var property_id = $(_this).attr('property_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open', {
			'property_id': property_id
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_package');
			// Gắn TinyMCE cho textarea.isoTextArea (Giới thiệu gói) trong modal vừa mở
			setTimeout(function(){
				$('.modal textarea.isoTextArea').each(function(){
					var $ta = $(this), id = $ta.attr('id');
					if(typeof tinyMCE === 'undefined' || !tinyMCE.get(id)){
						try { $ta.isoTextArea(); } catch(err){}
					}
				});
			}, 150);
		}, 'json');
		return false;
	},
	pop_save: (_this, e) => {
		e.preventDefault();
		var $_form = $(_this).closest('form'),
			property_id = $(_this).attr('property_id'),
			_invalid = $('input.required', $_form).filter(function(){ return $Core.util.isEmpty($(this).val()); });
		if(_invalid.length){ _invalid.first().focus(); return false; }
		// Lấy nội dung mọi editor isoTextArea qua util (thống nhất với module project)
		var more = {};
		$('.isoTextArea', $_form).each(function(){
			var name = $(this).data('name'), editorId = $(this).attr('id');
			if(name) more[name] = $Core.util.getTextAreaContent(editorId);
		});
		vietiso_loading(1);
		$_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=pop_save',
			data: $.extend(more, { 'property_id': property_id }),
			dataType: 'json',
			success: function(respJson){
				vietiso_loading(0);
				if(respJson.msg && respJson.msg.indexOf('_success') >= 0){
					$Core.alert.success('Đã lưu!');
					$Core.popup.close($_form.closest('.modal'));
					window.location.reload();
				} else {
					$Core.alert.error('Lưu thất bại!');
				}
			}
		});
		return false;
	}
}
// Phân quyền (dùng chung pattern $Core.permiss của setting): open + storage
$Core.permiss = {
	open: function(_this, e){
		var for_id = $(_this).attr('for_id'),
			profile_type = $(_this).attr('profile_type');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_permiss', {
			'for_id' : for_id,
			'profile_type': profile_type
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
				$Core.alert.success('Đã lưu phân quyền!');
				$Core.popup.close(_form.closest('.modal'));
			}
		});
		return false;
	}
}
