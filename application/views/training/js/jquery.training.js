$(function(){
	var storage = window.localStorage;
	window.addEventListener("load", function(e) {
		if($Core.training.stor.start_time !== undefined 
			&& $Core.training.stor.start_time > 0){
			$Core.training.log_training();
		}
    })
});
$Core.training = {
	stor : window.localStorage,
	start_time : 0,
	log_training : function(){
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=log_training', {
			'training_id' : $Core.training.stor.training_id,
			'lesson_id' : $Core.training.stor.lesson_id,
			'start_time' : $Core.training.stor.start_time,
		}, function(respJson){				
			$Core.training.stor.setItem("start_time",0);
			$Core.training.stor.setItem("training_id","");
			$Core.training.stor.setItem("lesson_id","");
		}, 'json');
		return false;
	},
	open: function(_this, e){
		e.preventDefault();
		var training_id = $(_this).attr('training_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/training/open.cfg', {
			'training_id' : training_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(__w*2/3,respJson.html,respJson.uid);
		}, 'json');
		return false;
	},
	loadHistory: function(_this, e){
		e.preventDefault();
		var training_id = $(_this).attr('training_id'),
			toId = $(_this).attr("data-bs-target");
		$.post(PCMS_URL+'/training/history.cfg', {
			'training_id' : training_id
		}, function(respJson){
			$(toId).html(respJson.html);
		}, 'json');
		return false;
	},
	learning: function(_this, e){
		e.preventDefault();
		var training_id = $(_this).data('training_id'),
			lesson_id = $(_this).data("lesson_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/training/learning.cfg', {
			'training_id' : training_id,
			'lesson_id' : lesson_id,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result) {
				var __w = $(window).width();
				$Core.training.stor.setItem("start_time",respJson.start_time);
				$Core.training.stor.setItem("training_id",training_id);
				$Core.training.stor.setItem("lesson_id",lesson_id);
				console.log($Core.training.stor);
				$Core.popup.openfull(__w/2,respJson.html,respJson.uid);
				
				$('#'+respJson.uid).on('hidden.bs.modal', function () {
					$Core.training.log_training();
				});
				$('#'+respJson.uid).on('shown.bs.modal', function () {
					let _modal = $('#'+respJson.uid);
					setTimeout(function(){
						console.log($("iframe",_modal).length);
					},1000);
					
					$('#'+respJson.uid).find("iframe").on('contextmenu', function(e) {
						e.preventDefault();
					});
				});
				
			}else{
				alertify.error("ERROR!");
			}
			
		}, 'json');
		return false;
	},
	completed: function(_this, e){
		e.preventDefault();
		var training_id = $(_this).data('training_id'),
			lesson_id = $(_this).data("lesson_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/training/completed.cfg', {
			'training_id' : training_id,
			'lesson_id' : lesson_id,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result) {
				$(_this).removeAttr("onclick").addClass("btn-success").removeClass("btn-primary").text(`Đã hoàn thành`);
				$(".btn_"+lesson_id).removeClass("btn-outline-primary").addClass("btn-outline-success");
				$(".btn_completed_"+lesson_id).removeClass("d-none");
				alertify.success("Bạn đã hoàn thành bài học!");
				$(".done_ratio_"+training_id).html(respJson.progress);
				$(".view_lesson_"+lesson_id).removeClass("d-none");
			}else{
				alertify.error("ERROR!");
			}
			
		}, 'json');
		return false;
	},
	set_view: function(_this, e){
		var view = $(_this).val();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=set_view', {'view' : view }, function(respJson){
			$Core.util.toggleIndicatior(0);
			window.location.reload(true);
		});
	},
	show_search : function (_this,e){
		e.preventDefault();
		var _form = $(_this).closest("_form");
		$(_this).closest(".box_search").toggleClass("show_search");
		if($(_this).closest(".box_search").hasClass("show_search")) {
			let _value = $(".inp_search",_form).val();
			$(".inp_search",_form).val(_value).focus().trigger('click');
		}
	},
	search : function (_this,e){
		if(e.keyCode == 13) {
			$(_this).closest("form").submit();
		}
	},
};
$Core.inspire = {	
	open_inspire: (_this, e) => {
		e.preventDefault();
		var training_id = $(_this).attr('training_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+"&act=open_inspire", {
			'training_id' : training_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
		return false;
	},
	loadListLesson: function(options){
		var $_adata = options || {};
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadListLesson', $_adata, function(respJson){
			$('.list_lesson').html(respJson.html);
			$(".TableListLesson tbody").sortable({
				connectWith: ".TableListLesson",
				handle: ".mySortableHandler"
			}).disableSelection();
		}, "json");
	},
	save_inspire : function(_this,e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest("form"),
			training_id = $(_this).attr("training_id"),
			cat_id = $(_this).attr("cat_id"),
			$_adata = {training_id:training_id, cat_id: cat_id};
		if($('input.required,select.required',_form).length){
			$('input.required,select.required',_form).each((_i, _elem) => {
				if($(_elem).val() == "") {
					_validated++;
					let name = $(_elem).attr("name");
					$(_elem).focus();
					let text = $(_elem).data("text");
					alertify.error(text+" không được bỏ trống");
					return false;
				}				
			});
		}
		if(_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type 		: 	'POST',
				url 		: 	PCMS_URL + "/index.php?mod="+MOD+"&act=save_inspire",
				dataType	:	'json',
				data		: $_adata,
				success 	: 	function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						$Core.swal.success("Thông báo","Thành công!");
						$(".btn-close",_form).trigger("click");
					}else{
						$Core.swal.error("Oops","Quá trình đăng video bị lỗi. Xin vui lòng thử lại");
					}
				}
			});
		}
			
	},
	select_video: (_this, e) => {
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			uid = $(_this).attr('uid'),
			toId = $(_this).attr('toId');
		$('#'+toId).attr('uid', uid).attr('tp', tp).trigger('click');
		return false;
	},
	upload_file: (_this, file) => {
		var _uid = $(_this).attr('uid'),
			_form = $(_this).closest("form");
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type 		: 	'POST',
			url 		: 	PCMS_URL + "/index.php?mod="+MOD+"&act=uploadVideo",
			dataType	:	'html',
			success 	: 	function(html){
				$Core.util.toggleIndicatior(0);
				$(".link_video_"+_uid).val(html);
			}
		});
	},	
	view_inspire: function(_this, e){
		e.preventDefault();
		var training_id = $(_this).data('training_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + "/index.php?mod="+MOD+"&act=view_inspire", {
			'training_id' : training_id,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result) {
				var __w = $(window).width();
				$Core.global.training.stor.setItem("start_time",respJson.start_time);
				$Core.global.training.stor.setItem("training_id",training_id);
				$Core.global.training.stor.setItem("lesson_id",lesson_id);
				console.log($Core.global.training.stor);
				$Core.popup.openfull(__w/2,respJson.html,respJson.uid);

				$('#'+respJson.uid).on('hidden.bs.modal', function () {
					$Core.global.training.log_training();
				});
				$('#'+respJson.uid).on('shown.bs.modal', function () {
					let _modal = $('#'+respJson.uid);
					setTimeout(function(){
						console.log($("iframe",_modal).length);
					},1000);

					$('#'+respJson.uid).find("iframe").on('contextmenu', function(e) {
						e.preventDefault();
					});
				});

			}else{
				alertify.error("ERROR!");
			}

		}, 'json');
		return false;
	},
	log_inspire : function(_this,e){
		var training_id = $(_this).attr("training_id"),
			lesson_id = $(_this).attr("lesson_id");
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=log_training', {
			'training_id' : training_id,
			'lesson_id' : lesson_id,
			'start_time' : "",
		}, function(respJson){	
		}, 'json');
		return false;
	},
	like : function(_this,e){
		e.preventDefault();
		var training_id = $(_this).attr('training_id');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=inspire_like', {
			'training_id' : training_id
		}, function(respJson){
			if(respJson.result) {
				$(".inspire_"+training_id).replaceWith(respJson.html);
				if($(".txt_like_"+training_id).length > 0) {
					$(".txt_like_"+training_id).html(respJson.html_like);	
				}				
			}
		},"json");
		return false;
	},
	update_field : function(_this,e){
		e.preventDefault();
		var training_id = $(_this).attr('training_id'),
			action = $(_this).attr("action");
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=update_field', {
			'training_id' : training_id,'action':action
		}, function(respJson){
			if(respJson.result) {
				$Core.swal.success("Thông báo","Thành công!");
			}else{
				$Core.swal.error("Oops","Quá trình đăng video bị lỗi. Xin vui lòng thử lại");
			}
			setTimeout(function(){
				window.location.reload();
			},500);
		},"json");
		return false;
	},
}