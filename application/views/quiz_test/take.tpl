<style>
	.qt-answer-label { cursor: pointer; display: block; width: 100%; border: 1px solid #d9dee3; border-radius: 0.375rem; padding: 12px 16px; transition: all 0.2s ease-in-out; margin-bottom: 0; }
	.qt-answer-label:hover { background-color: #f8f9fa; border-color: #696cff; }
	.form-check-input:checked + .qt-answer-label { background-color: rgba(105, 108, 255, 0.08); border-color: #696cff; color: #696cff; font-weight: 600; }
	.qt-question-card { transition: box-shadow 0.2s; }
	.qt-question-card:focus-within { box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.1); border-color: #696cff; }
	.sticky-bottom-bar { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); box-shadow: 0 -2px 10px rgba(0,0,0,0.05); border-top: 1px solid #eee; }
</style>
<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-3">
		<div class="p__left">
			<h4 class="fw-bold mb-1">{$oneItem.title}</h4>
			<div class="text-muted">
				{if $oneItem.test_type eq 'video'}<span class="badge bg-label-info me-1">Video</span>
				{elseif $oneItem.test_type eq 'module'}<span class="badge bg-label-warning me-1">Module</span>
				{else}<span class="badge bg-label-success me-1">Dự án</span>{/if}
				{if $oneItem.duration gt 0}· Thời gian: {$oneItem.duration} phút{else}· Không giới hạn thời gian{/if}
				· Pass: {$oneItem.pass_score}%
			</div>
		</div>
		<div class="p__right d-flex gap-2 align-items-center">
			<a href="/trac-nghiem-test/me" class="btn btn-outline-secondary">Hủy bỏ</a>
		</div>
	</div>

	{* === Video embed if video type === *}
	{if $oneItem.test_type eq 'video' && !empty($videoEmbed)}
		<div class="col-md-10 col-lg-8 mx-auto mb-4">
			<div class="card rounded-1 overflow-hidden shadow-sm border-0">
				<div class="card-body p-0 bg-dark">
					{$videoEmbed}
				</div>
				<div class="card-footer bg-white py-2">
					<small class="text-muted"><i class='bx bx-info-circle'></i> Vui lòng xem kỹ video trước khi trả lời câu hỏi bên dưới.</small>
				</div>
			</div>
		</div>
	{elseif $oneItem.test_type eq 'training'}
		<div class="col-md-10 col-lg-8 mx-auto mb-4">
			<div class="card rounded-1 overflow-hidden shadow-sm border-0">
				<div class="card-header">
					<h3 class="card-title">Danh sách video đào tạo</h3>
				</div>
				<div class="card-body p-0">
					<div class="lst_lesson border-top border-bottom p-2">
						{foreach from=$lstLesson item=_oLesson key=key name=i}
							<div class="item_lesson position-relative p-3 border rounded-3 cursor-pointer {if !$smarty.foreach.i.last}mb-2{/if}" onClick="$Core.global.training.learning(this,event)" data-training_id="{$training_id}" data-lesson_id="{$key}">						
								<div class="d-flex justify-content-between align-items-center">
									<div class="d-flex align-items-center gap-2 flex-fill">
										<button class="btn rounded-pill btn_{$key} {if !$clsISO->checkItemInArray($key,$lesson_complete)}btn-outline-primary{else}btn-outline-success{/if} btn-sm btn-icon" type="button"  data-bs-toggle="tooltip" title="Xem bài học"><i class='bx bxs-right-arrow fs-12'></i></button><a class="d-flex align-items-start collapsed text-dark fs-16 lh-xs" data-bs-toggle="collapse" href="#lesson_{$key}" role="button" aria-expanded="false">{$_oLesson.title} </a>
									</div>
									{if $deviceType eq "phone"}
										<span class="btn btn-xs btn-success btn_completed btn_completed_{$key} btn-icon {if !$clsISO->checkItemInArray($key,$lesson_complete)}d-none{/if}"><i class='bx bx-check'></i></span>
									{else}
										<span class="btn btn-xs btn-success text-nowrap btn_completed_{$key} {if !$clsISO->checkItemInArray($key,$lesson_complete)}d-none{/if}">Đã hoàn thành</span>
									{/if}
								</div>
							</div>
						{/foreach}
					</div>
				</div>
				<div class="card-footer bg-white py-2">
					<small class="text-muted"><i class='bx bx-info-circle'></i> Vui lòng hoàn thành video đào tạo trước khi trả lời câu hỏi bên dưới.</small>
				</div>
			</div>
		</div>
	{/if}

	<form action="" method="post" class="col-md-10 col-lg-8 mx-auto pb-5" id="qtTakeForm">
		<input type="hidden" name="test_id" value="{$oneItem.test_id}">
		<input type="hidden" name="duration_spent" id="qt_duration_spent" value="0">
		
		<div class="card mb-5 rounded-1 shadow-sm border-0">
			<div class="card-body p-4">
				{if !empty($questions)}
					{foreach from=$questions item=q key=qi name=i}
						<div class="qt-question-card border rounded-1 p-4 mb-4 bg-white" id="q_card_{$q.question_id}">
							<div class="d-flex align-items-start mb-3">
								<div class="badge bg-label-primary rounded-circle p-2 me-3 fs-6 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">{$smarty.foreach.i.iteration}</div>
								<div class="fw-semibold fs-5 mt-1" style="line-height: 1.4;">{$q.title}</div>
							</div>
							
							<div class="ps-5">
								{if $q.type eq 'multi'}
									<div class="text-muted small mb-2"><i class='bx bx-check-double'></i> Chọn nhiều đáp án</div>
									{foreach from=$q.options item=opt key=oi}
										<div class="form-check custom-option custom-option-basic mb-2 ps-0">
											<input class="form-check-input d-none" type="checkbox" name="result[{$q.question_id}][]" id="q{$q.question_id}_{$opt.answer_id}" value="{$opt.answer_id}">
											<label class="form-check-label qt-answer-label d-flex align-items-center" for="q{$q.question_id}_{$opt.answer_id}">
												<i class='bx bx-checkbox fs-4 me-2 unchecked-icon text-muted'></i>
												<i class='bx bxs-checkbox-checked fs-4 me-2 checked-icon text-primary d-none'></i>
												<span>{$opt.text}</span>
											</label>
										</div>
									{/foreach}
								{else}
									<div class="text-muted small mb-2"><i class='bx bx-check'></i> Chọn 1 đáp án</div>
									{foreach from=$q.options item=opt key=oi}
										<div class="form-check custom-option custom-option-basic mb-2 ps-0">
											<input class="form-check-input d-none" type="radio" name="result[{$q.question_id}]" id="q{$q.question_id}_{$opt.answer_id}" value="{$opt.answer_id}">
											<label class="form-check-label qt-answer-label d-flex align-items-center" for="q{$q.question_id}_{$opt.answer_id}">
												<i class='bx bx-radio-circle fs-4 me-2 unchecked-icon text-muted'></i>
												<i class='bx bxs-radio-circle-marked fs-4 me-2 checked-icon text-primary d-none'></i>
												<span>{$opt.text}</span>
											</label>
										</div>
									{/foreach}
								{/if}
							</div>
						</div>
					{/foreach}
				{else}
					<div class="text-center py-5 text-muted">
						<i class='bx bx-ghost fs-1 mb-2'></i>
						<div>Bài test chưa có câu hỏi nào.</div>
					</div>
				{/if}
			</div>
		</div>

		{* Sticky bottom bar for timer and submit action *}
		<div class="position-fixed bottom-0 start-0 w-100 sticky-bottom-bar p-3 zindex-1">
			<div class="container-xxl">
				<div class="col-md-10 col-lg-8 mx-auto d-flex justify-content-between align-items-center">
					<div class="d-flex align-items-center gap-4">
						{if $oneItem.duration gt 0}
							<div class="d-flex align-items-center gap-2">
								<div class="spinner-grow text-danger spinner-grow-sm" role="status" id="timer_pulse"></div>
								<span id="qt_timer" class="fw-bold fs-4 text-danger font-monospace" style="letter-spacing: 1px;">--:--</span>
							</div>
						{else}
							<div class="text-muted"><i class='bx bx-timer'></i> Không giới hạn</div>
						{/if}
						<div class="text-muted fw-semibold" id="qt_progress"><span class="text-primary">0</span> / {$questions|@count} đã trả lời</div>
					</div>
					<button type="submit" class="btn btn-primary btn-lg shadow-sm px-5" id="qt_submit_btn">
						<i class='bx bx-send me-2'></i> Nộp bài
					</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
$(function() {
	// Custom icons logic
	$('input[type="radio"], input[type="checkbox"]').on('change', function() {
		var name = $(this).attr('name');
		if($(this).attr('type') === 'radio') {
			$('input[name="' + name + '"]').siblings('label').find('.checked-icon').addClass('d-none');
			$('input[name="' + name + '"]').siblings('label').find('.unchecked-icon').removeClass('d-none');
		}
		if($(this).is(':checked')) {
			$(this).siblings('label').find('.unchecked-icon').addClass('d-none');
			$(this).siblings('label').find('.checked-icon').removeClass('d-none');
		} else {
			$(this).siblings('label').find('.checked-icon').addClass('d-none');
			$(this).siblings('label').find('.unchecked-icon').removeClass('d-none');
		}
	});

	// Prevent Enter to submit
	$(window).keydown(function(event){
		if(event.keyCode == 13) { event.preventDefault(); return false; }
	});

	var totalSeconds = 0;
	var maxDuration = {if $oneItem.duration gt 0}{$oneItem.duration}{else}0{/if} * 60; // seconds
	var timerEl = document.getElementById('qt_timer');
	var spentEl = document.getElementById('qt_duration_spent');

	// Countdown / elapsed timer
	if(maxDuration > 0) {
		var remaining = maxDuration;
		var timerInterval = setInterval(function() {
			totalSeconds++;
			remaining--;
			spentEl.value = totalSeconds;

			var m = Math.floor(remaining / 60);
			var s = remaining % 60;
			timerEl.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;

			if(remaining <= 60) {
				timerEl.classList.add('animate__animated', 'animate__flash', 'animate__infinite');
			}

			if(remaining <= 0) {
				clearInterval(timerInterval);
				timerEl.textContent = '00:00';
				if(typeof $Core.swal !== 'undefined') {
					$Core.swal.warning('Hết giờ!', 'Hệ thống tự động nộp bài của bạn.');
				} else {
					alert('Hết giờ! Hệ thống tự động nộp bài.');
				}
				setTimeout(function(){ $('#qtTakeForm').trigger('submit'); }, 1500);
			}
		}, 1000);

		// Initial display
		var im = Math.floor(remaining / 60);
		var is2 = remaining % 60;
		timerEl.textContent = (im < 10 ? '0' : '') + im + ':' + (is2 < 10 ? '0' : '') + is2;
	} else {
		setInterval(function() {
			totalSeconds++;
			spentEl.value = totalSeconds;
		}, 1000);
	}

	// Progress tracker
	function updateProgress() {
		var answered = 0;
		var total = {$questions|@count};
		$('.qt-question-card').each(function() {
			if($(this).find('input:checked').length > 0) answered++;
		});
		$('#qt_progress').html('<span class="text-primary">' + answered + '</span> / ' + total + ' đã trả lời');
		
		// If all answered, pulse submit button
		if(answered === total && total > 0) {
			$('#qt_submit_btn').removeClass('btn-primary').addClass('btn-success');
		} else {
			$('#qt_submit_btn').removeClass('btn-success').addClass('btn-primary');
		}
	}
	$(document).on('change', '#qtTakeForm input[type="radio"], #qtTakeForm input[type="checkbox"]', updateProgress);

	// Submit form
	$('#qtTakeForm').on('submit', function(e) {
		e.preventDefault();
		
		var answered = 0;
		var total = {$questions|@count};
		$('.qt-question-card').each(function() {
			if($(this).find('input:checked').length > 0) answered++;
		});

		var doSubmit = function() {
			$('#qt_submit_btn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Đang nộp...');
			var formData = $('#qtTakeForm').serialize();
			$.post('/index.php?mod=quiz_test&act=submitTest', formData, function(res) {
				var d = (typeof res === 'string') ? JSON.parse(res) : res;
				if(d.result) {
					var msg = 'Điểm: ' + d.score + '% (' + d.correct + '/' + d.total + ' câu)<br>';
					msg += 'Xếp hạng: <b>' + d.tier + '</b> (QS: ' + d.qs + ')';
					
					if(typeof $Core.swal !== 'undefined') {
						if(d.is_pass) {
							$Core.swal.success('Chúc mừng, bạn đã PASS!', msg);
						} else {
							$Core.swal.error('Rất tiếc, bạn FAIL!', msg);
						}
					} else {
						alert('Kết quả: ' + d.score + '% - QS: ' + d.qs);
					}
					
					if(d.url) setTimeout(function() {literal}{ location.href = d.url; }{/literal}, 2000);
				} else {
					$('#qt_submit_btn').prop('disabled', false).html("<i class='bx bx-send me-2'></i> Nộp bài");
					if(typeof $Core.swal !== 'undefined') $Core.swal.error('Có lỗi xảy ra', 'Vui lòng thử lại.');
				}
			}).fail(function() {
				$('#qt_submit_btn').prop('disabled', false).html("<i class='bx bx-send me-2'></i> Nộp bài");
				alert("Lỗi kết nối mạng.");
			});
		};

		if(answered < total && remaining > 0) {
			if(confirm('Bạn mới trả lời ' + answered + '/' + total + ' câu. Bạn có chắc chắn muốn nộp bài?')) {
				doSubmit();
			}
		} else {
			doSubmit();
		}
	});
});
</script>
