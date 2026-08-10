<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
	<div class="modal-content overflow-visible">
		<div class="modal-header align-items-center justify-content-between py-2 border-bottom">
			<div class="d-flex align-items-center gap-2">
				<i class="bx bx-user-pin text-primary fs-4"></i>
				<h5 class="modal-title mb-0 fs-16 fw-bold">{$oneCustomer.name}</h5>
			</div>
			<div class="d-flex align-items-center gap-2">
				<button type="button" class="btn-close " data-bs-dismiss="modal" route="/crm/" aria-label="Close"></button>
			</div>
		</div>
		<div class="modal-body bg-lightest p-3">
			<!-- Khối check trùng SĐT -->
			<div class="build" customer_id="{$customer_id}" id="{$clsISO->getUniqid()}"
				data-url="/index.php?mod={$mod}&act=load_duplicates"
				data-options='{ldelim}"customer_id":{$customer_id}{rdelim}'></div>
			<div class="row g-3">
				<!-- Cột trái: Sidebar thông tin khách hàng -->
				<div class="col-12 col-lg-4 col-xl-3">
					<div class="crm-detail-sidebar d-flex flex-column h-100">
						<!-- Card Avatar + Tên + Hành động nhanh -->
						<div class="crm-card p-3 d-flex flex-column gap-3">
							<div class="d-flex align-items-center gap-3">
								<form method="POST" id="frmIssue" class="m-0 position-relative" enctype="multipart/form-data" style="width: 48px; height: 48px; flex-shrink: 0;">
									<div class="avatar position-relative w-100 h-100 crm-sidebar-avatar-container">
										<img id="avatar_{$customer_id}" class="w-100 h-100 rounded-circle border shadow-sm" src="{$oneCustomer.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" />
										<input type="file" id="selectFile" customer_id="{$customer_id}" class="d-none" name="avatar">
										<a href="javascript:void(0);" customer_id="{$customer_id}" class="camera position-absolute top-0 start-0 w-100 h-100 rounded-circle d-flex align-items-center justify-content-center text-white" 
											onclick="$Core.crm.file_explorer(this,event)" 
											toId="selectFile" toImg="avatar_{$customer_id}">
											<i class="bx fs-12 bx-camera"></i>
										</a>
									</div>
								</form>
								<div class="d-flex flex-column text-start min-w-0">
									<h5 class="mb-0 fw-bold text-dark fs-15 text-truncate" style="color: #344054 !important;">{$oneCustomer.name}</h5>
									<div class="text-muted fs-11 text-truncate mt-1"><i class="bx bx-user-voice me-1" style="color: #667085;"></i>Phụ trách: {if !empty($oneCustomer.admin_id)}{$clsProfile->getIndentity($oneCustomer.admin_id, false)}{else}—{/if}</div>
								</div>
							</div>
							<!-- Action buttons Gọi / Zalo -->
							{if !empty($oneCustomer.phone)}
							<div class="crm-sidebar-actions">
								<a href="tel:{$oneCustomer.phone}" class="btn btn-sm btn-outline-success"><i class="bx bx-phone-call"></i> Gọi</a>
								<a href="https://zalo.me/{$oneCustomer.phone}" target="_blank" class="btn btn-sm btn-outline-info"><i class="bx bx-message-rounded"></i> Zalo</a>
							</div>
							{/if}
						</div>
						<!-- Card Vitals (Lead Score, Trạng thái...) -->
						<div class="crm-card p-3">
							<div class="crm-zone mb-2"><i class="bx bx-chart"></i> Chỉ số vitals</div>
							<div class="crm-vitals-sidebar">
								<div class="crm-vital-card">
									<span class="crm-vital-card-k"><i class="bx bxs-hot text-warning"></i> Lead Score</span>
									<span class="crm-vital-card-v text-warning">{if !empty($more_information.lead_score)}{$more_information.lead_score}{else}72{/if}</span>
								</div>
								<div class="crm-vital-card">
									<span class="crm-vital-card-k"><i class="bx bx-flag text-primary"></i> Giai đoạn</span>
									<span class="crm-vital-card-v text-truncate">{if $oneCustomer.status_id gt 0}{$clsProperty->getTitle($oneCustomer.status_id)}{else}Đã tư vấn{/if}</span>
								</div>
								<div class="crm-vital-card">
									<span class="crm-vital-card-k"><i class="bx bx-tachometer text-primary"></i> Khả năng</span>
									<span class="crm-vital-card-v">{if !empty($more_information.lead_score)}{$more_information.lead_score}%{else}48%{/if}</span>
								</div>
								<div class="crm-vital-card">
									<span class="crm-vital-card-k"><i class="bx bx-time text-info"></i> Pipeline</span>
									<span class="crm-vital-card-v">{if !empty($oneCustomer.reg_date)}{$clsISO->getTimeAgo($oneCustomer.reg_date)}{else}12 ngày{/if}</span>
								</div>
							</div>
							<div class="mt-2 text-center text-muted fs-11"><i class="bx bx-history"></i> Chạm gần nhất: {if !empty($oneCustomer.upd_date)}{$clsISO->getTimeAgo($oneCustomer.upd_date)}{else}2 ngày trước{/if}</div>
						</div>
						<!-- Card Thông tin liên hệ -->
						<div class="crm-card p-3">
							<div class="crm-zone mb-2"><i class="bx bx-id-card"></i> Thông tin liên hệ</div>
							<div class="crm-card-b p-0">
								<div class="crm-fld">
									<div class="crm-fld-k">Họ và tên</div>
									<div class="crm-fld-v metadata-row-editable InputCRMHandler">
										<div class="metadata-row-editable-triggerArea">{$oneCustomer.name}</div>
										{if $permiss_edit eq '1'}
										<a class="metadata-row-editable-button editInlineField"
											onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'name', p_id:{$customer_id}{rdelim})"
											p_field="name" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
										{/if}
									</div>
								</div>
								<div class="crm-fld mt-2">
									<div class="crm-fld-k">Điện thoại</div>
									<div class="crm-fld-v metadata-row-editable InputCRMHandler">
										<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.phone)}{$clsCustomer->mask($oneCustomer.phone, true)}{/if}</div>
										{if $permiss_edit eq '1'}
										<a class="metadata-row-editable-button editInlineField"
											onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'phone', p_id:{$customer_id}{rdelim})"
											p_field="phone" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
										{/if}
									</div>
								</div>
								<div class="crm-fld mt-2">
									<div class="crm-fld-k">E-mail</div>
									<div class="crm-fld-v metadata-row-editable InputCRMHandler">
										<div class="metadata-row-editable-triggerArea">{$oneCustomer.email}</div>
										{if $permiss_edit eq '1'}
										<a class="metadata-row-editable-button editInlineField"
											onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'email', p_id:{$customer_id}{rdelim})"
											p_field="email" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
										{/if}
									</div>
								</div>
								<div class="crm-fld mt-2">
									<div class="crm-fld-k">Địa chỉ</div>
									<div class="crm-fld-v metadata-row-editable InputCRMHandler">
										<div class="metadata-row-editable-triggerArea">{$oneCustomer.address}</div>
										{if $permiss_edit eq '1'}
										<a class="metadata-row-editable-button editInlineField"
											onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'address', p_id:{$customer_id}{rdelim})"
											p_field="address" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
										{/if}
									</div>
								</div>
								<div class="crm-fld mt-2">
									<div class="crm-fld-k">Ngày sinh</div>
									<div class="crm-fld-v metadata-row-editable InputCRMHandler">
										<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.birthday)}{$clsISO->convertTimeToText($oneCustomer.birthday)}{/if}</div>
										{if $permiss_edit eq '1'}
										<a class="metadata-row-editable-button editInlineField"
											onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'birthday', p_id:{$customer_id}{rdelim})"
											p_field="birthday" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
										{/if}
									</div>
								</div>
							</div>
							<!-- Mạng xã hội -->
							<div class="crm-zone mt-3 mb-2"><i class="bx bx-share-alt"></i> Mạng xã hội</div>
							<div class="crm-card-b p-0">
								<div class="crm-fld">
									<div class="crm-fld-k"><i class="bx bxl-facebook-circle text-primary"></i> Facebook</div>
									<div class="crm-fld-v metadata-row-editable InputCRMHandler">
										<div class="metadata-row-editable-triggerArea">{if !empty($more_information.facebook)}{$more_information.facebook}{/if}</div>
										{if $permiss_edit eq '1'}
										<a class="metadata-row-editable-button editInlineField"
											onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'facebook', p_id:{$customer_id}{rdelim})"
											p_field="facebook" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
										{/if}
									</div>
								</div>
								<div class="crm-fld mt-2">
									<div class="crm-fld-k"><i class="bx bxl-tiktok text-dark"></i> Tiktok</div>
									<div class="crm-fld-v metadata-row-editable InputCRMHandler">
										<div class="metadata-row-editable-triggerArea">{if !empty($more_information.tiktok)}{$more_information.tiktok}{/if}</div>
										{if $permiss_edit eq '1'}
										<a class="metadata-row-editable-button editInlineField"
											onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'tiktok', p_id:{$customer_id}{rdelim})"
											p_field="tiktok" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
										{/if}
									</div>
								</div>
								<div class="crm-fld mt-2">
									<div class="crm-fld-k"><i class="bx bxl-linkedin-square text-info"></i> Linkedin</div>
									<div class="crm-fld-v metadata-row-editable InputCRMHandler">
										<div class="metadata-row-editable-triggerArea">{if !empty($more_information.linkedin)}{$more_information.linkedin}{/if}</div>
										{if $permiss_edit eq '1'}
										<a class="metadata-row-editable-button editInlineField"
											onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'linkedin', p_id:{$customer_id}{rdelim})"
											p_field="linkedin" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
										{/if}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Cột phải: Content chính chia theo các Tabs -->
				<div class="col-12 col-lg-8 col-xl-9">
					<div class="crm-detail-tabs">
						<!-- Điều hướng Tab Pills -->
						<ul class="nav nav-pills nav-fill mb-3" id="crmCustomerTab_{$customer_id}" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="crm-tab-overview-btn_{$customer_id}" data-bs-toggle="tab" data-bs-target="#crm-tab-overview_{$customer_id}" type="button" role="tab" aria-controls="crm-tab-overview_{$customer_id}" aria-selected="true">
									<i class="bx bx-calendar-event me-1"></i>Tổng quan &amp; Ghi chú
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="crm-tab-needs-btn_{$customer_id}" data-bs-toggle="tab" data-bs-target="#crm-tab-needs_{$customer_id}" type="button" role="tab" aria-controls="crm-tab-needs_{$customer_id}" aria-selected="false">
									<i class="bx bx-home-heart me-1"></i>Nhu cầu BĐS
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="crm-tab-transactions-btn_{$customer_id}" data-bs-toggle="tab" data-bs-target="#crm-tab-transactions_{$customer_id}" type="button" role="tab" aria-controls="crm-tab-transactions_{$customer_id}" aria-selected="false">
									<i class="bx bx-wallet me-1"></i>Giao dịch &amp; Hồ sơ
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="crm-tab-contacts-btn_{$customer_id}" data-bs-toggle="tab" data-bs-target="#crm-tab-contacts_{$customer_id}" type="button" role="tab" aria-controls="crm-tab-contacts_{$customer_id}" aria-selected="false">
									<i class="bx bx-group me-1"></i>Người liên quan &amp; Phân loại
								</button>
							</li>
						</ul>
						<!-- Nội dung các Tab -->
						<div class="tab-content p-0 bg-transparent border-0" id="crmCustomerTabContent_{$customer_id}">
							<!-- Tab 1: Tổng quan & Ghi chú -->
							<div class="tab-pane fade show active" id="crm-tab-overview_{$customer_id}" role="tabpanel" aria-labelledby="crm-tab-overview-btn_{$customer_id}">
								<!-- Hành trình bán hàng ngang -->
								<div class="crm-card p-3">
									<div class="d-flex justify-content-between align-items-center mb-2">
										<span class="fw-bold text-dark"><i class="bx bx-trip text-primary me-1"></i>Hành trình khách hàng</span>
										<span class="badge bg-label-primary"><i class="bx bx-tachometer me-1"></i>Khả năng chốt: {if !empty($more_information.lead_score)}{$more_information.lead_score}{else}48{/if}%</span>
									</div>
									<div class="crm-stepper-horizontal">
										<div class="crm-step-item completed">
											<div class="crm-step-circle"><i class="bx bx-check"></i></div>
											<div class="crm-step-label">Lead mới</div>
											<small class="text-muted fs-10 mt-1">{if !empty($oneCustomer.reg_date)}{$oneCustomer.reg_date|date_format:'%d/%m/%Y'}{else}02/03{/if}</small>
										</div>
										<div class="crm-step-item active">
											<div class="crm-step-circle"><i class="bx bx-flag"></i></div>
											<div class="crm-step-label">{if $oneCustomer.status_id gt 0}{$clsProperty->getTitle($oneCustomer.status_id)}{else}Đã tư vấn{/if}</div>
											<small class="text-primary fs-10 mt-1">hiện tại</small>
										</div>
										<div class="crm-step-item">
											<div class="crm-step-circle">3</div>
											<div class="crm-step-label">Xem nhà</div>
										</div>
										<div class="crm-step-item">
											<div class="crm-step-circle">4</div>
											<div class="crm-step-label">Đặt cọc &amp; Ký HĐ</div>
										</div>
									</div>
								</div>
								<!-- Ghi chú chăm sóc -->
								<div class="crm-card">
									<div class="crm-card-h"><i class="bx bx-note text-primary"></i> Nhật ký &amp; Ghi chú chăm sóc</div>
									<div class="crm-card-b">
										{if $permiss_action eq '1' || $permiss_notes eq '1'}
										<form class="frmIssue mb-3" action="">
											<textarea class="form-control form-control-sm border shadow-none" name="content" rows="3"
												placeholder="Nhập nội dung ghi chú chăm sóc khách hàng..."></textarea>
											<div class="text-end mt-2">
												<button type="button" tp="_create" class="btn btn-sm btn-primary"
													for_id="{$customer_id}" clsTable="Customer" note_id=""
													onClick="$Core.helper.save_notes(this,event)"><i class="bx bx-paper-plane me-1"></i>Lưu ghi chú</button>
											</div>
										</form>
										{/if}
										<div class="holder_notes_{$customer_id}">
											<div class="p-3 text-center text-muted">Đang tải lịch sử ghi chú...</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Tab 2: Nhu cầu BĐS -->
							<div class="tab-pane fade" id="crm-tab-needs_{$customer_id}" role="tabpanel" aria-labelledby="crm-tab-needs-btn_{$customer_id}">
								<div class="crm-card">
									<div class="crm-card-h"><i class="bx bx-building-house text-primary"></i> Thông tin nhu cầu Bất động sản</div>
									<div class="crm-card-b crm-card-b--grid2">
										<div class="crm-fld">
											<div class="crm-fld-k">Loại hình</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if $oneCustomer.blocktype_id gt 0}{$clsProperty->getTitle($oneCustomer.blocktype_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'blocktype_id', p_id:{$customer_id}{rdelim})"
													p_field="blocktype_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Phòng ngủ</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.list_bedroom_id)}{$clsProperty->getTitleArray($oneCustomer.list_bedroom_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'list_bedroom_id', p_id:{$customer_id}{rdelim})"
													p_field="list_bedroom_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Mục đích</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.list_purpose_id)}{$clsProperty->getTitleArray($oneCustomer.list_purpose_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'list_purpose_id', p_id:{$customer_id}{rdelim})"
													p_field="list_purpose_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Dự án / Phân khu</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.list_block_id)}{$clsSetting->getTitleArray($oneCustomer.list_block_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'list_block_id', p_id:{$customer_id}{rdelim})"
													p_field="list_block_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Nhu cầu chi tiết</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.list_need_id)}{$clsProperty->getTitleArray($oneCustomer.list_need_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'list_need_id', p_id:{$customer_id}{rdelim})"
													p_field="list_need_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Quỹ căn chào khách</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.list_stock_id)}{$clsStock->getTitleArray($oneCustomer.list_stock_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'list_stock_id', p_id:{$customer_id}{rdelim})"
													p_field="list_stock_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
									</div>
								</div>
								<div class="crm-card">
									<div class="crm-card-h justify-content-between">
										<span><i class="bx bx-target-lock text-primary"></i> Danh sách Nhu cầu khách hàng</span>
										<button type="button" onClick="$Core.crm.open_need(this, event)"
											customer_id="{$customer_id}" need_id="" class="btn btn-outline-primary crm-btn-action">
												<i class="bx bx-plus"></i> Thêm nhu cầu
										</button>
									</div>
									<div class="crm-card-b p-0 overflow-x-auto">
										<table cellpadding="0" cellspacing="0" class="table table-hover mb-0 align-middle" width="100%">
											<thead><tr class="fs-12 text-muted">
												<th class="bg-white text-nowrap">Tiêu đề</th>
												<th class="bg-white text-nowrap">Loại hình</th>
												<th class="bg-white text-nowrap">Khoảng giá</th>
												<th class="bg-white" width="48px"></th>
											</tr></thead>
											<tbody class="holder_needs_{$customer_id}">
												<tr>
													<td class="text-center" colspan="4">Đang tải danh sách nhu cầu...</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- Tab 3: Giao dịch & Hồ sơ -->
							<div class="tab-pane fade" id="crm-tab-transactions_{$customer_id}" role="tabpanel" aria-labelledby="crm-tab-transactions-btn_{$customer_id}">
								<!-- Giao dịch (Billing list) -->
								<div class="crm-card">
									{assign var=gId value=$clsISO->getUniqid()}
									<div class="crm-card-h">
										<span><i class="bx bx-receipt text-primary"></i> Danh sách Giao dịch</span>
									</div>
									<div class="crm-card-b p-0">
										<div id="{$gId}" customer_id="{$customer_id}" class="build list_billings"
											data-url="/index.php?mod={$mod}&act=load_list_billing"
											data-options='{ldelim}"customer_id":{$customer_id}{rdelim}' data-chart="true">
											<div class="p-4 text-muted text-center">Đang tải lịch sử giao dịch...</div>
										</div>
									</div>
								</div>
								<!-- File đính kèm -->
								<div class="crm-card">
									<div class="crm-card-h justify-content-between">
										<span><i class="bx bx-paperclip text-primary"></i> Tài liệu đính kèm</span>
										<div class="d-flex align-items-center gap-1">
											<div class="input-group input-group-merge w-px-150">
												<span class="input-group-text"><i class="bx bx-search"></i></span>
												<input class="form-control" onkeyup="$Core.crm.iso_search_field(this, event)" toclass="iso_search_item" 
													placeholder="Tìm kiếm tài liệu...">
											</div>
											<button type="button" onClick="$Core.crm.open_file(this, event)" for_id="{$customer_id}"
												class="btn btn-icon btn-outline-primary crm-btn-action btn-icon-only" title="Thêm file"><i class="bx bx-plus"></i></button>
										</div>
									</div>
									<div class="crm-card-b p-0">
										<div class="holder_files_{$customer_id} overflow-x-auto">
											<div class="p-3 text-center text-muted">Đang tải danh sách tài liệu...</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Tab 4: Người liên hệ & Phân loại thêm -->
							<div class="tab-pane fade" id="crm-tab-contacts_{$customer_id}" role="tabpanel" aria-labelledby="crm-tab-contacts-btn_{$customer_id}">
								<!-- Nguồn và phân loại bổ sung -->
								<div class="crm-card">
									<div class="crm-card-h"><i class="bx bx-broadcast text-primary"></i> Nguồn &amp; Phân loại chi tiết</div>
									<div class="crm-card-b crm-card-b--grid2">
										<div class="crm-fld">
											<div class="crm-fld-k">Nguồn gốc</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if $oneCustomer.resource_id gt 0}{$clsProperty->getTitle($oneCustomer.resource_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'resource_id', p_id:{$customer_id}{rdelim})"
													p_field="resource_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Chiến dịch marketing</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.list_campaign_id)}{$clsCampaign->getTitleArray($oneCustomer.list_campaign_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'list_campaign_id', p_id:{$customer_id}{rdelim})"
													p_field="list_campaign_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Tình trạng chăm sóc</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if $oneCustomer.status_id gt 0}{$clsProperty->getTitle($oneCustomer.status_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'status_id', p_id:{$customer_id}{rdelim})"
													p_field="status_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Nhóm phân loại</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if $oneCustomer.list_type_id}{$clsProperty->getTitleArray($oneCustomer.list_type_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'list_type_id', p_id:{$customer_id}{rdelim})"
													p_field="list_type_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Tài chính (tầm giá)</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if $oneCustomer.finance_id gt 0}{$clsProperty->getTitle($oneCustomer.finance_id)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'finance_id', p_id:{$customer_id}{rdelim})"
													p_field="finance_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Nhu cầu ban đầu</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.begin_need)}{$oneCustomer.begin_need}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'begin_need', p_id:{$customer_id}{rdelim})"
													p_field="begin_need" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
										<div class="crm-fld">
											<div class="crm-fld-k">Nhân sự phụ trách</div>
											<div class="crm-fld-v metadata-row-editable InputCRMHandler">
												<div class="metadata-row-editable-triggerArea">{if !empty($oneCustomer.admin_id)}{$clsProfile->getIndentity($oneCustomer.admin_id, false)}{/if}</div>
												{if $permiss_edit eq '1'}
												<a class="metadata-row-editable-button editInlineField"
													onClick="$Core.crm.editInlineField(this,{ldelim}p_field:'admin_id', p_id:{$customer_id}{rdelim})"
													p_field="admin_id" p_id="{$customer_id}"><i class="bx bx-pencil"></i></a>
												{/if}
											</div>
										</div>
									</div>
								</div>
								<!-- Bàn giao khách -->
								{if $assign_track}
								<div class="crm-card">
									<div class="crm-card-h"><i class="bx bx-transfer-alt text-primary"></i> Nhật ký bàn giao khách hàng</div>
									<div class="crm-card-b d-flex flex-column gap-2">
										{foreach from=$assign_track item=_t}
										<div class="crm-handover-item">
											<div class="d-flex align-items-center justify-content-between">
												<span class="crm-handover-recipient fw-bold text-dark fs-13">
													<i class="bx bx-user-circle me-1 text-primary"></i>
													{$_t.recipient_name|escape}
												</span>
												<span class="fs-11 text-muted">
													Giao {$_t.assigned_ago}{if $_t.assigner_name} bởi {$_t.assigner_name|escape}{/if}
												</span>
											</div>
											<div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light">
												<div>
													{if $_t.is_confirmed}
														<span class="badge bg-label-success fs-10"><i class="bx bx-check-circle me-1"></i>Đã nhận</span>
													{else}
														<span class="badge bg-label-warning fs-10"><i class="bx bx-time-five me-1"></i>Chờ nhận</span>
													{/if}
												</div>
												{if $_t.is_confirmed}
												<span class="fs-11 text-muted">
													Nhận {$_t.confirmed_ago}
												</span>
												{/if}
											</div>
										</div>
										{/foreach}
									</div>
								</div>
								{/if}
								<!-- Người liên hệ phụ -->
								<div class="crm-card">
									<div class="crm-card-h justify-content-between">
										<span><i class="bx bx-user-voice text-primary"></i> Người liên hệ liên quan</span>
										<button type="button" onClick="$Core.crm.open_contact(this, event)" customer_id="{$customer_id}" class="btn btn-outline-primary crm-btn-action">
											<i class="bx bx-plus"></i> Thêm liên hệ
										</button>
									</div>
									<div class="crm-card-b p-0 overflow-x-auto">
										<table cellpadding="0" cellspacing="0" class="table table-hover mb-0 align-middle" width="100%">
											<thead><tr class="fs-12 text-muted">
												<th class="bg-white text-nowrap">Họ và tên</th>
												<th class="bg-white text-nowrap">Điện thoại</th>
												<th class="bg-white" width="48px"></th>
											</tr></thead>
											<tbody class="holder_contact_{$customer_id}">
												<tr>
													<td class="text-center" colspan="3">Đang tải người liên hệ...</td>
												</tr>
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
	</div>
</div>