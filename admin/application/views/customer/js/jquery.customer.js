function open_address_book(_this){
	var address_id = $(_this).attr('address_id'),
		$_adata = {'address_id':address_id,'profile_id': profile_id};
	$.post(path_ajax_script+"/index.php?mod="+mod+"&act=open_address_book", $_adata, function(html){
		makepopup('','auto',html,'open_address_book_'+profile_id);
	});
	return false;
}
function delete_address_book(_this){
	var address_id = $(_this).attr('address_id'),
		$_adata = {'address_id':address_id,'profile_id' : profile_id};
	$Core.alert.confirm('Xác nhận xóa', "Bạn có chắc chắn muốn xóa địa chỉ này?", function(){
		$.post(path_ajax_script+"/index.php?mod="+mod+"&act=delete_address_book", $_adata, function(html){
			load_addres_book(profile_id);
		});
	});
	return false;
}
function save_address_book(_this){
	var address_id = $(_this).attr('address_id'),
		$_adata = {'address_id':address_id,'profile_id':profile_id};
	
	var _validated = 0,
		_form = $(_this).closest('form');
	if($('input.required', _form).length){
		$('input.required', _form).each(function(){
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
			url: path_ajax_script+"/index.php?mod="+mod+"&act=save_address_book",
			data: $_adata,
			success: function(html){
				var tmp = html.split('|||');
				if(html.indexOf('_success') >= 0){
					load_addres_book(tmp[1]);
					$Core.popup.close(_form.closest('.modal'));
				} else if(html.indexOf('_error') >= 0){
					$Core.alert.error(tmp[1]);
				}
			}
		});
	}
}
function load_addres_book(profile_id){
	var $_adata = {'profile_id':profile_id};
	$.post(path_ajax_script+"/index.php?mod="+mod+"&act=load_addres_book", $_adata, function(html){
		$('#holder_address_book_'+profile_id).html(html);
	});
}
$(function(){
	if(act=='view'){
		load_addres_book(profile_id);
	}
});
