$(document).ready(function() {
    $('.auto-textarea').on('input', function () {
        this.style.height = 'auto'; // Reset chiều cao
        this.style.height = this.scrollHeight + 'px'; // Set chiều cao mới theo nội dung
    });
	
});
$(document).on("input",".auto-textarea",function(){
	this.style.height = 'auto'; // Reset chiều cao
	this.style.height = this.scrollHeight + 'px'; // Set chiều cao mới theo nội dung
})
$Core.quiz = {
	open_quiz : function(_this,e){
		e.preventDefault();
		var type = $(_this).data("type"),
			quiz_id = $(_this).data("quiz_id"),
			_form = $(_this).closest("form"),
			$_adata = {"type":type,"quiz_id":quiz_id};
		var check = 1;
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=open_quiz', $_adata , function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(__w*2/3,respJson.html,respJson.uid);
		},"json");
		return false;
	},
	addQuestionO : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			is_group = $("input[name=is_group]",_form).is(":checked") ? 1 : 0,
			group_id = $(_this).attr("group_id"),
			type = $(_this).data("type"),
			toId = $(_this).attr("toId"),
			question_id = $(_this).attr("question_id"),
			total_question = $("#"+toId).find(".item_question").length,
			$_adata = {"is_group":is_group,"group_id":group_id,"type":type,"toId":toId,"question_id":question_id,"number_question":total_question+1};
		if(type == "_OPEN") {
			$.post('/index.php?mod='+MOD+'&act=addQuestion', $_adata, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open("auto","auto",respJson.html,respJson.uid);
			},"json");
		}else if(type == "_ADD") {
			var _validated = 0;
			if($('input.required,select.required,textarea.required', _form).length){
				$('input.required,select.required,textarea.required', _form).each((_i, _elem) => {
					if($Core.util.isEmpty($(_elem).val())){
						_validated++;
						$(_elem).focus();
						return false;
					}
				});
			}
			if(_validated==0){
				if($("input.form-check-input:checked",_form).length == 0) {					
					$Core.swal.error("Lỗi!","Bạn chưa chọn đáp án đúng cho câu hỏi!");
					return false
				}
				_form.ajaxSubmit({
					type: "POST",
					url: '/index.php?mod='+MOD+'&act=addQuestion',
					data: $_adata,
					async: false,
					dataType: "json",
					success: function(respJson) {
						$("#"+toId).append(respJson.html);
						$(_this).closest(".modal").find(".btn-close").trigger("click");
					}
				});
			}
		}
		
	},
	checkTrue : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			question_type = $("select[name=question_type]",_form).val();
		if(question_type == "one" || question_type == "custom") {
			$("input[type=checkbox]",_form).prop("checked",false);
			if($(_this).hasClass("form-check-input")) {
				$(_this).prop("checked",true);
			}			
		}
	},
	loadType : function (_this,e) {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			is_group = $(_this).is(":checked") ? 1 : 0,
			toId = $(_this).attr("toId");
		$.post('/index.php?mod='+MOD+'&act=loadType', {"is_group":is_group,"toId":toId}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$("#"+toId).html(respJson.html);
			if(is_group == 0) {
				$(".btn_add_question",_form).attr("toId","lst_question_"+respJson.uid).removeClass("d-none");
			}else{
				$(".btn_add_question",_form).addClass("d-none");
			}
		},"json");
	},
	file_upload: function(_this, e){
		var _form = $(_this).closest("form"),
			formData = new FormData(),
			files = $(_this).prop('files');
		if (files && files.length) {
			formData.append('banner', files[0]);
		}
		$Core.util.toggleIndicatior(1);
		$.ajax({
			url: "/index.php?mod="+MOD+"&act=uploadImage",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					var tmp = html.split('|||');
					$('#imageList',_form).html(tmp[1]);
				} else if(html.indexOf('_limit_size') >= 0){
					$Core.swal.error("Thông báo", "Dung lượng file quá lớn <= 4MB");
				}
			}
		});
	},
	updateGroupFocus: function(_this, e){
		var _form = $(_this).closest("form"),
			group_id = $(_this).data("group_id");
		console.log(group_id);
		$("input[name=group_focus]",_form).val(group_id);
	},
	
	addQuestion : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			group_id = $("input[name=group_focus]").val(),
			total_question = $(".item_question",+_form).length,
			$_adata = {"group_id":group_id,"number_question":total_question+1};
		$.post('/index.php?mod='+MOD+'&act=addQuestion', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(".box_group_"+group_id,_form).append(respJson.html);
			$Core.quiz.load_score();
		},"json");
		
	},
	deleteQuiz : function (_this,e) {
		e.preventDefault();
		var quiz_id = $(_this).data("quiz_id");
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa bài thi này?', function(){
			$.post('/index.php?mod='+MOD+'&act=deleteQuiz', {"quiz_id":quiz_id}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result) {
					$Core.swal.success("Xóa thành công!","");
					window.location.reload();
				}else{					
					$Core.swal.error("Thất bại!","");
				}
			},"json");
		});
	},
	addAnswer: function(_this, e){
		var _form = $(_this).closest("form"),
			group_id = $(_this).closest(".box_group").data("group_id"),
			item_question = $(_this).closest(".item_question"),
			question_id =item_question.data("question_id"),
			question_type = $(_this).attr("question_type");
		
		$.post('/index.php?mod='+MOD+'&act=addAnswer', {"question_type":question_type,"group_id":group_id,"question_id":question_id}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(".lst_answers",item_question).append(respJson.html);
		},"json");
	},
	loadTypeAnswer: function(_this, e){
		var _form = $(_this).closest("form"),
			item_question = $(_this).closest(".item_question"),
			question_type = $(_this).val(),
			dropdown_menu = $(_this).closest(".dropdown-menu");
		if(dropdown_menu.length > 0) {	
			$(".dropdown-item",dropdown_menu).removeClass("active");
			$(_this).parent().addClass("active");
		}
		$(".btn_addAnswer",item_question).attr("question_type",question_type);
		
		if(question_type == 3) {
			$(".item_answers:not(.item_answers_custom),.btn_addAnswer",item_question).addClass("d-none");
			$(".item_answers:not(.item_answers_custom) .form-control",item_question).attr("disabled",true);
			$(".item_answers_custom",item_question).removeClass("d-none");
			$(".item_answers_custom .form-control",item_question).removeAttr("disabled");
		}else {
			$(".item_answers_custom",item_question).addClass("d-none");
			$(".item_answers_custom .form-control",item_question).attr("disabled");
			$(".item_answers:not(.item_answers_custom),.btn_addAnswer",item_question).removeClass("d-none");
			$(".item_answers:not(.item_answers_custom) .form-control",item_question).removeAttr("disabled");
			if(question_type == 1) {
				$(".item_answers input",item_question).prop("checked",false);
				$(".item_answers input.input_check_type",item_question).attr("type","radio");
		    }else{
				$(".item_answers input.input_check_type",item_question).attr("type","checkbox");
			}
	   }
	},
	setCorrect: function(_this, e){
		var _form = $(_this).closest("form"),
			item_question = $(_this).closest(".item_question"),
			item_answers = $(_this).closest(".item_answers"),
			question_type = $("select.question_type",item_question).val(),
			is_checked = $(_this).is(":checked") ? 1 : 0;
		if(question_type == 1) {
			console.log('sss');
			$("input:checked",item_question).prop("checked",false);
		}
		if(is_checked) {
			$("input",item_answers).prop("checked",true);
		}else{
			$("input",item_answers).prop("checked",false);
		}
	},
	removeQuestion : function (_this,e) {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa câu hỏi này?', function(){
			$(_this).closest(".item_question").remove();
			$Core.quiz.loadOrder("");
			$Core.quiz.load_score()
		});
	},
	loadOrder : function (type) {
		if(type == "group") {
			$(document).find(".box_group").each(function(index,_elm){
				$(".group_index",$(_elm)).text(index+1);
			});
		}else{
			$(document).find(".lst_question").each(function(index,elm){
				$(".item_question",$(elm)).each(function(i,_elm){
					$(".number_order",$(_elm)).text(i+1);
				});
			});	
		}		
	},
	removeAnswer : function (_this,e) {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa đáp án này?', function(){
			$(_this).closest(".item_answers").remove();
		});
	},
	addGroupQuestion : function (_this,e) {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			box_group = $(".box_group",_form).length;
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=addGroupQuestion', {"box_group":box_group+1}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(".body_content",_form).append(respJson.html);
			$Core.quiz.loadOrder("group");
			$("input[name=group_focus]",_form).val(respJson.group_id);
		},"json");
	},
	removeGroupQuestion : function (_this,e) {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa phần câu hỏi này?', function(){
			$(_this).closest(".box_group").remove();
			$Core.quiz.load_score()
		});
	},
	split_points : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			total_question = $("input.split_points",_form).length,
			total_score = $("input[name=score]",_form).val();
		if($(_this).is(":checked")) {
			$("input.split_points",_form).attr("disabled",true);
			if(parseInt(total_score) > 0) {
				var point = parseInt(total_question) / parseInt(total_score);
				$("input.split_points",_form).val(point);
			}
		}else{
			$("input.split_points",_form).removeAttr("disabled");
		}
	},
	load_score: function(){
		var _form = $("#frm_question"),
			score = $("input[name=score]",_form).val(),
			total_question = $("input.point_question",_form).length,
			is_split_points = $("input[name=is_split_points]",_form).is(":checked") ? 1 : 0;
		if(is_split_points == 1) {
			var point = (parseInt(total_question) > 0) ? parseInt(score) / parseInt(total_question) : 0;
			$("input.point_question",_form).each(function(index,elm){
				$(elm).closest(".field_point").addClass("point_disabled");
				$(elm).val(Math.round(point * 10) / 10);
			});
		}else{
			$(".field_point").removeClass("point_disabled");
		}
	},
	checkItemSetting: function(_this,e){
		e.preventDefault();
		var _parent = $(_this).closest(".item_setting");
		if($(_this).is(":checked")) {
			$("input:not(.form-check-input)",_parent).removeAttr("disabled");
		}else{
			$("input:not(.form-check-input)",_parent).attr("disabled",true);
		}
	},
	addQuiz: function(_this,e){
		e.preventDefault();
		var _form = $(_this).closest("form"),
			type = $(_this).data("type"),
			is_duration = $("input[name=is_duration]",_form).is(":checked") ? 1 : 0,
			is_start_date = $("input[name=is_start_date]",_form).is(":checked") ? 1 : 0,
			is_end_date = $("input[name=is_end_date]",_form).is(":checked") ? 1 : 0,
			is_split_points = $("input[name=is_split_points]",_form).is(":checked") ? 1 : 0,
			submission_count = $("input[name=submission_count]",_form).is(":checked") ? 1 : 0,
			check = 1,
			$_adata = {"is_duration":is_duration,"is_start_date":is_start_date,"is_end_date":is_end_date,"is_split_points":is_split_points,"submission_count":submission_count,"type":type};
//		===============
		$("input.required:not([disabled]):visible,select.required:not([disabled]):visible,textarea.required:not([disabled]):visible",_form).each(function(index,elm){
			if($(elm).val() == '' || $(elm).val() == 0){
				$(elm).focus();
				$(elm).addClass("error");
				check = 0;
				if($(elm).hasClass("iso-select2")) {
					$(elm).parent().addClass("error");
				}
				return false;
			}else{
				$(elm).removeClass("error");
				if($(elm).hasClass("iso-select2")) {
					$(elm).parent().removeClass("error");
				}
			}
		});
		console.log(check);
		if(check == 0) {
			return false;
		}					
		$(".item_question",_form).each(function(index, elm){
			let question_type = $("select.question_type",$(elm)).val();
			if(question_type != 3 && $("input.form-check-input:checked",$(elm)).length == 0) {
				$(".error_message",$(elm)).removeClass("d-none").focus().text(`Vui lòng thêm đáp án đúng cho câu hỏi`);
				check = 0;
				$(elm).focus();
			}else{
				$(".error_message",$(elm)).addClass("d-none").text('');
			}
		});
//		===============
		if(check == 1) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: "POST",
				url: '/index.php?mod='+MOD+'&act=addQuiz',
				data: $_adata,
				async: false,
				dataType: "json",
				success: function(respJson) {
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						$Core.swal.success("Thành công!","")
						setTimeout(() =>{
							window.location.href = respJson.url
						},500);
					}
				}
			});	
		}	
	},	
	checkStaff: function(_this, e){
		var _form = $(_this).closest("form"),
			toId = $(_this).attr("toId");
		$('.unit_staff',_form).addClass("d-none");
		$("#"+toId,_form).removeClass("d-none");
	},	
	sendResult : function(_this,e){
		e.preventDefault();
		var type = $(_this).data("type"),
			_form = $(_this).closest("form");
		if(type == "draft") {
			$.confirm({
				title: "Xác nhận",
				content: 'Bạn có muốn lưu câu trả lời không?',
				buttons: {
					action1: {
						text: 'Đồng ý',
						btnClass: 'btn-primary',
						action: function () {
							$Core.quiz.actSendResult(_form,type);
						}
					},
					action2: {
						text: 'Không',
						btnClass: 'btn-danger',
						action: function () {
						   window.location.href="/trac-nghiem/me";
						}
					},
					cancel: {
						text: 'Hủy',
						btnClass: 'btn-default'
					}
				}
			});
		}else if(type == 'haft-time'){			
			var msg = 'Thời gian làm bài của bạn đã hết. Bạn có muốn nộp kết quả này?';
			$.confirm({
				title: "Xác nhận",
				content: 'Thời gian làm bài của bạn đã hết. Bạn có muốn nộp kết quả này?',
				buttons: {
					action1: {
						text: 'Đồng ý',
						btnClass: 'btn-primary',
						action: function () {
							$Core.quiz.actSendResult(_form,type);
						}
					},
					action2: {
						text: 'Không',
						btnClass: 'btn-danger',
						action: function () {
						   window.location.href=current_page;
						}
					}
				}
			});
		}else{
			var msg = 'Bạn chắc chắn muốn nộp bài?';
			$.confirm({
				title: "Xác nhận",
				content: 'Bạn chắc chắn muốn nộp bài?',
				buttons: {
					action1: {
						text: 'Đồng ý',
						btnClass: 'btn-primary',
						action: function () {
							$Core.quiz.actSendResult(_form,type);
						}
					},
					cancel: {
						text: 'Hủy',
						btnClass: 'btn-default',
					}
				}
			});
		}		
	},
	actSendResult : function (_form,type) {
		$(_form).ajaxSubmit({
			type: "POST",
			url: '/index.php?mod='+MOD+'&act=sendResult',
			data: {"type":type},
			async: false,
			dataType: "json",
			success: function(respJson) {
				$Core.util.toggleIndicatior(0);
				if(respJson.result) {
					$("input,textarea",$('#'+respJson.uid)).attr("disabled",true);
					$(".box_result",$('#'+respJson.uid)).removeClass("d-none");
					$Core.swal.success("Thành công!",respJson.msg)
					$Core.quiz.loadMyQuiz("_ON_GOING");
					$(_form).closest(".modal").modal("hide");
				}else{
					$Core.swal.error("Thất bại!",respJson.msg);
				}
			}
		});	
	},
	begin_test: function(_this, e){
		e.preventDefault();
		var quiz_id = $(_this).attr("quiz_id"),
			action = $(_this).attr("action");
		$.post('/index.php?mod='+MOD+'&act=begin_test', {"quiz_id":quiz_id,"action":action} , function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(0,respJson.html,respJson.uid);
			if(action != "quiz") {
				$Core.quiz.loadTotalResult($("#"+respJson.uid));	
			}			
			
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				$Core.quiz.timeCountDown("begin_test");
				$Core.quiz.countResult($('#'+respJson.uid).find(".count_number"),e);
				$Core.util.popstate(respJson.return_url);
			});
			$('#'+respJson.uid).on('hide.bs.modal', function(){
				$Core.util.popstate(current_page)
			});
		},"json");
	},	
	view_quiz: function(_this, e){
		e.preventDefault();
		var quiz_id = $(_this).attr("quiz_id"),
			action = $(_this).attr("action");
		$.post('/index.php?mod='+MOD+'&act=view_quiz', {"quiz_id":quiz_id,"action":action} , function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(0,respJson.html,respJson.uid);
			if(action != "quiz") {
				$Core.quiz.loadTotalResult($("#"+respJson.uid));	
			}			
			
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				$Core.quiz.timeCountDown("begin_test");
				$Core.quiz.countResult($('#'+respJson.uid).find(".count_number"),e);
			});
			$('#'+respJson.uid).on('hide.bs.modal', function(){
				$Core.util.popstate(current_page)
			});
		},"json");
	},	
	countResult: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest("form"),
			total_result = 0;
		$(".item_question",_form).each(function(index,elm){
			var question_type = parseInt($(elm).data("question_type"));
			if(question_type == "3") {
				if($.trim($("textarea",$(elm)).val()) != "") {
					++total_result;
				}
			}else{
				if($("input:checked",$(elm)).length > 0) {
					++total_result;
				}
			}
		});
		$(".count_number",_form).text(total_result);
	},	
	loadMyQuiz: function(type){
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=loadMyQuiz', {"type":type}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(".lst_quiz").html(respJson.html);
			$(".btn_status").removeClass("active");
			$(".btn_status"+type).addClass("active");
			$Core.quiz.timeCountDown("my_list");
				
		},"json");
	},
	list_question_answer : function(_this,e){
		e.preventDefault();
		var quiz_id = $(_this).attr("quiz_id"),
			$_adata = {"quiz_id":quiz_id};
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=list_question_answer', $_adata , function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(__w*2/3,respJson.html,respJson.uid);
		},"json");
		return false;
	},
	setResult : function(_this,e){
		e.preventDefault();
		var _modal = $(_this).closest(".modal"),
			question_id = $(_this).attr("question_id"),
			is_true = $(_this).val(),
			score_corect = $(".score_corect_"+question_id,_modal).val(),
			item_question = $(_this).closest(".item_question");
		if(is_true == 1) {
			$("input[name='correct_result["+question_id+"][score]']",item_question).val(score_corect);	
			$(".result_textarea",item_question).html(`<span class="bg-lighter btn btn-default txt_score text-success">`+score_corect+` Đúng</span>`).removeClass("d-none");
			$(".edit_result_textarea",item_question).addClass("d-none");
		}else{
			$("input[name='correct_result["+question_id+"][score]']",item_question).val(0);
			$(".result_textarea",item_question).html(`<span class="bg-lighter btn btn-default txt_score text-danger">`+score_corect+` Sai</span>`).removeClass("d-none");
			$(".edit_result_textarea",item_question).addClass("d-none");
		}	
		$("input[name='correct_result["+question_id+"][is_true]']",item_question).val(is_true);
		$Core.quiz.loadTotalResult(_modal);
		
		return false;
	},
	cancelPublicQuiz : function(_this,e){
		e.preventDefault();
		var quiz_id = $(_this).attr("quiz_id");
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=cancelPublicQuiz', {"quiz_id":quiz_id} , function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result) {
				window.location.reload();
			}
		},"json");
		return false;
	},
	loadTotalResult : function(_modal){
		var total_score = 0,
			total_correct = 0;
		$("input.score_corect",_modal).each(function(index,elm){
			total_score += parseFloat($(elm).val());
		});
		$("input.result_correct",_modal).each(function(index,elm){
			if($(elm).val() == 1) {
				++total_correct;
			}
		});
		$(".txt_result_score",_modal).text(total_score.toFixed(1));
		$("input[name=result_score]",_modal).val(total_score);
		$(".txt_result_true",_modal).text(total_correct);
		$("input[name=total_correct]",_modal).val(total_correct);
	},
	grading_answers: function(_this, e){
		e.preventDefault();
		var quiz_id = $(_this).attr("quiz_id"),
			quiz_answer_id = $(_this).attr("quiz_answer_id"),
			$_adata = {"quiz_id":quiz_id,"quiz_answer_id":quiz_answer_id};
		$.post('/index.php?mod='+MOD+'&act=grading_answers', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(0,respJson.html,respJson.uid);	
			
			$('#'+respJson.uid).on('shown.bs.modal', function(){				
				$Core.quiz.loadTotalResult($("#"+respJson.uid));	
				$Core.quiz.countResult($('#'+respJson.uid).find(".count_number"),e);
			});
		},"json");
	},
	sendGrading : function(_this,e){
		e.preventDefault();
		var _form = $(_this).closest("form");
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: "POST",
			url: '/index.php?mod='+MOD+'&act=sendGrading',
			data: {},
			async: false,
			dataType: "json",
			success: function(respJson) {
				$Core.util.toggleIndicatior(0);
				if(respJson.result) {
					$Core.swal.success("Thành công!",respJson.msg)
					$(_this).addClass("d-none");
					$(".btn_graded").removeClass("d-none");
				}else{				
					$Core.swal.error("Thất bại!",respJson.msg);
				}
			}
		});	
		return false;
	},
	reGrading : function(_this,e){
		e.preventDefault();
		var _modal = $(_this).closest(".modal");
		$(".result_textarea,.btn_graded",_modal).addClass("d-none");
		$(".btn_grading,.edit_result_textarea",_modal).removeClass("d-none");
		return false;
	},
	open_import : function(_this,e){
		e.preventDefault();
		$.post('/index.php?mod='+MOD+'&act=open_import', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		},"json");
		return false;
	},
	 config_column: (_this, e) => {
		e.preventDefault();
		var _tp = $(_this).attr('tp'),
			_form = $(_this).closest("form");
		if (_tp == 'google_sheet') {
			var spreadsheetId = $('input[name=spreadsheetId]', _form).val();
			if ($Core.util.isEmpty(spreadsheetId)) {
				$('input[name=spreadsheetId]', _form).focus();
				$Core.swal.error("Thông báo", "Bạn cần chọn file ngân hàng câu hỏi");
			} else {
				$Core.util.toggleIndicatior(1);
				$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=configColumn', {
					'tp': _tp,
					'spreadsheetId': spreadsheetId,
				}, function (respJson) {
					$Core.util.toggleIndicatior(0);
					$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
				}, 'json');
			}
		} else {
			if ($("input[name='fileimport']", _form)[0].files.length > 0) {
				$Core.util.toggleIndicatior(1);
				_form.ajaxSubmit({
					type: 'POST',
					url: PCMS_URL + '/index.php?mod=' + MOD + '&act=configColumn',
					data: { 'tp': _tp },
					dataType: 'json',
					success: function (respJson) {
						$Core.util.toggleIndicatior(0);
						$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
						$("input[name='file_id']", _form).val(respJson.uid);
					}
				});
			} else {
				$Core.swal.error("Thông báo", "Bạn cần chọn file dữ liệu khách hàng");
			}
		}
		return false;
	},
	continue_config: (_this, e) => {
		e.preventDefault();
		var _uid = $(_this).attr("uid"),
			_form = $(_this).closest("form");
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=continue_config',
			data: { 'uid': _uid },
			dataType: 'json',
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				if (respJson.result) {
					alertify.success(respJson.msg);
					$(".btn-close", _form).trigger("click");
				} else {
					alertify.error(respJson.msg);
				}
			}
		});
		return false;
	},
	do_import: function (_this, e) {
		e.preventDefault();
		var $_form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);
		$_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=do_import',
			dataType: 'json',
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				$(".body_content").html(respJson.html);
				$Core.quiz.autoTextarea();
				$Core.quiz.load_score();
				$(_this).closest(".modal").find("[data-bs-dismiss='modal']").trigger("click");
			}
		});
		
		return false;
	},
	clickUploadExcel : function(_this,e){
		e.preventDefault();
		var toId = $(_this).attr("toId");
		$("#"+toId).trigger("click");
		return false;
	},
	uploadExcel : function(_this,e){
		e.preventDefault();
		e.preventDefault();
		var _form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : PCMS_URL+'?mod='+MOD+'&act=import_file',
			data : {},
			dataType:'json',
			success : function(respJson){
				$Core.util.toggleIndicatior(0);
				_form.clearForm();
				_form.resetForm();
				if(respJson.result){
					$Core.popup.close(_form.closest('.modal'));
					$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
				} else {					
					$Core.swal.error("Lỗi upload!","");
				}
			}
		});
		return false;
	},
	addQuestionExcel : function (_this,e) {
		e.preventDefault();
		var _form=$(_this).closest("form"),
			gId = $(_this).attr("uid"),
			group_id = $("#group_focus").val(),
//			total_question = $("#frm_question").find(".item_question").length,
			$_adata = {"gId":gId,"group_id":group_id};
		$.post('/index.php?mod='+MOD+'&act=addQuestionExcel', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
//			$(".box_group_"+group_id).append(respJson.html);
			$(".body_content").html(respJson.html);
			$Core.quiz.autoTextarea();
			$Core.quiz.load_score();
			$(_this).closest(".modal").find("[data-bs-dismiss='modal']").trigger("click");
		},"json");
		
	},
	autoTextarea : function () {	
		var _form = $("#frm_question");
		$(".auto-textarea",_form).each(function(index, elm){
			let height = $(elm).prop("scrollHeight");
			$(elm).css("height",height);
		});
		
	},	
	timeCountDown : function (type) {	
		var lst_quiz = $(".lst_quiz");
		$('.time_countdown').each(function(index,elm){
			var time = $(elm).data("time");
			var targetDate = new Date().getTime() + (time * 60 * 1000);
			$(elm).countdown(targetDate)
			.on('update.countdown', function(event) {
				var totalHours = event.offset.totalHours;
				var totalMinutes = event.offset.totalMinutes;
				var format = '%M:%S';
				if (event.offset.totalDays >= 1) {
					var format = '%-d ngày %H giờ %M phút';
				} else if (totalHours >= 1) {
					var format = '%H giờ %M phút %S giây';
				} else {
					var format = '%M phút %S giây';
				}
				$(this).html(event.strftime(format));
			})
			.on('finish.countdown', function() {
				if(type == "my_list") {
					var item_quiz = $(elm).closest(".item_quiz");
					$(".btn_not_started",item_quiz).attr("onClick","$Core.quiz.begin_test(this,event)").removeClass("bg-dark").addClass("bg-info").text("Bắt đầu kiểm tra").removeAttr("disabled");
					$(this).remove();
				}else if(type == "begin_test"){
					var _modal = $(elm).closest(".modal");
					$(".btn_send_result",_modal).attr("data-type","haft-time").trigger("click");
					/*$("input,textarea",_modal).attr("disabled",true);
					$(".box_result",_modal).removeClass("d-none");*/					
				}				
				
			});
		});
	},
	gradeExam : function (_this,e) {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			action = $(_this).attr("action"),
			quiz_id = $(_this).attr("quiz_id"),
			quiz_answer_id = $(_this).attr("quiz_answers_id"),
			$_adata = {"action":action,"quiz_id":quiz_id,"quiz_answer_id":quiz_answer_id};
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=gradeExam', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$.each(respJson.quiz_answer, function(key, val) {
				$(".btn_quiz_answer_"+val.quiz_answers_id).replaceWith(val.score+` điểm`);
				$(".txt_number_correct_"+val.quiz_answers_id).text(val.number_correct+` câu`);
			});
			if(action == '_ALL') {
				$(_this).remove();
			}
		},"json");
		
	},
	toggleVideoSource: function(_this,e) {
		e.preventDefault();
		if($(_this).val() === 'link') { 
			$('#qt_video_link').show(); $('#qt_video_upload').hide(); 
		}else { 
			$('#qt_video_link').hide(); $('#qt_video_upload').show(); 
		}
	},
	toggleType : function(_this,e) {
		e.preventDefault();
		$('.qt-type-panel').addClass('d-none');
		var type = $(_this).val();
		$('#qt_panel_' + type).removeClass('d-none');
	},
	select_video: function(_this,e) {
		e.preventDefault();
		var toId = $(_this).attr("toId");
		$(`#${toId}`).trigger("click");
	},
	uploadVideo : function(_this,e) {
		e.preventDefault();
		var formData = new FormData();
		formData.append('video_file', _this.files[0]);
		$Core.util.toggleIndicatior(1);
		$.ajax({ 
			url: path_ajax_script+'/index.php?mod='+MOD+'&act=uploadVideo', 
			type: 'POST', 
			data: formData, 
			processData: false, 
			contentType: false, 
			success: function(r) {
				$Core.util.toggleIndicatior(0);
				if(r.indexOf('_success') === 0) {
					var path = r.split('|||')[1];
					$('input[name="link_video"]').val(path);
				} else if(r === '_limit_size') {
					alert('File quá lớn (tối đa 100MB)');
				} else {
					alert('Lỗi upload video');
				}
			}
		});
	},
	addCategory : function() {
		var title = prompt('Tên danh mục Module:');
		if(!title) return;
		$.post(path_ajax_script + '/index.php?mod='+MOD+'&act=addCategory', {title: title}, function(res) {
			var d = (typeof res === 'string') ? JSON.parse(res) : res;
			if(d.result) { $('#qt_category_id').append('<option value="' + d.category_id + '" selected>' + d.title + '</option>'); }
		});
	}
}
