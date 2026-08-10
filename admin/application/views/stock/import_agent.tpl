<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Import bảng hàng Đại Lý')}</h1>
				<p class="type--subdued">{$core->get_Lang('Quản lý toàn bộ Qũy Căn Hộ Dự Án có trong Hệ Thống')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="btn-group btn-group-lg d-flex">
				<button class="btn bg-lg btn-default bg-white{if $_ss_view eq 'morning'} active{/if}" onclick="$Core.stock.setView(this,event)" data-type="morning" data-doc_type="top">Sáng (01-11:59)</button>
				<button class="btn bg-lg btn-default{if $_ss_view eq 'afternoon'} active{/if}" onclick="$Core.stock.setView(this,event)" data-type="afternoon" data-doc_type="top">Chiều (12->23:59)</button>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
			<div class="next-tab__container">
				<ul class="next-tab__list filter-tab-list">
					<li class="filter-tab-item" data-tab-index="1">
						<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">
							{$core->get_Lang('Danh sách đại lý')}
						</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<div class="hastable">
					<table id="tableCall" cellspacing="0" class="table table-vertical table-striped" width="100%">
						<thead><tr>
							<!-- <th width="3%" class="text-center border-end">STT</th>-->
							<th width="10%" class="text-left">Đại lý</th>
							<th width="140px" class="text-right">Thu thập L.Cuối</th>
							<th width="6%" class="text-center">Craw</th>
							<th width="5%" class="text-center">Tổng ({$total_stocks})</th>
							<th width="5%" class="text-center">PTG ({$total_price_sheets_mis})</th>
							<th class="text-left">Link Google Sheet</th>
							<!-- <th width="80px" class="text-center">Tự động</th>-->
						</tr></thead>
						{if !empty($list_agents)}
							{foreach from=$list_agents name=i item = _oG}
							{assign var = more_information value = $_oG.more_information}
							{if !empty($more_information.spreadsheetId)}
							<tr class="" {if !empty($_oG.has_update)}style="background-color:#deffe3 !important"{/if}>
								<!-- <td class="text-center">{$smarty.foreach.i.iteration}</td> -->
								<td class="text-left bold border-end">{$_oG.title}</td>
								<td class="text-right border-end">
									{if !empty($more_information.spreadsheetId) && !empty($more_information.last_cronjob_time)}
										{$clsISO->convertTimeToText($more_information.last_cronjob_time, true)}
										<a href="javascript:void(0);" onClick="$Core.stock.open_import_logs(this, event)" agency_id="{$_oG.property_id}" data-toggle="tooltip" title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
									{else}
										---
									{/if}
								</td>
								<td class="text-center border-end">
									<form action="" enctype="multipart/form-data">
										<div class="d-flex align-items-center">
											<button{if empty($more_information.spreadsheetId)} disabled{/if} onClick="$Core.stock.start_import_agent(this,event)" agency_id="{$_oG.property_id}" class="btn btn-sm btn-success mr-2">{$core->makeIcon('play', 'Crawl')}</button>
											<button onClick="$Core.stock.start_import_stock(this,event)" data-type="_COPY" agency_id="{$_oG.property_id}" class="btn btn-sm btn-danger mr-2">{$core->makeIcon('play', 'Copy/Paste Excel')}</button>
											<button onClick="$Core.stock.choose_image(this,event)" agency_id="{$_oG.property_id}" class="btn btn-sm btn-danger mr-2">{$core->makeIcon('upload', 'Upload Image')}</button>
											<button onClick="$Core.stock.update_ptg(this,event)" agency_id="{$_oG.property_id}" class="btn btn-sm btn-danger mr-2">{$core->makeIcon('upload', 'Update PTG')}</button>
											<input type="file" name="images[]" onChange="$Core.stock.start_import_stock(this,event)" data-type="_IMAGE" agency_id="{$_oG.property_id}" class="d-none file_upload" multiple>
										</div>
									</form>	
								</td>
								<td class="text-center bold border-end">{$_oG.total_stock_in}</td>
								<td class="text-center bold border-end">{$_oG.total_price_sheets_mis_in}</td>
								<td class="text-left">
									<a href="https://docs.google.com/spreadsheets/d/{$more_information.spreadsheetId}/edit#gid=0" target="_blank">Link cập nhật</a>
									{if !empty($_oG.intro)}
									<div class="alert alert-info m-0">{$_oG.intro|html_entity_decode}</div>
									{/if}
								</td>
								<!-- <td class="text-center">
									<label class="switch">
									  <input type="checkbox"{if $more_information.cron_automation_enable eq '1'} checked{/if} name="cron_automation_enable" onChange="$Core.stock.cron_automation_enable(this, event)" agency_id="{$_oG.property_id}" value="1"  />
									  <span class="slider round"></span>
									</label>
								</td>-->
							</tr>
							{/if}
							{/foreach}
						{/if}
					</table>
				</div>
			</div>
		</div></div>
	</div></div>
</div>
<link rel="stylesheet" href="{$URL_CSS}/stock.css?v={$upd_version}">
<script src="{$URL_JS}/jspreadsheet/jexcel.js"></script>
<script src="{$URL_JS}/jspreadsheet/jsuites.js"></script>
<link rel="stylesheet" href="{$URL_JS}/jspreadsheet/jsuites.css" type="text/css" />
<link rel="stylesheet" href="{$URL_JS}/jspreadsheet/jexcel.css" type="text/css" />