{if $action eq '_form'}
<div class="modal-dialog modal-standard">
	<form action="" method="post" class="modal-content" onsubmit="return false;" id="frmIssue" encrupt="miltipart/form-data">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<div class="modal-body">
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Code')}</label>
				<div class="col-md-4">
					<input type="text" class="form-control required" placeholder="Mã" name="setting_code" value="{if $setting_id gt '0'}{$more_information.setting_code}{/if}">
				</div>
				<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Name')}</label>
				<div class="col-md-4">
					<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title" value="{if $setting_id gt '0'}{$oneSetting.title}{/if}">
				</div>
			</div>
			<div class="form-group form-row">
				<label for="" class="col-md-2 text-right col-form-label">{$core->get_Lang('BgColor')}</label>
				<div class="col-md-4">
					<input type="color" class="form-control required" placeholder="Màu nền" 
					name="bgcolor" value="{if $setting_id gt '0'}{$more_information.bgcolor}{/if}">
				</div>
				<label for="" class="col-md-2 text-right col-form-label">{$core->get_Lang('TextColor')}</label>
				<div class="col-md-4">
					<input type="color" class="form-control required" placeholder="Màu chữ" 
					name="textcolor" value="{if $setting_id gt '0'}{$more_information.textcolor}{/if}">
				</div>
			</div>
			{if $setting_type eq '_PROJECT'}
			<div class="form-group form-row">
				<label for="" class="col-md-2 text-right col-form-label">Dự án</label>
				<div class="col-md-4">
					<select class="form-control required" onChange="$Core.setting.select_block(this, event)" 
						toId="slb_Block_Id" name="project_id">
						<option value="0">Chọn dự án</option>
						{foreach from=$list_projects item = _oProject}
						<option{if $more_information.project_id eq $_oProject.project_id} selected{/if} 
							value="{$_oProject.project_id}">{$_oProject.title}</option>
						{/foreach}
					</select>
				</div>
				<label for="" class="col-md-2 text-right col-form-label">Phân khu</label>
				<div class="col-md-4">
					<select class="form-control" id="slb_Block_Id" name="block_id">
						<option value="0">Chọn phân khu</option>
						{if !empty($list_blocks)}
							{foreach from=$list_blocks item = _oBlock}
							<option{if $more_information.block_id eq $_oBlock.property_id} selected{/if} 
								value="{$_oBlock.property_id}">{$_oBlock.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label">File quỹ ôm</label>
				<div class="col-md-4">
					<input type="text" class="form-control spreadsheetId" value="{$more_information.stock_hug_configs.spreadsheetId}" 
						name="stock_hug_configs[spreadsheetId]" onChange="$Core.setting.get_worksheets(this, event)" />
				</div>
				<label class="col-md-2 text-right col-form-label">Sheet name</label>
				<div class="col-md-4">
					<div class="input-group">
						<select class="form-control slb_worksheets" name="stock_hug_configs[sheet_name]">
							<option value="0">Chọn sheet name</option>
							{$html_worksheets}
						</select>
						<div class="input-group-btn">
							<button type="button" setting_id="{$setting_id}" onClick="$Core.setting.open_config_field(this, event)" 
								class="btn btn-cog btn-default"><i class="fa fa-cog"></i> Cài đặt</button>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label">Admin quản lý</label>
				<div class="col-md-10">
					<select class="form-control iso-select2" data-width="100%" multiple="true" name="project_admins[]">
						<option value="0">Admin dự án</option>
						{if !empty($list_staffs)}
							{foreach from=$list_staffs item = _oI}
							<option{if $clsISO->checkItemInArray($_oI.profile_id, $more_information.project_admins)} selected="selected"{/if} 
								value="{$_oI.profile_id}">{$_oI.code} - {$_oI.full_name}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>			
			{else if $setting_type eq '_ACCOUNT'}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Query Tag</label>
					<div class="col-md-4">
						<input type="text" class="form-control spreadsheetId" value="{if $setting_id gt '0' && !empty($more_information.query_tags)}{$clsISO->makeSlashListFromArray($more_information.query_tags)}{/if}" name="tags" placeholder="|ASSET|" autocomplete="on" />
					</div>
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('ParentCategory')}</label>
					<div class="col-md-4">
						<div class="input-group d-flex">
							<select class="form-control" name="parent_id" value="{if $setting_id gt '0'}{$oneSetting.title}{/if}">
								{$clsISO->getSelectBySettingTypeTitle($setting_type,$oneSetting.parent_id,"Danh mục cha")}
							</select>
							<input type="text" class="form-control w-35" Name="account_type" value="{$more_information.account_type}" />
						</div>
					</div>
				</div>			
						
			{else if $setting_type eq '_CRITERIA_ASSET_CATEGORY' || $setting_type eq '_LIABILITIES_EQUITY' || $setting_type eq '_CASH_FLOW_CATEGORY'}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Tài khoản</label>
					<div class="col-md-4">
						<select class="form-control iso-select2 " name="lst_account_id[]" multiple >
							{$clsISO->getSelectBySettingTypeTitle("_ACCOUNT",$more_information.lst_account_id,"Tài khoản")}
						</select>
					</div>
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('ParentCategory')}</label>
					<div class="col-md-4">
						{if $setting_type eq '_CASH_FLOW_CATEGORY'}
							<div class="input-group d-flex">
								<select class="form-control" name="parent_id" value="{if $setting_id gt '0'}{$oneSetting.title}{/if}">
									{$clsISO->getSelectBySettingTypeTitle($setting_type,$oneSetting.parent_id,"Danh mục cha")}
								</select>
								<input type="text" class="form-control w-35" name="direction" value="{$more_information.direction}" />
							</div>
						{else}
						<select class="form-control" name="parent_id" value="{if $setting_id gt '0'}{$oneSetting.title}{/if}">
							{$clsISO->getSelectBySettingTypeTitle($setting_type,$oneSetting.parent_id,"Danh mục cha")}
						</select>
						{/if}
					</div>
				</div>			
			{elseif $setting_type ne _MEETING_ROOM}
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label">{$core->get_Lang('ParentCategory')}</label>
				<div class="col-md-10">
					<select class="form-control" name="parent_id" value="{if $setting_id gt '0'}{$oneSetting.title}{/if}">
						{$clsISO->getSelectBySettingTypeTitle($setting_type,$oneSetting.parent_id,"Danh mục cha")}
					</select>
				</div>
			</div>
			{/if}
			{if $setting_type eq "_CHECKIN_TAGS"}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Icon</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="icon" placeholder="Nhập class icon" id="icon" value="{$more_information.icon}">
					</div>
				</div>			
			{/if}
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Intro')}</label>
				<div class="col-md-10">
					<textarea class="form-control isoTextArea" id="{$clsISO->getUniqid()}" cols="255" placeholder="Nhập giới thiệu" data-name="intro" rows="3">{if $setting_id gt '0'}{$more_information.intro}{/if}</textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			{if $setting_type eq _MEETING_ROOM}
				<input type="hidden" name="office_id" value="{$office_id}">
			{/if}
			<button type="button" class="btn btn-success" onClick="save_setting(this)" toId="{$toId}" 
				_reload="{$_reload}" setting_id="{$setting_id}" setting_type="{$setting_type}">
				{$core->makeIcon('check', $core->get_Lang('Save'))}
			</button>
		</div>
	</form>
</div>
{else}
    {if $setting_type eq _MEETING_ROOM}
		<div class="table-setting text-nowrap overflow-x-auto" style="max-height: 400px">
			<table class="table table-hover table-vertical table-striped table-responsive TableListSetting_{$setting_type}" width="100%">
				<thead style="position:sticky;top:0;background: #FFF" ><tr>
					<th class="text-left" width="5%">No.</th>
					<th class="text-left" width="45%">{$core->get_Lang('Name')}</th>
					<th class="text-left" width="20%">{$core->get_Lang('Code')}</th>
					<th class="text-center">Tình trạng</th>
				</tr></thead>
				<tbody>
				{if !empty($lstOffice)}
					{foreach from=$lstOffice item=_oItem key=key name=i}
						{assign var = office_id value = $_oItem.setting_id}
						{assign var = more_information value = $_oItem.more_information}
						<tr class="bold" id="{$lstSetting[i].setting_id}">
							<td data-label="No.">{$smarty.foreach.i.iteration}</td>
							<td class="text-nowrap" data-label="{$core->get_Lang('Name')}">{$_oItem.title}<a href="javascript:void(0);" onclick="open_setting(this)" office_id="{$office_id}" setting_type="_MEETING_ROOM" setting_id="0"><img src="{$URL_IMAGES}/add.png" width="25px"></a>
							</td>
							<td class="text-center" colspan="2"></td>
						</tr>
						{assign var = lstMeetingRoom value = $arr_meeting_room[$office_id]}
						<tr class="d-none"></tr>
						{if !empty($lstMeetingRoom)}
							{foreach from=$lstMeetingRoom item=_oMeeting key=j name=i_mr}
							{assign var=moreInformation value=$clsISO->to_array_json($_oMeeting.more_information)}
								<tr id="{$_oMeeting.setting_id}">								
									<td data-label="{$core->get_Lang('Actions')}">
										<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
											<button class="btn btn-default" onClick="open_setting(this)" office_id="{$office_id}" setting_id="{$_oMeeting.setting_id}" setting_type="{$setting_type}">{$core->makeIcon('pencil')}</button>
											<button class="btn btn-default" onClick="delete_setting(this)" setting_id="{$_oMeeting.setting_id}" setting_type="{$setting_type}">{$core->makeIcon('trash')}</button>
										</div>
									</td>
									<td data-label="{$core->get_Lang('Name')}">{$_oMeeting.title}</td>
									<td data-label="{$core->get_Lang('Name')}">
										{if !empty($moreInformation.setting_code)}
											{$moreInformation.setting_code}
										{else}
										--
										{/if}
									</td>
									<td class="text-center">
										<label class="switch">
											<input type="checkbox"{if $_oMeeting.is_trash eq '0'} checked{/if} onChange="$Core.setting.set_status(this, event)" 
												setting_id="{$_oMeeting.setting_id}" value="1" />
											<span class="slider round"></span>
										</label>
									</td>
								</tr>
							{/foreach}
						{/if}
					{/foreach}
				{else}
					<tr>
						<td colspan="7" class="text-center">
							{$clsISO->renderHTMLNoDocument($core->get_Lang('Not any records(s) here'))}
						</td>
					</tr>
				{/if}
				</tbody>
			</table>
		</div>
	{elseif $setting_type eq _LIST_FORM_BUSINESS}
		<div class="table-setting text-nowrap overflow-x-auto" style="max-height: 400px">
		<table class="table table-hover table-vertical table-striped table-responsive TableListSetting_{$setting_type}" width="100%">
			<thead style="position:sticky;top:0;background: #FFF" ><tr>
				<th class="text-center" width="5%"></th>
				<th class="text-left" width="5%">No.</th>
				<th class="text-left" width="45%">{$core->get_Lang('Name')}</th>
				<th class="text-left" width="20%">{$core->get_Lang('Code')}</th>
				<th class="text-center">Tình trạng</th>
				<th class="text-left" width="10%">{$core->get_Lang('Actions')}</th>
			</tr></thead>
			<tbody>
			{if $lstSetting[0].setting_id ne ''}
				{section name=i loop=$lstSetting}
				{assign var = setting_id value = $lstSetting[i].setting_id}
				{assign var = more_information value = $lstSetting[i].more_information}
				<tr class="bold" id="{$lstSetting[i].setting_id}">
					<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">
						{$core->makeIcon('bars')}
					</td>
					<td data-label="No.">{$smarty.section.i.iteration}</td>
					<td class="text-nowrap" data-label="{$core->get_Lang('Name')}">{$clsSetting->getTitle($setting_id)}
					</td>
					<td data-label="{$core->get_Lang('Name')}">
						{if !empty($more_information.setting_code)}
							{$more_information.setting_code}
						{else}
						--
						{/if}
					</td>
					<td class="text-center">
						<label class="switch">
							<input type="checkbox"{if $lstSetting[i].is_trash eq '0'} checked{/if} onChange="$Core.setting.set_status(this, event)" 
								setting_id="{$lstSetting[i].setting_id}" value="1" />
							<span class="slider round"></span>
						</label>
					</td>
					<td data-label="{$core->get_Lang('Actions')}">
						<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
							<button class="btn btn-default" onClick="open_setting(this)" setting_id="{$lstSetting[i].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('pencil')}</button>
							<button class="btn btn-default" onClick="delete_setting(this)" setting_id="{$lstSetting[i].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('trash')}</button>
							<button class="btn btn-default btn-add-property" onClick="$Core.setting.addProperty(this, event)" setting_id="{$lstSetting[i].setting_id}" setting_type="{$setting_type}"><i class="fa fa-cog"></i></button>
						</div>
					</td>
				</tr>
				{assign var = lstChild value = $clsSetting->getItems($setting_type, $lstSetting[i].setting_id)}
				<tr class="d-none"></tr>
				{if $lstChild[0].setting_id ne ''}
					{section name=j loop=$lstChild}
					{assign var=moreInformation value=$clsISO->to_array_json($lstChild[j].more_information)}
					<tr id="{$lstChild[j].setting_id}">
						<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
						<td data-label="No.">{$smarty.section.i.iteration}.{$smarty.section.j.iteration}</td>
						<td data-label="{$core->get_Lang('Name')}">+&nbsp;{$clsSetting->getTitle($lstChild[j].setting_id)}
							{if $lstChild[j].image ne ''}
							<span class="label label-default">Icon</span>
							{/if}
						</td>
						<td data-label="{$core->get_Lang('Name')}">
							{if !empty($moreInformation.setting_code)}
								{$moreInformation.setting_code}
							{else}
							--
							{/if}
						</td>
						<td class="text-center">
							<label class="switch">
								<input type="checkbox"{if $lstChild[j].is_trash eq '0'} checked{/if} onChange="$Core.setting.set_status(this, event)" 
									setting_id="{$lstChild[j].setting_id}" value="1" />
								<span class="slider round"></span>
							</label>
						</td>
						<td data-label="{$core->get_Lang('Actions')}">
							<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
								<button class="btn btn-default" onClick="open_setting(this)" setting_id="{$lstChild[j].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('pencil')}</button>
								<button class="btn btn-default" onClick="delete_setting(this)" setting_id="{$lstChild[j].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('trash')}</button>
							</div>
						</td>
					</tr>
					{assign var = lstSubChild value = $clsSetting->getItems($setting_type, $lstChild[j].setting_id)}
					{if !empty($lstSubChild)}
						{section name=k loop=$lstSubChild}
						{assign var=moreInformationChild value=$clsISO->to_array_json($lstSubChild[k].more_information)}
						<tr id="{$lstSubChild[k].setting_id}">
							<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
							<td data-label="No." class="text-left">{$smarty.section.i.iteration}.{$smarty.section.j.iteration}.{$smarty.section.k.iteration}</td>
							<td data-label="{$core->get_Lang('Name')}">++&nbsp;{$clsSetting->getTitle($lstSubChild[k].setting_id)}</td>
							<td data-label="{$core->get_Lang('Name')}">
								{if !empty($moreInformationChild.setting_code)}
									{$moreInformationChild.setting_code}
								{else}
								--
								{/if}
							</td>
							<td class="text-center">
								<label class="switch">
									<input type="checkbox"{if $lstSubChild[k].is_trash eq '0'} checked{/if} onChange="$Core.setting.set_status(this, event)" 
										setting_id="{$lstSubChild[k].setting_id}" value="1" />
									<span class="slider round"></span>
								</label>
							</td>
							<td data-label="{$core->get_Lang('Actions')}">
								<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
									<button class="btn btn-default" onClick="open_setting(this)" setting_id="{$lstSubChild[k].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('pencil')}</button>
									<button class="btn btn-default" onClick="delete_setting(this)" setting_id="{$lstSubChild[k].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('trash')}</button>
								</div>
							</td>
						</tr>
						{assign var = lstSubSubChild value = $clsSetting->getItems($setting_type, $lstSubChild[k].setting_id)}
						{if !empty($lstSubSubChild)}
							{section name=n loop=$lstSubSubChild}
							<tr id="{$lstSubSubChild[n].setting_id}">
								<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
								<td data-label="No.">{$smarty.section.i.iteration}.{$smarty.section.j.iteration}.{$smarty.section.k.iteration}.{$smarty.section.n.iteration}</td>
								<td data-label="{$core->get_Lang('Name')}">+++&nbsp;{$clsSetting->getTitle($lstSubSubChild[n].setting_id)}</td>
								<td class="text-center">
									<label class="switch">
										<input type="checkbox"{if $lstSubSubChild[n].is_trash eq '0'} checked{/if} onChange="$Core.setting.set_status(this, event)" 
											setting_id="{$lstSubSubChild[n].setting_id}" value="1" />
										<span class="slider round"></span>
									</label>
								</td>
								<td data-label="{$core->get_Lang('Actions')}">
									<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
										<button class="btn btn-default" onClick="open_setting(this)" setting_id="{$lstSubSubChild[n].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('pencil')}</button>
										<button class="btn btn-default" onClick="delete_setting(this)" setting_id="{$lstSubSubChild[n].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('trash')}</button>
									</div>
								</td>
							</tr>
							{/section}
						{/if}
						{/section}
					{/if}
					{/section}
				{/if}
				{/section}
			{else}
				<tr>
					<td colspan="7" class="text-center">
						{$clsISO->renderHTMLNoDocument($core->get_Lang('Not any records(s) here'))}
					</td>
				</tr>
			{/if}
			</tbody>
		</table>
	</div>
	{else}
		<div class="table-setting text-nowrap overflow-x-auto">
			<table class="table table-hover table-vertical table-striped table-responsive TableListSetting_{$setting_type}" width="100%">
				<thead style="position:sticky;top:0;z-index: 2;background: #FFF" ><tr>
					<th class="text-center" width="5%"></th>
					<th class="text-left" width="10%">{$core->get_Lang('Actions')}</th>
					<th class="text-left" width="5%">No.</th>
					<th class="text-left" width="20%">{$core->get_Lang('Code')}</th>
					<th class="text-left" width="25%">{$core->get_Lang('Name')}</th>
					{if $setting_type eq _ACCOUNT}
						<th class="text-left" width="20%">Query tags</th>
					{/if}
					<th class="text-center">Tình trạng</th>
				</tr></thead>
				<tbody>
				{if $lstSetting[0].setting_id ne ''}
					{section name=i loop=$lstSetting}
					{assign var = setting_id value = $lstSetting[i].setting_id}
					{assign var = more_information value = $lstSetting[i].more_information}
					<tr class="bold" id="{$lstSetting[i].setting_id}">
						<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">
							{$core->makeIcon('bars')}
						</td>
						<td data-label="{$core->get_Lang('Actions')}">
							<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
								<button class="btn btn-default" onClick="open_setting(this)" setting_id="{$lstSetting[i].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('pencil')}</button>
								<button class="btn btn-default" onClick="delete_setting(this)" setting_id="{$lstSetting[i].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('trash')}</button>
							</div>
						</td>
						<td data-label="No.">{$smarty.section.i.iteration}</td>
						<td data-label="{$core->get_Lang('Name')}">
							{if !empty($more_information.setting_code)}
								{$more_information.setting_code}
							{else}
							--
							{/if}
						</td>
						<td class="text-nowrap" data-label="{$core->get_Lang('Name')}">{$clsSetting->getTitle($setting_id)}
							{if $setting_type eq _ACCOUNT}
								({$more_information.account_type})
							{/if}
							<a href="javascript:void(0);" onclick="open_setting(this)" parent_id="{$setting_id}" setting_type="{$setting_type}" setting_id="0"><img src="{$URL_IMAGES}/add.png" width="25px" /></a>
						</td>
						{if $setting_type eq _ACCOUNT}
							<td data-label="Query tags">
								{if !empty($more_information.query_tags)}
									{$clsISO->makeSlashListFromArray($more_information.query_tags)}
								{else}
								--
								{/if}
							</td>
						{/if}
						<!--<td class="text-center">
							<label class="switch">
								<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_crawl_excel) && $more_information.hide_crawl_excel eq '1'} checked{/if} to_field="hide_crawl_excel" setting_id="{$setting_id}" value="1" /> <span class="slider round"></span>
							</label>
						</td>-->
						<!-- End -->
						<td class="text-center">
							<label class="switch">
								<input type="checkbox"{if $lstSetting[i].is_trash eq '0'} checked{/if} onChange="$Core.setting.set_status(this, event)" 
									setting_id="{$lstSetting[i].setting_id}" value="1" />
								<span class="slider round"></span>
							</label>
						</td>
					</tr>
					{assign var = lstChild value = $clsSetting->getItems($setting_type, $lstSetting[i].setting_id)}
					{if $lstChild[0].setting_id ne ''}
						{section name=j loop=$lstChild}
						{assign var=moreInformation value=$clsISO->to_array_json($lstChild[j].more_information)}
						<tr id="{$lstChild[j].setting_id}">
							<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
							<td data-label="{$core->get_Lang('Actions')}">
								<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
									<button class="btn btn-default" onClick="open_setting(this)" setting_id="{$lstChild[j].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('pencil')}</button>
									<button class="btn btn-default" onClick="delete_setting(this)" setting_id="{$lstChild[j].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('trash')}</button>
								</div>
							</td>
							<td data-label="No.">{$smarty.section.i.iteration}.{$smarty.section.j.iteration}</td>
							<td data-label="{$core->get_Lang('Name')}">
								{if !empty($moreInformation.setting_code)}
									{$moreInformation.setting_code}
								{else}
								--
								{/if}
							</td>
							<td data-label="{$core->get_Lang('Name')}">+&nbsp;{$clsSetting->getTitle($lstChild[j].setting_id)}
								{if $setting_type eq _ACCOUNT}
									({$moreInformation.account_type})
								{/if}
								{if $lstChild[j].image ne ''}
								<span class="label label-default">Icon</span>
								{/if}
							</td>
							{if $setting_type eq _ACCOUNT}
								<td data-label="Query tags">
									{if !empty($moreInformation.query_tags)}
										{$clsISO->makeSlashListFromArray($moreInformation.query_tags)}
									{else}
									--
									{/if}
								</td>
							{/if}

							<td class="text-center">
								<label class="switch">
									<input type="checkbox"{if $lstChild[j].is_trash eq '0'} checked{/if} onChange="$Core.setting.set_status(this, event)" 
										setting_id="{$lstChild[j].setting_id}" value="1" />
									<span class="slider round"></span>
								</label>
							</td>
						</tr>
						{assign var = lstSubChild value = $clsSetting->getItems($setting_type, $lstChild[j].setting_id)}
						{if !empty($lstSubChild)}
							{section name=k loop=$lstSubChild}
							{assign var=moreInformationChild value=$clsISO->to_array_json($lstSubChild[k].more_information)}
							<tr id="{$lstSubChild[k].setting_id}">
								<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
								<td data-label="{$core->get_Lang('Actions')}">
									<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
										<button class="btn btn-default" onClick="open_setting(this)" setting_id="{$lstSubChild[k].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('pencil')}</button>
										<button class="btn btn-default" onClick="delete_setting(this)" setting_id="{$lstSubChild[k].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('trash')}</button>
									</div>
								</td>
								<td data-label="No." class="text-left">{$smarty.section.i.iteration}.{$smarty.section.j.iteration}.{$smarty.section.k.iteration}</td>
								<td data-label="{$core->get_Lang('Name')}">
									{if !empty($moreInformationChild.setting_code)}
										{$moreInformationChild.setting_code}
									{else}
									--
									{/if}
								</td>
								<td data-label="{$core->get_Lang('Name')}">
									++&nbsp;{$clsSetting->getTitle($lstSubChild[k].setting_id)}
									{if $setting_type eq _ACCOUNT}
										({$moreInformationChild.account_type})
									{/if}
								</td>
								{if $setting_type eq _ACCOUNT}
									<td data-label="Query tags">
										{if !empty($moreInformationChild.query_tags)}
											{$clsISO->makeSlashListFromArray($moreInformationChild.query_tags)}
										{else}
										--
										{/if}
									</td>
								{/if}

								<td class="text-center">
									<label class="switch">
										<input type="checkbox"{if $lstSubChild[k].is_trash eq '0'} checked{/if} onChange="$Core.setting.set_status(this, event)" 
											setting_id="{$lstSubChild[k].setting_id}" value="1" />
										<span class="slider round"></span>
									</label>
								</td>
							</tr>
							{assign var = lstSubSubChild value = $clsSetting->getItems($setting_type, $lstSubChild[k].setting_id)}
							{if !empty($lstSubSubChild)}
								{section name=n loop=$lstSubSubChild}
								<tr id="{$lstSubSubChild[n].setting_id}">
									<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
									<td data-label="{$core->get_Lang('Actions')}">
										<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
											<button class="btn btn-default" onClick="open_setting(this)" setting_id="{$lstSubSubChild[n].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('pencil')}</button>
											<button class="btn btn-default" onClick="delete_setting(this)" setting_id="{$lstSubSubChild[n].setting_id}" setting_type="{$setting_type}">{$core->makeIcon('trash')}</button>
										</div>
									</td>
									<td data-label="No.">{$smarty.section.i.iteration}.{$smarty.section.j.iteration}.{$smarty.section.k.iteration}.{$smarty.section.n.iteration}</td>
									<td data-label="{$core->get_Lang('Name')}">+++&nbsp;{$clsSetting->getTitle($lstSubSubChild[n].setting_id)}</td>
									<td class="text-center">
										<label class="switch">
											<input type="checkbox"{if $lstSubSubChild[n].is_trash eq '0'} checked{/if} onChange="$Core.setting.set_status(this, event)" 
												setting_id="{$lstSubSubChild[n].setting_id}" value="1" />
											<span class="slider round"></span>
										</label>
									</td>
								</tr>
								{/section}
							{/if}
							{/section}
						{/if}
						{/section}
					{/if}
					{/section}
				{else}
					<tr>
						<td colspan="7" class="text-center">
							{$clsISO->renderHTMLNoDocument($core->get_Lang('Not any records(s) here'))}
						</td>
					</tr>
				{/if}
				</tbody>
			</table>
		</div>
	{/if}
{/if}
    
    