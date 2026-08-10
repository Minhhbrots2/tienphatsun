<div class="ui-title-bar-container ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Cấu hình quản lý website</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<button type="button" onClick="$Core.setting.open_domain(this,event)" class="btn btn-default" site_manager_id="">{$core->makeIcon('plus-circle', $core->get_Lang('Addnew'))}</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
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
						<div class="hastable">
							<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">
								<thead><tr>
									<th class="text-center" width="5%">No.</th>
									<th class="text-left">Tiêu đề</th>
									<th class="text-left">Domain</th>
									<th class="text-center" width="45px">{$core->get_Lang('Actions')}</th>
								</tr></thead>
								<tbody>
								{if !empty($list_domains)}
									{foreach from=$list_domains item=_oItem key=key name=i }
										<tr>
											<td class="text-center" data-label="No.">{$smarty.foreach.i.iteration}</td>
											<td class="text-nowrap" data-label="Tiêu đề">{$_oItem.title}</td>
											<td class="text-nowrap" data-label="Tên miền">{$_oItem.domain}</td>
											<td data-label="{$core->get_Lang('Actions')}">
												<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
													<button class="btn btn-default" onClick="$Core.setting.open_domain(this,event)" class="btn btn-default" domain_id="{$key}">{$core->makeIcon('pencil')}</button>
													<button class="btn btn-default" onClick="$Core.setting.save_domain(this,event)" domain_id="{$key}" data-action="delete">{$core->makeIcon('trash')}</button>
												</div>
											</td>
										</tr>
									{/foreach}
								{else}
									<tr><td class="text-center" colspan="5">Danh sách trống</td></tr>
								{/if}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>