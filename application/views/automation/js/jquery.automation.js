$Core.automation = {
	open: function (_this,e){
		e.preventDefault();
		var template_id = $(_this).attr("template_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=ajOpen',{template_id:template_id},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
		},"json");
		
	},
	save: function(_this,e){
		e.preventDefault();
		var _form = _this.closest('form'),
			_validated = 0,
			title = $("input[name='title']",_form).val(),
			content = $("textarea[name='content']",_form).val(),
			template_id = $("input[name='template_id']",_form).val(),
			is_share = $("input[name='is_share']:checked",_form).is(":checked") ? 1 : 0,
			$_adata = {'template_id':template_id,'title':title,'content':content,'is_share':is_share};		
		if($('select.required,input.required,textarea.required', _form).length){
			$('select.required,input.required,textarea.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		console.log(_form);
		if(_validated == 0) {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=ajSave',$_adata,function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result) {
					alertify.success(respJson.msg);
					setTimeout(function(){
						window.location.reload();
					},500);
				}else{
					alertify.error(respJson.msg);
				}
			},"json");
		}
		return false;
	},
	loadObject: function (_this,e) {
		e.preventDefault();
		var toId = $(_this).attr("toId"),
			apply_to = $(_this).val();
		if(apply_to == "all") {
			$("#"+toId).addClass("d-none").html("");
		}else{
			var to_value = $(_this).attr("to_value");
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadObject',{'apply_to':apply_to,'to_value':to_value},function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.html != "") {
					$("#"+toId).removeClass("d-none").html(respJson.html);
				}else{
					$("#"+toId).addClass("d-none").html("");
				}
			},"json");
		}
		
	}
}