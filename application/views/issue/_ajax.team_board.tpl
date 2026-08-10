<div class="issue-team-totals">
	<div class="itt-card"><span class="itt-n">{$totals.people}</span><span class="itt-l">Thành viên</span></div>
	<div class="itt-card"><span class="itt-n">{$totals.open}</span><span class="itt-l">Đang mở</span></div>
	<div class="itt-card itt-danger"><span class="itt-n">{$totals.overdue}</span><span class="itt-l">Quá hạn</span></div>
	<div class="itt-card"><span class="itt-n">{$totals.doing}</span><span class="itt-l">Đang xử lý</span></div>
	<div class="itt-card itt-success"><span class="itt-n">{$totals.done}</span><span class="itt-l">Hoàn thành kỳ này</span></div>
</div>

<div class="issue-team-tablewrap">
	<table class="issue-team-table">
		<thead><tr>
			<th>Thành viên</th>
			<th class="text-center">Đang mở</th>
			<th class="text-center">Đang xử lý</th>
			<th class="text-center">Quá hạn</th>
			<th class="text-center">Hoàn thành kỳ này</th>
			<th class="text-center">Tổng</th>
		</tr></thead>
		<tbody>
			{if !empty($rows)}
				{foreach from=$rows item=r}
					<tr{if $r.overdue > 0} class="has-overdue"{/if}>
						<td>
							<div class="itm-person">
								<img src="{$r.avatar}" class="itm-ava" alt="">
								<span class="itm-name">{$r.name|escape:'html'}</span>
							</div>
						</td>
						<td class="text-center">{$r.open}</td>
						<td class="text-center">{$r.doing}</td>
						<td class="text-center">
							{if $r.overdue > 0}
								<a href="#" class="issue-team-overdue" data-pid="{$r.profile_id}">{$r.overdue}</a>
							{else}
								<span class="itm-zero">0</span>
							{/if}
						</td>
						<td class="text-center">{$r.done}</td>
						<td class="text-center itm-total">{$r.total}</td>
					</tr>
				{/foreach}
			{else}
				<tr><td colspan="6" class="text-center py-4 itm-zero">Chưa có thành viên trong tầm quản lý.</td></tr>
			{/if}
		</tbody>
	</table>
</div>
