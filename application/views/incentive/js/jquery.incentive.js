$Core.incentive = {
	open: function (_this,e){
		e.preventDefault();
		var table_id = $(_this).attr("table_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=ajOpen',{table_id:table_id},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
			
		},"json");
		
	},
	save: function(_this,e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			table_id = $(_this).attr('table_id'),
			is_active = $("input[name=is_active]",_form).is(":checked") ? 1 : 0,
			$_adata = {'table_id':table_id,is_active:is_active};

		if($('select.required:visible,input.required:visible', _form).length){
			$('select.required:visible,input.required:visible', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if($('.isoTextArea', _form).length){
			$('.isoTextArea', _form).each((_i, _elem) => {
				var name= $(_elem).data('name'),
					editorId = $(_elem).attr('id');
				$_adata[name] = $Core.util.getTinyMCEContent(editorId);
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=ajSave",
				data: $_adata,
				dataType: "json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						alertify.success(respJson.msg);
						setTimeout(function(){
							window.location.reload();
						},500);
					}else{
						alertify.error(respJson.msg);
					}
				}
			});
		}
		return false;
	},
	checkMinute: function (_this,e) {
		/*e.preventDefault();
		var _form = $(_this).closest("form"),
			min = parseInt($(_this).attr("min")) || 30,
			minute = parseInt($(_this).val()) || 0;
		if(minute < min) {
			$(_this).val(min).focus();
			alertify.error("Thời gian gửi sau ít nhất 30 phút");
			minute = min;
		}
		
		const now = new Date();
		// Cộng số phút
		console.log(now.getMinutes(),minute);
		now.setMinutes(now.getMinutes() + minute);

		// Chuyển về định dạng hh:mm
		const hours = now.getHours().toString().padStart(2, '0');
		const minutes = now.getMinutes().toString().padStart(2, '0');

		$('input[name="time_end"]',_form).val(`${hours}:${minutes}`);*/
		
		e.preventDefault();
		var _form = $(_this).closest("form"),
			min = parseInt($(_this).attr("min")) || 30,
			timeValue = $(_this).val();		
		var inputTimeCheck = new Date();
    	inputTimeCheck.setMinutes(inputTimeCheck.getMinutes() + min);
		var hour = inputTimeCheck.getHours().toString().padStart(2, '0');
		var minute = inputTimeCheck.getMinutes().toString().padStart(2, '0');
		if (timeValue) {
    		var [hours, minutes] = timeValue.split(':').map(Number);
			var inputTime = new Date();
			inputTime.setHours(hours, minutes, 0, 0);
			if(inputTime < inputTimeCheck) {
				alertify.error("Thời gian gửi sau ít nhất 30 phút");
				$(_this).val(`${hour}:${minute}`);
			}
		}else{
			$(_this).val(`${hour}:${minute}`);
		}
		return false;
	},
	delete: function(_this,e){
		e.preventDefault();
		var table_id = $(_this).attr("table_id");
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa chương trình thi đua này?', function(){
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
	select_image: function(_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			uid = $(_this).attr('uid'),
			toId = $(_this).attr('toId');
		$('#'+toId).attr('uid', uid).attr('tp', tp).trigger('click');
		return false;
	},
	upload_image: function(_this, e){
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
	set_status: function(_this, e){
		var table_id = $(_this).attr('table_id'), 
			is_active = $(_this).is(':checked') ? 1 : 0;
		$Core.util.toggleIndicatior(1);	
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=set_status',{table_id:table_id,is_active:is_active},function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result) {
				alertify.success(respJson.msg);
			}else{
				alertify.error(respJson.msg);
				if(is_active == 1) {
					$(_this).prop("checked",false);
				}else{
					$(_this).prop("checked",true);
				}
			}

		},"json");
	},
}