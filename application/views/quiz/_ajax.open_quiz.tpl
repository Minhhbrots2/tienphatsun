<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable modal-ipad-xl">
		<form class="d-none" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="hid" value="upload">
			<input type="file" toid="{$toId}" class="upload_file_{$toId}" onchange="$Core.billing.upload_sp_file(this, event)" name="banner" billing_id="1059" to_field="banner">
		</form>
		<form method="POST" enctype="multipart/form-data" class="modal-content">
			<div class="modal-content">
				<div class="modal-header d-flex align-items-center justify-content-between">
					<div class="modal-header__left">
						{if $course_id eq 0}
						<h5 class="modal-title">Thêm mới bài trắc nghiệm</h5>
						{else}
						<h5 class="modal-title">Sửa bài trắc nghiệm</h5>
						{/if}
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
					</div>
				</div>
				<div class="modal-body modal-body-scrollable">	
					<div class="form-group mb-2">
						<label for="title" class="form-label mb-1">Tên bài trắc nghiệm</label>
						<input type="text" class="form-control required form-field" name="title" placeholder="Tên bài trắc nghiệm" value="{$oneItem.title}">	
					</div>
					<div class="form-group mb-2">
						<label class="form-label mb-1">Mô tả</label>
						<textarea class="form-control form-field" cols="255" rows="3" placeholder="Mô tả ngắn gọn" name="content" data-field="content" id="{$clsISO->getUniqid()}">{$oneItem.content}</textarea>
					</div>				
					<div class="form-group">
						<label for="nameSlideTop" class="form-label">Banner</label>
						<div class="we-filedrop-wrapper mb-2">
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
					<div class="form-group form-row mb-2">
						<div class="col-6 col-xxl-3 mb-2 mb-lg-0">
							<label class="form-label mb-1">Bắt đầu</label>
							<input class="form-control form-field w-100 required" type="datetime-local"  name="start_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{$oneItem.start_date}"/>
						</div>
						<div class="col-6 col-xxl-3 mb-2 mb-lg-0">
							<label class="form-label mb-1">Kết thúc</label>
							<input class="form-control form-field w-100 required" type="datetime-local"  name="end_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{$oneItem.end_date}"/>
						</div>
						<div class="col-12 col-xxl-3 mb-2 mb-lg-0">
							<label class="form-label mb-1">Thời lượng</label>
							<div class="input-group input-group-merge">
								<input type="text" class="form-control required numberonly" name="duration" value="0" placeholder="0" aria-label="0" aria-describedby="duration_{$uid}">
								<span class="input-group-text" id="duration_{$uid}">phút</span>
						  	</div>
						</div>
						<div class="col-6 col-xxl-3 mb-2 mb-lg-0">
							<label class="form-label mb-1">Điểm số</label>
							<input class="form-control form-field w-100 numberonly required" type="text"  name="score" value="{if !empty($oneItem.score)}{$oneItem.score}{else}10{/if}"/>
						</div>
					</div>
					<div class="form-group form-row mb-2">
						<div class="col-6 col-xxl-4 mb-2 mb-lg-0">
							<div class="form-check form-switch form-check-reverse mb-0">
								<input class="form-check-input" type="checkbox" name="submission_count" value="1" id="submission_count_{$uid}">
								<label class="form-check-label" for="submission_count_{$uid}">Cho phép sửa bài</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label mb-1">Thành phần tham gia</label>
						<fieldset class="p-3">
							<div class="form-group">
								{assign var = uid value = $clsISO->getUniqid()}
								<div class="radio mb-2">
									<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" value="1" onChange="$Core.quiz.checkStaff(this,event)" toId="{$uid}" {if $oneItem.is_all_staff eq '1' || !isset($oneItem.is_all_staff)} checked{/if}>
									<label for="{$uid}">Tất cả nhân viên</label>
								</div>
								{assign var = uid value = $clsISO->getUniqid()}
								<div class="radio mb-2">
									<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" value="0" onChange="$Core.quiz.checkStaff(this,event)" toId="unit_staff_{$uid}" {if $oneItem.is_all_staff eq '0'} checked{/if}>
									<label for="{$uid}">Phòng ban hoặc nhân viên</label>
								</div>	
								<div class="pl-4 box_unit_staff unit_staff mb-2" id="unit_staff_{$uid}" {if $oneItem.is_all_staff ne '0'} style="display: none"{/if}>
									<div class="form-group form-row">
										<div class="col-12">
											<label class="form-label mb-1">{$core->get_Lang('Phòng ban')}</label>
											<div class="w-100 mb-2">
												<select name="list_department_id[]" class="form-control form-field iso-select2 select2 w-100" data-width="100%" multiple="true" data-placeholder="Chọn phòng ban tham gia">
													{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$clsISO->getArrayByTextSlash($oneItem.list_department_id),'Phòng ban')}
												</select>
											</div>
										</div>
										<div class="col-12">
											<label class="form-label mb-1">{$core->get_Lang('Nhân viên')}</label>
											<div class="w-100">
												<select placeholder="Lựa chọn nhân viên" name="list_profile_id[]" class="form-control form-field iso-select2 select2 w-100" multiple="true" data-width="100%" data-placeholder="Chọn nhân viên tham gia">
													{assign var=arr_profile_ids value=$clsISO->getArrayByTextSlash($oneItem.list_profile_id)}
													{foreach name=i from=$list_profiles item=prof}
													<option{if $clsISO->checkItemInArray($prof.profile_id, $arr_profile_ids)} selected{/if} value="{$prof.profile_id}">{$clsProfile->getFullName($prof.profile_id, $prof)}</option>
													{/foreach}
												</select>
											</div>
										</div>
									</div>
								</div>						
								{assign var = uid value = $clsISO->getUniqid()}
								<div class="radio mb-2">
									<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" value="2" onChange="$Core.quiz.checkStaff(this,event)" toId="unit_group_staff_{$uid}" {if $oneItem.is_all_staff eq '2'} checked{/if}>
									<label for="{$uid}">Nhóm nhân viên</label>
								</div>
								<div class="pl-4 box_unit_staff unit_group_staff" id="unit_group_staff_{$uid}" {if $oneItem.is_all_staff ne '2'} style="display: none"{/if}>
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
						</fieldset>
					</div>
					<hr>
					<div class="form-group mb-2">					
						<div class="d-flex justify-content-between align-items-center gap-1">
							<div class="form-check form-switch form-check-reverse mb-0">
								<input class="form-check-input" type="checkbox" name="is_group" id="is_group_{$uid}" value="1" onChange="$Core.quiz.loadType(this,event)" toId="body_quiz_{$uid}">
								<label class="form-check-label" for="is_group_{$uid}">Chia phần trắc nghiệm</label>
							</div>
							<button class="btn btn-outline-primary btn-sm btn_add_question" type="button" toId="lst_question_{$gId}" group_id="{$gId}" question_id="" onClick="$Core.quiz.addQuestion(this,event)" data-type="_OPEN" data-bs-toggle="tooltip" title="Thêm câu hỏi">Thêm câu hỏi</button>
						</div>
					</div>	
					<div class="body_quiz" id="body_quiz_{$uid}">
						<div class="content_quiz" id="content_quiz_{$uid}">	
							<div class="lst_question" id="lst_question_{$gId}">							
							</div>
						</div>				
					</div>
				</div>
				<div class="modal-footer border-top">
					<button type="button" class="btn btn-primary" onClick="$Core.quiz.open_quiz(this,event)" data-quiz_id="{$quiz_id}" data-type="{$action}">Lưu lại</button>
				</div>
			</div>
		</form>
	</div>
</div>