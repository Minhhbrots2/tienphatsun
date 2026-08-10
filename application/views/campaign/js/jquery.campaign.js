$Core.campaign = {
	list: (options ) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=list', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_campaigns').html(respJson.html);
		}, 'json');
	},
	open: function(_this, e){
		e.preventDefault();
		var campaign_id = $(_this).attr('campaign_id'),
			$_adata = {'campaign_id':campaign_id};
		
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=open_campaign', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			$Core.campaign.load_content(respJson.uid, {
                'uid':respJson.uid,
				'campaign_id':respJson.campaign_id,
				'selector':respJson.selector
			}, true);
		}, 'json');
		return false;
	},
	set_staff_rdo: function(_this, e){
		var uid = $(_this).attr('uid'),
			form = $(_this).closest('form');
		if($('.opt_staff:checked', form).val()=='_select'){
			$('#rdo_staff_'+uid).removeAttr('disabled');
		} else {
			$('#rdo_staff_'+uid).attr('disabled', 'disabled');
			$('#rdo_staff_'+uid).val(null).trigger('change');
		}
	},
    set_department_rdo: function(_this, e){
		var uid = $(_this).attr('uid'),
			form = $(_this).closest('form');
		if($('.opt_department:checked', form).val()=='_select'){
			$('#rdo_department_'+uid).removeAttr('disabled');
		} else {
			$('#rdo_department_'+uid).attr('disabled', 'disabled');
			$('#rdo_department_'+uid).val(null).trigger('change');
		}
	},
	set_content(_this, e){
		var uid = $(_this).attr('uid'),
			_form = $(_this).closest('form'),
			_campaign_id = $(_this).attr('campaign_id'),
			_selector = $('input[name=selector]:checked',_form).val();
		$Core.campaign.load_content(uid, {'selector':_selector,'campaign_id':_campaign_id});
	},
	load_content: function(uid, options, hide_loading=false){
		var $_adata = options || {};
		$_adata['uid'] = uid;
		!1==hide_loading && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_content', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$('.loadcontentcampaign_'+uid).html(html);
		});
	},
	add_line: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
            uid = $(_this).attr('uid'),
			selector = $(_this).attr('selector'),
			total_line = $('.tr_campaign_'+uid, _form).length,
            is_target = $('.chk_target_'+uid, _form).is(':checked')?1:0,
			$_adata = {'uid':uid, 'is_target':is_target, 'selector':selector,'total_line':total_line};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=addline', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$('.tr_campaign_'+uid+':last').after(html);
		});
		return false;
	},
	delete_line: function(_this, e){
		e.preventDefault();
		var uid = $(_this).attr('uid');
		$('.tr_campaign_'+uid).remove();
		return false;
	},
	sw_terms: function(_this, e){
		var toId = $(_this).attr('toId');
		if($(_this).is(':checked')){
			$('#'+toId).removeClass('d-none');
		} else {
			$('#'+toId).addClass('d-none');
		}
	},
    sw_target: function(_this, e){
        var uid = $(_this).attr('uid'),
			_form = $(_this).closest('form'),
            selector = $(_this).attr('selector'),
            campaign_id = $(_this).attr('campaign_id'),
            is_target = $(_this).is(':checked') ? 1 : 0;
		if(parseInt(is_target) == 1){
			$('.chk_complete_'+uid, _form).removeAttr('disabled');
		} else {
			$('.chk_complete_'+uid, _form).attr('disabled', true);
		}
		$Core.campaign.load_content(uid, {
            'is_target' : is_target,
            'campaign_id':campaign_id,
            'selector':selector
        }, true);
    },
	open_target: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			selector = $('input[name=selector]:checked', _form).val(),
			campaign_id = $(_this).attr('campaign_id');
		if(selector=='staff'){
			var opt_staff = $('.opt_staff:checked', _form).val(),
				uid = $('.opt_staff:checked', _form).attr('uid'),
				$_adata = {'selector':selector, 'opt_staff':opt_staff, 'campaign_id':campaign_id};
			if($.trim(opt_staff)=='_select'){
				var group_members = $('#rdo_staff_'+uid).val();
				$_adata['group_members'] = group_members;
			}
		} else {
			var uid = $(_this).attr('uid'),
				group_members = $('.slb_group_members_'+uid).val(),
				$_adata = {
					'uid' : uid, 
					'campaign_id' : campaign_id, 
					'group_members' : group_members,
					'selector':selector
				};
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_target', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');	
		return false;
	},
	save_target: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			uid = $(_this).attr('uid'),
			selector = $(_this).attr('selector'),
			campaign_id = $(_this).attr('campaign_id'),
			$_adata = {'uid':uid, 'campaign_id':campaign_id, 'selector':selector};
		if($('select.required:visible,input.required:visible', _form).length){
			$('select.required:visible,input.required:visible', _form).each((_i, _elem) => {
				if($Core.util.isEmptyZero($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_target",
				data: $_adata,
				success: function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$Core.popup.close(_form.closest('.modal'));
					} else if(html.indexOf('_error') >= 0){
						var tmp = html.split('|||');
						if(!$Core.util.isEmpty(tmp[1])){
							$Core.swal.error("Oops", tmp[1]);
						} else {
							$Core.swal.error("Oops","Quá trình khởi tạo bị lỗi.");
						}
					}
				}
			});
		}
		return false;
	},
	delete: function(_this, e){
		e.preventDefault();
		var campaign_id = $(_this).attr('campaign_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$.post('/index.php?mod='+MOD+'&act=delete', {
				'campaign_id' : campaign_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					window.location.reload();
				}
			});
		});
		return false;
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			campaign_id = $(_this).attr('campaign_id'),
			$_adata = {'campaign_id':campaign_id};
		
		if($('select.required:visible,input.required:visible', _form).length){
			$('select.required:visible,input.required:visible', _form).each((_i, _elem) => {
				if($Core.util.isEmptyZero($(_elem).val())){
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
		if(_validated==0){
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_campain",
				data: $_adata,
				success: function(html){
					if(html.indexOf('_success') >= 0){
						window.location.reload(true);
					} else if(html.indexOf('_error') >= 0){
						var tmp = html.split('|||');
						if(!$Core.util.isEmpty(tmp[1])){
							$Core.swal.error("Oops", tmp[1]);
						} else {
							$Core.swal.error("Oops","Quá trình khởi tạo bị lỗi.");
						}
					}
				}
			});
		}
		return false;
	},
};