$().ready(function(){
	if(typeof act != 'undefined' && act == "edit" && typeof path_id != 'undefined' && path_id){
		$Core.learning_path.loadCourseList({"path_id": path_id});
	}
	// Đóng dropdown kết quả tìm khi click ra ngoài
	$(document).on('click', function(e){
		if(!$(e.target).closest('#course_search, #course_search_results').length){
			$('#course_search_results').hide();
		}
	});
});

$Core.learning_path = {
	_searchTimer: null,

	// Tạo nhanh lộ trình (mở popup / lưu) — mẫu $Core.training.addTraining
	addPath: function(_this, e){
		e.preventDefault();
		var type = $(_this).data("type"),
			$_adata = {"type": type};
		if(type == "_SAVE"){
			var _form = $(_this).closest("form"),
				title = $("input[name='title']", _form).val();
			$_adata['title'] = title;
			if(title.trim() == ""){
				$("input[name='title']", _form).focus();
				return false;
			}
		}
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=addPath', $_adata, function(respJson){
			vietiso_loading(0);
			if(type == "_OPEN"){
				$Core.popup.open('500','500', respJson.html, respJson.uid);
			}
			if(type == "_SAVE"){
				if(respJson.result){
					window.location.href = respJson.link;
				}else{
					alertify.error(respJson.msg);
				}
			}
		}, 'json');
	},

	// Tìm khoá học để thêm (debounce)
	searchCourse: function(_this, e){
		var keyword = $(_this).val(),
			$results = $('#course_search_results');
		clearTimeout($Core.learning_path._searchTimer);
		$Core.learning_path._searchTimer = setTimeout(function(){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=searchCourse', {
				"path_id": path_id, "keyword": keyword
			}, function(html){
				$results.html(html).show();
			});
		}, 300);
	},

	// Thêm 1 khoá vào lộ trình
	addCourse: function(_this, e){
		e.preventDefault();
		var training_id = $(_this).data("training_id"),
			p_id = $(_this).data("path_id");
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=addCourse', {
			"path_id": p_id, "training_id": training_id
		}, function(respJson){
			vietiso_loading(0);
			if(respJson.result){
				alertify.success("Đã thêm khoá học");
				$('#course_search').val('');
				$('#course_search_results').hide().html('');
				$Core.learning_path.loadCourseList({"path_id": path_id});
			}else{
				alertify.error(respJson.msg);
			}
		}, 'json');
	},

	// Xoá 1 khoá khỏi lộ trình
	removeCourse: function(_this, e){
		e.preventDefault();
		var id = $(_this).data("id"),
			p_id = $(_this).data("path_id");
		$Core.alert.confirm('Xác nhận', "Bạn chắc chắn muốn xoá khoá học này khỏi lộ trình?", function(){
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=removeCourse', {
				"id": id, "path_id": p_id
			}, function(respJson){
				vietiso_loading(0);
				if(respJson.result){
					alertify.success("Đã xoá");
					$Core.learning_path.loadCourseList({"path_id": path_id});
				}else{
					alertify.error("Lỗi!");
				}
			}, 'json');
		});
	},

	// Bật/tắt cờ "bắt buộc"
	toggleRequired: function(_this, e){
		var id = $(_this).data("id"),
			val = $(_this).is(":checked") ? 1 : 0;
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=toggleRequired', {
			"id": id, "val": val
		}, function(respJson){
			if(respJson.result){
				alertify.success("Đã cập nhật");
			}
		}, 'json');
	},

	// Nạp lại danh sách khoá học + bind kéo-thả sắp xếp
	loadCourseList: function(options){
		var $_adata = options || {};
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=loadCourseList', $_adata, function(html){
			$('.list_course').html(html);
			$(".TableListCourse tbody").sortable({
				connectWith: ".TableListCourse",
				handle: ".mySortableHandler",
				update: function(event, ui){
					var orderNo = $(this).sortable('toArray');
					vietiso_loading(1);
					$.post(path_ajax_script+"/index.php?mod="+mod+"&act=sortCourse", {
						"path_id": path_id, "orderNo": orderNo
					}, function(resp){
						vietiso_loading(0);
						$Core.learning_path.loadCourseList(options);
					}, 'json');
				}
			}).disableSelection();
		});
	}
};
