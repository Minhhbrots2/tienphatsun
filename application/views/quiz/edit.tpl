{$scriptJs}

<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">

	<div class="d-flex flex-wrap justify-content-between align-items-center py-2">

		<div class="p__left">

			<nav aria-label="breadcrumb">

				<ol class="breadcrumb mb-2">

					<li class="breadcrumb-item">

						<h4 class="fw-bold mb-1"><a href="{$clsISO->getLink('quiz')}">Trắc nghiệm</a></h4>

					</li>

					{if !empty($questions)}

						<li class="breadcrumb-item">

							<a href="#">Sửa #{$quiz_id}</a>

						</li>

					{else}

						<li class="breadcrumb-item">

							<a href="#">Tạo mới</a>

						</li>

					{/if}

				</ol>

			</nav>

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

	<form action="" enctype="multipart/form-data">

		<input class="d-none" id="file_upload" type="file" name="upload_excel" value="" onChange="$Core.quiz.uploadExcel(this,event)">

		<input type="file" id="video_upload" class="d-none" name="video_file_input" accept="video/*" onchange="$Core.quiz.uploadVideo(this,event)">

	</form>

	<form action="" method="post" id="frm_question">

		<div class="col-md-8 col-lg-6 mx-auto">

			<div class="card mb-4 rounded-1">

				{assign var=gId value=$clsISO->getUniqid()}

				<div class="card-header border-bottom d-flex align-items-center justify-content-between">

					<h2 class="title_box fs-5 text-dark mb-0">Thông tin chung</h2>

					<button class="btn btn-outline-none btn-icon" data-bs-toggle="collapse" data-bs-target="#collapse_{$gId}" aria-expanded="true" aria-controls="collapse_{$gId}" type="button"><i class='bx bx-chevron-down fs-4' ></i></button>

				</div>

				<div class="card-body pt-3 collapse show" id="collapse_{$gId}">

					<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} align-items-center mb-4">

						{if $deviceType ne 'phone'}

							<img src="{$URL_IMAGES}/icons/icon_title.svg" alt="" width="25" height="25">

						{/if}

						<input type="text" class="form-control form-control-lg no-focus required form-field flex-fill rounded-0 border-0 border-bottom px-0 fs-16" name="title" placeholder="Tên bài trắc nghiệm" value="{$oneItem.title}">

					</div>

					<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} align-items-start mb-4">

						{if $deviceType ne 'phone'}

							<img src="{$URL_IMAGES}/icons/icon_content.svg" alt="" width="25" height="25">

						{/if}						

						<textarea class="form-control no-focus form-field flex-fill fs-16" cols="255" rows="6" placeholder="Mô tả ngắn gọn" name="content" data-field="content" id="{$clsISO->getUniqid()}">{$oneItem.intro}</textarea>

					</div>

				</div>

			</div>

			<div class="card mb-4 rounded-1">

				<div class="card-header border-bottom d-flex align-items-center justify-content-between">

					<h2 class="title_box fs-5 text-dark mb-0">Chủ đề bài test</h2>

					<button class="btn btn-outline-none btn-icon" data-bs-toggle="collapse" data-bs-target="#collapseType" aria-expanded="true" type="button"><i class='bx bx-chevron-down fs-4'></i></button>

				</div>

				<div class="card-body pt-3 collapse show" id="collapseType">

					{* --- Video --- *}

					<div class="d-flex gap-4 align-items-start mb-4 item_quiz_type">

						<div class="form-check form-switch form-check-reverse mb-0">

							<input class="form-check-input" type="radio" name="quiz_type" value="video" {if $oneItem.quiz_type eq 'video' || empty($oneItem.quiz_type)}checked{/if} onChange="$Core.quiz.toggleType(this,event)">

						</div>

						<div class="d-flex flex-column w-100">

							<h4 class="mb-1 text-dark fs-16">Video</h4>

							<div class="text-muted mb-2">Dán link video, câu hỏi xoay quanh nội dung video</div>

							<div class="qt-type-panel {if $oneItem.quiz_type neq 'video' && !empty($oneItem.quiz_type)}d-none{/if}" id="qt_panel_video">

								<div class="input-group">

									<input type="text" placeholder="Link video" name="link_video" class="form-control required " maxlength="255" value="">

									<button type="button" toid="video_upload" onclick="$Core.quiz.select_video(this, event)" class="btn btn-icon btn-outline-default d-none"><i class="fa fa-upload"></i></button>

								</div>

							</div>

						</div>

					</div>

					{* --- Training --- *}

					<div class="d-flex gap-4 align-items-start mb-4 item_quiz_type">

						<div class="form-check form-switch form-check-reverse mb-0">

							<input class="form-check-input" type="radio" name="quiz_type" value="training" {if $oneItem.quiz_type eq 'training'}checked{/if} onChange="$Core.quiz.toggleType(this,event)">

						</div>

						<div class="d-flex flex-column w-100">

							<h4 class="mb-1 text-dark fs-16">Đào tạo</h4>

							<div class="text-muted mb-2">Chọn bài đào tạo cụ thể, câu hỏi xoay quanh kiến thức về nội dung bài đào tạo đó</div>

							<div class="qt-type-panel {if $oneItem.quiz_type neq 'training'}d-none{/if}" id="qt_panel_training">

								<select class="from-control form-select iso-select2 required" data-width="100%" name="training_id" id="training_id">

									<option value="0">-- Chọn bài đào tạo --</option>

									{$trainingOptions}

								</select>

							</div>

						</div>

					</div>

					{* --- Module --- *}

					<div class="d-flex gap-4 align-items-start mb-4 item_quiz_type">

						<div class="form-check form-switch form-check-reverse mb-0">

							<input class="form-check-input" type="radio" name="quiz_type" value="module" {if $oneItem.quiz_type eq 'module'}checked{/if} onChange="$Core.quiz.toggleType(this,event)">

						</div>

						<div class="d-flex flex-column w-100">

							<h4 class="mb-1 text-dark fs-16">Module</h4>

							<div class="text-muted mb-2">Đóng gói bài test theo chủ đề module, có thể thêm mới nhanh</div>

							<div class="qt-type-panel {if $oneItem.quiz_type neq 'module'}d-none{/if}" id="qt_panel_module">

								<div class="d-flex gap-2">

									<select class="from-control form-select iso-select2 required" data-width="100%" name="category_id" id="qt_category_id">

										<option value="0">-- Chọn danh mục --</option>

										{$categoryOptions}

									</select>

									<button type="button" class="btn btn-outline-primary text-nowrap" onclick="$Core.quiz.addCategory()"><i class="bx bx-plus"></i> Thêm</button>

								</div>

							</div>

						</div>

					</div>

					{* --- Project --- *}

					<div class="d-flex gap-4 align-items-start mb-4 item_quiz_type">

						<div class="form-check form-switch form-check-reverse mb-0">

							<input class="form-check-input" type="radio" name="quiz_type" value="project" {if $oneItem.quiz_type eq 'project'}checked{/if} onChange="$Core.quiz.toggleType(this,event)">

						</div>

						<div class="d-flex flex-column w-100">

							<h4 class="mb-1 text-dark fs-16">Dự án</h4>

							<div class="text-muted mb-2">Chọn dự án cụ thể, câu hỏi xoay quanh kiến thức về dự án đó</div>

							<div class="qt-type-panel {if $oneItem.quiz_type neq 'project'}d-none{/if}" id="qt_panel_project">

								<select class="from-control form-select iso-select2 required" data-width="100%" name="project_id">

									<option value="0">-- Chọn dự án --</option>

									{$projectOptions}

							</select>

						</div>

					</div>

				</div>

			</div>

		</div>

			<div class="card mb-4 rounded-1">

				{assign var=gId value=$clsISO->getUniqid()}

				<div class="card-header border-bottom d-flex align-items-center justify-content-between">

					<h2 class="title_box fs-5 text-dark mb-0">Thiết lập đối tượng làm bài</h2>

					<button class="btn btn-outline-none btn-icon" data-bs-toggle="collapse" data-bs-target="#collapse_{$gId}" aria-expanded="true" aria-controls="collapse_{$gId}" type="button"><i class='bx bx-chevron-down fs-4' ></i></button>

				</div>

				<div class="card-body pt-3 collapse show" id="collapse_{$gId}">				

					<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} align-items-start mb-4">

						{assign var = uid value = $clsISO->getUniqid()}

						<div class="form-check form-switch form-check-reverse mb-0">

							<input class="form-check-input" type="radio" name="is_all_staff" value="1" {if $oneItem.is_all_staff eq 1 || empty($oneItem.is_all_staff)}checked{/if} onChange="$Core.quiz.checkStaff(this,event)" toId="{$uid}">

					  	</div>

						<div class="d-flex flex-column w-100">

							<h4 class="mb-2 text-dark fs-16">Công khai</h4>

							<div class="">Toàn bộ thành viên công ty</div>

							{*<div class="unit_staff w-100" id="{$uid}">

								<div class="w-100 d-flex align-items-center justify-content-between {if $oneItem.is_all_staff ne 1 && !empty($oneItem.is_all_staff)}d-none{/if}">

									<a href="/trac-nghiem/abc.html" class="">{$smarty.const.DOMAIN_URL}/trac-nghiem/abc.html</a>

									<button class="btn btn-outline-primary"><i class='bx bx-copy' style='color:#404080' ></i>Copy</button>

								</div>

							</div>*}

						</div>

					</div>					

					<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} align-items-start mb-4">

						{assign var = uid value = $clsISO->getUniqid()}

						<div class="form-check form-switch form-check-reverse mb-0">

							<input class="form-check-input" type="radio" name="is_all_staff" value="2" onChange="$Core.quiz.checkStaff(this,event)" toId="{$uid}" {if $oneItem.is_all_staff eq 2}checked{/if} >

					  	</div>

						<div class="d-flex flex-column w-100">

							<h4 class="mb-2 text-dark fs-16">Phòng ban hoặc nhân viên</h4>

							<div class="">Nhân viên hoặc phòng ban được chọn đều có thể làm bài kiểm tra</div>

							<div class="box_unit_staff unit_staff mt-2 {if $oneItem.is_all_staff ne 2}d-none{/if}" id="{$uid}">

								<div class="form-group form-row">

									<div class="col-12">

										<label class="form-label mb-1">{$core->get_Lang('Phòng ban')}</label>

										<div class="w-100 mb-2">

											<select name="list_department_id[]" class="form-control form-control-lg form-field iso-select2 select2 w-100" data-width="100%" multiple="true" data-placeholder="Chọn phòng ban tham gia">

												{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$clsISO->getArrayByTextSlash($oneItem.list_department_id),'Phòng ban')}

											</select>

										</div>

									</div>

									<div class="col-12">

										<label class="form-label mb-1">{$core->get_Lang('Nhân viên')}</label>

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

					<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} align-items-start mb-4">

						{assign var = uid value = $clsISO->getUniqid()}

						<div class="form-check form-switch form-check-reverse mb-0">

							<input class="form-check-input" type="radio" name="is_all_staff" value="3" onChange="$Core.quiz.checkStaff(this,event)" toId="{$uid}" {if $oneItem.is_all_staff eq 3}checked{/if} >

					  	</div>

						<div class="d-flex flex-column w-100">

							<h4 class="mb-2 text-dark fs-16">Nhóm nhân viên</h4>

							<div class="">Thành viên thuộc nhóm được chọn đều có thể làm bài kiểm tra</div>

							<div class="box_unit_staff unit_group_staff {if $oneItem.is_all_staff ne 3}d-none{/if} mt-2" id="{$uid}">

								<div class="form-group form-row">

									<div class="col-12">

										<div class="w-100 mb-2">

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

			<div class="card mb-4 rounded-1">

				{assign var=gId value=$clsISO->getUniqid()}

				<div class="card-header border-bottom d-flex align-items-center justify-content-between">

					<h2 class="title_box fs-5 text-dark mb-0">Cài đặt thời gian</h2>

					<button class="btn btn-outline-none btn-icon" data-bs-toggle="collapse" data-bs-target="#collapse_{$gId}" aria-expanded="true" aria-controls="collapse_{$gId}" type="button"><i class='bx bx-chevron-down fs-4' ></i></button>

				</div>

				<div class="card-body pt-3 collapse show" id="collapse_{$gId}">	

					<div class="form-group form-row item_setting">	

						<div class="col-12 col-md-6">

							<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} align-items-start mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="is_duration" value="1" {if $more_information.is_duration eq 1 || !isset($more_information.is_duration)}checked{/if} onChange="$Core.quiz.checkItemSetting(this,event)">

								</div>

								<div class="d-flex flex-column w-100">

									<h4 class="mb-2 text-dark fs-16">Thời gian làm bài</h4>

									<div class="">Thời gian tối đa làm bài kiểm tra khi bắt đầu làm</div>

									{if $deviceType eq 'phone'}

										<div class="input-group input-group-merge mt-2" style="width: 150px">

											<input type="text" class="form-control form-control-lg no-focus required numberonly" name="duration" value="{if !empty($oneItem.duration)}{$oneItem.duration}{else}0{/if}" placeholder="0" aria-label="0" aria-describedby="duration_{$uid}" {if isset($more_information.is_duration) && $more_information.is_duration ne 1}disabled{/if} >

											<span class="input-group-text" id="duration_{$uid}">phút</span>

										</div>

									{/if}

								</div>

							</div>

						</div>

						{if $deviceType ne 'phone'}

							<div class="col-12 col-md-6">

								<div class="input-group input-group-merge w-px-150 ">

									<input type="text" class="form-control form-control-lg no-focus required numberonly" name="duration" value="{if !empty($oneItem.duration)}{$oneItem.duration}{else}0{/if}" placeholder="0" aria-label="0" aria-describedby="duration_{$uid}" {if isset($more_information.is_duration) && $more_information.is_duration ne 1}disabled{/if} >

									<span class="input-group-text" id="duration_{$uid}">phút</span>

								</div>

							</div>

						{/if}

					</div>

					<div class="form-group form-row item_setting">	

						<div class="col-12 col-md-6">

							<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} align-items-start mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="is_start_date" value="1" onChange="$Core.quiz.checkItemSetting(this,event)" {if $more_information.is_start_date eq 1 || !isset($more_information.is_start_date)}checked{/if} >

								</div>

								<div class="d-flex flex-column w-100">

									<h4 class="mb-2 text-dark fs-16">Thời gian bắt đầu làm bài</h4>

									<div class="">Quy định thời gian người dùng có thể bắt đầu làm bài kiểm tra, người dùng không thể bắt đầu làm bài trước mốc thời gian này</div>

									{if $deviceType eq 'phone'}

										<div class="input-group mt-2" style="width: 250px">

											<input class="form-control form-control-lg no-focus form-field w-100 required" type="datetime-local"  name="start_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{if !empty($oneItem.start_date)}{$clsISO->formatDate($oneItem.start_date,5)}{/if}" {if isset($more_information.is_start_date) && $more_information.is_start_date ne 1}disabled{/if} />

										</div>

									{/if}

								</div>

							</div>

						</div>

						{if $deviceType ne 'phone'}

							<div class="col-12 col-md-6">

								<div class="input-group w-px-250 ">

									<input class="form-control form-control-lg no-focus form-field w-100 required" type="datetime-local"  name="start_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{if !empty($oneItem.start_date)}{$clsISO->formatDate($oneItem.start_date,5)}{/if}" {if isset($more_information.is_start_date) && $more_information.is_start_date ne 1}disabled{/if} />

								</div>

							</div>

						{/if}

					</div>

					<div class="form-group form-row item_setting">	

						<div class="col-12 col-md-6">

							<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} align-items-start mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="is_end_date" value="1" onChange="$Core.quiz.checkItemSetting(this,event)" {if $more_information.is_end_date eq 1 || !isset($more_information.is_end_date)}checked{/if} >

								</div>

								<div class="d-flex flex-column w-100">

									<h4 class="mb-2 text-dark fs-16">Thời gian kết thúc làm bài</h4>

									<div class="">Quy định kết thúc hiệu lực làm bài kiểm tra, người dùng sẽ không thể làm bài khi đến mốc thời gian này hoặc nếu đang làm bài thì bài kiểm tra sẽ dừng và tự động nộp bài.</div>

									{if $deviceType eq 'phone'}

										<div class="input-group mt-2" style="width: 250px">

											<input class="form-control form-control-lg no-focus form-field w-100 required" type="datetime-local"  name="end_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{if !empty($oneItem.end_date)}{$clsISO->formatDate($oneItem.end_date,5)}{/if}" {if isset($more_information.is_end_date) && $more_information.is_end_date ne 1}disabled{/if}/>

										</div>

									{/if}

								</div>

							</div>

						</div>

						{if $deviceType ne 'phone'}

							<div class="col-12 col-md-6">

								<div class="input-group w-px-250 ">

									<input class="form-control form-control-lg no-focus form-field w-100 required" type="datetime-local"  name="end_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{if !empty($oneItem.end_date)}{$clsISO->formatDate($oneItem.end_date,5)}{/if}" {if isset($more_information.is_end_date) && $more_information.is_end_date ne 1}disabled{/if}/>

								</div>

							</div>

						{/if}

					</div>

					<div class="form-group form-row">	

						<div class="col-12 col-md-6 {if $deviceType eq 'phone'}mb-3{/if}">

							<div class="d-flex align-items-center gap-2 ">

								<h4 class="mb-2 text-dark fs-16">Điểm tối đa cho bài kiểm tra</h4>

								<div class="input-group w-px-100">

									<input class="form-control form-control-lg no-focus form-field w-100 numberonly required" type="text"  name="score" value="{if !empty($oneItem.score)}{$oneItem.score}{else}10{/if}" onchange="$Core.quiz.load_score()" />

								</div>

						  	</div>

						</div>

						<div class="col-12 col-md-6">

							<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="is_split_points" value="1" onChange="$Core.quiz.load_score()" {if $more_information.is_split_points eq 1 || !isset($more_information.is_split_points)}checked{/if} >

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

							<div class="d-flex {if $deviceType eq 'phone'}gap-2{else}gap-4{/if} align-items-start mb-4">

								<div class="form-check form-switch form-check-reverse mb-0">

									<input class="form-check-input" type="checkbox" name="submission_count" value="1" {if $oneItem.submission_count eq 1}checked{/if}>

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

				{if !empty($questions)}

					{foreach from=$questions item=group key=group_id name=i}

						{assign var=lstQuestions value=$group.questions}

						<div class="box_group box_group_{$group_id}" data-group_id="{$group_id}" onClick="$Core.quiz.updateGroupFocus(this,event)">

							<div class="card rounded-1 mb-4" data-group_id="{$group_id}">

								<div class=" border-bottom d-flex align-items-center justify-content-between bg-lighter ">

									<h2 class="card-header title_box fs-5 text-dark mb-0 py-4 flex-fill cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapse_group_{$group_id}" aria-expanded="false" aria-controls="collapse_group_{$group_id}">Phần <span class="group_index">{$smarty.foreach.i.iteration}</span></h2>

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

										<input type="text" class="form-control form-control-lg no-focus form-field" name="groups[{$group_id}][title]" placeholder="Nhập tiêu đề phần" value="{$group.title}">

									</div>

									<div class="form-group">

										<textarea class="form-control no-focus form-field flex-fill" cols="255" rows="3" placeholder="Nhập mô tả" name="groups[{$group_id}][content]" id="{$clsISO->getUniqid()}">{$group.content}</textarea>

									</div>

								</div>

							</div>		

							{if !empty($lstQuestions)}

								{foreach from=$lstQuestions item=question key=question_id name=k}

									{assign var=lstAnswer value=$question.answer_options}

									<div class="card rounded-1 mb-4 item_question" data-question_id="{$question_id}">

										<div class="card-body box_question_{$question_id}">

											<h4 class="mb-2 text-danger error_message d-none"></h4>

											{if $deviceType eq 'phone'}

												<div class="d-flex align-items-center mb-3">

													<div class="flex-fill">

														<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][title]" placeholder="Nhập nội dung câu hỏi?"  rows="1">{$question.title}</textarea>

													</div>

													<div class="dropdown dropstart">

														<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>

														</button>

														<div class="dropdown-menu">

															<label class="dropdown-item {if $question.question_type eq 1}active{/if}" href="javascript:void(0)" for="{$question_id}_1">

																<input type="radio" name="groups[{$group_id}][questions][{$question_id}][question_type]" value="1" id="{$question_id}_1" hidden="hidden" onChange="$Core.quiz.loadTypeAnswer(this,event)" {if $question.question_type eq 1}checked{/if}>

																<span>Chọn 1 đáp án</span>

															</label>

															<label class="dropdown-item {if $question.question_type eq 2}active{/if}" href="javascript:void(0)" for="{$question_id}_2">

																<input type="radio" name="groups[{$group_id}][questions][{$question_id}][question_type]" value="2" id="{$question_id}_2" hidden="hidden" onChange="$Core.quiz.loadTypeAnswer(this,event)" {if $question.question_type eq 2}checked{/if}>

																<span>Chọn nhiều đáp án</span>

															</label>

														</div>

													</div>

												</div>

											{else}

												<div class="row d-flex align-items-center mb-3">

													<div class="col-9">

														<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][title]" placeholder="Nhập nội dung câu hỏi?"  rows="1">{$question.title}</textarea>

													</div>

													<div class="col-3">

														<div class="dropdown bootstrap-select show-tick w-100 dropup">

															<select class="form-select form-control-lg rounded-1 no-focus w-100 question_type" name="groups[{$group_id}][questions][{$question_id}][question_type]" id="selectpickerIcons" data-icon-base="icon-base bx" data-tick-icon="bx-check" data-style="btn-default" tabindex="null" onChange="$Core.quiz.loadTypeAnswer(this,event)">

																<option value="1" data-icon="icon-base  mb-50" {if $question.question_type eq 1}selected{/if}><i class="bx bx-radio-circle-marked"></i>Chọn 1 đáp án</option>

																<option value="2" data-icon="icon-base bx bxs-check-square mb-50" {if $question.question_type eq 2}selected{/if}>Chọn nhiều đáp án</option>

															</select>

														</div>

													</div>

												</div>

											{/if}

											<div class="lst_answers mb-3">

												{if $question.question_type ne 3}

													{foreach from=$lstAnswer item=answer key=answer_id}												

														<div class="item_answers d-flex mb-2">

															<div class="d-flex w-100">

																<div class="form-check form-check-inline mt-2">

																	<input class="form-check-input input_check_type" type="{if $question.question_type eq 1}radio{elseif $question.question_type eq 2}checkbox{/if}" name="{$answer_id}" value="1" onChange="$Core.quiz.setCorrect(this,event)" {if $answer.is_correct eq 1}checked{/if}>

																</div>

																<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][title]" placeholder="Câu trả lời..."  rows="1">{$answer.title}</textarea>

															</div>

															<div class="d-flex justify-content-end h-100">

																<div class="form-check form-switch form-check-reverse mb-0 d-none">

																	<input class="form-check-input" type="checkbox" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][is_correct]" value="1" {if $answer.is_correct eq 1}checked{/if} onChange="$Core.quiz.setCorrect(this,event)">

																</div>

																<button class="btn btn-icon btn-outline-none" type="button" onClick="$Core.quiz.removeAnswer(this,event)"><i class='bx bx-trash fs-24'></i></button>

															</div>

														</div>

													{/foreach}

												{else}

													{foreach from=$answer_options item=answer key=answer_id}												

														<div class="item_answers mb-2 d-none">

															<div class="row">

																<div class="col-9">

																	<div class="d-flex">

																		<div class="form-check form-check-inline mt-2">

																			<input class="form-check-input input_check_type" type="{if $question.question_type eq 1}radio{elseif $question.question_type eq 2}checked{/if}" name="{$answer_id}" value="1" onChange="$Core.quiz.setCorrect(this,event)" {if $answer.is_correct eq 1}checked{/if}>

																		</div>

																		<textarea class="form-control no-focus no-focus form-field flex-fill rounded-0 px-0 border-0 border-bottom auto-textarea required" name="groups[{$group_id}][questions][{$question_id}][answer_options][{$answer_id}][title]" placeholder="Câu trả lời..."  rows="1" disabled></textarea>

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

												{/if}

											</div>

											<button class="btn btn-outline-info btn_addAnswer question_type="{$question.question_type}" {if $question.question_type eq 3}d-none{/if}" type="button" onClick="$Core.quiz.addAnswer(this,event)">Thêm câu trả lời<i class='bx bx-plus ml-1'></i></button>

											<div class="d-flex align-items-end justify-content-between mt-3">

												<div class="w-px-80">

													<fieldset class="rounded-1 pb-2 field_point {if $more_information.is_split_points eq 1}point_disabled{/if}">

														<legend class="fs-14 px-1 bg-white mb-0">Điểm</legend>

														<input type="number" name="groups[{$group_id}][questions][{$question_id}][score]" value="{$question.score}" class="w-100 border-0 form-control no-focus py-0 point_question" step="0.1" min="0" >

													</fieldset>

												</div>

												<button class="btn btn-icon btn-outline-none" type="button" title="Xóa câu hỏi" onClick="$Core.quiz.removeQuestion(this,event)"><i class="bx bx-trash fs-24"></i></button>

											</div>

										</div>

									</div>

								{/foreach}

							{/if}

						</div>

					{/foreach}

				{else}

				<div class="box_group box_group_{$group_id}" data-group_id="{$group_id}" onClick="$Core.quiz.updateGroupFocus(this,event)">

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

								<textarea class="form-control no-focus form-field flex-fill" cols="255" rows="3" placeholder="Nhập mô tả" name="groups[{$group_id}][content]" id="{$clsISO->getUniqid()}">{$oneItem.content}</textarea>

							</div>

						</div>

					</div>			

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

							<button class="btn btn-outline-info btn_addAnswer"  question_type="1" type="button" onClick="$Core.quiz.addAnswer(this,event)">Thêm câu trả lời<i class='bx bx-plus ml-1'></i></button>

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

				</div>

				{/if}

			</div>

			{if $deviceType eq 'phone'}

				<div class="d-flex position-sticky bottom-0 right-0 justify-content-end py-2 px-2 card float-end rounded-1 flex-row gap-2 w-100" style="bottom:55px !important;">

					<button class="btn rounded-1 btn-outline-none d-flex flex-column btn-sm px-1" onclick="$Core.quiz.addGroupQuestion(this,event)">

						<i class="bx bx-qr fs-20"></i>

						<span class="text-dark fs-11 text-nowrap">Thêm phần</span>

					</button>

					<button class="btn rounded-1 btn-outline-none d-flex flex-column btn-sm px-1" onclick="$Core.quiz.addQuestion(this,event)">

						<i class="bx bx-plus fs-20"></i>

						<span class="text-dark fs-11 text-nowrap">Thêm câu hỏi</span>

					</button>

					<button class="btn rounded-1 btn-outline-none d-flex flex-column btn-sm px-1" onclick="$Core.quiz.clickUploadExcel(this,event)" toid="file_upload">

						<i class="bx bx-import fs-20"></i>

						<span class="text-dark fs-11">Import</span>

					</button>

					<button class="btn btn-outline-primary fs-14 px-2" type="button" onclick="$Core.quiz.addQuiz(this,event)" data-type="draft">Lưu nháp</button>

					<button class="btn btn-primary fs-14 px-2" type="button" onclick="$Core.quiz.addQuiz(this,event)" data-type="publish">Đăng bài</button>

				</div>

			{else}

				<div class="d-flex position-sticky bottom-0 right-0 justify-content-end py-2 px-3 card float-end rounded-1 flex-row gap-2 w-100">

					<button class="btn rounded-1 btn-outline-none d-flex flex-column" onClick="$Core.quiz.addGroupQuestion(this,event)">

						<i class='bx bx-qr fs-24'></i>

						<span class="text-dark fs-11 text-nowrap">Thêm phần</span>

					</button>

					<button class="btn rounded-1 btn-outline-none d-flex flex-column" onClick="$Core.quiz.addQuestion(this,event)">

						<i class='bx bx-plus fs-24'></i>

						<span class="text-dark fs-11 text-nowrap">Thêm câu hỏi</span>

					</button>

					<button class="btn rounded-1 btn-outline-none d-flex flex-column" onclick="$Core.quiz.open_import(this,event)" toId="file_upload">

						<i class='bx bx-import fs-24' ></i>

						<span class="text-dark fs-11">Import</span>

					</button>

					<button class="btn btn-outline-primary btn-lg" type="button" onClick="$Core.quiz.addQuiz(this,event)" data-type="draft">Lưu nháp</button>

					<button class="btn btn-primary btn-lg" type="button" onClick="$Core.quiz.addQuiz(this,event)" data-type="publish">Đăng bài</button>

				</div>

			{/if}

			<input type="hidden" name="group_focus" id="group_focus" value="{$group_id}">

			<input type="hidden" name="quiz_id" value="{$quiz_id}">

		</div>

	</form>

</div>

{literal}

<style type="text/css">

	.ui-datepicker{ z-index:9999 !important;}

	form .error:not(li):not(input){font-size: 14px;}

	.error .select2-container--default .select2-selection--single {

		border-color: inherit;

	}

	table.form {

		background-color: #fff;

		padding: 0px;

		border: 2px solid #E2E7E9;

		-moz-border-radius: 4px;

		-webkit-border-radius: 4px;

		-o-border-radius: 4px;

		border-radius: 4px;

		border-collapse: separate;

	}

	table.form td.fieldarea {

		background-color: #efefef;

		text-align: left;

	}

	table.form td {

		padding: 4px;

	}

</style>

<script type="text/javascript">

	$(function(){

		$Core.quiz.autoTextarea();

//		$Core.quiz.list({}, true);

	});

</script>

{/literal}