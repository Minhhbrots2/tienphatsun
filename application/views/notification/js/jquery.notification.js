$Core.notification = {
	open:  (_this, e) => {
		e.preventDefault();
		var notification_id = $(_this).attr("notification_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open',{
			notification_id:notification_id
		},
		function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
		},"json");	
	}, save: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form'),
			notification_id = $(_this).attr('notification_id'),
			$_adata = {'notification_id' : notification_id};
		if($('select.required:visible,input.required:visible', _form).length){
			$('select.required:visible,input.required:visible', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_error==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save",
				data: $_adata,
				dataType: "json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						$Core.alert.success(respJson.msg);
						setTimeout(function(){
							window.location.reload();
						},500);
					}else{
						$Core.alert.error(respJson.msg);
					}
				}
			});
		}
		return false;
	}, delete: function(_this, e){
		e.preventDefault();
		var notification_id = $(_this).attr("notification_id");
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa thông báo này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete',{
				'notification_id' : notification_id
			}, function(respJson){
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
	}, select_image: (_this, e) => {
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			uid = $(_this).attr('uid'),
			toId = $(_this).attr('toId');
		$('#'+toId).attr('uid', uid).attr('tp', tp).trigger('click');
		return false;
	}, upload_image: (_this, e) => {
		var _tp = $(_this).attr('tp'),
			_uid = $(_this).attr('uid'), 
			_form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);	
		console.log(_tp);
		_form.ajaxSubmit({
			type : 'POST',
			url : '/index.php?mod='+MOD+'&act=uploadImage',
			data : {'tp':_tp},
			dataType:'json',
			success : function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result) {
					$("#image_"+_uid).val(respJson.image);	
				}else{
					alertify.error("Lỗi!");
				}
				
			}
		});	
	},
	send: (_this, e) => {
		e.preventDefault();
		var notification_id = $(_this).attr("notification_id");
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn gửi thông báo này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=send',{
				'notification_id' : notification_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				setTimeout(() => {
					window.location.reload();
				},500);
			},"json");
		});
		return false;
	},
}