<div class="modal-dialog modal-ipad">
	{assign var = toId value = $clsISO->getUniqid()}
	<div class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title lh-sm">Chi tiết tăng ca {$oneItem.code}</h5>
			<button type="button" onClick="$Core.overtime.close(this,event)" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="highlights">
			<div class="highlight-panel">
				<div class="highlight-list gap-2">
					<div class="highlight-item no-gap">
						<div class="highlight-label">Mã</div>
						<div class="metadata-row-viewer">
							<i class='bx bx-code'></i>
							{$oneItem.code}
						</div>
					</div>
					<div class="highlight-item no-gap">
						<div class="highlight-label">Trạng thái</div>
						<div id class="metadata-row-viewer status_{$toId}">
							{$clsOvertime->getStatus($oneItem.status_id)}
						</div>
					</div>
					<div class="highlight-item no-gap">
						<div class="highlight-label">Tiến độ (%)</div>
						<div  class="metadata-row-viewer text-nowrap done_ratio_{$toId} mt-2">
							{$clsOvertime->getProgress($overtime_id, $oneItem)}
						</div>
					</div>
					<div class="highlight-item no-gap">
						<div class="highlight-label">Ngày tạo</div>
						<div class="metadata-row-viewer text-nowrap">
							<i class="material-icons-outlined">more_time</i>
							{$clsISO->convertTimeToText($oneItem.reg_date, true)}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-body">
			<div class="widget-block">
				<div class="widget-header">Thông tin</div>
				<div class="widget-content">
					<table class="clientssummarystats" width="100%">
						<tr>
							<td class="text-right" width="35%">Mã</td>
							<td class="text-left">{$oneItem.code}</td>
						</tr>
						<tr>
							<td class="text-right" width="35%">Ưu tiên</td>
							<td class="text-left">{$clsOvertime->getPriority($oneItem.priority_id)}</td>
						</tr>
						<tr>
							<td class="text-right">Nhân viên</td>
							<td class="text-left">{$clsProfile->getFullName($oneItem.profile_id)}</td>
						</tr>
						<tr>
							<td class="text-right">Ngày đăng ký</td>
							<td class="text-left">{$clsISO->convertTimeToText($oneItem.regis_date)}</td>
						</tr>
						<tr>
							<td class="text-right">Bắt đầu</td>
							<td class="text-left">{$clsISO->convertTimeToText($oneItem.start_date, true)}</td>
						</tr>
						<tr>
							<td class="text-right">Kết thúc</td>
							<td class="text-left">{$clsISO->convertTimeToText($oneItem.due_date, true)}</td>
						</tr>
						<tr>
							<td class="text-right">Cả ngày</td>
							<td class="text-left">{if $oneItem.is_fullday eq '1'}
								<span class="text-danger">CẢ NGÀY</span>
							{else}KHÔNG{/if}</td>
						</tr>
						<tr>
							<td class="text-right" style="vertical-align:top">Lý do tăng ca</td>
							<td class="text-left">{$oneItem.content|nl2br}</td>
						</tr>
						{if $oneItem.is_done eq '1'}
						<tr>
							<td class="text-right" style="vertical-align:top">Ghi chú kết quả</td>
							<td class="text-left">{$more_information.notes|nl2br}</td>
						</tr>
						{/if}
						<tr>
							<td class="text-right">Người duyệt</td>
							<td class="text-left">
								{if !empty($list_approver_arrs)}
									{foreach from=$list_approver_arrs item = usr_id}
									<div class="download py-0">{$clsProfile->getFullName($usr_id)}</div>
									{/foreach}
								{/if}
							</td>
						</tr>
						<tr>
							<td class="text-right">Người duyệt kết quả</td>
							<td class="text-left">
								{if !empty($list_confirmed_arrs)}
									{foreach from=$list_confirmed_arrs item = usr_id}
									<div class="download py-0">{$clsProfile->getFullName($usr_id)}</div>
									{/foreach}
								{/if}
							</td>
						</tr>
					</table>
					{if $clsOvertime->checkHaveAction($overtime_id, 'approve', $oneItem) 
						&& ($oneItem.status_id eq $smarty.const._STATUS_OVERTIME_PENDING_ID || $oneItem.is_done eq '1')}
						{if $oneItem.status_id eq $smarty.const._STATUS_OVERTIME_PENDING_ID}
							<hr class="my-2" />
							<div id="{$toId}" class="d-flex gap-2 align-items-center justify-content-center">
								<button type="button" toId="{$toId}" holderG="approved" overtime_id="{$overtime_id}" 
								onClick="$Core.overtime.do_action(this, event)" class="btn btn-outline-primary">
									<i class="bx bx-check"></i> Duyệt</button>
								<button type="button" toId="{$toId}" holderG="refure" overtime_id="{$overtime_id}" 
								onClick="$Core.overtime.do_action(this, event)" class="btn btn-outline-danger">
									<i class="bx bx-x"></i> Không duyệt</button>
							</div>
						{else}
							{if $oneItem.is_done eq '1'}
							<hr class="my-2" />
							<div id="{$toId}" class="d-flex gap-2 align-items-center justify-content-center">
								{if $oneItem.is_confirmed eq '0'}
								<button type="button" toId="{$toId}" holderG="confirmed" overtime_id="{$overtime_id}" 
								onClick="$Core.overtime.do_action(this, event)" class="btn btn-outline-primary">
									<i class="bx bx-check"></i> Xác nhận hoàn thành</button>
								{else}
								<span class="text-primary">
									<i class="bx bx-check-double"></i> Đã xác nhận hoàn thành
								</span>
								{/if}
							</div>
							{/if}
						{/if}
					{elseif $oneItem.status_id eq $smarty.const._STATUS_OVERTIME_APPROVED_ID}
						{if $oneItem.is_confirmed eq '1'}
						<hr class="my-2" />
						<div class="d-flex gap-2 align-items-center justify-content-center">
							<span class="text-primary">
								<i class="bx bx-check-double"></i> 
								<span>Đã xác nhận hoàn thành</span>
							</span>
						</div>
						{else}
						<hr class="my-2" />
						{if $oneItem.is_done eq '0' && $profile_id eq $oneItem.profile_id}
							<div class="form-group mb-2">
								<label class="form-label mb-1">Ghi chú</label>
								<textarea id="{$editorId}" class="form-control required" cols="25" rows="3" name="notes" placeholder="Nội dung ghi chú kết quả">{if $action eq '_edit'}{$more_information.notes}{/if}</textarea>
							</div>
						{/if}
						<div class="d-flex gap-2 align-items-center justify-content-center">
							{if $oneItem.is_done eq '1'}
							<button type="button" toId="{$toId}" class="btn btn-outline-primary">
								<i class='bx bx-check-double'></i>
								<span>Đã hoàn thành</span>
							</button>
							{else}
								{if $profile_id eq $oneItem.profile_id}
								<button type="button" toId="{$toId}" class="btn btn-outline-primary mr-1" 
								overtime_id="{$overtime_id}" onclick="$Core.overtime.done(this, event)" >
									<i class='bx bx-check'></i> 
									<span>Hoàn thành</span>
								</button>
								{else}
								<span class="text-muted">
									<strong>{$clsProfile->getFullName($oneItem.profile_id)}</strong> chưa hoàn thành
								</span>
								{/if}
							{/if}
							{if $profile_id eq $oneItem.profile_id}
							<button type="button" class="btn btn-outline-info text-nowrap" data-toggle="webui-popover" 
							data-trigger="click" data-type="async" data-placement="top" data-closeable="false" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_done_ratio&overtime_id={$overtime_id}">Tiến độ 
								<i class="fa fa-caret-down"></i>
							</button>
							{/if}
						</div>
						{/if}
					{/if}
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-warning btn-block btn-lg" data-bs-dismiss="modal">Đóng cửa sổ</button>
		</div>
	</div>
</div>
		
