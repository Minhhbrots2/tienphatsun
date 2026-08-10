<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Đại lý</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<button type="button" onClick="open_property(this)" class="btn btn-default" property_id="0" property_type="_AGENCY">{$core->makeIcon('plus-circle', $core->get_Lang('Addnew'))}</button>
					<button type="button" onClick="$Core.property.storage_cache(this, event)" class="btn btn-icon btn-default ml2" property_id="0" property_type="_AGENCY" title="Cache">{$core->makeIcon('cloud')}</button> 
					<a href="{$PCMS}/admin?mod={$mod}&act=agency_hidden_stock" class="btn btn-icon btn-default ml2" title="Cấu hình quỹ ẩn"><i class="fa fa-cog fs-16"></i></a> 
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
						<form method="post">
							<div class="form-search form-inline">
								<div class="form-group">
									<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
								<div class="form-group pull-right">
									<a href="{$PCMS_URL}/?mod={$mod}{$pUrl}" class="btn text-white btn-warning">
										<i class="icon-folder-open icon-white"></i> 
										<span>{$core->get_Lang('all')} ({$total_record})</span>
									</a>
									<a href="javascript:void(0)" clsTable="News" class="btn btn-danger text-white btn-delete-all" style="display:none"> 
                           				<i class="icon-remove icon-white"></i> 
                           				<span>{$core->get_Lang('Delete')}</span> 
                           			</a>
								</div>
							</div>
							<div class="freeze-table dragscroll" style="overflow-x: scroll; width:100%;">
								<table id="tableCall" cellspacing="0" class="table table-vertical table-striped no-maxwidth" width="100%">
									<thead>
										<tr>
											<th class="text-center" width="5%" rowspan="2" style="vertical-align: middle;"></th>
											<th class="text-left" width="10%" rowspan="2" style="vertical-align: middle;">{$core->get_Lang('Actions')}</th>
											<th class="text-left" width="5%" rowspan="2" style="vertical-align: middle;">No.</th>
											<th class="text-left" width="45%" rowspan="2" style="vertical-align: middle;">{$core->get_Lang('Name')}</th>
											<th class="text-left" width="20%" rowspan="2" style="vertical-align: middle;">{$core->get_Lang('Code')}</th>
											<th class="text-left" width="20%" rowspan="2" style="vertical-align: middle;"></th>
											<!-- MOC -->
											{if !empty($agency_hidden_stock_MOC)}
												{foreach from=$agency_hidden_stock_MOC item=hidden_stock}
													<th class="text-left" width="15%">{$hidden_stock.title}</th>
												{/foreach}
											{/if}
											<!-- user.FH -->										
											{if !empty($agency_hidden_stock_FH)}
												{foreach from=$agency_hidden_stock_FH item=hidden_stock}
													<th class="text-left" width="15%">{$hidden_stock.title}</th>
												{/foreach}
											{/if}										
											<th class="text-center" rowspan="2" style="vertical-align: middle;">Tình trạng</th>
										</tr>
										<tr>
											<!-- MOC -->
											{if !empty($agency_hidden_stock_MOC)}
												{foreach from=$agency_hidden_stock_MOC item=hidden_stock key=key}
												<th class="text-left">
													<div class="input-group d-flex gap-1">
														<button class="btn btn-sm btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="{$key}" onClick="$Core.property.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
														<button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="{$key}" onClick="$Core.property.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
													</div>
												</th>
												{/foreach}
											{/if}
											<!-- user.FH -->										
											{if !empty($agency_hidden_stock_FH)}
												{foreach from=$agency_hidden_stock_FH item=hidden_stock key=key}
													<th class="text-left">
														<div class="input-group d-flex">
															<button class="btn btn-sm btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="{$key}" onClick="$Core.property.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
															<button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="{$key}" onClick="$Core.property.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
														</div>
													</th>
												{/foreach}
											{/if}
										</tr>
									</thead>
									<tbody class="holderPropertyType_agency">
										
									</tbody>
								</table>
								<div class="d-flex justify-content-center">
									<ul class="pagination">
										{$html_pager}
									</ul>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script>
	$(function(){
		$Core.property.load_list_agency("_AGENCY",{});
	})
</script>
{/literal}