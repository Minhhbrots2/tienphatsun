/* global MOD, $Core */
(function () {
	function getQuestionIndex($question) {
		var name = $('textarea[name^="questions["]', $question).attr('name') || '';
		var m = name.match(/^questions\\[(\\d+)\\]/);
		return m ? parseInt(m[1], 10) : 0;
	}

	function renumberQuestions() {
		$('#qtQuestions [data-qt-question]').each(function (idx) {
			var $q = $(this);
			$('textarea, input, select', $q).each(function () {
				var $el = $(this);
				var n = $el.attr('name');
				if (!n) return;
				$el.attr('name', n.replace(/^questions\\[\\d+\\]/, 'questions[' + idx + ']'));
			});
		});
	}

	function ensureSingleCorrectInput($question) {
		var type = $('[data-qt-type]', $question).val();
		if (type !== 'single') return;
		var $radios = $('input[type=radio][name$="[correct]"]', $question);
		if ($radios.length && !$radios.is(':checked')) {
			$radios.first().prop('checked', true);
		}
	}

	function renderTFOptions($question) {
		var qi = getQuestionIndex($question);
		var html = ''
			+ '<label class="form-label mb-2">Đáp án</label>'
			+ '<div class="d-flex align-items-center gap-2 mb-2 qt-option">'
			+ '  <input type="radio" name="questions[' + qi + '][correct]" value="0" checked>'
			+ '  <input type="hidden" name="questions[' + qi + '][options][0][text]" value="Đúng">'
			+ '  <span class="flex-fill">Đúng</span>'
			+ '</div>'
			+ '<div class="d-flex align-items-center gap-2 mb-2 qt-option">'
			+ '  <input type="radio" name="questions[' + qi + '][correct]" value="1">'
			+ '  <input type="hidden" name="questions[' + qi + '][options][1][text]" value="Sai">'
			+ '  <span class="flex-fill">Sai</span>'
			+ '</div>';
		$('[data-qt-options]', $question).html(html);
	}

	function renderChoiceOptions($question, type) {
		var qi = getQuestionIndex($question);
		var $wrap = $('[data-qt-options]', $question);
		var $existing = $('[data-qt-option]', $wrap);
		var optionsCount = Math.max($existing.length, 2);
		var html = '<label class="form-label mb-2">Đáp án</label>';
		for (var oi = 0; oi < optionsCount; oi++) {
			html += '<div class="d-flex align-items-center gap-2 mb-2 qt-option" data-qt-option>'
				+ (type === 'multi'
					? '<input type="checkbox" name="questions[' + qi + '][options][' + oi + '][is_correct]" value="1">'
					: '<input type="radio" name="questions[' + qi + '][correct]" value="' + oi + '">')
				+ '<input type="text" class="form-control" name="questions[' + qi + '][options][' + oi + '][text]" placeholder="Đáp án...">'
				+ '<button type="button" class="btn btn-outline-secondary btn-icon" data-qt-remove-option title="Xóa"><i class="bx bx-x"></i></button>'
				+ '</div>';
		}
		html += '<button type="button" class="btn btn-outline-info btn-sm" data-qt-add-option>Thêm đáp án</button>';
		$wrap.html(html);
		if (type === 'single') {
			$('input[type=radio][name="questions[' + qi + '][correct]"]', $wrap).first().prop('checked', true);
		}
	}

	$(document).on('click', '[data-qt-add-question]', function () {
		var $container = $('#qtQuestions');
		var qi = $('[data-qt-question]', $container).length;
		var html = ''
			+ '<div class="border rounded-1 p-3 mb-3 qt-question" data-qt-question>'
			+ '  <div class="d-flex justify-content-between align-items-start gap-2">'
			+ '    <div class="flex-fill">'
			+ '      <label class="form-label">Nội dung</label>'
			+ '      <textarea class="form-control required" name="questions[' + qi + '][title]" rows="2"></textarea>'
			+ '    </div>'
			+ '    <div style="width: 220px">'
			+ '      <label class="form-label">Loại</label>'
			+ '      <select class="form-select" name="questions[' + qi + '][type]" data-qt-type>'
			+ '        <option value="single">Trắc nghiệm 1 đáp án</option>'
			+ '        <option value="multi">Trắc nghiệm nhiều đáp án</option>'
			+ '        <option value="tf">Đúng/Sai</option>'
			+ '      </select>'
			+ '    </div>'
			+ '    <div style="width: 120px">'
			+ '      <label class="form-label">Điểm</label>'
			+ '      <input type="number" step="0.1" class="form-control" name="questions[' + qi + '][score]" value="1">'
			+ '    </div>'
			+ '    <button type="button" class="btn btn-outline-danger btn-icon mt-4" data-qt-remove-question title="Xóa"><i class="bx bx-trash"></i></button>'
			+ '  </div>'
			+ '  <div class="mt-3" data-qt-options></div>'
			+ '</div>';
		var $q = $(html);
		$container.append($q);
		renderChoiceOptions($q, 'single');
	});

	$(document).on('click', '[data-qt-remove-question]', function () {
		$(this).closest('[data-qt-question]').remove();
		renumberQuestions();
	});

	$(document).on('change', '[data-qt-type]', function () {
		var $q = $(this).closest('[data-qt-question]');
		var type = $(this).val();
		if (type === 'tf') {
			renderTFOptions($q);
		} else {
			renderChoiceOptions($q, type);
			ensureSingleCorrectInput($q);
		}
	});

	/*$(document).on('click', '[data-qt-add-option]', function () {
		var $q = $(this).closest('[data-qt-question]');
		var qi = getQuestionIndex($q);
		var type = $('[data-qt-type]', $q).val();
		if (type === 'tf') return;
		var $optionsWrap = $('[data-qt-options]', $q);
		var oi = $('[data-qt-option]', $optionsWrap).length;
		var html = '<div class="d-flex align-items-center gap-2 mb-2 qt-option" data-qt-option>'
			+ (type === 'multi'
				? '<input type="checkbox" name="questions[' + qi + '][options][' + oi + '][is_correct]" value="1">'
				: '<input type="radio" name="questions[' + qi + '][correct]" value="' + oi + '">')
			+ '<input type="text" class="form-control" name="questions[' + qi + '][options][' + oi + '][text]" placeholder="Đáp án...">'
			+ '<button type="button" class="btn btn-outline-secondary btn-icon" data-qt-remove-option title="Xóa"><i class="bx bx-x"></i></button>'
			+ '</div>';
		$(html).insertBefore($(this));
		if (type === 'single') {
			ensureSingleCorrectInput($q);
		}
	});*/

	$(document).on('click', '[data-qt-remove-option]', function () {
		var $q = $(this).closest('[data-qt-question]');
		var type = $('[data-qt-type]', $q).val();
		$(this).closest('[data-qt-option]').remove();
		renumberQuestions();
		if (type === 'single') ensureSingleCorrectInput($q);
	});

	$(document).on('click', '[data-quiz-test-delete]', function () {
		var testId = $(this).attr('data-quiz-test-delete');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa bài test này?', function () {
			$.post('/index.php?mod=' + MOD + '&act=deleteTest', { test_id: testId }, function (resp) {
				if (resp && resp.result) {
					window.location.reload();
				} else {
					$Core.swal.error('Thất bại!', '');
				}
			}, 'json');
		});
	});
})();
$Core.quiz = {
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
			url : path_ajax_script+'/index.php?mod='+MOD+'&act=import_file',
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
}

