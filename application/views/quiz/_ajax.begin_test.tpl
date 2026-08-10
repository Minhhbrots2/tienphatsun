<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable modal-ipad-xl {if $deviceType eq 'phone'}p-0{/if}" {if $deviceType eq 'phone'}style="padding-left:0 !important"{/if}>
		<form method="POST" enctype="multipart/form-data" class="">
			<div class="modal-content {if $deviceType eq 'phone'}m-0{/if}">
				<div class="modal-header d-flex align-items-center justify-content-between position-sticky top-0 {if $deviceType eq 'phone'}flex-wrap gap-2{/if}">
					<button class="btn btn-icon {if $deviceType eq 'phone'}btn-sm{/if} btn-outline-default" onclick="$Core.quiz.sendResult(this,event)" data-type="draft" aria-label="Close" type="button"><i class='bx bx-x' ></i></button>
					<div class="d-inline-flex align-items-center {if $deviceType eq 'phone'}gap-2{else}gap-3{/if}" style="top:70px">
<!--						<div class="time_countdown text-main fw-bold fs-16" data-time="{$second}"></div>-->
						{if $deviceType eq 'phone'}
							{if !empty($second)}
								<div class="d-flex align-items-center gap-2">
									<img src="{$URL_IMAGES}/icons/clock-icon.svg" width="20" height="20" alt="">
									<span class="fs-6 text-success time_countdown" data-time="{$second}"></span>
								</div>
							{/if}
							<div class="d-flex align-items-center gap-1">
								<img src="{$URL_IMAGES}/icons/icon_quiz.svg" alt="" width="20" height="20">
								<div class="fs-6"><span class="count_number fs-2 text-warning">0</span>/{$total_question}</div>
							</div>
							<button class="btn btn-primary btn-sm btn_send_result" type="button" onClick="$Core.quiz.sendResult(this,event)" data-type="publish">Nộp bài</button>
						{else}
							{if !empty($second)}
								<div class="d-flex align-items-center gap-2 {if $deviceType eq 'phone'}w-100 justify-content-center {else} title_pop{/if}">
									<img src="{$URL_IMAGES}/icons/clock-icon.svg" width="30" height="30" alt="">
									<span class="fs-4 text-success time_countdown" data-time="{$second}"></span>
								</div>
							{/if}
							<div class="d-flex align-items-center gap-1">
								<img src="{$URL_IMAGES}/icons/icon_quiz.svg" alt="" width="32" height="32">
								<div class="fs-5"><span class="count_number fs-2 text-warning">0</span>/{$total_question}</div>
							</div>
							<button class="btn btn-primary btn-lg btn_send_result" type="button" onClick="$Core.quiz.sendResult(this,event)" data-type="publish">Nộp bài</button>
						{/if}
					</div>
					
				</div>
				<div class="modal-body modal-body-scrollable {if $deviceType eq 'phone'}p-0{/if}">	
					{if $oneItem.quiz_type eq 'video' || $oneItem.quiz_type eq 'video' }
						<div class="col-md-8 col-lg-8 col-xxl-6 mx-auto border mb-2 {if $deviceType ne 'phone'}rounded-1{/if}">
							{if $oneItem.quiz_type eq 'video' && !empty($oneItem.link_video)}
								<div class="card rounded-1 overflow-hidden shadow-sm border-0">
									<div class="card-body p-0 bg-dark">
										{$clsQuiz->getEmbedVideo($oneItem.link_video)}
									</div>
									<div class="card-footer bg-white py-2">
										<small class="text-muted"><i class='bx bx-info-circle'></i> Vui lòng xem kỹ video trước khi trả lời câu hỏi bên dưới.</small>
									</div>
								</div>
							{elseif $oneItem.quiz_type eq 'training'}
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
							{/if}
						</div>
					{/if}
					<div class="col-md-8 col-lg-8 col-xxl-6 mx-auto border {if $deviceType ne 'phone'}rounded-1{/if}">
						<div class="card rounded-1">
							<div class="card-header bg-lighter pb-0">
								<div class="border-bottom d-flex flex-column gap-1 pb-2">
									<h1 class="fs-4 text-dark">{$oneItem.title}</h1>
									<span class="">ID: #{$oneItem.quiz_id}</span>
								</div>
								<div class="d-flex justify-content-between py-3 {if $deviceType eq 'phone'}flex-column gap-2{else}align-items-center gap-1{/if}">
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
									<div class="flex-flow text-dark fs-14">Câu hỏi: <span class="text-muted">{$total_question} câu</span></div>
									<div class="flex-flow text-dark fs-14 align-items-center">Làm bài: <span class="text-muted">{$time_start}</span></div>
									<div class="flex-flow text-dark fs-14">Kết thúc: {if empty($more_information.is_duration)}<span class="text-muted">Không giới hạn</span>{else}<span class="text-muted">{$time_end}</span>{/if}</div>
								</div>
								{if $deviceType eq 'phone'}
									<div class="box_result d-none">
										<div class="d-flex justify-content-between flex-column gap-2 py-3 border-top">
											<div class="flex-flow text-dark fs-14 align-items-center">
												<span class="">Câu đã chọn: </span>
												<span class="count_number text-info"></span> 
											</div>
											<div class="flex-flow text-dark fs-14 align-items-center">
												<span class="">Câu đúng: </span>
												<span class="text-info">Chờ chấm điểm</span> 
											</div>
											<div class="flex-flow text-dark fs-14 align-items-center">
												<span class="">Điểm: </span>
												<span class="txt_score text-info">Chờ chấm điểm</span> 
											</div>
										</div>
									</div>
								{else}
									<div class="box_result d-none">
										<div class="d-flex justify-content-between gap-1 align-items-center py-3 border-top">
											<div class="d-flex {if $deviceType eq 'phone'}flex-fill w-50{else}flex-flow{/if} text-dark fs-14 flex-column align-items-center">
												<span class="count_number text-info fs-20"></span> 
												<span class="">Câu đã chọn</span>
											</div>
											<div class="d-flex {if $deviceType eq 'phone'}flex-fill w-50{else}flex-flow{/if} text-dark fs-14 flex-column align-items-center">
												<span class="txt_result_true text-info fs-20">Chờ chấm điểm</span> 
												<span class="">Câu đúng</span>
											</div>
											<div class="d-flex {if $deviceType eq 'phone'}flex-fill w-50{else}flex-flow{/if} text-dark fs-14 flex-column align-items-center">
												<span class="txt_score text-info fs-20">Chờ chấm điểm</span> 
												<span class="">Điểm</span>
											</div>
										</div>
									</div>
								{/if}
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
														<div class="box_question_{$id}">
															<div class="mb-2 text-dark fs-16 fw-semibold">Câu <span class="number_question me-3">{$index}:</span> 
																<span class="fw-normal">{$question.title}</span>
															</div>
															{if $question.question_type ne 3}
																<div class="lst_answers mb-3">
																	{foreach from=$lstAnswer key=answer_id item=answer name=j}
																		<div class="form-check form-check-inline mt-2 w-100">
																			{if $question.question_type eq 1}
																				<input class="form-check-input cursor-pointer" type="radio" name="result[{$question_id}]" id="question_{$answer_id}_{$uid}" value="{$answer_id}" onChange="$Core.quiz.countResult(this,event)" {if !empty($answer.result)}checked{/if}>
																			{else}
																				<input class="form-check-input cursor-pointer" type="checkbox" name="result[{$question_id}][]" id="question_{$answer_id}_{$uid}" value="{$answer_id}" onChange="$Core.quiz.countResult(this,event)" {if !empty($answer.result)}checked{/if}>
																			{/if}																		
																			<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2 cursor-pointer" for="question_{$answer_id}_{$uid}">
																				<span class="">{$alphabet[$smarty.foreach.j.index]}.</span>
																				<span class="">{$answer.title}</span>
																			</label>
																		</div>
																	{/foreach}
																</div>
															{else}
																<div class="lst_answers mb-3">
																	<textarea class="form-control form-field flex-fill" cols="255" rows="3" placeholder="Nhập câu trả lời của bạn" name="result[{$question_id}]" onKeyUp="$Core.quiz.countResult(this,event)">{if !empty($question.result)}{$question.result}{/if}</textarea>
																</div>
															{/if}
															<div class="d-flex justify-content-between align-items-end">
																{if $question.question_type eq 1}
																	<i class="text-warning">Chọn 1 đáp án đúng</i>
																{else if $question.question_type eq 2}
																	<i class="text-warning">Chọn nhiều đáp án đúng</i>
																{else}
																	<i class="text-warning">Điền câu trả lời</i>
																{/if}
																<span class="bg-lighter btn btn-default txt_score">{$question.score} điểm</span>
															</div>

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
			<input type="hidden" name="time_start" value="{$str_time_start}">
			<input type="hidden" name="time_end" value="{$str_time_end}">
		</form>
	</div>
</div>