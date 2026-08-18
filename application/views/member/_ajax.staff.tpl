<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content overflow-hidden bg-grayter">
			<div class="modal-header bg-white gap-3 align-items-center justify-content-between">
				<div class="d-flex align-items-center gap-2 mb-lg-0">
					<form method="POST" id="frmIssue" class="mr-2" enctype="multipart/form-data">
						<div class="avatar avatar-lg position-relative">
							<img id="avatar_{$_profile_id}" class="w-100 h-100 rounded-circle" src="{$dbProfile.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" />
							<input type="file" id="selectFile_{$uid}" onChange="$Core.member.file_upload(this, event)" profile_id="{$_profile_id}" class="d-none" name="avatar" toImg="avatar_{$_profile_id}" >
							<a href="javascript:void(0);" profile_id="{$_profile_id}" class="camera" onclick=
							"$Core.member.file_explorer(this,event)" toId="selectFile_{$uid}"><i class="bx bx-camera fs-12"></i></a>
						</div>
					</form>
					<div class="meta__info">
						<h5 class="modal-title mb-0 fs-5 fw-bold">{$dbProfile.full_name}</h5>
						<div class="d-flex gap-3 align-items-center">
							<div class="highlight-item">
								<small class="text-muted text-left">Mã NV</small>
								<div class="metadata-row-viewer">{$dbProfile.code}</div>
							</div>
							<div class="highlight-item">
								<small class="text-muted text-left">Tình trạng</small>
								<div class="metadata-row-viewer">
									{$clsProperty->getTitle($dbProfile.status_id)}
								</div>
							</div>
							<div class="text-muted d-none d-lg-block">
								<small class="text-muted text-left">Cập nhật lần cuối</small>
								<div class="metadata-row-viewer">{$clsISO->convertTimeToText($dbProfile.upd_date, true)}</div>
							</div>				
						</div>
					</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body scroller" style="background:rgb(245,245,245) !important"><div class="form-row">
				<div class="col-12 col-md-8">
					<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
						{assign var = tabid value = $clsISO->getUniqid()}
						<li class="nav-item">
							<a class="nav-link active" data-bs-toggle="tab" role="tab" data-bs-target="#home_{$uid}">
								<i class='bx bx-info-circle' ></i> Chi tiết</a>
						</li>
						<li class="nav-item">
							<a class="nav-link billing" data-bs-toggle="tab" role="tab" data-bs-target="#billing_{$uid}">
								<i class='bx bx-chart'></i> Lịch sử giao dịch</a>
						</li>
						<li class="nav-item">
							<a class="nav-link level_logs" data-bs-toggle="tab" role="tab" data-bs-target="#level_logs_{$uid}">
								<i class='bx bx-analyse'></i> Quá trình thăng tiến </a>
						</li>
						<li class="nav-item">
							<a class="nav-link action_logs" data-bs-toggle="tab" role="tab" data-bs-target="#action_logs_{$uid}">
								<i class='bx bx-sync'></i> Lịch sử
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link view_stock_logs" data-bs-toggle="tab" role="tab" data-type="stock_logs" data-bs-target="#view_stock_logs_{$uid}">
								<i class='bx bx-search'></i> Check căn
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link view_report_share" data-bs-toggle="tab" role="tab" data-type="report_share" data-bs-target="#view_report_share_{$uid}">
								<i class='bx bx-run'></i> Tiếp khách
							</a>
						</li>
					</ul>
					<div class="w-100 tab-content pt-3 pb-0 px-0">
						<div class="tab-pane fade show active" id="home_{$uid}" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-heading">
										<div class="d-flex align-items-center justify-content-between">
											<h3 class="panel-title">Kênh mạng xã hội của bạn</h3>
											<a href="javascript:void(0);" profile_id="{$_profile_id}" onClick="$Core.member.open_social_channels(this,event)" 
												class="btn btn-sm btn-outline-default"><i class="bx bx-pencil"></i> Quản lý</a>
										</div>
									</div>
									<div class="panel-body no-easyui">
										<div class="holder_social_channels_{$_profile_id}">
										{if !empty($social_channels)}
											<div class="row g-2">
											{foreach from=$social_channels item=_oSC}
											{assign var=_sc_icon value='bxl-facebook'}{assign var=_sc_color value='text-primary'}{assign var=_sc_label value='Facebook'}
											{if $_oSC.category eq 'tiktok'}{assign var=_sc_icon value='bxl-tiktok'}{assign var=_sc_color value='text-dark'}{assign var=_sc_label value='TikTok'}{/if}
											{if $_oSC.category eq 'youtube'}{assign var=_sc_icon value='bxl-youtube'}{assign var=_sc_color value='text-danger'}{assign var=_sc_label value='Youtube'}{/if}
											{if $_oSC.category eq 'fanpage'}{assign var=_sc_icon value='bxl-facebook-square'}{assign var=_sc_color value='text-primary'}{assign var=_sc_label value='Fanpage'}{/if}
											<div class="col-12 col-sm-6">
												<a href="{$_oSC.link}" target="_blank" class="d-flex align-items-center gap-2 p-2 border rounded text-decoration-none">
													<i class="bx {$_sc_icon} fs-3 flex-shrink-0 {$_sc_color}"></i>
													<div class="flex-grow-1 overflow-hidden">
														<div class="fw-medium small">{if !empty($_oSC.title)}{$_oSC.title}{else}{$_sc_label}{/if}</div>
														<div class="text-muted text-truncate" style="font-size:11px">{$_oSC.link}</div>
													</div>
												</a>
											</div>
											{/foreach}
											</div>
										{else}
											<p class="text-muted py-1 mb-0 small">Chưa có kênh nào</p>
										{/if}
										</div>
									</div>
								</div>
							</div>
							<div class="dashboard-panel-item dashboard-panel-item--full">
								<div class="panel no-shadow border-0 panel-default">
									{assign var = toId value = $clsISO->getUniqid()}
									<div class="panel-heading">
										<h3 class="panel-title">Ghi chú</h3>
									</div>
									<div class="panel-body no-easyui">
										<form id="{$toId}" class="frmIssue p-3 rounded-2 bg-lighter mb-3" action="">
											<textarea class="form-control" name="content" rows="2" placeholder="Nhập ghi chú"></textarea>
											<div class="clearfix mt-2">
												<button type="button" tp="_create" class="btn btn-outline-primary" 
												for_id="{$_profile_id}" clsTable="Profile" note_id="" onClick="$Core.helper.save_notes(this,event)">Thêm</button>
											</div>
										</form>
										<div class="holder_notes_{$_profile_id}">
											<div class="loader p-5 text-center">
												Loading...
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="dashboard-panel-item dashboard-panel-item--full">
								<div class="panel panel-default no-shadow border-0">
									{assign var = toId value = $clsISO->getUniqid()}
									<div class="panel-heading">
										<div class="d-flex align-items-center justify-content-between">
											<h3 class="panel-title">Hồ sơ năng lực</h3>
											<a href="javascript:void(0);" toId="{$toId}" onClick="$Core.member.toggleForm(this,event)" class="btn btn-sm btn-outline-default"><i class="fa fa-plus"></i> Thêm mới</a>
										</div>
									</div>
									<div class="panel-body no-easyui">
										<form class="frmIssue d-none p-3 rounded-2 bg-lighter mb-2" id="{$toId}" 
											enctype="multipart/form-data" action="#" name="from-file">
											<textarea class="form-control" name="description" rows="2" placeholder="Nhập nội dung"></textarea>
											<div class="form-group">
												<div class="divider my-1 text-start">
													<div class="divider-text">Chọn file đính kèm</div>
												</div>
												<input type="file" class="form-control" name="attachment" />
											</div>
											<div class="clearfix mt-2">
												<input type="hidden" name="submit" value="Insert" />
												<button type="button" class="btn btn-outline-primary" 
												for_id="{$_profile_id}" clsTable="Profile" onClick="$Core.member.ms_save_file(this,event)">Thêm</button>
											</div>
										</form>
										<div class="holder_files_{$_profile_id}">
											<div class="loader p-5 text-center">
												Loading...
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-heading">
										<div class="d-flex align-items-center justify-content-between">
											<h3 class="panel-title">Tài khoản ngân hàng</h3>
											<a href="javascript:void(0);" toId="{$toId}" onClick="$Core.member.open_bank(this, event)" 
										profile_id="{$_profile_id}" class="btn btn-sm btn-outline-default"><i class="fa fa-plus"></i> Thêm mới</a>
										</div>
									</div>
									<div class="panel-body no-easyui">
										<div class="widget-content holder_bank__{$_profile_id}">
											<div class="loader text-center p-5">
												<span class="text-muted">Loading...</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="billing_{$uid}" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_billing_{$_profile_id} no-easyui">
										<div class="loader p-5 text-center">
											<img src="{$URL_IMAGES}/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="level_logs_{$uid}" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_level_logs_{$_profile_id} no-easyui">
										<div class="loader p-5 text-center">
											<img src="{$URL_IMAGES}/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="logs_{$uid}" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_login_logs_{$_profile_id} no-easyui">
										<div class="loader p-5 text-center">
											<img src="{$URL_IMAGES}/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="action_logs_{$uid}" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_login_logs_{$_profile_id} no-easyui">
										<h3 class="text-fs-16">Lịch sử thay đổi</h3>
										{if !empty($action_logs)}
										<ul class="logs">
											{foreach from=$action_logs item = _oLog}
												{if !empty($_oLog.content)}
												<li>{$clsISO->convertTimeToText($_oLog.reg_date, true)} : {$_oLog.content}</li>
												{/if}
											{/foreach}
										<ul>
										{else}
										<div class="p-4 text-center">
											<div class="mb-2">
												<img src="{$URL_IMAGES}/listing-empty.svg" class="w-px-75" />
											</div>
											<p class="text-muted mt-2">Chưa có lịch sử thực hiện nào</p>
										</div>
										{/if}
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="view_stock_logs_{$uid}" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_stock_logs_{$_profile_id} no-easyui">
										<div class="loader p-5 text-center">
											<img src="{$URL_IMAGES}/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="view_report_share_{$uid}" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_report_share_{$_profile_id} no-easyui">
										<div class="loader p-5 text-center">
											<img src="{$URL_IMAGES}/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						{if $clsISO->_DEV()}
						{/if}
						<!-- End -->
					</div>
				</div>
				<div class="col-12 col-md-4">
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel no-shadow border-0 panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Thông tin chi tiết</h3>
							</div>
							<div class="panel-body no-easyui">
								<div class="d-flex gap-2 align-items-center">
									<div class="gbox flex-fill rounded-2 border p-3">
										<p class="text-muted">Giao dịch</p>
										<strong class="text-main">{$total_billings} GD</strong>
									</div>
									<div class="gbox flex-fill rounded-2 border p-3">
										<p class="text-muted">Doanh số</p>
										<strong class="text-primary">{$total_revenues} {$clsISO->getRate()}</strong>
									</div>
									<div class="gbox flex-fill rounded-2 border p-3 cursor-pointer" onclick="$Core.global.open_Lpoint(this, event)" staff_id="{$_profile_id}">
										<p class="text-muted">Điểm Loyalty</p>
										<img src="{$URL_IMAGES}/point.png" width="12px">
										<strong class="text-warning">{$dbProfile.total_Lpoint}</strong>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel no-shadow border-0 panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Thông tin chi tiết</h3>
							</div>
							<div class="panel-body no-easyui">
								<table class="clientssummarystats" width="100%">
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Họ và tên</td>
										<td class="InputCRMHandler" colspan="3">
											{$dbProfile.full_name}
											{if ($permis_edit eq '1' || $permiss_edit_full eq '1') && !$clsISO->checkSale()}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'full_name', 'p_id':{$_profile_id}{rdelim})" p_field="full_name" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Điện thoại</td>
										<td  class="InputCRMHandler"colspan="3">
											{if !empty($dbProfile.phone)}
												<a href="tel:{$dbProfile.phone}">{$dbProfile.phone}</a>
											{else}
												-- 
											{/if}
											{if ($permis_edit eq '1' || $permiss_edit_full eq '1') && !$clsISO->checkSale()}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'phone', 'p_id':{$_profile_id}{rdelim})" p_field="phone" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Email</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.email)}
												<a href="mailto:{$dbProfile.email}">{$dbProfile.email}</a>
											{else}
												-- 
											{/if}
											{if ($permis_edit eq '1' || $permiss_edit_full eq '1') && !$clsISO->checkSale()}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'email', 'p_id':{$_profile_id}{rdelim})" p_field="email" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Địa chỉ</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.address)}
												{$dbProfile.address}
											{else}
												-- 
											{/if}
											{if $permis_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'address', 'p_id':{$_profile_id}{rdelim})" p_field="address" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Giới tính</td>
										<td class="InputCRMHandler" colspan="3">
											{if $dbProfile.gender_id eq '1'}
												Nam
											{elseif $dbProfile.gender_id eq '2'}
												Nữ
											{else}
												---
											{/if}
											{if $permis_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'gender_id', 'p_id':{$_profile_id}{rdelim})" p_field="gender_id" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Ngày sinh</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.birthday)}
												{$clsISO->convertTimeToText($dbProfile.birthday)}
											{else}
												-- 
											{/if}
											{if $permis_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'birthday', 'p_id':{$_profile_id}{rdelim})" p_field="birthday" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">CCID</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.CCID)}
												{$dbProfile.CCID}
											{else}
												-- 
											{/if}
											{if ($permis_edit eq '1' || $permiss_edit_full eq '1') && !$clsISO->checkSale()}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'CCID', 'p_id':{$_profile_id}{rdelim})" p_field="CCID" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Ngày vào làm</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.start_date)}
												{$clsISO->convertTimeToText($dbProfile.start_date)}
											{else}
												-- 
											{/if}
											{if $permiss_edit_full eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'start_date', 'p_id':{$_profile_id}{rdelim})" p_field="start_date" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Ngày ký HĐ</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.contract_date)}
												{$clsISO->convertTimeToText($dbProfile.contract_date)}
											{else}
												-- 
											{/if}
											{if $permiss_edit_full eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'contract_date', 'p_id':{$_profile_id}{rdelim})" p_field="contract_date" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Phòng ban</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.department_id)}
												{$clsProperty->getTitle($dbProfile.department_id)}
											{else}
												--
											{/if}
											{if $permiss_edit_full eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'department_id', 'p_id':{$_profile_id}{rdelim})" p_field="department_id" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Vai trò</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.role_id)}
												{$clsProperty->getTitle($dbProfile.role_id)}
											{else}
												--
											{/if}
											{if $permiss_edit_full eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'role_id', 'p_id':{$_profile_id}{rdelim})" p_field="role_id" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Vai trò phụ <span class="text-muted fw-normal" style="font-size:11px">(kiêm nhiệm)</span></td>
										<td class="InputCRMHandler" colspan="3">
											{if $permiss_edit_full eq '1' && $permis_edit neq 1}
											<div class="secondary-role-box">
												<div class="secondary-display">
													<span class="secondary-current-text">{if $sec_dep_id > 0}{$clsProperty->getTitle($sec_dep_id)} – {$clsProperty->getTitle($sec_role_id)}{else}--{/if}</span>
													<a class="editInlineField ml-1" onclick="$Core.member.edit_secondary(this, event)">{$clsISO->makeIcon('bx-pencil')}</a>
												</div>
												<div class="secondary-editor d-none">
													<div class="d-flex input-group inline-editor-container align-items-center gap-1">
														<select name="secondary_department_id" class="form-control form-select form-control-sm w-auto" onchange="$Core.member.load_secondary_role(this, event)">
															{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$sec_dep_id,'— Không kiêm nhiệm —')}
														</select>
														<select name="secondary_role_id" class="form-control form-select form-control-sm w-auto">
															{$html_role_options_secondary}
														</select>
														<button type="button" class="btn px-2 btm-sm btn-outline-success" onclick="$Core.member.save_secondary(this, event)" data-pid="{$_profile_id}">{$clsISO->makeIcon('bx-check')}</button>
														<button type="button" class="btn px-2 btm-sm btn-outline-danger" onclick="$Core.member.cancel_secondary(this, event)">{$clsISO->makeIcon('bx-x')}</button>
													</div>
												</div>
											</div>
											{else}
												{if $sec_dep_id > 0}{$clsProperty->getTitle($sec_dep_id)} – {$clsProperty->getTitle($sec_role_id)}{else}--{/if}
											{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Cấp bậc</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.level_id)}
												{$clsProperty->getTitle($dbProfile.level_id)}
											{else}
												--
											{/if}
											{if $permiss_edit_full eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'level_id', 'p_id':{$_profile_id}{rdelim})" p_field="level_id" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Tình trạng</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($dbProfile.status_id)}
												{$clsProperty->getTitle($dbProfile.status_id)}
											{else}
												--
											{/if}
											{if $permiss_edit_full eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'status_id', 'p_id':{$_profile_id}{rdelim})" p_field="level_id" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Zalo ID</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($more_information.zaloId)}
												{$more_information.zaloId}
											{else}
												--
											{/if}
											{if $permiss_edit_full eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'zaloId', 'p_id':{$_profile_id}{rdelim})" p_field="level_id" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<!-- <tr class="trPotentialEdit">
										<td class="text-right text-nowrap" width="25%">Twitter</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($more_information.twitter)}
												{$more_information.twitter}
											{else}
												--
											{/if}
											{if $permis_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'twitter', 'p_id':{$_profile_id}{rdelim})" p_field="twitter" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Facebook</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($more_information.facebook)}
												{$more_information.facebook}
											{else}
												--
											{/if}
											{if $permis_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'facebook', 'p_id':{$_profile_id}{rdelim})" p_field="facebook" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Linkedin</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($more_information.linkedin)}
												{$more_information.linkedin}
											{else}
												--
											{/if}
											{if $permis_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'linkedin', 'p_id':{$_profile_id}{rdelim})" p_field="linkedin" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Instagram</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($more_information.instagram)}
												{$more_information.instagram}
											{else}
												--
											{/if}
											{if $permis_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'instagram', 'p_id':{$_profile_id}{rdelim})" p_field="instagram" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr> -->
								</table>
							</div>
						</div>
					</div>
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel border-0 no-shadow panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Chứng chỉ môi giới</h3>
							</div>
							<div class="panel-body no-easyui">
								<table class="clientssummarystats" width="100%">
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap" width="25%">Số chứng chỉ</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($more_information.issue_number)}
												{$more_information.issue_number}
											{else}
												-- 
											{/if}
											{if $permiss_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'issue_number', 'p_id':{$_profile_id}{rdelim})" p_field="issue_number" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Ngày cấp</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($more_information.issue_date)}
												{$clsISO->convertTimeToText($more_information.issue_date)}
											{else}
												-- 
											{/if}
											{if $permiss_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'issue_date', 'p_id':{$_profile_id}{rdelim})" p_field="issue_date" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Nơi cấp</td>
										<td class="InputCRMHandler" colspan="3">
											{if !empty($more_information.issue_location)}
												{$more_information.issue_location}
											{else}
												-- 
											{/if}
											{if $permiss_edit eq '1' || $permiss_edit_full eq '1'}<a class="editInlineField" onClick="$Core.member.editInlineField(this,{ldelim}p_field:'issue_location', 'p_id':{$_profile_id}{rdelim})" p_field="issue_location" p_id="{$_profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>{/if}
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div></div>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.inline-editor-container {
		min-width: 150px;
		max-width: 220px;
		width: 260px;
	}
	.dashboard-panel-item,
	.dashboard-panel-item .panel{
		margin-bottom:10px !important
	}
</style>
{/literal}