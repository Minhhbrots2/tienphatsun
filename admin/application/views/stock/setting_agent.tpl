<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Quản lý Đại Lý')}</h1>
				<p class="type--subdued">{$core->get_Lang('Quản lý toàn bộ danh sách đại lý có trong Hệ Thống')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/index.php?mod=project&act=edit&project_id={$project_id}" class="ui-button ui-button--primary ui-title-bar__action" title="{$core->get_Lang('Addnew')}">{$core->makeIcon('plus', $core->get_Lang('Add'))}</a>
				</div>
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
							<th width="3%" class="text-center border-end">STT</th>
							<th width="10%" class="text-left">Đại lý</th>
							<th width="5%" class="text-center">Tổng ({$total_stocks})</th>
							<th class="text-left">Link Google Sheet</th>
							<th width="80px" class="text-center">Tự động</th>
							<th width="150px" class="text-right">Thu thập L.Cuối</th>
							<th width="6%" class="text-center">Craw</th>
						</tr></thead>
						{if !empty($list_agents)}
							{foreach from=$list_agents name=i item = _oG}
							{assign var = more_information value = $_oG.more_information}
							{if !empty($more_information.spreadsheetId)}
							<tr>
								<td class="text-center">{$smarty.foreach.i.iteration}</td>
								<td class="text-left bold border-end">{$_oG.title}</td>
								<td class="text-center bold border-end">{$_oG.total_stock_in}</td>
								<td class="text-left">
									<a href="https://docs.google.com/spreadsheets/d/{$more_information.spreadsheetId}/edit#gid=0" target="_blank">https://docs.google.com/spreadsheets/d/{$more_information.spreadsheetId}/edit#gid=0</a>
									{if !empty($_oG.intro)}
									<div class="alert alert-info m-0">{$_oG.intro}</div>
									{/if}
								</td>
								<td class="text-right">
									{if !empty($more_information.spreadsheetId) && !empty($more_information.last_cronjob_time)}
										{$clsISO->convertTimeToText($more_information.last_cronjob_time, true)}
									{else}
										---
									{/if}
								</td>
								<td class="text-center">
								
								</td>
								<td class="text-center">
									<button{if empty($more_information.spreadsheetId)} disabled{/if} onClick="$Core.stock.do_import_agent_advanced(this,event)" agency_id="{$_oG.property_id}" class="btn btn-sm btn-danger mr-2">{$core->makeIcon('play', 'Crawl')}</button>
								</td>
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