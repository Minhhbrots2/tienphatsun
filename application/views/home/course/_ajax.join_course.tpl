<div id="{$uid}" class="modal fade show" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center{if $deviceType eq 'phone'} flex-wrap justify-content-end{else} justify-content-between{/if}">
				<div class="modal-header__left position-relative{if $deviceType eq 'phone'} mb-2 w-100{else} w-80{/if}">
					<h5 class="modal-title fs-20{if $deviceType eq 'phone'} w-100{/if}">
						{$clsCourse->getField($course_id,'title',$oneCourse)}
					</h5>
					{if $clsISO->checkPermission('view_all_course') || $itemCourse.user_id eq $profile_id || $clsISO->checkPermissionGroup('SALE_DIRECTOR')}
						{if $oneCourse.cat_id eq $smarty.const._MEDIA_DISSEMINATION}
						<span class="text-muted">Tổng cộng có {$total_report_staffs} người đã báo cáo</span>
						{/if}
					{/if}
					
				</div>
				<button type="button" class="btn-close ml-2 {if $deviceType eq 'phone'}position-absolute{/if}" data-bs-dismiss="modal" aria-label="Close" {if $deviceType eq 'phone'}style="right:10px;top: 27px"{else}style="margin-top: 0;margin-right: 0"{/if}></button>
			</div>
			<div class="modal-body scroll-y-auto-hover" style="height:calc(100vh - 80px)">
				<div class="content-wrapper">
					<div class="tab-content p-0" id="tab_content_{$uid}">
						{if $clsISO->checkPermission('view_all_course') || $itemCourse.user_id eq $profile_id || $clsISO->checkPermissionGroup('SALE_DIRECTOR')}
							{if $oneCourse.cat_id eq $smarty.const._MEDIA_DISSEMINATION}
							<div class="tab-pane fade show active" id="tabcontent_e2_{$uid}" role="tabpanel">
								<div class="widget-content">
									<div class="table-wrapper overflow-auto">
										<table class="table table-bordered" width="100%">
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
														<td class="text-left">{$_oStaff.full_name}</td>
														<td class="text-left">{$_oStaff.department_name}</td>
														<td class="text-left">{$clsISO->convertTimeToText($_oStaff.time,1)}</td>
														<td class="text-center">
															<a class=" mb-0" data-fancybox href="{$_oStaff.image}">
																<img src="{$_oStaff.image}" width="50" alt="">
															</a>
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
							{/if}
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>