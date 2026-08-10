$().ready(function(){
	if(act == "edit"){
		$Core.training.loadListLesson({"training_id":training_id});
	}
});
$Core.training = {
	addTraining : function(_this,e){
		e.preventDefault();
		var type = $(_this).data("type"),
			$_adata = {"type":type};
		if(type == "_SAVE") {
			var _form = $(_this).closest("form"),
				title = $("input[name='title']",_form).val();
			$_adata['title'] = title;
			if(title.trim() == "") {
				$("input[name='title']",_form).focus();
				return false;
			}
		}
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=addTraining',$_adata, function(respJson){
			vietiso_loading(0);
			if(type == "_OPEN"){
				$Core.popup.open('500','500', respJson.html, respJson.uid);
			}
			if(type == "_SAVE"){
				if(respJson.result){
					window.location.href=respJson.link;	
				}else{
					alertify.error(respJson.msg);
				}
			}

		}, 'json');
	},
	changeStaff : function(_this,e){
		var form = _this.closest("form");
		var is_all_staff = $(_this).val();
		if(is_all_staff == 1){
			$('.unit_staff',form).hide();
			$('.unit_group_staff',form).hide();
			$('.iso-select2').prop('disabled', true).trigger("chosen:updated");
		}else if(is_all_staff == 2){
			$('.unit_staff',form).hide();
			$('.unit_group_staff',form).show();
			$('.iso-select2').prop('disabled', false).trigger("chosen:updated");
		} else {
			$('.unit_staff',form).show();
			$('.unit_group_staff',form).hide();		
			$('.iso-select2').prop('disabled', false).trigger("chosen:updated");
		}
	},
	open_lesson : function(_this,e){
		e.preventDefault();
		vietiso_loading(1);
		var training_id = $(_this).data("training_id"),
			lesson_id = $(_this).data("lesson_id"),
			$_adata = {"training_id":training_id,"lesson_id":lesson_id};
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_lesson',$_adata, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('500','500', respJson.html, respJson.uid);
		}, 'json');
	},
	save_lesson : function(_this,e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest("form"),
			training_id = $("input[name=training_id]",_form).val();
		if($('input.required,select.required',_form).length){
			$('input.required,select.required',_form).each((_i, _elem) => {
				let name = $(_elem).attr("name");
				if(name == "point") {
					if($(_elem).val() == "") {
						_validated++;
						$(_elem).focus();
						let text = $(_elem).data("text");
						alertify.error(text+" không được bỏ trống!");
					}					
				}else if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					let text = $(_elem).data("text");
					alertify.error(text+" không được bỏ trống");
					return false;
				}
			});
		}
		if(_validated == 0) {
			vietiso_loading(1);
			_form.ajaxSubmit({
				type 		: 	'POST',
				url 		: 	path_ajax_script+'/index.php?mod='+mod+'&act=save_lesson',
				dataType	:	'json',
				success 	: 	function(respJson){
					vietiso_loading(0);
					if(respJson.result) {
						alertify.success("Thành công!");
						$Core.training.loadListLesson({"training_id":training_id});
						$Core.popup.close(_form.closest('.modal'));
					}else{
						alertify.error("Lỗi!");
					}
				}
			});
		}
			
	},
	deleteLesson: function(_this,e){
		e.preventDefault();
		$Core.alert.confirm('Xác nhận', "Bạn chắc chắn có muốn xóa bài học này?", function(){
			vietiso_loading(1);
			var training_id = $(_this).data("training_id"),
				lesson_id = $(_this).data("lesson_id");
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=deleteLesson', {
				'training_id' : training_id,
				'lesson_id' : lesson_id
			}, function(respJson){
				console.log(respJson);
				vietiso_loading(0);
				if(respJson.result) {
					alertify.success("Xóa thành công");
					$Core.training.loadListLesson({"training_id":training_id});	
				}else{
					alertify.error(respJson.msg);
				}				
			},"json");
		});
	},
	loadListLesson: function(options){
		var $_adata = options || {};
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=loadListLesson', $_adata, function(html){
			$('.list_lesson').html(html);
			$(".TableListLesson tbody").sortable({
				connectWith: ".TableListLesson",
				handle: ".mySortableHandler",
				update: function (event, ui) {
					var orderNo = $(this).sortable('toArray');
					$_adata["orderNo"] = orderNo;
					console.log($_adata);
					vietiso_loading(1);	
					$.post(path_ajax_script+"/index.php?mod="+mod+"&act=sort_lesson",$_adata,function(html){
						vietiso_loading(0);	
						$Core.training.loadListLesson(options);
					});
				}
			}).disableSelection();
		});
	},
	select_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			type= $(_this).data("type");
		console.log(toId);
		if(type == "file") {
			$('.select_file_'+toId).trigger('click');	
		}
		if(type == "video") {
			$('.select_video_'+toId).trigger('click');	
		}
		
		return false;
	},
	upload_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('id'),
			form = $(_this).closest('form');
		vietiso_loading(1);
		form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=upload_file',
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				form.clearForm();
				form.resetForm();
				if(respJson.msg.indexOf('_success') >= 0){
					$('#content_file_'+toId).val(respJson.upload_file);
				} else {
					$Core.alert.error(respJson.msg);
				}
			}
		});	
		return false;
	},
	upload_video: function(_this, e){
		var toId = $(_this).attr('id'),
			form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);
		form.ajaxSubmit({
			type: "POST",
			url: path_ajax_script+'/index.php?mod='+mod+'&act=upload_video',
			dataType : 'json',
			success: function (respJson) {
				form.resetForm();
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_success') >= 0){
					$('#content_video_'+toId).val(respJson.upload_file);
				} else {
					$Core.alert.error(respJson.msg);
				}
			}
		});
	},
}