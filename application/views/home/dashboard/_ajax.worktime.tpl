{if !empty($list_worktimes)}
	{foreach from=$list_worktimes key=department_id item = rsList}
		{foreach name=i from=$rsList key=staff_id item = rssList}
		{assign var = uid value = $clsISO->getUniqid()}
		<tr>
			<td>{$clsProfile->getIndentityV3($staff_id, true)}</td>
			<td class="text-center">{$rssList.total_items} lần</td>
			<td class="align-center text-center"><button onClick="$Core.dashboard.toggleRow(this,event)" toId="{$uid}" class="btn p-1  btn-outline-default">{$clsISO->makeIcon('bx-plus fs-16')}</button></td>
		</tr>
		<tr id="{$uid}" class="kZnqTyguZE d-none nohover">
			<td colspan="4" class="p-1">
				<table class="table table-bordered" width="100%">
					<thead><tr>
						<th width="30%" class="align-center bg-lighter">Ngày nghỉ</th>
						<th width="25%" class="align-center bg-lighter">Loại</th>
						<th class="align-center bg-lighter">Lý do</th>
					</tr></thead>
					{foreach name=k from=$rssList.list_items item = oStaff}
					{assign var = staff_info value = $oStaff.staff_info}
					<tr>
						<td class="text-left">{$clsISO->convertTimeToText($oStaff.worktime_date)}</td>
						<td class="text-left">
							{if $staff_info.type eq '_MORNING'}
								Buổi sáng
							{elseif $staff_info.type eq '_AFTERNOON'}
								Buổi chiều
							{else}
								Cả ngày
							{/if}
						</td>
						<td class="text-left">
							{if !empty($staff_info.reason)}
								{$staff_info.reason}
							{else}
								...
							{/if}
						</td>
					</tr>
				{/foreach}
				</table>
			</td>
		</tr>
		{/foreach}
	{/foreach}
{/if}
{literal}
<style type="text/css">
	@media screen and (max-width:575px){
		.table th,
		.table td{ padding:0.525rem 0.325rem; }
	}
</style>
{/literal}