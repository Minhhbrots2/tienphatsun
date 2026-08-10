<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Đơn xin phép #{$takeleave_id}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="highlights">
				<div class="highlight-panel">
					<div class="highlight-list">
						<div class="highlight-item">
							<div class="highlight-label">Nhân viên</div>
							<div class="metadata-row-viewer">
								{$clsProfile->getFullName($oneTakeLeave.user_id)}
							</div>
						</div>
						<div class="highlight-item">
							<div class="highlight-label">{$core->get_Lang('Department')}</div>
							<div class="metadata-row-viewer text-bold">
								{$clsProperty->getTitle($department_id)}
							</div>
						</div>
						<div class="highlight-item">
							<div class="highlight-label">Vị trí</div>
							<div id="ns_confirm" class="metadata-row-viewer text-bold">
								{$oneTakeLeave.position}
							</div>
						</div>
						<div class="highlight-item">
							<div class="highlight-label">Người phụ trách</div>
							<div id="ms_confirm" class="metadata-row-viewer text-bold">
								{$clsProfile->getFullName($oneTakeLeave.curator_user_id)}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body scroller">
				
				<div class="widget-block">
					<div class="widget-header">Thông tin đơn xin nghỉ</div>
					<div class="widget-content mb-3">
						<table class="clientssummarystats" width="100%">
							<tr>
								<td class="text-right" width="30%">
									Nghỉ phép năm {$oneTakeLeave.start_date|date_format:"%Y"}
								</td>
								<td class="text-left">
									{$clsTakeLeave->getInfoTakeleave($oneTakeLeave.user_id)}
								</td>
							</tr>
							<tr>
								<td class="text-right">
									Nghỉ không lương năm {$oneTakeLeave.start_date|date_format:"%Y"}
								</td>
								<td class="text-left">
									<span class="rate-avg font-16 bold">
										{$clsTakeLeave->getNumberNoPaidLeave($oneTakeLeave.user_id)}
									</span> ngày
								</td>
							</tr>
							<tr>
								<td class="text-right">Ngày nghỉ có lương (Năm nay)</td>
								<td class="text-left">
									{$oneTakeLeave.number_day_paid_leave_this_year} ngày
								</td>
							</tr>
							{if $takeleave_configs.is_leave_carryover eq '1'}
							<tr{if $smarty.const._MONTH_TAKE_LEAVE_RESET lt $clsISO->getDateCreateFormat($oneTakeLeave.start_date,"n")} class="d-none"{/if}>
								<td class="text-right">Ngày nghỉ có lương (Năm trước)</td>
								<td class="text-left">
									{$oneTakeLeave.number_day_paid_leave_last_year} ngày
								</td>
							</tr>
							{/if}
							<tr>
								<td class="text-right">Ngày nghỉ không lương</td>
								<td class="text-left">
									{$oneTakeLeave.number_day_no_paid_leave} ngày
								</td>
							</tr>
							<tr class="fw-bold">
								<td class="text-right">Tổng số ngày nghỉ</td>
								<td class="text-left text-main">
									{$oneTakeLeave.number_day} ngày
								</td>
							</tr>
							<tr>
								<td class="text-right">Từ ngày</td>
								<td class="text-left">
									{$oneTakeLeave.start_date|date_format:"%d/%m/%Y"} {$oneTakeLeave.start_time} 
								</td>
							</tr>
							<tr>
								<td class="text-right">Đến ngày</td>
								<td class="text-left">
									{$oneTakeLeave.end_date|date_format:"%d/%m/%Y"} {$oneTakeLeave.end_time} 
								</td>
							</tr>
							<tr>
								<td class="text-right">Lí do nghỉ</td>
								<td class="text-left">
									{$oneTakeLeave.reason|html_entity_decode}
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="widget-block">
					<div class="widget-header">{$tp_title}</div>
					<div class="widget-content">
						<form method="post" action="">
							<div class="bg-lighter p-3">
								<div class="form-group form-row mb-2">
									<label class="col-form-label col-md-3 text-right">Duyệt đơn</label>
									<div class="col-md-9">
										{assign var = for_id value = $clsISO->getUniqid()}
										<div class="btn-group" role="group" aria-label="Xét duyệt">
											<input type="radio"{if $oneApproval.approval_status eq '1'} checked{/if} class="btn-check" name="approval_status" id="approved_{$for_id}" value="1" autocomplete="off">
											<label class="btn btn-outline-primary" for="approved_{$for_id}">Duyệt</label>
											<input type="radio" name="approval_status"{if $oneApproval.approval_status eq '2'} checked{/if} class="btn-check" id="not_approved_{$for_id}" value="2" autocomplete="off">
											<label class="btn btn-outline-primary" for="not_approved_{$for_id}">Không duyệt</label>
										</div>
									</div>
								</div>
								<div class="form-group form-row mb-2">
									<label class="col-form-label col-12 col-md-3 text-right">Nội dung xét duyệt</label>
									<div class="col-12 col-md-9">
										<textarea id="reason_{$tp}_{$takeleave_id}" class="form-control" name="content" 
										rows="4" placeholder="Viết nội dung xét duyệt">{$clsISO->parseP2nl($oneApproval.content)}</textarea>
									</div>
								</div>
								<div class="form-group form-row">
									<label class="col-form-label col-12 col-md-3"></label>
									<div class="col-12 col-md-9">
										<input type="hidden" name="submit" value="Update" />
										<button type="button" onClick="$Core.takeleave.approval_save(this, event)" tp="{$tp}" 
										takeleave_id="{$takeleave_id}" class="btn btn-warning">Cập nhật</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>