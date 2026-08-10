{if !empty($list_message)}
	{foreach name=i from=$list_message item = _oMsg}
	{assign var = _more_information value = $_oMsg.more_information}
	<tr>
		<td class="align-center">
			<a class="text-link fw-bold text-fs-15"> 
				{$_oMsg.title}
			</a>
			<div class="d-flex align-items-center gap-2">
				<div class="d-flex gap-1 align-items-center text-fs-12">
					<i class="bx bx-user"></i> 
					{$_oMsg.full_name}
				</div>
				<div class="d-flex gap-1 align-items-center text-fs-12">
					<i class="material-icons-outlined no-translate">more_time</i> 
					{$clsISO->convertTimeToText($_oMsg.reg_date, true)}
				</div>
			</div>
		</td>
		<td class="align-center">{$_oMsg.name_group}
			{if $_oMsg.total_groups gt '1'}
			<a class="text-link" data-bs-toggle="tooltip" data-bs-html="true" 
				title="<ul class='xxx pl-3'>{$_oMsg.html_more_group}</ul>">(+{$_oMsg.total_groups} nhóm)</a>
			{/if}
		</td>
		<td class="align-center">
			{if $_more_information.schedule_type eq '_now'}
			<span class="badge w-100 bg-label-primary">Gửi một lần</span>
			{else}
			<span class="badge w-100 bg-label-danger">Gửi hàng ngày</span>
			{/if}
		</td>
		<td class="align-center text-center">
			{if $_more_information.schedule_type eq '_now'}
			<button data-toggle="ripple" onClick="$Core.zalo.do_send_msg(this, event)" msg_id="{$_oMsg.id}" class="btn btn-sm btn-block btn-outline-default"><i class="bx bx-play"></i> Gửi tin</button>
			{else}
				<button data-toggle="ripple" onClick="$Core.zalo.do_send_msg(this, event)" msg_id="{$_oMsg.id}" 
				class="btn btn-sm btn-outline-default w-px-100"><i class="bx bx-play"></i> Gửi tin</button>
				{if $_oMsg.is_active eq '1'}
				<button data-toggle="ripple" msg_id="{$_oMsg.id}" class="btn btn-sm btn-outline-primary w-px-100" 
					onClick="$Core.zalo.set_status_msg(this, event)"><i class="bx bx-pause"></i> Tạm dừng</button>
				{else}
				<button data-toggle="ripple" msg_id="{$_oMsg.id}" class="btn btn-sm btn-outline-default w-px-100" 
					onClick="$Core.zalo.set_status_msg(this, event)"><i class="bx bx-play"></i> Bắt đầu</button>
				{/if}
			{/if}
		</td>
		<td class="align-center text-center status_{$_oMsg.id}">
			{if $_more_information.schedule_type eq '_now'}
				{if $_oMsg.is_send eq '1'}
				<span class="badge bg-label-success w-100">Đã gửi</span>
				{else}
				<span class="badge bg-label-dark w-100">Chưa gửi</span>
				{/if}
			{else}
				<div class="d-flex gap-1 align-items-center">
					{if $_oMsg.is_send eq '1'}
					<span class="badge bg-label-primary flex-fill">Đã gửi</span>
					{else}
					<span class="badge bg-label-dark flex-fill">Chưa gửi</span>
					{/if}
					{if $_oMsg.is_active eq '1'}
					<span class="badge bg-label-success flex-fill">Đang chạy</span>
					{else}
					<span class="badge bg-label-dark flex-fill">Đang dừng</span>
					{/if}
				</div>
			{/if}
		</div>
		<td class="align-center">{$clsISO->convertTimeToText($_oMsg.reg_date, true)}</td>
		<td class="align-center text-center">
			<div class="d-flex align-items-center gap-1">
				<button  onClick="$Core.zalo.history_msg(this, event)" send_type="{$_oMsg.send_type}" msg_id="{$_oMsg.id}" 
					class="btn btn-icon btn-sm btn-outline-default">
					<i class="bx bx-history"></i>
				</button>
				<button  onClick="$Core.zalo.open_msg(this, event)" send_type="{$_oMsg.send_type}" msg_id="{$_oMsg.id}" 
					class="btn btn-icon btn-sm btn-outline-default">
					<i class="bx bx-pencil"></i>
				</button>
				<button onClick="$Core.zalo.delete_msg(this, event)" send_type="{$_oMsg.send_type}" msg_id="{$_oMsg.id}" 
					class="btn btn-icon btn-sm btn-outline-default">
					<i class="bx bx-trash"></i>
				</button>
			</div>
		</td>
	</tr>
	{/foreach}
{else}
	<tr>
		<td class="border-0" colspan="8">
			<div class="text-center p-3">
				<img src="{$URL_IMAGES}/illustration-empty-results.svg" class="w-px-100" />
				<p>Chưa có danh sách nào được khởi tạo</p>
				<button type="button" onclick="$Core.zalo.open_msg(this, event)" msg_id="0" send_type="send_group" class="btn xs:flex-fill bg-white btn-outline-default"><i class="bx bx-plus"></i> Thêm mới</button>
			</div>
		</td>
	</tr>
{/if}