<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Cửa hàng/ Dịch vụ / Tiện ích</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="$Core.shop.open(this, event)" shop_id="0" class="ui-button ui-button--primary ui-title-bar__action {if $clsISO->_DEV()}dev{/if}">{$core->get_Lang('Addnew')}</a>
					<a href="?mod=setting&act=property&group=general#_SHOP" target="_blank" class="ui-button ui-button--default ui-title-bar__action {if $clsISO->_DEV()}dev{/if}">{$core->get_Lang('Category')}</a>
					<a href="?mod=setting&act=setting#_LIST_FORM_BUSINESS" target="_blank" class="ui-button ui-button--default ui-title-bar__action {if $clsISO->_DEV()}dev{/if}">Loại hình</a>
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
								{assign var = toId value = $clsISO->getUniqid()}
								<div class="form-group" style="width: 200px">
									<select name="project_id" class="form-control w-100" onChange="$Core.shop.select_block(this, event)" toId="{$toId}_Block_Id">
										<option value="0">Chọn dự án</option>
										{if !empty($list_projects)}
											{foreach name=i from=$list_projects item = _project}
											<option{if $project_id eq $_project.project_id} selected{/if} value="{$_project.project_id}">{$_project.title}</option>
											{/foreach}
										{/if}
									</select>
								</div>
								<div class="form-group">
									<select name="block_id" class="form-control" toId="{$toId}_Block_Id" id="{$toId}_Block_Id">
										<option value="0">Chọn phân khu</option>
										{if !empty($list_blocks)}
											{foreach from=$list_blocks item = _oblock}
											<option{if $block_id eq $_oblock.property_id} selected{/if} value="{$_oblock.property_id}">{$_oblock.title}</option>
											{/foreach}
										{/if}
									</select>
								</div>
								<div class="form-group{if empty($list_buildings)} d-none{/if}">
									<select name="building_id" class="form-control" id="{$toId}_Building_Id">
										<option value="0">Chọn phân khu</option>
										{if !empty($list_buildings)}
											{foreach from=$list_buildings item = _obuilding}
											<option{if $building_id eq $_obuilding.property_id} selected{/if} value="{$_obuilding.property_id}">{$_obuilding.title}</option>
											{/foreach}
										{/if}
									</select>
								</div>
								<div class="form-group" style="width: 200px">
									<select name="cat_id" class="form-control w-100">
										<option value="0">Chọn danh mục</option>
										{$clsProperty->getListOption('_SHOP', $cat_id)}
									</select>
								</div>
								<input type="hidden" name="filter" value="filter" />
								<div class="form-group">
									<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
								</div>
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
							<div class="hastable">
								<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">
									<thead><tr>
										<th width="5%" class="text-center">
											<div class="checkbox">
												<input type="checkbox" id="check_all" class="check_all styled" value="1" />
												<label></label>
											</div>
										</th>
										<th class="text-left">{$core->get_Lang('titleofarticle')}</th>
										<th class="text-left" width="15%">Phân khu</th>
										<th class="text-left" width="15%">Tòa</th>
										<th class="text-left" width="15%">Danh mục</th>
										<th class="text-center" width="5%">{$core->get_Lang('status')}</th>
										<th class="text-right" width="12%">{$core->get_Lang('update')}</th>
										<th class="text-center" colspan="4" width="4%">{$core->get_Lang('move')}</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{cycle values="row1,row2"}">
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].page_id}" />
												<label></label>
											</div>
										</td>
										<td class="text-left"><a href="javascript:void(0);" onClick="$Core.shop.open(this, event)" shop_id="{$allItem[i].$pkeyTable}">
											<strong class="fs-16">{$allItem[i].title}</strong></a>
											{if $allItem[i].is_trash eq '1'}<span class="fr text-red">{$core->get_Lang('intrash')}</span>{/if}
                                        </td>
										<td class="text-left">{$allItem[i].block_name}</td>
										<td class="text-left">{$allItem[i].building_name}</td>
										<td class="text-left">{$allItem[i].cat_name}</td>
										<td class="text-center bg-gray">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="News" pkey="{$pkeyTable}" sourse_id="{$allItem[i].$pkeyTable}" rel="{$clsClassTable->getOneField('is_online',$allItem[i].$pkeyTable)}" title="{$core->get_Lang('Click to change status')}">
												{if $clsClassTable->getOneField('is_online',$allItem[i].$pkeyTable) eq '1'}
												<i class="fa fa-check-circle green"></i>
												{else}
												<i class="fa fa-minus-circle red"></i>
												{/if}
											</a>
										</td>
										<td style="text-align:right">{$allItem[i].reg_date|date_format:"%d/%m/%Y %H:%M"}</td>
										<td class="text-center">
											{if !$smarty.section.i.first}
											<a title="{$core->get_Lang('movetop')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movetop&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-circle-arrow-up"></i></a>
											{/if}
										</td>
										<td class="text-center">
											{if !$smarty.section.i.last}
											<a title="{$core->get_Lang('movebottom')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movebottom&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-circle-arrow-down"></i></a>
											{/if}
										</td>
										<td class="text-center">
											{if !$smarty.section.i.first}
											<a title="{$core->get_Lang('moveup')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=moveup&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-arrow-up"></i></a>
											{/if}
										</td>
										<td class="text-center">
											{if !$smarty.section.i.last}
											<a title="{$core->get_Lang('movedown')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movedown&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-arrow-down"></i></a>
											{/if}
										</td>
										<td class="text-center" style="white-space: nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> 
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													{if $allItem[i].is_trash eq '0'}
													<li><a href="javascript:void(0);" onClick="$Core.shop.open(this, event)" shop_id="{$allItem[i].$pkeyTable}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span>
													</a></li>
													<li><a href="{$PCMS_URL}/?mod={$mod}&act=trash&shop_id={$core->encryptID($allItem[i].shop_id)}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
													{else}
													<li><a href="{$PCMS_URL}/?mod={$mod}&act=restore&shop_id={$core->encryptID($allItem[i].shop_id)}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
													<li><a class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&shop_id={$core->encryptID($allItem[i].shop_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
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
<link rel="stylesheet" href="{$URL_CSS}/shop.css?v={$upd_version}">