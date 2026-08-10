{if !empty($list_staffs)}
	{foreach from=$list_staffs item = _oStaff name=i}
	<tr>
		{if $deviceType ne 'phone'}
		<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
		{/if}
		<td class="align-center"><strong>{$_oStaff.full_name}</strong></td>
		<td class="align-center">{$_oStaff.department_name}</td>
		<td class="align-center">{$_oStaff.role_name}</td>
		<td class="align-center bg-primary text-white text-center">{$_oStaff.num_target}</td>
		<td class="align-center bg-primary text-white text-center">
			<i class="bx bx-user"></i> {$_oStaff.num_staff}
		</td>
		<td class="align-center bg-xmax text-white text-center">{$_oStaff.total_f1}</td>
		<td class="align-center bg-xmax text-white text-center">{$_oStaff.total_cross}</td>
		<td class="align-center bg-xmax text-white text-center">{$_oStaff.total_sop}</td>
		<td class="align-center bg-xmax text-white text-center">
			<i class="bx bx-user"></i> {$_oStaff.total_staffs}</td>
		<td class="align-center bg-success text-white text-center">{$_oStaff.total_score}</td>
		<td class="align-center bg-success text-white text-center">{$_oStaff.percent_done} %</td>
		<td class="align-center text-center text-main">
			{if !empty($_oStaff.salary)}
				<strong>{$clsISO->formatPrice($_oStaff.salary)}</strong> {$clsISO->getRate()}
			{else}
				{$clsISO->formatPrice($_oStaff.salary)} {$clsISO->getRate()}
			{/if}
		</td>
	</tr>
	{/foreach}
{/if}