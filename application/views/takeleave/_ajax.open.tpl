<div class="modal-dialog modal-dialog-centered modal-ipad" id="{$uid}">
	<form class="modal-content" method="post" action="#" enctype="multipart/form-data">
		<div class="modal-header"> 
			<h5 class="modal-title">
				Xin nghỉ phép <br />
				<span class="fs-12 text-main">
					<i class="bx bxs-user-plus"></i>
					{$clsProfile->getFullName($profile_id, $oneProfile)}, {$clsISO->convertTimeToText($smarty.now, true)}
				</span>
			</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group alert alert-danger alert-dismissible">
				<div class="form-row">
					<div class="col-sm-6">
						<label>Tình trạng phép năm {$smarty.now|date_format:"%Y"}:</label>
						{$clsTakeLeave->getInfoTakeleave($profile_id)}
					</div>
					<div class="col-sm-6">
						<label class="">Ngày nghỉ không lương đã dùng:</label> 
						<span class="rate-avg font-16 bold">{$clsTakeLeave->getNumberNoPaidLeave($profile_id)}</span>
					</div>
				</div>
				{if 0}
				<label class="col-form-label text-right col-md-4 col-sm-9">Tình trạng phép năm {$smarty.now|date_format:"%Y"}</label>
				<div class="col-xs-12 col-md-2 col-sm-3 pt-2">
					{$clsTakeLeave->getNumberTakeleaveCurrent($adminid)} / {$clsTakeLeave->getNumberTakeleaveValid($adminid)}
				</div>
				<label class="col-form-label text-right col-md-5 col-sm-9">Ngày nghỉ không lương đã dùng</label>
				<div class="col-xs-12 col-md-1 col-sm-3 pt-2">
					{$clsTakeLeave->getNumberNoPaidLeave($adminid)}
				</div>
				{/if}
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-7 col-md-7">
					<label class="form-label mb-1">Người phụ trách</label>
					<select class="form-control form-select iso-selectize" name="curator_user_id">
						<option value="0">Chọn người phụ trách</option>
						{if !empty($lstCurator)}
							{foreach from=$lstCurator item=item name=item}
							<option{if $oneTakeLeave.curator_user_id eq $item.profile_id} selected{/if} value="{$item.profile_id}">{$clsProfile->getIndentityV2($item.profile_id,$item)}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-5 col-md-5">
					<label class="form-label mb-1">Loại nghỉ phép</label>
					<select class="form-control form-select" name="cat_property_id" 
						onchange="$Core.takeleave.handle_change(this, event)">
						<option value="0">Chọn</option>
						{foreach from=$lstCat item=item name=item}
						<option{if $oneTakeLeave.cat_property_id eq $item.property_id} selected{/if} value="{$item.property_id}">{$clsProperty->getTitle($item.property_id,$item)}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="form-group mb-2">
				<div class="alert-cat-property alert alert-secondary">Bạn cần chọn loại nghỉ phép</div>
			</div>
			<div class="form-group mb-2 form-row">
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Nghỉ có lương(Năm nay)</label>	
					<div class="input-group input-group-merge">
						<input type="number" class="form-control" takeleave_id="{$takeleave_id}" onClick="this.select()" 
						onchange="$Core.takeleave.set_total_day_takeleave(this, event)" name="number_day_paid_leave_this_year" 
						value="{$oneTakeLeave.number_day_paid_leave_this_year}" placeholder="Số ngày nghỉ" />
						<div class="input-group-text">ngày</div>
					</div>
				</div>
				{if $takeleave_configs.is_leave_carryover eq '1'}
				<div class="col-6 col-md-4 {if $smarty.const._MONTH_TAKE_LEAVE_RESET lt $smarty.now|date_format:'%m'} d-none{/if}">
					<label class="form-label mb-1">Nghỉ có lương(Năm trước)</label>	
					<div class="input-group input-group-merge">
						<input type="number" class="form-control" name="number_day_paid_leave_last_year" 
						value="{$oneTakeLeave.number_day_paid_leave_last_year}" takeleave_id="{$takeleave_id}" 
						placeholder="Số ngày nghỉ" onClick="this.select()" onchange="$Core.takeleave.set_total_day_takeleave(this, event)"  />
						<div class="input-group-text">ngày</div>
					</div>
				</div>
				{/if}
				<div class="col-6 col-md-4">
					<label class="form-label mb-1">Nghỉ không lương</label>	
					<div class="input-group input-group-merge">
						<input type="number" onchange="$Core.takeleave.set_total_day_takeleave(this,event)" 
						class="form-control" name="number_day_no_paid_leave" value="{$oneTakeLeave.number_day_no_paid_leave}" 
						onClick="this.select()" placeholder="Số ngày nghỉ" takeleave_id="{$takeleave_id}" />
						<div class="input-group-text">ngày</div>
					</div>
				</div>
				{if $takeleave_configs.is_leave_carryover eq '0'}
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Tổng số ngày nghỉ</label>	
					<div class="input-group input-group-merge">
						<input type="number" class="form-control" readonly name="number_day" value="{$oneTakeLeave.number_day}" 
						placeholder="Số ngày nghỉ" takeleave_id="{$takeleave_id}" />
						<div class="input-group-text">ngày</div>
					</div>
				</div>
				{/if}
			</div>
			<div class="form-group mb-2 form-row">
				{if $takeleave_configs.is_leave_carryover eq '1'}
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Tổng số ngày nghỉ</label>	
					<div class="input-group input-group-merge">
						<input type="number" class="form-control" name="number_day" value="{$oneTakeLeave.number_day}" 
						placeholder="Số ngày nghỉ" readonly takeleave_id="{$takeleave_id}" />
						<div class="input-group-text">ngày</div>
					</div>
				</div>
				{/if}
				<div class="col-12 col-md-{if $takeleave_configs.is_leave_carryover eq '1'}4{else}6{/if} mb-2 mb-lg-0">
					<label class="form-label mb-1">Từ ngày</label>
					<div class="input-group">
						<input type="text" readonly value="{$oneTakeLeave.start_date|date_format:'%d/%m/%Y'}" id="start_date_{$uid}" name="start_date" class="form-control datepicker is_icon w-px-100" takeleave_id="{$takeleave_id}" />
						<select class="form-control form-select" name="start_time">
							{$clsISO->makeSelectTimeTakeLeave($oneTakeLeave.start_time)}
						</select>
					</div>
				</div>
				<div class="col-12 col-md-{if $takeleave_configs.is_leave_carryover eq '1'}4{else}6{/if}">
					<label class="form-label mb-1">Đến ngày </label>
					<div class="input-group">
						<input type="text" readonly value="{$oneTakeLeave.end_date|date_format:'%d/%m/%Y'}" id="end_date_{$uid}" name="end_date" class="form-control w-px-100 datepicker is_icon" takeleave_id="{$takeleave_id}" placeholder="dd/mm/yy" />
						<select class="form-control form-select" name="end_time">
							{$clsISO->makeSelectTimeTakeLeave($oneTakeLeave.end_time,'end')}
						</select>
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Nội dung/Lí do nghỉ</label>
				<textarea id="reason_{$takeleave_id}" name="reason" minlength="20" maxlength="10000" 
					class="form-control required minlength" rows="4" placeholder="Ghi cụ thể lý do nghỉ- không chấp nhận lý do chung như: việc bận/ việc gia đình...">{$oneTakeLeave.reason}</textarea>
			</div>
			<div class="form-group attachments">
				<label class="form-label mb-1">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
				{if !empty($oneTakeLeave.attachments)}
					{foreach name=i from = $oneTakeLeave.attachments item = _oFile}
					<div class="MultiFile-label">
						<a class="MultiFile-remove" href="javascript:void(0)" onclick="$Core.global.news.removeFile(this,event)" news_id="{$oneNews.news_id}" data-url="{$_oFile}">x</a> 
						<span><span class="MultiFile-label" title="{$_oFile}">
							<span class="MultiFile-title">{$_oFile.url}</span></span>
						</span>
					</div>
					{/foreach}
				{/if}
				</div>
				<div class="clearfix"></div>
				<input name="attachments[]" type="file" multiple="multiple" class="maxsize-10240" id="attachments_{$uid}" />
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Create" />
			<button type="button" class="btn{if $deviceType eq 'phone'} flex-fill{/if} btn-outline-secondary" 
				data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn{if $deviceType eq 'phone'} flex-fill{/if} btn-outline-primary" 
				onClick="$Core.takeleave.pop_save_takeleave(this, event);" takeleave_id="{$takeleave_id}">
				{$core->makeIcon('check', 'Lưu lại')}
			</button>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	.form-control:disabled, 
	.form-control[readonly]{background:rgba(255,255,255,1)}
	.modal-body{max-height:calc(100vh - 150px); overflow: auto;}
	.ui-datepicker{ z-index:9999 !important}
	@media (max-width: 767px) {
		.modal-body{max-height:70vh;}
	}
</style>
{/literal}