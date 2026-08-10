$Core.member = $.extend($Core.member, {
	edit_secondary : function(_this, e){
		if(e){ e.preventDefault(); }
		var box = $(_this).closest('.secondary-role-box');
		box.find('.secondary-display').addClass('d-none');
		box.find('.secondary-editor').removeClass('d-none');
		return false;
	},
	cancel_secondary : function(_this, e){
		if(e){ e.preventDefault(); }
		var box = $(_this).closest('.secondary-role-box');
		box.find('.secondary-editor').addClass('d-none');
		box.find('.secondary-display').removeClass('d-none');
		return false;
	},
	load_secondary_role : function(_this, e){
		var box = $(_this).closest('.secondary-role-box'),
			dep = $(_this).val();
		$.post(PCMS_URL+'/index.php?mod=member&act=load_option_role_secondary', {'department_id': dep}, function(respJson){
			box.find('select[name=secondary_role_id]').html(respJson.html_role_options);
		}, 'json');
	},
	save_secondary : function(_this, e){
		if(e){ e.preventDefault(); }
		var box = $(_this).closest('.secondary-role-box'),
			pid = $(_this).data('pid'),
			dep = box.find('select[name=secondary_department_id]').val(),
			role = box.find('select[name=secondary_role_id]').val();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=member&act=save_secondary', {'p_id': pid, 'secondary_department_id': dep, 'secondary_role_id': role}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result){
				box.find('.secondary-current-text').text(respJson.current);
				box.find('.secondary-editor').addClass('d-none');
				box.find('.secondary-display').removeClass('d-none');
			} else {
				alert(respJson.msg);
			}
		}, 'json');
		return false;
	},
	manage_group : (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=manage_group', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, add_group: (_this, e) => { 
		e.preventDefault();
		var type = $(_this).data('type'),
			group_id = $(_this).data("group_id"),
			modal = $(_this).closest('.modal');
		$_adata = {'type':type,'group_id':group_id};
		if(type == "open") {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=add_group', $_adata, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
				$('#'+respJson.uid).on('shown.bs.modal', function(){
					$('#'+respJson.uid).find('.autofocus').focus();
					$(".btn-close", modal).trigger("click");
				});
			}, 'json');
		}else if(type == "save") {
			var _validated = 0,
				_form = $(_this).closest("form");
			if($('select.required:visible,input.required:visible', _form).length){
				$('select.required:visible,input.required:visible', _form).each((_i, _elem) => {
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
					$_adata[name] = $Core.util.getTinyMCEContent(editorId);
				});
			}
			if(_validated==0){
				$Core.util.toggleIndicatior(1);
				_form.ajaxSubmit({
					type:'POST',
					url: PCMS_URL + "/index.php?mod="+MOD+"&act=add_group",
					data: $_adata,
					dataType: "json",
					success: function(respJson){
						$Core.util.toggleIndicatior(0);
						if(respJson.result) {
							alertify.success("Thêm mới nhóm nhân viên thành công");
							$('.btn-close', _form).trigger('click');				
							$(".js__group-manager").trigger('click');
						}else{
							alertify.error("Có lỗi xảy ra. Vui lòng thử lại!");
						}
					}
				});
			}else{
				alertify.error("Vui lòng nhập đủ thông tin!");
			}
		}
		return false;
	}, load_list_group : (_this,e) => {
		e.preventDefault();
		var $_adata = {};
		$.post('/index.php?mod='+MOD+'&act=load_list_group', $_adata, function(html) {});
	}, delete_group : (_this,e) => {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa nhóm này?', function(){
			var group_id = $(_this).data("group_id");
			var $_adata = {"group_id":group_id};
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete_group', $_adata, function(html) {
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_invalid') >= 0){
					$Core.alert.error("Bạn không có quyền xóa");
				} else if(html.indexOf('_error') >= 0){
					$Core.alert.error("Đã xảy ra lỗi trong quá trình xóa dữ liệu");
				} else {
					$(_this).closest("tr").remove();
					$Core.alert.success("Xoá thành công");
				}
			});
		});
	}, trans_to_dept: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=trans_to_dept', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, open_trans_to_dept: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_trans_to_dept', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, hande_dep_changed: (_this, e) => {
		
	}, active_new_version : (_this, e) => {
		var profile_id = $(_this).attr('profile_id'),
			is_active_new_version = $(_this).is(':checked') ? 1 : 0;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=active_new_version', {
			'profile_id' : profile_id,
			'is_active_new_version' : is_active_new_version,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
		});
	},
});