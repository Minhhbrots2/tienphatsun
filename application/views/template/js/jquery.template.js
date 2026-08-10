$Core.template = {
	openTemplate: function (_this,e){
		e.preventDefault();
		var template_id = $(_this).attr("template_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=ajOpenTemplate',{template_id:template_id},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
		},"json");
		
	},
	saveTemplate: function(_this,e){
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
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=ajSaveTemplate',$_adata,function(respJson){
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
	addTag: function (_this,e) {
		e.preventDefault();
		var toId = $(_this).attr("toId"),
			text_area = $("#"+toId).get(0),
			tag = $(_this).data("tag");
		
		text_area.focus();

		// Trình duyệt hiện đại
		if (typeof text_area.selectionStart === "number" && typeof text_area.selectionEnd === "number") {
			var start = text_area.selectionStart;
			var end   = text_area.selectionEnd;
			var val   = text_area.value;

			text_area.value = val.slice(0, start) + tag + val.slice(end);
			var pos = start + tag.length;
			text_area.selectionStart = text_area.selectionEnd = pos;
		} else if (document.selection) { // IE cũ
			var sel = document.selection.createRange();
			sel.text = tag;
			sel.select();
		} else {// Fallback
			text_area.value += tag;
		}
	}
}