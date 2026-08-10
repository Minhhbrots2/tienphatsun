{if $holderG eq '_form'}
<div class="modal-dialog modal-ipad">
	<form method="POST" class="modal-content">
		<div class="modal-header pb-3 border-bottom">
			<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Sửa{/if} Follow-Ups<br />
				<span class="text-danger fs-13">
					{$clsISO->makeIcon('bx-user-plus', 'Người tạo: ')}
					{$clsProfile->getFullName($profile_id,$oneProfile)}
				</span>
			</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body bg-lightest">
			<div class="form-row mb-2">
				<div class="col-6 col-md-6">
					{assign var = toId value = $clsISO->getUniqid()}
					<label class="form-label mb-1">Phương thức</label>
					<select id="{$toId}" class="form-control form-select required" name="type_id">
						{$clsProperty->getSelectByProperty('FOLLOWUP_TYPE',$oneFollowUp.type_id)}
					</select>
				</div>
				<div class="col-6 col-md-6">
					{assign var = toId value = $clsISO->getUniqid()}
					<label class="form-label mb-1">Trạng thái</label>
					<select id="{$toId}" class="form-control form-select required" name="status_id">
						{$clsProperty->getSelectByProperty('FOLLOWUP_STATUS',$oneFollowUp.status_id)}
					</select>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-md-6 mb-2 mb-lg-0">
					<label class="form-label mb-1">Thời gian</label>
					<div class="input-group">
						<input type="text" class="form-control datepicker" placeholder="dd/mm/yy" value="{$clsISO->convertTimeToText($oneFollowUp.date_id)}" name="date_id">
						<input type="text" class="form-control timepicker" placeholder="hh:ss" name="time_id" value="{$clsISO->formatTime($oneFollowUp.date_id)}">
					</div>
				</div>
				<div class="col-12 col-md-6">
					<label class="form-label mb-1">Uu tiên</label>
					<select id="{$toId}" class="form-control form-select required" name="priority_id">
						{$clsProperty->getSelectByProperty('_ISSUE_PRIORITY',$oneFollowUp.priority_id)}
					</select>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Nhắc nhở</label>
					<div class="clearfix"></div>
					<div class="input-group">
						<input type="number" class="form-control numberonly" placeholder="Thời gian" 
						value="{$oneFollowUp.reminder_before}" name="reminder_before">
						<select class="form-control form-select" name="reminder_unit">
							{$clsProperty->getSelectByProperty('_TIME_UNIT',$oneFollowUp.reminder_unit)}
						</select>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<label class="form-label mb-1">N. thực hiện</label>
					<select class="iso-selectizeNotSearch required" name="admin_id" data-width="100%" data-placeholder="Người tham gia" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" data-width="100%">
						{if $oneFollowUp.admin_id gt '0'}
						<option value="{$oneFollowUp.admin_id}" selected>
							{$clsProfile->getIndentity($oneFollowUp.admin_id, false)}
						</option>
						{/if}
					</select>
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Miêu tả</label>
				<textarea class="form-control" name="intro" rows="2">{if $action eq '_edit'}{$oneFollowUp.intro}{/if}</textarea>
			</div>
		</div>
		<div class="modal-footer border-top">
			<input type="hidden" name="hid" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" followup_id="{$followup_id}" customer_id="{$customer_id}" onClick="$Core.crm.save_followups(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.ui-datepicker{ z-index:9999 !important}
</style>
{/literal}
{elseif $holderG eq '_kk'}
			<div class="form-group">
				<input type="text" class="form-control filterItemFollowUps" id="txtSearchNoteAll" data-column="keySearch" placeholder="{__('Search')}" />
			</div>
			<div class="form-group" style="min-width:200px">
				<select class="form-control iso-selectboxAjaxSearch filterItemFollowUps" data-column="type_id" data-width="100%" data-placeholder="{__('Type')}" data-minimumInputLengthInit="1" data-allow-clear="true" data-url="{$PCMS_URL}/index.php?mod=ajax&act=ajLoadChoiceOnePropertyGlobe&property_type=_FOLLOWUP_TYPE"></select>
			</div>
			{if $permiss_access eq '1' || $permiss_access eq '2'}
			<div class="form-group" style="min-width:200px">
				<select class="form-control iso-selectboxAjaxSearch filterItemFollowUps"  data-column="admin_id" data-width="100%" data-placeholder="{__('Admin')}" data-minimumInputLengthInit="1" data-allow-clear="true" data-url="{$PCMS_URL}/index.php?mod=ajax&act=ajLoadChoiceOneUserPermiss&notall=1"></select>
			</div>
			{/if}
			<div class="form-group">
				<div class="input-group">
					<input type="text" readonly placeholder="Từ ngày"  class="form-control datepicker filterItemFollowUps" data-column="start_date" id="txtSearchFromDateAll" />
					<input type="text" readonly placeholder="Đến ngày" class="form-control datepicker filterItemFollowUps" data-column="due_date" id="txtSearchToDateAll" />
				</div>
			</div>
			<div class="form-group">
				<button class="btn btn-success filterItemFollowUpsBtn">{$core->makeIcon('search')}</button>
			</div>
		</div>
		<div class="box-body" style="min-height:600px">
			<div id="holderG_follow-ups" class="holderG_follow-ups">
			</div>
		</div>
	</div>
</div>
{elseif $holderG eq 'detail'}
<table class="table table-striped table-hover table-responsive" width="100%" cellpadding="2" cellspacing="2" border="0">
	<thead><tr>
		<th width="3%" class="text-center">No.</th>
		<th width="3%"></th>
		<th width="200px">{__('Customer')}</th>
		<th width="180px">{__('Date')}</th>
		<th width="80px">{__('Type')}</th>
		<th>{__('Content')}</th>
		<th width="100px">{__('_Status')}</th>
		<th  width="100px">{__('Reminders')}</th>
		<th class="text-center" width="60">{__('_Actions')}</th>
	</tr></thead>
	{$htmlTable}
</table>
{if $total_record gt '1'}
<div class="easyui-pagination pagination" id="PagerFollowUps" pageNumber="{$current_page}" pageList="[20,30,50]"></div>
{/if}
{/if}