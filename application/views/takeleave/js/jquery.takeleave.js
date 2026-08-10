$(function(){
	if(ACT == 'default'){
		$Core.takeleave.list({});
	}
	$_document.on('keyup','.txtSearchTakeLeave',function(_ev){
		var _code = _ev.keyCode || _ev.which;
		if(_code===13){
			load_list_takeleave({});
		}
	});
	$_document.on('click','.filterTakeLeave',function(ev){
		ev.preventDefault();
		load_list_takeleave({});
		return false;
	});
	
	$_document.on('change','.searchGroup',function(){
		var $_this = $(this),
			user_group_id = $_this.val();
		$.post(path_ajax_request+'/index.php?mod=ajax&act=getListUserByUserGroupAll', {
			'user_group_id':user_group_id
		}, function(html){
			$('.searchUser').html(html);
		});
		return false;
	});
	
});

function select_profile_in_department(_this, e){
	var toId = $(_this).attr('toId'),
		department_id = $(_this).val();
	$Core.util.toggleIndicatior(1);
	$.post('/index.php?mod='+MOD+'&act=load_profile_in_department', {
		'department_id' : department_id
	}, function(html){
		$Core.util.toggleIndicatior(0);
		$('#'+toId).html(html).val(null).trigger('change');
	});
}
$Core.takeleave = {
	list: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$(".holder_take_leave").html('<div class="text-center p-5">'
			+'<img class="img-center-ripple" src="'+URL_IMAGES+'/ripple-loading.svg" />'
		+'</div>');
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=list_takeleave', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(".holder_take_leave").html(respJson.html);
			$('.total_record').text(respJson.total_record);
			const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')),
				popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
					return new bootstrap.Popover(popoverTriggerEl, { html: true, sanitize: true });
				});
			if(parseInt(respJson.total_page) > 1){
				$('#pager_TakeLeave').pagination({
					listStyle:"pagination justify-content-center",
					currentPage: respJson.current_page, 
					itemsOnPage: respJson.per_page,
					items: respJson.total_record,
					cssStyle: 'light-theme',
					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
					onPageClick : function(pageNumber){
						load_list_takeleave($.extend(options, {'page':pageNumber}));
					}
				});
			}
		}, 'json');
	},
	open: (_this, e) => {
		e.preventDefault();
		var is_view = 0, 
			takeleave_id = $(_this).attr('takeleave_id');
		if($(_this).hasClass('create-new-takeleave')){
			takeleave_id = 0;
			$Core.popup.close($(_this).closest('.modal'));
		} else if($(_this).hasClass('ajViewTakeLeave')){
			is_view = 1;
		}
		$Core.util.toggleIndicatior(1);
		$.ajax({
			type : 'POST',
			url : `${PCMS_URL}/take-leave/open`,
			data : {'takeleave_id':takeleave_id,'is_view':is_view},
			dataType : 'json',
			success: function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto','auto', respJson.html, 'OpenTakeLeave_'+respJson.uid);
				$('#attachments_'+respJson.uid).MultiFile({
					list: '#MultiFile-preview_'+respJson.uid
				});
			}
		});
		return false;
	},
	set_total_day_takeleave: (_this, e) => {
		var _number_day = 0,
			_form = $(_this).closest('form'),
			number_day_paid_leave_this_year = $('[name=number_day_paid_leave_this_year]', _form).val(),
			number_day_no_paid_leave = $('[name=number_day_no_paid_leave]', _form).val(),
			number_day_paid_leave_this_year = parseFloat(number_day_paid_leave_this_year),
			number_day_no_paid_leave = parseFloat(number_day_no_paid_leave);
		if($('[name=number_day_paid_leave_last_year]', _form).length){
			var number_day_paid_leave_last_year = $('[name=number_day_paid_leave_last_year]', _form).val(),
			number_day_paid_leave_last_year = parseFloat(number_day_paid_leave_last_year);
		}
		if(!$Core.util.isEmpty(number_day_paid_leave_this_year)){
			_number_day += number_day_paid_leave_this_year;
		}if(!$Core.util.isEmpty(number_day_paid_leave_last_year)){
			_number_day += number_day_paid_leave_last_year;
		}if(!$Core.util.isEmpty(number_day_no_paid_leave)){
			_number_day += number_day_no_paid_leave;
		}
		$('[name=number_day]', _form).val(_number_day);
	},
	handle_change: (_this, e) => {
		var cat_property_id = $(_this).val();
		if(!$Core.util.isEmpty(cat_property_id)){
			$.post('/index.php?mod='+MOD+'&act=getIntroCatTekeLeave', {
				'cat_property_id':cat_property_id
			}, function(html){
				$('.alert-cat-property')
					.removeClass('alert-secondary')
					.addClass('alert-warning')
					.html(html);
			});
		} else {
			$('.alert-cat-property')
				.removeClass('alert-warning')
				.addClass('alert-secondary')
				.html('Bạn cần chọn loại nghỉ phép');
		}	
	},
	pop_save_takeleave:(_this, e) => {
		e.preventDefault();
		var $_valdiated = 0,
			$_form = $(_this).closest('form'),
			takeleave_id = $(_this).attr('takeleave_id');
		if($('input.required,select.required,textarea.required', $_form).length){
			$('input.required,select.required,textarea.required', $_form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					$_valdiated ++;
					$(_elem).focus();
					return false;
				}
				if($(_elem).hasClass('minlength')){
					var _text = $(_elem).val(),
						_minlength = $(_elem).attr('minlength');
					if(_text.split(' ').length < parseInt(_minlength)){
						$_valdiated ++;
						$Core.swal.error('Thông báo','Lý do nghỉ không ngắn dưới 40 từ !');
						return false;
					}
				}
			});
		}
		if($_valdiated == 0){
			$Core.util.toggleIndicatior(1);
			$_form.ajaxSubmit({
				type: 'POST',
				url: `${PCMS_URL}/take-leave/save`,
				data: {'takeleave_id':takeleave_id},
				dataType : 'json',
				success: function(res){
					$Core.util.toggleIndicatior(0);
					if(res.msg.indexOf('error') >= 0){
						$Core.messager.alert("Thông báo", res.html);
						if(!$Core.util.isEmpty(res.name)){
							$_form.find('input[name='+res.name+']').focus();
						}
					} else {
						$Core.takeleave.list({});
						$Core.popup.close($_form.closest('.modal'));
					}
				}
			});
		}
		return false;
	},
	delete: (_this, e) => {
		e.preventDefault();
		var takeleave_id = $(_this).attr('takeleave_id');
		$Core.messager.confirm('Xác nhận', 'Xác nhận xóa phiếu nghỉ phép?', function(){
			$Core.util.toggleIndicatior(1); 
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete', {
				'takeleave_id':takeleave_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('error') >= 0){
					$Core.swal.error("Thông báo", respJson.html);
					return false;
				} else {
					$Core.takeleave.list({});
					$Core.swal.success("Thông báo", "Xóa thành công");
				}
			}, 'json');
		});
		return false;
	},
	view: (_this, e) => {
		e.preventDefault();
		var takeleave_id = $(_this).attr('takeleave_id');
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=view', {
			'takeleave_id':takeleave_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
		return false;
	},
	approval : (_this, e) => {
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			takeleave_id = $(_this).attr('takeleave_id');	
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/take-leave/approval.fcg`, {
			'tp' : tp,
			'takeleave_id' : takeleave_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(__w*2/3.5,respJson.html,respJson.uid);
		}, 'json');
		return false;
	},
	approval_save: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			tp = $(_this).attr('tp'),
			takeleave_id = $(_this).attr('takeleave_id');	
		
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: `${PCMS_URL}/take-leave/approval-save.fcg`,
			data: {'tp':tp, 'takeleave_id':takeleave_id},
			dataType : 'json',
			success: function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_success') >= 0){
					window.location.href = '/take-leave/';
				}
			}
		});
		return false;
	},
}