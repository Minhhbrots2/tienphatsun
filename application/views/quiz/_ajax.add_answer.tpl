{if $question_type eq '2'}
	<div class="item_answers mb-2">
		<div class="form-row">
			<div class="col-9">
				<div class="d-flex">
					<div class="form-check form-check-inline mt-2">
						<input class="form-check-input input_check_type" type="checkbox" name="{$answer_id}" value="1" onChange="$Core.quiz.setCorrect(this,event)">
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
{else if $question_type eq '3'}
	<div class="item_answers mb-2">
		<div class="form-row">
			<div class="col-9">
				<textarea class="form-control form-field flex-fill" cols="255" rows="3" placeholder="Trả lời" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][custom]" disabled></textarea>
			</div>
			<div class="col-3">
			</div>
		</div>
	</div>
{else}
	<div class="item_answers mb-2">
		<div class="form-row">
			<div class="col-9">
				<div class="d-flex">
					<div class="form-check form-check-inline mt-2">
						<input class="form-check-input input_check_type" type="radio" name="{$answer_id}" value="1" onChange="$Core.quiz.setCorrect(this,event)">
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
{/if}