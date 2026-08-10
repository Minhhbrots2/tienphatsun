<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Cấu hình ẩn bảng hàng</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<button type="button" onClick="$Core.property.open_hidden_stock(this,event)" class="btn btn-default" agency_hidden_stock_id="" data-type="_FH">{$core->makeIcon('plus-circle', $core->get_Lang('Addnew'))}</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout ui-layout--full-width">
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
									<th class="text-left" width="5%">No.</th>
									<th class="text-left">Tiêu đề</th>
									<th class="text-left" width="20%">Phân khu</th>
									<th class="text-left" width="100px">Ẩn trên Website</th>
									<th class="text-left" width="10%">{$core->get_Lang('Actions')}</th>
								</tr></thead>
								<tbody>
									{if !empty($agency_hidden_stock_FH) || !empty($agency_hidden_stock_MOC) }
										{assign var=stt value=1}
										{foreach from=$agency_hidden_stock_FH item=_oItem key=key name=i }
											<tr>
												<td data-label="No.">{$stt}</td>
												<td class="text-nowrap" data-label="Tiêu đề">{$_oItem.title}</td>
												<td>
													{$clsProperty->getTitle($_oItem.block_id)}
												</td>
												<td class="text-center">
													{if $_oItem.site eq "_FH"}CA{else}MOC{/if}
												</td>
												<td data-label="{$core->get_Lang('Actions')}">
													<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
														<button class="btn btn-default" onClick="$Core.property.open_hidden_stock(this,event)" class="btn btn-default" agency_hidden_stock_id="{$key}" data-type="_FH" >{$core->makeIcon('pencil')}</button>
														<button class="btn btn-default" onClick="$Core.property.save_hidden_stock(this,event)" agency_hidden_stock_id="{$key}" data-type="_FH" data-action="delete">{$core->makeIcon('trash')}</button>
													</div>
												</td>
											</tr>
											{math equation="x+1" x=$stt assign="stt"}
										{/foreach}
										{foreach from=$agency_hidden_stock_MOC item=_oItem key=key name=i }
											<tr>
												<td data-label="No.">{$stt}</td>
												<td class="text-nowrap" data-label="Tiêu đề">{$_oItem.title}</td>
												<td>
													{$clsProperty->getTitle($_oItem.block_id)}
												</td>
												<td class="text-center">
													{if $_oItem.site eq "_FH"}CA{else}MOC{/if}
												</td>
												<td data-label="{$core->get_Lang('Actions')}">
													<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
														<button class="btn btn-default" onClick="$Core.property.open_hidden_stock(this,event)" class="btn btn-default" agency_hidden_stock_id="{$key}" data-type="_MOC">{$core->makeIcon('pencil')}</button>
														<button class="btn btn-default" onClick="$Core.property.save_hidden_stock(this,event)" agency_hidden_stock_id="{$key}" data-type="_MOC" data-action="delete">{$core->makeIcon('trash')}</button>
													</div>
												</td>
											</tr>
											{math equation="x+1" x=$stt assign="stt"}
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