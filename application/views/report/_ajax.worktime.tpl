<div class="table-wrapper">
	<table class="table table-sort table-bordered" width="100%">
		<thead><tr>
			{if $deviceType eq 'phone'}
			<th class="align-center bg-lighter">Nhân viên</th>
			<th width="20%" class="align-center bg-lighter">P.Ban</th>
			<th width="68px" class="align-center text-center bg-lighter">
				<input type="hidden" name="sort_type" value="{$sort_type}" />
				<a class="sortClick{if $sort_type} {$sort_type}{/if}" onClick="$Core.report.do_sort(this,event)">Tổng</a>
			</th>
			<td width="30px" class="align-center bg-lighter"></td>
			{else}
			<th width="20%" class="align-center bg-lighter">Nhân viên</th>
			<th width="10%" class="align-center bg-lighter">P.Ban</th>
			<th width="80px" class="align-center text-center bg-lighter">
				<input type="hidden" name="sort_type" value="{$sort_type}" />
				<a class="sortClick{if $sort_type} {$sort_type}{/if}" onClick="$Core.report.do_sort(this,event)">Tổng</a>
			</th>
			<th width="120px" class="align-center bg-lighter">Ngày nghỉ</th>
			<th width="120px" class="align-center bg-lighter">Loại</th>
			<th class="align-center bg-lighter">Lý do</th>
			{/if}
		</tr></thead>
		{if !empty($list_worktimes)}
			{if $deviceType eq 'phone'}
				<!-- Phone -->
				{foreach from=$list_worktimes key=department_id item = rsList}
					{foreach name=i from=$rsList key=staff_id item = rssList}
					{assign var = uid value = $clsISO->getUniqid()}
					<tr>
						<td>{$clsProfile->getFullName($staff_id)}</td>
						<td>{$clsProperty->getTitle($department_id)}</td>
						<td class="text-center">{$rssList.total_items}</td>
						<td class="align-center text-center"><button onClick="$Core.report.toggleRow(this,event)" toId="{$uid}" class="btn p-1 btn-outline-default">{$clsISO->makeIcon('bx-plus')}</button></td>
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
			{else}
				<!-- Computer -->
				{foreach from=$list_worktimes key=department_id item = rList}
					{foreach name=i from=$rList key=staff_id item = kList}
					<tr>
						<td rowspan="{$kList.total_items+1}">{$clsProfile->getFullName($staff_id)}</td>
						<td rowspan="{$kList.total_items+1}">{$clsProperty->getTitle($department_id)}</td>
						<td rowspan="{$kList.total_items+1}" class="text-center">{$kList.total_items}</td>
					</tr>
						{foreach name=k from=$kList.list_items item = oStaff}
						{assign var = staff_info value = $oStaff.staff_info}
						<tr>
							<td class="text-left">{$clsISO->convertTimeToText($oStaff.worktime_date)}</td>
							<td  class="text-left">
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
					{/foreach}
				{/foreach}
			{/if}
		{else}
			
		{/if}
	</table>
</div>
{literal}
<style type="text/css">
	@media screen and (max-width:575px){
		.table th,
		.table td{ padding:0.525rem 0.325rem; }
	}
</style>
{/literal}