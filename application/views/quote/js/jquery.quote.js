$Core.quote = {
	open: function (_this,e){
		e.preventDefault();
		var table_id = $(_this).attr("table_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=ajOpen',{table_id:table_id},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				$Core.quote.loadObject($("select[name=apply_to]",$('#'+respJson.uid)),e);
			});
			
		},"json");
		
	},
	save: function(_this,e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			_validated = 0;	
		if($('select.required:not(.multiselect),input.required,textarea.required', _form).length){
			$('select.required:not(.multiselect),input.required,textarea.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).addClass("error").focus();
					return false;
				}else{
					$(_elem).removeClass("error");
				}
			});
		}
		if($('select.required.multiselect', _form).length){
			$('select.required.multiselect', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).addClass("error");
					return false;
				}else{
					$(_elem).removeClass("error");
				}
			});
		}
		if(_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&act=ajSave',
				data: {},
				"dataType": "json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						alertify.success(respJson.msg);
						if(profile_id != 289) {
							setTimeout(function(){
								window.location.reload();							
							},500);
						}
						
					}else{
						alertify.error(respJson.msg);
					}
				}
			});
		}
		return false;
	},
	delete: function(_this,e){
		e.preventDefault();
		var table_id = $(_this).attr("table_id");
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa trích dẫn này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=ajDelete',{table_id:table_id},function(respJson){
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
		});
		
	},
	loadObject: function (_this,e) {
		console.log($(_this))
		e.preventDefault();
		var toId = $(_this).attr("toId"),
			apply_to = $(_this).val();
		if(apply_to == "all") {
			$("#"+toId).addClass("d-none").html("");
		}else{
			var share_ids = $(_this).attr("share_ids");
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadObject',{'apply_to':apply_to,'share_ids':share_ids},function(respJson){
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