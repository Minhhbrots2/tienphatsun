dâd{if !empty($list_issues)}
	{foreach from=$list_issues item = _oIssue}
	{assign var = issue_id value = $_oIssue.issue_id}
	{assign var = list_childs value = $_oIssue.list_childs}
	<tr class="trIssue">
		<td class="text-left">
			{$clsIssue->getTimeClock($issue_id, $_oIssue)} 
			<a href="javascript:void(0);" route="{$clsIssue->getLink($issue_id, false)}" 
				onClick="$Core.issue.view_issue(this, event)" issue_id="{$issue_id}">
				<strong class="fs-14">{$_oIssue.title}{$_oIssue.state_name}</strong>
			</a>
		</td>
		<td class="text-right text-nowrap">{$clsIssue->getStatusLabel($_oIssue.status_id)}</td>
		<td class="text-left text-nowrap">
			{$clsISO->convertTimeToText($_oIssue.start_date)} 
			<span>{$clsISO->makeIcon('bx-right-arrow-alt')}</span>
			{$clsISO->convertTimeToText($_oIssue.end_date)}
		</td>
		<td class="text-center text-nowrap">
			<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oIssue.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="350">
				<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oIssue.user_id)}">
				<span class="cre">{$core->makeIcon('plus')}</span>
			</a>
		</td>
		<td class="text-center text-nowrap">
			<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oIssue.assign_to_id}" data-toggle="webui-popover" data-trigger="hover" data-width="350">
				<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oIssue.assign_to_id)}">
				<span class="do">{$core->makeIcon('gavel')}</span>
			</a>
		</td>
		<td class="text-left text-nowrap">{$clsIssue->getPriority($_oIssue.priority_id)}</td>
		<td class="text-left text-nowrap">
			{$clsIssue->getProgress($issue_id, $_oIssue)}
		</td>
		<td class="text-left text-nowrap"><span class="mr-2 text-main">
			<i style="transform: translateY(5px);" class="material-icons-outlined">schedule</i> 
			{$_oIssue.time_do}</span>
		</td>
		<td class="text-left text-nowrap"><span class="mr-2 text-muted">
			<i style="transform: translateY(5px);" class="material-icons-outlined">more_time</i> 
			{$clsISO->getTimeAgo($_oIssue.reg_date)}</span>
		</td>
	</tr>
	{if !empty($list_childs)}
		{foreach from = $list_childs item = _oChild}
		{assign var = child_id value = $_oChild.issue_id}
		<tr class="trIssue">
			<td class="text-center"></td>
			<td class="text-left">
				<a href="javascript:void(0);" route="{$clsIssue->getLink($child_id, false)}" 
					onClick="$Core.issue.view_issue(this, event)" issue_id="{$child_id}">
					<strong class="fs-14">{$_oChild.title}</strong>
				</a>
			</td>
			<td class="text-right text-nowrap">{$clsIssue->getStatusLabel($_oChild.status_id)}</td>
			<td class="text-left text-nowrap">
				{$clsISO->convertTimeToText($_oChild.start_date)} 
				<span>{$clsISO->makeIcon('bx-right-arrow-alt')}</span>
				{$clsISO->convertTimeToText($_oChild.end_date)}
			</td>
			<td class="text-center text-nowrap">
				<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oChild.user_id}" 
				   data-toggle="webui-popover" data-trigger="hover" data-width="350">
					<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oChild.user_id)}">
					<span class="cre">{$core->makeIcon('plus')}</span>
				</a>
			</td>
			<td class="text-center text-nowrap">
				<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oChild.assign_to_id}" 
				   data-toggle="webui-popover" data-trigger="hover" data-width="350">
					<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oChild.assign_to_id)}">
					<span class="do">{$core->makeIcon('gavel')}</span>
				</a>
			</td>
			<td class="text-left text-nowrap">{$clsIssue->getPriority($_oChild.priority_id)}</td>
			<td class="text-left text-nowrap">{$clsIssue->getProgress($child_id, $_oChild)}</td>
			<td class="text-left text-nowrap">
				<span class="mr-2 text-muted">
					<i class="material-icons-outlined">more_time</i> 
					{$clsISO->getTimeAgo($_oChild.reg_date)}
				</span>
			</td>
		</tr>
		{/foreach}
	{/if}
	{/foreach}
{else}
	<tr class="no-focus nohover">
		<td class="text-center" colspan="10">
			<div class="text-center">
				<img class="my-2 w-px-75" src="{$URL_IMAGES}/table-no-data.png" />
				<p class="text-center">Chưa có công việc nào</p>
			</div>
		</td>
	</tr>
{/if}
