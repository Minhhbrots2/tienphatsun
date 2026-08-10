{assign var= toId value = $clsISO->getUniqid()}
<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Dịch vụ / Tiện ích</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="$Core.service.open(this, event)" service_id="0" class="ui-button ui-button--primary ui-title-bar__action mr-2">{$core->get_Lang('Addnew')}</a>
					<a href="javascript:void(0);" onClick="$Core.service.select_file(this, event)" project_meta_id="0" title="{$core->get_Lang('Import')}" 
					   class="ui-button ui-button--transparent js_start_select_file ui-title-bar__action" toId="{$toId}">{$core->makeIcon('upload', $core->get_Lang('Import'))}</a>
				</div>
			</div>
		</div>
	</div>
</div>
<form class="d-none" method="post" enctype="multipart/form-data">
	<input type="file" class="select_file select_file_{$toId}" name="import_file" onChange="$Core.service.importData(this,event)" />
</form>
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
								{assign var = toId value = $clsISO->getUniqid()}
								{*<div class="form-group">
									<select name="project_id" class="form-control" onChange="$Core.service.select_block(this, event)" toId="{$toId}_Block_Id">
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
								</div>*}
								<div class="form-group">
									<select name="cat_id" class="form-control">
										<option value="0">Chọn danh mục</option>
										{$clsProperty->getListOption('_CATEGORYSERVICES', $cat_id)}
									</select>
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
								<div class="form-group pull-right">
									<a href="{$PCMS_URL}/?mod={$mod}{$pUrl}" class="btn text-white btn-warning">
										<i class="icon-folder-open icon-white"></i> 
										<span>{$core->get_Lang('all')} ({$total_record})</span>
									</a>
									<a href="javascript:void(0)" clsTable="Service" class="btn btn-danger text-white btn-delete-all" style="display:none"> 
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
										<th class="text-left">Họ và tên</th>
										{*<th class="text-left" width="15%">Dự án</th>
										<th class="text-left" width="15%">Phân khu</th>
										<th class="text-left" width="15%">Toà</th>*}
										<th class="text-left" width="15%">Danh mục</th>
										<th class="text-left" width="15%">Tags</th>
										<th class="text-left" width="15%">Điện thoại</th>
										<th class="text-left" width="15%">Địa chỉ</th>
										<th class="text-left" width="6%">Trạng thái</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{cycle values="row1,row2"}">
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].service_id}" />
												<label></label>
											</div>
										</td>
										<td class="text-left"><a href="javascript:void(0);" onClick="$Core.service.open(this, event)" service_id="{$allItem[i].$pkeyTable}">
											<strong class="fs-16">{$allItem[i].name}</strong></a>
                                        </td>
										{*<td class="text-left">{$allItem[i].project_name}</td>
										<td class="text-left">{$allItem[i].block_name}</td>
										<td class="text-left">{$allItem[i].building_name}</td>*}
										<td class="text-left">{$allItem[i].cat_name}</td>
										<td class="text-left">{$allItem[i].tags}</td>
										<td class="text-left">{$allItem[i].phone}</td>
										<td class="text-left">{$allItem[i].address}</td>
										<td class="text-center bg-gray">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Service" pkey="{$pkeyTable}" 
											   sourse_id="{$allItem[i].$pkeyTable}" rel="{$allItem[i].is_online}" toField="is_online">
												{if $allItem[i].is_online eq '1'}
												<i class="fa fa-check-circle green"></i>
												{else}
												<i class="fa fa-minus-circle red"></i>
												{/if}
											</a>
										</td>
										<td class="text-center" style="white-space: nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> 
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													<li><a href="javascript:void(0);" onClick="$Core.service.open(this, event)" service_id="{$allItem[i].$pkeyTable}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span>
													</a></li>
													<li><a class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&service_id={$core->encryptID($allItem[i].service_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
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