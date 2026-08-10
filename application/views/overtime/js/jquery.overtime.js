$Core.overtime = {
	do_search: function(_this, e){
		e.preventDefault();
		var params = {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field');
				params[_field] = $(_elem).val();
			});
		}
		$Core.overtime.list(params);
		return false;
	},
	list: function(options){
		var $_adata = options || {};
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=list', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#'+'tableOvertime').html(respJson.html);
			$('#'+'tableOvertime').freezeTable('update');
			$('.total_record').text(respJson.total_record);
		}, 'json');
	},
	open: function(_this, e){
		e.preventDefault();
		var overtime_id = $(_this).attr('overtime_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open', {
			'overtime_id' : overtime_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			overtime_id = $(_this).attr('overtime_id'),
			$_adata = {'overtime_id' : overtime_id};
		
		if($('textarea.required,input.required,select.required', _form).length){
			$('textarea.required,input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					if($(_elem).hasClass('select2-hidden-accessible')){
						$(_elem).select2('open');
					} else {
						$(_elem).focus();
					}
					return false;
				}
			});
		}
		if(_validated == 0){
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save",
				data: $_adata,
				success: function(html){
					if(html.indexOf('_success') >= 0){
						var tmp = html.split('|||');
						$Core.overtime.list({});
						$Core.popup.close(_form.closest('.modal'));
					} else if(html.indexOf('_error') >= 0){
						$Core.swal.error( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} else if(html.indexOf('_duplicated') >= 0){
						$Core.swal.error( "Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại");
					}
				}
			});
		}
		return false;
	},
	view: function(_this, e){
		var overtime_id = $(_this).attr('overtime_id'),
			$_adata = {'overtime_id':overtime_id}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=view', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				$Core.news.load_comments(overtime_id, 'Overtime', {'action' : 'reload'});
			});
		}, 'json');
	},
	close: function(_this, e){
		e.preventDefault();
		$Core.util.popstate('/overtime.html');
	},
	delete: function(_this, e){
		e.preventDefault();
		var overtime_id = $(_this).attr('overtime_id'), 
			$_adata = {'overtime_id':overtime_id};
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete', $_adata, function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					window.location.reload();
				} else {
					$Core.swal.error( "Oops","Đã xảy ra lỗi!");
				}
			});
		});
		return false;
	},
	do_action: function(_this, e){
		e.preventDefault();
		var _modal = $(_this).closest('.modal'),
			toId = $(_this).attr('toId'),
			holderG = $(_this).attr('holderG'),
			overtime_id = $(_this).attr('overtime_id'),
			$_adata = {'toId':toId, 'overtime_id':overtime_id, 'holderG':holderG};
		
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=do_action', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_success') >= 0){
				var tmp = html.split('|||');
				$Core.overtime.list({});
				$Core.popup.close(_modal);
				$('.status_'+toId).html(tmp[1]);
			} else {
				$Core.swal.error( "Oops","Đã xảy ra lỗi!");
			}
		});	
		return false;
	},
	done: function(_this, e){
		e.preventDefault();
		var _modal = $(_this).closest('.modal'),
			openFrom = $(_this).getAttr('openFrom', '_pop'),
			overtime_id = $(_this).attr('overtime_id'),
			_validated = 0,
			$_adata = {'overtime_id':overtime_id, 'openFrom':openFrom},
			notes = $("textarea[name='notes']",_modal).val();
		
		$_adata['notes'] = notes;
		
		if($('textarea.required,input.required,select.required', _modal).length){
			$('textarea.required,input.required,select.required', _modal).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					if($(_elem).hasClass('select2-hidden-accessible')){
						$(_elem).select2('open');
					} else {
						$(_elem).focus();
					}
					return false;
				}
			});
		}			
		if(_validated == 0) {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=done', $_adata, function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					$Core.overtime.list({});
					$Core.popup.close(_modal);
				} else {
					$Core.swal.error( "Oops","Đã xảy ra lỗi!");
				}
			});	
		}
		return false;
	},
	upd_done_ratio: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			overtime_id = $(_this).attr('overtime_id');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: "POST",
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=upd_done_ratio",
			data: {'overtime_id': overtime_id},
			success: function(html) {
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					$Core.overtime.list({});
					$('.done_ratio_'+overtime_id).html(html);
					if($('.bs-webui-popover').length){
						$('.bs-webui-popover').webuiPopover('hideAll');
					}
				}
			}
		});
		return false;
	},
}