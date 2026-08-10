function open_email_notifier(_this){
	var holderG = $(_this).attr('holderG'),
		$_adata = {'holderG':holderG};
	
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_email_notifier', $_adata, function(html){
		vietiso_loading(0);
		makepopup('auto','auto',html, 'open_add_email_'+holderG);
	});
	return false;
}
function hanlder_email_type_change(_this){
	var email_type = $(_this).val();
	if(email_type=='email'){
		$('.email__address-group').removeClass('hidden');
		$('#ipn__email-address').addClass('required');
	} else {
		$('.email__address-group').addClass('hidden');
		$('#ipn__email-address').removeClass('required');
	}
}
function add_email_notifier(_this){
	var holderG = $(_this).attr('holderG'),
		_form = $(_this).closest('form');
	
	var _validated = 0;
	if($('input.required',_form).length){
		$('input.required',_form).each(function(){
			if($Core.util.isEmpty($(this).val())){
				_validated++;
				$(this).focus();
				return false;
			}
		});
	}
	if(_validated==0){
		_form.ajaxSubmit({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=add_email_notifier',
			data: {'holderG':holderG},
			success: function(html){
				load_email_notifier(holderG);
				$Core.popup.close(_form.closest('.modal'));
			}
		});
	}
	return false;
}
function stop_email_notifier(_this){
	var holderG = $(_this).attr('holderG'),
		email_id = $(_this).attr('email_id'),
		$_adata = {'holderG':holderG,'email_id':email_id};
	
	$Core.alert.confirm('Xác nhận xóa?', "Bạn có chắn chắn muốn xóa địa chỉ email nay?", function(){
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=stop_email_notifier', $_adata, function(html){
			load_email_notifier(holderG);
		});
	});
	return false;
}
function status_email_notifier(_this){
	var status = $(_this).attr('status'),
		holderG = $(_this).attr('holderG'),
		email_id = $(_this).attr('email_id'),
		$_adata = {'holderG':holderG,'status':status,'email_id':email_id};
	
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=status_email_notifier', $_adata, function(html){
		load_email_notifier(holderG);
	});
	return false;
}
function load_email_notifier(holderG){
	vietiso_loading(1);
	var $_adata = {'holderG':holderG};
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_list_email_notifier', $_adata, function(html){
		vietiso_loading(0);
		$('#'+'holder_email_'+holderG).html(html);
	});
}
var _timeOut;
$(function(){
	if(mod=='email_template' && act=='default'){
		load_email_notifier('order');
		clearTimeout(_timeOut);
		_timeOut = setTimeout(() => {
			load_email_notifier('contact');
		},1000);
	}
});