{if !empty($list_issues)}
	{foreach from=$list_issues item = _oIssue}
	{assign var = issue_id value = $_oIssue.issue_id}
	{assign var = list_childs value = $_oIssue.list_childs}
	<tr class="trIssue">
		{if $deviceType eq 'phone'}
		<!-- <td class="text-center px-0">
			<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="auto" title="{if $_oIssue.is_archived eq '1'}Bỏ lưu trữ{else}Lưu trữ{/if}" data-bs-trigger="hover" class="text-muted" onClick="$Core.issue.archive(this, event)" holderG="{$holderG}" issue_id="{$issue_id}">
				{if $_oIssue.is_archived eq '1'}
					{$clsISO->makeIcon('bx-star text-yellow')}
				{else}
					{$clsISO->makeIcon('bx-star text-blank')}
				{/if}
			</a>
		</td> -->
		<td class="text-left">
			<div class="mb-n1">
				{$clsIssue->getStatusLabel($_oIssue.status_id)}
				<a href="javascript:void(0);" route="{$clsIssue->getLink($issue_id, false)}" onClick="view_issue(this, event)" issue_id="{$issue_id}">
					<strong class="fs-14">{$_oIssue.title}</strong>
				</a>
			</div>
			<span class="text-muted fs-12"><i style="transform: translateY(5px);" class="material-icons-outlined mr-1">update</i> 
			{$clsISO->convertTimeToText($_oIssue.upd_date, true)}</span>
		</td>
		<td class="text-center">
			<div class="d-flex justify-content-center align-items-center">
				<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oIssue.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="350">
					<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oIssue.user_id)}">
					<span class="cre">{$core->makeIcon('plus')}</span>
				</a>
				<span class="py-2 px-1">{$clsISO->makeIcon('bx-right-arrow-alt')}</span>
				<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oIssue.assign_to_id}" data-toggle="webui-popover" data-trigger="hover" data-width="350">
					<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oIssue.assign_to_id)}">
					<span class="do">{$core->makeIcon('gavel')}</span>
				</a>
			</div>
		</td>
		{else}
		<td class="text-center">
			<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="auto" title="{if $_oIssue.is_archived eq '1'}Bỏ lưu trữ{else}Lưu trữ{/if}" data-bs-trigger="hover" class="text-muted" onClick="$Core.issue.archive(this, event)" holderG="{$holderG}" issue_id="{$issue_id}">
				{if $_oIssue.is_archived eq '1'}
					{$clsISO->makeIcon('bx-star text-yellow')}
				{else}
					{$clsISO->makeIcon('bx-star text-blank')}
				{/if}
			</a>
		</td>
		<td class="text-left">
			<a href="javascript:void(0);" route="{$clsIssue->getLink($issue_id, false)}" onClick="view_issue(this, event)" issue_id="{$issue_id}"><strong class="fs-14">{$_oIssue.title}</strong></a>
		</td>
		<td class="text-right">{$clsIssue->getStatusLabel($_oIssue.status_id)}</td>
		<td class="text-left">
			{$clsISO->convertTimeToText($_oIssue.start_date)} 
			<span>{$clsISO->makeIcon('bx-right-arrow-alt')}</span>
			{$clsISO->convertTimeToText($_oIssue.end_date)}
		</td>
		<td class="text-center">
			<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oIssue.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="350">
				<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oIssue.user_id)}">
				<span class="cre">{$core->makeIcon('plus')}</span>
			</a>
		</td>
		<td class="text-center">
			<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oIssue.assign_to_id}" data-toggle="webui-popover" data-trigger="hover" data-width="350">
				<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oIssue.assign_to_id)}">
				<span class="do">{$core->makeIcon('gavel')}</span>
			</a>
		</td>
		<td class="text-left">{$clsIssue->getPriority($_oIssue.priority_id)}</td>
		<td class="text-left">
			{$clsIssue->getProgress($issue_id, $_oIssue)}
		</td>
		<td class="text-left"><span class="mr-2 text-muted"><i style="transform: translateY(5px);" class="material-icons-outlined">more_time</i> {$clsISO->getTimeAgo($_oIssue.reg_date)}</span></td>
		<td class="text-center">
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
					data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
				</button>
				<div class="dropdown-menu w-px-100">
					<a class="dropdown-item" onClick="view_issue(this,event)" issue_id="{$issue_id}" 
					href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
					{if $profile_id eq $_oIssue.user_id}
					<a class="dropdown-item" href="javascript:void(0);" onClick="delete_issue(this,event)" issue_id="{$issue_id}"><i class="bx bx-trash me-1"></i> Xóa</a>
					{/if}
				</div>
			</div>
		</td>
		{/if}
	</tr>
	{if !empty($list_childs)}
		{foreach from = $list_childs item = _oChild}
		{assign var = child_id value = $_oChild.issue_id}
		<tr class="trIssue">
			{if $deviceType eq 'phone'}
			<td class="text-center"></td>
			<td class="text-left">
				<div class="mb-0">
					{$clsIssue->getStatusLabel($_oChild.status_id)}
					<a href="javascript:void(0);" route="{$clsIssue->getLink($child_id, false)}" onClick="view_issue(this, event)" issue_id="{$child_id}">
						<strong class="fs-14">{$_oChild.title}</strong>
					</a>
				</div>
				<span class="text-muted fs-12">
					<i class="material-icons-outlined mr-1">update</i> 
					{$clsISO->convertTimeToText($_oChild.upd_date, true)}
				</span>
			</td>
			<td class="text-center">
				<div class="d-flex justify-content-center align-items-center">
					<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oChild.user_id}" d
					   ata-toggle="webui-popover" data-trigger="hover" data-width="350">
						<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oChild.user_id)}">
						<span class="cre">{$core->makeIcon('plus')}</span>
					</a>
					<span class="py-2 px-1">{$clsISO->makeIcon('bx-right-arrow-alt')}</span>
					<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oChild.assign_to_id}" 
					   data-toggle="webui-popover" data-trigger="hover" data-width="350">
						<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oChild.assign_to_id)}">
						<span class="do">{$core->makeIcon('gavel')}</span>
					</a>
				</div>
			</td>
			{else}
			<td class="text-center"></td>
			<td class="text-left">
				<a href="javascript:void(0);" route="{$clsIssue->getLink($child_id, false)}" onClick="view_issue(this, event)" issue_id="{$child_id}">
					<strong class="fs-14">{$_oChild.title}</strong>
				</a>
			</td>
			<td class="text-right">{$clsIssue->getStatusLabel($_oChild.status_id)}</td>
			<td class="text-left">
				{$clsISO->convertTimeToText($_oChild.start_date)} 
				<span>{$clsISO->makeIcon('bx-right-arrow-alt')}</span>
				{$clsISO->convertTimeToText($_oChild.end_date)}
			</td>
			<td class="text-center">
				<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oChild.user_id}" 
				   data-toggle="webui-popover" data-trigger="hover" data-width="350">
					<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oChild.user_id)}">
					<span class="cre">{$core->makeIcon('plus')}</span>
				</a>
			</td>
			<td class="text-center">
				<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id={$_oChild.assign_to_id}" 
				   data-toggle="webui-popover" data-trigger="hover" data-width="350">
					<img class="avatar avatar-xs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oChild.assign_to_id)}">
					<span class="do">{$core->makeIcon('gavel')}</span>
				</a>
			</td>
			<td class="text-left">{$clsIssue->getPriority($_oChild.priority_id)}</td>
			<td class="text-left">{$clsIssue->getProgress($child_id, $_oChild)}</td>
			<td class="text-left">
				<span class="mr-2 text-muted">
					<i class="material-icons-outlined">more_time</i> 
					{$clsISO->getTimeAgo($_oChild.reg_date)}
				</span>
			</td>
			<td class="text-center">
				<div class="dropdown">
					<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
						data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu w-px-100">
						<a class="dropdown-item" onClick="view_issue(this,event)" issue_id="{$child_id}" 
						href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
						{if $profile_id eq $_oChild.user_id}
						<a class="dropdown-item" href="javascript:void(0);" onClick="delete_issue(this,event)" issue_id="{$child_id}">
							<i class="bx bx-trash me-1"></i> Xóa
						</a>
						{/if}
					</div>
				</div>
			</td>
			{/if}
		</tr>
		{/foreach}
	{/if}
	{/foreach}
{else}
	<tr class="no-focus nohover">
		<td class="text-center" colspan="10">
			<div class="text-center">
				<img class="my-2" src="{$URL_IMAGES}/table-no-data.png" width="150px" />
				<p class="text-center">Chưa có công việc nào</p>
			</div>
		</td>
	</tr>
{/if}
