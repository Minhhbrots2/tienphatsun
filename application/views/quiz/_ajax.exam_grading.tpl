<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable modal-ipad-xl {if $deviceType eq 'phone'}p-0{/if}" {if $deviceType eq 'phone'}style="padding-left:0 !important"{/if}>
		<form method="POST" enctype="multipart/form-data" class="{if $deviceType eq 'phone'}mt-0{/if}">
			<div class="modal-content {if $deviceType eq 'phone'}m-0{/if}">
				<div class="modal-header d-flex align-items-center justify-content-between position-sticky top-0">
					<button class="btn btn-icon btn-outline-default {if $deviceType eq 'phone'}btn-sm{/if}" data-bs-dismiss="modal" aria-label="Close" type="button"><i class='bx bx-x' ></i></button>
					{if $deviceType ne 'phone'}
						<span class="fs-5 text-info title_pop">Kết quả bài kiểm tra</span>
					{else}
						<span class="fs-5 text-info">Kết quả bài kiểm tra</span>
					{/if}
					<div class="d-inline-flex align-items-center gap-3" style="top:70px">
						<div class="d-flex align-items-center gap-1">
							{if $deviceType eq 'phone'}
								<button class="btn btn-primary btn-sm btn_graded {if empty($answersResult.is_scored)}d-none{/if} disabled" type="button">Đã chấm điểm</button>
								<button class="btn btn-primary btn-sm btn_grading {if !empty($answersResult.is_scored)}d-none{/if}" type="button" onClick="$Core.quiz.sendGrading(this,event)" data-type="publish">Xác nhận điểm</button>
							{else}
								<button class="btn btn-primary btn-lg btn_graded {if empty($answersResult.is_scored)}d-none{/if} disabled" type="button">Đã chấm điểm</button>
								<button class="btn btn-primary btn-lg btn_grading {if !empty($answersResult.is_scored)}d-none{/if}" type="button" onClick="$Core.quiz.sendGrading(this,event)" data-type="publish">Xác nhận điểm</button>
							{/if}							
						</div>
					</div>
				</div>
				<div class="modal-body modal-body-scrollable {if $deviceType eq 'phone'}p-0{/if}">						
					<div class="col-md-8 col-lg-6 mx-auto border {if $deviceType ne 'phone'}rounded-1{/if}">
						<div class="card rounded-1">
							<div class="card-header bg-lighter pb-0">
								<div class="border-bottom d-flex flex-column gap-1 pb-2">
									<h1 class="fs-4 text-dark">{$oneItem.title}</h1>
									<span class="">ID: #{$oneItem.quiz_id}</span>
								</div>
								<div class="border-bottom d-flex justify-content-between py-3 {if $deviceType eq 'phone'}flex-column gap-2{else}align-items-center gap-1{/if}">
									<div class="flex-flow text-dark fs-14">Câu hỏi: <span class="text-muted">{$total_question} câu</span></div>
									<div class="flex-flow text-dark fs-14">Thời gian: {if empty($more_information.is_duration)}<span class="text-muted">Không giới hạn</span>{else}<span class="text-muted">{$oneItem.duration} phút</span>{/if}</div>
									<div class="d-flex flex-flow">
										<div class="flex-shrink-0 me-2"><div class="avatar avatar-sm avatar-online">
											<img src="{$clsProfile->getAvatar($profile_id)}" onerror="this.src='{$URL_IMAGES}/avatars/1.png'" class="rounded">
										</div></div>
										<div class="flex-grow-1">
											<span class="fw-semibold d-block">{$oneProfile.full_name}</span>
											<div class="d-flex gap-1 fs-14 align-items-center">
												<span class="text-warning">{$clsProperty->getTitle($oneProfile.role_id)}</span>
											</div>
										</div>
									</div>
								</div>
								{if $deviceType eq 'phone'}
									<div class="d-flex justify-content-between gap-2 flex-column py-3">
										<div class="flex-flow text-dark fs-14">Làm bài: <span class="text-muted">{$time_start}</span></div>
										<div class="flex-flow text-dark fs-14">Nộp bài: 
											{if empty($more_information.is_end_date)}
												<span class="text-muted">Không giới hạn</span>
											{else}
												<span class="text-muted">{$time_end}</span>
											{/if}
										</div>
									</div>
								{else}
									<div class="d-flex justify-content-between gap-1 align-items-center py-3">
										<div class="d-flex flex-flow text-dark fs-14 flex-column align-items-center"><span class="text-muted">{$time_start}</span>Làm bài</div>
										<div class="d-flex flex-flow text-dark fs-14 flex-column align-items-center">
											{if empty($more_information.is_end_date)}
												<span class="text-muted">Không giới hạn</span>
											{else}
												<span class="text-muted">{$time_end}</span>
											{/if}Nộp bài
										</div>
									</div>
								{/if}
								<div class="box_result">
									<div class="d-flex justify-content-between gap-1 align-items-center py-3 border-top">
										<div class="d-flex flex-flow text-dark fs-14 flex-column align-items-center">
											<span class="count_number text-info fs-20"></span> 
											<span class="">Câu đã chọn</span>
										</div>
										<div class="d-flex flex-flow text-dark fs-14 flex-column align-items-center">
											<span class="txt_result_true text-info fs-20">{$total_correct}</span> 
											<span class="">Câu đúng</span>
										</div>
										<div class="d-flex flex-flow text-dark fs-14 flex-column align-items-center">
											<span class="txt_result_score text-info fs-20">{$answersResult.score}</span> 
											<span class="">Điểm</span>
										</div>
									</div>
								</div>
							</div>
							<div class="">
								{if !empty($questions)}
									{assign var=index value=1}
									{foreach from=$questions item=group key=group_id name=i}
										{assign var=lstQuestions value=$group.questions}
										<div class="card-body box_group box_group_{$group_id} mb-4 border-top">
											{if !empty($group.title)}
											<div class="py-4 border-bottom mb-4">
												<h2 class="title_box fs-5 text-dark mb-0 mb-2 flex-fill cursor-pointer">Phần <span class="group_index">{$smarty.foreach.i.iteration}</span>: {$group.title}</h2>
												<div class="">
													{$group.content}
												</div>
											</div>
											{/if}
											{if !empty($lstQuestions)}
												{foreach from=$lstQuestions item=question key=question_id name=k}
													{assign var=lstAnswer value=$question.answer_options}
													<div class="rounded-1 mb-4 item_question item_question_{$question_id}" data-question_id={$question_id} data-question_type="{$question.question_type}">
														<div class="box_question_{$question_id}">
															<div class="mb-2 text-dark fs-16 fw-semibold">Câu <span class="number_question me-3">{$index}:</span> 
																<span class="fw-normal">{$question.title}</span>
															</div>
															{if $question.question_type ne 3}
																<div class="lst_answers mb-3">
																	{foreach from=$lstAnswer key=answer_id item=answer name=j}
																		<div class="{if $deviceType eq 'phone'}p-1{else}p-2{/if} rounded-1 mb-1">
																			<div class="d-flex gap-2 w-100">
																				{if $question.question_type eq 1}
																					{if !empty($answer.result) && !empty($answer.is_true)}
																						<i class='bx bx-check-circle fs-24' style='color:#03b50f'  ></i>
																					{elseif !empty($answer.result) && empty($answer.is_true)}
																						<i class='bx bx-check-circle fs-24' style='color:#ff000052'  ></i>
																					{elseif empty($answer.result) && !empty($answer.is_correct)}
																						<i class='bx bx-circle fs-24' style='color:#03b50f'></i>
																					{else}
																						<i class='bx bx-circle fs-24'></i>
																					{/if}
																					<input class="form-check-input d-none" type="radio" name="result[{$question_id}]" id="question_{$answer_id}_{$uid}" value="{$answer_id}" onChange="$Core.quiz.countResult(this,event)" {if !empty($answer.result)}checked{/if}>
																				{else}
																					{if !empty($answer.result) && !empty($answer.is_true)}
																						<i class='bx bx-check-square fs-24' style='color:#03b50f'  ></i>
																					{elseif !empty($answer.result) && empty($answer.is_true)}
																						<i class='bx bx-check-square fs-24' style='color:#ff000052'  ></i>
																					{elseif empty($answer.result) && !empty($answer.is_correct)}
																						<i class='bx bx-square fs-24' style='color:#03b50f'></i>
																					{else}
																						<i class='bx bx-square fs-24'></i>
																					{/if}
																					<input class="form-check-input d-none" type="checkbox" name="result[{$question_id}][]" id="question_{$answer_id}_{$uid}" value="{$answer_id}" onChange="$Core.quiz.countResult(this,event)" {if !empty($answer.result)}checked{/if}>
																				{/if}																		
																				<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2" for="question_{$answer_id}_{$uid}">
																					<span class="">{$alphabet[$smarty.foreach.j.index]}.</span>
																					<span class="">{$answer.title}</span>
																				</label>
																			</div>
																		</div>
																	{/foreach}
																</div>
																<div class="d-flex justify-content-end">
																	{if !empty($question.is_true)}
																		<span class="bg-lighter btn btn-default txt_score text-success">{$question.score} Đúng</span>
																		<input type="hidden" class="score_corect" name="correct_result[{$question_id}][score]" value="{$question.score}">
																		<input type="hidden" class="result_correct" name="correct_result[{$question_id}][is_true]" value="1">
																	{else}
																		<span class="bg-lighter btn btn-default txt_score text-danger">{$question.score} Sai</span>
																		<input type="hidden" class="score_corect" name="correct_result[{$question_id}][score]" value="0">
																		<input type="hidden" class="result_correct" name="correct_result[{$question_id}][is_true]" value="0">
																	{/if}
																</div>
															{else}
																<div class="lst_answers mb-3">
																	<div class="box_text_answer {if $deviceType eq 'phone'}p-1{else}p-2{/if} border rounded-1">{if !empty($question.result)}{$question.result}{/if}</div>
																	<textarea class="form-control form-field flex-fill d-none" cols="255" rows="3" placeholder="Nhập câu trả lời của bạn" name="result[{$question_id}]" onKeyUp="$Core.quiz.countResult(this,event)">{if !empty($question.result)}{$question.result}{/if}</textarea>
																</div>
																<div class="result_textarea d-flex justify-content-end {if empty($answersResult.is_scored)}d-none{/if}">
																	{if !empty($question.is_true)}
																		<span class="bg-lighter btn btn-default txt_score text-success">{$question.score} Đúng</span>
																	{else}
																		<span class="bg-lighter btn btn-default txt_score text-danger">{$question.score} Sai</span>
																	{/if}
																</div>
																<div class="edit_result_textarea d-flex justify-content-end gap-1 {if !empty($answersResult.is_scored)}d-none{/if}">
																	<div class="w-px-100">
																		<div class="input-group input-group-merge">
																			<input type="text" class="form-control score_corect_{$question_id}" placeholder="0" value="{$question.score}" aria-describedby="basic-addon33">
																			<span class="input-group-text" id="basic-addon33">điểm</span>
																		</div>
																	</div>
																	<div class="d-flex align-items-center">
																		<button class="btn btn-outline-default radius-1 no-radius-right" type="button" onClick="$Core.quiz.setResult(this,event)" question_id="{$question_id}" value="0">Sai</button>
																		<button class="btn btn-outline-primary radius-1 no-radius-left" type="button" onClick="$Core.quiz.setResult(this,event)" question_id="{$question_id}" value="1">Đúng</button>
																		<input type="hidden" class="score_corect" name="correct_result[{$question_id}][score]" value="{if !empty($answersResult.is_scored)}{$question.score}{else}0{/if}">
																		<input type="hidden" class="result_correct" name="correct_result[{$question_id}][is_true]" value="{if !empty($answersResult.is_scored)}{$question.is_true}{else}0{/if}">
																	</div>
																</div>
															{/if}

														</div>
													</div>
													{math equation="x+1" x=$index assign="index"}
												{/foreach}
											{/if}
										</div>		
										
									{/foreach}
								{/if}
							</div>
						</div>
					</div>						
				</div>
			</div>
			<input type="hidden" name="quiz_id" value="{$oneItem.quiz_id}">
			<input type="hidden" name="quiz_answer_id" value="{$quiz_answer_id}">
			<input type="hidden" name="total_correct" value="{$total_correct}">
			<input type="hidden" name="result_score" value="{$result_score}">
		</form>
	</div>
</div>