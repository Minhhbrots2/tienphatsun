{if $type eq "load_more"}
	{if !empty($lstIssue)}
		{foreach from=$lstIssue item=issue}
			<div class="kanban-item draggable {if $profile_id ne $issue.user_id}unsortable{/if}" id="{$issue.issue_id}" data-badge-text="UX" data-badge="success">
				<div class="d-flex justify-content-between flex-wrap align-items-center mb-2">
					{$clsIssue->getPriority($issue.priority_id)}
					<div class="dropdown kanban-tasks-item-dropdown">
						<i class="dropdown-toggle bx bx-dots-vertical-rounded" id="kanban-tasks-item-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
						<div class="dropdown-menu dropdown-menu-end" aria-labelledby="kanban-tasks-item-dropdown">								
							<a class="dropdown-item" onClick="view_issue(this,event)" issue_id="{$issue.issue_id}" href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
							{if $profile_id eq $issue.user_id}
								<a class="dropdown-item" href="javascript:void(0);" onClick="delete_issue(this,event)" issue_id="{$issue.issue_id}">
									<i class="bx bx-trash me-1"></i> Xóa
								</a>
							{/if}
						</div>
					</div>
				</div>
				<a class="kanban-text fw-bold" href="javascript:void(0);" route="{$clsIssue->getLink($issue.issue_id, false)}" onClick="view_issue(this, event)" issue_id="{$issue.issue_id}"><strong class="fs-14">{$issue.title}</strong></a>
				<div class="d-flex justify-content-between align-items-center flex-wrap mt-2">
					<div class="d-flex align-items-center me-2 mb-2">
						<i class="bx bx-time-five me-1"></i><span class="attachments">{$clsISO->formatTimeDate($issue.start_date)}</span> - <span class="attachments">{$clsISO->formatTimeDate($issue.end_date)}</span>
					</div> 
					{$clsIssue->getProgress($issue.issue_id, $issue)}
					<div class="avatar-group d-flex align-items-center assigned-avatar">
						<div class="avatar avatar-xs" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$issue.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400" data-target="webuiPopover160">
							<img src="{$clsProfile->getAvatar($issue.user_id)}" alt="Avatar" class="rounded-circle  pull-up">
						</div>
					</div>
				</div>
			</div>
		{/foreach}
	{/if}
{else}
	{foreach from=$data item=status}
		{assign var=lstIssue value=$status.item}
		{assign var=textcolor value=$status.textcolor}
		<div data-id="board-in-progress" data-order="{$status.id}" class="kanban-board droppable">
			<header class="kanban-board-header">
				<div class="kanban-title-board" style="color: {$textcolor}">{$status.title} ({$status.total_record})</div>
			</header>
			<main class="kanban-drag overflow-y-auto" id="{$status.id}">
				{foreach from=$lstIssue item=issue}
					<div class="kanban-item draggable {if $profile_id ne $issue.user_id}unsortable{/if}" id="{$issue.issue_id}" data-badge-text="UX" data-badge="success">
						<div class="d-flex justify-content-between flex-wrap align-items-center mb-2">
							{$clsIssue->getPriority($issue.priority_id)}
							<div class="dropdown kanban-tasks-item-dropdown">
								<i class="dropdown-toggle bx bx-dots-vertical-rounded" id="kanban-tasks-item-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
								<div class="dropdown-menu dropdown-menu-end" aria-labelledby="kanban-tasks-item-dropdown">								
									<a class="dropdown-item" onClick="view_issue(this,event)" issue_id="{$issue.issue_id}" href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
									{if $profile_id eq $issue.user_id}
										<a class="dropdown-item" href="javascript:void(0);" onClick="delete_issue(this,event)" issue_id="{$issue.issue_id}">
											<i class="bx bx-trash me-1"></i> Xóa
										</a>
									{/if}
								</div>
							</div>
						</div>
						<a class="kanban-text fw-bold" href="javascript:void(0);" route="{$clsIssue->getLink($issue.issue_id, false)}" onClick="view_issue(this, event)" issue_id="{$issue.issue_id}"><strong class="fs-14">{$issue.title}</strong></a>
						<div class="d-flex justify-content-between align-items-center flex-wrap mt-2">
							<div class="d-flex align-items-center me-2 mb-2">
								<i class="bx bx-time-five me-1"></i><span class="attachments">{$clsISO->formatTimeDate($issue.start_date)}</span> - <span class="attachments">{$clsISO->formatTimeDate($issue.end_date)}</span>
							</div> 
							{$clsIssue->getProgress($issue.issue_id, $issue)}
							<div class="avatar-group d-flex align-items-center assigned-avatar">
								<div class="avatar avatar-xs" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$issue.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400" data-target="webuiPopover160">
									<img src="{$clsProfile->getAvatar($issue.user_id)}" alt="Avatar" class="rounded-circle  pull-up">
								</div>
							</div>
						</div>
					</div>
				{/foreach}
				{if $status.total_record gt 15}
					<div class="d-flex justify-content-between text-center" id="showmorethisresult_{$status.id}">
						<button type="button" class="showmorethisresult" onClick="$Core.issue.load_more_kanban(this, event)" page="1" data-options='{$options}' data-status="{$status.id}"> 
							<span>Xem thêm</span> 
						</button>
					</div>
				{/if}
			</main>
		</div>
	{/foreach}
{/if}