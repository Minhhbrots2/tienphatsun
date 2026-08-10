<div class="issue-overdue-pop">
	<h5 class="iop-title">Việc quá hạn — {$target_name|escape:'html'}</h5>
	{if !empty($list)}
		<div class="iop-list">
			{foreach from=$list item=it}
				<a class="iop-row" issue_id="{$it.issue_id}" onClick="$Core.issue.view_issue(this, event)">
					<span class="iop-name">{$it.title|escape:'html'}</span>
					<span class="iop-due"><i class="bx bx-time-five"></i> {$clsISO->convertTimeToText($it.end_date, true)}</span>
				</a>
			{/foreach}
		</div>
	{else}
		<p class="iop-empty">Không có việc quá hạn.</p>
	{/if}
</div>
