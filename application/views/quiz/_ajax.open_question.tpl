{if $type eq "_OPEN"}
<div class="modal-dialog modal-dialog-scrollable modal-sm">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center justify-content-between">
				<div class="modal-header__left">
					<h5 class="modal-title">Thêm mới câu hỏi</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
				</div>
			</div>
			<div class="modal-body modal-body-scrollable">	
				{assign var=uid value=$clsISO->getUniqid()}
				<div class="form-group mb-2">
					<div class="form-floating">
						<textarea rows="3" class="form-control required" id="{$uid}" name="title" maxlength="255" placeholder="Nhập nội dung câu hỏi">{$oneItem.title}</textarea>
						<label for="{$uid}">Câu hỏi</label>
					</div>
				</div>	
				{assign var=uid value=$clsISO->getUniqid()}
				<div class="form-group mb-2">
					<div class="form-floating">
						<textarea rows="3" class="form-control" id="{$uid}" name="note" maxlength="255" placeholder="Nhập chú thích">{$oneItem.note}</textarea>
						<label for="{$uid}">Chú thích</label>
					</div>
				</div>
				{assign var=uid value=$clsISO->getUniqid()}
				<div class="form-floating mb-2">
					<select id="{$uid}" name="question_type" class="form-control form-select" onChange="$Core.quiz.checkTrue(this,event)">
						<option value="one">1 đáp án đúng</option>
						<option value="multiple">Nhiều đáp án đúng</option>
						<option value="custom">Câu trả lời khác</option>
					</select>
					<label for="{$uid}">Loại câu hỏi</label>
				</div>
				<hr>
				<div class="form-group mb-2">
					<label class="form-label mb-1">Đáp án</label>
					<div class="answers">
						{assign var=uid value=$clsISO->getUniqid()}
						<div class="input-group mb-2">
							<div class="input-group-text">
								<input class="form-check-input mt-0" type="checkbox" name="answer_options[{$uid}][is_correct]" value="1" aria-label="Chọn nếu là đáp án đúng" onChange="$Core.quiz.checkTrue(this,event)" >
							</div>
							<input type="text" class="form-control required" name="answer_options[{$uid}][title]" aria-label="Câu trả lời 1" placeholder="Câu trả lời 1">
						</div>
						{assign var=uid value=$clsISO->getUniqid()}
						<div class="input-group mb-2">
							<div class="input-group-text">
								<input class="form-check-input mt-0" type="checkbox" name="answer_options[{$uid}][is_correct]" value="1" aria-label="Chọn nếu là đáp án đúng" onChange="$Core.quiz.checkTrue(this,event)" >
							</div>
							<input type="text" class="form-control required" name="answer_options[{$uid}][title]" aria-label="Câu trả lời 2" placeholder="Câu trả lời 2">
						</div>
						{assign var=uid value=$clsISO->getUniqid()}
						<div class="input-group mb-2">
							<div class="input-group-text">
								<input class="form-check-input mt-0" type="checkbox" name="answer_options[{$uid}][is_correct]" value="1" aria-label="Chọn nếu là đáp án đúng" onChange="$Core.quiz.checkTrue(this,event)" >
							</div>
							<input type="text" class="form-control required" name="answer_options[{$uid}][title]" aria-label="Câu trả lời 3" placeholder="Câu trả lời 3">
						</div>
						{assign var=uid value=$clsISO->getUniqid()}
						<div class="input-group mb-2">
							<div class="input-group-text">
								<input class="form-check-input mt-0" type="checkbox" name="answer_options[{$uid}][is_correct]" value="1" aria-label="Chọn nếu là đáp án đúng" onChange="$Core.quiz.checkTrue(this,event)" >
							</div>
							<input type="text" class="form-control required" name="answer_options[{$uid}][title]" aria-label="Câu trả lời 4" placeholder="Câu trả lời 4">
						</div>
				   	</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="group_id" value="{$group_id}">
				<input type="hidden" name="is_group" value="{$is_group}">
				<button type="button" class="btn btn-outline-primary" toId="{$toId}" question_id="{$question_id}" group_id="{$group_id}" onClick="$Core.quiz.addQuestion(this,event)" data-type="_ADD">Lưu lại</button>
			</div>
		</div>
	</form>
</div>
{else}
	<div class="item_question border mt-2 position-relative">
		<div class="d-inline-flex align-items-center position-absolute top-0 right-0">
			<button class="btn btn-icon btn-sm btn-outline-none" type="button" title="Thu lại" data-bs-toggle="collapse" data-bs-target="#answer_{$question_id}" aria-expanded="false" aria-controls="answer_{$question_id}"><i class='bx bx-minus-circle' ></i></button>
			<button class="btn btn-icon btn-sm btn-outline-none" type="button" data-bs-toggle="tooltip" title="Xóa câu hỏi" onClick="$Core.quiz.removeQuestion(this,event)"><i class='bx bx-x-circle' ></i></button>
		</div>
		{if !empty($group_id)}
			<input type="hidden" name="groups[{$group_id}][questions][{$question_id}]" value='{$question}'>
		{else}
			<input type="hidden" name="questions[{$question_id}]" value='{$question}'>
		{/if}
		<div class="form-group py-1 px-2 bg-lighter">
			<label class="form-label mb-1"><strong>Câu số <span class="number_order">{$number_question}</span>:</strong> {$title}</label>		
		</div>
		<div class="form-group collapse p-2 show" id="answer_{$question_id}">
			<label class="form-label mb-1">Phương án trả lời:</label>
			<ul class="unstyled-list">
				{assign var=index value=1}
				{foreach from=$answer_options item=option name=i}
					<li>Đáp án {$index}: {$option.title} {if !empty($option.is_correct)}(là đáp án đúng){/if}</li>
					{math equation="x+1" x=$index assign="index"}
				{/foreach}
				{if $question_type eq "custom"}
					<li>Đáp án {$index}: nhập câu trả lời</li>
				{/if}
			</ul>	
			{if !empty($note)}
			<label class="form-label mb-1"><strong>Lưu ý: </strong> <em class="">{$note}</em></label>
			{/if}
		</div>
	</div>
{/if}