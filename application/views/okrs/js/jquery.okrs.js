$Core.okrs = {
	list: function(options, hide_loading=true){
		var $_adata = options || {};
		!hide_loading && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=list',$_adata,function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_okrs').html(respJson.html)
			$('#basic').simpleTreeTable({
				expander: $('#expander'),
				collapser: $('#collapser')
			});
		}, 'json');
	},
	open: function(_this, e){
		e.preventDefault();
		var gr = $(_this).attr('gr'),
			okrs_id = $(_this).attr('okrs_id');
		
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open',{
			'okrs_id' : okrs_id
		},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			$('#attachments_'+respJson.uid).MultiFile({
				list: '#MultiFile-preview_'+respJson.uid
			});
		},'json');
		return false;
	}, 
	delete: function(_this, e){
		e.preventDefault();
		var okrs_id = $(_this).attr('okrs_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete', {
				'okrs_id' : okrs_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.okrs.list(okrs_id, {});
			});
		});
		return false;
	},
	add_result: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=add_result', {
			'toId' : toId
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if($('.okrs__result_'+toId).length){
				$('.okrs__result_'+toId+':last').after(html);
			} else {
				$('#'+toId).html(html);
			}
		});
		return false;
	},
	delete_result: function(_this, e){
		e.preventDefault();
		$(_this).closest('.okrs__result-item').remove();
		return false;
	},
	set_object: function(_this, e){
		var uid = $(_this).attr('uid'),
			type_id = $(_this).val();
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_object', {
			'type_id' : type_id
		}, function(html){
			$('.holder_object_'+uid).html(html);
		});
	},
	set_okrs_parent: function(_this, e){
		var uid = $(_this).attr('uid'),
			period_id = $(_this).val();
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_okrs_parent', {
			'period_id' : period_id
		}, function(html){
			$('.slb_okrs_parent_'+uid).html(html);
		});
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			okrs_id = $(_this).attr('okrs_id'),
			$_adata = {'okrs_id':okrs_id};
		
		if($('select.required,input.required',_form).length){
			$('select.required,input.required',_form).each((_i,_elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					if($(_elem).hasClass('iso-select2')){
						$(_elem).select2('open');
					} else if($(_elem).hasClass('selectize-control')){
						$(_elem)[0].selectize.open();
					} else {
						$(_elem).focus();
					}
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=pop_save_okrs",
				data: $_adata,
				success: function(html){
					$Core.util.toggleIndicatior(0);	
					if(html.indexOf('_success') >= 0){
						$Core.okrs.list({});
						$Core.popup.close(_form.closest('.modal'));
						$Core.alert.success("Thêm mục tiêu thành công !");
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
	open_kr: function(_this, e){
		e.preventDefault();
		var kr_id = $(_this).getAttr('kr_id',""),
			okrs_id = $(_this).getAttr('okrs_id', 0);
		$Core.util.toggleIndicatior(1);	
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_kr', {
			'kr_id' : kr_id,
			'okrs_id' : okrs_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);	
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		},'json');
	},
	save_kr: function(_this, e){
		e.preventDefault();
		var kr_id = $(_this).attr('kr_id'),
			okrs_id = $(_this).attr('okrs_id'),
			$_adata = {'kr_id':kr_id, 'okrs_id':okrs_id};
			
		var _validated = 0,
			_form = $(_this).closest('form');
		if($('select.required,input.required',_form).length){
			$('select.required,input.required',_form).each((_i,_elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					if($(_elem).hasClass('iso-select2')){
						$(_elem).select2('open');
					} else if($(_elem).hasClass('selectize-control')){
						$(_elem)[0].selectize.open();
					} else {
						$(_elem).focus();
					}
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_kr",
				data: $_adata,
				success: function(html){
					$Core.util.toggleIndicatior(0);	
					if(html.indexOf('_success') >= 0){
						$Core.okrs.load_list_kr(okrs_id, {});
						$Core.popup.close(_form.closest('.modal'));
						$Core.alert.success("Thêm kết quả chính thành công !");
					} else if(html.indexOf('_error') >= 0){
						$Core.swal.error( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					}
				}
			});
		}
		return false;
	},
	view_kr: function(okrs_id){
		$Core.util.toggleIndicatior(1);	
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=view_kr', {
			'okrs_id' : okrs_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);	
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			$Core.okrs.load_list_kr(okrs_id, {});
		},'json');
	},
	delete_kr: function(_this, e){
		e.preventDefault();
		var kr_id = $(_this).attr('kr_id'),
			okrs_id = $(_this).attr('okrs_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete_kr', {
				'kr_id' : kr_id,
				'okrs_id' : okrs_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.okrs.load_list_kr(okrs_id, {});
			});
		});
		return false;
	},
	load_list_kr: function(okrs_id, options){
		var $_adata = options || {};
		$_adata['okrs_id'] = okrs_id;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_list_kr', {
			'okrs_id' : okrs_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);	
			$('.holder_kr_'+okrs_id).html(respJson.html);
		},'json');
	},
}