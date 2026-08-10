<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog"><div class="modal-content">
		<div class="modal-header d-flex align-items-center justify-content-between">
			{if $deviceType eq 'phone'}
			<div class="p__pleft d-flex">
				{if $permiss_action eq '1'}
				<button type="button" class="btn btn-outline-primary text-nowrap" data-toggle="webui-popover" data-trigger="click" data-type="async" id="{$clsISO->getUniqid()}" data-closeable="false" data-url="{$PCMS_URL}/index.php?mod=issue&act=load_issue_done_ratio&issue_id={$issue_id}">Tiến độ {$core->makeIcon('caret-down')}
				</button>
				{else}
				<div class="header mb-2">
					<span class="text-muted">{$clsProperty->getTitle($oneIssue.type_id)}</span>
					<h5 class="modal-title">{$clsIssue->getTitle($issue_id, $oneIssue)}</h5>
				</div>
				{/if}
			</div>
			{else}
			<div class="p__pleft d-flex">
				<img src="{$smarty.const.ICON_TASK}" class="icon w-px-50 mr-2" />
				<div class="header">
					<h5 class="modal-title">{$clsIssue->getTitle($issue_id, $oneIssue)}</h5>
					<div class="dropdown">
						Mục tiêu công việc: <a href="javascript:void(0)" class="badge bg-label-secondary issue_target_{$uid} text-uppercase dropdown-toggle" 
						data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
							{$oneIssue.issue_target_name}</a>
						<div class="dropdown-menu w-px-350" data-popper-placement="bottom-start">
							<h6 class="dropdown-header mt-2 text-uppercase">Thêm mục tiêu công việc</h6>
							<div class="dropdown-body py-2 px-3">
								{assign var = _toId value = $clsISO->getUniqid()}
								<div class="form-group mb-2">
									<label class="form-label mb-1">Chọn mục tiêu</label>
									<div class="clearfix"></div>
									<select toId="{$_toId}" onChange="$Core.issue.load_select_target(this, event)" 
										class="form-control form-select" placeholder="Phòng ban" name="department_id">
										{if $clsISO->checkPermissionGroup('DIRECTOR')}
											{$clsISO->getSelectByPropertyTypeNotTitle('_DEPARTMENT', $department_id)}
										{else}
										<option value="{$department_id}" selected>{$clsProperty->getTitle($department_id)}</option>
										{/if}
									</select>
								</div>
								<div class="form-group mb-3">
									<label class="form-label mb-1">Chọn mục tiêu</label>
									<div id="issue_target_{$_toId}" class="issue_target_{$_toId}">
										<select name="issue_target_id" class="iso-selectizeNotSearch" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=get_issue_target&department_id={$oneProfile.department_id}&issue_target_id={$oneIssue.issue_target_id}" data-optgroup="false" placeholder="Chọn mục tiêu công việc"></select>
									</div>
								</div>
								<button type="button" uid="{$uid}" onClick="$Core.issue.add_target(this, event)" issue_id="{$issue_id}" 
									class="btn btn-primary">Thêm mục tiêu</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="p__right">
				{if $permiss_action eq '1'}
				<button type="button"{if $profile_id ne $oneIssue.assign_to_id} disabled{/if} class="btn btn-outline-primary text-nowrap" data-toggle="webui-popover" data-trigger="click" data-type="async" id="{$clsISO->getUniqid()}" data-placement="left-bottom" data-closeable="false" data-url="{$PCMS_URL}/index.php?mod=issue&act=load_issue_done_ratio&issue_id={$issue_id}">Tiến độ {$core->makeIcon('caret-down')}</button>
				{/if}
			</div>
			{/if}
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="highlights">
			<div class="highlight-panel">
				<div class="highlight-list">
					<div class="highlight-item">
						<div class="highlight-label">Link </div>
						<div class="metadata-row-viewer d-flex gap-1">
							<div class="issue-link text-muted">{$clsIssue->getLink($issue_id, true)}</div>
							<a class="btn btn-icon btn-xs btn-outline-default" onClick="$Core.util.copyToClipboard(this,event)" data-link="{$clsIssue->getLink($issue_id)}" title="Copy Link">{$clsISO->makeIcon('bx-copy fs-12')}</a>
						</div>
					</div>
					<div class="highlight-item">
						<div class="highlight-label">Ngày bắt đầu {if $permiss_edit eq '1'}<a href="javascript:void(0)" onClick="open_issue_edit(this, event)" class="text-muted" p_field="start_date" toId="start_date_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
						<div id="start_date_{$uid}" class="metadata-row-viewer fs-13">
							{$clsISO->convertTimeToText($oneIssue.start_date, true)}
						</div>
					</div>
					<div class="highlight-item">
						<div class="highlight-label">Ngày kết thúc {if $permiss_edit eq '1'}<a href="javascript:void(0)" onClick="open_issue_edit(this, event)" class="text-muted" p_field="end_date" toId="end_date_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
						<div id="end_date_{$uid}" class="metadata-row-viewer fs-13">
							{$clsISO->convertTimeToText($oneIssue.end_date, true)}
						</div>
					</div>
					<div class="highlight-item">
						<div class="highlight-label">Tình trạng {if $permiss_edit eq '1' || $permiss_action eq '1'}<a class="d-none" href="javascript:void(0)" title="Chỉnh sửa" onClick="open_issue_edit(this, event)" class="text-muted" p_field="status_id" toId="status_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
						<div id="status_{$uid}" class="metadata-row-viewer">
							{$clsIssue->getStatus($oneIssue.status_id)}
						</div>
					</div>
					<div class="highlight-item">
						<div class="highlight-label">Ưu tiên{if $permiss_edit eq '1'}<a href="javascript:void(0)" onClick="open_issue_edit(this, event)" class="text-muted" p_field="priority_id" toId="priority_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
						<div id="priority_{$uid}" class="metadata-row-viewer">
							{$clsIssue->getPriority($oneIssue.priority_id)}
						</div>
					</div>
					<div class="highlight-item">
						<div class="highlight-label">Tiến độ (0%)</div>
						<div id="done_ratio_{$issue_id}" class="metadata-row-viewer mt-2">
							{$clsIssue->getProgress($issue_id, $oneIssue)}
						</div>
					</div>
					<div class="highlight-item">
						<div class="highlight-label">Đã sử dụng</div>
						<div id="done_ratio_{$issue_id}" class="metadata-row-viewer">
							<strong class="time-issue-{$issue_id} {if $clsIssue->isTrackingTimeIssue($issue_id)}timeCountUp{/if}" style="font-size:16px; font-weight:bold; color:red;">{$clsIssue->getTimeDo($issue_id)}</strong>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-body scroller">
			{if $deviceType eq 'phone' && $permiss_action eq '1'}
			<div class="header mb-2">
				<span class="text-muted">{$clsProperty->getTitle($oneIssue.type_id)}</span>
				<h5 class="modal-title line-height-1">{$clsIssue->getTitle($issue_id, $oneIssue)}</h5>
			</div>
			{/if}
			<div class="p-4 mb-3 bg-lighter rounded-2">
				<h6 class="mb-2">Nội dung công việc{if $permiss_edit eq '1'}<a href="javascript:void(0)" onClick="open_issue_edit(this, event)" p_field="content" toId="content_{$uid}" class="text-muted" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</h6>
				<div class="tinyContent" id="content_{$uid}">
					{$oneIssue.content|html_entity_decode}
				</div>
			</div>
			<div class="form-group mb-3 form-row">
				<div class="col-6 col-md-3">
					<div class="text-muted mb-2">Người giao việc</div>
					{$clsProfile->getIndentityV5($oneIssue.user_id)}
				</div>
				<div class="col-6 col-md-3">
					<div class="text-muted mb-2">Người thực hiện {if $permiss_edit eq '1'}<a href="javascript:void(0)" onClick="open_issue_edit(this, event)" class="text-muted" p_field="assign_to_id" toId="assign_to_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
					<div id="assign_to_{$uid}">
						{$clsProfile->getIndentityV5($oneIssue.assign_to_id)}
					</div>
				</div>
				<div class="col-6 col-md-4">
					<div class="text-muted mb-2">Người liên quan</div>
					<ul id="participants_{$uid}" class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
						{$clsIssue->getImplementer($issue_id, $uid, $oneIssue)}
					</ul>
				</div>
				<div class="col-6 col-md-2 pt-3">
					<a href="javascript:void(0);" onClick="open_issue(this, event)" issue_id="0" issue_type="_addchild" parent_id="{$issue_id}">
						<img src="{$URL_IMAGES}/add.png" /> 
						<u>Thêm công việc</u>
					</a>
				</div>
			</div>
			
			<div class="issue-action-{$issue_id} d-flex flex-wrap align-items-center gap-2 text-nowrap my-2 p-3 rounded-2 bg-lighter">
				{$clsIssue->render_html_actions($issue_id)}
			</div>
			<div class="widget-block mb-3">
				<div class="widget-header d-flex align-items-center justify-content-between">
					<span class="p__left">Hạng mục công việc</span>
					<span class="oqAupliNnN_{$issue_id} p__right d-flex">
						<span class="d-flex align-items-center text-success">
							<i class="bx bx-check-circle fs-big mr-1"></i>
							<span class="rajqXvSIGn_{$issue_id}">0</span>
						</span>
						<span class="mx-1">-</span>
						<span class="d-flex text-danger align-items-center">
							<i class="bx bx-circle fs-big mr-1"></i> 
							<span class="entqeZXPZg_{$issue_id}">0</span>
						</span>
					</span>
				</div>
				<div class="widget-content">
					<div class="holder_task_{$issue_id}"></div>
					{assign var = toId value = $clsISO->getUniqid()}
					<div id="{$toId}" class="w-100{if $permiss_task eq '0'} d-none{/if} mt-2">
						<button class="btn btn-default" onClick="add_issue_task(this, event)" toId="{$toId}" issue_id="{$issue_id}">{$clsISO->makeIcon('bx-plus', 'Thêm mục công việc')}</button>
					</div>
				</div>
			</div>
			<div class="widget-block">
				<div class="widget-header">Hành động</div>
				<div class="widget-content">
					<div class="holder_issue_notes_{$issue_id}">
						<div class="loader text-center p-5">
							<p class="text-muted">Loading...</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div></div>
</div>