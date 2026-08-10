<div class="issue-tl-nav">
	<button type="button" class="issue-tl-navbtn" onClick="$Core.issue.load_timeline({ldelim}base:'{$tl_prev}'{rdelim})"><i class="bx bx-chevron-left"></i></button>
	<div class="issue-tl-label">{$tl_label}</div>
	<button type="button" class="issue-tl-navbtn" onClick="$Core.issue.load_timeline({ldelim}base:'{$tl_next}'{rdelim})"><i class="bx bx-chevron-right"></i></button>
	<button type="button" class="issue-tl-today" onClick="$Core.issue.load_timeline({ldelim}base:'{$tl_today}'{rdelim})">Hôm nay</button>
</div>
<div class="issue-tl-scroll">
	<div class="issue-tl-grid">
		<div class="issue-tl-head">
			<div class="issue-tl-headname">CÔNG VIỆC</div>
			<div class="issue-tl-days">
				{foreach from=$tl_days item=d}
				<div class="issue-tl-day{if $d.today} today{/if}"><div class="dow">{$d.dow}</div><div class="num">{$d.num}</div></div>
				{/foreach}
			</div>
		</div>
		{if $tl_rows}
		{foreach from=$tl_rows item=t}
		<div class="issue-tl-row" issue_id="{$t.issue_id}" onClick="$Core.issue.view_issue(this, event)">
			<div class="issue-tl-name"><span class="issue-tl-dot {$t.cls}"></span><span class="issue-tl-ttl" title="{$t.title|escape:'html'}">{$t.title|escape:'html'}</span></div>
			<div class="issue-tl-track"><div class="issue-tl-bar {$t.cls}" style="left:{$t.left}%;width:{$t.width}%"><div class="issue-tl-fill" style="width:{$t.pr}%"></div><span class="issue-tl-pct">{$t.pr}%</span></div></div>
		</div>
		{/foreach}
		{else}
		<div class="issue-tl-empty">Không có công việc nào trong khoảng này.</div>
		{/if}
	</div>
</div>
