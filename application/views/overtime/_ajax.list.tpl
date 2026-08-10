<table class="table table-iloocal table-{$deviceType} table-bordered">
	<thead><tr>
		<th class="align-item">Mã đăng ký</th>
		{if $permiss_view_all_overtime eq '1'}{/if}
		<th class="align-item">Tên nhân viên</th>
		<th class="align-item">Phòng ban</th>
		<th class="align-item">Ngày đăng ký</th>
		<th class="align-item">Bắt đầu</th>
		<th class="align-item">Kết thúc</th>
		<th class="align-item" width="25%">Lý do tăng ca</th>
		<th class="align-item">Ưu tiên</th>
		<th class="align-item">Trạng thái</th>
		<th class="align-item">Tiến độ</th>
		<th class="align-item">Ngày tạo</th>
		<th class="align-item" width="45px"></th>
	</tr></thead>
	{if !empty($list_items)}
		{foreach name=i from=$list_items item = _oItem}
		{assign var = overtime_id value = $_oItem.overtime_id}
		<tr {if $_oItem.is_confirmed eq '1'}class="tr_confirmed"{else}style="background-color:{$_oItem.bgcolor}"{/if} 
			ondblclick="$Core.overtime.view(this, event)" overtime_id="{$overtime_id}">
			<td class="text-left"><a href="javascript:void(0);" overtime_id="{$overtime_id}" onClick="$Core.overtime.view(this, event)">{$_oItem.code}</a>{if $_oItem.is_fullday eq '1'}(+{$smarty.const._SCORE_OVERTIME_FULLDAY}đ){/if}</td>
			{if $permiss_view_all_overtime eq '1'}{/if}
			<td>{$_oItem.oProfile.code} - {$_oItem.oProfile.full_name}</td>
			<td>{$_oItem.oProfile.department_name}</td>
			<td>{$clsISO->convertTimeToText($_oItem.regis_date)}</td>
			<td>{$clsISO->convertTimeToText($_oItem.start_date, true)}</td>
			<td>{$clsISO->convertTimeToText($_oItem.due_date, true)}</td>
			<td class="text-left">
				<div class="line-clamp-1">{$_oItem.content|truncate:40}</div>
			</td>
			<td>{$clsOvertime->getPriority($_oItem.priority_id, true)}</td>
			<td>{$_oItem.status_name}</td>
			<td>{$clsOvertime->getProgress(overtime_id, $_oItem)}</td>
			<td class="text-left">
				<i class="material-icons-outlined">more_time</i>
				{$clsISO->convertTimeToText($_oItem.reg_date, true)}
			</td>
			<td class="text-center">
				<div class="dropdown">
					<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
						data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu w-px-100">
						<a class="dropdown-item" onClick="$Core.overtime.view(this,event)" overtime_id="{$overtime_id}" 
						href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
						{if $_oItem.profile_id eq $profile_id && $_oItem.status_id eq $smarty.const._STATUS_OVERTIME_PENDING_ID}
						<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.overtime.open(this,event)" overtime_id="{$overtime_id}"><i class="bx bx-pencil me-1"></i> Sửa</a>
						<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.overtime.delete(this,event)" overtime_id="{$overtime_id}"><i class="bx bx-trash me-1"></i> Xóa</a>
						{/if}
					</div>
				</div>
			</td>
		</tr>
		{/foreach}
	{else}
	<tr>
		<td class="text-center" colspan="20">
			<div class="p-5 text-center">
				<img src="{$URL_IMAGES}/illustration-empty-results.svg" />
				<p class="text-muted">Chưa có dữ liệu</p>
			</div>
		</td>
	</tr>
	{/if}
</table>
