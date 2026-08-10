{assign var=prMap value=[191=>'Thấp',192=>'Bình thường',193=>'Cao',194=>'Khẩn cấp']}
{if $type eq "load_more"}
	{if !empty($lstIssue)}
		{foreach from=$lstIssue item=issue}
			{assign var=pct value=$issue.done_ratio}
			{assign var=isdone value=false}
			{if $issue.status_id eq 206 or $issue.status_id eq 207}{assign var=pct value=100}{assign var=isdone value=true}{/if}
			{assign var=isod value=false}
			{if $issue.end_date gt 0 and $issue.end_date lt $smarty.now and !$isdone}{assign var=isod value=true}{/if}
			<div class="kanban-item draggable issue-kb-card st-{$issue.status_id}{if $profile_id ne $issue.user_id} unsortable{/if}" id="{$issue.issue_id}" issue_id="{$issue.issue_id}" onClick="$Core.issue.view_issue(this, event)">
				<div class="issue-kb-card-top">
					<span class="issue-kb-id">#{$issue.issue_id}</span>
					{if $prMap[$issue.priority_id]}<span class="issue-kb-prio pr-{$issue.priority_id}"><i class="bx bxs-flag"></i>{$prMap[$issue.priority_id]}</span>{/if}
				</div>
				<div class="issue-kb-title">{$issue.title|escape:'html'}</div>
				<div class="issue-kb-prog">
					<div class="issue-kb-track"><div class="issue-kb-fill{if $isdone} done{/if}" style="width:{$pct}%"></div></div>
					<span class="issue-kb-pct">{$pct}%</span>
				</div>
				<div class="issue-kb-foot">
					<span class="issue-kb-meta{if $isod} od{/if}"><i class="bx bx-time-five"></i>{if $issue.end_date gt 0}{$issue.end_date|date_format:"%d/%m"}{else}--{/if}</span>
					<img class="issue-kb-av" src="{$clsProfile->getAvatar($issue.assign_to_id)}" alt="">
				</div>
			</div>
		{/foreach}
	{/if}
{else}
	{foreach from=$data item=status}
		<div data-id="board-{$status.id}" data-order="{$status.id}" class="kanban-board droppable issue-kb-col st-{$status.id}">
			<div class="issue-kb-colhead">
				<span class="issue-kb-dot"></span>
				<span class="issue-kb-colname">{$status.title}</span>
				<span class="issue-kb-count">{$status.total_record}</span>
				<button type="button" class="issue-kb-add" issue_id="0" parent_id="0" onClick="$Core.issue.open_issue(this, event)" title="Thêm việc"><i class="bx bx-plus"></i></button>
			</div>
			<main class="kanban-drag issue-kb-cards" id="{$status.id}">
				{foreach from=$status.item item=issue}
					{assign var=pct value=$issue.done_ratio}
					{assign var=isdone value=false}
					{if $issue.status_id eq 206 or $issue.status_id eq 207}{assign var=pct value=100}{assign var=isdone value=true}{/if}
					{assign var=isod value=false}
					{if $issue.end_date gt 0 and $issue.end_date lt $smarty.now and !$isdone}{assign var=isod value=true}{/if}
					<div class="kanban-item draggable issue-kb-card st-{$issue.status_id}{if $profile_id ne $issue.user_id} unsortable{/if}" id="{$issue.issue_id}" issue_id="{$issue.issue_id}" onClick="$Core.issue.view_issue(this, event)">
						<div class="issue-kb-card-top">
							<span class="issue-kb-id">#{$issue.issue_id}</span>
							{if $prMap[$issue.priority_id]}<span class="issue-kb-prio pr-{$issue.priority_id}"><i class="bx bxs-flag"></i>{$prMap[$issue.priority_id]}</span>{/if}
						</div>
						<div class="issue-kb-title">{$issue.title|escape:'html'}</div>
						<div class="issue-kb-prog">
							<div class="issue-kb-track"><div class="issue-kb-fill{if $isdone} done{/if}" style="width:{$pct}%"></div></div>
							<span class="issue-kb-pct">{$pct}%</span>
						</div>
						<div class="issue-kb-foot">
							<span class="issue-kb-meta{if $isod} od{/if}"><i class="bx bx-time-five"></i>{if $issue.end_date gt 0}{$issue.end_date|date_format:"%d/%m"}{else}--{/if}</span>
							{if $issue.list_childs|@count gt 0}<span class="issue-kb-meta"><i class="bx bx-check-square"></i>{$issue.list_childs|@count}</span>{/if}
							<img class="issue-kb-av" src="{$clsProfile->getAvatar($issue.assign_to_id)}" alt="">
						</div>
					</div>
				{/foreach}
				{if $status.total_record gt 15}
					<div class="issue-kb-showmore" id="showmorethisresult_{$status.id}">
						<button type="button" class="showmorethisresult" onClick="$Core.issue.load_more_kanban(this, event)" page="1" data-options='{$options}' data-status="{$status.id}">Xem thêm</button>
					</div>
				{/if}
			</main>
		</div>
	{/foreach}
{/if}
