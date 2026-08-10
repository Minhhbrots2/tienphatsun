$_document.on('change','#check_all[type=checkbox]', function(){
	$('.chkitem[type=checkbox]').prop('checked', $(this).prop('checked'));
	setList();
});
$_document.on('change', '.chkitem', function(){
	setList();
});
function view_client(_this, e){
	e.preventDefault();
	var tabfocus = $(_this).getAttr('tabfocus', 1),
		client_id = $(_this).attr('client_id'),
		$_adata = {'tabfocus':tabfocus, 'client_id':client_id};
	if(!$(_this).hasClass('clicked')){
		$(_this).addClass('clicked');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=view_client', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(_this).removeClass('clicked');
			var __w = $(window).width();
			$Core.popup.openfull(__w*1/2,respJson.html,respJson.uid);
		},'json');
	}
	return false;
}
function view_billing(_this, e){
	e.preventDefault();
	var tabfocus = $(_this).getAttr('tabfocus', 1),
		billing_id = $(_this).attr('billing_id'),
		$_adata = {'tabfocus':tabfocus, 'billing_id':billing_id};
	if(!$(_this).hasClass('clicked')){
		$(_this).addClass('clicked');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=home&act=view_billing', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(_this).removeClass('clicked');
			var __w = $(window).width();
			$Core.popup.openfull(__w*1/2,respJson.html,respJson.uid);
			list_billing_logs(billing_id, {});
			load_billing_sold(billing_id, {});
			$Core.helper.load_list_notes(billing_id, 'Billing', {});
			$Core.member.load_list_files(billing_id, 'Billing', {});
			if(respJson.callback) eval(respJson.callback);
		},'json');
	}
	return false;
}
function list_billing_logs(billing_id,options){
	$.post(PCMS_URL+'/index.php?mod=home&act=list_logs', {
		'billing_id' : billing_id
	}, function(respJson){
		$('.logs_'+billing_id).html(respJson.html);
	}, 'json');
}
function load_billing_sold(billing_id, options){
	var $_adata = options || {};
	$_adata['billing_id'] = billing_id;
	$.post(PCMS_URL+'/index.php?mod=home&act=load_billing_sold', $_adata, function(html){
		$Core.util.toggleIndicatior(0);
		$('.load_billing_sold_'+billing_id).html(html);
	});
}
function setList(){
	var list_selected_chkitem="", number_checked = 0, check_all = 1;
	$('.chkitem[type=checkbox]').each(function(){
		if($(this).is(':checked')){
			number_checked++;
			list_selected_chkitem += $(this).val()+'|';
		} else {
			check_all = 0;
		}
	});
	$('#'+'check_all').prop('checked', check_all);
	$('#'+'list_selected_chkitem').val(list_selected_chkitem);
	if(number_checked > 0){
		$('.btn-delete-all').show(); 
	} else {
		$('.btn-delete-all').hide(); 
	}
}
$Core.client = {
	loadFormSubmit : function (_this,e){
		var type = $(_this).data("type"),
			_form = $(_this).closest("form");
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=loadFormSubmit",
			data: {'type':type},
			dataType: "json",
			success: function(respJson){
				console.log(respJson);
				$Core.util.toggleIndicatior(0);
				var __w = $(window).width();
				$Core.popup.openfull(__w*1/2,respJson.html,respJson.uid);
			}
		});
	},
	edit_client : function (_this,e){
		var action = $(_this).data("action"),
			_form = $(_this).closest("form"),
			client_id = $(_this).attr("client_id");
		$("input.required,select.required,textarea.required",_form).each(function(index,elm){
			if($(elm).val() == ""){
				$(elm).focus();				
				return false;
			}
		});
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=edit_client",
			data: {'action':action,'client_id' : client_id},
			dataType: "json",
			success: function(respJson){
				$Core.util.toggleIndicatior(0);
				if(action == "open"){
					$Core.popup.open('auto','auto',respJson.html, respJson.uid);	
				}else if(respJson.result){
					$Core.popup.close(_form.closest('.modal'));
					alertify.success(respJson.message);
					setTimeout(function(){
						window.location.reload();
					},500);
				}else{
					alertify.error(respJson.message);
					window.location.reload();
				}
				
			}
		});
	},
	delete_client : function (_this,e){
		var client_id = $(_this).attr("client_id");
		if(client_id > 0 && confirm("Bạn chắc chắn muốn xoá khách hàng này")){
			$.ajax({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=delete_client",
				data: {'client_id' : client_id},
				dataType: "json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result){
						alertify.success(respJson.message);
						$(_this).closest("tr").remove();
					}else{
						alertify.error(respJson.message);
						window.location.reload();
					}
				}
			});
		}
	},
	selectAll : function (_this,e){
		if($(_this).val() == 0){
			$(_this).closest(".box_select").find("select[name='client_id'] option").attr("selected",true).trigger('change.select2');
			$(_this).val(1);
		}else{
			$(_this).closest(".box_select").find("select[name='client_id']").val("").trigger('change.select2');
			$(_this).val(0);
		}		
	},
	sendEmailClient : function (_this,e){
		var _form = $(_this).closest("form");
		$("input.required,select.required,textarea.required",_form).each(function(index,elm){
			if($(elm).val() == ""){
				if($(elm).hasClass("iso-select2")){
					$(elm).trigger("click");
				}else{
					$(elm).addClass("error").focus();	
				}				
				return false;
			}
		});
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=sendEmailClient",
			dataType: "json",
			success: function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result){
					alertify.success("Gửi mail thành công!");
				}
			}
		});
	},
};