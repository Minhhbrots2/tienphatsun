{$scriptJs}

<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">

	<div class="d-flex flex-wrap justify-content-between align-items-center py-2">

		<div class="p__left">

			<h4 class="fw-bold mb-1">Trắc nghiệm</h4>

			<span class="text-muted">Các bài trắc nghiệm tại FH</span>

		</div>

		<div class="p__right d-flex">			

			{if $permiss_add eq 1}

			<div class="d-flex justify-content-end">

				{if $deviceType eq 'phone'}

					<a href="javascript:void(0)" onClick="$Core.quiz.open_quiz(this,event)" data-type="open" data-quiz_id="0" class="btn btn-icon btn-outline-primary mr-2"><i class='bx bx-plus'></i></a>

				{else}

					<a href="javascript:void(0)" onClick="$Core.quiz.open_quiz(this,event)" data-type="open" data-quiz_id="0" class="btn btn-outline-primary mr-2">Thêm mới </a>

				{/if}

			</div>	

			{/if}

		</div>

	</div>

	<div class="col-md-8 col-lg-6 mx-auto">

		<form action="">

			<div class="card mb-4 rounded-1">

				<div class="card-header border-bottom d-flex align-items-center justify-content-between">

					<h2 class="title_box fs-5 text-dark mb-0">Thông tin chung</h2>

					<button class="btn btn-outline-none btn-icon" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1" type="button"><i class='bx bx-chevron-down fs-4' ></i></button>

				</div>

				<div class="card-body pt-3" id="collapse1">

					<div class="d-flex gap-4 align-items-center mb-4">

						<img src="{$URL_IMAGES}/icons/icon_title.svg" alt="" width="25" height="25">

						<input type="text" class="form-control form-control-lg no-focus required form-field flex-fill rounded-0 border-0 border-bottom" name="title" placeholder="Tên bài trắc nghiệm" value="{$oneItem.title}">

					</div>

					<div class="d-flex gap-4 align-items-start mb-4">

						<img src="{$URL_IMAGES}/icons/icon_content.svg" alt="" width="25" height="25">

						<textarea class="form-control no-focus form-field flex-fill" cols="255" rows="6" placeholder="Mô tả ngắn gọn" name="content" data-field="content" id="{$clsISO->getUniqid()}">{$oneItem.content}</textarea>

					</div>

					<div class="d-flex gap-4 align-items-start mb-4">

						<img src="{$URL_IMAGES}/icons/icon_image.svg" alt="" width="25" height="25">

						<div class="we-filedrop-wrapper mb-4 flex-fill">

							<input id="selectFile_{$uid}" onChange="$Core.quiz.file_upload(this, event)" class="d-none" accept="image/*" type="file" tabindex="-1">

							<div class="we-filedrop mb-1" toId="selectFile_{$uid}" onclick="$Core.upload.file_explorer(this,event);"> 

								<svg class="mb-2" width="70" height="70" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M118.42 75.84C118.43 83.2392 116.894 90.5589 113.91 97.33H16.09C12.8944 90.0546 11.3622 82.1579 11.6049 74.2154C11.8477 66.2728 13.8593 58.4844 17.4932 51.4177C21.1271 44.3511 26.2918 38.1841 32.6109 33.3662C38.93 28.5483 46.2443 25.2008 54.0209 23.5676C61.7976 21.9345 69.8406 22.0568 77.564 23.9257C85.2873 25.7946 92.4965 29.363 98.6661 34.3709C104.836 39.3787 109.81 45.6999 113.228 52.8739C116.645 60.0478 118.419 67.8937 118.42 75.84Z" fill="#F2F2F2"></path><path d="M5.54 97.33H126.37" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M97 97.33H49.91V34.65C49.91 34.3848 50.0154 34.1305 50.2029 33.9429C50.3904 33.7554 50.6448 33.65 50.91 33.65H84.18C84.6167 33.6541 85.0483 33.7445 85.4499 33.9162C85.8515 34.0878 86.2152 34.3372 86.52 34.65L96.02 44.15C96.3321 44.4533 96.5811 44.8153 96.7527 45.2151C96.9243 45.615 97.0152 46.0449 97.02 46.48L97 97.33Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" fill="white"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M88.97 52.42H77.33V40.77L88.97 52.42Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M27.32 65.49V70.6" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M29.88 68.04H24.76" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M110.49 32.5601V39.9901" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M114.2 36.27H106.77" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M34.07 14.58V25.59" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M39.57 20.08H28.57" stroke="#D7D7D7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M67 115.86V67.12" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" fill="white"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" stroke="#63666A" stroke-width="1" stroke-miterlimit="10"></path></svg> 

								<p class="mb-0">Bấm để chọn hình ảnh cần tải lên !!!</p>

							</div>

							<div id="imageList" class="d-flex flex-wrap box-done-img">

								{if !empty($oneItem.banner)}

									<span class="d-block w-100">

										<img class="w-100" height="200" src="{$oneItem.banner}" />

										<input type="hidden" name="banner" value="{$oneItem.banner}" />

										<a class="delete" src="{$oneItem.banner}" onClick="$Core.upload.delete(this, event)"></a>

									</span>

								{/if}

							</div>

						</div>

					</div>	

					<h3 class="mb-4 fs-4 text-dark">Thiết lập đối tượng làm bài</h3>					

					<div class="d-flex gap-4 align-items-start mb-4">

						<div class="form-check form-switch form-check-reverse mb-0">

							<input class="form-check-input" type="checkbox" name="is_all_staff" value="1" checked>

					  	</div>

						<div class="d-flex flex-column w-100">

							<h4 class="mb-2 text-dark fs-16">Công khai</h4>

							<div class="">Mọi người có link này đều có thể làm bài kiểm tra</div>

							<div class="w-100 d-flex align-items-center justify-content-between">

								<a href="/trac-nghiem/abc.html" class="">{$smarty.const.DOMAIN_URL}/trac-nghiem/abc.html</a>

								<button class="btn btn-outline-primary"><i class='bx bx-copy' style='color:#404080' ></i>Copy</button>

							</div>

						</div>

					</div>

					<h3 class="mb-4 fs-4 text-dark">Cài đặt thời gian</h3>

					<div class="form-group form-row">	

						<div class="col-12 col-md-6">

							<div class="d-flex gap-4 align-items-start mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="is_duration" value="1" checked>

								</div>

								<div class="d-flex flex-column w-100">

									<h4 class="mb-2 text-dark fs-16">Thời gian làm bài</h4>

									<div class="">Thời gian tối đa làm bài kiểm tra khi bắt đầu làm</div>

								</div>

							</div>

						</div>

						<div class="col-12 col-md-6">

							<div class="input-group input-group-merge w-px-150 ">

								<input type="text" class="form-control form-control-lg no-focus required numberonly" name="duration" value="0" placeholder="0" aria-label="0" aria-describedby="duration_{$uid}">

								<span class="input-group-text" id="duration_{$uid}">phút</span>

						  	</div>

						</div>

					</div>

					<div class="form-group form-row">	

						<div class="col-12 col-md-6">

							<div class="d-flex gap-4 align-items-start mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="is_start_date" value="1" checked>

								</div>

								<div class="d-flex flex-column w-100">

									<h4 class="mb-2 text-dark fs-16">Thời gian bắt đầu làm bài</h4>

									<div class="">Quy định thời gian người dùng có thể bắt đầu làm bài kiểm tra, người dùng không thể bắt đầu làm bài trước mốc thời gian này</div>

								</div>

							</div>

						</div>

						<div class="col-12 col-md-6">

							<div class="input-group w-px-200 ">

								<input class="form-control form-control-lg no-focus form-field w-100 required" type="datetime-local"  name="start_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{$oneItem.start_date}"/>

						  	</div>

						</div>

					</div>

					<div class="form-group form-row">	

						<div class="col-12 col-md-6">

							<div class="d-flex gap-4 align-items-start mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="is_end_date" value="1" checked>

								</div>

								<div class="d-flex flex-column w-100">

									<h4 class="mb-2 text-dark fs-16">Thời gian kết thúc làm bài</h4>

									<div class="">Quy định kết thúc hiệu lực làm bài kiểm tra, người dùng sẽ không thể làm bài khi đến mốc thời gian này hoặc nếu đang làm bài thì bài kiểm tra sẽ dừng và tự động nộp bài.</div>

								</div>

							</div>

						</div>

						<div class="col-12 col-md-6">

							<div class="input-group w-px-200 ">

								<input class="form-control form-control-lg no-focus form-field w-100 required" type="datetime-local"  name="end_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{$oneItem.end_date}"/>

						  	</div>

						</div>

					</div>

					<div class="form-group form-row">	

						<div class="col-12 col-md-6">

							<div class="d-flex align-items-center gap-2 ">

								<h4 class="mb-2 text-dark fs-16">Điểm tối đa cho bài kiểm tra</h4>

								<div class="input-group w-px-100">

									<input class="form-control form-control-lg no-focus form-field w-100 numberonly required" type="text"  name="score" value="{if !empty($oneItem.score)}{$oneItem.score}{else}10{/if}"/>

								</div>

						  	</div>

						</div>

						<div class="col-12 col-md-6">

							<div class="d-flex gap-4 align-items-center mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="is_split_points" value="1" checked>

								</div>

								<div class="d-flex flex-column w-100">

									<h4 class="mb-2 text-dark fs-16">Hệ thống sẽ tự động chia điểm</h4>

									<div class="">Tính năng tự động chia đều điểm cho các câu hỏi</div>

								</div>

							</div>

						</div>

					</div>

					

					<div class="form-group form-row">	

						<div class="col-12 col-md-6">

							<div class="d-flex gap-4 align-items-start mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="submission_count" value="1">

								</div>

								<div class="d-flex flex-column w-100">

									<h4 class="mb-2 text-dark fs-16">Cho phép sửa bài</h4>

									<div class="">Sau khi nộp bài xong mà vẫn trong thời gian làm bài quy định thì có thể sửa lại đáp áp.</div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="body_content">

				<div class="box_group box_group_{$group_id}" data-group_id="{$group_id}" onClick="$Core.quiz.updateGroupFocus(this,event)">

					<div class="card rounded-1 mb-4" data-group_id="{$group_id}">

						<div class=" border-bottom d-flex align-items-center justify-content-between bg-lighter ">

							<h2 class="card-header title_box fs-5 text-dark mb-0 py-4 flex-fill collapsed cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapse_group_{$group_id}" aria-expanded="false" aria-controls="collapse_group_{$group_id}">Phần <span class="group_index">1</span></h2>

							<div class="dropdown dropstart">

								<button type="button" class="btn p-0 dropdown-toggle btn-icon hide-arrow" data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>

								</button>

								<div class="dropdown-menu">

									<a class="dropdown-item" onclick="$Core.quiz.removeGroupQuestion(this,event)" billing_id="1064" href="javascript:void(0);"><i class="bx bx-bullseye me-1 fs-3"></i> Xóa phần</a>

								</div>

							</div>

						</div>

						<div class="card-body collapse pt-3" id="collapse_group_{$group_id}">

							<div class="form-group mb-3">

								<input type="text" class="form-control form-control-lg no-focus required form-field" name="group[{$group_id}][title]" placeholder="Nhập tiêu đề phần" value="">

							</div>

							<div class="form-group">

								<textarea class="form-control no-focus form-field flex-fill" cols="255" rows="3" placeholder="Nhập mô tả" name="group[{$group_id}][content]" id="{$clsISO->getUniqid()}">{$oneItem.content}</textarea>

							</div>

						</div>

					</div>

					<div class="card rounded-1 mb-4 item_question item_question_{$question_id}" data-question_id={$question_id}>

						<div class="card-body box_question_{$id}">

							<div class="mb-2 text-dark fs-16 fw-semibold">Câu <span class="number_question me-3">1:</span> 

								<span class="fw-normal">câu hỏi số 1</span>

							</div>

							<div class="lst_answers mb-3">

								<div class="form-check form-check-inline mt-2 w-100">

									<input class="form-check-input" type="radio" name="group[{$group_id}][question][{$id}]" id="inlineRadio1" value="1">

									<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2" for="inlineRadio1">

										<span class="">A.</span>

										<span class="">Đáp án A</span>

									</label>

								</div>

								<div class="form-check form-check-inline mt-2 w-100">

									<input class="form-check-input" type="radio" name="group[{$group_id}][question][{$id}]" id="inlineRadio1" value="1">

									<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2" for="inlineRadio1">

										<span class="">B.</span>

										<span class="">Đáp án B</span>

									</label>

								</div>

								<div class="form-check form-check-inline mt-2 w-100">

									<input class="form-check-input" type="radio" name="group[{$group_id}][question][{$id}]" id="inlineRadio1" value="1">

									<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2" for="inlineRadio1">

										<span class="">C.</span>

										<span class="">Đáp án C</span>

									</label>

								</div>

								<div class="form-check form-check-inline mt-2 w-100">

									<input class="form-check-input" type="radio" name="group[{$group_id}][question][{$id}]" id="inlineRadio1" value="1">

									<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2" for="inlineRadio1">

										<span class="">D.</span>

										<span class="">Đáp án D</span>

									</label>

								</div>

							</div>

							<div class="w-px-80">

								<fieldset class="rounded-1 pb-2">

									<legend class="fs-14 px-1 bg-white mb-0">Điểm</legend>

									<span class="px-2">5</span>

								</fieldset>

							</div>



						</div>

					</div>

					<div class="card rounded-1 mb-4 item_question">

						<div class="card-body box_question_{$id}">

							<div class="mb-2 text-dark fs-16 fw-semibold">Câu <span class="number_question me-3">2:</span> 

								<span class="fw-normal">câu hỏi số 2</span>

							</div>

							<div class="lst_answers mb-3">

								<div class="form-check form-check-inline mt-2 w-100">

									<input class="form-check-input" type="checkbox" name="group[{$group_id}][question][{$id}]" id="inlineRadio1" value="option1">

									<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2" for="inlineRadio1">

										<span class="">A.</span>

										<span class="">Đáp án A</span>

									</label>

								</div>

								<div class="form-check form-check-inline mt-2 w-100">

									<input class="form-check-input" type="checkbox" name="group[{$group_id}][question][{$id}]" id="inlineRadio1" value="option1">

									<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2" for="inlineRadio1">

										<span class="">B.</span>

										<span class="">Đáp án B</span>

									</label>

								</div>

								<div class="form-check form-check-inline mt-2 w-100">

									<input class="form-check-input" type="checkbox" name="group[{$group_id}][question][{$id}]" id="inlineRadio1" value="option1">

									<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2" for="inlineRadio1">

										<span class="">C.</span>

										<span class="">Đáp án C</span>

									</label>

								</div>

								<div class="form-check form-check-inline mt-2 w-100">

									<input class="form-check-input" type="checkbox" name="group[{$group_id}][question][{$id}]" id="inlineRadio1" value="option1">

									<label class="form-check-label d-flex gap-2 w-100 fs-16 text-dark pl-2" for="inlineRadio1">

										<span class="">D.</span>

										<span class="">Đáp án D</span>

									</label>

								</div>

							</div>

							<div class="w-px-80">

								<fieldset class="rounded-1 pb-2">

									<legend class="fs-14 px-1 bg-white mb-0">Điểm</legend>

									<span class="px-2">5</span>

								</fieldset>

							</div>



						</div>

					</div>

					<div class="card rounded-1 mb-4 item_question">

						<div class="card-body box_question_{$id}">

							<div class="mb-2 text-dark fs-16 fw-semibold">Câu <span class="number_question me-3">3:</span> 

								<span class="fw-normal">câu hỏi số 3</span>

							</div>

							<div class="lst_answers mb-3">

								<textarea class="form-control form-field flex-fill" cols="255" rows="3" placeholder="Nhập câu trả lời của bạn" name="group[{$group_id}][question][custom]"></textarea>

							</div>

							<div class="w-px-80">

								<fieldset class="rounded-1 pb-2">

									<legend class="fs-14 px-1 bg-white mb-0">Điểm</legend>

									<span class="px-2">5</span>

								</fieldset>

							</div>



						</div>

					</div>				

					<div class="card rounded-1 mb-4 item_question" data-question_id="{$question_id}">

						<div class="card-body box_question_{$id} item_answers">

							<div class="form-row d-flex align-items-center mb-3">

								<div class="col-9">

									<input type="text" class="form-control form-control-lg no-focus required form-field flex-fill rounded-0 border-0 border-bottom" name="question[{$question_id}][title]" placeholder="Nhập nội dung câu hỏi" value="{$oneItem.title}">

								</div>

								<div class="col-3">

									<div class="dropdown bootstrap-select show-tick w-100 dropup">

										<select class="form-control form-control-lg no-focus w-100 question_type" name="question[{$question_id}][question_type]" id="selectpickerIcons" data-icon-base="icon-base bx" data-tick-icon="bx-check" data-style="btn-default" tabindex="null">

											<option value="1" data-icon="icon-base  mb-50"><i class="bx bx-radio-circle-marked"></i>Chọn 1 đáp án</option>

											<option value="2" data-icon="icon-base bx bxs-check-square mb-50">Chọn nhiều đáp án</option>

											<option value="3" data-icon="icon-base bx bx-signal-3 bx-rotate-90 mb-50">Văn bản</option>

										</select>

									</div>

								</div>

							</div>

							<div class="lst_answers mb-3">

								<div class="item_answers mb-2">

									<div class="form-row">

										<div class="col-9">

											<div class="d-flex align-items-center">

												<div class="form-check form-check-inline mt-2">

													<input class="form-check-input" type="radio" name="question[{$question_id}][answer_options][{$answer_id}][is_correct]" id="inlineRadio1" value="1">

												</div>

												<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea" name="question[{$question_id}][answer_options][{$answer_id}][title]" placeholder="Câu trả lời..."  rows="1"></textarea>

											</div>

										</div>

										<div class="col-3">

											<div class="d-flex align-items-center justify-content-end">

												<div class="form-check form-switch form-check-reverse mb-0">

													<input class="form-check-input" type="checkbox" name="question[{$question_id}][is_correct]" value="1">

												</div>

												<button class="btn btn-icon btn-outline-none" type="button"><i class='bx bx-trash fs-24'></i></button>

											</div>

										</div>

									</div>

								</div>

								<div class="item_answers mb-2">

									<div class="form-row">

										<div class="col-9">

											<div class="d-flex align-items-center">

												<div class="form-check form-check-inline mt-2">

													<input class="form-check-input" type="checkbox" name="question[{$question_id}][answer_options][{$answer_id}][is_correct]" id="inlineRadio1" value="1">

												</div>

												<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea" name="question[{$question_id}][answer_options][{$answer_id}][title]" placeholder="Câu trả lời..."  rows="1"></textarea>

											</div>

										</div>

										<div class="col-3">

											<div class="d-flex align-items-center justify-content-end">

												<div class="form-check form-switch form-check-reverse mb-0">

													<input class="form-check-input" type="checkbox" name="question[{$question_id}][is_correct]" value="1">

												</div>

												<button class="btn btn-icon btn-outline-none" type="button"><i class='bx bx-trash fs-24'></i></button>

											</div>

										</div>

									</div>

								</div>

								<div class="item_answers mb-2">

									<div class="form-row">

										<div class="col-9">

											<textarea class="form-control form-field flex-fill" cols="255" rows="3" placeholder="Trả lời" name="question[{$question_id}][custom]" disabled></textarea>

										</div>

										<div class="col-3">

											<div class="d-flex align-items-center justify-content-end">

												<div class="form-check form-switch form-check-reverse mb-0">

													<input class="form-check-input" type="checkbox" name="question[{$question_id}][is_correct]" value="1">

												</div>

												<button class="btn btn-icon btn-outline-none" type="button"><i class='bx bx-trash fs-24'></i></button>

											</div>

										</div>

									</div>

								</div>

							</div>

							<button class="btn btn-outline-info" type="button" onClick="$Core.quiz.addAnswer(this,event)">Thêm câu trả lời<i class='bx bx-plus ml-1'></i></button>

							<div class="w-px-80 mt-3">

								<fieldset class="rounded-1 pb-2">

									<legend class="fs-14 px-1 bg-white mb-0">Điểm</legend>

									<input type="text" name="question[{$question_id}][score]" value="" class="w-100 border-0 form-control no-focus py-0">

								</fieldset>

							</div>



						</div>

					</div>

				</div>

			</div>

			<div class="d-inline-flex position-sticky bottom-0 right-0 justify-content-end py-2 px-3 card float-end rounded-1 flex-row gap-2">

				<button class="btn btn-icon btn-lg rounded-1 btn-outline-none d-flex flex-column" onClick="$Core.quiz.addGroupQuestion(this,event)">

					<i class='bx bx-qr fs-24'></i>

					<span class="text-dark fs-11">Phần</span>

				</button>

				<button class="btn btn-outline-primary btn-lg rounded-1" type="button" onClick="$Core.quiz.addQuestion(this,event)"><i class='bx bx-plus me-1'></i>Thêm câu hỏi</button>

			</div>

			<input type="hidden" name="group_focus" value="{$group_id}">

		</form>

	</div>

</div>

{literal}

<style type="text/css">

	.ui-datepicker{ z-index:9999 !important;}

</style>

<script type="text/javascript">

	$(function(){

//		$Core.quiz.list({}, true);

	});

</script>

{/literal}