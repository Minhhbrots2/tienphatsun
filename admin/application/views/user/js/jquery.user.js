$Core.user = {
	open_permiss_stock : function (_this,e) {
		e.preventDefault();
		vietiso_loading(1);
		var id = $(_this).data('id');
		$.post(path_ajax_script+'/index.php?mod=ajax&sub=user&act=open_permiss_stock', {
			'id' : id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_permiss_stock');
			$Core.user.loadList();
		});
	},
	save_permiss_stock : function (_this,e) {
		e.preventDefault();
		vietiso_loading(1);
		var _form = $(_this).closest("form"),
			$_adata = {};
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod=ajax&sub=user&act=save_permiss_stock',
			data : $_adata,
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				if(respJson.result) {
					alertify.success(respJson.msg);
				}else{
					alertify.error(respJson.msg);
				}
				setTimeout(function (){
					window.location.reload();
				},500);
			}
		});
	},
	checkedAll : function (_this,e){
		e.preventDefault();
		var parent = $(_this).closest(".box_check");
		if($(_this).is(":checked")) {
			$(".chkitem",parent).prop("checked",true);
		}else{
			$(".chkitem",parent).prop("checked",false);
		}
	},
	loadList : function () {
		var _form = $("#frmPermiss"); 
		$(".box_check",_form).each(function (index, elm) {
			if($(".chkitem:checked",$(elm)).length == $(".chkitem",$(elm)).length) {
				$(".check_all",$(elm)).prop("checked",true);
			}else{
				$(".check_all",$(elm)).prop("checked",false);
			}
		});
	}
}