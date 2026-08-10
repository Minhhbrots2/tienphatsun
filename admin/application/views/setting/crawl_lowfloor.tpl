<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Danh sách đại lý cập nhật thấp tầng</h1>
				<p class="type--subdued mb-0">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0)" class="ui-button ui-button--transparent ui-title-bar__action" title="Thực hiện">
						<i class="fa fa-play"></i> Thực hiện</a>
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
							{$core->get_Lang('AllPages')}
						</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<div class="hastable table-wrapper">
					<table class="table table-bordered mb-0" cellspacing="0" cellpadding="0" width="100%">
						<thead><tr>
							<th class="align-center text-center" width="3%" rowspan="2">No.</th>
							<th class="align-center text-center" width="5%" rowspan="2"></th>
							<th class="align-center text-left" rowspan="2">Tiêu đề</th>
							<th class="align-center text-center" width="20%" colspan="{$lst_project|@count}">Phân khu</th>
							<th class="align-center text-right align-center" width="60px" rowspan="2">{$core->get_Lang('Actions')}</th>
						</tr>
						<tr>
							{foreach from=$lst_project item=_project_name key=key}
							<th class="text-center" width="20%">{$_project_name}</th>
							{/foreach}
						</tr></thead>
						<tbody>
						{if !empty($lstAgency) }
							{foreach from=$lstAgency item=_oItem key=key name=i }
							{assign var = more_information value = $_oItem.more_information}
							{assign var = crawl_lowfloor value = $_oItem.crawl_lowfloor}
								<tr class="tr_agency tr_agency_{$_oItem.property_id}">
									<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
									<td class="align-center text-center">
										<button agency_id="{$_oItem.property_id}" type="button" class="btn btn-default" onClick="$Core.property.crawlLowfloor(this,event)">
											<i class="fa fa-play"></i> 
											<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
											<span>Thực hiện</span>
										</button>
									</td>
									<td class="text-nowrap" data-label="Tiêu đề">{$_oItem.title}</td>
									{foreach from=$lst_project item=_project_name key=key}
										<td class="text-center">
											<label class="switch">
												<input type="checkbox" onchange="$Core.property.setStatusCrawl(this, event)" stock_type="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}" project_id="{$key}" block_id="" agency_id="{$_oItem.property_id}" value="1" class="switch_{$clsISO->getUniqid()}" name="is_crawl" {if !empty($crawl_lowfloor[$key].is_crawl)}checked{/if}>
												<span class="slider round"></span>
											</label>
										</td>
									{/foreach}
									<td class="text-center" data-label="{$core->get_Lang('Actions')}">
										<button class="btn btn-icon btn-default" onClick="$Core.property.open_agency_crawl(this,event)" stock_type="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}" class="btn btn-default" agency_id="{$_oItem.property_id}">{$core->makeIcon('pencil')}</button>
									</td>
								</tr>
							{/foreach}
						{else}
							<tr><td class="text-center" colspan="4">Danh sách trống</td></tr>
						{/if}
						</tbody>
					</table>
				</div>
			</div>
		</div></div>
	</div></div>
</div>