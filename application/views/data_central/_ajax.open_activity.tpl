<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<form method="POST" enctype="multipart/form-data">
			<div class="modal-header border-bottom">
				<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Cập nhật{/if} {$titlePage}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				{if $tp eq 'notes'}
					<div class="form-group mb-2">
						<label class="form-label mb-1">Nội dung</label>
						<textarea name="intro" rows="6" cols="255" class="form-control autosize required" placeholder="Nội dung">{$oneItem.intro}</textarea>
					</div>
				{else}
					<div class="form-group mb-2">
						<label class="mb-1">Hình thức liên hệ</label>
						<div class="btn-group d-flex" role="group" aria-label="Sắp xếp" bis_skin_checked="1">
							{foreach from=$list_activity item = _oActivity}
							<input type="radio" class="btn-check" name="type_id" id="{$uid}_{$_oActivity.property_id}" 
								value="{$_oActivity.property_id}"{if $oneItem.type_id eq $_oActivity.property_id} checked="checked"{/if}>
							<label class="btn js-ripple btn-outline-default" for="{$uid}_{$_oActivity.property_id}">
								<i class="bx {$_oActivity.image}"></i> 
								{if $deviceType eq 'phone'}<div class="clearfix"></div>{/if}
								{$_oActivity.title}
							</label>					
							{/foreach}
						</div>
					</div>
					<div class="form-group mb-2">
						<label class="form-label mb-1">Nội dung</label>
						<textarea name="intro" rows="6" cols="255" class="form-control autosize required" placeholder="Nội dung">{$oneItem.intro}</textarea>
					</div>
					{assign var = toId value = $clsISO->getUniqid()}
					<div class="form-group mb-2">
						<label class="form-label mb-1">Thời gian</label>
						<div class="form-row">
							<div class="col-12 col-lg-5 mb-2 mb-lg-0">
								<select onChange="$Core.data_central.set_timerange(this, event)" data-type="after_time" 
									name="after_time" toId="{$toId}" class="form-control form-select">
									<option value="">Lựa chọn</option>
									{foreach from=$list_times key = _oK item = _oT}
									<option{if $time_def_id eq $_oK} selected{/if} value="{$_oK}">{$_oT}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-12 col-lg-7">
								<div class="input-group">
									<input type="date" toId="{$toId}" onchange="$Core.data_central.set_change(this, event)" class="form-control ipn_{$toId} w-px-100{if $deviceType eq 'phone'} datepick is_icon{/if}" placeholder="dd/mm/yy" value="{$oneItem.date_id|date_format:'%Y-%m-%d'}" name="date_id">
									<input type="time" toId="{$toId}" onchange="$Core.data_central.set_change(this, event)" class="form-control time_{$toId}" placeholder="hh:ss" value="{$oneItem.date_id|date_format:'%H:%M'}" name="time_id">
								</div>
							</div>
						</div>
					</div>
					{if $type_id eq $smarty.const._FOLLOWUP_TASK_ID}
					<div class="form-group mb-2">
						<div class="form-check-reverse mb-1">
							<input type="hidden" name="is_reminder" value="0" />
							<label class="form-label mb-0 me-1 cursor-pointer" for="before_time_{$uid}">Nhắc nhở </label>
							<input class="form-check-input cursor-pointer" name="is_reminder" value="1" type="checkbox" id="before_time_{$uid}" {if $oneItem.is_reminder eq 1}checked{/if}> 
						</div>	
					</div>
					{/if}
					<div class="p-3 bg-lighter rounded-2">
						<div class="form-group mb-2">
							<div class="form-check form-switch d-flex gap-2">
								<input class="form-check-input w-px-40" name="is_done" value="1" type="checkbox" id="{$toId}"
								{if $oneItem.status_id eq $smarty.const._FOLLOWUP_STATUS_DONE_ID} checked{/if}>
								<label class="form-check-label" for="{$toId}">Hoàn thành Follow-ups</label>
							</div>
						</div>
						<div class="form-group">
							<label class="form-label mb-1">Kết quả {$titlePage}</label>
							<textarea name="_result" rows="2" cols="255" class="form-control autosize" 
								placeholder="Kết quả">{$oneItem._result}</textarea>
						</div>
					</div>
					{if $tp ne 'notes'}
						<div class="form-row">
							<div class="col-12 col-md-4">
								<div class="form-group">
									<label class="form-label mb-1">Tình trạng</label>
									<select name="cus_status_id" class="form-control form-select" data-width="100">
										{$clsProperty->getSelectByProperty('DATA_CENTRAL_STATUS', $status_cus)}
									</select>
								</div>
							</div>
							<div class="col-12 col-md-4">
								<div class="form-group">
									<label class="form-label mb-1">Nhu cầu</label>
									<select id="{$clsISO->getUniqid()}" name="cus_purpose_id[]" multiple="true" data-width="100%" 
										data-placeholder="Nhu cầu" class="form-control iso-select2">
										{$clsProperty->getSelectByPropertyV2('PURPOSE', $oCustomer.list_need_arr, "Nhu cầu")}
									</select>
								</div>
							</div>
							<div class="col-12 col-md-4">
								<div class="form-group">
									<label class="form-label mb-1">Chiến dịch</label>
									<select id="{$clsISO->getUniqid()}" name="campaign_id" data-width="100%" 
										data-placeholder="Chiến dịch" class="form-control iso-select2" {if !empty($followup_id)}disabled{/if} >
										{foreach from=$lstCampaign item=_oItem key=key name=i}
											<option value="{$_oItem.campaign_id}" {if $_oItem.campaign_id eq $campaign_id}selected{/if} >{$_oItem.title}</option>
										{/foreach}
									</select>
									{if !empty($followup_id)}
										<input type="hidden" name="campaign_id" value="{$oneItem.campaign_id}">
									{/if}
								</div>
							</div>
						</div>
					{/if}
				{/if}
			</div>
			<div class="modal-footer">
				<div class="d-flex w-100 align-items-center justify-content-between">
					<div class="d-flex gap-1 align-items-center justify-content-between">
						{if $tp eq 'notes'}
							<select name="campaign_id" class="form-control w-px-125 form-select">
								{foreach from=$lstCampaign item=_oItem key=key name=i}
									<option value="{$_oItem.campaign_id}">{$_oItem.title}</option>
								{/foreach}
							</select>
						{/if}
					</div>
					<div class="group_buttons">
						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
						<button type="button" {$props} onClick="$Core.data_central.save_activity(this, event)" class="btn btn-primary">Lưu lại</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
{literal}
<style type="text/css">
	.ui-datepicker{
		z-index:9999 !important;
	}
</style>
{/literal}