<div class="modal-dialog modal-dialog-centered">
	<form action="#" class="modal-content" method="POST" enctype="multipart/form-data" onsubmit="return false;">
		<div class="modal-header">
			<h5 class="modal-title">Lịch hẹn</h5>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Loại lịch hẹn</label>
				<div class="btn-group d-flex" role="group">
					{foreach from=$list_activity item = _oActivity}
					<input type="radio" class="btn-check" name="type_id" id="{$uid}_{$_oActivity.property_id}" 
						value="{$_oActivity.property_id}"{if $oneItem.type_id eq $_oActivity.property_id} checked="checked"{/if}>
					<label data-toggle="ripple" class="btn btn-outline-default" for="{$uid}_{$_oActivity.property_id}">
						<i class="bx {$_oActivity.image}"></i> 
						{if $deviceType eq 'phone'}<div class="clearfix"></div>{/if} {$_oActivity.title}
					</label>					
					{/foreach}
					<input type="radio" class="btn-check" name="type_id" id="{$uid}_notes" value="0"{if $_type eq '_note'} checked="checked"{/if}>
					<label data-toggle="ripple" class="btn btn-outline-default" for="{$uid}_notes">
						<i class="bx bx-note"></i> 
						{if $deviceType eq 'phone'}<div class="clearfix"></div>{/if} Ghi chú
					</label>						
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Thời gian</label>
				<div class="clearfix"></div>
				<div class="form-row">
					<div class="col-8 col-xxl-9">
						<input type="date" class="form-control required" placeholder="dd/mm/yy" value="{$date}" name="date_id">
					</div>
					<div class="col-4 col-xxl-3">
						<input type="text" class="form-control max-w-px-200 timepicker" toId="{$gId}" readonly placeholder="hh:ss" name="time_id" value="{$clsISO->formatTime($oneItem.date_id)}">
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Nội dung ghi chú</label>
				<textarea name="intro" rows="4" cols="255" class="form-control autosize required" placeholder="Nội dung">{$oneItem.intro}</textarea>
			</div>
			<div class="divider text-start my-2">
				<div class="divider-text text-uppercase">Nhắc nhở</div>
			</div>
			<div class="form-group">
				<div class="d-flex align-items-center gap-2 mb-2">
					<div class="form-check">
						<input type="hidden" name="is_reminder" value="0" />
						<input class="form-check-input cursor-pointer" name="is_reminder" value="1" type="checkbox" id="is_reminder_{$uid}" 
							toId="group_reminder_{$uid}" onchange="$Core.note_calendar.set_reminder(this, event)"{if $oneItem.is_reminder eq 1} checked="checked"{/if}> 
						<label class="form-check-label" for="is_reminder_{$uid}">Nhắc nhở tôi </label>
					</div>
					<select data-type="before_time" name="before_time" disabled toid="{$uid}" class="form-control w-px-100 form-select">
						{foreach from=$list_times key=key item=_item}
						<option value="{$key}" {if $oneItem.reminder_before eq $key}selected{/if}>{$_item}</option>
						{/foreach}
					</select>
				</div>
				{*<div class="form-check">
					<input type="hidden" name="is_send_zalo" value="0" />
					<input type="checkbox" disabled class="form-check-input me-2 cursor-pointer" name="is_send_zalo" 
						value="1" id="is_send_zalo_{$uid}"{if $oneItem.is_send_zalo eq 1} checked="checked"{/if}> 
					<label class="form-check-label" for="is_send_zalo_{$uid}">Thông báo qua Zalo</label>
				</div>*}
			</div>	
		</div>
		<div class="modal-footer">
			<input type="hidden" name="date" value="{$date}">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" {$props} onClick="$Core.note_calendar.save_note(this, event)" toId="{$toId}" date="{$date}" 
				note_id="{$note_id}" class="btn btn-primary">{if $action eq '_add'}Thêm mới{else}Lưu lại{/if}</button>
		</div>
	</form>
</div>