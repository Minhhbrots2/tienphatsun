$Core.kpi = {
	open: (_this, e) => {
		e.preventDefault();
		var type = $(_this).attr('type'),
			staff_id = $(_this).attr('staff_id'),
			role_id = $(_this).attr('role_id'),
			department_id = $(_this).attr('department_id'),
			kpi_id = $(_this).attr('kpi_id');
		
		$Core.util.toggleIndicatior(1);
		$.post('index.php?mod='+MOD+'&act=open_kpi', {
			'type' : type,
			'kpi_id' : kpi_id,
			'role_id' : role_id,
			'staff_id' : staff_id,
			'department_id' : department_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.kpi.load_kpi_target(respJson.kpi_id, {});
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
		return false;
	}, set_view: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post('index.php?mod='+MOD+'&act=set_view', {
			'tp' : $(_this).val()
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			window.location.reload(true);
		});
		return false;
	}, set_loop: (_this, e) => {
		var loop = $(_this).val();
		if(loop=='ALLMONTH'){
			$('.cMXqMfvNJX').addClass('d-none');
		} else {
			$('.cMXqMfvNJX').removeClass('d-none');
		}
	}, set_year: (_this, e) => {
		var curr_year = $(_this).data('y'),
			curr_month = $(_this).data('m'),
			year = $(_this).val();
		if(parseInt(year) < parseInt(curr_year)){
			$(_this).val(curr_year);
			if(parseInt(curr_month) > 1){
				for(var i=0; i<parseInt(curr_month)-1; i++){
					$('.cMXaBZwAuJ .rbLnbPAdeY:eq('+i+')')
						.find('input:checkbox')
						.attr('disabled', true)
						.prop('checked', false);
				}
			}
		} else if(parseInt(year) == parseInt(curr_year)){
			if(parseInt(curr_month) > 1){
				for(var i=0; i<parseInt(curr_month)-1; i++){
					$('.cMXaBZwAuJ .rbLnbPAdeY:eq('+i+')')
						.find('input:checkbox')
						.attr('disabled', true)
						.prop('checked', false);
				}
			}
		} else {
			$('.eNXMGIDuZu').removeAttr('disabled');
		}
	}, open_target: (_this, e) => {
		e.preventDefault();
		var kpi_id = $(_this).attr('kpi_id'),
			kpi_target_id = $(_this).attr('kpi_target_id'),
			$_adata = {'kpi_id' : kpi_id,'kpi_target_id':kpi_target_id};
			
		$Core.util.toggleIndicatior(1);
		$.post('index.php?mod='+MOD+'&act=open_target', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
		return false;
	}, delete_target: (_this, e) => {
		e.preventDefault();
		var kpi_id = $(_this).attr('kpi_id'),
			kpi_target_id = $(_this).attr('kpi_target_id'),
			$_adata = {'kpi_id' : kpi_id,'kpi_target_id':kpi_target_id};
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn tham gia sự kiện này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('index.php?mod='+MOD+'&act=delete_target', $_adata, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.kpi.load_kpi_target(kpi_id, {});
			});
		});
		return false;
	}, pop_save_target: (_this, e) => {
		e.preventDefault();
		var kpi_id = $(_this).attr('kpi_id'),
			kpi_target_id = $(_this).attr('kpi_target_id'),
			$_adata = {'kpi_id' : kpi_id, 'kpi_target_id':kpi_target_id};
		
		var _validated = 0,
			_form = $(_this).closest('form');
		if($('select.required,input.required', _form).length){
			$('select.required,input.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=pop_save_target",
				data: $_adata,
				success: function(html){
					if(html.indexOf('success') >= 0){
						$Core.kpi.load_kpi_target(kpi_id, {});
						$('.btn-close', _form).trigger('click');
					} else if(html.indexOf('invalid') >= 0){
						$Core.swal.error("Oops","Lỗi trùng lặp dữ liệu");
					} else {
						$Core.swal.error("Oops","Xin vui lòng thử lại");
					} 
				}
			});
		}			
		return false;
	}, load_kpi_target: (kpi_id, options) => {
		var $_adata = options || {};
		$_adata['kpi_id'] = kpi_id;
		$.post('index.php?mod='+MOD+'&act=load_kpi_target', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_kpi_target_'+kpi_id).html(respJson.html);
		}, 'json');
	}, autosave_field: (_this, e) => {
		var _validated = 1,
			_form = $(_this).closest('form'),
			kpi_id = $(_this).attr('kpi_id'),
			p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field');
		if(p_field=='target_percent'){
			
		}
		$Core.util.toggleIndicatior(1);
		$.post('index.php?mod='+MOD+'&act=autosave_field', {
			'p_id' : p_id,
			'kpi_id' : kpi_id,
			'p_field' : p_field,
			'p_value' : $(_this).val()
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_error') >= 0){
				$Core.swal.error("Oops","Lỗi không lưu được dữ liệu");
			}
		});
	}, pop_save_kpi: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			kpi_id = $(_this).attr('kpi_id'),
			current_step = $(_this).attr('current_step'),
			next_step = $(_this).attr('next_step'),
			$_adata = {'kpi_id':kpi_id, 'current_step':current_step};
		
		if($('select.required,input.required', _form).length){
			$('select.required,input.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0 && current_step=='_first'){
			if($('.trTarget_'+kpi_id).length == 0){
				_validated += 1;
				$Core.swal.error("Oops","Vui lòng chọn chỉ tiêu");
				return false;
			}
		}
		if(_validated==0 && current_step=='_first'){
			var total_percent = 0;
			$('input[p_field=target_percent]',_form).each((_i, _elem) => {
				total_percent += parseInt($(_elem).val());
			});
			if(total_percent < 100 || total_percent > 100){
				_validated += 1;
				$Core.swal.error("Oops","Trọng số phải bằng 100%");
				return false;
			}
		}
		if(_validated==0){
			if($('.isoTextArea,.textarea_intro_editor', _form).length){
				$('.isoTextArea,.textarea_intro_editor', _form).each((_i, _elem) => {
					var name= $(_elem).data('name'),
						editorId = $(_elem).attr('id');
					$_adata[name] = $Core.util.getTinyMCEContent(editorId);
				});
			}
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=pop_save_kpi",
				data: $_adata,
				success: function(html){
					if(html.indexOf('success') >= 0){
						if(!$Core.util.isEmpty(next_step)){
							$Core.kpi.open_step(kpi_id, next_step, _this);
						} else {
							window.location.reload(true);
						}
					} else if(html.indexOf('invalid') >= 0){
						$Core.swal.error("Oops","Lỗi trùng lặp dữ liệu");
					} else {
						$Core.swal.error("Oops","Xin vui lòng thử lại");
					} 
				}
			});
		}
		return false;
	}, set_month: function(_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			uid = $(_this).attr('uid');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=set_month', {
			'tp' : tp,
			'month' : $('#'+uid).val()
		}, function(html){
			$('#'+uid).val(html).trigger('change');
		});
		return false;
	}, do_change: (_this, e) => {
		$Core.kpi.load_kpi({});
	}, select_month: function(_this, e){
		$Core.kpi.load_kpi({});
	}, toNumber: function(num){
		num = num.replaceAll('.','');
		num = num.replaceAll(',','');
		num = num.replaceAll('%','');
		console.log(num);
		return Number(num);
	}, load_kpi: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var name = $(_elem).data('field');
				if(typeof(name) !== 'undefined'){
					var type = $(_elem).attr('type');
					if(type === 'checkbox'){
						$_adata[name] = $(_elem).is(':checked') ? 1 : 0;
					} else {
						$_adata[name] = $(_elem).val();
					}
				}
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('index.php?mod='+MOD+'&act=load_kpi', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#'+'holder_kpi').html(respJson.html);
			if($(".table-sort").length){
				$(".table-sort").tableSortable({
					cmp:(a,b) => $Core.kpi.toNumber(a) < $Core.kpi.toNumber(b) ? -1 : 1
				});
			}
		}, 'json');
		return false;
	},
}