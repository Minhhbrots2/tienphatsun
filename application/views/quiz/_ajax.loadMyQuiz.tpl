<div class="row">

	{if !empty($lstQuiz)}

		{foreach from=$lstQuiz item=_oItem}

			{assign var=more_information value=$_oItem.more_information}

			<div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-4">

				<div class="card rounded-1 item_quiz">

					<div class="card-header mb-3 border-bottom d-flex justify-content-between align-items-center gap-2">

						<h3 class="card-title mb-0 text-dark limit_1line {if $deviceType eq 'phone'}fs-4{/if}">{$_oItem.title}</h3><div class="dropdown dropstart">

						{if !empty($_oItem.is_view)}

						<div class="btn-group dropstart">

							<button type="button" class="btn p-0 dropdown-toggle btn-icon btn-sm hide-arrow" data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>

							</button>

							<div class="dropdown-menu">

								<a class="dropdown-item" onclick="$Core.quiz.view_quiz(this,event)" action="view" quiz_id="{$_oItem.quiz_id}" href="javascript:void(0);"><i class='bx bx-vision' ></i>Xem bài kiểm tra</a>

							</div>

						</div>

						{/if}

					</div>

					</div>

					<div class="card-body">

						<ul class="list-unstyled mb-4">

							<li class="d-flex mb-2 align-items-center">

								<div class="flex-shrink-0 me-3">

									<span class="avatar-initial rounded bg-label-primary">

										<img src="{$URL_IMAGES}/icons/icon_time_start.svg" alt="" class="" width="24" height="24">

									</span>

								</div>

								<div class="d-flex w-100 align-items-center">

									<h6 class="mb-0 fs-14 text-black">Bắt đầu: {if empty($more_information.is_start_date)}Không giới hạn{else}{$clsISO->formatDate($_oItem.start_date,4)}{/if}</h6>

								</div>

							</li>

							<li class="d-flex mb-2 align-items-center">

								<div class="flex-shrink-0 me-3">

									<span class="avatar-initial rounded bg-label-primary">

										<img src="{$URL_IMAGES}/icons/icon_time_end.svg" alt="" class="" width="24" height="24">

									</span>

								</div>

								<div class="d-flex w-100 align-items-center">

									<h6 class="mb-0 fs-14 text-black">Kết thúc: {if empty($more_information.is_end_date)}Không giới hạn{else}{$clsISO->formatDate($_oItem.end_date,4)}{/if}</h6>

								</div>

							</li>

							<li class="d-flex mb-2 align-items-center">

								<div class="flex-shrink-0 me-3">

									<span class="avatar-initial rounded bg-label-primary">

										<img src="{$URL_IMAGES}/icons/icon_duration.svg" alt="" class="" width="24" height="24">

									</span>

								</div>

								<div class="d-flex w-100 align-items-center">

									<h6 class="mb-0 fs-14 text-black">Thời gian làm bài: {if empty($more_information.is_duration)}Không giới hạn{else}{$_oItem.duration} phút{/if}</h6>

								</div>

							</li>

							<li class="d-flex mb-2 align-items-center">

								<div class="flex-shrink-0 me-3">

									<span class="avatar-initial rounded bg-label-primary">

										<img src="{$URL_IMAGES}/icons/icon_quiz.svg" alt="" class="" width="24" height="24">

									</span>

								</div>

								<div class="d-flex w-100 align-items-center">

									<h6 class="mb-0 fs-14 text-black">Số câu hỏi: {$_oItem.total_question} câu</h6>

								</div>

							</li>

							<li class="d-flex align-items-center">

								<div class="flex-shrink-0 me-3">

									<span class="avatar-initial rounded bg-label-primary">

										<img src="{$URL_IMAGES}/icons/icon_staff.svg" alt="" class="" width="24" height="24">

									</span>

								</div>

								<div class="d-flex w-100 align-items-center justify-content-between">

									<h6 class="mb-0 fs-14 text-black">{$_oItem.total_profile} người</h6>

									{if !empty($_oItem.quiz_time_start)}

										<span class="text-main time_countdown" data-time="{$_oItem.quiz_time_start}"></span>

									{/if}

								</div>

							</li>

						</ul>

						<div class="d-flex align-items-center">

							{if !empty($_oItem.is_quiz)}

								<button class="btn {$_oItem.class_button} text-white {if $deviceType ne 'phone'}btn-lg{/if} rounded-1 w-100" type="button" action="quiz" onClick="$Core.quiz.begin_test(this,event)" quiz_id="{$_oItem.quiz_id}">{$_oItem.status_text}</button>

							{else}

								<button class="btn {$_oItem.class_button} text-white {if $deviceType ne 'phone'}btn-lg{/if} rounded-1 w-100 {if !empty($_oItem.quiz_time_start)}btn_not_started{/if}" action="quiz" quiz_id="{$_oItem.quiz_id}" type="button" disabled>{$_oItem.status_text}</button>

							{/if}

						</div>

					</div>

				</div>

			</div>

		{/foreach}

	{else}

		<div class="p-2 text-center d-flex justify-content-center flex-column align-items-center w-100 bg-white">

			<img src="{$URL_IMAGES}/listing-empty.svg" width="200" height="200">

			<p>Không có bài kiểm tra nào</p>

		</div>

	{/if}

</div>