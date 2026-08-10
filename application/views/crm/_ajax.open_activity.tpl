<div class="modal-dialog modal-dialog-centered crm-rec-dialog">
	<div class="modal-content crm-rec-modal">
		<form method="POST" enctype="multipart/form-data">
			<div class="modal-header crm-rec-modal-head">
				<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Cập nhật{/if} báo cáo</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"><i class="bx bx-x"></i></button>
			</div>
			<div class="modal-body">
				{if $tp eq 'notes'}
					<div class="form-group">
						<label class="crm-rec-label">Nội dung</label>
						<textarea name="intro" rows="6" cols="255" class="form-control autosize required" placeholder="Nội dung">{$oneItem.intro}</textarea>
					</div>
				{else}
					<div class="form-group mb-2">
						<label class="crm-rec-label">Hình thức liên hệ</label>
						<div class="crm-rec-seg" role="group" aria-label="Hình thức liên hệ">
							{foreach from=$list_activity item = _oActivity}
							<input type="radio" class="btn-check" name="type_id" id="{$uid}_{$_oActivity.property_id}"
								value="{$_oActivity.property_id}"{if $oneItem.type_id eq $_oActivity.property_id} checked="checked"{/if}>
							<label class="btn" for="{$uid}_{$_oActivity.property_id}"><i class="bx {$_oActivity.image}"></i> {$_oActivity.title}</label>
							{/foreach}
						</div>
					</div>
					<div class="form-group mb-2">
						<label class="crm-rec-label">Nội dung</label>
						<textarea name="intro" rows="4" cols="255" class="form-control autosize required"
							placeholder="Nội dung trao đổi với khách…">{$oneItem.intro}</textarea>
					</div>
					<div class="rounded-2 p-3 bg-label-warning mb-2">
						{if $action eq '_add'}
						<div class="form-group mb-2">
							<label class="crm-rec-label">Bước tiếp theo</label>
							<select name="outcome_id" class="form-control form-select">
								{$crm_task_next_options}
							</select>
						</div>
						{/if}
						{assign var = toId value = $clsISO->getUniqid()}
						<div class="form-group">
							<label class="crm-rec-label">Thời gian</label>
							<div class="crm-rec-time">
								<select onChange="$Core.crm.set_timerange(this, event)" data-type="after_time"
									name="after_time" toId="{$toId}" class="form-control form-select">
									<option value="">Lựa chọn</option>
									{foreach from=$list_times key = _oK item = _oT}
									<option{if $time_def_id eq $_oK} selected{/if} value="{$_oK}">{$_oT}</option>
									{/foreach}
								</select>
								<input type="date" toId="{$toId}" onchange="$Core.crm.set_change(this, event)" class="form-control ipn_{$toId}"
									placeholder="dd/mm/yy" value="{$oneItem.date_id|date_format:'%Y-%m-%d'}" name="date_id">
								<input type="time" toId="{$toId}" onchange="$Core.crm.set_change(this, event)" class="form-control time_{$toId}"
									placeholder="hh:ss" value="{$oneItem.date_id|date_format:'%H:%M'}" name="time_id">
							</div>
						</div>
					</div>
					<div class="form-group form-row mb-2">
						<div class="col-6">
							<label class="crm-rec-label mb-1 me-1 align-self-center">Trạng thái</label>
							<select name="cus_status_id" class="form-control form-select">
								{$clsProperty->getSelectByProperty('CUSTOMER_STATUS', $oCustomer.status_id)}
							</select>
						</div>
						<div class="col-6">
							<label class="crm-rec-label mb-1 me-1 align-self-center">Mục đích</label>
							<select id="{$clsISO->getUniqid()}" name="cus_purpose_id[]" multiple="true" data-width="100%"
								data-placeholder="Mục đích" class="form-control iso-select2">
								{$clsProperty->getSelectByPropertyV2('PURPOSE', $oCustomer.list_purpose_arr, "Nhu cầu")}
							</select>
						</div>
					</div>
					<div class="widget-block collapsed">
						<div onClick="$Core.helper.toggle_block(this,event)" class="widget-header">Thêm chi tiết (kết quả · nhắc hẹn)</div>
						<div class="widget-content">
							{if $type_id eq $smarty.const._FOLLOWUP_TASK_ID}
							<div class="form-group mb-2">
								<div class="form-check-reverse">
									<input type="hidden" name="is_reminder" value="0" />
									<label class="form-label mb-0 me-1 cursor-pointer" for="before_time_{$uid}">Nhắc nhở </label>
									<input class="form-check-input cursor-pointer" name="is_reminder" value="1" type="checkbox" id="before_time_{$uid}" {if $oneItem.is_reminder eq 1}checked{/if}>
								</div>
							</div>
							{/if}
							<label class="crm-rec-check">
								<input type="checkbox" name="is_done" value="1"{if $oneItem.status_id eq $smarty.const._FOLLOWUP_STATUS_DONE_ID} checked{/if}> Hoàn thành Follow-ups (đóng nhắc hẹn hiện tại)
							</label>
							<div class="form-group">
								<label class="crm-rec-label">Kết quả {$titlePage}</label>
								<textarea name="_result" rows="2" cols="255" class="form-control autosize"
									placeholder="Kết quả">{$oneItem._result}</textarea>
							</div>
						</div>
					</div>
				{/if}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" {$props} onClick="$Core.crm.save_activity(this, event)"
					class="btn btn-primary">Lưu lại</button>
			</div>
		</form>
	</div>
</div>
