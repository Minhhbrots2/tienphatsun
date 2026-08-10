{$scriptJs}
<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2">
		<div class="p__left">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-2">
					<li class="breadcrumb-item">
						<h4 class="fw-bold mb-1"><a href="/trac-nghiem-test.html">Bài test</a></h4>
					</li>
					<li class="breadcrumb-item">
						{if $test_id gt 0}<a href="#">Sửa #{$test_id}</a>{else}<a href="#">Tạo mới</a>{/if}
					</li>
				</ol>
			</nav>
		</div>
	</div>
	<form action="" enctype="multipart/form-data" class="d-none">
		<input id="file_upload" type="file" name="upload_excel" value="" accept=".xlsx, .xls" onChange="$Core.quizTest.uploadExcel(this,event)">
	</form>
	<form action="" method="post" id="quizTestForm">
		<input type="hidden" name="test_id" value="{$test_id}">
		<div class="col-md-8 col-lg-6 mx-auto">

			{* ========== CARD 1: Thông tin chung ========== *}
			<div class="card mb-4 rounded-1">
				<div class="card-header border-bottom d-flex align-items-center justify-content-between">
					<h2 class="title_box fs-5 text-dark mb-0">Thông tin chung</h2>
					<button class="btn btn-outline-none btn-icon" data-bs-toggle="collapse" data-bs-target="#collapseInfo" aria-expanded="true" type="button"><i class='bx bx-chevron-down fs-4'></i></button>
				</div>
				<div class="card-body pt-3 collapse show" id="collapseInfo">
					<div class="d-flex gap-4 align-items-center mb-4">
						<i class='bx bx-edit-alt fs-4 text-muted'></i>
						<input type="text" class="form-control form-control-lg no-focus required form-field flex-fill rounded-0 border-0 border-bottom px-0 fs-16" name="title" placeholder="Tên bài test" value="{$oneItem.title}">
					</div>
					<div class="d-flex gap-4 align-items-start mb-4">
						<i class='bx bx-text fs-4 text-muted mt-1'></i>
						<textarea class="form-control no-focus form-field flex-fill fs-16" cols="255" rows="4" placeholder="Mô tả ngắn gọn" name="intro">{$oneItem.intro}</textarea>
					</div>
				</div>
			</div>

			{* ========== CARD 2: Chủ đề (test_type) ========== *}
			<div class="card mb-4 rounded-1">
				<div class="card-header border-bottom d-flex align-items-center justify-content-between">
					<h2 class="title_box fs-5 text-dark mb-0">Chủ đề bài test</h2>
					<button class="btn btn-outline-none btn-icon" data-bs-toggle="collapse" data-bs-target="#collapseType" aria-expanded="true" type="button"><i class='bx bx-chevron-down fs-4'></i></button>
				</div>
				<div class="card-body pt-3 collapse show" id="collapseType">
					{* --- Video --- *}
					<div class="d-flex gap-4 align-items-start mb-4">
						<div class="form-check form-switch form-check-reverse mb-0">
							<input class="form-check-input" type="radio" name="test_type" value="video" {if $oneItem.test_type eq 'video' || empty($oneItem.test_type)}checked{/if} onChange="$Core.quizTest.toggleType(this)">
						</div>
						<div class="d-flex flex-column w-100">
							<h4 class="mb-1 text-dark fs-16">Video</h4>
							<div class="text-muted mb-2">Đăng tải video hoặc dán link video, câu hỏi xoay quanh nội dung video</div>
							<div class="qt-type-panel {if $oneItem.test_type neq 'video' && !empty($oneItem.test_type)}d-none{/if}" id="qt_panel_video">
								<div class="d-flex gap-3 mb-2">
									<div class="form-check"><input class="form-check-input" type="radio" name="video_source" value="link" id="vs_link" {if $oneItem.video_source neq 'upload'}checked{/if} onchange="$Core.quizTest.toggleVideoSource(this)"><label class="form-check-label" for="vs_link">Link</label></div>
									<div class="form-check"><input class="form-check-input" type="radio" name="video_source" value="upload" id="vs_upload" {if $oneItem.video_source eq 'upload'}checked{/if} onchange="$Core.quizTest.toggleVideoSource(this)"><label class="form-check-label" for="vs_upload">Upload</label></div>
								</div>
								<div id="qt_video_link" {if $oneItem.video_source eq 'upload'}style="display:none"{/if}>
									<input type="text" class="form-control" name="video_url" value="{$oneItem.video_url}" placeholder="https://youtube.com/watch?v=...">
								</div>
								<div id="qt_video_upload" {if $oneItem.video_source neq 'upload'}style="display:none"{/if}>
									<input type="file" class="form-control" name="video_file_input" accept="video/*" onchange="$Core.quizTest.uploadVideo(this)">
									<input type="hidden" name="video_file" value="{$oneItem.video_file}">
									{if !empty($oneItem.video_file)}<div class="mt-1"><small class="text-muted">{$oneItem.video_file}</small></div>{/if}
								</div>
							</div>
						</div>
					</div>
					{* --- Training --- *}
					<div class="d-flex gap-4 align-items-start mb-4">
						<div class="form-check form-switch form-check-reverse mb-0">
							<input class="form-check-input" type="radio" name="test_type" value="training" {if $oneItem.test_type eq 'training'}checked{/if} onChange="$Core.quizTest.toggleType(this)">
						</div>
						<div class="d-flex flex-column w-100">
							<h4 class="mb-1 text-dark fs-16">Đào tạo</h4>
							<div class="text-muted mb-2">Chọn bài đào tạo cụ thể, câu hỏi xoay quanh kiến thức về nội dung bài đào tạo đó</div>
							<div class="qt-type-panel {if $oneItem.test_type neq 'training'}d-none{/if}" id="qt_panel_training">
								<select class="from-control form-select iso-select2" data-width="100%" name="training_id">
									<option value="0">-- Chọn bài đào tạo --</option>
									{$trainingOptions}
								</select>
							</div>
						</div>
					</div>
					{* --- Module --- *}
					<div class="d-flex gap-4 align-items-start mb-4">
						<div class="form-check form-switch form-check-reverse mb-0">
							<input class="form-check-input" type="radio" name="test_type" value="module" {if $oneItem.test_type eq 'module'}checked{/if} onChange="$Core.quizTest.toggleType(this)">
						</div>
						<div class="d-flex flex-column w-100">
							<h4 class="mb-1 text-dark fs-16">Module</h4>
							<div class="text-muted mb-2">Đóng gói bài test theo chủ đề module, có thể thêm mới nhanh</div>
							<div class="qt-type-panel {if $oneItem.test_type neq 'module'}d-none{/if}" id="qt_panel_module">
								<div class="d-flex gap-2">
									<select class="from-control form-select iso-select2" data-width="100%" name="category_id" id="qt_category_id">
										<option value="0">-- Chọn danh mục --</option>
										{$categoryOptions}
									</select>
									<button type="button" class="btn btn-outline-primary text-nowrap" onclick="$Core.quizTest.addCategory()"><i class="bx bx-plus"></i> Thêm</button>
								</div>
							</div>
						</div>
					</div>
					{* --- Project --- *}
					<div class="d-flex gap-4 align-items-start mb-4">
						<div class="form-check form-switch form-check-reverse mb-0">
							<input class="form-check-input" type="radio" name="test_type" value="project" {if $oneItem.test_type eq 'project'}checked{/if} onChange="$Core.quizTest.toggleType(this)">
						</div>
						<div class="d-flex flex-column w-100">
							<h4 class="mb-1 text-dark fs-16">Dự án</h4>
							<div class="text-muted mb-2">Chọn dự án cụ thể, câu hỏi xoay quanh kiến thức về dự án đó</div>
							<div class="qt-type-panel {if $oneItem.test_type neq 'project'}d-none{/if}" id="qt_panel_project">
								<select class="from-control form-select iso-select2" data-width="100%" name="project_id">
									<option value="0">-- Chọn dự án --</option>
									{$projectOptions}
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>

			{* ========== CARD 3: Thiết lập đối tượng làm bài ========== *}
			<div class="card mb-4 rounded-1">
				<div class="card-header border-bottom d-flex align-items-center justify-content-between">
					<h2 class="title_box fs-5 text-dark mb-0">Thiết lập đối tượng làm bài</h2>
					<button class="btn btn-outline-none btn-icon" data-bs-toggle="collapse" data-bs-target="#collapseTarget" aria-expanded="true" type="button"><i class='bx bx-chevron-down fs-4'></i></button>
				</div>
				<div class="card-body pt-3 collapse show" id="collapseTarget">
					{* --- Công khai --- *}
					{assign var=uid_1 value=$clsISO->getUniqid()}
					<div class="d-flex gap-4 align-items-start mb-4">
						<div class="form-check form-switch form-check-reverse mb-0">
							<input class="form-check-input" type="radio" name="is_all_staff" value="1" {if $oneItem.is_all_staff eq 1 || empty($oneItem.is_all_staff)}checked{/if} onChange="$Core.quizTest.checkStaff(this,event)" toId="{$uid_1}">
						</div>
						<div class="d-flex flex-column w-100">
							<h4 class="mb-1 text-dark fs-16">Công khai</h4>
							<div class="">Toàn bộ thành viên công ty</div>
						</div>
					</div>
					{* --- Phòng ban hoặc nhân viên --- *}
					{assign var=uid_2 value=$clsISO->getUniqid()}
					<div class="d-flex gap-4 align-items-start mb-4">
						<div class="form-check form-switch form-check-reverse mb-0">
							<input class="form-check-input" type="radio" name="is_all_staff" value="2" onChange="$Core.quizTest.checkStaff(this,event)" toId="{$uid_2}" {if $oneItem.is_all_staff eq 2}checked{/if}>
						</div>
						<div class="d-flex flex-column w-100">
							<h4 class="mb-1 text-dark fs-16">Phòng ban hoặc nhân viên</h4>
							<div class="">Nhân viên hoặc phòng ban được chọn đều có thể làm bài kiểm tra</div>
							<div class="box_unit_staff unit_staff mt-2 {if $oneItem.is_all_staff ne 2}d-none{/if}" id="{$uid_2}">
								<div class="form-group form-row">
									<div class="col-12 mb-2">
										<label class="form-label mb-1">Phòng ban</label>
										<div class="w-100">
											<select name="list_department_id[]" class="form-control form-control-lg form-field iso-select2 select2 w-100" data-width="100%" multiple="true" data-placeholder="Chọn phòng ban tham gia">
												{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$clsISO->getArrayByTextSlash($oneItem.list_department_id),'Phòng ban')}
											</select>
										</div>
									</div>
									<div class="col-12">
										<label class="form-label mb-1">Nhân viên</label>
										<div class="w-100">
											<select placeholder="Lựa chọn nhân viên" name="list_profile_id[]" class="form-control form-control-lg form-field iso-select2 select2 w-100" multiple="true" data-width="100%" data-placeholder="Chọn nhân viên tham gia">
												{assign var=arr_profile_ids value=$clsISO->getArrayByTextSlash($oneItem.list_profile_id)}
												{foreach name=i from=$list_profiles item=prof}
												<option {if $clsISO->checkItemInArray($prof.profile_id, $arr_profile_ids)} selected{/if} value="{$prof.profile_id}">{$clsProfile->getFullName($prof.profile_id, $prof)}</option>
												{/foreach}
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					{* --- Nhóm nhân viên --- *}
					{assign var=uid_3 value=$clsISO->getUniqid()}
					<div class="d-flex gap-4 align-items-start mb-4">
						<div class="form-check form-switch form-check-reverse mb-0">
							<input class="form-check-input" type="radio" name="is_all_staff" value="3" onChange="$Core.quizTest.checkStaff(this,event)" toId="{$uid_3}" {if $oneItem.is_all_staff eq 3}checked{/if}>
						</div>
						<div class="d-flex flex-column w-100">
							<h4 class="mb-1 text-dark fs-16">Nhóm nhân viên</h4>
							<div class="">Thành viên thuộc nhóm được chọn đều có thể làm bài kiểm tra</div>
							<div class="box_unit_staff unit_group_staff {if $oneItem.is_all_staff ne 3}d-none{/if} mt-2" id="{$uid_3}">
								<div class="form-group form-row">
									<div class="col-12">
										<div class="w-100">
											<select name="list_group_profile_id[]" class="form-control form-field iso-select2 select2 w-100" data-width="100%" multiple="true" data-placeholder="Chọn nhóm nhân viên">
												{assign var=arr_group_id value=$clsISO->getArrayByTextSlash($oneItem.list_group_profile_id)}
												{foreach name=i from=$lstGroupProfile item=group}
													<option{if $clsISO->checkItemInArray($group.group_profile_id, $arr_group_id)} selected{/if} value="{$group.group_profile_id}">{$clsGroupProfile->getTitle($group.group_profile_id, $group)}</option>
												{/foreach}
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			{* ========== CARD 4: Cài đặt thời gian ========== *}
			<div class="card mb-4 rounded-1">
				<div class="card-header border-bottom d-flex align-items-center justify-content-between">
					<h2 class="title_box fs-5 text-dark mb-0">Cài đặt thời gian</h2>
					<button class="btn btn-outline-none btn-icon" data-bs-toggle="collapse" data-bs-target="#collapseSetting" aria-expanded="true" type="button"><i class='bx bx-chevron-down fs-4'></i></button>
				</div>
				<div class="card-body pt-3 collapse show" id="collapseSetting">
					{* --- Thời gian làm bài --- *}
					<div class="form-group form-row item_setting">
						<div class="col-12 col-md-6">
							<div class="d-flex gap-4 align-items-start mb-4">
								<div class="form-check form-switch form-check-reverse mb-0">
									<input class="form-check-input" type="checkbox" name="is_duration" value="1" {if $more_information.is_duration eq 1 || !isset($more_information.is_duration)}checked{/if} onChange="$Core.quizTest.checkItemSetting(this,event)">
								</div>
								<div class="d-flex flex-column w-100">
									<h4 class="mb-1 text-dark fs-16">Thời gian làm bài</h4>
									<div class="">Thời gian tối đa làm bài kiểm tra khi bắt đầu làm</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="input-group input-group-merge w-px-150">
								<input type="text" class="form-control form-control-lg no-focus required numberonly" name="duration" value="{if !empty($oneItem.duration)}{$oneItem.duration}{else}0{/if}" placeholder="0" {if isset($more_information.is_duration) && $more_information.is_duration ne 1}disabled{/if}>
								<span class="input-group-text">phút</span>
							</div>
						</div>
					</div>
					{* --- Thời gian bắt đầu --- *}
					<div class="form-group form-row item_setting">
						<div class="col-12 col-md-6">
							<div class="d-flex gap-4 align-items-start mb-4">
								<div class="form-check form-switch form-check-reverse mb-0">
									<input class="form-check-input" type="checkbox" name="is_start_date" value="1" onChange="$Core.quizTest.checkItemSetting(this,event)" {if $more_information.is_start_date eq 1 || !isset($more_information.is_start_date)}checked{/if}>
								</div>
								<div class="d-flex flex-column w-100">
									<h4 class="mb-1 text-dark fs-16">Thời gian bắt đầu làm bài</h4>
									<div class="">Quy định thời gian người dùng có thể bắt đầu làm bài kiểm tra, người dùng không thể bắt đầu làm bài trước mốc thời gian này</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="input-group w-px-250">
								<input class="form-control form-control-lg no-focus form-field w-100 required" type="datetime-local" name="start_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{if !empty($oneItem.start_date)}{$clsISO->formatDate($oneItem.start_date,5)}{/if}" {if isset($more_information.is_start_date) && $more_information.is_start_date ne 1}disabled{/if} />
							</div>
						</div>
					</div>
					{* --- Thời gian kết thúc --- *}
					<div class="form-group form-row item_setting">
						<div class="col-12 col-md-6">
							<div class="d-flex gap-4 align-items-start mb-4">
								<div class="form-check form-switch form-check-reverse mb-0">
									<input class="form-check-input" type="checkbox" name="is_end_date" value="1" onChange="$Core.quizTest.checkItemSetting(this,event)" {if $more_information.is_end_date eq 1 || !isset($more_information.is_end_date)}checked{/if}>
								</div>
								<div class="d-flex flex-column w-100">
									<h4 class="mb-1 text-dark fs-16">Thời gian kết thúc làm bài</h4>
									<div class="">Quy định kết thúc hiệu lực làm bài kiểm tra, người dùng sẽ không thể làm bài khi đến mốc thời gian này hoặc nếu đang làm bài thì bài kiểm tra sẽ dừng và tự động nộp bài.</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="input-group w-px-250">
								<input class="form-control form-control-lg no-focus form-field w-100 required" type="datetime-local" name="end_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{if !empty($oneItem.end_date)}{$clsISO->formatDate($oneItem.end_date,5)}{/if}" {if isset($more_information.is_end_date) && $more_information.is_end_date ne 1}disabled{/if} />
							</div>
						</div>
					</div>
					{* --- Điểm tối đa + Tự động chia điểm --- *}
					<div class="form-group form-row">
						<div class="col-12 col-md-6">
							<div class="d-flex align-items-center gap-2">
								<h4 class="mb-0 text-dark fs-16">Điểm tối đa cho bài kiểm tra</h4>
								<div class="input-group w-px-100">
									<input class="form-control form-control-lg no-focus form-field w-100 numberonly required" type="text" name="score" value="{if !empty($oneItem.score)}{$oneItem.score}{else}10{/if}" />
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="d-flex gap-4 mb-4">
								<div class="form-check form-switch form-check-reverse mb-0">
									<input class="form-check-input" type="checkbox" name="is_split_points" value="1" {if $more_information.is_split_points eq 1 || !isset($more_information.is_split_points)}checked{/if}>
								</div>
								<div class="d-flex flex-column w-100">
									<h4 class="mb-1 text-dark fs-16">Hệ thống sẽ tự động chia điểm</h4>
									<div class="">Tính năng tự động chia đều điểm cho các câu hỏi</div>
								</div>
							</div>
						</div>
					</div>
					{* --- Pass score --- *}
					<div class="form-group form-row mb-3">
						<div class="col-12 col-md-6">
							<div class="d-flex align-items-center gap-2">
								<h4 class="mb-0 text-dark fs-16">Điểm pass (%)</h4>
								<div class="input-group w-px-100">
									<input type="number" step="0.1" class="form-control form-control-lg no-focus" name="pass_score" value="{if !empty($oneItem.pass_score)}{$oneItem.pass_score}{else}80{/if}" min="0" max="100">
								</div>
							</div>
						</div>
					</div>
					{* --- Cho phép sửa bài --- *}
					<div class="form-group form-row">
						<div class="col-12 col-md-6">
							<div class="d-flex gap-4 align-items-start mb-4">
								<div class="form-check form-switch form-check-reverse mb-0">
									<input class="form-check-input" type="checkbox" name="submission_count" value="1" {if $oneItem.submission_count eq 1}checked{/if}>
								</div>
								<div class="d-flex flex-column w-100">
									<h4 class="mb-1 text-dark fs-16">Cho phép sửa bài</h4>
									<div class="">Sau khi nộp bài xong mà vẫn trong thời gian làm bài quy định thì có thể sửa lại đáp áp.</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			{* ========== CARD 4: Câu hỏi ========== *}
			<div class="body_content">
				{if !empty($questions)}
					{foreach from=$questions item=q key=qi name=i}
						<div class="card rounded-1 mb-4 item_question" data-qt-question>
							<div class="card-body">
								<h4 class="mb-2 text-danger error_message d-none"></h4>
								<div class="row d-flex align-items-center mb-3">
									<div class="col-9">
										<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="questions[{$qi}][title]" placeholder="Nhập nội dung câu hỏi?" rows="1">{$q.question_text}</textarea>
									</div>
									<div class="col-3">
										<select class="form-select form-control-lg rounded-1 no-focus w-100 question_type" name="questions[{$qi}][question_type]" onChange="$Core.quizTest.loadTypeAnswer(this,event)" key="{$qi}" >
											<option value="1" {if $q.question_type eq '1'}selected{/if}>Chọn 1 đáp án</option>
											<option value="2" {if $q.question_type eq '2'}selected{/if}>Chọn nhiều đáp án</option>
										</select>
									</div>
								</div>
								<div class="lst_answers mb-3">
									{if !empty($q.answers)}
										{foreach from=$q.answers item=ans key=ai}
											<div class="item_answers d-flex mb-2" data-qt-option>
												<div class="d-flex w-100">
													<div class="form-check form-check-inline mt-2">
														{if $q.question_type eq '2'}
															<input class="form-check-input input_check_type" type="checkbox" name="questions[{$qi}][correct][]" value="{$ai}" {if $ans.is_correct eq 1}checked{/if}>
														{else}
															<input class="form-check-input input_check_type" type="radio" name="questions[{$qi}][correct]" value="{$ai}" {if $ans.is_correct eq 1}checked{/if}>
														{/if}														
													</div>
													<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="questions[{$qi}][options][{$ai}][text]" placeholder="Câu trả lời..." rows="1">{$ans.answer_text}</textarea>
												</div>
												<div class="d-flex justify-content-end h-100">
													<button class="btn btn-icon btn-outline-none" type="button" data-qt-remove-option><i class='bx bx-trash fs-24'></i></button>
												</div>
											</div>
										{/foreach}
									{/if}
								</div>
								<button class="btn btn-outline-info btn_addAnswer" type="button" data-qt-add-option>Thêm câu trả lời<i class='bx bx-plus ml-1'></i></button>
								<div class="d-flex align-items-end justify-content-between mt-3">
									<div class="w-px-80">
										<fieldset class="rounded-1 pb-2 field_point">
											<legend class="fs-14 px-1 bg-white mb-0">Điểm</legend>
											<input type="text" name="questions[{$qi}][score]" value="1" class="w-100 border-0 form-control no-focus py-0 point_question numberonly text-center" step="0.1" min="0">
										</fieldset>
									</div>
									<button class="btn btn-icon btn-outline-none" type="button" title="Xóa câu hỏi" data-qt-remove-question><i class="bx bx-trash fs-24"></i></button>
								</div>
							</div>
						</div>
					{/foreach}
				{else}
					<div class="card rounded-1 mb-4 item_question" data-qt-question>
						<div class="card-body">
							<h4 class="mb-2 text-danger error_message d-none"></h4>
							<div class="row d-flex align-items-center mb-3">
								<div class="col-9">
									<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="questions[0][title]" placeholder="Nhập nội dung câu hỏi?" rows="1"></textarea>
								</div>
								<div class="col-3">
									<select class="form-select form-control-lg rounded-1 no-focus w-100 question_type" name="questions[0][question_type]" onchange="$Core.quizTest.loadTypeAnswer(this,event)" key="0" >
										<option value="1" selected>Chọn 1 đáp án</option>
										<option value="2">Chọn nhiều đáp án</option>
									</select>
								</div>
							</div>
							<div class="lst_answers mb-3">
								<div class="item_answers d-flex mb-2" data-qt-option>
									<div class="d-flex w-100">
										<div class="form-check form-check-inline mt-2"><input class="form-check-input input_check_type" type="radio" name="questions[0][correct]" value="0" ></div>
										<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="questions[0][options][0][text]" placeholder="Câu trả lời..." rows="1"></textarea>
									</div>
									<div class="d-flex justify-content-end h-100"><button class="btn btn-icon btn-outline-none" type="button" data-qt-remove-option><i class='bx bx-trash fs-24'></i></button></div>
								</div>
								<div class="item_answers d-flex mb-2" data-qt-option>
									<div class="d-flex w-100">
										<div class="form-check form-check-inline mt-2"><input class="form-check-input input_check_type" type="radio" name="questions[0][correct]" value="1"></div>
										<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="questions[0][options][1][text]" placeholder="Câu trả lời..." rows="1"></textarea>
									</div>
									<div class="d-flex justify-content-end h-100"><button class="btn btn-icon btn-outline-none" type="button" data-qt-remove-option><i class='bx bx-trash fs-24'></i></button></div>
								</div>
							</div>
							<button class="btn btn-outline-info btn_addAnswer" type="button" data-qt-add-option>Thêm câu trả lời<i class='bx bx-plus ml-1'></i></button>
							<div class="d-flex align-items-end justify-content-between mt-3">
								<div class="w-px-80">
									<fieldset class="rounded-1 pb-2 field_point"><legend class="fs-14 px-1 bg-white mb-0">Điểm</legend><input type="text" name="questions[0][score]" value="1" class="w-100 border-0 form-control no-focus py-0 point_question numberonly text-center" step="0.1" min="0"></fieldset>
								</div>
								<button class="btn btn-icon btn-outline-none" type="button" title="Xóa câu hỏi" data-qt-remove-question><i class="bx bx-trash fs-24"></i></button>
							</div>
						</div>
					</div>
				{/if}
			</div>

			{* ========== STICKY BOTTOM BAR ========== *}
			<div class="d-flex position-sticky bottom-0 right-0 justify-content-end py-2 px-3 card float-end rounded-1 flex-row gap-2 w-100">
				<button class="btn rounded-1 btn-outline-none d-flex flex-column" type="button" data-qt-add-question>
					<i class='bx bx-plus fs-24'></i>
					<span class="text-dark fs-11 text-nowrap">Thêm câu hỏi</span>
				</button>
				<button class="btn rounded-1 btn-outline-none d-flex flex-column" type="button" onclick="$Core.quizTest.clickUploadExcel(this,event)">
					<i class="bx bx-import fs-24"></i>
					<span class="text-dark fs-11">Import</span>
				</button>
				<a href="/trac-nghiem-test.html" class="btn btn-outline-secondary btn-lg">Quay lại</a>
				<button class="btn btn-primary btn-lg" type="submit">Lưu bài test</button>
			</div>

		</div>
	</form>
</div>

{literal}
<style type="text/css">
	.field_point { border: 1px solid #d9dee3; padding: 0 8px; }
	.field_point legend { font-size: 12px; width: auto; margin-bottom: 0; }
	.field_point.point_disabled { opacity: 0.5; pointer-events: none; }
	.auto-textarea { overflow: hidden; resize: none; }
	.no-focus:focus { box-shadow: none !important; }
	.qt-type-panel { transition: all 0.3s ease; }
	.item_answers .form-check-input:checked { background-color: #696cff; border-color: #696cff; }
	.btn_addAnswer { font-size: 13px; }
	.ui-datepicker { z-index: 9999 !important; }
</style>
<script type="text/javascript">
if(typeof $Core === 'undefined') $Core = {};
if(typeof $Core.quizTest === 'undefined') $Core.quizTest = {};

/* ===== Auto-resize textarea ===== */
$Core.quizTest.autoTextarea = function() {
	$(document).on('input', '.auto-textarea', function() {
		this.style.height = 'auto';
		this.style.height = this.scrollHeight + 'px';
	});
	$('.auto-textarea').each(function() {
		this.style.height = 'auto';
		this.style.height = this.scrollHeight + 'px';
	});
};

/* ===== Toggle test type panels ===== */
$Core.quizTest.toggleType = function(el) {
	$('.qt-type-panel').addClass('d-none');
	var type = $(el).val();
	$('#qt_panel_' + type).removeClass('d-none');
};

/* ===== Toggle video source ===== */
$Core.quizTest.toggleVideoSource = function(el) {
	if($(el).val() === 'link') { $('#qt_video_link').show(); $('#qt_video_upload').hide(); }
	else { $('#qt_video_link').hide(); $('#qt_video_upload').show(); }
};

/* ===== Add new category (Module type) ===== */
$Core.quizTest.addCategory = function() {
	var title = prompt('Tên danh mục Module:');
	if(!title) return;
	$.post('/index.php?mod=quiz_test&act=addCategory', {title: title}, function(res) {
		var d = (typeof res === 'string') ? JSON.parse(res) : res;
		if(d.result) { $('#qt_category_id').append('<option value="' + d.category_id + '" selected>' + d.title + '</option>'); }
	});
};

/* ===== Upload video file ===== */
$Core.quizTest.uploadVideo = function(el) {
	var fd = new FormData();
	fd.append('video_file', el.files[0]);
	$.ajax({ url: '/index.php?mod=quiz_test&act=uploadVideo', type: 'POST', data: fd, processData: false, contentType: false, success: function(r) {
		if(r.indexOf('_success') === 0) {
			var path = r.split('|||')[1];
			$('input[name="video_file"]').val(path);
			$(el).closest('#qt_video_upload').find('.text-muted').remove();
			$(el).after('<div class="mt-1"><small class="text-success"><i class="bx bx-check"></i> ' + path + '</small></div>');
		} else if(r === '_limit_size') {
			alert('File quá lớn (tối đa 100MB)');
		} else {
			alert('Lỗi upload video');
		}
	}});
};

/* ===== Import Excel File ===== */
$Core.quizTest.clickUploadExcel = function(el, e) {
	e.preventDefault();
	$('#file_upload').trigger('click');
};

$Core.quizTest.uploadExcel = function(el, e) {
	var file = el.files[0];
	if (!file) return;

	var fd = new FormData();
	fd.append('upload_excel', file);
	
	$(el).val(''); // Reset input
	
	if(typeof $Core.swal !== 'undefined' && $Core.swal.loading) {
		$Core.swal.loading('Đang xử lý file Excel...');
	}
	
	$.ajax({
		url: '/index.php?mod=quiz_test&act=importExcel', type: 'POST', data: fd, processData: false, contentType: false,
		success: function(res) {
			if(typeof swal !== 'undefined') swal.close();
			var d = (typeof res === 'string') ? JSON.parse(res) : res;
			if(d.result && d.questions && d.questions.length > 0) {
				d.questions.forEach(function(q) {
					var idx = Date.now() + Math.floor(Math.random() * 1000);
					var typeHtml = '<select class="form-select form-control-lg rounded-1 no-focus w-100 question_type" name="questions[' + idx + '][question_type]" onChange="$Core.quizTest.loadTypeAnswer(this,event)" key="'+ idx +'" >'
						+ '<option value="1" ' + (q.type == 1 ? 'selected' : '') + '>Chọn 1 đáp án</option>'
						+ '<option value="2" ' + (q.type == 2 ? 'selected' : '') + '>Chọn nhiều đáp án</option>'
						+ '</select>';
					
					var optsHtml = '';
					var inputType = q.type == 2 ? 'checkbox' : 'radio';
					q.options.forEach(function(opt, oi) {
						var checked = opt.is_correct ? 'checked' : '';
						optsHtml += '<div class="item_answers d-flex mb-2" data-qt-option>'
							+ '<div class="d-flex w-100">'
							+ '<div class="form-check form-check-inline mt-2"><input class="form-check-input input_check_type" type="' + inputType + '" name="questions[' + idx + '][correct]" value="' + oi + '" ' + checked + '></div>'
							+ '<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="questions[' + idx + '][options][' + oi + '][text]" placeholder="Câu trả lời..." rows="1">' + opt.text + '</textarea>'
							+ '</div>'
							+ '<div class="d-flex justify-content-end h-100"><button class="btn btn-icon btn-outline-none" type="button" data-qt-remove-option><i class="bx bx-trash fs-24"></i></button></div>'
							+ '</div>';
					});
					
					var html = '<div class="card rounded-1 mb-4 item_question" data-qt-question><div class="card-body"><h4 class="mb-2 text-danger error_message d-none"></h4>'
						+ '<div class="row d-flex align-items-center mb-3"><div class="col-9">'
						+ '<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="questions[' + idx + '][title]" placeholder="Nhập nội dung câu hỏi?" rows="1">' + q.title + '</textarea>'
						+ '</div><div class="col-3">' + typeHtml + '</div></div>'
						+ '<div class="lst_answers mb-3">' + optsHtml + '</div>'
						+ '<button class="btn btn-outline-info btn_addAnswer" type="button" data-qt-add-option>Thêm câu trả lời<i class="bx bx-plus ml-1"></i></button>'
						+ '<div class="d-flex align-items-end justify-content-between mt-3"><div class="w-px-80"><fieldset class="rounded-1 pb-2 field_point"><legend class="fs-14 px-1 bg-white mb-0">Điểm</legend><input type="text" name="questions[' + idx + '][score]" value="1" class="w-100 border-0 form-control no-focus py-0 point_question numberonly text-center" step="0.1" min="0"></fieldset></div><button class="btn btn-icon btn-outline-none" type="button" title="Xóa câu hỏi" data-qt-remove-question><i class="bx bx-trash fs-24"></i></button></div>'
						+ '</div></div>';
					$('.body_content').append(html);
				});
				$Core.quizTest.loadScore();
				// Trigger input to fix textarea heights
				$('.body_content').find('.auto-textarea').trigger('input'); 
				
				if(typeof $Core.swal !== 'undefined') {
					$Core.swal.success('Import thành công', 'Đã thêm ' + d.questions.length + ' câu hỏi!');
				} else {
					alert('Đã import ' + d.questions.length + ' câu hỏi!');
				}
			} else {
				if(typeof $Core.swal !== 'undefined') $Core.swal.error('Lỗi Import', d.msg || 'File không đúng định dạng');
				else alert(d.msg || 'Lỗi file Excel');
			}
		},
		error: function() {
			if(typeof swal !== 'undefined') swal.close();
			alert('Lỗi kết nối máy chủ');
		}
	});
};

/* ===== Switch answer type (radio/checkbox) ===== */
$Core.quizTest.loadTypeAnswer = function(el, e) {
	var card = $(el).closest('.item_question');
	var type = $(el).val(),
		key = $(el).attr("key"),
		elm_answer = card.find('.input_check_type');
	if(type == 2) {
		elm_answer.attr("name",`questions[`+key+`][correct][]`);
	}else{
		elm_answer.attr("name",`questions[`+key+`][correct]`);
	}
	elm_answer.attr('type', type == '2' ? 'checkbox' : 'radio');
};

/* ===== Set correct answer ===== */
$Core.quizTest.setCorrect = function(el, e) {
	var card = $(el).closest('.item_question');
	var isRadio = $(el).hasClass('input_check_type') && $(el).attr('type') === 'radio';
	if(isRadio) {
		card.find('.input_check_type').not(el).prop('checked', false);
	}
	var item = $(el).closest('.item_answers');
	item.find('input[type="checkbox"][name*="is_correct"]').prop('checked', $(el).is(':checked'));
};

/* ===== Toggle target audience panels ===== */
$Core.quizTest.checkStaff = function(el, e) {
	$('.unit_staff, .unit_group_staff').addClass('d-none');
	var toId = $(el).attr('toId');
	if(toId && $(el).val() != '1') {
		$('#' + toId).removeClass('d-none');
	}
};

/* ===== Toggle time setting inputs ===== */
$Core.quizTest.checkItemSetting = function(el, e) {
	var row = $(el).closest('.item_setting');
	var inputs = row.find('input[type="text"], input[type="datetime-local"], input[type="number"]').not(el);
	if($(el).is(':checked')) {
		inputs.prop('disabled', false);
	} else {
		inputs.prop('disabled', true).val('');
	}
};

/* ===== Split points auto-calculate ===== */
$Core.quizTest.loadScore = function() {
	var maxScore = parseFloat($('input[name="score"]').val()) || 10;
	var isSplit = $('input[name="is_split_points"]').is(':checked');
	var questions = $('.item_question');
	if(isSplit && questions.length > 0) {
		var perQ = Math.round((maxScore / questions.length) * 100) / 100;
		questions.find('.point_question').val(perQ);
		$('.field_point').addClass('point_disabled');
	} else {
		questions.find('.point_question').prop('disabled', false);
		$('.field_point').removeClass('point_disabled');
	}
};

$(function() {
	/* Init auto-textarea */
	$Core.quizTest.autoTextarea();

	/* Init select2 */
	if($.fn.select2) {
		$('.iso-select2').select2();
	}

	/* Score split toggle */
	$('input[name="is_split_points"]').on('change', function() { $Core.quizTest.loadScore(); });
	$('input[name="score"]').on('change', function() { $Core.quizTest.loadScore(); });

	/* Add question */
	$('[data-qt-add-question]').on('click', function() {
		var idx = Date.now();
		var html = `<div class="card rounded-1 mb-4 item_question" data-qt-question>
						<div class="card-body">
							<h4 class="mb-2 text-danger error_message d-none"></h4>
							<div class="row d-flex align-items-center mb-3"><div class="col-9">
								<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="questions[`+ idx +`][title]" placeholder="Nhập nội dung câu hỏi?" rows="1"></textarea>
							</div>
							<div class="col-3">
								<select class="form-select form-control-lg rounded-1 no-focus w-100 question_type" name="questions[`+ idx +`][question_type]" onChange="$Core.quizTest.loadTypeAnswer(this,event)" key="`+ idx +`" >
									<option value="1" selected>Chọn 1 đáp án</option>
									<option value="2">Chọn nhiều đáp án</option>
								</select>
							</div>
						</div>
						<div class="lst_answers mb-3">
							<div class="item_answers d-flex mb-2" data-qt-option>
								<div class="d-flex w-100">
									<div class="form-check form-check-inline mt-2">
										<input class="form-check-input input_check_type" type="radio" name="questions[`+ idx +`][correct]" value="0" >
									</div>
									<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea" name="questions[`+ idx +`][options][0][text]" placeholder="Câu trả lời..." rows="1"></textarea>
								</div>
								<div class="d-flex justify-content-end h-100">
									<button class="btn btn-icon btn-outline-none" type="button" data-qt-remove-option><i class="bx bx-trash fs-24"></i></button>
								</div>
							</div>
							<div class="item_answers d-flex mb-2" data-qt-option>
								<div class="d-flex w-100">
									<div class="form-check form-check-inline mt-2">
										<input class="form-check-input input_check_type" type="radio" name="questions[`+ idx +`][correct]" value="1">
									</div>
									<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea" name="questions[`+ idx +`][options][1][text]" placeholder="Câu trả lời..." rows="1"></textarea>
								</div>
								<div class="d-flex justify-content-end h-100">
									<button class="btn btn-icon btn-outline-none" type="button" data-qt-remove-option><i class="bx bx-trash fs-24"></i></button>
								</div>
							</div>
						</div>
						<button class="btn btn-outline-info btn_addAnswer" type="button" data-qt-add-option>Thêm câu trả lời<i class="bx bx-plus ml-1"></i></button>
						<div class="d-flex align-items-end justify-content-between mt-3">
							<div class="w-px-80">
								<fieldset class="rounded-1 pb-2 field_point">
									<legend class="fs-14 px-1 bg-white mb-0">Điểm</legend>
									<input type="text" name="questions[`+ idx +`][score]" value="1" class="w-100 border-0 form-control no-focus py-0 point_question numberonly text-center" step="0.1" min="0">
								</fieldset>
							</div>
							<button class="btn btn-icon btn-outline-none" type="button" title="Xóa câu hỏi" data-qt-remove-question><i class="bx bx-trash fs-24"></i></button>
						</div>
						</div>
					</div>`;
		$('.body_content').append(html);
		$Core.quizTest.loadScore();
	});

	/* Remove question */
	$(document).on('click', '[data-qt-remove-question]', function() {
		$(this).closest('[data-qt-question]').remove();
		$Core.quizTest.loadScore();
	});

	/* Add option */
	$(document).on('click', '[data-qt-add-option]', function() {
		var card = $(this).closest('.item_question');
		var qName = card.find('textarea[name*="[title]"]').first().attr('name');
		var prefix = qName.replace('[title]', '');
		var oi = card.find('.lst_answers .item_answers').length;
		var inputType = card.find('.input_check_type').first().attr('type') || 'radio';
		var html = `<div class="item_answers d-flex mb-2" data-qt-option>
						<div class="d-flex w-100">
							<div class="form-check form-check-inline mt-2">
								<input class="form-check-input input_check_type" type="` + inputType + `" name="` + prefix + `[correct]" value="` + oi + `">
							</div>
							<textarea class="form-control no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea" name="` + prefix + `[options][` + oi + `][text]" placeholder="Câu trả lời..." rows="1"></textarea>
						</div>
						<div class="d-flex justify-content-end h-100">
							<button class="btn btn-icon btn-outline-none" type="button" data-qt-remove-option><i class="bx bx-trash fs-24"></i></button>
						</div>
					</div>`;
		card.find('.lst_answers').append(html);
	});

	/* Remove option */
	$(document).on('click', '[data-qt-remove-option]', function() { $(this).closest('[data-qt-option]').remove(); });

	/* Form submit with validation */
	$('#quizTestForm').on('submit', function(e) {
		e.preventDefault();
		var _form = $(this),
			check = 1,
			title = $('input[name="title"]').val().trim();
		
		$("input.required:not([disabled]),select.required:not([disabled]),textarea.required:not([disabled])",_form).each(function(index,elm){
			if($(elm).val() == '' || $(elm).val() == 0){
				$(elm).focus();
				$(elm).addClass("error");
				check = 0;
				return false;
			}else{
				$(elm).removeClass("error")
			}
		});
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
				console.log("sss");
				$(".error_message",$(elm)).addClass("d-none").text('');
			}
		});
		/*if(!title) {
			$('input[name="title"]').focus().addClass('is-invalid');
			return false;
		}
		$('input[name="title"]').removeClass('is-invalid');*/
		
		if(check == 1) {
			var btn = $(this).find('button[type="submit"]');
			btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Đang lưu...');
			var formData = $(this).serialize();
			$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=saveTest', formData, function(res) {
				var d = (typeof res === 'string') ? JSON.parse(res) : res;
				if(d.result) {
					if(typeof $Core.swal !== 'undefined') { $Core.swal.success('Lưu thành công!', ''); }
					else { alert('Lưu thành công!'); }
					if(d.url) setTimeout(function(){ location.href = d.url; }, 800);
				} else {
					if(typeof $Core.swal !== 'undefined') { $Core.swal.error('Có lỗi xảy ra', ''); }
					else { alert('Có lỗi xảy ra!'); }
					btn.prop('disabled', false).html('Lưu bài test');
				}
			}).fail(function() {
				alert('Lỗi kết nối server!');
				btn.prop('disabled', false).html('Lưu bài test');
			});
		}
		
	});

	/* Init score split on load */
	$Core.quizTest.loadScore();
	updateTabIndex();
	function updateTabIndex() {
		let index = 1;
		$('input[type=radio], input[type=checkbox], button, select',$("#quizTestForm")).attr('tabindex', -1);
		$('input[type="text"],input[type="datetime-local"],input[type="number"], select, textarea',$("#quizTestForm")).filter(':visible:not([disabled])').each(function () {
			$(this).attr('tabindex', index++);
		});
	}
	let debounceTimer;
	function debounceUpdate() {
		console.log("aaaaaaaa");
		clearTimeout(debounceTimer);
		debounceTimer = setTimeout(() => {
			updateTabIndex();
		}, 1000);
	}
	const observer = new MutationObserver(debounceUpdate);
	
	
});
</script>
{/literal}
