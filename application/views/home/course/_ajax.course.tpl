<div class="modal-dialog modal-ipad-xl modal-dialog-scrollable modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$clsCourse->getField($course_id, 'title', $oneCourse)}
				{if $oneCourse.location}<br />
				<small class="d-flex align-items-center text-fs-12">
					<i class='bx bxs-map mr-1'></i> {$oneCourse.location}
				</small>
				{/if}
			</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="p-2 bg-lighter">
			{if $oneCourse.cat_id eq $smarty.const._MEDIA_DISSEMINATION}	
				<div class="d-flex justify-content-center align-items-center gap-2">
					{if !$check_overTime}
						<div class="course_report_{$uid}">
							{if $check_upload eq '0'}<button type="buton" class="btn btn-block btn-sm btn-outline-primary" onClick="$Core.course.open_report(this,event)" course_id="{$oneCourse.course_id}" openFrom="_pop" toId="{$toId}">
								<i class='bx bx-user-voice'></i> Báo cáo
							</button>								
							{else}
							<span class="btn btn-sm btn-primary">
								<i class='bx bx-user-check'></i> Đã báo cáo
							</span>
							{/if}
						</div>
					{else}
						<div class="course_report_{$uid}">
							<span class="text-main text-nowrap">Sự kiện hết giờ báo cáo</span>
						</div>
					{/if}
					<button data-toggle="ripple" type="button" onClick="$Core.util.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="Sao chép link" data-link="{$PCMS_URL}{$clsCourse->getLink($course_id)}" class="btn btn-sm btn-link text-dark">{$clsISO->makeIcon('bx-link', 'Copy link')}</button>
				</div>
			{else}
				<div class="course_report_{$uid} d-flex justify-content-center align-items-center gap-2">
				{if $oneCourse.is_joined eq '1'}
					<button data-toggle="ripple" type="button" class="btn btn-sm btn-primary text-nowrap btn_checkin{if $oneCourse.is_checked_in eq '1'} checked_in{/if}" onClick="$Core.course.checkin(this,event)" course_id="{$course_id}" holderG="cancel" toId="{$uid}" openFrom="_pop">
						<i class='bx bx-user-check'></i> Đã xác nhận
					</button>
					{if empty($oneCourse.is_time_checkin)}
						<button type="buton" disabled class="btn btn-sm btn-outline-danger text-nowrap">
							<i class="bx bx-user-check"></i> Check-In
						</button>
					{else}
						{if $oneCourse.is_checked_in eq '1'}
						<button type="buton" class="btn btn-sm btn-outline-success text-nowrap">
							<i class="bx bx-check-double"></i> Đã check-In 
						</button>
						{else}
						<form action="#" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="hid" value="hid" />
							<input type="file" class="d-none" id="upload_image_{$uid}" name="image" data-course_id="{$course_id}" toId="{$uid}" 
								onChange="$Core.course.upload_image(this,event)"  openFrom="_pop" data-type="checkin">
							<button data-toggle="ripple" type="buton" onClick="$Core.course.select_image(this, event)" openFrom="_pop" toId="{$uid}" 
								class="btn btn-sm  btn-outline-danger text-nowrap"><i class="bx bx-user-check"></i> Check-In</button>
						</form>
						{/if}
					{/if}
					<button type="button" data-toggle="ripple" onClick="$Core.util.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="Sao chép link" data-link="{$PCMS_URL}{$clsCourse->getLink($course_id)}" class="btn btn-sm btn-link text-dark">{$clsISO->makeIcon('bx-link', 'Copy link')}</button>
				{else}
					<button type="button" class="btn btn-sm btn-outline-primary btn_checkin" onClick="$Core.course.checkin(this,event)" 
					holderG="confirm" course_id="{$course_id}" openFrom="_pop" toId="{$uid}"><i class="bx bx-user-check"></i> Xác nhận</button>
					<button type="button" data-toggle="ripple" onClick="$Core.util.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="Sao chép link" data-link="{$PCMS_URL}{$clsCourse->getLink($course_id)}" class="btn btn-sm btn-link text-dark">{$clsISO->makeIcon('bx-link', 'Copy link')}</button>
				{/if}
				</div>
			{/if}
		</div>
		<div class="modal-body">
			<div class="border bg-lightest px-3 mb-2 rounded-2">
				<div class="row">
					<div class="col-12 col-xxl-6">
						<div class="d-flex justify-content-between py-2 border-bottom gap-1">
							<span class="fw-semibold text-nowrap"><i class="bx bx-closet"></i> Loại sự kiện</span>
							<span class="time text-muted">{$clsProperty->getTitle($oneCourse.cat_id)}</span>	
						</div>					
					</div>
					<div class="col-12 col-xxl-6">
						<div class="h-100 d-flex  justify-content-between py-2 border-bottom gap-1">
							<span class="fw-semibold text-nowrap"><i class='bx bx-user'></i> Số lượng</span>
							<span class="time text-muted">
								<i class='bx bx-user mr-1 align-top'></i> 
								{$oneCourse.total_staffs}
							</span>						
						</div>
					</div>
					<div class="col-12 col-xxl-6">
						<div class="h-100 d-flex justify-content-between py-2 border-bottom gap-1">
							<span class="fw-semibold text-nowrap"><i class='bx bx-time-five'></i> Bắt đầu</span>
							<span class="time text-muted">
								{$clsISO->convertTimeToTextFormat($oneCourse.start_date,"d/m/Y H:i")}
							</span>
						</div>
					</div>
					<div class="col-12 col-xxl-6">
						<div class="h-100 d-flex justify-content-between py-2 border-bottom gap-1">
							<span class="fw-semibold text-nowrap"><i class='bx bx-time-five'></i> Kết thúc</span>
							<span class="time text-muted">
								{$clsISO->convertTimeToTextFormat($oneCourse.due_date,"d/m/Y H:i")}
							</span>
						</div>
					</div>
					<div class="col-12 col-xxl-6 flex-fill">
						<div class="h-100 d-flex justify-content-between py-2 gap-1">
							<span class="fw-semibold text-nowrap"><i class='bx bx-user'></i> Đối tượng</span>
							{if $oneCourse.is_all_staff eq 1}
								<span class="time text-muted">Tất cả thành viên</span>
							{else}
								<div class="d-flex flex-column text-left">
								{if $oneCourse.profile_name}
									<span>Nhân viên: {$oneCourse.profile_name}</span>
								{/if}
								{if $oneCourse.department_name}
									<span>Phòng ban: {$oneCourse.department_name}</span>
								{/if}
								{if $oneCourse.group_name}
									<span>Nhóm: {$oneCourse.group_name}</span>
								{/if}
								</div>
							{/if} 						
						</div>
					</div>					
					{if !empty($more_information.link)}
						<div class="col-12 flex-fill">
							<div class="h-100 d-flex flex-wrap justify-content-between py-2 gap-1 border-top">
								<span class="fw-semibold text-nowrap"><i class='bx bx-link'></i> Link lan tỏa</span>
								{if $clsISO->_DEV()}
									<a class="time text-link text-break" href="{$more_information.link}" target="_blank" >{$more_information.link}</a>	
								{else}
									<a class="time text-link text-break" href="{$more_information.link}" target="_blank" >{$more_information.link}</a>	
								{/if}													
							</div>
						</div>
					{/if}
				</div>
			</div>
			<div class="content-wrapper">
				<ul class="nav mb-3 nav-tabs nav-tabs-bordered" role="tablist">
					<li class="nav-item" role="presentation">
						<button type="button" class="nav-link px-2 active" id="tabbox_e1_{$uid}" data-bs-toggle="pill" 
						data-bs-target="#tabcontent_e1_{$uid}" role="tab"><i class="bx bx-info-circle"></i> Nội dung</button>
					</li>
					{if $clsISO->checkPermission('view_all_course') 
						|| $clsISO->checkPermissionGroup('DIRECTOR')
						|| $clsISO->checkPermissionGroup('SALE_DIRECTOR')
						|| $oneCourse.user_id eq $profile_id 
						|| $clsISO->checkInArray($oneCourse.manager_ids, $profile_id)}
						{if $oneCourse.cat_id eq $smarty.const._MEDIA_DISSEMINATION}
						<li class="nav-item">
							<button type="button" class="nav-link" id="tabbox_e2_{$uid}" data-bs-toggle="pill" role="tab" 
								data-bs-target="#tabcontent_e2_{$uid}">Đã báo cáo({$total_report_staffs})</button>
						</li>
						<li class="nav-item">
							<button type="button" class="nav-link" id="tabbox_e3_{$uid}" data-bs-toggle="pill" role="tab" 
								data-bs-target="#tabcontent_e3_{$uid}">Chưa báo cáo({$total_unreport_staffs})</button>
						</li>
						{else}
						<li class="nav-item">
							<button type="button" class="nav-link px-2" id="tabbox_e2_{$uid}" data-bs-toggle="pill" role="tab"
								data-bs-target="#tabcontent_e2_{$uid}"><i class="bx bx-user"></i> Tham gia <span class="badge bg-secondary">{$oneCourse.total_checkin_staffs}</span></button>
						</li>
						{/if}
					{/if}
				</ul>
				<div class="tab-content p-0" id="tab_content_{$uid}">
					<div class="tab-pane fade show active" id="tabcontent_e1_{$uid}" role="tabpanel">
						{if $oneCourse.cat_id eq $smarty.const._MEDIA_DISSEMINATION}
							<div class="awe__comment-item">
								{if !empty($list_report_staffs)}
									{foreach name=i from=$list_report_staffs key=_oKey item = _oStaff}
										<div class="d-flex w-100 mb-2">
											<div class="awe__profile-avatar bs-webui-popover" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oStaff.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-placement="top" data-width="350">
												<img class="rounded-pill" src="{$clsProfile->getAvatar($_oStaff.profile_id,30,30)}" width="30" height="30">
											</div>
											<div class="awe__comment-item-body">
												<div class="bg-lighter p-2 rounded-2">
													<div class="awe__comment-profile d-flex align-items-center gap-1 mb-2">
														<h4 class="awe__comment-name text-bold mb-0 fs-14">{$_oStaff.full_name}</h4>
														<span class="awe__comment-time text-muted fs-11">{$clsISO->getTimeAgo($_oStaff.time)}</span>
													</div>
													<div class="awe__comment-content" data-fancybox="image_report_{$uid}" data-src="{$_oStaff.image}" data-caption="{$_oStaff.full_name}" >
														<img class="radius-4" src="{$clsISO->getUrlImageFH($_oStaff.image,200,400)}" title="comment" style="max-width:350px">
													</div>
												</div>
											</div>
										</div>
									{/foreach}
								{else}
									<div id="content_{$uid}" class="tinyContentx">
										Chưa có báo cáo
									</div>
								{/if}
							</div>
						{else}
							<div class="p-3 mb-3 rounded-2 bg-lighter"> 
								<div id="content_{$uid}" class="tinyContentx">
								{if !empty($oneCourse.content)}
									{$oneCourse.content|html_entity_decode}
								{else}
									Không có nội dung
								{/if}
								</div>					
							</div>
							<hr class="my-3" />
							<div class="form_comment">
								{$core->getBlock('comment', ['table_id' => $course_id, 'clsTable' => 'Course'])}
							</div>
						{/if}
					</div>
					{if $clsISO->checkPermission('view_all_course') 
						|| $clsISO->checkPermissionGroup('DIRECTOR')
						|| $clsISO->checkPermissionGroup('SALE_DIRECTOR')
						|| $oneCourse.user_id eq $profile_id 
						|| $clsISO->checkInArray($oneCourse.manager_ids, $profile_id)}
						{if $oneCourse.cat_id eq $smarty.const._MEDIA_DISSEMINATION}
						<div class="tab-pane fade" id="tabcontent_e2_{$uid}" role="tabpanel">
							<div class="widget-content">
								<div class="table-container no-shadow overflow-auto mb-0" style="max-height:40vh">
									<table cellpadding="0" cellspacing="0" class="table table-bordered mb-0" width="100%">
										<thead><tr>
											{if $deviceType ne 'phone'}
											<th class="align-center bg-lighter text-center" width="3%">STT</th>
											{/if}
											<th class="align-center bg-lighter text-left">Họ tên</th>
											<th class="align-center bg-lighter text-left">Phòng</th>
											<th class="align-center bg-lighter text-left">Thời gian</th>
											<th class="align-center bg-lighter text-center">Kết quả</th>
										</tr></thead>
										<tbody class="tbody_report_staffs_{$course_id}">
											{if !empty($list_report_staffs)}
												{foreach name=i from=$list_report_staffs key=_oKey item = _oStaff}
												<tr class="tr">
													{if $deviceType ne 'phone'}
													<td class="text-center">{$smarty.foreach.i.iteration}</td>
													{/if}
													<td class="text-left text-nowrap">{$_oStaff.full_name}</td>
													<td class="text-left">{$_oStaff.department_name}</td>
													<td class="text-left">{$clsISO->convertTimeToText($_oStaff.time,1)}</td>
													<td class="text-center">
														<div class="d-flex flex-wrap justify-content-center align-items-center gap-1">
															{if !empty($_oStaff.link)}<a href="{$_oStaff.link}" target="_blank" class="text-link">Link nghiệm thu</a>{/if}
																<a class="download mb-0 text-nowrap border-0" data-fancybox="image_report_lst_{$uid}" href="{$_oStaff.image}" data-caption="{$_oStaff.full_name}" >
																	<img src="{$clsISO->getUrlImageFH($_oStaff.image,40,80)}" width="40" height="80" alt="">
																</a>
														</div>
													</td>
													<td class="text-left d-none">
														<div class="d-flex justify-content-between">
															{if $_oStaff.profile_id eq $profile_id && !$check_overTime}
															<div>
																<form action="" enctype="multipart/form-data">
																	<div class="ui-stack ui-stack--wrap d-flex justify-content-end" style="position:relative">
																		<input class="form-field form-control" type="file" name="image" onChange="$Core.course.upload_image(this,event)"  data-course_id="{$course_id}" style="position:absolute;opacity: 0">
																		<a href="javascript:void(0)" type="buton" class="">Sửa</a>
																	</div>
																</form>
															</div>
															{/if}
														</div>
													</td>
													{*<td><a href="{$item.driver_image}?usp=sharing" target="_blank">{$_oStaff.driver_image}?usp=sharing</a></td>*}
												</tr>
												{/foreach}
											{else}
												<tr class="tr">
													<td class="text-center" colspan="{if $deviceType ne 'phone'}4{else}3{/if}">
														Danh sách trống
													</td>
												</tr>
											{/if}
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="tabcontent_e3_{$uid}" role="tabpanel">
							<div class="widget-content">
								<div class="table-container no-shadow overflow-auto" style="max-height:50vh">
									<table cellpadding="0" cellspacing="0" class="table table-bordered mb-0" width="100%">
										<thead><tr>
											{if $deviceType ne 'phone'}
											<th class="align-center bg-lighter text-center" width="3%">STT</th>
											{/if}
											<th class="align-center bg-lighter text-left">Họ tên</th>
											<th class="align-center bg-lighter text-left">Phòng</th>
										</tr></thead>
										<tbody class="tbody_unreport_staffs_{$course_id}">
											{if !empty($list_unreport_staffs)}
												{foreach name=i from=$list_unreport_staffs key=_oKey item = _oStaff}
												<tr class="tr">
													{if $deviceType ne 'phone'}
													<td class="text-center">{$smarty.foreach.i.iteration}</td>
													{/if}
													<td>{$_oStaff.full_name}</td>
													<td>{$_oStaff.department_name}</td>
												</tr>
												{/foreach}
											{else}
												<tr class="tr">
													<td class="text-center" colspan="{if $deviceType ne 'phone'}3{else}2{/if}">
														Danh sách trống
													</td>
												</tr>
											{/if}
										</tbody>
									</table>
								</div>
							</div>
						</div>
						{else}
						<div class="tab-pane fade" id="tabcontent_e2_{$uid}" role="tabpanel">
							<div class="table-container no-shadow overflow-x-auto text-nowrap mb-2">
								<table id="table_{$uid}" cellpadding="0" cellspacing="0" class="table table-bordered mb-0" width="100%">
									<thead><tr>
										{if $deviceType ne 'phone'}
										<th class="align-center bg-lighter text-center" width="3%">No.</th>{/if}
										<th class="align-center bg-lighter text-left">Nhân viên</th>
										{if $clsISO->_DEV()}
											<th class="align-center bg-lighter text-left">SĐT</th>
											<th class="align-center bg-lighter text-left">Chức vụ</th>
										{/if}
										<th class="align-center bg-lighter text-center">Xác nhận</th>
										<th class="align-center bg-lighter text-center">Check-In</th>
										<th class="align-center bg-lighter text-center">TG.Check-In</th>
									</tr></thead>
									<tbody class="tbody_checkin_staffs_{$course_id}">
									{if !empty($list_staffs)}
										{foreach name=i from=$list_staffs key=_oKey item = _oStaff}
										<tr class="tr">
											{if $deviceType ne 'phone'}
											<td class="text-center">{$smarty.foreach.i.iteration}</td>
											{/if}
											
											{if $clsISO->_DEV()}
												<td class="text-left">{$_oStaff.full_name}</td>
												<td class="text-left">{$_oStaff.phone}</td>
												<td class="text-left">{$clsProperty->getCode($_oStaff.role_id)}-{$clsProperty->getTitle($_oStaff.department_id)}</td>
											{else}
												<td class="text-left">{$clsProfile->getIndentityV3($_oStaff.profile_id, true, $_oStaff)}</td>
											{/if}
											<td class="text-center">
												<i class="material-icons-outlined noExl">more_time</i>
												{$clsISO->convertTimeToText($_oStaff.reg_date, true)}
											</td>
											<td class="text-center">
												{if $_oStaff.checked_in eq '1'}
												<span class="text-success">Đã check-In</span>
												{else}
												<span class="text-muted">Chưa check-In</span>
												{/if}
											</td>
											<td class="text-center">
												{if $_oStaff.checked_in eq '1'}
													<i class="material-icons-outlined noExl">more_time</i>
													{$clsISO->convertTimeToText($_oStaff.checked_in_date, true)}
												{else}
													--
												{/if}
											</td>
										</tr>
										{/foreach}
									{else}
										<tr><td colspan="2" class="text-center">Danh sách trống</td></tr>
									{/if}
									</tbody>
								</table>
							</div>
							<div class="d-flex justify-content-center">
								<button type="button" data-toggle="ripple" class="btn btn-block btn-sm btn-outline-default" 
									onClick="$Core.util.export2excel(this, event)" table_id="{$uid}" table_name="List"> 
									<i class="bx bx-export"></i> Tải về File Excel
								</button>
							</div>
						</div>
						{/if}
					{/if}
				</div>
			</div>
		</div>
		<div class="modal-footer d-flex justify-content-center">
			<button class="btn btn-outline-default" type="button" data-bs-dismiss="modal" aria-label="Close">Đóng</button>
		</div>
	</div>
</div>

