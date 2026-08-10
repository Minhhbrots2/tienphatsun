{if $action eq '_form'}
	<form method="post" accept-charset="UTF-8" action="">
		<div class="box light bordered">
			<div class="box-title">
				<div class="caption">
					<span class="uppercase">{$core->makeIcon('bell',__('AddSaleStatus'))}</span>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-form-label col-md-4">{__('Subject')} <span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" class="form-control required" name="title"  placeholder="{__('Subject')}" value="{$oneItem.title}" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4">{__('Description')} </label>
							<div class="col-md-8">
								<textarea type="text" class="form-control" name="intro" autocomplete="off" placeholder="{__('Description')}" rows="3" >{$oneItem.intro}</textarea>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-form-label col-md-4">{__('Status')}</label>
							<div class="col-md-8">
								<select class="form-control" name="status_id">
									{$clsISO->getSelectByPropertyTypeNotTitle('_SALE_STATUS',$oneItem.status_id)}
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4">{__('Startdate')} <span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" class="form-control required datepicker" readonly name="start_date" autocomplete="off" placeholder="{__('Startdate')}" value="{if $sale_status_id gt '0'}{$clsISO->convertTimeToText($oneItem.start_date)}{/if}" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4">{__('Duedate')}</label>
							<div class="col-md-8">
								<input type="text" class="form-control datepicker" readonly name="due_date" autocomplete="off" placeholder="{__('Duedate')}" value="{if $sale_status_id gt '0' and $oneItem.due_date gt '0'}{$clsISO->convertTimeToText($oneItem.due_date)}{/if}" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box-end text-center">
				{if $sale_status_id gt '0'}
				<button class="btn btn-default js_cancel-edit-sale-status" onClick="ejs_action_sale_status(this);" potential_id="{$potential_id}" sale_status_id="{$sale_status_id}" type="button">
					{$core->makeIcon('reply',__('Cancel'))}
				</button>
				{/if}
				<button class="btn btn-success js_save-sale_status" onClick="ejs_save_sale_status(this);" potential_id="{$potential_id}" sale_status_id="{$sale_status_id}" type="button">
					{if $sale_status_id gt '0'}{$core->makeIcon('check',__('Update'))}{else}{$core->makeIcon('check',__('AddSaleStatus'))}{/if}
				</button>
			</div>
		</div>
	</form>
{elseif $action eq '_list'}
	<table class="table table-striped table-responsive">
		<thead><tr>
			<th class="text-center" width="5%">No.</th>
			<th>{__('Subject')}</th>
			<th class="text-right" width="95">{__('Startdate')}</th>
			<th class="text-right" width="95">{__('Duedate')}</th>
			<th width="85">{__('Status')}</th>
			<th width="50%">{__('Description')}</th>
			<th width="45px"></th>
			<th width="1px"></th>
		</tr></thead>
		{if $lstItem[0].id ne ''}
			{section name=i loop=$lstItem}
			<tr>
				<td data-label="No." class="text-center">{$smarty.section.i.iteration}</td>
				<td data-label="{__('Subject')}">{$lstItem[i].title}</td>
				<td data-label="{__('Startdate')}" class="text-right">{$clsISO->convertTimeToText($lstItem[i].start_date)}</td>
				<td data-label="{__('Duedate')}" class="text-right">{if $lstItem[i].due_date gt '0'}{$clsISO->convertTimeToText($lstItem[i].due_date)}{else}-{/if}</td>
				<td data-label="{__('Status')}" style="color:{$clsProperty->getTextColor($lstItem[i].status_id)}">{$clsProperty->getTitle($lstItem[i].status_id)}</td>
				<td data-label="{__('Description')}">{$lstItem[i].intro}</td>
				<td data-label=""><a class="btn btn-default btn-sm js_edit-sale-status" onClick="ejs_action_sale_status(this);" sale_status_id="{$lstItem[i].id}" potential_id="{$potential_id}">{$core->makeIcon('pencil')}</a></td>
				<td data-label="" width="1px">&nbsp;</td>
			</tr>
			{/section}
		{else}
			<tr>
				<td colspan="7" class="text-center">
					{$clsISO->renderHTMLNoDocument(__('Not any records(s) here'))}
				</td>
			</tr>
		{/if}
	</table>
{/if}