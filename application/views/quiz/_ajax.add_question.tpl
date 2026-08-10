{if !empty($is_export)}
	{if !empty($questions)}
		{assign var=group_id value=$clsISO->getUniqid()}
		<div class="box_group box_group_{$group_id}" data-group_id="{$group_id}" onClick="$Core.quiz.updateGroupFocus(this,event)">				
			{if !empty($questions)}
				<div class="card rounded-1 mb-4" data-group_id="{$group_id}">
					<div class=" border-bottom d-flex align-items-center justify-content-between bg-lighter ">
						<h2 class="card-header title_box fs-5 text-dark mb-0 py-4 flex-fill cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapse_group_{$group_id}" aria-expanded="false" aria-controls="collapse_group_{$group_id}">Phần <span class="group_index">1</span></h2>
						<div class="dropdown dropstart">
							<button type="button" class="btn p-0 dropdown-toggle btn-icon hide-arrow" data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" onclick="$Core.quiz.removeGroupQuestion(this,event)" billing_id="1064" href="javascript:void(0);"><i class="bx bx-trash fs-3 me-1"></i> Xóa phần</a>
							</div>
						</div>
					</div>
					<div class="card-body collapse pt-3 show" id="collapse_group_{$group_id}">
						<div class="form-group mb-3">
							<input type="text" class="form-control form-control-lg no-focus form-field" name="groups[{$group_id}][title]" placeholder="Nhập tiêu đề phần" value="">
						</div>
						<div class="form-group">
							<textarea class="form-control no-focus form-field flex-fill" cols="255" rows="3" placeholder="Nhập mô tả" name="groups[{$group_id}][content]" id="{$clsISO->getUniqid()}"></textarea>
						</div>
					</div>
				</div>
				{foreach from=$questions item=_oQuestion key=question_id}
					{assign var=answer_options value=$_oQuestion.answer_options}
					<div class="card rounded-1 mb-4 item_question" data-question_id="{$question_id}">
						<div class="card-body box_question_{$question_id}">
							<h4 class="mb-2 text-danger error_message d-none"></h4>
							{if $deviceType eq 'phone'}
								<div class="d-flex align-items-center mb-3">
									<div class="flex-fill">
										<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][title]" placeholder="Nhập nội dung câu hỏi?"  rows="1">{$_oQuestion.title}</textarea>
									</div>
									<div class="dropdown dropstart">
										<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>
										</button>
										<div class="dropdown-menu">
											<label class="dropdown-item {if $_oQuestion.question_type eq 1}active{/if}" href="javascript:void(0)" for="{$question_id}_1">
												<input type="radio" name="groups[{$group_id}][questions][{$question_id}][question_type]" value="1" id="{$question_id}_1" hidden="hidden" onChange="$Core.quiz.loadTypeAnswer(this,event)" {if $_oQuestion.question_type eq 1}checked{/if}>
												<span>Chọn 1 đáp án</span>
											</label>
											<label class="dropdown-item {if $_oQuestion.question_type eq 2}active{/if}" href="javascript:void(0)" for="{$question_id}_2">
												<input type="radio" name="groups[{$group_id}][questions][{$question_id}][question_type]" value="2" id="{$question_id}_2" hidden="hidden" onChange="$Core.quiz.loadTypeAnswer(this,event)" {if $_oQuestion.question_type eq 2}checked{/if}>
												<span>Chọn nhiều đáp án</span>
											</label>
										</div>
									</div>
								</div>
							{else}					
								<div class="row d-flex align-items-center mb-3">
									<div class="col-9">
										<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][title]" placeholder="Nhập nội dung câu hỏi?"  rows="1">{$_oQuestion.title}</textarea>
									</div>
									<div class="col-3">
										<div class="dropdown bootstrap-select show-tick w-100 dropup">
											<select class="form-select form-control-lg rounded-1 no-focus w-100 question_type" name="groups[{$group_id}][questions][{$question_id}][question_type]" id="selectpickerIcons" data-icon-base="icon-base bx" data-tick-icon="bx-check" data-style="btn-default" tabindex="null" onChange="$Core.quiz.loadTypeAnswer(this,event)">
												<option value="1" data-icon="icon-base  mb-50" {if $_oQuestion.question_type eq 1}selected{/if}><i class="bx bx-radio-circle-marked"></i>Chọn 1 đáp án</option>
												<option value="2" data-icon="icon-base bx bxs-check-square mb-50" {if $_oQuestion.question_type eq 2}selected{/if}>Chọn nhiều đáp án</option>
											</select>
										</div>
									</div>
								</div>
							{/if}
							<div class="lst_answers mb-3">
								{foreach from=$answer_options item=answer key=answer_id}
									<div class="item_answers mb-2 {if $_oQuestion.question_type eq 3}d-none{/if}">
										<div class="row">
											<div class="col-9">
												<div class="d-flex">
													<div class="form-check form-check-inline mt-2">
														<input class="form-check-input input_check_type" type="{if $_oQuestion.question_type eq 2}checkbox{else}radio{/if}" name="{$answer_id}" value="1" onChange="$Core.quiz.setCorrect(this,event)" {if $answer.is_correct eq 1}checked{/if}>
													</div>
													<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][title]" placeholder="Câu trả lời..."  rows="1">{$answer.title}</textarea>
												</div>
											</div>
											<div class="col-3">
												<div class="d-flex justify-content-end h-100">
													<div class="form-check form-switch form-check-reverse mb-0 d-none">
														<input class="form-check-input" type="checkbox" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][is_correct]" value="1" {if $answer.is_correct eq 1}checked{/if} onChange="$Core.quiz.setCorrect(this,event)">
													</div>
													<button class="btn btn-icon btn-outline-none" type="button" onClick="$Core.quiz.removeAnswer(this,event)"><i class='bx bx-trash fs-24'></i></button>
												</div>
											</div>
										</div>
									</div>
								{/foreach}
							</div>
							<button class="btn btn-outline-info btn_addAnswer" type="button" onClick="$Core.quiz.addAnswer(this,event)" question_type="{$_oQuestion.question_type}">Thêm câu trả lời<i class='bx bx-plus ml-1'></i></button>
							<div class="d-flex align-items-end justify-content-between mt-3">
								<div class="w-px-80">
									<fieldset class="rounded-1 pb-2 field_point {if !empty($is_split_points)}point_disabled{/if}">
										<legend class="fs-14 px-1 bg-white mb-0">Điểm</legend>
										<input type="text" name="groups[{$group_id}][questions][{$question_id}][score]" value="" class="w-100 border-0 form-control no-focus py-0 point_question"  >
									</fieldset>
								</div>
								<button class="btn btn-icon btn-outline-none" type="button" title="Xóa câu hỏi" onClick="$Core.quiz.removeQuestion(this,event)"><i class="bx bx-trash fs-24"></i></button>
							</div>

						</div>
					</div>
				{/foreach}
			{/if}
		</div>
	{/if}
{else}
	<div class="card rounded-1 mb-4 item_question" data-question_id="{$question_id}">
		<div class="card-body box_question_{$question_id}">
			<h4 class="mb-2 text-danger error_message d-none"></h4>
			{if $deviceType eq 'phone'}
				<div class="d-flex align-items-center mb-3">
					<div class="flex-fill">
						<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][title]" placeholder="Nhập nội dung câu hỏi?"  rows="1"></textarea>
					</div>
					<div class="dropdown dropstart">
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu">
							<label class="dropdown-item active" href="javascript:void(0)" for="{$question_id}_1">
								<input type="radio" name="groups[{$group_id}][questions][{$question_id}][question_type]" value="1" id="{$question_id}_1" hidden="hidden" onChange="$Core.quiz.loadTypeAnswer(this,event)">
								<span>Chọn 1 đáp án</span>
							</label>
							<label class="dropdown-item" href="javascript:void(0)" for="{$question_id}_2">
								<input type="radio" name="groups[{$group_id}][questions][{$question_id}][question_type]" value="2" id="{$question_id}_2" hidden="hidden" onChange="$Core.quiz.loadTypeAnswer(this,event)">
								<span>Chọn nhiều đáp án</span>
							</label>
						</div>
					</div>
				</div>
				<div class="lst_answers mb-3">
					{foreach from=$answer_options item=answer key=answer_id}
						<div class="item_answers mb-2 d-flex">
							<div class="d-flex flex-fill">
								<div class="form-check form-check-inline mt-2">
									<input class="form-check-input input_check_type" type="radio" name="{$answer_id}" value="1" onChange="$Core.quiz.setCorrect(this,event)" {if $answer.is_correct eq 1}checked{/if}>
								</div>
								<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][title]" placeholder="Câu trả lời..."  rows="1"></textarea>
							</div>
							<div class="d-flex justify-content-end h-100">
								<div class="form-check form-switch form-check-reverse mb-0 d-none">
									<input class="form-check-input" type="checkbox" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][is_correct]" value="1" {if $answer.is_correct eq 1}checked{/if} onChange="$Core.quiz.setCorrect(this,event)">
								</div>
								<button class="btn btn-icon btn-outline-none" type="button" onClick="$Core.quiz.removeAnswer(this,event)"><i class='bx bx-trash fs-24'></i></button>
							</div>
						</div>
					{/foreach}
					<div class="item_answers mb-2 item_answers_custom d-none">
						<div class="form-row">
							<div class="col-9">
								<textarea class="form-control form-field flex-fill" cols="255" rows="3" placeholder="Trả lời" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][custom]" disabled></textarea>
							</div>
							<div class="col-3">
							</div>
						</div>
					</div>
				</div>
			{else}
				<div class="row d-flex align-items-center mb-3">
					<div class="col-9">
						<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][title]" placeholder="Nhập nội dung câu hỏi?"  rows="1"></textarea>
					</div>
					<div class="col-3">
						<div class="dropdown bootstrap-select show-tick w-100 dropup">
							<select class="form-control form-control-lg rounded-1 no-focus w-100 question_type" name="groups[{$group_id}][questions][{$question_id}][question_type]" id="selectpickerIcons" data-icon-base="icon-base bx" data-tick-icon="bx-check" data-style="btn-default" tabindex="null" onChange="$Core.quiz.loadTypeAnswer(this,event)">
								<option value="1" data-icon="icon-base  mb-50" {if $question_type eq 1}selected{/if}><i class="bx bx-radio-circle-marked"></i>Chọn 1 đáp án</option>
								<option value="2" data-icon="icon-base bx bxs-check-square mb-50 {if $question_type eq 2}selected{/if}">Chọn nhiều đáp án</option>
							</select>
						</div>
					</div>
				</div>
				<div class="lst_answers mb-3">
					{foreach from=$answer_options item=answer key=answer_id}
						<div class="item_answers mb-2">
							<div class="row">
								<div class="col-9">
									<div class="d-flex">
										<div class="form-check form-check-inline mt-2">
											<input class="form-check-input input_check_type" type="radio" name="{$answer_id}" value="1" onChange="$Core.quiz.setCorrect(this,event)" {if $answer.is_correct eq 1}checked{/if}>
										</div>
										<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][title]" placeholder="Câu trả lời..."  rows="1"></textarea>
									</div>
								</div>
								<div class="col-3">
									<div class="d-flex justify-content-end h-100">
										<div class="form-check form-switch form-check-reverse mb-0 d-none">
											<input class="form-check-input" type="checkbox" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][is_correct]" value="1" {if $answer.is_correct eq 1}checked{/if} onChange="$Core.quiz.setCorrect(this,event)">
										</div>
										<button class="btn btn-icon btn-outline-none" type="button" onClick="$Core.quiz.removeAnswer(this,event)"><i class='bx bx-trash fs-24'></i></button>
									</div>
								</div>
							</div>
						</div>
					{/foreach}
				</div>
			{/if}
			<button class="btn btn-outline-info btn_addAnswer" type="button" onClick="$Core.quiz.addAnswer(this,event)" question_type="1">Thêm câu trả lời<i class='bx bx-plus ml-1'></i></button>
			<div class="d-flex align-items-end justify-content-between mt-3">
				<div class="w-px-80">
					<fieldset class="rounded-1 pb-2 field_point {if !empty($is_split_points)}point_disabled{/if}">
						<legend class="fs-14 px-1 bg-white mb-0">Điểm</legend>
						<input type="text" name="groups[{$group_id}][questions][{$question_id}][score]" value="" class="w-100 border-0 form-control no-focus py-0 point_question"  >
					</fieldset>
				</div>
				<button class="btn btn-icon btn-outline-none" type="button" title="Xóa câu hỏi" onClick="$Core.quiz.removeQuestion(this,event)"><i class="bx bx-trash fs-24"></i></button>
			</div>

		</div>
	</div>
{/if}