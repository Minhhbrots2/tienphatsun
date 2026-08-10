<div class="modal right fade show issue-dw-modal" id="{$uid}" role="dialog">
	<div class="modal-dialog"><div class="modal-content issue-dw">
		<!-- HEADER -->
		<div class="issue-dw-head">
			<div class="issue-dw-headrow">
				<div class="issue-dw-ico st-{$oneIssue.status_id}"><i class="bx bxs-file-blank"></i></div>
				<div class="issue-dw-htext">
					<div class="issue-dw-meta">
						<span class="issue-dw-id">#{$issue_id}</span>
						<span id="status_{$uid}" class="issue-dw-badge st-{$oneIssue.status_id}"><span class="dot"></span>{$clsProperty->getTitle($oneIssue.status_id)}</span>
						{if $permiss_edit eq '1' || $permiss_action eq '1'}<a class="d-none" href="javascript:void(0)" onClick="$Core.issue.open_issue_edit(this, event)" p_field="status_id" toId="status_{$uid}" p_id="{$issue_id}"></a>{/if}
						<span class="issue-dw-prio pr-{$oneIssue.priority_id}" id="priority_{$uid}"><i class="bx bxs-flag"></i>{$clsProperty->getTitle($oneIssue.priority_id)}</span>
						{if $permiss_edit eq '1'}<a href="javascript:void(0)" class="issue-dw-edit" onClick="$Core.issue.open_issue_edit(this, event)" p_field="priority_id" toId="priority_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}
					</div>
					<div class="issue-dw-title" id="title_{$uid}">{$clsIssue->getTitle($issue_id, $oneIssue)}{if $permiss_edit eq '1'}<a href="javascript:void(0)" class="issue-dw-edit" onClick="$Core.issue.open_issue_edit(this, event)" p_field="title" toId="title_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
				</div>
				<button type="button" class="issue-dw-x close_pop" data-bs-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
			</div>
		</div>
		<!-- 3-STAT -->
		<div class="issue-dw-stats">
			<div class="issue-dw-stat">
				<div class="issue-dw-stat-l">THỜI GIAN {if $permiss_edit eq '1'}<a href="javascript:void(0)" class="issue-dw-edit" onClick="$Core.issue.open_issue_edit(this, event)" p_field="start_date" toId="start_date_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
				<div class="issue-dw-stat-v"><span id="start_date_{$uid}">{$clsISO->convertTimeToText($oneIssue.start_date, true)}</span> → <span id="end_date_{$uid}">{$clsISO->convertTimeToText($oneIssue.end_date, true)}</span>{if $permiss_edit eq '1'}<a href="javascript:void(0)" class="issue-dw-edit" onClick="$Core.issue.open_issue_edit(this, event)" p_field="end_date" toId="end_date_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
			</div>
			<div class="issue-dw-stat">
				<div class="issue-dw-stat-l">TIẾN ĐỘ{if $permiss_action eq '1'} <a href="javascript:void(0)" class="issue-dw-edit" data-toggle="webui-popover" data-trigger="click" data-type="async" id="{$clsISO->getUniqid()}" data-placement="bottom" data-closeable="false" data-url="{$PCMS_URL}/index.php?mod=issue&act=load_issue_done_ratio&issue_id={$issue_id}">{$core->makeIcon('caret-down')}</a>{/if}</div>
				<div class="issue-dw-stat-v" id="done_ratio_{$issue_id}">{$clsIssue->getProgress($issue_id, $oneIssue)}</div>
			</div>
			<div class="issue-dw-stat">
				<div class="issue-dw-stat-l">ĐÃ DÙNG</div>
				<div class="issue-dw-stat-v"><strong class="issue-dw-clock time-issue-{$issue_id}">{$clsIssue->getTimeDo($issue_id)}</strong></div>
			</div>
		</div>
		<!-- thanh bấm giờ -->
		<div class="issue-dw-timer{if $clsIssue->isTrackingTimeIssue($issue_id)} run{/if}">
			<span class="issue-dw-timer-ic"><i class="bx {if $clsIssue->isTrackingTimeIssue($issue_id)}bx-pause{else}bx-play{/if}"></i></span>
			<div class="issue-dw-clock issue-dw-timer-clock time-issue-{$issue_id}{if $clsIssue->isTrackingTimeIssue($issue_id)} timeCountUp{/if}">{$clsIssue->getTimeDo($issue_id)}</div>
			<span class="issue-dw-timer-hint">Bấm giờ làm việc thực tế</span>
		</div>
		<!-- BODY -->
		<div class="modal-body issue-dw-body scroller">
			<!-- nút hành động (timer + đổi trạng thái) -->
			<div class="issue-dw-acts issue-action-{$issue_id}">
				{$clsIssue->render_html_actions($issue_id)}
			</div>
			<!-- người giao / nhận / liên quan -->
			<div class="issue-dw-grid3">
				<div class="issue-dw-pcard">
					<div class="issue-dw-pcard-l">NGƯỜI GIAO</div>
					{$clsProfile->getIndentityV5($oneIssue.user_id)}
				</div>
				<div class="issue-dw-pcard">
					<div class="issue-dw-pcard-l">NGƯỜI THỰC HIỆN {if $permiss_edit eq '1'}<a href="javascript:void(0)" class="issue-dw-edit" onClick="$Core.issue.open_issue_edit(this, event)" p_field="assign_to_id" toId="assign_to_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
					<div id="assign_to_{$uid}">{$clsProfile->getIndentityV5($oneIssue.assign_to_id)}</div>
				</div>
				<div class="issue-dw-pcard">
					<div class="issue-dw-pcard-l">LIÊN QUAN <a href="javascript:void(0);" class="issue-dw-edit" onClick="$Core.issue.open_issue(this, event)" issue_id="0" issue_type="_addchild" parent_id="{$issue_id}" title="Thêm công việc con"><i class="bx bx-plus"></i></a></div>
					<ul id="participants_{$uid}" class="list-unstyled users-list m-0 avatar-group issue-dw-avgrp">{$clsIssue->getImplementer($issue_id, $uid, $oneIssue)}</ul>
				</div>
			</div>
			<!-- nội dung -->
			<div class="issue-dw-card">
				<div class="issue-dw-card-l">NỘI DUNG CÔNG VIỆC {if $permiss_edit eq '1'}<a href="javascript:void(0)" class="issue-dw-edit" onClick="$Core.issue.open_issue_edit(this, event)" p_field="content" toId="content_{$uid}" p_id="{$issue_id}">{$clsISO->makeIcon('bx-edit-alt')}</a>{/if}</div>
				<div class="issue-dw-content tinyContent" id="content_{$uid}">{$oneIssue.content|html_entity_decode}</div>
			</div>
			<!-- đính kèm -->
			{assign var=att_files value=$clsISO->to_array_json($oneIssue.attachments)}
			{if $att_files}
			<div class="issue-dw-card">
				<div class="issue-dw-card-l">ĐÍNH KÈM ({$att_files|@count})</div>
				<div class="issue-dw-atts">
					{foreach from=$att_files item=f}
					<a class="issue-dw-att" href="{$f}" target="_blank" rel="noopener"><i class="bx bx-paperclip"></i> {$f|regex_replace:'/^.*\//':''|escape:'html'}</a>
					{/foreach}
				</div>
			</div>
			{/if}
			<!-- việc con -->
			<div class="issue-dw-sec">
				<div class="issue-dw-sechead"><span class="issue-dw-sectitle">Công việc con</span></div>
				<div class="issue_page issue-dw-subwrap">
					<div class="holder_issue_{$issue_id}"><div class="ic-empty"><p>Đang tải...</p></div></div>
				</div>
			</div>
			<!-- checklist / hạng mục -->
			<div class="issue-dw-sec">
				<div class="issue-dw-sechead">
					<span class="issue-dw-sectitle">Hạng mục công việc</span>
					<span class="oqAupliNnN_{$issue_id} issue-dw-chkcount">
						<span class="text-success"><i class="bx bx-check-circle"></i> <span class="entqeZXPZg_{$issue_id}">0</span> xong</span>
						<span class="text-muted ms-2">○ <span class="rajqXvSIGn_{$issue_id}">0</span> còn lại</span>
					</span>
				</div>
				<div class="issue-dw-chkbar issue-dw-chkbar_{$issue_id}"><i></i></div>
				<div class="holder_task_{$issue_id} issue-dw-taskholder"></div>
				{assign var = toId value = $clsISO->getUniqid()}
				<div id="{$toId}" class="w-100{if $permiss_task eq '0'} d-none{/if} mt-2">
					<button class="issue-dw-act" onClick="$Core.issue.add_issue_task(this, event)" toId="{$toId}" issue_id="{$issue_id}"><i class="bx bx-plus"></i> Thêm mục công việc</button>
				</div>
			</div>
			<!-- báo cáo tiến độ nhanh -->
			<div class="issue-dw-card issue-dw-report">
				<div class="issue-dw-report-h">Báo cáo tiến độ nhanh</div>
				<textarea class="issue-dw-ta" placeholder="Cập nhật bạn đã làm được gì..." readonly onClick="$Core.issue.open_issue_notes(this, event)" issue_id="{$issue_id}" tp="add"></textarea>
				<div class="issue-dw-report-foot">
					<button type="button" class="issue-dw-act" onClick="$Core.issue.open_issue_notes(this, event)" issue_id="{$issue_id}" tp="add"><i class="bx bx-paperclip"></i> Đính kèm file</button>
					<button type="button" class="issue-dw-send" onClick="$Core.issue.open_issue_notes(this, event)" issue_id="{$issue_id}" tp="add">Gửi báo cáo</button>
				</div>
			</div>
			<!-- hoạt động & bình luận -->
			<div class="issue-dw-sec">
				<div class="issue-dw-sechead"><i class="bx bx-message"></i><span class="issue-dw-sectitle">Hành động &amp; trao đổi</span></div>
				<div class="holder_issue_notes_{$issue_id}"><div class="loader text-center p-5"><p class="text-muted">Loading...</p></div></div>
				<div class="issue-dw-cmt">
					<img class="issue-dw-cmt-av" src="{$clsProfile->getAvatar($profile_id)}" alt="">
					<input class="issue-dw-cmt-input" placeholder="Viết bình luận..." readonly onClick="$Core.issue.open_issue_notes(this, event)" issue_id="{$issue_id}" tp="add">
				</div>
			</div>
		</div>
	</div></div>
</div>
