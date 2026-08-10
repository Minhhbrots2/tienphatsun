{if !empty($list_issues)}
	{foreach from=$list_issues item=_oIssue}
	{assign var=issue_id value=$_oIssue.issue_id}
	{assign var=dr value=$_oIssue.done_ratio}
	{if $_oIssue.status_id eq $smarty.const._ISSUE_STATUS_COMPLETED}{assign var=dr value=100}{/if}
	{assign var=od value=false}
	{if $_oIssue.end_date > 0 and $_oIssue.end_date < $smarty.now and $_oIssue.status_id ne 206 and $_oIssue.status_id ne $smarty.const._ISSUE_STATUS_COMPLETED}{assign var=od value=true}{/if}
	{assign var=st value=$stMap[$_oIssue.status_id]}
	{assign var=pr value=$prMap[$_oIssue.priority_id]}
	{assign var=running value=false}
	{if $_oIssue.status_id eq $smarty.const._ISSUE_STATUS_DOING}{assign var=running value=true}{/if}
	{if $deviceType eq 'phone'}
	<div class="issue-card" route="{$clsIssue->getLink($issue_id, false)}" issue_id="{$issue_id}" onClick="$Core.issue.view_issue(this, event)">
		<div class="icm-head">
			{if $st}<span class="ic-badge st-{$_oIssue.status_id}"><span class="dot"></span>{$st.label}</span>{/if}
			<span class="ic-id">#{$issue_id}</span>
			{if $od}<span class="ic-od">Trễ hạn</span>{/if}
			<span class="ic-av ms-auto" title="Người nhận"><img src="{$clsProfile->getAvatar($_oIssue.assign_to_id)}"><span class="bdg recv">✓</span></span>
		</div>
		<div class="icm-title">{$_oIssue.title|escape:'html'}</div>
		<div class="icm-meta">
			<span class="ic-time{if $od} od{/if}"><i class="bx bx-time-five"></i> {$_oIssue.start_date|date_format:"%d/%m/%Y"} → {$_oIssue.end_date|date_format:"%d/%m/%Y"}</span>
			{if $pr}<span class="ic-prio pr-{$_oIssue.priority_id}"><i class="bx bx-flag"></i><b>{$pr.label}</b></span>{/if}
		</div>
		<div class="ic-prog"><div class="track"><i class="{if $dr >= 100}done{/if}" style="width:{$dr}%"></i></div><b>{$dr}%</b></div>
	</div>
	{else}
	<div class="issue-row" route="{$clsIssue->getLink($issue_id, false)}" issue_id="{$issue_id}" onClick="$Core.issue.view_issue(this, event)">
		<div class="ic-name">
			<button type="button" class="ic-star{if $_oIssue.is_archived eq '1'} on{/if}" onClick="event.stopPropagation(); $Core.issue.archive(this, event)" holderG="{$holderG}" issue_id="{$issue_id}" title="{if $_oIssue.is_archived eq '1'}Bỏ lưu trữ{else}Lưu trữ{/if}"><i class="bx {if $_oIssue.is_archived eq '1'}bxs-star{else}bx-star{/if}"></i></button>
			<span class="ic-id">#{$issue_id}</span>
			<span class="ic-title">{$_oIssue.title|escape:'html'}</span>
			{if $od}<span class="ic-od">Trễ hạn</span>{/if}
		</div>
		<div>{if $st}<span class="ic-badge st-{$_oIssue.status_id}"><span class="dot"></span>{$st.label}</span>{/if}</div>
		<div class="ic-time{if $od} od{/if}">{$_oIssue.start_date|date_format:"%d/%m/%Y"} → {$_oIssue.end_date|date_format:"%d/%m/%Y"}</div>
		<div class="ic-people">
			<span class="ic-av" title="Người giao"><img src="{$clsProfile->getAvatar($_oIssue.user_id)}"><span class="bdg give">+</span></span>
			<span class="ic-av" title="Người nhận"><img src="{$clsProfile->getAvatar($_oIssue.assign_to_id)}"><span class="bdg recv">✓</span></span>
		</div>
		<div class="ic-prio{if $pr} pr-{$_oIssue.priority_id}{/if}">{if $pr}<i class="bx bx-flag"></i><b>{$pr.label}</b>{/if}</div>
		<div class="ic-prog"><div class="track"><i class="{if $dr >= 100}done{/if}" style="width:{$dr}%"></i></div><b>{$dr}%</b></div>
		<div class="ic-timer">
			{if $_oIssue.assign_to_id eq $profile_id}
			{if $running}
			<button type="button" class="tbtn run" onClick="event.stopPropagation(); $Core.issue.stop_issue(this, event)" issue_id="{$issue_id}" title="Tạm dừng"><i class="bx bx-pause"></i></button>
			<span class="ic-clock run time-issue-{$issue_id} timeCountUp">{$clsIssue->getTimeDo($issue_id)}</span>
			{else}
			<button type="button" class="tbtn" onClick="event.stopPropagation(); $Core.issue.start_issue(this, event)" issue_id="{$issue_id}" title="Bắt đầu bấm giờ"><i class="bx bx-play"></i></button>
			<span class="ic-clock">00:00:00</span>
			{/if}
			{else}
			<span class="ic-clock{if $running} run time-issue-{$issue_id} timeCountUp{/if}">{if $running}{$clsIssue->getTimeDo($issue_id)}{else}00:00:00{/if}</span>
			{/if}
		</div>
		<div class="dropdown" onClick="event.stopPropagation();">
			<button type="button" class="ic-more hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
			<div class="dropdown-menu dropdown-menu-end">
				<a class="dropdown-item" onClick="$Core.issue.view_issue(this,event)" issue_id="{$issue_id}" href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
				{if $_oIssue.assign_to_id eq $profile_id && $_oIssue.status_id ne $smarty.const._ISSUE_STATUS_COMPLETED}
				{if $running}
				<a class="dropdown-item text-warning" href="javascript:void(0);" onClick="$Core.issue.stop_issue(this,event)" issue_id="{$issue_id}"><i class="bx bx-pause-circle me-1"></i> Tạm dừng</a>
				{else}
				<a class="dropdown-item text-primary" href="javascript:void(0);" onClick="$Core.issue.start_issue(this,event)" issue_id="{$issue_id}"><i class="bx bx-play-circle me-1"></i> Bắt đầu</a>
				{/if}
				<a class="dropdown-item text-success" href="javascript:void(0);" onClick="$Core.issue.done_issue(this,event)" issue_id="{$issue_id}"><i class="bx bx-check-circle me-1"></i> Hoàn thành</a>
				{/if}
				{if $profile_id eq $_oIssue.user_id || $clsISO->checkDEV()}
				<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.issue.delete_issue(this,event)" issue_id="{$issue_id}"><i class="bx bx-trash me-1"></i> Xóa</a>
				{/if}
			</div>
		</div>
	</div>
	{/if}
	{/foreach}
{else}
	<div class="ic-empty"><img class="my-2 w-px-75" src="{$URL_IMAGES}/table-no-data.png" /><p>Chưa có công việc nào</p></div>
{/if}
