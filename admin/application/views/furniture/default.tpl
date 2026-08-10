<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Nội thất</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS}?mod=furniture&act=edit" furniture_id="0" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
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
								<div class="form-group">
									<select name="cat_id" class="form-control" >
										{$clsProperty->getSelectByProperty('_CATEGORYSFURNITURE',$cat_id)}
									</select>
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
								<div class="form-group pull-right">
									<a href="{$PCMS_URL}/?mod={$mod}{$pUrl}" class="btn text-white btn-warning">
										<i class="icon-folder-open icon-white"></i> 
										<span>{$core->get_Lang('all')} ({$total_record})</span>
									</a>
									<a href="javascript:void(0)" clsTable="Furniture" class="btn btn-danger text-white btn-delete-all" style="display:none"> 
                           				<i class="icon-remove icon-white"></i> 
                           				<span>{$core->get_Lang('Delete')}</span> 
                           			</a>
								</div>
							</div>
							<div class="hastable">
								<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">
									<thead><tr>
										<th width="5%" class="text-center" col-span="2">
											<div class="checkbox">
												<input type="checkbox" id="check_all" class="check_all styled" value="1" />
												<label></label>
											</div>
										</th>
										<th class="text-left" width="15%" col-span="2">Tên sản phẩm</th>
										<th class="text-left" width="15%" col-span="2">Danh mục</th>
										<th class="text-left" width="6%">Giá (VNĐ)</th>
										<th class="text-left" width="15%">Đơn vị</th>
										<th class="text-left">Mô tả vật liệu</th>
										<th class="text-center">Trạng thái</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{cycle values="row1,row2"}">
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].furniture_id}" />
												<label></label>
											</div>
										</td>
										<td class="text-left">
											<a href="{$PCMS}?mod={$mod}&act=edit&furniture_id={$allItem[i].furniture_id}">
											<strong class="fs-16">{$allItem[i].title}</strong></a>
											{if $allItem[i].is_trash eq '1'}<span class="fr text-red">{$core->get_Lang('intrash')}</span>{/if}</td>
										<td class="text-left">{$allItem[i].cat_names}</td>
										<td class="text-left">{$clsISO->priceFormat($allItem[i].price)}đ</td>
										<td class="text-left">{$allItem[i].unit_name}</td>
										<td class="text-left">{$allItem[i].intro|html_entity_decode|nl2br}</td>
										<td bgcolor="#F5F5F5" class="text-center">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Furniture" toField="is_online" pkey="furniture_id" sourse_id="{$allItem[i].furniture_id}" rel="{$allItem[i].is_online}" title="{$core->get_Lang('Click to change status')}">
												{if $allItem[i].is_online eq '1'}
												<i class="fa fa-check-circle green"></i>{else}
												<i class="fa fa-minus-circle red"></i>{/if}
											</a>
										</td>
										
										<td class="text-center" style="white-space: nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> 
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													{if $allItem[i].is_trash eq '0'}
														<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&furniture_id={$allItem[i].furniture_id}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
														<li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&furniture_id={$allItem[i].furniture_id}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
													{else}
														<li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&furniture_id={$allItem[i].furniture_id}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
														<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&furniture_id={$allItem[i].furniture_id}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
													{/if}
												</ul>
											</div>
										</td>
									</tr>
									{/section}
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