<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTopTitle">Thông tin khách hàng</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body scroller">
				{if $deviceType eq 'phone'}
					<div class="card-body">
						<div class="lst_info d-flex flex-wrap">
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Họ tên:</span>
								<span class="text-break">
									{if $oneClient.full_name}
										{$oneClient.full_name}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Email:</span>
								<span class="text-break">
									{if $oneClient.email}
										{$oneClient.email}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Điện thoại:</span>
								<span class="text-break">
									{if $oneClient.phone}
										{$oneClient.phone}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Giới tính:</span>
								<span class="text-break">
									{if $oneClient.gender}
										{$oneClient.gender}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Ngày sinh:</span>
								<span class="text-break">
									{if $oneClient.birthday}
										{$oneClient.birthday}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">CMT/CCID:</span>
								<span class="text-break">
									{if $oneClient.identity_card}
										{$oneClient.identity_card}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Ngày cấp:</span>
								<span class="text-break">
									{if $oneClient.issuance_date}
										{$oneClient.issuance_date}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Nơi cấp:</span>
								<span class="text-break">
									{if $oneClient.issuance_location}
										{$oneClient.issuance_location}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
							<div class="item w-100 mb-2">
								<span class="fw-semibold mr-1">Địa chỉ thường trú:</span>
								<span class="text-break">
									{if $oneClient.address}
										{$oneClient.address}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
							<div class="item w-100 mb-2">
								<span class="fw-semibold mr-1">Địa chỉ hiện tại:</span>
								<span class="text-break">
									{if $oneClient.contact_address}
										{$oneClient.contact_address}
									{else}
										Chưa cập nhật
									{/if}
								</span>
							</div>
						</div>
					</div>
				{else}
				<div class="highlights">
					<div class="highlight-panel">
						<div class="highlight-list">
							<div class="highlight-item">
								<div class="highlight-label">Tên khách hàng</div>
								<div class="metadata-row-viewer">{$oneClient.full_name}</div>
							</div>
							<div class="highlight-item">
								<div class="highlight-label">Email</div>
								<div class="metadata-row-viewer text-bold">
									<a href="mailto:{$oneClient.email}">{$oneClient.email}</a>
								</div>
							</div>
							<div class="highlight-item">
								<div class="highlight-label">Điện thoại</div>
								<div class="metadata-row-viewer text-bold">
									<a href="tel:{$oneClient.phone}">{$oneClient.phone}</a>
								</div>
							</div>
							<div class="highlight-item">
							</div>
						</div>
						<div class="highlight-list">
							<div class="highlight-item">
								<div class="highlight-label">CMT/CCID</div>
								<div class="metadata-row-viewer">
									{if $oneClient.identity_card}
										{$oneClient.identity_card}
									{else}
										Chưa cập nhật
									{/if}
								</div>
							</div>
							<div class="highlight-item">
								<div class="highlight-label">Ngày sinh</div>
								<div class="metadata-row-viewer text-bold">
									{if $oneClient.birthday}
										{$oneClient.birthday}
									{else}
										Chưa cập nhật
									{/if}
								</div>
							</div>
							<div class="highlight-item">
								<div class="highlight-label">Giới tính</div>
								<div class="metadata-row-viewer text-bold">
									{if $oneClient.gender}
										{$oneClient.gender}
									{else}
										Chưa cập nhật
									{/if}
								</div>
							</div>
							<div class="highlight-item">
								<div class="highlight-label">Ngày cấp</div>
								<div class="metadata-row-viewer text-bold">
									{if $oneClient.issuance_date}
										{$oneClient.issuance_date}
									{else}
										Chưa cập nhật
									{/if}
								</div>
							</div>
						</div>
						<div class="highlight-list">
							<div class="highlight-item">
								<div class="highlight-label">Nơi cấp</div>
								<div class="metadata-row-viewer text-bold">
									{if $oneClient.issuance_location}
										{$oneClient.issuance_location}
									{else}
										Chưa cập nhật
									{/if}
								</div>
							</div>						
							<div class="highlight-item">
								<div class="highlight-label">Địa chỉ thường trú</div>
								<div class="metadata-row-viewer text-bold">
									{if $oneClient.address gt '0'}
										{$oneClient.address}
									{else}
										Chưa cập nhật
									{/if}
								</div>
							</div>
							<div class="highlight-item">
								<div class="highlight-label">Địa chỉ hiện tại</div>
								<div class="metadata-row-viewer text-bold">
									{if $oneClient.contact_address gt '0'}
										{$oneClient.contact_address}
									{else}
										Chưa cập nhật
									{/if}
								</div>
							</div>
							<div class="highlight-item">
							</div>
						</div>
					</div>
				</div>
				{/if}
				<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
					{assign var = tabid value = $clsISO->getUniqid()}
					<li class="nav-item"><button type="button" class="nav-link{if $tabfocus eq '1'} active{/if} d-none" role="tab" data-bs-toggle="tab" data-bs-target="#tabpanel-home-{$tabid}"><i class="material-icons-outlined">account_balance_wallet</i> Hoạt động</button></li>
					<li class="nav-item"><button type="button" class="nav-link{if $tabfocus eq '2'} active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#tabpanel-notes-{$tabid}"><i class="material-icons-outlined">speaker_notes</i> Các giao dịch</button></li>
				</ul>
				<div class="tab-content py-3 px-0">
					<div class="tab-pane fade{if $tabfocus eq '1'} show active{/if} d-none" id="tabpanel-home-{$tabid}" role="tabpanel">
						<div class="text-center">
							<button class="btn btn-outline-none" data-client_id="" onClick="$Core.client.addActivities(this,event)"><i class='bx bx-plus me-2'></i>Thêm hoạt động</button>
						</div>
						<div class="widget-block mt-3">
							<div class="widget-content">
								<div class="holder_notes_{$client_id}">
									<div class="card item_activities mb-3" style="background:#fffeef">
										<div class="card-header d-flex justify-content-between align-items-center">
											<div class="fs-14">
												<span class="fw-bold mr-3">Liên hệ với khách hàng</span> 
												<span class="text-muted">hôm nay, 02:57 pm</span>
											</div>
											<div class="staff avatar rounded-pill overflow-hidden"><img src="https://user.futurehomes.vn//images/BROKER/avatar/2024-04-26-06-50-39-bg_achievements.jpg" alt=""></div>
										</div>
										<div class="card-body d-flex">
											<div class="crm-timeline__card-logo_container">
												<div class="crm-timeline__card-logo --undefined" style="--crm-timeline__logo-background: #FFF1D6;">
													<div class="crm-timeline__card-logo_content">
														<div class="crm-timeline__calendar-icon-container">
															<!---->
															<div class="crm-timeline__calendar-icon">
																<header class="crm-timeline__calendar-icon_top">
																	<div class="crm-timeline__calendar-icon_bullets">
																		<div class="crm-timeline__calendar-icon_bullet"></div>
																		<div class="crm-timeline__calendar-icon_bullet"></div>
																	</div>
																</header>
																<main class="crm-timeline__calendar-icon_content">
																	<div class="crm-timeline__calendar-icon_day">20</div>
																	<div class="crm-timeline__calendar-icon_month">tháng 6</div>
																	<div class="crm-timeline__calendar-icon_date"><span class="crm-timeline__calendar-icon_day-week">T5</span><span class="crm-timeline__calendar-icon_time">03:00 pm</span></div>
																</main>
															</div>
														</div>
														<!---->
													</div>
												</div>
											</div>
											<div class="content_activities">
												<div class="time_end p-2 fs-12 d-inline-block mb-2"><span class="text-muted">Hạn chót</span> T5, 20 tháng 6, 03:00 pm</div>
												<div class="activities_intro w-100 border mb-2">
													Gọi điện tư vấn chăm sóc khách hàng
												</div>
												<div class="notes mb-3">
													<div class="checklist-item-details js-checkitem">
														<div class="checklist-item-row js-checkitem-row">
															<div class="checklist-item-text-and-controls">
																<div onclick="edit_issue_task(this,event)" toid="666bc7278cfa8103898298" class="checklist-item-details-text">sdfsdfdf</div>
																<a href="javascript:void(0);" onclick="delete_issue_task(this,event)" issue_task_id="417" issue_id="184" data-bs-toggle="tooltip" title="" class="checklist-item-delete-button" data-bs-original-title="Xóa" aria-label="Xóa"><i class="bx bx-trash-alt"></i></a>
																<div class="d-flex align-items-center justiry-content-between">
																	<span class="d-none d-lg-block text-muted fs-12 mr-2"><i style="transform:translateY(5px);" class="material-icons-outlined">more_time</i>
																		10/06/2024 17:12
																	</span>
																	<span class="d-none d-lg-block text-muted fs-12 mr-2"><i style="transform:translateY(5px);" class="material-icons-outlined">more_time</i>
																		10/06/2024 17:12
																	</span>
																	<span class="d-flex text-muted align-items-center fs-12">
																		<img class="avatar avatar-xxs mr-2 rounded-pill" src="/images/BROKER/avatar/2024-04-26-06-50-39-bg_achievements.jpg">FH0079-Trần  Văn Trường
																	</span>
																</div>
															</div>
															<form class="666bc7278cfa8103898298 checklist-new-item js-new-checklist-item d-none" method="POST" enctype="multipart/form-data">
																<textarea class="form-control autosize textarea_issue_task_666bc7278cfa8103898298 hasIsoRedactor" name="content" placeholder="Nhập nội dung" onkeyup="set_fireEvent(this,event)" toid="666bc7278cfa8103898298" rows="2" style="overflow: hidden; overflow-wrap: break-word; resize: none;">sdfdsfdsfd</textarea>
																<div id="issue_attachments_file_666bc7278cfa8103898298" class="py-2 MultiFile-preview"></div>
																<input id="issue_task_upload_file_666bc7278cfa8103898298" uid="666bc7278cfa8103898298" type="file" class="issue_task_upload_file d-none" name="attachments[]" multiple="multiple">
																<div class="checklist-add-controls mt-2 clearfix">
																	<button type="button" class="btn btn-primary js-add-task confirm mod-submit-edit js-save-edit" onclick="save_add_task(this, event)" issue_task_id="417" issue_id="184" toid="666bc7278cfa8103898298">Cập nhật</button>
																	<button type="button" class="btn btn-link js-upload-edit btn-only-icon" onclick="issue_task_upload(this, event)" action="_edit" issue_id="184" toid="666bc7278cfa8103898298"><i class="material-icons-outlined no-translate">attach_file</i></button>
																	<button type="button" class="btn btn-link js-cancel-task js-cancel-edit btn-only-icon" onclick="cancel_add_task(this, event)" action="_edit" issue_id="184" toid="666bc7278cfa8103898298"><i class="material-icons-outlined no-translate">close</i></button>
																</div>
															</form>
														</div>
													</div>
												</div>
												<div class="d-flex justify-content-between align-items-center">
													<div class="d-flex align-items-center">
														<button class="btn btn-sm btn-outline-primary me-2">Đã hoàn tất</button>
														<button class="btn btn-sm btn-outline-default">Sửa</button>
													</div>
													<div class="dropdown">
														<button class="btn btn-icon dropdown-toggle hide-arrow" type="button" id="dropdownMenuClickableInside" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><i class="bx bx-dots-vertical-rounded" data-trigger="hover"></i></button>
														<ul class="dropdown-menu w-px-100" aria-labelledby="dropdownMenuClickableInside">
															<li class="dropdown-item">
																<a class="dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false">Trì hoãn<i class='bx bx-chevron-right' ></i></a>
																<ul class="dropdown-menu" style="">
																	<li><a class="dropdown-item" href="javascript:void(0);">1 giờ</a></li>
																	<li><a class="dropdown-item" href="javascript:void(0);">2 giờ</a></li>
																	<li><a class="dropdown-item" href="javascript:void(0);">3 giờ</a></li>
																	<li><a class="dropdown-item" href="javascript:void(0);">1 ngày</a></li>
																	<li><a class="dropdown-item" href="javascript:void(0);">2 ngày</a></li>
																	<li><a class="dropdown-item" href="javascript:void(0);">3 ngày</a></li>
																</ul>
															</li>
															<li><a class="dropdown-item" href="javascript:void(0);" onclick="delete_issue(this,event)" issue_id="184">Xóa</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="loader p-5 text-center">Loading...</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade{if $tabfocus eq '2'} show active{/if}" id="tabpanel-notes-{$tabid}" role="tabpanel">
						<div class="widget-block" id="group_bill">		
							<div class="table-responsive dragscroll text-nowrap" style="overflow-x: auto;">
								<table class="table table-striped mb-2" width="100%">
									<thead><tr>
										<th class="align-center" width="90px">Mã GD</th>
										<th class="align-center" width="100px">Mã căn</th>
										<th class="align-center" width="100px">Ngày cọc</th>
										<th class="align-center" width="100px">Ngày ký HĐMB</th>
										<th class="align-center" width="200px">Sale bán</th>
										<th class="align-center text-right" width="130px">Doanh số</th>
										<th class="align-center text-right" width="130px">Ủy nhiệm chi</th> 
									</tr> </thead>
									<tbody class="table-border-bottom-0">					
										{foreach from=$listBilling item=oneBilling name=i}
											<tr class="trBilling" >
												<td data-label="Mã GD"><a href="javascript:void(0)" onclick="view_billing(this, event)" billing_id="{$oneBilling.billing_id}">{$oneBilling.billing_code}</a></td>
												<td data-label="Mã căn">
													<a target="_blank" href="/{$oneBilling.stock_code}.html">
														{$clsISO->makeIcon('bx-link-external', $oneBilling.stock_code)}
													<a/>
												</td>
												<td data-label="Ngày cọc">
													{if !empty($oneBilling.deposit_date)}
														{$clsISO->convertTimeToText($oneBilling.deposit_date)}
													{else}
														--
													{/if}
												</td>
												<td data-label="Ngày ký HĐMB">
													{if $oneBilling.estimate_date gt '0'}
														{$clsISO->convertTimeToText($oneBilling.estimate_date)}
													{else}
														-- 
													{/if}
												</td>
												<td data-label="Nhân viên" class="text-wrap">{$clsProfile->getIndentity($oneBilling.staff_id)}</td>
												<td data-label="Tổng tiền GD" class="text-right">
													{if !empty($oneBilling.totalgrand)}
														{$clsISO->formatNumberToEasyRead($oneBilling.totalgrand)} {$clsISO->getRate()}
													{else}
														--
													{/if}
												</td>
												<td data-label="Uỷ nhiệm chi" class="text-right">
													{if !empty($more_information.payment_order)}
														<ul class="list-unstyled mb-1">	
															{foreach from = $more_information.payment_order item = _olink}
															<li><a class="download" target="_blank" data-fancybox="true" href="{$clsISO->getGoogleUrl($_olink.image)}">{$_olink.title}</a></li>
															{/foreach}
														</ul>
													{/if}
												</td>
											</tr>
										{/foreach}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>