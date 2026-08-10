<div class="modal-dialog modal-dialog-centered">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_edit'}Sửa{else}Đăng ký{/if} lịch phòng họp</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label for="name" class="form-label mb-1">Tiêu đề</label>
				<input type="text" name="title" value="{if $action eq '_edit'}{$oneCalendar.title}{/if}"
				class="form-control required" placeholder="Tiêu đề" maxlength="255" charset="UTF-8" autocomplete="off">
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1"><i class='bx bx-buildings'></i> Phòng họp</label>
				<select class="form-control js__room-select" name="room_combo">
					{if !empty($room_offices)}
						{foreach from=$room_offices item=_oOffice}
							{assign var=_oid value=$_oOffice.office_id}
							{if !empty($room_list[$_oid])}
								<optgroup label="{$_oOffice.title}">
									{foreach from=$room_list[$_oid] item=_oRoom}
										<option value="{$_oRoom.office_id}:{$_oRoom.room_id}"{if $_oRoom.office_id eq $sel_office_id && $_oRoom.room_id eq $sel_room_id} selected{/if}>{if $_oRoom.is_default}Phòng họp {/if}{$_oRoom.title}</option>
									{/foreach}
								</optgroup>
							{/if}
						{/foreach}
					{/if}
				</select>
			</div>
			<div class="form-group mb-2">
				<label for="name" class="form-label mb-1">Thành phần tham gia</label>
				<div class="pl-3">
					<ul class="list-unstyled">
						<li class="mb-2">
							<label for="name" class="form-label mb-1">Phong ban</label>
							<select id="{$clsISO->getUniqid()}" multiple="multiple" class="form-control iso-selectizeNotSearch" placeholder="Chọn phòng ban" data-url="{$PCMS_URL}/index.php?mod=ajax&act=load_department" name="list_department_id[]" data-optgroup="false">{$html_dep_options}</select>
						</li>
						<li class="mb-2">
							<label for="name" class="form-label mb-1">Đội nhóm</label>
							<select id="{$clsISO->getUniqid()}" multiple="multiple" class="form-control iso-selectizeNotSearch" placeholder="Chọn đội nhóm" data-url="{$PCMS_URL}/index.php?mod=ajax&act=load_group_staff" name="list_group_id[]" data-optgroup="false">{$html_group_options}</select>
						</li>
						<li class="mb-2">
							<label for="name" class="form-label mb-1">Nhân viên</label>
							<select id="{$clsISO->getUniqid()}" multiple="multiple" class="form-control iso-selectizeNotSearch" placeholder="Chọn nhân viên" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" name="list_staff_id[]" data-optgroup="false">{$html_staff_options}</select>
						</li>
					</ul>
				</div>
			</div>
			<div class="form-group mb-4">
				<label class="form-label mb-1">Màu sắc</label>
				<div class="d-flex my-3 pl-3 gap-4">
					{foreach from = $list_colors item = _oColor}
					<label class="el-radio-color mr-3">
						<input{if $oneCalendar.bgcolor eq $_oColor} checked{/if} name="bgcolor"
						value="{$_oColor}" style="color:{$_oColor}" type="radio" />
					</label>
					{/foreach}
				</div>
			</div>
			<div class="form-group form-row mt-1">
				<div class="col-6">
					<label for="name" class="form-label mb-1">Ngày</label>
					<input type="date" name="regis_date" value="{$oneCalendar.start_date|date_format:'%Y-%m-%d'}" class="form-control required"
					placeholder="dd/mm/YYYY" maxlength="255" charset="UTF-8" autocomplete="off" />
				</div>
				<div class="col-6">
					<label for="name" class="form-label mb-1">Tới thời gian</label>
					<div class="input-group mb-2">
						<input type="time" name="start_time" value="{$oneCalendar.start_date|date_format:'%H:%M'}"
						class="form-control required" placeholder="hh:ii" maxlength="255" charset="UTF-8" autocomplete="off"  />
						<input type="time" name="end_time" value="{$oneCalendar.end_date|date_format:'%H:%M'}"
						class="form-control required" placeholder="hh:ii" maxlength="255" charset="UTF-8" autocomplete="off" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="form-check">
					{assign var = uid value = $clsISO->getUniqid()}
					<input class="form-check-input"{if $oneCalendar.is_fullday eq '1'} checked{/if}
						type="checkbox" value="1" id="{$uid}" name="is_fullday" onChange="$Core.calendar.set_fullday(this, event)">
					<label class="form-check-label" for="{$uid}"> Cả ngày</label>
				</div>
			</div>
		</div>
		<div class="modal-footer border-top">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" calendar_id="{$calendar_id}" data-toggle="ripple" onClick="$Core.calendar.save(this, event)"
			class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
