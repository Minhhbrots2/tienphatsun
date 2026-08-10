{if $holderG eq 'manage'}
<div class="modal right fade" id="open_manage_followups_{$pval_id}" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<button type="button" class="closeEv" aria-label="Close" aria-hidden="true">
				<span aria-hidden="true">&times;</span>
			</button>
			<div class="modal-header">
				<div class="links-bar-create-edit">
					<div class="profile-photo-create-edit">
						<img src="{$_ICON_GENERAL}" width="48px" />
					</div>
				</div>
				<div class="head" style="padding-top: 5px">
					<div class="row">
						<div class="col-md-10">
							<div class="subtitle">{$titlePage}</div>
							<div class="title"><strong>{$name}/{$company}</strong></div>
						</div>
						<div class="col-md-2">
							<a class="btn pull-right btn-success js_add-followups" mod_page="{$mod_page}" act_page="{$act_page}" is_upsale="0" is_transfer="0" pval_id="{$pval_id}" potential_id="{$resource_id}" tp="{$tp}" client_id="{$client_id}" project_id="{$project_id}">{$core->makeIcon('plus-circle', __('Add FollowUps'))}</a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body" style="height: calc(100% - 130px);">
				<div class="homewidget">
					<div class="widget-header">
						<span class="ui-icon ui-icon-minusthick toggleWidget"></span> {__('List FollowUps')}
					</div>
					<div class="widget-content">
						<div class="holderFollowUpCRM_{$resource_id}" pval_id></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{elseif $holderG eq 'add'}
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage} {if $is_upsale eq '1'}({__('Upsales')}){elseif $is_transfer eq '1'}({__('TransferAccountant')}){/if}</strong></h3>
		</div>
		<form method="post" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-md-3 text-right col-form-label">{__('Admin')}</label>
					<div class="col-md-9">
						<select class="form-control required iso-selectbox" name="admin_id">
							{$htmlFormSelectAdmin}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 text-right col-form-label">{__('Type')}</label>
					<div class="col-md-9">
						<select class="form-control required iso-selectbox" name="type_id">
							{$clsISO->getSelectByPropertyTypeNotTitle('_FOLLOWUP_TYPE')}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 text-right col-form-label">{__('Date')} {$core->makeIcon('calendar')}</label>
					<div class="col-md-9 form-inline">
						<div class="form-group">
							<input type="text" class="form-control datepicker" value="{$clsISO->convertTimeToText($smarty.now)}" name="date_id" readonly placeholder="{__('Days')}">
						</div>
						<div class="form-group">    
							 <input type="text" class="form-control timepicker" name="time" value="{$smarty.now|date_format:"%H:%S"}" placeholder="{__('Time')}">   
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 text-right col-form-label">{__('Description')}{if $is_upsale eq '1'}({__('Upsales')}){elseif $is_transfer eq '1'}({__('TransferAccountant')}){/if}</label>
					<div class="col-md-9">
						<textarea class="form-control required" msg_error="Nội dung không được trống" name="content" rows="6" placeholder="{__('Description')}"></textarea>
					</div>
				</div>
				<div id="accounting_notes_area" class="form-group{if $clsVS_ServiceStatus->checkStatusIn($tp,$pval_id,'transfer') eq '0'} hidden{/if}">
					<label class="col-md-3 text-right col-form-label">{__('AccountingNotes')}</label>
					<div class="col-md-9">
						<textarea class="form-control text-red" name="accounting_notes" rows="6" placeholder="{__('AccountingNotes')}">{$clsCrmFollowUp->getNotes($tp, $pval_id)}</textarea>
					</div>
				</div>
			</div>
			{assign var = status_transfered value = $clsVS_ServiceStatus->checkStatusIn($tp,$pval_id,'transfer')}
			<div class="modal-footer">
				{if $is_upsale eq '1'}
				<label class="label-checkbox-standard pull-left">
					<input type="checkbox" {if $status_transfered eq '1'}checked{/if} name="is_upsale" onChange="ejs_change_upsale(this)" value="1" /> {__("TransferAccountant")}
				</label>
				{/if}
				<button type="button" class="btn btn-success pull-right js_save-followups{if $status_transfered eq '1'} js_status-transfered{/if}" is_upsale="{$is_upsale}" is_transfer="{$is_transfer}" holderG="{$tp}" mod_page="{$mod_page}" act_page="{$act_page}" resource_id="{$resource_id}" pval_id="{$pval_id}" project_id="{$project_id}" crm_followup_id="{$crm_followup_id}" info_service="{$info_service}" info_domain="{$info_domain}" info_identity="{$info_identity}">{$core->makeIcon('floppy-o', __("Save"))}</button>
				<button class="btn btn-default pull-right mr-half" data-dismiss="modal">{__("Close")}</button>
			</div>
		</form>
	</div>
</div>
{elseif $holderG eq 'list'}
<table class="table table-hover table-striped table-responsive" width="100%">
	<thead><tr>
		<th class="text-center" width="5%">No.</th>
		<th class="text-left sorthandler {if $sortby eq 'date_id'}bs-sort-{$sorttype}{/if}" column="date_id" width="15%">{__('Date')}</th>
		<th class="text-left sorthandler {if $sortby eq 'type_id'}bs-sort-{$sorttype}{/if}"column="type_id" width="8%">{__('Type')}</th>
		<th class="text-left sorthandler {if $sortby eq 'admin_id'}bs-sort-{$sorttype}{/if}" column="admin_id" width="15%">{__('Admin')}</th>
		<th class="text-left sorthandler {if $sortby eq 'content'}bs-sort-{$sorttype}{/if}" column="content">{__('Description')}</th>
		{if $openFrom eq 'hosting' || $openFrom eq 'addon'}<th class="text-center" width="5%">{__('_Status')}</th>{/if}
		<th class="text-center" width="10%">{__('Reminders')}</th>
		<th class="text-center" width="5%">{__('_Actions')}</th>
	</tr></thead>
	<tbody>
		{if $lstFollowUp}
			{foreach from = $lstFollowUp item = followup name = i}
			{assign var = id value = $followup.crm_followup_id}
			<tr{if $followup.is_done eq '1'} class="done"{/if}>
				<td data-label="No." class="text-center">{$smarty.foreach.i.iteration}</td>
				<td data-label="{__('Date')}">{$clsISO->convertTimeToText($followup.date_id, true)}</td>
				<td data-label="{__('Type')}">{$clsCrmFollowUp->getHTMLType($id, $followup)}</td>
				<td data-label="{__('Admin')}">{$clsUser->getFullName($followup.admin_id)}</td>
				<td data-label="{__('Description')}" class="white-space-normal-all">{$followup.content}</td>
				{if $openFrom eq 'hosting' || $openFrom eq 'addon'}
				<td bgcolor="#F5F5F5" data-label="{__('Description')}" class="white-space-normal-all text-center">
					{if $followup.is_done}{$core->makeIcon('check')}{/if}
				</td>
				{/if}
				<td data-label="{__('Reminders')}" class="text-center"><button class="iso-button-small">{$followup.number_reminder}</button></td>
				<td data-label="{__('_Actions')}" class="text-center">
					<div class="dropdown dropdown-action">
						<a class="dropdown-toggle" data-toggle="dropdown"> 
							{$core->makeIcon('ellipsis-v')}
						</a>
						{if $followup.user_id eq $adminid || $clsISO->checkPermission('full_permissions_crm')}
						<ul class="dropdown-menu">
							<li><a  class="aj_open-followup-reschedue" {$props} crm_followup_id="{$id}">{$core->makeIcon('clock-o',__('Reschedue'))}</a></li>
							<li><a class="aj_open-followup" {$props} crm_followup_id="{$id}">{$core->makeIcon('pencil',__('EditFollowUp'))}</a></li>
							{if $followup.is_done eq '0'}
							<li><a class="aj_done-followup" {$props} crm_followup_id="{$id}">{$core->makeIcon('check-circle-o',__('Done'))}</a></li>
							{/if}
							<li><a class="aj_delete-followup" {$props} crm_followup_id="{$id}">{$core->makeIcon('trash',__('Delete'))}</a></li>
						</ul>
						{/if}
					</div>
				</td>
			</tr>
			{/foreach}
		{else}
			<tr>
				<td colspan="7">{$clsISO->renderHTMLNoDocument('Not any follow-up(s) in this contact')}</td>
			</tr>
		{/if}
	</tbody>
</table>
{/if}