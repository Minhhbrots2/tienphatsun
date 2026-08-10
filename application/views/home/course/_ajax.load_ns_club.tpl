<thead>
	<tr>
		<th class="bg-main text-center text-white fs-6 border-0" colspan="{$total_col}" {$total_col}>Báo cáo lan tỏa câu lạc bộ ngôi sao</th>
	</tr>
	<tr>
		<th class="bg-warning text-white border text-center sticky-left" width="60" rowspan="3" style="min-width: 60px">STT</th>
		<th class="bg-warning align-center text-left text-white border sticky-left" rowspan="3"  style="min-width: 150px">Tên sale</th>
		{foreach from=$lstDate item=_oDate key=key name=i}
			{assign var=lstCourse value=$_oDate.lstCourse}
			{if !empty($lstCourse)}
				{if $deviceType eq 'phone'}
					<th class="bg-warning text-center text-white border" width="10%" {if !empty($lstCourse)}colspan="{$lstCourse|@count}"{/if}>{$_oDate.time|date_format:"%d/%m"}</th>
				{else}
					<th class="bg-warning text-center text-white border" width="10%" {if !empty($lstCourse)}colspan="{$lstCourse|@count}"{/if}>{$clsISO->formatTimeDate($_oDate.time)}</th>
				{/if}
			{/if}			

		{/foreach}
		{if $deviceType eq 'phone'}
			<th class="bg-warning align-center text-center text-white border sticky-right" rowspan="3" width="10%">Tỷ lệ</th>
		{else}
			<th class="bg-warning align-center text-center text-white border sticky-right" rowspan="3" width="150">Tỷ lệ báo cáo</th>
		{/if}
	</tr>
	<tr>
		{foreach from=$lstDate item=_oDate key=key name=i}
			{assign var=lstCourse value=$_oDate.lstCourse}
			{if !empty($lstCourse)}
				{foreach from=$lstCourse item=_oCourse key=key name=i}
					<th class="bg-warning text-center text-white border"><a href="{$clsCourse->getLink($_oCourse.course_id)}" target="_blank" title="{$_oCourse.title}">Link<i class="bx bx-link-external ml-1"></i></a></th>
				{/foreach}
			{/if}
		{/foreach}
	</tr>
</thead>
<tbody>
	{if !empty($lstProfile)}
		{foreach from=$lstProfile key=k item=_oItem name=i_member}
			<tr>
				<td class="text-center border sticky-left" style="min-width: 60px">
					{$smarty.foreach.i_member.iteration}
				</td>
				<td class="text-left border sticky-left"  style="min-width: 150px">
					{$_oItem.full_name}
				</td>
				{assign var=bc value=0}
				{assign var=total_course value=0}
				{foreach from=$lstDate item=_oDate key=key name=i}
					{assign var=lstCourse value=$_oDate.lstCourse}
					{math equation="x+y" x=$total_course y=$lstCourse|@count assign="total_course"}
					{if !empty($lstCourse)}
						{foreach from=$lstCourse item=_oCourse key=key name=i}
							{assign var=profile_ids value=$_oCourse.profile_ids}
							<td class="text-center border fw-bold text-success">
								{if $clsISO->checkItemInArray($_oItem.profile_id,$profile_ids)}
								X
								{math equation="x+1" x=$bc assign="bc"}
								{/if}
							</td>
						{/foreach}
					{/if}
				{/foreach}
				<td class="text-center border sticky-right">
					<span class="{if $bc eq $total_course}text-success{else}text-warning{/if} fw-bold">{$bc}</span>/<span class="text-main fw-bold">{$total_course}</span>
				</td>
			</tr>
		{/foreach}
	{else}
		<tr>
			<td colspan="{$lstDate|@count + 2}" class="text-center">Danh sách trống</td>
		</tr>
	{/if}
</tbody>
