{foreach from=$list_time_points name=i item = _OI}
	{assign var = list_staffs value = $_OI.list_staffs}
	{if !empty($list_staffs)}{/if}
	<div class="card mb-2">
		<div class="card-header">
			<div class="d-flex align-items-center justify-content-between">
				<h3 class="card-title fs-5 mb-0">{$_OI.title}</h3>
				<span class="text-muted text-fs-12">{$clsISO->convertTimeToText($_OI.start_date)} - {$clsISO->convertTimeToText($_OI.end_date)}</span>
			</div>
		</div>
		<div class="card-body">
			<div class="table-container no-shadow overflow-x-auto text-nowrap">
				<table class="table table-bordered" cellpadding="0" cellspacing="0">
					<thead><tr>
						{if $deviceType ne 'phone'}
						<th width="5%" class="align-center h-px-35 bg-lighter text-center">No.</th>
						{/if}
						<th class="align-center bg-lighter h-px-35">Họ và tên</th>
						<th class="align-center bg-lighter h-px-35">Phòng ban</th>
						<th class="align-center bg-lighter h-px-35" width="30%">Ngày vào</th>
					</tr></thead>
					{foreach from = $list_staffs name=k item = _oStaff}
					<tr>
						{if $deviceType ne 'phone'}
						<td class="text-center">{$smarty.foreach.k.iteration}</td>{/if}
						<td class="align-center">{$_oStaff.full_name}</td>
						<td class="align-center">{$_oStaff.department_name}</td>
						<td class="align-center">{$clsISO->convertTimeToText($_oStaff.start_date)}</td>
					</tr>
					{/foreach}
				</table>
			</div>
		</div>
	</div>
	
{/foreach}