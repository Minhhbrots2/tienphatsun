<div class="issue-cal-nav">
	<button type="button" class="issue-cal-navbtn" onClick="$Core.issue.load_calendar({ldelim}year:{$cal_prev_y},month:{$cal_prev_m}{rdelim})"><i class="bx bx-chevron-left"></i></button>
	<div class="issue-cal-label">{$cal_label}</div>
	<button type="button" class="issue-cal-navbtn" onClick="$Core.issue.load_calendar({ldelim}year:{$cal_next_y},month:{$cal_next_m}{rdelim})"><i class="bx bx-chevron-right"></i></button>
</div>
<div class="issue-cal-grid">
	<div class="issue-cal-wd"><span>T2</span><span>T3</span><span>T4</span><span>T5</span><span>T6</span><span>T7</span><span>CN</span></div>
	{foreach from=$cal_weeks item=week}
	<div class="issue-cal-week">
		{foreach from=$week item=d}
		{if $d eq null}
		<div class="issue-cal-day out"></div>
		{else}
		<div class="issue-cal-day">
			<div class="issue-cal-daynum{if $d eq $cal_today} today{/if}">{$d}</div>
			{if isset($cal_byday[$d])}
				{foreach from=$cal_byday[$d] item=t name=cd}
				{if $smarty.foreach.cd.index < 3}
				<div class="issue-cal-chip {$stCls[$t.status_id]}" issue_id="{$t.issue_id}" onClick="$Core.issue.view_issue(this, event)" title="{$t.title|escape:'html'}">{$t.title|escape:'html'}</div>
				{/if}
				{/foreach}
				{if $cal_byday[$d]|@count > 3}<div class="issue-cal-more" issue_id="0">+{$cal_byday[$d]|@count - 3} nữa</div>{/if}
			{/if}
		</div>
		{/if}
		{/foreach}
	</div>
	{/foreach}
</div>
